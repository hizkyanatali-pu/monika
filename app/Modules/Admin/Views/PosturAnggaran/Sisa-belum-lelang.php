<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<style>
    .blocker-line-syc-1 { background: #fdfdfd; height: 10px; width: 100px; position: absolute; z-index: 9999; top: 0px; left: 77px; }
    .blocker-line-syc-2 { background: #fdfdfd; height: 22px; width: 100px; position: absolute; z-index: 9999; top: 0px; left: 77px; }
    .blocker-line-myc-1 { background: #fdfdfd; height: 10px; width: 100px; position: absolute; z-index: 9999; top: 0px; left: -18px; }
    .blocker-line-myc-2 { background: #fdfdfd; height: 22px; width: 100px; position: absolute; z-index: 9999; top: 0px; left: 77px; }
</style>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                POSTUR PAKET BELUM LELANG TA <?= session("userData.tahun") ?>
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        <button class="btn btn-primary mt-3" onclick="capture('#diagram-section', 'Belum Lelang')">
            <i class="fas fa-image"></i> Download Diagram
        </button>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" id="diagram-section">
    <div class=" kt-portlet">
    <div class="kt-portlet__body" style="padding:0px;">

        <!--begin::Section-->
        <div class="kt-section  pb-4 pt-3">

            <div class="kt-section__content" style="overflow-x: auto;">
                <!-- <div class="text-center mt-4">
                        <h4 class="text-dark"><b><?= $title; ?></b></h4>
                        <hr class="w-75 mb-0">
                    </div> -->
                <div class="" style="width: 1400px; margin: 0px auto">
                    <div class="tree ml--105 pr-4">
                        <ul>
                            <li class="w-100">
                                <a href="#" class="w-25">
                                    <div class="tree-content">
                                        <div class="card card-body bg-tree-1">
                                            <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                            <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                            <label> <?= formatNumber($nilai_rpm['jml_paket'] + $nilai_sbsn['jml_paket'] + $nilai_phln['jml_paket']); ?> Paket</label>
                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                <h5 class="mb-0">
                                                    <?= toMilyar($nilai_rpm['nilai_kontrak'] + $nilai_sbsn['nilai_kontrak'] + $nilai_phln['nilai_kontrak'], true, 2); ?> M
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li class="" style="width: 33% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> RPM </b></h4>
                                                    <label> <?= formatNumber($nilai_rpm['jml_paket']) ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($nilai_rpm['nilai_kontrak'], true, 2); ?> M
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        
                                        <?php if ($rpmSyc['jml_paket'] > 0 || $rpmMyc['jml_paket'] > 0) : ?>
                                            <ul>
                                                <li class="w-50">
                                                    <?php if ($rpmSyc['jml_paket'] <= 0) : ?>
                                                        <div class="blocker-line-syc-1"></div>
                                                        <div class="blocker-line-syc-2"></div>
                                                    <?php endif; ?>
    
                                                    <?php if ($rpmSyc['jml_paket'] > 0) : ?>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card card-body bg-tree-3">
                                                                    <h4 class="mb-0"><b> SYC </b></h4>
                                                                    <label> <?= formatNumber($rpmSyc['jml_paket']); ?> Paket</label>
                                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                        <h5 class="mb-0">
                                                                            <?= toMilyar($rpmSyc['nilai_kontrak'], true, 2); ?> M
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
    
                                                    <?php if (count($rpmSycList) > 0) : ?>
                                                        <div class="border-single-tree-down"></div>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card bg-secondary text-dark bg-tree-footer card-body text-left">
                                                                    <h6>Antara Lain :</h6>
                                                                    <?php foreach ($rpmSycList as $key => $daftarsyc) { ?>
                                                                        <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                </li>
    
                                                <li class="w-50">
                                                    <?php if ($rpmMyc['jml_paket'] <= 0) : ?>
                                                        <div class="blocker-line-myc-1"></div>
                                                        <div class="blocker-line-myc-2"></div>
                                                    <?php endif; ?>
    
                                                    <?php if ($rpmMyc['jml_paket'] > 0) : ?>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card card-body bg-tree-3">
                                                                    <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                                    <label> <?= formatNumber($rpmMyc['jml_paket']); ?> Paket</label>
                                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                        <h5 class="mb-0">
                                                                            <?= toMilyar($rpmMyc['nilai_kontrak'], true, 2); ?> M
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                        
                                                    <?php if (count($rpmMycList) > 0) : ?>
                                                        <div class="border-single-tree-down"></div>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card bg-secondary text-dark bg-tree-footer card-body text-left">
                                                                    <h6>Antara Lain :</h6>
                                                                    <?php foreach ($rpmMycList as $key => $daftarsyc) { ?>
                                                                        <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
    
                                    <li class="" style="width: 33% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> SBSN </b></h4>
                                                    <label><?= formatNumber($nilai_sbsn['jml_paket']); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($nilai_sbsn['nilai_kontrak'], true, 2); ?> M
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
    
                                        <?php if ($sbsnSyc['jml_paket'] > 0 || $sbsnMyc['jml_paket'] > 0) : ?>
                                            <ul>
                                                <li class="w-50">
                                                    <?php if ($sbsnSyc['jml_paket'] <= 0) : ?>
                                                        <div class="blocker-line-syc-1"></div>
                                                        <div class="blocker-line-syc-2"></div>
                                                    <?php endif; ?>
    
                                                    <?php if ($sbsnSyc['jml_paket'] > 0) : ?>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card card-body bg-tree-3">
                                                                    <h4 class="mb-0"><b> SYC </b></h4>
                                                                    <label> <?= formatNumber($sbsnSyc['jml_paket']); ?> Paket</label>
                                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                        <h5 class="mb-0">
                                                                            <?= toMilyar($sbsnSyc['nilai_kontrak'], true, 2); ?> M
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (count($sbsnSycList) > 0) : ?>
                                                        <div class="border-single-tree-down"></div>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card bg-secondary text-dark bg-tree-footer card-body text-left">
                                                                    <h6>Antara Lain :</h6>
                                                                    <?php foreach ($sbsnSycList as $key => $daftarsyc) { ?>
                                                                        <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                </li>
    
                                                <li class="w-50">
                                                    <?php if ($sbsnMyc['jml_paket'] <= 0) : ?>
                                                        <div class="blocker-line-myc-1"></div>
                                                        <div class="blocker-line-myc-2"></div>
                                                    <?php endif; ?>
    
                                                    <?php if ($sbsnMyc['jml_paket'] > 0) : ?>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card card-body bg-tree-3">
                                                                    <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                                    <label> <?= formatNumber($sbsnMyc['jml_paket']); ?> Paket</label>
                                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                        <h5 class="mb-0">
                                                                            <?= toMilyar($sbsnMyc['nilai_kontrak'], true, 2); ?> M
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
    
                                                    <?php if (count($sbsnMycList) > 0) : ?>
                                                        <div class="border-single-tree-down"></div>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card bg-secondary text-dark bg-tree-footer card-body text-left">
                                                                    <h6>Antara Lain :</h6>
                                                                    <?php foreach ($sbsnMycList as $key => $daftarsyc) { ?>
                                                                        <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
    
                                    <li class="" style="width: 33% !important">
                                        <a href="#" class="w-75">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> PHLN </b></h4>
                                                    <label><?= formatNumber($nilai_phln['jml_paket']); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($nilai_phln['nilai_kontrak'], true, 2); ?> M
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
    
                                        <?php if ($phlnSyc['jml_paket'] > 0 || $phlnMyc['jml_paket'] > 0) : ?>
                                            <ul>
                                                <li class="w-50">
                                                    <?php if ($phlnSyc['jml_paket'] <= 0) : ?>
                                                        <div class="blocker-line-syc-1"></div>
                                                        <div class="blocker-line-syc-2"></div>
                                                    <?php endif; ?>
    
                                                    <?php if ($phlnSyc['jml_paket'] > 0) : ?>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card card-body bg-tree-3">
                                                                    <h4 class="mb-0"><b> SYC </b></h4>
                                                                    <label> <?= formatNumber($phlnSyc['jml_paket']); ?> Paket</label>
                                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                        <h5 class="mb-0">
                                                                        <?= toMilyar($phlnSyc['nilai_kontrak'], true, 2); ?> M
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                        
                                                    <?php if (count($phlnSycList) > 0) : ?>
                                                        <div class="border-single-tree-down"></div>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card bg-secondary text-dark bg-tree-footer card-body text-left">
                                                                    <h6>Antara Lain :</h6>
                                                                    <?php foreach ($phlnSycList as $key => $daftarsyc) { ?>
                                                                        <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                </li>
    
                                                <li class="w-50">
                                                    <?php if ($phlnMyc['jml_paket'] <= 0) : ?>
                                                        <div class="blocker-line-myc-1"></div>
                                                        <div class="blocker-line-myc-2"></div>
                                                    <?php endif; ?>
    
                                                    <?php if ($phlnMyc['jml_paket'] > 0) : ?>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card card-body bg-tree-3">
                                                                    <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                                    <label> <?= formatNumber($phlnMyc['jml_paket']); ?> Paket</label>
                                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                        <h5 class="mb-0">
                                                                            <?= toMilyar($phlnMyc['nilai_kontrak'], true, 2); ?> M
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
    
                                                    <?php if (count($phlnMycList) > 0) : ?>
                                                        <div class="border-single-tree-down"></div>
                                                        <a href="#" class="w-100">
                                                            <div class="tree-content">
                                                                <div class="card bg-secondary text-dark bg-tree-footer card-body text-left">
                                                                    <h6>Antara Lain :</h6>
                                                                    <?php foreach ($phlnMycList as $key => $daftarsyc) { ?>
                                                                        <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
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
</div>

<?= $this->endSection() ?>