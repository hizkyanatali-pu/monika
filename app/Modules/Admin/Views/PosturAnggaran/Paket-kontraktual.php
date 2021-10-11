<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                POSTUR PAKET KONTRAKTUAL TA 2021
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
                                                <i>* Termasuk MYC lanjutan</i>
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
</div>

<!-- <div id="wrapper">
    <div id="container">
        <h1><center>POSTUR PAKET KONTRAKTUAL TA 2021</center></h1>
        <br>
        <ol class="organizational-chart">
            <li>
                <div>
                    <h1>KONTRAKTUAL</h1>
                    <hr>
                     Paket
                    <hr>
                    Rp. 
                </div>

                <ol>
                    <li>
                        <div>
                            <h2>BELUM KONTRAK</h2>
                            <hr>
                             Paket
                            <hr>
                            Rp s
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <h2>PROSES LELANG</h2>
                                    <hr>
                                    8787878 Paket
                                    <hr>
                                    Rp 8787878
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h2>BELUM LELANG</h2>
                                    <hr>
                                    8787878 Paket
                                    <hr>
                                    Rp 8787878
                                </div>
                            </li>
                        </ol>
                    </li>


                    <li>
                        <div>
                            <h2>TERKONTRAK*</h2>
                            <hr>
                             Paket
                            <hr>
                            Rp 
                        </div>
                    </li>

                </ol>
            </li>
        </ol>

    </div>
</div> -->

<?= $this->endSection() ?>