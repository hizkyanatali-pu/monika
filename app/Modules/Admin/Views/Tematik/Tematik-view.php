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
                    <!-- <div class="col-md-6">
                        <div class="input-group" style="width:200px !important">
                            <select class="form-control" id="listmonth" name="month" disabled>
                                <option><?= session('userData.tahun') ?></option>
                            </select>
                            <div class="dropdown dropright">
                                <button type="button" class="btn btn-primary btn-icon ml-3" dropdown-toggle data-toggle="dropdown"><i class="la la-filter"></i></button>
                                <div class="dropdown-menu" style="overflow-y: auto; height: 200px; z-index: 5;">
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="satker">Satker / Paket
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="vol">Vol
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="satuan">Satuan
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="provinsi">Provinsi
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="lokasi">Lokasi
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="pengadaan">Pengadaan
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="pagu">Pagu
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="realisasi">Realisasi
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="p_keu">% Keu
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="p_fis">% Fis
                                            </label>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-md-6">
                        <a href="/tematik/add" class="btn btn-info">Tambah</a>
                    </div>
                    <div class="col-md-6 text-right mt-3">
                        <i><b>*Dalam Ribuan</b></i>
                    </div>
                </div>

                <div id="example-table"></div>


            </div>

            <!--end::Section-->
        </div>

        <!--end::Form-->
    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<link href="/plugins/tabulator/dist/css/tabulator_semanticui.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>

<script>
    // var $th = $('.tableFixHead1').find('thead th')
    // $('.tableFixHead1').on('scroll', function() {
    //     $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    // })

    // $("#search").click(function() {
    //     window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
    // });


    // $.ajax({
    //     type: "GET",
    //     url: "/api/data-tematik-list",
    //     // data: "data",
    //     // dataType: "dataType",
    //     success: function(response) {

    var table = new Tabulator("#example-table", {
        ajaxURL: "/api/data-tematik-list",
        groupBy: ["tematik", "subtematik", "tahun"],
        columns: [{
                title: "ID",
                field: "kdpaket",
                formatter: function(cell, formatterParams, onRendered) {

                    // Periksa apakah nilai tidak kosong
                    if (cell.getValue()) {
                        // Pisahkan nilai menggunakan koma sebagai pemisah
                        var nilaiArray = cell.getValue().split('$$');

                        // Kembalikan nilai dalam format yang diinginkan
                        return nilaiArray.join('<hr>'); // Anda dapat mengganti <br> dengan karakter pemisah yang sesuai
                    }
                    return "";
                }

            }, {

                title: "Tematik/Subtematik/Tahun/Paket",
                field: "nmpaket",
                formatter: function(cell, formatterParams, onRendered) {

                    if (cell.getValue()) {
                        // Pisahkan nilai menggunakan koma sebagai pemisah
                        var nilaiArray = cell.getValue().split('$$');

                        // Kembalikan nilai dalam format yang diinginkan
                        return nilaiArray.join('<hr>'); // Anda dapat mengganti <br> dengan karakter pemisah yang sesuai
                    }
                    return "";
                }

            },
            {
                title: "Pagu",
                field: "pagu_total",
                hozAlign: "right",

                formatter: function(cell, formatterParams, onRendered) {

                    if (cell.getValue()) {
                        // Pisahkan nilai menggunakan koma sebagai pemisah
                        var nilaiArray = cell.getValue().split('$$');

                        // Format nilai ke format rupiah
                        var formattedArray = nilaiArray.map(function(nilai) {
                            return formatRupiah(nilai); // Gunakan fungsi formatRupiah untuk mengubah nilai ke format rupiah
                        });

                        // Kembalikan nilai dalam format yang diinginkan
                        return formattedArray.join('<hr>'); // Anda dapat mengganti <br> dengan karakter pemisah yang sesuai
                    }
                    return "";
                }
            },
            {
                title: "Realisasi",
                field: "real_total",
                hozAlign: "right",

                formatter: function(cell, formatterParams, onRendered) {

                    if (cell.getValue()) {
                        // Pisahkan nilai menggunakan koma sebagai pemisah
                        var nilaiArray = cell.getValue().split('$$');
                        // Format nilai ke format rupiah
                        var formattedArray = nilaiArray.map(function(nilai) {
                            return formatRupiah(nilai); // Gunakan fungsi formatRupiah untuk mengubah nilai ke format rupiah
                        });

                        // Kembalikan nilai dalam format yang diinginkan
                        return formattedArray.join('<hr>'); // Anda dapat mengganti <br> dengan karakter pemisah yang sesuai
                    }
                    return "";
                }
            },
            {
                title: "% Keuangan",
                field: "progres_keuangan",
                hozAlign: "right",

                formatter: function(cell, formatterParams, onRendered) {

                    if (cell.getValue()) {
                        // Pisahkan nilai menggunakan koma sebagai pemisah
                        var nilaiArray = cell.getValue().split('$$');


                        // Ganti titik dengan koma pada setiap nilai
                        var modifiedArray = nilaiArray.map(function(nilai) {
                            return nilai.replace(/\./g, ','); // Mengganti titik dengan koma
                        });

                        // Kembalikan nilai dalam format yang diinginkan
                        return modifiedArray.join('<hr>'); // Anda dapat mengganti <br> dengan karakter pemisah yang sesuai
                    }
                    return "";
                }
            },
            {
                title: "% Fisik",
                field: "progres_fisik",

                hozAlign: "right",
                formatter: function(cell, formatterParams, onRendered) {

                    if (cell.getValue()) {
                        // Pisahkan nilai menggunakan koma sebagai pemisah
                        var nilaiArray = cell.getValue().split('$$');

                        // Ganti titik dengan koma pada setiap nilai
                        var modifiedArray = nilaiArray.map(function(nilai) {
                            return nilai.replace(/\./g, ','); // Mengganti titik dengan koma
                        });

                        // Kembalikan nilai dalam format yang diinginkan
                        return modifiedArray.join('<hr>'); // Anda dapat mengganti <br> dengan karakter pemisah yang sesuai
                    }
                    return "";
                }
            }


        ],
    });
    //     }
    // });
</script>
<?= $this->endSection() ?>