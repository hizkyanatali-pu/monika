<?php

namespace Modules\Admin\Controllers;
use Modules\Admin\Models\KinerjaOutputBulananModel;

class DataJson extends \App\Controllers\BaseController
{
    public function __construct() {

        $this->kinerja        = new KinerjaOutputBulananModel();
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
       
    }

    public function getData()
    {

        return json_encode($this->kinerja->getPaket());


       
    }

   
}