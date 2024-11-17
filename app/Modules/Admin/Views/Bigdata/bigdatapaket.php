<?= $this->extend('admin/layouts/default') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?= $this->section('content') ?>
<style>
  #map {
    width: 100%;
    height: 700px;
  }
</style>
<!-- <style>
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
</style> -->
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
                    <button class="btn btn-default columnList" data-toggle="modal" data-target="#ModalFKolom">
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
    <!-- <div>
        <button id="download-csv">Download CSV</button>
        <button id="download-json">Download JSON</button>
        <button id="download-xlsx">Download XLSX</button>
        <button id="download-pdf">Download PDF</button>
        <button id="download-html">Download HTML</button>
    </div> -->
    <!-- Form tersembunyi untuk mengirimkan permintaan ke server -->
    <form id="downloadData" action="<?= site_url('bigdata/unduh'); ?>" method="post" target="_blank">
        <!-- Tambahkan input tersembunyi untuk data yang dikirimkan -->
        <input type="hidden" name="year" id="year" value="">
        <input type="hidden" name="filter" id="filter" value="">
        <input type="hidden" name="columns" id="columns" value="">
    </form>
    <div id="example-table"></div>
</div>
<!-- end:: Content -->

<!--begin::Modal-->
<div class="modal fade ModalFKolom" id="ModalFKolom" role="dialog" aria-labelledby="" aria-hidden="true">
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
                    <!-- <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Kolom</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <select name="filter-kolom" class="form-control select2" id="filter-kolom" multiple="multiple">

                            </select>
                        </div>
                    </div> -->

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12 mr-4">Kolom</label>
                        <div class="form-group">

                            <div class="kt-checkbox-list">
                                <label class="kt-checkbox kt-checkbox--brand">
                                    <input type="checkbox">
                                    <span></span>
                                </label>

                            </div>

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

<!-- Modal Filter Data -->
<div class="modal fade" id="modalFilterData" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Filter Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="exampleInputEmail1">Opsi Data</label>
                    <select class="form-control select2" name="filter-opsi-data">
                        <option value="0" selected>Semua Data</option>
                        <!-- <option value="1">Hanya Data Aktif</option> -->
                        <option value="2">Hanya Data Di Blokir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Satker</label>
                    <select class="form-control select2" name="filter-satker">
                        <option value="*">Semua</option>
                        <?php foreach ($data['satker'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Program</label>
                    <select class="form-control select2" name="filter-program">
                        <option value="*">Semua</option>
                        <?php foreach ($data['program'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Kegiatan</label>
                    <select class="form-control select2" name="filter-kegiatan">
                        <option value="*">Pilih Program Lebih Dahulu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Output</label>
                    <select class="form-control select2" name="filter-output">
                        <option value="*">Pilih Kegiatan Lebih Dahulu</option>
                        <?php foreach ($data['output'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Suboutput</label>
                    <select class="form-control select2" name="filter-suboutput">
                        <option value="*">Pilih Output Lebih Dahulu</option>
                        <?php foreach ($data['suboutput'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Pagu Total</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Rp.</span>
                        </div>
                        <input type="text" id="filter-pagutotal-start" class="form-control" name="filter-pagutotal-start" placeholder="Min Pagu Total">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Hingga</span>
                        </div>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Rp.</span>
                        </div>
                        <input type="text" id="filter-pagutotal-end" class="form-control" name="filter-pagutotal-end" placeholder="Max Pagu Total">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="act-filter-data" class="btn btn-primary">Terapkan</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Filter Column -->
<div class="modal fade" id="mapModal" aria-labelledby="mapModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mapModalLabel">Peta Kabupaten</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="map"></div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>

<!-- <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet"> -->
<link href="/plugins/tabulator/dist/css/tabulator_semanticui.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
<!-- <script>
    var tabel = new Tabulator("#example-table", {
        ajaxURL: "http://localhost:8080/bigdata/sda_paket", // Ganti dengan URL ke file PHP
        layout: "fitColumns",
        ajaxResponse: function(url, params, response) {
            // Mengambil definisi kolom dari respons JSON
            var kolomData = response.kolom;
            tabel.setColumns(kolomData);

            // Mengambil data dari respons JSON
            var data = response.data;
            return data;
        }
    }); 
</script>-->
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1MgLuZuyqR_OGY3ob3M52N46TDBRI_9k" ></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPQQfSUVDLy4nPRf6csSip-cuOmpEMpJs&libraries=places" ></script>
<script>
    $(document).on('click','.btn-map',function(){
        lat = $(this).data('lat')
        lng = $(this).data('lng')
        initialize(parseInt(lat),parseInt(lng))
        $("#mapModal").modal("show");
    })
    function initialize(lat,lng) {
        var mapCanvas = document.getElementById('map');
        var myLatLng = {lat: lat, lng: lng};
        var mapOptions = {
            center: new google.maps.LatLng(myLatLng),
            zoom: 11,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions)
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Kabupaten'
        });
    }
</script>

<script>
    function loadColumn() {
        // Mengambil data kolom menggunakan Ajax
        $.getJSON("<?php echo site_url('/bigdata/sda_paket_column?year=') ?>" + $('#tahun').val(), function(data) {
            var table = "monika_data_" + $('#tahun').val();
            var namaKolom = data.kolom;
            var defaultColumn = data.defaultColumn; // Ambil kolom default

            var checkboxContainer = $('.kt-checkbox-list');
            checkboxContainer.empty();

            // Iterasi melalui setiap nama kolom
            $.each(namaKolom, function(index, kolom) {
                // Mengecualikan beberapa kolom yang tidak ingin ditampilkan
                if (kolom.value !== "idpull" && kolom.value !== "no" && kolom.value !== "in_dt" && kolom.value !== "tayang" && kolom.value !== "kdppk") {
                    // Membuat elemen checkbox untuk setiap kolom
                    var isChecked = defaultColumn.includes(kolom.value); // Cek apakah kolom termasuk dalam defaultColumn

                    var checkbox = $('<label class="kt-checkbox kt-checkbox--brand">' +
                        '<input type="checkbox" name="selectedColumns[]" value="' + kolom.value + '"' + (isChecked ? ' checked' : '') + '>' +
                        kolom.label +
                        '<span></span>' +
                        '</label>');

                    // Menambahkan checkbox ke dalam container
                    checkboxContainer.append(checkbox);
                }
            });

            // Menambahkan logika Select2 jika diperlukan
            checkboxContainer.find('input[type="checkbox"]').on('change', function() {
                // Lakukan sesuatu saat checkbox berubah
                var isChecked = $(this).prop('checked');
                var value = $(this).val();
                // Lakukan sesuatu dengan nilai checkbox yang dipilih
            });
        });
    }

    function selectedColumns(year) {

        var selectedColumns = $('input[name="selectedColumns[]"]:checked').map(function() {
            var originalValue = $(this).val();
            switch (true) {
                case originalValue.includes("nmbalai"):
                    return "m_balai.balai as " + originalValue;

                case originalValue.includes("kdprogram"):
                    return "monika_data_" + year + ".kdprogram as " + originalValue;

                case originalValue.includes("kdgiat"):
                    return "monika_data_" + year + ".kdgiat as " + originalValue;

                case originalValue.includes("kdoutput"):
                    return "monika_data_" + year + ".kdoutput as " + originalValue;

                case originalValue.includes("kdsoutput"):
                    return "monika_data_" + year + ".kdsoutput as " + originalValue;

                case originalValue.includes("kdkmpnen"):
                    return "monika_data_" + year + ".kdkmpnen as " + originalValue;

                case originalValue.includes("kdskmpnen"):
                    return "monika_data_" + year + ".kdskmpnen as " + originalValue;

                case originalValue.includes("kdpaket"):
                    return "monika_data_" + year + ".kdpaket as " + originalValue;

                case originalValue.includes("balaiid"):
                    return "m_balai.balaiid as " + originalValue;

                case originalValue.includes("kdsatker"):
                    return "monika_data_" + year + ".kdsatker as " + originalValue;

                case originalValue.includes("nmpaket"):
                    return "monika_data_" + year + ".nmpaket as " + originalValue;

                case originalValue.includes("kdls"):
                    return "monika_data_" + year + ".kdls as " + originalValue;

                case originalValue.includes("ufis"):
                    return "monika_data_" + year + ".ufis as " + originalValue;

                case originalValue.includes("pfis"):
                    return "monika_data_" + year + ".pfis as " + originalValue;


                case originalValue.includes("blokir"):
                    return "monika_data_" + year + ".blokir as " + originalValue;

                case originalValue.includes("jenis_kontrak"):
                    return "CASE WHEN monika_data_" + year + ".nmpaket LIKE '%SYC%' THEN 'SYC' WHEN monika_data_" + year + ".nmpaket LIKE '%MYC%' THEN 'MYC' ELSE NULL END AS jenis_kontrak";

                case originalValue.includes("kdkabkota"):
                    return "monika_data_" + year + ".kdkabkota as " + originalValue;
                case originalValue.includes("kdlokasi"):
                    return "monika_data_" + year + ".kdlokasi as " + originalValue;

                case originalValue === "sat":
                    return "monika_data_" + year + ".sat as " + originalValue;

                case originalValue.includes("nmsatker"):
                    if (year >= 2020) {
                        return "m_satker.satker as " + originalValue;
                    } else {
                        return "nmsatker as " + originalValue;
                    }



                default:
                    return originalValue;
            }
        }).get();

        return selectedColumns


    }
</script>




<script>
    var table;

    function getFilterDataValue() {
        let value = {}

        value.opsiData = $('select[name=filter-opsi-data]').val()
        if ($('select[name=filter-satker]').val() != '*') value.kdsatker = $('select[name=filter-satker]').val()
        if ($('select[name=filter-program]').val() != '*') value.kdprogram = $('select[name=filter-program').val()
        if ($('select[name=filter-kegiatan]').val() != '*') value.kdgiat = $('select[name=filter-kegiatan]').val()
        if ($('select[name=filter-output]').val() != '*') value.kdoutput = $('select[name=filter-output]').val()
        if ($('select[name=filter-suboutput]').val() != '*') value.kdsoutput = $('select[name=filter-suboutput]').val()
        if ($('input[name=filter-pagutotal-start]').val() != '') value.pagutotalStart = filterPagutotalStartMask.unmaskedValue
        if ($('input[name=filter-pagutotal-end]').val() != '') value.pagutotalEnd = filterPagutotalEndMask.unmaskedValue

        return value
    }

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
                "filter": getFilterDataValue(),
                "column": column,
            },
            success: function(response) {
                // Mendapatkan kolom-kolom dari respons pertama
                var firstItem = response.data[0];
                var columns = [];

                // Loop melalui kunci (nama kolom) dalam objek pertama
                for (var key in firstItem) {
                    if (firstItem.hasOwnProperty(key)) {
                        // Tambahkan kolom ke array kolom
                        if(key != 'lat' && key != 'lng'){
                            columns.push({
                                title: key,
                                field: key
                            });
                        }
                    }
                }
                columns.push({
                    title: 'Lokasi',
                    field: 'kdkabkota',
                    formatter : function(cell, formatterParams, onRendered){
                        let lat_data = cell._cell.row.data.lat
                        let lng_data = cell._cell.row.data.lng
                        return `<button type='button' data-lat='${lat_data}' data-lng='${lng_data}' class='btn btn-info btn-map'>Map</button>`
                    }

                });

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
                        "column": column,
                        "filter": getFilterDataValue(),

                    },
                    columns: columns,
                    ajaxResponse: function(url, params, response) {
                        // Mendapatkan jumlah baris yang telah dimuat
                        var rowCount = response.data.length;

                        // Mendapatkan jumlah total baris dari respons (contoh: jika respons memiliki properti 'total')
                        var totalRowCount = response.totalData || 0;

                        // Menambahkan elemen tambahan
                        var footerContents = document.querySelector('.tabulator-footer-contents');

                        // Menghapus elemen sebelumnya jika ada
                        var previousElement = document.querySelector('.custom-info');
                        if (previousElement) {
                            footerContents.removeChild(previousElement);
                        }
                        var additionalElement = document.createElement('span');
                        additionalElement.className = 'custom-info';
                        additionalElement.innerText = "Showing " + params.page + " to " + rowCount + " of " + totalRowCount + " entries ";
                        footerContents.insertBefore(additionalElement, document.querySelector('.tabulator-paginator'));

                        return response; // Perlu mengembalikan respons untuk membiarkan Tabulator melanjutkan pemrosesan
                    }

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

    $('button[name=prepare-download-bigdata]').click(function() {
        var year = $('#tahun').val();

        var columns = selectedColumns(year);
        var filterData = getFilterDataValue();

        $('#year').val(year);
        $('#filter').val(JSON.stringify(filterData));
        $('#columns').val(columns);

        // Submit form tersembunyi
        $('#downloadData').submit();
    })




    // document.getElementById("download-xlsx").addEventListener("click", function() {
    //     // Mendapatkan semua baris dari tabel
    //     table.getRows(true, 'data', function(rows) {
    //         // Membuat array dari data baris
    //         var data = rows.map(function(row) {
    //             return row.getData();
    //         });

    //         // Membuat objek workbook menggunakan SheetJS
    //         var wb = XLSX.utils.book_new();
    //         var ws = XLSX.utils.json_to_sheet(data);

    //         // Menyematkan sheet ke workbook
    //         XLSX.utils.book_append_sheet(wb, ws, "Data Tabel");

    //         // Mengunduh file
    //         XLSX.writeFile(wb, "data_tabel.xlsx");
    //     });
    // });

    // Panggil fungsi getData saat halaman pertama kali dimuat
    // getData(1, 10);
</script>

<script>
    // Memanggil loadColumn() dan mengeksekusi perintah klik saat halaman dimuat
    $(document).ready(function() {


        $(".select2").select2();


        $(".tampilkan").on("click", function() {
            var year = $('#tahun').val();

            var columns = selectedColumns(year);
            if (columns.length > 0) {
                columns.push('lat')
                columns.push('lng')
            }
            getData(1, 10, columns, year);

            $("#ModalFKolom").modal("hide")

        })





        $("#tahun").on("change", function() {

            loadColumn();

            $(".tampilkan").trigger("click")
        });


        $("#tahun").trigger("change");



        $(document).on('click', 'button[name=act-filter-data]', function() {
            var year = $('#tahun').val();

            var columns = selectedColumns(year);
            getData(1, 10, columns, year);
            $("#modalFilterData").modal("hide")

        })
    })
</script>
<?= $this->endSection() ?>