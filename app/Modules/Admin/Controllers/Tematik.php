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



    public function pageFoodEstate() {
        $data = $this->TematikModel->getListTematik('TXX0003');
    	return view($this->renderFolder.'\Tematik-view', [
            'title'         => 'Food Estate',
            'filterTitle'   => 'Food Estate TA',
            'exportCode'    => 'food_estate',
            'data'          => $data,
            'id_report_pdf' => 'cetak_food_estate'
        ]);
    }

    public function cetakFoodEstate(){

        $data = [
            'title' => 'Food Estate',
            'data'  => $this->TematikModel->getListTematik('TXX0003')
        ];

        return view($this->renderFolder."\Cetak\Tematik-pdf", $data);
    }


    public function pageKawasanIndustri() {
        $data = $this->TematikModel->getListTematik('T060012');
        return view($this->renderFolder.'\Tematik-view', [
            'title'         => 'Kawasan Industri',
            'filterTitle'   => 'Dukungan Kawasan Industri di Lingkungan Ditjen SDA TA',
            'exportCode'    => 'kawasan_industri',
            'data'          => $data,
            'id_report_pdf' => 'cetak_kawasan_industri'
        ]);
    }

    public function cetakKawasanIndustri(){

        $data = [
            'title' => 'Kawasan Industri',
            'data'  => $this->TematikModel->getListTematik('T060012')
        ];

        return view($this->renderFolder."\Cetak\Tematik-pdf", $data);
    }


    public function pageKspn($kspCode) {
        $kspnTitle = $this->kspnFilterTitle($kspCode);
        $data = $this->TematikModel->getListTematikKspn($kspCode);

    	return view($this->renderFolder.'\Kspn', [
            'title'         => 'KSPN',
            'uri'           => current_url(true),
            'filterTitle'   => $kspnTitle['filterTitle'],
            'data'          => $data,
            'id_report_pdf' => 'cetak_kspn'
        ]);
    }

    public function cetakKspn($kspCode){

        $data = [
            'title' => 'KSPN',
            'data'  => $this->TematikModel->getListTematikKspn($kspCode),
            'id_report_pdf' => 'cetak_kspn'
        ];

        return view($this->renderFolder."\Cetak\Kspn-pdf", $data);
    }


    public function pageRekap() {
        $grupData = $this->rekapGroupData();
        $data = $this->TematikModel->getListRekap($grupData);

    	return view($this->renderFolder.'\Rekap', [
            'title'         => 'Rekap',
            'data' => $data,
            'id_report_pdf' => 'cetak_rekap'
        ]);
    }

    public function cetakRekap(){

        $data = [
            'title' => 'Rekap',
            'data'  => $this->TematikModel->getListRekap($this->rekapGroupData())
        ];

        return view($this->renderFolder."\Cetak\Rekap-pdf", $data);
    }


    public function exportExcel($tematikType) {
        if ($tematikType == 'food_estate') {
            $tematikCode = "TXX0003";
            $title = "Food Estate";
            $filterTitle = "Food Estate TA";
        }
        else {
            $tematikCode = "T060012";
            $title = "Kawasan Industri";
            $filterTitle = "Dukungan Kawasan Industri di Lingkungan Ditjen SDA TA";
        }

        $data = $this->TematikModel->getListTematik($tematikCode);
        return view($this->renderFolder.'\Cetak\Tematik-excel', [
            'title'         => $title,
            'filterTitle'   => $filterTitle,
            'data'          => $data
        ]);
    }



    public function exportExcelKspn($kspnCode) {
        $kspnTitle = $this->kspnFilterTitle($kspnCode);
        $data = $this->TematikModel->getListTematikKspn($kspnCode);
        return view($this->renderFolder.'\Cetak\Kspn-excel', [
            'title'         => $kspnTitle['title'],
            'filterTitle'   => $kspnTitle['filterTitle'],
            'data'          => $data
        ]);
    }



    public function exportExcelRekap() {
        $grupData = $this->rekapGroupData();
        $data = $this->TematikModel->getListRekap($grupData);

        return view($this->renderFolder.'\Cetak\Rekap-excel', [
            'data'          => $data
        ]);
    }



    private function kspnFilterTitle($kspnCode) {
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



    private function rekapGroupData() {
        return [
            [
                'title' => 'Food Estate',
                'tematikCode' => ["'TXX0003'"]
            ],
            [
                'title' => 'Kawasan Industri',
                'tematikCode' => ["'T060012'"]
            ],
            [
                'title' => 'KSPN',
                'tematikCode' => ["'kspn01'", "'kspn02'", "'kspn03'", "'kspn04'", "'kspn05'"]
            ]
        ];
    }
}