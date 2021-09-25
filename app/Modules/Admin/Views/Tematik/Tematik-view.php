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
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="mb-0"><?php echo $filterTitle ?></label>
                        <div class="input-group" style="width:100px !important">
                            <select class="form-control" id="listmonth" name="month" disabled>
                                <option>2021</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 text-right mt-3">
                        <div class="form-group">
                            <button class="btn btn-info btn-sm filter-columns">Filter Columns</button>
                            <a 
                                href="<?php echo site_url('tematik/excel/'.$exportCode) ?>" 
                                class="btn btn-success btn-sm text-white" target="_blank"
                            >
                                <i class="fa fa-file-excel"></i>Excel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="filter-columns mb-3 text-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="no">No
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="satker">Satker / Paket
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="vol">Vol
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="satuan">Satuan
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="provinsi">Provinsi
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="lokasi">Lokasi
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="pengadaan">Pengadaan
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="pagu">Pagu
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="realisasi">Realisasi
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="p_keu">% Keu
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="" class="form-check-label">
                                    <input type="checkbox" type="checkbox" class="form-check-input" name="p_fis">% Fis
                                </label>
                            </div>
                        </div>
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
                                    <td colspan="11"><?php echo $value->satker ?></td>
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

    $('div.filter-columns').hide()
    $(document).on('click', 'button.filter-columns', function(){

        $('div.filter-columns').toggle()
    })

    $("input:checkbox").click(function(){
        var column = "table ." + $(this).attr("name");
        var columns = "table .col-" + $(this).attr("name");
        $(column).toggle();
        $(columns).toggle();
    });
</script>
</script>
<?= $this->endSection() ?>