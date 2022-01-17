<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\ImportdataModel;

class CronJob extends \App\Controllers\BaseController
{
    public function __construct()
    {

        // $this->TopikModel        = new TopikModel();
        $this->ImportdataModel        = new ImportdataModel();
    }


    public function dataPaket()
    {
        $request = \Config\Services::request();
        $path = $request->uri->getSegments();
        $type = $path[2];
        $param = $request->uri->getQuery();
        // dd($param);

        if ($param == "apikey=monika2k21") {

            ini_set('max_execution_time', 0);

            // persiapan
            // pull data

            // if (
            //     $_ENV['SERVER']
            //     == "local"
            // ) {

            if ($type == 'paket') {

                $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/paket?thang=" . date("Y"));
                $nmFile = date("ymdHis") . '_fromemon_paket_' . date("Y");

                $fno = array('pagu_51', 'pagu_52', 'pagu_53', 'pagu_rpm', 'pagu_sbsn', 'pagu_phln', 'pagu_total', 'real_51', 'real_52', 'real_53', 'real_rpm', 'real_sbsn', 'real_phln', 'real_total', 'progres_keuangan', 'progres_fisik', 'progres_keu_jan', 'progres_keu_feb', 'progres_keu_mar', 'progres_keu_apr', 'progres_keu_mei', 'progres_keu_jun', 'progres_keu_jul', 'progres_keu_agu', 'progres_keu_sep', 'progres_keu_okt', 'progres_keu_nov', 'progres_keu_des', 'progres_fisik_jan', 'progres_fisik_feb', 'progres_fisik_mar', 'progres_fisik_apr', 'progres_fisik_mei', 'progres_fisik_jun', 'progres_fisik_jul', 'progres_fisik_agu', 'progres_fisik_sep', 'progres_fisik_okt', 'progres_fisik_nov', 'progres_fisik_des', 'ren_keu_jan', 'ren_keu_feb', 'ren_keu_mar', 'ren_keu_apr', 'ren_keu_mei', 'ren_keu_jun', 'ren_keu_jul', 'ren_keu_agu', 'ren_keu_sep', 'ren_keu_okt', 'ren_keu_nov', 'ren_keu_des', 'ren_fis_jan', 'ren_fis_feb', 'ren_fis_mar', 'ren_fis_apr', 'ren_fis_mei', 'ren_fis_jun', 'ren_fis_jul', 'ren_fis_agu', 'ren_fis_sep', 'ren_fis_okt', 'ren_fis_nov', 'ren_fis_des', 'kdkabkota', 'kdlokasi', 'kdppk', 'kdskmpen', 'sat', 'vol', 'ufis', 'pfis', 'prognosis');
                $tabel = "monika_data_" . date("Y");
            } else if ($type == 'kontrak') {
                $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/kontrak?thang=" . date("Y"));
                $nmFile = date("ymdHis") . '_fromemon_kontrak_' . date("Y");

                $fno =  array('tahun', 'kdsatker', 'kdprogram', 'kdgiat', 'kdoutput', 'kdsoutput', 'kdkmpnen', 'kdskmpnen', 'kdpaket', 'kdls', 'nmpaket', 'kdpengadaan', 'kdkategori', 'kdjnskon', 'rkn_nama', 'rkn_npwp', 'nomor_kontrak', 'nilai_kontrak', 'tanggal_kontrak', 'tgl_spmk', 'waktu', 'status_tender', 'tgl_rencana_lelang', 'jadwal_pengumuman', 'jadwal_pemenang', 'jadwal_kontrak', 'jadwal_tgl_kontrak', 'status_sipbj', 'ufis', 'pfis', 'sumber_dana');
                $tabel = "monika_kontrak_" . date("Y");
            } else if ($type == 'paket_register') {
                $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/paket_register?thang=" . date("Y"));
                $nmFile = date("ymdHis") . '_fromemon_paket_register_' . date("Y");

                $fno = array('tahun', 'kode', 'kdregister');
                $tabel = "monika_paket_register_" . date("Y");
            } else {

                $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/rekap_unor?thang=" . date("Y"));
                $nmFile = date("ymdHis") . '_fromemon_rekap_unor_' . date("Y");

                $fno = array('status', 'kdunit', 'nmunit', 'pagu_rpm', 'pagu_sbsn', 'pagu_phln', 'pagu_total', 'real_rpm', 'real_sbsn', 'real_phln', 'real_total', 'progres_keu', 'progres_fisik');
                $tabel = "monika_rekap_unor_" . date("Y");
            }
            // }else{
            // $data = file_get_contents("http://34.120.159.131/ws_sda/");


            // }

            $pull_get_file =  $this->ImportdataModel->getDok(["type" => $type], "no_order_by")->get()->getRowArray();
            $namefile = (isset($pull_get_file['nmfile']) ? $pull_get_file['nmfile'] : '');
            $namefilesql = (isset($pull_get_file['sqlfile_nm']) ? $pull_get_file['sqlfile_nm'] : '');

            $pullcount = $this->ImportdataModel->getDok(["type" => $type, "tahunanggaran" => date("Y")], "no_order_by")->countAllResults();

            $targetDir = WRITEPATH . "emon" . DIRECTORY_SEPARATOR . "FileTxt";
            $targetDir1 = WRITEPATH . "emon" . DIRECTORY_SEPARATOR . "FileSql";


            if ($pullcount > 14) {

                $query = $this->ImportdataModel->deleteFiles(["nmfile" => $namefile, "type" => $type]);

                unlink($targetDir . DIRECTORY_SEPARATOR . $namefile);
                unlink($targetDir1 . DIRECTORY_SEPARATOR . $namefilesql);
            }


            //import data
            $l = WRITEPATH . "emon/FileTxt/" . $nmFile;


            $nf = fopen($l, "w+");
            fwrite($nf, trim(preg_replace('/\s+/', ' ', $data)));
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
                    'in_uid' => "server",
                    'type' => $path[2],
                    'tahunanggaran' => date("Y")
                ];

                $q = $this->ImportdataModel->save($post);
                $idpull = $this->ImportdataModel->getInsertID();



                //generate to sql

                $block = 1024 * 1024; //1MB or counld be any higher than HDD block_size*2

                $locsql = WRITEPATH . "emon/FileSql/$nmFile.sql";
                if ($fh = fopen($l, "r")) {

                    $nf = fopen($locsql, "w+");
                    fwrite($nf, "DELETE FROM {$tabel};\n");
                    fclose($nf);

                    $nf = fopen($locsql, "a+");

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
                        $data = str_replace(array(",}"), array("}"), $data);
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

                    $post = [
                        'sqlfile_nm'    =>  $nmFile . ".sql",
                        'sqlfile_size'  => filesize($l),
                        'sqlfile_row'   => $row,
                        'sqlfile_dt'    => date("ymdHis"),
                        'sqlfile_uid'   => "server",
                        'st'            => 2
                    ];

                    $update_convert_to_sql = $this->ImportdataModel->where(['idpull' => $idpull, 'type' => $type])->set($post)->update();


                    $post = [
                        'import_dt'    => date("ymdHis"),
                        'import_uid'   => "server",
                        'st'            => 2
                    ];
                    $update_convert_to_sql_import = $this->ImportdataModel->where(['st' => 3, 'type' => $type])->set($post)->update();
                    // dd($update_convert_to_sql_import);

                    $this->importsql($nmFile);

                    $post = [
                        'import_dt'    => date("ymdHis"),
                        'import_uid'   => "server",
                        'aktif_dt'    => date("ymdHis"),
                        'aktif_uid'   => "server",
                        'st'            => 3
                    ];

                    $update_convert_to_sql_import = $this->ImportdataModel->where(['idpull' => $idpull, 'type' => $type])->set($post)->update();
                }
            }
        } else {



            return json_encode(["error" => "Auth Failure"]);
        }



        // // if ($this->request->isAJAX()) {

        //     return json_encode(['success'=> 'success', 'csrf' => csrf_hash(), 'query ' => "haloo" ]);
        // // }

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
