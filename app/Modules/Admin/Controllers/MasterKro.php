<?php

namespace Modules\Admin\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class MasterKro extends \App\Controllers\BaseController
{
    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->tOutput = $this->db->table('toutput');
    }



    public function index()
    {
        return view('Modules\Admin\Views\MasterKro\index.php', [
            'tahunAnggaran' => $this->user['tahun'] ,
            'mainData'      => $this->tOutput->getWhere(['tahun_anggaran' => $this->user['tahun']])->getResult()
        ]);
    }



    public function exportDataToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('C')->setAutoSize(true);
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

        $sheet->getStyle('A2:S2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $sheet->getStyle('A2:S2')->applyFromArray($styleArray);

        $sheet->setCellValue('A1', 'Tahun');
        $sheet->setCellValue('B1', $this->user['tahun']);

        $sheet->setCellValue('A2', 'Kode kegiatan');
        $sheet->setCellValue('B2', 'Kode Output');
        $sheet->setCellValue('C2', 'Nama');
        $sheet->setCellValue('D2', 'Satuan');
        $sheet->setCellValue('E2', 'Kode Sum');
        $sheet->setCellValue('F2', 'Tahun Awal');
        $sheet->setCellValue('G2', 'Tahun Akhir');
        $sheet->setCellValue('H2', 'Kode Multi');
        $sheet->setCellValue('I2', 'Kode Jenis Suboutput');
        $sheet->setCellValue('J2', 'Kode IKK');
        $sheet->setCellValue('K2', 'Kode Tema');
        $sheet->setCellValue('L2', 'Kode PN');
        $sheet->setCellValue('M2', 'Kode PP');
        $sheet->setCellValue('N2', 'Kode KP');
        $sheet->setCellValue('O2', 'Kode Proy');
        $sheet->setCellValue('P2', 'Kode Nawacita');
        $sheet->setCellValue('Q2', 'Kode Janpres');
        $sheet->setCellValue('R2', 'Kode Cttout');
        $sheet->setCellValue('S2', 'Kode Unit');

        $mainData = $this->tOutput->getWhere(['tahun_anggaran' => $this->user['tahun']])->getResult();
        foreach ($mainData as $key => $data) :
            $row = $key+3;
            $sheet->setCellValue('A'.$row, $data->kdgiat);
            $sheet->setCellValue('B'.$row, $data->kdoutput);
            $sheet->setCellValue('C'.$row, $data->nmoutput);
            $sheet->setCellValue('D'.$row, $data->sat);
            $sheet->setCellValue('E'.$row, $data->kdsum);
            $sheet->setCellValue('F'.$row, $data->thnawal);
            $sheet->setCellValue('G'.$row, $data->thnakhir);
            $sheet->setCellValue('H'.$row, $data->kdmulti);
            $sheet->setCellValue('I'.$row, $data->kdjnsout);
            $sheet->setCellValue('J'.$row, $data->kdikk);
            $sheet->setCellValue('K'.$row, $data->kdtema);
            $sheet->setCellValue('L'.$row, $data->kdpn);
            $sheet->setCellValue('M'.$row, $data->kdpp);
            $sheet->setCellValue('N'.$row, $data->kdkp);
            $sheet->setCellValue('O'.$row, $data->kdproy);
            $sheet->setCellValue('P'.$row, $data->kdnawacita);
            $sheet->setCellValue('Q'.$row, $data->kdjanpres);
            $sheet->setCellValue('R'.$row, $data->kdcttout);
            $sheet->setCellValue('S'.$row, $data->kdunit);
        endforeach;

        $writer = new Xlsx($spreadsheet);
        $filename = $this->user['tahun'].'-Data KRO-'.date('Y-m-d-His');

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
                    'kdoutput'       => $data[1],
                    'nmoutput'       => $data[2],
                    'sat'            => $data[3],
                    'kdsum'          => $data[4],
                    'thnawal'        => $data[5],
                    'thnakhir'       => $data[6],
                    'kdmulti'        => $data[7],
                    'kdjnsout'       => $data[8],
                    'kdikk'          => $data[9],
                    'kdtema'         => $data[10],
                    'kdpn'           => $data[11],
                    'kdpp'           => $data[12],
                    'kdkp'           => $data[13],
                    'kdproy'         => $data[14],
                    'kdnawacita'     => $data[15],
                    'kdjanpres'      => $data[16],
                    'kdcttout'       => $data[17],
                    'kdunit'         => $data[18],
                    'tahun_anggaran' => $inputTahun
                ]);
            }
        endforeach;

        $this->tOutput->where('tahun_anggaran', $inputTahun);
        $this->tOutput->delete();
        $this->tOutput->insertBatch($tempInsert);

        unlink('uploads/'.$filename);

        header("Content-Type: application/json");
        return json_encode([
            'jumlah_data' => count($tempInsert)
        ]);
    }
}
