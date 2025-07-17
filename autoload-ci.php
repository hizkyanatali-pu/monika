<?php

// Load Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Define base system path
define('APPPATH', realpath(__DIR__ . '/app') . DIRECTORY_SEPARATOR);
define('ROOTPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath(__DIR__ . '/vendor/codeigniter4/framework/system') . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath(__DIR__ . '/writable') . DIRECTORY_SEPARATOR);
define('FCPATH', realpath(__DIR__ . '/public') . DIRECTORY_SEPARATOR);
define('CI_DEBUG', 1);

// Load CI base system autoloader
require_once SYSTEMPATH . 'Config/AutoloadConfig.php';
require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
require_once SYSTEMPATH . 'Config/Services.php';
require_once SYSTEMPATH . 'CodeIgniter.php';

use Config\Services;