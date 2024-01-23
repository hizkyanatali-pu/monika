<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class BigData extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session                       = session();
        $this->user                    = $session->get('userData');
        $this->db                      = \Config\Database::connect();
        $this->monikaData              = $this->db->table('monika_data_' . $this->user['tahun']);
        $this->satker                  = $this->db->table('m_satker');
        $this->program                 = $this->db->table('tprogram');
        $this->kegiatan                = $this->db->table('tgiat');
        $this->output                  = $this->db->table('toutput');
        $this->suboutput               = $this->db->table('tsoutput');
        $this->tempExportBigdataColumn = $this->db->table("temp_export_bigdata_column");
        $this->request                 = \Config\Services::request();
    }


    // ini yang sudah pakai tabulator
    function index()
    {

        return view('Modules\Admin\Views\Bigdata\bigdatapaket.php', [
            'data'          => [
                'satker'    => $this->satker->select('satkerid as id, satker as nama')->get()->getResult(),
                'program'   => $this->program->select('kdprogram as id, nmprogram as nama')->get()->getResult(),
                'kegiatan'  => $this->kegiatan->select('kdgiat as id, nmgiat as nama')->get()->getResult(),
                'output'    => $this->output->select('kdoutput as id, nmoutput as nama')->get()->getResult(),
                'suboutput' => $this->suboutput->select('kdro as id, nmro as nama')->get()->getResult(),
            ]
        ]);
    }

    function getDataMonikaData()
    {

        // $kolomYangDiminta = "satker";
        // $kolomArray = explode(',', $kolomYangDiminta);

        // Bangun array kolom seperti yang diminta
        // $daftarkolom = [];
        // foreach ($kolomArray as $field) {

        //     $daftarkolom[] = [
        //         'title' => $field,
        //         'field' => $field
        //     ];
        // }

        // Ambil parameter dari permintaan
        $page = $this->request->getVar('page') ?? 1;
        $perPage = $this->request->getVar('size') ?? 10;
        $year = $this->request->getVar('year');


        $defaultKolom = [
            "m_balai.balai as nmbalai",
            "m_satker.satker as nmsatker",
            "monika_data_$year.kdpaket",
            "monika_data_$year.nmpaket",
            "pagu_rpm",
            "pagu_sbsn",
            "pagu_phln",
            "pagu_total",
            "real_rpm",
            "real_sbsn",
            "real_phln",
            "real_total",
            "progres_keuangan",
            "progres_fisik"
        ];

        $column = $this->request->getVar('column') ?? $defaultKolom;

        $listKolom = implode(',', $column);
        $filter = $this->request->getVar('filter');

        $dataPaket = $this->getDataWithColumns($listKolom, $page, $perPage, $year, $filter);
        $resultArray = array();


        // foreach ($dataPaket['data'] as $data) {
        //     foreach ($data as $kolom => $nilai) {
        //         $resultArray[] = array(
        //             "title" => $kolom,
        //             "field" => $kolom
        //         );
        //     }
        //     // Hentikan loop setelah satu baris data
        //     break;
        // }

        return $this->respond(
            [
                "last_page" => ceil($dataPaket['total'] / $perPage),
                "totalData" => $dataPaket['total'],
                "data" =>  $dataPaket['data'],
                // "columns" => $resultArray,
                // "page" => $page,
            ]
        );
    }


    public function getDataWithColumns($kolom, $page, $perpage, $year, $_filterData = [])
    {


        $table  = "monika_data_" .  $year;
        $offset = ($page - 1) * $perpage;


        $q  =  $this->db->table($table)->select($kolom)
            ->join('m_satker', "$table.kdsatker = m_satker.satkerid", 'left')
            ->join('m_balai', "m_satker.balaiid = m_balai.balaiid", 'left')
            ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
            ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran=$year", 'left')
            ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran=$year)", 'left')
            ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran=$year)", 'left')

            ->join('tkabkota', "($table.kdkabkota=tkabkota.kdkabkota AND $table.kdlokasi=tkabkota.kdlokasi)", 'left')
            ->join('tlokasi', "$table.kdlokasi=tlokasi.kdlokasi", 'left');

        if ($year >= 2020) {
            $q->join("monika_kontrak_$year as kontrak", "$table.kdsatker = kontrak.kdsatker AND $table.kdprogram = kontrak.kdprogram AND 
                $table.kdgiat = kontrak.kdgiat AND $table.kdprogram = kontrak.kdprogram AND $table.kdoutput = kontrak.kdoutput AND $table.kdsoutput = kontrak.kdsoutput
                AND $table.kdkmpnen = kontrak.kdkmpnen AND  $table.kdskmpnen = kontrak.kdskmpnen", 'left');
        }


        $totalData = $this->db->table($table);


        if (is_array($_filterData)) {
            foreach ($_filterData as $key => $value) {
                if ($key != 'opsiData' && $key != 'pagutotalStart' && $key != 'pagutotalEnd') {
                    $q->where($table . '.' . $key, $value);
                    $totalData->where($table . '.' . $key, $value);
                }
            }

            if (array_key_exists('opsiData', $_filterData)) {
                switch ($_filterData['opsiData']) {
                    case '1':
                        $q->where("$table.blokir", '0');
                        $totalData->where("$table.blokir", '0');
                        break;

                    case '2':
                        $q->where("$table.blokir >", '0');
                        $totalData->where("$table.blokir >", '0');
                        break;
                }
            }
        }


        $data = $q->limit($perpage, $offset)->get()->getResultArray();

        // print_r($this->db->getLastQuery());
        // exit;

        $totalData = $totalData->countAllResults();

        // Menyiapkan alias untuk response agar sesuai dengan function tableColumn()
        $aliasQ = [];
        foreach ($data as $row) {
            $aliasRow = [];
            foreach ($row as $key => $value) {
                // Mencari label yang sesuai dengan key pada fungsi tableColumn()
                $label = array_column($this->tableColumn($year), 'label', 'value')[$key] ?? $key;
                $aliasRow[$label] = $value;
            }
            $aliasQ[] = $aliasRow;
        }


        return ["data" => $aliasQ, "total" => $totalData];
    }

    function getColom()
    {
        $year = $this->request->getVar('year') ?? $this->user['tahun'];

        // $table = 'monika_data_' . $year;

        // Gunakan metode select() untuk mengambil kolom-kolom dari tabel
        // $kolomValid = $this->db->table($table)->select("*")->limit(1)->get()->getResult();

        $kolom = $this->tableColumn($year);

        // Jika data berhasil diambil, ambil nama kolom dari array pertama
        // if (!empty($kolomValid)) {
        // $namaKolom = array_keys((array)$kolomValid[0]);

        // Tambahkan nama tabel di depan setiap nama kolom
        // $namaKolomDenganTabel = array_map(function ($kolom) use ($table) {
        //     return $table . '.' . $kolom;
        // }, $namaKolom);

        $responseArray = [
            "kolom" => $kolom,
            'defaultColumn' => [
                'nmbalai',
                'nmsatker',
                'kdpaket',
                'nmpaket',
                'pagu_rpm',
                'pagu_sbsn',
                'pagu_phln',
                'pagu_total',
                'real_rpm',
                'real_sbsn',
                'real_phln',
                'real_total',
                'progres_keuangan',
                'progres_fisik'
            ],
        ];

        // Mengubah array menjadi format JSON
        $jsonResult = json_encode($responseArray, JSON_PRETTY_PRINT);

        // Mengembalikan hasil JSON
        return $this->response->setJSON($jsonResult);
        // } else {
        //     // Handle jika data tidak ditemukan
        //     return $this->response->setStatusCode(404)->setJSON(['message' => 'Data tidak ditemukan']);
        // }
    }

    // public function getValidColumns()
    // {
    //     $table = 'monika_data_2023';
    //     $query = $this->db->table($table)->select("$table.*,tprogram.nmprogram,tgiat.nmgiat,toutput.nmoutput,tsoutput.nmro")
    //         ->join('m_satker', "$table.kdsatker = m_satker.satkerid", 'left')
    //         ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
    //         ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran='2023'", 'left')
    //         ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran='2023')", 'left')
    //         ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran='2023')", 'left')
    //         ->limit(1)->get();
    //     $fields = $query->getFieldNames();


    //     $kolomValid = [];
    //     foreach ($fields as $field) {
    //         $kolomValid[] = $field;
    //     }

    //     return $kolomValid;
    // }





    //ubah nama menjadi index jika ingin menerapkan yang lama
    public function index_lama()
    {
        $tahun = $this->user['tahun'];
        $column = $this->tableColumn($tahun);

        $table  = 'monika_data_' . $tahun;
        $DBtable = $this->db->table($table);
        $data   = $DBtable->select("
            $table.*, 
            m_satker.satker as nmsatker,
            tprogram.nmprogram,
            tgiat.nmgiat,
            toutput.nmoutput,
            tsoutput.nmro
        ")

            ->join('m_satker', "$table.kdsatker = m_satker.satkerid", 'left')
            ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
            ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran='$tahun'", 'left')
            ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran='$tahun')", 'left')
            ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran='$tahun')", 'left')
            ->groupBy("$table.kdpaket")
            ->limit(1)
            ->get()
            ->getResultArray();

        return view('Modules\Admin\Views\Bigdata\index.php', [
            'column'        => $column,
            'defaultColumn' => [
                'nmbalai',
                'nmsatker',
                'kdpaket',
                'nmpaket',
                'pagu_rpm',
                'pagu_sbsn',
                'pagu_phln',
                'pagu_total',
                'real_rpm',
                'real_sbsn',
                'real_phln',
                'real_total',
                'progres_keuangan',
                'progres_fisik'
            ],
            'tableWidth'    => array_sum(array_column($column, 'widthColumn')),
            'mainData'      => $data,
            'data'          => [
                'satker'    => $this->satker->select('satkerid as id, satker as nama')->get()->getResult(),
                'program'   => $this->program->select('kdprogram as id, nmprogram as nama')->get()->getResult(),
                'kegiatan'  => $this->kegiatan->select('kdgiat as id, nmgiat as nama')->get()->getResult(),
                'output'    => $this->output->select('kdoutput as id, nmoutput as nama')->get()->getResult(),
                'suboutput' => $this->suboutput->select('kdro as id, nmro as nama')->get()->getResult(),
            ]
        ]);
    }



    public function loadData()
    {
        $limitData  = 1000;
        $offsetData = $this->request->getGet('page') * $limitData;
        $filterData = $this->request->getGet('filter');

        $result = [
            'input'  => $this->request->getGet(),
            'column' => $this->tableColumn(2023),
            'data'   => $this->getData($filterData, $limitData, $offsetData)
        ];

        if ($offsetData == 0) $result['totalData'] = $this->getData($filterData, null, null, true);

        return $this->respond($result, 200);
    }



    public function filterSelectLookup()
    {
        $input  = $this->request->getGet();
        $result = [];

        switch ($input['childTarget']) {
            case 'kegiatan':
                $result = $this->kegiatan->select('kdgiat as id, nmgiat as nama')->where('kdprogram', $input['parentValue'])->get()->getResult();
                break;

            case 'output':
                $result = $this->output->select('kdoutput as id, nmoutput as nama')->where('kdgiat', $input['parentValue'])->get()->getResult();
                break;

            case 'suboutput':
                $result = $this->suboutput->select('kdro as id, nmro as nama')->where('kdgiat', $input['kdgiat'])->where('kdkro', $input['parentValue'])->get()->getResult();
                break;
        }

        return $this->respond($result, 200);
    }



    public function downloadExcelBigDataNew()
    {


        $year = $this->request->getPost('year');

        // print_r($this->request->getVar('columns'));
        // exit;

        $defaultKolom = [
            "m_balai.balai as nmbalai",
            "m_satker.satker as nmsatker",
            "monika_data_$year.kdpaket",
            "monika_data_$year.nmpaket",
            "pagu_rpm",
            "pagu_sbsn",
            "pagu_phln",
            "pagu_total",
            "real_rpm",
            "real_sbsn",
            "real_phln",
            "real_total",
            "progres_keuangan",
            "progres_fisik"
        ];

        // $listKolom = $this->request->getVar('columns') ?? $defaultKolom;
        $listKolom = $this->request->getVar('columns') ? explode(",", $this->request->getVar('columns')) : $defaultKolom;

        $kolom = implode(',', $listKolom);

        $_filterData = $this->request->getVar('filter');

        $table  = "monika_data_" .  $year;

        $q  =  $this->db->table($table)->select($kolom)
            ->join('m_satker', "$table.kdsatker = m_satker.satkerid", 'left')
            ->join('m_balai', "m_satker.balaiid = m_balai.balaiid", 'left')
            ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
            ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran=$year", 'left')
            ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran=$year)", 'left')
            ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran=$year)", 'left')
            ->join('tkabkota', "($table.kdkabkota=tkabkota.kdkabkota AND $table.kdlokasi=tkabkota.kdlokasi)", 'left')
            ->join('tlokasi', "$table.kdlokasi=tlokasi.kdlokasi", 'left');

        if ($year >= 2020) {
            $q->join("monika_kontrak_$year as kontrak", "$table.kdsatker = kontrak.kdsatker AND $table.kdprogram = kontrak.kdprogram AND 
            $table.kdgiat = kontrak.kdgiat AND $table.kdprogram = kontrak.kdprogram AND $table.kdoutput = kontrak.kdoutput AND $table.kdsoutput = kontrak.kdsoutput
            AND $table.kdkmpnen = kontrak.kdkmpnen AND  $table.kdskmpnen = kontrak.kdskmpnen", 'left');
        }


        $totalData = $this->db->table($table);


        if (is_array($_filterData)) {
            foreach ($_filterData as $key => $value) {
                if ($key != 'opsiData' && $key != 'pagutotalStart' && $key != 'pagutotalEnd') {
                    $q->where($table . '.' . $key, $value);
                    $totalData->where($table . '.' . $key, $value);
                }
            }

            if (array_key_exists('opsiData', $_filterData)) {
                switch ($_filterData['opsiData']) {
                    case '1':
                        $q->where("$table.blokir", '0');
                        $totalData->where("$table.blokir", '0');
                        break;

                    case '2':
                        $q->where("$table.blokir >", '0');
                        $totalData->where("$table.blokir >", '0');
                        break;
                }
            }
        }


        $data = $q->groupBy($table . ".kdpaket")->get()->getResultArray();
        $kolom_nama = array_keys($data[0]);


        // Membuat objek PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menulis header kolom
        $headerRow = 1;
        foreach ($kolom_nama as $index => $kolom) {

            $sheet->setCellValueByColumnAndRow($index + 1, $headerRow, $kolom);
        }

        $dataRow = $headerRow + 1;

        foreach ($data as $rowData) {

            if (!empty($rowData)) { // Periksa apakah array tidak kosong
                foreach ($kolom_nama as $index => $kolom) {
                    $sheet->setCellValueByColumnAndRow($index + 1, $dataRow, $rowData[$kolom] ?? ''); // Menggunakan null coalescing untuk mengatasi Undefined offset
                }
                $dataRow++;
            }
        }


        // Membuat nama file Excel
        $filename = $this->user['tahun'] . '-MONIKA-IMON' . "-" . date('Y-m-d-His') . ".xlsx";


        // Simpan ke file Excel
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save(ROOTPATH . 'public/' . $filename);

        // Output ke browser sebagai file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }


    public function downloadExcelBigData()
    {
        // $limitData    = 1000;
        // $offsetData   = ($this->request->getGet('fileNumber') - 1) * $limitData;
        $limitData    = null;
        $offsetData   = $this->request->getGet('fileNumber') - 1;
        $filterData   = $this->request->getGet('filter');
        $masterColumn = $this->tableColumn(2023);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No.');

        $cellHeaderEndIndex = 0;
        $columnHeader = $this->tempExportBigdataColumn->getWhere(['session' => $this->user['nama']])->getResultArray();
        foreach ($columnHeader as $keyColHeader => $dataColHeader) {
            $cellHeaderEndIndex = $keyColHeader + 2;
            $sheet->setCellValueByColumnAndRow($cellHeaderEndIndex, 1, $dataColHeader['label']);

            $cellStringHeader = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cellHeaderEndIndex);;
            $sheet->getColumnDimension($cellStringHeader)->setAutoSize(true);
        }
        $cellHeaderEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cellHeaderEndIndex);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getRowDimension('1')->setRowHeight(30);
        $styleArray = [
            'font'  => [
                'bold'  => true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 8,
                'name'  => 'Arial'
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];

        $sheet->getStyle('A1:' . $cellHeaderEnd . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $sheet->getStyle('A1:' . $cellHeaderEnd . '1')->applyFromArray($styleArray);


        $mainData = $this->getData($filterData, $limitData, $offsetData);
        foreach ($mainData as $key => $data) {
            $row = $key + 2;
            $sheet->setCellValue('A' . $row, ($key + 1) + $offsetData);

            foreach ($columnHeader as $kelCell => $dataCell) {
                $cellIndex = $kelCell + 2;
                $cellText = $data[$dataCell['name']];

                $masterColumnKey  = array_search($dataCell['name'], array_column($masterColumn, 'value'));
                $masterColumnCell = $masterColumn[$masterColumnKey];
                $masterColumn_isNumberFormat = array_key_exists('isNumberFormat', $masterColumnCell) ? $masterColumnCell['isNumberFormat'] : false;

                if ($masterColumn_isNumberFormat) $cellText = $cellText ? rupiahFormat($cellText, false) : 0;

                $sheet->setCellValueByColumnAndRow($cellIndex, $row, $cellText);
            }
        }

        /*
        $mainData = $this->tGiat->getWhere(['tahun_anggaran' => $this->user['tahun']])->getResult();
        foreach ($mainData as $key => $data) :
            $row = $key+3;
            $sheet->setCellValue('A'.$row, $data->kdgiat);
            $sheet->setCellValue('B'.$row, $data->nmgiat);
            $sheet->setCellValue('C'.$row, $data->kddept);
            $sheet->setCellValue('D'.$row, $data->kdunit);
            $sheet->setCellValue('E'.$row, $data->kdprogram);
            $sheet->setCellValue('F'.$row, $data->kdfungsi);
            $sheet->setCellValue('G'.$row, $data->kdsfung);
            $sheet->setCellValue('H'.$row, $data->kdes2);
            $sheet->setCellValue('I'.$row, $data->kdprogout);
        endforeach;
        */

        ob_start();
        $writer = new Xlsx($spreadsheet);
        // $filename = $this->user['tahun'].'-Data Kegiatan-'.date('Y-m-d-His');

        // $filename = 'monika-bigdata-part-' . $this->request->getGet('fileNumber');
        $filename = 'MONIKA-IMON-' . $this->user['tahun'] . "-" . date('Y-m-d-His');

        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        // header('Cache-Control: max-age=0');

        $writer->save('php://output');
        $xlsData = ob_get_contents();

        ob_end_clean();
        $response =  array(
            'status' => TRUE,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));
    }



    public function prepareToDownload()
    {
        $filterData = $this->request->getGet('filter');

        return $this->respond([
            'totalFile' => 1 //ceil($this->getData($filterData, null, null, true)['total'] / 1000)
        ], 200);
    }



    public function setTempColumn()
    {
        $filterColumn      = json_decode($this->request->getPost('columnString'));
        $session_userNama = $this->user['nama'];

        /* remove existing temp */
        $this->tempExportBigdataColumn->where('session', $session_userNama);
        $this->tempExportBigdataColumn->delete();
        /** end-of: remove existing temp */

        /* insert temp */
        $map_filterColumn = array_map(function ($arr) use ($session_userNama) {
            return [
                'session' => $session_userNama,
                'name'    => $arr->value,
                'label'   => $arr->label
            ];
        }, $filterColumn);

        $this->tempExportBigdataColumn->insertBatch($map_filterColumn);
        /** end-of: insert temp */

        return $this->respond([
            'status'  => true,
            'session' => $this->user['nama']
        ]);
    }



    private function getData($_filterData = [], $_limitData = null, $_offsetData = null, $_getTotal = false)
    {
        $tahun = $_filterData['tahun'] ?? $this->user['tahun'];
        $table = 'monika_data_' . $tahun;

        $satker = ($tahun >= 2020) ? "m_satker.satker as nmsatker" : "$table.nmsatker as nmsatker";
        $giat = ($tahun >= 2020) ? "tgiat.nmgiat" : "$table.nmgiat as nmgiat";
        $kontrak = ($tahun >= 2020) ? "kontrak.rkn_nama,rkn_npwp,nilai_kontrak,tanggal_kontrak,tgl_spmk,waktu,status_tender,tgl_rencana_lelang,jadwal_pengumuman,jadwal_pemenang,
        jadwal_kontrak,jadwal_tgl_kontrak,status_sipbj,kdrup,sumber_dana" : "";


        $DBtable = $this->db->table($table);
        $select = "
            $table.*, 
            $satker,
            $kontrak,
            m_balai.balaiid as balai_id,
            m_balai.balai as nmbalai,
            tprogram.nmprogram,
            $giat,
            toutput.nmoutput,
            tsoutput.nmro,
            tkabkota.nmkabkota,
            tlokasi.nmlokasi,
                
        CASE 
            WHEN nmpaket LIKE '%SYC%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(nmpaket, ';', -1), ';', 1)
                WHEN nmpaket LIKE '%MYC%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(nmpaket, ';', -1), ';', 1)
            ELSE NULL
        END AS jenis_kontrak
        ";

        if ($_getTotal) $select = "count($table.kdpaket) as total";

        if ($tahun >= 2020) {
            $data = $DBtable->select($select)
                ->join("monika_kontrak_$tahun as kontrak", "$table.kdsatker = kontrak.kdsatker AND $table.kdprogram = kontrak.kdprogram AND 
        $table.kdgiat = kontrak.kdgiat AND $table.kdprogram = kontrak.kdprogram AND $table.kdoutput = kontrak.kdoutput AND $table.kdsoutput = kontrak.kdsoutput
        AND $table.kdkmpnen = kontrak.kdkmpnen AND  $table.kdskmpnen = kontrak.kdskmpnen", 'left')
                ->join('m_satker', "$table.kdsatker = m_satker.satkerid", 'left')
                ->join('m_balai', "m_satker.balaiid = m_balai.balaiid", 'left')
                ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
                ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran='$tahun'", 'left')
                ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran='$tahun')", 'left')
                ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran='$tahun')", 'left')
                ->join('tkabkota', "($table.kdkabkota=tkabkota.kdkabkota AND $table.kdlokasi=tkabkota.kdlokasi)", 'left')
                ->join('tlokasi', "$table.kdlokasi=tlokasi.kdlokasi", 'left');
        } else {
            $data = $DBtable->select($select)
                ->join('m_satker', "$table.kdsatker = m_satker.satkerid", 'left')
                ->join('m_balai', "m_satker.balaiid = m_balai.balaiid", 'left')
                ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
                ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran='$tahun'", 'left')
                ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran='$tahun')", 'left')
                ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran='$tahun')", 'left')
                ->join('tkabkota', "($table.kdkabkota=tkabkota.kdkabkota AND $table.kdlokasi=tkabkota.kdlokasi)", 'left')
                ->join('tlokasi', "$table.kdlokasi=tlokasi.kdlokasi", 'left');
        }

        if (array_key_exists('opsiData', $_filterData)) {
            switch ($_filterData['opsiData']) {
                case '1':
                    $data->where('blokir', '0');
                    break;

                case '2':
                    $data->where('blokir >', '0');
                    break;
            }
        }

        if (is_array($_filterData)) {
            foreach ($_filterData as $key => $value) {
                if ($key != 'opsiData' && $key != 'pagutotalStart' && $key != 'pagutotalEnd') {
                    $data->where($table . '.' . $key, $value);
                }
            }
        }

        if (isset($_filterData['pagutotalStart'])) {
            $data->where('pagu_total >=', $_filterData['pagutotalStart']);
        }

        if (isset($_filterData['pagutotalEnd'])) {
            $data->where('pagu_total <=', $_filterData['pagutotalEnd']);
        }

        // print_r($data->get()->getResultArray()); exit;

        if (!empty($_limitData)) $data->limit($_limitData, $_offsetData);

        if ($_getTotal) return $data->get()->getRowArray();

        return $data->get()->getResultArray();
    }



    private function tableColumn($year)
    {
        $kolomA = [
            [
                'value'       => 'no',
                'label'       => 'No. ',
                'widthColumn' => 80,
                'align'       => 'center'
            ],
            [
                'value'       => 'balaiid',
                'label'       => 'kode balai',
                'widthColumn' => 80
            ],
            [
                'value'       => 'nmbalai',
                'label'       => 'nama balai',
                'widthColumn' => 220
            ],
            [
                'value'       => 'kdsatker',
                'label'       => 'kode satker',
                'widthColumn' => 80
            ],
            [
                'value'       => 'nmsatker',
                'label'       => 'nama satker',
                'widthColumn' => 220
            ],
            [
                'value'       => 'kdprogram',
                'label'       => 'kode program',
                'widthColumn' => 80
            ],
            [
                'value'       => 'nmprogram',
                'label'       => 'nama program',
                'widthColumn' => 220
            ],
            [
                'value'       => 'kdgiat',
                'label'       => 'kode kegiatan',
                'widthColumn' => 80
            ],
            [
                'value'       => 'nmgiat',
                'label'       => 'nama kegiatan',
                'widthColumn' => 220
            ],
            [
                'value'       => 'kdoutput',
                'label'       => 'kode output',
                'widthColumn' => 80
            ],

            [
                'value'       => 'nmoutput',
                'label'       => 'nama output',
                'widthColumn' => 220
            ],
            [
                'value'       => 'kdsoutput',
                'label'       => 'kode sub output',
                'widthColumn' => 90
            ],
            [
                'value'       => 'nmro',
                'label'       => 'nama sub output',
                'widthColumn' => 220
            ],
            [
                'value'       => 'kdkmpnen',
                'label'       => 'kode komponen',
                'widthColumn' => 90
            ],
            [
                'value'       => 'kdpaket',
                'label'       => 'kode paket',
                'widthColumn' => 250
            ],
            [
                'value'       => 'kdls',
                'label'       => 'kdls',
                'widthColumn' => 100
            ],
            [
                'value'       => 'nmpaket',
                'label'       => 'nama paket',
                'widthColumn' => 450
            ],
            [
                'value'          => 'pagu_51',
                'label'          => 'pagu 51',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'pagu_52',
                'label'          => 'pagu 52',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'pagu_53',
                'label'          => 'pagu 53',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'pagu_rpm',
                'label'          => 'pagu rpm',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'pagu_sbsn',
                'label'          => 'pagu sbsn',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'pagu_phln',
                'label'          => 'pagu phln',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'pagu_total',
                'label'          => 'pagu total',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'real_51',
                'label'          => 'realisasi 51',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'real_52',
                'label'          => 'realisasi 52',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'real_53',
                'label'          => 'realisasi 53',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'real_rpm',
                'label'          => 'realisasi rpm',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'real_sbsn',
                'label'          => 'realisasi sbsn',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'real_phln',
                'label'          => 'realisasi phln',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'real_total',
                'label'          => 'realisasi total',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'       => 'progres_keuangan',
                'label'       => 'progres keuangan',
                'widthColumn' => 150,
                'align'          => 'right'

            ],
            [
                'value'       => 'progres_fisik',
                'label'       => 'progres fisik',
                'widthColumn' => 150,
                'align'          => 'right',

            ],
            [
                'value'       => 'progres_keu_jan',
                'label'       => 'progres keuangan jan',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_feb',
                'label'       => 'progres keuangan feb',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_mar',
                'label'       => 'progres keuangan mar',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_apr',
                'label'       => 'progres keuangan apr',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_mei',
                'label'       => 'progres keuangan mei',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_jun',
                'label'       => 'progres keuangan jun',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_jul',
                'label'       => 'progres keuangan jul',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_agu',
                'label'       => 'progres keuangan agu',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_sep',
                'label'       => 'progres keuangan sep',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_okt',
                'label'       => 'progres keuangan okt',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_nov',
                'label'       => 'progres keuangan nov',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_keu_des',
                'label'       => 'progres keuangan des',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_jan',
                'label'       => 'progres fisik jan',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_feb',
                'label'       => 'progres fisik feb',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_mar',
                'label'       => 'progres fisik mar',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_apr',
                'label'       => 'progres fisik apr',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_mei',
                'label'       => 'progres fisik mei',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_jun',
                'label'       => 'progres fisik jun',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_jul',
                'label'       => 'progres fisik jul',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_agu',
                'label'       => 'progres fisik agu',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_sep',
                'label'       => 'progres fisik sep',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_okt',
                'label'       => 'progres fisik okt',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_nov',
                'label'       => 'progres fisik nov',
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik_des',
                'label'       => 'progres fisik des',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_jan',
                'label'       => 'rencana keuangan jan',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_feb',
                'label'       => 'rencana keuangan feb',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_mar',
                'label'       => 'rencana keuangan mar',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_apr',
                'label'       => 'rencana keuangan apr',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_mei',
                'label'       => 'rencana keuangan mei',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_jun',
                'label'       => 'rencana keuangan jun',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_jul',
                'label'       => 'rencana keuangan jul',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_agu',
                'label'       => 'rencana keuangan agu',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_sep',
                'label'       => 'rencana keuangan sep',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_okt',
                'label'       => 'rencana keuangan okt',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_nov',
                'label'       => 'rencana keuangan nov',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_keu_des',
                'label'       => 'rencana keuangan des',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_jan',
                'label'       => 'rencana fisik jan',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_feb',
                'label'       => 'rencana fisik feb',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_mar',
                'label'       => 'rencana fisik mar',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_apr',
                'label'       => 'rencana fisik apr',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_mei',
                'label'       => 'rencana fisik mei',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_jun',
                'label'       => 'rencana fisik jun',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_jul',
                'label'       => 'rencana fisik jul',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_agu',
                'label'       => 'rencana fisik agu',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_sep',
                'label'       => 'rencana fisik sep',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_okt',
                'label'       => 'rencana fisik okt',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_nov',
                'label'       => 'rencana fisik nov',
                'widthColumn' => 150
            ],
            [
                'value'       => 'ren_fis_des',
                'label'       => 'rencana fisik des',
                'widthColumn' => 150
            ],
            [
                'value'       => 'kdkabkota',
                'label'       => 'kode kabupaten/kota',
                'widthColumn' => 150
            ],
            [
                'value'       => 'nmkabkota',
                'label'       => 'nama kabupaten/kota',
                'widthColumn' => 150
            ],
            [
                'value'       => 'kdlokasi',
                'label'       => 'kode lokasi',
                'widthColumn' => 150
            ],
            [
                'value'       => 'nmlokasi',
                'label'       => 'nama lokasi',
                'widthColumn' => 150
            ],
            [
                'value'       => 'kdppk',
                'label'       => 'kode ppk',
                'widthColumn' => 150
            ],
            [
                'value'       => 'kdskmpnen',
                'label'       => 'kode sub kompnen',
                'widthColumn' => 150
            ],
            [
                'value'       => 'sat',
                'label'       => 'satuan',
                'widthColumn' => 150
            ],
            [
                'value'       => 'vol',
                'label'       => 'volume',
                'widthColumn' => 150
            ],
            [
                'value'       => 'pengadaan',
                'label'       => 'pengadaan',
                'widthColumn' => 150
            ],
            [
                'value'          => 'ufis',
                'label'          => 'uang fisik',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => 'pfis',
                'label'          => 'progres fisik',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'       => 'prognosis',
                'label'       => 'prognosis',
                'widthColumn' => 150
            ],
            [
                'value'          => 'blokir',
                'label'          => 'blokir',
                'widthColumn'    => 150,
                'align'          => 'right',
                'isNumberFormat' => true
            ],
            [
                'value'          => "jenis_kontrak",
                'label'          => 'jenis kontrak (MYC/SYC)',
                'widthColumn'    => 150,
                // 'align'          => 'right',
                // 'isNumberFormat' => true
            ],
        ];

        $kolomKontrak = [];

        if ($year >= 2020) {



            $kolomKontrak = [
                [
                    'value'          => 'rkn_nama',
                    'label'          => 'nama rekanan',
                    'widthColumn'    => 150,
                ],

                [
                    'value'          => 'rkn_npwp',
                    'label'          => 'NPWP Rekanan',
                    'widthColumn'    => 150,

                ],

                [
                    'value'          => 'nilai_kontrak',
                    'label'          => 'nilai kontrak',
                    'widthColumn'    => 150,
                    'align'          => 'right',
                    'isNumberFormat' => true

                ],
                [
                    'value'          => 'tanggal_kontrak',
                    'label'          => 'tanggal kontrak',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'tgl_spmk',
                    'label'          => 'tanggal spmk',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'waktu',
                    'label'          => 'waktu',
                    'widthColumn'    => 150,

                ],

                [
                    'value'          => 'status_tender',
                    'label'          => 'status tender',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'tgl_rencana_lelang',
                    'label'          => 'tgl rencana lelang',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'jadwal_pengumuman',
                    'label'          => 'jadwal pengumuman',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'jadwal_pemenang',
                    'label'          => 'jadwal pemenang',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'jadwal_kontrak',
                    'label'          => 'jadwal kontrak',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'jadwal_tgl_kontrak',
                    'label'          => 'jadwal tgl kontrak',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'status_sipbj',
                    'label'          => 'status sipbj',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'kdrup',
                    'label'          => 'kdrup',
                    'widthColumn'    => 150,

                ],
                [
                    'value'          => 'sumber_dana',
                    'label'          => 'sumber dana',
                    'widthColumn'    => 150,

                ],

            ];
        }

        $gabunganArray = array_merge($kolomA, $kolomKontrak);

        return   $gabunganArray;
    }
}


// kontrak.rkn_nama,rkn_npwp,nilai_kontrak,tanggal_kontrak,tgl_spmk,waktu,status_tender,tgl_rencana_lelang,jadwal_pengumuman,jadwal_pemenang,jadwal_kontrak,jadwal_tgl_kontrak,status_sipbj,kdrup,sumber_dana