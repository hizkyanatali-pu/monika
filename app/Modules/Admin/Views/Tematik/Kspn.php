<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                KSPN 
            </h5>
            <select class="form-control" style="width: 140px;">
                <option>Danau Toba</option>
                <option>Borobudur</option>
                <option>Mandalika</option>
                <option>Labuan Bojo</option>
                <option>Manado</option>
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
                    <div class="col-md-4">
                        <label class="mb-0">KSPN TA</label>
                        <div class="input-group" style="width:100px !important">
                            <select class="form-control" id="listmonth" name="month">
                            	<option>2021</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 text-right mt-3">
                        <div class="form-group">
                            <a href="" class="btn btn-success btn-sm text-white" target="_blank"><i class="fa fa-file-excel"></i>Excel</a>
                        </div>
                    </div>
                </div>    

                <div class="table-responsive tableFixHead">

                    <?php $colspan = 8; ?>
                    <table class="table table-bordered mb-0 table-striped" id="table">
                        <thead>
                            <tr class=" text-center bg-purple">
                                <th class="" rowspan="2">No</th>
                                <th class="" rowspan="2">Satker / Paket</th>
                                <th class="" colspan="2">Target</th>
                                <th class="" rowspan="2">Lokasi</th>
                                <th class="" colspan="3">Pagu</th>
                                <th class="" colspan="3">Realisasi</th>
                                <th class="" colspan="2">Progres (%)</th>
                            </tr>
                            <tr class=" text-center bg-purple">
                                <th class="">Vol</th>
                                <th class="">Satuan</th>
                                <th class="">RPM</th>
                                <th class="">PHLN</th>
                                <th class="">Total</th>
                                <th class="">RPM</th>
                                <th class="">PHLN</th>
                                <th class="">Total</th>
                                <th class="">Keu</th>
                                <th class="">Fis</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            
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