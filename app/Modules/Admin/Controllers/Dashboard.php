<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\TematikModel;
use Modules\Admin\Models\RekapUnorModel;





class Dashboard extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);

        $this->TematikModel = new TematikModel();
        $this->RekapUnorModel = new RekapUnorModel();
    }

    public function index()
    {
        $grupData = $this->rekapGroupData();
        $qdata = $this->TematikModel->getListRekap($grupData);

        $query = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span");
        $query1 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'");
        $query2 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'");


        $row = $query->getRow();
        $row1 = $query1->getRow();
        $row2 = $query2->getRow();


        $rekapUnor =  $this->RekapUnorModel->getRekapUnor();

        $data = array(
            'title' => 'Dashboard',
            'data' => $qdata,
            'totaldjs' => $row,
            'totalketahanansda' => $row1,
            'totaldukungan' => $row2,
            'rekapunor' => $rekapUnor

        );
        return view('Modules\Admin\Views\Dashboard', $data);
    }

    private function rekapGroupData()
    {
        return [
            [
                'title' => 'Food Estate',
                'tematikCode' => ["'TXX0003'"]
            ],
            [
                'title' => 'Kawasan Industri',
                'tematikCode' => ["'T060012'"]
            ],
            [
                'title' => 'KSPN',
                'tematikCode' => ["'kspn01'", "'kspn02'", "'kspn03'", "'kspn04'", "'kspn05'"]
            ]
        ];
    }
}
