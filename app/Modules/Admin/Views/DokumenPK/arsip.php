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
</style>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex justify-content-start">
                    <h3 class="kt-subheader__title" style="width: 430px">
                        ARSIP PERJANJIAN KINERJA
                    </h3>

                    <select name="filter-jenis-dokumen" class="form-control">
                        <option value="all">SEMUA</option>
                        <option value="satker">SATKER</option>
                        <option value="balai">BALAI</option>
                        <option value="eselon2">ESELON 2</option>
                        <option value="eselon1">ESELON 1</option>
                    </select>

                    <?= csrf_field() ?>
                </div>
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
                <!-- <button class="btn btn-danger mb-4 __deletePermanenMultiple" data-target="hold">
                    <i class="fas fa-trash"></i> Hapus Permanen Data Terchecklist
                </button> -->

                <table class="table table-bordered" id="table-hold">
                    <thead>
                        <tr class="text-center">
                            <th width="15px">
                                <input type="checkbox" name="checkall" data-status="hold" />
                            </th>
                            <th width="25px">No</th>
                            <th>Dokumen</th>
                            <th width="120px">Tanggal Kirim</th>
                            <th width="25px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                    </tbody>
                </table>
            </div>



            <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab">
                <!-- <button class="btn btn-danger mb-4 __deletePermanenMultiple" data-target="setuju">
                    <i class="fas fa-trash"></i> Hapus Permanen Data Terchecklist
                </button> -->

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
                            <th width="25px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                    </tbody>
                </table>
            </div>



            <div class="tab-pane fade" id="pills-three" role="tabpanel" aria-labelledby="pills-three-tab">
                <!-- <button class="btn btn-danger mb-4 __deletePermanenMultiple" data-target="tolak">
                    <i class="fas fa-trash"></i> Hapus Permanen Data Terchecklist
                </button> -->

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
                            <th width="25px">Aksi</th>
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
                <iframe width="100%" style="height: 80vh" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Preview Cetak Dokumen -->
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    var element_tableHold = '',
        element_tableSetuju = '',
        element_tableTolak = '',
        element_modalPreviewCetakDokumen = $('#modal-preview-cetak')



    $(document).ready(function() {
        setTimeout(() => {
            element_tableHold = $('#table-hold').DataTable({
                scrollX: true
            })
            getData('hold');
        }, 300)
    })

    $('#pills-one-tab').on('click', () => {
        setTimeout(() => {
            if (element_tableSetuju == '') {
                element_tableSetuju = $('#table-setuju').DataTable({
                    scrollX: true
                })
            }
            getData('hold');
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

    $(document).on('change', 'select[name=filter-jenis-dokumen]', function() {
        $('#pills-one-tab').trigger('click')
    })



    $(document).on('click', '.__preview-dokumen', function() {
        cetakDokumen(
            $(this).data('id'),
            $(this).data('to-confirm')
        )
    })



    $(document).on('click', '.__reStore', function() {
        Swal.fire({
            title: 'Restore Dokumen',
            text: "Kembalikan dokumen ini ?",
            showCancelButton: true,
            confirmButtonColor: '#0abb87',
            cancelButtonColor: '#000',
            confirmButtonText: 'Ya, Restore',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/arsip/restore') ?>",
                    type: 'POST',
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        id: $(this).data('id')
                    },
                    success: (res) => {
                        Swal.fire(
                            'Berhasil',
                            'Dokumen telah di kembalikan',
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



    $(document).on('click', '.__deletePermanen', function() {
        Swal.fire({
            title: 'Hapus Permanen',
            text: "Dokumen akan di hapus secara permanen dan tidak dapat di kembalikan lagi",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#000',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/arsip/delete-permanent') ?>",
                    type: 'POST',
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        id: $(this).data('id')
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
    })
    
    
    
    $(document).on('click', '.__deletePermanenMultiple', function() {
        let tempCheck = [],
            checklist = $('input[type=checkbox][name=checklist-multiple-delete][data-status='+$(this).data('target')+']:checked')

        checklist.each((index, element) => {
            tempCheck.push($(element).val())
        })

        if (tempCheck.length > 0) {
            Swal.fire({
                title: 'Hapus Permanen',
                text: "Apakah anda yakin menghapus permanen semua data yang telah di pilih",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#000',
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "<?php echo site_url('dokumenpk/arsip/delete-permanent-multiple') ?>",
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
        }
        else {
            Swal.fire({
                title: 'Oops',
                text: "anda belum memilih data untuk di hapus",
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

        if (!this.checked) {
            rowChild.addClass('disabled')
        } else {
            rowChild.removeClass('disabled')
        }
    });



    function getData(_status) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/arsip/get-data/') ?>" + _status + '/' + $("select[name=filter-jenis-dokumen]").val(),
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

            const tr = $(`
                <tr id="_dokumen-row-${data.id}">
                    <td class="text-center">
                        <input type="checkbox" name="checklist-multiple-delete" value="${data.id}" data-status="${data.status}" />
                    </td>
                    <td class="text-center">${ index+1 }</td>
                    <td>
                        ${data.dokumenTitle}
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
                            class="btn btn-sm btn-outline-primary __preview-dokumen mr-4"
                            data-id="${data.id}"
                            data-to-confirm="${buttonData_toConfirm}"
                        >
                            <i class="fas fa-print"></i><br/>
                            Cetak
                        </button>
                        <!--
                        <button 
                            class="btn btn-sm btn-outline-success __reStore"
                            data-id="${data.id}"
                        >
                            <i class="fas fa-sync-alt"></i><br/>
                            Restore
                        </button>
                        <button 
                            class="btn btn-sm btn-outline-danger __deletePermanen"
                            data-id="${data.id}"
                        >
                            <i class="fas fa-trash"></i><br/>
                            Hapus
                        </button>
                        -->
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
                        element_iframePreviewDokumen.css({
                            'height': '60vh'
                        })
                        $('.container-revision-alert-cetak').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Perlu Di Koreksi !</h5>
                                <p>${res.dokumen.revision_message}</p>
                            </div>
                        `)
                    } else {
                        element_iframePreviewDokumen.css({
                            'height': '80vh'
                        })
                        $('.container-revision-alert-cetak').html('')
                    }

                    element_iframePreviewDokumen.attr('src', '<?php echo site_url('dokumen-perjanjian-kinerja.pdf') ?>')
                    element_modalPreviewCetakDokumen.modal('show')
                }, 400)
            }
        })
    }
</script>
<?= $this->endSection() ?>