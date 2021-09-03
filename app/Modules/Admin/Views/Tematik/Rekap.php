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
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="row mb-3">
                    <div class="col-md-4">
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
                                <th class="" rowspan="2">Tematik</th>
                                <th class="" rowspan="2">Pagu (dalam Milyar)</th>
                                <th class="" rowspan="2">Realisasi Keu</th>
                                <th class="" colspan="2">Progres (%)</th>
                                <th class="" rowspan="2">Keterangan</th>
                            </tr>
                            <tr class=" text-center bg-purple">
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