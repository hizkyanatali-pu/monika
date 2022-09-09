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

        $this->dokumenPK               = $this->db->table('dokumen_pk_template');
        $this->dokumenPK_row           = $this->db->table('dokumen_pk_template_row');
        $this->dokumenPK_kegiatan      = $this->db->table('dokumen_pk_template_kegiatan');
        $this->dokumenPK_info          = $this->db->table('dokumen_pk_template_info');
        $this->dokumenPK_akses         = $this->db->table('dokumen_pk_template_akses');
        $this->tempExportBigdataColumn = $this->db->table("temp_export_bigdata_column");
        $this->tableSatker             = $this->db->table("m_satker");
        $this->tableBalai              = $this->db->table("m_balai");
        $this->tableKegiatan           = $this->db->table("tgiat");
        $this->tableProgram            = $this->db->table("tprogram");


        $this->request = \Config\Services::request();
    }



    public function template()
    {
        $sessionYear = $this->user['tahun'];

        return view('Modules\Admin\Views\DokumenPK\template.php', [
            'data'        => $this->dokumenPK->where('deleted_at is NULL', NULL, false)->get()->getResult(),
            'allSatker'   => $this->tableSatker->whereNotIn('satker', ['', '1'])->get()->getResult(),
            'allBalai'    => $this->tableBalai->get()->getResult(),
            'allKegiatan' => $this->tableKegiatan->where('tahun_anggaran', $sessionYear)->get()->getResult(),
            'allProgram'  => $this->tableProgram->get()->getResult(),
            'sessionYear' => $sessionYear
        ]);
    }
}