<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;

class ImportdataModel extends Model
{
    protected $table      = 'monika_pull';
    protected $primaryKey = 'idpull';

    protected $allowedFields = ['idpull', 'nmfile', 'sizefile', 'sqlfile_nm', 'sqlfile_size', 'sqlfile_row', 'sqlfile_dt', 'sqlfile_uid', 'st', 'import_dt', 'import_uid', 'aktif_dt', 'aktif_uid', 'in_dt', 'in_uid', 'aksi', 'type', 'tahunanggaran'];

    public function getDok($w = "", $orderby = "")
    {
        $query = $this->table($this->table)
            ->select($this->table . ".*")
            ->where($this->table . '.aksi', 1);
        if ($w != "") $query->where($w);

        ($orderby != '' ? "" : $query->orderBy($this->table . '.in_dt', 'DESC'));

        // return $query->get()->getRowArray();
        return $query;
    }

    public function deleteFiles($w = "")
    {
        $query = $this->table($this->table)
            ->where($this->table . '.aksi', 1);
        if ($w != "") $query->where($w);
        $query->delete();
        // return $query->get()->getRowArray();
        return $query;
    }
}
