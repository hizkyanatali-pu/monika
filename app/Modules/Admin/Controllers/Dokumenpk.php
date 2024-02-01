<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Dokumenpk extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->dokumenSatker = $this->db->table('dokumenpk_satker');

        $this->tahun = session('userData.tahun');

        $this->dokumenPK               = $this->db->table('dokumen_pk_template_' . $this->tahun);
        $this->dokumenPK_row           = $this->db->table('dokumen_pk_template_row_' . $this->tahun);
        $this->dokumenPk_rowRumus      = $this->db->table('dokumen_pk_template_rowrumus_' . $this->tahun);
        $this->dokumenPK_kegiatan      = $this->db->table('dokumen_pk_template_kegiatan_' . $this->tahun);
        $this->dokumenPK_info          = $this->db->table('dokumen_pk_template_info_' . $this->tahun);
        $this->dokumenPK_akses         = $this->db->table('dokumen_pk_template_akses_' . $this->tahun);
        $this->tempExportBigdataColumn = $this->db->table("temp_export_bigdata_column");
        $this->tableSatker             = $this->db->table("m_satker");
        $this->tableBalai              = $this->db->table("m_balai");
        $this->tableKegiatan           = $this->db->table("tgiat");
        $this->tableProgram            = $this->db->table("tprogram");
        $this->templateDokumen         = $this->db->table('dokumen_pk_template_' . $this->tahun);
        $this->dokumenSatker_paket     = $this->db->table('dokumenpk_satker_paket');



        $this->satker   = $this->db->table('m_satker');
        $this->balai    = $this->db->table('m_balai');

        $this->request = \Config\Services::request();
    }



    public function dashboard()
    {
        $grup_jabatan = ["ESELON II", "UPT/BALAI", "BALAI TEKNIK", "SATKER PUSAT", "SKPD TP-OP"];
        $jumlahTotal = $this->dokumenPK_akses->countAllResults();
        $balai_s =  $this->balai->select('balai,balaiid')->where("jabatan_penanda_tangan_pihak_1 !=", "")->get()->getResult();

        $tanggal = date('d');
        $month = date('m');
        if ($month == '01') {
            $bulan = 'Januari';
        } else if ($month == '02') {
            $bulan = 'Februari';
        } else if ($month == '03') {
            $bulan = 'Maret';
        } else if ($month == '04') {
            $bulan = 'April';
        } else if ($month == '05') {
            $bulan = 'Mei';
        } else if ($month == '06') {
            $bulan = 'Juni';
        } else if ($month == '07') {
            $bulan = 'Juli';
        } else if ($month == '08') {
            $bulan = 'Agustus';
        } else if ($month == '09') {
            $bulan = 'September';
        } else if ($month == '10') {
            $bulan = 'Oktober';
        } else if ($month == '11') {
            $bulan = 'November';
        } else if ($month == '12') {
            $bulan = 'Desember';
        }
        $tahun = date('Y');
        $jam = date('H:i:s');

        return view('Modules\Admin\Views\DokumenPK\dashboard.php', [
            'title' => 'Dashboard Perjanjian Kinerja',
            'session_year'      =>   $this->user['tahun'],
            'tanggal'           =>   $tanggal,
            'bulan'      =>   $bulan,
            'bulan_status'      =>   $bulan,
            'tahun'             =>   $tahun,
            'jam'               =>   $jam,
            'group_jabatan'     =>   $grup_jabatan,
            'balai_s'           =>   $balai_s,
        ]);
    }



    public function satker()
    {
        $dataBelumInput = $this->satker->select("
            m_satker.satker
        ")
            ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='satker' and satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun= {$this->user['tahun']} AND `status` != 'revision'
            AND deleted_at is null) < 1 and m_satker.grup_jabatan = 'satker'")
            ->get()->getResult();

        $dataBelumInput = array_map(function ($arr) {
            return [
                'nama' => $arr->satker
            ];
        }, $dataBelumInput);

        $instansi =  $this->tableSatker->select("satkerid,satker")->get()->getResult();

        return view('Modules\Admin\Views\DokumenPK\satker.php', [
            'sessionYear'              => $this->user['tahun'],
            'title'                => 'Dokumen Penjanjian Kinerja - Satker',
            'dokumenType'              => 'satker',
            'dataBelumInput'           => $dataBelumInput,
            'createDokumen_userOption' => json_encode($this->tableSatker->select("satkerid as id, satker as title")->whereNotIn('satker', ['', '1'])->where('grup_jabatan', 'satker')->get()->getResult()),
            'instansi' => $instansi
        ]);
    }


    public function balai()
    {
        $dataBelumInput = $this->balai->select("
            balai
        ")
            ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='balai' and balaiid=m_balai.balaiid and tahun={$this->user['tahun']} AND `status` != 'revision'
            AND deleted_at is null) < 1 AND kota_penanda_tangan != ''")
            ->get()->getResult();

        $dataBelumInput = array_map(function ($arr) {
            return [
                'nama' => $arr->balai
            ];
        }, $dataBelumInput);

        return view('Modules\Admin\Views\DokumenPK\satker.php', [
            'sessionYear'              => $this->user['tahun'],
            'title'                => 'Dokumen Penjanjian Kinerja - Balai',
            'dokumenType'              => 'balai',
            'dataBelumInput'           => $dataBelumInput,
            'createDokumen_userOption' => json_encode($this->tableBalai->select("balaiid as id, balai as title")->where('kota_penanda_tangan !=', '')->get()->getResult())
        ]);
    }


    public function eselon2()
    {
        $dataBelumInput = $this->satker->select("
        m_satker.satker
    ")
            ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='eselon2' and satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun= {$this->user['tahun']} AND `status` != 'revision'
		AND deleted_at is null) < 1 and m_satker.grup_jabatan = 'eselon2'")
            ->get()->getResult();

        $dataBelumInput = array_map(function ($arr) {
            return [
                'nama' => $arr->satker
            ];
        }, $dataBelumInput);


        return view('Modules\Admin\Views\DokumenPK\satker.php', [
            'sessionYear'              => $this->user['tahun'],
            'title'                => 'Dokumen Penjanjian Kinerja - Eselon 2',
            'dokumenType'              => 'eselon2',
            'dataBelumInput'           => $dataBelumInput,
            'createDokumen_userOption' => json_encode($this->tableSatker->select("satkerid as id, satker as title")->whereNotIn('satker', ['', '1'])->where('grup_jabatan', 'eselon2')->get()->getResult())
        ]);
    }


    public function eselon1()
    {

        $dataBelumInput = $this->satker->select("
        m_satker.satker
    ")
            ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='eselon1' and satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun= {$this->user['tahun']} AND `status` != 'revision'
		AND deleted_at is null) < 1 and m_satker.grup_jabatan = 'eselon1'")
            ->get()->getResult();

        $dataBelumInput = array_map(function ($arr) {
            return [
                'nama' => $arr->satker
            ];
        }, $dataBelumInput);

        return view('Modules\Admin\Views\DokumenPK\satker.php', [
            'sessionYear'              => $this->user['tahun'],
            'title'                => 'Dokumen Penjanjian Kinerja - Eselon1',
            'dokumenType'              => 'eselon1',
            'dataBelumInput'           => $dataBelumInput,
            'createDokumen_userOption' => json_encode($this->tableSatker->select("satkerid as id, satker as title")->whereNotIn('satker', ['', '1'])->where('grup_jabatan', 'eselon1')->get()->getResult())
        ]);
    }



    public function getListTemplateBuatDokumen($userType, $userId)
    {
        $template_type    = $userType;
        $templae_revTable = '';
        $template_revID   = $userId;
        switch ($userType) {
            case 'satker':
                $templae_revTable = 'm_satker';
                $dataSatker = $this->tableSatker->join('m_balai', 'm_satker.balaiid=m_balai.balaiid', 'left')->where('satkerid', $userId)->get()->getRow();
                $this->session->set('createDokumenByAdmin', [
                    'byAdmin_user_type'   => 'satker',
                    'byAdmin_satker_nama' => $dataSatker->satker,
                    'byAdmin_balai_nama'  => $dataSatker->balai,
                    'byAdmin_satker_id'   => $dataSatker->satkerid,
                    'byAdmin_balai_id'    => $dataSatker->balaiid
                ]);
                break;

            case 'balai':
                $template_type    = 'master-balai';
                $templae_revTable = 'm_balai';

                $dataBalai = $this->tableBalai->where('balaiid', $userId)->get()->getRow();
                $this->session->set('createDokumenByAdmin', [
                    'byAdmin_user_type'  => 'balai',
                    'byAdmin_balai_id'   => $dataBalai->balaiid,
                    'byAdmin_balai_nama' => $dataBalai->balai
                ]);
                break;
        }

        $dataTemplate = $this->templateDokumen->select('dokumen_pk_template_' . session('userData.tahun') . '.*')
            ->join('dokumen_pk_template_akses_' . session('userData.tahun'), 'dokumen_pk_template_' . session('userData.tahun') . '.id = dokumen_pk_template_akses_' . session('userData.tahun') . '.template_id', 'left')
            ->where('dokumen_pk_template_' . session('userData.tahun') . '.status', '1')
            ->where('dokumen_pk_template_akses_' . session('userData.tahun') . '.rev_id', $template_revID)
            // ->where('dokumen_pk_template.type', $template_type)
            ->where('dokumen_pk_template_akses_' . session('userData.tahun') . '.rev_table', $templae_revTable)
            ->where("deleted_at is null")
            ->groupBy('dokumen_pk_template_' . session('userData.tahun') . '.id')
            ->get()->getResult();

        if (!$dataTemplate) {
            $dataTemplate = [];
            goto globalUserTemplate;
        }

        goto returnSection;

        globalUserTemplate:
        if (isset($this->user['user_type'])) $this->templateDokumen->where('type', $this->user['user_type']);
        $dataTemplate = $this->templateDokumen->where('dokumen_pk_template.status', '1')->where("deleted_at is null")->get()->getResult();


        returnSection:
        return $this->respond([
            'templateDokumen'   => $dataTemplate,
            'templateAvailable' => count($dataTemplate) > 0 ? 'true' : 'false'
        ]);
    }



    public function template()
    {
        return $this->pageTemplate([
            'title' => 'Template Perjanjian Kinerja - Satker',
            'data' => $this->dokumenPK->groupStart()
                ->where('type', 'satker')
                ->orWhere('type', 'balai')
                ->groupEnd()
                ->where('deleted_at is NULL')->get()->getResult(),
            'defaultType' => 'satker',
        ]);
    }



    public function templateEselon2()
    {
        return $this->pageTemplate([
            'title' => 'Template Perjanjian Kinerja - Eselon 2',
            'data' => $this->dokumenPK->where('type', 'eselon2')
                ->where('deleted_at is NULL')->get()->getResult(),
            'defaultType' => 'eselon2'
        ]);
    }



    public function templateEselon1()
    {
        return $this->pageTemplate([
            'title' => 'Template Perjanjian Kinerja - Eselon 1',
            'data' => $this->dokumenPK->where('type', 'eselon1')
                ->where('deleted_at is NULL')->get()->getResult(),
            'defaultType' => 'eselon1'
        ]);
    }



    private function pageTemplate($params = [
        'title' => '',
        'data' => [],
        'defaultType' => 'datker'
    ])
    {
        $sessionYear = $this->user['tahun'];

        return view('Modules\Admin\Views\DokumenPK\template.php', [
            'title'   => $params['title'],
            'data'        => $params['data'],
            'allSatker'   => $this->tableSatker->whereNotIn('satker', ['', '1'])->get()->getResult(),
            'allBalai'    => $this->tableBalai->get()->getResult(),
            'allKegiatan' => $this->tableKegiatan->where('tahun_anggaran', $sessionYear)->get()->getResult(),
            'allProgram'  => $this->tableProgram->get()->getResult(),
            'defaultType' => $params['defaultType'],
            'sessionYear' => $sessionYear
        ]);
    }



    public function show($_id)
    {
        return $this->respond([
            'template' => $this->dokumenPK->where('id', $_id)->get()->getRow(),
            'rows'     => $this->dokumenPK_row->select('dokumen_pk_template_row_' . $this->tahun . '.*, (SELECT COUNT(dokumen_pk_template_rowrumus_' . $this->tahun . '.rowId) FROM dokumen_pk_template_rowrumus_' . $this->tahun . ' WHERE dokumen_pk_template_rowrumus_' . $this->tahun . '.rowId=dokumen_pk_template_row_' . $this->tahun . '.id) as rumusJml')->where('template_id', $_id)->orderBy('no_urut')->get()->getResult(),
            'rowRumus' => $this->dokumenPk_rowRumus->where('template_id', $_id)->get()->getResult(),
            'kegiatan' => $this->dokumenPK_kegiatan->where('template_id', $_id)->get()->getResult(),
            'info'     => $this->dokumenPK_info->where('template_id', $_id)->get()->getResult(),
            'akses'    => $this->dokumenPK_akses->where('template_id', $_id)->get()->getResult()
        ], 200);
    }



    public function createTemplate()
    {
        $input_dokumenType = $this->request->getPost('type');

        /** template */
        $this->dokumenPK->insert([
            'title'              => $this->request->getPost('title'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'kegiatan_table_ref' => $this->request->getPost('kegiatan_table_ref'),
            'info_title'         => $this->request->getPost('info_title'),
            'type'               => $input_dokumenType
        ]);
        $templateId = $this->db->insertID();
        /** end-of: template */


        /** row */
        $this->insertDokumenPK_row($this->request->getPost(), $templateId);
        /** end-of: row */


        /** kegiatan */
        $this->insertDokumenPK_kegiatan($this->request->getPost(), $templateId);
        /** end-of: kegiatan */


        /** info */
        $this->insertDokumenPK_info($this->request->getPost(), $templateId);
        /** end-of: info */


        /** Akses */
        $this->insertDokumenPK_akses($this->request->getPost(), $templateId, $input_dokumenType);
        /** end-of: Akses */

        return $this->respond([
            'status' => true
        ], 200);
    }



    public function updateTemplate()
    {
        $templateID        = $this->request->getPost('dataId');
        $input_dokumenType = $this->request->getPost('type');

        /* template */
        $this->dokumenPK->where('id', $templateID);
        $this->dokumenPK->update([
            'title'              => $this->request->getPost('title'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'kegiatan_table_ref' => $this->request->getPost('kegiatan_table_ref'),
            'info_title'         => $this->request->getPost('info_title'),
            'type'               => $input_dokumenType
        ]);
        /** end-of: template */


        /* row */
        // $this->dokumenPK_row->delete(['template_id' => $templateID]);
        // $this->dokumenPk_rowRumus->delete(['template_id' => $templateID]);
        $this->updateDokumenPK_row($this->request->getPost(), $templateID);
        /** end-of: row */


        /* kegiatan */
        $this->dokumenPK_kegiatan->delete(['template_id' => $templateID]);
        $this->insertDokumenPK_kegiatan($this->request->getPost(), $templateID);
        /** end-of: kegiatan */


        /* info */
        $this->dokumenPK_info->delete(['template_id' => $templateID]);
        $this->insertDokumenPK_info($this->request->getPost(), $templateID);
        /** end-of: info */


        /** Akses */
        $this->dokumenPK_akses->delete(['template_id' => $templateID]);
        $this->insertDokumenPK_akses($this->request->getPost(), $templateID, $input_dokumenType);
        /** end-of: Akses */


        return $this->respond([
            'status' => true,
            'input'  => $this->request->getPost()
        ], 200);
    }



    public function updateTemplateStatus()
    {
        $this->dokumenPK->where('id', $this->request->getPost('dataId'));
        $this->dokumenPK->update([
            'status' => $this->request->getPost('newStatus')
        ]);

        return $this->respond([
            'status' => true,
            'input'  => $this->request->getPost()
        ], 200);
    }



    public function removeTemplate()
    {
        $this->dokumenPK->where('id', $this->request->getPost('id'));
        $this->dokumenPK->update([
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return $this->respond([
            'status' => true,
            'input' => $this->request->getPost('id')
        ]);
    }



    public function changeStatus()
    {

        switch ($this->request->getPost('dokumenType')) {
            case 'satker':
                $this->dokumenSatker->where('id', $this->request->getPost('dataID'));

                $newStatus = $this->request->getPost('newStatus');

                $updatedData = [
                    'status'           => $newStatus,
                    'change_status_at' => date("Y-m-d H:i:s")
                ];

                $from = $this->request->getPost('dataID');

                if ($newStatus == "tolak") {
                    $updatedData['revision_message'] = $this->request->getPost('message');
                    $updatedData['reject_by']           = $this->user['idpengguna'];
                    $updatedData['reject_date']           = date("Y-m-d H:i:s");
                } else {

                    $updatedData['acc_by']           = $this->user['idpengguna'];
                    $updatedData['acc_date']           = date("Y-m-d H:i:s");
                };



                $this->dokumenSatker->update($updatedData);
                break;
            case 'hold-edit':
                $this->dokumenSatker->where('id', $this->request->getPost('dataID'));
                $newStatus = $this->request->getPost('newStatus');

                $updatedData = [
                    'status'           => $newStatus,
                    'change_status_at' => date("Y-m-d H:i:s")
                ];
                $updatedData['revision_message'] = $this->request->getPost('message');


                // var_dump($updat edData);die;

                $this->dokumenSatker->update($updatedData);
                break;
        }

        return $this->respond([
            'status' => true,
            'dataStatus' => 'setuju', //stuju||tolak
            'token' => csrf_hash(),
            'input'  => $this->request->getPost()
        ]);
    }










    private function insertDokumenPK_row($input, $templateID)
    {
        $rows = [];
        $input_rowRumus = [];
        $rowsNumber = 1000;

        foreach ($input['formTable_title'] as $key_rowTitle => $data_rowTitle) {
            $rowId = $input['formTable_idRow'][$key_rowTitle];
            array_push($rows, [
                'id'             => $rowId,
                'template_id'    => $templateID,
                'prefix_title'   => $input['formTable_prefixTitle'][$key_rowTitle],
                'title'          => $data_rowTitle,
                'target_satuan'  => $input['formTable_targetSatuan'][$key_rowTitle],
                'outcome_satuan' => $input['formTable_outcomeSatuan'][$key_rowTitle],
                'type'           => $input['formTable_type'][$key_rowTitle]
            ]);


            if ($input['formTable_rumus'][$key_rowTitle]) {
                $this->insertDokumenPK_rowRumus(explode(',', $input['formTable_idRow'][$key_rowTitle]), $rowId, $templateID);
            }
        }
        // var_dump($rows[0]['id']);die;
        $this->dokumenPK_row->insertBatch($rows);
    }

    private function updateDokumenPK_row($input, $templateID)
    {

        $rows = [];
        $input_rowRumus = [];
        $rowsNumber = 1000;
        $this->dokumenPk_rowRumus->delete(['template_id' => $templateID]);

        foreach ($input['formTable_title'] as $key_rowTitle => $data_rowTitle) {
            $rowId = $input['formTable_idRow'][$key_rowTitle];

            array_push($rows, [
                'id'             => $input['formTable_idRow'][$key_rowTitle],
                'template_id'    => $templateID,
                'prefix_title'   => $input['formTable_prefixTitle'][$key_rowTitle],
                'title'          => $data_rowTitle,
                'target_satuan'  => $input['formTable_targetSatuan'][$key_rowTitle],
                'outcome_satuan' => $input['formTable_outcomeSatuan'][$key_rowTitle],
                'type'           => $input['formTable_type'][$key_rowTitle]
            ]);


            // print_r($input);
            // die;

            // if ($input['formTable_rumus'][$key_rowTitle]) {
            //     array_push($input_rowRumus, [
            //         'rowId'     => $input['formTable_idRow'][$key_rowTitle],
            //         'rumus'     => $input['formTable_rumus'][$key_rowTitle]
            //     ]);
            //     $this->updateDokumenPK_rowRumus($input_rowRumus, $templateID);
            // }

            if ($input['formTable_rumus'][$key_rowTitle]) {
                $this->insertDokumenPK_rowRumus(explode(',', $input['formTable_rumus'][$key_rowTitle]), $rowId, $templateID);
            }
        }
        // var_dump($rows[0]['id']);die;
        $this->dokumenPK_row->updateBatch($rows, 'id');
    }



    private function insertDokumenPK_kegiatan($input, $templateID)
    {

        $records = [];
        foreach ($input['kegiatan_id'] as $key => $data) {
            array_push($records, [
                'template_id' => $templateID,
                'id'          => $data,
                'nama'        => $input['kegiatan_nama'][$key],
                'table'       => $input['kegiatan_rev'][$key]
            ]);
        }
        $this->dokumenPK_kegiatan->insertBatch($records);
    }



    private function insertDokumenPK_info($input, $templateID)
    {
        /*
        $info = array_map(function($arr) use ($templateID) {
            return [
                'template_id' => $templateID,
                'info'        => $arr,
            ];
        }, $input['info_item']);
        $this->dokumenPK_info->insertBatch($info);
        */
    }



    private function insertDokumenPK_akses($input, $templateID, $inputDokumenType)
    {
        $revTable = 'm_balai';
        switch ($inputDokumenType) {
            case 'satker':
                $revTable = 'm_satker';
                break;

            case 'eselon2':
                $revTable = 'm_satker';
                break;

            case 'eselon1':
                $revTable = 'm_satker';
                break;

            case 'balai':
                $revTable = 'm_balai';
                break;
        }

        $akses = array_map(function ($arr) use ($templateID, $revTable) {
            return [
                'template_id' => $templateID,
                'rev_id'      => $arr,
                'rev_table'   => $revTable,
            ];
        }, $input['akses']);
        $this->dokumenPK_akses->insertBatch($akses);
    }



    private function insertDokumenPK_rowRumus($input, $rowId, $templateId)
    {
        $rumus = [];


        foreach ($input as $key => $data) {

            array_push($rumus, [
                'template_id' => $templateId,
                'rumus'       => $data,
                'rowId'       => $rowId,

            ]);
        }

        $this->dokumenPk_rowRumus->insertBatch($rumus);
    }

    private function updateDokumenPK_rowRumus($input_rowRumus, $templateId)
    {
        $rumus = [];

        foreach ($input_rowRumus as $key => $data) {

            array_push($rumus, [
                'template_id' => $templateId,
                'rumus'       => $data['rumus'],
                'rowId'       => $data['rowId']
            ]);
        }
        // echo json_encode($rumus);die;

        $this->dokumenPk_rowRumus->updateBatch($rumus, 'rowId');
    }


    public function eselon1_export_rekap_excel()
    {
        // header("Content-type: application/vnd.ms-excel");
        // header("Content-disposition: attachment; filename=Rekap-progress-per-provinsi.xls");
        // header("Pragma: no-cache");
        // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        // header("Expires: 0");

        $sessionTahun = $this->user['tahun'];
        $tempData = [];
        // $dataBalai = $this->tableBalai->where('kota_penanda_tangan !=', '')->where('balaiid in (1, 2)')->get()->getResult();
        $dataBalai = $this->tableBalai->where('kota_penanda_tangan !=', '')->get()->getResult();

        foreach ($dataBalai as $keyBalai => $valueBalai) {
            $itemTemp = [
                'namaBalai' => $valueBalai->balai,
                'rowspan' => 0
            ];


            $dataSP = $this->db->query("
                SELECT 
                    dokumen_pk_template_row.*
                FROM 
                    dokumen_pk_template_akses
                    left join dokumen_pk_template on 
                        dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                    left join dokumen_pk_template_row ON
                        dokumen_pk_template.id = dokumen_pk_template_row.template_id
                where 
                    dokumen_pk_template_akses.rev_table='m_balai' 
                    and dokumen_pk_template_akses.rev_id='" . $valueBalai->balaiid . "'
                    and dokumen_pk_template.type='master-balai'
                    and dokumen_pk_template_row.type='section_title';
            ")->getResult();

            foreach ($dataSP as $keySp => $valueSp) {
                $itemTemp['sp'][$keySp]['namaSp'] = $valueSp->title;
                $itemTemp['sp'][$keySp]['rowspan'] = 0;

                $indikatorItemSpChild = true;
                $indikatorSp = $this->db->query("
                    SELECT 
                        id, title, type 
                    FROM 
                        `dokumen_pk_template_row` 
                    where 
                        template_id='" . $valueSp->template_id . "' 
                        and id > '" . $valueSp->id . "'
                ")->getResult();

                foreach ($indikatorSp as $keyIndicatorSp => $valueIndicatorSp) {
                    if ($valueIndicatorSp->type == 'section_title') $indikatorItemSpChild = false;
                    if ($indikatorItemSpChild) {
                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp] = (array) $valueIndicatorSp;
                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] = 0;

                        $rumusIndikatorSp = $this->db->query("
                        SELECT rumus FROM dokumen_pk_template_rowrumus_$this->tahun WHERE template_id='" . $valueSp->template_id . "' and rowId='" . $valueIndicatorSp->id . "'
                        ")->getResult();

                        $itemTemp['rowspan']++;
                        $itemTemp['sp'][$keySp]['rowspan']++;

                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = [];
                        foreach ($rumusIndikatorSp as $keyRumusIndikatorSp => $valueRumusIndikatorSp) {
                            // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rumus'][$keyRumusIndikatorSp]['rumus'] = $valueRumusIndikatorSp->rumus;


                            $dataSatker = $this->db->query("
                                SELECT 
                                    m_satker.*
                                FROM 
                                    dokumen_pk_template_rowrumus_$this->tahun 
                                    left join dokumen_pk_template_$this->tahun on dokumen_pk_template_rowrumus_$this->tahun.template_id = dokumen_pk_template_$this->tahun.id
                                    left join dokumen_pk_template_akses_$this->tahun on dokumen_pk_template_$this->tahun.id = dokumen_pk_template_akses_$this->tahun.template_id
                                    left join m_satker on dokumen_pk_template_akses_$this->tahun.rev_id = m_satker.satkerid
                                WHERE 
                                    dokumen_pk_template_rowrumus_$this->tahun.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                    and dokumen_pk_template_akses_$this->tahun.rev_table='m_satker'
                                    and m_satker.balaiid = '" . $valueBalai->balaiid . "';
                            ")->getResult();

                            $queryBalai = $this->db->query("
                                SELECT 
                                    m_balai.*
                                FROM 
                                dokumen_pk_template_rowrumus_$this->tahun 
                                    left join dokumen_pk_template_$this->tahun on dokumen_pk_template_rowrumus_$this->tahun.template_id = dokumen_pk_template_$this->tahun.id
                                    left join dokumen_pk_template_akses_$this->tahun on dokumen_pk_template_$this->tahun.id = dokumen_pk_template_akses_$this->tahun.template_id
                                    left join m_balai on dokumen_pk_template_akses_$this->tahun.rev_id = m_balai.balaiid
                                WHERE 
                                    dokumen_pk_template_rowrumus_$this->tahun.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                    and dokumen_pk_template_akses_$this->tahun.rev_table='m_balai'
                                    and m_balai.balaiid = '" . $valueBalai->balaiid . "';
                            ")->getResult();


                            if ($dataSatker) {
                                foreach ($dataSatker as $keyDataSatker => $valueDataSatker) {
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

                                    if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemTemp['rowspan']++;
                                        $itemTemp['sp'][$keySp]['rowspan']++;
                                    }

                                    if (array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => $valueDataSatker->satker,
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }
                                    // array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rumus'], $valueRumusIndikatorSp->rumus);

                                    $dataIndicatorSK = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row.*,
                                            dokumen_pk_template_rowrumus_$this->tahun.rumus
                                        FROM 
                                            dokumen_pk_template_akses_$this->tahun
                                            left join dokumen_pk_template_$this->tahun on 
                                                dokumen_pk_template_akses_$this->tahun.template_id = dokumen_pk_template_$this->tahun.id
                                            left join dokumen_pk_template_row_$this->tahun ON
                                                dokumen_pk_template.id = dokumen_pk_template_row_$this->tahun.template_id
                                            left join dokumen_pk_template_rowrumus_$this->tahun ON
                                                dokumen_pk_template_row_$this->tahun.id = dokumen_pk_template_rowrumus_$this->tahun.rowId
                                        where 
                                            dokumen_pk_template_akses_$this->tahun.rev_table='m_satker' 
                                            and dokumen_pk_template_akses_$this->tahun.rev_id='" . $valueDataSatker->satkerid . "'
                                            and dokumen_pk_template_rowrumus_$this->tahun.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                            and dokumen_pk_template_$this->tahun.type='satker'
                                    ")->getRow();

                                    $dataSk = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row_$this->tahun.*
                                        FROM 
                                            dokumen_pk_template_akses_$this->tahun
                                            left join dokumen_pk_template_$this->tahun on 
                                                dokumen_pk_template_akses_$this->tahun.template_id = dokumen_pk_template_$this->tahun.id
                                            left join dokumen_pk_template_row_$this->tahun ON
                                                dokumen_pk_template_$this->tahun.id = dokumen_pk_template_row_$this->tahun.template_id
                                            left join dokumen_pk_template_rowrumus_$this->tahun ON
                                                dokumen_pk_template_row_$this->tahun.id = dokumen_pk_template_rowrumus_$this->tahun.rowId
                                        where 
                                            dokumen_pk_template_akses_$this->tahun.rev_table='m_satker' 
                                            and dokumen_pk_template_akses_$this->tahun.rev_id='" . $valueDataSatker->satkerid . "'
                                            and dokumen_pk_template_$this->tahun.type='satker'
                                            and dokumen_pk_template_row_$this->tahun.type='section_title'
                                            and dokumen_pk_template_row_$this->tahun.id < '" . $dataIndicatorSK->id . "'
                                        ORDER BY dokumen_pk_template_row_$this->tahun.id DESC;
                                    ")->getRow();

                                    $findDataSatkerIndex = array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
                                    if (array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
                                            'namaSk' => $dataSk->title,
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;
                                    $findDataSKIndex = array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));
                                    $dataDokumen = $this->db->query("
                                        SELECT
                                            dokumenpk_satker_rows.target_value,
                                            dokumenpk_satker_rows.outcome_value
                                        FROM
                                            dokumenpk_satker_rows
                                            LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                                        WHERE
                                            dokumenpk_satker.template_id='" . $dataIndicatorSK->template_id . "'
                                            AND dokumenpk_satker.satkerid='" . $valueDataSatker->satkerid . "'
                                            AND dokumenpk_satker.balaiid='" . $valueDataSatker->balaiid . "'
                                            AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                                            AND dokumenpk_satker.status='setuju'
                                            AND dokumenpk_satker.deleted_at is null
                                            AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK->id . "'
                                    ")->getRow();
                                    array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
                                        'title'         => $dataIndicatorSK->title,
                                        'output'        => $dataDokumen->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK->target_satuan,
                                        'outcome'       => $dataDokumen->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK->outcome_satuan
                                    ]);
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
                                }
                            } else if ($queryBalai) {
                                foreach ($queryBalai as $keyDataBalai => $valueDataBalai) {
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

                                    if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemTemp['rowspan']++;
                                        $itemTemp['sp'][$keySp]['rowspan']++;
                                    }

                                    if (array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => '-',
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }



                                    $dataIndicatorSK2 = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row.*,
                                            dokumen_pk_template_rowrumus.rumus
                                        FROM 
                                            dokumen_pk_template_akses
                                            left join dokumen_pk_template on 
                                            dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                            left join dokumen_pk_template_row ON
                                            dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                            left join dokumen_pk_template_rowrumus ON
                                            dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                        where 
                                            dokumen_pk_template_akses.rev_table='m_balai' 
                                            and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
                                            and dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                            and dokumen_pk_template.type='master-balai'
                                        ")->getRow();


                                    $dataSkBalai = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row.*
                                        FROM 
                                            dokumen_pk_template_akses
                                            left join dokumen_pk_template on 
                                                dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                            left join dokumen_pk_template_row ON
                                                dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                            left join dokumen_pk_template_rowrumus ON
                                                dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                        where 
                                            dokumen_pk_template_akses.rev_table='m_balai' 
                                            and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
                                            and dokumen_pk_template.type='balai'
                                            and dokumen_pk_template_row.type='section_title'
                                            and dokumen_pk_template_row.id < '" . $dataIndicatorSK2->id . "'
                                        ORDER BY dokumen_pk_template_row.id DESC;
                                    ")->getRow();


                                    $findDataSatkerIndex = array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
                                    // var_dump($dataSkBalai->title);
                                    // die;
                                    if (array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
                                            'namaSk' => '-',
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;


                                    $findDataSKIndex = array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));

                                    $dataDokumen2 = $this->db->query("
                                        SELECT
                                            dokumenpk_satker_rows.target_value,
                                            dokumenpk_satker_rows.outcome_value
                                        FROM
                                            dokumenpk_satker_rows
                                            LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                                        WHERE
                                            dokumenpk_satker.template_id='" . $dataIndicatorSK2->template_id . "'
                                            AND dokumenpk_satker.balaiid='" . $valueDataBalai->balaiid . "'
                                            AND dokumenpk_satker.satkerid is null
                                            AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                                            AND dokumenpk_satker.status='setuju'
                                            AND dokumenpk_satker.deleted_at is null
                                            AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK2->id . "'
                                    ")->getRow();

                                    // var_dump($dataDokumen2);
                                    // die;

                                    array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
                                        // 'title'         => $dataIndicatorSK2->title,
                                        'title'         => '-',
                                        'output'        => $dataDokumen2->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK2->target_satuan,
                                        'outcome'       => $dataDokumen2->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK2->outcome_satuan
                                    ]);
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
                                }
                            }
                            // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = $dataSatker->satker ?? null;
                        }
                    }
                }
            }

            array_push($tempData, $itemTemp);
        }

        // echo json_encode($tempData); exit;
        // $filename = 'Rekap Data Eselon 1';
        // header("Content-type: application/vnd.ms-excel");
        // header("Content-disposition: attachment; filename=" . $filename . ".xls");
        // header("Pragma: no-cache");
        // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        // header("Expires: 0");

        return view("Modules\Admin\Views\DokumenPk\Rekap\Rekap-Eselon1", [
            'data' => $tempData,
            'tahun' => $sessionTahun
        ]);
    }

    public function rekapitulasi()
    {
        $sessionTahun = $this->user['tahun'];
        $rowPaket = [];
        $satkerList = [];

        $q = "
        SELECT
        a.id,
        a.template_id,
        b.template_row_id,
        a.satkerid,
        a.balaiid,
        d.title AS indikator,
        b.target_value,
        d.target_satuan,
        b.outcome_value,
        d.outcome_satuan,
        a.status,
        a.is_revision_same_year,
        a.pihak1_initial,
        b.target_sat
    FROM
        dokumenpk_satker a
    LEFT JOIN dokumenpk_satker_rows b ON b.dokumen_id = a.id
    LEFT JOIN dokumen_pk_template_row_$sessionTahun d ON b.template_row_id = d.id
    WHERE
        (a.created_at = (
            SELECT MAX(created_at)
            FROM dokumenpk_satker
            WHERE 
                a.status != 'revision'
                AND balaiid = a.balaiid
                AND satkerid = a.satkerid
                AND tahun = $sessionTahun 
                AND ISNULL(deleted_at)
        ) OR
        (a.dokumen_type = 'balai'
        AND a.tahun = $sessionTahun 
        AND ISNULL(a.deleted_at)
        AND (a.balaiid, a.created_at) IN (
            SELECT balaiid, MAX(created_at)
            FROM dokumenpk_satker
            WHERE 
            a.status != 'revision'
            AND dokumen_type = 'balai'
                AND tahun = $sessionTahun 
                AND ISNULL(deleted_at)
            GROUP BY balaiid
        )))
    ORDER BY
    d.id, a.pihak1_initial;
     
        ";


        $queryResult = $this->db->query($q)->getResultArray();

        $data = [];
        $targetValue = '';


        foreach ($queryResult as $row) {

            if ($row['satkerid'] != null) {
                // jika satker
                $hasilPaket = $this->db->table('dokumenpk_satker_paket')
                    ->select('dokumenpk_satker_paket.*,dokumenpk_satker.satkerid')
                    ->join('dokumenpk_satker', 'dokumenpk_satker.id = dokumenpk_satker_paket.dokumen_id', 'left')
                    ->where('dokumen_id', $row['id'])
                    ->where('template_row_id', $row['template_row_id'])
                    ->get()->getResult();

                $satker = instansi_name($row['satkerid'])->nama_instansi;
                $targetValue =  $row['target_value'];
                $outputSatuan = $row['target_sat'] ?? trim(explode(";", $row['target_satuan'])[0]);
                $outcomeValue = $row['outcome_value'];
                $outcomeSatuan = $row['outcome_satuan'];
            } else {
                $satker = instansi_name($row['balaiid'])->nama_instansi;
                $targetValue =  $row['target_value'];
                $outputSatuan = $row['target_sat'] ?? trim(explode(";", $row['target_satuan'])[0]);
                $outcomeSatuan = '';
                $outcomeValue = 0;


                $templateRowRumus = $this->dokumenPk_rowRumus->select('rumus')->where(['template_id' => $row['template_id'], 'rowId' =>  $row['template_row_id']])->get()->getResult();
                foreach ($templateRowRumus as $key => $dataRumus) {
                    $rumus = $this->dokumenSatker->select(
                        'dokumenpk_satker_rows.outcome_value, dokumenpk_satker_rows.target_value, dokumenpk_satker_rows.template_row_id,
                                    dokumenpk_satker.satkerid,dokumenpk_satker.id,dokumenpk_satker_rows.target_sat,target_satuan'
                    )
                        ->join('dokumenpk_satker_rows', 'dokumenpk_satker.id = dokumenpk_satker_rows.dokumen_id', 'left')
                        ->join('dokumen_pk_template_row_' . session('userData.tahun'), "(dokumenpk_satker_rows.template_row_id=dokumen_pk_template_row_" . session('userData.tahun') . ".id)", 'left')
                        ->join('dokumen_pk_template_rowrumus_' . session('userData.tahun'), "(dokumenpk_satker.template_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".template_id AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".rowId)", 'left')
                        ->where('dokumen_pk_template_rowrumus_' . session('userData.tahun') . '.rumus', $dataRumus->rumus)
                        ->where('dokumenpk_satker.balaiid', $row['balaiid'])
                        ->where('dokumenpk_satker.status', 'setuju')
                        ->where('dokumenpk_satker.satkerid is not null')
                        ->where('dokumenpk_satker.deleted_at is null')
                        ->where('dokumenpk_satker.tahun', $this->user['tahun'])
                        ->where('dokumenpk_satker_rows.is_checked', '1')
                        ->get()->getResult();

                    $outcomeRumus = 0;
                    $outputRumus = 0;

                    foreach ($rumus as $keyOutcome => $dataOutput) {
                        $outputRumus += $dataOutput ? ($dataOutput->target_value != '' ? $dataOutput->target_value : 0) : 0;
                        $outcomeSatuan = $dataOutput->target_sat ?? $dataOutput->target_satuan;

                        if (!in_array($dataOutput->satkerid, $satkerList)) {
                            array_push($satkerList,  $dataOutput->satkerid);
                        }

                        $hasilPaket = $this->dokumenSatker_paket->select('dokumenpk_satker_paket.*,dokumenpk_satker.satkerid')
                            ->join('dokumenpk_satker', 'dokumenpk_satker.id = dokumenpk_satker_paket.dokumen_id', 'left')
                            ->where('dokumen_id',  $dataOutput->id)
                            ->where('template_row_id',  $dataOutput->template_row_id)
                            ->get()->getResult();

                        $targetSatuan =  $dataOutput->target_sat ??  $dataOutput->target_satuan;


                        $rowPaket = array_merge($rowPaket, $hasilPaket);
                    }
                    if ($outcomeValue == '' && $outcomeRumus > 0) $outcomeValue = 0;


                    if ($outputRumus > 0) {
                        $outcomeValue  += $outputRumus;
                    }
                }
            }




            $indikator = [
                'indikator' => $row['indikator'],
                'rowId' => $row['id'],
                'template_row_id' => $row['template_row_id'],
                'target_value' =>  str_replace('.', ',', $targetValue),
                'target_satuan' => $outputSatuan,
                'outcome_value' => str_replace('.', ',', $outcomeValue),
                'outcome_satuan' => $outcomeSatuan,
                'is_revision_same_year' => $row['is_revision_same_year'],
                'status' => $row['status'] != 'setuju' ? 'Belum' : 'Sudah',
                'paket' => $hasilPaket
            ];

            if (!isset($data[$satker])) {
                $data[$satker] = ['satker' => $satker, 'indikators' => [], 'idDoc' => $row['id'], 'satkerid' => $row['satkerid']];
            }

            $data[$satker]['indikators'][] = $indikator;
        }

        return view('Modules\Admin\Views\DokumenPK\rekapitulasi.php', [
            'data' => $data,
            'tahun' => $sessionTahun
        ]);
    }

    public function rekapitulasi_export_excel()
    {

        $sessionTahun = $this->user['tahun'];
        $satkerList = array();
        $rowPaket = array();
        $currentSheetNumber = 1;

        $q = "
        SELECT
        a.id,
        a.template_id,
        b.template_row_id,
        a.satkerid,
        a.balaiid,
        d.title AS indikator,
        b.target_value,
        d.target_satuan,
        b.outcome_value,
        d.outcome_satuan,
        a.status,
        a.is_revision_same_year,
        a.pihak1_initial,
        b.target_sat
    FROM
        dokumenpk_satker a
    LEFT JOIN dokumenpk_satker_rows b ON b.dokumen_id = a.id
    LEFT JOIN dokumen_pk_template_row_$sessionTahun d ON b.template_row_id = d.id
    WHERE
        (a.created_at = (
            SELECT MAX(created_at)
            FROM dokumenpk_satker
            WHERE 
                balaiid = a.balaiid
                AND satkerid = a.satkerid
                AND tahun = $sessionTahun 
                AND ISNULL(deleted_at)
        ) OR
        (a.dokumen_type = 'balai'
        AND a.tahun = $sessionTahun 
        AND ISNULL(a.deleted_at)
        AND (a.balaiid, a.created_at) IN (
            SELECT balaiid, MAX(created_at)
            FROM dokumenpk_satker
            WHERE dokumen_type = 'balai'
                AND tahun = $sessionTahun 
                AND ISNULL(deleted_at)
            GROUP BY balaiid
        )))
    ORDER BY
    d.id, a.pihak1_initial;
     
        ";

        $queryResult = $this->db->query($q)->getResultArray();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap');

        // Tambahkan sheet baru untuk data paket
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex($currentSheetNumber);
        $sheetPaket = $spreadsheet->getActiveSheet();
        $sheetPaket->setTitle('Daftar Paket'); // Ganti 'SheetPaket' dengan nama yang diinginkan


        // Set header row
        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Unit Kerja')
            ->setCellValue('C1', 'Indikator')
            ->setCellValue('D1', 'Target')
            ->setCellValue('E1', 'Satuan')
            ->setCellValue('F1', 'Outcome')
            ->setCellValue('G1', 'Satuan')
            ->setCellValue('H1', 'Jenis PK')
            ->setCellValue('I1', 'Verifikasi');

        // Set header paket
        $sheetPaket->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Balai')
            ->setCellValue('C1', 'Satker')
            ->setCellValue('D1', 'Indikator')
            ->setCellValue('E1', 'Paket')
            ->setCellValue('F1', 'Output')
            ->setCellValue('G1', 'Output Satuan')
            ->setCellValue('H1', 'Outcome')
            ->setCellValue('I1', 'Outcome  Satuan');


        // Fill data from query into rows
        $rowNumber = 2;
        $PaketrowNumber = 2;
        foreach ($queryResult as $row) {

            if ($row['satkerid'] != null) {
                // jika satker
                $satker = instansi_name($row['satkerid'])->nama_instansi;
                $targetValue =  $row['target_value'];
                $outputSatuan = $row['target_sat'] ?? trim(explode(";", $row['target_satuan'])[0]);
                $outcomeValue = $row['outcome_value'];
                $outcomeSatuan = $row['outcome_satuan'];
            } else {
                $satker = instansi_name($row['balaiid'])->nama_instansi;
                $targetValue =  $row['target_value'];
                $outputSatuan = $row['target_sat'] ?? trim(explode(";", $row['target_satuan'])[0]);
                $outcomeSatuan = '';
                $outcomeValue = 0;


                $templateRowRumus = $this->dokumenPk_rowRumus->select('rumus')->where(['template_id' => $row['template_id'], 'rowId' =>  $row['template_row_id']])->get()->getResult();
                foreach ($templateRowRumus as $key => $dataRumus) {
                    $rumus = $this->dokumenSatker->select(
                        'dokumenpk_satker_rows.outcome_value, dokumenpk_satker_rows.target_value, dokumenpk_satker_rows.template_row_id,
                                    dokumenpk_satker.satkerid,dokumenpk_satker.id,dokumenpk_satker_rows.target_sat,target_satuan'
                    )
                        ->join('dokumenpk_satker_rows', 'dokumenpk_satker.id = dokumenpk_satker_rows.dokumen_id', 'left')
                        ->join('dokumen_pk_template_row_' . session('userData.tahun'), "(dokumenpk_satker_rows.template_row_id=dokumen_pk_template_row_" . session('userData.tahun') . ".id)", 'left')
                        ->join('dokumen_pk_template_rowrumus_' . session('userData.tahun'), "(dokumenpk_satker.template_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".template_id AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".rowId)", 'left')
                        ->where('dokumen_pk_template_rowrumus_' . session('userData.tahun') . '.rumus', $dataRumus->rumus)
                        ->where('dokumenpk_satker.balaiid', $row['balaiid'])
                        ->where('dokumenpk_satker.status', 'setuju')
                        ->where('dokumenpk_satker.satkerid is not null')
                        ->where('dokumenpk_satker.deleted_at is null')
                        ->where('dokumenpk_satker.tahun', $this->user['tahun'])
                        ->where('dokumenpk_satker_rows.is_checked', '1')
                        ->get()->getResult();

                    $outcomeRumus = 0;
                    $outputRumus = 0;

                    foreach ($rumus as $keyOutcome => $dataOutput) {
                        $outputRumus += $dataOutput ? ($dataOutput->target_value != '' ? $dataOutput->target_value : 0) : 0;
                        $outcomeSatuan = $dataOutput->target_sat ?? $dataOutput->target_satuan;

                        if (!in_array($dataOutput->satkerid, $satkerList)) {
                            array_push($satkerList, $dataOutput->satkerid);
                        }

                        $hasilPaket = $this->dokumenSatker_paket->select('dokumenpk_satker_paket.*,dokumenpk_satker.satkerid')
                            ->join('dokumenpk_satker', 'dokumenpk_satker.id = dokumenpk_satker_paket.dokumen_id', 'left')
                            ->where('dokumen_id', $dataOutput->id)
                            ->where('template_row_id', $dataOutput->template_row_id)
                            ->get()->getResult();

                        $targetSatuan = $dataOutput->target_sat ?? $dataOutput->target_satuan;


                        $rowPaket = array_merge($rowPaket, $hasilPaket);
                    }
                    if ($outcomeValue == '' && $outcomeRumus > 0) $outcomeValue = 0;


                    if ($outputRumus > 0) {
                        $outcomeValue  += $outputRumus;
                    }
                }
            }


            $sheet->setCellValue('A' . $rowNumber, ($rowNumber - 1))
                ->setCellValue('B' . $rowNumber, $satker)
                ->setCellValue('C' . $rowNumber, $row['indikator'])
                ->setCellValue('D' . $rowNumber,  $targetValue)
                ->setCellValue('E' . $rowNumber, $outputSatuan)
                ->setCellValue('F' . $rowNumber,  $outcomeValue)
                ->setCellValue('G' . $rowNumber, $outcomeSatuan)
                ->setCellValue('H' . $rowNumber, $row['is_revision_same_year'] > 1 ? "Revisi" : "Awal")
                ->setCellValue('I' . $rowNumber, $row['status'] != 'setuju' ? 'Belum' : 'Sudah');

            $rowNumber++;


            $hasilPaketSheet = $this->dokumenSatker_paket->select("dokumenpk_satker_paket.*,dokumenpk_satker.satkerid,dokumenpk_satker.balaiid")
                ->join('dokumenpk_satker', 'dokumenpk_satker.id = dokumenpk_satker_paket.dokumen_id', 'left')
                ->where('dokumen_id', $row['id'])
                ->where('template_row_id', $row['template_row_id'])
                ->get()->getResult();

            if ($hasilPaketSheet) {

                foreach ($hasilPaketSheet as $indx => $value) {

                    $sheetPaket->setCellValue('A' .  $PaketrowNumber, ($PaketrowNumber - 1))
                        ->setCellValue('B' .  $PaketrowNumber, instansi_name($value->balaiid)->nama_instansi)
                        ->setCellValue('C' .  $PaketrowNumber, instansi_name($value->satkerid)->nama_instansi)
                        ->setCellValue('D' .  $PaketrowNumber,  indikatorPK_name($value->template_row_id, $this->user['tahun']))
                        ->setCellValue('E' .  $PaketrowNumber, paket_name($value->idpaket, $this->user['tahun']))
                        ->setCellValue('F' .  $PaketrowNumber, $value->target_value)
                        ->setCellValue('G' .  $PaketrowNumber,  $value->target_unit)
                        ->setCellValue('H' .  $PaketrowNumber, $value->output_value)
                        ->setCellValue('I' .  $PaketrowNumber,  $value->output_unit);


                    $PaketrowNumber++;
                }
            }
        }

        try {

            // Save spreadsheet as Excel file
            $writer = new Xlsx($spreadsheet);
            $filename = $this->user['tahun'] . '-Rekapitulasi PK-' . date('Y-m-d-His');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            // Handle error, misalnya:
            echo 'Error: ' . $e->getMessage();
        }
    }





    // public function export_rekap_excel_all()
    // {
    //     $sessionTahun = $this->user['tahun'];
    //     $tempData = [];
    //     $tempDataBalai = [];
    //     $tempSKPD = [];
    //     $tempSatpus = [];
    //     $tempEselon = [];
    //     $tempBalaiTeknik = [];
    //     // $dataBalai = $this->tableBalai->where('kota_penanda_tangan !=', '')->where('balaiid in (1, 2)')->get()->getResult();
    //     $dataBalai = $this->tableBalai->where('kota_penanda_tangan !=', '')->get()->getResult();
    //     $dataSKPD = $this->tableBalai->where('balaiid', 98)->get()->getRowArray();
    //     $dataSatpus = $this->tableBalai->where('balaiid', 99)->get()->getRowArray();
    //     $dataBalaiTeknik = $this->tableBalai->where('balaiid', 97)->get()->getRow();

    //     //Balai dan Satker
    //     foreach ($dataBalai as $keyBalai => $valueBalai) {

    //         $itemTemp = [
    //             'namaBalai' => $valueBalai->balai,
    //             'rowspan' => 0
    //         ];

    //         $itemBalai = [
    //             'nama_balai' => $valueBalai->balai,
    //             'rowspan' => 0
    //         ];


    //         $dataSP = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_balai' 
    //                 and dokumen_pk_template_akses.rev_id='" . $valueBalai->balaiid . "'
    //                 and dokumen_pk_template.type='master-balai'
    //                 and dokumen_pk_template_row.type='section_title';
    //         ")->getResult();

    //         foreach ($dataSP as $keySp => $valueSp) {
    //             $itemTemp['sp'][$keySp]['namaSp'] = $valueSp->title;
    //             $itemTemp['sp'][$keySp]['rowspan'] = 0;
    //             $itemBalai['sp'][$keySp]['nama_sp'] = $valueSp->title;
    //             $itemBalai['sp'][$keySp]['rowspan'] = 0;

    //             $indikatorItemSpChild = true;
    //             $indikatorSp = $this->db->query("
    //             SELECT 
    //             id, title, type 
    //             FROM 
    //             `dokumen_pk_template_row` 
    //             where 
    //             template_id='" . $valueSp->template_id . "' 
    //             and id > '" . $valueSp->id . "'
    //             ")->getResult();

    //             foreach ($indikatorSp as $keyIndicatorSp => $valueIndicatorSp) {
    //                 if ($valueIndicatorSp->type == 'section_title') $indikatorItemSpChild = false;
    //                 if ($indikatorItemSpChild) {
    //                     $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp] = (array) $valueIndicatorSp;
    //                     $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] = 0;
    //                     $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp] = (array) $valueIndicatorSp;
    //                     $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] = 0;

    //                     $rumusIndikatorSp = $this->db->query("
    //                     SELECT rumus FROM dokumen_pk_template_rowrumus WHERE template_id='" . $valueSp->template_id . "' and rowId='" . $valueIndicatorSp->id . "'
    //                     ")->getResult();

    //                     $itemTemp['rowspan']++;
    //                     $itemTemp['sp'][$keySp]['rowspan']++;
    //                     $itemBalai['rowspan']++;
    //                     $itemBalai['sp'][$keySp]['rowspan']++;

    //                     $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = [];
    //                     $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = [];
    //                     foreach ($rumusIndikatorSp as $keyRumusIndikatorSp => $valueRumusIndikatorSp) {
    //                         // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rumus'][$keyRumusIndikatorSp]['rumus'] = $valueRumusIndikatorSp->rumus;

    //                         $dataSatker = $this->db->query("
    //                             SELECT 
    //                                 m_satker.*
    //                             FROM 
    //                                 dokumen_pk_template_rowrumus 
    //                                 left join dokumen_pk_template on dokumen_pk_template_rowrumus.template_id = dokumen_pk_template.id
    //                                 left join dokumen_pk_template_akses on dokumen_pk_template.id = dokumen_pk_template_akses.template_id
    //                                 left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid
    //                             WHERE 
    //                                 dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
    //                                 and dokumen_pk_template_akses.rev_table='m_satker'
    //                                 and m_satker.balaiid = '" . $valueBalai->balaiid . "';
    //                         ")->getResult();

    //                         $queryBalai = $this->db->query("
    //                             SELECT 
    //                                 m_balai.*
    //                             FROM 
    //                             dokumen_pk_template_rowrumus 
    //                                 left join dokumen_pk_template on dokumen_pk_template_rowrumus.template_id = dokumen_pk_template.id
    //                                 left join dokumen_pk_template_akses on dokumen_pk_template.id = dokumen_pk_template_akses.template_id
    //                                 left join m_balai on dokumen_pk_template_akses.rev_id = m_balai.balaiid
    //                             WHERE 
    //                                 dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
    //                                 and dokumen_pk_template_akses.rev_table='m_balai'
    //                                 and m_balai.balaiid = '" . $valueBalai->balaiid . "';
    //                         ")->getResult();


    //                         if ($dataSatker) {
    //                             foreach ($dataSatker as $keyDataSatker => $valueDataSatker) {
    //                                 $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;
    //                                 $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

    //                                 if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
    //                                     $itemTemp['rowspan']++;
    //                                     $itemTemp['sp'][$keySp]['rowspan']++;
    //                                 }

    //                                 if ($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
    //                                     $itemBalai['rowspan']++;
    //                                     $itemBalai['sp'][$keySp]['rowspan']++;
    //                                 }

    //                                 if (array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
    //                                     array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
    //                                         'namaSatker' => $valueDataSatker->satker,
    //                                         'rowspan' => 0,
    //                                         // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                                         'sk' => []
    //                                     ]);
    //                                 }

    //                                 if (array_search($valueDataSatker->satker, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
    //                                     array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
    //                                         'namaSatker' => '-',
    //                                         'rowspan' => 0,
    //                                         // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                                         'sk' => []
    //                                     ]);
    //                                 }
    //                                 // array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rumus'], $valueRumusIndikatorSp->rumus);

    //                                 $dataIndicatorSK = $this->db->query("
    //                                     SELECT 
    //                                         dokumen_pk_template_row.*,
    //                                         dokumen_pk_template_rowrumus.rumus
    //                                     FROM 
    //                                         dokumen_pk_template_akses
    //                                         left join dokumen_pk_template on 
    //                                             dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                                         left join dokumen_pk_template_row ON
    //                                             dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                                         left join dokumen_pk_template_rowrumus ON
    //                                             dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //                                     where 
    //                                         dokumen_pk_template_akses.rev_table='m_satker' 
    //                                         and dokumen_pk_template_akses.rev_id='" . $valueDataSatker->satkerid . "'
    //                                         and dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
    //                                         and dokumen_pk_template.type='satker'
    //                                 ")->getRow();

    //                                 $dataSk = $this->db->query("
    //                                     SELECT 
    //                                         dokumen_pk_template_row.*
    //                                     FROM 
    //                                         dokumen_pk_template_akses
    //                                         left join dokumen_pk_template on 
    //                                             dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                                         left join dokumen_pk_template_row ON
    //                                             dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                                         left join dokumen_pk_template_rowrumus ON
    //                                             dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //                                     where 
    //                                         dokumen_pk_template_akses.rev_table='m_satker' 
    //                                         and dokumen_pk_template_akses.rev_id='" . $valueDataSatker->satkerid . "'
    //                                         and dokumen_pk_template.type='satker'
    //                                         and dokumen_pk_template_row.type='section_title'
    //                                         and dokumen_pk_template_row.id < '" . $dataIndicatorSK->id . "'
    //                                     ORDER BY dokumen_pk_template_row.id DESC;
    //                                 ")->getRow();

    //                                 $findDataSatkerIndex = array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
    //                                 $findDataSatkerIndex2 = array_search($valueDataSatker->satker, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));

    //                                 if (array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
    //                                     array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
    //                                         'namaSk' => $dataSk->title,
    //                                         'rowspan' => 0,
    //                                         'indikatorSk' => []
    //                                     ]);
    //                                 }

    //                                 if (array_search($dataSk->title, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], 'namaSk')) === FALSE) {
    //                                     array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], [
    //                                         'namaSk' => '-',
    //                                         'rowspan' => 0,
    //                                         'indikatorSk' => []
    //                                     ]);
    //                                 }
    //                                 $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;
    //                                 $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['rowspan']++;

    //                                 $findDataSKIndex = array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));

    //                                 $findDataSKIndex2 = array_search($dataSk->title, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], 'namaSk'));

    //                                 $dataDokumen = $this->db->query("
    //                                     SELECT
    //                                         dokumenpk_satker_rows.target_value,
    //                                         dokumenpk_satker_rows.outcome_value,
    //                                         dokumenpk_satker_rows.is_checked
    //                                     FROM
    //                                         dokumenpk_satker_rows
    //                                         LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
    //                                     WHERE
    //                                         dokumenpk_satker.template_id='" . $dataIndicatorSK->template_id . "'
    //                                         AND dokumenpk_satker.satkerid='" . $valueDataSatker->satkerid . "'
    //                                         AND dokumenpk_satker.balaiid='" . $valueDataSatker->balaiid . "'
    //                                         AND dokumenpk_satker.tahun='" . $sessionTahun . "'
    //                                         AND dokumenpk_satker.status='setuju'
    //                                         AND dokumenpk_satker.deleted_at is null
    //                                         AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK->id . "'
    //                                 ")->getRow();
    //                                 array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
    //                                     'title'         => $dataIndicatorSK->title,
    //                                     'output'        => $dataDokumen->target_value ?? '-',
    //                                     'outputSatuan'  => $dataIndicatorSK->target_satuan,
    //                                     'outcome'       => $dataDokumen->outcome_value ?? '-',
    //                                     'outcomeSatuan' => $dataIndicatorSK->outcome_satuan,
    //                                     'is_checked'    => $dataDokumen->is_checked ?? '-'
    //                                 ]);
    //                                 $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;

    //                                 array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'][$findDataSKIndex2]['indikatorSk'], [
    //                                     'title'         => '-',
    //                                     'output'        => $dataDokumen->target_value ?? '-',
    //                                     'outputSatuan'  => $dataIndicatorSK->target_satuan,
    //                                     'outcome'       => $dataDokumen->outcome_value ?? '-',
    //                                     'outcomeSatuan' => $dataIndicatorSK->outcome_satuan,
    //                                     'is_checked'    => $dataDokumen->is_checked ?? '-'
    //                                 ]);
    //                                 $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'][$findDataSKIndex2]['rowspan']++;
    //                             }
    //                         } else if ($queryBalai) {
    //                             foreach ($queryBalai as $keyDataBalai => $valueDataBalai) {
    //                                 $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;
    //                                 $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

    //                                 if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
    //                                     $itemTemp['rowspan']++;
    //                                     $itemTemp['sp'][$keySp]['rowspan']++;
    //                                 }

    //                                 if ($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
    //                                     $itemBalai['rowspan']++;
    //                                     $itemBalai['sp'][$keySp]['rowspan']++;
    //                                 }

    //                                 if (array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
    //                                     array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
    //                                         'namaSatker' => '-',
    //                                         'rowspan' => 0,
    //                                         // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                                         'sk' => []
    //                                     ]);
    //                                 }

    //                                 if (array_search($valueDataBalai->balai, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
    //                                     array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
    //                                         'namaSatker' => '-',
    //                                         'rowspan' => 0,
    //                                         // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                                         'sk' => []
    //                                     ]);
    //                                 }

    //                                 // var_dump($valueDataBalai->balaiid);
    //                                 // die;


    //                                 $dataIndicatorSK2 = $this->db->query("
    //                                     SELECT 
    //                                         dokumen_pk_template_row.*,
    //                                         dokumen_pk_template_rowrumus.rumus
    //                                     FROM 
    //                                         dokumen_pk_template_akses
    //                                         left join dokumen_pk_template on 
    //                                         dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                                         left join dokumen_pk_template_row ON
    //                                         dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                                         left join dokumen_pk_template_rowrumus ON
    //                                         dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //                                     where 
    //                                         dokumen_pk_template_akses.rev_table='m_balai' 
    //                                         and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
    //                                         and dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
    //                                         and dokumen_pk_template.type='master-balai'
    //                                 ")->getRow();


    //                                 $dataSkBalai = $this->db->query("
    //                                     SELECT 
    //                                         dokumen_pk_template_row.*
    //                                     FROM 
    //                                         dokumen_pk_template_akses
    //                                         left join dokumen_pk_template on 
    //                                             dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                                         left join dokumen_pk_template_row ON
    //                                             dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                                         left join dokumen_pk_template_rowrumus ON
    //                                             dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //                                     where 
    //                                         dokumen_pk_template_akses.rev_table='m_balai' 
    //                                         and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
    //                                         and dokumen_pk_template.type='balai'
    //                                         and dokumen_pk_template_row.type='section_title'
    //                                         and dokumen_pk_template_row.id < '" . $dataIndicatorSK2->id . "'
    //                                     ORDER BY dokumen_pk_template_row.id DESC;
    //                                 ")->getRow();


    //                                 $findDataSatkerIndex = array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
    //                                 $findDataBalaiIndex = array_search($valueDataBalai->balai, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));

    //                                 if (array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
    //                                     array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
    //                                         'namaSk' => '-',
    //                                         'rowspan' => 0,
    //                                         'indikatorSk' => []
    //                                     ]);
    //                                 }

    //                                 if (array_search(isset($dataSkBalai->title), array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], 'namaSk')) === FALSE) {
    //                                     array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], [
    //                                         'namaSk' => '-',
    //                                         'rowspan' => 0,
    //                                         'indikatorSk' => []
    //                                     ]);
    //                                 }

    //                                 $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;
    //                                 $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['rowspan']++;


    //                                 $findDataSKIndex = array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));

    //                                 $findDataSKIndexBalai = array_search(isset($dataSkBalai->title), array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], 'namaSk'));

    //                                 $dataDokumen2 = $this->db->query("
    //                                     SELECT
    //                                         dokumenpk_satker_rows.target_value,
    //                                         dokumenpk_satker_rows.outcome_value,
    //                                         dokumenpk_satker_rows.is_checked
    //                                     FROM
    //                                         dokumenpk_satker_rows
    //                                         LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
    //                                     WHERE
    //                                         dokumenpk_satker.template_id='" . $dataIndicatorSK2->template_id . "'
    //                                         AND dokumenpk_satker.balaiid='" . $valueDataBalai->balaiid . "'
    //                                         AND dokumenpk_satker.satkerid is null
    //                                         AND dokumenpk_satker.tahun='" . $sessionTahun . "'
    //                                         AND dokumenpk_satker.status='setuju'
    //                                         AND dokumenpk_satker.deleted_at is null
    //                                         AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK2->id . "'
    //                                 ")->getRow();


    //                                 array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
    //                                     // 'title'         => $dataIndicatorSK2->title,
    //                                     'title'         => '-',
    //                                     'output'        => $dataDokumen2->target_value ?? '-',
    //                                     'outputSatuan'  => $dataIndicatorSK2->target_satuan,
    //                                     'outcome'       => $dataDokumen2->outcome_value ?? '-',
    //                                     'outcomeSatuan' => $dataIndicatorSK2->outcome_satuan,
    //                                     'is_checked'    => $dataDokumen2->is_checked ?? '-'
    //                                 ]);

    //                                 array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'][$findDataSKIndexBalai]['indikatorSk'], [
    //                                     // 'title'         => $dataIndicatorSK2->title,
    //                                     'title'         => '-',
    //                                     'output'        => $dataDokumen2->target_value ?? '-',
    //                                     'outputSatuan'  => $dataIndicatorSK2->target_satuan,
    //                                     'outcome'       => $dataDokumen2->outcome_value ?? '-',
    //                                     'outcomeSatuan' => $dataIndicatorSK2->outcome_satuan,
    //                                     'is_checked'    => $dataDokumen2->is_checked ?? '-'
    //                                 ]);

    //                                 $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
    //                                 $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
    //                             }
    //                         }
    //                         // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = $dataSatker->satker ?? null;
    //                     }
    //                 }
    //             }
    //         }

    //         array_push($tempData, $itemTemp);
    //         array_push($tempDataBalai, $itemBalai);
    //         // echo json_encode($itemBalai); die;
    //     }


    //     //SKPD TP-OP
    //     $itemSKPD = [
    //         'nama_balai' => $dataSKPD['balai'],
    //         'rowspan' => 0
    //     ];

    //     $dataSK_SKPD = $this->db->query("
    //         SELECT 
    //             dokumen_pk_template_row.*
    //         FROM 
    //             dokumen_pk_template_akses
    //             left join dokumen_pk_template on 
    //                 dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //             left join dokumen_pk_template_row ON
    //                 dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //         where 
    //             dokumen_pk_template_akses.rev_table='m_satker'
    //             and dokumen_pk_template_row.template_id = '11'
    //             and dokumen_pk_template.type='satker'
    //             and dokumen_pk_template_row.type='section_title';
    //     ")->getResult();

    //     foreach ($dataSK_SKPD as $keySK_SKPD => $valueSkSKPD) {
    //         $itemSKPD['sp'][$keySK_SKPD]['namaSp'] = '-';
    //         $itemSKPD['sp'][$keySK_SKPD]['rowspan'] = 0;

    //         $indikatorItemSkChild = true;
    //         $indikatorSkSKPD = $this->db->query("
    //         SELECT 
    //             id, title, type 
    //         FROM 
    //             `dokumen_pk_template_row` 
    //         where 
    //             template_id='" . $valueSkSKPD->template_id . "' 
    //             and id > '" . $valueSkSKPD->id . "'
    //         ")->getResult();

    //         foreach ($indikatorSkSKPD as $keyIndikatorSkSKPD => $valueIndikatorSkSKPD) {
    //             $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD] = (array) $valueIndikatorSkSKPD;
    //             $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['rowspan'] = 0;

    //             $satkerSKPD = $this->db->query("
    //                 SELECT 
    //                     m_satker.* 
    //                 FROM 
    //                     dokumen_pk_template_akses left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
    //                 WHERE 
    //                     dokumen_pk_template_akses.rev_table='m_satker' 
    //                     and m_satker.balaiid ='" . $dataSKPD['balaiid'] . "' 
    //                 ORDER BY 
    //                     `satkerid` ASC 
    //             ")->getResult();

    //             $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'] = [];
    //             foreach ($satkerSKPD as $keySatkerSKPD => $valueSatkerSKPD) {
    //                 $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['rowspan']++;

    //                 if ($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['rowspan'] > 1) {
    //                     $itemSKPD['rowspan']++;
    //                     $itemSKPD['sp'][$keySK_SKPD]['rowspan']++;
    //                 }


    //                 if (array_search($valueSatkerSKPD->satker, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'], 'nama_satker')) === FALSE) {
    //                     array_push($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'], [
    //                         'nama_satker' => $valueSatkerSKPD->satker ?? '-',
    //                         'rowspan' => 0,
    //                         // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                         'sk' => []
    //                     ]);
    //                 }

    //                 $findDataSatkerSKPD = array_search($valueSatkerSKPD->satker, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'], 'nama_satker'));
    //                 if (array_search($valueSkSKPD->title, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'], 'nama_sk')) === FALSE) {
    //                     array_push($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'], [
    //                         'namaSk' => $valueSkSKPD->title,
    //                         'rowspan' => 0,
    //                         'indikatorSk' => []
    //                     ]);
    //                 }

    //                 $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['rowspan']++;
    //                 $findDataSKIndexSKPD = array_search($valueSkSKPD->title, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'], 'namaSk'));

    //                 $dataDokumenSKPD = $this->db->query("
    //                     SELECT
    //                         dokumenpk_satker_rows.target_value,
    //                         dokumenpk_satker_rows.outcome_value,
    //                         dokumenpk_satker_rows.is_checked
    //                     FROM
    //                         dokumenpk_satker_rows
    //                         LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
    //                     WHERE
    //                         dokumenpk_satker.template_id='" . $valueSkSKPD->template_id . "'
    //                         AND dokumenpk_satker.satkerid='" . $valueSatkerSKPD->satkerid . "'
    //                         AND dokumenpk_satker.balaiid='" . $dataSKPD['balaiid'] . "'
    //                         AND dokumenpk_satker.tahun='" . $sessionTahun . "'
    //                         AND dokumenpk_satker.status='setuju'
    //                         AND dokumenpk_satker.deleted_at is null;
    //                 ")->getRow();

    //                 array_push($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'][$findDataSKIndexSKPD]['indikatorSk'], [
    //                     'title'         => $valueIndikatorSkSKPD->title,
    //                     'output'        => $dataDokumenSKPD->target_value ?? '-',
    //                     'outputSatuan'  => $valueSkSKPD->target_satuan,
    //                     'outcome'       => $dataDokumenSKPD->outcome_value ?? '-',
    //                     'outcomeSatuan' => $valueSkSKPD->outcome_satuan,
    //                     'is_checked'    => $dataDokumenSKPD->is_checked ?? '-'
    //                 ]);
    //                 $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'][$findDataSKIndexSKPD]['rowspan']++;
    //             }
    //         }
    //         array_push($tempSKPD, $itemSKPD);
    //     }


    //     //Satker Pusat
    //     $itemSatpus = [
    //         'nama_balai' => $dataSatpus['balai'],
    //         'rowspan' => 0,
    //         'namaSp' => '-',
    //         'indikator_sp' => '-'
    //     ];

    //     $dataSatker_Satpus = $this->db->query("
    //         SELECT 
    //             m_satker.* 
    //         FROM 
    //             dokumen_pk_template_akses 
    //             left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
    //         WHERE 
    //             dokumen_pk_template_akses.rev_table='m_satker' 
    //             and m_satker.satkerid IN (403477,654098)
    //         ORDER BY 
    //             `satkerid` ASC;
    //     ")->getResult();

    //     $itemSatpus['satker'] = [];
    //     foreach ($dataSatker_Satpus as $keySatker_Satpus => $valueSatker_Satpus) {
    //         $itemSatpus['rowspan']++;
    //         if (array_search($valueSatker_Satpus->satker, array_column($itemSatpus['satker'], 'nama_satker')) === FALSE) {
    //             array_push($itemSatpus['satker'], [
    //                 'nama_satker' => $valueSatker_Satpus->satker,
    //                 'rowspan' => 0,
    //                 // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                 'sk' => []
    //             ]);
    //         }

    //         $dataIndikatorSk_Satker = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*,
    //                 dokumen_pk_template_rowrumus.rumus
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                 left join dokumen_pk_template_rowrumus ON
    //                     dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_satker' 
    //                 and dokumen_pk_template_akses.rev_id='" . $valueSatker_Satpus->satkerid . "'
    //                 and dokumen_pk_template_row.type='form'
    //                 and dokumen_pk_template.type='satker';
    //         ")->getRow();

    //         $dataSK_Satpus = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_satker'
    //                 and dokumen_pk_template_akses.rev_id = '" . $valueSatker_Satpus->satkerid . "'
    //                 and dokumen_pk_template.type='satker'
    //                 and dokumen_pk_template_row.prefix_title='SK'
    //                 and dokumen_pk_template_row.type='section_title'
    //             ORDER BY 
    //                 dokumen_pk_template_row.id DESC;
    //         ")->getRow();

    //         $findDataSatkerIndexSatpus = array_search($valueSatker_Satpus->satker, array_column($itemSatpus['satker'], 'nama_satker'));
    //         $findDataSKIndexEselon = array_search($dataSK_Satpus->title, array_column($itemSatpus['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));
    //         if (array_search($dataSK_Satpus->title, array_column($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'], 'namaSk')) === FALSE) {
    //             array_push($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'], [
    //                 'namaSk' => $dataSK_Satpus->title,
    //                 'rowspan' => 0,
    //                 'indikatorSk' => []
    //             ]);
    //         }

    //         $itemSatpus['satker'][$findDataSatkerIndexSatpus]['rowspan']++;
    //         $findDataSKIndexSatpus = array_search($dataSK_Satpus->title, array_column($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'], 'namaSk'));
    //         $dataDokumen_Satker = $this->db->query("
    //             SELECT
    //                 dokumenpk_satker_rows.target_value,
    //                 dokumenpk_satker_rows.outcome_value,
    //                 dokumenpk_satker_rows.is_checked
    //             FROM
    //                 dokumenpk_satker_rows
    //                 LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
    //             WHERE
    //                 dokumenpk_satker.template_id='" . $dataIndikatorSk_Satker->template_id . "'
    //                 AND dokumenpk_satker.satkerid='" . $valueSatker_Satpus->satkerid . "'
    //                 AND dokumenpk_satker.balaiid='" . $dataSatpus['balai'] . "'
    //                 AND dokumenpk_satker.tahun='" . $sessionTahun . "'
    //                 AND dokumenpk_satker.status='setuju'
    //                 AND dokumenpk_satker.deleted_at is null
    //                 AND dokumenpk_satker_rows.template_row_id='" . $dataIndikatorSk_Satker->id . "'
    //         ")->getRow();

    //         array_push($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'][$findDataSKIndexSatpus]['indikatorSk'], [
    //             'title'         => $dataIndikatorSk_Satker->title,
    //             'output'        => $dataDokumen_Satker->target_value ?? '-',
    //             'outputSatuan'  => $dataIndikatorSk_Satker->target_satuan,
    //             'outcome'       => $dataDokumen_Satker->outcome_value ?? '-',
    //             'outcomeSatuan' => $dataIndikatorSk_Satker->outcome_satuan,
    //             'is_checked'    => $dataDokumen_Satker->is_checked ?? '-'
    //         ]);
    //         $itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'][$findDataSKIndexSatpus]['rowspan']++;
    //     }
    //     array_push($tempSatpus, $itemSatpus);


    //     //Eselon2
    //     $itemEselon = [
    //         'nama_balai' => '-',
    //         'rowspan' => 0,
    //         'namaSp' => '-',
    //         'indikator_sp' => '-',
    //     ];

    //     $dataSatker_Eselon = $this->db->query("
    //         SELECT 
    //             m_satker.* 
    //         FROM 
    //             dokumen_pk_template_akses left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
    //         WHERE 
    //             dokumen_pk_template_akses.rev_table='m_satker' 
    //             and m_satker.grup_jabatan ='eselon2'
    //         ORDER BY 
    //             `satkerid` ASC;
    //     ")->getResult();

    //     $itemEselon['satker'] = [];
    //     foreach ($dataSatker_Eselon as $keySatker_Eselon => $valueSatker_Eselon) {
    //         if (array_search($valueSatker_Eselon->satker, array_column($itemEselon['satker'], 'nama_satker')) === FALSE) {
    //             array_push($itemEselon['satker'], [
    //                 'nama_satker' => $valueSatker_Eselon->satker,
    //                 'rowspan' => 0,
    //                 // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                 'sk' => []
    //             ]);
    //         }

    //         $dataSk_Eselon = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_satker'
    //                 and dokumen_pk_template_akses.rev_id = '" . $valueSatker_Eselon->satkerid . "'
    //                 and dokumen_pk_template.type='eselon2'
    //                 and dokumen_pk_template_row.prefix_title='SK'
    //                 and dokumen_pk_template_row.type='section_title'
    //             ORDER BY 
    //                 dokumen_pk_template_row.id DESC;
    //         ")->getRow();

    //         $dataIndikatorSk_Eselon = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*,
    //                 dokumen_pk_template_rowrumus.rumus
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                 left join dokumen_pk_template_rowrumus ON
    //                     dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_satker' 
    //                 and dokumen_pk_template_akses.rev_id='" . $valueSatker_Eselon->satkerid . "'
    //                 and dokumen_pk_template_row.type='form'
    //                 and dokumen_pk_template.type='eselon2';
    //         ")->getResult();

    //         $findDataSatkerIndexEselon = array_search($valueSatker_Eselon->satker, array_column($itemEselon['satker'], 'nama_satker'));

    //         if (array_search($dataSk_Eselon->title, array_column($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'], 'namaSk')) === FALSE) {
    //             array_push($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'], [
    //                 'namaSk' => $dataSk_Eselon->title,
    //                 'rowspan' => 0,
    //                 'indikatorSk' => []
    //             ]);
    //         }

    //         foreach ($dataIndikatorSk_Eselon as $keyIndikatorSk_Eselon => $valueIndikatorSk_Eselon) {
    //             $itemEselon['rowspan']++;
    //             $itemEselon['satker'][$findDataSatkerIndexEselon]['rowspan']++;
    //             $findDataSKIndexEselon = array_search($dataSk_Eselon->title, array_column($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'], 'namaSk'));
    //             $dataDokumen_Eselon = $this->db->query("
    //             SELECT
    //                 dokumenpk_satker_rows.target_value,
    //                 dokumenpk_satker_rows.outcome_value,
    //                 dokumenpk_satker_rows.is_checked
    //             FROM
    //                 dokumenpk_satker_rows
    //                 LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
    //             WHERE
    //                 dokumenpk_satker.template_id='" . $valueIndikatorSk_Eselon->template_id . "'
    //                 AND dokumenpk_satker.satkerid='" . $valueSatker_Eselon->satkerid . "'
    //                 AND dokumenpk_satker.balaiid='" . $valueBalai->balaiid . "'
    //                 AND dokumenpk_satker.tahun='" . $sessionTahun . "'
    //                 AND dokumenpk_satker.status='setuju'
    //                 AND dokumenpk_satker.deleted_at is null
    //                 AND dokumenpk_satker_rows.template_row_id='" . $valueIndikatorSk_Eselon->id . "'
    //             ")->getRow();

    //             array_push($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'][$findDataSKIndexEselon]['indikatorSk'], [
    //                 'title'         => $valueIndikatorSk_Eselon->title,
    //                 'output'        => $dataDokumen_Eselon->target_value ?? '-',
    //                 'outputSatuan'  => $valueIndikatorSk_Eselon->target_satuan,
    //                 'outcome'       => $dataDokumen_Eselon->outcome_value ?? '-',
    //                 'outcomeSatuan' => $valueIndikatorSk_Eselon->outcome_satuan,
    //                 'is_checked'    => $dataDokumen_Eselon->is_checked ?? '-'
    //             ]);
    //             $itemEselon['satker'][$findDataSatkerIndexEselon]['sk'][$findDataSKIndexEselon]['rowspan']++;
    //         }
    //     }
    //     array_push($tempEselon, $itemEselon);


    //     //Balai Teknik
    //     $itemBaltek = [
    //         'nama_balai' => $dataBalaiTeknik->balai,
    //         'rowspan' => 0,
    //         'namaSp' => '-',
    //         'indikator_sp' => '-'

    //     ];

    //     $dataSatker_Baltek = $this->db->query("
    //         SELECT 
    //             m_satker.* 
    //         FROM 
    //             dokumen_pk_template_akses left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
    //         WHERE 
    //             dokumen_pk_template_akses.rev_table='m_satker' 
    //             and m_satker.balaiid ='97'
    //         ORDER BY 
    //             `satkerid` ASC;
    //     ")->getResult();

    //     $itemBaltek['satker'] = [];
    //     foreach ($dataSatker_Baltek as $keySatker_Baltek => $valueSatker_Baltek) {

    //         if (array_search($valueSatker_Baltek->satker, array_column($itemBaltek['satker'], 'nama_satker')) === FALSE) {
    //             array_push($itemBaltek['satker'], [
    //                 'nama_satker' => $valueSatker_Baltek->satker,
    //                 'rowspan' => 0,
    //                 // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                 'sk' => []
    //             ]);
    //         }

    //         $dataSk_Baltek = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_satker'
    //                 and dokumen_pk_template_akses.rev_id = '" . $valueSatker_Baltek->satkerid . "'
    //                 and dokumen_pk_template.type='satker'
    //                 and dokumen_pk_template_row.type='section_title'
    //             ORDER BY 
    //                 dokumen_pk_template_row.id ASC;
    //         ")->getResult();


    //         $findDataSatkerIndexBaltek = array_search($valueSatker_Baltek->satker, array_column($itemBaltek['satker'], 'nama_satker'));
    //         $itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'] = [];
    //         foreach ($dataSk_Baltek as $keySk_Baltek => $valueSk_Baltek) {
    //             $itemBaltek['rowspan']++;
    //             $itemBaltek['satker'][$findDataSatkerIndexBaltek]['rowspan']++;

    //             if (array_search($valueSk_Baltek->title, array_column($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'], 'namaSk')) === FALSE) {
    //                 array_push($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'], [
    //                     'namaSk' => $valueSk_Baltek->title,
    //                     'rowspan' => 0,
    //                     'indikatorSk' => []
    //                 ]);
    //             }

    //             $dataIndikatorSk_Baltek = $this->db->query("
    //                 SELECT 
    //                     dokumen_pk_template_row.*,
    //                     dokumen_pk_template_rowrumus.rumus
    //                 FROM 
    //                     dokumen_pk_template_akses
    //                     left join dokumen_pk_template on 
    //                         dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                     left join dokumen_pk_template_row ON
    //                         dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                     left join dokumen_pk_template_rowrumus ON
    //                         dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //                 where 
    //                     dokumen_pk_template_akses.rev_table='m_satker' 
    //                     and dokumen_pk_template_akses.rev_id='" . $valueSatker_Baltek->satkerid . "'
    //                     and dokumen_pk_template_row.type='form'
    //                     and dokumen_pk_template_row.id > '" . $valueSk_Baltek->id . "'
    //                     and dokumen_pk_template.type='satker';
    //             ")->getRow();


    //             $findDataSKIndexBaltek = array_search($valueSk_Baltek->title, array_column($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'], 'namaSk'));
    //             $dataDokumen_Baltek = $this->db->query("
    //                 SELECT
    //                     dokumenpk_satker_rows.target_value,
    //                     dokumenpk_satker_rows.outcome_value,
    //                     dokumenpk_satker_rows.is_checked
    //                 FROM
    //                     dokumenpk_satker_rows
    //                     LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
    //                 WHERE
    //                     dokumenpk_satker.template_id='" . $dataIndikatorSk_Baltek->template_id . "'
    //                     AND dokumenpk_satker.satkerid='" . $valueSatker_Baltek->satkerid . "'
    //                     AND dokumenpk_satker.balaiid='" . $dataBalaiTeknik->balaiid . "'
    //                     AND dokumenpk_satker.tahun='" . $sessionTahun . "'
    //                     AND dokumenpk_satker.status='setuju'
    //                     AND dokumenpk_satker.deleted_at is null
    //                     AND dokumenpk_satker_rows.template_row_id='" . $dataIndikatorSk_Baltek->id . "'
    //             ")->getRow();

    //             array_push($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'][$findDataSKIndexBaltek]['indikatorSk'], [
    //                 'title'         => $dataIndikatorSk_Baltek->title,
    //                 'output'        => $dataDokumen_Baltek->target_value ?? '-',
    //                 'outputSatuan'  => $dataIndikatorSk_Baltek->target_satuan,
    //                 'outcome'       => $dataDokumen_Baltek->outcome_value ?? '-',
    //                 'outcomeSatuan' => $dataIndikatorSk_Baltek->outcome_satuan,
    //                 'is_checked'    => $dataDokumen_Baltek->is_checked ?? '-'
    //             ]);
    //             $itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'][$findDataSKIndexBaltek]['rowspan']++;
    //         }
    //     }
    //     array_push($tempBalaiTeknik, $itemBaltek);

    //     $filename = 'Rekapitulasi Data Semua';
    //     header("Content-type: application/vnd.ms-excel");
    //     header("Content-disposition: attachment; filename=" . $filename . ".xls");
    //     header("Pragma: no-cache");
    //     header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    //     header("Expires: 0");

    //     // echo json_encode($tempData); exit;
    //     return view('Modules\Admin\Views\DokumenPK\Rekap\Rekap-Semua.php', [
    //         'title' => "Perjanjian Kinerja Arsip",
    //         'data' => $tempData,
    //         'databalai' => $tempDataBalai,
    //         'dataskpd' => $tempSKPD,
    //         'datasatpus' => $tempSatpus,
    //         'dataeselon2' => $tempEselon,
    //         'databaltek' => $tempBalaiTeknik,
    //         'nama_balai' => $dataSKPD['balai'],
    //         'tahun' => $sessionTahun
    //     ]);
    // }

    public function export_rekap_excel_satker()
    {
        $sessionTahun = $this->user['tahun'];
        $tempData = [];
        $tempDataBalai = [];
        $dataBalai = $this->tableBalai->where('kota_penanda_tangan !=', '')->get()->getResult();
        //Balai dan Satker
        foreach ($dataBalai as $keyBalai => $valueBalai) {

            $itemTemp = [
                'namaBalai' => $valueBalai->balai,
                'rowspan' => 0
            ];

            $itemBalai = [
                'nama_balai' => $valueBalai->balai,
                'rowspan' => 0
            ];


            $dataSP = $this->db->query("
                SELECT 
                    dokumen_pk_template_row.*
                FROM 
                    dokumen_pk_template_akses
                    left join dokumen_pk_template on 
                        dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                    left join dokumen_pk_template_row ON
                        dokumen_pk_template.id = dokumen_pk_template_row.template_id
                where 
                    dokumen_pk_template_akses.rev_table='m_balai' 
                    and dokumen_pk_template_akses.rev_id='" . $valueBalai->balaiid . "'
                    and dokumen_pk_template.type='master-balai'
                    and dokumen_pk_template_row.type='section_title';
            ")->getResult();

            foreach ($dataSP as $keySp => $valueSp) {
                $itemTemp['sp'][$keySp]['namaSp'] = $valueSp->title;
                $itemTemp['sp'][$keySp]['rowspan'] = 0;
                $itemBalai['sp'][$keySp]['nama_sp'] = $valueSp->title;
                $itemBalai['sp'][$keySp]['rowspan'] = 0;

                $indikatorItemSpChild = true;
                $indikatorSp = $this->db->query("
                SELECT 
                id, title, type 
                FROM 
                `dokumen_pk_template_row` 
                where 
                template_id='" . $valueSp->template_id . "' 
                and id > '" . $valueSp->id . "'
                ")->getResult();

                foreach ($indikatorSp as $keyIndicatorSp => $valueIndicatorSp) {
                    if ($valueIndicatorSp->type == 'section_title') $indikatorItemSpChild = false;
                    if ($indikatorItemSpChild) {
                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp] = (array) $valueIndicatorSp;
                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] = 0;
                        $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp] = (array) $valueIndicatorSp;
                        $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] = 0;

                        $rumusIndikatorSp = $this->db->query("
                        SELECT rumus FROM dokumen_pk_template_rowrumus WHERE template_id='" . $valueSp->template_id . "' and rowId='" . $valueIndicatorSp->id . "'
                        ")->getResult();

                        $itemTemp['rowspan']++;
                        $itemTemp['sp'][$keySp]['rowspan']++;
                        $itemBalai['rowspan']++;
                        $itemBalai['sp'][$keySp]['rowspan']++;

                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = [];
                        $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = [];
                        foreach ($rumusIndikatorSp as $keyRumusIndikatorSp => $valueRumusIndikatorSp) {
                            // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rumus'][$keyRumusIndikatorSp]['rumus'] = $valueRumusIndikatorSp->rumus;

                            $dataSatker = $this->db->query("
                                SELECT 
                                    m_satker.*
                                FROM 
                                    dokumen_pk_template_rowrumus 
                                    left join dokumen_pk_template on dokumen_pk_template_rowrumus.template_id = dokumen_pk_template.id
                                    left join dokumen_pk_template_akses on dokumen_pk_template.id = dokumen_pk_template_akses.template_id
                                    left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid
                                WHERE 
                                    dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                    and dokumen_pk_template_akses.rev_table='m_satker'
                                    and m_satker.balaiid = '" . $valueBalai->balaiid . "';
                            ")->getResult();

                            $queryBalai = $this->db->query("
                                SELECT 
                                    m_balai.*
                                FROM 
                                dokumen_pk_template_rowrumus 
                                    left join dokumen_pk_template on dokumen_pk_template_rowrumus.template_id = dokumen_pk_template.id
                                    left join dokumen_pk_template_akses on dokumen_pk_template.id = dokumen_pk_template_akses.template_id
                                    left join m_balai on dokumen_pk_template_akses.rev_id = m_balai.balaiid
                                WHERE 
                                    dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                    and dokumen_pk_template_akses.rev_table='m_balai'
                                    and m_balai.balaiid = '" . $valueBalai->balaiid . "';
                            ")->getResult();


                            if ($dataSatker) {
                                foreach ($dataSatker as $keyDataSatker => $valueDataSatker) {
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

                                    if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemTemp['rowspan']++;
                                        $itemTemp['sp'][$keySp]['rowspan']++;
                                    }

                                    if ($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemBalai['rowspan']++;
                                        $itemBalai['sp'][$keySp]['rowspan']++;
                                    }

                                    if (array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => $valueDataSatker->satker,
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }

                                    if (array_search($valueDataSatker->satker, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => '-',
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }
                                    // array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rumus'], $valueRumusIndikatorSp->rumus);

                                    $dataIndicatorSK = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row.*,
                                            dokumen_pk_template_rowrumus.rumus
                                        FROM 
                                            dokumen_pk_template_akses
                                            left join dokumen_pk_template on 
                                                dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                            left join dokumen_pk_template_row ON
                                                dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                            left join dokumen_pk_template_rowrumus ON
                                                dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                        where 
                                            dokumen_pk_template_akses.rev_table='m_satker' 
                                            and dokumen_pk_template_akses.rev_id='" . $valueDataSatker->satkerid . "'
                                            and dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                            and dokumen_pk_template.type='satker'
                                    ")->getRow();

                                    $dataSk = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row.*
                                        FROM 
                                            dokumen_pk_template_akses
                                            left join dokumen_pk_template on 
                                                dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                            left join dokumen_pk_template_row ON
                                                dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                            left join dokumen_pk_template_rowrumus ON
                                                dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                        where 
                                            dokumen_pk_template_akses.rev_table='m_satker' 
                                            and dokumen_pk_template_akses.rev_id='" . $valueDataSatker->satkerid . "'
                                            and dokumen_pk_template.type='satker'
                                            and dokumen_pk_template_row.type='section_title'
                                            and dokumen_pk_template_row.id < '" . $dataIndicatorSK->id . "'
                                        ORDER BY dokumen_pk_template_row.id DESC;
                                    ")->getRow();

                                    $findDataSatkerIndex = array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
                                    $findDataSatkerIndex2 = array_search($valueDataSatker->satker, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));

                                    if (array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
                                            'namaSk' => $dataSk->title,
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }

                                    if (array_search($dataSk->title, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], [
                                            'namaSk' => '-',
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['rowspan']++;

                                    $findDataSKIndex = array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));

                                    $findDataSKIndex2 = array_search($dataSk->title, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], 'namaSk'));

                                    $dataDokumen = $this->db->query("
                                        SELECT
                                            dokumenpk_satker_rows.target_value,
                                            dokumenpk_satker_rows.outcome_value,
                                            dokumenpk_satker_rows.is_checked
                                        FROM
                                            dokumenpk_satker_rows
                                            LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                                        WHERE
                                            dokumenpk_satker.template_id='" . $dataIndicatorSK->template_id . "'
                                            AND dokumenpk_satker.satkerid='" . $valueDataSatker->satkerid . "'
                                            AND dokumenpk_satker.balaiid='" . $valueDataSatker->balaiid . "'
                                            AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                                            AND dokumenpk_satker.status='setuju'
                                            AND dokumenpk_satker.deleted_at is null
                                            AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK->id . "'
                                    ")->getRow();
                                    array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
                                        'title'         => $dataIndicatorSK->title,
                                        'output'        => $dataDokumen->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK->target_satuan,
                                        'outcome'       => $dataDokumen->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK->outcome_satuan,
                                        'is_checked'    => $dataDokumen->is_checked ?? '-'
                                    ]);
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;

                                    array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'][$findDataSKIndex2]['indikatorSk'], [
                                        'title'         => '-',
                                        'output'        => $dataDokumen->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK->target_satuan,
                                        'outcome'       => $dataDokumen->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK->outcome_satuan,
                                        'is_checked'    => $dataDokumen->is_checked ?? '-'
                                    ]);
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'][$findDataSKIndex2]['rowspan']++;
                                }
                            } else if ($queryBalai) {
                                foreach ($queryBalai as $keyDataBalai => $valueDataBalai) {
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

                                    if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemTemp['rowspan']++;
                                        $itemTemp['sp'][$keySp]['rowspan']++;
                                    }

                                    if ($itemBalai['sp'][$keySp]['indikatorSp'][$keyRumusIndikatorSp]['rowspan'] > 1) {
                                        $itemBalai['rowspan']++;
                                        $itemBalai['sp'][$keySp]['rowspan']++;
                                    }

                                    if (array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => '-',
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }

                                    if (array_search($valueDataBalai->balai, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => '-',
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }

                                    // var_dump($valueDataBalai->balaiid);
                                    // die;


                                    $dataIndicatorSK2 = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row.*,
                                            dokumen_pk_template_rowrumus.rumus
                                        FROM 
                                            dokumen_pk_template_akses
                                            left join dokumen_pk_template on 
                                            dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                            left join dokumen_pk_template_row ON
                                            dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                            left join dokumen_pk_template_rowrumus ON
                                            dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                        where 
                                            dokumen_pk_template_akses.rev_table='m_balai' 
                                            and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
                                            and dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                            and dokumen_pk_template.type='master-balai'
                                    ")->getRow();


                                    $dataSkBalai = $this->db->query("
                                        SELECT 
                                            dokumen_pk_template_row.*
                                        FROM 
                                            dokumen_pk_template_akses
                                            left join dokumen_pk_template on 
                                                dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                            left join dokumen_pk_template_row ON
                                                dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                            left join dokumen_pk_template_rowrumus ON
                                                dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                        where 
                                            dokumen_pk_template_akses.rev_table='m_balai' 
                                            and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
                                            and dokumen_pk_template.type='balai'
                                            and dokumen_pk_template_row.type='section_title'
                                            and dokumen_pk_template_row.id < '" . $dataIndicatorSK2->id . "'
                                        ORDER BY dokumen_pk_template_row.id DESC;
                                    ")->getRow();


                                    $findDataSatkerIndex = array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
                                    $findDataBalaiIndex = array_search($valueDataBalai->balai, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));

                                    if (array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
                                            'namaSk' => '-',
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }

                                    if (array_search(isset($dataSkBalai->title), array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], [
                                            'namaSk' => '-',
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }

                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['rowspan']++;


                                    $findDataSKIndex = array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));

                                    $findDataSKIndexBalai = array_search(isset($dataSkBalai->title), array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], 'namaSk'));

                                    $dataDokumen2 = $this->db->query("
                                        SELECT
                                            dokumenpk_satker_rows.target_value,
                                            dokumenpk_satker_rows.outcome_value,
                                            dokumenpk_satker_rows.is_checked
                                        FROM
                                            dokumenpk_satker_rows
                                            LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                                        WHERE
                                            dokumenpk_satker.template_id='" . $dataIndicatorSK2->template_id . "'
                                            AND dokumenpk_satker.balaiid='" . $valueDataBalai->balaiid . "'
                                            AND dokumenpk_satker.satkerid is null
                                            AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                                            AND dokumenpk_satker.status='setuju'
                                            AND dokumenpk_satker.deleted_at is null
                                            AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK2->id . "'
                                    ")->getRow();


                                    array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
                                        // 'title'         => $dataIndicatorSK2->title,
                                        'title'         => '-',
                                        'output'        => $dataDokumen2->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK2->target_satuan,
                                        'outcome'       => $dataDokumen2->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK2->outcome_satuan,
                                        'is_checked'    => $dataDokumen2->is_checked ?? '-'
                                    ]);

                                    array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'][$findDataSKIndexBalai]['indikatorSk'], [
                                        // 'title'         => $dataIndicatorSK2->title,
                                        'title'         => '-',
                                        'output'        => $dataDokumen2->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK2->target_satuan,
                                        'outcome'       => $dataDokumen2->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK2->outcome_satuan,
                                        'is_checked'    => $dataDokumen2->is_checked ?? '-'
                                    ]);

                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
                                }
                            }
                            // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = $dataSatker->satker ?? null;
                        }
                    }
                }
            }

            array_push($tempData, $itemTemp);
            array_push($tempDataBalai, $itemBalai);
            // echo json_encode($itemBalai); die;
        }

        $filename = 'Rekap Data Satker';
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        return view('Modules\Admin\Views\DokumenPK\Rekap\Rekap-Satker.php', [
            'title' => "Perjanjian Kinerja Arsip",
            'data' => $tempData,
            'tahun' => $sessionTahun
        ]);
    }

    public function export_rekap_excel_balai()
    {
        $sessionTahun = $this->user['tahun'];
        $tempDataBalai = [];
        $tempData = [];
        $dataBalai = $this->tableBalai->where('kota_penanda_tangan !=', '')->get()->getResult();
        //Balai dan Satker
        foreach ($dataBalai as $keyBalai => $valueBalai) {

            $itemTemp = [
                'namaBalai' => $valueBalai->balai,
                'rowspan' => 0
            ];

            $itemBalai = [
                'nama_balai' => $valueBalai->balai,
                'rowspan' => 0
            ];


            $dataSP = $this->db->query("
               SELECT 
                   dokumen_pk_template_row.*
               FROM 
                   dokumen_pk_template_akses
                   left join dokumen_pk_template on 
                       dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                   left join dokumen_pk_template_row ON
                       dokumen_pk_template.id = dokumen_pk_template_row.template_id
               where 
                   dokumen_pk_template_akses.rev_table='m_balai' 
                   and dokumen_pk_template_akses.rev_id='" . $valueBalai->balaiid . "'
                   and dokumen_pk_template.type='master-balai'
                   and dokumen_pk_template_row.type='section_title';
           ")->getResult();

            foreach ($dataSP as $keySp => $valueSp) {
                $itemTemp['sp'][$keySp]['namaSp'] = $valueSp->title;
                $itemTemp['sp'][$keySp]['rowspan'] = 0;
                $itemBalai['sp'][$keySp]['nama_sp'] = $valueSp->title;
                $itemBalai['sp'][$keySp]['rowspan'] = 0;

                $indikatorItemSpChild = true;
                $indikatorSp = $this->db->query("
               SELECT 
               id, title, type 
               FROM 
               `dokumen_pk_template_row` 
               where 
               template_id='" . $valueSp->template_id . "' 
               and id > '" . $valueSp->id . "'
    
               ")->getResult();

                foreach ($indikatorSp as $keyIndicatorSp => $valueIndicatorSp) {
                    if ($valueIndicatorSp->type == 'section_title') $indikatorItemSpChild = false;
                    if ($indikatorItemSpChild) {
                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp] = (array) $valueIndicatorSp;
                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] = 0;
                        $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp] = (array) $valueIndicatorSp;
                        $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] = 0;

                        $rumusIndikatorSp = $this->db->query("
                        SELECT rumus FROM dokumen_pk_template_rowrumus WHERE template_id='" . $valueSp->template_id . "' and rowId='" . $valueIndicatorSp->id . "'
                    ")->getResult();

                        $itemTemp['rowspan']++;
                        $itemTemp['sp'][$keySp]['rowspan']++;
                        $itemBalai['rowspan']++;
                        $itemBalai['sp'][$keySp]['rowspan']++;

                        $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = [];
                        $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = [];
                        foreach ($rumusIndikatorSp as $keyRumusIndikatorSp => $valueRumusIndikatorSp) {
                            // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rumus'][$keyRumusIndikatorSp]['rumus'] = $valueRumusIndikatorSp->rumus;

                            $dataSatker = $this->db->query("
                               SELECT 
                                   m_satker.*
                               FROM 
                                   dokumen_pk_template_rowrumus 
                                   left join dokumen_pk_template on dokumen_pk_template_rowrumus.template_id = dokumen_pk_template.id
                                   left join dokumen_pk_template_akses on dokumen_pk_template.id = dokumen_pk_template_akses.template_id
                                   left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid
                               WHERE 
                                   dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                   and dokumen_pk_template_akses.rev_table='m_satker'
                                   and m_satker.balaiid = '" . $valueBalai->balaiid . "';
                           ")->getResult();

                            $queryBalai = $this->db->query("
                               SELECT 
                                   m_balai.*
                               FROM 
                               dokumen_pk_template_rowrumus 
                                   left join dokumen_pk_template on dokumen_pk_template_rowrumus.template_id = dokumen_pk_template.id
                                   left join dokumen_pk_template_akses on dokumen_pk_template.id = dokumen_pk_template_akses.template_id
                                   left join m_balai on dokumen_pk_template_akses.rev_id = m_balai.balaiid
                               WHERE 
                                   dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                   and dokumen_pk_template_akses.rev_table='m_balai'
                                   and m_balai.balaiid = '" . $valueBalai->balaiid . "';
                           ")->getResult();


                            if ($dataSatker) {
                                foreach ($dataSatker as $keyDataSatker => $valueDataSatker) {
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

                                    if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemTemp['rowspan']++;
                                        $itemTemp['sp'][$keySp]['rowspan']++;
                                    }

                                    if ($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemBalai['rowspan']++;
                                        $itemBalai['sp'][$keySp]['rowspan']++;
                                    }

                                    if (array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => $valueDataSatker->satker,
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }

                                    if (array_search($valueDataSatker->satker, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => '-',
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }
                                    // array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rumus'], $valueRumusIndikatorSp->rumus);

                                    $dataIndicatorSK = $this->db->query("
                                       SELECT 
                                           dokumen_pk_template_row.*,
                                           dokumen_pk_template_rowrumus.rumus
                                       FROM 
                                           dokumen_pk_template_akses
                                           left join dokumen_pk_template on 
                                               dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                           left join dokumen_pk_template_row ON
                                               dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                           left join dokumen_pk_template_rowrumus ON
                                               dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                       where 
                                           dokumen_pk_template_akses.rev_table='m_satker' 
                                           and dokumen_pk_template_akses.rev_id='" . $valueDataSatker->satkerid . "'
                                           and dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                           and dokumen_pk_template.type='satker'
                                   ")->getRow();

                                    $dataSk = $this->db->query("
                                       SELECT 
                                           dokumen_pk_template_row.*
                                       FROM 
                                           dokumen_pk_template_akses
                                           left join dokumen_pk_template on 
                                               dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                           left join dokumen_pk_template_row ON
                                               dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                           left join dokumen_pk_template_rowrumus ON
                                               dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                       where 
                                           dokumen_pk_template_akses.rev_table='m_satker' 
                                           and dokumen_pk_template_akses.rev_id='" . $valueDataSatker->satkerid . "'
                                           and dokumen_pk_template.type='satker'
                                           and dokumen_pk_template_row.type='section_title'
                                           and dokumen_pk_template_row.id < '" . $dataIndicatorSK->id . "'
                                       ORDER BY dokumen_pk_template_row.id DESC;
                                   ")->getRow();

                                    $findDataSatkerIndex = array_search($valueDataSatker->satker, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
                                    $findDataSatkerIndex2 = array_search($valueDataSatker->satker, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));

                                    if (array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
                                            'namaSk' => $dataSk->title,
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }

                                    if (array_search($dataSk->title, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], [
                                            'namaSk' => '-',
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['rowspan']++;

                                    $findDataSKIndex = array_search($dataSk->title, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));

                                    $findDataSKIndex2 = array_search($dataSk->title, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'], 'namaSk'));

                                    $dataDokumen = $this->db->query("
                                       SELECT
                                           dokumenpk_satker_rows.target_value,
                                           dokumenpk_satker_rows.outcome_value,
                                           dokumenpk_satker_rows.is_checked
                                       FROM
                                           dokumenpk_satker_rows
                                           LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                                       WHERE
                                           dokumenpk_satker.template_id='" . $dataIndicatorSK->template_id . "'
                                           AND dokumenpk_satker.satkerid='" . $valueDataSatker->satkerid . "'
                                           AND dokumenpk_satker.balaiid='" . $valueDataSatker->balaiid . "'
                                           AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                                           AND dokumenpk_satker.status='setuju'
                                           AND dokumenpk_satker.deleted_at is null
                                           AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK->id . "'
                                   ")->getRow();
                                    array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
                                        'title'         => $dataIndicatorSK->title,
                                        'output'        => $dataDokumen->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK->target_satuan,
                                        'outcome'       => $dataDokumen->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK->outcome_satuan,
                                        'is_checked'    => $dataDokumen->is_checked ?? '-'
                                    ]);
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;

                                    array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'][$findDataSKIndex2]['indikatorSk'], [
                                        'title'         => '-',
                                        'output'        => $dataDokumen->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK->target_satuan,
                                        'outcome'       => $dataDokumen->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK->outcome_satuan,
                                        'is_checked'    => $dataDokumen->is_checked ?? '-'
                                    ]);
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex2]['sk'][$findDataSKIndex2]['rowspan']++;
                                }
                            } else if ($queryBalai) {
                                foreach ($queryBalai as $keyDataBalai => $valueDataBalai) {
                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan']++;

                                    if ($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemTemp['rowspan']++;
                                        $itemTemp['sp'][$keySp]['rowspan']++;
                                    }

                                    if ($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['rowspan'] > 1) {
                                        $itemBalai['rowspan']++;
                                        $itemBalai['sp'][$keySp]['rowspan']++;
                                    }

                                    if (array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => '-',
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }

                                    if (array_search($valueDataBalai->balai, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], [
                                            'namaSatker' => '-',
                                            'rowspan' => 0,
                                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                                            'sk' => []
                                        ]);
                                    }

                                    // var_dump($valueDataBalai->balaiid);
                                    // die;


                                    $dataIndicatorSK2 = $this->db->query("
                                       SELECT 
                                           dokumen_pk_template_row.*,
                                           dokumen_pk_template_rowrumus.rumus
                                       FROM 
                                           dokumen_pk_template_akses
                                           left join dokumen_pk_template on 
                                           dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                           left join dokumen_pk_template_row ON
                                           dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                           left join dokumen_pk_template_rowrumus ON
                                           dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                       where 
                                           dokumen_pk_template_akses.rev_table='m_balai' 
                                           and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
                                           and dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                           and dokumen_pk_template.type='master-balai'
                                   ")->getRow();


                                    $dataSkBalai = $this->db->query("
                                       SELECT 
                                           dokumen_pk_template_row.*
                                       FROM 
                                           dokumen_pk_template_akses
                                           left join dokumen_pk_template on 
                                               dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                                           left join dokumen_pk_template_row ON
                                               dokumen_pk_template.id = dokumen_pk_template_row.template_id
                                           left join dokumen_pk_template_rowrumus ON
                                               dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                                       where 
                                           dokumen_pk_template_akses.rev_table='m_balai' 
                                           and dokumen_pk_template_akses.rev_id='" . $valueDataBalai->balaiid . "'
                                           and dokumen_pk_template.type='balai'
                                           and dokumen_pk_template_row.type='section_title'
                                           and dokumen_pk_template_row.id < '" . $dataIndicatorSK2->id . "'
                                       ORDER BY dokumen_pk_template_row.id DESC;
                                   ")->getRow();


                                    $findDataSatkerIndex = array_search($valueDataBalai->balai, array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));
                                    $findDataBalaiIndex = array_search($valueDataBalai->balai, array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'], 'namaSatker'));

                                    if (array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], [
                                            'namaSk' => '-',
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }

                                    if (array_search(isset($dataSkBalai->title), array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], 'namaSk')) === FALSE) {
                                        array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], [
                                            'namaSk' => '-',
                                            'rowspan' => 0,
                                            'indikatorSk' => []
                                        ]);
                                    }

                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['rowspan']++;


                                    $findDataSKIndex = array_search(isset($dataSkBalai->title), array_column($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'], 'namaSk'));

                                    $findDataSKIndexBalai = array_search(isset($dataSkBalai->title), array_column($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'], 'namaSk'));

                                    $dataDokumen2 = $this->db->query("
                                       SELECT
                                           dokumenpk_satker_rows.target_value,
                                           dokumenpk_satker_rows.outcome_value,
                                           dokumenpk_satker_rows.is_checked
                                       FROM
                                           dokumenpk_satker_rows
                                           LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                                       WHERE
                                           dokumenpk_satker.template_id='" . $dataIndicatorSK2->template_id . "'
                                           AND dokumenpk_satker.balaiid='" . $valueDataBalai->balaiid . "'
                                           AND dokumenpk_satker.satkerid is null
                                           AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                                           AND dokumenpk_satker.status='setuju'
                                           AND dokumenpk_satker.deleted_at is null
                                           AND dokumenpk_satker_rows.template_row_id='" . $dataIndicatorSK2->id . "'
                                   ")->getRow();


                                    array_push($itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['indikatorSk'], [
                                        // 'title'         => $dataIndicatorSK2->title,
                                        'title'         => '-',
                                        'output'        => $dataDokumen2->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK2->target_satuan,
                                        'outcome'       => $dataDokumen2->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK2->outcome_satuan,
                                        'is_checked'    => $dataDokumen2->is_checked ?? '-'
                                    ]);

                                    array_push($itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataBalaiIndex]['sk'][$findDataSKIndexBalai]['indikatorSk'], [
                                        // 'title'         => $dataIndicatorSK2->title,
                                        'title'         => '-',
                                        'output'        => $dataDokumen2->target_value ?? '-',
                                        'outputSatuan'  => $dataIndicatorSK2->target_satuan,
                                        'outcome'       => $dataDokumen2->outcome_value ?? '-',
                                        'outcomeSatuan' => $dataIndicatorSK2->outcome_satuan,
                                        'is_checked'    => $dataDokumen2->is_checked ?? '-'
                                    ]);

                                    $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
                                    $itemBalai['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'][$findDataSatkerIndex]['sk'][$findDataSKIndex]['rowspan']++;
                                }
                            }
                            // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = $dataSatker->satker ?? null;
                        }
                    }
                }
            }

            array_push($tempData, $itemTemp);
            array_push($tempDataBalai, $itemBalai);
            // echo json_encode($itemBalai); die;
        }

        $filename = 'Rekap Data Balai';
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        return view('Modules\Admin\Views\DokumenPK\Rekap\Rekap-Balai.php', [
            'title' => "Perjanjian Kinerja Arsip",
            'data' => $tempData,
            'databalai' => $tempDataBalai,
            'tahun' => $sessionTahun
        ]);
    }

    public function export_rekap_excel_skpd()
    {
        $sessionTahun = $this->user['tahun'];
        $tempSKPD = [];
        $dataSKPD = $this->tableBalai->where('balaiid', 98)->get()->getRowArray();
        $itemSKPD = [
            'nama_balai' => $dataSKPD['balai'],
            'rowspan' => 0
        ];

        $dataSK_SKPD = $this->db->query("
            SELECT 
                dokumen_pk_template_row.*
            FROM 
                dokumen_pk_template_akses
                left join dokumen_pk_template on 
                    dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                left join dokumen_pk_template_row ON
                    dokumen_pk_template.id = dokumen_pk_template_row.template_id
            where 
                dokumen_pk_template_akses.rev_table='m_satker'
                and dokumen_pk_template_row.template_id = '11'
                and dokumen_pk_template.type='satker'
                and dokumen_pk_template_row.type='section_title';
        ")->getResult();

        foreach ($dataSK_SKPD as $keySK_SKPD => $valueSkSKPD) {
            $itemSKPD['sp'][$keySK_SKPD]['namaSp'] = '-';
            $itemSKPD['sp'][$keySK_SKPD]['rowspan'] = 0;

            $indikatorItemSkChild = true;
            $indikatorSkSKPD = $this->db->query("
            SELECT 
                id, title, type 
            FROM 
                `dokumen_pk_template_row` 
            where 
                template_id='" . $valueSkSKPD->template_id . "' 
                and id > '" . $valueSkSKPD->id . "'
            ")->getResult();

            foreach ($indikatorSkSKPD as $keyIndikatorSkSKPD => $valueIndikatorSkSKPD) {
                $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD] = (array) $valueIndikatorSkSKPD;
                $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['rowspan'] = 0;

                $satkerSKPD = $this->db->query("
                    SELECT 
                        m_satker.* 
                    FROM 
                        dokumen_pk_template_akses left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
                    WHERE 
                        dokumen_pk_template_akses.rev_table='m_satker' 
                        and m_satker.balaiid ='" . $dataSKPD['balaiid'] . "' 
                    ORDER BY 
                        `satkerid` ASC 
                ")->getResult();

                $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'] = [];
                foreach ($satkerSKPD as $keySatkerSKPD => $valueSatkerSKPD) {
                    $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['rowspan']++;

                    if ($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['rowspan'] > 1) {
                        $itemSKPD['rowspan']++;
                        $itemSKPD['sp'][$keySK_SKPD]['rowspan']++;
                    }


                    if (array_search($valueSatkerSKPD->satker, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'], 'nama_satker')) === FALSE) {
                        array_push($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'], [
                            'nama_satker' => $valueSatkerSKPD->satker ?? '-',
                            'rowspan' => 0,
                            // 'rumus' => [$valueRumusIndikatorSp->rumus],
                            'sk' => []
                        ]);
                    }

                    $findDataSatkerSKPD = array_search($valueSatkerSKPD->satker, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'], 'nama_satker'));
                    if (array_search($valueSkSKPD->title, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'], 'nama_sk')) === FALSE) {
                        array_push($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'], [
                            'namaSk' => $valueSkSKPD->title,
                            'rowspan' => 0,
                            'indikatorSk' => []
                        ]);
                    }

                    $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['rowspan']++;
                    $findDataSKIndexSKPD = array_search($valueSkSKPD->title, array_column($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'], 'namaSk'));

                    $dataDokumenSKPD = $this->db->query("
                        SELECT
                            dokumenpk_satker_rows.target_value,
                            dokumenpk_satker_rows.outcome_value,
                            dokumenpk_satker_rows.is_checked
                        FROM
                            dokumenpk_satker_rows
                            LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                        WHERE
                            dokumenpk_satker.template_id='" . $valueSkSKPD->template_id . "'
                            AND dokumenpk_satker.satkerid='" . $valueSatkerSKPD->satkerid . "'
                            AND dokumenpk_satker.balaiid='" . $dataSKPD['balaiid'] . "'
                            AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                            AND dokumenpk_satker.status='setuju'
                            AND dokumenpk_satker.deleted_at is null;
                    ")->getRow();

                    array_push($itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'][$findDataSKIndexSKPD]['indikatorSk'], [
                        'title'         => $valueIndikatorSkSKPD->title,
                        'output'        => $dataDokumenSKPD->target_value ?? '-',
                        'outputSatuan'  => $valueSkSKPD->target_satuan,
                        'outcome'       => $dataDokumenSKPD->outcome_value ?? '-',
                        'outcomeSatuan' => $valueSkSKPD->outcome_satuan,
                        'is_checked'    => $dataDokumenSKPD->is_checked ?? '-'
                    ]);
                    $itemSKPD['sp'][$keySK_SKPD]['indikatorSkSKPD'][$keyIndikatorSkSKPD]['satker'][$findDataSatkerSKPD]['sk'][$findDataSKIndexSKPD]['rowspan']++;
                }
            }
            array_push($tempSKPD, $itemSKPD);
        }

        $filename = 'Rekap Data SKPD TP-OP';
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        return view('Modules\Admin\Views\DokumenPK\Rekap\Rekap-SKPD.php', [
            'title' => "Perjanjian Kinerja Arsip",
            'dataskpd' => $tempSKPD,
            'nama_balai' => $dataSKPD['balai'],
            'tahun' => $sessionTahun
        ]);
    }

    public function export_rekap_excel_satpus()
    {
        $sessionTahun = $this->user['tahun'];
        $tempSatpus = [];
        $dataSatpus = $this->tableBalai->where('balaiid', 99)->get()->getRowArray();

        //Satker Pusat
        $itemSatpus = [
            'nama_balai' => $dataSatpus['balai'],
            'rowspan' => 0,
            'namaSp' => '-',
            'indikator_sp' => '-'
        ];

        $dataSatker_Satpus = $this->db->query("
            SELECT 
                m_satker.* 
            FROM 
                dokumen_pk_template_akses 
                left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
            WHERE 
                dokumen_pk_template_akses.rev_table='m_satker' 
                and m_satker.satkerid IN (403477,654098)
            ORDER BY 
                `satkerid` ASC;
        ")->getResult();

        $itemSatpus['satker'] = [];
        foreach ($dataSatker_Satpus as $keySatker_Satpus => $valueSatker_Satpus) {
            $itemSatpus['rowspan']++;
            if (array_search($valueSatker_Satpus->satker, array_column($itemSatpus['satker'], 'nama_satker')) === FALSE) {
                array_push($itemSatpus['satker'], [
                    'nama_satker' => $valueSatker_Satpus->satker,
                    'rowspan' => 0,
                    // 'rumus' => [$valueRumusIndikatorSp->rumus],
                    'sk' => []
                ]);
            }

            $dataIndikatorSk_Satker = $this->db->query("
                SELECT 
                    dokumen_pk_template_row.*,
                    dokumen_pk_template_rowrumus.rumus
                FROM 
                    dokumen_pk_template_akses
                    left join dokumen_pk_template on 
                        dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                    left join dokumen_pk_template_row ON
                        dokumen_pk_template.id = dokumen_pk_template_row.template_id
                    left join dokumen_pk_template_rowrumus ON
                        dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                where 
                    dokumen_pk_template_akses.rev_table='m_satker' 
                    and dokumen_pk_template_akses.rev_id='" . $valueSatker_Satpus->satkerid . "'
                    and dokumen_pk_template_row.type='form'
                    and dokumen_pk_template.type='satker'
                ;
            ")->getRow();

            $dataSK_Satpus = $this->db->query("
                SELECT 
                    dokumen_pk_template_row.*
                FROM 
                    dokumen_pk_template_akses
                    left join dokumen_pk_template on 
                        dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                    left join dokumen_pk_template_row ON
                        dokumen_pk_template.id = dokumen_pk_template_row.template_id
                where 
                    dokumen_pk_template_akses.rev_table='m_satker'
                    and dokumen_pk_template_akses.rev_id = '" . $valueSatker_Satpus->satkerid . "'
                    and dokumen_pk_template.type='satker'
                    and dokumen_pk_template_row.prefix_title='SK'
                    and dokumen_pk_template_row.type='section_title'
                ORDER BY 
                    dokumen_pk_template_row.id DESC;
            ")->getRow();

            $findDataSatkerIndexSatpus = array_search($valueSatker_Satpus->satker, array_column($itemSatpus['satker'], 'nama_satker'));
            $findDataSKIndexSatpus = array_search($dataSK_Satpus->title, array_column($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'], 'namaSk'));
            if (array_search($dataSK_Satpus->title, array_column($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'], 'namaSk')) === FALSE) {
                array_push($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'], [
                    'namaSk' => $dataSK_Satpus->title,
                    'rowspan' => 0,
                    'indikatorSk' => []
                ]);
            }

            $itemSatpus['satker'][$findDataSatkerIndexSatpus]['rowspan']++;
            $findDataSKIndex = array_search($dataSK_Satpus->title, array_column($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'], 'namaSk'));
            $dataDokumen_Satker = $this->db->query("
                SELECT
                    dokumenpk_satker_rows.target_value,
                    dokumenpk_satker_rows.outcome_value,
                    dokumenpk_satker_rows.is_checked
                FROM
                    dokumenpk_satker_rows
                    LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                WHERE
                    dokumenpk_satker.template_id='" . $dataIndikatorSk_Satker->template_id . "'
                    AND dokumenpk_satker.satkerid='" . $valueSatker_Satpus->satkerid . "'
                    AND dokumenpk_satker.balaiid='" . $dataSatpus['balai'] . "'
                    AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                    AND dokumenpk_satker.status='setuju'
                    AND dokumenpk_satker.deleted_at is null
                    AND dokumenpk_satker_rows.template_row_id='" . $dataIndikatorSk_Satker->id . "'
            ")->getRow();

            array_push($itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'][$findDataSKIndexSatpus]['indikatorSk'], [
                'title'         => $dataIndikatorSk_Satker->title,
                'output'        => $dataDokumen_Satker->target_value ?? '-',
                'outputSatuan'  => $dataIndikatorSk_Satker->target_satuan,
                'outcome'       => $dataDokumen_Satker->outcome_value ?? '-',
                'outcomeSatuan' => $dataIndikatorSk_Satker->outcome_satuan,
                'is_checked'    => $dataDokumen_Satker->is_checked ?? '-'
            ]);
            $itemSatpus['satker'][$findDataSatkerIndexSatpus]['sk'][$findDataSKIndexSatpus]['rowspan']++;
        }
        array_push($tempSatpus, $itemSatpus);

        $filename = 'Rekap Data Satker Pusat';
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        return view('Modules\Admin\Views\DokumenPK\Rekap\Rekap-Satpus.php', [
            'title' => "Perjanjian Kinerja Arsip",
            'datasatpus' => $tempSatpus,
            'tahun' => $sessionTahun
        ]);
    }

    // public function export_rekap_excel_eselon2()
    // {
    //     $sessionTahun = $this->user['tahun'];
    //     $tempEselon = [];

    //     //Eselon2
    //     $itemEselon = [
    //         'nama_balai' => '-',
    //         'rowspan' => 0,
    //         'namaSp' => '-',
    //         'indikator_sp' => '-',
    //     ];

    //     $dataSatker_Eselon = $this->db->query("
    //         SELECT 
    //             m_satker.* 
    //         FROM 
    //             dokumen_pk_template_akses left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
    //         WHERE 
    //             dokumen_pk_template_akses.rev_table='m_satker' 
    //             and m_satker.grup_jabatan ='eselon2'
    //         ORDER BY 
    //             `satkerid` ASC;
    //     ")->getResult();

    //     $itemEselon['satker'] = [];
    //     foreach ($dataSatker_Eselon as $keySatker_Eselon => $valueSatker_Eselon) {
    //         if (array_search($valueSatker_Eselon->satker, array_column($itemEselon['satker'], 'nama_satker')) === FALSE) {
    //             array_push($itemEselon['satker'], [
    //                 'nama_satker' => $valueSatker_Eselon->satker,
    //                 'rowspan' => 0,
    //                 // 'rumus' => [$valueRumusIndikatorSp->rumus],
    //                 'sk' => []
    //             ]);
    //         }

    //         $dataSk_Eselon = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_satker'
    //                 and dokumen_pk_template_akses.rev_id = '" . $valueSatker_Eselon->satkerid . "'
    //                 and dokumen_pk_template.type='eselon2'
    //                 and dokumen_pk_template_row.prefix_title='SK'
    //                 and dokumen_pk_template_row.type='section_title'
    //             ORDER BY 
    //                 dokumen_pk_template_row.id DESC;
    //         ")->getRow();

    //         $dataIndikatorSk_Eselon = $this->db->query("
    //             SELECT 
    //                 dokumen_pk_template_row.*,
    //                 dokumen_pk_template_rowrumus.rumus
    //             FROM 
    //                 dokumen_pk_template_akses
    //                 left join dokumen_pk_template on 
    //                     dokumen_pk_template_akses.template_id = dokumen_pk_template.id
    //                 left join dokumen_pk_template_row ON
    //                     dokumen_pk_template.id = dokumen_pk_template_row.template_id
    //                 left join dokumen_pk_template_rowrumus ON
    //                     dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
    //             where 
    //                 dokumen_pk_template_akses.rev_table='m_satker' 
    //                 and dokumen_pk_template_akses.rev_id='" . $valueSatker_Eselon->satkerid . "'
    //                 and dokumen_pk_template_row.type='form'
    //                 and dokumen_pk_template.type='eselon2';
    //         ")->getResult();

    //         $findDataSatkerIndexEselon = array_search($valueSatker_Eselon->satker, array_column($itemEselon['satker'], 'nama_satker'));

    //         if (array_search($dataSk_Eselon->title, array_column($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'], 'namaSk')) === FALSE) {
    //             array_push($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'], [
    //                 'namaSk' => $dataSk_Eselon->title,
    //                 'rowspan' => 0,
    //                 'indikatorSk' => []
    //             ]);
    //         }

    //         foreach ($dataIndikatorSk_Eselon as $keyIndikatorSk_Eselon => $valueIndikatorSk_Eselon) {
    //             $itemEselon['rowspan']++;
    //             $itemEselon['satker'][$findDataSatkerIndexEselon]['rowspan']++;
    //             $findDataSKIndexEselon = array_search($dataSk_Eselon->title, array_column($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'], 'namaSk'));
    //             $dataDokumen_Eselon = $this->db->query("
    //             SELECT
    //                 dokumenpk_satker_rows.target_value,
    //                 dokumenpk_satker_rows.outcome_value,
    //                 dokumenpk_satker_rows.is_checked
    //             FROM
    //                 dokumenpk_satker_rows
    //                 LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
    //             WHERE
    //                 dokumenpk_satker.template_id='" . $valueIndikatorSk_Eselon->template_id . "'
    //                 AND dokumenpk_satker.satkerid='" . $valueSatker_Eselon->satkerid . "'
    //                 AND dokumenpk_satker.tahun='" . $sessionTahun . "'
    //                 AND dokumenpk_satker.status='setuju'
    //                 AND dokumenpk_satker.deleted_at is null
    //                 AND dokumenpk_satker_rows.template_row_id='" . $valueIndikatorSk_Eselon->id . "'
    //             ")->getRow();

    //             array_push($itemEselon['satker'][$findDataSatkerIndexEselon]['sk'][$findDataSKIndexEselon]['indikatorSk'], [
    //                 'title'         => $valueIndikatorSk_Eselon->title,
    //                 'output'        => $dataDokumen_Eselon->target_value ?? '-',
    //                 'outputSatuan'  => $valueIndikatorSk_Eselon->target_satuan,
    //                 'outcome'       => $dataDokumen_Eselon->outcome_value ?? '-',
    //                 'outcomeSatuan' => $valueIndikatorSk_Eselon->outcome_satuan,
    //                 'is_checked'    => $dataDokumen_Eselon->is_checked ?? '-'
    //             ]);
    //             $itemEselon['satker'][$findDataSatkerIndexEselon]['sk'][$findDataSKIndexEselon]['rowspan']++;
    //         }
    //     }
    //     array_push($tempEselon, $itemEselon);

    //     $filename = 'Rekap Data Eselon 2';
    //     header("Content-type: application/vnd.ms-excel");
    //     header("Content-disposition: attachment; filename=" . $filename . ".xls");
    //     header("Pragma: no-cache");
    //     header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    //     header("Expires: 0");

    //     return view('Modules\Admin\Views\DokumenPK\Rekap\Rekap-Eselon2.php', [
    //         'title' => "Perjanjian Kinerja Arsip",
    //         'dataeselon2' => $tempEselon,
    //         'tahun' => $sessionTahun
    //     ]);
    // }

    public function export_rekap_excel_baltek()
    {
        $sessionTahun = $this->user['tahun'];
        $tempBalaiTeknik = [];
        $dataBalaiTeknik = $this->tableBalai->where('balaiid', 97)->get()->getRow();

        //Balai Teknik
        $itemBaltek = [
            'nama_balai' => $dataBalaiTeknik->balai,
            'rowspan' => 0,
            'namaSp' => '-',
            'indikator_sp' => '-'

        ];

        $dataSatker_Baltek = $this->db->query("
            SELECT 
                m_satker.* 
            FROM 
                dokumen_pk_template_akses left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid 
            WHERE 
                dokumen_pk_template_akses.rev_table='m_satker' 
                and m_satker.balaiid ='97'
            ORDER BY 
                `satkerid` ASC;
        ")->getResult();

        $itemBaltek['satker'] = [];
        foreach ($dataSatker_Baltek as $keySatker_Baltek => $valueSatker_Baltek) {

            if (array_search($valueSatker_Baltek->satker, array_column($itemBaltek['satker'], 'nama_satker')) === FALSE) {
                array_push($itemBaltek['satker'], [
                    'nama_satker' => $valueSatker_Baltek->satker,
                    'rowspan' => 0,
                    // 'rumus' => [$valueRumusIndikatorSp->rumus],
                    'sk' => []
                ]);
            }

            $dataSk_Baltek = $this->db->query("
                SELECT 
                    dokumen_pk_template_row.*
                FROM 
                    dokumen_pk_template_akses
                    left join dokumen_pk_template on 
                        dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                    left join dokumen_pk_template_row ON
                        dokumen_pk_template.id = dokumen_pk_template_row.template_id
                where 
                    dokumen_pk_template_akses.rev_table='m_satker'
                    and dokumen_pk_template_akses.rev_id = '" . $valueSatker_Baltek->satkerid . "'
                    and dokumen_pk_template.type='satker'
                    and dokumen_pk_template_row.type='section_title'
                ORDER BY 
                    dokumen_pk_template_row.id ASC;
            ")->getResult();


            $findDataSatkerIndexBaltek = array_search($valueSatker_Baltek->satker, array_column($itemBaltek['satker'], 'nama_satker'));
            $itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'] = [];
            foreach ($dataSk_Baltek as $keySk_Baltek => $valueSk_Baltek) {
                $itemBaltek['rowspan']++;
                $itemBaltek['satker'][$findDataSatkerIndexBaltek]['rowspan']++;

                if (array_search($valueSk_Baltek->title, array_column($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'], 'namaSk')) === FALSE) {
                    array_push($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'], [
                        'namaSk' => $valueSk_Baltek->title,
                        'rowspan' => 0,
                        'indikatorSk' => []
                    ]);
                }

                $dataIndikatorSk_Baltek = $this->db->query("
                    SELECT 
                        dokumen_pk_template_row.*,
                        dokumen_pk_template_rowrumus.rumus
                    FROM 
                        dokumen_pk_template_akses
                        left join dokumen_pk_template on 
                            dokumen_pk_template_akses.template_id = dokumen_pk_template.id
                        left join dokumen_pk_template_row ON
                            dokumen_pk_template.id = dokumen_pk_template_row.template_id
                        left join dokumen_pk_template_rowrumus ON
                            dokumen_pk_template_row.id = dokumen_pk_template_rowrumus.rowId
                    where 
                        dokumen_pk_template_akses.rev_table='m_satker' 
                        and dokumen_pk_template_akses.rev_id='" . $valueSatker_Baltek->satkerid . "'
                        and dokumen_pk_template_row.type='form'
                        and dokumen_pk_template_row.id > '" . $valueSk_Baltek->id . "'
                        and dokumen_pk_template.type='satker';
                ")->getRow();


                $findDataSKIndexBaltek = array_search($valueSk_Baltek->title, array_column($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'], 'namaSk'));
                $dataDokumen_Baltek = $this->db->query("
                    SELECT
                        dokumenpk_satker_rows.target_value,
                        dokumenpk_satker_rows.outcome_value,
                        dokumenpk_satker_rows.is_checked
                    FROM
                        dokumenpk_satker_rows
                        LEFT JOIN dokumenpk_satker ON dokumenpk_satker_rows.dokumen_id = dokumenpk_satker.id
                    WHERE
                        dokumenpk_satker.template_id='" . $dataIndikatorSk_Baltek->template_id . "'
                        AND dokumenpk_satker.satkerid='" . $valueSatker_Baltek->satkerid . "'
                        AND dokumenpk_satker.balaiid='" . $dataBalaiTeknik->balaiid . "'
                        AND dokumenpk_satker.tahun='" . $sessionTahun . "'
                        AND dokumenpk_satker.status='setuju'
                        AND dokumenpk_satker.deleted_at is null
                        AND dokumenpk_satker_rows.template_row_id='" . $dataIndikatorSk_Baltek->id . "'
                ")->getRow();

                array_push($itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'][$findDataSKIndexBaltek]['indikatorSk'], [
                    'title'         => $dataIndikatorSk_Baltek->title,
                    'output'        => $dataDokumen_Baltek->target_value ?? '-',
                    'outputSatuan'  => $dataIndikatorSk_Baltek->target_satuan,
                    'outcome'       => $dataDokumen_Baltek->outcome_value ?? '-',
                    'outcomeSatuan' => $dataIndikatorSk_Baltek->outcome_satuan,
                    'is_checked'    => $dataDokumen_Baltek->is_checked ?? '-'
                ]);
                $itemBaltek['satker'][$findDataSatkerIndexBaltek]['sk'][$findDataSKIndexBaltek]['rowspan']++;
            }
        }
        array_push($tempBalaiTeknik, $itemBaltek);

        $filename = 'Rekap Data Balai Teknik';
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");

        return view('Modules\Admin\Views\DokumenPK\Rekap\Rekap-Baltek.php', [
            'title' => "Perjanjian Kinerja Arsip",
            'databaltek' => $tempBalaiTeknik,
            'tahun' => $sessionTahun
        ]);
    }



    public function instansiList($instansi)
    {
        $instansiList = [];
        $searchTerm = $this->request->getVar('term') ?? '';

        if ($instansi == 'satker') {
            $dataSatker = $this->db->query("
                    SELECT
                        satkerid as id,satker as text
                    FROM
                       m_satker
                    WHERE
                      grup_jabatan = 'satker' 
                      AND
                    satker LIKE '%" . $searchTerm . "%'
                ")->getResult();


            foreach ($dataSatker as $satker) {
                $text = $satker->text;

                // Jika 'term' mengandung kata 'satker' atau jika 'text' mengandung kata 'BALAI', tambahkan 'SATKER'
                // if (stripos($text, 'BALAI') !== false) {
                //     $text = 'SATKER ' . $text;
                // }
                $instansiList[] = [
                    'id' => $satker->id,
                    'text' => $text
                ];
            }
        }



        return $this->response->setJSON($instansiList);
    }
}
