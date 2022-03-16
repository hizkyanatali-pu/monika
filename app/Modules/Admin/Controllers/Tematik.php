<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\TematikModel;

class Tematik extends \App\Controllers\BaseController
{
    private $renderFolder = 'Modules\Admin\Views\Tematik';



    public function __construct()
    {
        $this->TematikModel = new TematikModel();
    }



    public function pageFoodEstate()
    {
        $data = $this->TematikModel->getListTematik('T060019');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'Food Estate',
            'filterTitle'   => 'Food Estate TA',
            'exportCode'    => 'food_estate',
            'data'          => $data,
            'id_report_pdf' => 'cetak_food_estate'
        ]);
    }

    public function cetakFoodEstate()
    {

        $data = [
            'title' => 'Food Estate',
            'data'  => $this->TematikModel->getListTematik('T060019')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageKawasanIndustri()
    {
        $data = $this->TematikModel->getListTematik('TMKEM0005');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'Kawasan Industri',
            'filterTitle'   => 'Dukungan Kawasan Industri di Lingkungan Ditjen SDA TA',
            'exportCode'    => 'kawasan_industri',
            'data'          => $data,
            'id_report_pdf' => 'cetak_kawasan_industri'
        ]);
    }

    public function cetakKawasanIndustri()
    {

        $data = [
            'title' => 'Kawasan Industri',
            'data'  => $this->TematikModel->getListTematik('TMKEM0005')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageKspn($kspCode)
    {
        $kspnTitle = $this->kspnFilterTitle($kspCode);
        $data = $this->TematikModel->getListTematikKspn($kspCode);

        return view($this->renderFolder . '\Kspn', [
            'title'         => 'KSPN',
            'uri'           => current_url(true),
            'filterTitle'   => $kspnTitle['filterTitle'],
            'data'          => $data,
            'id_report_pdf' => 'cetak_kspn'
        ]);
    }

    public function cetakKspn($kspCode)
    {

        $data = [
            'title' => 'KSPN',
            'data'  => $this->TematikModel->getListTematikKspn($kspCode),
            'id_report_pdf' => 'cetak_kspn'
        ];

        return view($this->renderFolder . "\Cetak\Kspn-pdf", $data);
    }


    public function pageG20()
    {
        $data = $this->TematikModel->getListTematik('T060020');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'G20',
            'filterTitle'   => 'G20 TA',
            'exportCode'    => 'g20',
            'data'          => $data,
            'id_report_pdf' => 'cetak_g20'
        ]);
    }


    public function cetakG20()
    {
        $data = [
            'title' => 'G20',
            'data'  => $this->TematikModel->getListTematik('T060020')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageIkn()
    {
        $data = $this->TematikModel->getListTematik('TXX0011');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'Ibu Kota Negara (IKN)',
            'filterTitle'   => 'IKN TA',
            'exportCode'    => 'ikn',
            'data'          => $data,
            'id_report_pdf' => 'cetak_ikn'
        ]);
    }


    public function cetakIkn()
    {
        $data = [
            'title' => 'Ibu Kota Negara (IKN)',
            'data'  => $this->TematikModel->getListTematik('TXX0011')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageRekap()
    {
        $grupData = $this->rekapGroupData();
        $data = $this->TematikModel->getListRekap($grupData);

        return view($this->renderFolder . '\Rekap', [
            'title'         => 'Rekap',
            'data'          => $data,
            'id_report_pdf' => 'cetak_rekap'
        ]);
    }

    public function cetakRekap()
    {

        $data = [
            'title' => 'Rekap',
            'data'  => $this->TematikModel->getListRekap($this->rekapGroupData())
        ];

        return view($this->renderFolder . "\Cetak\Rekap-pdf", $data);
    }


    public function exportExcel($tematikType)
    {
        if ($tematikType == 'food_estate') {
            $tematikCode = "T060019";
            $title = "Food Estate";
            $filterTitle = "Food Estate TA";
        }
        elseif ($tematikType == 'g20') {
            $tematikCode = "T060020";
            $title = "G20";
            $filterTitle = "G20 TA";
        } 
        elseif ($tematikType == 'ikn') {
            $tematikCode = "TXX0011";
            $title = "Ibu Kota Negara (IKN)";
            $filterTitle = "IKN TA";
        } 
        else {
            $tematikCode = "TMKEM0005";
            $title = "Kawasan Industri";
            $filterTitle = "Dukungan Kawasan Industri di Lingkungan Ditjen SDA TA";
        }

        $data = $this->TematikModel->getListTematik($tematikCode);
        return view($this->renderFolder . '\Cetak\Tematik-excel', [
            'title'         => $title,
            'filterTitle'   => $filterTitle,
            'data'          => $data
        ]);
    }



    public function exportExcelKspn($kspnCode)
    {
        $kspnTitle = $this->kspnFilterTitle($kspnCode);
        $data = $this->TematikModel->getListTematikKspn($kspnCode);
        return view($this->renderFolder . '\Cetak\Kspn-excel', [
            'title'         => $kspnTitle['title'],
            'filterTitle'   => $kspnTitle['filterTitle'],
            'data'          => $data
        ]);
    }



    public function exportExcelRekap()
    {
        $grupData = $this->rekapGroupData();
        $data = $this->TematikModel->getListRekap($grupData);

        return view($this->renderFolder . '\Cetak\Rekap-excel', [
            'data'          => $data
        ]);
    }



    private function kspnFilterTitle($kspnCode)
    {
        switch ($kspnCode):
            case 'kspn01':
                $title = 'Danau Toba';
                $filterTitle = 'Danau Toba TA';
                break;

            case 'kspn02':
                $title = 'Borobudur';
                $filterTitle = 'Borobudur TA';
                break;

            case 'kspn03':
                $title = 'Mandalika';
                $filterTitle = 'Mandalika TA';
                break;

            case 'kspn04':
                $title = 'Labuan Bajo';
                $filterTitle = 'Labuan Bajo TA';
                break;

            case 'kspn05':
                $title = 'Manado';
                $filterTitle = 'Manado - Bitung - Likupang TA';
                break;

            case 'kspn06':
                $title = 'Tanjung Kelayang ';
                $filterTitle = 'Tanjung Kelayang  TA';
                break;

            case 'kspn08':
                $title = 'Wakatobi';
                $filterTitle = 'Wakatobi TA';
                break;

            case 'kspn09':
                $title = 'Morotai';
                $filterTitle = 'Morotai TA';
                break;

            default:
                $title = '';
                $filterTitle = '';
                break;
        endswitch;
        return [
            'title'         =>  $title,
            'filterTitle'   =>  $filterTitle,
        ];
    }



    private function rekapGroupData()
    {
        return [
            [
                'title' => 'Food Estate',
                'tematikCode' => ["'T060019'"]
            ],
            [
                'title' => 'Kawasan Industri',
                'tematikCode' => ["'TMKEM0005'"]
            ],
            [
                'title' => 'KSPN',
                'tematikCode' => ["'kspn01'", "'kspn02'", "'kspn03'", "'kspn04'", "'kspn05'", "'kspn06'", "'kspn08'", "'kspn09'"]
            ],
            [
                'title' => 'G20',
                'tematikCode' => ["'T060020'"]
            ],
            [
                'title' => 'Ibu Kota Negara (IKN)',
                'tematikCode' => ["'TXX0011'"]
            ]
        ];
    }
}
