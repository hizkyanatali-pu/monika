<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<style>
    footer {
        display: none;
    }

    .block_all {
        position: fixed;
        top: 0;
        left: 0;
        background-color: white;
        width: 100%;
        height: 100vh;
        z-index: 999;
    }

    @media print {
        .pagebreak {
            clear: both;
            page-break-after: always;
        }

        @page {
            size: landscape;
            background-color: white;
            margin-top: 0;
            width: 290mm;
            height: 420mm;
        }

        .tableFixHead {
            height: auto !important;
        }

        .table-bordered thead td,
        .table-bordered thead th {
            padding: 8px !important;
        }

        #kt_subheader {
            display: none;
        }

        #kt_scrolltop {
            display: none;
        }

        #kt_header_mobile {

            display: none;
        }

        .kt-header__topbar-item.kt-header__topbar-item--user {
            display: none;
        }

        .kt-grid.kt-grid--hor.kt-grid--root {

            display: none;

        }

        .kt-container.kt-container--fluid.kt-grid__item.kt-grid__item--fluid {
            background-color: white;

        }

        .kt-portlet.kt-portlet--tab {
            color: black;
            font-size: 16px;
            margin-top: 0.9cm;
            font-family: Arial, Helvetica, sans-serif;
            /* background-color: #fff;
            font-family: Arial, Helvetica, sans-serif;
            color: #424849;
            font-size: 12px;
            zoom: 1.5;
            -moz-transform: scale(1.5); */

        }

        #tabletematik {
            width: 100%;
            zoom: 0.7;
            -moz-transform: scale(0.7);
        }

        .kt-footer {
            display: none;
        }

        .footer {
            display: block;
            /* position: absolute; */
            /* bottom: 0px; */
        }
    }
</style>
<!-- begin:: Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main d-flex justify-content-between w-100">
            <div>
                <h3 class="kt-subheader__title">
                    Dashboard
                </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
            </div>
            <div class="d-flex">
                <form action="<?php echo site_url('dashboard') ?>" method="GET">
                    <div class="input-group mr-3">
                        <input
                            type="date"
                            class="form-control"
                            name="filter-date-start"
                            value="<?php echo date('Y-01-01') ?>">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">Sampai</span>
                        </div>
                        <div class="input-group-append">
                            <input
                                type="date"
                                class="form-control"
                                name="filter-date-end"
                                value="<?php echo date('Y-m-d') ?>">
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" id="__terapkan-filter">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </form>

                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalFilterMenu">
                    <i class="fas fa-filter"></i> Filter Menu
                </button>

                <button class="btn btn-sm btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!-- PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR -->

    <div class="kt-portlet kt-portlet--tab" id="progres_fisik_keuangan_kementerian_pupr">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR
                </h3>
            </div>

        </div>
        <div class="kt-portlet__body">
            <div class="kt-section mb-0">
                <div class="kt-section__content">
                    <div class="float-left">
                        <i><b>Status : <?= $rekapunor['total']['status'] ?></b></i>
                    </div>

                    <div class="float-right">
                        <i><b>*Dalam Ribu Rupiah</b></i>
                    </div>

                    <div class="table-responsive">
                        <table class="table-bordered" width="100%">
                            <thead class="text-center text-white" style="background-color: #1562aa;">
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" rowspan="2">No</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" rowspan="2">Unit Organisasi</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="4">Pagu</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="4">Realisasi</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="2">Progress</th>

                                </tr>
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">RPM</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">SBSN</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">PHLN</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Total</th>

                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">RPM</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">SBSN</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">PHLN</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Total</th>

                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keuangan</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Fisik</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rekapunor['unor'] as $key => $val) { ?>

                                    <tr <?= ($val['kdunit'] == 06 ? "class='tdprogram font-weight-bold'" : "") ?>>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" scope="row"><?= ++$key ?></th>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px"><?= $val['nmsingkat']; ?></td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_rpm'], 2, ',', '.'); ?></td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_sbsn'], 2, ',', '.'); ?></td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_phln'], 2, ',', '.'); ?></td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_total'], 2, ',', '.'); ?></td>

                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_rpm"], 2, ',', '.'); ?></td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_sbsn"], 2, ',', '.'); ?></td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_phln"], 2, ',', '.'); ?></td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_total"], 2, ',', '.'); ?></td>

                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_keu'], 2, ',', '.'); ?> %</td>
                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_fisik'], 2, ',', '.'); ?> %</td>

                                    </tr>


                                <?php   } ?>

                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                    <td colspan="2">TOTAL</td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_rpm'], 2, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_sbsn'], 2, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_phln'], 2, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_total'], 2, ',', '.'); ?></td>

                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_rpm"], 2, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_sbsn"], 2, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_phln"], 2, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_total"], 2, ',', '.'); ?></td>

                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['progres_keu'], 2, ',', '.'); ?> %</td>
                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['progres_fisik'], 2, ',', '.'); ?> %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr style="border: 1px solid #ddd;">
                <div class="chart-container mt-2" style="height: 500px; overflow-x: auto;">
                    <div id="placeholder-bar-chart" class="mychart"></div>
                    <div id="bar-legend" class="chart-legend"></div>
                </div>
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>
    <!-- END  PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR -->

    <div class="pagebreak"></div>

    <!-- PROGRES PROGRAM PADAT KARYA PER KEGIATAN -->
    <div class="kt-portlet kt-portlet--tab" id="progres_program_padat_karya_per_kegiatan">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRES PROGRAM PADAT KARYA PER KEGIATAN
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section mb-0">
                <div class="kt-section__content">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%">
                            <thead class="text-center text-white" style="background-color: #1562aa;">
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important;" rowspan="2" class="text-center">No</th>
                                    <th style="padding: 0px 4px 0px 4px !important;" rowspan="2" class="text-center">Kegiatan</th>
                                    <th style="padding: 0px 4px 0px 4px !important;" colspan="4" class="text-center">Target</th>
                                    <th style="padding: 0px 4px 0px 4px !important;" colspan="8" class="text-center">Realisasi</th>
                                </tr>
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important;">Paket/Lokasi</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">Pagu</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">Tenaga Kerja</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">H.O.K</th>

                                    <th style="padding: 0px 4px 0px 4px !important;">Paket/Lokasi</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">%</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">Pagu</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">%</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">Tenaga Kerja</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">%</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">H.O.K</th>
                                    <th style="padding: 0px 4px 0px 4px !important;">%</th>


                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important;" scope="row">1</th>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                    <td style="padding: 0px 4px 0px 4px !important;">Table cell</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div id="line-chart1" style="height: 300px;"></div>

        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>

    <!-- END  PROGRES PROGRAM PADAT KARYA PER KEGIATAN -->
    <div class="pagebreak"> </div>
    <!-- PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR T.A.2021 -->

    <div class="kt-portlet kt-portlet--tab" id="progress_kegiatan_tematik_direktorat_jenderal_sumber_daya_air">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR T.A.<?= session('userData.tahun') ?>
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-portlet">
                <div class="kt-portlet__body">

                    <!--begin::Section-->
                    <div class="kt-section mb-0">
                        <div class="row mb-3">
                            <!-- <div class="col-md-6 mt-3">
                                <div class="dropdown dropright">
                                    <button type="button" class="btn btn-primary btn-icon" dropdown-toggle data-toggle="dropdown"><i class="la la-filter"></i></button>
                                    <div class="dropdown-menu" style="overflow-y: auto; height: 200px; z-index: 5;">
                                        <a href="#" class="dropdown-item">
                                            <div class="form-check-inline">
                                                <label for="" class="form-check-label">
                                                    <input type="checkbox" type="checkbox" class="form-check-input" name="tematik">Tematik
                                                </label>
                                            </div>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <div class="form-check-inline">
                                                <label for="" class="form-check-label">
                                                    <input type="checkbox" type="checkbox" class="form-check-input" name="pagu">Pagu
                                                </label>
                                            </div>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <div class="form-check-inline">
                                                <label for="" class="form-check-label">
                                                    <input type="checkbox" type="checkbox" class="form-check-input" name="realisasi">Realisasi
                                                </label>
                                            </div>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <div class="form-check-inline">
                                                <label for="" class="form-check-label">
                                                    <input type="checkbox" type="checkbox" class="form-check-input" name="progres_keu">Progres (Keu)
                                                </label>
                                            </div>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <div class="form-check-inline">
                                                <label for="" class="form-check-label">
                                                    <input type="checkbox" type="checkbox" class="form-check-input" name="progres_fis">Progres (Fis)
                                                </label>
                                            </div>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <div class="form-check-inline">
                                                <label for="" class="form-check-label">
                                                    <input type="checkbox" type="checkbox" class="form-check-input" name="keterangan">Keterangan
                                                </label>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-12 text-right mt-3 float-right">
                                <!-- <div class="form-group">
                                    <a class="btn btn-warning btn-sm text-white pdf-report"><i class="fa fa-file-pdf"></i>PDF</a>
                                    <a href="<?php //echo site_url('/tematik/excel-rekap') 
                                                ?>" class="btn btn-success btn-sm text-white" target="_blank">
                                        <i class="fa fa-file-excel"></i>Excel
                                    </a>
                                </div> -->
                                <i><b>*Dalam Milyar Rupiah</b></i>
                            </div>
                        </div>

                        <!-- <div class="table-responsive tableFixHead"> -->

                        <?php $colspan = 8; ?>
                        <div class="table-responsive">
                            <table class="table-bordered mb-0 table-striped" id="tabletematik" width="100%">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th style="padding: 1px !important;" rowspan="3">No</th>
                                        <th style="padding: 4px !important; width: 25%;" class="tematik" rowspan="3" style="width: 21%;">Tematik</th>
                                        <th style="padding: 1px !important;" class="pagu" rowspan="3" style="width: 6%;">Pagu (dalam Milyar)</th>
                                        <th style="padding: 4px !important; width: 25%;" class="realisasi" colspan="3">Realisasi</th>
                                        <th style="padding: 1px !important;" class="keterangan" rowspan="3">Keterangan</th>
                                    </tr>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th style="padding: 0px 4px 0px 4px !important;" class="progres_keu progres" colspan="2">Keuangan</th>
                                        <th style="padding: 0px 4px 0px 4px !important;" class="progres_fis progres">Fisik</th>
                                    </tr>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th style="padding: 0px 4px 0px 4px !important;" class="progres_keu progres" style="width: 6%;">Rp</th>
                                        <th style="padding: 0px 4px 0px 4px !important;" class="progres_fis progres" style="width: 6%;">%</th>
                                        <th style="padding: 0px 4px 0px 4px !important;" class="progres_fis progres" style="width: 6%;">%</th>

                                    </tr>
                                </thead>

                                <tbody id="tbody-utama">
                                    <?php
                                    $no = 1;
                                    foreach ($data as $key => $value) :
                                    ?>
                                        <tr>
                                            <td style="padding: 0px 4px 0px 4px !important;" class="tdprogram"><?php echo $no++ ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important;" class="col-tematik tdprogram"><?php echo $value['title'] ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important;" class="text-right tdprogram"><?php echo toMilyar($value['totalPagu'], false, 2) ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important;" class="text-right tdprogram"><?php echo toMilyar($value['totalRealisasi'], false, 2) ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important;" class="text-right tdprogram"><?php echo onlyTwoDecimal($value['totalProgKeu']) ?> %</td>
                                            <td style="padding: 0px 4px 0px 4px !important;" class="text-right tdprogram"><?php echo onlyTwoDecimal($value['totalProgFis']) ?> %</td>
                                            <td style="padding: 0px 4px 0px 4px !important;" class="col-sm-10 tdprogram"></td>
                                        </tr>
                                        <?php foreach ($value['list'] as $key2 => $value2) : ?>
                                            <tr>
                                                <td style="padding: 0px 4px 0px 4px !important;"></td>
                                                <td style="padding: 0px 4px 0px 4px !important;" class="col-tematik"><?php echo $value2->tematik ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important;" class="text-right text-right"><?php echo toMilyar($value2->pagu, false, 2) ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important;" class="text-right"><?php echo toMilyar($value2->realisasi, false, 2) ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important;" class="text-right"><?php echo onlyTwoDecimal($value2->prog_keu) ?> %</td>
                                                <td style="padding: 0px 4px 0px 4px !important;" class="text-right"><?php echo onlyTwoDecimal($value2->prog_fis) ?> %</td>
                                                <td style="padding: 0px 4px 0px 4px !important;" class="col-sm-10"><?php echo $value2->ket ?></td>

                                                <!--<td><?php echo  "- " . str_replace("||", "<br> - ", str_replace(", ", ",", $value2->ket))  ?></td>-->
                                            </tr>
                                        <?php endforeach ?>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- </div> -->

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>

    <!-- END  PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR T.A.2021 -->
    <div class="pagebreak"> </div>
    <!-- POSTUR PAKET KONTRAKTUAL -->

    <div id="viewkontraktual" class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    POSTUR PAKET KONTRAKTUAL T.A. <?= session('userData.tahun') ?>
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section mb-0">
                <div class="kt-section__content" style="overflow-x: auto">
                    <div style="width: 1400px; margin: 0px auto">
                        <div class="tree">
                            <ul>
                                <li class="w-100">
                                    <a href="#viewkontraktual" class="">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-1">
                                                <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                <h4 class="mb-0"><b> KONTRAKTUAL </b></h4>

                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h6><?= number_format(($terkontrak['jml_paket'] + ($proseslelang['jml_paket'] + $belumlelang['jml_paket'] + $persiapankontrak['jml_paket'] + $gagallelang['jml_paket'])), 0, ',', '.'); ?> Paket</h6>
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($terkontrak['nilai_kontrak'] + ($proseslelang['nilai_kontrak'] + $belumlelang['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'] + $gagallelang['nilai_kontrak']), true, 2); ?> M
                                                    </h5>

                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul>
                                        <li class="" style="width: 60% !important">
                                            <a href="#viewkontraktual" class="">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> BELUM KONTRAK </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h6><?= number_format(($belumlelang['jml_paket'] + $proseslelang['jml_paket'] + $gagallelang['jml_paket']), 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($belumlelang['nilai_kontrak'] + $proseslelang['nilai_kontrak'] + $gagallelang['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                            <ul>
                                                <li class="w-30">
                                                    <a href="#viewkontraktual" class="w-100">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-4">
                                                                <h4 class="mb-0"><b> GAGAL LELANG </b></h4>

                                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                    <h6><?= number_format($gagallelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                                    <h5 class="mb-0">
                                                                        <?= toMilyar($gagallelang['nilai_kontrak'], true, 2); ?> M
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </a>
                                                </li>
                                                <li class="w-30">
                                                    <a href="#viewkontraktual" class="w-100">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-4">
                                                                <h4 class="mb-0"><b> PROSES LELANG </b></h4>

                                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                    <h6><?= number_format($proseslelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                                    <h5 class="mb-0">
                                                                        <?= toMilyar($proseslelang['nilai_kontrak'], true, 2); ?> M
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </a>
                                                </li>
                                                <li class="w-40">
                                                    <a href="#belum-lelang" class="w-100">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-4">
                                                                <h4 class="mb-0"><b> BELUM LELANG </b></h4>

                                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                    <h6><?= number_format($belumlelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                                    <h5 class="mb-0">
                                                                        <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?> M
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="" style="width: 40% !important">
                                            <a href="#viewkontraktual" class="">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> TERKONTRAK * </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h6> <?= number_format($terkontrak['jml_paket'] + $persiapankontrak['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($terkontrak['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <b><i>* Termasuk MYC lanjutan</i></b>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>

    <!-- END  POSTUR PAKET KONTRAKTUAL -->
    <div class="pagebreak"> </div>
    <!-- POSTUR PAKET BELUM LELANG -->

    <div id="belum-lelang" class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    POSTUR PAKET BELUM LELANG TA <?= session('userData.tahun') ?>
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body" style="padding:0px;">

            <!--begin::Section-->
            <div class="kt-section  pb-4 pt-3">

                <div class="kt-section__content" style="overflow-x: auto;">
                    <!-- <div class="text-center mt-4">
                        <h4 class="text-dark"><b><?= $title; ?></b></h4>
                        <hr class="w-75 mb-0">
                    </div> -->

                    <div class="" style="width: 1400px; margin: 0px auto">
                        <div class="tree ml--105 pr-4">
                            <ul>
                                <li class="w-100">
                                    <a href="#" class="w-25">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-1">
                                                <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                                <label> <?= formatNumber($nilai_rpm['jml_paket'] + $nilai_sbsn['jml_paket'] + $nilai_phln['jml_paket']); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($nilai_rpm['nilai_kontrak'] + $nilai_sbsn['nilai_kontrak'] + $nilai_phln['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul>

                                        <li class="" style="width: 50% !important">
                                            <a href="#" class="w-50">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <label> <?= formatNumber($nilai_rpm['jml_paket']) ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($nilai_rpm['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <ul>
                                                <li class="w-50">
                                                    <a href="#" class="w-100">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-3">
                                                                <h4 class="mb-0"><b> SYC </b></h4>
                                                                <label> <?= formatNumber($rpmSyc['jml_paket']); ?> Paket</label>
                                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                    <h5 class="mb-0">
                                                                        <?= toMilyar($rpmSyc['nilai_kontrak'], true, 2); ?> M
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div class="border-single-tree-down"></div>
                                                    <a href="#" class="w-100">
                                                        <div class="tree-content">
                                                            <div class="card bg-secondary text-dark bg-tree-footer card-body shadow text-left">
                                                                <h6>Antara Lain :</h6>
                                                                <?php foreach ($rpmSycList as $key => $daftarsyc) { ?>

                                                                    <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>


                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="w-50">
                                                    <a href="#" class="w-100">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-3">
                                                                <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                                <label> <?= formatNumber($rpmMyc['jml_paket']); ?> Paket</label>
                                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                    <h5 class="mb-0">
                                                                        <?= toMilyar($rpmMyc['nilai_kontrak'], true, 2); ?> M
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div class="border-single-tree-down"></div>
                                                    <a href="#" class="w-100">
                                                        <div class="tree-content">
                                                            <div class="card bg-secondary text-dark bg-tree-footer card-body shadow text-left">
                                                                <h6>Antara Lain :</h6>
                                                                <?php foreach ($rpmMycList as $key => $daftarsyc) { ?>

                                                                    <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>


                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="" style="width: 20% !important">
                                            <a href="#" class="w-75">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <label><?= formatNumber($nilai_sbsn['jml_paket']); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($nilai_sbsn['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="" style="width: 30% !important">
                                            <a href="#" class="w-75">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PHLN </b></h4>
                                                        <label><?= formatNumber($nilai_phln['jml_paket']); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($nilai_phln['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-75">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-3">
                                                        <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                        <label><?= formatNumber($phlnMyc['jml_paket']); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($phlnMyc['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-75">
                                                <div class="tree-content">
                                                    <div class="card bg-secondary text-dark bg-tree-footer card-body shadow text-left">
                                                        <h6>Antara Lain :</h6>
                                                        <?php foreach ($phlnMycList as $key => $daftarsyc) { ?>

                                                            <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>


                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>



                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>

    <!-- END  POSTUR PAKET BELUM LELANG -->
    <div class="pagebreak"> </div>
    <!-- DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN -->

    <div class="kt-portlet kt-portlet--tab" id="daftar_paket_belum_lelang_rpm_syc_per_kegiatan">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-portlet">
                <div class="kt-portlet__body">

                    <!--begin::Section-->
                    <div class="kt-section mb-0">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right mt-3 float-right">
                                <i><b>*Dalam Milyar Rupiah</b></i>
                            </div>
                        </div>

                        <div class="table-responsive tableFixHead">

                            <?php $colspan = 8; ?>
                            <table class="table-bordered mb-0 table-striped" id="table" width="100%">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th style="padding: 0px 4px 0px 4px !important">No</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Kegiatan</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Paket</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Pagu</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Antara Lain</th>
                                </thead>

                                <tbody id="tbody-utama">
                                    <?php
                                    if ($belum_lelang_rpm_syc) {
                                        foreach ($belum_lelang_rpm_syc as $key => $val) { ?>
                                            <tr>
                                                <th style="padding: 0px 4px 0px 4px !important" scope="row"><?= ++$key ?></th>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= $val->nmgiat; ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= $val->jml_paket; ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= toMilyar($val->pagu, false, 2); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= str_replace(",", "", $val->paketList->paket) ?></td>
                                            </tr>

                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td style="padding: 0px 4px 0px 4px !important;text-align: center;" scope="row" colspan="5"><b>Tidak Ada Data</b></td>
                                        </tr>;
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>
    <!-- END DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN -->
    <div class="pagebreak"> </div>
    <!-- DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN -->

    <div class="kt-portlet kt-portlet--tab" id="daftar_paket_belum_lelang_myc_per_kegiatan">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-portlet">
                <div class="kt-portlet__body">

                    <!--begin::Section-->
                    <div class="kt-section mb-0">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right mt-3 float-right">
                                <i><b>*Dalam Milyar Rupiah</b></i>
                            </div>
                        </div>

                        <div class="table-responsive tableFixHead">

                            <?php $colspan = 8; ?>
                            <table class="table-bordered mb-0 table-striped" id="table" width="100%">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th style="padding: 0px 4px 0px 4px !important">No</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Kegiatan</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Paket</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Pagu</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Antara Lain</th>
                                </thead>

                                <tbody id="tbody-utama">
                                    <?php
                                    if ($belum_lelang_myc) {
                                        foreach ($belum_lelang_myc as $key => $val) { ?>
                                            <tr>
                                                <th style="padding: 0px 4px 0px 4px !important" scope="row"><?= ++$key ?></th>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= $val->nmgiat; ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= $val->jml_paket; ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= toMilyar($val->pagu, false, 2); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= str_replace(",", "", $val->paketList->paket) ?></td>
                                            </tr>

                                        <?php }
                                    } else {  ?>
                                        <tr>
                                            <td style="padding: 0px 4px 0px 4px !important;text-align: center;" scope="row" colspan="5"><b>Tidak Ada Data</b></td>
                                        </tr>;
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>
    <!-- END DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN -->
    <div class="pagebreak"> </div>
    <!-- PAKET BELUM LELANG PHLN - MYC PROJECT LOAN -->

    <div class="kt-portlet kt-portlet--tab" id="paket_belum_lelang_phln_myc_project_loan">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PAKET BELUM LELANG PHLN - MYC PROJECT LOAN
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-portlet">
                <div class="kt-portlet__body">

                    <!--begin::Section-->
                    <div class="kt-section mb-0">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right mt-3 float-right">
                                <i><b>*Dalam Milyar Rupiah</b></i>
                            </div>
                        </div>

                        <div class="table-responsive tableFixHead">

                            <?php $colspan = 8; ?>
                            <table class="table-bordered mb-0 table-striped" id="table" width="100%">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th style="padding: 0px 4px 0px 4px !important">No</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Kegiatan</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Paket</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Pagu</th>
                                        <th style="padding: 0px 4px 0px 4px !important">Antara Lain</th>
                                </thead>

                                <tbody id="tbody-utama">
                                    <?php
                                    if ($belum_lelang_phln_project_loan) {

                                        foreach ($belum_lelang_phln_project_loan as $key => $dt) { ?>
                                            <tr>
                                                <th style="padding: 0px 4px 0px 4px !important" scope="row"><?= ++$key ?></th>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= $dt->nmloan; ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= $dt->jml_paket; ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= toMilyar($dt->pagu, false, 2); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important"><?= str_replace(",", "", $dt->paketList->paket) ?></td>

                                            </tr>
                                        <?php }
                                    } else {  ?>
                                        <tr>
                                            <td style="padding: 0px 4px 0px 4px !important;text-align: center;" scope="row" colspan="5"><b>Tidak Ada Data</b></td>
                                        </tr>;
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>
    <!-- END PAKET BELUM LELANG PHLN - MYC PROJECT LOAN -->
    <div class="pagebreak"> </div>
    <!-- RENCANA TENDER, PAKET BELUM LELANG RPM -->

    <div class="kt-portlet kt-portlet--tab" id="rencana_tender_paket_belum_lelang_rpm">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    RENCANA TENDER, PAKET BELUM LELANG RPM
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section mb-0">
                <div class="kt-section__content" style="overflow-x: auto;">
                    <div class="tree ml--105 pr-4">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                            <label> <?= $belumlelang['jml_paket']  ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?> M
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="border-single-tree-down"></div>
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-2">
                                            <h4 class="mb-0"><b> RPM </b></h4>
                                            <label> <?= $tenderRpm['jml_paket'] ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    <?= toMilyar($tenderRpm['nilai_kontrak'], true, 2); ?> M
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>


                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>

    <!-- END RENCANA TENDER, PAKET BELUM LELANG RPM -->
    <div class="pagebreak"> </div>
    <!-- RENCANA TENDER, PAKET BELUM LELANG PLN -->

    <div class="kt-portlet kt-portlet--tab" id="rencana_tender_paket_belum_lelang_pln">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    RENCANA TENDER, PAKET BELUM LELANG PLN
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section mb-0">
                <div class="kt-section__content" style="overflow-x: auto;">
                    <div class="tree ml--105 pr-4">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                            <label><?= $belumlelang['jml_paket'] ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?> M
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="border-single-tree-down"></div>
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-2">
                                            <h4 class="mb-0"><b> PLN </b></h4>
                                            <label> <?= $tenderPhln['jml_paket'] ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    <?= toMilyar($tenderPhln['nilai_kontrak'], true, 2); ?> M
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>

    <!-- END RENCANA TENDER, PAKET BELUM LELANG RPM -->
    <div class="pagebreak"> </div>
    <!-- PROGRES KEUANGAN & FISIK DITJEN SDA -->
    <div class="kt-portlet kt-portlet--tab" id="progres_keuangan_fisik_ditjen_sda">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRES KEUANGAN & FISIK DITJEN SDA
                </h3>
            </div>

        </div>
        <div class="kt-portlet__body">
            <div class="kt-section mb-0">
                <div class="kt-section__content" style="overflow-x: auto">
                    <div class="row">
                        <div class="col-6">
                            <!--begin::Portlet-->
                            <div class="kt-portlet">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <span class="kt-portlet__head-icon kt-hidden">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="kt-portlet__head-title">
                                            PROGRESS KEUANGAN
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body pl-0">
                                    <input type="hidden" class="arrayget" value="<?= date("n") ?>">
                                    <div id="line-chart" style="height: 250px;"></div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                            <!--begin::Portlet-->
                            <div class="kt-portlet">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <span class="kt-portlet__head-icon kt-hidden">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="kt-portlet__head-title">
                                            PROGRESS FISIK
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div id="line-chart2" style="height: 250px;"></div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                        <div class="col-6">
                            <!--begin::Portlet-->
                            <div class="kt-portlet">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <span class="kt-portlet__head-icon kt-hidden">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="kt-portlet__head-title">
                                            PROGRESS PER SUMBER DANA
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body pt-0 pb-0">
                                    <div class="chart-container mt-2">
                                        <div id="bar-legend" class="chart-legend"></div>
                                        <div id="persumberdana" style="height: 250px;"></div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                            <!--begin::Portlet-->
                            <div class="kt-portlet">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <span class="kt-portlet__head-icon kt-hidden">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="kt-portlet__head-title">
                                            PROGRESS PER JENIS BELANJA
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body pt-0">
                                    <div id="bar-legend-jenis-belanja" class="chart-legend"></div>
                                    <div id="chatperjenisbelanja" style="height: 250px;"></div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>
    <!-- END PROGRES KEUANGAN & FISIK DITJEN SDA-->
    <div class="pagebreak"> </div>
    <!-- PROGRES  PROGRES KEUANGAN & FISIK PER KEGIATAN -->
    <div class="kt-portlet kt-portlet--tab" id="progres_keuangan_fisik_per_kegiatan">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label card-center">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRES KEUANGAN & FISIK PER KEGIATAN
                </h3>
            </div>

        </div>
        <div class="kt-portlet__body pt-1">
            <div class="kt-section mb-0" style="overflow-x: auto">
                <div class="card-body">
                    <div class="chart-container mt-2" style="height: 400px">
                        <div id="bar-legend-perkegiatan" class="chart-legend"></div>
                        <div id="perkegiatan" class="mychart"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important">No</th>
                                    <th style="padding: 0px 4px 0px 4px !important">Kode Kegiatan</th>
                                    <th style="padding: 0px 4px 0px 4px !important" style="text-align: center;">Kegiatan</th>
                                    <th style="padding: 0px 4px 0px 4px !important">Keuangan %</th>
                                    <th style="padding: 0px 4px 0px 4px !important">Fisik %</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($perkegiatan as $key => $value) {

                                    if ($value->kdgiat != '-') {
                                ?>
                                        <tr>
                                            <th style="padding: 0px 4px 0px 4px !important" scope="row"><?= ++$key ?></th>
                                            <td style="padding: 0px 4px 0px 4px !important"> <?= $value->kdgiat; ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important"> <?= $value->nmgiat; ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important"> <?= onlyTwoDecimal($value->keu); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important"> <?= onlyTwoDecimal($value->fis); ?></td>

                                        </tr>

                                <?php  }
                                } ?>


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
        <!-- <div class="footer">
            <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
        </div> -->
    </div>
    <!-- END PROGRES  PROGRES KEUANGAN & FISIK PER KEGIATAN-->

    <?php
    foreach ($qdata as $key1 => $progfis) {
        $elementId = $key1 == "Semua Satker" ? 'progres_10_satuan_kerja_terendah' : 'progres_keuangan_fisik_' . str_replace(' ', '_',  strtolower($key1));
    ?>
        <!-- PROGRESS KEU & FISIK BBWS -->

        <div class="pagebreak"> </div>
        <!-- PROGRES  PROGRES KEUANGAN & FISIK PER KEGIATAN -->
        <div id="<?php echo $elementId ?>" class="kt-portlet kt-portlet--tab">
            <div class="kt-portlet__head" style="height: 100px;">
                <div class="kt-portlet__head-label card-center">
                    <span class="kt-portlet__head-icon kt-hidden">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="kt-portlet__head-title pt-4 mt-4">
                        <?php if ($key1 != "Semua Satker") { ?>
                            PROGRES KEUANGAN & FISIK - <?= strtoupper($key1) ?>
                        <?php } else { ?>
                            <center>PROGRES 10 SATUAN KERJA TERENDAH TA <?= session('userData.tahun') ?> <br>
                                <small>Direktorat Jenderal Sumber Daya Air</small>
                            </center>
                        <?php  } ?>
                    </h3>
                </div>

            </div>
            <div class="kt-portlet__body">
                <div class="kt-section mb-0">
                    <div class="">
                        <div class="col-md-12 text-right float-right">
                            <!-- <div class="form-group">
                                    <a class="btn btn-warning btn-sm text-white pdf-report"><i class="fa fa-file-pdf"></i>PDF</a>
                                    <a href="<?php //echo site_url('/tematik/excel-rekap') 
                                                ?>" class="btn btn-success btn-sm text-white" target="_blank">
                                        <i class="fa fa-file-excel"></i>Excel
                                    </a>
                                </div> -->
                            <i><b>*Dalam Ribu Rupiah</b></i>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered mb-0 table-striped" width="100%">
                                <thead class="text-center text-white" style="background-color: #1562aa;">
                                    <tr class="text-center">
                                        <!-- <th colspan="2">&nbsp;</th> -->
                                        <th style="padding: 0px 4px 0px 4px !important" class="unit_kerja">&nbsp;</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="paket">&nbsp;</th>
                                        <?= ($key1 == 'Semua Satker' ?    '<th style="padding: 0px 4px 0px 4px !important" class="satker_">&nbsp;</th>' : '') ?>
                                        <th style="padding: 0px 4px 0px 4px !important" class="pagu-main" colspan="<?= ($key1 == 'Semua Satker' ? "4" : "5") ?>">Pagu (Rp)</th>
                                        <?= ($key1 == 'Semua Satker' ?    ' <th style="padding: 0px 4px 0px 4px !important" class="pagu-main" colspan="4">Realisasi (Rp)</th>' : '') ?>
                                        <th style="padding: 0px 4px 0px 4px !important" class="progres" colspan="2">Progres (%)</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="deviasi" colspan="2">Deviasi</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th style="padding: 0px 4px 0px 4px !important" class="unit_kerja"><?= $key1; ?></th>
                                        <?= ($key1 == 'Semua Satker' ?    '<th style="padding: 0px 4px 0px 4px !important" class="satker_">Satker</th>' : '') ?>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdNilai paket">Jml&nbsp;Paket</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_rpm pagu">RPM</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_sbsn pagu">SBSN</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_phln pagu">PHLN </th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_total pagu">TOTAL </th>

                                        <?php

                                        if ($key1 == 'Semua Satker') { ?>

                                            <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_rpm pagu">RPM </th>
                                            <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_sbsn pagu">SBSN </th>
                                            <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_phln pagu">PHLN </th>
                                            <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_total pagu">TOTAL </th>


                                        <?php } else { ?>

                                            <th style="padding: 0px 4px 0px 4px !important" class="tdNilai pagu_realisasi pagu">Realisasi</th>
                                        <?php
                                        }
                                        ?>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdPersen keu">keu</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdPersen fisik">fisik</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdPersen percentage">%</th>
                                        <th style="padding: 0px 4px 0px 4px !important" class="tdNilai rp">Rp</th>
                                    </tr>
                                </thead>

                                <tbody id="tbody-utama">
                                    <?php if ($progfis) : ?>
                                        <?php
                                        $total_pagu_rpm = 0;
                                        $total_pagu_sbsn = 0;
                                        $total_pagu_phln = 0;
                                        $total_pagu_total = 0;

                                        $total_real_rpm = 0;
                                        $total_real_sbsn = 0;
                                        $total_real_phln = 0;
                                        $total_real_total = 0;
                                        $total_paket = 0;

                                        ?>
                                        <?php
                                        foreach ($progfis as $key => $data) : ?>

                                            <!-- balai -->
                                            <tr class="stw<?= $data['stw']; ?>">
                                                <td style="padding: 0px 4px 0px 4px !important" class="tdKodeLabel col-unit_kerja">
                                                    <?php echo $data['label']; ?>
                                                </td>
                                                <?= ($key1 == 'Semua Satker' ? '<td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-center col-paket">' . $data['st'] . '</td>' : '') ?>
                                                <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-center col-paket"><?php echo $data['jml_paket']; ?></td>

                                                <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_pagu_rpm'] / 1000, 0, ',', '.'); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_pagu_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_pagu_phln'] / 1000, 0, ',', '.'); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_pagu_total'] / 1000, 0, ',', '.'); ?></td>

                                                <?php if ($key1 == 'Semua Satker') { ?>
                                                    <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_real_rpm'] / 1000, 0, ',', '.'); ?></td>
                                                    <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_real_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                                    <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_real_phln'] / 1000, 0, ',', '.'); ?></td>
                                                    <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>
                                                <?php } else { ?>

                                                    <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>

                                                <?php } ?>


                                                <td style="padding: 0px 4px 0px 4px !important" class="tdPersen text-right col-keu"><?php echo number_format($data['jml_progres_keuangan'], 2, ',', '.'); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important" class="tdPersen text-right col-fisik"><?php echo number_format($data['jml_progres_fisik'], 2, ',', '.'); ?></td>

                                                <td style="padding: 0px 4px 0px 4px !important" class="tdPersen text-right col-percentage"><?php echo ($data['jml_progres_fisik'] >= $data['jml_progres_keuangan'] ? number_format($data['jml_persen_deviasi'], 2, ',', '.') : '-'); ?></td>
                                                <td style="padding: 0px 4px 0px 4px !important" class="tdPersen text-right col-rp"><?php echo ($data['jml_progres_fisik'] >= $data['jml_progres_keuangan'] ? number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.') : '-'); ?></td>
                                            </tr>
                                            <?php
                                            $total_pagu_rpm += $data['jml_pagu_rpm'];
                                            $total_pagu_sbsn += $data['jml_pagu_sbsn'];
                                            $total_pagu_phln += $data['jml_pagu_phln'];
                                            $total_pagu_total += $data['jml_pagu_total'];

                                            $total_real_rpm += $data['jml_real_rpm'];
                                            $total_real_sbsn += $data['jml_real_sbsn'];
                                            $total_real_phln += $data['jml_real_phln'];
                                            $total_real_total += $data['jml_real_total'];
                                            $total_paket += $data['jml_paket'];

                                            ?>
                                        <?php endforeach; ?>
                                        <!-- <tr class="text-center text-white" style="background-color: #1562aa;">
                                            <td style="padding: 0px 4px 0px 4px !important" class="text-center">TOTAL</td>
                                            <?= ($key1 == 'Semua Satker' ?    '<th class="satker_">&nbsp;</th>' : '') ?>
                                            <td style="padding: 0px 4px 0px 4px !important" class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_pagu_rpm / 1000, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_pagu_sbsn / 1000, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_pagu_phln / 1000, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_total"><?php echo number_format($total_pagu_total / 1000, 0, ',', '.'); ?></td> -->

                                        <!-- <?php if ($key1 == 'Semua Satker') {  ?> -->
                                        <!-- <td style="padding: 0px 4px 0px 4px !important" class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td> -->
                                        <!-- <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_real_rpm / 1000, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_real_sbsn / 1000, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_real_phln / 1000, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>
                                        <?php  } else {  ?>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>
                                            <td style="padding: 0px 4px 0px 4px !important" class="tdNilai text-right col-pagu_total"><?php echo number_format($total_real_total / $total_pagu_total * 100, 2, ',', '.'); ?></td>

                                        <?php  }  ?> -->



                                        <!-- <td style="padding: 0px 4px 0px 4px !important" colspan="4" class="tdPersen text-right last-col">&nbsp;</td> -->
                                        <!-- </tr> -->
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <!-- <div class="footer">
                <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
            </div> -->
        </div>
        <!-- END PROGRES KEU & FISIK BBWS -->
    <?php } ?>
</div>

<!-- Modal Filter Menu -->
<div class="modal fade" id="modalFilterMenu" tabindex="-1" role="dialog" aria-labelledby="modalFilterMenuLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFilterMenuLabel">Filter Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-check mb-3">
                    <input name="filter-menu-all" class="form-check-input" type="checkbox" value="*" id="filter-all-menu" checked="">
                    <label class="form-check-label" for="filter-all-menu">
                        <strong>Semua Menu</strong>
                    </label>
                </div>
                <?php foreach ($filterMenu as $keyFilterMenu => $dataFilterMenu) : ?>
                    <div class="form-check mt-2">
                        <input
                            name="filter-menu"
                            class="form-check-input"
                            type="checkbox"
                            value="<?php echo $dataFilterMenu['menuId'] ?>"
                            data-label="<?php echo $dataFilterMenu['title'] ?>"
                            id="filter_<?php echo $dataFilterMenu['menuId'] ?>"
                            data-always-show="<?php echo $dataFilterMenu['alwaysShow'] ?>"
                            checked>
                        <label class="form-check-label" for="filter_<?php echo $dataFilterMenu['menuId'] ?>">
                            <?php echo $dataFilterMenu['title'] ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="modal-footer">
                <button type="button" name="action-filter-menu" class="btn btn-primary">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<div class="block_all"></div>

<footer>
    <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
</footer>

<!-- end:: Content -->
<?= $this->endSection() ?>



<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.js'); ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.time.min.js'); ?>


<!-- CHART REKAP REKAP UNOR -->
<?php echo view('Modules\Admin\Views\Dashboard\js\Dashboard'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartRekapUnor'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartProgresKeuFis'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartPersumberDana'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartPerJenisBelanja'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartPerkegiatan'); ?>


<script>
    if ((window.innerWidth <= 900) || (window.innerHeight <= 700)) {
        window.location.href = window.location.origin + '/dashboard_mobile'
    } else {
        $(".block_all").css("display", "none")
    }
    $(document).ready(function() {
        checkDefaultFilterMenu()
        actionFilterMenu()
    })



    $('input:checkbox[name=filter-menu-all]').change(function() {
        let checked = true
        if (!this.checked) checked = false
        $('input:checkbox[name=filter-menu]').prop('checked', checked)
    })

    $(document).on('click', 'button[name=action-filter-menu]', function() {
        actionFilterMenu(true)

        $('#modalFilterMenu').modal('hide')
    })



    function actionFilterMenu(saveOptions = false) {
        tempSave = []

        $('input:checkbox[name=filter-menu]').each((index, element) => {
            let menuElement = $('#' + $(element).val())

            if ($(element).is(':checked')) {
                menuElement.removeClass('d-none')
            } else {
                menuElement.addClass('d-none')

                if ($(element).data('always-show') != '1') {
                    tempSave.push({
                        'value': $(element).val(),
                        'checked': false
                    })
                }
            }
        })

        if (saveOptions) localStorage.setItem("filter-menu-dashboard", JSON.stringify(tempSave));
    }



    function checkDefaultFilterMenu() {
        JSON.parse(localStorage.getItem("filter-menu-dashboard")).forEach((data, index) => {
            let checkElement = $('input:checkbox[name=filter-menu][value=' + data.value + ']')

            if (checkElement.data('always-show') != '1') checkElement.removeAttr('checked')
        })
    }
</script>


<!-- END CHART REKAP REKAP UNOR -->

<?= $this->endSection() ?>