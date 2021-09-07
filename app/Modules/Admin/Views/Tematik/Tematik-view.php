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
                    <div class="col-md-4">
                        <label class="mb-0"><?php echo $filterTitle ?></label>
                        <div class="input-group" style="width:100px !important">
                            <select class="form-control" id="listmonth" name="month" disabled>
                            	<option>2021</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 text-right mt-3">
                        <div class="form-group">
                            <a 
                                href="<?php echo site_url('tematik/excel/'.$exportCode) ?>" 
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
                                <th class="">No</th>
                                <th class="">Satker / Paket</th>
                                <th class="">Vol</th>
                                <th class="">Satuan</th>
                                <th class="">Provinsi</th>
                                <th class="">Lokasi</th>
                                <th class="">Pengadaan</th>
                                <th class="">Pagu</th>
                                <th class="">Realisasi</th>
                                <th class="">% Keu</th>
                                <th class="">% Fis</th>
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
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $value->nmpaket ?></td>
                                        <td><?php echo ($value->vol) ?></td>
                                        <td><?php echo $value->satuan ?></td>
                                        <td><?php echo $value->provinsi ?></td>
                                        <td><?php echo $value->lokasi ?></td>
                                        <td><?php echo $value->pengadaan ?></td>
                                        <td><?php echo toRupiah($value->pagu, false) ?></td>
                                        <td><?php echo toRupiah($value->realisasi, false) ?></td>
                                        <td><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                                        <td><?php echo onlyTwoDecimal($value->persen_fis) ?></td>
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
</script>
</script>
<?= $this->endSection() ?>