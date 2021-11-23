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
                                            <?= toMilyar($mycbaru1['total_phln']+$mycbaru2['total_phln']+$syc['total_rpm']+$mycbaru1['total_rpm']+$mycbaru2['total_rpm'], true, 2); ?> M
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
                                                    <?= toMilyar($syc['total_rpm']+$mycbaru1['total_rpm']+$mycbaru2['total_rpm'], true, 2); ?> M
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
                                                              <?= toMilyar($syc['total_rpm'], true, 2); ?>
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
                                                        <?php foreach($syclist AS $key => $daftarsyc){ ?>

                                                            <p><?= ++$key .". ". $daftarsyc['nmpaket'] ?></p>


                                                        <?php } ?>
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
                                                            <?= toMilyar($mycbaru1['total_rpm']+$mycbaru2['total_rpm'], true, 2); ?> M
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
                                                        <?php foreach($mycbarulist AS $key => $daftarsyc){ ?>

                                                            <p><?= ++$key .". ". $daftarsyc['nmpaket'] ?></p>


                                                            <?php } ?>
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
                                                    <?= toMilyar($mycbaru1['total_phln']+$mycbaru2['total_phln'], true, 2); ?> M
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
                                                    <?= toMilyar($mycbaru1['total_phln']+$mycbaru2['total_phln'], true, 2); ?> M
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
                                                <?php foreach($mycbaruphlnlist AS $key => $daftarsyc){ ?>

                                                    <p><?= ++$key .". ". $daftarsyc['nmpaket'] ?></p>


                                                    <?php } ?>
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