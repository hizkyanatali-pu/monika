<?php

namespace Modules\Satker\Controllers;

use CodeIgniter\API\ResponseTrait;
// use App\Libraries\FPDF;
use App\Libraries\FPDF_PROTEC as FPDF;
use Dompdf\Dompdf;

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
        $this->dokumenSatker_paket     = $this->db->table('dokumenpk_satker_paket');
        $this->dokumenSatker_kegiatan = $this->db->table('dokumenpk_satker_kegiatan');

        $this->templateDokumen  = $this->db->table('dokumen_pk_template_' . session('userData.tahun'));
        $this->templateRow      = $this->db->table('dokumen_pk_template_row_' . session('userData.tahun'));
        $this->templateRowRumus = $this->db->table('dokumen_pk_template_rowrumus_' . session('userData.tahun'));
        $this->templateKegiatan = $this->db->table('dokumen_pk_template_kegiatan_' . session('userData.tahun'));
        $this->templateInfo     = $this->db->table('dokumen_pk_template_info_' . session('userData.tahun'));

        $this->satker   = $this->db->table('m_satker');
        $this->balai    = $this->db->table('m_balai');
        $this->kegiatan = $this->db->table('tgiat');
        $this->program = $this->db->table('tprogram');


        $this->kota = $this->db->table('tkabkota');
        $this->kuUser = $this->db->table('ku_user');

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
        $isEselon1 = false;
        $listSatkerCreateCokumen = false;
        $this->session->remove('createDokumenByBalai');

        $query_dataDokumen = $this->dokumenSatker->select('
            dokumenpk_satker.id,
            dokumenpk_satker.template_id,
            dokumenpk_satker.revision_master_dokumen_id,
            dokumenpk_satker.revision_master_number,
            dokumenpk_satker.revision_number,
            dokumenpk_satker.status,
            (CASE
            WHEN dokumenpk_satker.acc_by IS NULL THEN dokumenpk_satker.reject_by
            ELSE dokumenpk_satker.acc_by
            END) AS verif_by,
            
            dokumenpk_satker.is_revision_same_year,
            dokumenpk_satker.change_status_at,
            dokumenpk_satker.created_at,
            dokumen_pk_template_' . session('userData.tahun') . '.title as dokumenTitle
        ')
            ->join('dokumen_pk_template_' . session('userData.tahun'), 'dokumenpk_satker.template_id = dokumen_pk_template_' . session('userData.tahun') . '.id', 'left')
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
                    if ($this->user['satker_id'] == '000000') $isEselon1 = true;
                    break;

                case 'balai':
                    $template_type    = 'master-balai';
                    $templae_revTable = 'm_balai';
                    $template_revID   = $this->user['balai_id'];
                    $listSatkerCreateCokumen = true;
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

            if (!$dataTemplate) $dataTemplate = [];

            goto returnSection;
        }

        globalUserTemplate:
        if (isset($this->user['user_type'])) $this->templateDokumen->where('type', $this->user['user_type']);
        $dataTemplate = $this->templateDokumen->where('dokumen_pk_template_' . session('userData.tahun') . '.status', '1')->where("deleted_at is null")->get()->getResult();

        returnSection:

        return view('Modules\Satker\Views\Dokumenpk.php', [
            'user_type'             =>  $this->user['user_type'] ?? "",
            'title'             => "Perjanjian Kinerja Balai",
            'sessionYear'       => $this->user['tahun'],
            'templateDokumen'   => $dataTemplate,
            'templateAvailable' => count($dataTemplate) > 0 ? 'true' : 'false',
            'isCanCreated'      => true,
            'isCanConfirm'      => false,
            'isEselon1'         => $isEselon1,

            'listSatkerCreateCokumen' => $listSatkerCreateCokumen,

            'dataDokumen'   => $dataDokumen,
            'dokumenStatus' => $this->dokumenStatus
        ]);
    }



    public function balaiSatker($_satkerId)
    {
        /** 
         * Jangan di komen !
         * fungsi berikut merupakan pembuatan session untuk mengetahu bahwa dokumen tersebut di buat oleh balai
         */
        $setSession_userData['byBalai_user_type']    = 'upt-balai';
        $this->session->set('createDokumenByBalai', $setSession_userData);
        /* */

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
            (CASE
            WHEN dokumenpk_satker.acc_by IS NULL THEN dokumenpk_satker.reject_by
            ELSE dokumenpk_satker.acc_by
            END) AS verif_by,
            dokumen_pk_template_' . session('userData.tahun') . '.title as dokumenTitle,
            ku_user.nama as userCreatedName
        ')
            ->join('dokumen_pk_template_' . session('userData.tahun'), 'dokumenpk_satker.template_id = dokumen_pk_template_' . session('userData.tahun') . '.id', 'left')
            ->join('ku_user', 'dokumenpk_satker.user_created=ku_user.uid', 'left')
            ->where('dokumenpk_satker.status !=', 'revision')
            ->where('dokumenpk_satker.dokumen_type', 'satker')
            ->where("dokumenpk_satker.tahun", $this->user['tahun'])
            ->where("dokumenpk_satker.deleted_at is null")
            ->orderBy('dokumenpk_satker.id', 'DESC');

        if ($_satkerId == 'all') {
            $queryDataDokumen->where('dokumenpk_satker.balaiid', $this->user['balaiid'])->where("dokumenpk_satker.deleted_at is null");

            $dataTemplate = [];
            $isCanCreated = false;
        } else {
            $queryDataDokumen->where('dokumenpk_satker.satkerid', $_satkerId);

            $dataTemplate = $this->templateDokumen->select('dokumen_pk_template_' . session('userData.tahun') . '.*')
                ->join('dokumen_pk_template_akses_' . session('userData.tahun'), 'dokumen_pk_template_' . session('userData.tahun') . '.id = dokumen_pk_template_akses_' . session('userData.tahun') . '.template_id', 'left')
                ->where('dokumen_pk_template_' . session('userData.tahun') . '.status', '1')
                ->where('dokumen_pk_template_akses_' . session('userData.tahun') . '.rev_id', $_satkerId)
                // ->where('dokumen_pk_template.type', $template_type)
                ->where('dokumen_pk_template_akses_' . session('userData.tahun') . '.rev_table', 'm_satker')
                ->where("deleted_at is null")
                ->groupBy('dokumen_pk_template_' . session('userData.tahun') . '.id')
                ->get()->getResult();

            $isCanCreated = true;
        }

        $dataDokumen = $queryDataDokumen->get()->getResult();



        return view('Modules\Satker\Views\Dokumenpk.php', [
            'title' => 'Perjanjian Kinerja Satker',


            'user_type'             =>  $this->user['user_type'] ?? "",
            'sessionYear'             => $this->user['tahun'],
            'templateDokumen'         => $dataTemplate,
            'templateAvailable'       => count($dataTemplate) > 0 ? 'true' : 'false',
            'isCanCreated'            => $isCanCreated,
            'isCanConfirm'            => true,
            'isEselon1'               => false,
            'listSatkerCreateCokumen' => true,

            'filterSatker'          => $this->satker->where('balaiid', $this->user['balaiid'])->get()->getResult(),
            'filterSatker_selected' => $_satkerId,

            'dataDokumen'   => $dataDokumen,
            'dokumenStatus' => $this->dokumenStatus,

            'balaiCreateForSatker' =>  $_satkerId
        ]);
    }



    public function dataDokumenSatker($_status, $_dokumenType, $instansi = '')
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
            dokumen_pk_template_' . session('userData.tahun') . '.title as dokumenTitle,
            ku_user.nama as userCreatedName,
            dokumenpk_satker.satkerid,
            dokumenpk_satker.balaiid
            
        ')
            ->join('dokumen_pk_template_' . session('userData.tahun'), 'dokumenpk_satker.template_id = dokumen_pk_template_' . session('userData.tahun') . '.id', 'left')
            ->join('ku_user', 'dokumenpk_satker.user_created = ku_user.uid', 'left')
            ->where('dokumen_pk_template_' . session('userData.tahun') . '.status', '1')
            ->where('dokumenpk_satker.status', $_status)
            ->where('dokumenpk_satker.dokumen_type', $_dokumenType)
            ->where("dokumenpk_satker.deleted_at is null")
            ->where("dokumenpk_satker.tahun", $this->user['tahun']);
        if ($instansi) {
            $dataDokumen->where("dokumenpk_satker.satkerid", $instansi);
        }

        $dataDokumen = $dataDokumen->orderBy('dokumenpk_satker.id', 'DESC')
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



    public function dataBelumInput($type = '')
    {
        $returnData = [];

        if ($type == 'satker') {
            $dataBelumInput = $this->satker->select("
                m_satker.satker
            ")
                ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='satker' and satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun= {$this->user['tahun']}) < 1")
                ->get()->getResult();

            $returnData = array_map(function ($arr) {
                return [
                    'nama' => $arr->satker
                ];
            }, $dataBelumInput);
        }

        if ($type == 'balai') {
            $dataBelumInput = $this->balai->select("
                balai
            ")
                ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='balai' and balaiid=m_balai.balaiid and tahun={$this->user['tahun']} and status='setuju') < 1 AND kota_penanda_tangan != ''")
                ->get()->getResult();

            $returnData = array_map(function ($arr) {
                return [
                    'nama' => $arr->balai
                ];
            }, $dataBelumInput);
        } else {


            $dataBelumInput = $this->satker->select("
                    m_satker.satker
                ")
                ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='" . $type . "' and satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun= {$this->user['tahun']}) < 1 and m_satker.grup_jabatan='{$type}'")
                ->get()->getResult();

            $returnData = array_map(function ($arr) {
                return [
                    'nama' => $arr->satker
                ];
            }, $dataBelumInput);
        }

        return $this->respond([
            'data' => $returnData
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
        // $balai_checklistSatker = $this->satker->select("
        //     m_satker.satker,
        //     (SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status!='setuju'  order by created_at DESC limit 1 ) as iscreatedPKBeforeAcc,
        //     (SELECT 
        //         CASE
        //             WHEN status = 'hold' THEN 'Menunggu Konfirmasi'
        //             WHEN status = 'tolak' THEN 'Ditolak'
        //             WHEN status = 'revision' THEN 'Telah Di Koreksi'
        //         END
        //     FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status!='setuju' ORDER BY created_at DESC LIMIT 1 ) as status_now,
        //     (SELECT 
        //         CASE
        //             WHEN status = 'hold' THEN 'bg-secondary'
        //             WHEN status = 'tolak' THEN 'bg-danger text-white'
        //             WHEN status = 'revision' THEN 'bg-dark text-white'
        //         END
        //         FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status!='setuju'  ORDER BY created_at DESC LIMIT 1) as status_color,
        //     (SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status='setuju' ) as iscreatedPK
        // ")
        //     ->where('balaiid', $session_balaiId)->get()->getResult();


        $balai_checklistSatker = $this->satker->select("
            m_satker.satker,m_satker.satkerid
        ")
            ->where('balaiid', $session_balaiId)->get()->getResult();



        $list = [];
        foreach ($balai_checklistSatker as $data) {
            // perubahan kode satker SNVT PELAKSANAAN JARINGAN SUMBER AIR WS BATANGHARI PROVINSI SUMATERA BARAT dan SNVT PEMBANGUNAN BENDUNGAN BBWS CIMANUK CISANGGARUNG (2024)
            if (($data->satkerid != "498366" and $data->satkerid != "498077") and $this->user['tahun'] > 2023) {

                $dokPK = $this->dokumenSatker->select("satkerid,status")
                    ->where('tahun', $this->user['tahun'])
                    ->where('satkerid', $data->satkerid)
                    ->where('deleted_at', null)
                    ->where('status !=', 'revision')
                    ->orderBy('id', 'DESC')
                    ->get()->getRow();

                $list[] = [
                    'satker' => $data->satker,
                    'satkerCheck' =>  $dokPK->status ?? ''
                ];
            } elseif (
                ($data->satkerid != "690680" and $data->satkerid != "633074" and $this->user['tahun'] < 2024)
            ) {
                $dokPK = $this->dokumenSatker->select("satkerid,status")
                    ->where('tahun', $this->user['tahun'])
                    ->where('satkerid', $data->satkerid)
                    ->where('deleted_at', null)
                    ->where('status !=', 'revision')
                    ->orderBy('id', 'DESC')
                    ->get()->getRow();

                $list[] = [
                    'satker' => $data->satker,
                    'satkerCheck' =>  $dokPK->status ?? ''
                ];
            }
        }

        return $this->respond([
            'data' => $list
        ]);
    }



    public function getTemplate($id, $iddoc = null)
    {

        $sessionYear = $this->user['tahun'];
        $checkCreateFromBalai = $this->session->get('createDokumenByBalai');
        if ($this->user['user_type'] == 'other' || isset($checkCreateFromBalai)) {

            $idbalai = $this->dokumenSatker->select("
            balaiid,satkerid
        ")
                ->where('id', $iddoc)
                ->where("status != ", 'revision')
                ->where("deleted_at is null")
                ->where("tahun", $sessionYear)->orderBy('id', 'desc')->get()->getRow();

            // kode satker 498077 = PJSA BATANGHARI 2024 ganti kode ke 633074
            // kode satker 498366 = Bendungan Cimanuk 2024 ganti kode ke 690690
            if ($idbalai) {
                if ($idbalai->satkerid == "498077" and $sessionYear > 2023) {
                    $idsatker = "633074";
                } elseif ($idbalai->satkerid == "498077" and $sessionYear > 2023) {
                    $idsatker = "690680";
                } else {

                    $idsatker =  $idbalai->satkerid;
                }
            }

            $createByAdmin = $this->session->get('createDokumenByAdmin');
            $session_userType   = $createByAdmin['byAdmin_user_type'] ?? "balai";
            $session_satkerNama = $createByAdmin['byAdmin_satker_nama'] ?? null;
            $session_balaiNama  = $createByAdmin['byAdmin_balai_nama'] ?? null;
            $session_satkerId   = $createByAdmin['byAdmin_satker_id'] ??  $idsatker;
            $session_balaiId    = $createByAdmin['byAdmin_balai_id'] ?? $idbalai->balaiid;
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
            $pihak2 = $dataBalai->jabatan_penanda_tangan_pihak_2;
            // $sessions = array("balaiid" => $session_balaiId);
        }


        $dokumenExistSameYear = $this->dokumenSatker->select("
            id as last_dokumen_id,
            IFNULL (revision_master_dokumen_id, id) AS revision_master_dokumen_id,
            is_revision_same_year,
            tahun_ttd
        ")
            ->where('template_id', $id)
            ->where('satkerid', $session_satkerId)
            ->where('balaiid', $session_balaiId)
            ->where("status != ", 'revision')
            ->where("deleted_at is null")
            ->where("tahun", $sessionYear)->orderBy('id', 'desc')->get()->getRow();

        $templateDokumen = $this->templateDokumen->where('id', $id)->get()->getRow();

        $templateRows = array_map(function ($arr) use ($session_userType, $session_balaiId, $templateDokumen, $dokumenExistSameYear) {
            $outcomeSatkerValue      = 0;
            $Testing_Data            = 0;
            $targetBalaiDefualtValue = 0;
            $outcomeDefaultValue     = 0;
            $targetDefaultValue      = 0;
            $targetSatkerValue      = 0;
            $satkerList = array();
            $rowPaket = array();
            $targetSatuan = '';
            $average = 0;







            if ($dokumenExistSameYear) {
                $rowDokumenExistsValue = $this->dokumenSatker_rows
                    ->where('dokumen_id', $dokumenExistSameYear->last_dokumen_id)
                    ->where('template_row_id', $arr->id)
                    ->get()->getRow();

                $rowPaket = $this->dokumenSatker_paket
                    ->where('dokumen_id', $dokumenExistSameYear->last_dokumen_id)
                    ->where('template_row_id', $arr->id)
                    ->get()->getResult();
            }

            if ($session_userType == "satker") {
                if ($dokumenExistSameYear) {
                    $outcomeSatkerValue = $rowDokumenExistsValue->target_value ?? 0;
                    $outcomeDefaultValue = $rowDokumenExistsValue->outcome_value ?? 0;
                }
            }

            if ($session_userType == "balai") {
                if ($templateDokumen->type == 'satker') {
                    $outcomeSatkerValue = $rowDokumenExistsValue->target_value ?? 0;
                    $outcomeDefaultValue = $rowDokumenExistsValue->outcome_value ?? 0;
                } else {
                    if ($dokumenExistSameYear) {
                        $targetBalaiDefualtValue = $rowDokumenExistsValue->target_value ?? '';
                    }


                    $templateRowRumus = $this->templateRowRumus->select('rumus')->where(['template_id' => $arr->template_id, 'rowId' => $arr->id])->get()->getResult();
                    // print_r($this->db->getLastQuery());
                    // exit;

                    foreach ($templateRowRumus as $key => $data) {
                        $targetRumusOutcome = $this->dokumenSatker->select(
                            'dokumenpk_satker_rows.outcome_value, dokumenpk_satker_rows.target_value, dokumenpk_satker_rows.template_row_id,
                            dokumenpk_satker.satkerid,dokumenpk_satker.id,dokumenpk_satker_rows.target_sat,target_satuan,dokumenpk_satker_rows.outcome_sat,outcome_satuan'
                        )
                            ->join('dokumenpk_satker_rows', 'dokumenpk_satker.id = dokumenpk_satker_rows.dokumen_id', 'left')
                            ->join('dokumen_pk_template_row_' . session('userData.tahun'), "(dokumenpk_satker_rows.template_row_id=dokumen_pk_template_row_" . session('userData.tahun') . ".id)", 'left')
                            ->join('dokumen_pk_template_rowrumus_' . session('userData.tahun'), "(dokumenpk_satker.template_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".template_id AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".rowId)", 'left')
                            ->where('dokumen_pk_template_rowrumus_' . session('userData.tahun') . '.rumus', $data->rumus)
                            ->where('dokumenpk_satker.balaiid', $session_balaiId)
                            ->where('dokumenpk_satker.status', 'setuju')
                            ->where('dokumenpk_satker.satkerid is not null')
                            ->where('dokumenpk_satker.deleted_at is null')
                            ->where('dokumenpk_satker.tahun', $this->user['tahun'])
                            ->where('dokumenpk_satker_rows.is_checked', '1')
                            ->get()->getResult();

                        // print_r($this->db->getLastQuery());
                        // exit;

                        $outcomeRumus = 0;
                        $outputRumus = 0;

                        foreach ($targetRumusOutcome as $keyOutcome => $dataOutcome) {
                            $outputRumus += $dataOutcome ? ($dataOutcome->target_value != '' ? $dataOutcome->target_value : 0) : 0;
                            $outcomeRumus += $dataOutcome ? ($dataOutcome->outcome_value != '' ? $dataOutcome->outcome_value : 0) : 0;

                            // print_r($outcomeRumus . " , \n");


                            if (!in_array($dataOutcome->satkerid, $satkerList)) {
                                array_push($satkerList, $dataOutcome->satkerid);
                            }

                            $hasilPaket = $this->dokumenSatker_paket->select('dokumenpk_satker_paket.*,dokumenpk_satker.satkerid')
                                ->join('dokumenpk_satker', 'dokumenpk_satker.id = dokumenpk_satker_paket.dokumen_id', 'left')
                                ->where('dokumen_id', $dataOutcome->id)
                                ->where('template_row_id', $dataOutcome->template_row_id)
                                ->get()->getResult();

                            //satuan satker
                            $targetSatuan = $dataOutcome->target_sat ?? $dataOutcome->target_satuan;
                            $outcomeSatuan = $dataOutcome->outcome_sat ?? $dataOutcome->outcome_satuan;




                            $rowPaket = array_merge($rowPaket, $hasilPaket);
                        }

                        if ($outcomeSatkerValue == '' && $outcomeRumus > 0) $outcomeSatkerValue = 0;
                        if ($targetSatkerValue == '' && $outputRumus > 0) $targetSatkerValue = 0;

                        if ($outcomeRumus > 0) {
                            // $outcomeSatkerValue += $outcomeRumus;
                            $satuanOutcomeFix = (strtolower($outcomeSatuan) == "hektar" ? "ha" : strtolower($outcomeSatuan));




                            if ($satuanOutcomeFix == strtolower($arr->target_satuan)) {
                                // print_r($satuanOutcomeFix . " - " . strtolower($arr->target_satuan));
                                $outcomeSatkerValue += $outcomeRumus;
                            }
                            //indikator dukman
                            if ($arr->id == "291011") {
                                // 1 tandanya on 
                                $average  = 1;
                            }
                        }

                        if ($outputRumus > 0) {
                            $target_array = explode(';', $targetSatuan);
                            $targetSatuan = $target_array[0];


                            // target satker yang dijumlahkan hanya satuannya yang sama dengan satuan yang di balai
                            if (strtolower($targetSatuan)  == strtolower($arr->outcome_satuan)) {
                                $targetSatkerValue  += $outputRumus;
                            }

                            //indikator dukman
                            if ($arr->id == "291011") {

                                $targetSatkerValue = 0;
                            }
                        }
                    }
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
                            dokumen_pk_template_rowrumus_' . session('userData.tahun') . '
                        WHERE 
                            dokumenpk_satker.template_id = dokumen_pk_template_rowrumus_' . session('userData.tahun') . '.template_id 
                            AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus_' . session('userData.tahun') . '.rowId
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
                            dokumen_pk_template_rowrumus_" . session('userData.tahun') . "
                        WHERE 
                            dokumenpk_satker.template_id = dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".template_id 
                            AND dokumenpk_satker_rows.template_row_id=dokumen_pk_template_rowrumus_" . session('userData.tahun') . ".rowId
                    ) = '$rumusRow'
                ")
                    ->get()->getResult();
                // print_r($targetBalaiRow);
                // print_r($rumusRow);

                $targetRumus = 0;
                foreach ($targetBalaiRow as $key => $value) {
                    $targetRumus += $value ? ($value->target_value != '' ? $value->target_value : 0) : 0;
                }

                if ($outcomeSatkerValue == '' && $targetRumus > 0) $outcomeSatkerValue = 0;

                if ($rumusPersenBalai) {
                    $jumlahBalai = $this->balai->where('kota_penanda_tangan !=', '')->countAllResults();
                    $targetRumus = round($targetRumus / $jumlahBalai, 3);
                    $rumusPersenBalai = false;
                }

                if ($targetRumus > 0) $outcomeSatkerValue += $targetRumus;
            }



            if ($templateDokumen->type === "master-balai" || $templateDokumen->type === "balai") {

                $rSeparator = explode('.', $average == 1 ?  ($outcomeSatkerValue / 3) :  $outcomeSatkerValue);

                // Inisialisasi $decimalLength dengan nilai default
                $decimalLength = 0;

                // Memeriksa apakah indeks 1 ada dalam array $rSeparator
                if (isset($rSeparator[1])) {
                    $decimalLength = min(2, strlen($rSeparator[1]));

                    if (strtolower($arr->target_satuan) == "m3/detik") {

                        $decimalLength = 4;
                    }
                }
                $outcomeSatkerValue = number_format(($average == 1 ?  ($outcomeSatkerValue / 3) : $outcomeSatkerValue), $decimalLength, ',', '.');
            }


            return [
                'id'                      => $arr->id,
                'template_id'             => $arr->template_id,
                'prefix_title'            => $arr->prefix_title,
                'title'                   => $arr->title,
                'target_satuan'           => $arr->target_satuan,
                'outcome_satuan'          => $arr->outcome_satuan,
                'type'                    => $arr->type,
                'targetSatkerValue'       => $targetSatkerValue,
                // 'outcomeSatkerValue'      => ($average == 1 ? ($outcomeSatkerValue / 3) : $outcomeSatkerValue),
                'outcomeSatkerValue'      => $outcomeSatkerValue,
                'targetSatkerSatuan'       => $targetSatuan,
                'target_balai_value'      => $targetDefaultValue,
                'targetBalaiDefualtValue' => $targetBalaiDefualtValue,
                'outcomeDefaultValue'     => $outcomeDefaultValue,
                'paket'                 => $rowPaket ?? '',
                'listSatker'  => $satkerList

            ];
        }, $this->templateRow->where('template_id', $id)->orderBy('no_urut')->get()->getResult());


        $valudasiCreatedDokumen = true;
        $balai_checklistSatker = [];
        $list = [];

        if ($session_userType == "balai") {
            // $balai_checklistSatker = $this->satker->select("
            //     m_satker.satker,
            //     (SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun={$this->user['tahun']} and status='setuju' ) as iscreatedPK
            // ")
            //     ->where('balaiid', $session_balaiId)->get()->getResult();

            // $totalSatkerIsCreated = count(array_filter($balai_checklistSatker, function ($arr) {
            //     return $arr->iscreatedPK > 0;
            // }));
            // if (count($balai_checklistSatker) == $totalSatkerIsCreated) $valudasiCreatedDokumen = true;

            $balai_checklistSatker = $this->satker->select("
            m_satker.satker,m_satker.satkerid
        ")
                ->where('balaiid', $session_balaiId)->get()->getResult();
            // foreach ($balai_checklistSatker as $data) {

            //     $dokPK = $this->dokumenSatker->select("satkerid,status")
            //         ->where('tahun', $this->user['tahun'])
            //         ->where('satkerid', $data->satkerid)
            //         ->where('deleted_at', null)
            //         ->where('status !=', 'revision')
            //         ->orderBy('id', 'DESC')
            //         ->get()->getRow();

            //     $list[] = [
            //         'satker' => $data->satker,
            //         'satkerCheck' =>  $dokPK->status ?? ''
            //     ];
            // }

            foreach ($balai_checklistSatker as $data) {
                // perubahan kode satker SNVT PELAKSANAAN JARINGAN SUMBER AIR WS BATANGHARI PROVINSI SUMATERA BARAT dan SNVT PEMBANGUNAN BENDUNGAN BBWS CIMANUK CISANGGARUNG (2024)
                if (($data->satkerid != "498366" and $data->satkerid != "498077") and $this->user['tahun'] > 2023) {

                    $dokPK = $this->dokumenSatker->select("satkerid,status")
                        ->where('tahun', $this->user['tahun'])
                        ->where('satkerid', $data->satkerid)
                        ->where('deleted_at', null)
                        ->where('status !=', 'revision')
                        ->orderBy('id', 'DESC')
                        ->get()->getRow();

                    $list[] = [
                        'satker' => $data->satker,
                        'satkerCheck' =>  $dokPK->status ?? ''
                    ];
                } elseif (
                    ($data->satkerid != "690680" and $data->satkerid != "633074" and $this->user['tahun'] < 2024)
                ) {
                    $dokPK = $this->dokumenSatker->select("satkerid,status")
                        ->where('tahun', $this->user['tahun'])
                        ->where('satkerid', $data->satkerid)
                        ->where('deleted_at', null)
                        ->where('status !=', 'revision')
                        ->orderBy('id', 'DESC')
                        ->get()->getRow();

                    $list[] = [
                        'satker' => $data->satker,
                        'satkerCheck' =>  $dokPK->status ?? ''
                    ];
                }
            }

            $valudasiCreatedDokumen = !(in_array('', array_column($list, 'satkerCheck')) || in_array('hold', array_column($list, 'satkerCheck')) || in_array('tolak', array_column($list, 'satkerCheck')));

            // $valudasiCreatedDokumen = array_search('setuju', array_column($list, 'satkerCheck')) === false;
            // $valudasiCreatedDokumen = array_search('', array_column($list, 'satkerCheck')) !== false;
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
            'satkerid' => $session_satkerId,
            'kota'  => $this->kota->get()->getResult(),
            'bulan' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            'tahun' => $dokumenExistSameYear->tahun_ttd ?? $this->user['tahun'],
            'templateExtraData' => [
                'kotaNama'      => $kotaNama,
                'jabatanPihak2' => $jabatanPihak2
            ],
            'balaiValidasiSatker' => [
                'balaiChecklistSatker'   => $list,
                'valudasiCreatedDokumen' => $valudasiCreatedDokumen,
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
            ->where("deleted_at is null")
            ->where("tahun = '$_createdYear'")->orderBy('id', 'desc')->get()->getRow();

        $dokumentType = $this->templateDokumen->select("type")->where('id', $_templateId)->get()->getRow();


        return $this->respond([
            'dokumenExistSameYear' => $dokumenExistSameYear,
            'dokumen_type' => $dokumentType->type
        ]);
    }



    public function show($id)
    {
        $listPesanRevision = [];
        $dataDokumen =  $this->dokumenSatker->where('id', $id)->get()->getRow();

        if (!is_null($dataDokumen->revision_master_dokumen_id)) {
            $dataListRevision = $this->dokumenSatker->where('status', 'revision')
                ->where('id', $dataDokumen->revision_master_dokumen_id)
                ->orWhere('revision_master_dokumen_id', $dataDokumen->revision_master_dokumen_id)
                ->get()->getResult();

            $listPesanRevision = array_map(function ($arr) {
                // var_dump($arr);die;
                $dataPengguna = $this->kuUser->where('uid', $arr->reject_by)->get()->getRow();
                return [
                    'tanggal'       => date_indo($arr->change_status_at) . " " . date("H:i", strtotime($arr->change_status_at)),
                    'pesan'         => $arr->revision_message,
                    'koreksi_by'    => $dataPengguna->nama ?? '',
                ];
            }, $dataListRevision);
        }




        return $this->respond([
            'dokumen'      => $dataDokumen,
            'rows'         => $this->dokumenSatker_rows->where('dokumen_id', $id)->get()->getResult(),
            'paket'         => $this->dokumenSatker_paket->where('dokumen_id', $id)->get()->getResult(),
            'kegiatan'     => $this->dokumenSatker_kegiatan->where('dokumen_id', $id)->get()->getResult(),
            'listRevision' => $listPesanRevision
        ]);
    }



    public function logKoreksi($id)
    {
        $pdf = new Dompdf();
        $dataDokumen =  $this->dokumenSatker->where('id', $id)->get()->getRow();

        if (!is_null($dataDokumen->revision_master_dokumen_id)) {
            $dataListKoreksi = $this->dokumenSatker->where('status', 'revision')
                ->where('id', $dataDokumen->revision_master_dokumen_id)
                ->orWhere('revision_master_dokumen_id', $dataDokumen->revision_master_dokumen_id)
                ->get()->getResult();

            $listPesanRevision = array_map(function ($arr) {
                // var_dump($arr);die;
                $dataPengguna = $this->kuUser->where('uid', $arr->reject_by)->get()->getRow();
                return [
                    'tanggal'       => date_indo($arr->change_status_at),
                    'pesan'         => $arr->revision_message,
                    'koreksi_by'    => $dataPengguna->nama ?? '',
                ];
            }, $dataListKoreksi);
        }

        $data_value = [
            'datakoreksi' => $listPesanRevision,
        ];

        $pdf = new Dompdf();
        $pdf->set_option('isRemoteEnabled', TRUE);
        $pdf->loadHtml(view('Modules\Satker\Views\LogKoreksi', $data_value), 'UTF-8');
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $pdf->stream('log_koreksi.pdf', array('Attachment' => false));
    }




    public function getListRevisioned($id, $feature = false)
    {



        $this->dokumenSatker->select('
            id,
            revision_master_number,
            revision_number,
            status,
            is_revision_same_year,
            created_at
        ');
        $this->dokumenSatker->where('revision_master_dokumen_id', $id);
        if ($feature == false) {

            $this->dokumenSatker->where('deleted_at is null');
        }
        $this->dokumenSatker->orWhere('id', $id);
        $this->dokumenSatker->orderBy('created_at', 'DESC');
        $dokumenList =  $this->dokumenSatker->get()->getResult();
        // Ubah format tanggal ke Indonesia
        foreach ($dokumenList as &$dokumen) {
            $dokumen->created_at = date_indo($dokumen->created_at);
        }

        return $this->respond([
            'dokumenList' => $dokumenList
        ]);
    }



    public function getTgiatForFormPk()
    {
        $search = $_GET['term'] ?? '';
        $exits = $_GET['exists'];
        $info = $_GET['info'];


        if ($exits == "[]") {


            $queryDataGiat = ($info == "KEGIATAN" ? $this->kegiatan->where('tahun_anggaran', $this->user['tahun']) :
                $this->program->whereIn("kdprogram", ["FC", "WA"]));
        } else {
            $queryDataGiat = ($info == "KEGIATAN" ? $this->kegiatan->where('tahun_anggaran', $this->user['tahun'])
                ->whereNotIn('nmgiat', json_decode($exits)) :
                $this->program->whereNotIn('nmprogram', json_decode($exits))->whereIn("kdprogram", ["FC", "WA"]));
        }




        if ($search) {
            $info == "KEGIATAN" ? $queryDataGiat->like('nmgiat', $search) :  $queryDataGiat->like('nmprogram', $search);
        }

        $dataGiat = $info == "KEGIATAN" ? $queryDataGiat->orderBy('nmgiat', 'ASC')->get()->getResult() : $queryDataGiat->orderBy('nmprogram', 'ASC')->get()->getResult();


        // $query = $this->db->getLastQuery();




        $dataGiatResults = array_map(function ($arr) {
            $idselect = (isset($arr->nmgiat) ? $arr->nmgiat : $arr->nmprogram);
            return [
                "id"   => $idselect,
                "text" => $idselect
            ];
        }, $dataGiat);

        return $this->respond([
            "results" => $dataGiatResults
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
            'tanggal'                 => $this->request->getPost('tanggal'),
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
            $inserted_dokumenSatker['pihak2_initial'] = $dataBalai->jabatan_penanda_tangan_pihak_2;
            $inserted_dokumenSatker['dokumen_type']   = 'balai';
            $inserted_dokumenSatker['balaiid']        = $session_balaiId;
        }

        $checkDokumenSatkerExist = $this->dokumenSatker->select('id')
            ->where('template_id', $inserted_dokumenSatker['template_id'])
            ->where('dokumen_type', $inserted_dokumenSatker['dokumen_type'])
            ->where('satkerid', $inserted_dokumenSatker['satkerid'] ?? null)
            ->where('balaiid', $inserted_dokumenSatker['balaiid'])
            ->where('tahun', $inserted_dokumenSatker['tahun'])
            ->where('status', 'hold')
            ->where('deleted_at is null')
            ->where('reject_date is null')
            ->get()->getNumRows();

        if ($checkDokumenSatkerExist > 0) {
            return $this->respond([
                'status' => false,
                'message' => 'Dokumen telah di terdaftar'
            ]);
        } else {
            if ($this->request->getPost('ttdPihak2Jabatan')) $inserted_dokumenSatker['pihak2_initial'] = $this->request->getPost('ttdPihak2Jabatan');


            if ($this->request->getPost('revision_dokumen_id')) {
                $revision_dokumenID       = $this->request->getPost('revision_dokumen_id');
                $revision_dokumenMasterID = $this->request->getPost('revision_dokumen_master_id');
                $revision_message         = $this->request->getPost('revision_message');
                $reject_by                = $this->user['idpengguna'];

                $inserted_dokumenSatker['revision_dokumen_id']        = $revision_dokumenID;
                $inserted_dokumenSatker['revision_master_dokumen_id'] = $revision_dokumenMasterID;
                $inserted_dokumenSatker['revision_message']           = $revision_message;
                $inserted_dokumenSatker['reject_by']                  = $reject_by;

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
            $_templateID = $this->request->getPost('templateID');
            if ($this->user['tahun'] != 2023) {

                // satker yang tidak perlu tagging paket
                if (!in_array($_templateID, ['5', '6', '9', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '29', '31', '32', '33', '34', '35', '36', '37', '38', '40', '42'])) {

                    /* dokumen paket */
                    $this->insertDokumenSatker_paket($this->request->getPost(), $dokumenID);
                    /** end-of: dokumen rows */
                }
            }

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
            'tanggal'               => $this->request->getPost('tanggal'),
            'tahun'                 => $this->request->getPost('tahun'),
            'created_at'            => date('Y-m-d H:i:s')
        ];

        if ($this->request->getPost('ttdPihak2Jabatan')) $inserted_dokumenSatker['pihak2_initial'] = $this->request->getPost('ttdPihak2Jabatan');

        $this->dokumenSatker->where('id', $dokumenID);
        $this->dokumenSatker->update($inserted_dokumenSatker);
        /** end-of: dokumen */

        $_templateID = $this->request->getPost('templateID');
        if ($this->user['tahun'] != 2023) {
            if (!in_array($_templateID, ['5', '6', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '29', '31', '32', '33', '34', '35', '36', '37', '38', '40', '42'])) {

                /* dokumen paket */
                $this->dokumenSatker_paket->where('dokumen_id', $dokumenID);
                $this->dokumenSatker_paket->delete();
                $this->insertDokumenSatker_paket($this->request->getPost(), $dokumenID);
                /** end-of: dokumen rows */
            }
        }

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
                'target_sat'    =>  $arr['target_satuan'] ??  null,
                'outcome_value'   => str_replace(',', '.', $arr['outcome']),
                'is_checked'      => $arr['isChecked']
            ];
        }, $input['rows']);
        $this->dokumenSatker_rows->insertBatch($rows);
    }

    private function insertDokumenSatker_paket($input, $_dokumenID)
    {

        $data = $this->request->getPost('paket');
        $dataPaket = [];
        foreach ($input['paket'] as $item) {

            if (isset($item['paketId']) && !empty($item['paketId'])) {
                $paketIds = json_decode($item['paketId'], true); // Mendekode JSON menjadi array
                // print_r($paketIds);
                // exit;
                foreach ($paketIds as $paketId) {

                    $dataPaket[] = [
                        'template_row_id' => $item['id'],
                        'idpaket' => $paketId['paketId'],
                        'target_value' => $paketId['target_nilai'],
                        'target_unit' => $paketId['target_satuan'],
                        'output_value' => $paketId['outcome_nilai'],
                        'output_unit' => $paketId['outcome_satuan'],
                        // 'isChecked' => $item['id']

                    ];
                }
            }
        }

        $rows = array_map(function ($arr) use ($_dokumenID) {
            return [
                'dokumen_id'      => $_dokumenID,
                'template_row_id' => $arr['template_row_id'],
                'idpaket'    =>  $arr['idpaket'],
                'target_value' => $arr['target_value'],
                'target_unit' => $arr['target_unit'],
                'output_value' => $arr['output_value'],
                'output_unit' => $arr['output_unit'],
                // 'outcome_value'   => str_replace(',', '.', $arr['outcome']),
                // 'is_checked'      => $arr['isChecked']
            ];
        }, $dataPaket);



        $this->dokumenSatker_paket->insertBatch($rows);
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

class PDF extends FPDF
{
    protected $B                   = 0;
    protected $I                   = 0;
    protected $U                   = 0;
    protected $HREF                = '';

    var $angle               = 0;

    public $watermarkText              = '';
    public $watermarkSubText           = '';
    public $watermarkOffsetLeft        = 70;
    public $watermarkSubTextOffsetLeft = 95;
    public $watermarkBorder_width      = 0;
    public $watermarkBorder_offsetLeft = 0;
    public $watermarkBorder_offsetRight = 0;

    var $widths;
    var $aligns;
    var $valigns;
    var $lineHeight;

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                // Text
                if ($this->HREF)
                    $this->PutLink($this->HREF, $e);
                else
                    $this->Write(5, $e);
            } else {
                // Tag
                if ($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    // Extract attributes
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag, $attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Opening tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if ($tag == 'A')
            $this->HREF = $attr['HREF'];
        if ($tag == 'BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if ($tag == 'A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s) {
            if ($this->$s > 0)
                $style .= $s;
        }
        $this->SetFont('', $style);
    }

    function PutLink($URL, $txt)
    {
        // Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }


    function Header()
    {
        if ($this->PageNo() == 1 || $this->PageNo() == 2 || $this->PageNo() || 3) {
            $this->SetLineWidth(0.0);
            // border merah
            // $this->SetDrawColor(220,20,60);
            // $this->Rect($this->watermarkBorder_offsetLeft, 13, $this->watermarkBorder_width, 10, 'D');

            //bg hitam
            $this->SetDrawColor(0, 0, 0);
            $this->Rect($this->watermarkBorder_offsetLeft, 16, $this->watermarkBorder_width, 5, 'D');

            //Put the watermark
            $this->SetFont('Times', 'B', 11);
            // $this->SetTextColor(255, 192, 203);
            //$this->RotatedText($this->watermarkOffsetLeft, 110, $this->watermarkText, 0);
            // $this->SetTextColor(220,20,60); //text merah
            $this->SetTextColor(0, 0, 0); //text hitam
            $this->RotatedText($this->watermarkOffsetLeft, 20, $this->watermarkText, 0);


            $this->SetFont('Times', 'B', 40);
            $this->RotatedText($this->watermarkSubTextOffsetLeft, 130, $this->watermarkSubText, 0);
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }




    function SetWidths($w)
    {
        $this->widths = $w;
    }

    //Set the array of column alignments
    function SetAligns($a)
    {
        $this->aligns = $a;
    }

    function SetValigns($a)
    {
        $this->valigns = $a;
    }

    //Set line height
    function SetLineHeight($h)
    {
        $this->lineHeight = $h;
    }

    //Calculate the height of the row
    function Row($data, $fill = null)
    {
        // number of line
        $nb = 0;

        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;

        //Issue a page break first if needed
        $this->CheckPageBreak($h);

        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $valign = $this->valigns[$i] ? $this->valigns[$i] : false;
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h, 'DF');
            //Print the text
            $text = $valign ? $this->drawTextBox($data[$i], $w, $h, $a, 'M', false) : $data[$i];
            $this->MultiCell($w, 6, $text, 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h > 6 ? ($h - 6) : 0);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //calculate the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function drawTextBox($strText, $w, $h, $align = 'L', $valign = 'T', $border = true)
    {
        $xi = $this->GetX();
        $yi = $this->GetY();

        $hrow = $this->FontSize;
        $textrows = $this->drawRows($w, $hrow, $strText, 0, $align, 0, 0, 0);
        $maxrows = floor($h / $this->FontSize);
        $rows = min($textrows, $maxrows);

        $dy = 0;
        if (strtoupper($valign) == 'M')
            $dy = ($h - $rows * $this->FontSize) / 2;
        if (strtoupper($valign) == 'B')
            $dy = $h - $rows * $this->FontSize;

        $this->SetY($yi + $dy);
        $this->SetX($xi);

        $this->drawRows($w, $hrow, $strText, 0, $align, false, $rows, 1);

        if ($border)
            $this->Rect($xi, $yi, $w, $h);
    }

    function drawRows($w, $h, $txt, $border = 0, $align = 'J', $fill = false, $maxline = 0, $prn = 0)
    {
        if (!isset($this->CurrentFont))
            $this->Error('No font has been set');
        $cw = $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $b = 0;
        if ($border) {
            if ($border == 1) {
                $border = 'LTRB';
                $b = 'LRT';
                $b2 = 'LR';
            } else {
                $b2 = '';
                if (is_int(strpos($border, 'L')))
                    $b2 .= 'L';
                if (is_int(strpos($border, 'R')))
                    $b2 .= 'R';
                $b = is_int(strpos($border, 'T')) ? $b2 . 'T' : $b2;
            }
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while ($i < $nb) {
            //Get next character
            $c = $s[$i];
            if ($c == "\n") {
                //Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    if ($prn == 1) $this->_out('0 Tw');
                }
                if ($prn == 1) {
                    $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                }
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2)
                    $b = $b2;
                if ($maxline && $nl > $maxline)
                    return substr($s, $i);
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                //Automatic line break
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        if ($prn == 1) $this->_out('0 Tw');
                    }
                    if ($prn == 1) {
                        $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                    }
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        if ($prn == 1) $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    if ($prn == 1) {
                        $this->Cell($w, $h, substr($s, $j, $sep - $j), $b, 2, $align, $fill);
                    }
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2)
                    $b = $b2;
                if ($maxline && $nl > $maxline)
                    return substr($s, $i);
            } else
                $i++;
        }
        //Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            if ($prn == 1) $this->_out('0 Tw');
        }
        if ($border && is_int(strpos($border, 'B')))
            $b .= 'B';
        if ($prn == 1) {
            $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
        }
        $this->x = $this->lMargin;
        return $nl;
    }
}
