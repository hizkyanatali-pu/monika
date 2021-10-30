<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
use Modules\Admin\Models\KinerjaOutputBulananModel;
use Config\Services;


class KinerjaOutputBulanan extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
    }

    public function index($param = '')
    {
        $model = new KinerjaOutputBulananModel();
        
        $bulan = decrypt_url($param);
        if ($bulan == '') {
            $bulan = date('n');
        }
        if (empty($_GET['exp'])) {
            $_GET['exp'] = '';
        }

        $filterkdgiat = "'2408', '5036', '5037', '5039', '5040'";
        $filterkdoutput = "'CBG','CBS','RBG','RBS'";
        $qpaket = $this->db->query("SELECT
        pkt.kdprogram,pkt.kdgiat,pkt.kdoutput,pkt.kdsoutput,pkt.kdkmpnen,tp.nmprogram,tgiat.nmgiat,toutput.nmoutput,toutput.sat,
        pkt.pg,CAST(REPLACE(pkt.vol,',','.') AS DECIMAL) vol,pkt.kdprogram || '.' || pkt.kdgiat || '.' || pkt.kdoutput || '.' || pkt.kdsoutput || pkt.kdkmpnen AS kode
        ,CASE
        WHEN pkt.rtot is null THEN 0
        WHEN pkt.rtot = '' THEN 0
        ELSE pkt.rtot
        END as rtot
        ,CASE
        WHEN pkt.rr_b$bulan is null THEN 0
        WHEN pkt.rr_b$bulan = '' THEN 0
        ELSE pkt.rr_b$bulan
        END as rr_b
        ,CASE
        WHEN pkt.renk_b$bulan is null THEN 0
        WHEN pkt.renk_b$bulan = '' THEN 0
        ELSE pkt.renk_b$bulan
        END as renk_b
        ,CASE
        WHEN pkt.renf_b$bulan is null THEN 0
        WHEN pkt.renf_b$bulan = '' THEN 0
        ELSE pkt.renf_b$bulan
        END as renf_b
        ,CASE
        WHEN pkt.ff_b$bulan is null THEN 0
        WHEN pkt.ff_b$bulan = '' THEN 0
        ELSE pkt.ff_b$bulan
        END as ff_b
        ,CASE
        WHEN pkt.ufis is null THEN 0
        WHEN pkt.ufis = '' THEN 0
        ELSE pkt.ufis
        END as ufis
        --,d_soutput.ursoutput
        FROM paket pkt 
        LEFT JOIN tprogram tp ON tp.kdprogram = pkt.kdprogram  
        LEFT JOIN tgiat ON tgiat.kdgiat = pkt.kdgiat  
        LEFT JOIN toutput ON pkt.kdgiat = toutput.kdgiat AND pkt.kdoutput = toutput.kdoutput 
        -- LEFT JOIN d_soutput ON pkt.kdprogram = d_soutput.kdprogram AND pkt.kdgiat = d_soutput.kdgiat AND pkt.kdoutput = d_soutput.kdoutput AND pkt.kdsoutput = d_soutput.kdsoutput  
        WHERE pkt.kdprogram = 'FC' AND pkt.kdgiat IN ($filterkdgiat) AND pkt.kdoutput IN ($filterkdoutput) AND pkt.kdkmpnen = '074' ORDER BY pkt.kdgiat,pkt.kdoutput,pkt.kdsoutput")->getResultArray();

        $data = array(
            'title' => 'Kinerja Output Bulanan',
            // 'idk'   => $balaiid,
            // 'idks'  => $satkerid,
            // 'label' => $balai,
            // 'label2' => $satker,
            // 'posisi' => ['<a href="' . site_url("pulldata/unitkerja") . '"><i class="fa fa-home"></i></a>', '<a href="' . site_url("pulldata/satuankerja/$balaiid/$balai") . '">' . $balai . '</a>', $satker],
            // 'qdata' => $this->PulldataModel->getPaket("md.kdsatker='$satkerid'"),
            'qdata' => $qpaket,
            'dataKegiatan' => $model->getDataKegiatan()->getResult(),
            'rekap' => 'paket',
            // 'dbuse' => $this->db
        );
        if ($_GET['exp'] == 'xlxs') {

            header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: attachment; filename=" . "Kinerja-Output-Bulanan" . date('d_m_y_His') . ".xls");
            header("Pragma: no-cache");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");
            return view('Modules\Admin\Views\KinerjaOutputBulanan\Cetak\Cetak-KOB-1.php', $data);
        } else {
            return view('Modules\Admin\Views\KinerjaOutputBulanan\FormatKOB_1.php', $data);
        }
    }

    public function getOutput(){
        
        $request = Services::request();
        $model = new KinerjaOutputBulananModel($request);
        $post = $request->getPost();

        $query = $model->getDataOutput($post['kode_kegiatan'])->getResultArray();
        $data = [];

        foreach($query as $key => $res){

            $data[] = [
                'kode' => $res['kode'],
                'nama' => $res['nama']
            ];
        }

        echo json_encode($data);
    }

    public function getSOutput(){
        
        $request = Services::request();
        $model = new KinerjaOutputBulananModel($request);
        $post = $request->getPost();

        $query = $model->getDataSOutput($post)->getResultArray();
        $data = [];

        foreach($query as $key => $res){

            $data[] = [
                'kode' => $res['kode'],
                'nama' => $res['nama']
            ];
        }

        echo json_encode($data);
    }

    public function getData(){
        
        error_reporting(0);
        // ini_set('memory_limit', -1);
        $request = Services::request();
        $model = new KinerjaOutputBulananModel($request);

        if($request->getMethod(true) === 'POST'){
            
            $post = $request->getPost();
            $kodeKegiatan = '';
            $kodeOutput = '';

            if($post['inputs']['kode_kegiatan'] != null){

                $kodeKegiatan = sprintf("'%s'", implode("','", $post['inputs']['kode_kegiatan']));
            }
            if($post['inputs']['kode_output'] != null){

                $kodeOutput = sprintf("'%s'", implode("','", $post['inputs']['kode_output']));
            }
            
            $qdata = $model->data($post, $kodeKegiatan, $kodeOutput);
            //backup dev
            // echo"<pre>";print_r($this->db->getLastQuery());exit;
            // echo"<pre>";print_r($post);exit;
            $idp = [];
            $idg = [];
            $ido = [];
            $idso = [];
            $idkk = [];
            $noprogram = 1;
            $nogiat = 1;
            $nooutput = 1;
            $nosoutput = 1;
            $nArraysoutput =  unique_multidim_array($qdata['result'], "kode", "kode", "vol", "pg", "rtot", "rr_b", "renk_b", "renf_b", "ff_b", 'jumlah_data', 'ufis');
            $bulanberjalan = decrypt_url($post['inputs']['bulan']);
            $bulansekarang = date('n');

            $list = [];
            foreach($nArraysoutput as $d){

                $row = [];
                $idk = $d['kdprogram'];
                $jumlah_data = isset($d['jumlah_data']) ? $d['jumlah_data'] : 1;

                if (!in_array($idk, $idp)){

                    $pg_program = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : $d['kdoutput']), 'programpg', $post['inputs']['kode_komponen']);
                    $realisasi_program = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'programrealisasi', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                    $keu_rn_program = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'programkeu_rn', $post['inputs']['kode_komponen'], '0', 'pkt.renk_b' . $bulanberjalan) / $pg_program * 100;
                    $keu_rl_program = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'programkeu_rl', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                    $fis_rn_program = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'programfis_rn', $post['inputs']['kode_komponen'], 'pkt.renf_b' . $bulanberjalan);
                    $fis_rl_program =  gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'programfis_rl', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.ufis'  : 'pkt.ff_b' . $bulanberjalan));
                    
                    $row['class'] = 'tdprogram';
                    $row['no'] = $noprogram++;
                    $row['kode'] = $idk;
                    $row['nama_program'] = $d['nmprogram'];
                    $row['target'] = str_replace('.', ',', round(gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'programvol', $post['inputs']['kode_komponen']), 2));
                    $row['satuan'] = 'Paket Program';
                    $row['pagu'] = number_format($pg_program / 1000, 0, ',', '.');
                    $row['realisasi'] = number_format($realisasi_program / 1000, 0, ',', '.');
                    $row['keuangan_rn'] = str_replace('.', ',', round($keu_rn_program, 2));
                    $row['keuangan_rl'] = str_replace('.', ',', round($keu_rl_program, 2));
                    $row['fisik_rn'] = str_replace('.', ',', round($fis_rn_program, 2));
                    $row['fisik_rl'] = str_replace('.', ',', round($fis_rl_program, 2));
                    $row['fisik_kinerja'] = ($fis_rn_program != 0 ?  str_replace('.', ',', round($fis_rl_program / $fis_rn_program, 2)) : '~');

                    $idp = array_merge([$idk], $idp);
                    $list[] = $row;
                }

                $idk = $d['kdgiat'];
                if (!in_array($idk, $ido)){

                    $pg_giat = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'kegiatanpg', $post['inputs']['kode_komponen']);
                    $realisasi_giat = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'kegiatanrealisasi', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                    $keu_rn_giat = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'kegiatankeu_rn', $post['inputs']['kode_komponen'], 'pkt.renk_b' . $bulanberjalan);
                    $keu_rl_giat = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'kegiatankeu_rl', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                    $fis_rn_giat = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'kegiatanfis_rn', $post['inputs']['kode_komponen'], 'pkt.renf_b' . $bulanberjalan);
                    $fis_rl_giat =  gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'kegiatanfis_rl', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.ufis'  : 'pkt.ff_b' . $bulanberjalan));

                    $row['class'] = 'tdgiat';
                    $row['no'] = $nogiat++;
                    $row['kode'] = $idk;
                    $row['nama_program'] = $d['nmgiat'];
                    $row['target'] = str_replace('.', ',', round(gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'kegiatanvol', $post['inputs']['kode_komponen']), 2));
                    $row['satuan'] = 'Paket Kegiatan';
                    $row['pagu'] = number_format($pg_giat / 1000, 0, ',', '.');
                    $row['realisasi'] = number_format($realisasi_giat / 1000, 0, ',', '.');
                    $row['keuangan_rn'] = str_replace('.', ',', round($keu_rn_giat, 2));
                    $row['keuangan_rl'] = str_replace('.', ',', round($keu_rl_giat, 2));
                    $row['fisik_rn'] = str_replace('.', ',', round($fis_rn_giat, 2));
                    $row['fisik_rl'] = str_replace('.', ',', round($fis_rl_giat, 2));
                    $row['fisik_kinerja'] = ($fis_rn_giat != 0 ?  str_replace('.', ',', round($fis_rl_giat / $fis_rn_giat, 2)) : '~');

                    $ido = array_merge([$idk], $ido);
                    $list[] = $row;
                }

                $idk = $idk . '.' . $d['kdoutput'];
                if (!in_array($idk, $ido)){

                    $pg_output = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'outputpg', $post['inputs']['kode_komponen']);
                    $realisasi_output = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'outputrealisasi', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                    $keu_rn = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'outputkeu_rn', $post['inputs']['kode_komponen'], 'pkt.renk_b' . $bulanberjalan);
                    $keu_rl = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'outputkeu_rl', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                    $fis_rn = gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'outputfis_rn', $post['inputs']['kode_komponen'], 'pkt.renf_b' . $bulanberjalan);
                    $fis_rl =  gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'outputfis_rl', $post['inputs']['kode_komponen'], ($bulanberjalan == $bulansekarang ?  'pkt.ufis'  : 'pkt.ff_b' . $bulanberjalan));

                    $row['class'] = 'tdoutput';
                    $row['no'] = $nooutput++;
                    $row['kode'] = $idk;
                    $row['nama_program'] = $d['nmoutput'];
                    $row['target'] = str_replace('.', ',', round(gettotal($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), 'outputvol', $post['inputs']['kode_komponen']), 2));
                    $row['satuan'] = $d['sat'];
                    $row['pagu'] = number_format($pg_output / 1000, 0, ',', '.');
                    $row['realisasi'] = number_format($realisasi_output / 1000, 0, ',', '.');
                    $row['keuangan_rn'] = str_replace('.', ',', round($keu_rn, 2));
                    $row['keuangan_rl'] = str_replace('.', ',', round($keu_rl, 2));
                    $row['fisik_rn'] = str_replace('.', ',', round($fis_rn, 2));
                    $row['fisik_rl'] = str_replace('.', ',', round($fis_rl, 2));
                    $row['fisik_kinerja'] = ($fis_rn != 0 ?  str_replace('.', ',', round($fis_rl / $fis_rn, 2)) : '~');

                    $ido = array_merge([$idk], $ido);
                    $list[] = $row;
                }
                $idk = $idk . '.' . $d['kdsoutput'];
                if (!in_array($idk, $ido)){

                    $realisasi = ($bulanberjalan == $bulansekarang ?  $d['rtot']  : $d['rr_b']);
                    $rl_keu = ($bulanberjalan == $bulansekarang ?  round($d['rtot'] / $d['pg'] * 100 / $jumlah_data, 2) : round($d['rr_b'] / $d['pg'] * 100 / $jumlah_data, 2));
                    $rl_fis = ($bulanberjalan == $bulansekarang ?  round($d['ufis'] / $d['pg'] * 100 / $jumlah_data, 2)  : round($d['ff_b'] / $d['pg'] * 100 / $jumlah_data, 2));

                    $row['class'] = 'tdsoutput';
                    $row['no'] = $nosoutput++;
                    $row['kode'] = $idk;
                    $row['nama_program'] = getoutputname($this->db, $post['inputs']['kode_program'], $d['kdgiat'], (!empty($kodeOutput) ? $kodeOutput : "'".$d['kdoutput']."'"), $d['kdsoutput']);
                    $row['target'] = str_replace('.', ',', round(str_replace(",", ".", $d['vol']), 2));
                    $row['satuan'] = $d['sat'];
                    $row['pagu'] = number_format($d['pg'] / 1000, 0, ',', '.');
                    $row['realisasi'] = number_format($realisasi / 1000, 0, ',', '.');
                    $row['keuangan_rn'] = str_replace('.', ',', round($d['renk_b'] / $d['pg'] * 100 / $jumlah_data, 2));
                    $row['keuangan_rl'] = str_replace('.', ',', $rl_keu);
                    $row['fisik_rn'] = str_replace('.', ',', round($d['renf_b'] / $jumlah_data, 2));
                    $row['fisik_rl'] = str_replace('.', ',', $rl_fis);
                    $row['fisik_kinerja'] = ($d['renf_b'] != 0 ?  str_replace('.', ',', round($rl_fis / $d['renf_b'], 2)) : '~');
                    $ido = array_merge([$idk], $ido);
                    $list[] = $row;
                }
            }

            $data = [
                'draw' => $request->getPost('draw'),
                'data' => $list,
                'recordsTotal' => $qdata['countAll'],
                'recordsFiltered' => count($list),
            ];
            
            echo json_encode($data);
        }
    }
}
