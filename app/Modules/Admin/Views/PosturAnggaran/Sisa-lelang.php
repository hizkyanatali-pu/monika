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
                                        <label> <?= formatNumber($nilaiRpm['jml_paket'] + $nilaiSbsn['jml_paket'] + $nilaiPhln['jml_paket']) ?> Paket</label>
                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                            <h5 class="mb-0">
                                                <?= checkMorT(($nilaiRpm['nilai_kontrak'] + $nilaiSbsn['nilai_kontrak'] + $nilaiPhln['nilai_kontrak']), true, 2); ?>
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
                                                <label> <?= formatNumber($nilaiRpm['jml_paket']) ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= checkMorT($nilaiRpm['nilai_kontrak'], true, 2); ?>
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
                                                <label> <?= formatNumber($nilaiRpmSyc['jml_paket']) ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= checkMorT($nilaiRpmSyc['nilai_kontrak'], true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php if (count($listPaketRpmSyc) > 0) : ?>
                                    <div class="border-single-tree-down"></div>
                                    <a href="#" class="w-100">
                                        <div class="tree-content">
                                            <div class="card bg-secondary text-dark bg-tree-footer card-body shadow text-left">
                                                <h6>Antara Lain :</h6>
                                                <?php foreach ($listPaketRpmSyc as $key_listPaketRpmSyc => $data_listPaketRpmSyc) : ?>
                                                    <p>
                                                        <?php echo $key_listPaketRpmSyc+1 ?>. 
                                                        <?php echo ($key_listPaketRpmSyc+1 == 4) ? 'Dan Lain - Lain' : $data_listPaketRpmSyc['nama_paket'] ?> 
                                                    </p>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </a>
                                    <?php endif; ?>
                                </li>

                                <li class="" style="width: 25% !important">
                                    <a href="#" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> SBSN </b></h4>
                                                <label> <?= formatNumber($nilaiSbsn['jml_paket']) ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= checkMorT($nilaiSbsn['nilai_kontrak'], true, 2); ?>
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
                                                <label> <?= formatNumber($nilaiSbsnSyc['jml_paket']) ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= checkMorT($nilaiSbsnSyc['nilai_kontrak'], true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php if (count($listPaketSbsnSyc) > 0) : ?>
                                        <div class="border-single-tree-down"></div>
                                        <a href="#" class="w-100">
                                            <div class="tree-content">
                                                <div class="card bg-secondary text-dark bg-tree-footer card-body shadow text-left">
                                                    <h6>Antara Lain :</h6>
                                                    <?php foreach ($listPaketSbsnSyc as $key_listPaketSbsnSyc => $data_listPaketSbsnSyc) : ?>
                                                        <p>
                                                            <?php echo $key_listPaketSbsnSyc+1 ?>. 
                                                            <?php echo ($key_listPaketSbsnSyc+1 == 4) ? 'Dan Lain - Lain' : $data_listPaketSbsnSyc['nama_paket'] ?> 
                                                        </p>
                                                    <?php endforeach ?>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                </li>

                                <li class="" style="width: 50% !important">
                                    <a href="#" class="w-50">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> PHLN </b></h4>
                                                <label> <?= formatNumber($nilaiPhln['jml_paket']) ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= checkMorT($nilaiPhln['nilai_kontrak'], true, 2); ?>
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
                                                        <label> <?= formatNumber($nilaiPhlnSyc['jml_paket']) ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= checkMorT($nilaiPhlnSyc['nilai_kontrak'], true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <?php if (count($listPaketPhlnSyc) > 0) : ?>
                                                <div class="border-single-tree-down"></div>
                                                <a href="#" class="w-100">
                                                    <div class="tree-content">
                                                        <div class="card bg-secondary text-dark bg-tree-footer card-body shadow text-left">
                                                            <h6>Antara Lain :</h6>
                                                            <?php foreach ($listPaketPhlnSyc as $key_listPaketPhlnSyc => $data_listPaketPhlnSyc) : ?>
                                                                <p>
                                                                    <?php echo $key_listPaketPhlnSyc+1 ?>. 
                                                                    <?php echo ($key_listPaketPhlnSyc+1 == 4) ? 'Dan Lain - Lain' : $data_listPaketPhlnSyc['nama_paket'] ?> 
                                                                </p>
                                                            <?php endforeach ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
                                        </li>
                                        <li class="w-50">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-3">
                                                        <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                        <label> <?= formatNumber($nilaiPhlnMycBaru['jml_paket']) ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= checkMorT($nilaiPhlnMycBaru['nilai_kontrak'], true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <?php if (count($listPaketPhlnMycBaru) > 0) : ?>
                                                <div class="border-single-tree-down"></div>
                                                <a href="#" class="w-100">
                                                    <div class="tree-content">
                                                        <div class="card bg-secondary text-dark bg-tree-footer card-body shadow text-left">
                                                            <h6>Antara Lain :</h6>
                                                            <?php foreach ($listPaketPhlnMycBaru as $key_listPaketPhlnMycBaru => $data_listPaketPhlnMycBaru) : ?>
                                                                <p>
                                                                    <?php echo $key_listPaketPhlnMycBaru+1 ?>. 
                                                                    <?php echo ($key_listPaketPhlnMycBaru+1 == 4) ? 'Dan Lain - Lain' : $data_listPaketPhlnMycBaru['nama_paket'] ?> 
                                                                </p>
                                                            <?php endforeach ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
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