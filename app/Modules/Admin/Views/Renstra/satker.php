<?php
$session = session();
$isAdmin = strpos($session->get('userData')['uid'], 'admin') !== false
?>

<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .paginate_button.page-item {
        padding: 0px !important;
    }

    .paginate_button.page-item:hover {
        background-color: #FFF !important;
    }

    ._remove-row-item {
        width: 20px;
        height: 20px;
        position: absolute;
        right: -12.9px;
        top: 18px;
        padding: 0px 0px 19px 6px !important
    }

    ._table-form ._remove-row-item {
        padding: 0px 0px 19px 5px !important
    }

    ._remove-row-item .fas {
        font-size: 10px
    }

    .select2-dropdown {
        z-index: 1061;
    }

    .btn-table-opsi {
        position: absolute;
        top: 235px;
        right: 220px;
        z-index: 9;
    }

    .m-demo__preview {
        background: white;
        border: 4px solid #f7f7fa;
        padding: 30px
    }
</style>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex justify-content-start">
                    <h5 class="kt-subheader__title">
                        <?php echo $pageTitle ?? 'Dokumen PK' ?>
                    </h5>
                    <?= csrf_field() ?>
                </div>
                <?php if ($isAdmin) { ?>
                    <div>
                        <?php if ($dokumenType == 'eselon1') { ?>
                            <a href="<?php echo site_url('dokumenpk/eselon1/export-rekap-excel'); ?>" target="_blank" class="btn btn-success mr-4">
                                <i class="fas fa-file"></i> Rekap
                            </a>
                        <?php } ?>

                        <button class="btn btn-primary __admin-create-dokumen-opsi-users">
                            <i class="fas fa-plus"></i> Buat Dokumen
                        </button>
                        <button class="btn btn-primary __opsi-template d-none">
                            <i class="fas fa-plus"></i> Buat Dokumen
                        </button>
                    </div>
                <?php } ?>
            </div>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <ul class="nav nav-pills mb-3 flex-column flex-sm-row" id="pills-tab" role="tablist">
        <?php if ($isAdmin) { ?>
            <?php if (isset($dataBelumInput)) { ?>
                <li class="flex-sm-fill text-sm-center nav-item">
                    <a class="nav-link" id="belum-input-tab" data-toggle="pill" href="#belum-input" role="tab" aria-controls="belum-input" aria-selected="true">Belum Input</a>
                </li>
            <?php } ?>
        <?php } ?>

        <li class="flex-sm-fill text-sm-center nav-item">
            <a class="nav-link active" id="pills-one-tab" data-toggle="pill" href="#pills-one" role="tab" aria-controls="pills-one" aria-selected="true">Menunggu Persetujuan</a>
        </li>
        <li class="flex-sm-fill text-sm-center nav-item">
            <a class="nav-link" id="pills-two-tab" data-toggle="pill" href="#pills-two" role="tab" aria-controls="pills-two" aria-selected="false">Di Setujui</a>
        </li>
        <li class="flex-sm-fill text-sm-center nav-item">
            <a class="nav-link" id="pills-three-tab" data-toggle="pill" href="#pills-three" role="tab" aria-controls="pills-three" aria-selected="false">Di Tolak</a>
        </li>
    </ul>

    <div class="kt-portlet" style="margin-top: -5px">
        <div class="kt-portlet__body tab-content" id="pills-tabContent">
            <!-- <button class="btn btn-icon btn-warning">
           <i class="fas fa-sync-alt"></i>
                                </button> -->

            <?php if ($isAdmin) { ?>
                <?php if (isset($dataBelumInput)) { ?>
                    <div class="tab-pane fade show" id="belum-input" role="tabpanel" aria-labelledby="belum-input-tab">
                        <button class="btn btn-sm btn-primary btn-table-opsi __refresh-data-table" data-status="belum-input" data-dokumen-type="<?php echo $dokumenType ?>">
                            <i class="fas fa-sync"></i> Refresh Data
                        </button>

                        <table class="table table-bordered" id="table-belum-input">
                            <thead>
                                <tr class="text-center">
                                    <th width="25px">No</th>
                                    <th><?php echo ucfirst($dokumenType) ?></th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 12px">
                                <?php foreach ($dataBelumInput as $key_belumInput => $value_belumInput) { ?>
                                    <tr>
                                        <td><?php echo $key_belumInput + 1 ?></td>
                                        <td><?php echo $value_belumInput['nama'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            <?php } ?>




            <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab">

                <div class="col-12">
                    <div class="m-demo__preview">
                        <!--begin::Form-->
                        <div class="m-form__group form-group row">
                            <label class="col-3 col-form-label">Instansi</label>
                            <div class="col-6">
                                <select class="form-control F_instansi">

                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary filter-instansi" data-status="hold">Cari</button>
                        <button type="button" class="btn btn-danger __refresh-data-table" data-status="hold">Reset</button>
                        <!--end::Form-->
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button class="btn btn-danger mb-4 __deletePermanenMultiple" data-target="hold">
                        <i class="fas fa-trash"></i> Arsipkan Data Terpilih
                    </button>

                    <button class="btn btn-primary mb-4 __refresh-data-table" data-status="hold">
                        <i class="fas fa-sync"></i> Refresh Data
                    </button>
                </div>

                <table class="table table-bordered" id="table-hold">
                    <thead>
                        <tr class="text-center">
                            <th width="15px">
                                <input type="checkbox" name="checkall" data-status="hold" />
                            </th>
                            <th width="25px">No</th>
                            <th>Dokumen</th>
                            <th width="120px">Tanggal Kirim</th>
                            <th width="<?php echo $isAdmin ? '280px' : '50px' ?>">Aksi</th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                    </tbody>
                </table>
            </div>



            <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab">
                <div class="col-12">
                    <div class="m-demo__preview">
                        <!--begin::Form-->
                        <div class="m-form__group form-group row">
                            <label class="col-3 col-form-label">Instansi</label>
                            <div class="col-6">
                                <select class="form-control F_instansi">

                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary filter-instansi" data-status="setuju">Cari</button>
                        <button type="button" class="btn btn-danger __refresh-data-table" data-status="setuju">Reset</button>
                        <!--end::Form-->
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button class="btn btn-danger mb-4 __deletePermanenMultiple" data-target="setuju">
                        <i class="fas fa-trash"></i> Arsipkan Data Terpilih
                    </button>

                    <button class="btn btn-primary mb-4 __refresh-data-table" data-status="setuju">
                        <i class="fas fa-sync"></i> Refresh Data
                    </button>
                </div>


                <table class="table table-bordered" id="table-setuju">
                    <thead>
                        <tr class="text-center">
                            <th width="15px">
                                <input type="checkbox" name="checkall" data-status="setuju" />
                            </th>
                            <th width="30px">No</th>
                            <th>Dokumen</th>
                            <th width="120px">Tanggal Kirim</th>
                            <th width="120px">Tanggal disetujui</th>
                            <th width="<?php echo $isAdmin ? '280px' : '50px' ?>">Aksi</th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                    </tbody>
                </table>
            </div>



            <div class="tab-pane fade" id="pills-three" role="tabpanel" aria-labelledby="pills-three-tab">
                <div class="col-12">
                    <div class="m-demo__preview">
                        <!--begin::Form-->
                        <div class="m-form__group form-group row">
                            <label class="col-3 col-form-label">Instansi</label>
                            <div class="col-6">
                                <select class="form-control F_instansi">

                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary filter-instansi" data-status="tolak">Cari</button>
                        <button type="button" class="btn btn-danger __refresh-data-table" data-status="tolak">Reset</button>
                        <!--end::Form-->
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button class="btn btn-danger mb-4 __deletePermanenMultiple" data-target="tolak">
                        <i class="fas fa-trash"></i> Arsipkan Data Terpilih
                    </button>

                    <button class="btn btn-primary mb-4 __refresh-data-table" data-status="tolak">
                        <i class="fas fa-sync"></i> Refresh Data
                    </button>
                </div>

                <table class="table table-bordered" id="table-tolak">
                    <thead>
                        <tr class="text-center">
                            <th width="15px">
                                <input type="checkbox" name="checkall" data-status="tolak" />
                            </th>
                            <th width="30px">No</th>
                            <th>Dokumen</th>
                            <th width="120px">Tanggal Kirim</th>
                            <th width="120px">Tanggal Ditolak</th>
                            <th width="<?php echo $isAdmin ? '280px' : '50px' ?>">Aksi</th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<table class="d-none" id="_exported_table"></table>
<!-- end:: Content -->







<!-- Modal Preview Cetak Dokumen -->
<div class="modal fade" id="modal-preview-cetak" tabindex="-1" role="dialog" aria-labelledby="modal-preview-cetakTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="clearfix w-100">
                    <div class="float-left">
                        <div class="d-flex">
                            <button type="button" class="btn btn-default pr-2 d-none __back-pilih-dokumen">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h5 class="modal-title" id="exampleModalLongTitle">Preview Dokumen</h5>
                            <!-- <h5 class="modal-title pt-2 pl-2">Pilih Dokumen</h5> -->
                        </div>
                    </div>
                    <div class="float-right">
                        <button type="button" class="btn btn-modal-full text-right" style="margin: -10px;"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="container-revision-alert-cetak"></div>
                <iframe width="100%" style="height: 80vh" frameborder="0"></iframe>
            </div>
            <div class="modal-footer p-0">
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Preview Cetak Dokumen -->




<!-- Modal Form Detail -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="clearfix w-100">
                    <div class="float-left">
                        <div class="d-flex">
                            <button type="button" class="btn btn-default pr-2 d-none __back-pilih-dokumen">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h5 class="modal-title pt-2 pl-2">Pilih Dokumen</h5>
                        </div>
                    </div>
                    <div class="float-right">
                        <button type="button" class="btn btn-modal-full text-right" style="margin: -10px;"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="list-group" id="choose-template">
                    <?php /* foreach ($templateDokumen as $keyTemplate => $dataTemplate) : ?>
                        <a class="list-group-item list-group-item-action __buat-dokumen-pilih-template" href="javascript:void(0)" data-id="<?php echo $dataTemplate->id ?>">
                            <?php echo $dataTemplate->title ?>
                        </a>
                    <?php endforeach */ ?>
                </div>
                <div class="p-4 d-none" id="make-dokumen">
                </div>
            </div>

            <div class="modal-footer d-none">
                <button type="button" class="btn btn-primary __save-dokumen">Simpan Dokumen</button>
                <button type="button" class="btn btn-success __save-update-dokumen d-none">Simpan Dokumen</button>
                <div id="parentModalFooter"></div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Form Detail -->


<!-- Modal Pilih Paket -->
<div class="modal fade" id="modalPilihPaket" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormTitlePaket">
                    Pilih Paket
                </h5>
                <div class="">
                    <div class="d-flex">
                        <button type="button" class="btn btn-modal-full ml-auto"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close ml-0 text-right pl-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <small><b>* Terakhir Update Data Emon : </b></small> <small class="text-danger"><i> <?= (getLastUpdateData() ? getLastUpdateData() . " WIB" : 0);  ?></i></small>
                </div>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead class="table-primary">
                        <tr class="text-center theader sticky-header-1" style="background-color: #a8b0ed;">
                            <th></th>
                            <th class="tdKode">Kode</th>
                            <th class="tdLabel">Paket</th>
                            <th class="tdvol">Vol</th>
                            <th class="tdSatuan">Satuan</th>
                            <th class="tdNilai">Pagu Dipa</th>
                            <th class="tdNilai">Realisasi</th>
                            <th class="tdPersen">%keu</th>
                            <th class="tdPersen">%fisik</th>
                            <th class="tdPersen">Output</th>
                            <th class="tdPersen">Outcome</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <!-- Footer Modal Pertama -->
                <button class="btn btn-success save-btn-paket"> Simpan Paket</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Pilih Paket -->



<!-- Modal Cetak Dokumen Terevisi -->
<div class="modal fade" id="modal-cetak-dokumen-revisioned" role="dialog" aria-labelledby="modal-cetak-dokumen-revisionedTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Pilih Dokumen :</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Cetak Dokumen Terevisi -->


<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php echo $this->include('jspages/renstra_js') ?>

<script>
    var isAdmin = '<?php echo $isAdmin ?>'
    element_tableHold = '',
        element_tableSetuju = '',
        element_tableTolak = '',
        element_tableBelumInput = '',
        element_modalPreviewCetakDokumen = $('#modal-preview-cetak'),
        element_modalListRevision = $('#modal-cetak-dokumen-revisioned'),
        element_formTable = $('._table-form').find('tbody'),
        element_tableInformasi = $('._table-informasi').find('tbody')


    $(document).ready(function() {
        setTimeout(() => {
            element_tableHold = $('#table-hold').DataTable({
                scrollX: true
            })
            getData('hold');
        }, 300)


        $('.F_instansi').select2({
            placeholder: 'Pilih Instansi',
            ajax: {
                url: '<?php echo site_url('instansi-list/satker') ?>', // Ganti '/path/to/instansiList/satker' dengan URL sesuai dengan endpoint Anda
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        });


    })



    $('#belum-input-tab').on('click', () => {
        setTimeout(() => {
            if (element_tableBelumInput == '') {
                element_tableBelumInput = $('#table-belum-input').DataTable({
                    scrollX: true
                });
            }
        }, 300)
    })



    $('#pills-two-tab').on('click', () => {
        setTimeout(() => {
            if (element_tableSetuju == '') {
                element_tableSetuju = $('#table-setuju').DataTable({
                    scrollX: true
                })
            }
            getData('setuju');
        }, 300)
    })

    $('#pills-three-tab').on('click', () => {
        setTimeout(() => {
            if (element_tableTolak == '') {
                element_tableTolak = $('#table-tolak').DataTable({
                    scrollX: true
                })
            }
            getData('tolak');
        }, 300)
    })



    $(document).on('click', '.__refresh-data-table', function() {
        let status = $(this).data('status')
        $('.F_instansi').val(null).trigger('change');
        console.log("ok");
        switch (status) {
            case 'belum-input':
                element_tableBelumInput.clear().draw()
                getDataBelumInput($(this).data('dokumen-type'))
                break;

            case 'hold':
                element_tableHold.clear().draw()
                getData(status)
                break;

            case 'setuju':
                element_tableSetuju.clear().draw()
                getData(status)
                break;

            case 'tolak':
                element_tableTolak.clear().draw()
                getData(status)
                break;

        }

        // if (status != 'belum-input') {
        //     getData(status)
        // }
    })



    $(document).on('click', '.__admin-create-dokumen-opsi-users', function() {
        let optionData = <?php echo $createDokumen_userOption ?>,
            selectOpntionList = ''

        optionData.forEach((data, index) => {
            selectOpntionList += `
                <option value="${data.id}">${data.title}</option>
            `
        });

        let html = `
            <select class="select2 list-satker" name="states[]"> 
                ${selectOpntionList}
            </select>
        `

        Swal.fire({
            title: 'Pilih Satker Untuk Membuat Dokumen',
            html: html,
            showCancelButton: true,
            confirmButtonText: 'Pilih',
            showLoaderOnConfirm: true,
            onOpen: function() {
                $('.list-satker').select2({
                    width: '100%',
                    // placeholder: "Seleziona",
                });
            },
            preConfirm: () => {
                return $('.list-satker').val()
            }
        }).then((result) => {
            if (result.value != undefined) {
                let userType = "<?php echo $dokumenType ?>" == "balai" ? "balai" : "satker"

                $.ajax({
                    url: "<?php echo site_url('dokumenpk/get-list-template-buat-dokumen') ?>" + "/" + userType + "/" + result.value,
                    type: 'GET',
                    success: (res) => {
                        let renderListTemplate = ''

                        res.templateDokumen.forEach((data, index) => {
                            renderListTemplate += `
                                <a 
                                    class="list-group-item list-group-item-action __buat-dokumen-pilih-template" 
                                    href="javascript:void(0)" 
                                    data-id="${data.id}"
                                >
                                    ${data.title}
                                </a>
                            `
                        });
                        $('#choose-template').html(renderListTemplate)

                        $('.__opsi-template').attr('data-available', res.templateAvailable)
                        $('.__opsi-template').trigger('click')
                    }
                })

            }
        })
    })



    $(document).on('click', '.__preview-dokumen', function() {

        cetakDokumen(
            $(this).data('id'),
            $(this).data('to-confirm'),
            $(this).data('createdat')
        )
    })



    $(document).on('click', '.__arsipkan-dokumen', function() {
        Swal.fire({
            title: 'Arsipkan Dokumen Perjanjian Kinerja',
            text: "Apakah anda yakin mengarsipkan dokumen ini ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#000',
            confirmButtonText: 'Ya, Arsipkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/arsip/arsipkan') ?>",
                    type: 'POST',
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        id: $(this).data('id')
                    },
                    success: (res) => {
                        Swal.fire(
                            'Berhasil',
                            'Dokumen telah di arsipkan',
                            'success'
                        )

                        setTimeout(() => {
                            location.reload()
                        }, 1500)
                    }
                })
            }
        })
    })



    $(document).on('click', '.__open-list-revisioned', function() {
        let dokumenMasterID = $(this).data('dokumen-master-id')

        $.ajax({
            url: "<?php echo site_url('dokumenpk/satker/get-list-revisioned/') ?>" + dokumenMasterID,
            type: 'GET',
            success: (res) => {
                let list = ''
                res.dokumenList.forEach((data, key) => {
                    let listTitle = 'Dokumen Awal',
                        activeClass = '',
                        activeSubTitle = '',
                        buttonData_toConfirm = false

                    if (data.revision_master_number) listTitle = 'Koreksi #' + data.revision_number
                    if (data.status == 'setuju') {
                        activeClass = 'active bg-success border-success'
                        activeSubTitle = '<div><small>Telah di setujui</small></div>'
                    }
                    if (data.is_revision_same_year == '1') {
                        listTitle = 'Revisi'
                        activeClass = 'active bg-danger border-danger'
                    }
                    if (key == 0 && data.status == 'hold') buttonData_toConfirm = true

                    list += `
                        <button 
                            class="list-group-item list-group-item-action ${activeClass} __preview-dokumen"
                            data-id="${data.id}" data-createdat = "${data.created_at}"
                            data-to-confirm="${buttonData_toConfirm}"
                        >
                            ${listTitle}
                            ${activeSubTitle}
                        </button>
                    `
                })

                element_modalListRevision.find('.list-group').html(list)
            }
        })
        element_modalListRevision.modal('show')
    })



    $(document).on('click', '.__tolak-dokumen', function() {
        let dataID = $(this).data('id')

        Swal.fire({
            title: "Kenapa dokumen ini di tolak?",
            html: `<textarea class="form-control" name="pesan-tolak-dokumen" rows="10" placeholder="Tulis pesan untuk pembuat dokumen"></textarea>`,
            confirmButtonText: "Kirim, dan Tolak Dokumen",
            cancelButtonText: "Batal",
            showLoaderOnConfirm: true,
            showCancelButton: true,
            preConfirm: () => {
                if ($('textarea[name=pesan-tolak-dokumen]').val() == '') {

                    return Swal.showValidationMessage('Alasan Penolakan Harus Diisi')

                }
                $.ajax({
                    url: "<?php echo site_url('renstra/change-status') ?>",
                    type: "POST",
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        dokumenType: 'satker',
                        dataID: dataID,
                        message: $('textarea[name=pesan-tolak-dokumen]').val(),
                        newStatus: 'tolak'
                    },
                    success: (res) => {
                        return res
                    }
                })
            }
        }).then((res) => {
            if (res.value) {
                element_modalPreviewCetakDokumen.modal('hide')

                element_tableHold
                    .row($('#_dokumen-row-' + dataID))
                    .remove()
                    .draw();
            }
        })
    })



    $(document).on('click', '.__setujui-dokumen', function() {
        let dataID = $(this).data('id')
        return
        $.ajax({
            url: "<?php echo site_url('renstra/change-status') ?>",
            type: "POST",
            data: {
                csrf_test_name: $('input[name=csrf_test_name]').val(),
                dokumenType: 'satker',
                dataID: dataID,
                newStatus: 'setuju'
            },
            success: (res) => {
                element_modalPreviewCetakDokumen.modal('hide')

                element_tableHold
                    .row($('#_dokumen-row-' + dataID))
                    .remove()
                    .draw();
            }
        })
    })






    $(document).on('click', '.__deletePermanenMultiple', function() {
        let tempCheck = [],
            checklist = $('input[type=checkbox][name=checklist-multiple-delete][data-status=' + $(this).data('target') + ']:checked')

        checklist.each((index, element) => {
            tempCheck.push($(element).val())
        })

        if (tempCheck.length > 0) {
            Swal.fire({
                title: 'Hapus Permanen',
                text: "Apakah anda yakin mengarsipkan semua data yang telah di pilih",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#000',
                confirmButtonText: 'Ya, Arsipkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "<?php echo site_url('dokumenpk/arsip/arsipkan-multiple') ?>",
                        type: 'POST',
                        data: {
                            csrf_test_name: $('input[name=csrf_test_name]').val(),
                            id: tempCheck
                        },
                        success: (res) => {
                            Swal.fire(
                                'Berhasil',
                                'Dokumen telah di hapus secara permanent',
                                'success'
                            )

                            setTimeout(() => {
                                location.reload()
                            }, 1500)
                        }
                    })
                }
            })
        } else {
            Swal.fire({
                title: 'Oops',
                text: "Tidak Ada Data Terpilih",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: '#000',
                confirmButtonText: 'OK'
            })
        }
    })



    $(document).on('click', 'input:checkbox[name=checkall]', function() {
        let rowChild = $('input:checkbox[name=checklist-multiple-delete]').parents('tr').find('td')

        $('input:checkbox[name=checklist-multiple-delete]').prop('checked', this.checked);
    });



    function getData(_status, instansi = '') {
        $.ajax({
            url: "<?php echo site_url('renstra/satker/get-data/') ?>" + _status + "/<?php echo $dokumenType ?>" + (instansi ? '/' + instansi : '') + "?_=" + new Date().getTime(),
            type: 'GET',
            success: (res) => {
                renderTableRow(_status, res.data)
            }
        })
    }



    function getDataBelumInput(_dokumenType) {
        $.ajax({
            url: "<?php echo site_url('renstra/satker/get-data-belum-input/') ?>" + _dokumenType,
            type: 'GET',
            success: (res) => {
                renderTableRowBelumInput(res.data)
            }
        })
    }



    function renderTableRow(_status, _data) {
        let buttonData_toConfirm = false

        switch (_status) {
            case 'hold':
                buttonData_toConfirm = true
                element_tableHold.clear().draw()
                break;

            case 'setuju':
                element_tableSetuju.clear().draw()
                break;

            case 'tolak':
                element_tableTolak.clear().draw()
                break;
        }

        _data.forEach((data, index) => {
            let render_badgeRevisi = '',
                render_columnChangeStatusAt = ''

            if (data.revision_master_number) {
                let badgeText = data.is_revision_same_year == '1' ? 'Revisi' : 'Koreksi Ke ' + data.revision_number,
                    badgeColor = data.is_revision_same_year == '1' ? 'bg-danger' : 'bg-warning'

                render_badgeRevisi = `
                    <button 
                        class="badge badge-sm ${badgeColor} text-white __open-list-revisioned"
                        data-dokumen-master-id="${data.revision_master_dokumen_id}"
                        style="border: none"
                    >
                        ${badgeText}
                    </button>
                `
            }

            if (_status != 'hold') render_columnChangeStatusAt = `<td>${data.change_status_at}</td>`

            var menuOptions = ''

            if (isAdmin == '1') {
                menuOptions = `
                    <!--<button 
                        class="btn btn-sm btn-outline-primary __preview-dokumen mr-4"
                        data-id="${data.id}" data-createdat ="${data.created_at}"
                        data-to-confirm="${buttonData_toConfirm}" title="Cetak"
                    >
                        <i class="fas fa-print"></i>
                    </button>--!>
                    <button 
                        class="btn btn-sm btn-outline-success __edit-dokumen"
                        data-id="${data.id}"
                        data-type="Admin"
                        data-template-id="${data.template_id}" title="Edit"
                    >
                        <i class="fas fa-edit"></i>
                    </button>
                    <!--<button 
                        class="btn btn-sm btn-outline-danger __arsipkan-dokumen"
                        data-id="${data.id}" title="Arsipkan"
                    >
                        <i class="fas fa-trash"></i>
                    </button>--!>
                `
            }

            const tr = $(`
                <tr id="_dokumen-row-${data.id}">
                    <td class="text-center">
                        <input type="checkbox" name="checklist-multiple-delete" value="${data.id}" data-status="${data.status}" />
                    </td>
                    <td class="text-center">${ index+1 }</td>
                    <td>
                        PERJANJIAN KINERJA ${data.dokumenTitle}
                        ${render_badgeRevisi}

                        <div class="mt-2">
                        Instansi : <strong>${data.satkerid}</strong><br>
                        Di buat oleh : <strong>${data.userCreatedName}</strong>

                        </div>
                    </td>
                    <td>${data.created_at}</td>
                    ${render_columnChangeStatusAt}
                    <td>
                        <button 
                            class="btn btn-sm btn-outline-secondary __lihat-dokumen"
                            data-id="${data.id}"
                            data-template-id="${data.template_id}"
                            data-to-confirm="${buttonData_toConfirm}" title="Lihat"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        ${menuOptions}
                    </td>
                </tr>
            `);

            switch (_status) {
                case 'hold':
                    element_tableHold.row.add(tr).draw()
                    break;

                case 'setuju':
                    element_tableSetuju.row.add(tr).draw()
                    break;

                case 'tolak':
                    element_tableTolak.row.add(tr).draw()
                    break;
            }
        });
    }



    function renderTableRowBelumInput(_data) {
        element_tableBelumInput.clear().draw()

        _data.forEach((data, index) => {
            element_tableBelumInput.row.add($(`
                <tr>
                    <td>${ index+1 }</td>
                    <td>${ data.nama }</td>
                </tr>
            `)).draw()
        });
    }

    function cetakDokumen(_dokumenID, _toConfirm, createAt) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/satker/export-pdf/') ?>" + _dokumenID + "_" + createAt + "?_=" + new Date().getTime(),
            type: 'GET',
            cache: false,
            success: (res) => {
                $('#modal-cetak-dokumen-revisioned').modal('hide')
                setTimeout(() => {
                    let element_iframePreviewDokumen = element_modalPreviewCetakDokumen.find('iframe')

                    if (res.dokumen.revision_message != null) {
                        element_iframePreviewDokumen.css({
                            'height': '60vh'
                        })
                        $('.container-revision-alert-cetak').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Pesan !</h5>
                                <p>${res.dokumen.revision_message}</p>
                            </div>
                        `)
                    } else {
                        element_iframePreviewDokumen.css({
                            'height': '80vh'
                        })
                        $('.container-revision-alert-cetak').html('')
                    }

                    element_iframePreviewDokumen.attr('src', '/api/showpdf/tampilkan/' + _dokumenID + "_" + createAt + '?preview=true&_=' + Math.round(Math.random() * 10000000))
                    element_modalPreviewCetakDokumen.modal('show')

                    if (_toConfirm) {
                        element_modalPreviewCetakDokumen.find('.modal-footer').html(`
                            <div class="p-2">
                                <button class="btn btn-sm btn-outline-danger mr-2 __tolak-dokumen" data-id="${_dokumenID}">
                                    <i class="fa fa-ban"></i> Tolak
                                </button>

                                <button class="btn btn-sm btn-success __setujui-dokumen" data-id="${_dokumenID}">
                                    <i class="fa fa-check"></i> Setujui
                                </button>
                            </div>
                        `)
                    } else {
                        element_modalPreviewCetakDokumen.find('.modal-footer').empty()
                    }
                }, 400)
            },
            error: function(XMLHttpRequest, testStatus, error) {
                if (XMLHttpRequest.readyState == 4) {
                    // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
                    Swal.fire({
                        type: 'error',
                        title: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else if (XMLHttpRequest.readyState == 0) {
                    // Network error (i.e. connection refused, access denied due to CORS, etc.)
                    Swal.fire({
                        type: 'error',
                        title: 'Periksa Jaringan Internet Anda',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                } else {
                    alert('error');
                }


            }
        })
    }
    $(document).on('click', '.__edit-dokumen', function() {
        let _dokumenID = $(this).data('id')
        let html = `
        <button class="btn btn-sm btn-outline-danger mr-2 __tolak-dokumen" data-id="${_dokumenID}">
            <i class="fa fa-ban"></i> Tolak
        </button>
        <button class="btn btn-sm btn-success __setujui-dokumen" data-id="${_dokumenID}">
            <i class="fa fa-check"></i> Setujui
        </button>`
        $("#parentModalFooter").html(html)
    })

    //filter
    $(document).on('click', '.filter-instansi', function() {
        let status = $(this).data('status')
        let value_instansi = $('.tab-pane.active .F_instansi').val();
        switch (status) {
            case 'belum-input':
                element_tableBelumInput.clear().draw()
                getDataBelumInput($(this).data('dokumen-type'))
                break;

            case 'hold':

                element_tableHold.clear();
                getData(status, value_instansi)
                break;

            case 'setuju':

                element_tableSetuju.clear();
                getData(status, value_instansi)
                break;

            case 'tolak':
                element_tableTolak.clear()
                getData(status, value_instansi)
                break;
        }

        // if (status != 'belum-input') {
        //     getData(status)
        // }
    })
</script>
<?= $this->endSection() ?>