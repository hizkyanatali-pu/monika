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
        $this->dokumenPK_info          = $this->db->table('dokumen_pk_template_info');
        $this->tempExportBigdataColumn = $this->db->table("temp_export_bigdata_column");

        $this->request = \Config\Services::request();
    }


    public function satker()
    {
        return view('Modules\Admin\Views\DokumenPK\satker.php');
    }



    public function template()
    {
        return view('Modules\Admin\Views\DokumenPK\template.php', [
            'data' => $this->dokumenPK->where('deleted_at is NULL', NULL, false)->get()->getResult()
        ]);
    }



    public function show($_id)
    {
        return $this->respond([
            'template' => $this->dokumenPK->where('id', $_id)->get()->getRow(),
            'rows'     => $this->dokumenPK_row->where('template_id', $_id)->get()->getResult(),
            'info'     => $this->dokumenPK_info->where('template_id', $_id)->get()->getResult()
        ], 200);
    }



    public function createTemplate()
    {
        /** template */
        $this->dokumenPK->insert([
            'title'      => $this->request->getPost('title'),
            'keterangan' => $this->request->getPost('keterangan'),
            'info_title' => $this->request->getPost('info_title')
        ]);
        $templateId = $this->db->insertID();
        /** end-of: template */


        /** row */
        $this->insertDokumenPK_row($this->request->getPost(), $templateId);
        /** end-of: row */


        /** info */
        $this->insertDokumenPK_info($this->request->getPost(), $templateId);
        /** end-of: info */

        return $this->respond([
            'status' => true
        ], 200);
    }



    public function updateTemplate() 
    {
        $templateID = $this->request->getPost('dataId');

        /* template */
        $this->dokumenPK->where('id', $templateID);
        $this->dokumenPK->update([
            'title'      => $this->request->getPost('title'),
            'keterangan' => $this->request->getPost('keterangan'),
            'info_title' => $this->request->getPost('info_title')
        ]);
        /** end-of: template */

        
        /* row */
        $this->dokumenPK_row->delete(['template_id' => $templateID]);
        $this->insertDokumenPK_row($this->request->getPost(), $templateID);
        /** end-of: row */


        /* info */
        $this->dokumenPK_info->delete(['template_id' => $templateID]);
        $this->insertDokumenPK_info($this->request->getPost(), $templateID);
        /** end-of: info */


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
                $updatedData = ['status' => $newStatus];
                if ($newStatus == "tolak") $updatedData['revision_message'] = $this->request->getPost('message');

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
            array_push($rows, [
                'id'             => $templateID . $rowsNumber++,
                'template_id'    => $templateID,
                'title'          => $data_rowTitle,
                'target_satuan'  => $input['formTable_targetSatuan'][$key_rowTitle],
                'outcome_satuan' => $input['formTable_outcomeSatuan'][$key_rowTitle],
                'type'           => $input['formTable_type'][$key_rowTitle]
            ]);
        }
        $this->dokumenPK_row->insertBatch($rows);
    }



    private function insertDokumenPK_info($input, $templateID)
    {
        $info = array_map(function($arr) use ($templateID) {
            return [
                'template_id' => $templateID,
                'info'        => $arr,
            ];
        }, $input['info_item']);
        $this->dokumenPK_info->insertBatch($info);
    }
}