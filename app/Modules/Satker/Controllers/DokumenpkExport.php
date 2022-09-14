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
        $session             = session();
        $this->user          = $session->get('userData');
        $this->userUID       = $this->user['uid'];
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
        $pdf = new PDF();

        $dataDokumen = $this->dokumenSatker->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
            ->join('tkabkota', "(SUBSTRING_INDEX(dokumenpk_satker.kota, '-', 1) = tkabkota.kdlokasi AND SUBSTRING_INDEX(dokumenpk_satker.kota, '-', -1) = tkabkota.kdkabkota)", 'left')
            ->where('dokumenpk_satker.id', $_dokumenSatkerID)
            ->get()
            ->getRowArray();

        if ($dataDokumen) {
            $this->dokumenBulan = bulan(date('m', strtotime($dataDokumen['created_at'])));

            if ($dataDokumen['tahun'] != '') {
                $this->dokumenYear = $dataDokumen['tahun'];
            }
            else {
                $this->dokumenYear = date('Y', strtotime($dataDokumen['created_at']));
            }
            
            if ($dataDokumen['kota'] != '') $this->dokumenLokasi = $dataDokumen['nmkabkota'];

            if ($dataDokumen['bulan'] != '') $this->dokumenBulan = $this->bulan[$dataDokumen['bulan'] - 1];
        }
        
        $this->pdf_renderWatermarkKonsep($pdf, $dataDokumen['status'], $dataDokumen['revision_master_number']);

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

        $pdf->SetFont('Arial', 'B', 50);
        $pdf->SetTextColor(255, 192, 203);

        $pdf->SetLineWidth(4);
        $pdf->SetDrawColor(3, 106, 138);
        $pdf->Rect(8, 8, 282, 192, 'D');

        /**  Dokumen KOP */
        $pdf->Image('images/logo_pu_border.png', 143, 14, 12);
        $pdf->Ln(17);

        $dokumenKopTitle2_prefix = $dataDokumen['dokumen_type'] == "satker" ? 'SATUAN KERJA' : '';

        $dokumenKopTitle1 = 'PERJANJIAN KINERJA TAHUN ' . $this->dokumenYear;
        $dokumenKopTitle2 = $dokumenKopTitle2_prefix . $dataDokumen['pihak1_initial'];
        $dokumenKopTitle3 = $dataDokumen['pihak2_initial'];

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Kop Title 1
        $width_kopTitle1 = $pdf->GetStringWidth($dokumenKopTitle1) + 6;
        $pdf->SetX((300 - $width_kopTitle1) / 2);
        $pdf->Cell($width_kopTitle1, 6, $dokumenKopTitle1, 0, 1, 'C');

        // Kop Title 2
        $pdf->SetFont('Arial', 'B', 10);
        $width_kopTitle2 = $pdf->GetStringWidth($dokumenKopTitle2) + 6;
        $pdf->SetX((300 - $width_kopTitle2) / 2);
        $pdf->Cell($width_kopTitle2, 6, $dokumenKopTitle2, 0, 1, 'C');

        // Kop Title 3
        $kopTitle3 = $dataDokumen['dokumen_type'] != "satker" ? 'DIREKTORAT JENDERAL SUMBER DAYA AIR' : $dokumenKopTitle3;
        $pdf->SetFont('Arial', 'B', 10);
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
        $this->pdf_renderIntroductionSection($pdf, 'Jabatan', $jabatanPihak1_isPlt . 'KEPALA ' . $dataDokumen['pihak1_initial']);

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
        $pdf->Ln(5);


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
        $pdf->Ln(8);
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
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX((330 - $this->sectionWidth) / 2);
        $pdf->Cell(35, 5, $_title, 0);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX((360 - $this->sectionWidth) / 2);
        $pdf->Cell(5, 5, ':', 0);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX((370 - $this->sectionWidth) / 2);
        $pdf->Cell(150, 5, $_introduction, 0);

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








    private function pdf_pageDokumenDetail($pdf, $_dokumenSatkerID, $dataDokumen)
    {
        $pdf->AddPage('L', 'A4');

        $header      = ['SASARAN PROGRAM / SASARAN KEGIATAN / INDIKATOR', 'TARGET ' . $this->dokumenYear];
        $headerWidth = [
            200,
            65
        ];

        $dataDokumenKegiatan = $this->dokumenSatker_kegiatan->where('dokumen_id', $_dokumenSatkerID)->get()->getResultArray();
        $dataDokumenInfo     = $this->templateInfo->where('template_id', $dataDokumen['template_id'])->get()->getResultArray();

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

        $divisiPihak2 = $dataDokumen['dokumen_type'] != "satker" ? 'DIREKTORAT JENDERAL SUMBER DAYA AIR' : $dataDokumen['pihak2_initial'];
        $dokumenKopTitle2 = $dataDokumen['pihak1_initial'] . ' - ' . $divisiPihak2;

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
        $pdf->SetX((300 - $width_kopTitle2) / 2);
        $pdf->Cell($width_kopTitle2, 6, $dokumenKopTitle2, 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 10);

        // Line break
        $pdf->Ln(6);
        

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
            $celTableDataFill = $this->dokumenLoadedStatus == 'setuju' ? true : false;
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
                $rowNUmber = 'SK';
            }


            $pdf->SetX((300 - array_sum($tableDataWidth)) / 2);
            if ($data_targetValue['is_checked'] == '1') {
                $pdf->Cell($tableDataWidth[0], 6, $rowNUmber, 'T,B,L', 0, 'C', $celTableDataFill);
                $pdf->Cell($width_cellTitle, 6, $data['title'], 'T,R,B', 0, 'L', $celTableDataFill);
            }

            if ($data['type'] == 'form') {
                if ($data_targetValue['is_checked'] == '1')  $pdf->Cell($tableDataWidth[2], 6, $data_targetValue['target_value'] . ' ' . $data['target_satuan'], 1, 0, 'C', $celTableDataFill);
            } else {
                $rowNUmber = 0;
            }

            if ($data_targetValue['is_checked'] == '1') $pdf->Ln();
        }
        $pdf->Ln(1);


        /** Keterangan Section */
        if ($dataDokumen['keterangan'] != '') {
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
        }


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

        // kegiatan
        foreach ($dataDokumenKegiatan as $key_kegiatan => $data_kegiatan) {
            $pdf->SetFont($this->fontFamily, '', 8);
            $pdf->SetX((310 - array_sum($tableDataWidth)) / 2);
            $pdf->Cell(100, 7, ltrim($data_kegiatan['nama']), 0, 0, 'L');
            $pdf->Ln(4);
        }

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
        $dokumenKopTitle1_prefix = $dataDokumen['dokumen_type'] == "satker" ? 'SATUAN KERJA' : '';
        $this->pdf_renderSectionTtd($pdf, array_sum($tableDataWidth), [
            'person1Title' => $jabatanPihak2_isPlt . $dataDokumen['pihak2_initial'],
            'person1Name'  => $dataDokumen['pihak2_ttd'],
            'person2Date'  => $this->dokumenLokasi . ',          ' . $this->dokumenBulan . ' ' . $this->dokumenYear,
            'person2Title' => $jabatanPihak1_isPlt . 'KEPALA ' . $dokumenKopTitle1_prefix . $dataDokumen['pihak1_initial'],
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
        $pdf->Cell(134, 4, $_ttd['person2Date'], 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont($this->fontFamily, 'B', 9);
        $pdf->SetX(167);
        $pdf->MultiCell(100, 5, $_ttd['person2Title'], 0, 'C');
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



    private function pdf_renderWatermarkKonsep($pdf, $_dokumenStatus, $_revisionNumber)
    {
        if ($_dokumenStatus != "setuju") {
            switch ($_dokumenStatus) {
                case 'revision':
                    $pdf->watermarkText = 'K O R E K S I';

                    if (! is_null($_revisionNumber)) {
                        $pdf->watermarkSubText = $_revisionNumber > 1 ? 'Ke - ' . $_revisionNumber : '';
                        $pdf->watermarkSubTextOffsetLeft = 133;
                    }
                    else {
                        $pdf->watermarkSubText = 'Dokumen Awal';
                    }

                    $pdf->watermarkOffsetLeft = 80;
                    break;
                
                default:
                    $pdf->watermarkText = 'K O N S E P';
                    $pdf->watermarkOffsetLeft = 70;
                    break;
            }
            
            // $pdf->Image('images/watermark_dokumen_konsep.png', 23, 80, 250);
        }
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

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extract attributes
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        // Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }


    function Header()
    {
        //Put the watermark
        $this->SetFont('Arial','B',80);
        $this->SetTextColor(255, 192, 203);
        $this->RotatedText($this->watermarkOffsetLeft, 110, $this->watermarkText, 0);

        $this->SetFont('Arial','B',40);
        $this->RotatedText($this->watermarkSubTextOffsetLeft, 130, $this->watermarkSubText, 0);
    }
    
    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    function Rotate($angle,$x=-1,$y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }
}