<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Sisa Lelang
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body p-0">

            <!--begin::Section-->
            <div class="kt-section p-0">
                <div class="row mb-3 p-0">
                    <div class="col-md-12 p-0">
                        <iframe src="https://emonitoring.pu.go.id/api_pep/sisa_lelang_sda" title="emon sis lelang" style="width: 100%; height: 60vh; padding: 20px; border: none"></iframe>
                    </div>
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