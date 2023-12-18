<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\AksesModel;
use Modules\Admin\Models\PulldataModel;
use Modules\Admin\Models\RekapUnorModel;
use CodeIgniter\API\ResponseTrait;



class Pulldata extends \App\Controllers\BaseController
{
    use ResponseTrait;
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->akses          = new AksesModel();
        $this->PulldataModel  = new PulldataModel();
        $this->RekapUnorModel = new RekapUnorModel();
        $this->InModul        = "Pulldata";
        $session              = session();
        $this->user           = $session->get('userData');
        $this->tableProvinsi = $this->db->table('tlokasi');
    }

    public function index()
    {
        $data = array(
            'title' => 'Paket kegiatan',
            'qdata' => $this->PulldataModel->getBalaiPaket("balai")
        );
        return view('Modules\Admin\Views\Paket\Paketbalai', $data);
    }

    public function getsatker($slug = '')
    {
        echo json_encode(['qdata' => $this->PulldataModel->getBalaiPaket("satker", "b.balaiid='$slug'")]);
        die();
    }

    function getpaket($slug = null)
    {
        echo json_encode(['qdata' => $this->PulldataModel->getPaket("md.kdsatker='$slug'")]);
        die();
    }


    function getPaketTagging()
    {


        $w = $this->request->getVar('satkerId');
        $templateid = $this->request->getVar('templateId');

        if ($w == 0) {
            $jsonResponse =  json_encode(["message" => "tidak ada data"]);
            header('Content-Type: application/json');
            echo $jsonResponse;
            exit;
        }

        $where = "md.kdsatker IN ($w)";

        // $getSatker = $this->db->query("SELECT * from m_satker WHERE kdsatker IN ($w)")->getRow();
        // if (empty($getSatker)) {
        //     // $getSatkerResult = $this->db->query("SELECT GROUP_CONCAT(satkerid) as satkerids from m_satker WHERE balaiid=$w")->getRow();
        //     // $getSatkerIds = $getSatkerResult->satkerids;
        //     $where = "md. IN ($getSatkerIds)";
        // }
        $validation = '';
        if ($templateid == 1 || $templateid == 2 || $templateid == 3 || $templateid == 4 || $templateid == 8) {

            $validation = "AND sat NOT IN ('dokumen','layanan','laporan','0')";
        }



        $q = "SELECT
        b.balaiid, b.balai,
		md.kdsatker as satkerid, s.satker,
        md.kdprogram as programid, md.kdgiat as giatid, md.kdoutput as outputid, md.kdsoutput as soutputid, md.kdkmpnen as komponenid,
        md.kdpaket as id, md.nmpaket as label, 
       
        md.vol,
        SUBSTRING_INDEX(SUBSTRING_INDEX(md.nmpaket,';',3),';',-1) as lokasi, 
        SUBSTRING_INDEX(SUBSTRING_INDEX(md.nmpaket,';',6),';',-1) as jenis_paket, SUBSTRING_INDEX(SUBSTRING_INDEX(md.nmpaket,';',7),';',-1) as metode_pemilihan,
        md.sat,
        md.pagu_rpm as pagu_rpm,
        md.pagu_sbsn as pagu_sbsn,
        md.pagu_phln as pagu_phln,
        md.pagu_total as pagu_total,

        md.real_total as real_total, md.progres_keuangan, md.progres_fisik

        FROM monika_data_{$this->user['tahun']} md
		LEFT JOIN m_satker s ON s.satkerid=md.kdsatker
		LEFT JOIN m_balai b ON b.balaiid=s.balaiid WHERE
        " . ($w ? "$where" : '') . "$validation ORDER BY b.balaiid ASC, md.kdsatker ASC, md.kdpaket ASC";



        // Eksekusi query dan ambil hasil dari database
        $result =   $this->db->query($q)->getResultArray();

        // if (!$result) {
        // }

        $nestedData = array();

        foreach ($result as $row) {
            $satkerid = $row['satkerid'];
            $id = $row['id'];

            // Jika belum ada data untuk satker ini, inisialisasi array kosong
            if (!isset($nestedData[$satkerid])) {
                $nestedData[$satkerid] = array(
                    'balai' => $row['balai'],
                    'satkerid' => $satkerid,
                    'satker' => $row['satker'],
                    'paket' => array()
                );
            }

            // Menambahkan data paket ke dalam array paket
            $nestedData[$satkerid]['paket'][] = array(
                'paketId' => $id,
                'label' => $row['label'],
                'vol' => $row['vol'],
                'satuan' => $row['sat'],
                'paguDipa' => rupiahFormat($row['pagu_total'], false),
                'realisasi' => rupiahFormat($row['real_total'], false),
                'persenKeu' => $row['progres_keuangan'],
                'persenFis' => $row['progres_fisik']
            );
        }

        // Konversi array ke format JSON
        $jsonResponse = json_encode(array_values($nestedData), JSON_PRETTY_PRINT);

        // Menampilkan response JSON
        header('Content-Type: application/json');
        echo $jsonResponse;
    }

    //format satu-satu
    public function unitkerja($slug = '')
    {
        $data = array(
            'title' => 'Unit Kerja',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket("balai", "md.tahun = " . session('userData.tahun')),
            'rekap' => 'unitkerja',
            'id_report' => 'cetak_ditjen_sda',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        //dd($data);
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }


    function getPaketTematik()
    {
        $page = $this->request->getVar('page') ?? 1;
        $perPage = $this->request->getVar('size') ?? 10;
        $year = $this->request->getVar('year') ?? 2023;
        $table  = "monika_data_" .  $year;
        $offset = ($page - 1) * $perPage;

        $filter = $this->request->getVar('filter') ?? '';



        $q  =  $this->db->table($table)->select(" $table.kdsatker as satkerid, s.satker,
         $table.kdprogram as programid,  $table.kdgiat as giatid,  $table.kdoutput as outputid,  $table.kdsoutput as soutputid,  $table.kdkmpnen as komponenid,
         $table.kdpaket as id,  $table.nmpaket as label, 
       
         $table.vol,
        SUBSTRING_INDEX(SUBSTRING_INDEX( $table.nmpaket,';',3),';',-1) as lokasi, 
        SUBSTRING_INDEX(SUBSTRING_INDEX( $table.nmpaket,';',6),';',-1) as jenis_paket, SUBSTRING_INDEX(SUBSTRING_INDEX( $table.nmpaket,';',7),';',-1) as metode_pemilihan,
         $table.sat,
         $table.pagu_rpm as pagu_rpm,
         $table.pagu_sbsn as pagu_sbsn,
         $table.pagu_phln as pagu_phln,
         $table.pagu_total as pagu_total,
         $table.real_rpm as real_rpm,
         $table.real_sbsn as real_sbsn,
         $table.real_phln as real_phln,
         $table.real_total as real_total,
         $table.progres_keuangan,
         $table.progres_fisik,


         $table.real_total as real_total,  $table.progres_keuangan,  $table.progres_fisik")
            ->join('m_satker as s', "$table.kdsatker =  s.satkerid", 'left')
            ->join('m_balai', " s.balaiid = m_balai.balaiid", 'left')
            ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
            ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran=$year", 'left')
            ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran=$year)", 'left')
            ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran=$year)", 'left')
            ->join('tkabkota', "($table.kdkabkota=tkabkota.kdkabkota AND $table.kdlokasi=tkabkota.kdlokasi)", 'left')
            ->join('tlokasi', "$table.kdlokasi=tlokasi.kdlokasi", 'left');
        $totalData = $this->db->table($table);


        if (isset($filter['satker'])) {
            $q->where("$table.kdsatker", $filter['satker']);
            $totalData->where("$table.kdsatker", $filter['satker']);
        }

        $data = $q->limit($perPage, $offset)->get()->getResultArray();
        $totalData = $totalData->countAllResults();


        return $this->respond(
            [
                "last_page" => ceil($totalData / $perPage),
                "totalData" =>  $totalData,
                "data" =>  $data,
                // "columns" => $resultArray,
                // "page" => $page,
            ]
        );
    }

    public function cetak_ditjensda()
    {

        $data = [
            'title' => 'Unit Kerja',
            'qdata' => $this->PulldataModel->getBalaiPaket("balai")
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function pagusda($slug = '')
    {
        $data = array(
            'title' => 'Unit Kerja',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket(),
            'rekap' => 'pagusda'
        );
        dd($data);
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }
    public function satuankerja($balaiid = '', $label = '')
    {
        $data = array(
            'title' => 'Satuan Kerja',
            'posisi' => ['<a href="' . site_url("pulldata/unitkerja") . '"><i class="fa fa-home"></i></a>', $label],
            'idk' => $balaiid,
            'label' => $label,
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket("satker", "b.balaiid='$balaiid' AND md.tahun =" . session('userData.tahun')),
            'rekap' => 'satuankerja'
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }
    public function paket($balaiid = '', $satkerid = '',  $balai = '', $satker = '')
    {
        $data = array(
            'title' => 'Paket',
            'idk'   => $balaiid,
            'idks'  => $satkerid,
            'label' => $balai,
            'label2' => $satker,
            'posisi' => ['<a href="' . site_url("pulldata/unitkerja") . '"><i class="fa fa-home"></i></a>', '<a href="' . site_url("pulldata/satuankerja/$balaiid/$balai") . '">' . $balai . '</a>', $satker],
            'qdata' => $this->PulldataModel->getPaket("md.kdsatker='$satkerid'"),
            'rekap' => 'paket'
        );
        return view('Modules\Admin\Views\Paket\Format_3', $data);
    }

    //lain
    public function bbws($slug = '')
    {
        $data = array(
            'title' => 'BBWS',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS' AND md.tahun =" . session('userData.tahun')),
            'rekap' => 'bbws',
            'id_report' => 'cetak_bbws',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        // dd($data);
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_bbws()
    {

        $data = [
            'title' => 'BBWS',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS' AND md.tahun =" . session('userData.tahun'))
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function bws($slug = '')
    {
        $data = array(
            'title' => 'BWS',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => $slug,
            'label' => '',
            'nextlink' => 'satuankerja',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BWS' AND md.tahun =" . session('userData.tahun')),
            'rekap' => 'bws',
            'id_report' => 'cetak_bws',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_bws()
    {

        $data = [
            'title' => 'BWS/PUSAT/SKPD TPOP',
            'qdata' => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BWS' AND md.tahun =" . session('userData.tahun')),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function satkerpusat($slug = '')
    {
        $data = array(
            'title' => 'Satker Pusat',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 99,
            'label' => 'Satker Pusat',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='99' AND md.tahun =" . session('userData.tahun')),
            'rekap' => 'satkerpusat',
            'id_report' => 'cetak_satker_pusat',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_satkerpusat()
    {

        $data = [
            'title' => 'Satker Pusat',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='99' AND md.tahun =" . session('userData.tahun')),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function balaiteknik($slug = '')
    {
        $data = array(
            'title' => 'Balai Teknik',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 97,
            'label' => 'Balai Teknik',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='97' AND md.tahun =" . session('userData.tahun')),
            'rekap' => 'balaiteknik',
            'id_report' => 'cetak_balai_teknik',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_balaiteknik()
    {

        $data = [
            'title' => 'Balai Teknik',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='97' AND md.tahun =" . session('userData.tahun')),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function skpdtpop()
    {
        $data = array(
            'title' => 'SKPD TP OP',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 98,
            'label' => 'SKPD TP OP',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='98' AND md.tahun =" . session('userData.tahun')),
            'rekap' => 'skpdtpop',
            'id_report' => 'cetak_skpd_tp_op',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_skpdtpop()
    {

        $data = [
            'title' => 'SKPD TP OP',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='98' AND md.tahun =" . session('userData.tahun')),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    public function satkerpagu100m()
    {
        $data = array(
            'title' => 'Satker Pagu > 100 M',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 98,
            'label' => 'Satker',
            'nextlink' => 'paket',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker100m', "md.tahun =" . session('userData.tahun') . " AND md.jml_pagu_total>100000000000"),
            'rekap' => 'satkerpagu100m',
            'id_report' => 'cetak_satker_pagu_100m',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_satkerpagu100m()
    {

        $data = [
            'title' => 'Satker Pagu > 100 M',
            'qdata' => $this->PulldataModel->getBalaiPaket('satker100m', "md.tahun =" . session('userData.tahun') .  " AND md.jml_pagu_total>100000000000"),
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }


    // semua satker

    public function semua_satker($slug = '')
    {
        $kdgiat = ($slug == 'all' ?  "md.tahun = " . session('userData.tahun') : "md.kdgiat={$slug} AND md.tahun = " . session('userData.tahun'));
        $data = array(
            'title'            => 'Semua Satker',
            'posisi'           => ['<i class="fa fa-home"></i>'],
            'idk'              => 'all',
            'label'            => 'Semua Satker',
            'nextlink'         => 'paket',
            'qdata'            => $this->PulldataModel->getBalaiPaket("satker", $kdgiat),
            'kegiatan'         => $this->PulldataModel->getKegiatan(),
            'slug'             => $slug,
            'rekap'            => 'semuasatker',
            'id_report'        => 'cetak_semua_satker',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
    }

    public function cetak_semua_satker()
    {
        $data = [
            'title' => 'Cetak Semua Satker',
            'qdata' => $this->PulldataModel->getBalaiPaket("satker")
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_2_cetak', $data);
    }

    //satker terendah
    public function satker_terendah()
    {
        $data = [
            'title' => 'Satker Terendah',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 'all',
            'label' => 'Satker Terendah',
            'nextlink' => 'paket',
            'qdata' => ['Satker Terendah' => $this->PulldataModel->getBalaiPaket("satker10terendah", "md.tahun = " . session('userData.tahun'))],
            'rekap' => 'satkerterendah',
            'id_report' => 'cetak_satker_terendah',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        ];
        return view('Modules\Admin\Views\Paket\Satker_terendah', $data);
    }

    public function cetak_satker_terendah()
    {
        $data = [
            'title' => 'Cetak Satker Terendah',
            'qdata' => [
                [
                    'title' => 'Satker Terendah',
                    'firstFieldTitle' => 'Satker Terendah',
                    'data' => $this->PulldataModel->getBalaiPaket("satker10terendah", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_terendah'
                ]
            ]
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_cetak_satker', $data);
    }

    //satker tertinggi
    public function satker_tertinggi()
    {
        $data = [
            'title' => 'Satker Tertinggi',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 'all',
            'label' => 'Satker Tertinggi',
            'nextlink' => 'paket',
            'qdata' => ['Satker Tertinggi' => $this->PulldataModel->getBalaiPaket("satker10tertinggi", "md.tahun = " . session('userData.tahun'))],
            'rekap' => 'satkertertinggi',
            'id_report' => 'cetak_satker_tertinggi',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        ];
        return view('Modules\Admin\Views\Paket\Satker_terendah', $data);
    }

    public function cetak_satker_tertinggi()
    {
        $data = [
            'title' => 'Cetak Satker Tertinggi',
            'qdata' => [
                [
                    'title' => 'Satker Tertinggi',
                    'firstFieldTitle' => 'Satker Tertinggi',
                    'data' => $this->PulldataModel->getBalaiPaket("satker10tertinggi", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_tertinggi'
                ]
            ]
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_cetak_satker', $data);
    }


    //satker deviasi terbesar
    public function satker_deviasi_terbesar()
    {
        $data = [
            'title' => 'Satker Deviasi terbesar',
            'posisi' => ['<i class="fa fa-home"></i>'],
            'idk' => 'all',
            'label' => 'Satker Deviasi terbesar',
            'nextlink' => 'paket',
            'qdata' => ['Satker Deviasi terbesar' => $this->PulldataModel->getBalaiPaket("satkerdeviasiterbesar", "md.tahun = " . session('userData.tahun'))],
            'qdata2' => ['Satker Deviasi terbesar' => $this->PulldataModel->getBalaiPaket("satkerdeviasiterbesar_persen", "md.tahun = " . session('userData.tahun'))],
            'rekap' => 'satkerdeviasiterbesar',
            'id_report' => 'cetak_satker_deviasi_terbesar',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        ];
        return view('Modules\Admin\Views\Paket\Satker_deviasi_terbesar', $data);
    }

    public function cetak_satker_deviasi_terbesar()
    {
        $data = [
            'title' => 'Cetak Satker Deviasi Terbesar',
            'qdata' => [
                [
                    'title' => 'Nominal Deviasi Terbesar',
                    'firstFieldTitle' => 'Satker Deviasi terbesar',
                    'data' => $this->PulldataModel->getBalaiPaket("satkerdeviasiterbesar", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_terendah'
                ],
                [
                    'title' => 'Persentase Deviase Terbesar',
                    'firstFieldTitle' => 'Satker Deviasi terbesar',
                    'data' => $this->PulldataModel->getBalaiPaket("satkerdeviasiterbesar_persen", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_terendah'
                ]
            ]
        ];
        return view('Modules\Admin\Views\Paket\cetak\Format_cetak_satker', $data);
    }



    public function progresPerProvinsi()
    {
        $pageData = [];
        $dataProvinsi = $this->tableProvinsi->get()->getResult();
        foreach ($dataProvinsi as $key => $data) {
            $provinsiKey = count($pageData);
            array_push($pageData, [
                'stw'                  => '1',
                'id'                   => $data->kdlokasi,
                'label'                => $data->nmlokasi . " - " . count($pageData),
                'st'                   => '',
                'jml_paket'            => 0,
                'jml_pagu_rpm'         => 0,
                'jml_pagu_sbsn'        => 0,
                'jml_pagu_phln'        => 0,
                'jml_pagu_total'       => 0,
                'jml_real_rpm'         => 0,
                'jml_real_sbsn'        => 0,
                'jml_real_phln'        => 0,
                'jml_real_total'       => 0,
                'jml_progres_keuangan' => 0,
                'jml_progres_fisik'    => 0,
                'jml_persen_deviasi'   => 0,
                'jml_nilai_deviasi'    => 0,
                'is_subheader'         => '1'
            ]);

            $dataPaket = $this->PulldataModel->getBalaiPaket(
                "satker",
                "md.tahun= " . session('userData.tahun') . " AND kdlokasi= " . $data->kdlokasi
            );
            foreach ($dataPaket as $keyPaket => $dataPaket) {
                array_push($pageData, $dataPaket);

                $pageData[$provinsiKey]['jml_paket']            += $dataPaket['jml_paket'];
                $pageData[$provinsiKey]['jml_pagu_rpm']         += $dataPaket['jml_pagu_rpm'];
                $pageData[$provinsiKey]['jml_pagu_sbsn']        += $dataPaket['jml_pagu_sbsn'];
                $pageData[$provinsiKey]['jml_pagu_phln']        += $dataPaket['jml_pagu_phln'];
                $pageData[$provinsiKey]['jml_pagu_total']       += $dataPaket['jml_pagu_total'];
                $pageData[$provinsiKey]['jml_real_rpm']         += $dataPaket['jml_real_rpm'];
                $pageData[$provinsiKey]['jml_real_sbsn']        += $dataPaket['jml_real_sbsn'];
                $pageData[$provinsiKey]['jml_real_phln']        += $dataPaket['jml_real_phln'];
                $pageData[$provinsiKey]['jml_real_total']       += $dataPaket['jml_real_total'];
                $pageData[$provinsiKey]['jml_progres_keuangan'] += $dataPaket['jml_progres_keuangan'];
                $pageData[$provinsiKey]['jml_progres_fisik']    += $dataPaket['jml_progres_fisik'];
                $pageData[$provinsiKey]['jml_persen_deviasi']   += $dataPaket['jml_persen_deviasi'];
                $pageData[$provinsiKey]['jml_nilai_deviasi']    += $dataPaket['jml_nilai_deviasi'];
            }
        }
        // print_r($dataProvinsi);exit;
        //$this->PulldataModel->getBalaiPaket("satker", "md.tahun = " . session('userData.tahun'))
        $data = array(
            'title'            => 'Progres Per Provinsi',
            'posisi'           => ['<i class="fa fa-home"></i>'],
            'idk'              => 'all',
            'label'            => 'Semua Satker',
            'nextlink'         => 'paket',
            'qdata'            => $pageData,
            'kegiatan'         => $this->PulldataModel->getKegiatan(),
            'slug'             => '',
            'rekap'            => 'progress_per_profinsi',
            'id_report'        => 'cetak_semua_satker',
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik')
        );
        return view('Modules\Admin\Views\Paket\Format_2', $data);
        // return view('Modules\Admin\Views\Paket\Progres_per_provinsi', []);
    }





    //pindah ya!
    // function simpandata(){
    //     $this->akses->goakses('add', $this->InModul);

    //     $i=0;
    //     $block =1024*1024;//1MB or counld be any higher than HDD block_size*2
    //     if ($fh = fopen(WRITEPATH . "emon/emon_01.json", "r")) {
    //         $left='';
    //         while (!feof($fh)) {// read the file
    //         $temp = fread($fh, $block);
    //         $fgetslines = explode("\n",$temp);
    //         $fgetslines[0]=$left.$fgetslines[0];
    //         if(!feof($fh) )$left = array_pop($fgetslines);
    //         foreach ($fgetslines as $k => $line) {
    //             $v=str_replace(array('[', ']', '},'), array('', '', '}'), $line);
    //             if($v!=""){
    //                 $this->PulldataModel->replace(json_decode("[$v]", true)[0]);
    //                 $i++;
    //             }
    //         }
    //         }
    //     }
    //     fclose($fh);

    //     return json_encode(['datatersimpan'=>$i]);
    // }

    function simpandata()
    {
        // $this->akses->goakses('add', $this->InModul);

        //$dAr=json_decode('["tahun":"2020","kdsatker":"020104","kdprogram":"10","kdgiat":"5300","kdoutput":"022","kdsoutput":"001","kdkmpnen":"071","kdpaket":"06.020104.10.5300.022.001.071.A","kdls":"","nmpaket":"Operasi Rutin Jaringan Irigasi Air Tanah Kab. Cirebon;Tersebar;Jawa Barat;2 km;30 Hektar;F;S;SYC","pagu_51":"0","pagu_52":"2024000","pagu_53":"0","pagu_rpm":"2024000","pagu_sbsn":"0","pagu_phln":"0","pagu_total":"2024000","real_51":"0","real_52":"2024000","real_53":"0","real_rpm":"2024000","real_sbsn":"0","real_phln":"0","real_total":"2024000","progres_keuangan":"100","progres_fisik":"100","progres_keu_jan":"0","progres_keu_feb":"0","progres_keu_mar":"0","progres_keu_apr":"100","progres_keu_mei":"100","progres_keu_jun":"100","progres_keu_jul":"100","progres_keu_agu":"100","progres_keu_sep":"100","progres_keu_okt":"100","progres_keu_nov":"100","progres_keu_des":"100","progres_fisik_jan":"0","progres_fisik_feb":"0","progres_fisik_mar":"0","progres_fisik_apr":"100","progres_fisik_mei":"100","progres_fisik_jun":"100","progres_fisik_jul":"100","progres_fisik_agu":"100","progres_fisik_sep":"100","progres_fisik_okt":"100","progres_fisik_nov":"100","progres_fisik_des":"100","ren_keu_jan":"0","ren_keu_feb":"0","ren_keu_mar":"20","ren_keu_apr":"20","ren_keu_mei":"40","ren_keu_jun":"40","ren_keu_jul":"60","ren_keu_agu":"60","ren_keu_sep":"80","ren_keu_okt":"80","ren_keu_nov":"100","ren_keu_des":"100","ren_fis_jan":"0","ren_fis_feb":"0","ren_fis_mar":"20","ren_fis_apr":"20","ren_fis_mei":"40","ren_fis_jun":"40","ren_fis_jul":"60","ren_fis_agu":"60","ren_fis_sep":"80","ren_fis_okt":"80","ren_fis_nov":"100","ren_fis_des":"100"]', true);
        //d($dAr);
        // $d =["tahun"=>"2020","kdsatker"=>"020104","kdprogram"=>"10","kdgiat"=>"5300","kdoutput"=>"022","kdsoutput"=>"001","kdkmpnen"=>"071","kdpaket"=>"06.020104.10.5300.022.001.071.A","kdls"=>"","nmpaket"=>"Operasi Rutin Jaringan Irigasi Air Tanah Kab. Cirebon;Tersebar;Jawa Barat;2 km;30 Hektar;F;S;SYC","pagu_51"=>"0","pagu_52"=>"2024000","pagu_53"=>"0","pagu_rpm"=>"2024000","pagu_sbsn"=>"0","pagu_phln"=>"0","pagu_total"=>"2024000","real_51"=>"0","real_52"=>"2024000","real_53"=>"0","real_rpm"=>"2024000","real_sbsn"=>"0","real_phln"=>"0","real_total"=>"2024000","progres_keuangan"=>"100","progres_fisik"=>"100","progres_keu_jan"=>"0","progres_keu_feb"=>"0","progres_keu_mar"=>"0","progres_keu_apr"=>"100","progres_keu_mei"=>"100","progres_keu_jun"=>"100","progres_keu_jul"=>"100","progres_keu_agu"=>"100","progres_keu_sep"=>"100","progres_keu_okt"=>"100","progres_keu_nov"=>"100","progres_keu_des"=>"100","progres_fisik_jan"=>"0","progres_fisik_feb"=>"0","progres_fisik_mar"=>"0","progres_fisik_apr"=>"100","progres_fisik_mei"=>"100","progres_fisik_jun"=>"100","progres_fisik_jul"=>"100","progres_fisik_agu"=>"100","progres_fisik_sep"=>"100","progres_fisik_okt"=>"100","progres_fisik_nov"=>"100","progres_fisik_des"=>"100","ren_keu_jan"=>"0","ren_keu_feb"=>"0","ren_keu_mar"=>"20","ren_keu_apr"=>"20","ren_keu_mei"=>"40","ren_keu_jun"=>"40","ren_keu_jul"=>"60","ren_keu_agu"=>"60","ren_keu_sep"=>"80","ren_keu_okt"=>"80","ren_keu_nov"=>"100","ren_keu_des"=>"100","ren_fis_jan"=>"0","ren_fis_feb"=>"0","ren_fis_mar"=>"20","ren_fis_apr"=>"20","ren_fis_mei"=>"40","ren_fis_jun"=>"40","ren_fis_jul"=>"60","ren_fis_agu"=>"60","ren_fis_sep"=>"80","ren_fis_okt"=>"80","ren_fis_nov"=>"100","ren_fis_des"=>"100"];
        // $h = $this->PulldataModel->insert($d);
        // $h = $this->PulldataModel->replace($d);
        // dd($h);

        $i = 0;
        $block = 1024 * 1024; //1MB or counld be any higher than HDD block_size*2
        if ($fh = fopen(WRITEPATH . "emon/20200827_0017.txt", "r")) {
            $left = '';
            while (!feof($fh)) { // read the file
                $temp = fread($fh, $block);

                $temp = str_replace(array("[", "]", "}{"), array("", "", "},{"), $temp);
                // d($temp);

                $fgetslines = explode("},", $temp);
                $fgetslines[0] = $left . $fgetslines[0];
                if (!feof($fh)) $left = array_pop($fgetslines);
                // d($fgetslines);

                $data = "";
                // d($fgetslines);
                foreach ($fgetslines as $k => $line) {
                    // $v=str_replace(array('[', ']', '},'), array('', '', '}'), $line);
                    // if($v!=""){
                    //     $this->PulldataModel->replace(json_decode("[$v]", true)[0]);
                    //     $i++;
                    // }
                    if ($line != "") {
                        // $data.=$line . "}" . ($k < (count($fgetslines)-1) ? ",":"");

                        $d = $line . "}";
                        //dd(json_decode("[$d]", true));
                        $d = json_decode("[$d]", true);
                        // d($dAr);
                        // if(count($dAr)>0) $this->PulldataModel->save($dAr[0]);

                        if ($this->PulldataModel->replace($d[0])) $i++;
                    }
                }
                // d($k);
                // d($data);
                // $data = json_decode("[$data]", true);
                // $this->PulldataModel->save($data);
                // dd($data);


            }
        }
        fclose($fh);

        return json_encode(['datatersimpan' => $i]);
    }

    //rekap
    public function rekap($pg)
    {
        $session = session();
        if (empty($_GET['idks'])) {
            $_GET['idks'] = '';
        }

        $pgview = "Rekap-1";
        if ($pg == "paket") $pgview = "Rekap-2";

        $useSatkerFormat = ['satkerterendah', 'satkertertinggi', 'satkerdeviasiterbesar'];

        $hal['unitkerja']       = ['pg' => 'unitkerja', 'idk' => '', 'label' => '', 'filter' => 'balai', 'where' => null, 'title' => 'Unit Kerja'];
        $hal['bbws']            = ['pg' => 'bbws', 'idk' => '', 'label' => '', 'filter' => 'balai', 'where' => "b.st like 'BBWS'", 'title' => 'BBWS'];
        $hal['bws']             = ['pg' => 'bws', 'idk' => '', 'label' => '', 'filter' => 'balai', 'where' => "b.st like 'BWS'", 'title' => 'BWS'];
        $hal['satkerpusat']     = ['pg' => 'satkerpusat', 'idk' => '', 'label' => '', 'filter' => 'satker', 'where' => "b.balaiid='99'", 'title' => 'Satker Pusat'];
        $hal['satker']          = ['pg' => 'satker', 'idk' => '', 'label' => '', 'filter' => 'satker', 'where' => "b.balaiid!='99'", 'title' => 'Satuan Kerja'];
        $hal['skpdtpop']        = ['pg' => 'skpdtpop', 'idk' => '', 'label' => '', 'filter' => 'satker', 'where' => "b.balaiid='98'", 'title' => 'SKPD TP OP'];
        $hal['satkerpagu100m']  = ['pg' => 'satkerpagu100m', 'idk' => '', 'label' => '', 'filter' => 'satker100m', 'where' => "md.jml_pagu_total>100000000000", 'title' => 'Satker Pagu > 100 M'];
        $hal['satuankerja']     = ['pg' => 'satuankerja', 'idk' => $_GET['idk'], 'label' => $_GET['label'], 'filter' => 'satker', 'where' => "b.balaiid='{$_GET['idk']}'", 'title' => 'Satuan Kerja'];

        //$balaiid='',$satkerid='',  $balai='', $satker=''
        $hal['paket']     = ['pg' => 'paket', 'idk' => $_GET['idk'], 'label' => $_GET['label'], 'label2' => (!empty($_GET['label2']) ? $_GET['label2'] : ''), 'format' => (!empty($_GET['format']) ? $_GET['format'] : ''), 'filter' => 'satker', 'where' => "md.kdsatker='{$_GET['idks']}'", 'title' => 'Paket'];
        $hal['balaiteknik']     = ['pg' => 'balaiteknik', 'idk' => $_GET['idk'], 'label' => $_GET['label'], 'filter' => 'satker', 'where' => "b.balaiid='{$_GET['idk']}'", 'title' => 'Balai Teknik'];
        $hal['semuasatker']     = ['pg' => 'balaiteknik', 'idk' => '', 'label' => $_GET['label'], 'filter' => 'satker', 'where' => "", 'title' => 'Semua Satker'];

        $hal['satkerterendah']     = [
            'pg' => 'balaiteknik',
            'idk' => '',
            'label' => $_GET['label'],
            'getData' => [
                [
                    'title' => 'Satker Terendah',
                    'firstFieldTitle' => 'Satker Terendah',
                    'data' => $this->PulldataModel->getBalaiPaket("satker10terendah", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_terendah'
                ]
            ],
            'title' => 'Satker Terendah'
        ];

        $hal['satkertertinggi']     = [
            'pg' => 'balaiteknik',
            'idk' => '',
            'label' => $_GET['label'],
            'getData' => [
                [
                    'title' => 'Satker Tertinggi',
                    'firstFieldTitle' => 'Satker Tertinggi',
                    'data' => $this->PulldataModel->getBalaiPaket("satker10tertinggi", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_tertinggi'
                ]
            ],
            'title' => 'Satker Tertinggi'
        ];

        $hal['satkerdeviasiterbesar']     = [
            'pg' => 'balaiteknik',
            'idk' => '',
            'label' => $_GET['label'],
            'getData' => [
                [
                    'title' => 'Nominal Deviasi Terbesar',
                    'firstFieldTitle' => 'Satker Deviasi terbesar',
                    'data' => $this->PulldataModel->getBalaiPaket("satkerdeviasiterbesar", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_terendah'
                ],
                [
                    'title' => 'Persentase Deviase Terbesar',
                    'firstFieldTitle' => 'Satker Deviasi terbesar',
                    'data' => $this->PulldataModel->getBalaiPaket("satkerdeviasiterbesar_persen", "md.tahun = " . session('userData.tahun')),
                    'template' => 'satker_terendah'
                ]
            ],
            'title' => 'Satker Deviasi Terbesar'
        ];

        foreach ($hal as $key => $value) {
            $hall[] = $key;
        }

        if ($pg == "paket") {
            if (in_array($_GET['rekap'], array("satkerpagu100m"))) {
                return redirect()->to('/pulldata/nopage/' . $pg);
            }
            if (!in_array($_GET['idks'], array("satkerpagu100m")) && in_array($_GET['rekap'], $hall)) {
                $hal['paket']['where'] = $hal[$_GET['rekap']]['where'];
            } elseif ($session->get('userData')['group_id'] == "Administrator" && $_GET['idks'] == 'all') {
                $hal['paket']['where'] = '';
            } elseif ($_GET['idks'] == $_GET['idk']) {
                $hal['paket']['where'] = "b.balaiid='{$_GET['idk']}'";
            }
            // else{
            //     $hal['paket']['where']=$hal[$_GET['idks']]['where'];
            // }

            if ($hal['paket']['format'] == 'db') {
                $pgview = "Rekap-3";
            }
        }
        // d($pgview);
        // dd($hal);

        // foreach ($hal as $key => $value) {$hall[]=$key;}
        if (!in_array($pg, $hall)) {
            return redirect()->to('/pulldata/nopage/' . $pg);
        }

        $fileName = "rekapMonika-" . $hal[$pg]['title'] . "-" . date('Ymdhis');
        $data = $hal[$pg];


        if (in_array($pg, $useSatkerFormat)) {
            $data['qdata'] = $hal[$pg]['getData'];
            $pgview = "Rekap-satker";
        } else {
            $data['qdata'] = ($pg == "paket" ? $this->PulldataModel->getPaket($hal[$pg]['where']) : ($hal[$pg]['where'] == null ? $this->PulldataModel->getBalaiPaket($hal[$pg]['filter']) : $this->PulldataModel->getBalaiPaket($hal[$pg]['filter'], $hal[$pg]['where'])));
        }
        // dd($data);

        // $data = array(
        //     'title' => $hal[$pg]['title'],
        //     //'posisi'=> ['<i class="fa fa-home"></i>'],
        //     'idk'   => $hal[$pg]['idk'],
        //     'label' => $hal[$pg]['label'],
        //     'qdata' =>
        //     ($pg=="paket" ? $this->PulldataModel->getPaket($hal[$pg]['where']) :
        //     ($hal[$pg]['where']==null ? $this->PulldataModel->getBalaiPaket($hal[$pg]['filter']) : $this->PulldataModel->getBalaiPaket($hal[$pg]['filter'], $hal[$pg]['where']))
        //     )
        // );

        // $data = array(
        //     'title' => 'Paket',
        //     'posisi'=>['<a href="'.site_url("pulldata/unitkerja").'"><i class="fa fa-home"></i></a>','<a href="'.site_url("pulldata/satuankerja/$balaiid/$balai").'">'.$balai.'</a>', $satker],
        //     'qdata'=> $this->PulldataModel->getPaket("md.kdsatker='$satkerid'")
        // );
        // return view('Modules\Admin\Views\Paket\Format_3', $data);

        if ($pg == 'progress_per_profinsi') {
            $pgview = 'Rekap-progress-per-provinsi';
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $fileName . ".xls");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        return view("Modules\Admin\Views\Paket\Rekap\\$pgview", $data);
    }




    public function rekapProgressPerProvinsi($target)
    {
        if ($target == 'excel') {
            header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: attachment; filename=Rekap-progress-per-provinsi.xls");
            header("Pragma: no-cache");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");
        }

        $pageData = [];
        $dataProvinsi = $this->tableProvinsi->get()->getResult();
        foreach ($dataProvinsi as $key => $data) {
            $provinsiKey = count($pageData);
            array_push($pageData, [
                'stw'                  => '1',
                'id'                   => $data->kdlokasi,
                'label'                => $data->nmlokasi . " - " . count($pageData),
                'st'                   => '',
                'jml_paket'            => 0,
                'jml_pagu_rpm'         => 0,
                'jml_pagu_sbsn'        => 0,
                'jml_pagu_phln'        => 0,
                'jml_pagu_total'       => 0,
                'jml_real_rpm'         => 0,
                'jml_real_sbsn'        => 0,
                'jml_real_phln'        => 0,
                'jml_real_total'       => 0,
                'jml_progres_keuangan' => 0,
                'jml_progres_fisik'    => 0,
                'jml_persen_deviasi'   => 0,
                'jml_nilai_deviasi'    => 0,
                'is_subheader'         => '1'
            ]);

            $dataPaket = $this->PulldataModel->getBalaiPaket(
                "satker",
                "md.tahun= " . session('userData.tahun') . " AND kdlokasi= " . $data->kdlokasi
            );
            foreach ($dataPaket as $keyPaket => $dataPaket) {
                array_push($pageData, $dataPaket);

                $pageData[$provinsiKey]['jml_paket']            += $dataPaket['jml_paket'];
                $pageData[$provinsiKey]['jml_pagu_rpm']         += $dataPaket['jml_pagu_rpm'];
                $pageData[$provinsiKey]['jml_pagu_sbsn']        += $dataPaket['jml_pagu_sbsn'];
                $pageData[$provinsiKey]['jml_pagu_phln']        += $dataPaket['jml_pagu_phln'];
                $pageData[$provinsiKey]['jml_pagu_total']       += $dataPaket['jml_pagu_total'];
                $pageData[$provinsiKey]['jml_real_rpm']         += $dataPaket['jml_real_rpm'];
                $pageData[$provinsiKey]['jml_real_sbsn']        += $dataPaket['jml_real_sbsn'];
                $pageData[$provinsiKey]['jml_real_phln']        += $dataPaket['jml_real_phln'];
                $pageData[$provinsiKey]['jml_real_total']       += $dataPaket['jml_real_total'];
                $pageData[$provinsiKey]['jml_progres_keuangan'] += $dataPaket['jml_progres_keuangan'];
                $pageData[$provinsiKey]['jml_progres_fisik']    += $dataPaket['jml_progres_fisik'];
                $pageData[$provinsiKey]['jml_persen_deviasi']   += $dataPaket['jml_persen_deviasi'];
                $pageData[$provinsiKey]['jml_nilai_deviasi']    += $dataPaket['jml_nilai_deviasi'];
            }
        }

        return view("Modules\Admin\Views\Paket\Rekap\Rekap-progress-per-provinsi", [
            'qdata'                    => $pageData,
            'pagusda_progres_keuangan' => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'pagusda_progres_fisik'    => $this->RekapUnorModel->getProgresSda('progres_fisik'),
            'dokumen_target'           => $target
        ]);
    }
}
