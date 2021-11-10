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
    <div class=" kt-portlet">
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
                                        <label> Paket</label>
                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                            <h5 class="mb-0">
                                                <?= toMilyar($prosesbelumlelang['nilai_kontrak'], true, 2); ?> M
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
                                                <label> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        ?
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
                                                        <label> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                ?
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
                                                        <label> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                ?
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
                                                <label> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        ?
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
                                                <label> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        ?
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

<?= $this->endSection() ?>