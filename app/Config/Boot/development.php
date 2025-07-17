<?php

use Config\Services;

ini_set('display_errors', '1');
error_reporting(E_ALL);

$app = Services::codeigniter();
$app->initialize();

return $app;