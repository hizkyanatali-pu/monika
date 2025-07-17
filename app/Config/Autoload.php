<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

require_once SYSTEMPATH . 'Config/AutoloadConfig.php';

class Autoload extends AutoloadConfig
{
    public $psr4 = [
        'App'     => APPPATH,
        'Config'  => APPPATH . 'Config',
        'Modules' => APPPATH . 'Modules',
    ];

    public $classmap = [
        'FPDF' => APPPATH . 'Libraries/fpdf/FPDF.php',
    ];
}