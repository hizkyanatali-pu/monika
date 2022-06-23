<?php

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $session = session();

    if ($session->get('userData')) {
        if (strpos($session->get('userData')['uid'], 'satker') !== false) {
            $routes->get('dashboard', '\Modules\Satker\Controllers\Dashboard::index');
        }
    }
});
