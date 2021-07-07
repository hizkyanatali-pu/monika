<?= $this->extend('admin/layouts/default') ?>
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
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid"">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="padding:0px;">

            <!--begin::Section-->
            <div class="kt-section">

                <div class="kt-section__content">
                
                    <ol class="organizational-chart">
                        <li>
                            <div>
                                <h4>DIREKTORAT JENDERAL SDA</h4>
                                RP. <?= number_format($djsda->total / 1000, 0, ',', '.'); ?>
                            </div>

                            <ol>
                                <li>
                                    <div>
                                        <h4>PROGRAM KETAHANAN SDA</h4>
                                        RP. <?= number_format($programketahanan->total / 1000, 0, ',', '.'); ?>
                                    </div>
                                    <ol>
                                        <li>
                                            <div>
                                                <h3>RUPIAH MURNI</h3>
                                                RP. <?= number_format($rupiahmurni->total / 1000, 0, ',', '.'); ?>
                                            </div>
                                            <ol>
                                                <li>
                                                    <div>
                                                        <h4>KONTRAK TAHUN JAMAK</h4>
                                                        RP. <?= number_format($kontraktahunjamakRM->total / 1000, 0, ',', '.'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div>
                                                        <h4>SINGLE YEAR</h4>
                                                        RP. <?= number_format($singleyearRM->total / 1000, 0, ',', '.'); ?>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                        <li>
                                            <div>
                                                <h3>PHLN DAN RMP</h3>
                                                RP. <?= number_format($phlnrmp->total / 1000, 0, ',', '.'); ?>

                                            </div>
                                            <ol>
                                                <li>
                                                    <div>
                                                        <h4>KONTRAK TAHUN JAMAK</h4>
                                                        RP. <?= number_format($phlnrmp->total / 1000, 0, ',', '.'); ?>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                        <li>
                                            <div>
                                                <h3>SBSN</h3>
                                                RP. <?= number_format($sbsn->total / 1000, 0, ',', '.'); ?>

                                            </div>
                                            <ol>
                                                <li>
                                                    <div>
                                                        <h4>KONTRAK TAHUN JAMAK</h4>
                                                        RP. <?= number_format($kontraktahunjamakSBSN->total / 1000, 0, ',', '.'); ?>

                                                    </div>
                                                </li>
                                                <li>
                                                    <div>
                                                        <h4>SINGLE YEAR</h4>
                                                        RP. <?= number_format($singleyearSBSN->total / 1000, 0, ',', '.'); ?>

                                                    </div>
                                                </li>
                                            </ol>
                                        </li>

                                    </ol>
                                </li>


                                <li>
                                    <div>
                                        <h4>PROGRAM DUKUNGAN MANAJEMEN</h4>
                                        RP. <?= number_format($programdukunganmanajemen->total / 1000, 0, ',', '.'); ?>
                                    </div>
                                    <ol>
                                        <li>
                                            <div>
                                                <h3>GAJI DAN TUNJANGAN</h3>
                                                RP. <?= number_format($gajidantunjangan->total / 1000, 0, ',', '.'); ?>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <h3>ADMINISTRASI SATKER</h3>
                                                RP. <?= number_format($administrasisatker->total / 1000, 0, ',', '.'); ?>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <h3>LAYANAN PERKANTORAN DAN SARANA PRASARANA</h3>
                                                RP. <?= number_format($layananperkantoran->total / 1000, 0, ',', '.'); ?>


                                            </div>
                                        </li>
                                    </ol>
                                </li>

                            </ol>
                        </li>
                    </ol>

                </div>

            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>