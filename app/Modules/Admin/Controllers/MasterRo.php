<?php

namespace Modules\Admin\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class MasterRo extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->tSubOutput = $this->db->table('tsoutput');
    }



    public function index()
    {
        return view('Modules\Admin\Views\MasterRo\index.php', [
            'tahunAnggaran' => $this->user['tahun'] ,
            'mainData'      => $this->tSubOutput->getWhere(['tahun_anggaran' => $this->user['tahun']])->getResult()
        ]);
    }



    public function exportDataToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('D')->setAutoSize(true);
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

        $sheet->setCellValue('A2', 'Kode Kegiatan');
        $sheet->setCellValue('B2', 'Kode KRO');
        $sheet->setCellValue('C2', 'Kode RO');
        $sheet->setCellValue('D2', 'Nama');

        $mainData = $this->tSubOutput->getWhere(['tahun_anggaran' => $this->user['tahun']])->getResult();
        foreach ($mainData as $key => $data) :
            $row = $key+3;
            $sheet->setCellValue('A'.$row, $data->kdgiat);
            $sheet->setCellValue('B'.$row, $data->kdkro);
            $sheet->setCellValue('C'.$row, $data->kdro);
            $sheet->setCellValue('D'.$row, $data->nmro);
        endforeach;

        $writer = new Xlsx($spreadsheet);
        $filename = $this->user['tahun'].'-Data RO-'.date('Y-m-d-His');

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
                    'kdkro'          => $data[1],
                    'kdro'           => $data[2],
                    'nmro'           => $data[3],
                    'tahun_anggaran' => $inputTahun
                ]);
            }
        endforeach;

        $this->tSubOutput->where('tahun_anggaran', $inputTahun);
        $this->tSubOutput->delete();
        $this->tSubOutput->insertBatch($tempInsert);

        unlink('uploads/'.$filename);

        header("Content-Type: application/json");
        return json_encode([
            'jumlah_data' => count($tempInsert)
        ]);
    }
}
