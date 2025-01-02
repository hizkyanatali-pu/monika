<?php

// $routes->group('admin', function($routes) {
//     $routes->add('dashboard', '\Modules\Admin\Controllers\Dashboard::index');
// });

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
        //$routes->post('datadukung', '\Modules\Admin\Controllers\Api::index');
        $routes->get('getpaket', '\Modules\Admin\Controllers\Pulldata::getPaketTagging');
        $routes->get('get-paket-tematik', '\Modules\Admin\Controllers\Pulldata::getPaketTematik');
        $routes->get('m-tematik-list', '\Modules\Admin\Controllers\Tematik::mTematikList');
        $routes->post('m-tematik-insert', '\Modules\Admin\Controllers\Tematik::mTematikInsert');

        $routes->get('m-subtematik-list', '\Modules\Admin\Controllers\Tematik::mSubTematikList');
        $routes->post('m-subtematik-insert', '\Modules\Admin\Controllers\Tematik::mSubTematikInsert');


        $routes->post('data-tematik-insert', '\Modules\Admin\Controllers\Tematik::addDataTematik');
        $routes->get('data-tematik-list', '\Modules\Admin\Controllers\Tematik::DataTematik');
        $routes->get('check-data-tematik', '\Modules\Admin\Controllers\Tematik::checkDataTematik');


        $routes->get('cron-tarik-data/(:any)', '\Modules\Admin\Controllers\CronJob::dataPaket/$1');

        $routes->group('posturanggaran', ['namespace' => 'App\Controllers'], function ($routes) {
            $routes->get('get-data-rencana-tender/(:any)', '\Modules\Admin\Controllers\Api\RencanaTender::getData_rencanaTender/$1');
        });
        $routes->get('cron-tarik-data-sisa-lelang', '\Modules\Admin\Controllers\CronJob::tarikDataEmonSisaLelangSda/$1');
        //check dokument PK
        $routes->get('showpdf/tampilkan/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdf/$1');
        $routes->get('showpdf-berita-acara/tampilkan/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdfBeritaAcara/$1');

        $routes->get('backup-table/(:any)', '\Modules\Admin\Controllers\DataJson::downloadDataTable/$1');

        $routes->get('download-backup-table/(:any)', '\Modules\Admin\Controllers\DownloadBackupTable::download/$1');

        $routes->get('getOutputKegiatan', '\Modules\Satker\Controllers\renstra_api::getOutputKegiatanTagging');
    });


    $routes->get('auth', '\Modules\Admin\Controllers\Auth::index', ['as' => 'auth']);
    $routes->post('auth', '\Modules\Admin\Controllers\Auth::attemptLogin');
    $routes->get('logout', '\Modules\Admin\Controllers\Auth::logout');

    $routes->get('csrf-update', '\Modules\Admin\Controllers\Csrf::index');


    $routes->get('account', '\Modules\Admin\Controllers\Account::index', ['as' => 'account']);
    $routes->post('account', '\Modules\Admin\Controllers\Account::update');
    $routes->get('change-password', '\Modules\Admin\Controllers\Account::change_password', ['as' => 'change-password']);
    $routes->post('change-password', '\Modules\Admin\Controllers\Account::updatePassword');




    $routes->get('users', '\Modules\Admin\Controllers\Users::index', ['as' => 'users']);
    $routes->get('passwordRechange', '\Modules\Admin\Controllers\Users::passwordRechange', ['as' => 'passwordRechange']);

    $routes->get('users/edit/(:segment)', '\Modules\Admin\Controllers\Users::edit/$1');
    $routes->get('users/changepassword/(:segment)', '\Modules\Admin\Controllers\Users::ChangePassword/$1');

    $routes->get('users/create', '\Modules\Admin\Controllers\Users::create');
    $routes->post('users/store', '\Modules\Admin\Controllers\Users::store');
    $routes->post('users/update', '\Modules\Admin\Controllers\Users::update');
    $routes->post('users/updatechangepassword', '\Modules\Admin\Controllers\Users::UpdateChangePassword');

    $routes->get('users/delete/(:segment)', '\Modules\Admin\Controllers\Users::delete/$1');

    $routes->group('usergroups', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('', '\Modules\Admin\Controllers\Usergroup::index', ['as' => 'group']);
        $routes->get('create', '\Modules\Admin\Controllers\Usergroup::create');
        $routes->post('store', '\Modules\Admin\Controllers\Usergroup::store');
        $routes->get('edit/(:segment)', '\Modules\Admin\Controllers\Usergroup::edit/$1');
        $routes->post('update', '\Modules\Admin\Controllers\Usergroup::update');
    });







    $session = session();
    if ($session->get('userData')) {
        if (strpos($session->get('userData')['uid'], 'admin') !== false) {
            $routes->group('dashboard', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->add('/', '\Modules\Admin\Controllers\Dashboard::index');
            });
            $routes->group('dashboard2', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->add('/', '\Modules\Admin\Controllers\Dashboard::index2');
            });
            $routes->group('dashboard_mobile', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->add('/', '\Modules\Admin\Controllers\Dashboard::index_mobile');
            });
            $routes->group('laporan', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->add('/', '\Modules\Admin\Controllers\Dashboard::laporan');
            });
            $routes->group('laporan/cetak', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->add('/', '\Modules\Admin\Controllers\Dashboard::cetak_laporan');
            });

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
                $routes->get('progres_per_provinsi', '\Modules\Admin\Controllers\Pulldata::progresPerProvinsi');

                $routes->get('emon-sisa-lelang-sda', '\Modules\Admin\Controllers\SisaLelangSda::tarikDataEmonSisaLelangSda');




                $routes->get('simpandata', '\Modules\Admin\Controllers\Pulldata::simpandata');

                $routes->get('rekap/(:any)', '\Modules\Admin\Controllers\Pulldata::rekap/$1');
                $routes->get('rekap-progreskeu-progres-per-provinsi/(:any)', '\Modules\Admin\Controllers\Pulldata::rekapProgressPerProvinsi/$1');

                //cetak laporan pdf progres keuangan & fisik
                $routes->get('cetak_ditjen_sda', '\Modules\Admin\Controllers\Pulldata::cetak_ditjensda');
                $routes->get('cetak_bbws', '\Modules\Admin\Controllers\Pulldata::cetak_bbws');
                $routes->get('cetak_bws', '\Modules\Admin\Controllers\Pulldata::cetak_bws');
                $routes->get('cetak_satker_pusat', '\Modules\Admin\Controllers\Pulldata::cetak_satkerpusat');
                $routes->get('cetak_balai_teknik', '\Modules\Admin\Controllers\Pulldata::cetak_balaiteknik');
                $routes->get('cetak_skpd_tp_op', '\Modules\Admin\Controllers\Pulldata::cetak_skpdtpop');
                $routes->get('cetak_satker_pagu_100m', '\Modules\Admin\Controllers\Pulldata::cetak_satkerpagu100m');
                $routes->get('cetak_semua_satker', '\Modules\Admin\Controllers\Pulldata::cetak_semua_satker');
                $routes->get('cetak_satker_terendah', '\Modules\Admin\Controllers\Pulldata::cetak_satker_terendah');
                $routes->get('cetak_satker_tertinggi', '\Modules\Admin\Controllers\Pulldata::cetak_satker_tertinggi');
                $routes->get('cetak_satker_deviasi_terbesar', '\Modules\Admin\Controllers\Pulldata::cetak_satker_deviasi_terbesar');
            });

            $routes->group('importdata', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('', '\Modules\Admin\Controllers\Importdata::index');
                $routes->get('imdata/(:segment)', '\Modules\Admin\Controllers\Importdata::imdata/$1');
                $routes->get('pullimport/(:any)', '\Modules\Admin\Controllers\Importdata::pullimport/$1');
                $routes->get('unduh/(:segment)/(:segment)', '\Modules\Admin\Controllers\Importdata::unduh/$1/$2');
                $routes->get('create-table-template-pk', '\Modules\Admin\Controllers\Importdata::copyTableTemplatePK');
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
                $routes->get('/', '\Modules\Admin\Controllers\Tematik::index');
                $routes->get('add', '\Modules\Admin\Controllers\Tematik::addForm');
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

            $routes->group('bigdata', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('/', '\Modules\Admin\Controllers\BigData::index');
                $routes->get('sda_paket', '\Modules\Admin\Controllers\BigData::getDataMonikaData');
                $routes->get('sda_paket_column', '\Modules\Admin\Controllers\BigData::getColom');

                $routes->get('load-data', '\Modules\Admin\Controllers\BigData::loadData');
                $routes->get('filter-select-lookup', '\Modules\Admin\Controllers\BigData::filterSelectLookup');

                $routes->group('unduh', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->post('/', '\Modules\Admin\Controllers\BigData::downloadExcelBigDataNew');
                });

                $routes->group('download', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\BigData::downloadExcelBigData');
                    $routes->get('prepare', '\Modules\Admin\Controllers\BigData::prepareToDownload');
                    $routes->post('set-temp-column', '\Modules\Admin\Controllers\BigData::setTempColumn');
                });
            });

            //dokumen PK
            $routes->group('dokumenpk', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('satker', '\Modules\Admin\Controllers\Dokumenpk::satker');
                $routes->get('dashboard', '\Modules\Admin\Controllers\Dokumenpk::dashboard');

                $routes->post('change-status', '\Modules\Admin\Controllers\Dokumenpk::changeStatus');

                $routes->group('arsip', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\DokumenpkArsip::arsip');

                    $routes->get('get-data/(:any)/(:any)', '\Modules\Admin\Controllers\DokumenpkArsip::getDataArsip/$1/$2');
                    $routes->post('arsipkan', '\Modules\Admin\Controllers\DokumenpkArsip::arsipkanDokumen');
                    $routes->post('arsipkan-multiple', '\Modules\Admin\Controllers\DokumenpkArsip::arsipkanMultipleDokumen');
                    $routes->post('restore', '\Modules\Admin\Controllers\DokumenpkArsip::restoreArsip');

                    //sementara didisabled dulu
                    // $routes->post('delete-permanent', '\Modules\Admin\Controllers\DokumenpkArsip::deletePermanent');
                    // $routes->post('delete-permanent-multiple', '\Modules\Admin\Controllers\DokumenpkArsip::deletePermanentMultiple');
                });
                $routes->group('eselon1', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::eselon1');
                    $routes->get('export-rekap-excel', '\Modules\Admin\Controllers\Dokumenpk::eselon1_export_rekap_excel');
                });
                $routes->group('rekapitulasi', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::rekapitulasi');
                    $routes->get('export-excel', '\Modules\Admin\Controllers\Dokumenpk::rekapitulasi_export_excel');
                    $routes->get('export-rekap-all', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_all');
                    $routes->get('export-rekap-satker', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_satker');
                    $routes->get('export-rekap-balai', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_balai');
                    $routes->get('export-rekap-skpd', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_skpd');
                    $routes->get('export-rekap-satpus', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_satpus');
                    $routes->get('export-rekap-eselon2', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_eselon2');
                    $routes->get('export-rekap-baltek', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_baltek');
                });
                $routes->get('get-list-template-buat-dokumen/(:any)/(:any)', '\Modules\Admin\Controllers\Dokumenpk::getListTemplateBuatDokumen/$1/$2');

                $routes->group('template', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::template');
                    $routes->get('detail/(:any)', '\Modules\Admin\Controllers\Dokumenpk::show/$1');
                    $routes->post('create', '\Modules\Admin\Controllers\Dokumenpk::createTemplate');
                    $routes->post('update', '\Modules\Admin\Controllers\Dokumenpk::updateTemplate');
                    $routes->post('update-status', '\Modules\Admin\Controllers\Dokumenpk::updateTemplateStatus');
                    $routes->post('delete', '\Modules\Admin\Controllers\Dokumenpk::removeTemplate');
                });

                $routes->group('template-balai', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\DokumenpkBalai::template');
                });

                $routes->group('template-eselon2', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::templateEselon2');
                });

                $routes->group('template-eselon1', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::templateEselon1');
                });

                $routes->group('setting', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('berita-acara', '\Modules\Admin\Controllers\Dokumenpk::settingBA');
                    $routes->post('berita-acara/update', '\Modules\Admin\Controllers\Dokumenpk::changeStatussettingBA');
                });
            });

            //Renstra
            $routes->group('renstra', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('satker', '\Modules\Admin\Controllers\Renstra::satker');
                $routes->get('dashboard', '\Modules\Admin\Controllers\Renstra::dashboard');

                $routes->post('change-status', '\Modules\Admin\Controllers\Renstra::changeStatus');

                $routes->group('arsip', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\RenstraArsip::arsip');

                    $routes->get('get-data/(:any)/(:any)', '\Modules\Admin\Controllers\RenstraArsip::getDataArsip/$1/$2');
                    $routes->post('arsipkan', '\Modules\Admin\Controllers\RenstraArsip::arsipkanDokumen');
                    $routes->post('arsipkan-multiple', '\Modules\Admin\Controllers\RenstraArsip::arsipkanMultipleDokumen');
                    $routes->post('restore', '\Modules\Admin\Controllers\RenstraArsip::restoreArsip');

                    //sementara didisabled dulu
                    // $routes->post('delete-permanent', '\Modules\Admin\Controllers\RenstraArsip::deletePermanent');
                    // $routes->post('delete-permanent-multiple', '\Modules\Admin\Controllers\RenstraArsip::deletePermanentMultiple');
                });

                $routes->get('eselon2', '\Modules\Admin\Controllers\Renstra::eselon2');

                $routes->group('eselon1', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Renstra::eselon1');
                    $routes->get('export-rekap-excel', '\Modules\Admin\Controllers\Renstra::eselon1_export_rekap_excel');
                });
                $routes->group('rekapitulasi', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Renstra::RekapRenstraView');

                    // $routes->get('export-excel', '\Modules\Admin\Controllers\Renstra::rekapitulasi_export_excel');
                    // $routes->get('export-rekap-all', '\Modules\Admin\Controllers\Renstra::export_rekap_excel_all');
                    // $routes->get('export-rekap-satker', '\Modules\Admin\Controllers\Renstra::export_rekap_excel_satker');
                    // $routes->get('export-rekap-balai', '\Modules\Admin\Controllers\Renstra::export_rekap_excel_balai');
                    // $routes->get('export-rekap-skpd', '\Modules\Admin\Controllers\Renstra::export_rekap_excel_skpd');
                    // $routes->get('export-rekap-satpus', '\Modules\Admin\Controllers\Renstra::export_rekap_excel_satpus');
                    // $routes->get('export-rekap-eselon2', '\Modules\Admin\Controllers\Renstra::export_rekap_excel_eselon2');
                    // $routes->get('export-rekap-baltek', '\Modules\Admin\Controllers\Renstra::export_rekap_excel_baltek');
                });
                $routes->get('get-list-template-buat-dokumen/(:any)/(:any)', '\Modules\Admin\Controllers\Renstra::getListTemplateBuatDokumen/$1/$2');

                $routes->group('template', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Renstra::template');
                    $routes->get('detail/(:any)', '\Modules\Admin\Controllers\Renstra::show/$1');
                    $routes->post('create', '\Modules\Admin\Controllers\Renstra::createTemplate');
                    $routes->post('update', '\Modules\Admin\Controllers\Renstra::updateTemplate');
                    $routes->post('update-status', '\Modules\Admin\Controllers\Renstra::updateTemplateStatus');
                    $routes->post('delete', '\Modules\Admin\Controllers\Renstra::removeTemplate');
                });

                $routes->group('template-balai', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\RenstraBalai::template');
                });

                $routes->group('template-eselon2', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Renstra::templateEselon2');
                });

                $routes->group('template-eselon1', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Renstra::templateEselon1');
                });
            });



            // RENSTRA 

            // $routes->group('renstra', ['namespace' => 'App\Controllers'], function ($routes) {
            //     $routes->get('satker', '\Modules\Admin\Controllers\Renstra::satker');
            // });
        }



        if (
            strtolower($session->get('userData')['user_type']) == 'satker'
            || strtolower($session->get('userData')['user_type']) == 'balai'
            || strpos($session->get('userData')['uid'], 'admin') !== false
        ) {
            $routes->group('dokumenpk', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('/', '\Modules\Satker\Controllers\Dokumenpk::index');
                $routes->get('get-template/(:any)', '\Modules\Satker\Controllers\Dokumenpk::getTemplate/$1');
                $routes->get('check-dokumen-same-year-exist/(:any)/(:any)', '\Modules\Satker\Controllers\Dokumenpk::checkDocumentSameYearExist/$1/$2');
                $routes->get('detail/(:any)', '\Modules\Satker\Controllers\Dokumenpk::show/$1');
                $routes->get('get-list-revisioned/(:any)', '\Modules\Satker\Controllers\Dokumenpk::getListRevisioned/$1');
                $routes->post('create', '\Modules\Satker\Controllers\Dokumenpk::create');
                $routes->post('editDokumen', '\Modules\Satker\Controllers\Dokumenpk::edit');
                $routes->post('editDokumenBA', '\Modules\Satker\Controllers\Dokumenpk::editBA');
                $routes->get('get-paket/(:any)/(:any)', '\Modules\Satker\Controllers\Dokumenpk::getPaket/$1/$2');


                $routes->get('export-pdf/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdf/$1');
                $routes->get('export-pdf-berita-acara/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdfBeritaAcara/$1');
                $routes->get('get-list-template-buat-dokumen/(:any)/(:any)', '\Modules\Admin\Controllers\Dokumenpk::getListTemplateBuatDokumen/$1/$2');

                $routes->post('change-status', '\Modules\Admin\Controllers\Dokumenpk::changeStatus');

                $routes->get('list-satker-balai', '\Modules\Satker\Controllers\Dokumenpk::listSatkerBalai');

                $routes->group('satker', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('get-data/(:any)/(:any)', '\Modules\Satker\Controllers\Dokumenpk::dataDokumenSatker/$1/$2');
                    $routes->get('get-list-revisioned/(:any)', '\Modules\Satker\Controllers\Dokumenpk::getListRevisioned/$1');
                    $routes->get('export-pdf/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdf/$1');
                    $routes->get('export-pdf-berita-acara/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdfBeritaAcara/$1');
                    $routes->get('get-data-belum-input/(:any)', '\Modules\Satker\Controllers\Dokumenpk::dataBelumInput/$1');
                });

                $routes->get('balai', '\Modules\Admin\Controllers\Dokumenpk::balai');
                $routes->get('eselon2', '\Modules\Admin\Controllers\Dokumenpk::eselon2');

                $routes->group('eselon1', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::eselon1');
                    $routes->get('export-rekap-excel', '\Modules\Admin\Controllers\Dokumenpk::eselon1_export_rekap_excel');
                });

                $routes->get('get-tgiat-for-formpk', '\Modules\Satker\Controllers\Dokumenpk::getTgiatForFormPk');
                $routes->get('rekap', '\Modules\Admin\Controllers\RekapPk::pdf');
                $routes->group('rekapitulasi', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Dokumenpk::rekapitulasi');
                    $routes->get('export-rekap-all', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_all');
                    $routes->get('export-rekap-satker', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_satker');
                    $routes->get('export-rekap-balai', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_balai');
                    $routes->get('export-rekap-skpd', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_skpd');
                    $routes->get('export-rekap-satpus', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_satpus');
                    $routes->get('export-rekap-eselon2', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_eselon2');
                    $routes->get('export-rekap-baltek', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_baltek');
                });
            });


            $routes->get('dokumenpk-balai-satker/(:any)', '\Modules\Satker\Controllers\Dokumenpk::balaiSatker/$1');
            $routes->get('dokumenpk-download-log/(:any)', '\Modules\Satker\Controllers\Dokumenpk::logKoreksi/$1');
            $routes->get('panduan', '\Modules\Satker\Controllers\Dokumenpk::panduanpk');
            $routes->get('instansi-list/(:any)', '\Modules\Admin\Controllers\Dokumenpk::instansiList/$1');



            //RENSTRA
            $routes->group('renstra', ['namespace' => 'App\Controllers'], function ($routes) {
                $routes->get('/', '\Modules\Satker\Controllers\Renstra::index');
                $routes->get('get-template/(:any)', '\Modules\Satker\Controllers\Renstra::getTemplate/$1');
                $routes->get('get-rumus-outcome/(:any)', '\Modules\Satker\Controllers\Renstra::getRumusOutcome/$1');
                $routes->get('check-dokumen-same-year-exist/(:any)/(:any)', '\Modules\Satker\Controllers\Renstra::checkDocumentSameYearExist/$1/$2');
                $routes->get('detail/(:any)', '\Modules\Satker\Controllers\Renstra::show/$1');
                $routes->get('total-paket/(:any)', '\Modules\Satker\Controllers\Renstra::totalPaket/$1');
                $routes->get('get-paket/(:any)/(:any)', '\Modules\Satker\Controllers\Renstra::getPaketbyOgiat/$1/$2');
                $routes->get('get-list-revisioned/(:any)', '\Modules\Satker\Controllers\Renstra::getListRevisioned/$1');
                $routes->post('create', '\Modules\Satker\Controllers\Renstra::create');
                $routes->post('editDokumen', '\Modules\Satker\Controllers\Renstra::edit');
                $routes->get('get-paket/(:any)/(:any)', '\Modules\Satker\Controllers\Renstra::getPaket/$1/$2');


                $routes->get('export-pdf/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdf/$1');
                $routes->get('get-list-template-buat-dokumen/(:any)/(:any)', '\Modules\Admin\Controllers\Dokumenpk::getListTemplateBuatDokumen/$1/$2');

                $routes->post('change-status', '\Modules\Admin\Controllers\Renstra::changeStatus');

                $routes->get('list-satker-balai', '\Modules\Satker\Controllers\Renstra::listSatkerBalai');

                $routes->group('satker', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('get-data/(:any)/(:any)', '\Modules\Satker\Controllers\Renstra::dataDokumenSatker/$1/$2');
                    $routes->get('get-list-revisioned/(:any)', '\Modules\Satker\Controllers\Renstra::getListRevisioned/$1');
                    $routes->get('export-pdf/(:any)', '\Modules\Satker\Controllers\DokumenpkExport::pdf/$1');
                    $routes->get('get-data-belum-input/(:any)', '\Modules\Satker\Controllers\Renstra::dataBelumInput/$1');
                });

                $routes->get('balai', '\Modules\Admin\Controllers\Renstra::balai');
                $routes->get('eselon2', '\Modules\Admin\Controllers\Renstra::eselon2');

                $routes->group('eselon1', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Renstra::eselon1');
                    $routes->get('export-rekap-excel', '\Modules\Admin\Controllers\Dokumenpk::eselon1_export_rekap_excel');
                });

                $routes->get('get-tgiat-for-formpk', '\Modules\Satker\Controllers\Renstra::getTgiatForFormPk');
                // $routes->get('rekap', '\Modules\Admin\Controllers\RekapPk::pdf');
                $routes->group('rekapitulasi', ['namespace' => 'App\Controllers'], function ($routes) {
                    $routes->get('/', '\Modules\Admin\Controllers\Renstra::RekapRenstraView');
                    // $routes->get('export-rekap-all', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_all');
                    // $routes->get('export-rekap-satker', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_satker');
                    // $routes->get('export-rekap-balai', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_balai');
                    // $routes->get('export-rekap-skpd', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_skpd');
                    // $routes->get('export-rekap-satpus', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_satpus');
                    // $routes->get('export-rekap-eselon2', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_eselon2');
                    // $routes->get('export-rekap-baltek', '\Modules\Admin\Controllers\Dokumenpk::export_rekap_excel_baltek');
                });

                $routes->get('rekap-renstra/(:any)', '\Modules\Admin\Controllers\Renstra::rekapRenstra/$1');
            });


            $routes->get('renstra-balai-satker/(:any)', '\Modules\Satker\Controllers\Renstra::balaiSatker/$1');
            $routes->get('dokumenpk-download-log/(:any)', '\Modules\Satker\Controllers\Renstra::logKoreksi/$1');
            $routes->get('panduan', '\Modules\Satker\Controllers\Renstra::panduanpk');
            $routes->get('instansi-list/(:any)', '\Modules\Admin\Controllers\Renstra::instansiList/$1');
        }
    }
});
