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
    }

    public function index($bulan = '')
    {
        $bulan = decrypt_url($bulan);
        if ($bulan == '') {
            $bulan = date('n');
        }
        if (empty($_GET['exp'])) {
            $_GET['exp'] = '';
        }


        $filterkdgiat = "'2408', '5036', '5037', '5039', '5040'";
        $filterkdoutput = "'CBG','CBS','RBG','RBS'";
        $qpaket = $this->db->query("SELECT
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
        --,d_soutput.ursoutput
        FROM paket pkt 
        LEFT JOIN tprogram tp ON tp.kdprogram = pkt.kdprogram  
        LEFT JOIN tgiat ON tgiat.kdgiat = pkt.kdgiat  
        LEFT JOIN toutput ON pkt.kdgiat = toutput.kdgiat AND pkt.kdoutput = toutput.kdoutput 
        -- LEFT JOIN d_soutput ON pkt.kdprogram = d_soutput.kdprogram AND pkt.kdgiat = d_soutput.kdgiat AND pkt.kdoutput = d_soutput.kdoutput AND pkt.kdsoutput = d_soutput.kdsoutput  
        WHERE pkt.kdprogram = 'FC' AND pkt.kdgiat IN ($filterkdgiat) AND pkt.kdoutput IN ($filterkdoutput) AND pkt.kdkmpnen = '074' ORDER BY pkt.kdgiat,pkt.kdoutput,pkt.kdsoutput")->getResultArray();

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
