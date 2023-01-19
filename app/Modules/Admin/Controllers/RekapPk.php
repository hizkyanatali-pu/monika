<?php

namespace Modules\Admin\Controllers;

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


class RekapPK extends \App\Controllers\BaseController
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->balai  = $this->db->table('m_balai');
        $this->satker  = $this->db->table('m_satker');
    }

    public function pdf()
    {

        $grup_jabatan = ["ESELON II", "UPT/BALAI", "BALAI TEKNIK", "SATKER PUSAT", "SKPD TP-OP"];

        $balai_s =  $this->balai->select('balai,balaiid')->where("jabatan_penanda_tangan_pihak_1 !=", "")->get()->getResult();




        $no = 1;
        foreach ($grup_jabatan as $key => $grup) {
            echo $grup . "<br>";

            switch ($grup) {
                case 'ESELON II':
                    # code...
                    $satker_s =  $this->satker->select('satker')->where('grup_jabatan', "eselon2")->Orwhere('satkerid', 352611)->get()->getResult();
                    break;

                case 'UPT/BALAI':
                    # code...
                    $satker_s =  $this->balai->select('balai as satker')->where("jabatan_penanda_tangan_pihak_1 !=", "")->get()->getResult();
                    break;

                case 'BALAI TEKNIK':
                    # code...
                    $satker_s =  $this->satker->select('satker')->where('balaiid', "97")->get()->getResult();
                    break;

                case 'SATKER PUSAT':
                    # code...
                    $satker_s =  $this->satker->select('satker')->where('grup_jabatan', "satker-pusat")->get()->getResult();
                    break;

                case 'SKPD TP-OP':
                    # code...
                    $satker_s =  $this->satker->select('satker')->where('balaiid', "98")->Orwhere('balaiid', "100")->get()->getResult();
                    break;

                default:
                    # code...
                    break;
            }


            foreach ($satker_s as $satker) {
                echo $no . ". " . $satker->satker . "<br>";
                $no++;
            }
        }

        foreach ($balai_s as $key => $balai) {
            $satker_s =  $this->satker->select('satker')->where('balaiid', $balai->balaiid)->get()->getResult();




            echo $balai->balai . "<br>";

            foreach ($satker_s as $key => $satker) {
                echo $no . ". " . $satker->satker . "<br>";
                $no++;
            }
        }
    }
}


// class PDF extends FPDF
// {
//     protected $B                   = 0;
//     protected $I                   = 0;
//     protected $U                   = 0;
//     protected $HREF                = '';

//     var $angle               = 0;

//     public $watermarkText              = '';
//     public $watermarkSubText           = '';
//     public $watermarkOffsetLeft        = 70;
//     public $watermarkSubTextOffsetLeft = 95;
//     public $watermarkBorder_width      = 0;
//     public $watermarkBorder_offsetLeft = 0;

//     function WriteHTML($html)
//     {
//         // HTML parser
//         $html = str_replace("\n", ' ', $html);
//         $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
//         foreach ($a as $i => $e) {
//             if ($i % 2 == 0) {
//                 // Text
//                 if ($this->HREF)
//                     $this->PutLink($this->HREF, $e);
//                 else
//                     $this->Write(5, $e);
//             } else {
//                 // Tag
//                 if ($e[0] == '/')
//                     $this->CloseTag(strtoupper(substr($e, 1)));
//                 else {
//                     // Extract attributes
//                     $a2 = explode(' ', $e);
//                     $tag = strtoupper(array_shift($a2));
//                     $attr = array();
//                     foreach ($a2 as $v) {
//                         if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
//                             $attr[strtoupper($a3[1])] = $a3[2];
//                     }
//                     $this->OpenTag($tag, $attr);
//                 }
//             }
//         }
//     }

//     function OpenTag($tag, $attr)
//     {
//         // Opening tag
//         if ($tag == 'B' || $tag == 'I' || $tag == 'U')
//             $this->SetStyle($tag, true);
//         if ($tag == 'A')
//             $this->HREF = $attr['HREF'];
//         if ($tag == 'BR')
//             $this->Ln(5);
//     }

//     function CloseTag($tag)
//     {
//         // Closing tag
//         if ($tag == 'B' || $tag == 'I' || $tag == 'U')
//             $this->SetStyle($tag, false);
//         if ($tag == 'A')
//             $this->HREF = '';
//     }

//     function SetStyle($tag, $enable)
//     {
//         // Modify style and select corresponding font
//         $this->$tag += ($enable ? 1 : -1);
//         $style = '';
//         foreach (array('B', 'I', 'U') as $s) {
//             if ($this->$s > 0)
//                 $style .= $s;
//         }
//         $this->SetFont('', $style);
//     }

//     function PutLink($URL, $txt)
//     {
//         // Put a hyperlink
//         $this->SetTextColor(0, 0, 255);
//         $this->SetStyle('U', true);
//         $this->Write(5, $txt, $URL);
//         $this->SetStyle('U', false);
//         $this->SetTextColor(0);
//     }


//     function Header()
//     {
//         if ($this->PageNo() == 1 || $this->PageNo() == 2 || $this->PageNo() || 3) {
//             $this->SetLineWidth(0.2);
//             // border merah
//             // $this->SetDrawColor(220,20,60);
//             // $this->Rect($this->watermarkBorder_offsetLeft, 13, $this->watermarkBorder_width, 10, 'D');

//             //bg hitam
//             $this->SetDrawColor(0, 0, 0);
//             $this->Rect($this->watermarkBorder_offsetLeft, 16, $this->watermarkBorder_width, 5, 'D');

//             //Put the watermark
//             $this->SetFont('Times', 'B', 11);
//             // $this->SetTextColor(255, 192, 203);
//             //$this->RotatedText($this->watermarkOffsetLeft, 110, $this->watermarkText, 0);
//             // $this->SetTextColor(220,20,60); //text merah
//             $this->SetTextColor(0, 0, 0); //text hitam
//             $this->RotatedText($this->watermarkOffsetLeft, 20, $this->watermarkText, 0);


//             $this->SetFont('Times', 'B', 40);
//             $this->RotatedText($this->watermarkSubTextOffsetLeft, 130, $this->watermarkSubText, 0);
//         }
//     }

//     function RotatedText($x, $y, $txt, $angle)
//     {
//         //Text rotated around its origin
//         $this->Rotate($angle, $x, $y);
//         $this->Text($x, $y, $txt);
//         $this->Rotate(0);
//     }

//     function Rotate($angle, $x = -1, $y = -1)
//     {
//         if ($x == -1)
//             $x = $this->x;
//         if ($y == -1)
//             $y = $this->y;
//         if ($this->angle != 0)
//             $this->_out('Q');
//         $this->angle = $angle;
//         if ($angle != 0) {
//             $angle *= M_PI / 180;
//             $c = cos($angle);
//             $s = sin($angle);
//             $cx = $x * $this->k;
//             $cy = ($this->h - $y) * $this->k;
//             $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
//         }
//     }
// }
