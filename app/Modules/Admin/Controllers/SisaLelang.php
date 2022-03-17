<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
// use Modules\Admin\Models\KinerjaOutputBulananModel;


class SisaLelang extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
        $this->db_mysql = \Config\Database::connect();
    }

    public function index()
    {
        return view('Modules\Admin\Views\SisaLelang.php');
    }
}
