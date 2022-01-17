<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\TematikModel;
use Modules\Admin\Models\RekapUnorModel;
use Modules\Admin\Models\PohonAnggaranModel;
use Modules\Admin\Models\PulldataModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $dbcustom = switch_db($this->user['dbuse']);
        $this->db = \Config\Database::connect($dbcustom);

        $this->TematikModel = new TematikModel();
        $this->RekapUnorModel = new RekapUnorModel();
        $this->PohonAnggaran        = new PohonAnggaranModel();
        $this->PulldataModel        = new PulldataModel();
    }

    public function index()
    {
        $grupData = $this->rekapGroupData();
        $qdata = $this->TematikModel->getListRekap($grupData);

        //pohon terkontrak
        $qterkontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "terkontrak"]);
        $qproseslelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Proses Lelang"]);
        $qbelumlelang = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Belum Lelang"]);
        $qpersiapankontrak = $this->PohonAnggaran->getDataKontrak(["status_tender" => "Persiapan kontrak"]);


        $rekapUnor =  $this->RekapUnorModel->getRekapUnor();
        $getGraphicData = $this->PulldataModel->getGraphicDataProgressPerSumberDana();
        $getGraphicDataJenisBelanja = $this->PulldataModel->getGraphicDataProgressPerJenisBelanja();
        $getGraphicDataPerkegiatan = $this->PulldataModel->getGraphicDataProgressPerKegiatan();


        // postur belum lelang
        $syc = $this->PohonAnggaran->getDataBelumLelangNilai([[0]], "pagu_rpm");
        $mycbaru1rpm = $this->PohonAnggaran->getDataBelumLelangNilai([[1]], "pagu_rpm");
        $mycbaru2rpm = $this->PohonAnggaran->getDataBelumLelangNilai([[3]], "pagu_rpm");

        $mycbaru1phln = $this->PohonAnggaran->getDataBelumLelangNilai([[1]], "pagu_phln");
        $mycbaru2phln = $this->PohonAnggaran->getDataBelumLelangNilai([[3]], "pagu_phln");

        $myclanjutan = $this->PohonAnggaran->getDataBelumLelangNilai([[2]], "pagu_rpm");
        $Sbsn =  $this->PohonAnggaran->getDataBelumLelangNilai([[0, 1, 2, 3]], "pagu_sbsn");

        //table perkegiatan

        $belumlelangPerkegiatanRpmSyc =  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_rpm", 0, true);
        $belumlelangPerkegiatanMyc =  $this->PohonAnggaran->getDataBelumLelangPerKegiatan("pagu_total", "1,2,3");


        $syclist =  $this->PohonAnggaran->getDataBelumLelangList([[0]], "pagu_rpm");
        $mycbarulist =  $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "pagu_rpm");
        $mycbaruphlnlist =  $this->PohonAnggaran->getDataBelumLelangList([[1, 3]], "pagu_phln");



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

            // belum lelang
            'syc' => $syc,
            'mycbaru1' => $mycbaru1rpm,
            'mycbaru2' => $mycbaru2rpm,
            'mycbaruphln' => $mycbaru1phln + $mycbaru2phln,
            'sbsn' => $Sbsn,
            'myclanjutan' => $myclanjutan,
            'syclist' => $syclist,
            'mycbarulist' => $mycbarulist,
            'mycbaruphlnlist' => $mycbaruphlnlist,

            // belum lelang RPM syc
            'belum_lelang_rpm_syc' => $belumlelangPerkegiatanRpmSyc,
            'belum_lelang_myc' => $belumlelangPerkegiatanMyc,
            'belum_lelang_phln_project_loan' =>  $this->PohonAnggaran->getDataBelumLelangPhlnMycProjectLoan("pagu_phln", "1,2,3"),


            'qdata' => ["bbws" => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BBWS'"), "bws" => $this->PulldataModel->getBalaiPaket('balai', "b.st like 'BWS'"), "pusat" => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='99'"), 'Balai Teknik' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='97'"), 'dinas' => $this->PulldataModel->getBalaiPaket('satker', "b.balaiid='98'"), 'Semua Satker' => $this->PulldataModel->getBalaiPaket("satker10terendah")],



        );
        return view('Modules\Admin\Views\Dashboard', $data);
    }

    private function rekapGroupData()
    {
        return [
            [
                'title' => 'Food Estate',
                'tematikCode' => ["'TXX0003'"]
            ],
            [
                'title' => 'Kawasan Industri',
                'tematikCode' => ["'T060012'"]
            ],
            [
                'title' => 'KSPN',
                'tematikCode' => ["'kspn01'", "'kspn02'", "'kspn03'", "'kspn04'", "'kspn05'"]
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

        $rekapUnor =  $this->RekapUnorModel->getRekapUnor('', 'urutan ASC');


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
