<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use Hermawan\DataTables\DataTable;

class Services extends BaseService
{
    public static function datatables($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('datatables');
        }

        // Hindari config injection yang bisa trigger loop
        return new DataTable();
    }
}