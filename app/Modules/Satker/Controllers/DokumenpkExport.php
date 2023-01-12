<?php

namespace Modules\Satker\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Libraries\FPDF_PROTEC as FPDF;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class DokumenpkExport extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        error_reporting(0);
        helper('dbdinamic');
        $session             = session();
        $this->user          = $session->get('userData');
        $this->userUID       = $this->user['uid'];
        $this->userType      = $this->user['user_type'];
        $this->dokumenYear   = $this->user['tahun'];
        $this->dokumenLokasi = 'JAKARTA';
        $this->dokumenBulan  = '';
        $this->bulan         = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $this->db            = \Config\Database::connect();

        $this->dokumenSatker          = $this->db->table('dokumenpk_satker');
        $this->dokumenSatker_rows     = $this->db->table('dokumenpk_satker_rows');
        $this->dokumenSatker_kegiatan = $this->db->table('dokumenpk_satker_kegiatan');

        $this->templateDokumen = $this->db->table('dokumen_pk_template');
        $this->templateRow     = $this->db->table('dokumen_pk_template_row');
        $this->templateInfo    = $this->db->table('dokumen_pk_template_info');

        $this->dokumenStatus = [
            'hold'     => ['message' => 'Menunggu Konfirmasi', 'color' => 'bg-secondary'],
            'setuju'   => ['message' => 'Telah di Setujui', 'color' => 'bg-success text-white'],
            'tolak'    => ['message' => 'Di Tolak', 'color' => 'bg-danger text-white'],
            'revision' => ['message' => 'Telah Di Koreksi', 'color' => 'bg-dark text-white']
        ];
        $this->request = \Config\services::request();

        $this->dokumenLoadedStatus = '';

        $this->fontFamily = 'Arial';
        $this->sectionWidth = 265;
    }



    public function pdf($_dokumenSatkerID)
    {
        //qrcode
        $writer = new PngWriter();

        $qrcodeSite = base_url() . "/api/showpdf/tampilkan/" . $_dokumenSatkerID . "?preview=true";

        // Create QR code
        $qrCode = QrCode::create($qrcodeSite)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        // Create generic logo
        $logo = Logo::create(FCPATH . 'logo.png')
            ->setResizeToWidth(50);

        // // Create generic label
        // $label = Label::create($qrcodeSite)
        //     ->setTextColor(new Color(255, 0, 0));

        $result = $writer->write($qrCode, $logo);

        $qrcode = $result->getDataUri();
        // echo '<img src="'.$dataUri.'" alt="PUPR">';exit;



        //generate pdf
        $pdf = new PDF();

        $dataDokumen = $this->dokumenSatker->select('
            dokumenpk_satker.*,
            tkabkota.*,
            dokumen_pk_template.keterangan,
            dokumen_pk_template.info_title
        ')
            ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
            ->join('tkabkota', "(SUBSTRING_INDEX(dokumenpk_satker.kota, '-', 1) = tkabkota.kdlokasi AND SUBSTRING_INDEX(dokumenpk_satker.kota, '-', -1) = tkabkota.kdkabkota)", 'left')
            ->where('dokumenpk_satker.id', $_dokumenSatkerID)
            ->get()
            ->getRowArray();

        if ($dataDokumen) {
            $this->dokumenBulan = bulan(date('m', strtotime($dataDokumen['created_at'])));

            if ($dataDokumen['tahun'] != '') {
                $this->dokumenYear = $dataDokumen['tahun'];
            } else {
                $this->dokumenYear = date('Y', strtotime($dataDokumen['created_at']));
            }

            if ($dataDokumen['kota_nama'] != '') {
                $this->dokumenLokasi = $dataDokumen['kota_nama'];
            } else {
                if ($dataDokumen['kota'] != '') $this->dokumenLokasi = $dataDokumen['nmkabkota'];
            }

            if ($dataDokumen['bulan'] != '') $this->dokumenBulan = $this->bulan[$dataDokumen['bulan'] - 1];
        }
        $watermaskRevisi       = $dataDokumen['is_revision_same_year'] == '1' ? 'revision-same-year' : $dataDokumen['status'];
        $watermarkRevisiNumber = $dataDokumen['is_revision_same_year'] == '1' ? $dataDokumen['revision_same_year_number'] : $dataDokumen['revision_number'];
        $this->pdf_renderWatermarkKonsep($pdf, $watermaskRevisi, $watermarkRevisiNumber);

        $this->dokumenLoadedStatus = $dataDokumen['status'];

        /** Dokumen Opening */
        $this->pdf_pageDokumenOpening($pdf, $dataDokumen);

        /** Dokumen Detail */
        $this->pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, 'target', '');

        /** Dokumen Detail 2 */
        // dd($dataDokumen['dokumen_type']);
        if ($dataDokumen['dokumen_type'] == 'balai') {

            $this->pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, 'output', $qrcode);
        } else {

            $this->pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, 'outcome', $qrcode);
        }

        $pdf->SetProtection(array('print'));



        if ($_GET['preview']) {
            $nm_file = "PK " . str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak1_initial'])) . " - " . str_replace(array('DIREKTUR', 'DIREKTORAT'), array("MENTERI", "KEMENTERIAN"), str_replace('KEPALA', '', $dataDokumen['pihak2_initial']));
            $pdf->Output('I', $nm_file . '.pdf');
            exit;
        } else {

            $pdf->Output('F', 'dokumen-perjanjian-kinerja.pdf');
            return $this->respond([
                'dokumen' => $dataDokumen
            ]);
        };
    }










    private function pdf_pageDokumenOpening($pdf, $dataDokumen)
    {
        $pdf->AddPage('L', 'A4');

        $pdf->SetFont('Arial', 'B', 50);
        $pdf->SetTextColor(255, 192, 203);

        // $pdf->SetLineWidth(4);
        $pdf->SetLineWidth(12);

        // $pdf->SetDrawColor(3, 106, 138);
        // $pdf->Rect(8, 8, 282, 192, 'D');
        $pdf->SetDrawColor(0, 28, 153);
        $pdf->Rect(5, 1, 287, 208, 'D');



        /**  Dokumen KOP */
        // $pdf->Image('images/logo_pu_border.png', 143, 14, 12);
        $pdf->Image('images/logo_pu_border.png', 143, 9, 20, 20);

        $pdf->Ln(20.5);

        // $dokumenKopTitle2_prefix = ($dataDokumen['dokumen_type'] == "satker" && strpos($dataDokumen['pihak1_initial'], 'OPERASI DAN PEMELIHARAAN')) ? 'SATUAN KERJA' : '';

        // $dokumenKopTitle2 = str_replace('KEPALA', '', $dokumenKopTitle2_prefix . $dataDokumen['pihak1_initial']);
        $dokumenKopTitle2 = str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak1_initial']));

        $dokumenKopTitle3 = str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak2_initial']));
        $dokumenKopTitle3 = str_replace('MENTERI', 'KEMENTERIAN', $dokumenKopTitle3);



        $dokumenKopTitle1 = 'PERJANJIAN KINERJA TAHUN ' . $this->dokumenYear;
        $dokumenKopTitle2 = $dokumenKopTitle2;
        $dokumenKopTitle3 = $dokumenKopTitle3;

        $pdf->SetFont('Times', 'B', 17);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Kop Title 1
        $width_kopTitle1 = $pdf->GetStringWidth($dokumenKopTitle1) + 6;
        $pdf->SetX((300 - $width_kopTitle1) / 2);
        $pdf->Cell($width_kopTitle1, 6, $dokumenKopTitle1, 0, 1, 'C');

        // Kop Title 2
        $pdf->SetFont('Times', 'B', 13);
        $width_kopTitle2 = $pdf->GetStringWidth($dokumenKopTitle2) + 6;
        // $pdf->SetX((300 - $width_kopTitle2) / 2);
        // $pdf->Cell($width_kopTitle2, 6, $dokumenKopTitle2, 0, 1, 'C');
        $pdf->MultiCell(0, 4, $dokumenKopTitle2, 0, 'C');

        // Kop Title 3
        $kopTitle3 = $dokumenKopTitle3;
        $pdf->SetFont('Times', 'B', 13);
        $width_kopTitle3 = $pdf->GetStringWidth($dokumenKopTitle3) + 6;
        $pdf->SetX((300 - $width_kopTitle3) / 2);
        $pdf->Cell($width_kopTitle3, 6, $kopTitle3, 0, 1, 'C');

        // Line break
        $pdf->Ln(6);


        /**  Dokumen Pengenalan Pihak */
        $pdf->SetFont('Arial', '', 11);
        // Text
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 4, "Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan, dan akuntabel serta berorientasi pada hasil, kami yang bertandatangan di bawah ini:", 0, 'J');
        $pdf->Ln(2);

        // Pihak Pertama
        $jabatanPihak1_isPlt = $dataDokumen['pihak1_is_plt'] ? 'Plt. ' : '';
        $this->pdf_renderIntroductionSection($pdf, 'Nama', $dataDokumen['pihak1_ttd']);
        $this->pdf_renderIntroductionSection($pdf, 'Jabatan', $jabatanPihak1_isPlt . $dataDokumen['pihak1_initial']);

        // Text 2
        $pdf->Ln(4);
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        // $pdf->MultiCell($this->sectionWidth, 5, "Selanjutnya disebut PIHAK PERTAMA", 0, 'J');
        $pdf->MultiCell($this->sectionWidth, 4, $pdf->WriteHTML("Selanjutnya disebut <b>PIHAK PERTAMA</b>"), 0, 'J');
        $pdf->Ln(2);

        // Pihak Kedua
        $jabatanPihak2_isPlt = $dataDokumen['pihak2_is_plt'] ? 'Plt. ' : '';
        $this->pdf_renderIntroductionSection($pdf, 'Nama', $dataDokumen['pihak2_ttd']);
        $this->pdf_renderIntroductionSection($pdf, 'Jabatan', $jabatanPihak2_isPlt . $dataDokumen['pihak2_initial']);

        // Text 3
        $pdf->Ln(4);
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, $pdf->WriteHTML("Selaku atasan langsung <b>PIHAK PERTAMA</b>. selanjutnya disebut <b>PIHAK KEDUA</b>"), 0, 'J');
        $pdf->Ln(3);


        /** Isi */
        // title
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, $pdf->WriteHTML("<b>PIHAK PERTAMA</b> dan <b>PIHAK KEDUA</b> sepakat untuk membuat Perjanjian Kinerja dengan ketentuan sebagai berikut :"), 0, 'J');


        // Text
        $pdf->Ln(2);
        $this->pdf_renderListIsiSection($pdf, '1.', "<b>Pihak pertama</b> pada tahun " . $this->dokumenYear . " ini berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah di tetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab <b>pihak pertama</b>.");
        $pdf->Ln(2);
        $this->pdf_renderListIsiSection($pdf, '2.', "<b>Pihak kedua</b> akan melakukan supervisi yang di perlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi.");


        /** TTD Section */
        $pdf->Ln(5);
        $this->pdf_renderSectionTtd($pdf, $this->sectionWidth, [
            'person1Title' => 'Pihak Kedua',
            'person1Name'  => $dataDokumen['pihak2_ttd'],
            'person2Date'  => $this->dokumenLokasi . ',          ' . $this->dokumenBulan . ' ' . $this->dokumenYear,
            'person2Title' => 'Pihak Pertama',
            'person2Name'  => $dataDokumen['pihak1_ttd'],
        ]);
    }



    private function pdf_renderIntroductionSection($pdf, $_title, $_introduction)
    {
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->SetX((330 - $this->sectionWidth) / 2);
        $pdf->Cell(35, 5, $_title, 0);

        $pdf->SetFont('Arial', '', 8.5);
        $pdf->SetX((360 - $this->sectionWidth) / 2);
        $pdf->Cell(5, 5, ':', 0);

        $pdf->SetFont('Arial', '', 8.5);
        $pdf->SetX((370 - $this->sectionWidth) / 2);
        $pdf->Cell(150, 5, $_introduction, 0);
        // $pdf->MultiCell(0, 4, $_introduction, 0, 'L');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(7);
    }



    private function pdf_renderListIsiSection($pdf, $_listNo, $_listContent)
    {
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->Cell(7, 5, $_listNo, 0);
        $pdf->SetLeftMargin(25);
        $pdf->MultiCell($this->sectionWidth - 7, 5, $pdf->WriteHTML($_listContent), 0);
    }








    private function pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, $_detailDokumenType, $qrcode)
    {
        $pdf->SetMargins(0, 13, 0, 0);
        $pdf->AddPage('L', 'A4');
        $pdf->SetAutoPageBreak(false);
        // $headerTarget = $_detailDokumenType == 'target' ? 'TARGET ' : 'OUTCOME ';
        $headerTarget = strtoupper($_detailDokumenType);

        $header      = ['SASARAN PROGRAM / SASARAN KEGIATAN / INDIKATOR', $headerTarget . " " . $this->dokumenYear];
        $headerWidth = [
            200,
            65
        ];

        $dataDokumenKegiatan = $this->dokumenSatker_kegiatan->where('dokumen_id', $_dokumenSatkerID)->get()->getResultArray();
        $dataDokumenInfo     = $this->templateInfo->where('template_id', $dataDokumen['template_id'])->get()->getResultArray();

        // $tableData = $this->templateRow
        //     ->where('template_id', $dataDokumen['template_id'])
        //     ->get()
        //     ->getResultArray();

        $tableData = $this->gettemplateRowChecked($dataDokumen['template_id'], $_dokumenSatkerID);

        $tableDataWidth = [
            20,
            180,
            65
        ];

        // print_r($qrcode);exit;
        if ($qrcode) {

            $pdf->Image($qrcode, 282, 195, 15, 15, "PNG");
        }

        /**  Dokumen KOP */
        // $pdf->Ln();
        $dokumenKopTitle1 = 'PERJANJIAN KINERJA TAHUN ' . $this->dokumenYear;

        $divisiPihak2 = str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak2_initial']));
        $divisiPihak2 = str_replace('MENTERI', 'KEMENTERIAN', $divisiPihak2);
        $dokumenKopTitle2 = str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak1_initial'])) . ' - ' . $divisiPihak2;

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Kop Title 1
        $width_kopTitle1 = $pdf->GetStringWidth($dokumenKopTitle1) + 6;
        $pdf->SetX((300 - $width_kopTitle1) / 2);
        $pdf->Cell($width_kopTitle1, 6, $dokumenKopTitle1, 0, 1, 'C');

        // Kop Title 2
        $pdf->SetFont('Arial', 'B', 9);
        $width_kopTitle2 = $pdf->GetStringWidth($dokumenKopTitle2) + 6;
        // $pdf->SetX((300 - $width_kopTitle2) / 2);
        // $pdf->Cell($width_kopTitle2, 6, $dokumenKopTitle2, 0, 1, 'C');
        $pdf->MultiCell(0, 4, $dokumenKopTitle2, 0, 'C');
        $pdf->SetFont('Arial', 'B', 10);

        // Line break
        $pdf->Ln(1);


        $pdf->SetLineWidth(0.01);
        $pdf->SetDrawColor(0, 0, 0);


        /** Table */
        // Header 1
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        $pdf->SetX((300 - array_sum($headerWidth)) / 2);
        foreach ($header as $key_header => $data_header)
            $pdf->Cell($headerWidth[$key_header], 8, $data_header, 1, 0, 'C');
        $pdf->Ln();

        // Header 2 (Header Number)
        $pdf->SetFont($this->fontFamily, 'B', 8);
        $pdf->SetX((300 - array_sum($headerWidth)) / 2);
        foreach ($header as $key_header => $data_header)
            $pdf->Cell($headerWidth[$key_header], 4, '(' . (string)($key_header + 1) . ')', 1, 0, 'C');
        $pdf->Ln();

        // Data
        $pdf->SetFont($this->fontFamily, '', 8);
        $rowNUmber = 0;
        foreach ($tableData as $key => $data) {
            // $celTableDataFill = $this->dokumenLoadedStatus == 'setuju' ? true : false;
            $celTableDataFill = true;
            $data_targetValue = [];

            if ($data['type'] == 'form') {
                $data_targetValue = $this->dokumenSatker_rows->where('dokumen_id', $_dokumenSatkerID)
                    ->where('template_row_id', $data['id'])
                    ->get()
                    ->getRowArray();

                $pdf->SetFillColor(255);
                $width_cellTitle = $tableDataWidth[1];

                if ($data_targetValue['is_checked'] == '1') $rowNUmber++;
            } else {
                $data_targetValue['is_checked'] = '1';

                $pdf->SetFillColor(233);
                $width_cellTitle = 245;

                if ($data['prefix_title'] == 'full') {
                    $rowNUmber = '';
                    // $data['title'] = '';
                } else {
                    $rowNUmber = $data['prefix_title'] ?? '-';
                }
            }


            $pdf->SetX((300 - array_sum($tableDataWidth)) / 2);
            if ($data_targetValue['is_checked'] == '1') {
                $pdf->Cell($tableDataWidth[0], 6, $rowNUmber, 'T,B,L', 0, 'C', $celTableDataFill);
                $pdf->Cell($width_cellTitle, 6, $data['title'], 'T,R,B', 0, 'L', $celTableDataFill);
            }

            if ($data['type'] == 'form') {
                $targetValue = '';
                switch ($_detailDokumenType) {
                    case 'target':
                        // $targetValue = rupiahFormat($data_targetValue['target_value'], false, 3) . ' ' . $data['target_satuan'];
                        $targetValue = rtrim(rtrim(number_format($data_targetValue['target_value'], 3, ',', '.'), '0'), ','). ' ' . $data['target_satuan'];

                        break;

                    case 'outcome':
                        // $targetValue = rupiahFormat($data_targetValue['outcome_value'], false, 3) . ' ' . $data['outcome_satuan'];
                        $targetValue = rtrim(rtrim(number_format($data_targetValue['outcome_value'], 3, ',', '.'), '0'), ',') . ' ' . $data['outcome_satuan'];

                        break;

                    case 'output':
                        // $targetValue = rupiahFormat($data_targetValue['target_value'], false, 3) . ' ' . $data['target_satuan'];
                        $targetValue = rtrim(rtrim(number_format($data_targetValue['target_value'], 3, ',', '.'), '0'), ',') . ' ' . $data['target_satuan'];


                        break;
                    default:
                        $targetValue = '';
                        break;
                }

                if ($data_targetValue['is_checked'] == '1')  $pdf->Cell($tableDataWidth[2], 6, $targetValue, 1, 0, 'C', $celTableDataFill);
            } else {
                $rowNUmber = 0;
            }

            if ($data_targetValue['is_checked'] == '1') $pdf->Ln();
        }
        $pdf->Ln(1);


        /** Keterangan Section */
        // if ($dataDokumen['keterangan'] != '') {
        //     // keterangan title
        //     $pdf->SetFont($this->fontFamily, 'B', 9);
        //     $pdf->SetX((297 - array_sum($tableDataWidth)) / 2);
        //     $pdf->Cell(100, 7, 'KETERANGAN', 0, 0, 'L');
        //     $pdf->Ln(5);

        //     // keterangan
        //     $pdf->SetFont($this->fontFamily, 'B', 8);
        //     $pdf->SetX((297 - array_sum($tableDataWidth)) / 2);
        //     $pdf->Cell(100, 7, $dataDokumen['keterangan'], 0, 0, 'L');
        //     $pdf->Ln(8);
        // }


        /** Info & Anggaran Section */
        // Info title
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((297 - array_sum($tableDataWidth)) / 2);
        $pdf->Cell(100, 7, $dataDokumen['info_title'], 0, 0, 'L');

        // anggaran title
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(183);
        $pdf->Cell(85, 7, 'Anggaran', 0, 0, 'R');
        $pdf->Ln(5);



        // kegiatan
        foreach ($dataDokumenKegiatan as $key_kegiatan => $data_kegiatan) {
            $pdf->SetFont($this->fontFamily, '', 8);
            $pdf->SetX((310 - array_sum($tableDataWidth)) / 2);
            $pdf->Cell(100, 7, ++$key_kegiatan . ". " . ltrim($data_kegiatan['nama']), 0, 0, 'L');

            // anggaran perkegiatan value
            $pdf->SetFont($this->fontFamily, 'B', 8);
            $pdf->SetX(170);
            $pdf->Cell(80, 7, "Rp", 0, 0, 'R');

            // anggaran value
            $pdf->SetFont($this->fontFamily, '', 8);
            $pdf->SetX(183);
            $pdf->Cell(100, 7, rupiahFormat($data_kegiatan['anggaran'], false, 2), 0, 0, 'R');

            $pdf->Ln(5);
        }
        // $pdf->Ln(2);

        // total anggaran title
        // $pdf->SetFont($this->fontFamily, 'B', 8);
        // $pdf->SetX(150);
        // $pdf->Cell(100, 7, "JUMLAH", 0, 0, 'L');


        //RP title
        $pdf->SetFont($this->fontFamily, 'B', 8);
        $pdf->SetX(170);
        // $pdf->Cell(80, 7, "JUMLAH : \t \t \t \t \t \t \t Rp", 0, 0, 'R');
        $pdf->Cell(80, 7, "Rp", 0, 0, 'R');



        // total anggaran value
        $pdf->SetFont($this->fontFamily, 'B', 8);
        $pdf->SetX(183);
        $pdf->Cell(100, 7, rupiahFormat($dataDokumen['total_anggaran'], false, 2), 0, 0, 'R');

        // info
        // foreach ($dataDokumenInfo as $key_info => $data_info) {
        //     $pdf->SetFont($this->fontFamily, '', 8);
        //     $pdf->SetX((310 - array_sum($tableDataWidth)) / 2);
        //     $pdf->Cell(100, 7, $data_info['info'], 0, 0, 'L');
        //     $pdf->Ln(4);
        // }


        /** TTD Section */
        $pdf->Ln(10);
        $jabatanPihak1_isPlt = $dataDokumen['pihak1_is_plt'] ? 'Plt. ' : '';
        $jabatanPihak2_isPlt = $dataDokumen['pihak2_is_plt'] ? 'Plt. ' : '';
        // $dokumenKopTitle1_prefix = ($dataDokumen['dokumen_type'] == "satker" && strpos($dataDokumen['pihak1_initial'], 'OPERASI DAN PEMELIHARAAN')) ? 'SATUAN KERJA' : '';
        $this->pdf_renderSectionTtd($pdf, array_sum($tableDataWidth), [
            'person1Title' => $jabatanPihak2_isPlt . $dataDokumen['pihak2_initial'],
            'person1Name'  => $dataDokumen['pihak2_ttd'],
            'person2Date'  => $this->dokumenLokasi . ',          ' . $this->dokumenBulan . ' ' . $this->dokumenYear,
            // 'person2Title' => $jabatanPihak1_isPlt . $dokumenKopTitle1_prefix . $dataDokumen['pihak1_initial'],
            'person2Title' => $jabatanPihak1_isPlt . $dataDokumen['pihak1_initial'],
            'person2Name'  => $dataDokumen['pihak1_ttd'],
        ]);
    }





    private function pdf_renderSectionTtd($pdf, $_sectionWidth, $_ttd = [
        'person1Title' => '-',
        'person1Name'  => '-',
        'person2Date'  => '-',
        'person2Title' => '-',
        'person2Name'  => '-',
    ])
    {
        // title ttd 1
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((300 - $_sectionWidth) / 2);
        $pdf->Cell(125, 13.7, $_ttd['person1Title'], 0, 0, 'C');

        // title ttd 2
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(149);
        $pdf->Cell(144, 4, $_ttd['person2Date'], 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(167);
        $pdf->MultiCell(110, 5, $_ttd['person2Title'], 0, 'C');
        $pdf->Ln(20);

        // td 1
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((300 - $_sectionWidth) / 2);
        $pdf->Cell(125, 4, strtoupper($_ttd['person1Name']), 0, 0, 'C');

        // td 2
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(149);
        $pdf->Cell(144, 4, strtoupper($_ttd['person2Name']), 0, 0, 'C');
    }



    private function pdf_renderWatermarkKonsep($pdf, $_dokumenStatus, $_revisionNumber)
    {
        if ($_dokumenStatus != "setuju") {
            switch ($_dokumenStatus) {
                case 'revision':
                    $subtitle = '';
                    if (!is_null($_revisionNumber)) {
                        // $subtitle = $_revisionNumber > 1 ? ' Ke - ' . $_revisionNumber : '';
                        $pdf->watermarkSubTextOffsetLeft = 133;
                    } else {
                        // $subtitle = ' - Dokumen Awal';
                    }

                    $pdf->watermarkText = 'KOREKSI' . $subtitle;
                    if ($subtitle == '') {
                        //lama
                        // $pdf->watermarkOffsetLeft        = 257;
                        // $pdf->watermarkBorder_width      = 30;
                        // $pdf->watermarkBorder_offsetLeft = 254;

                        //koreksi
                        $pdf->watermarkOffsetLeft        = 243.5;
                        $pdf->watermarkBorder_width      = 24;
                        $pdf->watermarkBorder_offsetLeft = 240;
                    } else {
                        $pdf->watermarkOffsetLeft        = 243;
                        $pdf->watermarkBorder_width      = 46;
                        $pdf->watermarkBorder_offsetLeft = 240;
                    }
                    break;

                case 'revision-same-year':
                    $subtitle = '';
                    if (!is_null($_revisionNumber)) {
                        // $subtitle = $_revisionNumber > 1 ? ' Ke - ' . $_revisionNumber : '';
                        $pdf->watermarkSubTextOffsetLeft = 133;
                    } else {
                        // $subtitle = ' - Dokumen Awal';
                    }

                    $pdf->watermarkText = 'REVISI' . $subtitle;
                    if ($subtitle == '') {
                        //lama
                        // $pdf->watermarkOffsetLeft        = 260;
                        // $pdf->watermarkBorder_width      = 28;
                        // $pdf->watermarkBorder_offsetLeft = 255;

                        //revisi
                        $pdf->watermarkOffsetLeft        = 246;
                        $pdf->watermarkBorder_width      = 24;
                        $pdf->watermarkBorder_offsetLeft = 240;
                    } else {
                        $pdf->watermarkOffsetLeft        = 250;
                        $pdf->watermarkBorder_width      = 39;
                        $pdf->watermarkBorder_offsetLeft = 247;
                    }
                    break;

                default:
                    $pdf->watermarkText = 'KONSEP';
                    // $pdf->watermarkOffsetLeft = 70;
                    // $pdf->watermarkOffsetLeft        = 259;
                    // $pdf->watermarkBorder_width      = 28;
                    // $pdf->watermarkBorder_offsetLeft = 256;

                    //konsep 
                    $pdf->watermarkOffsetLeft        = 244;
                    $pdf->watermarkBorder_width      = 24;
    
                    $pdf->watermarkBorder_offsetLeft = 240;



                    break;
            }

            // $pdf->Image('images/watermark_dokumen_konsep.png', 23, 80, 250);
        }
    }



    private function gettemplateRowChecked($templateId, $dokumenSatkerId)
    {
        $tempRow = [];

        $tableData = $this->templateRow
            ->where('template_id', $templateId)
            ->get()
            ->getResultArray();

        foreach ($tableData as $key => $data) {
            if ($data['type'] == 'form') {
                $data_targetValue = $this->dokumenSatker_rows->where('dokumen_id', $dokumenSatkerId)
                    ->where('template_row_id', $data['id'])
                    ->get()
                    ->getRowArray();

                if ($data_targetValue['is_checked'] == '1') {
                    array_push($tempRow, $data);
                }
            } else {
                array_push($tempRow, $data);

                if ($tempRow[array_key_last($tempRow) - 1]['type'] == 'section_title' && $key > 0) {
                    unset($tempRow[array_key_last($tempRow) - 1]);
                }
            }

            if ($key == count($tableData) - 1) {
                if ($tempRow[array_key_last($tempRow)]['type'] == 'section_title') {
                    unset($tempRow[array_key_last($tempRow)]);
                }
            }
        }

        return $tempRow;
    }
}








class PDF extends FPDF
{
    protected $B                   = 0;
    protected $I                   = 0;
    protected $U                   = 0;
    protected $HREF                = '';

    var $angle               = 0;

    public $watermarkText              = '';
    public $watermarkSubText           = '';
    public $watermarkOffsetLeft        = 70;
    public $watermarkSubTextOffsetLeft = 95;
    public $watermarkBorder_width      = 0;
    public $watermarkBorder_offsetLeft = 0;

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                // Text
                if ($this->HREF)
                    $this->PutLink($this->HREF, $e);
                else
                    $this->Write(5, $e);
            } else {
                // Tag
                if ($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    // Extract attributes
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag, $attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Opening tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if ($tag == 'A')
            $this->HREF = $attr['HREF'];
        if ($tag == 'BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if ($tag == 'A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s) {
            if ($this->$s > 0)
                $style .= $s;
        }
        $this->SetFont('', $style);
    }

    function PutLink($URL, $txt)
    {
        // Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }


    function Header()
    {
        if ($this->PageNo() == 1 || $this->PageNo() == 2 || $this->PageNo() || 3) {
            $this->SetLineWidth(0.2);
            // border merah
            // $this->SetDrawColor(220,20,60);
            // $this->Rect($this->watermarkBorder_offsetLeft, 13, $this->watermarkBorder_width, 10, 'D');

            //bg hitam
            $this->SetDrawColor(0, 0, 0);
            $this->Rect($this->watermarkBorder_offsetLeft, 16, $this->watermarkBorder_width, 5, 'D');

            //Put the watermark
            $this->SetFont('Times', 'B', 11);
            // $this->SetTextColor(255, 192, 203);
            //$this->RotatedText($this->watermarkOffsetLeft, 110, $this->watermarkText, 0);
            // $this->SetTextColor(220,20,60); //text merah
            $this->SetTextColor(0, 0, 0); //text hitam
            $this->RotatedText($this->watermarkOffsetLeft, 20, $this->watermarkText, 0);


            $this->SetFont('Times', 'B', 40);
            $this->RotatedText($this->watermarkSubTextOffsetLeft, 130, $this->watermarkSubText, 0);
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }
}
