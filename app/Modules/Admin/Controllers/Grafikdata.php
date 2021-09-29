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
                    if($i != date("m")){
                        $data['realisasi'][]=[$i, number_format($d["realisasi_$i"],2,'.','.')];
                    }else{
                        $date = date("Y-$i-d");
                        $a = date("t", strtotime($date));
                        $day = $a - date("d");
                        if($day != 0){
                            $data['realisasi'][]=[($i-1)+0.5, number_format($d["realisasi_$i"],2,'.','.')];
                            $data_rencana = $d["rencana_$i"]/$a*date("d");
                            $data_grafik = [($i-1)+0.5, number_format($data_rencana,2,'.','.')];
                            array_splice($data['rencana'], $i, 0, [[($i-1)+0.5, number_format($data_rencana,2,'.','.')]]);
                        }else{
                            $data['realisasi'][]=[$i, number_format($d["realisasi_$i"],2,'.','.')];
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function progres_per_sumber_dana()
    {
        $getGraphicData = $this->PulldataModel->getGraphicDataProgressPerSumberDana();

        $data = array(
            'title' => 'Grafik Progres Per Sumber Dana',
            'pagu'  => $getGraphicData
        );
        return view('Modules\Admin\Views\Grafik\Progress-per-sumber-dana', $data);
    }

    public function progres_per_jenis_belanja()
    {
        $getGraphicData = $this->PulldataModel->getGraphicDataProgressPerJenisBelanja();

        $data = array(
            'title' => 'Grafik Progres Per Jenis Belanja',
            'pagu'  => $getGraphicData
        );
        return view('Modules\Admin\Views\Grafik\Progress-per-jenis-belanja', $data);
    }

    public function progres_per_kegiatan()
    {
        $getGraphicData = $this->PulldataModel->getGraphicDataProgressPerKegiatan();

        $data = array(
            'title' => 'Grafik Progres Per Kegiatan',
            'pagu'  => $getGraphicData
        );
        return view('Modules\Admin\Views\Grafik\Progress-per-kegitan', $data);
    }
}