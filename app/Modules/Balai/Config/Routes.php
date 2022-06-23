<?php

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $session = session();

    if ($session->get('userData')) {
        if (strpos($session->get('userData')['uid'], 'balai') !== false) {
            $routes->get('dashboard', '\Modules\Balai\Controllers\Dashboard::index');
        }
    }
});
