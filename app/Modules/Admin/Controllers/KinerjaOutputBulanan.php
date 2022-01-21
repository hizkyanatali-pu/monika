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
   
     tsoutput.nmro
    FROM
        monika_data_{$tahun} pkt
    LEFT JOIN tprogram tp ON tp.kdprogram = pkt.kdprogram
    LEFT JOIN tgiat ON tgiat.kdgiat = pkt.kdgiat AND tgiat.tahun_anggaran = {$tahun}
    LEFT JOIN toutput ON pkt.kdgiat = toutput.kdgiat
    AND pkt.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran = {$tahun}
    LEFT JOIN tsoutput ON
    pkt.kdgiat = tsoutput.kdgiat
    AND pkt.kdoutput = tsoutput.kdkro
    AND pkt.kdsoutput = tsoutput.kdro
    AND tsoutput.tahun_anggaran = {$tahun}
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
