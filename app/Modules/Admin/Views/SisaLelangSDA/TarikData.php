<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Tarik Data E-Monitoring Sisa Lelang SDA
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__head row pt-3">
            <div class="col-md-3 mt-2 mb-2">
                <button class="btn btn-brand btn-elevate btn-sm __handle_tarikData">
                    <i class="la la-download"></i>
                    Tarik Data
                </button>
            </div>
        </div>

        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah Satker</th>
                                <th>Jumlah Paket Pekerjaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($qdata) : ?>
                                <?php foreach ($qdata as $k => $d) : ?>
                                    <tr>
                                        <td>
                                           <?php echo $d->created_at ?>
                                        </td>
                                        <td>
                                            <?php echo $d->jumlah_satker ?>
                                        </td>
                                        <td>
                                            <?php echo $d->jumlah_paket ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <!--end::Section-->
        </div>
        <!--end::Form-->
    </div>
</div>


<!-- Modal Tarik Data -->
<div class="modal fade" id="modalTarikData" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTarikDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTarikDataLabel">Tarik Data</h5>
        </div>
        <div class="modal-body">
            <div class="_tarikData_loading">
                <i class="fa fa-spinner fa-spin"></i>
                Sedang menarik data
            </div>
            <div class="d-none _tarikData_done">
                <i class="fa fa-check"></i>
                Data berhasil di tarik
            </div>
        </div>
        <div class="modal-footer d-none _tarikData_done">
            <a href="<?php echo site_url('preferensi/tarik-data-emon-sisa-lelang-sda'); ?>" class="btn btn-primary">
                Selesai
            </a>
        </div>
        </div>
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

    let report_open = true
    let checkbox = $("input:checkbox")
    $("input:checkbox").prop("checked", true)
    $("input:checkbox").click(function() {

        //checking checked checkbox for report button
        if ((checkbox.length - checkbox.filter(":checked").length) == checkbox.length) {

            report_open = false
        } else {

            report_open = true
        }

        var column = "table ." + $(this).attr("name")
        var columns = "table .col-" + $(this).attr("name")
        $(column).toggle();
        $(columns).toggle();
    });

    $('.__handle_tarikData').click(function() {
        $('#modalTarikData').modal('show');

        $.ajax({
            type : 'GET',
            url  : "<?php echo site_url('pulldata/emon-sisa-lelang-sda'); ?>",
            success: function (data) {
                $('._tarikData_loading').addClass('d-none');
                $('._tarikData_done').removeClass('d-none');
            }
        });
    });

    $(".pdf-report").click(function() {

        let arr = [];

        if (!$("input[name=satker]").prop("checked")) {

            arr.push("satker")
        }
        if (!$("input[name=vol]").prop("checked")) {

            arr.push("vol")
        }
        if (!$("input[name=satuan]").prop("checked")) {

            arr.push("satuan")
        }
        if (!$("input[name=provinsi]").prop("checked")) {

            arr.push("provinsi")
        }
        if (!$("input[name=lokasi]").prop("checked")) {

            arr.push("lokasi")
        }
        if (!$("input[name=pengadaan]").prop("checked")) {

            arr.push("pengadaan")
        }
        if (!$("input[name=pagu]").prop("checked")) {

            arr.push("pagu")
        }
        if (!$("input[name=realisasi]").prop("checked")) {

            arr.push("realisasi")
        }
        if (!$("input[name=p_keu]").prop("checked")) {

            arr.push("p_keu")
        }
        if (!$("input[name=p_fis]").prop("checked")) {

            arr.push("p_fis")
        }

        //condition for report button
        if (report_open) {

            $(this).attr("href", "filter=" + arr.join(','))
            $(this).attr("target", "_blank")
        } else {

            $(this).removeAttr("href")
            $(this).removeAttr("target")
        }
    })
</script>
<?= $this->endSection() ?>