<?php

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $session = session();
    if ($session->get('userData')) {
        if (
            strtolower($session->get('userData')['user_type']) == 'balai'
        ) {
            $routes->get('dashboard', '\Modules\Satker\Controllers\Dokumenpk::index');
        }
    }
});
