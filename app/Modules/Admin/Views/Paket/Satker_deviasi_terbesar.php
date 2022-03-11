<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<?php

// jumlah hari bulan ini & tanggal hari ini
$maxDays = date('t');
$currentDayOfMonth = date('j');

?>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                <?= $title; ?>
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">

                <h6 class="pb-2 mb-0"><a id="a-go-pagu" data-toggle="collapse" href="#go-pagu" aria-expanded="true" aria-controls="go-pagu">Progres Keuangan dan Fisik SDA <i class="fa fa-angle-double-down"></i></a></h6>
                <div id="go-pagu" class="row collapse in show" role="tabpanel" aria-labelledby="a-go-pagu">
                    <?php /*
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu RPM (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_rpm'] /1000,0,',','.');?></strong>
                                </p>
                            </div>

                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu SBSN (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_sbsn'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu PLN (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_phln'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu TOTAL (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_total'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Realisasi (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_real_total'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Keungan (%)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_progres_keuangan'] ,2,',','.');?></strong>
                                </p>
                            </div>

                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Fisik (%)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_progres_fisik'] ,2,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Deviasi (%)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_persen_deviasi'] ,2,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Deviasi (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_nilai_deviasi'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                        </div>
                    */ ?>

                    <div class="col-md-6">
                        <div class="card card-body text-white bg-primary">
                            <div class="clearfix">
                                <div class="float-left">
                                    <!-- <h3><?php //number_format((($qdata[0]['pagusda_progres_keuangan']-$qdata[0]['pagusda_progres_keuangan_bulan_sebelumnya']) / $maxDays * $currentDayOfMonth )+$qdata[0]['pagusda_progres_keuangan_bulan_sebelumnya'], 2, ',', '.'); 
                                                ?>% </h3> -->
                                    <h3><?= number_format((isset($qdata["Satker Deviasi terbesar"][0]['pagusda_progres_keuangan']) ? $qdata["Satker Deviasi terbesar"][0]['pagusda_progres_keuangan'] : 0), 2, ',', '.'); ?>% </h3>

                                </div>
                                <div class="float-right text-right">
                                    <h6> Progres Keuangan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body text-white bg-success">
                            <div class="clearfix">
                                <div class="float-left">
                                    <!-- <h3><?php // number_format(((($qdata[0]['pagusda_progres_fisik']-$qdata[0]['pagusda_progres_fisik_bulan_sebelumnya']) )  / $maxDays * $currentDayOfMonth ) + $qdata[0]['pagusda_progres_fisik_bulan_sebelumnya'] , 2, ',', '.'); 
                                                ?>% </h3> -->
                                    <h3><?= number_format((isset($qdata["Satker Deviasi terbesar"][0]['pagusda_progres_fisik']) ? $qdata["Satker Deviasi terbesar"][0]['pagusda_progres_fisik'] : 0), 2, ',', '.'); ?>% </h3>

                                </div>
                                <div class="float-right text-right">
                                    <h6>Progres Fisik</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php /*
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <h3>
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Keuangan (%)
                                <strong class="d-block text-gray-dark"><?=number_format($qdata[0]['pagusda_progres_keuangan'], 2, ',', '.'); ?></strong>
                                </p>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <h3>
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Fisik (%)
                                <strong class="d-block text-gray-dark"><?=number_format($qdata[0]['pagusda_progres_fisik'], 2, ',', '.'); ?></strong>
                                </p>
                                </h3>
                            </div>
                        </div>
                    */ ?>

                </div>

                <!-- <div class="bg-white rounded box-shadow"> -->
                <!-- <a id="a-go-" data-toggle="collapse" href="#go-" aria-expanded="true" aria-controls="go-">
                        menu</a>
                        <div id="go-" class="collapse in" role="tabpanel" aria-labelledby="a-go-" >
                        box-Konten
                        </div> -->

                <!-- <small class="d-block text-right mt-3">
                        <a href="#">All updates</a>
                        </small> -->
                <!-- </div> -->

                <hr>

                <div class="clearfix mb-3">
                    <div class="float-left">
                        <h6>Progres Keuangan dan Fisik <?= $title; ?></h6>
                        <!-- <div class="kt-section__content dropdown dropright">
                            <button type="button" class="btn btn-warning btn-icon" data-toggle="kt-popover" data-trigger="focus" title="" data-html="true" data-content="a. Deviasi <b>(-)</b> : keuangan lebih besar dari pada fisik. <br> <br>b. Data yang berwarna <b>Merah</b> menandakan dibawah nilai rata-rata." data-original-title="Petunjuk !"><i class="la la-lightbulb-o"></i></button> -->
                        <!-- <button type="button" class="btn btn-primary btn-icon" dropdown-toggle data-toggle="dropdown"><i class="la la-filter"></i></button> -->
                        <!--<div class="dropdown-menu" style="overflow-y: auto; height: 200px; z-index: 5;">
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="unit_kerja" disabled><?= $title ?>
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="paket" disabled>Jml Paket
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_rpm">Pagu (RPM)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_sbsn">Pagu (SBSN)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_phln">Pagu (PHLN)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_total">Pagu (Total)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_realisasi">Pagu (Realisasi)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="keu">Progres % (Keu)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="fisik">Progres % (Fisik)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="percentage">Deviasi (%)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="rp">Deviasi (Rp)
                                        </label>
                                    </div>
                                </a>
                            </div>
                        </div> -->
                    </div>
                    <div class="float-right">
                        <!-- <a class="btn btn-warning btn-sm text-white pdf-report"><i class="fa fa-file-pdf"></i>PDF</a>
                        <a target="_blank" href="<?php echo site_url('pulldata/rekap/' . $rekap) . "?idk=" . $idk . "&label=" . $label; ?>" class="btn btn-success btn-sm text-white"><i class="fa fa-file-excel"></i>Rekap</a> -->
                        <b>*Dalam Ribuan</b>
                    </div>
                </div>

                <?php foreach ($qdata as $key1 => $progfis) { ?>

                    <div class="table-responsive tableFixHead">
                        <table class="table-bordered mb-0 table-striped" width="100%">
                            <thead class="text-center text-white" style="background-color: #1562aa;">
                                <tr class="text-center">
                                    <!-- <th colspan="2">&nbsp;</th> -->
                                    <th class="unit_kerja">&nbsp;</th>
                                    <th class="paket">&nbsp;</th>
                                    <?= ($key1 == 'Satker Deviasi terbesar' ?    '<th class="satker_">&nbsp;</th>' : '') ?>
                                    <th class="pagu-main" colspan="<?= ($key1 == 'Satker Deviasi terbesar' ? "4" : "5") ?>">Pagu (Rp)</th>
                                    <?= ($key1 == 'Satker Deviasi terbesar' ?    ' <th class="pagu-main" colspan="4">Realisasi (Rp)</th>' : '') ?>
                                    <th class="progres" colspan="2">Progres (%)</th>
                                    <th class="deviasi" colspan="2">Deviasi</th>
                                </tr>
                                <tr class="text-center">
                                    <th class="unit_kerja"><?= $key1; ?></th>
                                    <?= ($key1 == 'Satker Deviasi terbesar' ?    '<th class="satker_">Satker</th>' : '') ?>
                                    <th class="tdNilai paket">Jml&nbsp;Paket</th>
                                    <th class="tdNilai pagu_rpm pagu">RPM</th>
                                    <th class="tdNilai pagu_sbsn pagu">SBSN</th>
                                    <th class="tdNilai pagu_phln pagu">PHLN </th>
                                    <th class="tdNilai pagu_total pagu">TOTAL </th>

                                    <?php

                                    if ($key1 == 'Satker Deviasi terbesar') { ?>

                                        <th class="tdNilai pagu_rpm pagu">RPM </th>
                                        <th class="tdNilai pagu_sbsn pagu">SBSN </th>
                                        <th class="tdNilai pagu_phln pagu">PHLN </th>
                                        <th class="tdNilai pagu_total pagu">TOTAL </th>


                                    <?php } else { ?>

                                        <th class="tdNilai pagu_realisasi pagu">Realisasi</th>
                                    <?php
                                    }
                                    ?>
                                    <th class="tdPersen keu">keu</th>
                                    <th class="tdPersen fisik">fisik</th>
                                    <th class="tdPersen percentage">%</th>
                                    <th class="tdNilai rp">Rp</th>
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
                                            <td class="tdKodeLabel col-unit_kerja">
                                                <?php echo $data['label']; ?>
                                            </td>
                                            <?= ($key1 == 'Satker Deviasi terbesar' ? '<td class="tdNilai text-center col-paket">' . $data['st'] . '</td>' : '') ?>
                                            <td class="tdNilai text-center col-paket"><?php echo $data['jml_paket']; ?></td>

                                            <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_pagu_rpm'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_pagu_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_pagu_phln'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_pagu_total'] / 1000, 0, ',', '.'); ?></td>

                                            <?php if ($key1 == 'Satker Deviasi terbesar') { ?>
                                                <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_real_rpm'] / 1000, 0, ',', '.'); ?></td>
                                                <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_real_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                                <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_real_phln'] / 1000, 0, ',', '.'); ?></td>
                                                <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>
                                            <?php } else { ?>

                                                <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>

                                            <?php } ?>


                                            <td class="tdPersen text-right col-keu"><?php echo number_format($data['jml_progres_keuangan'], 2, ',', '.'); ?></td>
                                            <td class="tdPersen text-right col-fisik"><?php echo number_format($data['jml_progres_fisik'], 2, ',', '.'); ?></td>

                                            <!-- <td class="tdPersen text-right col-percentage"><?php //($data['jml_progres_fisik'] >= $data['jml_progres_keuangan'] ? number_format($data['jml_persen_deviasi'], 2, ',', '.') : '-'); 
                                                                                                ?></td> -->
                                            <!-- <td class="tdPersen text-right col-rp"><?php //echo($data['jml_progres_fisik'] >= $data['jml_progres_keuangan'] ? number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.') : '-'); 
                                                                                        ?></td> -->

                                            <td class="tdPersen text-right col-percentage"><?= number_format($data['jml_persen_deviasi'], 2, ',', '.'); ?></td>
                                            <td class="tdPersen text-right col-rp"><?php echo number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.'); ?></td>

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
                                    <tr class="text-center text-white" style="background-color: #1562aa;">
                                        <td class="text-center">TOTAL</td>
                                        <?= ($key1 == 'Satker Deviasi terbesar' ?    '<th class="satker_">&nbsp;</th>' : '') ?>
                                        <td class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_pagu_rpm / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_pagu_sbsn / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_pagu_phln / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_total"><?php echo number_format($total_pagu_total / 1000, 0, ',', '.'); ?></td>

                                        <?php if ($key1 == 'Satker Deviasi terbesar') {  ?>
                                            <!-- <td class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td> -->
                                            <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_real_rpm / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_real_sbsn / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_real_phln / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>
                                        <?php  } else {  ?>
                                            <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_total"><?php echo number_format($total_real_total / $total_pagu_total * 100, 2, ',', '.'); ?></td>

                                        <?php  }  ?>



                                        <td colspan="4" class="tdPersen text-right last-col">&nbsp;</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

            </div>

            <!--end::Section-->
        </div>

        <!--end::Form-->
    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<script>
    // console.log('additional footer js')
</script>
<?= $this->endSection() ?>