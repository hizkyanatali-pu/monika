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

        $this->dokumenSatker      = $this->db->table('dokumenpk_satker');
        $this->dokumenSatker_rows = $this->db->table('dokumenpk_satker_rows');

        $this->templateDokumen = $this->db->table('dokumen_pk_template');
        $this->templateRow     = $this->db->table('dokumen_pk_template_row');
        $this->templateInfo    = $this->db->table('dokumen_pk_template_info');

        $this->satker = $this->db->table('m_satker');
        $this->balai  = $this->db->table('m_balai');

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
            dokumenpk_satker.status,
            dokumenpk_satker.created_at,
            dokumen_pk_template.title as dokumenTitle
        ')
        ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
        ->where('user_created', $this->userUID)
        ->where('status !=', 'revision')
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
            ->where('dokumen_pk_template_akses.rev_id', $template_revID)
            ->where('dokumen_pk_template.type', $template_type)
            ->where('dokumen_pk_template_akses.rev_table', $templae_revTable)
            ->groupBy('dokumen_pk_template.id')
            ->get()->getResult();
            
            if (!$dataTemplate) goto globalUserTemplate;
            goto returnSection;
        }
        
        globalUserTemplate:
        if (isset($this->user['user_type'])) $this->templateDokumen->where('type', $this->user['user_type']);
        $dataTemplate = $this->templateDokumen->get()->getResult();
        
        returnSection:
        return view('Modules\Satker\Views\Dokumenpk.php', [
            'sessionYear'     => $this->user['tahun'],
            'templateDokumen' => $dataTemplate,

            'dataDokumen'   => $dataDokumen,
            'dokumenStatus' => $this->dokumenStatus
        ]);
    }



    public function dataDokumenSatker($_status) 
    {
        $dataDokumen = $this->dokumenSatker->select('
            dokumenpk_satker.id,
            dokumenpk_satker.template_id,
            dokumenpk_satker.revision_master_dokumen_id,
            dokumenpk_satker.revision_master_number,
            dokumenpk_satker.status,
            dokumenpk_satker.created_at,
            dokumen_pk_template.title as dokumenTitle,
            ku_user.nama as userCreatedName
        ')
        ->join('dokumen_pk_template', 'dokumenpk_satker.template_id = dokumen_pk_template.id', 'left')
        ->join('ku_user', 'dokumenpk_satker.user_created = ku_user.uid', 'left')
        ->where('status', $_status)
        ->orderBy('dokumenpk_satker.id', 'DESC')
        ->get()->getResult();

        return $this->respond([
            'data' => $dataDokumen
        ]);
    }



    public function getTemplate($id)
    {
        $pihak1   = '';
        $pihak2   = '';

        if ($this->user['idkelompok'] == "SATKER") {
            $pihak1    = $this->user['satker_nama'];
            $pihak2    = $this->user['balai_nama'];
        }

        return $this->respond([
            'template'      => $this->templateDokumen->where('id', $id)->get()->getRow(),
            'templateRow'   => $this->templateRow->where('template_id', $id)->get()->getResult(),
            'templateInfo'  => $this->templateInfo->where('template_id', $id)->get()->getResult(),
            'penandatangan' => [
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
            status
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
            'template_id'    => $this->request->getPost('templateID'),
            'user_created'   => $this->userUID,
            'total_anggaran' => $this->request->getPost('totalAnggaran'),
            'pihak1_ttd'     => $this->request->getPost('ttdPihak1'),
            'pihak2_ttd'     => $this->request->getPost('ttdPihak2')
        ];
       
        if ($this->user['user_type'] == "satker") {
            $inserted_dokumenSatker['pihak1_id']      = $this->user['satker_id'];
            $inserted_dokumenSatker['pihak1_initial'] = $this->user['satker_nama'];
            $inserted_dokumenSatker['pihak2_id']      = $this->user['balai_id'];
            $inserted_dokumenSatker['pihak2_initial'] = $this->user['balai_nama'];
        }
        elseif ($this->user['user_type'] == "balai") {
        }


        if ($this->request->getPost('revision_dokumen_id')) {
            $revision_dokumenID       = $this->request->getPost('revision_dokumen_id');
            $revision_dokumenMasterID = $this->request->getPost('revision_dokumen_master_id');

            $inserted_dokumenSatker['revision_dokumen_id']        = $revision_dokumenID;
            $inserted_dokumenSatker['revision_master_dokumen_id'] = $revision_dokumenMasterID;
            $inserted_dokumenSatker['revision_master_number']     = $this->dokumenSatker->selectCount('id')->where('revision_master_dokumen_id', $revision_dokumenMasterID)->orWhere('id', $revision_dokumenMasterID)->get()->getFirstRow()->id;

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
                'target_value'    => $arr['target'],
                'outcome_value'   => $arr['outcome'],
            ];
        }, $input['rows']);
        $this->dokumenSatker_rows->insertBatch($rows);
    }
}