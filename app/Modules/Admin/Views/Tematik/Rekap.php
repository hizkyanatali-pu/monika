<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Rekap
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="row mb-3">
                    <div class="col-md-6 mt-3">
                        <div class="dropdown dropright">
                            <button type="button" class="btn btn-primary" dropdown-toggle data-toggle="dropdown">Filter</button>
                            <div class="dropdown-menu" style="overflow-y: auto; height: 200px; z-index: 5;">
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="no" checked>No
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="tematik" checked>Tematik
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="pagu" checked>Pagu
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="realisasi" checked>Realisasi
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="progres" checked>Progres
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="keterangan" checked>Keterangan
                                        </label>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right mt-3">
                        <div class="form-group">
                            <a href="<?php echo site_url('/tematik/excel-rekap') ?>" class="btn btn-success btn-sm text-white" target="_blank">
                                <i class="fa fa-file-excel"></i>Excel
                            </a>
                        </div>
                    </div>
                </div>    

                <div class="table-responsive tableFixHead">

                    <?php $colspan = 8; ?>
                    <table class="table table-bordered mb-0 table-striped" id="table">
                        <thead>
                            <tr class=" text-center bg-purple">
                                <th class="no" rowspan="2">No</th>
                                <th class="tematik" rowspan="2">Tematik</th>
                                <th class="pagu" rowspan="2">Pagu (dalam Milyar)</th>
                                <th class="realisasi" rowspan="2">Realisasi Keu</th>
                                <th class="progres" colspan="2">Progres (%)</th>
                                <th class="keterangan" rowspan="2">Keterangan</th>
                            </tr>
                            <tr class=" text-center bg-purple">
                                <th class="progres">Keu</th>
                                <th class="progres">Fis</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php 
                                $no = 1;
                                foreach($data as $key => $value): 
                            ?>
                                <tr>
                                    <td class="no"><?php echo $no++ ?></td>
                                    <td class="tematik"><?php echo $value['title'] ?></td>
                                    <td class="pagu"><?php echo toRupiah($value['totalPagu'], false) ?></td>
                                    <td class="realisasi"><?php echo toRupiah($value['totalRealisasi'], false) ?></td>
                                    <td class="progres"><?php echo onlyTwoDecimal($value['totalProgKeu']) ?></td>
                                    <td class="progres"><?php echo onlyTwoDecimal($value['totalProgFis']) ?></td>
                                    <td class="keterangan"></td>
                                </tr>
                                <?php foreach($value['list'] as $key2 => $value2): ?>
                                    <tr>
                                        <td class="no"></td>
                                        <td class="tematik"><?php echo $value2->tematik ?></td>
                                        <td class="pagu"><?php echo toRupiah($value2->pagu, false) ?></td>
                                        <td class="realisasi"><?php echo toRupiah($value2->realisasi, false) ?></td>
                                        <td class="progres"><?php echo onlyTwoDecimal($value2->prog_keu) ?></td>
                                        <td class="progres"><?php echo onlyTwoDecimal($value2->prog_fis) ?></td>
                                        <td class="keterangan"><?php echo $value2->ket ?></td>

                                        <!--<td><?php echo  "- ". str_replace("||","<br> - ",str_replace(", ", ",", $value2->ket))  ?></td>-->
                                    </tr>
                                <?php endforeach ?>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <!--end::Section-->
        </div>

        <!--end::Form-->
    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<script>
    var $th = $('.tableFixHead1').find('thead th')
    $('.tableFixHead1').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    })

    $("#search").click(function() {
        window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
    });

    $("input:checkbox").click(function(){
        
        var column = "table ." + $(this).attr("name")
        var columns = "table .col-" + $(this).attr("name")
        $(column).toggle();
        $(columns).toggle();
    });
</script>
</script>
<?= $this->endSection() ?>