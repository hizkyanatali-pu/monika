<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;
use Modules\Admin\Models\AksesModel;

class RekapUnorModel extends Model
{

    protected $table      = 'monika_rekap_unor';
    protected $primaryKey = 'kdunit';

    protected $allowedFields = [
        'status', 'kdunit', 'nmunit', 'pagu_rpm', 'pagu_sbsn', 'pagu_phln', 'pagu_total', 'real_rpm', 'real_sbsn', 'real_phln', 'real_total', 'progres_keu', 'progres_fisik'
    ];

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect();
        $this->db_2 = \Config\Database::connect($dbcustom);
    }

    function getRekapUnor($w = '')
    {
        $this->akses = new AksesModel();
        $w = $this->akses->unitsatker("", $w);
        return $this->db->query("SELECT * FROM monika_rekap_unor JOIN tunitkerja ON monika_rekap_unor.kdunit = tunitkerja.kdunit ORDER BY progres_keu DESC ")->getResultArray();
    }
}
