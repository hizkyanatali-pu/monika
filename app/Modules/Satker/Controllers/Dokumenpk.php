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

        return view('Modules\Satker\Views\Dokumenpk.php', [
            'sessionYear'     => $this->user['tahun'],
            'templateDokumen' => $this->templateDokumen->get()->getResult(),

            'dataDokumen'   => $dataDokumen,
            'dokumenStatus' => $this->dokumenStatus
        ]);
    }



    public function getTemplate($id)
    {
        return $this->respond([
            'template'     => $this->templateDokumen->where('id', $id)->get()->getRow(),
            'templateRow'  => $this->templateRow->where('template_id', $id)->get()->getResult(), 
            'templateInfo' => $this->templateInfo->where('template_id', $id)->get()->getResult()
        ]);
    }



    public function cetak()
    {
        echo 1;
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
            'template_id' => $this->request->getPost('templateID'),
            'user_created' => $this->userUID
        ];

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
            'status' => true
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