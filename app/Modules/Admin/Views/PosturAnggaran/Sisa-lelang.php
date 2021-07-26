<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                POSTUR SISA LELANG DARI PAKET TERKONTRAK TA 2021
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
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> SISA LELANG </b></h4>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    Rp. 0
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li class="" style="width: 33% !important">
                                        <a href="#" class="w-100">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> RPM </b></h4>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. <?= number_format($totalketahanansda->total); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="w-100">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-3">
                                                    <h4 class="mb-0"><b> SYC </b></h4>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. 100.000.000
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body shadow">
                                                    <label>Antara lain :</label>
                                                    <label>1 Pembangunan pengendali banjir kali kemuning</label>
                                                    <label>Kab. Sampang (lanjutan); Sisa lelang 31,68 M</label>
                                                    <label>2 Peningkatan jaringan Irigrasi DI</label>
                                                    <label>Ladongi Kab Kolaka Timur Provinsi</label>
                                                    <label>Sulawesi tenggara, sisa lelang 18,54 M</label>
                                                    <label>3 Pembangunan Bendung lereh I di</label>
                                                    <label>Lereh Kabupaten Jayapura Tahp III;</label>
                                                    <label>Papua; Kab jayapura; 0.55</label>
                                                    <label>Bendung; 0.55 bendung; F; K; SYC;</label>
                                                    <label>Sisa lelang 16.85 M</label>
                                                    <label>4 Dan Lain Lain</label>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="" style="width: 33% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> SBSN </b></h4>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. <?= number_format($totalketahanansda->total); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    
                                    <li class="" style="width: 33% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> PHLN </b></h4>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            Rp. <?= number_format($totalketahanansda->total); ?>
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
</div>

<div id="wrapper">
    <div id="container">
        <h1><center>POSTUR SISA LELANG DARI PAKET TERKONTRAK TA 2021</center></h1>
        <br>
        <ol class="organizational-chart">
            <li>
                <div>
                    <h1>SISA LELANG</h1>
                    <hr>
                    
                </div>

                <ol>
                    <li>
                        <div>
                            <h2>RPM</h2>
                            <hr>
                            Rp <?= number_format($totalketahanansda->total); ?>s
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <h2>SYC</h2>
                                    <hr>
                                    Rp 8787878
                                </div>
                                <ol>
                                    <li>
                                        <div>
                                            <p>Antara lain :</p>
                                            <p>1 Pembangunan pengendali banjir kali kemuning</p>
                                            <p>Kab. Sampang (lanjutan); Sisa lelang 31,68 M</p>
                                            <p>2 Peningkatan jaringan Irigrasi DI</p>
                                            <p>Ladongi Kab Kolaka Timur Provinsi</p>
                                            <p>Sulawesi tenggara, sisa lelang 18,54 M</p>
                                            <p>3 Pembangunan Bendung lereh I di</p>
                                            <p>Lereh Kabupaten Jayapura Tahp III;</p>
                                            <p>Papua; Kab jayapura; 0.55</p>
                                            <p>Bendung; 0.55 bendung; F; K; SYC;</p>
                                            <p>Sisa lelang 16.85 M</p>
                                            <p>4 Dan Lain Lain</p>
                                        </div>
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </li>


                    <li>
                        <div>
                            <h2>SBSN</h2>
                            <hr>
                            Rp <?= number_format($totalketahanansda->total); ?>s
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <h2>SYC</h2>
                                    <hr>
                                    Rp 8787878
                                </div>
                                <ol>
                                    <li>
                                        <div>
                                            <p>Antara lain :</p>
                                            <p>1 Pembangunan pengendali banjir kali kemuning</p>
                                            <p>Kab. Sampang (lanjutan); Sisa lelang 31,68 M</p>
                                            <p>2 Peningkatan jaringan Irigrasi DI</p>
                                            <p>Ladongi Kab Kolaka Timur Provinsi</p>
                                            <p>Sulawesi tenggara, sisa lelang 18,54 M</p>
                                            <p>3 Pembangunan Bendung lereh I di</p>
                                            <p>Lereh Kabupaten Jayapura Tahp III;</p>
                                            <p>Papua; Kab jayapura; 0.55</p>
                                            <p>Bendung; 0.55 bendung; F; K; SYC;</p>
                                            <p>Sisa lelang 16.85 M</p>
                                            <p>4 Dan Lain Lain</p>
                                        </div>
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </li>

                    <li>
                        <div>
                            <h2>PHLN</h2>
                            <hr>
                            Rp <?= number_format($totalketahanansda->total); ?>s
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <h2>SYC</h2>
                                    <hr>
                                    Rp 8787878
                                </div>
                                <ol>
                                    <li>
                                        <div>
                                            <p>Antara lain :</p>
                                            <p>1 Pembangunan pengendali banjir kali kemuning</p>
                                            <p>Kab. Sampang (lanjutan); Sisa lelang 31,68 M</p>
                                            <p>2 Peningkatan jaringan Irigrasi DI</p>
                                            <p>Ladongi Kab Kolaka Timur Provinsi</p>
                                            <p>Sulawesi tenggara, sisa lelang 18,54 M</p>
                                            <p>3 Pembangunan Bendung lereh I di</p>
                                            <p>Lereh Kabupaten Jayapura Tahp III;</p>
                                            <p>Papua; Kab jayapura; 0.55</p>
                                            <p>Bendung; 0.55 bendung; F; K; SYC;</p>
                                            <p>Sisa lelang 16.85 M</p>
                                            <p>4 Dan Lain Lain</p>
                                        </div>
                                    </li>
                                </ol>
                            </li>
                            <li>
                                <div>
                                    <h2>MYC Baru</h2>
                                    <hr>
                                    Rp 8787878
                                </div>
                                <ol>
                                    <li>
                                        <div>
                                            <p>Antara lain :</p>
                                            <p>1 Pembangunan pengendali banjir kali kemuning</p>
                                            <p>Kab. Sampang (lanjutan); Sisa lelang 31,68 M</p>
                                            <p>2 Peningkatan jaringan Irigrasi DI</p>
                                            <p>Ladongi Kab Kolaka Timur Provinsi</p>
                                            <p>Sulawesi tenggara, sisa lelang 18,54 M</p>
                                            <p>3 Pembangunan Bendung lereh I di</p>
                                            <p>Lereh Kabupaten Jayapura Tahp III;</p>
                                            <p>Papua; Kab jayapura; 0.55</p>
                                            <p>Bendung; 0.55 bendung; F; K; SYC;</p>
                                            <p>Sisa lelang 16.85 M</p>
                                            <p>4 Dan Lain Lain</p>
                                        </div>
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </li>

                </ol>
            </li>
        </ol>

    </div>
</div>

<?= $this->endSection() ?>