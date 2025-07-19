<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\PohonAnggaranModel;


class PohonAnggaran extends \App\Controllers\BaseController
{

    public function __construct()
    {

        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
        $this->PohonAnggaran        = new PohonAnggaranModel();
    }


    public function index()
    {

        $query = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span");
        $query1 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE '%FC'");
        $query2 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE '%WA'");

        /* OLD
        $query3 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE '%WA' AND kdoutput = 'EAA'");
        $query4 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE '%WA' AND kdoutput <> 'EAA'");
        */
        $query3 = $this->db->query("SELECT SUM(pgrupiah) as total FROM paket WHERE kdgiat='2421' AND kdoutput='EBA' AND kdsoutput='994'");
        $query4 = $this->db->query("SELECT sum(pgrupiah) as total FROM paket WHERE kdgiat='2421' AND kode NOT IN (SELECT kode FROM paket WHERE kdgiat='2421' AND kdoutput='EBA' AND kdsoutput='994')");


        $row = $query->getRow();
        $row1 = $query1->getRow();
        $row2 = $query2->getRow();
        $row3 = $query3->getRow();
        $row4 = $query4->getRow();


        $total_operasional_nonOperasional = $row3->total + $row4->total;

        // echo $row2->total;
        // echo '<br/>';
        // echo $total_operasional_nonOperasional;
        // exit;


        $data = array(
            'title' => 'Pohon Anggaran Dipa',
            'totaldjs' => $row,
            'totalketahanansda' => $row1,
            'totaldukungan' => $row2,
            'totaloperasional' => $row3,
            'totalnonoperasional' => $row4
        );
        return view('Modules\Admin\Views\PosturAnggaran\Pohon-anggaran-dipa', $data);
    }

    public function posturPagu()
    {

        $data['title'] = 'Pagu Per Program';
        $data['programketahanan'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'")->getRow();

        $data['rupiahmurni'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC' AND sumber_dana='A'")->getRow();
        $data['phlnrmp'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC' AND sumber_dana in ('B','C')")->getRow();
        $data['sbsn'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC' AND sumber_dana='T'")->getRow();

        $data['kontraktahunjamakRM'] = $this->db->query("SELECT SUM(pgrupiah) as total FROM paket where kdprogram='FC' AND kdjnskon != 0")->getRow();
        $data['singleyearRM'] = $this->db->query("SELECT SUM(pgrupiah)  as total FROM paket where kdprogram='FC' AND kdjnskon = 0")->getRow();

        $data['kontraktahunjamakSBSN'] = $this->db->query("SELECT SUM(pgsbsn) as total FROM paket where kdprogram='FC' AND kdjnskon != 0")->getRow();
        $data['singleyearSBSN'] = $this->db->query("SELECT SUM(pgsbsn)  as total FROM paket where kdprogram='FC' AND kdjnskon = 0")->getRow();

        /** OLD */
        // $data['gajidantunjangan'] = $this->db->query("SELECT SUM(pg) as total FROM	paket WHERE kdprogram='WA' AND kdsoutput = '001' AND kdkmpnen='001'")->getRow();
        // $data['administrasisatker'] = $this->db->query("SELECT SUM(pg) as total FROM paket WHERE kdprogram='WA' AND kdsoutput = '970'")->getRow();
        // $data['layananperkantoran'] = $this->db->query("SELECT SUM(pg) as total FROM paket WHERE kdprogram='WA' AND kdsoutput IN ('001','950','951') AND kdkmpnen != '001'")->getRow();

        $queryCondition_gajidantunjangan   = "kdgiat='2421' AND kdoutput='EBA' AND kdsoutput='994' AND kdkmpnen='001'";
        $queryCondition_administrasisatker = "(kdgiat='2421' AND kdoutput='EBA' AND kdsoutput='960') OR (kdgiat='2421' AND kdoutput='EAH')";

        $data['gajidantunjangan']   = $this->db->query("SELECT SUM(pg) as total FROM paket WHERE $queryCondition_gajidantunjangan")->getRow();
        $data['administrasisatker'] = $this->db->query("SELECT SUM(pg) as total FROM paket WHERE $queryCondition_administrasisatker")->getRow();
        $data['layananperkantoran'] = $this->db->query("SELECT 
                SUM(pg) as total 
            FROM 
                paket 
            WHERE 
                ((kdgiat='2421' AND kdoutput='EBB') OR (kdgiat='2421' AND kdoutput='EBA' AND kdsoutput='994' AND kdkmpnen='002'))
                AND kode NOT IN (SELECT kode FROM paket WHERE $queryCondition_gajidantunjangan)
                AND kode NOT IN (SELECT kode FROM paket WHERE $queryCondition_administrasisatker)
        ")->getRow();

        // OLD
        // $data['programdukunganmanajemen'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'")->getRow();
        $data['programdukunganmanajemen'] = (object) ['total' => ($data['gajidantunjangan']->total + $data['administrasisatker']->total + $data['layananperkantoran']->total)];

        // OLD
        // $data['djsda'] = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span")->getRow();
        $data['djsda'] = (object) ['total' => ($data['programketahanan']->total + $data['programdukunganmanajemen']->total)];

        return view('Modules\Admin\Views\PosturAnggaran\Pagu-per-program', $data);
    }

    public function alokasiAnggaran()
    {

        $query = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span");
        $paket1 = $this->db->query("SELECT COUNT(*) as paket FROM paket");
        $query1 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%FC'");
        $paket2 = $this->db->query("SELECT COUNT(*) as paket FROM paket WHERE kdprogram ='FC'");
        $query2 = $this->db->query("SELECT SUM(amount) as total FROM d_dipa_span WHERE program LIKE'%WA'");
        $paket3 = $this->db->query("SELECT COUNT(*) as paket FROM paket WHERE kdprogram ='WA'");



        $row = $query->getRow();
        $row1 = $query1->getRow();
        $row2 = $query2->getRow();

        $data = array(
            'title' => 'Pagu Per Program',
            'totaldjs' => $row,
            'paketdjs' => $paket1->getRow(),
            'totalketahanansda' => $row1,
            'paketketahanansda' => $paket2->getRow(),
            'totaldukungan' => $row2,
            'paketdukungan' => $paket3->getRow(),
        );

        return view('Modules\Admin\Views\PosturAnggaran\Alokasi-anggaran', $data);
    }



    public function alokasiAnggaranNew()
    {
        $db    = \Config\Database::connect();
        $tahun = session("userData.tahun");

        $query_treeRow1 = $db->query("
            select
                SUM(pagu_51) as total_pagu_51,
                SUM(pagu_52) as total_pagu_52,
                SUM(pagu_53) as total_pagu_53
            FROM
                monika_data_$tahun
        ")
            ->getRow();
        $alokasiAnggaran = $query_treeRow1->total_pagu_51 + $query_treeRow1->total_pagu_52 + $query_treeRow1->total_pagu_53;

        $query_treeRow3_tender = $db->query("SELECT SUM(pfis) as total FROM  monika_kontrak_$tahun")->getRow();

        $query_treeLevel4_syc         = $db->query("SELECT SUM(pfis) as total FROM  monika_kontrak_$tahun WHERE kdjnskon='0'")->getRow()->total;
        $query_treeLevel4_mycBaru     = $db->query("SELECT SUM(pfis) as total FROM  monika_kontrak_$tahun WHERE kdjnskon IN ('1', '3')")->getRow()->total;
        $query_treeLevel4_mycLanjutan = $db->query("SELECT SUM(pfis) as total FROM  monika_kontrak_$tahun WHERE kdjnskon='2'")->getRow()->total;


        $data = [
            'title'                        => 'Alokasi Anggaran',

            '_0_alokasiAnggaran'           => $alokasiAnggaran,
            '_1_belanjaPegawai'            => $query_treeRow1->total_pagu_51,
            '_1_belanjaPegawai_persentase' => round(($query_treeRow1->total_pagu_51 / $alokasiAnggaran) * 100, 2),
            '_2_belanjaBarang'             => $query_treeRow1->total_pagu_52,
            '_2_belanjaBarang_persentase'  => round(($query_treeRow1->total_pagu_52 / $alokasiAnggaran) * 100, 2),
            '_3_belanjaModal'              => $query_treeRow1->total_pagu_53,
            '_3_belanjaModal_persentase'   => round(($query_treeRow1->total_pagu_53 / $alokasiAnggaran) * 100, 2),

            '_31_barangModalOperasional'               => 0,
            '_31_barangModalOperasional_persentase'    => 0,
            '_32_barangModalNonOperasional'            => 0,
            '_32_barangModalNonOperasional_persentase' => 0,

            '_321_tender'               => $query_treeRow3_tender->total,
            '_321_tender_persentase'    => '0',
            '_322_nonTender'            => 0,
            '_322_nonTender_persentase' => 0,

            '_3211_syc'                    => $query_treeLevel4_syc,
            '_3211_syc_persentase'         => round(($query_treeLevel4_syc / $query_treeRow3_tender->total) * 100, 2),
            '_3212_mycBaru'                => $query_treeLevel4_mycBaru,
            '_3212_mycBaru_persentase'     => round(($query_treeLevel4_mycBaru / $query_treeRow3_tender->total) * 100, 2),
            '_3213_mycLanjutan'            => $query_treeLevel4_mycLanjutan,
            '_3213_mycLanjutan_persentase' => round(($query_treeLevel4_mycLanjutan / $query_treeRow3_tender->total) * 100, 2),

            '_3221_barang'              => 0,
            '_3221_barang_persentase'   => 0,
            '_3222_nonLahan'            => 0,
            '_3222_nonLahan_persentase' => 0,
            '_3222_lahan'               => 0,
            '_3222_lahan_persentase'    => 0,

            '_32111_barang'            => 0,
            '_32111_barang_persentase' => 0,
            '_32112_modal'             => 0,
            '_32112_modal_persentase'  => 0,

            '_32121_barang'            => 0,
            '_32121_barang_persentase' => 0,
            '_32122_modal'             => 0,
            '_32122_modal_persentase'  => 0,

            '_32131_barang'            => 0,
            '_32131_barang_persentase' => 0,
            '_32132_modal'             => 0,
            '_32132_modal_persentase'  => 0,
        ];

        return view('Modules\Admin\Views\PosturAnggaran\Alokasi-anggaran-new', $data);
    }

    public function paketKontraktual()
    {
        $qterkontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "terkontrak"]);
        $qproseslelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Proses Lelang"]);
        $qbelumlelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Belum Lelang"]);
        $qpersiapankontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Persiapan kontrak"]);

        $data = array(
            'title'            => 'Kontraktual',
            'terkontrak'       => $qterkontrak,
            'proseslelang'     => $qproseslelang,
            'belumlelang'      => $qbelumlelang,
            'persiapankontrak' => $qpersiapankontrak,
            'gagallelang'      => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Gagal Lelang"]),
            'template2'        => [
                'mycBaruSyc'      => $this->PohonAnggaran->getDataKontrak(["jenis_kontrak" => ['0', '1', '3'], "status_tender" => ['terkontrak', 'persiapan kontrak', 'Proses Lelang', 'Gagal Lelang', 'Belum Lelang']]),
                'mycLanjutan'     => $this->PohonAnggaran->getDataKontrak(["jenis_kontrak" => ['2']]),
                'data_mycBaruSyc' => [
                    'sudahLelang_terkontrak'       => $this->PohonAnggaran->getDataKontrak(["jenis_kontrak" => ['0', '1', '3'], "status_tender" => "terkontrak"]),
                    'sudahLelang_persiapanKontrak' => $this->PohonAnggaran->getDataKontrak(["jenis_kontrak" => ['0', '1', '3'], "status_tender" => "persiapan kontrak"]),
                    'prosesLelang'                 => $this->PohonAnggaran->getDataKontrak(["jenis_kontrak" => ['0', '1', '3'], "status_tender" => "Proses Lelang"]),
                    'belumLelang_terjadwal'        => $this->PohonAnggaran->getDataKontrak(["jenis_kontrak" => ['0', '1', '3'], "status_tender" => "Belum Lelang"]),
                    'belumLelang_gagalLelang'      => $this->PohonAnggaran->getDataKontrak(["jenis_kontrak" => ['0', '1', '3'], "status_tender" => "Gagal Lelang"])
                ]
            ]
        );

        return view('Modules\Admin\Views\PosturAnggaran\Paket-kontraktual', $data);
    }

    public function sisaLelang()
    {
        $data['title'] = 'Sisa Lelang';

        $dataRpm = $this->PohonAnggaran->getDataSisaLelang('RPM');
        $data['nilaiRpm']['nilai_kontrak'] = $dataRpm['nilai_kontrak'] * 1000;
        $data['nilaiRpm']['jml_paket']     = $dataRpm['jml_paket'];

        $dataSbsn = $this->PohonAnggaran->getDataSisaLelang('SBSN');
        $data['nilaiSbsn']['nilai_kontrak'] = $dataSbsn['nilai_kontrak'] * 1000;
        $data['nilaiSbsn']['jml_paket']     = $dataSbsn['jml_paket'];

        $dataPhln = $this->PohonAnggaran->getDataSisaLelang('PHLN');
        $data['nilaiPhln']['nilai_kontrak'] = $dataPhln['nilai_kontrak'] * 1000;
        $data['nilaiPhln']['jml_paket']     = $dataPhln['jml_paket'];

        /** 
         * syc-myc-list RPM
         */
        $dataRpmSyc = $this->PohonAnggaran->getDataSisaLelang('RPM', ['SYC ']);
        $data['nilaiRpmSyc']['nilai_kontrak'] = $dataRpmSyc['nilai_kontrak'] * 1000;
        $data['nilaiRpmSyc']['jml_paket']     = $dataRpmSyc['jml_paket'];

        $dataRpmMycBaru = $this->PohonAnggaran->getDataSisaLelang('RPM', ['MYC Baru ']);
        $data['nilaiRpmMycBaru']['nilai_kontrak'] = $dataRpmMycBaru['nilai_kontrak'] * 1000;
        $data['nilaiRpmMycBaru']['jml_paket']     = $dataRpmMycBaru['jml_paket'];
        // $data['nilaiRpmMycBaru']['nilai_kontrak'] = 0;
        // $data['nilaiRpmMycBaru']['jml_paket']     = 0;

        $dataRpmMycLanjutan = $this->PohonAnggaran->getDataSisaLelang('RPM', ['MYC Lanjutan ']);
        $data['nilaiRpmMycLanjutan']['nilai_kontrak'] = $dataRpmMycLanjutan['nilai_kontrak'] * 1000;
        $data['nilaiRpmMycLanjutan']['jml_paket']     = $dataRpmMycLanjutan['jml_paket'];
        // $data['nilaiRpmMycLanjutan']['nilai_kontrak'] = 0;
        // $data['nilaiRpmMycLanjutan']['jml_paket']     = 0;

        $data['listPaketRpmSyc'] = $this->PohonAnggaran->getDataSisaLelang('RPM', ['SYC '], true);
        $data['listPaketRpmMycBaru'] = $this->PohonAnggaran->getDataSisaLelang('RPM', ['MYC Baru '], true);
        $data['listPaketRpmMycLanjutan'] = $this->PohonAnggaran->getDataSisaLelang('RPM', ['MYC Lanjutan '], true);
        /** end-of: syc-myc-list RPM */


        /** 
         * syc-myc-list SBSN
         */
        $dataSbsnSyc = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['SYC ']);
        $data['nilaiSbsnSyc']['nilai_kontrak'] = $dataSbsnSyc['nilai_kontrak'] * 1000;
        $data['nilaiSbsnSyc']['jml_paket']     = $dataSbsnSyc['jml_paket'];

        $dataSbsnMycBaru = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['MYC Baru ']);
        $data['nilaiSbsnMycBaru']['nilai_kontrak'] = $dataSbsnMycBaru['nilai_kontrak'] * 1000;
        $data['nilaiSbsnMycBaru']['jml_paket']     = $dataSbsnMycBaru['jml_paket'];
        // $data['nilaiSbsnMycBaru']['nilai_kontrak'] = 0;
        // $data['nilaiSbsnMycBaru']['jml_paket']     = 0;

        $dataSbsnMycLanjutan = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['MYC Lanjutan ']);
        $data['nilaiSbsnMycLanjutan']['nilai_kontrak'] = $dataSbsnMycLanjutan['nilai_kontrak'] * 1000;
        $data['nilaiSbsnMycLanjutan']['jml_paket']     = $dataSbsnMycLanjutan['jml_paket'];
        // $data['nilaiSbsnMycLanjutan']['nilai_kontrak'] = 0;
        // $data['nilaiSbsnMycLanjutan']['jml_paket']     = 0;

        $data['listPaketSbsnSyc'] = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['SYC '], true);
        $data['listPaketSbsnMycBaru'] = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['MYC Baru '], true);
        $data['listPaketSbsnMycLanjutan'] = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['MYC Lanjutan '], true);
        /** end-of: syc-myc-list SBSN */


        /** 
         * syc-myc-list PHLN
         */
        $dataPhlnSyc = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['SYC ']);
        $data['nilaiPhlnSyc']['nilai_kontrak'] = $dataPhlnSyc['nilai_kontrak'] * 1000;
        $data['nilaiPhlnSyc']['jml_paket']     = $dataPhlnSyc['jml_paket'];

        $dataPhlnMycBaru = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['MYC Baru ']);
        $data['nilaiPhlnMycBaru']['nilai_kontrak'] = $dataPhlnMycBaru['nilai_kontrak'] * 1000;
        $data['nilaiPhlnMycBaru']['jml_paket']     = $dataPhlnMycBaru['jml_paket'];
        // $data['nilaiPhlnMycBaru']['nilai_kontrak'] = 0;
        // $data['nilaiPhlnMycBaru']['jml_paket']     = 0;

        $dataPhlnMycLanjutan = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['MYC Lanjutan ']);
        $data['nilaiPhlnMycLanjutan']['nilai_kontrak'] = $dataPhlnMycLanjutan['nilai_kontrak'] * 1000;
        $data['nilaiPhlnMycLanjutan']['jml_paket']     = $dataPhlnMycLanjutan['jml_paket'];
        // $data['nilaiPhlnMycLanjutan']['nilai_kontrak'] = 0;
        // $data['nilaiPhlnMycLanjutan']['jml_paket']     = 0;

        $data['listPaketPhlnSyc'] = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['SYC '], true);
        $data['listPaketPhlnMycBaru'] = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['MYC Baru '], true);
        $data['listPaketPhlnMycLanjutan'] = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['MYC Lanjutan '], true);
        /** end-of: syc-myc-list PHLN */


        return view('Modules\Admin\Views\PosturAnggaran\Sisa-lelang', $data);
    }

    public function sisaBelumLelang()
    {
        $data['title'] = "Belum Lelang";
        $data['nilai_rpm'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "RPM");
        $data['nilai_sbsn'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "SBSN");
        $data['nilai_phln'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "PHLN");

        $data['rpmSyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "RPM");
        $data['rpmMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "RPM");

        $data['sbsnSyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "SBSN");
        $data['sbsnMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "SBSN");

        $data['phlnSyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "PHLN");
        $data['phlnMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "PHLN");


        $data['rpmSycList'] = $this->PohonAnggaran->getDataBelumLelangList([[0]], "RPM");
        $data['rpmMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "RPM");

        $data['sbsnSycList'] = $this->PohonAnggaran->getDataBelumLelangList([[0]], "SBSN");
        $data['sbsnMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "SBSN");

        $data['phlnSycList'] = $this->PohonAnggaran->getDataBelumLelangList([[0]], "PHLN");
        $data['phlnMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "PHLN");

        return view('Modules\Admin\Views\PosturAnggaran\Sisa-belum-lelang', $data);
    }

    public function rencanaTender()
    {
        $data['title'] = "Belum Lelang";
        $data['nilai_rpm'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "RPM");
        $data['nilai_sbsn'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "SBSN");
        $data['nilai_phln'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "PHLN");

        $data['rpmSyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "RPM");
        $data['rpmMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "RPM");

        $data['phlnMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "PHLN");


        $data['rpmSycList'] = $this->PohonAnggaran->getDataBelumLelangList([[0]], "RPM");
        $data['rpmMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "RPM");
        $data['phlnMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "PHLN");

        return view('Modules\Admin\Views\PosturAnggaran\Rencana-tender', $data);
    }

    public function danatidakTerserap()
    {


        $getDataRpm = $this->PohonAnggaran->getDataSisaDataTidakTerserap("rpm");
        $getDataPhln = $this->PohonAnggaran->getDataSisaDataTidakTerserap("phln");
        $getDataSbsn = $this->PohonAnggaran->getDataSisaDataTidakTerserap("sbsn");
        $getDataTotal = $this->PohonAnggaran->getDataSisaDataTidakTerserap("total");

        $getDataRpmSwakelola = $this->PohonAnggaran->getDataSisaDataTidakTerserap("rpm", ["AU","S"]);
        $getDataSbsnSwakelola = $this->PohonAnggaran->getDataSisaDataTidakTerserap("sbsn", ["AU","S"]);
        $getDataPhlnSwakelola = $this->PohonAnggaran->getDataSisaDataTidakTerserap("phln", ["AU","S"]);


        $getDataRpmDrop = $this->PohonAnggaran->getDataSisaDataTidakTerserap("rpm", [], true);
        $getDataSbsnDrop = $this->PohonAnggaran->getDataSisaDataTidakTerserap("sbsn", [], true);
        $getDataPhlnDrop = $this->PohonAnggaran->getDataSisaDataTidakTerserap("phln", [], true);


        $dataRpmSyc = $this->PohonAnggaran->getDataSisaLelang('RPM', ['SYC ']);
        $dataSbsnSyc = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['SYC ']);
        $dataPhlnSyc = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['SYC ']);


        $dataRpmMyc = $this->PohonAnggaran->getDataSisaLelang('RPM', ['MYC ', 'MYC Baru ', 'MYC Lanjutan ']);
        $dataSbsnMyc = $this->PohonAnggaran->getDataSisaLelang('SBSN', ['MYC ', 'MYC Baru ', 'MYC Lanjutan ']);
        $dataPhlnMyc = $this->PohonAnggaran->getDataSisaLelang('PHLN', ['MYC ', 'MYC Baru ', 'MYC Lanjutan ']);

        $dataRpmSyc_val = $dataRpmSyc['nilai_kontrak'];
        $dataSbsnSyc_val = $dataSbsnSyc['nilai_kontrak'];
        $dataPhlnSyc_val = $dataPhlnSyc['nilai_kontrak'];

        $dataRpmMyc_val = $dataRpmMyc['nilai_kontrak'];
        $dataSbsnMyc_val = $dataSbsnMyc['nilai_kontrak'];
        $dataPhlnMyc_val = $dataPhlnMyc['nilai_kontrak'];



        $data = array(
            'title' => 'Dana Tidak Terserap',
            'getDataRpm' => $getDataRpm,
            'getDataPhln' => $getDataPhln,
            'getDataSbsn' => $getDataSbsn,
            'getDataTotal' => $getDataTotal,

            // Sisa Drop
            'getSisaDropRpm' =>  $getDataRpmDrop['monika_data']['sisaPagu'],
            'getSisaDropSbsn' =>  $getDataSbsnDrop['monika_data']['sisaPagu'] ,
            'getSisaDropPhln' =>  $getDataPhlnDrop['monika_data']['sisaPagu'] ,

            // Sisa Swakelola
            'getSisaSwakelolaRpm' =>  $getDataRpmSwakelola['monika_data']['sisaPagu'] ,
            'getSisaSwakelolaSbsn' =>  $getDataSbsnSwakelola['monika_data']['sisaPagu']  ,
            'getSisaSwakelolaPhln' =>  $getDataPhlnSwakelola['monika_data']['sisaPagu'] ,

            //sisa lelang SYC
            'getSisaLelangRpmSyc' => $dataRpmSyc_val,
            'getSisaLelangSbsnSyc' => $dataSbsnSyc_val,
            'getSisaLelangPhlnSyc' => $dataPhlnSyc_val,

            //sisa lelang MYC
            'getSisaLelangRpmMyc' => $dataRpmMyc_val,
            'getSisaLelangSbsnMyc' => $dataSbsnMyc_val,
            'getSisaLelangPhlnMyc' => $dataPhlnMyc_val,







        );

        return view('Modules\Admin\Views\PosturAnggaran\Dana-tidak-terserap', $data);
    }
}
