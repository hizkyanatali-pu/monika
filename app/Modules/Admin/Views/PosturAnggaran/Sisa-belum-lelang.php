<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                POSTUR PAKET BELUM LELANG TA 2021
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
</div>

<!-- <div id="wrapper">
    <div id="container">
        <h1><center>POSTUR PAKET BELUM LELANG TA 2021</center></h1>
        <br>
        <ol class="organizational-chart">
            <li>
                <div>
                    <h1>BELUM LELANG</h1>
                    <hr>
                    <?= number_format($totaldjs->total); ?> Paket
                    <hr>
                    Rp. <?= number_format($totaldjs->total); ?>
                </div>

                <ol>
                    <li>
                        <div>
                            <h2>RPM</h2>
                            <hr>
                            <?= number_format($totaldjs->total); ?> Paket
                            <hr>
                            Rp <?= number_format($totalketahanansda->total); ?>s
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <h2>SYC</h2>
                                    <hr>
                                    <?= number_format($totaldjs->total); ?> Paket
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
                                    <h2>MYC BARU</h2>
                                    <hr>
                                    <?= number_format($totaldjs->total); ?> Paket
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
                            <?= number_format($totaldjs->total); ?> Paket
                            <hr>
                            Rp <?= number_format($totalketahanansda->total); ?>s
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <h2>MYC BARU</h2>
                                    <hr>
                                    <?= number_format($totaldjs->total); ?> Paket
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
</div> -->

<?= $this->endSection() ?>