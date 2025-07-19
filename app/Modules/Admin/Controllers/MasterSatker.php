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
        $this->mBalai = $this->db->table('m_balai');
        // $this->satkerTable = $this->db->table('emon_tarik_sisalelang_sda_satker');
        // $this->paketTable = $this->db->table('emon_tarik_sisalelang_sda_paketpekerjaan');
    }



    public function index()
    {
        return view('Modules\Admin\Views\MasterSatker\index.php', [
            'tahunAnggaran' => $this->user['tahun'] ,
            'mainData'      => $this->mSatker->join('m_balai', 'm_satker.balaiid=m_balai.balaiid', 'left')->getWhere(['m_satker.tahun' => $this->user['tahun']])->getResult()
        ]);
    }



    public function exportDataToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
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

        $sheet->getStyle('A2:H2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $sheet->getStyle('A2:H2')->applyFromArray($styleArray);

        $sheet->setCellValue('A1', 'Tahun');
        $sheet->setCellValue('B1', $this->user['tahun']);

        $sheet->setCellValue('A2', 'Satker ID');
        $sheet->setCellValue('B2', 'Balai ID');
        $sheet->setCellValue('C2', 'Sarket');
        $sheet->setCellValue('D2', 'KD KPPN');
        $sheet->setCellValue('E2', 'Jabatan Penanda Tangan Pihak 1');
        $sheet->setCellValue('F2', 'Jabatan Penanda Tangan Pihak 2');
        $sheet->setCellValue('G2', 'Kota Penanda Tangan');
        $sheet->setCellValue('H2', 'Grup Jabatan');

        $dataSatker = $this->mSatker->getWhere(['tahun' => $this->user['tahun']])->getResult();
        foreach ($dataSatker as $key => $data) :
            $row = $key+3;
            $sheet->setCellValue('A'.$row, $data->satkerid);
            $sheet->setCellValue('B'.$row, $data->balaiid);
            $sheet->setCellValue('C'.$row, $data->satker);
            $sheet->setCellValue('D'.$row, $data->kdkppn);
            $sheet->setCellValue('E'.$row, $data->jabatan_penanda_tangan_pihak_1);
            $sheet->setCellValue('F'.$row, $data->jabatan_penanda_tangan_pihak_2);
            $sheet->setCellValue('G'.$row, $data->kota_penanda_tangan);
            $sheet->setCellValue('H'.$row, $data->grup_jabatan);
        endforeach;



        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet2 = $spreadsheet->getActiveSheet();
        $sheet2->setTitle('Balai');

        $sheet2->getColumnDimension('A')->setAutoSize(true);
        $sheet2->getColumnDimension('B')->setAutoSize(true);
        $sheet2->getRowDimension('1')->setRowHeight(30);

        $sheet2->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $sheet2->getStyle('A1:B1')->applyFromArray($styleArray);

        $sheet2->setCellValue('A1', 'Balai ID');
        $sheet2->setCellValue('B1', 'Balai Nama');

        $dataSatker = $this->mBalai->get()->getResult();
        foreach ($dataSatker as $key => $data) :
            $row = $key+2;
            $sheet2->setCellValue('A'.$row, $data->balaiid);
            $sheet2->setCellValue('B'.$row, $data->balai);
        endforeach;

        
        $spreadsheet->setActiveSheetIndex(0);

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
                    'satkerid'                       => $data[0],
                    'balaiid'                        => $data[1],
                    'satker'                         => $data[2],
                    'kdkppn'                         => $data[3],
                    'tahun'                          => $inputTahun,
                    'jabatan_penanda_tangan_pihak_1' => $data[4],
                    'jabatan_penanda_tangan_pihak_2' => $data[5],
                    'kota_penanda_tangan'            => $data[6],
                    'grup_jabatan'                   => $data[7]
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
