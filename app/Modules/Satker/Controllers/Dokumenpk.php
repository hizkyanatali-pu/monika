<?php

namespace Modules\Satker\Controllers;

class Dokumenpk extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->templateDokumen = $this->db->table('dokumen_pk_template');
    }



    public function index(){
        return view('Modules\Satker\Views\Dokumenpk.php', [
            'templateDokumen' => $this->templateDokumen->get()->getResult()
        ]);
    }
}