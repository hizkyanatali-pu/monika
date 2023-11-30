<?php

namespace Modules\Admin\Controllers;

use \CodeIgniter\Controller;
use \Hermawan\DataTables\DataTable;
use Modules\Admin\Models\ImportdataSqliteModel;
use Modules\Admin\Models\ImportdataModel;


class Preferensi extends \App\Controllers\BaseController
{
    public function __construct()
    {
        // $this->akses                = new AksesModel();
        $this->ImportdataSqliteModel        = new ImportdataSqliteModel();
        $this->ImportdataModel        = new ImportdataModel();


        // $this->InModul = "Pulldata";
        helper('dbdinamic');
        helper('filesystem');
        $session = session();
        $this->user = $session->get('userData');
        $this->db1 = \Config\Database::connect();
        $this->request = \Config\Services::request();
    }
    public function index()
    {
        $pg = 20;
        $data = [
            'title' => 'Daftar DB SQLITE',
            'pg' => $pg,
            'qdata' => $this->ImportdataSqliteModel->getDok()->paginate($pg),
            'pager' => $this->ImportdataSqliteModel->getDok()->pager,
        ];
        // return view('Modules\Admin\Views\Dok\Importdata', $data);
        return view('Modules\Admin\Views\Preferensi\Formuploadsqlite', $data);
    }
    //fitur tarik data paket
    public function tarikdata()
    {
        $pg = 20;
        $data = [
            'title' => 'Tarik Data',
            'pg' => $pg,
            'qdata' => $this->ImportdataSqliteModel->getDok()->paginate($pg),
            'pager' => $this->ImportdataSqliteModel->getDok()->pager,
        ];
        // return view('Modules\Admin\Views\Dok\Importdata', $data);
        return view('Modules\Admin\Views\Preferensi\Preferensi', $data);
    }

    //proses tarik data dari API
    public function savetodb()
    {


        ini_set('max_execution_time', 0);

        $builder = $this->db1->table('monika_data');


        $url = 'https://emonitoring.pu.go.id/ws_sda';

        $ch = curl_init();
        $options = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => $url
        ];

        curl_setopt_array($ch, $options);
        // $jsonCont = json_decode(curl_exec($ch));
        $jsonCont = curl_exec($ch);


        // persiapan
        $nmFile = date("ymdHis") . '_fromemon.json';

        //lokasi
        $l = WRITEPATH . "emon/Json/" . $nmFile;
        //replace 
        $fp = fopen($l, 'w+');
        fwrite($fp, $jsonCont);
        $contents = file_get_contents($l);
        $str = str_replace("}{", "},{", $contents);
        file_put_contents($l, $str);
        curl_close($ch);

        $openfile = file_get_contents($l);

        $data = json_decode($openfile);

        if (count($data) > 0) {
            $builder->truncate();
            $builder->insertBatch($data);
            // $session->setFlashdata('msg', 'Data Berhasil Disinkronisasikan');
            echo json_encode(count($data));
        }
    }

    // proses menampilkan data dari sqlite dan proses insert data dari sqlite ke sql
    public function opendbsqlite()
    {
        $this->opendb(decrypt_url($_POST['db']));

        $table = $_POST['table'];
        $forinsert = isset($_POST['params']) ? $_POST['params'] : '';
        $fields = $this->db->getFieldNames($table);

        foreach ($fields as $key => $field) {
            if ($key <= 70) {
                $data[] = $field;
            }
        }
        $select = implode(', ', $data);

        $builder = $this->db->table($table)->select($select);

        $query = $this->db->query("SELECT * FROM $table")->getResultArray();

        if ($forinsert == 'oke') {
            //proses insert data dari sqlite
            ini_set('max_execution_time', 0);
            $builder1 = $this->db1->table($table);
            $builder1->truncate();
            $builder1->insertBatch($query);
            $builder1->set('user_created', $this->user['uid']);
            $builder1->update();
            // echo json_encode($query);
            echo json_encode(count($query));
        } else {

            return DataTable::of($builder)
                ->addNumbering()
                ->toJson();
        }
    }

    // get list table sqlite
    public function showdatasqlite()
    {

        $dbname =  decrypt_url($this->request->uri->getSegment(3));

        $this->opendb($dbname);

        $data = array(
            'title' => 'Sqlite',
            'listtable' => $this->db->listTables(),
            'dbname' => $dbname
        );
        return view('Modules\Admin\Views\Preferensi\Opendbsqlite', $data);
    }

    // menampilkan thead table
    public function getthead()
    {
        $table = $_POST['table'];
        $this->opendb(decrypt_url($_POST['db']));
        $fields = $this->db->getFieldNames($table);
        $data = [];
        foreach ($fields as $key => $value) {
            if ($key <= 70) {
                $data[] = [
                    "title" => $value
                ];
            }
        }

        echo json_encode($data);
    }

    //upload dbsqlite
    function pullimportfilesqlite()
    {
        ini_set('max_execution_time', 0);
        // persiapan
        $nmFile = date("ymdHis") . '_fromemonSQLITE.db';

        // pull data
        if (!$this->validate([
            'berkas' => [
                'rules' => 'uploaded[berkas]|mime_in[berkas,application/x-sqlite3]',
                'errors' => [
                    'uploaded' => 'Harus Ada File yang diupload',
                    'mime_in' => 'File Extention Harus Berupa .db'
                ]

            ]
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }


        $dataBerkas = $this->request->getFile('berkas');

        // $type = $dataBerkas->getMimeType();

        // print_r($file->getSize());
        // exit;

        // save info data
        if ($dataBerkas) {

            $dataBerkas->move(WRITEPATH . 'emon/sqlite/', $nmFile);

            $post = [
                'idpull' => null,
                'nmfile' => $nmFile,
                'sizefile' => $dataBerkas->getSize(),
                'in_dt' => date("ymdHis"),
                'in_uid' => $this->user['uid']
            ];

            $q = $this->ImportdataSqliteModel->save($post);

            return redirect()->to('/preferensi')->with('success', 'Pull data berhasil');
        } else {
            return redirect()->to('/preferensi')
                ->withInput()
                ->with('error', 'Terjadi kesalahan ketika Push Data');
        }
    }

    public function opendb($dbname)
    {

        if ($this->request->getMethod() == 'get' || isset($_POST['table'])) {
            $dbcustom = switch_db($dbname);
            return $this->db = \Config\Database::connect($dbcustom);
        }
    }

    public function usedb()
    {

        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');

        $where = $_POST['param'];

        $table = $this->db1->table('monika_pull_sqlite');
        $table->set('status_aktif', '0');
        $table->update();

        $table->set('status_aktif', '1');
        $table->where('nmfile', $where);
        $table->update();

        $getNamedb = $table->select("nmfile")->where('status_aktif', '1')->get()->getRowArray();

        $this->ExportImportTable($getNamedb['nmfile']);

        $tableSession = $this->db1->table('ci_sessions');
        $tableSession->truncate();
        $cache = \Config\Services::cache();
        $cache->clean();
    }


    public function uploadtoserver()
    {
        // 5 minutes execution time
        @set_time_limit(0);
        // Uncomment this one to fake upload time
        // usleep(5000);

        // Settings

        $targetDir = WRITEPATH . "emon" . DIRECTORY_SEPARATOR . "sqlite";
        //$targetDir = 'uploads';
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds


        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["namafile"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["namafile"];
        } else {
            $fileName = uniqid("file_");
        }


        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


        // Remove old temp files	
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off 
            rename("{$filePath}.part", $filePath);

            // $fileName = date("ymdHis") . '_fromemonSQLITE.db';

            $query = $this->db1->table('monika_pull_sqlite');
            $countdata = $query->countAll();
            $query2 = $this->db1->query("SELECT * FROM monika_pull_sqlite ORDER BY idpull asc LIMIT 1");
            $result = $query2->getRow();

            if ($result) {

                $fileDelete = $targetDir . DIRECTORY_SEPARATOR . $result->nmfile;

                if ($countdata > 30) {
                    if (file_exists($fileDelete)) {

                        unlink($fileDelete);

                        $query->where('nmfile', $result->nmfile);
                        $query->delete();
                    }
                }
            }



            $post = [
                'idpull' => null,
                'nmfile' => $fileName,
                'sizefile' =>  $_FILES["file"]["size"] ?? '',
                'in_dt' => date("ymdHis"),
                'in_uid' => $this->user['uid'],
                'nmfileoriginal' => $_REQUEST['name']
            ];
            $q = $this->ImportdataSqliteModel->save($post);
        }

        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }


    public function DTlistdb()
    {

        $customers =  $this->db1->table('monika_pull_sqlite')->select("in_dt,sizefile,nmfile,status_aktif,TIME(in_dt) as waktu,idpull");

        return DataTable::of($customers)->addNumbering('no')
            ->add('waktu', function ($row) {

                return "[" . date_indo($row->in_dt) . " " . $row->waktu  . '  size: ' . number_format($row->sizefile / 1000000, '2', ',', '.') . " Mb ]";
            }, 'first')
            ->add('status', function ($row) {
                $elem = '<a href="' . site_url("preferensi/showdbsqlite/" . encrypt_url($row->nmfile)) . '"class="btn btn-sm btn-brand btn-elevate btn-icon" target="_blank" data-toggle="kt-tooltip" data-placement="right"  data-original-title="Buka Database"> <i class="fas fa-info"></i></a>';
                if ($row->status_aktif == '0') {
                    return $elem . ' <button type="button" class="btn btn-sm btn-success btn-elevate btn-icon usedatabase" data-toggle="kt-tooltip" data-placement="right" data-original-title="Gunakan Database" Onclick="gunakandatabase(\'' . $row->nmfile . '\')" data-id=""><i class="fas fa-check"></i></button>';
                } else {
                    return $elem . '<div class="badge badge-warning ml-1" disabled style="cursor: unset; font-size: 12px;"> Aktif</div>';
                }
            }, 'last')
            ->toJson(true);
    }



    // export & import tabel d_pkt_prognosis to mysql DB

    public function ExportImportTable($getNamedb)
    {


        ini_set('max_execution_time', 0);

        // persiapan
        // pull data

        // if (
        //     $_ENV['SERVER']
        //     == "local"
        // ) {


        $dbcustom = switch_db($getNamedb);
        $this->db_sqlite = \Config\Database::connect($dbcustom);


        $type = [

            "table" => ["d_pkt_prognosis", "tlokasi", "tkabkota", "tematik_link"]


        ];



        foreach ($type["table"] as $tabel) {



            $data = json_encode($this->db_sqlite->query("SELECT *  FROM $tabel")->getResultArray(), true);

            $nmFile = date("ymdHis") . '_fromemon_' . $tabel;

            switch ($tabel) {
                case 'd_pkt_prognosis':
                    $fno =  array('kddept', 'kdsatker', 'kdpaket', 'kdspaket', 'iddipa', 'kdbelanja', 'prognosis', 'userid', 'stsupdate', 'kode_ang');
                    break;
                case 'tlokasi':
                    $fno =  array('kdlokasi', 'kdlokasi2', 'nmlokasi', 'ibukota', 'nmsingkatlok', 'kdpulau', 'urutan');
                    break;
                case 'tkabkota':
                    $fno =  array('kdlokasi', 'kdkabkota', 'nmkabkota', 'kdkppn', 'kdupdate', 'updater', 'tglupdate');
                    break;
                case 'tematik_link':
                    $fno =  array('ID', 'grup', 'kdtematik', 'kdunit', 'kode_ang', 'kdsatker', 'kdpaket', 'nmpaket', 'pg', 'kdlokasi');
                    break;
            }
            // $tabel = "d_pkt_prognosis";


            $pull_get_file =  $this->ImportdataModel->getDok(["type" => $tabel], "no_order_by")->get()->getRowArray();
            $namefile = (isset($pull_get_file['nmfile']) ? $pull_get_file['nmfile'] : '');
            $namefilesql = (isset($pull_get_file['sqlfile_nm']) ? $pull_get_file['sqlfile_nm'] : '');

            $pullcount = $this->ImportdataModel->getDok(["type" => $tabel], "no_order_by")->countAllResults();

            $targetDir = WRITEPATH . "emon" . DIRECTORY_SEPARATOR . "FileTxt";
            $targetDir1 = WRITEPATH . "emon" . DIRECTORY_SEPARATOR . "FileSql";


            if ($pullcount > 30) {

                $query = $this->ImportdataModel->deleteFiles(["nmfile" => $namefile, "type" => $tabel]);

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
                    'type' => $tabel
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
                                        $v .= ($v ? ',' : '') . '"' . str_replace(array("\n", "\t"), array(" ", " "), $value) . '"';
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

                    $update_convert_to_sql = $this->ImportdataModel->where(['idpull' => $idpull, 'type' => $tabel])->set($post)->update();


                    $post = [
                        'import_dt'    => date("ymdHis"),
                        'import_uid'   => "server",
                        'st'            => 2
                    ];
                    $update_convert_to_sql_import = $this->ImportdataModel->where(['st' => 3, 'type' => $tabel])->set($post)->update();
                    // dd($update_convert_to_sql_import);

                    $this->importsql($nmFile);

                    $post = [
                        'import_dt'    => date("ymdHis"),
                        'import_uid'   => "server",
                        'aktif_dt'    => date("ymdHis"),
                        'aktif_uid'   => "server",
                        'st'            => 3
                    ];

                    $update_convert_to_sql_import = $this->ImportdataModel->where(['idpull' => $idpull, 'type' => $tabel])->set($post)->update();
                }
            }


            // $data = file_get_contents("https://emonitoring.pu.go.id/ws_sda/rekap_unor");



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
