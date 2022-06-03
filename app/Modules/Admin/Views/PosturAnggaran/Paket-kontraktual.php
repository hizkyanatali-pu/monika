<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                POSTUR PAKET KONTRAKTUAL TA <?= session("userData.tahun") ?>
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>
            <div>
                <select class="form-control" name="filter_paguAnggaran">
                    <option value="template1">Template 1</option>
                    <option value="template2" selected>Template 2</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary mt-3" id="__download-diagram">
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

            <div class="kt-section__content  d-none" id="container_template1">
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

                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                            <h6><?= number_format(($terkontrak['jml_paket'] + ($proseslelang['jml_paket'] + $belumlelang['jml_paket'] + $persiapankontrak['jml_paket'] + $gagallelang['jml_paket'])), 0, ',', '.'); ?> Paket</h6>
                                            <h5 class="mb-0">
                                                <?= toMilyar($terkontrak['nilai_kontrak'] + ($proseslelang['nilai_kontrak'] + $belumlelang['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'] + $gagallelang['nilai_kontrak']), true, 2); ?> M
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

                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h6><?= number_format(($belumlelang['jml_paket'] + $proseslelang['jml_paket'] + $gagallelang['jml_paket']), 0, ',', '.'); ?> Paket</h6>
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($belumlelang['nilai_kontrak'] + $proseslelang['nilai_kontrak'] + $gagallelang['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>

                                    </a>
                                    <ul>
                                        <li class="w-30">
                                            <a href="#" class="">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-4">
                                                        <h4 class="mb-0"><b> GAGAL LELANG </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h6><?= number_format($gagallelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($gagallelang['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                        </li>
                                        <li class="w-30">
                                            <a href="#" class="">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-4">
                                                        <h4 class="mb-0"><b> PROSES LELANG </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h6><?= number_format($proseslelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($proseslelang['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                        </li>
                                        <li class="w-40">
                                            <a href="#" class="">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-4">
                                                        <h4 class="mb-0"><b> BELUM LELANG </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h6><?= number_format($belumlelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?> M
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

                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h6> <?= number_format($terkontrak['jml_paket'] + $persiapankontrak['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($terkontrak['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <b><i>* Termasuk MYC lanjutan</i></b>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="kt-section__content" id="container_template2">
                <!-- <div class="text-center mt-4">
                        <h4 class="text-dark"><b><?= $title; ?></b></h4>
                        <hr class="w-75 mb-0">
                    </div> -->

                <d<div class="tree tree-program">
                    <ul>
                        <li class="w-100">
                            <a href="#" class="w-25">
                                <div class="tree-content">
                                    <div class="card card-body bg-tree-black shadow-lg">
                                        <h4 class="mb-0"> KONTRAKTUAL </h4>
                                        <h4 class="mb-0">DITJEN SDA</h4>
                                        <h4 class="mb-0"><?= checkMorT(($template2['mycBaruSyc']['nilai_kontrak'] + $template2['mycLanjutan']['nilai_kontrak']), true, 2) ?></h4>
                                        <h4 class="mb-0"><?= $template2['mycBaruSyc']['jml_paket'] + $template2['mycLanjutan']['jml_paket'] ?> Paket</h4>
                                    </div>
                                </div>
                            </a>
                            <ul>

                                <li class="" style="width: 65%">
                                    <a href="#" class="w-50">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-grey">
                                                <h4 class="mb-0"><b> MYC BARU & SYC </b></h4>
                                                <h4 class="mb-0"><?= checkMorT($template2['mycBaruSyc']['nilai_kontrak'], true, 2) ?></h4>
                                                <h4 class="mb-0"><?= $template2['mycBaruSyc']['jml_paket'] ?> Paket</h4>
                                            </div>
                                        </div>
                                    </a>

                                    <ul>
                                        <li class="" style="width: 33% !important">
                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-009fff">
                                                        <h4 class="mb-0"><b> Sudah Lelang</b></h4>
                                                        <h4 class="mb-0"><?= checkMorT(($template2['data_mycBaruSyc']['sudahLelang_terkontrak']['nilai_kontrak'] + $template2['data_mycBaruSyc']['sudahLelang_persiapanKontrak']['nilai_kontrak']), true, 2) ?></h4>
                                                        <h4 class="mb-0"><?= $template2['data_mycBaruSyc']['sudahLelang_terkontrak']['jml_paket'] + $template2['data_mycBaruSyc']['sudahLelang_persiapanKontrak']['jml_paket'] ?> Paket</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <ul class="ul-down">
                                                <li class="li-down w-100">
                                                    <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-ffbc00">
                                                                <h4 class="mb-0"><b> Terkontrak </b></h4>
                                                                <h4 class="mb-0"><?= checkMorT($template2['data_mycBaruSyc']['sudahLelang_terkontrak']['nilai_kontrak'], true, 2) ?></h4>
                                                                <h4 class="mb-0"><?= $template2['data_mycBaruSyc']['sudahLelang_terkontrak']['jml_paket'] ?> Paket</h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="li-down w-100">
                                                    <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-ffbc00">
                                                                <h4 class="mb-0"><b> Persiapan Kontrak </b></h4>
                                                                <h4 class="mb-0"><?= checkMorT($template2['data_mycBaruSyc']['sudahLelang_persiapanKontrak']['nilai_kontrak'], true, 2) ?></h4>
                                                                <h4 class="mb-0"><?= $template2['data_mycBaruSyc']['sudahLelang_persiapanKontrak']['jml_paket'] ?> Paket</h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="" style="width: 33% !important">
                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-009fff">
                                                        <h4 class="mb-0"><b> Proses Lelang </b></h4>
                                                        <h4 class="mb-0"><?= checkMorT($template2['data_mycBaruSyc']['prosesLelang']['nilai_kontrak'], true, 2) ?></h4>
                                                        <h4 class="mb-0"><?= $template2['data_mycBaruSyc']['prosesLelang']['jml_paket'] ?> Paket</h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="" style="width: 33% !important">
                                            <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-009fff">
                                                        <h4 class="mb-0"><b> Belum Lelang </b></h4>
                                                        <h4 class="mb-0"><?= checkMorT(($template2['data_mycBaruSyc']['belumLelang_terjadwal']['nilai_kontrak'] + $template2['data_mycBaruSyc']['belumLelang_gagalLelang']['nilai_kontrak']), true, 2) ?></h4>
                                                        <h4 class="mb-0"><?= $template2['data_mycBaruSyc']['belumLelang_terjadwal']['jml_paket'] + $template2['data_mycBaruSyc']['belumLelang_gagalLelang']['jml_paket'] ?> Paket</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <ul class="ul-down">
                                                <li class="li-down w-100">
                                                    <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-ffbc00">
                                                                <h4 class="mb-0"><b> Terjadwal </b></h4>
                                                                <h4 class="mb-0"><?= checkMorT($template2['data_mycBaruSyc']['belumLelang_terjadwal']['nilai_kontrak'], true, 2) ?></h4>
                                                                <h4 class="mb-0"><?= $template2['data_mycBaruSyc']['belumLelang_terjadwal']['jml_paket'] ?> Paket</h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="li-down w-100">
                                                    <a href="#" class="w-100 position-relative" style="z-index: 10;">
                                                        <div class="tree-content">
                                                            <div class="card card-body bg-tree-ffbc00">
                                                                <h4 class="mb-0"><b> Gagal Lelang </b></h4>
                                                                <h4 class="mb-0"><?= checkMorT($template2['data_mycBaruSyc']['belumLelang_gagalLelang']['nilai_kontrak'], true, 2) ?></h4>
                                                                <h4 class="mb-0"><?= $template2['data_mycBaruSyc']['belumLelang_gagalLelang']['jml_paket'] ?> Paket</h4>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                    </ul>

                                </li>

                                <li class="" style="width: 35%">
                                    <a href="#" class="position-relative w-50" style="z-index: 10;">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-grey">
                                                <h4 class="mb-0"><b> MYC LANJUTAN </b></h4>
                                                <h4 class="mb-0"><?= checkMorT($template2['mycLanjutan']['nilai_kontrak'], true, 2) ?></h4>
                                                <h4 class="mb-0"><?= $template2['mycLanjutan']['jml_paket'] ?> Paket</h4>
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



<?= $this->section('footer_js') ?>
<script>
    $(document).on('change', 'select[name=filter_paguAnggaran]', function() {
        $('.kt-section__content').addClass('d-none');
        $('#container_'+$(this).val()).removeClass('d-none');
    });

    $(document).on('click', '#__download-diagram', function() {
        let choosedTemplate = $('select[name=filter_paguAnggaran]').val();
        capture('#diagram-section', 'Rencana Tender ' + choosedTemplate)
    })
</script>
<?= $this->endSection() ?>