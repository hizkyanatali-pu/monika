<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<style>

</style>
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Add
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon kt-hidden">
                    <i class="la la-gear"></i>
                </span>
                <a href="javascript:history.back()" class="btn btn-info"> <i class="fas fa-arrow-left"> </i> </a>

                <h3 class="kt-portlet__head-title">
                    &nbsp;Form Tematik
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form">
            <div class="kt-portlet__body">
                <div class="kt-form__section kt-form__section--first">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Tematik</label>
                        <div class="col-lg-4">
                            <select name="tematik" id="tematikList" class="form-control tematikList">
                                <option value="">Pilih Tematik</option>

                            </select>
                        </div>
                        <div class="col-lg-4"> <button class="btn btn-bold btn-sm btn-label-brand addTematik" type="button">
                                <i class="la la-plus"></i> Add
                            </button></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Sub Tematik</label>
                        <div class="col-lg-4">
                            <select name="subTematik" id="subTematikList" class="form-control subTematikList">
                                <option value="">Pilih SubTematik</option>
                            </select>
                        </div>
                        <div class="col-lg-4"> <button class="btn btn-bold btn-sm btn-label-brand addSubTematik" type="button">
                                <i class="la la-plus"></i> Add
                            </button></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Tahun</label>
                        <div class="col-lg-4">
                            <select name="tahun" id="tahun" class="form-control">
                                <?php
                                for ($i = 2013; $i <= date('Y') + 1; $i++) { ?>

                                    <option value="<?= $i ?>" <?= ($i == date('Y') ? "selected" : "") ?>><?= $i ?></option>


                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Paket</label>
                        <div class="col-lg-2">
                            <button class="btn btn-bold btn-sm btn-label-brand addPaket" type="button">
                                <i class="la la-plus"></i> Add
                            </button> <span class="kt-widget12__value"> Jumlah Paket Terpilih : <b><span class="count"></span></b></span>
                        </div>
                    </div>



                    <!-- <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div> -->


                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-success simpan">Simpan</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
</div>

<!-- Modal add Tematik -->
<div class="modal fade modalAdd" id="modalAddTematik" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content" style="height: 100% !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormTitlePaket">
                    Add Tematik
                </h5>
                <div>
                    <div class="d-flex">
                        <button type="button" class="btn btn-modal-full ml-auto"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close  ml-0 text-right pl-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

            </div>
            <div class="modal-body">
                <form class="kt-form kt-form--label-right" id="formAddTematik">
                    <div class="kt-portlet__body">
                        <div class="form-group form-group-last">
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                <div class="alert-text">
                                    Form ini digunakan untuk menambahkan data <code>Tematik</code>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-2 col-form-label">ID Tematik</label>
                            <div class="col-4">
                                <input class="form-control" name="idTematik" type="text" placeholder="Masukkan ID Tematik">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-2 col-form-label">Nama Tematik</label>
                            <div class="col-4">
                                <input class="form-control" name="namaTematik" type="text" placeholder="Masukkan Nama Tematik">
                            </div>
                        </div>

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-2">
                                </div>
                                <div class="col-10">
                                    <button type="button" class="btn btn-success saveTematik">Simpan</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Footer Modal Pertama -->
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close"> Batal</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal add Tematik -->


<!-- Modal add SubTematik -->
<div class="modal fade modalAdd" id="modalAddSubTematik" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content" style="height: 100% !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormTitlePaket">
                    Add SubTematik
                </h5>
                <div>
                    <div class="d-flex">
                        <button type="button" class="btn btn-modal-full ml-auto"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close  ml-0 text-right pl-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

            </div>
            <div class="modal-body">
                <form class="kt-form kt-form--label-right" id="formAddSubTematik">
                    <div class="kt-portlet__body">
                        <div class="form-group form-group-last">
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                <div class="alert-text">
                                    Form ini digunakan untuk menambahkan data <code>SubTematik</code>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-2 col-form-label">Tematik</label>
                            <div class="col-4">
                                <input type="text" name="namaTematik2" class="form-control" readonly>
                                <input type="hidden" name="idTematik2" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-2 col-form-label">ID SubTematik</label>
                            <div class="col-4">
                                <input class="form-control" name="idSubTematik" type="text" placeholder="Masukkan ID Tematik">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-2 col-form-label">Nama Sub Tematik</label>
                            <div class="col-4">
                                <input class="form-control" name="namaSubTematik" type="text" placeholder="Masukkan Nama Tematik">
                            </div>
                        </div>

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-2">
                                </div>
                                <div class="col-10">
                                    <button type="button" class="btn btn-success saveSubTematik">Simpan</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Footer Modal Pertama -->
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close"> Batal</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal add SubTematik -->


<!-- Modal Pilih Paket -->
<div class="modal fade" id="modalPilihPaket" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content" style="height: 100% !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormTitlePaket">
                    Pilih Paket
                </h5>
                <div>
                    <div class="d-flex">
                        <button type="button" class="btn btn-modal-full ml-auto"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close  ml-0 text-right pl-0 save-btn-paket" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <small><b>* Terakhir Update Data Emon : </b></small> <small class="text-danger"><i> <?= (getLastUpdateData() ? getLastUpdateData() . " WIB" : 0);  ?></i></small>
                </div>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <select id="filter-field" class="form-control">
                            <option value="">Pilih Satker</option>
                            <?php
                            foreach ($unit_kerja as $key => $value) { ?>
                                <option value="<?= $value['satkerid'] ?>"><?= $value['satker'] ?> </option>
                            <?php } ?>
                        </select>

                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="search" name="search">
                    </div>
                </div>
                <div id="table-paket"></div>


            </div>
            <div class="modal-footer">
                <!-- Footer Modal Pertama -->
                <button class="btn btn-success save-btn-paket"> Simpan Paket</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Pilih Paket -->

<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<link href="/plugins/tabulator/dist/css/tabulator_semanticui.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $(".tematikList").change(function() {

            refreshSelect2SubTematik($(this).val())


        });
        // Tematik
        function refreshSelect2Tematik() {
            $('.tematikList').empty(); // Mengosongkan opsi

            // Panggil API untuk mendapatkan data baru
            $.ajax({
                url: '/api/m-tematik-list', // Ganti dengan URL API yang sesuai
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.unshift({
                        id: "",
                        text: "Pilih Tematik"
                    });
                    // Isi opsi dengan data dari API
                    $('.tematikList').select2({
                        data: data,
                        placeholder: "Pilih Tematik",
                        allowClear: true

                    });

                    // Trigger event 'change' untuk memperbarui tampilan Select2
                    $('.tematikList').trigger('change');
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        function refreshSelect2SubTematik(tematik) {
            $('.subTematikList').empty(); // Mengosongkan opsi

            // Panggil API untuk mendapatkan data baru
            $.ajax({
                url: '/api/m-subtematik-list', // Ganti dengan URL API yang sesuai
                method: 'GET',
                dataType: 'json',
                data: {
                    "tematik": tematik
                },
                success: function(response) {

                    $('.subTematikList').select2({
                        data: response,
                        placeholder: "Pilih SubTematik",
                    });

                    // Trigger event 'change' untuk memperbarui tampilan Select2
                    $('.subTematikList').trigger('change');
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        refreshSelect2Tematik();

        $(".addTematik").click(function() {
            $("#modalAddTematik").modal("show");
        });

        $(".saveTematik").click(function() {

            var url = '/api/m-tematik-insert';
            var formData = $('#formAddTematik').serialize();

            AjaxCall(url, formData);

        });
        // end Tematik


        // SubTematik
        $(".addSubTematik").click(function() {
            $("#modalAddSubTematik").modal("show");
            $("input[name=namaTematik2]").val($("select[name=tematik]").find(":selected").text());
            $("input[name=idTematik2]").val($("select[name=tematik]").val());

        });

        $(".saveSubTematik").click(function() {

            var url = '/api/m-subtematik-insert';
            var formData = $('#formAddSubTematik').serialize();

            AjaxCall(url, formData);

        });



        // EndSubTematik



        function AjaxCall(url, formData) {

            $.ajax({
                type: 'POST',
                url: url, // Sesuaikan dengan URL yang sesuai
                data: formData,
                success: function(response) {

                    if (response.status == 'error') {
                        swal.fire("Gagal !", JSON.stringify(response.message), "error");
                        return false;
                    }
                    swal.fire("Berhasil Menambah Data !", "Data Berhasil Ditambahkan", "success");
                    $(".modalAdd").modal("hide");
                    $("form").trigger("reset");
                    refreshSelect2Tematik();


                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });

        }




        $("#filter-field").select2();
        var selectedRows = [];

        var table;
        // Temukan tombol "Add" berdasarkan kelas atau atribut lain yang sesuai
        $(".addPaket").click(function() {
            // Panggil modal dengan ID "modalPilihPaket"
            $("#modalPilihPaket").modal("show");

        });

        tableParams = "monika_data_" + $('#tahun').val();

        // Mendapatkan data respons JSON dari server
        function initializeTable(page, size, year, filter = []) {

            table = new Tabulator("#table-paket", {
                pagination: true,
                paginationMode: "remote",
                ajaxURL: "<?php echo site_url('api/get-paket-tematik') ?>",
                ajaxParams: {
                    "page": page,
                    "size": size,
                    "year": year,
                    // "column": column,
                    "filter": filter,

                },

                layout: "fitColumns",
                resizableColumnFit: true,
                selectable: true,
                columns: [{
                        formatter: "rowSelection",
                        titleFormatter: "rowSelection",
                        hozAlign: "center",
                        headerSort: false,
                        cellClick: function(e, cell) {
                            cell.getRow().toggleSelect();
                        }
                    },
                    {
                        title: "Unit Kerja",
                        field: "satker",
                    },
                    {
                        title: "kode Paket",
                        field: "id",
                    },
                    {
                        title: "Paket",
                        field: "label",
                    },
                    {
                        title: "vol",
                        field: "vol",
                        hozAlign: "center",
                        width: 80
                    },
                    {
                        title: "Satuan",
                        field: "sat"
                    },
                    {
                        title: "pagu",
                        field: "pagu_total",
                        hozAlign: "right",
                        sorter: "number",
                        formatter: function(cell, formatterParams, onRendered) {

                            if (cell.getValue()) {

                                return formatRupiah(cell.getValue())
                            }
                            return "";
                        }
                    },
                    {
                        title: "realisasi",
                        field: "real_total",
                        hozAlign: "right",
                        sorter: "number",
                        formatter: function(cell, formatterParams, onRendered) {

                            if (cell.getValue()) {

                                return formatRupiah(cell.getValue())
                            }
                            return "";
                        }
                    },
                    {
                        title: "progres keuangan",
                        field: "progres_keuangan",
                        hozAlign: "right",
                        sorter: "number",
                        formatter: function(cell, formatterParams, onRendered) {

                            if (cell.getValue()) {

                                return cell.getValue().replace(/\./g, ',');
                            }
                            return "";
                        }
                    },
                    {
                        title: "progres fisik",
                        field: "progres_fisik",
                        hozAlign: "right",
                        sorter: "number",
                        formatter: function(cell, formatterParams, onRendered) {

                            if (cell.getValue()) {

                                return cell.getValue().replace(/\./g, ',');
                            }
                            return "";
                        }
                    },
                ],

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

            table.on("renderComplete", function() {
                // Proses pencarian dan pemilihan kembali baris di sini
                selectedRows.forEach(function(selectedRow) {
                    // console.log("Mencoba memilih kembali baris dengan ID:", selectedRow.id);

                    // Pastikan ada nilai ID yang valid
                    if (selectedRow.id) {
                        var row = table.getRow(selectedRow.id);
                        // Periksa apakah baris ditemukan
                        if (row) {
                            row.select();
                            // console.log("Berhasil memilih kembali baris dengan ID:", selectedRow.id);
                        } else {
                            // console.warn("Baris dengan ID " + selectedRow.id + " tidak ditemukan.");
                        }
                    } else {
                        // console.warn("ID baris tidak valid.");
                    }
                });
            });




            // Event listener saat baris dipilih
            table.on("rowSelected", function(row) {
                if (row && row.getData) {
                    var rowData = row.getData();

                    selectedRows.push(rowData);
                }

            });

            // Event listener saat baris tidak dipilih
            table.on("rowDeselected", function(row) {

                if (row && row.getData) {
                    var rowData = row.getData();
                    // Menghapus baris yang tidak dipilih dari array
                    selectedRows = selectedRows.filter(function(selectedRow) {
                        return selectedRow.id !== rowData.id;
                    });
                }
            });

        }

        initializeTable(1, 10, $('#tahun').val());

        $('#tahun').change(function() {

            var selectedYear = $(this).val();
            initializeTable(1, 10, selectedYear);
        });


        // Menangani perubahan pada dropdown menggunakan jQuery
        $('#filter-field').change(function() {

            var selectedSatker = $(this).val();
            initializeTable(1, 10, $('#tahun').val(), {
                "satker": selectedSatker
            });
        });

        $('#search').keyup(function() {

            var search = $(this).val();
            initializeTable(1, 10, $('#tahun').val(), {
                "search": search
            });
        });


        $(".save-btn-paket").click(function() {
            $(".count").html(selectedRows.length);
            $(".modal").modal("hide");

        });
        $(".simpan").click(function() {
            var data = {
                tematikID: $("#tematikList").val(),
                subTematikID: $("#subTematikList").val(),
                tahun: $("#tahun").val(),
                paket: selectedRows
            };

            $.ajax({
                type: "POST",
                url: "/api/data-tematik-insert",
                data: data,
                success: function(response) {

                    if (response.status == "error") {
                        swal.fire("Gagal !", JSON.stringify(response.message), "error");
                        return false;

                    }


                    swal.fire({
                        title: 'Data Berhasil Disimpan !',
                        text: response.message,
                        type: 'success',
                        // showCancelButton: true,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                    }).then(function(result) {
                        if (result.value) {
                            window.location.reload();

                        }
                    });

                },
                error: function(error) {

                }
            });



        });




    });
</script>

<?= $this->endSection() ?>