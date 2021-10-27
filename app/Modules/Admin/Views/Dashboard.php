<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Dashboard </h3>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!-- PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="float-left">
                        <i><b>Status : <?= $rekapunor[0]['status'] ?></b></i>
                    </div>

                    <div class="float-right">
                        <i><b>*Dalam Ribu Rupiah</b></i>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Unit Organisasi</th>
                                    <th rowspan="2">Pagu</th>
                                    <th rowspan="2">Realisasi</th>
                                    <th colspan="2">Progress</th>

                                </tr>
                                <tr class="text-center  text-white" style="background-color: #1562aa;">
                                    <th>Keuangan</th>
                                    <th>Fisik</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rekapunor as $key => $val) { ?>

                                    <tr <?= ($val['kdunit'] == 06 ? "class='tdprogram font-weight-bold'" : "") ?>>
                                        <th scope="row"><?= ++$key ?></th>
                                        <td><?= $val['nmsingkat']; ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= toRupiah($val['pagu_total'], false); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= toRupiah($val["real_total"], false); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_keu'], 2, ',', '.'); ?> %</td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_fisik'], 2, ',', '.'); ?> %</td>

                                    </tr>


                                <?php   } ?>


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="chart-container mt-2" style="height: 500px">
                    <div id="bar-legend" class="chart-legend"></div>
                    <div id="placeholder-bar-chart" class="mychart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- END  PROGRES FISIK & KEUANGAN KEMENTERIAN PUPR -->

    <!-- PROGRES PROGRAM PADAT KARYA PER KEGIATAN -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRES PROGRAM PADAT KARYA PER KEGIATAN
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                    <th rowspan="2" class="text-center">No</th>
                                    <th rowspan="2" class="text-center">Kegiatan</th>
                                    <th colspan="4" class="text-center">Target</th>
                                    <th colspan="8" class="text-center">Realisasi</th>
                                </tr>
                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                    <th>Paket/Lokasi</th>
                                    <th>Pagu</th>
                                    <th>Tenaga Kerja</th>
                                    <th>H.O.K</th>

                                    <th>Paket/Lokasi</th>
                                    <th>%</th>
                                    <th>Pagu</th>
                                    <th>%</th>
                                    <th>Tenaga Kerja</th>
                                    <th>%</th>
                                    <th>H.O.K</th>
                                    <th>%</th>


                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>

                                    <td>Table cell</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div id="line-chart" style="height: 300px;"></div>

    </div>

    <!-- END  PROGRES PROGRAM PADAT KARYA PER KEGIATAN -->

    <!-- PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR T.A.2021 -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR T.A.2021
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-portlet">
                <div class="kt-portlet__body">

                    <!--begin::Section-->
                    <div class="kt-section">
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

                        <div class="table-responsive tableFixHead">

                            <?php $colspan = 8; ?>
                            <table class="table table-bordered mb-0 table-striped" id="table">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th rowspan="3">No</th>
                                        <th class="tematik" rowspan="3">Tematik</th>
                                        <th class="pagu" rowspan="3">Pagu (dalam Milyar)</th>
                                        <th class="realisasi" colspan="3">Realisasi</th>
                                        <th class="keterangan" rowspan="3">Keterangan</th>
                                    </tr>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th class="progres_keu progres" colspan="2">Keuangan</th>
                                        <th class="progres_fis progres">Fisik</th>
                                    </tr>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th class="progres_keu progres">Rp</th>
                                        <th class="progres_fis progres">%</th>
                                        <th class="progres_fis progres">%</th>

                                    </tr>
                                </thead>

                                <tbody id="tbody-utama">
                                    <?php
                                    $no = 1;
                                    foreach ($data as $key => $value) :
                                    ?>
                                        <tr>
                                            <td class="tdprogram"><?php echo $no++ ?></td>
                                            <td class="col-tematik tdprogram"><?php echo $value['title'] ?></td>
                                            <td class="col-pagu tdprogram"><?php echo toMilyar($value['totalPagu'], false) ?></td>
                                            <td class="col-realisasi tdprogram"><?php echo toMilyar($value['totalRealisasi'], false) ?></td>
                                            <td class="col-progres_keu tdprogram"><?php echo onlyTwoDecimal($value['totalProgKeu']) ?></td>
                                            <td class="col-progres_fis tdprogram"><?php echo onlyTwoDecimal($value['totalProgFis']) ?></td>
                                            <td class="col-keterangan tdprogram"></td>
                                        </tr>
                                        <?php foreach ($value['list'] as $key2 => $value2) : ?>
                                            <tr>
                                                <td></td>
                                                <td class="col-tematik"><?php echo $value2->tematik ?></td>
                                                <td class="col-pagu"><?php echo toMilyar($value2->pagu, false) ?></td>
                                                <td class="col-realisasi"><?php echo toMilyar($value2->realisasi, false) ?></td>
                                                <td class="col-progres_keu"><?php echo onlyTwoDecimal($value2->prog_keu) ?></td>
                                                <td class="col-progres_fis"><?php echo onlyTwoDecimal($value2->prog_fis) ?></td>
                                                <td class="col-keterangan"><?php echo $value2->ket ?></td>

                                                <!--<td><?php echo  "- " . str_replace("||", "<br> - ", str_replace(", ", ",", $value2->ket))  ?></td>-->
                                            </tr>
                                        <?php endforeach ?>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
    </div>
    <!-- END  PROGRESS KEGIATAN TEMATIK DIREKTORAT JENDERAL SUMBER DAYA AIR T.A.2021 -->


    <!-- POSTUR PAKET KONTRAKTUAL -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    POSTUR PAKET KONTRAKTUAL T.A. 2021
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="tree">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> KONTRAKTUAL </b></h4>
                                            <small>900.000 Paket</small>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    Rp. 900.000.000
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li class="w-50">
                                        <a href="#" class="">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> BELUM KONTRAK </b></h4>
                                                    <small>900.000 Paket</small>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. 100.000.000
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>

                                        </a>
                                        <ul>
                                            <li class="w-50">
                                                <a href="#" class="">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-4">
                                                            <h4 class="mb-0"><b> PROSES LELANG </b></h4>
                                                            <small>500.000 Paket</small>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    Rp. 100.000.000
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </a>
                                            </li>
                                            <li class="w-50">
                                                <a href="#" class="">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-4">
                                                            <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                                            <small>129.000 Paket</small>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    Rp. 100.0000.000
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="w-50">
                                        <a href="#" class="">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> TERKONTRAK * </b></h4>
                                                    <small>10.000.000 Paket</small>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. 100.000.000
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

    <!-- END  POSTUR PAKET KONTRAKTUAL -->

    <!-- POSTUR PAKET BELUM LELANG -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    POSTUR PAKET BELUM LELANG TA 2021
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="tree ml--105 pr-4">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                            <label><?= number_format($totaldjs->total); ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    Rp. <?= number_format($totaldjs->total); ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>

                                    <li class="" style="width: 60% !important">
                                        <a href="#" class="w-50">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> RPM </b></h4>
                                                    <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. <?= number_format($totalketahanansda->total); ?>
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
                                                            <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    <?= number_format($totaldjs->total); ?> Paket
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
                                                            <p>1. Pembangunan Saluran Drainase Utama Kawasan Industri Kab. Subang; Pagu 96,50 M </p>
                                                            <p>2. Pembangunan Ambang dan Normalisasi Sungai Bone; Kab. Bone Bolango ; Gorontalo; 0,5 Km; 5 Ha; F; K; SYC; Pagu 58,00 M</p>
                                                            <p>3. Relokasi Fasilitas Umum Pada Daerah Genangan Bendungan Karian; 0 bendungan; 0 juta m3; F; K; SYC; Pagu 50,00 M</p>
                                                            <p>4. Dan Lain - Lain</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="w-50">
                                                <a href="#" class="w-100">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-3">
                                                            <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                            <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    Rp. 100.000.000
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
                                                            <p>1. Penyelesaian Pembangunan Bendungan Keureuto Kabupaten Aceh Utara (MYC); Pagu 80,05 M</p>
                                                            <p>2. Pembangunan Bendung Gerak Karangnongko di Kab Bojonegoro; 1 bendung; 0 juta m3; F; K; MYC; Pagu 71,94 M </p>
                                                            <p>3. Pembangunan Penahan Beban (Counterweight) dan Bangunan Pelengkap Lainnya Bendungan Gondang di Kab. Karanganyar; 0 bendungan; 0 juta m3; F; K; MYC; Pagu 67,50 M </p>
                                                            <p>4. Dan Lain - Lain </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="" style="width: 40% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> PHLN </b></h4>
                                                    <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp <?= number_format($totalketahanansda->total); ?>
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
                                                    <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. 100.000.000
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
                                                    <p>1. Rehabilitation D.I. Gumbasa Weir and Groundsill Construction; Kab. Sigi; Sulawesi Tengah; 0 km; 0 hektar; F; K; MYC; Pagu 52,77 M </p>
                                                    <p>2. Works for PASIGALA raw water transmission system rehabilitation (Paket 1); Kab. Sigi; Sulawesi Tengah; 0 km; 0 m3/detik; F; K; MYC; Pagu 18,62 M </p>
                                                    <p>3. Works for PASIGALA raw water transmission system rehabilitation (Paket 1); Kab. Sigi; Sulawesi Tengah; 0 km; 0 m3/detik; F; K; MYC; Pagu 18,62 M </p>
                                                    <p>4. Dan Lain - Lain </p>
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

    <!-- END  POSTUR PAKET BELUM LELANG -->

    <!-- DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
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
                    <div class="kt-section">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right mt-3 float-right">
                                <i><b>*Dalam Milyar Rupiah</b></i>
                            </div>
                        </div>

                        <div class="table-responsive tableFixHead">

                            <?php $colspan = 8; ?>
                            <table class="table table-bordered mb-0 table-striped" id="table">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th>No</th>
                                        <th>Kegiatan</th>
                                        <th>Paket</th>
                                        <th>Pagu</th>
                                        <th>Antara Lain</th>
                                </thead>

                                <tbody id="tbody-utama">
                                    <th scope="row">1</th>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
    </div>
    <!-- END DAFTAR PAKET BELUM LELANG RPM - SYC PER KEGIATAN -->

    <!-- DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
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
                    <div class="kt-section">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right mt-3 float-right">
                                <i><b>*Dalam Milyar Rupiah</b></i>
                            </div>
                        </div>

                        <div class="table-responsive tableFixHead">

                            <?php $colspan = 8; ?>
                            <table class="table table-bordered mb-0 table-striped" id="table">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th>No</th>
                                        <th>Kegiatan</th>
                                        <th>Paket</th>
                                        <th>Pagu</th>
                                        <th>Antara Lain</th>
                                </thead>

                                <tbody id="tbody-utama">
                                    <th scope="row">1</th>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
    </div>
    <!-- END DAFTAR PAKET BELUM LELANG MYC PER KEGIATAN -->


    <!-- PAKET BELUM LELANG PHLN - MYC PROJECT LOAN -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
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
                    <div class="kt-section">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right mt-3 float-right">
                                <i><b>*Dalam Milyar Rupiah</b></i>
                            </div>
                        </div>

                        <div class="table-responsive tableFixHead">

                            <?php $colspan = 8; ?>
                            <table class="table table-bordered mb-0 table-striped" id="table">
                                <thead>
                                    <tr class="text-center  text-white" style="background-color: #1562aa;">
                                        <th>No</th>
                                        <th>Kegiatan</th>
                                        <th>Paket</th>
                                        <th>Pagu</th>
                                        <th>Antara Lain</th>
                                </thead>

                                <tbody id="tbody-utama">
                                    <th scope="row">1</th>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                    <td>Table cell</td>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!--end::Section-->
                </div>

                <!--end::Form-->
            </div>
        </div>
    </div>
    <!-- END PAKET BELUM LELANG PHLN - MYC PROJECT LOAN -->

    <!-- RENCANA TENDER, PAKET BELUM LELANG RPM -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    RENCANA TENDER, PAKET BELUM LELANG RPM
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="tree ml--105 pr-4">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                            <label><?= number_format($totaldjs->total); ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    Rp. <?= number_format($totaldjs->total); ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>

                                    <li class="" style="width: 60% !important">
                                        <a href="#" class="w-50">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> RPM </b></h4>
                                                    <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. <?= number_format($totalketahanansda->total); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="" style="width: 40% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> PLN </b></h4>
                                                    <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp <?= number_format($totalketahanansda->total); ?>
                                                        </h5>
                                                    </div>
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

    <!-- END RENCANA TENDER, PAKET BELUM LELANG RPM -->

    <!-- RENCANA TENDER, PAKET BELUM LELANG PLN -->

    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__head" style="text-align: center;">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    RENCANA TENDER, PAKET BELUM LELANG PLN
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="tree ml--105 pr-4">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                            <label><?= number_format($totaldjs->total); ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    Rp. <?= number_format($totaldjs->total); ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>

                                    <li class="" style="width: 60% !important">
                                        <a href="#" class="w-50">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> RPM </b></h4>
                                                    <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. <?= number_format($totalketahanansda->total); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="" style="width: 40% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> PLN </b></h4>
                                                    <label><?= number_format($totaldjs->total); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp <?= number_format($totalketahanansda->total); ?>
                                                        </h5>
                                                    </div>
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

    <!-- END RENCANA TENDER, PAKET BELUM LELANG RPM -->

</div>


<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.js'); ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.time.min.js'); ?>


<!-- CHART REKAP REKAP UNOR -->
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartRekapUnor'); ?>
<!-- END CHART REKAP REKAP UNOR -->

<?= $this->endSection() ?>