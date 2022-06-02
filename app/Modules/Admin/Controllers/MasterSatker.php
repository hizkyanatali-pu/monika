<?php

namespace Modules\Admin\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class MasterSatker extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->mSatker = $this->db->table('m_satker');
        // $this->satkerTable = $this->db->table('emon_tarik_sisalelang_sda_satker');
        // $this->paketTable = $this->db->table('emon_tarik_sisalelang_sda_paketpekerjaan');
    }



    public function index()
    {
        return view('Modules\Admin\Views\MasterSatker\index.php', [
            'tahunAnggaran' => $this->user['tahun'] ,
            'mainData'      => $this->mSatker->getWhere(['tahun' => $this->user['tahun']])->getResult()
        ]);
    }



    public function exportDataToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getRowDimension('2')->setRowHeight(30);

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

        $sheet->getStyle('A2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $sheet->getStyle('A2:D2')->applyFromArray($styleArray);

        $sheet->setCellValue('A1', 'Tahun');
        $sheet->setCellValue('B1', $this->user['tahun']);

        $sheet->setCellValue('A2', 'Satker ID');
        $sheet->setCellValue('B2', 'Balai ID');
        $sheet->setCellValue('C2', 'Sarket');
        $sheet->setCellValue('D2', 'KD KPPN');

        $dataSatker = $this->mSatker->getWhere(['tahun' => $this->user['tahun']])->getResult();
        foreach ($dataSatker as $key => $data) :
            $row = $key+3;
            $sheet->setCellValue('A'.$row, $data->satkerid);
            $sheet->setCellValue('B'.$row, $data->balaiid);
            $sheet->setCellValue('C'.$row, $data->satker);
            $sheet->setCellValue('D'.$row, $data->kdkppn);
        endforeach;

        $writer = new Xlsx($spreadsheet);
        $filename = $this->user['tahun'].'-Data Satker-'.date('Y-m-d-His');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }



    public function importDataToExcel() {
        $file = $this->request->getFile('file');
        $filename = $file->getName();
        if (file_exists('uploads/'.$filename)) unlink('uploads/'.$filename);
        $file->move('uploads', $filename);
        
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load('uploads/'.$filename);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $inputTahun = $sheet->getCell('B1')->getValue();
        $tempInsert = [];
        foreach ($rows as $key => $data) :
            if ($key > 1) {
                array_push($tempInsert, [
                    'satkerid' => $data[0],
                    'balaiid'  => $data[1],
                    'satker'   => $data[2],
                    'kdkppn'   => $data[3],
                    'tahun'    => $inputTahun
                ]);
            }
        endforeach;

        $this->mSatker->where('tahun', $inputTahun);
        $this->mSatker->delete();
        $this->mSatker->insertBatch($tempInsert);

        unlink('uploads/'.$filename);

        header("Content-Type: application/json");
        return json_encode([
            'jumlah_data' => count($tempInsert)
        ]);
    }
}
