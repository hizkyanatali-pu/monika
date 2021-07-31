<?php

namespace Modules\Admin\Controllers;

// use Modules\Admin\Models\AksesModel;
use Modules\Admin\Models\PulldataModel;


class Grafikdata extends \App\Controllers\BaseController
{
    public function __construct()
    {
        $this->PulldataModel        = new PulldataModel();
        // $this->akses                = new AksesModel();
    }

    public function index($slug="keuangan")
    {
        $data = array(
            'title' => 'Grafik Progres ' . ($slug==="keuangan"?'Keuangan':'Fisik'),
            'current' => $slug,
            'qdata'=>$this->getdata($slug)
        );
        return view('Modules\Admin\Views\Grafik\Grafikdata', $data);
    }

    function getdata($slug='')
    {
        $q = $this->PulldataModel->getgrafik($slug);
        $Jbln = $this->PulldataModel->bln();
        $data['bln'][]=[0, ''];
        $data['rencana'][]=[0, 0];
        $data['realisasi'][]=[0, 0];
        foreach ($q as $k =>$d){
            for($i=1 ; $i<=count($Jbln) ; $i++){
                $data['bln'][]=[$i, $Jbln[$i-1]];
                $data['rencana'][]=[$i, number_format($d["rencana_$i"],2,'.','.')];
                if($i<= date("m")){
                    $data['realisasi'][]=[$i, number_format($d["realisasi_$i"],2,'.','.')];
                }
            }
        }
        return $data;
    }

    public function progres_per_sumber_dana()
    {
        $data = array(
            'title' => 'Grafik Progres Per Sumber Dana',
        );
        return view('Modules\Admin\Views\Grafik\Progress-per-sumber-dana', $data);
    }

   


}