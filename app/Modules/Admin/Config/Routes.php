<?php

// $routes->group('admin', function($routes) {
//     $routes->add('dashboard', '\Modules\Admin\Controllers\Dashboard::index');
// });

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
        //$routes->post('datadukung', '\Modules\Admin\Controllers\Api::index');
        $routes->get('cron-tarik-data/(:any)', '\Modules\Admin\Controllers\CronJob::dataPaket/$1');
        
        $routes->group('posturanggaran', ['namespace' => 'App\Controllers'], function ($routes) {
            $routes->get('get-data-rencana-tender/(:any)', '\Modules\Admin\Controllers\Api\RencanaTender::getData_rencanaTender/$1');
        });
        $routes->get('cron-tarik-data-sisa-lelang', '\Modules\Admin\Controllers\CronJob::tarikDataEmonSisaLelangSda/$1');
    });


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


    $session = session();
    if ($session->get('userData')) {
        if (strpos($session->get('userData')['uid'], 'admin') !== false) {
            $routes->add('dashboard', '\Modules\Admin\Controllers\Dashboard::index');
            $routes->add('excel', '\Modules\Admin\Controllers\Dashboard::Excel');

            $routes->group('pulldata', ['namespace' => 'App\Controllers'], function ($routes) {
                // $routes->get('', '\Modules\Admin\Controllers\Pulldata::index');
                // $routes->get('index', '\Modules\Admin\Controllers\Pulldata::index');
                $routes->get('ditjensda', '\Modules\Admin\Controllers\Pulldata::unitkerja');
                $routes->get('getpaket/(:any)', '\Modules\Admin\Controllers\Pulldata::getpaket/$1');
                $routes->get('getsatker/(:any)', '\Modules\Admin\Controllers\Pulldata::getsatker/$1');

                $routes->get('unitkerja', '\Modules\Admin\Controllers\Pulldata::unitkerja');
                $routes->get('satuankerja/(:any)/(:any)', '\Modules\Admin\Controllers\Pulldata::satuankerja/$1/$2');
                $routes->get('paket/(:any)/(:any)/(:any)/(:any)', '\Modules\Admin\Controllers\Pulldata::paket/$1/$2/$3/$4');
                $routes->get('pagusda', '\Modules\Admin\Controllers\Pulldata::pagusda');
                $routes->get('bbws', '\Modules\Admin\Controllers\Pulldata::bbws');
                $routes->get('bws', '\Modules\Admin\Controllers\Pulldata::bws');

                $routes->get('satkerpusat', '\Modules\Admin\Controllers\Pulldata::satkerpusat');
                $routes->get('balaiteknik', '\Modules\Admin\Controllers\Pulldata::balaiteknik');

                $routes->get('skpdtpop', '\Modules\Admin\Controllers\Pulldata::skpdtpop');
                $routes->get('satkerpagu100m', '\Modules\Admin\Controllers\Pulldata::satkerpagu100m');
                $routes->get('semuasatker/(:any)', '\Modules\Admin\Controllers\Pulldata::semua_satker/$1');
                $routes->get('satker_terendah', '\Modules\Admin\Controllers\Pulldata::satker_terendah');
                $routes->get('satker_tertinggi', '\Modules\Admin\Controllers\Pulldata::satker_tertinggi');
                $routes->get('satker_deviasi_terbesar', '\Modules\Admin\Controllers\Pulldata::satker_deviasi_terbesar');

                $routes->get('emon-sisa-lelang-sda', '\Modules\Admin\Controllers\SisaLelangSda::tarikDataEmonSisaLelangSda');




                $routes->get('simpandata', '\Modules\Admin\Controllers\Pulldata::simpandata');

                $routes->get('rekap/(:any)', '\Modules\Admin\Controllers\Pulldata::rekap/$1');

                //cetak laporan pdf progres keuangan & fisik
                $routes->get('cetak_ditjen_sda', '\Modules\Admin\Controllers\Pulldata::cetak_ditjensda');
                $routes->get('cetak_bbws', '\Modules\Admin\Controllers\Pulldata::cetak_bbws');
                $routes->get('cetak_bws', '\Modules\Admin\Controllers\Pulldata::cetak_bws');
                $routes->get('cetak_satker_pusat', '\Modules\Admin\Controllers\Pulldata::cetak_satkerpusat');
                $routes->get('cetak_balai_teknik', '\Modules\Admin\Controllers\Pulldata::cetak_balaiteknik');
                $routes->get('cetak_skpd_tp_op', '\Modules\Admin\Controllers\Pulldata::cetak_skpdtpop');
                $routes->get('cetak_satker_pagu_100m', '\Modules\Admin\Controllers\Pulldata::cetak_satkerpagu100m');
                $routes->get('cetak_semua_satker', '\Modules\Admin\Controllers\Pulldata::cetak_semua_satker');
            });

            $routes->group('importdata', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('', '\Modules\Admin\Controllers\Importdata::index');
                $routes->get('imdata/(:segment)', '\Modules\Admin\Controllers\Importdata::imdata/$1');
                $routes->get('pullimport/(:any)', '\Modules\Admin\Controllers\Importdata::pullimport/$1');
                $routes->get('unduh/(:segment)/(:segment)', '\Modules\Admin\Controllers\Importdata::unduh/$1/$2');
            });

            $routes->group('grafikdata', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('progres-keuangan-fisik', '\Modules\Admin\Controllers\Grafikdata::index');
                $routes->get('progres-per-sumber-dana', '\Modules\Admin\Controllers\Grafikdata::progres_per_sumber_dana');
                $routes->get('progres-per-jenis-belanja', '\Modules\Admin\Controllers\Grafikdata::progres_per_jenis_belanja');
                $routes->get('progres-per-kegiatan', '\Modules\Admin\Controllers\Grafikdata::progres_per_kegiatan');
                $routes->get('progres-grafik-pupr', '\Modules\Admin\Controllers\Grafikdata::progres_pupr');

                $routes->get('(:segment)', '\Modules\Admin\Controllers\Grafikdata::index/$1');
            });

            $routes->group('preferensi', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('dari-sqlite', '\Modules\Admin\Controllers\Preferensi::index');
                $routes->get('tarik-data-emon/(:any)', '\Modules\Admin\Controllers\Importdata::index/$1');
                $routes->get('tarik-data-emon-sisa-lelang-sda', '\Modules\Admin\Controllers\SisaLelangSda::pageTarikData');

                //fitur tarik data dari api
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

            $routes->group('master-data', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('master-satker', '\Modules\Admin\Controllers\MasterSatker::index');
                $routes->get('master-satker-export-excel', '\Modules\Admin\Controllers\MasterSatker::exportDataToExcel');
                $routes->post('master-satker-import-excel', '\Modules\Admin\Controllers\MasterSatker::importDataToExcel');

                $routes->get('master-kegiatan', '\Modules\Admin\Controllers\MasterKegiatan::index');
                $routes->get('master-kegiatan-export-excel', '\Modules\Admin\Controllers\MasterKegiatan::exportDataToExcel');
                $routes->post('master-kegiatan-import-excel', '\Modules\Admin\Controllers\MasterKegiatan::importDataToExcel');

                $routes->get('master-kro', '\Modules\Admin\Controllers\MasterKro::index');
                $routes->get('master-kro-export-excel', '\Modules\Admin\Controllers\MasterKro::exportDataToExcel');
                $routes->post('master-kro-import-excel', '\Modules\Admin\Controllers\MasterKro::importDataToExcel');

                $routes->get('master-ro', '\Modules\Admin\Controllers\MasterRo::index');
                $routes->get('master-ro-export-excel', '\Modules\Admin\Controllers\MasterRo::exportDataToExcel');
                $routes->post('master-ro-import-excel', '\Modules\Admin\Controllers\MasterRo::importDataToExcel');
            });

            $routes->group('posturanggaran', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('pohon-anggaran-dipa', '\Modules\Admin\Controllers\PohonAnggaran::index');
                $routes->get('pagu-per-program', '\Modules\Admin\Controllers\PohonAnggaran::posturPagu');
                $routes->get('alokasi-anggaran', '\Modules\Admin\Controllers\PohonAnggaran::alokasiAnggaran');
                $routes->get('alokasi-anggaran-new', '\Modules\Admin\Controllers\PohonAnggaran::alokasiAnggaranNew');
                $routes->get('paket-kontraktual', '\Modules\Admin\Controllers\PohonAnggaran::paketKontraktual');
                $routes->get('sisa-lelang', '\Modules\Admin\Controllers\PohonAnggaran::sisaLelang');
                $routes->get('sisa-belum-lelang', '\Modules\Admin\Controllers\PohonAnggaran::sisaBelumLelang');
                $routes->get('rencana-tender', '\Modules\Admin\Controllers\PohonAnggaran::rencanaTender');
                $routes->get('get-data-rencana-tender', '\Modules\Admin\Controllers\Api\RencanaTender::getData_rencanaTender');
                $routes->get('dana-tidak-terserap', '\Modules\Admin\Controllers\PohonAnggaran::danatidakTerserap');
            });

            $routes->group('tematik', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('food-estate', '\Modules\Admin\Controllers\Tematik::pageFoodEstate');
                $routes->get('kawasan-industri', '\Modules\Admin\Controllers\Tematik::pageKawasanIndustri');
                $routes->get('kspn/(:any)', '\Modules\Admin\Controllers\Tematik::pageKspn/$1');
                $routes->get('g20', '\Modules\Admin\Controllers\Tematik::pageG20');
                $routes->get('ikn', '\Modules\Admin\Controllers\Tematik::pageIkn');
                $routes->get('rekap', '\Modules\Admin\Controllers\Tematik::pageRekap');
                $routes->get('excel/(:any)', '\Modules\Admin\Controllers\Tematik::exportExcel/$1');
                $routes->get('excel-kspn/(:any)', '\Modules\Admin\Controllers\Tematik::exportExcelKspn/$1');
                $routes->get('excel-rekap', '\Modules\Admin\Controllers\Tematik::exportExcelRekap');

                //cetak laporan pdf tematik
                $routes->get('cetak_food_estate', '\Modules\Admin\Controllers\Tematik::cetakFoodEstate');
                $routes->get('cetak_kawasan_industri', '\Modules\Admin\Controllers\Tematik::cetakKawasanIndustri');
                $routes->get('cetak_kspn/(:any)', '\Modules\Admin\Controllers\Tematik::cetakKspn/$1');
                $routes->get('cetak_g20', '\Modules\Admin\Controllers\Tematik::cetakG20');
                $routes->get('cetak_ikn', '\Modules\Admin\Controllers\Tematik::cetakIkn');
                $routes->get('cetak_rekap', '\Modules\Admin\Controllers\Tematik::cetakRekap');
            });

            $routes->group('sisa-lelang', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('ditjen-sda', '\Modules\Admin\Controllers\SisaLelangSda::index');
                $routes->get('per-kategori', '\Modules\Admin\Controllers\SisaLelangSda::pagePerKategori');
            });

            $routes->get('blokir', '\Modules\Admin\Controllers\Blokir::index');

            $routes->group('Kinerja-Output-Bulanan', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('(:any)', '\Modules\Admin\Controllers\KinerjaOutputBulanan::index/$1');
                $routes->get('(:any)/(:any)', '\Modules\Admin\Controllers\KinerjaOutputBulanan::index/$1/$2');
            });

            $routes->get('maintenance', '\Modules\Admin\Controllers\Maintenance::index');
            $routes->get('pemaketan', '\Modules\Admin\Controllers\Pemaketan::index');
            
            $routes->group('bigdata', ['namespace' => 'App\Controllers'], function($routes) {
                $routes->get('/', '\Modules\Admin\Controllers\BigData::index');
                $routes->get('load-data', '\Modules\Admin\Controllers\BigData::loadData');

                $routes->group('download', ['namespace' => 'App\Controllers'], function($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\BigData::downloadExcelBigData');
                    $routes->get('prepare', '\Modules\Admin\Controllers\BigData::prepareToDownload');
                    $routes->post('set-temp-column', '\Modules\Admin\Controllers\BigData::setTempColumn');
                });
            });

            $routes->group('dokumenpk', ['namespace' => 'App\Controllers'], function($routes) {
                $routes->post('change-status', '\Modules\Admin\Controllers\Dokumenpk::changeStatus');

                $routes->group('satker', ['namespace' => 'App\Controllers'], function($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::satker');

                    $routes->get('get-data/(:any)', '\Modules\Satker\Controllers\Dokumenpk::dataDokumenSatker/$1');
                    $routes->get('get-list-revisioned/(:any)', '\Modules\Satker\Controllers\Dokumenpk::getListRevisioned/$1');
                    $routes->get('export-pdf/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdf/$1');
                });

                $routes->group('template', ['namespace' => 'App\Controllers'], function($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::template');
                    $routes->get('detail/(:any)', '\Modules\Admin\Controllers\Dokumenpk::show/$1');
                    $routes->post('create', '\Modules\Admin\Controllers\Dokumenpk::createTemplate');
                    $routes->post('update', '\Modules\Admin\Controllers\Dokumenpk::updateTemplate');
                    $routes->post('delete', '\Modules\Admin\Controllers\Dokumenpk::removeTemplate');
                });
            });
        }





        elseif (strpos($session->get('userData')['uid'], 'satker') !== false) {
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
