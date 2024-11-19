<?php

namespace Modules\Satker\Controllers;

use CodeIgniter\API\ResponseTrait;

class renstra_api extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->userUID = $this->user['uid'];
        $this->db = \Config\Database::connect();
        $this->templateOgiat = $this->db->table('renstra_template_ogiat');
    }

    public function getOutputKegiatanTagging()
    {

        $w = $this->request->getVar('satkerId');
        $templateid = $this->request->getVar('templateId');
        $templateRowid = $this->request->getVar('templateRowId');
        $_tahun = $this->request->getVar('_tahun') ?? $this->user['tahun'];

        if ($w == 0) {
            $jsonResponse =  json_encode(["message" => "tidak ada data"]);
            header('Content-Type: application/json');
            echo $jsonResponse;
            exit;
        }




        //ogiat
        $ogiat = $this->templateOgiat
            ->select("renstra_template_ogiat.*, 
                  renstra_template_subogiat.title as title2, 
                  renstra_template_subogiat.satuan_output, 
                  CASE WHEN '$templateRowid' = '11004'THEN NULL ELSE renstra_template_subogiat.satuan_outcome1 END as satuan_outcome1,
                  CASE WHEN '$templateRowid' = '11001' OR '$templateRowid' = '11002'  THEN NULL ELSE renstra_template_subogiat.satuan_outcome3 END as satuan_outcome3,
                 renstra_template_subogiat.satuan_outcome2,
                 ")
            ->join("renstra_template_subogiat", "renstra_template_subogiat.parent_id = renstra_template_ogiat.id")
            ->join("renstra_template_akses_ogiat", "renstra_template_akses_ogiat.ogiat_id = renstra_template_ogiat.id")
            ->where('template_id', $templateid)
            ->where('renstra_template_akses_ogiat.template_row_id', $templateRowid)
            ->get()->getResult();



        $jsonResponse = json_encode($ogiat, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        echo $jsonResponse;
    }
}
