<?php

namespace Modules\Satker\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Libraries\FPDF;

class DokumenpkExport extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session           = session();
        $this->user        = $session->get('userData');
        $this->userUID     = $this->user['uid'];
        $this->dokumenYear = $this->user['tahun'];
        $this->db          = \Config\Database::connect();

        $this->dokumenSatker      = $this->db->table('dokumenpk_satker');
        $this->dokumenSatker_rows = $this->db->table('dokumenpk_satker_rows');

        $this->templateDokumen = $this->db->table('dokumen_pk_template');
        $this->templateRow     = $this->db->table('dokumen_pk_template_row');
        $this->templateInfo    = $this->db->table('dokumen_pk_template_info');

        $this->dokumenStatus = [
            'hold'     => ['message' => 'Menunggu Konfirmasi', 'color' => 'bg-secondary'],
            'setuju'   => ['message' => 'Telah di Setujui', 'color' => 'bg-success text-white'],
            'tolak'    => ['message' => 'Di Tolak', 'color' => 'bg-danger text-white'],
            'revision' => ['message' => 'Telah Di Revisi', 'color' => 'bg-dark text-white']
        ];
        $this->request = \Config\services::request();

        $this->dokumenLoadedStatus = '';

        $this->fontFamily = 'Arial';
        $this->sectionWidth = 265;
    }



    public function pdf($_dokumenSatkerID)
    {
        $pdf = new FPDF();

        $dataDokumen = $this->dokumenSatker->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
            ->where('dokumenpk_satker.id', $_dokumenSatkerID)
            ->get()
            ->getRowArray();

        if ($dataDokumen) $this->dokumenYear = date('Y', strtotime($dataDokumen['created_at']));

        $this->dokumenLoadedStatus = $dataDokumen['status'];

        /** Dokumen Opening */
        $this->pdf_pageDokumenOpening($pdf, $dataDokumen);

        /** Dokumen Detail */
        $this->pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen);

        $pdf->Output('F', 'dokumen-perjanjian-kinerja.pdf');

        return $this->respond([
            'dokumen' => $dataDokumen
        ]);
    }










    private function pdf_pageDokumenOpening($pdf, $dataDokumen)
    {
        $pdf->AddPage('L', 'A4');
        $this->pdf_renderWatermarkKonsep($pdf);

        $pdf->SetFont('Arial', 'B', 50);
        $pdf->SetTextColor(255, 192, 203);

        /**  Dokumen KOP */
        $pdf->Image('Images/logo_pu_border.png', 143, 6, 16);
        $pdf->Ln(15);

        $dokumenKopTitle1 = 'PERJANJIAN KINERJA TAHUN ' . $this->dokumenYear;
        $dokumenKopTitle2 = $dataDokumen['pihak1_initial'];
        $dokumenKopTitle3 = $dataDokumen['pihak2_initial'];

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Kop Title 1
        $width_kopTitle1 = $pdf->GetStringWidth($dokumenKopTitle1) + 6;
        $pdf->SetX((300 - $width_kopTitle1) / 2);
        $pdf->Cell($width_kopTitle1, 6, $dokumenKopTitle1, 0, 1, 'C');

        // Kop Title 2
        $pdf->SetFont('Arial', 'B', 12);
        $width_kopTitle2 = $pdf->GetStringWidth($dokumenKopTitle2) + 6;
        $pdf->SetX((300 - $width_kopTitle2) / 2);
        $pdf->Cell($width_kopTitle2, 6, $dokumenKopTitle2, 0, 1, 'C');

        // Kop Title 2
        $pdf->SetFont('Arial', 'B', 12);
        $width_kopTitle3 = $pdf->GetStringWidth($dokumenKopTitle3) + 6;
        $pdf->SetX((300 - $width_kopTitle3) / 2);
        $pdf->Cell($width_kopTitle3, 6, $dokumenKopTitle3, 0, 1, 'C');

        // Line break
        $pdf->Ln(6);


        /**  Dokumen Pengenalan Pihak */
        $pdf->SetFont('Arial', '', 12);
        // Text
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, "Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan, dan akuntabel serta berorientasi pada hasil, kami yang bertandatangan di bawah ini:", 0, 'J');
        $pdf->Ln(4);

        // Pihak Pertama
        $this->pdf_renderIntroductionSection($pdf, 'Nama', $dataDokumen['pihak1_ttd']);
        $this->pdf_renderIntroductionSection($pdf, 'Jabatan', 'KEPALA ' . $dataDokumen['pihak1_initial']);

        // Text 2
        $pdf->Ln(4);
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, "Selanjutnya disebut PIHAK PERTAMA", 0, 'J');
        $pdf->Ln(4);

        // Pihak Kedua
        $this->pdf_renderIntroductionSection($pdf, 'Nama', $dataDokumen['pihak2_ttd']);
        $this->pdf_renderIntroductionSection($pdf, 'Jabatan', 'KEPALA ' . $dataDokumen['pihak2_initial']);

        // Text 3
        $pdf->Ln(4);
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, "Selaku atasan langsung pihak pertama. selanjutnya disebut PIHAK KEDUA", 0, 'J');
        $pdf->Ln(5);


        /** Isi */
        // title
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->MultiCell($this->sectionWidth, 5, "PIHAK PERTAMA dan PIHAK KEDUA sepakat untuk membuat Perjanjian Kinerja dengan ketentuan sebagai berikut :", 0, 'J');

        // Text
        $pdf->Ln(2);
        $this->pdf_renderListIsiSection($pdf, '1.', "Pihak pertama pada tahun " . $this->dokumenYear . " ini berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah di tetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab pihak pertama.");
        $pdf->Ln(2);
        $this->pdf_renderListIsiSection($pdf, '2.', "Pihak kedua akan melakukan supervisi yang di perlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi.");


        /** TTD Section */
        $pdf->Ln(8);
        $this->pdf_renderSectionTtd($pdf, $this->sectionWidth, [
            'person1Title' => 'Pihak Kedua',
            'person1Name'  => $dataDokumen['pihak2_ttd'],
            'person2Date'  => 'JAKARTA ,          ' . bulan(date('m', strtotime($dataDokumen['created_at']))) . ' ' . $this->dokumenYear,
            'person2Title' => 'Pihak Pertama',
            'person2Name'  => $dataDokumen['pihak1_ttd'],
        ]);
    }



    private function pdf_renderIntroductionSection($pdf, $_title, $_introduction)
    {
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX((350 - $this->sectionWidth) / 2);
        $pdf->Cell(35, 5, $_title, 0);
        $pdf->SetX((420 - $this->sectionWidth) / 2);
        $pdf->Cell(5, 5, ':', 0);
        $pdf->SetX((430 - $this->sectionWidth) / 2);
        $pdf->Cell(150, 5, $_introduction, 0);
        $pdf->Ln(7);
    }



    private function pdf_renderListIsiSection($pdf, $_listNo, $_listContent)
    {
        $pdf->SetX((297 - $this->sectionWidth) / 2);
        $pdf->Cell(7, 5, $_listNo, 0);
        $pdf->MultiCell($this->sectionWidth - 7, 5, $_listContent, 0);
    }








    private function pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen)
    {
        $pdf->AddPage('L', 'A4');
        $this->pdf_renderWatermarkKonsep($pdf);

        $header      = ['SASARAN PROGRAM / SASARAN KEGIATAN / INDIKATOR', 'TARGET ' . $this->dokumenYear];
        $headerWidth = [
            200,
            65
        ];

        $dataDokumenInfo = $this->templateInfo->where('template_id', $dataDokumen['template_id'])->get()->getResultArray();

        $tableData = $this->templateRow
            ->where('template_id', $dataDokumen['template_id'])
            ->get()
            ->getResultArray();

        $tableDataWidth = [
            20,
            180,
            65
        ];


        /**  Dokumen KOP */
        $dokumenKopTitle1 = 'PERJANJIAN KINERJA TAHUN ' . $this->dokumenYear;
        $dokumenKopTitle2 = $dataDokumen['pihak1_initial'] . ' - ' . $dataDokumen['pihak2_initial'];

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Kop Title 1
        $width_kopTitle1 = $pdf->GetStringWidth($dokumenKopTitle1) + 6;
        $pdf->SetX((300 - $width_kopTitle1) / 2);
        $pdf->Cell($width_kopTitle1, 6, $dokumenKopTitle1, 0, 1, 'C');

        // Kop Title 2
        $width_kopTitle2 = $pdf->GetStringWidth($dokumenKopTitle2) + 6;
        $pdf->SetX((300 - $width_kopTitle2) / 2);
        $pdf->Cell($width_kopTitle2, 6, $dokumenKopTitle2, 0, 1, 'C');

        // Line break
        $pdf->Ln(6);



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
            $celTableDataFill = $this->dokumenLoadedStatus == 'setuju' ? true : false;

            if ($data['type'] == 'form') {
                $pdf->SetFillColor(255);
                $width_cellTitle = $tableDataWidth[1];
                $rowNUmber++;
            } else {
                $pdf->SetFillColor(233);
                $width_cellTitle = 245;
                $rowNUmber = 'SK';
            }


            $pdf->SetX((300 - array_sum($tableDataWidth)) / 2);
            $pdf->Cell($tableDataWidth[0], 6, $rowNUmber, 'T,B,L', 0, 'C', $celTableDataFill);
            $pdf->Cell($width_cellTitle, 6, $data['title'], 'T,R,B', 0, 'L', $celTableDataFill);

            if ($data['type'] == 'form') {
                $data_targetValue = $this->dokumenSatker_rows->where('dokumen_id', $_dokumenSatkerID)
                    ->where('template_row_id', $data['id'])
                    ->get()
                    ->getRowArray();

                $pdf->Cell($tableDataWidth[2], 6, $data_targetValue['target_value'] . ' ' . $data['target_satuan'], 1, 0, 'C', $celTableDataFill);
            } else {
                $rowNUmber = 0;
            }

            $pdf->Ln();
        }
        $pdf->Ln(1);


        /** Keterangan Section */
        // keterangan title
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((297 - array_sum($tableDataWidth)) / 2);
        $pdf->Cell(100, 7, 'KETERANGAN', 0, 0, 'L');
        $pdf->Ln(5);

        // keterangan
        $pdf->SetFont($this->fontFamily, 'B', 8);
        $pdf->SetX((297 - array_sum($tableDataWidth)) / 2);
        $pdf->Cell(100, 7, $dataDokumen['keterangan'], 0, 0, 'L');
        $pdf->Ln(8);


        /** Info & Anggaran Section */
        // Info title
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((297 - array_sum($tableDataWidth)) / 2);
        $pdf->Cell(100, 7, $dataDokumen['info_title'], 0, 0, 'L');

        // anggaran title
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(183);
        $pdf->Cell(100, 7, 'ANGGARAN', 0, 0, 'R');
        $pdf->Ln(5);

        // anggaran title
        $pdf->SetFont($this->fontFamily, 'B', 8);
        $pdf->SetX(183);
        $pdf->Cell(100, 7, rupiahFormat($dataDokumen['total_anggaran']), 0, 0, 'R');

        // info
        foreach ($dataDokumenInfo as $key_info => $data_info) {
            $pdf->SetFont($this->fontFamily, '', 8);
            $pdf->SetX((310 - array_sum($tableDataWidth)) / 2);
            $pdf->Cell(100, 7, $data_info['info'], 0, 0, 'L');
            $pdf->Ln(4);
        }


        /** TTD Section */
        $pdf->Ln(10);
        $this->pdf_renderSectionTtd($pdf, array_sum($tableDataWidth), [
            'person1Title' => 'KEPALA ' . $dataDokumen['pihak2_initial'],
            'person1Name'  => $dataDokumen['pihak2_ttd'],
            'person2Date'  => 'JAKARTA ,          ' . bulan(date('m', strtotime($dataDokumen['created_at']))) . ' ' . $this->dokumenYear,
            'person2Title' => 'KEPALA ' . $dataDokumen['pihak1_initial'],
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
        $pdf->Cell(125, 4, $_ttd['person1Title'], 0, 0, 'C');

        // title ttd 2
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(149);
        $pdf->Cell(134, 4, $_ttd['person2Date'], 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(149);
        $pdf->Cell(134, 4, $_ttd['person2Title'], 0, 0, 'C');
        $pdf->Ln(20);

        // td 1
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX((300 - $_sectionWidth) / 2);
        $pdf->Cell(125, 4, $_ttd['person1Name'], 0, 0, 'C');

        // td 2
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(149);
        $pdf->Cell(134, 4, $_ttd['person2Name'], 0, 0, 'C');
    }



    private function pdf_renderWatermarkKonsep($pdf)
    {
        if ($this->dokumenLoadedStatus != "setuju") {
            $pdf->Image('Images/watermark_dokumen_konsep.png', 23, 80, 250);
        }
    }
}
