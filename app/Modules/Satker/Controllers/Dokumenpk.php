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
        $this->templateKegiatan = $this->db->table('dokumen_pk_template_kegiatan');
        $this->templateInfo     = $this->db->table('dokumen_pk_template_info');

        $this->satker   = $this->db->table('m_satker');
        $this->balai    = $this->db->table('m_balai');
        $this->kegiatan = $this->db->table('tgiat');

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
            dokumen_pk_template.title as dokumenTitle
        ')
        ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
        ->where('user_created', $this->userUID)
        ->where('dokumenpk_satker.status !=', 'revision')
        ->orderBy('dokumenpk_satker.id', 'DESC')
        ->get()->getResult();
        
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
                    $templae_revTable = 'm_balai';
                    $template_revID   = $this->user['balai_id'];
                    break;
            }

            $dataTemplate = $this->templateDokumen->select('dokumen_pk_template.*')
            ->join('dokumen_pk_template_akses', 'dokumen_pk_template.id = dokumen_pk_template_akses.template_id', 'left')
            ->where('dokumen_pk_template.status', '1')
            ->where('dokumen_pk_template_akses.rev_id', $template_revID)
            ->where('dokumen_pk_template.type', $template_type)
            ->where('dokumen_pk_template_akses.rev_table', $templae_revTable)
            ->groupBy('dokumen_pk_template.id')
            ->get()->getResult();
            
            if (!$dataTemplate) $dataTemplate = [];
            goto returnSection;
        }
        
        globalUserTemplate:
        if (isset($this->user['user_type'])) $this->templateDokumen->where('type', $this->user['user_type']);
        $dataTemplate = $this->templateDokumen->where('dokumen_pk_template.status', '1')->get()->getResult();
        
        returnSection:
        return view('Modules\Satker\Views\Dokumenpk.php', [
            'sessionYear'       => $this->user['tahun'] ,
            'templateDokumen'   => $dataTemplate,
            'templateAvailable' => count($dataTemplate) > 0 ? 'true' : 'false',

            'dataDokumen'   => $dataDokumen,
            'dokumenStatus' => $this->dokumenStatus
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
            ku_user.nama as userCreatedName
        ')
        ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
        ->join('ku_user', 'dokumenpk_satker.user_created = ku_user.uid', 'left')
        ->where('dokumen_pk_template.status', '1')
        ->where('dokumenpk_satker.status', $_status)
        ->where('dokumenpk_satker.dokumen_type', $_dokumenType)
        ->orderBy('dokumenpk_satker.id', 'DESC')
        ->get()->getResult();

        $returnDaata = array_map(function($arr) {
            return [
                'id'                         => $arr->id,
                'template_id'                => $arr->template_id,
                'revision_master_dokumen_id' => $arr->revision_master_dokumen_id,
                'revision_master_number'     => $arr->revision_master_number,
                'revision_number'            => $arr->revision_number,
                'status'                     => $arr->status,
                'is_revision_same_year'      => $arr->is_revision_same_year,
                'change_status_at'           => $arr->change_status_at != null ? date_indo($arr->change_status_at) : '',
                'created_at'                 => $arr->created_at != null ? date_indo($arr->created_at) : '',
                'dokumenTitle'               => $arr->dokumenTitle,
                'userCreatedName'            => $arr->userCreatedName,
            ];
        }, $dataDokumen);

        return $this->respond([
            'data' => $returnDaata
        ]);
    }



    public function getTemplate($id)
    {
        $pihak1   = '';
        $pihak2   = '';

        if ($this->user['user_type'] == "satker") {
            $pihak1 = $this->user['satker_nama'];
            $pihak2 = $this->user['balai_nama'];
        }
        elseif ($this->user['user_type'] == "balai") {
            $pihak1 = $this->user['balai_nama'];
        }

        $dokumenExistSameYear = $this->dokumenSatker->select("
            id as last_dokumen_id,
            revision_master_dokumen_id
        ")->where([
            'template_id'  => $id,
            'user_created' => $this->user['uid']
        ])->where("YEAR(created_at) = YEAR(CURDATE())")->orderBy('id', 'desc')->get()->getRow();

        return $this->respond([
            'dokumenExistSameYear' => $dokumenExistSameYear,
            'template'             => $this->templateDokumen->where('id', $id)->get()->getRow(),
            'templateRow'          => $this->templateRow->where('template_id', $id)->get()->getResult(),
            'templateKegiatan'     => $this->templateKegiatan->where('template_id', $id)->get()->getResult(),
            'templateInfo'         => $this->templateInfo->where('template_id', $id)->get()->getResult(),
            'kegiatan'             => $this->kegiatan->get()->getResult(),
            'penandatangan'        => [
                'pihak1' => $pihak1,
                'pihak2' => $pihak2
            ]
        ]);
    }



    public function show($id)
    {
        return $this->respond([
            'dokumen' => $this->dokumenSatker->where('id', $id)->get()->getRow(),
            'rows'    => $this->dokumenSatker_rows->where('dokumen_id', $id)->get()->getResult()
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
        /* dokumen */
        $inserted_dokumenSatker = [
            'template_id'           => $this->request->getPost('templateID'),
            'user_created'          => $this->userUID,
            'total_anggaran'        => $this->request->getPost('totalAnggaran'),
            'pihak1_ttd'            => $this->request->getPost('ttdPihak1'),
            'pihak2_ttd'            => $this->request->getPost('ttdPihak2'),
            'is_revision_same_year' => $this->request->getPost('revisionSameYear')
        ];
       
        if ($this->user['user_type'] == "satker") {
            $inserted_dokumenSatker['pihak1_id']      = $this->user['satker_id'];
            $inserted_dokumenSatker['pihak1_initial'] = $this->user['satker_nama'];
            $inserted_dokumenSatker['pihak2_id']      = $this->user['balai_id'];
            $inserted_dokumenSatker['pihak2_initial'] = $this->user['balai_nama'];
            $inserted_dokumenSatker['dokumen_type']   = "satker";
        }
        elseif ($this->user['user_type'] == "balai") {
            $inserted_dokumenSatker['pihak1_id']      = $this->user['balai_id'];
            $inserted_dokumenSatker['pihak1_initial'] = $this->user['balai_nama'];
            $inserted_dokumenSatker['dokumen_type']   = "balai";
        }

        if ($this->request->getPost('ttdPihak2Jabatan')) $inserted_dokumenSatker['pihak2_initial'] = $this->request->getPost('ttdPihak2Jabatan');


        if ($this->request->getPost('revision_dokumen_id')) {
            $revision_dokumenID       = $this->request->getPost('revision_dokumen_id');
            $revision_dokumenMasterID = $this->request->getPost('revision_dokumen_master_id');

            $inserted_dokumenSatker['revision_dokumen_id']        = $revision_dokumenID;
            $inserted_dokumenSatker['revision_master_dokumen_id'] = $revision_dokumenMasterID;

            $inserted_dokumenSatker['revision_master_number'] = $this->dokumenSatker->selectCount('id')->where('revision_master_dokumen_id', $revision_dokumenMasterID)->orWhere('id', $revision_dokumenMasterID)->get()->getFirstRow()->id;

            if ($this->request->getPost('revisionSameYear') == '1') {
                $inserted_dokumenSatker['revision_same_year_number'] = $this->dokumenSatker->select('revision_same_year_number')->groupStart()
                    ->where('revision_master_dokumen_id', $revision_dokumenMasterID)
                    ->orWhere('id', $revision_dokumenMasterID)
                ->groupEnd()
                ->where('is_revision_same_year', '1')
                ->orderBy('id', 'DESC')
                ->get()->getFirstRow()->revision_same_year_number + 1;

                $inserted_dokumenSatker['revision_number'] = '0';
            }
            else {
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







    private function insertDokumenSatker_rows($input, $_dokumenID) 
    {
        $rows = array_map(function($arr) use ($_dokumenID) {
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
        $records = array_map(function($arr) use ($_dokumenID) {
            return [
                'dokumen_id' => $_dokumenID,
                'id'         => $arr['id'],
                'nama'       => $arr['nama'],
                'anggaran'   => $arr['anggaran']
            ];
        }, $input['kegiatan']);
        $this->dokumenSatker_kegiatan->insertBatch($records);
    }
}