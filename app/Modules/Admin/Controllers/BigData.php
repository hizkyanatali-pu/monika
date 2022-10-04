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
        $this->monikaData              = $this->db->table('monika_data_'.$this->user['tahun']);
        $this->satker                  = $this->db->table('m_satker');
        $this->program                 = $this->db->table('tprogram');
        $this->kegiatan                = $this->db->table('tgiat');
        $this->output                  = $this->db->table('toutput');
        $this->suboutput               = $this->db->table('tsoutput');
        $this->tempExportBigdataColumn = $this->db->table("temp_export_bigdata_column");
        $this->request                 = \Config\Services::request();
    }


    
    public function index()
    {
        $tahun = $this->user['tahun'];
        $column = $this->tableColumn();

        $table  = 'monika_data_'.$this->user['tahun'];
        $data   = $this->monikaData->select("
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



    public function loadData() {
        $limitData  = 10;
        $offsetData = $this->request->getGet('page') * $limitData;
        $filterData = $this->request->getGet('filter');

        $result = [
            'input'  => $this->request->getGet(),
            'column' => $this->tableColumn(),
            'data'   => $this->getData($filterData, $limitData, $offsetData)
        ];

        if ($offsetData == 0) $result['totalData'] = $this->getData($filterData, null, null, true);

        return $this->respond($result, 200);
    }
    
    
    
    public function filterSelectLookup ()
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



    public function downloadExcelBigData() {
        $limitData    = 1000;
        $offsetData   = ($this->request->getGet('fileNumber') - 1) * $limitData;
        $filterData   = $this->request->getGet('filter');
        $masterColumn = $this->tableColumn();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No.');
        
        $cellHeaderEndIndex = 0;
        $columnHeader = $this->tempExportBigdataColumn->getWhere(['session' => $this->user['nama']])->getResultArray();
        foreach ($columnHeader as $keyColHeader => $dataColHeader) {
            $cellHeaderEndIndex = $keyColHeader+2;
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

        $sheet->getStyle('A1:'.$cellHeaderEnd.'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $sheet->getStyle('A1:'.$cellHeaderEnd.'1')->applyFromArray($styleArray);


        $mainData = $this->getData($filterData, $limitData, $offsetData);
        foreach ($mainData as $key => $data) {
            $row = $key+2;
            $sheet->setCellValue('A'.$row, ($key+1)+$offsetData);

            foreach ($columnHeader as $kelCell => $dataCell) {
                $cellIndex = $kelCell+2;
                $cellText = $data[$dataCell['name']];

                $masterColumnKey  = array_search($dataCell['name'], array_column($masterColumn, 'value'));
                $masterColumnCell = $masterColumn[$masterColumnKey];
                $masterColumn_isNumberFormat = array_key_exists('isNumberFormat', $masterColumnCell) ? $masterColumnCell['isNumberFormat'] : false;

                if ($masterColumn_isNumberFormat) $cellText = rupiahFormat($cellText, false);

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
        $filename = 'monika-bigdata-part-' . $this->request->getGet('fileNumber');

        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        // header('Cache-Control: max-age=0');

        $writer->save('php://output');
        $xlsData = ob_get_contents();

        ob_end_clean();
        $response =  array(
            'status' => TRUE,
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
        );
    
        die(json_encode($response));
    }



    public function prepareToDownload() {
        $filterData = $this->request->getGet('filter');

        return $this->respond([
            'totalFile' => ceil($this->getData($filterData, null, null, true)['total'] / 1000)
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
        $map_filterColumn = array_map(function($arr) use ($session_userNama) {
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



    private function getData($_filterData = [], $_limitData=null, $_offsetData=null, $_getTotal = false) 
    {
        $tahun = $this->user['tahun'];
        $table = 'monika_data_'.$tahun;

        $select = "
            $table.*, 
            m_satker.satker as nmsatker,
            m_balai.balaiid as balai_id,
            m_balai.balai as nmbalai,
            tprogram.nmprogram,
            tgiat.nmgiat,
            toutput.nmoutput,
            tsoutput.nmro,
            tkabkota.nmkabkota,
            tlokasi.nmlokasi
        ";

        if ($_getTotal) $select = "count($table.kdpaket) as total";

        $data = $this->monikaData->select($select)
        ->join('m_satker', "$table.kdsatker = m_satker.satkerid", 'left')
        ->join('m_balai', "m_satker.balaiid = m_balai.balaiid", 'left')
        ->join('tprogram', "$table.kdprogram = tprogram.kdprogram", 'left')
        ->join('tgiat', "$table.kdgiat = tgiat.kdgiat AND tgiat.tahun_anggaran='$tahun'", 'left')
        ->join('toutput', "($table.kdgiat = toutput.kdgiat AND $table.kdoutput = toutput.kdoutput AND toutput.tahun_anggaran='$tahun')", 'left')
        ->join('tsoutput', "($table.kdgiat = tsoutput.kdgiat AND $table.kdoutput = tsoutput.kdkro AND $table.kdsoutput = tsoutput.kdro AND tsoutput.tahun_anggaran='$tahun')", 'left')
        ->join('tkabkota', "($table.kdkabkota=tkabkota.kdkabkota AND $table.kdlokasi=tkabkota.kdlokasi)", 'left')
        ->join('tlokasi', "$table.kdlokasi=tlokasi.kdlokasi", 'left');

        switch ($_filterData['opsiData']) {
            case '1':
                $data->where('blokir', '0');
                break;
            
            case '2':
                $data->where('blokir', '1');
                break;
        }

        if (is_array($_filterData)) {
            foreach ($_filterData as $key => $value) {
                if ($key != 'opsiData') $data->where($table.'.'.$key, $value);
            }
        }

        if (!empty($_limitData)) $data->limit($_limitData, $_offsetData);
        
        if ($_getTotal) return $data->get()->getRowArray();
        return $data->get()->getResultArray();
    }



    private function tableColumn() 
    {
        return [
            [
                'value'       => 'no',
                'label'       => 'No. ',
                'widthColumn' => 80,
                'align'       => 'center'
            ],
            [
                'value'       => 'balai_id',
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
                'widthColumn' => 150
            ],
            [
                'value'       => 'progres_fisik',
                'label'       => 'progres fisik',
                'widthColumn' => 150
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
        ];
    }
}
