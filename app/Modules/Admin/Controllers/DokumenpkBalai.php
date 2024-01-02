<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class DokumenpkBalai extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->dokumenSatker = $this->db->table('dokumenpk_satker');

        $this->dokumenPK          = $this->db->table('dokumen_pk_template_' . session('userData.tahun'));
        $this->dokumenPK_row      = $this->db->table('dokumen_pk_template_row');
        $this->dokumenPk_rowRumus = $this->db->table('dokumen_pk_template_rowrumus');
        $this->dokumenPK_kegiatan = $this->db->table('dokumen_pk_template_kegiatan');
        $this->dokumenPK_akses    = $this->db->table('dokumen_pk_template_akses');
        $this->tableBalai         = $this->db->table("m_balai");
        $this->tableProgram       = $this->db->table("tprogram");


        $this->request = \Config\Services::request();
    }



    public function template()
    {
        $sessionYear = $this->user['tahun'];

        return view('Modules\Admin\Views\DokumenPK\templateBalai.php', [
            'data'        => $this->dokumenPK->where('type', 'master-balai')->where('deleted_at is NULL', NULL, false)->get()->getResult(),
            'allBalai'    => $this->tableBalai->get()->getResult(),
            'allProgram'  => $this->tableProgram->get()->getResult(),
            'sessionYear' => $sessionYear
        ]);
    }
}
