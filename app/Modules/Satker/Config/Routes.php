<?php

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $session = session();

    if ($session->get('userData')) {
        if (
            strpos($session->get('userData')['uid'], 'satker') !== false 
            || strtolower($session->get('userData')['idkelompok']) == 'satker'
        ) {
            // $routes->get('dashboard', '\Modules\Satker\Controllers\Dashboard::index');
            $routes->get('dashboard', '\Modules\Satker\Controllers\Dokumenpk::index');
            $routes->group('dokumenpk', ['namespace' => 'App\Controllers'], function($routes) {
                $routes->get('/', '\Modules\Satker\Controllers\Dokumenpk::index');
                $routes->get('get-template/(:any)', '\Modules\Satker\Controllers\Dokumenpk::getTemplate/$1');
                $routes->get('detail/(:any)', '\Modules\Satker\Controllers\Dokumenpk::show/$1');
                $routes->get('get-list-revisioned/(:any)', '\Modules\Satker\Controllers\Dokumenpk::getListRevisioned/$1');
                $routes->post('create', '\Modules\Satker\Controllers\Dokumenpk::create');

                $routes->get('export-pdf/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdf/$1');
            });
        }
    }
});
