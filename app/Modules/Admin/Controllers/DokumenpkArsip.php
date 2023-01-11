<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\API\ResponseTrait;


class DokumenpkArsip extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('dbdinamic');
        $session = session();
        $this->user = $session->get('userData');
        $this->db = \Config\Database::connect();

        $this->dokumenSatker          = $this->db->table('dokumenpk_satker');
        $this->dokumenSatker_kegiatan = $this->db->table('dokumenpk_satker_kegiatan');
        $this->dokumenSatker_rows     = $this->db->table('dokumenpk_satker_rows');

        $this->request = \Config\Services::request();
    }



    public function arsip()
    {
        return view('Modules\Admin\Views\DokumenPK\arsip.php', ['title' => "Perjanjian Kinerja Arsip"]);
    }



    public function getDataArsip($_status, $_dokumenType)
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
            ->where("dokumenpk_satker.deleted_at is not null")
            ->orderBy('dokumenpk_satker.id', 'DESC');

        if ($_dokumenType != 'all') $dataDokumen->where('dokumenpk_satker.dokumen_type', $_dokumenType);

        $returnDaata = array_map(function ($arr) {
            
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
                'satkerid'                   => instansi_name($arr->satkerid ?? $arr->balaiid)->nama_instansi
            ];
        }, $dataDokumen->get()->getResult());

        return $this->respond([
            'data' => $returnDaata,
            'status' => $_status
        ]);
    }



    public function arsipkanDokumen()
    {
        $this->dokumenSatker->where('id', $this->request->getPost('id'));
        $this->dokumenSatker->update([
            'deleted_at' => date("Y-m-d H:i:s")
        ]);

        return $this->respond([
            'status' => true
        ]);
    }
    
    
    
    public function arsipkanMultipleDokumen()
    {
        foreach ($this->request->getPost('id') as $key => $value) {
            $this->dokumenSatker->where('id', $this->request->getPost('id'));
            $this->dokumenSatker->update([
                'deleted_at' => date("Y-m-d H:i:s")
            ]);
        }

        return $this->respond([
            'status' => true
        ]);
    }



    public function restoreArsip()
    {
        $this->dokumenSatker->where('id', $this->request->getPost('id'));
        $this->dokumenSatker->update([
            'deleted_at' => null
        ]);

        return $this->respond([
            'status' => true
        ]);
    }



    public function deletePermanent()
    {
        $dokumenId = $this->request->getPost('id');

        $this->deleteDokumen($dokumenId);

        return $this->respond([
            'status' => true
        ]);
    }
    
    
    
    public function deletePermanentMultiple()
    {
        foreach ($this->request->getPost('id') as $key => $value) {
            $this->deleteDokumen($value);
        }

        return $this->respond([
            'status' => true
        ]);
    }
    
    
    
    
    
    
    
    private function deleteDokumen($dokumenId)
    {
        $dataDokumen = $this->dokumenSatker->select('revision_master_dokumen_id')->where(['id' => $dokumenId])->get()->getFirstRow();

        $this->dokumenSatker->delete(['id' => $dokumenId]);
        $this->dokumenSatker_kegiatan->delete(['dokumen_id' => $dokumenId]);
        $this->dokumenSatker_rows->delete(['dokumen_id' => $dokumenId]);

        $this->dokumenSatker->delete(['id' => $dataDokumen->revision_master_dokumen_id]);
        $this->dokumenSatker_kegiatan->delete(['dokumen_id' => $dataDokumen->revision_master_dokumen_id]);
        $this->dokumenSatker_rows->delete(['dokumen_id' => $dataDokumen->revision_master_dokumen_id]);

        $dataDokumenRevision = $this->dokumenSatker->select('id')->where(['revision_master_dokumen_id' => $dataDokumen->revision_master_dokumen_id])->get()->getResult();
        foreach ($dataDokumenRevision as $keyRevision => $valueRevision) {
            $this->dokumenSatker->delete(['id' => $valueRevision->id]);
            $this->dokumenSatker_kegiatan->delete(['dokumen_id' => $valueRevision->id]);
            $this->dokumenSatker_rows->delete(['dokumen_id' => $valueRevision->id]);
        }
    }
}
