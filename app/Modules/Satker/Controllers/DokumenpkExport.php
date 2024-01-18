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
        $this->tanggal = '';
        $this->bulan         = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $this->db            = \Config\Database::connect();

        $this->dokumenSatker          = $this->db->table('dokumenpk_satker');
        $this->dokumenSatker_rows     = $this->db->table('dokumenpk_satker_rows');
        $this->dokumenSatker_kegiatan = $this->db->table('dokumenpk_satker_kegiatan');

        $this->templateDokumen = $this->db->table('dokumen_pk_template_' . session('userData.tahun'));
        $this->templateRow     = $this->db->table('dokumen_pk_template_row_' . session('userData.tahun'));
        $this->templateInfo    = $this->db->table('dokumen_pk_template_info_' . session('userData.tahun'));
        $this->templateRowRumus = $this->db->table('dokumen_pk_template_rowrumus_' . session('userData.tahun'));


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
            dokumen_pk_template_' . session('userData.tahun') . '.keterangan,
            dokumen_pk_template_' . session('userData.tahun') . '.info_title
        ')
            ->join('dokumen_pk_template_' . session('userData.tahun'), 'dokumenpk_satker.template_id = dokumen_pk_template_' . session('userData.tahun') . '.id', 'left')
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
            if ($dataDokumen['tanggal'] != '') $this->tanggal = $dataDokumen['tanggal'];
        }
        // $watermaskRevisi       = $dataDokumen['is_revision_same_year'] == '1' ? 'revision-same-year' : $dataDokumen['status'];
        // $watermarkRevisiNumber = $dataDokumen['is_revision_same_year'] == '1' ? $dataDokumen['revision_same_year_number'] : $dataDokumen['revision_number'];



        // $this->pdf_renderWatermarkKonsep($pdf, $dataDokumen);

        $this->dokumenLoadedStatus = $dataDokumen['status'];

        /** Dokumen Opening */
        $this->pdf_pageDokumenOpening($pdf, $dataDokumen);

        /** Dokumen Detail */
        $this->pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, 'target', '');

        /** Dokumen Detail 2 */
        // dd($dataDokumen['dokumen_type']);
        if ($dataDokumen['dokumen_type'] == 'balai' || $dataDokumen['dokumen_type'] == 'eselon2') {

            $this->pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, 'output', $qrcode);
        } else {

            $this->pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, 'outcome', $qrcode);
        }

        $pdf->SetProtection(array('print'));



        if ($_GET['preview']) {
            $nm_file = date('Ymdhis') . "_PK " . str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak1_initial'])) . " - " . str_replace(array('DIREKTUR', 'DIREKTORAT'), array("MENTERI", "KEMENTERIAN"), str_replace('KEPALA', '', $dataDokumen['pihak2_initial']));
            $pdf->Output('I', $nm_file . '.pdf');
            exit;
        } else {

            // $pdf->Output('F', 'dokumen-perjanjian-kinerja.pdf');
            return $this->respond([
                'dokumen' => $dataDokumen
            ]);
        };
    }










    private function pdf_pageDokumenOpening($pdf, $dataDokumen)
    {
        $this->pdf_renderWatermarkKonsep($pdf, $dataDokumen, 16, 20);

        $pdf->SetMargins(30, 10, 30, 0);
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

        $pdf->SetFont('Times', 'B', 15);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Kop Title 1
        $width_kopTitle1 = $pdf->GetStringWidth($dokumenKopTitle1) + 6;
        $pdf->SetX((300 - $width_kopTitle1) / 2);
        $pdf->Cell($width_kopTitle1, 6, $dokumenKopTitle1, 0, 1, 'C');

        // Kop Title 2
        // jika satker dinas jawatengah
        $fontsize = ($dataDokumen['satkerid'] == '039428' ? '12' : '13');
        $pdf->SetFont('Times', 'B', $fontsize);
        $width_kopTitle2 = $pdf->GetStringWidth($dokumenKopTitle2) + 6;
        $pdf->SetX((300 - $width_kopTitle2) / 2);
        // $pdf->Cell($width_kopTitle2, 6, $dokumenKopTitle2, 0, 1, 'C');
        $pdf->MultiCell($width_kopTitle2, 4, $dokumenKopTitle2, 0, 'C');

        // Kop Title 3
        $kopTitle3 = $dokumenKopTitle3;
        $pdf->SetFont('Times', 'B', 13);
        $width_kopTitle3 = $pdf->GetStringWidth($dokumenKopTitle3) + 6;
        $pdf->SetX((300 - $width_kopTitle3) / 2);
        $pdf->Cell($width_kopTitle3, 6, $kopTitle3, 0, 1, 'C');

        // Line break
        $pdf->Ln(6);


        /**  Dokumen Pengenalan Pihak */
        $pdf->SetFont('Arial', '', 13);
        // Text
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 4, "Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan, dan akuntabel serta berorientasi pada hasil, kami yang bertandatangan di bawah ini:", 0, 'J');
        $pdf->Ln(7);

        // Pihak Pertama
        $jabatanPihak1_isPlt = $dataDokumen['pihak1_is_plt'] ? 'Plt. ' : '';
        $this->pdf_renderIntroductionSection($pdf, 'Nama', $dataDokumen['pihak1_ttd']);
        $this->pdf_renderIntroductionSection($pdf, 'Jabatan', $jabatanPihak1_isPlt . str_replace(["Snvt", "Skpd Tp-op", "Bws", "Bbws", "Ii", "IIi", "Iv", "Vi", "VIi", "VIIi", "(kaltim)", "(kalteng)", "(kalsel)", "D.i"], ["SNVT", "SKPD TP-OP", "BWS", "BBWS", "II", "III", "IV", "VI", "VII", "VIII", "(Kaltim)", "(Kalteng)", "(Kalsel)", "D.I"], ucwords(strtolower($dataDokumen['pihak1_initial']))));

        // Text 2
        $pdf->Ln(2);
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        // $pdf->MultiCell($this->sectionWidth, 5, "Selanjutnya disebut PIHAK PERTAMA", 0, 'J');
        $pdf->MultiCell($this->sectionWidth, 4, $pdf->WriteHTML("Selanjutnya disebut <b>PIHAK PERTAMA</b>"), 0, 'J');
        $pdf->Ln(7);

        // Pihak Kedua
        $jabatanPihak2_isPlt = $dataDokumen['pihak2_is_plt'] ? 'Plt. ' : '';
        $this->pdf_renderIntroductionSection($pdf, 'Nama', ucwords($dataDokumen['pihak2_ttd']));
        $this->pdf_renderIntroductionSection($pdf, 'Jabatan', $jabatanPihak2_isPlt . str_replace(["Snvt", "Skpd Tp-op", "Bws", "Bbws", "Ii", "IIi", "Iv", "Vi", "VIi", "VIIi", "(kaltim)", "(kalteng)", "(kalsel)", "D.i"], ["SNVT", "SKPD TP-OP", "BWS", "BBWS", "II", "III", "IV", "VI", "VII", "VIII", "(Kaltim)", "(Kalteng)", "(Kalsel)", "D.I"], ucwords(strtolower($dataDokumen['pihak2_initial']))));

        // Text 3
        $pdf->Ln(2);
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, $pdf->WriteHTML("Selaku atasan langsung pihak pertama, selanjutnya disebut <b>PIHAK KEDUA</b>"), 0, 'J');
        $pdf->Ln(5);


        /** Isi */
        // title
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, $pdf->WriteHTML("<b>PIHAK PERTAMA</b> dan <b>PIHAK KEDUA</b> sepakat untuk membuat Perjanjian Kinerja dengan ketentuan sebagai berikut :"), 0, 'J');


        // Text
        // $pdf->Ln(1);
        $this->pdf_renderListIsiSection($pdf, '1.', "Pihak pertama pada tahun " . $this->dokumenYear . " ini berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab pihak pertama.");
        $pdf->Ln(1.5);
        $this->pdf_renderListIsiSection($pdf, '2.', "Pihak kedua akan melakukan supervisi yang diperlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi.");


        /** TTD Section */
        $pdf->Ln(2);
        // $this->pdf_renderSectionTtd($pdf, $this->sectionWidth, [
        //     'person1Title' => 'Pihak Kedua',
        //     'person1Name'  => $dataDokumen['pihak2_ttd'],
        //     'person2Date'  => $this->dokumenLokasi . ',          ' . $this->dokumenBulan . ' ' . $this->dokumenYear,
        //     'person2Title' => 'Pihak Pertama',
        //     'person2Name'  => $dataDokumen['pihak1_ttd'],
        // ]);

        // title ttd 1
        $pdf->SetFont($this->fontFamily, 'B', 12);
        $pdf->SetX((300 - $this->sectionWidth) / 2);
        $pdf->Cell(125, 13.7, 'Pihak Kedua', 0, 0, 'C');

        // title ttd 2
        $pdf->SetFont($this->fontFamily, '', 12);
        $pdf->SetX(149);
        $pdf->Cell(144, 4, $this->dokumenLokasi . ',   ' . $this->tanggal . ' ' . $this->dokumenBulan . ' ' . $this->dokumenYear, 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont($this->fontFamily, 'B', 12);
        $pdf->SetX(167);
        $pdf->MultiCell(110, 5, 'Pihak Pertama', 0, 'C');
        $pdf->Ln(19);

        // td 1
        $pdf->SetFont($this->fontFamily, 'B', 12);
        $pdf->SetX((300 - $this->sectionWidth) / 2);
        $pdf->Cell(125, 4, strtoupper($dataDokumen['pihak2_ttd']), 0, 0, 'C');

        // td 2
        $pdf->SetFont($this->fontFamily, 'B', 12);
        $pdf->SetX(149);
        $pdf->Cell(144, 4, strtoupper($dataDokumen['pihak1_ttd']), 0, 0, 'C');
    }



    private function pdf_renderIntroductionSection($pdf, $_title, $_introduction)
    {
        $pdf->SetFont('Arial', '', 13);
        $pdf->SetX((330 - $this->sectionWidth) / 2);
        $pdf->Cell(35, 5, $_title, 0);

        $pdf->SetFont('Arial', '', 13);
        $pdf->SetX((365 - $this->sectionWidth) / 2);
        $pdf->Cell(2, 5, ':', 0);

        $pdf->SetFont('Arial', '', 13);
        $pdf->SetX((370 - $this->sectionWidth) / 2);
        // $pdf->Cell(150, 5, $_introduction, 0);
        $pdf->MultiCell(0, 5.5, $_introduction, 0, 'L');

        $pdf->SetFont('Arial', '', 13);
        // $pdf->Ln(1);
    }



    private function pdf_renderListIsiSection($pdf, $_listNo, $_listContent)
    {
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->Cell(7, 5, $_listNo, 0);
        $pdf->SetMargins(23, 0, 12, 0);
        // $pdf->MultiCell($this->sectionWidth - 7, 5, $pdf->WriteHTML($_listContent), 0);
        $pdf->MultiCell($this->sectionWidth - 7, 5, $pdf->WriteHTML($_listContent), 0, 'J');
    }








    private function pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen, $_detailDokumenType, $qrcode)
    {
        $this->pdf_renderWatermarkKonsep($pdf, $dataDokumen, 6, 10);

        // header('Content-Type: text/html; charset=utf-8');
        $pdf->SetMargins(0, 16, 0, 0);
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
        // Set top margin (adjust the value as needed)
        $topMargin = 6;
        $pdf->SetY($topMargin);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Kop Title 1
        $width_kopTitle1 = $pdf->GetStringWidth($dokumenKopTitle1) + 6;
        $pdf->SetX((300 - $width_kopTitle1) / 2);
        $pdf->Cell($width_kopTitle1, 6, $dokumenKopTitle1, 0, 1, 'C');


        if ($dataDokumen['satkerid'] == 309214) {
            $dokumenKopTitle2 = str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak1_initial'])) . chr(10) . $divisiPihak2;
        } else {
            $dokumenKopTitle2 = str_replace('DIREKTUR', 'DIREKTORAT', str_replace('KEPALA', '', $dataDokumen['pihak1_initial'])) . ' - ' . $divisiPihak2;
        }

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
            $numberText = $rowNUmber;
            if ($data_targetValue['is_checked'] == '1') {
                // $pdf->Cell($tableDataWidth[0], 6, $rowNUmber, 'T,B,L', 0, 'C', $celTableDataFill);
                // $pdf->Cell($width_cellTitle, 6, $data['title'], 'T,R,B', 0, 'L', $celTableDataFill);
            }

            if ($data['type'] == 'form') {
                $targetValue = '';
                $str = iconv('UTF-8', 'windows-1252', html_entity_decode("&sup3;"));
                switch (strtolower($data_targetValue['target_sat'] ??  trim(explode(";", $data['target_satuan'])[0]))) {
                    case 'm3/detik':
                        $satuan_target = 'M' . $str . "/Detik";

                        break;
                    case 'juta m3':
                        $satuan_target = "Juta M" . $str;

                        break;
                    case 'm3/kapita':
                        $satuan_target = 'M' . $str . '/Kapita';
                        break;
                    case 'm3/tahun/hektar':
                        $satuan_target = 'M' . $str . '/Tahun/Hektar';
                        break;
                    case 'miliar m3':
                        $satuan_target = 'Miliar M' . $str;
                        break;
                    default:
                        $satuan_target = $data_targetValue['target_sat'] ?? trim(explode(";", $data['target_satuan'])[0]);
                        break;
                }

                switch (strtolower($data['outcome_satuan'])) {
                    case 'm3/detik':
                        $satuan_outcome = 'M' . $str . "/Detik";
                        break;
                    case 'juta m3':
                        $satuan_outcome = "Juta M" . $str;
                        break;
                    default:
                        $satuan_outcome = $data['outcome_satuan'];
                        break;
                }

                switch ($_detailDokumenType) {
                    case 'target':
                        // $targetValue = rupiahFormat($data_targetValue['target_value'], false, 3) . ' ' . $data['target_satuan'];
                        $rSeparator = explode('.', $data_targetValue['target_value']);
                        $targetValue = number_format($data_targetValue['target_value'], strlen($rSeparator[1]), ',', '.') .  ' ' .  $satuan_target;
                        // $targetValue = str_replace('.',',',$data_targetValue['target_value']) .  ' ' .  $satuan_target;
                        // $targetValue = (number_format($data_targetValue['target_value'],)) .  ' ' .  $satuan_target;

                        break;

                    case 'outcome':
                        // $targetValue = rupiahFormat($data_targetValue['outcome_value'], false, 3) . ' ' . $data['outcome_satuan'];
                        // $targetValue = rtrim(rtrim(number_format($data_targetValue['outcome_value'], 10, ',', '.'), '0'), ',') . ' ' . $satuan_outcome;
                        $rSeparator = explode('.', $data_targetValue['outcome_value']);
                        $targetValue = number_format($data_targetValue['outcome_value'], strlen($rSeparator[1]), ',', '.') .  ' ' .  $satuan_outcome;


                        break;

                        //balai
                    case 'output':
                        // $targetValue = rupiahFormat($data_targetValue['target_value'], false, 3) . ' ' . $data['target_satuan'];
                        // $targetValue = rtrim(rtrim(number_format($data_targetValue['target_value'], 10, ',', '.'), '0'), ',') . ' ' .  $satuan_target;
                        $targetValue = 0 . " %";

                        $sumOutputValue     = 0;
                        $outputSatuan = '';
                        $average = 0;


                        $templateRowRumus = $this->templateRowRumus->select('rumus')->where(['template_id' => $data['template_id'], 'rowId' =>  $data['id']])->get()->getResult();
                        foreach ($templateRowRumus as $key => $dataRumus) {
                            $rumus = $this->dokumenSatker->select(
                                'dokumenpk_satker_rows.outcome_value, dokumenpk_satker_rows.target_value, dokumenpk_satker_rows.template_row_id,
                                dokumenpk_satker.satkerid,dokumenpk_satker.id,dokumenpk_satker_rows.target_sat,target_satuan,target_satuan'
                            )
                                ->join('dokumenpk_satker_rows', 'dokumenpk_satker.id = dokumenpk_satker_rows.dokumen_id', 'left')
                                ->join('dokumen_pk_template_row_' . session('userData.tahun'), "(dokumenpk_satker_rows.template_row_id=dokumen_pk_template_row_" . session('userData.tahun') . ".id)", 'left')
                                ->join('dokumen_pk_template_rowrumus_' . session('userData.tahun'), "(dokumenpk_satker.template_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".template_id AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".rowId)", 'left')
                                ->where('dokumen_pk_template_rowrumus_' . session('userData.tahun') . '.rumus', $dataRumus->rumus)
                                ->where('dokumenpk_satker.balaiid', $dataDokumen['balaiid'])
                                ->where('dokumenpk_satker.status', 'setuju')
                                ->where('dokumenpk_satker.satkerid is not null')
                                ->where('dokumenpk_satker.deleted_at is null')
                                ->where('dokumenpk_satker.tahun', $this->user['tahun'])
                                // ->where('dokumenpk_satker_rows.is_checked', '1')
                                ->get()->getResult();

                            $outcomeRumus = 0;
                            $outputRumus = 0;

                            foreach ($rumus as $keyOutcome => $dataOutput) {
                                $outputRumus += $dataOutput ? ($dataOutput->target_value != '' ? $dataOutput->target_value : 0) : 0;
                                // $outputSatuan = $dataOutput->target_sat ?? trim(explode(";", $dataOutput->target_satuan)[0]);
                                $outputSatuan = $data['outcome_satuan'];
                            }
                            if ($sumOutputValue == '' && $outcomeRumus > 0) $sumOutputValue = 0;


                            if ($outputRumus > 0) {
                                $sumOutputValue  += $outputRumus;
                            }
                            $rSeparator = explode('.',  $sumOutputValue);
                            $targetValue = number_format($data['id'] == "291011" ? ($sumOutputValue / 3) : $sumOutputValue, strlen($rSeparator[1]), ',', '.') .  ' ' .  $outputSatuan;
                        }



                        break;
                    default:
                        $targetValue = 0;
                        break;
                }

                $pdf->SetWidths(array($tableDataWidth[0], $width_cellTitle, $tableDataWidth[2]));
                $pdf->SetAligns(array('C', 'L', 'C'));
                $pdf->SetValigns(array(true, false, true));
                $pdf->SetLineHeight(6);
                $pdf->Row(array(
                    $numberText,
                    $data['title'],
                    $targetValue,
                ), $celTableDataFill);

                // if ($data_targetValue['is_checked'] == '1')  $pdf->Cell($tableDataWidth[2], 6, $targetValue, 1, 0, 'C', $celTableDataFill);
            } else {
                $rowNUmber = 0;

                $pdf->SetWidths(array($tableDataWidth[0], $width_cellTitle));
                $pdf->SetAligns(array('C', 'L'));
                $pdf->SetValigns(array(true, false));
                $pdf->SetLineHeight(6);
                $pdf->Row(array(
                    $numberText,
                    $data['title']
                ), $celTableDataFill);
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
        $pdf->Cell(100, 4, $dataDokumen['info_title'], 0, 0, 'L');

        // anggaran title
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(183);
        $pdf->Cell(85, 4, 'Anggaran', 0, 0, 'R');
        $pdf->Ln(5);



        // kegiatan
        foreach ($dataDokumenKegiatan as $key_kegiatan => $data_kegiatan) {
            $pdf->SetFont($this->fontFamily, '', 8);
            $pdf->SetX((310 - array_sum($tableDataWidth)) / 2);
            $pdf->Cell(100, 3, ++$key_kegiatan . ". " . ltrim($data_kegiatan['nama']), 0, 0, 'L');

            // anggaran perkegiatan value
            $pdf->SetFont($this->fontFamily, 'B', 8);
            $pdf->SetX(170);
            $pdf->Cell(80, 3, "Rp", 0, 0, 'R');

            // anggaran value
            $pdf->SetFont($this->fontFamily, '', 8);
            $pdf->SetX(183);
            $pdf->Cell(100, 3, rupiahFormat($data_kegiatan['anggaran'], false, 2), 0, 0, 'R');

            $pdf->Ln(4);
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
        $pdf->Cell(80, 3, "Rp", 0, 0, 'R');



        // total anggaran value
        $pdf->SetFont($this->fontFamily, 'B', 8);
        $pdf->SetX(183);
        $pdf->Cell(100, 3, rupiahFormat($dataDokumen['total_anggaran'], false, 2), 0, 0, 'R');

        // info
        // foreach ($dataDokumenInfo as $key_info => $data_info) {
        //     $pdf->SetFont($this->fontFamily, '', 8);
        //     $pdf->SetX((310 - array_sum($tableDataWidth)) / 2);
        //     $pdf->Cell(100, 7, $data_info['info'], 0, 0, 'L');
        //     $pdf->Ln(4);
        // }


        /** TTD Section */
        $pdf->Ln(5);
        $jabatanPihak1_isPlt = $dataDokumen['pihak1_is_plt'] ? 'Plt. ' : '';
        $jabatanPihak2_isPlt = $dataDokumen['pihak2_is_plt'] ? 'Plt. ' : '';
        // $dokumenKopTitle1_prefix = ($dataDokumen['dokumen_type'] == "satker" && strpos($dataDokumen['pihak1_initial'], 'OPERASI DAN PEMELIHARAAN')) ? 'SATUAN KERJA' : '';
        $this->pdf_renderSectionTtd($pdf, array_sum($tableDataWidth), [
            'person1Title' => $jabatanPihak2_isPlt . $dataDokumen['pihak2_initial'],
            'person1Name'  => $dataDokumen['pihak2_ttd'],
            'person2Date'  => $this->dokumenLokasi . ',   ' . $this->tanggal . ' '  . $this->dokumenBulan . ' ' . $this->dokumenYear,
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

        // Tanda tangan instansi Khusus
        switch ($_ttd['person2Title']) {
                // merapikan ttd
            case 'KEPALA BALAI BESAR WILAYAH SUNGAI BENGAWAN SOLO':
                $ttd = $_ttd['person2Title'];
                $widthTitleJabatan = 85;
                $widthNamaPejabat = 122;
                break;

            case 'KEPALA SEKRETARIAT DIREKTORAT JENDERAL SUMBER DAYA AIR':
                $ttd =  str_replace('SEKRETARIAT', 'SEKRETARIS', str_replace('KEPALA', '', $_ttd['person2Title']));
                $widthTitleJabatan = 78;
                $widthNamaPejabat = 115;
                break;

                // case 'KEPALA SNVT PEMBANGUNAN BENDUNGAN BWS NUSA TENGGARA I':
                //     $ttd = $_ttd['person2Title'];
                //     $widthTitleJabatan = 85;
                //     $widthNamaPejabat = 122;
                //     break;

            case 'KEPALA SKPD TP-OP DINAS PEKERJAAN UMUM SUMBER DAYA AIR DAN PENATAAN RUANG PROVINSI JAWA TENGAH':
                $ttd = $_ttd['person2Title'];
                $widthTitleJabatan = 115;
                $widthNamaPejabat = 150;
                break;

            default:
                $widthTitleJabatan = 95;
                $widthNamaPejabat = 130;
                $ttd = $_ttd['person2Title'];
                break;
        }

        // title ttd 1
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((300 - $_sectionWidth) / 2);
        $pdf->Cell(125, 13.7, $_ttd['person1Title'], 0, 0, 'C');

        // title ttd 2
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(149);
        // $pdf->Cell(144, 4, $_ttd['person2Date'], 0, 0, 'C');
        $pdf->Cell($widthNamaPejabat, 4, $_ttd['person2Date'], 0, 0, 'C');
        $pdf->Ln();



        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(167);
        // $pdf->MultiCell(115, 5, $_ttd['person2Title'], 0, 'C');
        $pdf->MultiCell($widthTitleJabatan, 5, $ttd, 0, 'C');

        $pdf->Ln(20);

        // td 1
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((300 - $_sectionWidth) / 2);
        $pdf->Cell(120, 4, strtoupper($_ttd['person1Name']), 0, 0, 'C');

        // td 2
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(149);
        // $pdf->Cell(144, 4, strtoupper($_ttd['person2Name']), 0, 0, 'C');
        $pdf->Cell($widthNamaPejabat, 4, strtoupper($_ttd['person2Name']), 0, 0, 'C');
    }

    private function pdf_renderWatermarkKonsep($pdf, $_dataDokumen, $topBorder, $topWatermark)
    {




        if ($_dataDokumen['is_revision_same_year'] < 1) {
            //     $created_at = $this->dokumenSatker->select('
            //     dokumenpk_satker.created_at
            // ')
            //         ->where('dokumenpk_satker.satkerid', $_dataDokumen['satkerid'])
            //         ->where('dokumenpk_satker.status', 'revision')
            //         ->where('dokumenpk_satker.is_revision_same_year', 0)
            //         ->where('dokumenpk_satker.revision_master_number', '!=', null)
            //         ->orderBy('created_at', 'desc')
            //         ->limit(1)
            //         ->get()
            //         ->getRowArray();

            //     // Ubah nilai string menjadi timestamp
            //     $acuan = strtotime($created_at['created_at']);
            //     $createdAtThisDokumen = strtotime($_dataDokumen['created_at']);
            // print_r($created_at['created_at'] . " -- " . $_dataDokumen['created_at']);
            // exit;


            $rev_number = $this->dokumenSatker->select('
                dokumenpk_satker.revision_number
            ')
                ->where('dokumenpk_satker.satkerid', $_dataDokumen['satkerid'])
                ->where('dokumenpk_satker.balaiid', $_dataDokumen['balaiid'])
                // ->where('dokumenpk_satker.status', 'revision')
                ->where('dokumenpk_satker.is_revision_same_year', 0)
                // ->where('dokumenpk_satker.revision_master_number', '!=', null)
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->get()
                ->getRowArray();



            if ($_dataDokumen['status'] == 'hold' || $_dataDokumen['status'] == 'tolak') {
                $pdf->watermarkText = 'KONSEP';
                $pdf->watermarkOffsetLeft        = 254.5;
                $pdf->watermarkBorder_width      = 24;
                $pdf->watermarkBorder_offsetLeft = 250;
                $pdf->watermarkBorder_offsetTop = $topBorder;
                $pdf->watermarkOffsetTop        = $topWatermark;
            }
            if (($_dataDokumen['status'] == 'revision' && $_dataDokumen['acc_by'] == null) || ($rev_number['revision_number'] != $_dataDokumen['revision_number'])) {
                $pdf->watermarkText = 'KOREKSI';
                $pdf->watermarkOffsetLeft        = 202.5;
                $pdf->watermarkBorder_width      = 24;
                $pdf->watermarkBorder_offsetLeft = 200;
                $pdf->watermarkBorder_offsetTop = $topBorder;
                $pdf->watermarkOffsetTop        = $topWatermark;
            }
        }

        if ($_dataDokumen['is_revision_same_year'] > 0) {

            $pdf->watermarkText = 'REVISI';
            $pdf->watermarkOffsetLeft        = 246;
            $pdf->watermarkBorder_width      = 24;
            $pdf->watermarkBorder_offsetLeft = 240;
            $pdf->watermarkBorder_offsetTop = $topBorder;
            $pdf->watermarkOffsetTop        = $topWatermark;
        }
    }




    // private function pdf_renderWatermarkKonsep($pdf, $_dokumenStatus, $_revisionNumber)
    // {
    //     if ($_dokumenStatus != "setuju") {
    //         switch ($_dokumenStatus) {
    //             case 'revision':
    //                 $subtitle = '';
    //                 if (!is_null($_revisionNumber)) {
    //                     // $subtitle = $_revisionNumber > 1 ? ' Ke - ' . $_revisionNumber : '';
    //                     $pdf->watermarkSubTextOffsetLeft = 133;
    //                 } else {
    //                     // $subtitle = ' - Dokumen Awal';
    //                 }

    //                 $pdf->watermarkText = 'KOREKSI' . $subtitle;
    //                 if ($subtitle == '') {
    //                     //lama
    //                     // $pdf->watermarkOffsetLeft        = 257;
    //                     // $pdf->watermarkBorder_width      = 30;
    //                     // $pdf->watermarkBorder_offsetLeft = 254;

    //                     //koreksi
    //                     $pdf->watermarkOffsetLeft        = 202.5;
    //                     $pdf->watermarkBorder_width      = 24;
    //                     $pdf->watermarkBorder_offsetLeft = 200;
    //                 } else {
    //                     $pdf->watermarkOffsetLeft        = 200;
    //                     $pdf->watermarkBorder_width      = 46;
    //                     $pdf->watermarkBorder_offsetLeft = 200;
    //                 }
    //                 break;

    //             case 'revision-same-year':
    //                 $subtitle = '';
    //                 if (!is_null($_revisionNumber)) {
    //                     // $subtitle = $_revisionNumber > 1 ? ' Ke - ' . $_revisionNumber : '';
    //                     $pdf->watermarkSubTextOffsetLeft = 133;
    //                 } else {
    //                     // $subtitle = ' - Dokumen Awal';
    //                 }

    //                 $pdf->watermarkText = 'REVISI' . $subtitle;
    //                 if ($subtitle == '') {
    //                     //lama
    //                     // $pdf->watermarkOffsetLeft        = 260;
    //                     // $pdf->watermarkBorder_width      = 28;
    //                     // $pdf->watermarkBorder_offsetLeft = 255;

    //                     //revisi
    //                     $pdf->watermarkOffsetLeft        = 246;
    //                     $pdf->watermarkBorder_width      = 24;
    //                     $pdf->watermarkBorder_offsetLeft = 240;
    //                 } else {
    //                     $pdf->watermarkOffsetLeft        = 250;
    //                     $pdf->watermarkBorder_width      = 39;
    //                     $pdf->watermarkBorder_offsetLeft = 247;
    //                 }
    //                 break;

    //             default:
    //                 $pdf->watermarkText = 'KONSEP';
    //                 // $pdf->watermarkOffsetLeft = 70;
    //                 // $pdf->watermarkOffsetLeft        = 259;
    //                 // $pdf->watermarkBorder_width      = 28;
    //                 // $pdf->watermarkBorder_offsetLeft = 256;

    //                 //konsep 
    //                 $pdf->watermarkOffsetLeft        = 254.5;
    //                 $pdf->watermarkBorder_width      = 24;

    //                 $pdf->watermarkBorder_offsetLeft = 250;



    //                 break;
    //         }

    //         // $pdf->Image('images/watermark_dokumen_konsep.png', 23, 80, 250);
    //     }
    // }



    private function gettemplateRowChecked($templateId, $dokumenSatkerId)
    {
        $tempRow = [];
        $lastFormIndex = 0;

        $tableData = $this->templateRow
            ->where('template_id', $templateId)
            ->orderBy('no_urut')
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
                    $lastFormIndex = count($tempRow);
                }
            } else {
                array_push($tempRow, $data);
            }
        }

        array_splice($tempRow, $lastFormIndex);

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
    public $watermarkOffsetTop       = 0;
    public $watermarkSubTextOffsetLeft = 95;
    public $watermarkBorder_width      = 0;
    public $watermarkBorder_offsetLeft = 0;
    public $watermarkBorder_offsetRight = 0;
    public $watermarkBorder_offsetTop = 0;

    var $widths;
    var $aligns;
    var $valigns;
    var $lineHeight;

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
            $this->SetLineWidth(0.0);
            // border merah
            // $this->SetDrawColor(220,20,60);
            // $this->Rect($this->watermarkBorder_offsetLeft, 13, $this->watermarkBorder_width, 10, 'D');

            //bg hitam
            $this->SetDrawColor(0, 0, 0);
            // $this->Rect($this->watermarkBorder_offsetLeft, 16, $this->watermarkBorder_width, 5, 'D');
            $this->Rect($this->watermarkBorder_offsetLeft, $this->watermarkBorder_offsetTop, $this->watermarkBorder_width, 5, 'D');

            //Put the watermark
            $this->SetFont('Times', 'B', 11);
            // $this->SetTextColor(255, 192, 203);
            //$this->RotatedText($this->watermarkOffsetLeft, 110, $this->watermarkText, 0);
            // $this->SetTextColor(220,20,60); //text merah
            $this->SetTextColor(0, 0, 0); //text hitam
            // $this->RotatedText($this->watermarkOffsetLeft, 20, $this->watermarkText, 0);
            $this->RotatedText($this->watermarkOffsetLeft, $this->watermarkOffsetTop, $this->watermarkText, 0);


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




    function SetWidths($w)
    {
        $this->widths = $w;
    }

    //Set the array of column alignments
    function SetAligns($a)
    {
        $this->aligns = $a;
    }

    function SetValigns($a)
    {
        $this->valigns = $a;
    }

    //Set line height
    function SetLineHeight($h)
    {
        $this->lineHeight = $h;
    }

    //Calculate the height of the row
    function Row($data, $fill = null)
    {
        // number of line
        $nb = 0;

        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;

        //Issue a page break first if needed
        $this->CheckPageBreak($h);

        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $valign = $this->valigns[$i] ? $this->valigns[$i] : false;
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h, 'DF');
            //Print the text
            $text = $valign ? $this->drawTextBox($data[$i], $w, $h, $a, 'M', false) : $data[$i];
            $this->MultiCell($w, 6, $text, 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h > 6 ? ($h - 6) : 0);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //calculate the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function drawTextBox($strText, $w, $h, $align = 'L', $valign = 'T', $border = true)
    {
        $xi = $this->GetX();
        $yi = $this->GetY();

        $hrow = $this->FontSize;
        $textrows = $this->drawRows($w, $hrow, $strText, 0, $align, 0, 0, 0);
        $maxrows = floor($h / $this->FontSize);
        $rows = min($textrows, $maxrows);

        $dy = 0;
        if (strtoupper($valign) == 'M')
            $dy = ($h - $rows * $this->FontSize) / 2;
        if (strtoupper($valign) == 'B')
            $dy = $h - $rows * $this->FontSize;

        $this->SetY($yi + $dy);
        $this->SetX($xi);

        $this->drawRows($w, $hrow, $strText, 0, $align, false, $rows, 1);

        if ($border)
            $this->Rect($xi, $yi, $w, $h);
    }

    function drawRows($w, $h, $txt, $border = 0, $align = 'J', $fill = false, $maxline = 0, $prn = 0)
    {
        if (!isset($this->CurrentFont))
            $this->Error('No font has been set');
        $cw = $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $b = 0;
        if ($border) {
            if ($border == 1) {
                $border = 'LTRB';
                $b = 'LRT';
                $b2 = 'LR';
            } else {
                $b2 = '';
                if (is_int(strpos($border, 'L')))
                    $b2 .= 'L';
                if (is_int(strpos($border, 'R')))
                    $b2 .= 'R';
                $b = is_int(strpos($border, 'T')) ? $b2 . 'T' : $b2;
            }
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while ($i < $nb) {
            //Get next character
            $c = $s[$i];
            if ($c == "\n") {
                //Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    if ($prn == 1) $this->_out('0 Tw');
                }
                if ($prn == 1) {
                    $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                }
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2)
                    $b = $b2;
                if ($maxline && $nl > $maxline)
                    return substr($s, $i);
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                //Automatic line break
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        if ($prn == 1) $this->_out('0 Tw');
                    }
                    if ($prn == 1) {
                        $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                    }
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        if ($prn == 1) $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    if ($prn == 1) {
                        $this->Cell($w, $h, substr($s, $j, $sep - $j), $b, 2, $align, $fill);
                    }
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2)
                    $b = $b2;
                if ($maxline && $nl > $maxline)
                    return substr($s, $i);
            } else
                $i++;
        }
        //Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            if ($prn == 1) $this->_out('0 Tw');
        }
        if ($border && is_int(strpos($border, 'B')))
            $b .= 'B';
        if ($prn == 1) {
            $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
        }
        $this->x = $this->lMargin;
        return $nl;
    }
}
