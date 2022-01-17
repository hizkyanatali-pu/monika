<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                POSTUR SISA LELANG DARI PAKET TERKONTRAK TA <?= session("userData.tahun") ?>
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

                <div class="tree ml--60 pr-4">
                    <ul>
                        <li class="w-100">
                            <a href="#" class="w-25">
                                <div class="tree-content">
                                    <div class="card card-body bg-tree-1">
                                        <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                        <h4 class="mb-0"><b> SISA LELANG </b></h4>
                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                            <h5 class="mb-0">
                                                <?= toMilyar($proseslelang['nilai_kontrak'], true, 2); ?> M

                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <ul>
                                <li class="" style="width: 25% !important">
                                    <a href="#" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> RPM </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        Rp. ?
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="#" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-3">
                                                <h4 class="mb-0"><b> SYC </b></h4>
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
                                                <p>1. Pembangunan Pengendali Banjir Kali Kemuning Kab. Sampang (Lanjutan); Sisa Lelang 31,68 M </p>
                                                <p>2. Peningkatan Jaringan Irigasi DI Ladongi Kab. Kolaka Timur Provinsi Sulawesi Tenggara; Sisa Lelang 18,54 M</p>
                                                <p>3. Pembangunan Bendung Lereh I DI Lereh Kabupaten Jayapura Tahap III; Papua; Kab. Jayapura; 0.55 bendung; 0.55 bendung; F; K; SYC; Sisa Lelang 16,85 M </p>
                                                <p>4. Dan Lain - Lain </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li class="" style="width: 25% !important">
                                    <a href="#" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> SBSN </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        Rp. ?
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="#" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-3">
                                                <h4 class="mb-0"><b> SYC </b></h4>
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
                                                <p>1. Pembangunan Pengaman Pantai Kalianda (Pantai Sukaraja) Kabupaten Lampung Selatan; Sisa Lelang 24,48 M</p>
                                                <p>2. Pembangunan Pengaman Pantai Kalianda (Pantai Maja) Kabupaten Lampung Selatan; Sisa Lelang 18,85 M </p>
                                                <p>3. Pembangunan Prasarana Pengamanan Pantai Ruas Lembeng - Purnama di Kabupaten Gianyar; Sisa Lelang 18,67 M </p>
                                                <p>4. Dan Lain - Lain </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li class="" style="width: 50% !important">
                                    <a href="#" class="w-50">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> PHLN </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        Rp. ?
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
                                                        <p>1. Review Penyusunan PSETK DI Kewenangan Pusat; Sisa Lelang 0,18 M </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="w-50">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-3">
                                                        <h4 class="mb-0"><b> MYC Baru </b></h4>
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
                                                        <p>1. Penyusunan Dokumen Lingkungan Daerah Irigasi Rawa Karang Agung Tengah di Kab. Musi Banyuasin; Sisa Lelang 0,08 M </p>
                                                        <p>2. Penyusunan Dokumen Lingkungan Daerah Irigasi Rawa Delta Air Sugihan Kiri di Kab. Banyuasin; Sisa Lelang 0,08 M </p>
                                                        <p>3. Penyusunan Dokumen Lingkungan Daerah Irigasi Kelingi Tugu Mulyo di Kota Lubuk Linggau dan Kab. Musi Rawas; Sisa Lelang 0,08 M </p>
                                                        <p>4. Dan Lain - Lain </p>
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