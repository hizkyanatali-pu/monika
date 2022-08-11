<?php

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $session = session();

    if ($session->get('userData')) {
        if (
            strtolower($session->get('userData')['user_type']) == 'satker'
        ) {
            // $routes->get('dashboard', '\Modules\Satker\Controllers\Dashboard::index');
            $routes->get('dashboard', '\Modules\Satker\Controllers\Dokumenpk::index');
        }
    }
});
