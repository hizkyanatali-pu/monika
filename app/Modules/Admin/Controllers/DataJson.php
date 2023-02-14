<?php

namespace Modules\Admin\Controllers;
use CodeIgniter\API\ResponseTrait;
use Modules\Admin\Models\KinerjaOutputBulananModel;

class DataJson extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct() {

        $this->kinerja        = new KinerjaOutputBulananModel();
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);

        $this->dbUtama                       = \Config\Database::connect();;
    }

    public function getData()
    {
        return json_encode($this->kinerja->getPaket());
    }

    

    public function downloadDataTable($tableName)
    {
        $allowedTable = [
            'dokumenpk_log_satker',
            'dokumenpk_log_satker_kegiatan',
            'dokumenpk_log_satker_rows',
            'dokumenpk_satker',
            'dokumenpk_satker_kegiatan',
            'dokumenpk_satker_rows',
            'dokumen_pk_template',
            'dokumen_pk_template_akses',
            'dokumen_pk_template_info',
            'dokumen_pk_template_kegiatan',
            'dokumen_pk_template_row',
            'dokumen_pk_template_rowrumus'
        ];

        if (in_array($tableName, $allowedTable)) {
            $response =  [ 'data' => $this->dbUtama->table($tableName)->get()->getResult()];
        }
        else {
            $response = [
                'status' => 'fail',
                'message' => 'tidak dapat mengakses data'
            ];
        }   

        return $this->respond($response);
    }
}