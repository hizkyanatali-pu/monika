<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;

class PohonAnggaranModel extends Model
{
    protected $table      = 'monika_kontrak';
    protected $primaryKey = 'kdpaket';

    protected $allowedFields = array('tahun', 'kdsatker', 'kdprogram', 'kdgiat', 'kdoutput', 'kdsoutput', 'kdkmpnen', 'kdskmpnen', 'kdpaket', 'kdls', 'nmpaket', 'kdpengadaan', 'kdkategori', 'kdjnskon', 'rkn_nama', 'rkn_npwp', 'nomor_kontrak', 'nilai_kontrak', 'tanggal_kontrak', 'tgl_spmk', 'waktu', 'status_tender');
    public function __construct()
    {
        $session = session();
        $this->user = $session->get('userData');
    }

    public function getDataKontrak($w = [], $_filterDateStart = null, $_filterDateEnd = null)
    {
        /*
            // $query = $this->table($this->table)
            //     ->select("SUM(monika_data_{$this->user['tahun']}.pagu_total) nilai_kontrak,
            //     COUNT(nilai_kontrak) jml_paket")
            //     ->join('monika_data_{$this->user['tahun']}', "{$this->table}.kdpaket = monika_data_{$this->user['tahun']}.kdpaket", 'left')
            //     ;
            // if ($w != "") $query->where($w);
            // $db = \Config\Database::connect();
            //     $query = $db->query(
            //         "SELECT
            //         SUM(monika_data_{$this->user['tahun']}.pagu_total) nilai_kontrak,
            //        COUNT(mnk_kontrak.nilai_kontrak) jml_paket

            // FROM
            //     monika_data_{$this->user['tahun']}
            // RIGHT JOIN (
            //     SELECT
            //         monika_kontrak_{$this->user['tahun']}.nilai_kontrak,
            //         monika_kontrak_{$this->user['tahun']}.status_tender,
            //         CASE
            //     WHEN LENGTH(monika_kontrak_{$this->user['tahun']}.kdpaket) - LENGTH(
            //         REPLACE (
            //             monika_kontrak_{$this->user['tahun']}.kdpaket,
            //             '.',
            //             ''
            //         )
            //     ) > 7 THEN
            //         SUBSTRING(
            //             monika_kontrak_{$this->user['tahun']}.kdpaket,
            //             1,
            //             CHAR_LENGTH(monika_kontrak_{$this->user['tahun']}.kdpaket) - 2
            //         )
            //     ELSE
            //         monika_kontrak_{$this->user['tahun']}.kdpaket
            //     END AS kdpaket_kontrak
            //     FROM
            //         monika_kontrak_{$this->user['tahun']}
            // ) mnk_kontrak ON monika_data_{$this->user['tahun']}.kdpaket = mnk_kontrak.kdpaket_kontrak WHERE mnk_kontrak.status_tender = :status_tender:",

            //         $w
            //     );
        */

        $whereCondition = " WHERE ";
        if (array_key_exists('status_tender', $w)) {
            if (is_array($w['status_tender'])) {
                $mapStatusTender = array_map(function ($arr) {
                    return "'$arr'";
                }, $w['status_tender']);
                $implodeStatustender = implode(",", $mapStatusTender);
                $whereCondition .= " status_tender IN ($implodeStatustender) ";
            } else {
                $whereCondition .= " status_tender = :status_tender: ";
            }
        }

        if (array_key_exists('jenis_kontrak', $w)) {
            $preffixCondition = $whereCondition == " WHERE " ? "" : "AND";

            $mapJenisKontrak = array_map(function ($arr) {
                return "'$arr'";
            }, $w['jenis_kontrak']);
            $implodeJenisKontrak = implode(",", $mapJenisKontrak);
            $whereCondition .= " $preffixCondition kdjnskon IN ($implodeJenisKontrak) ";
        };

        $whereCondition_dateFilter = "";
        $whereCondition_dateFilter_andPrefix =  $whereCondition == " WHERE " ? '' : 'AND';
        if (!is_null($_filterDateStart) && !is_null($_filterDateEnd)) $whereCondition_dateFilter = " $whereCondition_dateFilter_andPrefix (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) >= '$_filterDateStart' AND  (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) <= '$_filterDateEnd'";

        $db = \Config\Database::connect();

        $query = $db->query("SELECT 
            COUNT(pfis) jml_paket,
            SUM(pfis) as nilai_kontrak  
        FROM 
            monika_kontrak_{$this->user['tahun']}  
        $whereCondition $whereCondition_dateFilter", $w);
        $return =  $query->getRowArray();
        return $return;
    }

    public function getDataSisaDataTidakTerserap($sumber_dana = "", $pengadaan = [], $drop = '')
    {
        ini_set('max_execution_time', 0);

        $wherePengadaan = '';
        if (count($pengadaan) > 0) {
            $mapJenisKontrak = array_map(function ($arr) {
                return "'$arr'";
            }, $pengadaan);
            $implodeJenisKontrak = implode(",", $mapJenisKontrak);
            $wherePengadaan = "AND pengadaan IN ($implodeJenisKontrak)";
        }

        $whereDrop = $drop ? " AND real_$sumber_dana < 1 AND prognosis < 1 AND pengadaan NOT IN ('AU','S')" : "";

        $db = \Config\Database::connect();
        $qdata['monika_data'] = $db->query("SELECT
            SUM(prognosis) / 1000 as prognosis,SUM(pagu_$sumber_dana)/1000 as pagu, 
            SUM(pagu_$sumber_dana - prognosis) / 1000 AS sisaPagu
            FROM
            monika_data_{$this->user['tahun']} md
            WHERE
            pagu_$sumber_dana != 0
            AND pagu_$sumber_dana IS NOT NULL $wherePengadaan $whereDrop
        ")->getRowArray();

        $qdata['monika_rekap'] = $db->query("SELECT
            pagu_rpm,pagu_sbsn,pagu_phln,pagu_total,real_rpm,real_sbsn,real_phln,real_total
            FROM monika_rekap_unor_{$this->user['tahun']}
            WHERE kdunit = '06'
        ")->getRowArray();

        return $qdata;
    }

    public function getDataSisaDataTerserap($sumber_dana = "", $pengadaan = [], $drop = '')
    {
        ini_set('max_execution_time', 0);

        $wherePengadaan = '';
        if (count($pengadaan) > 0) {
            $mapJenisKontrak = array_map(function ($arr) {
                return "'$arr'";
            }, $pengadaan);
            $implodeJenisKontrak = implode(",", $mapJenisKontrak);
            $wherePengadaan = "AND pengadaan IN ($implodeJenisKontrak)";
        }

        $whereDrop = $drop ? " AND real_$sumber_dana < 1 AND prognosis < 1 AND pengadaan NOT IN ('AU','S')" : "";

        $db = \Config\Database::connect();
        $qdata['monika_data'] = $db->query("SELECT
            SUM(prognosis) / 1000 as prognosis,SUM(pagu_$sumber_dana)/1000 as pagu, 
            SUM(pagu_$sumber_dana - prognosis) / 1000 AS sisaPagu
            FROM
            monika_data_{$this->user['tahun']} md
            WHERE
            pagu_$sumber_dana != 0
            AND pagu_$sumber_dana IS NOT NULL $wherePengadaan $whereDrop
        ")->getRowArray();

        $qdata['monika_rekap'] = $db->query("SELECT
            pagu_rpm,pagu_sbsn,pagu_phln,pagu_total,real_rpm,real_sbsn,real_phln,real_total
            FROM monika_rekap_unor_{$this->user['tahun']}
            WHERE kdunit = '06'
        ")->getRowArray();

        return $qdata;
    }

    public function getDataBelumLelangNilai($w = "", $nama_pagu = "", $_filterDateStart = null, $_filterDateEnd = null)
    {
        // dd($nama_pagu);
        // $query = $this->table($this->table)
        //     ->select("

        //     sum(pagu_rpm) AS total_rpm,
        //     sum(pagu_phln)AS total_phln,
        //     sum(pagu_sbsn) AS total_sbsn

        //     ")
        //     ->join('monika_data_{$this->user['tahun']}', "{$this->table}.kdpaket = monika_data_{$this->user['tahun']}.kdpaket", 'left')
        //     ;
        // if ($w != "") $query->where($w);

        // $return =  $query->get()->getRowArray();

        // return $return;


        $db = \Config\Database::connect();
        //     $query = $db->query(
        //         "SELECT
        //     sum(monika_data_{$this->user['tahun']}.pagu_rpm) AS total_rpm,
        //     sum(monika_data_{$this->user['tahun']}.pagu_phln)AS total_phln,
        //     sum(monika_data_{$this->user['tahun']}.pagu_sbsn) AS total_sbsn,
        //     count(monika_data_{$this->user['tahun']}.nmpaket) AS jml_paket

        //  FROM
        //      monika_data_{$this->user['tahun']}
        //  RIGHT JOIN (
        //      SELECT
        //          monika_kontrak_{$this->user['tahun']}.nilai_kontrak,
        //          monika_kontrak_{$this->user['tahun']}.kdjnskon,
        //          monika_kontrak_{$this->user['tahun']}.status_tender,
        //          CASE
        //      WHEN LENGTH(monika_kontrak_{$this->user['tahun']}.kdpaket) - LENGTH(
        //          REPLACE (
        //              monika_kontrak_{$this->user['tahun']}.kdpaket,
        //              '.',
        //              ''
        //          )
        //      ) > 7 THEN
        //          SUBSTRING(
        //              monika_kontrak_{$this->user['tahun']}.kdpaket,
        //              1,
        //              CHAR_LENGTH(monika_kontrak_{$this->user['tahun']}.kdpaket) - 2
        //          )
        //      ELSE
        //          monika_kontrak_{$this->user['tahun']}.kdpaket
        //      END AS kdpaket_kontrak
        //      FROM
        //          monika_kontrak_{$this->user['tahun']}
        //  ) mnk_kontrak ON monika_data_{$this->user['tahun']}.kdpaket = mnk_kontrak.kdpaket_kontrak WHERE mnk_kontrak.kdjnskon IN ? AND mnk_kontrak.status_tender = 'Belum Lelang'
        //  AND
        //  monika_data_{$this->user['tahun']}.$nama_pagu != 0 AND monika_data_{$this->user['tahun']}.$nama_pagu IS NOT NULL
        //  ",

        //         $w
        //     );

        $whereCondition_dateFilter = "";
        if (!is_null($_filterDateStart) && !is_null($_filterDateEnd)) $whereCondition_dateFilter = " AND (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) >= '$_filterDateStart' AND  (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) <= '$_filterDateEnd' ";

        $query =  $db->query("SELECT sum(pfis) AS nilai_kontrak,count(nmpaket) AS jml_paket
        FROM monika_kontrak_{$this->user['tahun']} 
        WHERE  status_tender = 'Belum Lelang'
        AND sumber_dana = '{$nama_pagu}'
        $whereCondition_dateFilter
        AND kdjnskon IN ?
         ", $w);

        $return =  $query->getRowArray();

        return $return;
    }


    public function getDataBelumLelangList($w = "", $pagu = "", $_filterDateStart = null, $_filterDateEnd = null)
    {
        // $query = $this->table($this->table)
        //     ->select("

        //     monika_data_{$this->user['tahun']}.nmpaket,
        //     monika_data_{$this->user['tahun']}.$pagu

        //     ")
        //     ->join('monika_data_{$this->user['tahun']}', "{$this->table}.kdpaket = monika_data_{$this->user['tahun']}.kdpaket", 'left')
        //     ;
        // if ($w != "") $query->where($w)->orderBy("monika_data_{$this->user['tahun']}.$pagu", "DESC")->limit(4);

        // $return =  $query->get()->getResultArray();

        // return $return;


        $db = \Config\Database::connect();
        //     $query = $db->query(
        //         "SELECT
        //     monika_data_{$this->user['tahun']}.nmpaket,
        //     monika_data_{$this->user['tahun']}.$pagu

        //  FROM
        //      monika_data_{$this->user['tahun']}
        //  RIGHT JOIN (
        //      SELECT
        //          monika_kontrak_{$this->user['tahun']}.nilai_kontrak,
        //          monika_kontrak_{$this->user['tahun']}.kdjnskon,
        //          monika_kontrak_{$this->user['tahun']}.status_tender,
        //          CASE
        //      WHEN LENGTH(monika_kontrak_{$this->user['tahun']}.kdpaket) - LENGTH(
        //          REPLACE (
        //              monika_kontrak_{$this->user['tahun']}.kdpaket,
        //              '.',
        //              ''
        //          )
        //      ) > 7 THEN
        //          SUBSTRING(
        //              monika_kontrak_{$this->user['tahun']}.kdpaket,
        //              1,
        //              CHAR_LENGTH(monika_kontrak_{$this->user['tahun']}.kdpaket) - 2
        //          )
        //      ELSE
        //          monika_kontrak_{$this->user['tahun']}.kdpaket
        //      END AS kdpaket_kontrak
        //      FROM
        //          monika_kontrak_{$this->user['tahun']}
        //  ) mnk_kontrak ON monika_data_{$this->user['tahun']}.kdpaket = mnk_kontrak.kdpaket_kontrak WHERE mnk_kontrak.kdjnskon IN ? AND mnk_kontrak.status_tender = 'Belum Lelang' AND  monika_data_{$this->user['tahun']}.$pagu != 0 AND monika_data_{$this->user['tahun']}.$pagu IS NOT NULL ORDER BY monika_data_{$this->user['tahun']}.$pagu DESC LIMIT 4",

        //         $w
        //     );

        $whereCondition_dateFilter = "";
        if (!is_null($_filterDateStart) && !is_null($_filterDateEnd)) $whereCondition_dateFilter = " AND (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) >= '$_filterDateStart' AND  (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) <= '$_filterDateEnd' ";

        $query =  $db->query("SELECT nmpaket
                FROM monika_kontrak_{$this->user['tahun']} 
                WHERE  status_tender = 'Belum Lelang'
                AND sumber_dana = '{$pagu}'
                $whereCondition_dateFilter
                AND  kdjnskon IN ?  ORDER BY pfis DESC LIMIT 4", $w);



        $return =  $query->getResultArray();

        return $return;
    }



    public function getDataBelumLelangPerKegiatan($sumber_dana, $kdjnskon, $where = false, $_filterDateStart = null, $_filterDateEnd = null)
    {


        $db = \Config\Database::connect();

        ($where != false ? $where = " AND monika_data_{$this->user['tahun']}.$sumber_dana != 0 AND monika_data_{$this->user['tahun']}.$sumber_dana IS NOT NULL" : $where = "");

        $whereCondition_dateFilter = "";
        if (!is_null($_filterDateStart) && !is_null($_filterDateEnd)) $whereCondition_dateFilter = " AND (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) >= '$_filterDateStart' AND  (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) <= '$_filterDateEnd' ";

        $data = $db->query("
        
        SELECT
        monika_data_{$this->user['tahun']}.kdgiat,
        tgiat.nmgiat,
        monika_kontrak_{$this->user['tahun']}.nmpaket,
        SUM(monika_data_{$this->user['tahun']}.$sumber_dana) AS pagu,
        COUNT(monika_data_{$this->user['tahun']}.nmpaket) AS jml_paket,
        (
            JSON_OBJECT (
                'paket',
                GROUP_CONCAT(
                    CASE 
                        WHEN
                            (INSTR(monika_kontrak_{$this->user['tahun']}.nmpaket, ';')) > 0
                        THEN
						CONCAT('- ',SUBSTR(monika_kontrak_{$this->user['tahun']}.nmpaket, 1, (INSTR(monika_kontrak_{$this->user['tahun']}.nmpaket, ';')-1)))									
                        ELSE
                           CONCAT('- ',monika_kontrak_{$this->user['tahun']}.nmpaket)
                    END, '<br>'
                ) 
            )
        ) AS paket
    FROM
        monika_data_{$this->user['tahun']}
    RIGHT JOIN monika_kontrak_{$this->user['tahun']} ON monika_data_{$this->user['tahun']}.kdsatker = monika_kontrak_{$this->user['tahun']}.kdsatker
    AND monika_data_{$this->user['tahun']}.kdprogram = monika_kontrak_{$this->user['tahun']}.kdprogram
    AND monika_data_{$this->user['tahun']}.kdgiat = monika_kontrak_{$this->user['tahun']}.kdgiat
    AND monika_data_{$this->user['tahun']}.kdoutput = monika_kontrak_{$this->user['tahun']}.kdoutput
    AND monika_data_{$this->user['tahun']}.kdsoutput = monika_kontrak_{$this->user['tahun']}.kdsoutput
    AND monika_data_{$this->user['tahun']}.kdkmpnen = monika_kontrak_{$this->user['tahun']}.kdkmpnen
    AND monika_data_{$this->user['tahun']}.kdskmpnen = monika_kontrak_{$this->user['tahun']}.kdskmpnen
    LEFT JOIN tgiat ON tgiat.kdgiat = monika_data_{$this->user['tahun']}.kdgiat AND tgiat.tahun_anggaran = {$this->user['tahun']}
WHERE
        monika_kontrak_{$this->user['tahun']}.status_tender = 'Belum Lelang'
        AND monika_kontrak_{$this->user['tahun']}.kdjnskon IN ($kdjnskon)
    {$where}
    {$whereCondition_dateFilter}
    GROUP BY
        monika_kontrak_{$this->user['tahun']}.kdgiat
        
        ")->getResult();

        return array_map(function ($arr) {
            return (object) [
                'kdgiat'     => $arr->kdgiat,
                'nmgiat'     => $arr->nmgiat,
                'pagu'     => $arr->pagu,
                'jml_paket'     => $arr->jml_paket,
                'paketList' => json_decode($arr->paket)
            ];
        }, $data);
    }



    public function getDataBelumLelangPhlnMycProjectLoan($sumber_dana, $kdjnskon, $where = false, $_filterDateStart = null, $_filterDateEnd = null)
    {
        $db = \Config\Database::connect();

        ($where != false ? $where = " AND monika_data_{$this->user['tahun']}.$sumber_dana != 0 AND monika_data_{$this->user['tahun']}.$sumber_dana IS NOT NULL" : $where = "");

        $whereCondition_dateFilter = "";
        if (!is_null($_filterDateStart) && !is_null($_filterDateEnd)) $whereCondition_dateFilter = " AND (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) >= '$_filterDateStart' AND  (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) <= '$_filterDateEnd' ";

        $data = $db->query("
        
        SELECT
        t_register.nmloan,
        t_register.register,
        monika_kontrak_{$this->user['tahun']}.nmpaket,
        SUM(monika_data_{$this->user['tahun']}.$sumber_dana) AS pagu,
        COUNT(monika_data_{$this->user['tahun']}.nmpaket) AS jml_paket,
        (
            JSON_OBJECT (
                'paket',
                GROUP_CONCAT(
                    CASE 
                        WHEN
                            (INSTR(monika_kontrak_{$this->user['tahun']}.nmpaket, ';')) > 0
                        THEN
						CONCAT('- ',SUBSTR(monika_kontrak_{$this->user['tahun']}.nmpaket, 1, (INSTR(monika_kontrak_{$this->user['tahun']}.nmpaket, ';')-1)))									
                        ELSE
                           CONCAT('- ',monika_kontrak_{$this->user['tahun']}.nmpaket)
                    END, '<br>'
                ) 
            )
        ) AS paket
    FROM
        monika_data_{$this->user['tahun']}
    RIGHT JOIN monika_kontrak_{$this->user['tahun']} ON monika_data_{$this->user['tahun']}.kdsatker = monika_kontrak_{$this->user['tahun']}.kdsatker
    AND monika_data_{$this->user['tahun']}.kdprogram = monika_kontrak_{$this->user['tahun']}.kdprogram
    AND monika_data_{$this->user['tahun']}.kdgiat = monika_kontrak_{$this->user['tahun']}.kdgiat
    AND monika_data_{$this->user['tahun']}.kdoutput = monika_kontrak_{$this->user['tahun']}.kdoutput
    AND monika_data_{$this->user['tahun']}.kdsoutput = monika_kontrak_{$this->user['tahun']}.kdsoutput
    AND monika_data_{$this->user['tahun']}.kdkmpnen = monika_kontrak_{$this->user['tahun']}.kdkmpnen
    AND monika_data_{$this->user['tahun']}.kdskmpnen = monika_kontrak_{$this->user['tahun']}.kdskmpnen
    LEFT JOIN 
    (

        SELECT * FROM monika_paket_register_{$this->user['tahun']} GROUP BY kode
        
        ) monika_paket_register_{$this->user['tahun']} ON monika_paket_register_{$this->user['tahun']}.kode = monika_data_{$this->user['tahun']}.kdpaket
    LEFT JOIN t_register ON monika_paket_register_{$this->user['tahun']}.kdregister = t_register.register
    WHERE
        monika_kontrak_{$this->user['tahun']}.status_tender = 'Belum Lelang'
        AND monika_kontrak_{$this->user['tahun']}.kdjnskon IN ($kdjnskon)
    {$where}
    {$whereCondition_dateFilter}
    GROUP BY
        monika_paket_register_{$this->user['tahun']}.kode
        
        ")->getResult();

        return array_map(function ($arr) {
            return (object) [
                'kdregister'     => $arr->register,
                'nmloan'     => $arr->nmloan,
                'pagu'     => $arr->pagu,
                'jml_paket'     => $arr->jml_paket,
                'paketList' => json_decode($arr->paket)
            ];
        }, $data);
    }


    public function getDataRencanaTenderBelumLelang($w = '', $_rangeMonthOperation = null, $_bulan = null, $full = false, $_filterDateStart = null, $_filterDateEnd = null)
    {
        $whereMonthYear = "";
        if (!is_null($_bulan)) {
            // $whereMonthYear = " AND DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y') = '" . session("userData.tahun") . "' ";
            $whereMonthYear = " AND DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m') $_rangeMonthOperation '" . session("userData.tahun") . '-' . $_bulan . "' ";
        } else {
            if ($full) {
                $whereMonthYear = "  ";
            } else {
                $whereMonthYear = " AND DATE_FORMAT(now(),'%m') = MID(tgl_rencana_lelang,4,2) ";
            }
        }

        $whereCondition_dateFilter = "";
        if (!is_null($_filterDateStart) && !is_null($_filterDateEnd)) $whereCondition_dateFilter = " AND (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) >= '$_filterDateStart' AND  (DATE_FORMAT(STR_TO_DATE(tgl_rencana_lelang, '%d-%m-%Y'), '%Y-%m-%d')) <= '$_filterDateEnd'";

        $db = \Config\Database::connect();
        $query = $db->query("SELECT DATE_FORMAT(now(),'%m') ,MID(tgl_rencana_lelang,4,2),sum(pfis) as nilai_kontrak,count(nmpaket) AS jml_paket FROM monika_kontrak_{$this->user['tahun']}
        WHERE status_tender = 'Belum Lelang' 
        $whereMonthYear
        $whereCondition_dateFilter
        AND sumber_dana = '{$w}'
        ");

        return $query->getRowArray();
    }



    public function getDataRencanaTender_detail4Bulan($_pagu)
    {
        return [
            'pagu' => 'aleksander'
        ];
    }



    public function getDataSisaLelang($_namaPagu, $_jenisKontrak = [], $selectList = false)
    {
        $db = \Config\Database::connect();

        $whereInJenisKontrak = '';
        if (count($_jenisKontrak) > 0) {
            $mapJenisKontrak = array_map(function ($arr) {
                return "'$arr'";
            }, $_jenisKontrak);
            $implodeJenisKontrak = implode(",", $mapJenisKontrak);
            // $whereInJenisKontrak = "AND (SELECT jenis_kontrak FROM monika_kontrak_{$this->user['tahun']} c WHERE SUBSTRING_INDEX(c.kdpaket, '.', -5) = SUBSTRING_INDEX(a.kode, '.', 5) LIMIT 1) IN ($implodeJenisKontrak)";
            $whereInJenisKontrak = "AND jenis_kontrak IN ($implodeJenisKontrak)";
        }

        $select = '';
        $limit = '';
        if ($selectList) {
            $select = "
                a.nama as nama_paket
            ";
            $limit = "LIMIT 4";
        } else {
            $select = "
                sum(a.sisa_lelang) AS nilai_kontrak,
                count(a.nama) AS jml_paket
            ";
        }

        $query =  $db->query("SELECT 
                $select
            FROM 
                emon_tarik_sisalelang_sda_paketpekerjaan a
            WHERE 
                a.nama != 'TOTAL' 
                AND a.sumber_dana = '$_namaPagu' 
                /*AND IFNULL((SELECT sumber_dana FROM monika_kontrak_2022 b WHERE SUBSTRING_INDEX(b.kdpaket, '.', -5)=SUBSTRING_INDEX(a.kode, '.', 5) ORDER BY `sumber_dana` ASC LIMIT 1), 'RPM') = '$_namaPagu'*/
                $whereInJenisKontrak 
            $limit
        ");

        $return = ($selectList) ? $query->getResultArray() : $query->getRowArray();
        return $return;
    }




    /*
    public function getDataSisaLelang($_namaPagu, $_jenisKontrak=[], $selectList=false) {
        $db = \Config\Database::connect();

        $whereInJenisKontrak = '';
        if (count($_jenisKontrak) > 0) {
            $mapJenisKontrak = array_map(function($arr) {
                return "'$arr'";
            }, $_jenisKontrak);
            $implodeJenisKontrak = implode(",", $mapJenisKontrak);
            $whereInJenisKontrak = "AND jenis_kontrak IN ($implodeJenisKontrak)";
        }

        $select = '';
        $limit = '';
        if ($selectList) {
            $select = "
                a.nama as nama_paket
            ";
            $limit = "LIMIT 4";
        }
        else {
            $select = "
                sum(a.sisa_lelang) AS nilai_kontrak,
                count(a.nama) AS jml_paket
            ";
        }

        $query =  $db->query("SELECT 
                $select
            FROM 
                emon_tarik_sisalelang_sda_paketpekerjaan a
                left join monika_kontrak_{$this->user['tahun']} b on SUBSTRING_INDEX(b.kdpaket, '.', -6) = a.kode
            WHERE 
                b.status_tender = 'Belum Lelang'
                AND sumber_dana = '$_namaPagu' 
                $whereInJenisKontrak 
            $limit
         ");

        $return = ($selectList) ? $query->getResultArray() : $query->getRowArray();
        return $return;
    }
    */
}
