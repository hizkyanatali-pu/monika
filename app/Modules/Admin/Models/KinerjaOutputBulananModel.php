
<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;
// use Modules\Admin\Models\AksesModel;

class KinerjaOutputBulananModelModel extends Model
{

    protected $table      = 'paket';
    protected $primaryKey = 'kode';

    protected $allowedFields = ['kode', 'kdoutput', 'kdsuboutput', 'kdkmpnen', 'kdgiat', 'kdprogram', 'vol', 'renk', 'rtot', 'renf_b5', 'ufis', 'pg', 'rr_b1', 'renk_b1', 'renf_b1', 'ff_b1'];


    function getPaket()
    {
        // $this->akses = new AksesModel();
        // $w = $this->akses->unitbalai("b", $w);
        // $w = $this->akses->unitsatker("md", $w, 'kdsatker');
        // $w = $this->akses->unitgiat("md", $w, 'kdgiat');

        $qkdprog = "SELECT
            pkt.kdprogram FROM paket ";
        return $this->db->query($qkdprog)->getResultArray();
    }
}
