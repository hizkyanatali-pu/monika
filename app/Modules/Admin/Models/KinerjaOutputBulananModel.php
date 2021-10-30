<?php

namespace Modules\Admin\Models;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;
// use Modules\Admin\Models\AksesModel;

class KinerjaOutputBulananModel extends Model
{

    protected $table      = 'paket pkt';
    protected $primaryKey = 'kode';
    // protected $dt;

    //fill with columns if want to order column
    // protected column_search = [];

    protected $allowedFields = ['kode', 'kdoutput', 'kdsuboutput', 'kdkmpnen', 'kdgiat', 'kdprogram', 'vol', 'renk', 'rtot', 'renf_b5', 'ufis', 'pg', 'rr_b1', 'renk_b1', 'renf_b1', 'ff_b1'];

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($this->dbcustom);
        $dt = $this->db->table($this->table);
    }

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

    public function data($param, $kd_kegiatan = null, $kd_output = null){
        
        error_reporting(0);
        $start = isset($param['start']) ? $param['start'] : 0;
        $length = isset($param['length']) ? $param['length'] : 10;
        $kd_program = $param['inputs']['kode_program'];
        $kd_komponen = $param['inputs']['kode_komponen'];
        $bulan = decrypt_url($param['inputs']['bulan']);
        if ($bulan == '') {
            $bulan = date('n');
        }

        if($kd_program || $kd_kegiatan || $kd_output || $kd_komponen){
            
            $where = "WHERE";
            if($kd_program != ''){
                
                $where .= " pkt.kdprogram = '$kd_program'";
            }
            if($kd_kegiatan != ''){

                $where .= " AND pkt.kdgiat IN ($kd_kegiatan)";
            }
            if($kd_output != ''){

                $where .= " AND pkt.kdoutput IN ($kd_output)";
            }
            if($kd_komponen != ''){

                $where .= " AND pkt.kdkmpnen = '$kd_komponen'";
            }
        }else{

            $where = '';
        }

        // if($length != -1){
            
            // $limit = "LIMIT ".$start.", ".$length;
        // }

        $total = $this->db->query("SELECT
        pkt.kdprogram
        FROM paket pkt 
        LEFT JOIN tprogram tp ON tp.kdprogram = pkt.kdprogram  
        LEFT JOIN tgiat ON tgiat.kdgiat = pkt.kdgiat  
        LEFT JOIN toutput ON pkt.kdgiat = toutput.kdgiat AND pkt.kdoutput = toutput.kdoutput
        {$where}
        ORDER BY pkt.kdgiat,pkt.kdoutput,pkt.kdsoutput");

        $query = $this->db->query("SELECT
        pkt.kdprogram,pkt.kdgiat,pkt.kdoutput,pkt.kdsoutput,pkt.kdkmpnen,tp.nmprogram,tgiat.nmgiat,toutput.nmoutput,toutput.sat,
        pkt.pg,CAST(REPLACE(pkt.vol,',','.') AS DECIMAL) vol,pkt.kdprogram || '.' || pkt.kdgiat || '.' || pkt.kdoutput || '.' || pkt.kdsoutput || pkt.kdkmpnen AS kode
        ,CASE
        WHEN pkt.rtot is null THEN 0
        WHEN pkt.rtot = '' THEN 0
        ELSE pkt.rtot
        END as rtot
        ,CASE
        WHEN pkt.rr_b$bulan is null THEN 0
        WHEN pkt.rr_b$bulan = '' THEN 0
        ELSE pkt.rr_b$bulan
        END as rr_b
        ,CASE
        WHEN pkt.renk_b$bulan is null THEN 0
        WHEN pkt.renk_b$bulan = '' THEN 0
        ELSE pkt.renk_b$bulan
        END as renk_b
        ,CASE
        WHEN pkt.renf_b$bulan is null THEN 0
        WHEN pkt.renf_b$bulan = '' THEN 0
        ELSE pkt.renf_b$bulan
        END as renf_b
        ,CASE
        WHEN pkt.ff_b$bulan is null THEN 0
        WHEN pkt.ff_b$bulan = '' THEN 0
        ELSE pkt.ff_b$bulan
        END as ff_b
        ,CASE
        WHEN pkt.ufis is null THEN 0
        WHEN pkt.ufis = '' THEN 0
        ELSE pkt.ufis
        END as ufis
        FROM paket pkt 
        LEFT JOIN tprogram tp ON tp.kdprogram = pkt.kdprogram  
        LEFT JOIN tgiat ON tgiat.kdgiat = pkt.kdgiat  
        LEFT JOIN toutput ON pkt.kdgiat = toutput.kdgiat AND pkt.kdoutput = toutput.kdoutput
        {$where}
        ORDER BY pkt.kdgiat,pkt.kdoutput,pkt.kdsoutput
        {$limit}");

        $datas = [
            'result' => $query->getResultArray(),
            // 'countFiltered' => $total->getNumRows(),
            'countAll' => $total->getNumRows(),
        ];

        return $datas;
    }

    public function getDataKegiatan(){

        $query = $this->db->table('tgiat');
        $query->select('kdgiat kode, nmgiat nama');
        $query->where('kdunit', '06');
        $query->groupBy('kode');

        $response = $query->get();

        return $response;
    }

    public function getDataOutput($param){

        $query = $this->db->table('toutput');
        $query->select('kdoutput kode, nmoutput nama');
        $query->whereIn('kdgiat', $param);
        $query->where('kdunit', '06');
        $query->groupBy('kode');

        $response = $query->get();

        return $response;
    }

    public function getDataSOutput($param){

        $query = $this->db->table('d_soutput');
        $query->select('kdsoutput kode, ursoutput nama');
        $query->whereIn('kdgiat', $param['kode_kegiatan']);
        $query->whereIn('kdoutput', $param['kode_output']);
        $query->where('kdunit', '06');
        $query->groupBy('kode');

        $response = $query->get();

        return $response;
    }
}
