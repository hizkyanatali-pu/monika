<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                <?php echo $title ?>
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
                    <div class="col-md-6">
                        <label class="mb-0"><?php echo $filterTitle ?></label>
                        <div class="input-group" style="width:200px !important">
                            <select class="form-control" id="listmonth" name="month" disabled>
                                <option>2021</option>
                            </select>
                            <div class="dropdown dropright">
                                <button type="button" class="btn btn-primary btn-icon ml-3" dropdown-toggle data-toggle="dropdown"><i class="la la-filter"></i></button>
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
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="vol" checked>Vol
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="satuan" checked>Satuan
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="provinsi" checked>Provinsi
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
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="pengadaan" checked>Pengadaan
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
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="p_keu" checked>% Keu
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" type="checkbox" class="form-check-input" name="p_fis" checked>% Fis
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
                                href="<?php echo site_url('tematik/excel/'.$exportCode) ?>" 
                                class="btn btn-success btn-sm text-white" target="_blank"
                            >
                                <i class="fa fa-file-excel"></i>Excel
                            </a>
                        </div>
                        <i><b>*Dalam Ribuan</b></i>
                    </div>
                </div>
    
                <div class="table-responsive tableFixHead">

                    <?php $colspan = 8; ?>
                    <table class="table table-bordered mb-0 table-striped" id="table">
                        <thead>
                            <tr class=" text-center bg-purple">
                                <th class="no">No</th>
                                <th class="satker">Satker / Paket</th>
                                <th class="vol">Vol</th>
                                <th class="satuan">Satuan</th>
                                <th class="provinsi">Provinsi</th>
                                <th class="lokasi">Lokasi</th>
                                <th class="pengadaan">Pengadaan</th>
                                <th class="pagu">Pagu</th>
                                <th class="realisasi">Realisasi</th>
                                <th class="p_keu">% Keu</th>
                                <th class="p_fis">% Fis</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php 
                                $no = 1;
                                foreach($data as $key => $value) : 
                            ?>
                                <tr>
                                    <td colspan="11" class="tdprogram"><?php echo $value->satker ?></td>
                                </tr>
                                <?php foreach ($value->paketList as $key => $value) : ?>
                                    <tr>
                                        <td class="col-no"><?php echo $no++ ?></td>
                                        <td class="col-satker"><?php echo $value->nmpaket ?></td>
                                        <td class="col-vol"><?php echo ($value->vol) ?></td>
                                        <td class="col-satuan"><?php echo $value->satuan ?></td>
                                        <td class="col-provinsi"><?php echo $value->provinsi ?></td>
                                        <td class="col-lokasi"><?php echo $value->lokasi ?></td>
                                        <td class="col-pengadaan"><?php echo $value->pengadaan ?></td>
                                        <td class="col-pagu"><?php echo toRupiah($value->pagu, false) ?></td>
                                        <td class="col-realisasi"><?php echo toRupiah($value->realisasi, false) ?></td>
                                        <td class="col-p_keu"><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                                        <td class="col-p_fis"><?php echo onlyTwoDecimal($value->persen_fis) ?></td>
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