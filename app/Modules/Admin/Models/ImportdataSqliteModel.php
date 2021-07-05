<?php

namespace Modules\Admin\Models;

use CodeIgniter\Model;

class ImportdataSqliteModel extends Model
{
    protected $table      = 'monika_pull_sqlite';
    protected $primaryKey = 'idpull';

    protected $allowedFields = ['idpull', 'nmfile', 'nmfileoriginal', 'sizefile', 'tbl_nm', 'tbl_size', 'tbl_row', 'tbl_dt', 'tbl_uid', 'st', 'import_dt', 'import_uid', 'aktif_dt', 'aktif_uid', 'in_dt', 'in_uid', 'aksi'];


    public function getDok($w = "")
    {
        $query = $this->table($this->table)
            ->select($this->table . ".*")
            ->where($this->table . '.aksi', 1);
        if ($w != "") $query->where($w);
        $query->orderBy($this->table . '.in_dt', 'DESC');
        // return $query->get()->getRowArray();
        return $query;
    }

    public function getactiveDB()
    {
        $query = $this->table($this->table)
            ->select($this->table . ".nmfile")
            ->where($this->table . ".status_aktif", '1');
        $row = $query->get()->getRowArray();
        return $row['nmfile'];
    }
}
