<?= $this->extend('admin/layouts/default') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?= $this->section('content') ?>
<style>
    select,
    .select2 {
        width: 90%;
        margin: 0 auto;
        border-radius: 0 !important;
        display: block;
    }

    .select2-container .select2-selection {
        height: 200px;
        overflow: scroll;
        overflow-x: hidden;
        /* Menghilangkan scrollbar horizontal */
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        line-height: 30px;
        font-size: 14p;
        border: 1px solid #fafafa;
    }
</style>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <h5 class="kt-subheader__title">
                    BIG DATA
                </h5>
                <?= csrf_field() ?>

                <div>

                    <button class="btn btn-default" data-toggle="modal" data-target="#modalFilterData">
                        <i class="fas fa-filter"></i> Filter Data
                    </button>
                    <button class="btn btn-default columnList" data-toggle="modal" data-target="#select2_modal">
                        <i class="fas fa-filter"></i> Kolom Tabel
                    </button>
                    <button class="btn btn-success" name="prepare-download-bigdata">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </button>
                </div>
            </div>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div id="example-table"></div>



</div>
<!-- end:: Content -->

<!--begin::Modal-->
<div class="modal fade" id="select2_modal" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter Kolom</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Tahun</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <select name="tahun" class="form-control" id="tahun">
                                <?php for ($i = 2013; $i <= date("Y"); $i++) {
                                    echo '<option value="' . $i . '"' . ($i == date("Y") ? "selected" : "") . '>' . $i . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Kolom</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <select name="filter-kolom" class="form-control select2" id="filter-kolom" multiple="multiple">

                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-brand tampilkan">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->

<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>

<!-- <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet"> -->
<link href="/plugins/tabulator/dist/css/tabulator_semanticui.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- <script>
    var tabel = new Tabulator("#example-table", {
        ajaxURL: "http://localhost:8080/bigdata/sda_paket", // Ganti dengan URL ke file PHP
        layout: "fitColumns",
        ajaxResponse: function(url, params, response) {
            console.log(response);
            // Mengambil definisi kolom dari respons JSON
            var kolomData = response.kolom;
            tabel.setColumns(kolomData);

            // Mengambil data dari respons JSON
            var data = response.data;
            return data;
        }
    }); 
</script>-->

<script>
    function loadColumn() {
        $('#filter-kolom').select2({});
        $.getJSON("<?php echo site_url('/bigdata/sda_paket_column?year=') ?>" + $('#tahun').val(), function(data) {
            var table = "monika_data_" + $('#tahun').val();
            // Data berisi nama-nama kolom dari backend
            var namaKolom = data;

            // Misalnya, Anda dapat memuat nama kolom ke dalam elemen HTML, seperti elemen <select>
            var selectElement = $('#filter-kolom');
            selectElement.val('');

            // Inisialisasi variabel untuk menyimpan nilai default


            $.each(namaKolom, function(index, kolom) {
                if (kolom !== "idpull" &&
                    kolom !== "in_dt" &&
                    kolom != "tayang" &&
                    kolom !== "in_dt" &&
                    kolom != "kdppk") {
                    var option = new Option(kolom, table + "." + kolom);
                    if (kolom === kolom) { // Gantilah "nilai_default" dengan nilai default yang Anda inginkan
                        option.selected = true;
                    }
                    selectElement.append(option);

                }
            });

            // Inisialisasi atau perbarui elemen Select2 jika diperlukan
            selectElement.select2({
                scrollAfterSelect: true, // Aktifkan opsi scrollAfterSelect

            });
        });
    }
</script>




<script>
    var table;

    // Mendapatkan data respons JSON dari server
    function getData(page, size, column, year) {
        if (table) {
            table.destroy();
        }
        // Lakukan AJAX request ke URL Anda dengan parameter halaman dan ukuran
        $.ajax({
            url: "<?php echo site_url('bigdata/sda_paket') ?>",
            type: "GET",
            data: {
                "page": page,
                "size": size,
                "year": year,
                "column": column
            },
            success: function(response) {
                // Mendapatkan kolom-kolom dari respons pertama
                var firstItem = response.data[0];
                var columns = [];

                // Loop melalui kunci (nama kolom) dalam objek pertama
                for (var key in firstItem) {
                    if (firstItem.hasOwnProperty(key)) {
                        // Tambahkan kolom ke array kolom
                        columns.push({
                            title: key,
                            field: key
                        });
                    }
                }

                // Inisialisasi tabel TabulatorJS dengan kolom yang telah dihasilkan
                // if (!table) {

                table = new Tabulator("#example-table", {
                    pagination: true,
                    paginationMode: "remote",
                    ajaxURL: "<?php echo site_url('bigdata/sda_paket') ?>",
                    ajaxParams: {
                        "page": page,
                        "size": size,
                        "year": year,
                        "column": column
                    },
                    columns: columns,
                });
                // } else {
                //     // Jika tabel sudah ada, update kolomnya
                //     table.setColumns(columns);
                // }
            },
            error: function(error) {
                console.error("Error: ", error);
            }
        });
    }

    // Panggil fungsi getData saat halaman pertama kali dimuat
    // getData(1, 10);
</script>

<script>
    // Memanggil loadColumn() dan mengeksekusi perintah klik saat halaman dimuat
    $(document).ready(function() {


        $(".tampilkan").on("click", function() {
            kolom = $('#filter-kolom').val();
            year = $('#tahun').val();
            getData(1, 10, kolom, year);


        })

        // $("select[name=filter-kolom]").on("change", function() {
        //     console.log($(this).val());

        // })

        $("#tahun").on("change", function() {

            loadColumn();

            // $("select[name=filter-kolom]").trigger("change")

            // $(".tampilkan").trigger("click")
        });


        $("#tahun").trigger("change");








    });
</script>
<?= $this->endSection() ?>