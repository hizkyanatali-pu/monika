<?php

namespace Modules\Admin\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class MasterKegiatan extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->tGiat = $this->db->table('tgiat');
    }



    public function index()
    {
        return view('Modules\Admin\Views\MasterKegiatan\index.php', [
            'tahunAnggaran' => $this->user['tahun'] ,
            'mainData'      => $this->tGiat->getWhere(['tahun_anggaran' => $this->user['tahun']])->getResult()
        ]);
    }



    public function exportDataToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('B')->setAutoSize(true);
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

        $sheet->getStyle('A2:I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $sheet->getStyle('A2:I2')->applyFromArray($styleArray);

        $sheet->setCellValue('A1', 'Tahun');
        $sheet->setCellValue('B1', $this->user['tahun']);

        $sheet->setCellValue('A2', 'Kode');
        $sheet->setCellValue('B2', 'Nama');
        $sheet->setCellValue('C2', 'Kode Dept');
        $sheet->setCellValue('D2', 'Kode Unit');
        $sheet->setCellValue('E2', 'Kode Program');
        $sheet->setCellValue('F2', 'Kode Fungsi');
        $sheet->setCellValue('G2', 'Kode Sfung');
        $sheet->setCellValue('H2', 'KDes2');
        $sheet->setCellValue('I2', 'Kode Program Output');

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

        $writer = new Xlsx($spreadsheet);
        $filename = $this->user['tahun'].'-Data Kegiatan-'.date('Y-m-d-His');

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
                    'kdgiat'         => $data[0],
                    'nmgiat'         => $data[1],
                    'kddept'         => $data[2],
                    'kdunit'         => $data[3],
                    'kdprogram'      => $data[4],
                    'kdfungsi'       => $data[5],
                    'kdsfung'        => $data[6],
                    'kdes2'          => $data[7],
                    'kdprogout'      => $data[8],
                    'tahun_anggaran' => $inputTahun
                ]);
            }
        endforeach;

        $this->tGiat->where('tahun_anggaran', $inputTahun);
        $this->tGiat->delete();
        $this->tGiat->insertBatch($tempInsert);

        unlink('uploads/'.$filename);

        header("Content-Type: application/json");
        return json_encode([
            'jumlah_data' => count($tempInsert)
        ]);
    }
}