<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;
// use Modules\Admin\Models\AksesModel;

class KinerjaOutputBulananModel extends Model
{

    protected $table      = 'paket';
    protected $primaryKey = 'kode';

    protected $allowedFields = ['kode', 'kdoutput', 'kdsuboutput', 'kdkmpnen', 'kdgiat', 'kdprogram', 'vol', 'renk', 'rtot', 'renf_b5', 'ufis', 'pg', 'rr_b1', 'renk_b1', 'renf_b1', 'ff_b1'];


    function getoutputname($kdprogram, $kdgiat, $kdoutput, $kdsoutput, $kdro = null)
    {
        $like = '';
        if(!empty($kdro)){

            $like = ' AND (ursoutput ';
            foreach($kdro as $key => $x){

                if($key > 0 && ($key <= count($kdro))){

                    $like .= " or like '%$x%'";
                }else{
                
                    $like .= "like '%$x%'";   
                }
            }
            $like .= ')';
        }
        $builder = $this->db->query("SELECT ursoutput FROM d_soutput WHERE 
            kdprogram = '$kdprogram' AND kdgiat = '$kdgiat' AND kdoutput ='$kdoutput' 
             AND kdsoutput = '$kdsoutput' {$like}")->getRow();
        return $builder->ursoutput;
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
<<<<<<< HEAD
}
=======

    public function data($param, $kd_kegiatan = null, $kd_output = null){
        
        error_reporting(0);
        $start = isset($param['start']) ? $param['start'] : 0;
        $length = isset($param['length']) ? $param['length'] : 10;
        $kd_program = $param['inputs']['kode_program'];
        $kd_komponen = $param['inputs']['kode_komponen'];
        $kd_ro = $param['inputs']['kode_ro'];
        $bulan = decrypt_url($param['inputs']['bulan']);
        if ($bulan == '') {
            $bulan = date('n');
        }

        // $like = '';
        // if(!empty($kd_ro)){
    
        //     $like = ' AND (ursoutput ';
        //     foreach($kd_ro as $key => $x){
    
        //         if($key > 0 && ($key <= count($kd_ro))){
    
        //             $like .= " or like '%$x%'";
        //         }else{
                 
        //             $like .= "like '%$x%'";   
        //         }
        //     }
        //     $like .= ')';
        // }

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
            
        //     $limit = "LIMIT ".$start.", ".$length;
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
        {$like}
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

    public function getKomponenProgram($kdprogram){

        $query = $this->db->table("paket");
        $query->select("SUM(REPLACE(vol,',','.')) as target, SUM(pg) as pagu");
        $query->where("kdprogram", $kdprogram);
        // $query->where("p.kdkmpnen", $kdkomponen);

        return $query->get();
    }

    public function getKomponenProgram2($kdprogram, $field){

        $realisasi = $field['realisasi'];
        $keu_rn = $field['keu_rn'];
        $keu_rl = $field['keu_rl'];
        $fis_rn = $field['fis_rn'];
        $fis_rl = $field['fis_rl'];

        $query = $this->db->table("paket");
        $query->select("
        SUM($realisasi) as realisasi, 
        SUM($keu_rn) / sum(pg) * 100 / (SELECT COUNT($keu_rn) FROM paket WHERE kdprogram = '$kdprogram') as keu_rn,
        SUM($keu_rl) / sum(pg) * 100 / (SELECT COUNT($keu_rl) FROM paket WHERE kdprogram = '$kdprogram') as keu_rl,
        SUM($fis_rn) / (SELECT COUNT($fis_rn) FROM paket WHERE kdprogram = '$kdprogram') as fis_rn,
        SUM($fis_rl) / SUM(pg) * 100 / (SELECT COUNT($fis_rl) FROM paket WHERE kdprogram = '$kdprogram') as fis_rl
        ");

        //backup - dengan kode output dan kode komponen
        // $query->select("
        // SUM($realisasi) as realisasi, 
        // SUM($keu_rn) / sum(pg) * 100 / (SELECT COUNT($keu_rn) FROM paket WHERE kdprogram = '$kdprogram' AND kdoutput IN ($kdoutput) AND kdkmpnen = '$kdkomponen') as keu_rn,
        // SUM($keu_rl) / sum(pg) * 100 / (SELECT COUNT($keu_rl) FROM paket WHERE kdprogram = '$kdprogram' AND kdoutput IN ($kdoutput) AND kdkmpnen = '$kdkomponen' ) as keu_rl,
        // SUM($fis_rn) / (SELECT COUNT($fis_rn) FROM paket WHERE kdprogram = '$kdprogram' AND kdoutput IN ($kdoutput) AND kdkmpnen = '$kdkomponen') as fis_rn,
        // SUM($field1) / SUM(pg) * 100 / (SELECT COUNT($field1) FROM paket WHERE kdprogram = '$kdprogram' AND kdoutput IN ($kdoutput) AND kdkmpnen = '$kdkomponen' ) as fis_rl
        // ");

        $query->where("kdprogram", $kdprogram);
        // $query->whereIn("p.kdoutput", $kdoutput);
        // $query->whereIn("p.kdkmpnen", $kdkomponen);

        return $query->get();
    }

    public function getKomponenKegiatan($kdprogram, $kdkegiatan){

        $temp_kegiatan = str_replace("'", "", $kdkegiatan);
        $query = $this->db->table("paket");
        $query->select("SUM(REPLACE(vol,',','.')) as target, SUM(pg) as pagu");
        $query->where("kdprogram", $kdprogram);
        $query->where("kdgiat", $kdkegiatan);

        return $query->get();
    }

    public function getKomponenKegiatan2($kdprogram, $kdkegiatan, $field){

        $temp_kegiatan = str_replace("'", "", $kdkegiatan);
        $realisasi = $field['realisasi'];
        $keu_rn = $field['keu_rn'];
        $keu_rl = $field['keu_rl'];
        $fis_rn = $field['fis_rn'];
        $fis_rl = $field['fis_rl'];

        $query = $this->db->table("paket");
        $query->select("
        SUM($realisasi) as realisasi, 
        SUM($keu_rn) / sum(pg) * 100 / (SELECT COUNT($keu_rn) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan') as keu_rn,
        SUM($keu_rl) / sum(pg) * 100 / (SELECT COUNT($keu_rl) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan') as keu_rl,
        SUM($fis_rn) / (SELECT COUNT($fis_rn) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan') as fis_rn,
        SUM($fis_rl) / SUM(pg) * 100 / (SELECT COUNT($fis_rl) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan') as fis_rl
        ");

        $query->where("kdprogram", $kdprogram);
        $query->where("kdgiat", $kdkegiatan);
        // $query->whereIn("p.kdoutput", $kdoutput);
        // $query->whereIn("p.kdkmpnen", $kdkomponen);

        return $query->get();
    }

    public function getKomponenOutput($kdprogram, $kdkegiatan, $kdoutput){

        $temp_kegiatan = str_replace("'", "", $kdkegiatan);
        $temp_output = str_replace("'", "", $kdoutput);
        $query = $this->db->table("paket");
        $query->select("SUM(REPLACE(vol,',','.')) as target, SUM(pg) as pagu");
        $query->where("kdprogram", $kdprogram);
        $query->where("kdgiat", $kdkegiatan);
        $query->where("kdoutput", $kdoutput);

        return $query->get();
    }

    public function getKomponenOutput2($kdprogram, $kdkegiatan, $kdoutput, $field){

        $temp_kegiatan = str_replace("'", "", $kdkegiatan);
        $temp_output = str_replace("'", "", $kdoutput);
        $realisasi = $field['realisasi'];
        $keu_rn = $field['keu_rn'];
        $keu_rl = $field['keu_rl'];
        $fis_rn = $field['fis_rn'];
        $fis_rl = $field['fis_rl'];

        $query = $this->db->table("paket");
        $query->select("
        SUM($realisasi) as realisasi, 
        SUM($keu_rn) / sum(pg) * 100 / (SELECT COUNT($keu_rn) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan' AND kdoutput = '$kdoutput') as keu_rn,
        SUM($keu_rl) / sum(pg) * 100 / (SELECT COUNT($keu_rl) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan' AND kdoutput = '$kdoutput') as keu_rl,
        SUM($fis_rn) / (SELECT COUNT($fis_rn) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan') as fis_rn,
        SUM($fis_rl) / SUM(pg) * 100 / (SELECT COUNT($fis_rl) FROM paket WHERE kdprogram = '$kdprogram' AND kdgiat = '$kdkegiatan' AND kdoutput = '$kdoutput') as fis_rl
        ");

        $query->where("kdprogram", $kdprogram);
        $query->where("kdgiat", $kdkegiatan);
        $query->where("kdoutput", $kdoutput);
        // $query->whereIn("p.kdkmpnen", $kdkomponen);

        // return $temp_kegiatan;
        return $query->get();
    }
}
>>>>>>> 49bd92a322b513c092a991bea33097f4ffd1790d
