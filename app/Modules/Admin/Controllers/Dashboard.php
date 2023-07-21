<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\API\ResponseTrait;
use Modules\Admin\Models\TematikModel;
use Modules\Admin\Models\RekapUnorModel;
use Modules\Admin\Models\PohonAnggaranModel;
use Modules\Admin\Models\PulldataModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $qdata = $this->TematikModel->getListRekap($grupData, $filterDateStart, $filterDateEnd);

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
