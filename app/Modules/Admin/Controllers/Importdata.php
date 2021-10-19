<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
use Modules\Admin\Models\ImportdataModel;


class Importdata extends \App\Controllers\BaseController
{
    public function __construct()
    {
        // $this->akses                = new AksesModel();
        $this->ImportdataModel        = new ImportdataModel();
        // $this->InModul = "Pulldata";
        $session = session();
        $this->user = $session->get('userData');
    }

    public function index($slug="paket")
    {
        $pg = 20;
        $data = [
            'title' => 'Daftar pemanggilan data',
            'pg' => $pg,
            'current' => $slug,
            'qdata' => $this->ImportdataModel->getDok()->paginate($pg),
            'pager' => $this->ImportdataModel->getDok()->pager
        ];
        return view('Modules\Admin\Views\Dok\Importdata', $data);
    }
    public function imdata($slug = null)
    {
        $d = $this->ImportdataModel->getDok("monika_pull.idpull='{$slug}'")->paginate()[0];
        // dd($d);
        $aksi = false;
        if ($d['st'] == 0) {
            $txtsql = $this->simpandata($d['idpull'], $d['nmfile'],$d["type"]);
            $post = [
                'sqlfile_nm'    => $d['nmfile'] . ".sql",
                'sqlfile_size'  => $txtsql['sqlfile_size'],
                'sqlfile_row'   => $txtsql['sqlfile_row'],
                'sqlfile_dt'    => date("ymdHis"),
                'sqlfile_uid'   => $this->user['uid'],
                'st'            => 1
            ];
            $aksi = true;
        } elseif ($d['st'] == 1) {
            $post = [
                'import_dt'    => date("ymdHis"),
                'import_uid'   => $this->user['uid'],
                'st'            => 2
            ];
            $aksi = true;
        } elseif ($d['st'] == 2) {
            $post = [
                'import_dt'    => date("ymdHis"),
                'import_uid'   => $this->user['uid'],
                'st'            => 2
            ];
            $this->ImportdataModel->where('st',  3)->set($post)->update();
            $this->importsql($d['nmfile']);
            $post = [
                'aktif_dt'    => date("ymdHis"),
                'aktif_uid'   => $this->user['uid'],
                'st'            => 3
            ];
            $aksi = true;
        }

        if ($aksi == true) {
            $q = $this->ImportdataModel->where('idpull',  $slug)->set($post)->update();
        }

        return redirect()->to('/preferensi/tarik-data-emon/'.$d["type"])->with('success', 'Proses selesai');
    }
    public function unduh($slug = null, $type = null)
    {
        $d = $this->ImportdataModel->getDok("monika_pull.idpull='{$slug}'")->paginate()[0];

        $tipe = 'Txt';
        $file = $d['nmfile'];
        $nmFile = $d['nmfile'] . '.txt';
        if ($type == "sql") {
            $tipe = 'Sql';
            $file = $d['sqlfile_nm'];
            $nmFile = $d['sqlfile_nm'];
        }
        $l = WRITEPATH . 'emon/File' . $tipe . '/' . $file;
        // dd($l);
        if (!file_exists($l)) {
            return ['status' => 'Error', 'pesan' => 'File Tidak ada'];
        } else return $this->response->download(WRITEPATH . 'emon/File' . $tipe . '/' . $file, null)->setFileName($nmFile);
    }

    function pullimport($type='')
    {
        ini_set('max_execution_time', 0);

        // persiapan
        // pull data
        // if (
        //     $_ENV['SERVER']
        //     == "local"
        // ) {

        if($type == 'paket'){

            $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/paket");
            $nmFile = date("ymdHis") . '_fromemon_paket';

        }else{
            $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/kontrak");
            $nmFile = date("ymdHis") . '_fromemon_kontrak';


        }
        // }else{
        // $data = file_get_contents("http://34.120.159.131/ws_sda/");
         

        // }
        //import data
        $l = WRITEPATH . "emon/FileTxt/" . $nmFile;

        
        $nf = fopen($l, "w+");
        fwrite($nf,trim(preg_replace('/\s+/', ' ', $data)));
        fclose($nf);
        // $json = json_encode($data);
        // $json_dcode = json_decode($json,TRUE);
        // print_r($json_dcode);exit;

        // save info data
        if (file_exists($l)) {
            $post = [
                'idpull' => null,
                'nmfile' => $nmFile,
                'sizefile' => filesize($l),
                'in_dt' => date("ymdHis"),
                'in_uid' => $this->user['uid'],
                'type' => $type
            ];

            $q = $this->ImportdataModel->save($post);


            // $data = file_get_contents($l);
    
          

            return redirect()->to('/preferensi/tarik-data-emon/'.$type)->with('success', 'Pull data berhasil');
        } else {
            return redirect()->to('/preferensi/tarik-data-emon/'.$type)
                ->withInput()
                ->with('error', 'Terjadi kesalahan ketika Pull Data');
        }
    }

    function simpandata($idpull, $slug,$param)
    {
        $file = WRITEPATH . "emon/FileTxt/{$slug}";
        if (!file_exists($file)) {
            return ['status' => false];
        } else {
            $block = 1024 * 1024; //1MB or counld be any higher than HDD block_size*2
            // $fno = array('pagu_51', 'pagu_52', 'pagu_53', 'pagu_rpm', 'pagu_sbsn', 'pagu_phln', 'pagu_total', 'real_51', 'real_52', 'real_53', 'real_rpm', 'real_sbsn', 'real_phln', 'real_total', 'progres_keuangan', 'progres_fisik', 'progres_keu_jan', 'progres_keu_feb', 'progres_keu_mar', 'progres_keu_apr', 'progres_keu_mei', 'progres_keu_jun', 'progres_keu_jul', 'progres_keu_agu', 'progres_keu_sep', 'progres_keu_okt', 'progres_keu_nov', 'progres_keu_des', 'progres_fisik_jan', 'progres_fisik_feb', 'progres_fisik_mar', 'progres_fisik_apr', 'progres_fisik_mei', 'progres_fisik_jun', 'progres_fisik_jul', 'progres_fisik_agu', 'progres_fisik_sep', 'progres_fisik_okt', 'progres_fisik_nov', 'progres_fisik_des', 'ren_keu_jan', 'ren_keu_feb', 'ren_keu_mar', 'ren_keu_apr', 'ren_keu_mei', 'ren_keu_jun', 'ren_keu_jul', 'ren_keu_agu', 'ren_keu_sep', 'ren_keu_okt', 'ren_keu_nov', 'ren_keu_des', 'ren_fis_jan', 'ren_fis_feb', 'ren_fis_mar', 'ren_fis_apr', 'ren_fis_mei', 'ren_fis_jun', 'ren_fis_jul', 'ren_fis_agu', 'ren_fis_sep', 'ren_fis_okt', 'ren_fis_nov', 'ren_fis_des');
            
            if($param == 'kontrak'){

                $fno =  array('tahun','kdsatker','kdprogram','kdgiat','kdoutput','kdsoutput','kdkmpnen','kdskmpnen','kdpaket','kdls','nmpaket','kdpengadaan','kdkategori','kdjnskon','rkn_nama','rkn_npwp','nomor_kontrak','nilai_kontrak','tanggal_kontrak','tgl_spmk','waktu');
                $tabel = "monika_kontrak";
            }else{

                $fno = array('pagu_51', 'pagu_52', 'pagu_53', 'pagu_rpm', 'pagu_sbsn', 'pagu_phln', 'pagu_total', 'real_51', 'real_52', 'real_53', 'real_rpm', 'real_sbsn', 'real_phln', 'real_total', 'progres_keuangan', 'progres_fisik', 'progres_keu_jan', 'progres_keu_feb', 'progres_keu_mar', 'progres_keu_apr', 'progres_keu_mei', 'progres_keu_jun', 'progres_keu_jul', 'progres_keu_agu', 'progres_keu_sep', 'progres_keu_okt', 'progres_keu_nov', 'progres_keu_des', 'progres_fisik_jan', 'progres_fisik_feb', 'progres_fisik_mar', 'progres_fisik_apr', 'progres_fisik_mei', 'progres_fisik_jun', 'progres_fisik_jul', 'progres_fisik_agu', 'progres_fisik_sep', 'progres_fisik_okt', 'progres_fisik_nov', 'progres_fisik_des', 'ren_keu_jan', 'ren_keu_feb', 'ren_keu_mar', 'ren_keu_apr', 'ren_keu_mei', 'ren_keu_jun', 'ren_keu_jul', 'ren_keu_agu', 'ren_keu_sep', 'ren_keu_okt', 'ren_keu_nov', 'ren_keu_des', 'ren_fis_jan', 'ren_fis_feb', 'ren_fis_mar', 'ren_fis_apr', 'ren_fis_mei', 'ren_fis_jun', 'ren_fis_jul', 'ren_fis_agu', 'ren_fis_sep', 'ren_fis_okt', 'ren_fis_nov', 'ren_fis_des','kdkabkota','kdlokasi','kdppk','kdskmpen','sat','vol');
                $tabel = "monika_data";
            
            }



            if ($fh = fopen($file, "r")) {

                $l = WRITEPATH . "emon/FileSql/$slug.sql";

                $nf = fopen($l, "w+");
                fwrite($nf, "DELETE FROM {$tabel};\n");
                fclose($nf);

                $nf = fopen($l, "a+");

                $left = '';
                $i = 0;
                $row = 0;
                while (!feof($fh)) {

                    $temp = fread($fh, $block);
                    $temp = str_replace(array("[", "]", "},", "}", "#ku#"), array("", "", "#ku#", "#ku#", "},"), $temp);
                    $fgetslines = explode("},", $temp);
                    $fgetslines[0] = $left . $fgetslines[0];
                    if (!feof($fh)) $left = array_pop($fgetslines);

                    $data = "";
                    foreach ($fgetslines as $k => $line) {
                        if ($line != "") $data .= ($data ? ',' : '') . $line . "}";
                    }
                    $data =str_replace(array(",}"), array("}"), $data);
                    $qdata = [];
                    if ($data != '') {
                        $qdata = json_decode("[$data]", true);
                    }

                    // dd($qdata);

                    if (count($qdata) > 0) {
                        $ii = 0;
                        foreach ($qdata as $k => $d) {
                            if (count($d) > 0) {
                                if ($ii == 0) $f = "idpull";
                                $v = "'" . $idpull . "'";
                                foreach ($d as $field => $value) {
                                    if ($ii == 0) {
                                        $f .= ($f ? ',' : '') . $field;
                                    }
                                    if (in_array($field, $fno) and $value == "") {
                                        $value = 0;
                                    }
                                    $v .= ($v ? ',' : '') . "'" . str_replace(array("\n", "\t"), array(" ", " "), $value) . "'";
                                }
                                if ($ii == 0) {
                                    fwrite($nf, ($i > 0 ? ";\n" : "") . "INSERT INTO {$tabel} ($f) VALUES ");
                                }
                                fwrite($nf, ($ii > 0 ? ',' : '') . "\n($v)");

                                $ii++;
                                $row++;
                            }
                        }
                        $i++;
                    }
                }
                fwrite($nf, ";");
                fclose($nf);
                fclose($fh);

                // return ['status'=>true, 'query'=>$i, 'rowdata'=>$row, 'imporsql'=>$this->importsql($slug)];
                return ['status' => true, 'query' => $i, 'sqlfile_row' => $row, 'sqlfile_size' => filesize($l)];
            }
        }
    }

    function importsql($slug)
    {

        if (
            $_ENV['SERVER']
            == "local"
        ) {

            $command = "E:\laragon\bin\mysql\mysql-5.7.24-winx64\bin\mysql --user=root --password= -h localhost -D monika < E:\\xampp\\htdocs\\monika\\writable\\emon\FileSql\\$slug.sql";
        } else if (
            $_ENV['SERVER']
            == 'mascitra'
        ) {

            $command = "mysql --user='" . $_ENV['database.default.username'] . "' --password='" . $_ENV['database.default.password'] . "' -h localhost -D " . $_ENV['database.default.database'] . " < /home/mascitra/public_html/monika-sda-new/writable/emon/FileSql/$slug.sql";
        } else {
            $command = "mysql --user='" . $_ENV['database.default.username'] . "' --password='" . $_ENV['database.default.password'] . "' -h localhost -D " . $_ENV['database.default.database'] . "< /var/www/monika-new/writable/emon/FileSql/$slug.sql";
        }
        // dd($command);
        $cmd = shell_exec($command);
        return ['command' => $command];
    }
}
