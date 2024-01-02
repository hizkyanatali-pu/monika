<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Models\TematikModel;
use CodeIgniter\API\ResponseTrait;


class Tematik extends \App\Controllers\BaseController
{
    use ResponseTrait;

    private $renderFolder = 'Modules\Admin\Views\Tematik';



    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->TematikModel = new TematikModel();
    }



    // function index()
    // {
    //      $m_tematik =  $this->db->table("m_tematik")->where('deleted_at', null)->get()->getResultArray();
    //     return view($this->renderFolder . '\Tematik-view', [
    //         'title'         => 'Kawasan Industri',
    //         'filterTitle'   => 'Dukungan Kawasan Industri di Lingkungan Ditjen SDA TA',
    //         'exportCode'    => 'kawasan_industri',
    //         'data'          => $data,
    //         'id_report_pdf' => 'cetak_kawasan_industri'
    //     ]);
    // }

    function index()
    {
        $data =  $this->db->table("data_tematik")->where('deleted_at', null)->groupBy('tematikid')->get()->getResultArray();






        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'Tematik',
            'data'          => $data,

        ]);
    }

    function addForm()
    {
        $unit_kerja  =  $this->db->table("m_satker")->get()->getResultArray();
        return view($this->renderFolder . '\Tematik-add', ["unit_kerja" => $unit_kerja]);
    }



    function mTematikList()
    {
        $m_tematik =  $this->db->table("m_tematik")->where('deleted_at', null)->get()->getResultArray();

        $formatted_data = [];
        foreach ($m_tematik as $tematik) {
            $formatted_data[] = [
                'id'   => $tematik['tematikid'],    // Ganti dengan kolom ID yang sesuai dari tabel Anda
                'text' => $tematik['tematikid'] . " - " . $tematik['tematik']   // Ganti dengan kolom NAMA atau kolom yang sesuai dari tabel Anda
            ];
        }
        return $this->respond($formatted_data);
    }

    function mTematikInsert()
    {
        $builder = $this->db->table('m_tematik');

        $data = [
            'tematikid' => $this->request->getPost('idTematik'),
            'tematik' => $this->request->getPost('namaTematik'),
        ];

        // Validasi data jika diperlukan
        $validation = \Config\Services::validation();
        $validation->setRules([
            'idTematik' => 'required',
            'namaTematik' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kirim respons dengan pesan kesalahan
            return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
        }


        // Pengecekan apakah ID sudah ada
        $idToCheck = $this->request->getPost('idTematik'); // Gantilah dengan nama kolom ID yang sesuai
        $namaToCheck = $this->request->getPost('namaTematik'); // Gantilah dengan nama kolom ID yang sesuai
        $idAlreadyExists = $builder->where('tematikid', $idToCheck)->countAllResults() > 0;
        $nameAlreadyExists = $builder->where('tematik', $namaToCheck)->countAllResults() > 0;
        if ($idAlreadyExists) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Tematik sudah ada dalam database']);
        }
        if ($nameAlreadyExists) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Tematik sudah ada dalam database']);
        }



        $builder->insert($data);


        return $this->respond(['status' => 'success', 'message' => 'Data berhasil disimpan']);
    }

    function mSubTematikList()
    {

        $tematikid = $this->request->getVar('tematik');

        $m_subtematik =  $this->db->table("m_subtematik")->where('deleted_at', null)->where('tematikid', $tematikid)->get()->getResultArray();

        $formatted_data = [];
        foreach ($m_subtematik as $tematik) {
            $formatted_data[] = [
                'id'   => $tematik['subtematikid'],    // Ganti dengan kolom ID yang sesuai dari tabel Anda
                'text' => $tematik['subtematikid'] . " - " . $tematik['subtematik']   // Ganti dengan kolom NAMA atau kolom yang sesuai dari tabel Anda
            ];
        }
        return $this->respond($formatted_data);
    }

    function mSubTematikInsert()
    {
        $builder = $this->db->table('m_subtematik');

        $data = [
            'tematikid' => $this->request->getPost('idTematik2'),
            'subtematikid' => $this->request->getPost('idSubTematik'),
            'subtematik' => $this->request->getPost('namaSubTematik'),
        ];


        // Validasi data jika diperlukan
        $validation = \Config\Services::validation();
        $validation->setRules([
            'idTematik2' => 'required',
            'idSubTematik' => 'required',
            'namaSubTematik' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kirim respons dengan pesan kesalahan
            return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
        }


        // Pengecekan apakah ID sudah ada
        $idToCheck = $this->request->getPost('idSubTematik'); // Gantilah dengan nama kolom ID yang sesuai
        $namaToCheck = $this->request->getPost('namaSubTematik'); // Gantilah dengan nama kolom ID yang sesuai
        $idAlreadyExists = $builder->where('subtematikid', $idToCheck)->countAllResults() > 0;
        $nameAlreadyExists = $builder->where('subtematik', $namaToCheck)->countAllResults() > 0;
        if ($idAlreadyExists) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID SubTematik sudah ada dalam database']);
        }
        if ($nameAlreadyExists) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama SubTematik sudah ada dalam database']);
        }



        $builder->insert($data);


        return $this->respond(['status' => 'success', 'message' => 'Data berhasil disimpan']);
    }


    public function addDataTematik()
    {


        $data = [
            'tematikid' => $this->request->getPost('tematikID'),
            'tematik' => $this->request->getPost('tematikID') ? tematik_name($this->request->getPost('tematikID')) : "",
            'subtematikid' => $this->request->getPost('subTematikID'),
            'subtematik' =>  $this->request->getPost('subTematikID') ? subtematik_name($this->request->getPost('subTematikID')) : "",
            "tahun" => $this->request->getPost('tahun')
        ];


        $paket = $this->request->getPost('paket');

        $update = $this->request->getPost('actionupdate');
        $id = $this->request->getPost('iddata');



        // Validasi data jika diperlukan
        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'tematikID' => 'required',
                'subTematikID' => 'required',
                'tahun' => 'required',
                'paket' => 'required',
            ],
            [
                'tematikID' => [
                    'required' => 'Kolom Tematik ID wajib diisi.',
                ],
                'subTematikID' => [
                    'required' => 'Kolom Sub Tematik ID wajib diisi.',
                ],
                'tahun' => [
                    'required' => 'Kolom Tahun wajib diisi.',
                ],
                'paket' => [
                    'required' => 'Minimal 1 paket harus dipilih',
                ]
            ]
        );



        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kirim respons dengan pesan kesalahan
            return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
        }

        $tableTematik = $this->db->table('data_tematik');
        $tabletematikPaket = $this->db->table('data_tematik_paket');

        if ($update == "false") {

            $tableTematik->insert($data);
            $insertedID = $this->db->insertID();
        } else {
            $validation->setRules(
                [
                    'iddata' => 'required',
                ],
                [
                    'ID' => [
                        'required' => 'Tidak ada ID',
                    ]

                ]
            );

            if (!$validation->withRequest($this->request)->run()) {
                // Jika validasi gagal, kirim respons dengan pesan kesalahan
                return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
            }

            $tabletematikPaket->where('datatematikid', $id);
            $tabletematikPaket->delete();
            $tableTematik->insert($data);
            $insertedID = $this->db->insertID();
        }






        foreach ($paket as $key => $value) {



            $dataPaket = [
                "datatematikid" =>  $insertedID,
                "kdpaket"       => $value['id'],
                "nmpaket"       => $value['label'],
                "pagu_rpm"      => $value['pagu_rpm'],
                "pagu_sbsn"     => $value['pagu_sbsn'],
                "pagu_phln"     => $value['pagu_phln'],
                "pagu_total"     => $value['pagu_total'],

                "real_rpm"      => $value['real_rpm'],
                "real_sbsn"     => $value['real_sbsn'],
                "real_phln"     => $value['real_phln'],
                "real_total"     => $value['real_total'],
                "progres_keuangan"     => $value['progres_keuangan'],
                "progres_fisik"     => $value['progres_fisik'],



            ];

            if ($update == "false") {
                $tabletematikPaket->insert($dataPaket);
            } else {
                $tabletematikPaket->where('datatematikid', $id);
                $tabletematikPaket->delete();
                $tabletematikPaket->insert($dataPaket);
            }
        }

        return $this->respond(['status' => 'success', 'message' => 'Data berhasil disimpan']);
    }


    function DataTematik()
    {


        $tableTematik = $this->db->table('data_tematik');

        $data = $tableTematik
            ->join('data_tematik_paket', 'data_tematik.id = data_tematik_paket.datatematikid')
            ->groupBy("data_tematik.tematikid, data_tematik.subtematikid, data_tematik.tahun")

            ->select('data_tematik.*, GROUP_CONCAT(data_tematik_paket.kdpaket SEPARATOR "$$") as kdpaket,GROUP_CONCAT(data_tematik_paket.nmpaket SEPARATOR "$$") as nmpaket,GROUP_CONCAT(data_tematik_paket.pagu_total SEPARATOR "$$") as pagu_total,GROUP_CONCAT(data_tematik_paket.real_total SEPARATOR "$$") as real_total,GROUP_CONCAT(data_tematik_paket.progres_keuangan SEPARATOR "$$") progres_keuangan,GROUP_CONCAT(data_tematik_paket.progres_fisik SEPARATOR "$$") progres_fisik')
            ->get()
            ->getResultArray();
        return $this->respond($data);
    }


    function checkDataTematik()
    {
        $tematikid = $this->request->getVar('tematikid') ?? '';
        $subtematikid = $this->request->getVar('subtematikid') ?? '';
        $tahun = $this->request->getVar('tahun') ?? '';




        $tableTematik = $this->db->table('data_tematik');

        $data = $tableTematik->select('data_tematik.tematikid,kdpaket as id,pagu_total,data_tematik.id as datatematikid')
            ->join('data_tematik_paket', 'data_tematik.id = data_tematik_paket.datatematikid')
            ->where('tematikid', $tematikid)
            ->where('subtematikid', $subtematikid)
            ->where('tahun', $tahun)
            ->get()
            ->getResultArray();



        return $this->respond($data);
    }





    public function pageFoodEstate()
    {
        $data = $this->TematikModel->getListTematikFoodEstate('T060019');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'Food Estate',
            'filterTitle'   => 'Food Estate TA',
            'exportCode'    => 'food_estate',
            'data'          => $data,
            'id_report_pdf' => 'cetak_food_estate'
        ]);
    }

    public function cetakFoodEstate()
    {

        $data = [
            'title' => 'Food Estate',
            'data'  => $this->TematikModel->getListTematik('T060019')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageKawasanIndustri()
    {
        // $data = $this->TematikModel->getListTematik('TMKEM0005');
        $data = $this->TematikModel->getListTematik('T060012');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'Kawasan Industri',
            'filterTitle'   => 'Dukungan Kawasan Industri di Lingkungan Ditjen SDA TA',
            'exportCode'    => 'kawasan_industri',
            'data'          => $data,
            'id_report_pdf' => 'cetak_kawasan_industri'
        ]);
    }

    public function cetakKawasanIndustri()
    {

        $data = [
            'title' => 'Kawasan Industri',
            'data'  => $this->TematikModel->getListTematik('TMKEM0005')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageKspn($kspCode)
    {
        $kspnTitle = $this->kspnFilterTitle($kspCode);
        $data = $this->TematikModel->getListTematikKspn($kspCode);

        return view($this->renderFolder . '\Kspn', [
            'title'         => 'KSPN',
            'uri'           => current_url(true),
            'filterTitle'   => $kspnTitle['filterTitle'],
            'data'          => $data,
            'id_report_pdf' => 'cetak_kspn'
        ]);
    }

    public function cetakKspn($kspCode)
    {

        $data = [
            'title' => 'KSPN',
            'data'  => $this->TematikModel->getListTematikKspn($kspCode),
            'id_report_pdf' => 'cetak_kspn'
        ];

        return view($this->renderFolder . "\Cetak\Kspn-pdf", $data);
    }


    public function pageG20()
    {
        $data = $this->TematikModel->getListTematik('T060020');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'G20',
            'filterTitle'   => 'G20 TA',
            'exportCode'    => 'g20',
            'data'          => $data,
            'id_report_pdf' => 'cetak_g20'
        ]);
    }


    public function cetakG20()
    {
        $data = [
            'title' => 'G20',
            'data'  => $this->TematikModel->getListTematik('T060020')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageIkn()
    {
        $data = $this->TematikModel->getListTematik('TXX0011');
        return view($this->renderFolder . '\Tematik-view', [
            'title'         => 'Ibu Kota Negara (IKN)',
            'filterTitle'   => 'IKN TA',
            'exportCode'    => 'ikn',
            'data'          => $data,
            'id_report_pdf' => 'cetak_ikn'
        ]);
    }


    public function cetakIkn()
    {
        $data = [
            'title' => 'Ibu Kota Negara (IKN)',
            'data'  => $this->TematikModel->getListTematik('TXX0011')
        ];

        return view($this->renderFolder . "\Cetak\Tematik-pdf", $data);
    }


    public function pageRekap()
    {
        $grupData = $this->rekapGroupData();
        $data = $this->TematikModel->getListRekap($grupData);

        return view($this->renderFolder . '\Rekap', [
            'title'         => 'Rekap',
            'data'          => $data,
            'id_report_pdf' => 'cetak_rekap'
        ]);
    }

    public function cetakRekap()
    {

        $data = [
            'title' => 'Rekap',
            'data'  => $this->TematikModel->getListRekap($this->rekapGroupData())
        ];

        return view($this->renderFolder . "\Cetak\Rekap-pdf", $data);
    }


    public function exportExcel($tematikType)
    {
        if ($tematikType == 'food_estate') {
            $tematikCode = "T060019";
            $title = "Food Estate";
            $filterTitle = "Food Estate TA";
        } elseif ($tematikType == 'g20') {
            $tematikCode = "T060020";
            $title = "G20";
            $filterTitle = "G20 TA";
        } elseif ($tematikType == 'ikn') {
            $tematikCode = "TXX0011";
            $title = "Ibu Kota Negara (IKN)";
            $filterTitle = "IKN TA";
        } else {
            $tematikCode = "TMKEM0005";
            $title = "Kawasan Industri";
            $filterTitle = "Dukungan Kawasan Industri di Lingkungan Ditjen SDA TA";
        }

        $data = $this->TematikModel->getListTematik($tematikCode);
        return view($this->renderFolder . '\Cetak\Tematik-excel', [
            'title'         => $title,
            'filterTitle'   => $filterTitle,
            'data'          => $data
        ]);
    }



    public function exportExcelKspn($kspnCode)
    {
        $kspnTitle = $this->kspnFilterTitle($kspnCode);
        $data = $this->TematikModel->getListTematikKspn($kspnCode);
        return view($this->renderFolder . '\Cetak\Kspn-excel', [
            'title'         => $kspnTitle['title'],
            'filterTitle'   => $kspnTitle['filterTitle'],
            'data'          => $data
        ]);
    }



    public function exportExcelRekap()
    {
        $grupData = $this->rekapGroupData();
        $data = $this->TematikModel->getListRekap($grupData);

        return view($this->renderFolder . '\Cetak\Rekap-excel', [
            'data'          => $data
        ]);
    }



    private function kspnFilterTitle($kspnCode)
    {
        switch ($kspnCode):
            case 'kspn01':
                $title = 'Danau Toba';
                $filterTitle = 'Danau Toba TA';
                break;

            case 'kspn02':
                $title = 'Borobudur';
                $filterTitle = 'Borobudur TA';
                break;

            case 'kspn03':
                $title = 'Mandalika';
                $filterTitle = 'Mandalika TA';
                break;

            case 'kspn04':
                $title = 'Labuan Bajo';
                $filterTitle = 'Labuan Bajo TA';
                break;

            case 'kspn05':
                $title = 'Manado';
                $filterTitle = 'Manado - Bitung - Likupang TA';
                break;

            case 'kspn06':
                $title = 'Tanjung Kelayang ';
                $filterTitle = 'Tanjung Kelayang  TA';
                break;

            case 'kspn08':
                $title = 'Wakatobi';
                $filterTitle = 'Wakatobi TA';
                break;

            case 'kspn09':
                $title = 'Morotai';
                $filterTitle = 'Morotai TA';
                break;

            default:
                $title = '';
                $filterTitle = '';
                break;
        endswitch;
        return [
            'title'         =>  $title,
            'filterTitle'   =>  $filterTitle,
        ];
    }



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
            ],
            [
                'title' => 'G20',
                'tematikCode' => ["'T060020'"]
            ],
            [
                'title' => 'Ibu Kota Negara (IKN)',
                'tematikCode' => ["'TXX0011'"]
            ]
        ];
    }
}
