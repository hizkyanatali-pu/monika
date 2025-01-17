<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\API\ResponseTrait;
use Modules\Admin\Models\TematikModel;
use Modules\Admin\Models\RekapUnorModel;
use Modules\Admin\Models\PohonAnggaranModel;
use Modules\Admin\Models\PulldataModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class Dashboard extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db_mysql = \Config\Database::connect();
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);
        $this->TematikModel = new TematikModel();
        $this->RekapUnorModel = new RekapUnorModel();
        $this->PohonAnggaran        = new PohonAnggaranModel();
        $this->PulldataModel        = new PulldataModel();

        $this->request = \Config\Services::request();
    }




    public function index()
    {

        //cek jika tabel yang berkaitan dengan api emon kosong 

        $check_empty_table_paket = $this->db_mysql->query("SELECT * FROM monika_data_{$this->user['tahun']}")->getNumRows();
        $check_empty_table_kontrak = $this->db_mysql->query("SELECT * FROM monika_kontrak_{$this->user['tahun']}")->getNumRows();
        $check_empty_table_rekap_unor = $this->db_mysql->query("SELECT * FROM monika_rekap_unor_{$this->user['tahun']}")->getNumRows();
        $check_empty_table_paket_register = $this->db_mysql->query("SELECT * FROM monika_paket_register_{$this->user['tahun']}")->getNumRows();



        if ($check_empty_table_paket < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/paket"));
        }

        if ($check_empty_table_kontrak < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/kontrak"));
        }

        if ($check_empty_table_rekap_unor < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/rekap_unor"));
        }

        if ($check_empty_table_paket_register < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/paket_register"));
        }

        // end


        $filterDateStart = $this->request->getGet('filter-date-start') ?? null;
        $filterDateEnd   = $this->request->getGet('filter-date-end') ?? null;

        // print_r($filterDateStart);
        // exit;

        $grupData = $this->rekapGroupData();
        $qdata = [];

        //pohon terkontrak
        $qterkontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "terkontrak"], $filterDateStart, $filterDateEnd);
        $qproseslelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Proses Lelang"], $filterDateStart, $filterDateEnd);
        $qbelumlelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Belum Lelang"], $filterDateStart, $filterDateEnd);
        $qpersiapankontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Persiapan kontrak"], $filterDateStart, $filterDateEnd);


        $rekapUnor = $this->RekapUnorModel->getRekapUnor();
        $getGraphicData = $this->PulldataModel->getGraphicDataProgressPerSumberDana();
        $getGraphicDataJenisBelanja = $this->PulldataModel->getGraphicDataProgressPerJenisBelanja();
        $getGraphicDataPerkegiatan = $this->PulldataModel->getGraphicDataProgressPerKegiatan();


        // postur belum lelang



        //table perkegiatan

        $belumlelangPerkegiatanRpmSyc =  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_rpm", 0, true, $filterDateStart, $filterDateEnd);
        $belumlelangPerkegiatanMyc =  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_total", "1,2,3", false, $filterDateStart, $filterDateEnd);




        // $data = array(
        //     'title' => 'Belum Lelang',
        // );


        $data = array(
            'title' => 'Dashboard',
            'data' => $qdata,
            'rekapunor' => $rekapUnor,

            //pohon kontraktual
            'terkontrak' => $qterkontrak,
            'proseslelang' => $qproseslelang,
            'belumlelang' => $qbelumlelang,
            'persiapankontrak' => $qpersiapankontrak,
            'gagallelang' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Gagal Lelang"]),


            'keu' => $this->getprogreskeu("keuangan"),
            'fis' => $this->getprogreskeu("fisik"),
            'pagu' =>    $getGraphicData,
            'jenisbelanja' =>  $getGraphicDataJenisBelanja,
            'perkegiatan' =>  $getGraphicDataPerkegiatan,

            // belum lelang RPM syc
            'belum_lelang_rpm_syc' => $belumlelangPerkegiatanRpmSyc,
            'belum_lelang_myc' => $belumlelangPerkegiatanMyc,
            'belum_lelang_phln_project_loan' =>  $this->PohonAnggaran->getDataBelumLelangPhlnMycProjectLoan("pagu_phln", "1,2,3", false, $filterDateStart, $filterDateEnd),


            'qdata' => [
                "bbws" => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS'"),
                "bws" => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BWS'"),
                "pusat" => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='99'"),
                'Balai Teknik' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='97'"),
                'dinas' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='98'"),
                'Semua Satker' => $this->PulldataModel->getBalaiPaket("satker10terendah")
            ],



        );

        // belum lelang 

        $data['nilai_rpm'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "RPM", $filterDateStart, $filterDateEnd);
        $data['nilai_sbsn'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "SBSN", $filterDateStart, $filterDateEnd);
        $data['nilai_phln'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "PHLN", $filterDateStart, $filterDateEnd);

        $data['rpmSyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "RPM", $filterDateStart, $filterDateEnd);
        $data['rpmMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "RPM", $filterDateStart, $filterDateEnd);

        $data['phlnMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd);


        $data['rpmSycList'] = $this->PohonAnggaran->getDataBelumLelangList([[0]], "RPM", $filterDateStart, $filterDateEnd);
        $data['rpmMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "RPM", $filterDateStart, $filterDateEnd);
        $data['phlnMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd);


        //rencana tender
        $data['tenderRpm'] =  $this->PohonAnggaran->getDataRencanaTenderBelumLelang("RPM", null, null, false, $filterDateStart, $filterDateEnd);
        $data['tenderPhln'] =  $this->PohonAnggaran->getDataRencanaTenderBelumLelang("PHLN", null, null, false, $filterDateStart, $filterDateEnd);


        // Filter Menu Dashboard
        $data['filterMenu'] = [
            [
                'title'      => 'PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR',
                'menuId'     => 'progres_fisik_keuangan_kementerian_pupr',
                'alwaysShow' => true
            ],
            [
                'title'      => 'PROGRES PROGRAM PADAT KARYA PER KEGIATAN',
                'menuId'     => 'progres_program_padat_karya_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR',
                'menuId'     => 'progress_kegiatan_tematik_direktorat_jenderal_sumber_daya_air',
                'alwaysShow' => false
            ],
            [
                'title'      => 'POSTUR PAKET KONTRAKTUAL',
                'menuId'     => 'viewkontraktual',
                'alwaysShow' => false
            ],
            [
                'title'      => 'POSTUR PAKET BELUM LELANG',
                'menuId'     => 'belum-lelang',
                'alwaysShow' => false
            ],
            [
                'title'      => 'DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN',
                'menuId'     => 'daftar_paket_belum_lelang_rpm_syc_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN',
                'menuId'     => 'daftar_paket_belum_lelang_myc_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PAKET BELUM LELANG PHLN - MYC PROJECT LOAN',
                'menuId'     => 'paket_belum_lelang_phln_myc_project_loan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'RENCANA TENDER, PAKET BELUM LELANG RPM',
                'menuId'     => 'rencana_tender_paket_belum_lelang_rpm',
                'alwaysShow' => false
            ],
            [
                'title'      => 'RENCANA TENDER, PAKET BELUM LELANG PLN',
                'menuId'     => 'rencana_tender_paket_belum_lelang_pln',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK DITJEN SDA',
                'menuId'     => 'progres_keuangan_fisik_ditjen_sda',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK PER KEGIATAN',
                'menuId'     => 'progres_keuangan_fisik_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - BBWS',
                'menuId'     => 'progres_keuangan_fisik_bbws',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - BWS',
                'menuId'     => 'progres_keuangan_fisik_bws',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - PUSAT',
                'menuId'     => 'progres_keuangan_fisik_pusat',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - BALAI TEKNIK',
                'menuId'     => 'progres_keuangan_fisik_balai_teknik',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - DINAS',
                'menuId'     => 'progres_keuangan_fisik_dinas',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES 10 SATUAN KERJA TERENDAH',
                'menuId'     => 'progres_10_satuan_kerja_terendah',
                'alwaysShow' => false
            ]
        ];

        return view('Modules\Admin\Views\Dashboard', $data);
    }

    public function index2(){
        //cek jika tabel yang berkaitan dengan api emon kosong 

        $check_empty_table_paket = $this->db_mysql->query("SELECT * FROM monika_data_{$this->user['tahun']}")->getNumRows();
        $check_empty_table_kontrak = $this->db_mysql->query("SELECT * FROM monika_kontrak_{$this->user['tahun']}")->getNumRows();
        $check_empty_table_rekap_unor = $this->db_mysql->query("SELECT * FROM monika_rekap_unor_{$this->user['tahun']}")->getNumRows();
        $check_empty_table_paket_register = $this->db_mysql->query("SELECT * FROM monika_paket_register_{$this->user['tahun']}")->getNumRows();



        if ($check_empty_table_paket < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/paket"));
        }

        if ($check_empty_table_kontrak < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/kontrak"));
        }

        if ($check_empty_table_rekap_unor < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/rekap_unor"));
        }

        if ($check_empty_table_paket_register < 1) {

            return redirect()->to(site_url("preferensi/tarik-data-emon/paket_register"));
        }

        // end


        $filterDateStart = $this->request->getGet('filter-date-start') ?? null;
        $filterDateEnd   = $this->request->getGet('filter-date-end') ?? null;

        // print_r($filterDateStart);
        // exit;

        $grupData = $this->rekapGroupData();
        $qdata = [];

        //pohon terkontrak
        $qterkontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "terkontrak"], $filterDateStart, $filterDateEnd);
        $qproseslelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Proses Lelang"], $filterDateStart, $filterDateEnd);
        $qbelumlelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Belum Lelang"], $filterDateStart, $filterDateEnd);
        $qpersiapankontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Persiapan kontrak"], $filterDateStart, $filterDateEnd);


        $rekapUnor = $this->RekapUnorModel->getRekapUnor();
        $getGraphicData = $this->PulldataModel->getGraphicDataProgressPerSumberDana();
        $getGraphicDataJenisBelanja = $this->PulldataModel->getGraphicDataProgressPerJenisBelanja();
        $getGraphicDataPerkegiatan = $this->PulldataModel->getGraphicDataProgressPerKegiatan();


        // postur belum lelang



        //table perkegiatan

        $belumlelangPerkegiatanRpmSyc =  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_rpm", 0, true, $filterDateStart, $filterDateEnd);
        $belumlelangPerkegiatanMyc =  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_total", "1,2,3", false, $filterDateStart, $filterDateEnd);




        // $data = array(
        //     'title' => 'Belum Lelang',
        // );


        $data = array(
            'title' => 'Dashboard',
            'data' => $qdata,
            'rekapunor' => $rekapUnor,

            //pohon kontraktual
            'terkontrak' => $qterkontrak,
            'proseslelang' => $qproseslelang,
            'belumlelang' => $qbelumlelang,
            'persiapankontrak' => $qpersiapankontrak,
            'gagallelang' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Gagal Lelang"]),


            'keu' => $this->getprogreskeu("keuangan"),
            'fis' => $this->getprogreskeu("fisik"),
            'pagu' =>    $getGraphicData,
            'jenisbelanja' =>  $getGraphicDataJenisBelanja,
            'perkegiatan' =>  $getGraphicDataPerkegiatan,

            // belum lelang RPM syc
            'belum_lelang_rpm_syc' => $belumlelangPerkegiatanRpmSyc,
            'belum_lelang_myc' => $belumlelangPerkegiatanMyc,
            'belum_lelang_phln_project_loan' =>  $this->PohonAnggaran->getDataBelumLelangPhlnMycProjectLoan("pagu_phln", "1,2,3", false, $filterDateStart, $filterDateEnd),


            'qdata' => [
                "bbws" => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS'"),
                "bws" => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BWS'"),
                "pusat" => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='99'"),
                'Balai Teknik' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='97'"),
                'dinas' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='98'"),
                'Semua Satker' => $this->PulldataModel->getBalaiPaket("satker10terendah")
            ],



        );

        // belum lelang 

        $data['nilai_rpm'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "RPM", $filterDateStart, $filterDateEnd);
        $data['nilai_sbsn'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "SBSN", $filterDateStart, $filterDateEnd);
        $data['nilai_phln'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "PHLN", $filterDateStart, $filterDateEnd);

        $data['rpmSyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "RPM", $filterDateStart, $filterDateEnd);
        $data['rpmMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "RPM", $filterDateStart, $filterDateEnd);
        $data['phlnMyc'] = $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd);


        $data['rpmSycList'] = $this->PohonAnggaran->getDataBelumLelangList([[0]], "RPM", $filterDateStart, $filterDateEnd);
        $data['rpmMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "RPM", $filterDateStart, $filterDateEnd);
        $data['phlnMycList'] = $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd);


        //rencana tender
        $data['tenderRpm'] =  $this->PohonAnggaran->getDataRencanaTenderBelumLelang("RPM", null, null, false, $filterDateStart, $filterDateEnd);
        $data['tenderPhln'] =  $this->PohonAnggaran->getDataRencanaTenderBelumLelang("PHLN", null, null, false, $filterDateStart, $filterDateEnd);


        // Filter Menu Dashboard
        $data['filterMenu'] = [
            [
                'title'      => 'PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR',
                'menuId'     => 'progres_fisik_keuangan_kementerian_pupr',
                'alwaysShow' => true
            ],
            [
                'title'      => 'PROGRES PROGRAM PADAT KARYA PER KEGIATAN',
                'menuId'     => 'progres_program_padat_karya_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR',
                'menuId'     => 'progress_kegiatan_tematik_direktorat_jenderal_sumber_daya_air',
                'alwaysShow' => false
            ],
            [
                'title'      => 'POSTUR PAKET KONTRAKTUAL',
                'menuId'     => 'viewkontraktual',
                'alwaysShow' => false
            ],
            [
                'title'      => 'POSTUR PAKET BELUM LELANG',
                'menuId'     => 'belum-lelang',
                'alwaysShow' => false
            ],
            [
                'title'      => 'DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN',
                'menuId'     => 'daftar_paket_belum_lelang_rpm_syc_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN',
                'menuId'     => 'daftar_paket_belum_lelang_myc_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PAKET BELUM LELANG PHLN - MYC PROJECT LOAN',
                'menuId'     => 'paket_belum_lelang_phln_myc_project_loan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'RENCANA TENDER, PAKET BELUM LELANG RPM',
                'menuId'     => 'rencana_tender_paket_belum_lelang_rpm',
                'alwaysShow' => false
            ],
            [
                'title'      => 'RENCANA TENDER, PAKET BELUM LELANG PLN',
                'menuId'     => 'rencana_tender_paket_belum_lelang_pln',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK DITJEN SDA',
                'menuId'     => 'progres_keuangan_fisik_ditjen_sda',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK PER KEGIATAN',
                'menuId'     => 'progres_keuangan_fisik_per_kegiatan',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - BBWS',
                'menuId'     => 'progres_keuangan_fisik_bbws',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - BWS',
                'menuId'     => 'progres_keuangan_fisik_bws',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - PUSAT',
                'menuId'     => 'progres_keuangan_fisik_pusat',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - BALAI TEKNIK',
                'menuId'     => 'progres_keuangan_fisik_balai_teknik',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES KEUANGAN & FISIK - DINAS',
                'menuId'     => 'progres_keuangan_fisik_dinas',
                'alwaysShow' => false
            ],
            [
                'title'      => 'PROGRES 10 SATUAN KERJA TERENDAH',
                'menuId'     => 'progres_10_satuan_kerja_terendah',
                'alwaysShow' => false
            ]
        ];
        return view('Modules\Admin\Views\Dashboard2',$data);
    }

    public function index_mobile(){
        $filterDateStart = $this->request->getGet('filter-date-start') ?? null;
        $filterDateEnd   = $this->request->getGet('filter-date-end') ?? null;
        $tahun = $this->user['tahun'];

        $data = [
            'pagu' =>    $this->PulldataModel->getGraphicDataProgressPerSumberDana(),
            'rekapunor' => $this->RekapUnorModel->getRekapUnor(),
            'data'  => [],
            'terkontrak' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "terkontrak"], $filterDateStart, $filterDateEnd),
            'proseslelang' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Proses Lelang"], $filterDateStart, $filterDateEnd),
            'belumlelang' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Belum Lelang"], $filterDateStart, $filterDateEnd),
            'persiapankontrak' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Persiapan kontrak"], $filterDateStart, $filterDateEnd),
            'gagallelang' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Gagal Lelang"]),
            'nilai_rpm' => $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "RPM", $filterDateStart, $filterDateEnd),
            'nilai_sbsn' => $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "SBSN", $filterDateStart, $filterDateEnd),
            'nilai_phln' => $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "PHLN", $filterDateStart, $filterDateEnd),
            'rpmSyc' => $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "RPM", $filterDateStart, $filterDateEnd),
            'rpmMyc' => $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "RPM", $filterDateStart, $filterDateEnd),
            'phlnMyc' => $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd),
            'rpmSycList' => $this->PohonAnggaran->getDataBelumLelangList([[0]], "RPM", $filterDateStart, $filterDateEnd),
            'rpmMycList' => $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "RPM", $filterDateStart, $filterDateEnd),
            'phlnMycList' => $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd),
            'belum_lelang_rpm_syc' =>  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_rpm", 0, true, $filterDateStart, $filterDateEnd),
            'belum_lelang_myc' =>  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_total", "1,2,3", false, $filterDateStart, $filterDateEnd),
            'belum_lelang_phln_project_loan' =>  $this->PohonAnggaran->getDataBelumLelangPhlnMycProjectLoan("pagu_phln", "1,2,3", false, $filterDateStart, $filterDateEnd),
            'perkegiatan' => $this->PulldataModel->getGraphicDataProgressPerKegiatan(),
            'keu' => $this->getprogreskeu("keuangan"),
            'fis' => $this->getprogreskeu("fisik"),
            'pagu' =>    $this->PulldataModel->getGraphicDataProgressPerSumberDana(),
            'jenisbelanja' =>  $this->PulldataModel->getGraphicDataProgressPerJenisBelanja(),
            'perkegiatan' =>  $this->PulldataModel->getGraphicDataProgressPerKegiatan(),
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik'),
        ];
        $qdata = $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS' AND md.tahun =" . session('userData.tahun'));
        $data['total_deviasi_keuangan'] = 0;
        $data['total_deviasi_fisik'] = 0;
        foreach ($qdata as $qdata_val) {
            $data['total_deviasi_keuangan'] += $qdata_val['jml_persen_deviasi_keuangan'];
            $data['total_deviasi_fisik'] += $qdata_val['jml_persen_deviasi_fisik'];
        }
        // echo json_encode($data['total_deviasi_keuangan']);die;

        $data['pagu_total'] = $this->db_mysql->table("monika_data_{$tahun}")
        ->selectSum('pagu_total', 'total_pagu')
        ->get()->getRow();

        $data['real_total'] = $this->db_mysql->table("monika_data_{$tahun}")
        ->selectSum('real_total', 'total_real')
        ->get()->getRow();

        $sql = "SELECT (SUM(pagu_total) - SUM(real_total)) AS selisih 
        FROM monika_data_{$tahun}";
        $data['selisih_total'] = $this->db_mysql->query($sql)->getRow();

        $data['pagu_all'] = $this->db_mysql->table("monika_data_{$tahun}") 
        ->selectSum('pagu_total', 'total_pagu')
        ->selectSum('pagu_rpm', 'total_rpm')
        ->selectSum('real_rpm', 'total_real_rpm')
        ->selectSum('pagu_sbsn', 'total_sbsn')
        ->selectSum('real_sbsn', 'total_real_sbsn')
        ->selectSum('pagu_phln', 'total_phln')
        ->selectSum('real_phln', 'total_real_phln')
        ->get()->getRow();

        $data['satker_desc'] = $this->db_mysql->table("monika_data_{$tahun}")
        ->select("m_satker.satker, SUM(progres_keuangan) AS total_keu_progres, SUM(progres_fisik) AS total_fis_progres, SUM(progres_keuangan + progres_fisik) AS total_progres, SUM(blokir) AS total_blokir")
        ->join('m_satker',"m_satker.satkerid = monika_data_{$tahun}.kdsatker",'left')
        ->where("m_satker.satker is not null")
        ->groupBy("kdsatker")
        ->orderBy('total_progres', 'DESC')
        ->limit(10)
        ->get()->getResult();

        $data['satker_asc'] = $this->db_mysql->table("monika_data_{$tahun}")
        ->select("m_satker.satker, SUM(progres_keuangan) AS total_keu_progres, SUM(progres_fisik) AS total_fis_progres, SUM(progres_keuangan + progres_fisik) AS total_progres, SUM(blokir) AS total_blokir")
        ->join('m_satker',"m_satker.satkerid = monika_data_{$tahun}.kdsatker",'left')
        ->where("m_satker.satker is not null")
        ->groupBy('kdsatker')
        ->orderBy('total_progres', 'ASC')
        ->limit(10)
        ->get()->getResult();

        $data['balai_desc'] = $this->db_mysql->table("monika_data_{$tahun}")
        ->select("m_balai.balai, SUM(progres_keuangan) AS total_keu_progres, SUM(progres_fisik) AS total_fis_progres, SUM(progres_keuangan + progres_fisik) AS total_progres, SUM(blokir) AS total_blokir")
        ->join('m_satker',"m_satker.satkerid = monika_data_{$tahun}.kdsatker",'left')
        ->where("m_balai.balai is not null")
        ->join('m_balai',"m_satker.balaiid = m_balai.balaiid",'left')
        ->groupBy('m_balai.balaiid')
        ->orderBy('total_progres', 'DESC')
        ->limit(10)
        ->get()->getResult();

        $data['balai_asc'] = $this->db_mysql->table("monika_data_{$tahun}")
        ->select("m_balai.balai, SUM(progres_keuangan) AS total_keu_progres, SUM(progres_fisik) AS total_fis_progres, SUM(progres_keuangan + progres_fisik) AS total_progres, SUM(blokir) AS total_blokir")
        ->join('m_satker',"m_satker.satkerid = monika_data_{$tahun}.kdsatker",'left')
        ->where("m_balai.balai is not null")
        ->join('m_balai',"m_satker.balaiid = m_balai.balaiid",'left')
        ->groupBy('m_balai.balaiid')
        ->orderBy('total_progres', 'ASC')
        ->limit(10)
        ->get()->getResult();

        $data['kegiatan'] = $this->db_mysql->table("tgiat")
        ->select("tgiat.nmgiat as nmgiat, SUM(pagu_total) AS total_pagu, SUM(real_total) AS total_real, SUM(blokir) AS total_blokir")
        ->join("monika_data_{$tahun}","tgiat.kdgiat = monika_data_{$tahun}.kdgiat",'left')
        ->where("tgiat.tahun_anggaran",$tahun)
        ->orderBy('tgiat.kdgiat', 'ASC')
        ->groupBy('tgiat.kdgiat')
        ->get()->getResult();

        $data['sub_terkontrak'] = $this->db_mysql->table("monika_kontrak_{$tahun} kon")
        ->select("
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_syc,

            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_myc,

            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as sum_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as sum_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_syc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as count_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as count_myc
        ")
        ->join("monika_data_{$tahun} dat","kon.kdpaket = dat.kdpaket",'left')
        ->orderBy("kon.idpull", 'ASC')
        ->where("kon.status_tender", 'terkontrak')
        ->get()->getRow();

        $data['sub_proses_lelang'] = $this->db_mysql->table("monika_kontrak_{$tahun} kon")
        ->select("
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_syc,

            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_myc,

            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as sum_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as sum_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_syc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as count_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as count_myc
        ")
        ->join("monika_data_{$tahun} dat","kon.kdpaket = dat.kdpaket",'left')
        ->orderBy("kon.idpull", 'ASC')
        ->where("kon.status_tender", 'Proses Lelang')
        ->get()->getRow();

        $data['sub_belum_lelang'] = $this->db_mysql->table("monika_kontrak_{$tahun} kon")
        ->select("
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_syc,

            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_myc,

            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as sum_syc, 
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as sum_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_syc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as count_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as count_myc
        ")
        ->join("monika_data_{$tahun} dat","kon.kdpaket = dat.kdpaket",'left')
        ->orderBy("kon.idpull", 'ASC')
        ->where("kon.status_tender", 'Belum Lelang')
        ->get()->getRow();

        $data['kegiatan_syc'] = $this->db_mysql->table("tgiat")
        ->select("tgiat.nmgiat as nmgiat, SUM(dat.pagu_total) AS total_pagu, SUM(dat.real_total) AS total_real")
        ->join("monika_data_{$tahun} dat","tgiat.kdgiat = dat.kdgiat",'left')
        ->join("monika_kontrak_{$tahun} kon","kon.kdpaket = dat.kdpaket",'left')
        ->where("tgiat.tahun_anggaran",$tahun)
        ->like("kon.nmpaket", "%SYC%")
        ->orderBy('tgiat.kdgiat', 'ASC')
        ->groupBy('tgiat.kdgiat')
        ->get()->getResult();

        $data['kegiatan_myc'] = $this->db_mysql->table("tgiat")
        ->select("tgiat.nmgiat as nmgiat, SUM(pagu_total) AS total_pagu, SUM(real_total) AS total_real")
        ->join("monika_data_{$tahun} dat","tgiat.kdgiat = dat.kdgiat",'left')
        ->join("monika_kontrak_{$tahun} kon","kon.kdpaket = dat.kdpaket",'left')
        ->where("tgiat.tahun_anggaran",$tahun)
        ->like("kon.nmpaket", "%SYC%")
        ->orderBy('tgiat.kdgiat', 'ASC')
        ->groupBy('tgiat.kdgiat')
        ->get()->getResult();

        return view('Modules\Admin\Views\Dashboard_mobile',$data);
    }

    public function laporan(){
        $data = array(
            'title' => 'Laporan',
            'report_list' => [
                "PROGRES ANGGARAN PER SUMBER DANA", /*sudah ada, menu dashboard, PROGRESS PER SUMBER DANA*/
                "PROGRES PUPR PER UNIT ORGANISASI", /*sudah ada, menu dashboard, PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR*/
                "PAKET KEGIATAN KONTRAKTUAL TA TAHUN", /*sudah ada, menu dashboard, POSTUR PAKET KONTRAKTUAL T.A. 2024 */
                "PAKET KEGIATAN BELUM LELANG TA TAHUN", /*sudah ada, menu dashboard, POSTUR PAKET BELUM LELANG TA 2024 */
                "PROGRES PAKET BELUM LELANG TA TAHUN", /*tidak ditemukan*/
                "PAKET BELUM LELANG TA TAHUN (RPM-SYC)", /*sudah ada, menu dashboard, DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN*/
                "PAKET BELUM LELANG TA TAHUN (RPM-MYC)", /*sudah ada, menu dashboard, DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN*/
                "PAKET BELUM LELANG TA TAHUN (PLN SYC)", /*sudah ada, menu dashboard, DAFTAR PAKET BELUM LELANG PHLN - MYC PROJECT LOAN*/
                "PAKET BELUM LELANG TA TAHUN (PLN-MYC)", /*sudah ada, menu dashboard, PAKET BELUM LELANG PHLN - MYC PROJECT LOAN*/
                "PAKET BELUM LELANG TA TAHUN (PLN-MYC MENDAHULUI DIPA)", /*tidak ditemukan*/
                "PAKET DENGAN SISA BELUM TERSERAP TERTINGGI", /*adanya terendah, re-query, menu dashboard, PROGRES 10 SATUAN KERJA TERENDAH TA 2024*/
                "PROGRES PELAKSANAAN IKN", /*harus tanya pupr*/
                "REKAPITULASI PAKET KEGIATAN BIDANG SDA DI KAWASAN IKN", /*harus tanya pupr*/
                "REKAPITULASI PAKET KEGIATAN BIDANG SDA DI KAWASAN IKN (selesai)", /*harus tanya pupr*/
                "PROGNOSIS PENYERAPAN ANGGARAN TA TAHUN (1)", /*tidak ada, adanya Dana Tidak Terserap, di postur anggaran*/
                "RENCANA PEMANFAATAN RUPIAH MURNI TA TAHUN (1)", /*tidak ditemukan*/
                "PROGNOSIS PENYERAPAN ANGGARAN TA TAHUN (2)", /*tidak ada, adanya Dana Tidak Terserap, di postur anggaran*/
                "PROYEK BENDUNGAN PERLU PERHATIAN KHUSUS (1)", /*harus tanya pupr*/
                "PROGNOSIS SESUAI IEMONITORING SEBELUM PEMANFAATAN ANGGARAN TA TAHUN PER UNIT KERJA", /*tidak ditemukan*/
                "PROYEK BENDUNGAN PERLU PERHATIAN KHUSUS (2)", /*harus tanya pupr*/
                "PROGNOSIS PENYERAPAN ANGGARAN TA TAHUN (3)", /*tidak ada, adanya Dana Tidak Terserap, di postur anggaran*/
                "RENCANA PEMANFAATAN RUPIAH MURNI TA TAHUN (2)", /*tidak ditemukan*/
            ]
        );

        return view('Modules\Admin\Views\Laporan\index',$data);
    }

    public function cetak_laporan(){
        $filterDateStart = $this->request->getGet('filter-date-start') ?? null;
        $filterDateEnd   = $this->request->getGet('filter-date-end') ?? null;
        $rekapUnor = $this->RekapUnorModel->getRekapUnor();
        $qterkontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "terkontrak"], $filterDateStart, $filterDateEnd);
        $qproseslelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Proses Lelang"], $filterDateStart, $filterDateEnd);
        $qbelumlelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Belum Lelang"], $filterDateStart, $filterDateEnd);
        $qpersiapankontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Persiapan kontrak"], $filterDateStart, $filterDateEnd);
        $getGraphicData = $this->PulldataModel->getGraphicDataProgressPerSumberDana();
        
        $data = array(
            'title' => 'Cetak Laporan',
            'report_list_selected' => $this->request->getVar('report_list'),
            'rekapunor' => $rekapUnor,
            'terkontrak' => $qterkontrak,
            'proseslelang' => $qproseslelang,
            'belumlelang' => $qbelumlelang,
            'persiapankontrak' => $qpersiapankontrak,
            'gagallelang' => $this->PohonAnggaran->getDataKontrak(["status_tender" => "Gagal Lelang"]),
            'nilai_rpm' => $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "RPM", $filterDateStart, $filterDateEnd),
            'nilai_sbsn' => $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "SBSN", $filterDateStart, $filterDateEnd),
            'nilai_phln' => $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "PHLN", $filterDateStart, $filterDateEnd),
            'rpmSyc' => $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "RPM", $filterDateStart, $filterDateEnd),
            'rpmMyc' => $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "RPM", $filterDateStart, $filterDateEnd),
            'rpmSycList' => $this->PohonAnggaran->getDataBelumLelangList([[0]], "RPM", $filterDateStart, $filterDateEnd),
            'rpmMycList' => $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "RPM", $filterDateStart, $filterDateEnd),
            'phlnMyc' => $this->PohonAnggaran->getDataBelumLelangNilai([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd),
            'phlnMycList' => $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "PHLN", $filterDateStart, $filterDateEnd),
            'tenderRpm' =>  $this->PohonAnggaran->getDataRencanaTenderBelumLelang("RPM", null, null, false, $filterDateStart, $filterDateEnd),
            'tenderPhln' =>  $this->PohonAnggaran->getDataRencanaTenderBelumLelang("PHLN", null, null, false, $filterDateStart, $filterDateEnd),
            'pagu' =>    $getGraphicData,
            'keuProgressSda'   => $this->RekapUnorModel->getProgresSda('progres_keu'),
            'fisikProgressSda' => $this->RekapUnorModel->getProgresSda('progres_fisik'),
        );

        $qdata = $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS' AND md.tahun =" . session('userData.tahun'));
        $data['total_deviasi'] = 0;
        foreach ($qdata as $qdata_val) {
            $data['total_deviasi'] += $qdata_val['jml_nilai_deviasi'];
        }

        $data['pagu_all'] = $this->db_mysql->table("monika_data_".date('Y'))
        ->selectSum('pagu_total', 'total_pagu')
        ->selectSum('pagu_rpm', 'total_rpm')
        ->selectSum('real_rpm', 'total_real_rpm')
        ->selectSum('pagu_sbsn', 'total_sbsn')
        ->selectSum('real_sbsn', 'total_real_sbsn')
        ->selectSum('pagu_phln', 'total_phln')
        ->selectSum('real_phln', 'total_real_phln')
        ->get()->getRow();

        $tahun = 2024;
        $data['kegiatan'] = $this->db_mysql->table("tgiat")
        ->select("tgiat.nmgiat as nmgiat, SUM(pagu_total) AS total_pagu, SUM(real_total) AS total_real, SUM(blokir) AS total_blokir")
        ->join("monika_data_{$tahun}","tgiat.kdgiat = monika_data_{$tahun}.kdgiat",'left')
        ->where("tgiat.tahun_anggaran",$tahun)
        ->orderBy('tgiat.kdgiat', 'ASC')
        ->groupBy('tgiat.kdgiat')
        ->get()->getResult();

        $data['sub_belum_lelang'] = $this->db_mysql->table("monika_kontrak_{$tahun} kon")
        ->select("
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_syc,

            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as sum_rpm_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as sum_phln_myc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as sum_sbsn_myc,

            SUM(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as sum_syc,
            SUM(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as sum_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_syc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'RPM' THEN dat.pagu_total ELSE 0 END) as count_rpm_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'PHLN' THEN dat.pagu_total ELSE 0 END) as count_phln_myc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' AND kon.sumber_dana = 'SBSN' THEN dat.pagu_total ELSE 0 END) as count_sbsn_myc,

            COUNT(CASE WHEN kon.nmpaket LIKE '%SYC%' THEN dat.pagu_total ELSE 0 END) as count_syc,
            COUNT(CASE WHEN kon.nmpaket LIKE '%MYC%' THEN dat.pagu_total ELSE 0 END) as count_myc
        ")
        ->join("monika_data_{$tahun} dat","kon.kdpaket = dat.kdpaket",'left')
        ->orderBy("kon.idpull", 'ASC')
        ->orderBy("kon.status_tender", 'Belum Lelang')
        ->get()->getRow();

        return view('Modules\Admin\Views\Laporan\cetak', $data);
        $pdf = new Dompdf();
        $pdf->set_option('isRemoteEnabled', TRUE);
        $pdf->loadHtml(view('Modules\Admin\Views\Laporan\cetak', $data), 'UTF-8');
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $pdf->stream('laporan_monika.pdf', array('Attachment' => false));
    }

    // private function rekapGroupData()
    // {
    //     return [
    //         [
    //             'title' => 'Food Estate',
    //             'tematikCode' => ["'TXX0003'"]
    //         ],
    //         [
    //             'title' => 'Kawasan Industri',
    //             'tematikCode' => ["'T060012'"]
    //         ],
    //         [
    //             'title' => 'KSPN',
    //             'tematikCode' => ["'kspn01'", "'kspn02'", "'kspn03'", "'kspn04'", "'kspn05'"]
    //         ]
    //     ];
    // }

    private function rekapGroupData()
    {
        return [
            [
                'title' => 'Food Estate',
                'tematikCode' => ["'T060019'"]
            ],
            [
                'title' => 'Kawasan Industri',
                'tematikCode' => ["'TMKEM0005'"]
            ],
            [
                'title' => 'KSPN',
                'tematikCode' => ["'kspn01'", "'kspn02'", "'kspn03'", "'kspn04'", "'kspn05'", "'kspn06'", "'kspn08'", "'kspn09'"]
            ]
        ];
    }

    private function getprogreskeu($p)
    {

        // grafik progres keu
        $q = $this->PulldataModel->getgrafik($p, "md.tahun=" . session('userData.tahun'));
        $Jbln = $this->PulldataModel->bln();
        $Arry['bln'][] = [0, ''];
        $Arry['rencana'][] = [0, 0];
        $Arry['realisasi'][] = [0, 0];
        foreach ($q as $k => $d) {
            for ($i = 1; $i <= count($Jbln); $i++) {
                $Arry['bln'][] = [$i, $Jbln[$i - 1]];
                $Arry['rencana'][] = [$i, number_format($d["rencana_$i"], 2, '.', '.')];

                //Pengecekan data untuk data realisasi
                if ($i <= date("m")) {

                    if ($i != date("m")) {

                        $Arry['realisasi'][] = [$i, number_format($d["realisasi_$i"], 2, '.', '.')];
                    } else {

                        //Menghitung jumlah hari dalam 1 bulan
                        $date = date("Y-$i-d");
                        $a = date("t", strtotime($date));
                        $day = $a - date("d");

                        //Membuat point baru ketika belum akhir bulan
                        if ($day != 0) {

                            // $prev_month = $i - 1;
                            $prev_month = ($i == 1 ? 1 : $i - 1);
                            //balik awal
                            //data realisasi 
                            //$Arry_realisasi = $d["realisasi_$prev_month"] + (date("d")/ $a * ($d["realisasi_$i"] - $d["realisasi_$prev_month"]));
                            //penyesuaian nilai realisasi
                            $data_realisasi = $d["realisasi_$i"];

                            $Arry['realisasi'][] = [($i - 1) + 0.5, number_format($data_realisasi, 2, '.', '.')];

                            //data rencana

                            $data_rencana = $d["rencana_$prev_month"] + (date("d") / $a * ($d["rencana_$i"] - $d["rencana_$prev_month"]));

                            array_splice($Arry['rencana'], $i, 0, [[($i - 1) + 0.5, number_format($data_rencana, 2, '.', '.')]]);
                        } else {

                            $Arry['realisasi'][] = [$i, number_format($d["realisasi_$i"], 2, '.', '.')];
                        }
                    }
                }
            }
        }

        return $Arry;
    }

    public function Excel()
    {


        ini_set('max_execution_time', 300);

        $rekapUnor =  $this->RekapUnorModel->getRekapUnor('', '');


        $inputFileType = 'Xlsx'; // Xlsx - Xml - Ods - Slk - Gnumeric - Csv
        $inputFileName =  WRITEPATH . "template" . DIRECTORY_SEPARATOR . "test1.xlsx";

        /**  Create a new Reader of the type defined in $inputFileType  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setIncludeCharts(true);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);
        $sheetData = $spreadsheet->getSheetByName("#s1");
        if (!empty($sheetData)) {
            $column = 8;
            foreach ($rekapUnor as $data) {
                $sheetData
                    ->setCellValue('B' . $column, $data['nmsingkat'])
                    ->setCellValue('C' . $column, $data['pagu_rpm'])
                    ->setCellValue('D' . $column, $data['pagu_sbsn'])
                    ->setCellValue('E' . $column, $data['pagu_phln'])
                    ->setCellValue('F' . $column, $data['pagu_total'])
                    ->setCellValue('G' . $column, $data['real_rpm'])
                    ->setCellValue('H' . $column, $data['real_sbsn'])
                    ->setCellValue('I' . $column, $data['real_phln'])
                    ->setCellValue('J' . $column, $data['real_total'])
                    ->setCellValue('K' . $column, $data['progres_keu'])
                    ->setCellValue('L' . $column, $data['progres_fisik']);

                $column++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename =  'PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR' . date('Y-m-d-His');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
            header('Cache-Control: max-age=0');
            $writer->setIncludeCharts(true);
            $writer->save('php://output');
        }
    }
}
