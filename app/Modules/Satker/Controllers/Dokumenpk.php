<?php

namespace Modules\Satker\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Libraries\FPDF;

class Dokumenpk extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->userUID = $this->user['uid'];
        $this->db = \Config\Database::connect();

        $this->dokumenSatker          = $this->db->table('dokumenpk_satker');
        $this->dokumenSatker_rows     = $this->db->table('dokumenpk_satker_rows');
        $this->dokumenSatker_kegiatan = $this->db->table('dokumenpk_satker_kegiatan');

        $this->templateDokumen  = $this->db->table('dokumen_pk_template');
        $this->templateRow      = $this->db->table('dokumen_pk_template_row');
        $this->templateRowRumus = $this->db->table('dokumen_pk_template_rowrumus');
        $this->templateKegiatan = $this->db->table('dokumen_pk_template_kegiatan');
        $this->templateInfo     = $this->db->table('dokumen_pk_template_info');

        $this->satker   = $this->db->table('m_satker');
        $this->balai    = $this->db->table('m_balai');
        $this->kegiatan = $this->db->table('tgiat');

        $this->kota = $this->db->table('tkabkota');

        $this->dokumenStatus = [
            'hold'     => ['message' => 'Menunggu Konfirmasi', 'color' => 'bg-secondary'],
            'setuju'   => ['message' => 'Telah di Setujui', 'color' => 'bg-success text-white'],
            'tolak'    => ['message' => 'Di Tolak', 'color' => 'bg-danger text-white'],
            'revision' => ['message' => 'Telah Di Revisi', 'color' => 'bg-dark text-white']
        ];
        $this->request = \Config\services::request();
    }



    public function index()
    {
        $listSatkerCreateCokumen = false;
        $this->session->remove('createDokumenByBalai');

        $query_dataDokumen = $this->dokumenSatker->select('
            dokumenpk_satker.id,
            dokumenpk_satker.template_id,
            dokumenpk_satker.revision_master_dokumen_id,
            dokumenpk_satker.revision_master_number,
            dokumenpk_satker.revision_number,
            dokumenpk_satker.status,
            dokumenpk_satker.is_revision_same_year,
            dokumenpk_satker.change_status_at,
            dokumenpk_satker.created_at,
            dokumen_pk_template.title as dokumenTitle
        ')
            ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
            // ->where('user_created', $this->userUID)
            ->where('dokumenpk_satker.status !=', 'revision')
            ->where("dokumenpk_satker.deleted_at is null")
            ->where("dokumenpk_satker.tahun", $this->user['tahun'])
            ->orderBy('dokumenpk_satker.id', 'DESC');

        if ($this->user['user_type'] == 'satker') {
            $query_dataDokumen->where('satkerid', $this->user['satker_id'])
                ->groupStart()
                ->where('dokumen_type', 'satker')
                ->orWhere('dokumen_type', 'eselon2')
                ->orWhere('dokumen_type', 'eselon1')
                ->groupEnd();
        } elseif ($this->user['user_type'] == 'balai') {
            $query_dataDokumen->where('balaiid', $this->user['balai_id'])
                ->where('dokumen_type', 'balai');
        }
        $dataDokumen = $query_dataDokumen->get()->getResult();


        if (
            isset($this->user['satker_id'])
            || isset($this->user['balai_id'])
        ) {
            $template_type    = $this->user['user_type'];
            $templae_revTable = '';
            $template_revID   = '';

            switch ($this->user['user_type']) {
                case 'satker':
                    $templae_revTable = 'm_satker';
                    $template_revID   = $this->user['satker_id'];
                    break;

                case 'balai':
                    $template_type    = 'master-balai';
                    $templae_revTable = 'm_balai';
                    $template_revID   = $this->user['balai_id'];
                    $listSatkerCreateCokumen = true;
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

            if (!$dataTemplate) $dataTemplate = [];

            goto returnSection;
        }

        globalUserTemplate:
        if (isset($this->user['user_type'])) $this->templateDokumen->where('type', $this->user['user_type']);
        $dataTemplate = $this->templateDokumen->where('dokumen_pk_template.status', '1')->where("deleted_at is null")->get()->getResult();

        returnSection:

        return view('Modules\Satker\Views\Dokumenpk.php', [
            'title'             => "Perjanjian Kinerja Balai",
            'sessionYear'       => $this->user['tahun'],
            'templateDokumen'   => $dataTemplate,
            'templateAvailable' => count($dataTemplate) > 0 ? 'true' : 'false',
            'isCanCreated'      => true,
            'isCanConfirm'      => false,

            'listSatkerCreateCokumen' => $listSatkerCreateCokumen,

            'dataDokumen'   => $dataDokumen,
            'dokumenStatus' => $this->dokumenStatus
        ]);
    }



    public function balaiSatker($_satkerId)
    {
        $setSession_userData['byBalai_user_type']    = 'upt-balai';
        $this->session->set('createDokumenByBalai', $setSession_userData);

        $queryDataDokumen = $this->dokumenSatker->select('
            dokumenpk_satker.id,
            dokumenpk_satker.template_id,
            dokumenpk_satker.revision_master_dokumen_id,
            dokumenpk_satker.revision_master_number,
            dokumenpk_satker.revision_number,
            dokumenpk_satker.status,
            dokumenpk_satker.is_revision_same_year,
            dokumenpk_satker.change_status_at,
            dokumenpk_satker.created_at,
            dokumenpk_satker.satkerid,
            dokumen_pk_template.title as dokumenTitle,
            ku_user.nama as userCreatedName
        ')
            ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
            ->join('ku_user', 'dokumenpk_satker.user_created=ku_user.uid', 'left')
            ->where('dokumenpk_satker.status !=', 'revision')
            ->where('dokumenpk_satker.dokumen_type', 'satker')
            ->orderBy('dokumenpk_satker.id', 'DESC');

        if ($_satkerId == 'all') {
            $queryDataDokumen->where('dokumenpk_satker.balaiid', $this->user['balaiid']);

            $dataTemplate = [];
            $isCanCreated = false;
        } else {
            $queryDataDokumen->where('dokumenpk_satker.satkerid', $_satkerId);

            $dataTemplate = $this->templateDokumen->select('dokumen_pk_template.*')
                ->join('dokumen_pk_template_akses', 'dokumen_pk_template.id = dokumen_pk_template_akses.template_id', 'left')
                ->where('dokumen_pk_template.status', '1')
                ->where('dokumen_pk_template_akses.rev_id', $_satkerId)
                // ->where('dokumen_pk_template.type', $template_type)
                ->where('dokumen_pk_template_akses.rev_table', 'm_satker')
                ->where("deleted_at is null")
                ->groupBy('dokumen_pk_template.id')
                ->get()->getResult();

            $isCanCreated = true;
        }

        $dataDokumen = $queryDataDokumen->get()->getResult();



        return view('Modules\Satker\Views\Dokumenpk.php', [
            'title' => 'Perjanjian Kinerja Satker',

            'sessionYear'             => $this->user['tahun'],
            'templateDokumen'         => $dataTemplate,
            'templateAvailable'       => count($dataTemplate) > 0 ? 'true' : 'false',
            'isCanCreated'            => $isCanCreated,
            'isCanConfirm'            => true,
            'listSatkerCreateCokumen' => true,

            'filterSatker'          => $this->satker->where('balaiid', $this->user['balaiid'])->get()->getResult(),
            'filterSatker_selected' => $_satkerId,

            'dataDokumen'   => $dataDokumen,
            'dokumenStatus' => $this->dokumenStatus,

            'balaiCreateForSatker' =>  $_satkerId
        ]);
    }



    public function dataDokumenSatker($_status, $_dokumenType)
    {
        $dataDokumen = $this->dokumenSatker->select('
            dokumenpk_satker.id,
            dokumenpk_satker.template_id,
            dokumenpk_satker.revision_master_dokumen_id,
            dokumenpk_satker.revision_master_number,
            dokumenpk_satker.revision_number,
            dokumenpk_satker.status,
            dokumenpk_satker.is_revision_same_year,
            dokumenpk_satker.change_status_at,
            dokumenpk_satker.created_at,
            dokumen_pk_template.title as dokumenTitle,
            ku_user.nama as userCreatedName,
            dokumenpk_satker.satkerid,
            dokumenpk_satker.balaiid
            
        ')
            ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
            ->join('ku_user', 'dokumenpk_satker.user_created = ku_user.uid', 'left')
            ->where('dokumen_pk_template.status', '1')
            ->where('dokumenpk_satker.status', $_status)
            ->where('dokumenpk_satker.dokumen_type', $_dokumenType)
            ->where("dokumenpk_satker.deleted_at is null")
            ->where("dokumenpk_satker.tahun", $this->user['tahun'])
            ->orderBy('dokumenpk_satker.id', 'DESC')
            ->get()->getResult();

        $returnDaata = array_map(function ($arr) {
            return [
                'id'                         => $arr->id,
                'template_id'                => $arr->template_id,
                'revision_master_dokumen_id' => $arr->revision_master_dokumen_id,
                'revision_master_number'     => $arr->revision_master_number,
                'revision_number'            => $arr->revision_number,
                'status'                     => $arr->status,
                'is_revision_same_year'      => $arr->is_revision_same_year,
                'change_status_at'           => $arr->status != 'hold' ? date_indo($arr->change_status_at) : '-',
                'created_at'                 => $arr->created_at != null ? date_indo($arr->created_at) : '',
                'dokumenTitle'               => $arr->dokumenTitle,
                'userCreatedName'            => $arr->userCreatedName,
                'satkerid'                   => instansi_name($arr->satkerid ?? $arr->balaiid)->nama_instansi,

            ];
        }, $dataDokumen);

        return $this->respond([
            'data' => $returnDaata
        ]);
    }



    public function listSatkerBalai()
    {
        if ($this->user['user_type'] == 'other') {
            $createByAdmin = $this->session->get('createDokumenByAdmin');

            $session_balaiId    = $createByAdmin['byAdmin_balai_id'] ?? null;
        } else {
            $session_balaiId    = $this->user['balai_id'] ?? null;
        }

        $balai_checklistSatker = [];
        $balai_checklistSatker = $this->satker->select("
            m_satker.satker,
            (SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status!='setuju' ) as iscreatedPKBeforeAcc,
            (SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status='setuju' ) as iscreatedPK
        ")
            ->where('balaiid', $session_balaiId)->get()->getResult();


        return $this->respond([
            'data' => $balai_checklistSatker
        ]);
    }



    public function getTemplate($id)
    {
        $checkCreateFromBalai = $this->session->get('createDokumenByBalai');
        if ($this->user['user_type'] == 'other' || isset($checkCreateFromBalai)) {
            $createByAdmin = $this->session->get('createDokumenByAdmin');

            $session_userType   = $createByAdmin['byAdmin_user_type'] ?? null;
            $session_satkerNama = $createByAdmin['byAdmin_satker_nama'] ?? null;
            $session_balaiNama  = $createByAdmin['byAdmin_balai_nama'] ?? null;
            $session_satkerId   = $createByAdmin['byAdmin_satker_id'] ?? null;
            $session_balaiId    = $createByAdmin['byAdmin_balai_id'] ?? null;
        } else {
            $session_userType   = $this->user['user_type'];
            $session_satkerNama = $this->user['satker_nama'] ?? null;
            $session_balaiNama  = $this->user['balai_nama'] ?? null;
            $session_satkerId   = $this->user['satker_id'] ?? null;
            $session_balaiId    = $this->user['balai_id'] ?? null;
        }

        $pihak1        = '';
        $pihak2        = '';
        $kotaNama      = '';
        $jabatanPihak2 = '';

        if ($session_userType == "satker") {
            $dataSatker = $this->satker->select("jabatan_penanda_tangan_pihak_1, jabatan_penanda_tangan_pihak_2, kota_penanda_tangan")->where('satkerid', $session_satkerId)->get()->getRow();
            $pihak1 = $dataSatker->jabatan_penanda_tangan_pihak_1;
            $pihak2 = $dataSatker->jabatan_penanda_tangan_pihak_2;
            $kotaNama = $dataSatker->kota_penanda_tangan;
            // $sessions = array("satkerid" => $session_satkerId);
        } elseif ($session_userType == "balai") {
            $dataBalai = $this->balai->select("jabatan_penanda_tangan_pihak_1, jabatan_penanda_tangan_pihak_2, kota_penanda_tangan")->where('balaiid', $session_balaiId)->get()->getRow();

            $pihak1 = $dataBalai->jabatan_penanda_tangan_pihak_1;
            $kotaNama = $dataBalai->kota_penanda_tangan;
            $jabatanPihak2 = $dataBalai->jabatan_penanda_tangan_pihak_2;
            // $sessions = array("balaiid" => $session_balaiId);
        }

        $dokumenExistSameYear = $this->dokumenSatker->select("
            id as last_dokumen_id,
            IFNULL (revision_master_dokumen_id, id) AS revision_master_dokumen_id
        ")
            ->where('template_id', $id)
            ->where('satkerid', $session_satkerId)
            ->where('balaiid', $session_balaiId)
            ->where("status != ", 'revision')
            ->where("tahun = YEAR(CURDATE())")->orderBy('id', 'desc')->get()->getRow();

        $templateDokumen = $this->templateDokumen->where('id', $id)->get()->getRow();

        $templateRows = array_map(function ($arr) use ($session_userType, $session_balaiId, $templateDokumen) {
            $targetDefualtValue = '';

            if ($session_userType == "balai") {
                $templateRowRumus = $this->templateRowRumus->select('rumus')->where(['template_id' => $arr->template_id, 'rowId' => $arr->id])->get()->getResult();

                foreach ($templateRowRumus as $key => $data) {
                    $targetRumusOutcome = $this->dokumenSatker->select(
                        'dokumenpk_satker_rows.outcome_value'
                    )
                        ->join('dokumenpk_satker_rows', 'dokumenpk_satker.id = dokumenpk_satker_rows.dokumen_id', 'left')
                        ->join('dokumen_pk_template_rowrumus', "(dokumenpk_satker.template_id=dokumen_pk_template_rowrumus.template_id AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus.rowId)", 'left')
                        ->where('dokumen_pk_template_rowrumus.rumus', $data->rumus)
                        ->where('dokumenpk_satker.balaiid', $session_balaiId)
                        ->where('dokumenpk_satker.status', 'setuju')
                        ->where('dokumenpk_satker.tahun', $this->user['tahun'])
                        ->where('dokumenpk_satker_rows.is_checked', '1')
                        ->get()->getResult();

                    $outcomeRumus = 0;
                    foreach ($targetRumusOutcome as $keyOutcome => $dataOutcome) {
                        $outcomeRumus += $dataOutcome ? ($dataOutcome->outcome_value != '' ? $dataOutcome->outcome_value : 0) : 0;
                    }

                    if ($targetDefualtValue == '' && $outcomeRumus > 0) $targetDefualtValue = 0;

                    if ($outcomeRumus > 0) $targetDefualtValue += $outcomeRumus;
                }
            }

            if ($templateDokumen->type == 'eselon1') {
                $templateRowRumus = $this->templateRowRumus->select('rumus')->where(['template_id' => $arr->template_id, 'rowId' => $arr->id])->get()->getResultArray();

                $rumusRow         = implode(',', array_column($templateRowRumus, 'rumus'));
                $rumusPersenBalai = false;

                if (strpos($rumusRow, '%balai')) {
                    $rumusPersenBalai = true;
                    $rumusRow = str_replace(',%balai', '', $rumusRow);
                }

                $targetBalaiRow = $this->dokumenSatker->select('
                    dokumenpk_satker.id,
                    dokumenpk_satker.template_id,
                    dokumenpk_satker_rows.target_value,
                    (
                        SELECT 
                            group_concat(rumus) 
                        FROM 
                            dokumen_pk_template_rowrumus 
                        WHERE 
                            dokumenpk_satker.template_id = dokumen_pk_template_rowrumus.template_id 
                            AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus.rowId
                    ) as rumus
                ')
                    ->join('dokumenpk_satker_rows', 'dokumenpk_satker.id = dokumenpk_satker_rows.dokumen_id', 'left')
                    ->where('dokumenpk_satker.status', 'setuju')
                    ->where('dokumenpk_satker.tahun', $this->user['tahun'])
                    ->where('dokumenpk_satker_rows.is_checked', '1')
                    ->where('dokumenpk_satker.pihak2_initial', 'DIREKTUR JENDERAL SUMBER DAYA AIR')
                    ->where("
                    (
                        SELECT 
                            group_concat(rumus) 
                        FROM 
                            dokumen_pk_template_rowrumus 
                        WHERE 
                            dokumenpk_satker.template_id = dokumen_pk_template_rowrumus.template_id 
                            AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus.rowId
                    ) = '$rumusRow'
                ")
                    ->get()->getResult();
                // print_r($targetBalaiRow);
                // print_r($rumusRow);

                $targetRumus = 0;
                foreach ($targetBalaiRow as $key => $value) {
                    $targetRumus += $value ? ($value->target_value != '' ? $value->target_value : 0) : 0;
                }

                if ($targetDefualtValue == '' && $targetRumus > 0) $targetDefualtValue = 0;

                if ($rumusPersenBalai) {
                    $jumlahBalai = $this->balai->where('kota_penanda_tangan !=', '')->countAllResults();
                    $targetRumus = round($targetRumus / $jumlahBalai, 3);
                    $rumusPersenBalai = false;
                }

                if ($targetRumus > 0) $targetDefualtValue += $targetRumus;
            }

            return [
                'id'                 => $arr->id,
                'template_id'        => $arr->template_id,
                'prefix_title'       => $arr->prefix_title,
                'title'              => $arr->title,
                'target_satuan'      => $arr->target_satuan,
                'outcome_satuan'     => $arr->outcome_satuan,
                'type'               => $arr->type,
                'targetDefualtValue' => $targetDefualtValue
            ];
        }, $this->templateRow->where('template_id', $id)->get()->getResult());


        $valudasiCreatedDokumen = true;
        $balai_checklistSatker = [];
        if ($session_userType == 'balai') {
            $balai_checklistSatker = $this->satker->select("
                m_satker.satker,
                (SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status='setuju' ) as iscreatedPK
            ")
                ->where('balaiid', $session_balaiId)->get()->getResult();

            $totalSatkerIsCreated = count(array_filter($balai_checklistSatker, function ($arr) {
                return $arr->iscreatedPK > 0;
            }));
            if (count($balai_checklistSatker) != $totalSatkerIsCreated) $valudasiCreatedDokumen = false;
        }


        return $this->respond([
            'dokumenExistSameYear' => $dokumenExistSameYear,
            'template'             => $templateDokumen,
            'templateRow'          => $templateRows,
            'templateKegiatan'     => $this->templateKegiatan->where('template_id', $id)->get()->getResult(),
            'templateInfo'         => $this->templateInfo->where('template_id', $id)->get()->getResult(),
            'kegiatan'             => $this->kegiatan->get()->getResult(),
            'penandatangan'        => [
                'pihak1' => $pihak1,
                'pihak2' => $pihak2
            ],
            'kota'  => $this->kota->get()->getResult(),
            'bulan' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            'tahun' => $this->user['tahun'],
            'templateExtraData' => [
                'kotaNama'      => $kotaNama,
                'jabatanPihak2' => $jabatanPihak2
            ],
            'balaiValidasiSatker' => [
                'valudasiCreatedDokumen' => $valudasiCreatedDokumen,
                'balaiChecklistSatker'   => $balai_checklistSatker,
            ]
        ]);
    }



    public function checkDocumentSameYearExist($_createdYear, $_templateId)
    {
        $checkCreateFromBalai = $this->session->get('createDokumenByBalai');

        if ($this->user['user_type'] == 'other' || isset($checkCreateFromBalai)) {
            $createByAdmin = $this->session->get('createDokumenByAdmin');

            $session_userType   = $createByAdmin['byAdmin_user_type'] ?? null;
            $session_satkerNama = $createByAdmin['byAdmin_satker_nama'] ?? null;
            $session_balaiNama  = $createByAdmin['byAdmin_balai_nama'] ?? null;
            $session_satkerId   = $createByAdmin['byAdmin_satker_id'] ?? null;
            $session_balaiId    = $createByAdmin['byAdmin_balai_id'] ?? null;
        } else {
            $session_userType   = $this->user['user_type'];
            $session_satkerNama = $this->user['satker_nama'] ?? null;
            $session_balaiNama  = $this->user['balai_nama'] ?? null;
            $session_satkerId   = $this->user['satker_id'] ?? null;
            $session_balaiId    = $this->user['balai_id'] ?? null;
        }

        $dokumenExistSameYear = $this->dokumenSatker->select("
            id as last_dokumen_id,
            IFNULL (revision_master_dokumen_id, id) AS revision_master_dokumen_id
        ")->where('template_id', $_templateId)
            ->where('satkerid', $session_satkerId)
            ->where('balaiid', $session_balaiId)
            ->where("status != ", 'revision')
            ->where("tahun = '$_createdYear'")->orderBy('id', 'desc')->get()->getRow();

        $dokumentType = $this->templateDokumen->select("type")->where('id', $_templateId)->get()->getRow();


        return $this->respond([
            'dokumenExistSameYear' => $dokumenExistSameYear,
            'dokumen_type' => $dokumentType->type
        ]);
    }



    public function show($id)
    {
        return $this->respond([
            'dokumen'  => $this->dokumenSatker->where('id', $id)->get()->getRow(),
            'rows'     => $this->dokumenSatker_rows->where('dokumen_id', $id)->get()->getResult(),
            'kegiatan' => $this->dokumenSatker_kegiatan->where('dokumen_id', $id)->get()->getResult()
        ]);
    }



    public function getListRevisioned($id)
    {
        $dokumenList = $this->dokumenSatker->select('
            id,
            revision_master_number,
            revision_number,
            status,
            is_revision_same_year
        ')
            ->where('revision_master_dokumen_id', $id)
            ->orWhere('id', $id)
            ->orderBy('id', 'DESC')
            ->get()->getResult();

        return $this->respond([
            'dokumenList' => $dokumenList
        ]);
    }



    public function create()
    {
        $createByAdmin = $this->session->get('createDokumenByAdmin');
        $replacements = [
            "." => "",
            "," => ".",
        ];

        // print_r($createByAdmin);exit;

        if ($this->user['user_type'] == 'other' || isset($createByAdmin)) {
            $session_userType   = $createByAdmin['byAdmin_user_type'];
            $session_satkerNama = $createByAdmin['byAdmin_satker_nama'] ?? null;
            $session_balaiNama  = $createByAdmin['byAdmin_balai_nama'] ?? null;
            $session_satkerId   = $createByAdmin['byAdmin_satker_id'] ?? null;
            $session_balaiId    = $createByAdmin['byAdmin_balai_id'] ?? null;

            $this->session->remove('createDokumenByAdmin');
        } else {
            $session_userType   = $this->user['user_type'];
            $session_satkerNama = $this->user['satker_nama'] ?? null;
            $session_balaiNama  = $this->user['balai_nama'] ?? null;
            $session_satkerId   = $this->user['satker_id'] ?? null;
            $session_balaiId    = $this->user['balai_id'] ?? null;
        }


        /* dokumen */
        $dataTemplateDokumen = $this->templateDokumen->select('type')->where('id', $this->request->getPost('templateID'))->get()->getRow();

        $inserted_dokumenSatker = [
            'template_id'           => $this->request->getPost('templateID'),
            'user_created'          => $this->userUID,
            'total_anggaran'        => strtr($this->request->getPost('totalAnggaran'), $replacements),
            'pihak1_ttd'            => $this->request->getPost('ttdPihak1'),
            'pihak1_is_plt'         => $this->request->getPost('ttdPihak1_isPlt'),
            'pihak2_ttd'            => $this->request->getPost('ttdPihak2'),
            'pihak2_is_plt'         => $this->request->getPost('ttdPihak2_isPlt'),
            'is_revision_same_year' => $this->request->getPost('revisionSameYear'),
            'kota'                  => $this->request->getPost('kota'),
            'kota_nama'             => $this->request->getPost('kotaNama'),
            'bulan'                 => $this->request->getPost('bulan'),
            'tahun'                 => $this->request->getPost('tahun')
        ];

        if ($session_userType == "satker") {
            $dataSatker = $this->satker->select("jabatan_penanda_tangan_pihak_1, jabatan_penanda_tangan_pihak_2, kota_penanda_tangan")->where('satkerid', $session_satkerId)->get()->getRow();

            $inserted_dokumenSatker['pihak1_id']      = $session_satkerId;
            $inserted_dokumenSatker['pihak1_initial'] = $dataSatker->jabatan_penanda_tangan_pihak_1;
            $inserted_dokumenSatker['pihak2_id']      = $session_balaiId;
            $inserted_dokumenSatker['pihak2_initial'] = $dataSatker->jabatan_penanda_tangan_pihak_2;
            $inserted_dokumenSatker['dokumen_type']   = $dataTemplateDokumen->type;
            $inserted_dokumenSatker['balaiid']        = $session_balaiId;
            $inserted_dokumenSatker['satkerid']       = $session_satkerId;
        } elseif ($session_userType == "balai") {
            $dataBalai = $this->balai->select("jabatan_penanda_tangan_pihak_1, jabatan_penanda_tangan_pihak_2, kota_penanda_tangan")->where('balaiid', $session_balaiId)->get()->getRow();

            $inserted_dokumenSatker['pihak1_id']      = $session_balaiId;
            $inserted_dokumenSatker['pihak1_initial'] = $dataBalai->jabatan_penanda_tangan_pihak_1;
            $inserted_dokumenSatker['dokumen_type']   = "balai";
            $inserted_dokumenSatker['balaiid']        = $session_balaiId;
        }

        if ($this->request->getPost('ttdPihak2Jabatan')) $inserted_dokumenSatker['pihak2_initial'] = $this->request->getPost('ttdPihak2Jabatan');


        if ($this->request->getPost('revision_dokumen_id')) {
            $revision_dokumenID       = $this->request->getPost('revision_dokumen_id');
            $revision_dokumenMasterID = $this->request->getPost('revision_dokumen_master_id');

            $inserted_dokumenSatker['revision_dokumen_id']        = $revision_dokumenID;
            $inserted_dokumenSatker['revision_master_dokumen_id'] = $revision_dokumenMasterID;

            $inserted_dokumenSatker['revision_master_number'] = $this->dokumenSatker->selectCount('id')->where('revision_master_dokumen_id', $revision_dokumenMasterID)->orWhere('id', $revision_dokumenMasterID)->get()->getFirstRow()->id;

            if ($this->request->getPost('revisionSameYear') == '1') {
                $lastRevisionSameYearNumber = $this->dokumenSatker->select('revision_same_year_number')->groupStart()
                    ->where('revision_master_dokumen_id', $revision_dokumenMasterID)
                    ->orWhere('id', $revision_dokumenMasterID)
                    ->groupEnd()
                    ->where('is_revision_same_year', '1')
                    ->orderBy('id', 'DESC')
                    ->get()->getFirstRow();

                $inserted_dokumenSatker['revision_same_year_number'] = $lastRevisionSameYearNumber ? $lastRevisionSameYearNumber->revision_same_year_number + 1 : 1;
                $inserted_dokumenSatker['revision_number'] = '0';
            } else {
                $inserted_dokumenSatker['revision_number'] = $this->dokumenSatker->select('revision_number')->where('revision_master_dokumen_id', $revision_dokumenMasterID)
                    ->orWhere('id', $revision_dokumenMasterID)
                    ->orderBy('id', 'DESC')
                    ->get()->getFirstRow()->revision_number + 1;
            }

            $this->dokumenSatker->update([
                'status' => 'revision'
            ], [
                'id' => $revision_dokumenID
            ]);
        }

        $this->dokumenSatker->insert($inserted_dokumenSatker);
        $dokumenID = $this->db->insertID();
        /** end-of: dokumen */

        /* dokumen rows */
        $this->insertDokumenSatker_rows($this->request->getPost(), $dokumenID);
        /** end-of: dokumen rows */

        /** dokumen kegiatan */
        $this->insertDokumenSatker_kegiatan($this->request->getPost(), $dokumenID);
        /** end-of: dokumen kegiatan */

        return $this->respond([
            'status' => true,
        ]);
    }



    public function edit()
    {
        $dokumenID = $this->request->getPost('id');

        $replacements = [
            "." => "",
            "," => ".",
        ];


        /* dokumen */
        $inserted_dokumenSatker = [
            'template_id'           => $this->request->getPost('templateID'),
            'total_anggaran'        => strtr($this->request->getPost('totalAnggaran'), $replacements),
            'pihak1_ttd'            => $this->request->getPost('ttdPihak1'),
            'pihak1_is_plt'         => $this->request->getPost('ttdPihak1_isPlt'),
            'pihak2_ttd'            => $this->request->getPost('ttdPihak2'),
            'pihak2_is_plt'         => $this->request->getPost('ttdPihak2_isPlt'),
            'kota'                  => $this->request->getPost('kota'),
            'kota_nama'             => $this->request->getPost('kotaNama'),
            'bulan'                 => $this->request->getPost('bulan'),
            'tahun'                 => $this->request->getPost('tahun')
        ];

        if ($this->request->getPost('ttdPihak2Jabatan')) $inserted_dokumenSatker['pihak2_initial'] = $this->request->getPost('ttdPihak2Jabatan');

        $this->dokumenSatker->where('id', $dokumenID);
        $this->dokumenSatker->update($inserted_dokumenSatker);
        /** end-of: dokumen */


        /* dokumen rows */
        $this->dokumenSatker_rows->where('dokumen_id', $dokumenID);
        $this->dokumenSatker_rows->delete();
        $this->insertDokumenSatker_rows($this->request->getPost(), $dokumenID);
        /** end-of: dokumen rows */

        /** dokumen kegiatan */
        $this->dokumenSatker_kegiatan->where('dokumen_id', $dokumenID);
        $this->dokumenSatker_kegiatan->delete();
        $this->insertDokumenSatker_kegiatan($this->request->getPost(), $dokumenID);
        /** end-of: dokumen kegiatan */

        return $this->respond([
            'status' => true
        ]);
    }







    private function insertDokumenSatker_rows($input, $_dokumenID)
    {
        $rows = array_map(function ($arr) use ($_dokumenID) {
            return [
                'dokumen_id'      => $_dokumenID,
                'template_row_id' => $arr['id'],
                'target_value'    => str_replace(',', '.', $arr['target']),
                'outcome_value'   => str_replace(',', '.', $arr['outcome']),
                'is_checked'      => $arr['isChecked']
            ];
        }, $input['rows']);
        $this->dokumenSatker_rows->insertBatch($rows);
    }



    private function insertDokumenSatker_kegiatan($input, $_dokumenID)
    {
        $records = array_map(function ($arr) use ($_dokumenID) {
            return [
                'dokumen_id' => $_dokumenID,
                'id'         => $arr['id'],
                'nama'       => $arr['nama'],
                'anggaran'   => strtr($arr['anggaran'], [
                    "." => "",
                    "," => ".",
                ])
            ];
        }, $input['kegiatan']);
        $this->dokumenSatker_kegiatan->insertBatch($records);
    }

    public function panduanpk()
    {

        return view('Modules\Satker\Views\PanduanPK.php', [
            'title'             => "Panduan Perjanjian Kinerja Satker",
            'sessionYear'       => $this->user['tahun'],
            'session'           => $this->user['user_type']
        ]);
    }
}
