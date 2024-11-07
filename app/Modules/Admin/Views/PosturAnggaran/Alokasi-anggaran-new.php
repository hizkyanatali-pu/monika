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
        <button class="btn btn-primary mt-3" onclick="capture('#diagram-section', 'Alokasi Anggaran New')">
            <i class="fas fa-image"></i> Download Diagram
        </button>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" id="diagram-section">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="padding:0px;">

            <!--begin::Section-->
            <div class="kt-section">

                <div class="kt-section__content" style="overflow-x: auto;">
                    
                    <div class="border-red-dashed" style="width: 58% !important; margin-left: 35vw; margin-top: 100px;"></div>

                    <div class="" style="width: 1000px; margin: 0px auto">
                        <div class="tree tree-program">
                            <ul>
                                <li class="w-100">
                                    <a href="#" class="w-25">
                                        <div class="tree-content">
                                            <div class="card card-body shadow-lg bg-tree-27324C">
                                                <h4 class="mb-0"> ALOKASI ANGGARAN </h4>
                                                <h4 class="mb-0">
                                                    <?= checkMorT($_0_alokasiAnggaran, true, 2); ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </a>
    
                                    <ul>
    
                                        <li class="" style="width: 33%">
                                            <a href="#" class="w-75">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-FCD964">
                                                        <h4 class="mb-0"><b> BELANJA PEGAWAI </b></h4>
                                                        <h4 class="mb-0">
                                                            <?= checkMorT($_1_belanjaPegawai, true, 2); ?>
                                                        </h4>
                                                        <h4 class="mb-0">
                                                            (<?= $_1_belanjaPegawai_persentase; ?>%)
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
    
                                        <li style="width: 33%">
                                            <a href="#" class="position-relative w-75" style="z-index: 10;">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-FCD964">
                                                        <h4 class="mb-0"><b> BELANJA BARANG </b></h4>
                                                        <h4 class="mb-0">
                                                            <?= checkMorT($_2_belanjaBarang, true, 2); ?>
                                                        </h4>
                                                        <h4 class="mb-0">
                                                            (<?= $_2_belanjaBarang_persentase; ?>%)
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="have-line-bottom"></d>
                                        </li>
                                        
                                        <li class="" style="width: 33%">
                                            <a href="#" class="position-relative w-75" style="z-index: 10;">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-FCD964">
                                                        <h4 class="mb-0"><b> BELANJA MODAL </b></h4>
                                                        <h4 class="mb-0">
                                                            <?= checkMorT($_3_belanjaModal, true, 2); ?>
                                                        </h4>
                                                        <h4 class="mb-0">
                                                            (<?= $_3_belanjaModal_persentase; ?>%)
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <div class="border-single-tree-down last-custom"></div>
                                        </li>
    
                                    </ul>
                                    
                                    <ul class="postur-anggaran-3">
    
                                        <li class="" style="width: 50%">
                                            <a href="#" class="w-75">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-C5E2B7">
                                                        <h4 class="mb-0"><b> BARANG & MODAL OPERASIONAL </b></h4>
                                                        <h4 class="mb-0">
                                                            <?= checkMorT($_31_barangModalOperasional, true, 2); ?>
                                                        </h4>
                                                        <h4 class="mb-0">
                                                            (<?= $_31_barangModalOperasional_persentase; ?>%)
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
    
                                        <li class="" style="width: 50%">
                                            <a href="#" class="position-relative w-75" style="z-index: 10;">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-C5E2B7">
                                                        <h4 class="mb-0"><b> BARANG & MODAL NON OPERASIONAL </b></h4>
                                                        <h4 class="mb-0">
                                                            <?= checkMorT($_32_barangModalNonOperasional, true, 2); ?>
                                                        </h4>
                                                        <h4 class="mb-0">
                                                            (<?= $_32_barangModalNonOperasional_persentase; ?>%)
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <div class="border-single-tree-down last-custom-2"></div>
                                        </li>
    
                                    </ul>
                                    
                                    <ul class="postur-anggaran-3">
    
                                        <li class="" style="width: 71%">
                                            <a href="#" class="w-75">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-FEBF2C">
                                                        <h4 class="mb-0"><b> TENDER </b></h4>
                                                        <h4 class="mb-0">
                                                            <?= checkMorT($_321_tender, true, 2); ?>
                                                        </h4>
                                                        <h4 class="mb-0">
                                                            (<?= $_321_tender_persentase; ?>%)
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
    
                                            <ul>
    
                                                <li class="" style="width: 33%">
                                                    <a href="#" class="w-100">
                                                        <div class="tree-content" style="z-index: 9;">
                                                            <div class="card card-body bg-tree-F4B083">
                                                                <h4 class="mb-0"><b> SYC </b></h4>
                                                                <h4 class="mb-0">
                                                                    <?= checkMorT($_3211_syc, true, 2); ?>
                                                                </h4>
                                                                <h4 class="mb-0">
                                                                    (<?= $_3211_syc_persentase; ?>%)
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <ul class="ul-down mx-auto" style="width: 100%">
                                                        <li class="li-down" style="width: 100%;">
                                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                                <div class="tree-content">
                                                                    <div class="card card-body bg-tree-9EC2E7">
                                                                        <h4 class="mb-0"><b> BARANG</b></h4>
                                                                        <h4 class="mb-0">
                                                                            <?= checkMorT($_32111_barang, true, 2); ?>
                                                                        </h4>
                                                                        <h4 class="mb-0">
                                                                            (<?= $_32111_barang_persentase; ?>%)
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="li-down" style="width: 100%;">
                                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                                <div class="tree-content">
                                                                    <div class="card card-body bg-tree-9EC2E7">
                                                                        <h4 class="mb-0"><b> MODAL</b></h4>
                                                                        <h4 class="mb-0">
                                                                            <?= checkMorT($_32112_modal, true, 2); ?>
                                                                        </h4>
                                                                        <h4 class="mb-0">
                                                                            (<?= $_32112_modal_persentase; ?>%)
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                            
                                                <li class="" style="width: 33%">
                                                    <a href="#" class="position-relative w-100" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-F4B083">
                                                                <h4 class="mb-0"><b> MYC BARU </b></h4>
                                                                <h4 class="mb-0">
                                                                    <?= checkMorT($_3212_mycBaru, true, 2); ?>
                                                                </h4>
                                                                <h4 class="mb-0">
                                                                    (<?= $_3212_mycBaru_persentase; ?>%)
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <ul class="ul-down mx-auto" style="width: 100%">
                                                        <li class="li-down" style="width: 100%;">
                                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                                <div class="tree-content">
                                                                    <div class="card card-body bg-tree-9EC2E7">
                                                                        <h4 class="mb-0"><b> BARANG</b></h4>
                                                                        <h4 class="mb-0">
                                                                            <?= checkMorT($_32121_barang, true, 2); ?>
                                                                        </h4>
                                                                        <h4 class="mb-0">
                                                                            (<?= $_32121_barang_persentase; ?>%)
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="li-down" style="width: 100%;">
                                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                                <div class="tree-content">
                                                                    <div class="card card-body bg-tree-9EC2E7">
                                                                        <h4 class="mb-0"><b> MODAL</b></h4>
                                                                        <h4 class="mb-0">
                                                                            <?= checkMorT($_32122_modal, true, 2); ?>
                                                                        </h4>
                                                                        <h4 class="mb-0">
                                                                            (<?= $_32122_modal_persentase; ?>%)
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                
                                                <li class="" style="width: 33%">
                                                    <a href="#" class="position-relative w-100" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-F4B083">
                                                                <h4 class="mb-0"><b> MYC LANJUTAN </b></h4>
                                                                <h4 class="mb-0">
                                                                    <?= checkMorT($_3213_mycLanjutan, true, 2); ?>
                                                                </h4>
                                                                <h4 class="mb-0">
                                                                    (<?= $_3213_mycLanjutan_persentase; ?>%)
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <ul class="ul-down mx-auto" style="width: 100%">
                                                        <li class="li-down" style="width: 100%;">
                                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                                <div class="tree-content">
                                                                    <div class="card card-body bg-tree-9EC2E7">
                                                                        <h4 class="mb-0"><b> BARANG</b></h4>
                                                                        <h4 class="mb-0">
                                                                            <?= checkMorT($_32131_barang, true, 2); ?>
                                                                        </h4>
                                                                        <h4 class="mb-0">
                                                                            (<?= $_32131_barang_persentase; ?>%)
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="li-down" style="width: 100%;">
                                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                                <div class="tree-content">
                                                                    <div class="card card-body bg-tree-9EC2E7">
                                                                        <h4 class="mb-0"><b> MODAL</b></h4>
                                                                        <h4 class="mb-0">
                                                                            <?= checkMorT($_32132_modal, true, 2); ?>
                                                                        </h4>
                                                                        <h4 class="mb-0">
                                                                            (<?= $_32132_modal_persentase; ?>%)
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                            
                                            </ul>
    
                                        </li>
    
                                        <li class="" style="width: 29%">
                                            <a href="#" class="position-relative w-75" style="z-index: 10;">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-FEBF2C">
                                                        <h4 class="mb-0"><b> NON TENDER </b></h4>
                                                        <h4 class="mb-0">
                                                            <?= checkMorT($_322_nonTender, true, 2); ?>
                                                        </h4>
                                                        <h4 class="mb-0">
                                                            (<?= $_322_nonTender_persentase; ?>%)
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
    
                                            <ul class="ul-down mx-auto" style="width: 78%">
                                                <li class="li-down" style="width: 100%;">
                                                    <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-9EC2E7">
                                                                <h4 class="mb-0"><b> BARANG</b></h4>
                                                                <h4 class="mb-0">
                                                                    <?= checkMorT($_3221_barang, true, 2); ?>
                                                                </h4>
                                                                <h4 class="mb-0">
                                                                    (<?= $_3221_barang_persentase; ?>%)
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="li-down" style="width: 100%;">
                                                    <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-9EC2E7">
                                                                <h4 class="mb-0"><b> NON LAHAN</b></h4>
                                                                <h4 class="mb-0">
                                                                    <?= checkMorT($_3222_nonLahan, true, 2); ?>
                                                                </h4>
                                                                <h4 class="mb-0">
                                                                    (<?= $_3222_nonLahan_persentase; ?>%)
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="li-down mb-2" style="width: 100%;">
                                                    <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-9EC2E7">
                                                                <h4 class="mb-0"><b> LAHAN</b></h4>
                                                                <h4 class="mb-0">
                                                                    <?= checkMorT($_3222_lahan, true, 2); ?>
                                                                </h4>
                                                                <h4 class="mb-0">
                                                                    (<?= $_3222_lahan_persentase; ?>%)
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <h5><b>MODAL</b></h5>
                                            </ul>
    
                                        </li>
    
                                    </ul>
                                    
                                </li>
                            </ul>
    
                        </div>
                    </div>


                    <div class="border-red-dashed-lahan" style="top: 910px !important; height: 285px;"></div>

                </div>

            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>