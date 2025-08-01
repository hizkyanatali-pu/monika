<?php

// $routes->group('admin', function($routes) {
//     $routes->add('dashboard', '\Modules\Admin\Controllers\Dashboard::index');
// });

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
        //$routes->post('datadukung', '\Modules\Admin\Controllers\Api::index');
    });
    $routes->add('dashboard', '\Modules\Admin\Controllers\Dashboard::index');

    $routes->get('auth', '\Modules\Admin\Controllers\Auth::index', ['as' => 'auth']);
    $routes->post('auth', '\Modules\Admin\Controllers\Auth::attemptLogin');
    $routes->get('logout', '\Modules\Admin\Controllers\Auth::logout');
    /*
    $routes->get('account', '\Modules\Admin\Controllers\Account::index', ['as' => 'account']);
    $routes->post('account', '\Modules\Admin\Controllers\Account::update');

    $routes->get('users', '\Modules\Admin\Controllers\Users::index', ['as' => 'users']);
    $routes->get('users/edit/(:segment)', '\Modules\Admin\Controllers\Users::edit/$1');
    $routes->get('users/create', '\Modules\Admin\Controllers\Users::create');
    $routes->post('users/store', '\Modules\Admin\Controllers\Users::store');
    $routes->post('users/update', '\Modules\Admin\Controllers\Users::update');
    $routes->get('users/delete/(:segment)', '\Modules\Admin\Controllers\Users::delete/$1');

    $routes->group('usergroups', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('', '\Modules\Admin\Controllers\Usergroup::index', ['as' => 'group']);
        $routes->get('create', '\Modules\Admin\Controllers\Usergroup::create');
        $routes->post('store', '\Modules\Admin\Controllers\Usergroup::store');
        $routes->get('edit/(:segment)', '\Modules\Admin\Controllers\Usergroup::edit/$1');
        $routes->post('update', '\Modules\Admin\Controllers\Usergroup::update');
    });
    */

    $routes->group('pulldata', ['namespace' => 'App\Controllers'], function ($routes) {
        // $routes->get('', '\Modules\Admin\Controllers\Pulldata::index');
        $routes->get('index', '\Modules\Admin\Controllers\Pulldata::index');
        $routes->get('', '\Modules\Admin\Controllers\Pulldata::unitkerja');
        $routes->get('getpaket/(:any)', '\Modules\Admin\Controllers\Pulldata::getpaket/$1');
        $routes->get('getsatker/(:any)', '\Modules\Admin\Controllers\Pulldata::getsatker/$1');

        $routes->get('unitkerja', '\Modules\Admin\Controllers\Pulldata::unitkerja');
        $routes->get('satuankerja/(:any)/(:any)', '\Modules\Admin\Controllers\Pulldata::satuankerja/$1/$2');
        $routes->get('paket/(:any)/(:any)/(:any)/(:any)', '\Modules\Admin\Controllers\Pulldata::paket/$1/$2/$3/$4');
        $routes->get('pagusda', '\Modules\Admin\Controllers\Pulldata::pagusda');
        $routes->get('bbws', '\Modules\Admin\Controllers\Pulldata::bbws');
        $routes->get('bws', '\Modules\Admin\Controllers\Pulldata::bws');

        $routes->get('satkerpusat', '\Modules\Admin\Controllers\Pulldata::satkerpusat');
        $routes->get('satker', '\Modules\Admin\Controllers\Pulldata::satker');

        $routes->get('skpdtpop', '\Modules\Admin\Controllers\Pulldata::skpdtpop');
        $routes->get('satkerpagu100m', '\Modules\Admin\Controllers\Pulldata::satkerpagu100m');

        $routes->get('simpandata', '\Modules\Admin\Controllers\Pulldata::simpandata');

        $routes->get('rekap/(:any)', '\Modules\Admin\Controllers\Pulldata::rekap/$1');
    });

    $routes->group('importdata', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('', '\Modules\Admin\Controllers\Importdata::index');
        $routes->get('imdata/(:segment)', '\Modules\Admin\Controllers\Importdata::imdata/$1');
        $routes->get('pullimport', '\Modules\Admin\Controllers\Importdata::pullimport');
        $routes->get('unduh/(:segment)/(:segment)', '\Modules\Admin\Controllers\Importdata::unduh/$1/$2');
    });

    $routes->group('grafikdata', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('progres-keuangan-fisik', '\Modules\Admin\Controllers\Grafikdata::index');
        $routes->get('progres-per-sumber-dana', '\Modules\Admin\Controllers\Grafikdata::progres_per_sumber_dana');

        $routes->get('(:segment)', '\Modules\Admin\Controllers\Grafikdata::index/$1');
    });

    //from me

    $routes->group('preferensi', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('', '\Modules\Admin\Controllers\Preferensi::index');
        $routes->get('tarikdata', '\Modules\Admin\Controllers\Preferensi::tarikdata');
        $routes->get('getdatafromdb', '\Modules\Admin\Controllers\Preferensi::savetodb');
        $routes->get('showdbsqlite/(:any)', '\Modules\Admin\Controllers\Preferensi::showdatasqlite/$1');
        $routes->post('dataemon', '\Modules\Admin\Controllers\Preferensi::opendbsqlite');
        $routes->post('getthead', '\Modules\Admin\Controllers\Preferensi::getthead');
        $routes->post('saveuploadsqlite', '\Modules\Admin\Controllers\Preferensi::pullimportfilesqlite');
        $routes->post('usedb', '\Modules\Admin\Controllers\Preferensi::usedb');
        $routes->post('uploadsqlitenew', '\Modules\Admin\Controllers\Preferensi::uploadtoserver');
        $routes->get('DTlistdb', '\Modules\Admin\Controllers\Preferensi::DTlistdb');
    });

    $routes->group('posturanggaran', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('pohon-anggaran-dipa', '\Modules\Admin\Controllers\PohonAnggaran::index');
        $routes->get('pagu-per-program', '\Modules\Admin\Controllers\PohonAnggaran::posturPagu');
        $routes->get('alokasi-anggaran', '\Modules\Admin\Controllers\PohonAnggaran::alokasiAnggaran');
        $routes->get('paket-kontraktual', '\Modules\Admin\Controllers\PohonAnggaran::paketKontraktual');
        $routes->get('sisa-lelang', '\Modules\Admin\Controllers\PohonAnggaran::sisaLelang');
        $routes->get('sisa-belum-lelang', '\Modules\Admin\Controllers\PohonAnggaran::sisaBelumLelang');
    });

    $routes->group('Kinerja-Output-Bulanan', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('(:any)', '\Modules\Admin\Controllers\KinerjaOutputBulanan::index/$1');
    });



    $routes->get('maintenance', '\Modules\Admin\Controllers\Maintenance::index');
});
