<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
// use Modules\Admin\Models\KinerjaOutputBulananModel;


class KinerjaOutputBulanan extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
        $this->db_mysql = \Config\Database::connect();
    }

    public function index($bulan = '', $keyword = '')
    {
        $tahun = $this->user['tahun'];
        $bulan = decrypt_url($bulan);
        if ($bulan == '') {
            $bulan = date('n');
        }
        if (empty($_GET['exp'])) {
            $_GET['exp'] = '';
        }

        if ($keyword) {

            $keyword = "WHERE tsoutput.nmro LIKE '%$keyword' AND  pkt.tahun ='$tahun'";
        } else {

            $keyword = "WHERE pkt.tahun ='$tahun'";
        }



        $qpaket = $this->db_mysql->query("SELECT
        pkt.kdprogram,
        pkt.kdgiat,
        pkt.kdoutput,
        pkt.kdsoutput,
        pkt.kdkmpnen,
        pkt.sat,
        tp.nmprogram,
        tgiat.nmgiat,
        toutput.nmoutput,
        toutput.sat AS toutputsat,
        pkt.pagu_total,
        CAST(
            REPLACE (pkt.vol, ',', '.') AS DECIMAL
        ) vol,
        pkt.kdprogram || '.' || pkt.kdgiat || '.' || pkt.kdoutput || '.' || pkt.kdsoutput || pkt.kdkmpnen AS kode,
   
     tsoutput.nmro
    FROM
        monika_data_{$tahun} pkt
    LEFT JOIN tprogram tp ON tp.kdprogram = pkt.kdprogram
    LEFT JOIN tgiat ON tgiat.kdgiat = pkt.kdgiat AND tgiat.tahun_anggaran = {$tahun}
    LEFT JOIN toutput ON pkt.kdgiat = toutput.kdgiat
    AND pkt.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran = {$tahun}
    LEFT JOIN tsoutput ON
    pkt.kdgiat = tsoutput.kdgiat
    AND pkt.kdoutput = tsoutput.kdkro
    AND pkt.kdsoutput = tsoutput.kdro
    AND tsoutput.tahun_anggaran = {$tahun}
    $keyword
    ORDER BY
    pkt.kdprogram,
        pkt.kdgiat,
        pkt.kdoutput,
        pkt.kdsoutput")->getResultArray();

        $data = array(
            'title' => 'Kinerja Output Bulanan',
            // 'idk'   => $balaiid,
            // 'idks'  => $satkerid,
            // 'label' => $balai,
            // 'label2' => $satker,
            // 'posisi' => ['<a href="' . site_url("pulldata/unitkerja") . '"><i class="fa fa-home"></i></a>', '<a href="' . site_url("pulldata/satuankerja/$balaiid/$balai") . '">' . $balai . '</a>', $satker],
            // 'qdata' => $this->PulldataModel->getPaket("md.kdsatker='$satkerid'"),
            'qdata' => $qpaket,
            'rekap' => 'paket',
            'dbuse' => $this->db
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
<<<<<<< HEAD
=======

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
        $request = Services::request();
        $model = new KinerjaOutputBulananModel($request);

        if($request->getMethod(true) === 'POST'){
            
            $post = $request->getPost();
            $kodeKegiatan = '';
            $kodeOutput = '';

            if(isset($post['param'])){

                if($post['param']['kegiatan'] != null){

                    $kodeKegiatan = sprintf("'%s'", implode("','", [$post['param']['kegiatan']]));
                }
                if($post['param']['output'] != null){

                    $kodeOutput = sprintf("'%s'", implode("','", [$post['param']['output']]));
                }

                $qdata = $model->data($post, $kodeKegiatan, $kodeOutput);
            // echo"<pre>";print_r($this->db->getLastQuery());exit;
            }else{

                if($post['inputs']['kode_kegiatan'] != null){

                    $kodeKegiatan = sprintf("'%s'", implode("','", $post['inputs']['kode_kegiatan']));
                }
                if($post['inputs']['kode_output'] != null){

                    $kodeOutput = sprintf("'%s'", implode("','", $post['inputs']['kode_output']));
                }
                
                $qdata = $model->data($post, $kodeKegiatan, $kodeOutput);
            }
            //backup dev
            // echo"<pre>";print_r($this->db->getLastQuery());exit;
            // echo"<pre>";print_r($qdata['result']);exit;
            // echo"<pre>";print_r($post['inputs']['kode_kegiatan']);exit;
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

            // echo"<pre>";print_r($nArraysoutput);exit;
            // $program = $model->getPg();
            // echo"<pre>";print_r($program);exit;
            $list = [];
            foreach($nArraysoutput as $d){

                $row = [];
                $idk = $d['kdprogram'];
                $jumlah_data = isset($d['jumlah_data']) ? $d['jumlah_data'] : 1;

                if (!in_array($idk, $idp)){

                    $data_program = $model->getKomponenProgram($d['kdprogram'])->getRow();
                    $data_program2 = $model->getKomponenProgram2($d['kdprogram'], [
                        "realisasi" => ($bulanberjalan == $bulansekarang ?  'rtot'  : 'rr_b' . $bulanberjalan), 
                        "keu_rn" => 'renk_b' . $bulanberjalan, 
                        "keu_rl" => ($bulanberjalan == $bulansekarang ?  'rtot'  : 'rr_b' . $bulanberjalan), 
                        "fis_rn" => 'renf_b' . $bulanberjalan, 
                        "fis_rl" => ($bulanberjalan == $bulansekarang ?  'ufis'  : 'ff_b' . $bulanberjalan)])->getRow();
            // echo"<pre>";print_r($this->db->getLastQuery());exit;
            // echo"<pre>";var_dump($komponen_program);
            // echo"<pre>";var_dump($data_program2);exit;
                    
                    $row['class'] = 'tdprogram';
                    $row['no'] = $noprogram++;
                    $row['kode'] = $idk;
                    $row['nama'] = $d['nmprogram'];
                    $row['target'] = str_replace('.', ',', round($data_program->target, 2));
                    $row['satuan'] = 'Paket Program';
                    $row['pagu'] = number_format($data_program->pagu / 1000, 0, ',', '.');
                    $row['realisasi'] = number_format($data_program2->realisasi / 1000, 0, ',', '.');
                    $row['keuangan_rn'] = str_replace('.', ',', round($data_program2->keu_rn/ $data_program->pagu * 100, 2));
                    $row['keuangan_rl'] = str_replace('.', ',', round($data_program2->keu_rl, 2));
                    $row['fisik_rn'] = str_replace('.', ',', round($data_program2->fis_rn, 2));
                    $row['fisik_rl'] = str_replace('.', ',', round($data_program2->fis_rl, 2));
                    $row['fisik_kinerja'] = ($data_program2->fis_rn != 0 ?  str_replace('.', ',', round($data_program2->fis_rl / $data_program2->fis_rn, 2)) : '~');

                    $idp = array_merge([$idk], $idp);
                    $list[] = $row;
                }

                if(isset($post['param']['program']) || (isset($post['inputs']['kode_kegiatan']) && $post['inputs']['kode_kegiatan'] != null)){

                    $idk = $d['kdgiat'];
                    if (!in_array($idk, $ido)){

                        $data_kegiatan = $model->getKomponenKegiatan($d['kdprogram'], $d['kdgiat'])->getRow();
                        $data_kegiatan2 = $model->getKomponenKegiatan2($d['kdprogram'], $d['kdgiat'], [
                            "realisasi" => ($bulanberjalan == $bulansekarang ?  'rtot'  : 'rr_b' . $bulanberjalan), 
                            "keu_rn" => 'renk_b' . $bulanberjalan, 
                            "keu_rl" => ($bulanberjalan == $bulansekarang ?  'rtot'  : 'rr_b' . $bulanberjalan), 
                            "fis_rn" => 'renf_b' . $bulanberjalan, 
                            "fis_rl" => ($bulanberjalan == $bulansekarang ?  'ufis'  : 'ff_b' . $bulanberjalan)])->getRow();
                            // echo"<pre>";var_dump($this->db->getLastQuery());exit;

                        $row['class'] = 'tdgiat';
                        $row['no'] = $nogiat++;
                        $row['kode'] = $idk;
                        $row['nama'] = $d['nmgiat'];
                        $row['target'] = str_replace('.', ',', round($data_kegiatan->target, 2));
                        $row['satuan'] = 'Paket Kegiatan';
                        $row['pagu'] = number_format($data_kegiatan->pagu / 1000, 0, ',', '.');
                        $row['realisasi'] = number_format($data_kegiatan2->realisasi / 1000, 0, ',', '.');
                        $row['keuangan_rn'] = str_replace('.', ',', round($data_kegiatan2->keu_rn, 2));
                        $row['keuangan_rl'] = str_replace('.', ',', round($data_kegiatan2->keu_rl, 2));
                        $row['fisik_rn'] = str_replace('.', ',', round($data_kegiatan2->fis_rn, 2));
                        $row['fisik_rl'] = str_replace('.', ',', round($data_kegiatan2->fis_rl, 2));
                        $row['fisik_kinerja'] = ($data_kegiatan2->fis_rn != 0 ?  str_replace('.', ',', round($data_kegiatan2->fis_rl / $data_kegiatan2->fis_rn, 2)) : '~');

                        $ido = array_merge([$idk], $ido);
                        $list[] = $row;
                    }
                }

                if(isset($post['param']['kegiatan'])){

                    $idk = $idk . '.' . $d['kdoutput'];
                    if (!in_array($idk, $ido)){

                        $data_output = $model->getKomponenOutput($d['kdprogram'], $d['kdgiat'], $d['kdoutput'])->getRow();
                        $data_output2 = $model->getKomponenOutput2($d['kdprogram'], $d['kdgiat'], $d['kdoutput'], [
                            "realisasi" => ($bulanberjalan == $bulansekarang ?  'rtot'  : 'rr_b' . $bulanberjalan), 
                            "keu_rn" => 'renk_b' . $bulanberjalan, 
                            "keu_rl" => ($bulanberjalan == $bulansekarang ?  'rtot'  : 'rr_b' . $bulanberjalan), 
                            "fis_rn" => 'renf_b' . $bulanberjalan, 
                            "fis_rl" => ($bulanberjalan == $bulansekarang ?  'ufis'  : 'ff_b' . $bulanberjalan)])->getRow();

                        $row['class'] = 'tdoutput';
                        $row['no'] = $nooutput++;
                        $row['kode'] = $idk;
                        $row['nama'] = $d['nmoutput'];
                        $row['target'] = str_replace('.', ',', round($data_output->target, 2));
                        $row['satuan'] = $d['sat'];
                        $row['pagu'] = number_format($data_output->pagu / 1000, 0, ',', '.');
                        $row['realisasi'] = number_format($data_output2->realisasi / 1000, 0, ',', '.');
                        $row['keuangan_rn'] = str_replace('.', ',', round($data_output2->keu_rn, 2));
                        $row['keuangan_rl'] = str_replace('.', ',', round($data_output2->keu_rl, 2));
                        $row['fisik_rn'] = str_replace('.', ',', round($data_output2->fis_rn, 2));
                        $row['fisik_rl'] = str_replace('.', ',', round($data_output2->fis_rl, 2));
                        $row['fisik_kinerja'] = ($data_output2->fis_rn != 0 ?  str_replace('.', ',', round($data_output2->fis_rl / $data_output2->fis_rn, 2)) : '~');

                        $ido = array_merge([$idk], $ido);
                        $list[] = $row;
                    }
                }
                
                if(isset($post['param']['output'])){

                    $idk = $idk . '.' . $d['kdsoutput'];
                    if (!in_array($idk, $ido)){

                        $realisasi = ($bulanberjalan == $bulansekarang ?  $d['rtot']  : $d['rr_b']);
                        $rl_keu = ($bulanberjalan == $bulansekarang ?  round($d['rtot'] / $d['pg'] * 100 / $jumlah_data, 2) : round($d['rr_b'] / $d['pg'] * 100 / $jumlah_data, 2));
                        $rl_fis = ($bulanberjalan == $bulansekarang ?  round($d['ufis'] / $d['pg'] * 100 / $jumlah_data, 2)  : round($d['ff_b'] / $d['pg'] * 100 / $jumlah_data, 2));

                        $row['class'] = 'tdsoutput';
                        $row['no'] = $nosoutput++;
                        $row['kode'] = $idk;
                        $row['nama'] = $model->getoutputname($d['kdprogram'], $d['kdgiat'], $d['kdoutput'], $d['kdsoutput'], $post['inputs']['kode_ro']);
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
            }

            // echo"<pre>";print_r(json_encode($idp));exit;
            $data = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $qdata['countAll'],
                'recordsFiltered' => count($list),
                'data' => $list,
            ];
            
            echo json_encode($data);
            exit;
        }
    }
>>>>>>> 49bd92a322b513c092a991bea33097f4ffd1790d
}
