<?php

namespace Modules\Admin\Controllers;

use \CodeIgniter\Controller;
use \Hermawan\DataTables\DataTable;
use Modules\Admin\Models\ImportdataSqliteModel;



class Preferensi extends \App\Controllers\BaseController
{
    public function __construct()
    {
        // $this->akses                = new AksesModel();
        $this->ImportdataSqliteModel        = new ImportdataSqliteModel();

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
    //fitur tarik data
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

        $where = $_POST['param'];

        $table = $this->db1->table('monika_pull_sqlite');
        $table->set('status_aktif', '0');
        $table->update();

        $table->set('status_aktif', '1');
        $table->where('nmfile', $where);
        $table->update();

        $tableSession = $this->db1->table('ci_sessions');
        $tableSession->truncate();
        $cache = \Config\Services::cache();
        $cache->clean();
    }


    public function uploadtoserver()
    {
        // die(json_encode(["ok"=>1, "info"=>$_REQUEST["namafile"]]));
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
        if (!empty($_REQUEST["name"])) {
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

            if ($countdata > 4) {

                unlink($targetDir . DIRECTORY_SEPARATOR . $result->nmfile);

                $query->where('nmfile', $result->nmfile);
                $query->delete();
            }


            $post = [
                'idpull' => null,
                'nmfile' => $fileName,
                'sizefile' =>  $_FILES["file"]["size"],
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
}
