<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<!--
ORG CHART
=========================================-->

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
            <div class="kt-section  pb-4 pt-3">

                <div class="kt-section__content">
                    <!-- <div class="text-center mt-4">
                        <h4 class="text-dark"><b><?= $title; ?></b></h4>
                        <hr class="w-75 mb-0">
                    </div> -->
                    
                    <div class="tree">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="">
                                    <div class="content">
                                        <div class="card card-body p-3 shadow">
                                            <h4 class="mb-0"><b> DIREKTORAT JENDERAL SDA </b></h4>
                                        </div>
                                        <div class="card card-body p-1 shadow bg-secondary text-dark mt-2">
                                            <h5 class="mb-0">
                                                Rp. <?= number_format($totaldjs->total); ?>
                                            </h5>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li class="w-50">
                                        <a href="#" class="">
                                            <div class="content">
                                                <div class="card card-body p-3 shadow">
                                                    <h4 class="mb-0"><b> PROGRAM KETAHANAN SDA </b></h4>
                                                </div>
                                                <div class="card card-body p-1 shadow bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        Rp. <?= number_format($totalketahanansda->total); ?>
                                                    </h5>
                                                </div>
                                            </div>

                                        </a>
                                    </li>

                                    <li class="w-50">
                                        <a href="#" class="">
                                            <div class="content">
                                                <div class="card card-body p-3 shadow">
                                                    <h4 class="mb-0"><b> PROGRAM DUKUNGAN MANAJEMEN </b></h4>
                                                </div>
                                                <div class="card card-body p-1 shadow bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        Rp. <?= number_format($totaldukungan->total); ?>
                                                    </h5>
                                                </div>
                                            </div>

                                        </a>
                                        <ul>
                                            <li class="w-50">
                                                <a href="#" class="">
                                                    <div class="content">
                                                        <div class="card card-body p-3 shadow">
                                                            <h4 class="mb-0"><b> OPERASIONAL </b></h4>
                                                            <small>(Gaji, tunjangan, operasional perkantoran)</small>
                                                        </div>
                                                        <div class="card card-body p-1 shadow bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                Rp. <?= number_format($totaloperasional->total); ?>
                                                            </h5>
                                                        </div>
                                                    </div>

                                                </a>
                                            </li>
                                            <li class="w-50">
                                                <a href="#" class="">
                                                    <div class="content">
                                                        <div class="card card-body p-3 shadow">
                                                            <h4 class="mb-0"><b> NON OPERASIONAL </b></h4>
                                                            <small>(Administrasi Kegiatan)</small>
                                                        </div>
                                                        <div class="card card-body p-1 shadow bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                Rp. <?= number_format($totalnonoperasional->total); ?>
                                                            </h5>
                                                        </div>
                                                    </div>

                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>