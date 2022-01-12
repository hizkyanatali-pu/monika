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

    function getRekapUnor($w = '', $sort = 'progres_keu DESC')
    {
        $this->akses = new AksesModel();
        $w = $this->akses->unitsatker("", $w);
        $data['unor'] = $this->db->query("SELECT * FROM monika_rekap_unor_{$this->user['tahun']} JOIN tunitkerja ON monika_rekap_unor_{$this->user['tahun']}.kdunit = tunitkerja.kdunit ORDER BY $sort ")->getResultArray();
        $data['total'] = $this->db->query("SELECT * FROM monika_rekap_unor_{$this->user['tahun']} WHERE kdunit='033'")->getRowArray();
        return $data;
    }
}
