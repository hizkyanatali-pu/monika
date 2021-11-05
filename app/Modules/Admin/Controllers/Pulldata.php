<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
use Modules\Admin\Models\PulldataModel;


class Pulldata extends \App\Controllers\BaseController
{
    public function __construct()
    {
        $this->akses                = new AksesModel();
        $this->PulldataModel        = new PulldataModel();
        $this->InModul = "Pulldata";
        $session = session();
        $this->user = $session->get('userData');
    }

    public function index()
    {
        $data = array(
            'title' => 'Paket kegiatan',
            'qdata' => $this->PulldataModel->getBalaiPaket("balai")
        );
        return view('Modules\Admin\Views\Paket\Paketbalai', $data);
    }

    public function getsatker($slug = '')
    {
        echo json_encode(['qdata' => $this->PulldataModel->getBalaiPaket("satker", "b.balaiid='$slug'")]);
        die();
    }

    function getpaket($slug = null)
    {
        echo json_encode(['qdata' => $this->PulldataModel->getPaket("md.kdsatker='$slug'")]);
        die();
    }

    //format satu-satu
    public function unitkerja($slug = '')
    {
        $data = array(
            'title' => 'Unit Kerja',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket("balai"),
            'rekap' => 'unitkerja',
            'id_report' => 'cetak_ditjen_sda'
        );
        //dd($data);
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_ditjensda(){

        $data = [
            'title' => 'Unit Kerja',
            'qdata' => $this->PulldataModel->getBalaiPaket("balai")
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function pagusda($slug = '')
    {
        $data = array(
            'title' => 'Unit Kerja',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket(),
            'rekap' => 'pagusda'
        );
        dd($data);
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }
    public function satuankerja($balaiid = '', $label = '')
    {
        $data = array(
            'title' => 'Satuan Kerja',
            'posisi' => ['<a href="' . site_url("pulldata/unitkerja") . '"><i class="fa fa-home"></i></a>', $label],
            'idk' => $balaiid,
            'label' => $label,
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket("satker", "b.balaiid='$balaiid'"),
            'rekap' => 'satuankerja'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }
    public function paket($balaiid = '', $satkerid = '',  $balai = '', $satker = '')
    {
        $data = array(
            'title' => 'Paket',
            'idk'   => $balaiid,
            'idks'  => $satkerid,
            'label' => $balai,
            'label2' => $satker,
            'posisi' => ['<a href="' . site_url("pulldata/unitkerja") . '"><i class="fa fa-home"></i></a>', '<a href="' . site_url("pulldata/satuankerja/$balaiid/$balai") . '">' . $balai . '</a>', $satker],
            'qdata' => $this->PulldataModel->getPaket("md.kdsatker='$satkerid'"),
            'rekap' => 'paket'
        );
        return view('Modules\Admin\Views\Paket\Format_3', $data);
    }

    //lain
    public function bbws($slug = '')
    {
        $data = array(
            'title' => 'BBWS',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS'"),
            'rekap' => 'bbws',
            'id_report' => 'cetak_bbws'
        );
        // dd($data);
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_bbws(){

        $data = [
            'title' => 'BBWS',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS'")
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function bws($slug = '')
    {
        $data = array(
            'title' => 'BWS/PUSAT/SKPD TPOP',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BWS'"),
            'rekap' => 'bws',
            'id_report' => 'cetak_bws'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_bws(){

        $data = [
            'title' => 'BWS/PUSAT/SKPD TPOP',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BWS'"),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function satkerpusat($slug = '')
    {
        $data = array(
            'title' => 'Satker Pusat',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 99,
            'label' => 'Satker Pusat',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='99'"),
            'rekap' => 'satkerpusat',
            'id_report' => 'cetak_satker_pusat'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_satkerpusat(){

        $data = [
            'title' => 'Satker Pusat',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='99'"),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function balaiteknik($slug = '')
    {
        $data = array(
            'title' => 'Balai Teknik',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 97,
            'label' => 'Balai Teknik',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='97'"),
            'rekap' => 'balaiteknik',
            'id_report' => 'cetak_balai_teknik'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_balaiteknik(){

        $data = [
            'title' => 'Balai Teknik',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='97'"),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function skpdtpop()
    {
        $data = array(
            'title' => 'SKPD TP OP',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 98,
            'label' => 'SKPD TP OP',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='98'"),
            'rekap' => 'skpdtpop',
            'id_report' => 'cetak_skpd_tp_op'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_skpdtpop(){

        $data = [
            'title' => 'SKPD TP OP',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='98'"),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function satkerpagu100m()
    {
        $data = array(
            'title' => 'Satker Pagu > 100 M',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 98,
            'label' => 'Satker',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker100m', "md.jml_pagu_total>100000000000"),
            'rekap' => 'satkerpagu100m',
            'id_report' => 'cetak_satker_pagu_100m'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_satkerpagu100m(){

        $data = [
            'title' => 'Satker Pagu > 100 M',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker100m', "md.jml_pagu_total>100000000000"),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }


    // semua satker

    public function semua_satker($slug = '')
    {
        $kdgiat = ($slug == 'all' ? "":"md.kdgiat={$slug}");
        $data = array(
            'title' => 'Semua Satker',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 'all',
            'label' => 'Semua Satker',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket("satker",$kdgiat),
            'kegiatan' => $this->PulldataModel->getKegiatan(),
            'slug' => $slug, 
            'rekap' => 'semuasatker',
            'id_report' => 'cetak_semua_satker'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_semua_satker(){

        $data = [
            'title' => 'Cetak Semua Satker',
            'qdata' => $this->PulldataModel->getBalaiPaket("satker")
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }


    //pindah ya!
    // function simpandata(){
    //     $this->akses->goakses('add', $this->InModul);

    //     $i=0;
    //     $block =1024*1024;//1MB or counld be any higher than HDD block_size*2
    //     if ($fh = fopen(WRITEPATH . "emon/emon_01.json", "r")) {
    //         $left='';
    //         while (!feof($fh)) {// read the file
    //         $temp = fread($fh, $block);
    //         $fgetslines = explode("\n",$temp);
    //         $fgetslines[0]=$left.$fgetslines[0];
    //         if(!feof($fh) )$left = array_pop($fgetslines);
    //         foreach ($fgetslines as $k => $line) {
    //             $v=str_replace(array('[', ']', '},'), array('', '', '}'), $line);
    //             if($v!=""){
    //                 $this->PulldataModel->replace(json_decode("[$v]", true)[0]);
    //                 $i++;
    //             }
    //         }
    //         }
    //     }
    //     fclose($fh);

    //     return json_encode(['datatersimpan'=>$i]);
    // }

    function simpandata()
    {
        // $this->akses->goakses('add', $this->InModul);

        //$dAr=json_decode('["tahun":"2020","kdsatker":"020104","kdprogram":"10","kdgiat":"5300","kdoutput":"022","kdsoutput":"001","kdkmpnen":"071","kdpaket":"06.020104.10.5300.022.001.071.A","kdls":"","nmpaket":"Operasi Rutin Jaringan Irigasi Air Tanah Kab. Cirebon;Tersebar;Jawa Barat;2 km;30 Hektar;F;S;SYC","pagu_51":"0","pagu_52":"2024000","pagu_53":"0","pagu_rpm":"2024000","pagu_sbsn":"0","pagu_phln":"0","pagu_total":"2024000","real_51":"0","real_52":"2024000","real_53":"0","real_rpm":"2024000","real_sbsn":"0","real_phln":"0","real_total":"2024000","progres_keuangan":"100","progres_fisik":"100","progres_keu_jan":"0","progres_keu_feb":"0","progres_keu_mar":"0","progres_keu_apr":"100","progres_keu_mei":"100","progres_keu_jun":"100","progres_keu_jul":"100","progres_keu_agu":"100","progres_keu_sep":"100","progres_keu_okt":"100","progres_keu_nov":"100","progres_keu_des":"100","progres_fisik_jan":"0","progres_fisik_feb":"0","progres_fisik_mar":"0","progres_fisik_apr":"100","progres_fisik_mei":"100","progres_fisik_jun":"100","progres_fisik_jul":"100","progres_fisik_agu":"100","progres_fisik_sep":"100","progres_fisik_okt":"100","progres_fisik_nov":"100","progres_fisik_des":"100","ren_keu_jan":"0","ren_keu_feb":"0","ren_keu_mar":"20","ren_keu_apr":"20","ren_keu_mei":"40","ren_keu_jun":"40","ren_keu_jul":"60","ren_keu_agu":"60","ren_keu_sep":"80","ren_keu_okt":"80","ren_keu_nov":"100","ren_keu_des":"100","ren_fis_jan":"0","ren_fis_feb":"0","ren_fis_mar":"20","ren_fis_apr":"20","ren_fis_mei":"40","ren_fis_jun":"40","ren_fis_jul":"60","ren_fis_agu":"60","ren_fis_sep":"80","ren_fis_okt":"80","ren_fis_nov":"100","ren_fis_des":"100"]', true);
        //d($dAr);
        // $d =["tahun"=>"2020","kdsatker"=>"020104","kdprogram"=>"10","kdgiat"=>"5300","kdoutput"=>"022","kdsoutput"=>"001","kdkmpnen"=>"071","kdpaket"=>"06.020104.10.5300.022.001.071.A","kdls"=>"","nmpaket"=>"Operasi Rutin Jaringan Irigasi Air Tanah Kab. Cirebon;Tersebar;Jawa Barat;2 km;30 Hektar;F;S;SYC","pagu_51"=>"0","pagu_52"=>"2024000","pagu_53"=>"0","pagu_rpm"=>"2024000","pagu_sbsn"=>"0","pagu_phln"=>"0","pagu_total"=>"2024000","real_51"=>"0","real_52"=>"2024000","real_53"=>"0","real_rpm"=>"2024000","real_sbsn"=>"0","real_phln"=>"0","real_total"=>"2024000","progres_keuangan"=>"100","progres_fisik"=>"100","progres_keu_jan"=>"0","progres_keu_feb"=>"0","progres_keu_mar"=>"0","progres_keu_apr"=>"100","progres_keu_mei"=>"100","progres_keu_jun"=>"100","progres_keu_jul"=>"100","progres_keu_agu"=>"100","progres_keu_sep"=>"100","progres_keu_okt"=>"100","progres_keu_nov"=>"100","progres_keu_des"=>"100","progres_fisik_jan"=>"0","progres_fisik_feb"=>"0","progres_fisik_mar"=>"0","progres_fisik_apr"=>"100","progres_fisik_mei"=>"100","progres_fisik_jun"=>"100","progres_fisik_jul"=>"100","progres_fisik_agu"=>"100","progres_fisik_sep"=>"100","progres_fisik_okt"=>"100","progres_fisik_nov"=>"100","progres_fisik_des"=>"100","ren_keu_jan"=>"0","ren_keu_feb"=>"0","ren_keu_mar"=>"20","ren_keu_apr"=>"20","ren_keu_mei"=>"40","ren_keu_jun"=>"40","ren_keu_jul"=>"60","ren_keu_agu"=>"60","ren_keu_sep"=>"80","ren_keu_okt"=>"80","ren_keu_nov"=>"100","ren_keu_des"=>"100","ren_fis_jan"=>"0","ren_fis_feb"=>"0","ren_fis_mar"=>"20","ren_fis_apr"=>"20","ren_fis_mei"=>"40","ren_fis_jun"=>"40","ren_fis_jul"=>"60","ren_fis_agu"=>"60","ren_fis_sep"=>"80","ren_fis_okt"=>"80","ren_fis_nov"=>"100","ren_fis_des"=>"100"];
        // $h = $this->PulldataModel->insert($d);
        // $h = $this->PulldataModel->replace($d);
        // dd($h);

        $i = 0;
        $block = 1024 * 1024; //1MB or counld be any higher than HDD block_size*2
        if ($fh = fopen(WRITEPATH . "emon/20200827_0017.txt", "r")) {
            $left = '';
            while (!feof($fh)) { // read the file
                $temp = fread($fh, $block);

                $temp = str_replace(array("[", "]", "}{"), array("", "", "},{"), $temp);
                // d($temp);

                $fgetslines = explode("},", $temp);
                $fgetslines[0] = $left . $fgetslines[0];
                if (!feof($fh)) $left = array_pop($fgetslines);
                // d($fgetslines);

                $data = "";
                // d($fgetslines);
                foreach ($fgetslines as $k => $line) {
                    // $v=str_replace(array('[', ']', '},'), array('', '', '}'), $line);
                    // if($v!=""){
                    //     $this->PulldataModel->replace(json_decode("[$v]", true)[0]);
                    //     $i++;
                    // }
                    if ($line != "") {
                        // $data.=$line . "}" . ($k < (count($fgetslines)-1) ? ",":"");

                        $d = $line . "}";
                        //dd(json_decode("[$d]", true));
                        $d = json_decode("[$d]", true);
                        // d($dAr);
                        // if(count($dAr)>0) $this->PulldataModel->save($dAr[0]);

                        if ($this->PulldataModel->replace($d[0])) $i++;
                    }
                }
                // d($k);
                // d($data);
                // $data = json_decode("[$data]", true);
                // $this->PulldataModel->save($data);
                // dd($data);


            }
        }
        fclose($fh);

        return json_encode(['datatersimpan' => $i]);
    }

    //rekap
    public function rekap($pg)
    {
        $session = session();
        if (empty($_GET['idks'])) {
            $_GET['idks'] = '';
        }

        $pgview = "Rekap-1";
        if ($pg == "paket") $pgview = "Rekap-2";


        $hal['unitkerja']       = ['pg' => 'unitkerja', 'idk' => '', 'label' => '', 'filter' => 'balai', 'where' => null, 'title' => 'Unit Kerja'];
        $hal['bbws']            = ['pg' => 'bbws', 'idk' => '', 'label' => '', 'filter' => 'balai', 'where' => "b.st like 'BBWS'", 'title' => 'BBWS'];
        $hal['bws']             = ['pg' => 'bws', 'idk' => '', 'label' => '', 'filter' => 'balai', 'where' => "b.st like 'BWS'", 'title' => 'BWS'];
        $hal['satkerpusat']     = ['pg' => 'satkerpusat', 'idk' => '', 'label' => '', 'filter' => 'satker', 'where' => "b.balaiid='99'", 'title' => 'Satker Pusat'];
        $hal['satker']          = ['pg' => 'satker', 'idk' => '', 'label' => '', 'filter' => 'satker', 'where' => "b.balaiid!='99'", 'title' => 'Satuan Kerja'];
        $hal['skpdtpop']        = ['pg' => 'skpdtpop', 'idk' => '', 'label' => '', 'filter' => 'satker', 'where' => "b.balaiid='98'", 'title' => 'SKPD TP OP'];
        $hal['satkerpagu100m']  = ['pg' => 'satkerpagu100m', 'idk' => '', 'label' => '', 'filter' => 'satker100m', 'where' => "md.jml_pagu_total>100000000000", 'title' => 'Satker Pagu > 100 M'];
        $hal['satuankerja']     = ['pg' => 'satuankerja', 'idk' => $_GET['idk'], 'label' => $_GET['label'], 'filter' => 'satker', 'where' => "b.balaiid='{$_GET['idk']}'", 'title' => 'Satuan Kerja'];

        //$balaiid='',$satkerid='',  $balai='', $satker=''
        $hal['paket']     = ['pg' => 'paket', 'idk' => $_GET['idk'], 'label' => $_GET['label'], 'label2' => (!empty($_GET['label2']) ? $_GET['label2'] : ''), 'format' => (!empty($_GET['format']) ? $_GET['format'] : ''), 'filter' => 'satker', 'where' => "md.kdsatker='{$_GET['idks']}'", 'title' => 'Paket'];
        $hal['balaiteknik']     = ['pg' => 'balaiteknik', 'idk' => $_GET['idk'], 'label' => $_GET['label'], 'filter' => 'satker', 'where' => "b.balaiid='{$_GET['idk']}'", 'title' => 'Balai Teknik'];
        $hal['semuasatker']     = ['pg' => 'balaiteknik', 'idk' => '', 'label' => $_GET['label'], 'filter' => 'satker', 'where' => "", 'title' => 'Semua Satker'];

        foreach ($hal as $key => $value) {
            $hall[] = $key;
        }

        if ($pg == "paket") {
            if (in_array($_GET['rekap'], array("satkerpagu100m"))) {
                return redirect()->to('/pulldata/nopage/' . $pg);
            }
            if (!in_array($_GET['idks'], array("satkerpagu100m")) && in_array($_GET['rekap'], $hall)) {
                $hal['paket']['where'] = $hal[$_GET['rekap']]['where'];
            } elseif ($session->get('userData')['group_id'] == "Administrator" && $_GET['idks'] == 'all') {
                $hal['paket']['where'] = '';
            } elseif ($_GET['idks'] == $_GET['idk']) {
                $hal['paket']['where'] = "b.balaiid='{$_GET['idk']}'";
            }
            // else{
            //     $hal['paket']['where']=$hal[$_GET['idks']]['where'];
            // }

            if ($hal['paket']['format'] == 'db') {
                $pgview = "Rekap-3";
            }
        }
        // d($pgview);
        // dd($hal);

        // foreach ($hal as $key => $value) {$hall[]=$key;}
        if (!in_array($pg, $hall)) {
            return redirect()->to('/pulldata/nopage/' . $pg);
        }

        $fileName = "rekapMonika-" . $hal[$pg]['title'] . "-" . date('Ymdhis');
        $data = $hal[$pg];
        $data['qdata'] = ($pg == "paket" ? $this->PulldataModel->getPaket($hal[$pg]['where']) : ($hal[$pg]['where'] == null ? $this->PulldataModel->getBalaiPaket($hal[$pg]['filter']) : $this->PulldataModel->getBalaiPaket($hal[$pg]['filter'], $hal[$pg]['where'])));
        // dd($data);

        // $data = array(
        //     'title' => $hal[$pg]['title'],
        //     //'posisi'=> ['<i class="fa fa-home"></i>'],
        //     'idk'   => $hal[$pg]['idk'],
        //     'label' => $hal[$pg]['label'],
        //     'qdata' =>
        //     ($pg=="paket" ? $this->PulldataModel->getPaket($hal[$pg]['where']) :
        //     ($hal[$pg]['where']==null ? $this->PulldataModel->getBalaiPaket($hal[$pg]['filter']) : $this->PulldataModel->getBalaiPaket($hal[$pg]['filter'], $hal[$pg]['where']))
        //     )
        // );

        // $data = array(
        //     'title' => 'Paket',
        //     'posisi'=>['<a href="'.site_url("pulldata/unitkerja").'"><i class="fa fa-home"></i></a>','<a href="'.site_url("pulldata/satuankerja/$balaiid/$balai").'">'.$balai.'</a>', $satker],
        //     'qdata'=> $this->PulldataModel->getPaket("md.kdsatker='$satkerid'")
        // );
        // return view('Modules\Admin\Views\Paket\Format_3', $data);

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $fileName . ".xls");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        return view("Modules\Admin\Views\Paket\Rekap\\$pgview", $data);
    }
}
