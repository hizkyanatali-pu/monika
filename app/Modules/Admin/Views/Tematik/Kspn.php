<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                KSPN 
            </h5>
            <select class="form-control" id="selectKspn" style="width: 140px;">
                <option 
                    value="kspn01" 
                    <?php if($uri->getSegment(3) == "kspn01"): ?> selected <?php endif; ?>
                >
                    Danau Toba
                </option>
                <option 
                    value="kspn02" 
                    <?php if($uri->getSegment(3) == "kspn02"): ?> selected <?php endif; ?>
                >
                    Borobudur
                </option>
                <option 
                    value="kspn03" 
                    <?php if($uri->getSegment(3) == "kspn03"): ?> selected <?php endif; ?>
                >
                    Mandalika
                </option>
                <option 
                    value="kspn04" 
                    <?php if($uri->getSegment(3) == "kspn04"): ?> selected <?php endif; ?>
                >
                    Labuan Bojo
                </option>
                <option 
                    value="kspn05" 
                    <?php if($uri->getSegment(3) == "kspn05"): ?> selected <?php endif; ?>
                >
                    Manado
                </option>
            </select>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="mb-0"><?php echo $filterTitle ?></label>
                        <div class="input-group" style="width:200px !important">
                            <select class="form-control" id="listmonth" name="month" disabled>
                            	<option>2021</option>
                            </select>
                            <div class="dropdown dropright">
                                <button type="button" class="btn btn-primary ml-3" dropdown-toggle data-toggle="dropdown">Filter</button>
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
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="satker" checked>Satker / Paket
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="target" checked>Target
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="lokasi" checked>Lokasi
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right mt-3">
                        <div class="form-group">
                            <a 
                                href="<?php echo site_url('tematik/excel-kspn/'.$uri->getSegment(3)) ?>" 
                                class="btn btn-success btn-sm text-white" target="_blank"
                            >
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
                                <th class="satker" rowspan="2">Satker / Paket</th>
                                <th class="target" colspan="2">Target</th>
                                <th class="lokasi" rowspan="2">Lokasi</th>
                                <th class="pagu" colspan="3">Pagu</th>
                                <th class="realisasi" colspan="3">Realisasi</th>
                                <th class="progres" colspan="2">Progres (%)</th>
                            </tr>
                            <tr class=" text-center bg-purple">
                                <th class="target">Vol</th>
                                <th class="target">Satuan</th>
                                <th class="pagu">RPM</th>
                                <th class="pagu">PHLN</th>
                                <th class="pagu">Total</th>
                                <th class="realisasi">RPM</th>
                                <th class="realisasi">PHLN</th>
                                <th class="realisasi">Total</th>
                                <th class="progres">Keu</th>
                                <th class="progres">Fis</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php 
                                $no = 1;
                                foreach($data as $key => $value) : 
                            ?>
                                <tr>
                                    <td colspan="13"><?php echo $value->satker ?></td>
                                </tr>
                                <?php foreach ($value->paketList as $key => $value) : ?>
                                    <tr>
                                        <td class="col-no"><?php echo $no++ ?></td>
                                        <td class="col-satker"><?php echo $value->nmpaket ?></td>
                                        <td class="col-target"><?php echo onlyTwoDecimal($value->vol) ?></td>
                                        <td class="col-target"><?php echo $value->satuan ?></td>
                                        <td class="col-lokasi"><?php echo $value->lokasi ?></td>
                                        <td class="col-pagu"><?php echo toRupiah($value->pagu_rpm, false) ?></td>
                                        <td class="col-pagu"><?php echo toRupiah($value->pagu_phln, false) ?></td>
                                        <td class="col-pagu"><?php echo toRupiah($value->pagu_total, false) ?></td>
                                        <td class="col-realisasi"><?php echo toRupiah($value->realisasi_rpm, false) ?></td>
                                        <td class="col-realisasi"><?php echo toRupiah($value->realisasi_phln, false) ?></td>
                                        <td class="col-realisasi"><?php echo toRupiah($value->realisasi_total, false) ?></td>
                                        <td class="col-progres"><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                                        <td class="col-progres"><?php echo onlyTwoDecimal($value->persen_fi) ?></td>
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









<?= $this->section('upper_footer_js') ?>
<script>
    $('#selectKspn').on('change', function() {
        
        window.location = "<?php echo site_url('/') ?>/tematik/kspn/" + $(this).val();
    });
</script>
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
<?= $this->endSection() ?>