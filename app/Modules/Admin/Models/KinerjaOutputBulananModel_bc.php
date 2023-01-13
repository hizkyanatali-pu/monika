<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;
// use Modules\Admin\Models\AksesModel;

class KinerjaOutputBulananModel extends Model
{


    protected $table      = 'paket';
    protected $primaryKey = 'kode';

    protected $allowedFields = ['kode', 'kdoutput', 'kdsuboutput', 'kdkmpnen', 'kdgiat', 'kdprogram', 'vol', 'renk', 'rtot', 'renf_b5', 'ufis', 'pg', 'rr_b1', 'renk_b1', 'renf_b1', 'ff_b1'];
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
    }
    function getPaket()
    {
        // $this->akses = new AksesModel();
        // $w = $this->akses->unitbalai("b", $w);
        // $w = $this->akses->unitsatker("md", $w, 'kdsatker');
        // $w = $this->akses->unitgiat("md", $w, 'kdgiat');

        $qkdprog = "select 
        kdprogram
        (
            select 
                '[' || GROUP_CONCAT(
                    JSON_OBJECT(
                        'kdgiat',paket.kdgiat,
                        'kdoutput', paket.kdoutput,

                    )
                ) || ']'
            from 
                paket 
 
        ) as paket1
    from 
        paket
    where
        (select count(paket.kdunit) from paket \) > 0";
        return $this->db->query($qkdprog)->getResultArray();
    }
}
