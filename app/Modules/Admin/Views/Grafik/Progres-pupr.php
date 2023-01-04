<?= $this->extend('admin/layouts/grafik') ?>





<?= $this->section('content') ?>
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
        <div class="kt-portlet__body" style="padding: 20px !important;">
            <!--begin::Section-->
            <div class="kt-section">
                <input type="hidden" class="arrayget" value="<?= date("n") ?>">
                <div class="card-body pt-5">

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
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Unit Organisasi</th>
                                    <th colspan="4">Pagu</th>
                                    <th colspan="4">Realisasi</th>
                                    <th colspan="2">Progress</th>

                                </tr>
                                <tr>
                                    <th>RPM</th>
                                    <th>SBSN</th>
                                    <th>PHLN</th>
                                    <th>Total</th>

                                    <th>RPM</th>
                                    <th>SBSN</th>
                                    <th>PHLN</th>
                                    <th>Total</th>

                                    <th>Keuangan</th>
                                    <th>Fisik</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($rekapunor['unor'] as $key => $val) { ?>

                                    <tr <?= ($val['kdunit'] == 06 ? "class='tdprogram font-weight-bold'" : "") ?>>
                                        <th scope="row"><?= ++$key ?></th>
                                        <td><?= $val['nmsingkat']; ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_rpm'], 2, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_sbsn'], 2, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_phln'], 2, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_total'], 2, ',', '.'); ?></td>

                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_rpm"], 2, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_sbsn"], 2, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_phln"], 2, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_total"], 2, ',', '.'); ?></td>

                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_keu'], 2, ',', '.'); ?> %</td>
                                        <td class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_fisik'], 2, ',', '.'); ?> %</td>

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
                    <hr style="border: 1px solid black;">
                    <div class="chart-container mt-2" style="height: 500px">

                        <div id="placeholder-bar-chart" class="mychart mb-md-4 mt-5"></div>
                        <div id="bar-legend" class="chart-legend"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Section-->
        <!--end::Form-->
    </div>
</div>
<!-- end:: Content -->
<?= $this->endSection() ?>

<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.js'); ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.time.min.js'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartRekapUnor'); ?>
<?= $this->endSection() ?>