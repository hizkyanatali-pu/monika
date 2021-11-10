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
        $query = $this->table($this->table)
            ->select("SUM(nilai_kontrak) AS nilai_kontrak,COUNT(nilai_kontrak) jml_paket");
        if ($w != "") $query->where($w);

        $return =  $query->get()->getRowArray();

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
}
