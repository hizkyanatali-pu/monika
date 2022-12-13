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

        $this->dokumenPK               = $this->db->table('dokumen_pk_template');
        $this->dokumenPK_row           = $this->db->table('dokumen_pk_template_row');
        $this->dokumenPk_rowRumus      = $this->db->table('dokumen_pk_template_rowrumus');
        $this->dokumenPK_kegiatan      = $this->db->table('dokumen_pk_template_kegiatan');
        $this->dokumenPK_info          = $this->db->table('dokumen_pk_template_info');
        $this->dokumenPK_akses         = $this->db->table('dokumen_pk_template_akses');
        $this->tempExportBigdataColumn = $this->db->table("temp_export_bigdata_column");
        $this->tableSatker             = $this->db->table("m_satker");
        $this->tableBalai              = $this->db->table("m_balai");
        $this->tableKegiatan           = $this->db->table("tgiat");
        $this->tableProgram            = $this->db->table("tprogram");
        $this->templateDokumen         = $this->db->table('dokumen_pk_template');

        $this->satker   = $this->db->table('m_satker');
        $this->balai    = $this->db->table('m_balai');

        $this->request = \Config\Services::request();
    }



    public function dashboard()
    {
        $jumlahTotal = $this->dokumenPK_akses->countAllResults();

        $menungguKonfirmasi_satker = $this->dokumenPK_akses->join('dokumenpk_satker', "(dokumenpk_satker.template_id=dokumen_pk_template_akses.template_id AND dokumenpk_satker.satkerid=dokumen_pk_template_akses.rev_id)", 'left')
            ->where("dokumen_pk_template_akses.rev_table='m_satker' AND dokumenpk_satker.status='hold' AND dokumenpk_satker.tahun='" . $this->user['tahun'] . "'")
            ->countAllResults();

        $menungguKonfirmasi_balai = $this->dokumenPK_akses->join('dokumenpk_satker', "(dokumenpk_satker.template_id=dokumen_pk_template_akses.template_id AND dokumenpk_satker.balaiid=dokumen_pk_template_akses.rev_id)", 'left')
            ->where("dokumen_pk_template_akses.rev_table='m_balai' AND dokumenpk_satker.status='hold' AND dokumenpk_satker.tahun='" . $this->user['tahun'] . "'")
            ->where('dokumenpk_satker.tahun', $this->user['tahun'])
            ->countAllResults();

        $menungguKonfirmasi_jumlah = $menungguKonfirmasi_satker + $menungguKonfirmasi_balai;
        $menungguKonfirmasi_persentase = ($menungguKonfirmasi_jumlah / $jumlahTotal) * 100;



        $terverifikasi_satker = $this->dokumenPK_akses->join('dokumenpk_satker', "(dokumenpk_satker.template_id=dokumen_pk_template_akses.template_id AND dokumenpk_satker.satkerid=dokumen_pk_template_akses.rev_id)", 'left')
            ->where("dokumen_pk_template_akses.rev_table='m_satker' AND dokumenpk_satker.status='setuju' AND dokumenpk_satker.tahun='" . $this->user['tahun'] . "'")
            ->where('dokumenpk_satker.tahun', $this->user['tahun'])
            ->countAllResults();

        $terverifikasi_balai = $this->dokumenPK_akses->join('dokumenpk_satker', "(dokumenpk_satker.template_id=dokumen_pk_template_akses.template_id AND dokumenpk_satker.balaiid=dokumen_pk_template_akses.rev_id)", 'left')
            ->where("dokumen_pk_template_akses.rev_table='m_balai' AND dokumenpk_satker.status='setuju' AND dokumenpk_satker.tahun='" . $this->user['tahun'] . "'")
            ->where('dokumenpk_satker.tahun', $this->user['tahun'])
            ->countAllResults();

        $terverifikasi_jumlah = $terverifikasi_satker + $terverifikasi_balai;
        $terverifikasi_persentase = ($terverifikasi_jumlah / $jumlahTotal) * 100;



        $ditolak_satker = $this->dokumenPK_akses->join('dokumenpk_satker', "(dokumenpk_satker.template_id=dokumen_pk_template_akses.template_id AND dokumenpk_satker.satkerid=dokumen_pk_template_akses.rev_id)", 'left')
            ->where("dokumen_pk_template_akses.rev_table='m_satker' AND dokumenpk_satker.status='tolak' AND dokumenpk_satker.tahun='" . $this->user['tahun'] . "'")
            ->where('dokumenpk_satker.tahun', $this->user['tahun'])
            ->countAllResults();

        $ditolak_balai = $this->dokumenPK_akses->join('dokumenpk_satker', "(dokumenpk_satker.template_id=dokumen_pk_template_akses.template_id AND dokumenpk_satker.balaiid=dokumen_pk_template_akses.rev_id)", 'left')
            ->where("dokumen_pk_template_akses.rev_table='m_balai' AND dokumenpk_satker.status='tolak' AND dokumenpk_satker.tahun='" . $this->user['tahun'] . "'")
            ->where('dokumenpk_satker.tahun', $this->user['tahun'])
            ->countAllResults();

        $ditolak_jumlah = $ditolak_satker + $ditolak_balai;
        $ditolak_persentase = ($ditolak_jumlah / $jumlahTotal) * 100;



        $belumMenginputkan_jumlah = $jumlahTotal - ($menungguKonfirmasi_jumlah + $terverifikasi_jumlah + $ditolak_jumlah);
        $belumMenginputkan_persentase = ($belumMenginputkan_jumlah / $jumlahTotal) * 100;

        return view('Modules\Admin\Views\DokumenPK\dashboard.php', [
            'piechart' => [
                'belumMenginputkan'  => ['persentase' => round($belumMenginputkan_persentase, 2), 'jumlah' => $belumMenginputkan_jumlah],
                'menungguKonfirmasi' => ['persentase' => round($menungguKonfirmasi_persentase, 2), 'jumlah' => $menungguKonfirmasi_jumlah],
                'terverifikasi'      => ['persentase' => round($terverifikasi_persentase, 2), 'jumlah' => $terverifikasi_jumlah],
                'ditolak'            => ['persentase' => round($ditolak_persentase, 2), 'jumlah' => $ditolak_jumlah],
                'jumlahall'          => $jumlahTotal
            ]
        ]);
    }



    public function satker()
    {
        $dataBelumInput = $this->satker->select("
            m_satker.satker
        ")
            ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='satker' and satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun= {$this->user['tahun']}) < 1")
            ->get()->getResult();

        $dataBelumInput = array_map(function ($arr) {
            return [
                'nama' => $arr->satker
            ];
        }, $dataBelumInput);

        return view('Modules\Admin\Views\DokumenPK\satker.php', [
            'sessionYear'              => $this->user['tahun'],
            'title'                => 'Dokumen Penjanjian Kinerja - Satker',
            'dokumenType'              => 'satker',
            'dataBelumInput'           => $dataBelumInput,
            'createDokumen_userOption' => json_encode($this->tableSatker->select("satkerid as id, satker as title")->whereNotIn('satker', ['', '1'])->where('grup_jabatan', 'satker')->get()->getResult())
        ]);
    }


    public function balai()
    {
        $dataBelumInput = $this->balai->select("
            balai
        ")
            ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='balai' and balaiid=m_balai.balaiid and tahun={$this->user['tahun']} and status='setuju') < 1 AND kota_penanda_tangan != ''")
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
        return view('Modules\Admin\Views\DokumenPK\satker.php', [
            'sessionYear'              => $this->user['tahun'],
            'title'                => 'Dokumen Penjanjian Kinerja - Eselon 2',
            'dokumenType'              => 'eselon2',
            'createDokumen_userOption' => json_encode($this->tableSatker->select("satkerid as id, satker as title")->whereNotIn('satker', ['', '1'])->where('grup_jabatan', 'eselon2')->get()->getResult())
        ]);
    }


    public function eselon1()
    {
        return view('Modules\Admin\Views\DokumenPK\satker.php', [
            'sessionYear'              => $this->user['tahun'],
            'title'                => 'Dokumen Penjanjian Kinerja - Eselon1',
            'dokumenType'              => 'eselon1',
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

        $dataTemplate = $this->templateDokumen->select('dokumen_pk_template.*')
            ->join('dokumen_pk_template_akses', 'dokumen_pk_template.id = dokumen_pk_template_akses.template_id', 'left')
            ->where('dokumen_pk_template.status', '1')
            ->where('dokumen_pk_template_akses.rev_id', $template_revID)
            // ->where('dokumen_pk_template.type', $template_type)
            ->where('dokumen_pk_template_akses.rev_table', $templae_revTable)
            ->where("deleted_at is null")
            ->groupBy('dokumen_pk_template.id')
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
                ->where('deleted_at is NULL', NULL, false)->get()->getResult(),
            'defaultType' => 'satker',
        ]);
    }



    public function templateEselon2()
    {
        return $this->pageTemplate([
            'title' => 'Template Perjanjian Kinerja - Eselon 2',
            'data' => $this->dokumenPK->where('type', 'eselon2')
                ->where('deleted_at is NULL', NULL, false)->get()->getResult(),
            'defaultType' => 'eselon2'
        ]);
    }



    public function templateEselon1()
    {
        return $this->pageTemplate([
            'title' => 'Template Perjanjian Kinerja - Eselon 1',
            'data' => $this->dokumenPK->where('type', 'eselon1')
                ->where('deleted_at is NULL', NULL, false)->get()->getResult(),
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
            'rows'     => $this->dokumenPK_row->select('dokumen_pk_template_row.*, (SELECT COUNT(dokumen_pk_template_rowrumus.rowId) FROM dokumen_pk_template_rowrumus WHERE dokumen_pk_template_rowrumus.rowId=dokumen_pk_template_row.id) as rumusJml')->where('template_id', $_id)->get()->getResult(),
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
        $this->dokumenPK_row->delete(['template_id' => $templateID]);
        $this->dokumenPk_rowRumus->delete(['template_id' => $templateID]);
        $this->insertDokumenPK_row($this->request->getPost(), $templateID);
        /** end-of: row */


        /* kegiatan */
        $this->dokumenPK_kegiatan->delete(['template_id' => $templateID]);
        $this->insertDokumenPK_kegiatan($this->request->getPost(), $templateID);
        /** end-of: kegiatan */


        /* info */
        // $this->dokumenPK_info->delete(['template_id' => $templateID]);
        // $this->insertDokumenPK_info($this->request->getPost(), $templateID);
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
        }

        return $this->respond([
            'status' => true,
            'dataStatus' => 'setuju', //stuju||tolak
            'input'  => $this->request->getPost()
        ]);
    }










    private function insertDokumenPK_row($input, $templateID)
    {
        $rows = [];
        $rowsNumber = 1;
        foreach ($input['formTable_title'] as $key_rowTitle => $data_rowTitle) {
            $rowId = $templateID . rand(10, 100);

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
                $this->insertDokumenPK_rowRumus(explode(',', $input['formTable_rumus'][$key_rowTitle]), $rowId, $templateID);
            }
        }
        $this->dokumenPK_row->insertBatch($rows);
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







    private function insertDokumenPK_rowRumus($inputRumus, $rowId, $templateId)
    {
        $rumus = [];

        foreach ($inputRumus as $key => $data) {
            array_push($rumus, [
                'template_id' => $templateId,
                'rowId'       => $rowId,
                'rumus'       => $data
            ]);
        }

        $this->dokumenPk_rowRumus->insertBatch($rumus);
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
                            SELECT rumus FROM dokumen_pk_template_rowrumus WHERE template_id='" . $valueSp->template_id . "' and rowId='" . $valueIndicatorSp->id . "'
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
                                    dokumen_pk_template_rowrumus 
                                    left join dokumen_pk_template on dokumen_pk_template_rowrumus.template_id = dokumen_pk_template.id
                                    left join dokumen_pk_template_akses on dokumen_pk_template.id = dokumen_pk_template_akses.template_id
                                    left join m_satker on dokumen_pk_template_akses.rev_id = m_satker.satkerid
                                WHERE 
                                    dokumen_pk_template_rowrumus.rumus='" . $valueRumusIndikatorSp->rumus . "'
                                    and dokumen_pk_template.type='satker'
                                    and dokumen_pk_template_akses.rev_table='m_satker'
                                    and m_satker.balaiid = '" . $valueBalai->balaiid . "';
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
                                            AND dokumenpk_satker.dokumen_type='satker'
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
                            }
                            // $itemTemp['sp'][$keySp]['indikatorSp'][$keyIndicatorSp]['satker'] = $dataSatker->satker ?? null;
                        }
                    }
                }
            }

            array_push($tempData, $itemTemp);
        }

        // echo json_encode($tempData); exit;

        return view("Modules\Admin\Views\DokumenPk\Rekap\Rekap-Eselon1", [
            'data' => $tempData,
            'tahun' => $sessionTahun
        ]);
    }
}
