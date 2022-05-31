<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
// use Modules\Admin\Models\KinerjaOutputBulananModel;


class Blokir extends \App\Controllers\BaseController
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



    public function index($bulan = '', $keyword = '')
    {
        $dataBlokir = $this->db->query("
            SELECT 
                a.kdgiat,
                b.nmgiat,
                (SELECT IFNULL(SUM(c.RPHBLOKIR), 0) FROM d_item c WHERE c.kdgiat=a.kdgiat AND c.kdbeban IN ('A', 'C') ) AS	rpm,
                (SELECT IFNULL(SUM(d.RPHBLOKIR), 0) FROM d_item d WHERE d.kdgiat=a.kdgiat AND d.kdbeban IN ('K')) AS sbsn,
                (SELECT IFNULL(SUM(e.BLOKIRPHLN), 0) FROM d_item e WHERE e.kdgiat=a.kdgiat) AS phln
            FROM 
                d_item a
                LEFT JOIN tgiat b ON a.kdgiat = b.kdgiat
            GROUP BY a.kdgiat
        ")->getResultArray();

        return view('Modules\Admin\Views\Blokir\index.php', [
            'dataBlokir' => $dataBlokir
        ]);
    }
}
