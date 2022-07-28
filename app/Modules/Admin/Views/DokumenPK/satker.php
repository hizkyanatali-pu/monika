<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
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
</style>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <h5 class="kt-subheader__title">
                    Dokumen Satker
                </h5>
                <?= csrf_field() ?>
            </div>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <ul class="nav nav-pills mb-3 flex-column flex-sm-row" id="pills-tab" role="tablist">
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
            <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab">
                <table 
                    class="table table-bordered" 
                    id="table-hold"
                >
                    <thead>
                        <tr class="text-center">
                            <th width="25px">No</th>
                            <th>Dokumen</th>
                            <th width="250px">Tanggal</th>
                            <th width="120px"></th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                    </tbody>
                </table>
            </div>



            <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab">
                <table 
                    class="table table-bordered" 
                    id="table-setuju"
                >
                    <thead>
                        <tr class="text-center">
                            <th width="30px">No</th>
                            <th>Dokumen</th>
                            <th width="250px">Tanggal</th>
                            <th width="120px"></th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                    </tbody>
                </table>
            </div>



            <div class="tab-pane fade" id="pills-three" role="tabpanel" aria-labelledby="pills-three-tab">
                <table 
                    class="table table-bordered" 
                    id="table-tolak"
                >
                    <thead>
                        <tr class="text-center">
                            <th width="30px">No</th>
                            <th>Dokumen</th>
                            <th width="250px">Tanggal</th>
                            <th width="120px"></th>
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
<div class="modal fade" id="modal-preview-cetak" role="dialog" aria-labelledby="modal-preview-cetakTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="container-revision-alert-cetak"></div>
                <iframe 
                    width="100%"
                    style="height: 80vh"
                    frameborder="0"
                ></iframe>
            </div>
            <div class="modal-footer p-0">
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Preview Cetak Dokumen -->



<!-- Modal Cetak Dokumen Terevisi -->
<div class="modal fade" id="modal-cetak-dokumen-revisioned" role="dialog" aria-labelledby="modal-cetak-dokumen-revisionedTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
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
<script>
    var element_tableHold                = '',
        element_tableSetuju              = '',
        element_tableTolak               = '',
        element_modalPreviewCetakDokumen = $('#modal-preview-cetak'),
        element_modalListRevision        = $('#modal-cetak-dokumen-revisioned'),
        element_formTable                = $('._table-form').find('tbody'),
        element_tableInformasi           = $('._table-informasi').find('tbody')


    $(document).ready(function () {
        setTimeout(() => {
            element_tableHold = $('#table-hold').DataTable({
                scrollX: true
            })
            getData('hold');
        },300)
    })



    $('#pills-two-tab').on('click', () => {
        setTimeout(() => {
            if (element_tableSetuju == '') {
                element_tableSetuju = $('#table-setuju').DataTable({
                    scrollX: true
                })
            }
            getData('setuju');
        },300)
    })

    $('#pills-three-tab').on('click', () => {
        setTimeout(() => {
            if (element_tableTolak == '') {
                element_tableTolak = $('#table-tolak').DataTable({
                    scrollX: true
                })
            }
            getData('tolak');
        },300)
    })



    $(document).on('click', '.__preview-dokumen', function() {
        cetakDokumen(
            $(this).data('id'),
            $(this).data('to-confirm')
        )
    })



    $(document).on('click', '.__open-list-revisioned', function() {
        let dokumenMasterID = $(this).data('dokumen-master-id')

        $.ajax({
            url: "<?php echo site_url('dokumenpk/satker/get-list-revisioned/') ?>" + dokumenMasterID,
            type: 'GET',
            success: (res) => {
                let list = ''
                res.dokumenList.forEach((data, key) => {
                    let listTitle      = 'Dokumen Awal',
                        activeClass    = '',
                        activeSubTitle = '',
                        buttonData_toConfirm = false

                    if (data.revision_master_number) listTitle = 'Revisi #' + data.revision_master_number
                    if (data.status == 'setuju') {
                        activeClass    = 'active bg-success border-success'
                        activeSubTitle = '<div><small>Telah di setujui</small></div>'
                    }
                    if (key == 0 && data.status == 'hold') buttonData_toConfirm = true
                    
                    list += `
                        <button 
                            class="list-group-item list-group-item-action ${activeClass} __preview-dokumen"
                            data-id="${data.id}"
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
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/change-status') ?>",
                    type: "POST",
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        dokumenType   : 'satker',
                        dataID        : dataID,
                        message       : $('textarea[name=pesan-tolak-dokumen]').val(),
                        newStatus     : 'tolak'
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
                .row($('#_dokumen-row-'+dataID))
                .remove()
                .draw();
            }
        })
    })



    $(document).on('click', '.__setujui-dokumen', function() {
        let dataID = $(this).data('id')
        $.ajax({
            url: "<?php echo site_url('dokumenpk/change-status') ?>",
            type: "POST",
            data: { 
                csrf_test_name: $('input[name=csrf_test_name]').val(),
                dokumenType   : 'satker',
                dataID        : dataID,
                newStatus     : 'setuju'
            },
            success: (res) => {
                element_modalPreviewCetakDokumen.modal('hide')

                element_tableHold
                .row($('#_dokumen-row-'+dataID))
                .remove()
                .draw();
            }
        })
    })






    function getData(_status) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/satker/get-data/') ?>" + _status,
            type: 'GET',
            success: (res) => {
                renderTableRow(_status, res.data)
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
            let render_badgeRevisi = ''
            if (data.revision_master_number) {
                render_badgeRevisi = `
                    <button 
                        class="badge badge-sm bg-warning text-white __open-list-revisioned"
                        data-dokumen-master-id="${data.revision_master_dokumen_id}"
                        style="border: none"
                    >
                        Revisi Ke ${data.revision_master_number}
                    </button>
                `
            }

            const tr = $(`
                <tr id="_dokumen-row-${data.id}">
                    <td class="text-center">${ index+1 }</td>
                    <td>
                        ${data.dokumenTitle}
                        ${render_badgeRevisi}

                        <div class="mt-2">
                            Di buat oleh : <strong>${data.userCreatedName}</strong>
                        </div>
                    </td>
                    <td>${data.created_at}</td>
                    <td>
                        <button 
                            class="btn btn-sm btn-outline-primary __preview-dokumen"
                            data-id="${data.id}"
                            data-to-confirm="${buttonData_toConfirm}"
                        >
                            <i class="fas fa-file"></i>
                            Lihat Dokumen
                        </button>
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



    function cetakDokumen(_dokumenID, _toConfirm) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/satker/export-pdf/') ?>" + _dokumenID,
            type: 'GET',
            success: (res) => {
                $('#modal-cetak-dokumen-revisioned').modal('hide')

                setTimeout(() => {
                    let element_iframePreviewDokumen = element_modalPreviewCetakDokumen.find('iframe')

                    if (res.dokumen.revision_message != null) {
                        element_iframePreviewDokumen.css({'height':'60vh'})
                        $('.container-revision-alert-cetak').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Perlu Di Revisi !</h5>
                                <p>${res.dokumen.revision_message}</p>
                            </div>
                        `)
                    }
                    else {
                        element_iframePreviewDokumen.css({'height':'80vh'})
                        $('.container-revision-alert-cetak').html('')
                    }

                    element_iframePreviewDokumen.attr('src', '<?php echo site_url('dokumen-perjanjian-kinerja.pdf') ?>')
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
                    }
                    else {
                        element_modalPreviewCetakDokumen.find('.modal-footer').empty()
                    }
                }, 400)
            }
        })
    }
</script>
<?= $this->endSection() ?>