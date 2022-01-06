<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
// use Modules\Admin\Models\KinerjaOutputBulananModel;


class KinerjaOutputBulanan extends \App\Controllers\BaseController
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
        $tahun = $this->user['tahun'];
        $bulan = decrypt_url($bulan);
        if ($bulan == '') {
            $bulan = date('n');
        }
        if (empty($_GET['exp'])) {
            $_GET['exp'] = '';
        }

        if ($keyword) {

            $keyword = "WHERE tsoutput.nmro LIKE '%$keyword' AND  pkt.tahun ='$tahun'";
        } else {

            $keyword = "WHERE pkt.tahun ='$tahun'";
        }



        $qpaket = $this->db_mysql->query("SELECT
        pkt.kdprogram,
        pkt.kdgiat,
        pkt.kdoutput,
        pkt.kdsoutput,
        pkt.kdkmpnen,
        pkt.sat,
        tp.nmprogram,
        tgiat.nmgiat,
        toutput.nmoutput,
        toutput.sat AS toutputsat,
        pkt.pagu_total,
        CAST(
            REPLACE (pkt.vol, ',', '.') AS DECIMAL
        ) vol,
        pkt.kdprogram || '.' || pkt.kdgiat || '.' || pkt.kdoutput || '.' || pkt.kdsoutput || pkt.kdkmpnen AS kode,
    -- CASE
    -- WHEN pkt.real_total IS NULL THEN
    --     0
    -- WHEN pkt.real_total = '' THEN
    --     0
    -- ELSE
    -- pkt.progres_keu_des/100 * pkt.real_total
    -- END AS rtot,
    -- CASE
    -- WHEN pkt.progres_keu_des IS NULL THEN
    --     0
    -- WHEN pkt.progres_keu_des = '' THEN
    --     0
    -- ELSE
    --     pkt.progres_keu_des
    -- END AS rr_b,
    -- CASE
    -- WHEN pkt.progres_fisik_des IS NULL THEN
    --     0
    -- WHEN pkt.progres_fisik_des = '' THEN
    --     0
    -- ELSE
    --     pkt.progres_keu_des
    -- END AS ff_b,
    --  CASE
    -- WHEN pkt.ren_keu_des IS NULL THEN
    --     0
    -- WHEN pkt.ren_keu_des = '' THEN
    --     0
    -- ELSE
    --     pkt.ren_keu_des
    -- END AS renk_b,
    --  CASE
    -- WHEN pkt.ren_fis_des IS NULL THEN
    --     0
    -- WHEN pkt.ren_fis_des = '' THEN
    --     0
    -- ELSE
    --     pkt.ren_fis_des
    -- END AS renf_b,
    
    --  CASE
    -- WHEN pkt.ufis IS NULL THEN
    --     0
    -- WHEN pkt.ufis = '' THEN
    --     0
    -- ELSE
    --     pkt.ufis
    -- END AS ufis,
     tsoutput.nmro
    FROM
        monika_data pkt
    LEFT JOIN tprogram tp ON tp.kdprogram = pkt.kdprogram
    LEFT JOIN tgiat ON tgiat.kdgiat = pkt.kdgiat
    LEFT JOIN toutput ON pkt.kdgiat = toutput.kdgiat
    AND pkt.kdoutput = toutput.kdoutput
    LEFT JOIN tsoutput ON
    pkt.kdgiat = tsoutput.kdgiat
    AND pkt.kdoutput = tsoutput.kdkro
    AND pkt.kdsoutput = tsoutput.kdro
    $keyword
    ORDER BY
    pkt.kdprogram,
        pkt.kdgiat,
        pkt.kdoutput,
        pkt.kdsoutput")->getResultArray();

        $data = array(
            'title' => 'Kinerja Output Bulanan',
            // 'idk'   => $balaiid,
            // 'idks'  => $satkerid,
            // 'label' => $balai,
            // 'label2' => $satker,
            // 'posisi' => ['<a href="' . site_url("pulldata/unitkerja") . '"><i class="fa fa-home"></i></a>', '<a href="' . site_url("pulldata/satuankerja/$balaiid/$balai") . '">' . $balai . '</a>', $satker],
            // 'qdata' => $this->PulldataModel->getPaket("md.kdsatker='$satkerid'"),
            'qdata' => $qpaket,
            'rekap' => 'paket',
            'dbuse' => $this->db
        );
        if ($_GET['exp'] == 'xlxs') {

            header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: attachment; filename=" . "Kinerja-Output-Bulanan" . date('d_m_y_His') . ".xls");
            header("Pragma: no-cache");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");
            return view('Modules\Admin\Views\KinerjaOutputBulanan\Cetak\Cetak-KOB-1.php', $data);
        } else {
            return view('Modules\Admin\Views\KinerjaOutputBulanan\FormatKOB_1.php', $data);
        }
    }
}
