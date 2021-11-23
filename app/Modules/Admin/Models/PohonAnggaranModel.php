<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;

class PohonAnggaranModel extends Model
{
    protected $table      = 'monika_kontrak';
    protected $primaryKey = 'kdpaket';

    protected $allowedFields = array('tahun', 'kdsatker', 'kdprogram', 'kdgiat', 'kdoutput', 'kdsoutput', 'kdkmpnen', 'kdskmpnen', 'kdpaket', 'kdls', 'nmpaket', 'kdpengadaan', 'kdkategori', 'kdjnskon', 'rkn_nama', 'rkn_npwp', 'nomor_kontrak', 'nilai_kontrak', 'tanggal_kontrak', 'tgl_spmk', 'waktu', 'status_tender');

    public function getDataKontrak($w = "")
    {
        // $query = $this->table($this->table)
        //     ->select("SUM(monika_data.pagu_total) nilai_kontrak,
        //     COUNT(nilai_kontrak) jml_paket")
        //     ->join('monika_data', "{$this->table}.kdpaket = monika_data.kdpaket", 'left')
        //     ;
        // if ($w != "") $query->where($w);
        $db = \Config\Database::connect();
       $query = $db->query("SELECT
            SUM(monika_data.pagu_total) nilai_kontrak,
           COUNT(mnk_kontrak.nilai_kontrak) jml_paket

    FROM
        monika_data
    RIGHT JOIN (
        SELECT
            monika_kontrak.nilai_kontrak,
            monika_kontrak.status_tender,
            CASE
        WHEN LENGTH(monika_kontrak.kdpaket) - LENGTH(
            REPLACE (
                monika_kontrak.kdpaket,
                '.',
                ''
            )
        ) > 7 THEN
            SUBSTRING(
                monika_kontrak.kdpaket,
                1,
                CHAR_LENGTH(monika_kontrak.kdpaket) - 2
            )
        ELSE
            monika_kontrak.kdpaket
        END AS kdpaket_kontrak
        FROM
            monika_kontrak
    ) mnk_kontrak ON monika_data.kdpaket = mnk_kontrak.kdpaket_kontrak WHERE mnk_kontrak.status_tender = :status_tender:",
    
    $w
    );

     
        $return =  $query->getRowArray();

        return $return;
    }

    public function getDataSisaDataTidakTerserap($w = "")
    {
        $db = \Config\Database::connect();
        $query = $db->table("monika_rekap_unor")
            ->select("pagu_rpm,pagu_sbsn,pagu_phln,pagu_total,real_rpm,real_sbsn,real_phln,real_total");
        if ($w != "") $query->where($w);

        $return =  $query->get()->getRowArray();

        return $return;
    }


    public function getDataBelumLelangNilai($w = "")
    {
        // $query = $this->table($this->table)
        //     ->select("

        //     sum(pagu_rpm) AS total_rpm,
        //     sum(pagu_phln)AS total_phln,
        //     sum(pagu_sbsn) AS total_sbsn
            
        //     ")
        //     ->join('monika_data', "{$this->table}.kdpaket = monika_data.kdpaket", 'left')
        //     ;
        // if ($w != "") $query->where($w);

        // $return =  $query->get()->getRowArray();

        // return $return;


        $db = \Config\Database::connect();
        $query = $db->query("SELECT
        sum(monika_data.pagu_rpm) AS total_rpm,
        sum(monika_data.pagu_phln)AS total_phln,
        sum(monika_data.pagu_sbsn) AS total_sbsn
 
     FROM
         monika_data
     RIGHT JOIN (
         SELECT
             monika_kontrak.nilai_kontrak,
             monika_kontrak.kdjnskon,
             CASE
         WHEN LENGTH(monika_kontrak.kdpaket) - LENGTH(
             REPLACE (
                 monika_kontrak.kdpaket,
                 '.',
                 ''
             )
         ) > 7 THEN
             SUBSTRING(
                 monika_kontrak.kdpaket,
                 1,
                 CHAR_LENGTH(monika_kontrak.kdpaket) - 2
             )
         ELSE
             monika_kontrak.kdpaket
         END AS kdpaket_kontrak
         FROM
             monika_kontrak
     ) mnk_kontrak ON monika_data.kdpaket = mnk_kontrak.kdpaket_kontrak WHERE mnk_kontrak.kdjnskon IN (:kdjnskon:) ",
     
     $w
     );
 
      
         $return =  $query->getRowArray();

         return $return;
    }

    
    public function getDataBelumLelangList($w = "",$pagu)
    {
        // $query = $this->table($this->table)
        //     ->select("

        //     monika_data.nmpaket,
        //     monika_data.$pagu
            
        //     ")
        //     ->join('monika_data', "{$this->table}.kdpaket = monika_data.kdpaket", 'left')
        //     ;
        // if ($w != "") $query->where($w)->orderBy("monika_data.$pagu", "DESC")->limit(4);

        // $return =  $query->get()->getResultArray();

        // return $return;


        $db = \Config\Database::connect();
        $query = $db->query("SELECT
        monika_data.nmpaket,
        monika_data.$pagu
 
     FROM
         monika_data
     RIGHT JOIN (
         SELECT
             monika_kontrak.nilai_kontrak,
             monika_kontrak.kdjnskon,
             CASE
         WHEN LENGTH(monika_kontrak.kdpaket) - LENGTH(
             REPLACE (
                 monika_kontrak.kdpaket,
                 '.',
                 ''
             )
         ) > 7 THEN
             SUBSTRING(
                 monika_kontrak.kdpaket,
                 1,
                 CHAR_LENGTH(monika_kontrak.kdpaket) - 2
             )
         ELSE
             monika_kontrak.kdpaket
         END AS kdpaket_kontrak
         FROM
             monika_kontrak
     ) mnk_kontrak ON monika_data.kdpaket = mnk_kontrak.kdpaket_kontrak WHERE mnk_kontrak.kdjnskon IN (:kdjnskon:) LIMIT 4",
     
     $w
     );
 
      
      $return =  $query->getResultArray();

        return $return;


    }


}
