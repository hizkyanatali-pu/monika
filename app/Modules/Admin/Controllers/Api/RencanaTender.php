<?php

namespace Modules\Admin\Controllers\Api;

use Modules\Admin\Models\PohonAnggaranModel;
use CodeIgniter\RESTful\ResourceController;

class RencanaTender extends ResourceController
{
    public function __construct() {
        $this->PohonAnggaran  = new PohonAnggaranModel();
        helper('date_helper');
    }



    public function getData_rencanaTender($_pagu) {
        $res_perBulan = [];
        for ($iMonth=0; $iMonth < 4; $iMonth++) { 
            $bulan = date('m', strtotime("+$iMonth month"));

            $prefixBulan = $iMonth == 0 ? 's/d ' : '';
            $queryCondition = $iMonth == 0 ? '<=' : '=';

            $dataBulan = $this->PohonAnggaran->getDataRencanaTenderBelumLelang($_pagu, $queryCondition, $bulan);
            $dataBulan['bulan'] = $prefixBulan . bulan($bulan);

            array_push($res_perBulan, $dataBulan);
        }

        return $this->respond([
            'pagu' => $_pagu,
            'data' => [
                'pagu'     => $this->PohonAnggaran->getDataRencanaTenderBelumLelang($_pagu),
                'perBulan' => $res_perBulan
            ]
        ]);
    }
}