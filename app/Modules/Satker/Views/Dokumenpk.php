<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    td.disabled {
        background: #a8a8a8;
    }

    td.disabled input[readonly],
    td.disabled span {
        background: #a8a8a8;
    }
</style>

<!-- Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <h3 class="kt-subheader__title">
                    Input Perjanjian Kinerja
                </h3>
                <?= csrf_field() ?>

                <div>
                    <button class="btn btn-primary __opsi-template" data-available="<?php echo $templateAvailable ?>">
                        <i class="fas fa-plus"></i> Buat Dokumen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Subheader -->



<!-- Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__body">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th width="100px">No. Dokumen</td>
                        <th>Dokumen</td>
                        <th width="150px">Tanggal Kirim</th>
                        <th width="150px">Tanggal Disetujui / Ditolak</th>
                        <th width="250px">Status</th>
                        <th width="70px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dataDokumen as $key => $data) :
                        $dokumenMasterID = $data->revision_master_dokumen_id ?? $data->id;
                    ?>
                        <tr>
                            <td><?php echo $dokumenMasterID ?></td>
                            <td>
                                <?php echo $data->dokumenTitle ?>

                                <?php if ($data->revision_master_number || $data->is_revision_same_year) : 
                                    $badgeText  = ($data->is_revision_same_year) ? 'Revisi' : 'Koreksi Ke ' . $data->revision_number;
                                    $badgeColor = ($data->is_revision_same_year) ? 'bg-danger' : 'bg-warning'
                                ?>
                                    <div>
                                        <span class="badge badge-sm <?php echo $badgeColor ?> text-white __cetak-dokumen" data-dokumen-master-id="<?php echo $dokumenMasterID ?>" data-number-revisioned="<?php echo $data->revision_master_number ?>">
                                            <?php echo $badgeText ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo date_indo($data->created_at) ?>
                            </td>
                            <td>
                                <?php echo $data->change_status_at != null ? date_indo($data->change_status_at) : '' ?>
                            </td>
                            <td class="pr-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pill px-3 font-weight-bold <?php echo $dokumenStatus[$data->status]['color'] ?>">
                                        <?php echo $dokumenStatus[$data->status]['message'] ?>
                                    </span>


                                    <?php if ($data->status == 'tolak') : ?>
                                        <button class="btn btn-sm btn-outline-danger __prepare-revisi-dokumen" data-id="<?php echo $data->id ?>" data-template-id="<?php echo $data->template_id ?>">
                                            <i class="fas fa-edit"></i> Koreksi
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm __cetak-dokumen <?php echo $data->status == 'setuju' ? 'btn-outline-success' : 'btn-outline-secondary' ?>" data-dokumen-master-id="<?php echo $dokumenMasterID ?>" data-number-revisioned="<?php echo $data->revision_master_number ?>" data-select-top="true">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end-of: Content -->



<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <button type="button" class="btn btn-default pr-2 d-none __back-pilih-dokumen">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h5 class="modal-title pt-2 pl-2">Pilih Dokumen</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group" id="choose-template">
                    <?php foreach ($templateDokumen as $keyTemplate => $dataTemplate) : ?>
                        <a class="list-group-item list-group-item-action __buat-dokumen-pilih-template" href="javascript:void(0)" data-id="<?php echo $dataTemplate->id ?>">
                            <?php echo $dataTemplate->title ?>
                        </a>
                    <?php endforeach ?>
                </div>
                <div class="p-4 d-none" id="make-dokumen">
                </div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-primary __save-dokumen">Simpan Dokumen</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Form -->



<!-- Modal Opsi Cetak Dokumen Terevisi -->
<div class="modal fade" id="modal-cetak-dokumen-revisioned" tabindex="-1" role="dialog" aria-labelledby="modal-cetak-dokumen-revisionedTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="list-group">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Opsi Cetak Dokumen Terevisi -->



<!-- Modal Preview Cetak Dokumen -->
<div class="modal fade" id="modal-preview-cetak" tabindex="-1" role="dialog" aria-labelledby="modal-preview-cetakTitle" aria-hidden="true">
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
    var element_modalForm = $('#modalForm'),
        element_modalDialog = element_modalForm.find('.modal-dialog'),
        element_modalFooter = element_modalForm.find('.modal-footer'),
        element_modalFormChooseTemplate = element_modalForm.find('#choose-template'),
        element_modalFormMakeDokumen = element_modalForm.find('#make-dokumen'),
        element_modalFormTitle = element_modalForm.find('.modal-title'),
        element_modalFormBackChooseTemplate = element_modalForm.find('.__back-pilih-dokumen'),
        element_modalPreviewCetakDokumen = $('#modal-preview-cetak'),
        element_btnSaveDokumen = $('.__save-dokumen')


    $(document).ready(function() {
        $('#table').DataTable({
            ordering: false,
            scrollX: true
        })

        $('#modalForm').on('hidden.bs.modal', function() {
            prepareForm_reset();
        })
    })



    $(document).on('click', '.__opsi-template', function() {
        if ($(this).data('available') == true) {
            $('#modalForm').modal('show')
            let elements_optionListDokumen = $('.__buat-dokumen-pilih-template')
            if (elements_optionListDokumen.length == 1) {
                elements_optionListDokumen.eq(0).trigger('click')
            }
        } else {
            Swal.fire(
                'Template Tidak Tersedia',
                'Tidak ada template yang dapat di akses oleh akun anda',
                'warning'
            )
        }
    })



    $(document).on('click', '.__buat-dokumen-pilih-template', function() {
        let dataID = $(this).data('id')

        $.ajax({
            url: "<?php echo site_url('dokumenpk/get-template/') ?>" + dataID,
            type: 'GET',
            data: {},
            success: (res) => {
                preapreForm_afterChooseTemplate({
                    templateId: dataID,
                    templateTitle: $(this).text(),
                    data: res,
                    target: 'create'
                })
            },
            fail: (xhr) => {
                alert("Terjadi kesalahan pada sistem")
                console.log(xhr)
            }
        })
    })



    element_modalFormBackChooseTemplate.on('click', function() {
        prepareForm_reset();
    })



    $(document).on('click', 'input:checkbox[name=form-checkall-row]', function() {
        let rowChild = $('input:checkbox[name=form-check-row]').parents('tr').find('td')

        $('input:checkbox[name=form-check-row]').prop('checked', this.checked);

        if (!this.checked) {
            rowChild.addClass('disabled')
        } else {
            rowChild.removeClass('disabled')
        }
    });



    $(document).on('change', 'input:checkbox[name=form-check-row]', function() {
        let element_checkAll = $('input:checkbox[name=form-checkall-row]'),
            isAllChecked = false,
            element_parentsColumn = $(this).parents('tr').find('td')

        if (!$(this).is(':checked')) {
            element_parentsColumn.addClass('disabled')
            element_parentsColumn.find('input').attr('readonly', 'readonly')
        } else {
            element_parentsColumn.removeClass('disabled')

            element_parentsColumn.find('input').removeAttr('readonly')
        }

        if ($('input:checkbox[name=form-check-row]:checked').length == $('input:checkbox[name=form-check-row]').length) {
            isAllChecked = true
        }

        element_checkAll.prop('checked', isAllChecked)
    });



    let timerInput
    $(document).on('keyup', 'input[name=kegiatan-anggaran]', function() {
        clearTimeout(timerInput)

        timerInput = setTimeout(() => {
            let totalAnggaran = 0
            $('input[name=kegiatan-anggaran]').each((key, element) => {
                totalAnggaran += parseInt($(element).val())
            })
            $('input[name=total-anggaran]').val(totalAnggaran)
        }, 1200);
    })
    
    
    
    $(document).on('change', 'select[name=created-tahun]', function() {
        if ($(this).data('action-target') == 'create') {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/check-dokumen-same-year-exist/') ?>" + $(this).val() + '/' + $(this).data('template-id'),
                type: 'GET',
                data: {},
                success: (res) => {
                    render_warningDokumenYearRevisoin = ''
                    inputValue_revisionSameYear = 0

                    if (res.dokumenExistSameYear != null) {
                        inputValue_revisionSameYear = 1
                        render_warningDokumenYearRevisoin = `
                            <div class="bg-warning text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Peringatan</h5>
                                <p>membuat dokumen baru akan merevisi dokumen yang telah anda buat sebelumnya</p>
                            </div>
                        `

                        render_prepare_btnSubmitToRevision({
                            dokumenID      : res.dokumenExistSameYear.last_dokumen_id,
                            dokumenMasterID: res.dokumenExistSameYear.revision_master_dokumen_id,
                            buttonType     : 'warning',
                            buttonText     : 'Buat Revisi'
                        });
                    }
                    else {
                        render_reset_btnSubmitToRevision()
                    }

                    $('input[name=revision_same_year]').val(inputValue_revisionSameYear)
                    $('.container-revision-alert').html(render_warningDokumenYearRevisoin)
                    $('.container-revision-alert-bottom').html(render_warningDokumenYearRevisoin)
                },
                fail: (xhr) => {
                    alert("Terjadi kesalahan pada sistem")
                    console.log(xhr)
                }
            })
        }
    })



    element_btnSaveDokumen.on('click', function() {
        if (saveDokumenValidation()) {
            let formData = getFormValue();
            console.log(formData)

            if ($(this).attr('data-dokumen-id')) {
                formData['revision_dokumen_id'] = $(this).data('dokumen-id')
                formData['revision_dokumen_master_id'] = $(this).data('dokumen-master-id')
            }

            $.ajax({
                url: "<?php echo site_url('dokumenpk/create') ?>",
                type: 'POST',
                data: formData,
                success: (res) => {
                    if (res.status) {
                        location.reload()
                    }
                },
                fail: (xhr) => {
                    alert('Terjadi kesalahan pada sistem')
                    console.log(xhr)
                }
            })
        }
    })



    $(document).on('click', '.__prepare-revisi-dokumen', function() {
        let dataID = $(this).data('id'),
            templateID = $(this).data('template-id')

        const promiseGetTemplate = new Promise((resolve, reject) => {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/get-template/') ?>" + templateID,
                type: 'GET',
                success: (res) => {
                    preapreForm_afterChooseTemplate({
                        templateId: templateID,
                        data      : res,
                        target    : 'koreksi'
                    })

                    element_modalFormTitle.html(`
                        <h6>Koreksi Dokumen</h6>
                        <small>${ res.template.title }</small>
                    `)

                    resolve(dataID)
                },
                fail: (xhr) => {
                    alert("Terjadi kesalahan pada sistem")
                    console.log(xhr)
                    reject(then)
                }
            })
        })

        promiseGetTemplate.then((res) => {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/detail/') ?>" + res,
                type: 'GET',
                success: (res) => {
                    res.rows.forEach((data, key) => {
                        let elementInput_target = $('.__inputTemplateRow-target[data-row-id=' + data.template_row_id + ']'),
                            elementInput_outcome = $('.__inputTemplateRow-outcome[data-row-id=' + data.template_row_id + ']')

                        elementInput_target.val(data.target_value)
                        elementInput_outcome.val(data.outcome_value)

                        if (data.is_checked == '0') elementInput_target.parents('tr').find('input:checkbox[name=form-check-row]').trigger('click')
                    })

                    res.kegiatan.forEach((data, key) => {
                        let elementInput_target = $('tr[data-kegiatan-id='+data.id+']').find('input[name=kegiatan-anggaran]')
                        elementInput_target.val(data.anggaran)
                    })

                    $('input[name=total-anggaran]').val(res.dokumen.total_anggaran)
                    $('input[name=ttd-pihak1]').val(res.dokumen.pihak1_ttd)
                    $('input[name=ttd-pihak2]').val(res.dokumen.pihak2_ttd)

                    $('.title-ttd-pihak1').text('KEPALA ' + res.dokumen.pihak1_initial)
                    $('.title-ttd-pihak2').text('KEPALA ' + res.dokumen.pihak2_initial)

                    if ($('input[name=ttd-pihak2-jabatan]').length) {
                        $('input[name=ttd-pihak2-jabatan]').val(res.dokumen.pihak2_initial)
                    }

                    if (res.dokumen.pihak1_is_plt == '1') $('input:checkbox[name=ttd-pihak1-plt]').prop('checked', true)
                    if (res.dokumen.pihak2_is_plt == '1') $('input:checkbox[name=ttd-pihak2-plt]').prop('checked', true)
                    
                    $('select[name=created-kota]').val(res.dokumen.kota).trigger('change')
                    $('select[name=created-bulan]').val(res.dokumen.bulan).trigger('change')
                    $('select[name=created-tahun]').val(res.dokumen.tahun).trigger('change')

                    if (res.dokumen.revision_message != null) {
                        $('.container-revision-alert').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Perlu Di Koreksi !</h5>
                                <p>${res.dokumen.revision_message}</p>
                            </div>
                        `)
                    }


                    render_prepare_btnSubmitToRevision({
                        dokumenID: res.dokumen.id,
                        dokumenMasterID: res.dokumen.revision_master_dokumen_id ?? res.dokumen.id
                    });
                    $('#modalForm').modal('show')
                },
                fail: (xhr) => {
                    alert("Terjadi kesalahan pada sistem")
                    console.log(xhr)
                }
            })
        })
    })



    $(document).on('click', '.__cetak-dokumen', function() {
        let dokumenMasterID = $(this).data('dokumen-master-id')

        if ($(this).data('number-revisioned')) {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/get-list-revisioned/') ?>" + dokumenMasterID,
                type: 'GET',
                success: (res) => {
                    let list = ''
                    if ($(this).data('select-top')) {
                        cetakDokumen(res.dokumenList[0].id)
                    } else {
                        res.dokumenList.forEach((data, key) => {
                            let listTitle = 'Dokumen Awal',
                                activeClass = '',
                                activeSubTitle = ''

                            if (data.revision_master_number) listTitle = 'Koreksi #' + data.revision_number

                            if (data.status == 'setuju') {
                                activeClass = 'active bg-success border-success'
                                activeSubTitle = '<div><small>Telah di setujui</small></div>'
                            }

                            if (data.is_revision_same_year == '1') {
                                listTitle = 'Revisi'
                                activeClass = 'active bg-danger border-danger'
                            }

                            list += `
                                <button 
                                    class="list-group-item list-group-item-action ${activeClass}"
                                    onclick="cetakDokumen(${data.id})"
                                >
                                    ${listTitle}
                                    ${activeSubTitle}
                                </button>
                            `
                        })

                        $('#modal-cetak-dokumen-revisioned').find('.list-group').html(list)
                        $('#modal-cetak-dokumen-revisioned').modal('show')
                    }
                }
            })
        } else {
            cetakDokumen(dokumenMasterID)
        }
    })



    function getFormValue() {
        let rows = [],
            kegiatan = []

        $('.__inputTemplateRow-target').each((key, element) => {
            let elementInput_target = $(element),
                elementInput_outcome = $('.__inputTemplateRow-outcome').eq(key),
                element_checkRow = $('input:checkbox[name=form-check-row]').eq(key)

            rows.push({
                id: elementInput_target.data('row-id'),
                target: elementInput_target.val(),
                outcome: elementInput_outcome.val(),
                isChecked: element_checkRow.is(':checked') ? '1' : '0'
            })
        })

        $('.__table-kegiatan').find('tbody').find('tr').each((key, element) => {
            kegiatan.push({
                id: $(element).data('kegiatan-id'),
                nama: $(element).data('kegiatan-nama'),
                anggaran: $(element).find('input[name=kegiatan-anggaran]').val()
            })
        })

        let inputValue = {
            csrf_test_name  : $('input[name=csrf_test_name]').val(),
            revisionSameYear: $('input[name=revision_same_year]').val(),
            templateID      : element_btnSaveDokumen.data('template-id'),
            rows            : rows,
            kegiatan        : kegiatan,
            totalAnggaran   : $('input[name=total-anggaran]').val(),
            ttdPihak1       : $('input[name=ttd-pihak1]').val(),
            ttdPihak1_isPlt : $('input:checkbox[name=ttd-pihak1-plt]').is(':checked') ? '1': '0',
            ttdPihak2       : $('input[name=ttd-pihak2]').val(),
            ttdPihak2_isPlt : $('input:checkbox[name=ttd-pihak2-plt]').is(':checked') ? '1': '0',
            kota            : $('select[name=created-kota]').val(),
            bulan           : $('select[name=created-bulan]').val(),
            tahun           : $('select[name=created-tahun]').val()
        }
        if ($('input[name=ttd-pihak2-jabatan]').length) inputValue.ttdPihak2Jabatan = $('input[name=ttd-pihak2-jabatan]').val()

        return inputValue
    }



    function saveDokumenValidation() {
        let checkInputKegiatanAnggatan = true
        $('input[name=kegiatan-anggaran]').each((index, element) => {
            if ($(element).val() > 0 && checkInputKegiatanAnggatan == true) {
                checkInputKegiatanAnggatan = true
            } else {
                checkInputKegiatanAnggatan = false
            }
        })
        if (checkInputKegiatanAnggatan == false) {
            Swal.fire(
                'Peringatan',
                'Terdapat angaran untuk kegiatan yang belum terisi',
                'warning'
            )
            return false
        }

        if ($('input[name=ttd-pihak1]').val() == '') {
            Swal.fire(
                'Peringatan',
                'Penandatangan pihak pertama belum terisi',
                'warning'
            )
            return false
        }

        if ($('input[name=ttd-pihak2]').val() == '') {
            Swal.fire(
                'Peringatan',
                'Penandatangan pihak kedua belum terisi',
                'warning'
            )
            return false
        }

        return true
    }



    function prepareForm_reset() {
        element_modalDialog.removeClass('modal-xl')
        element_modalFooter.addClass('d-none')
        element_modalFormMakeDokumen.addClass('d-none')
        element_modalFormChooseTemplate.removeClass('d-none')
        element_modalFormBackChooseTemplate.addClass('d-none')
        element_modalFormTitle.html('Pilih Dokumen')
        render_reset_btnSubmitToRevision()
    }



    function cetakDokumen(_dokumenID) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/export-pdf/') ?>" + _dokumenID,
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



    function preapreForm_afterChooseTemplate(params = {
        templateId   : '',
        templateTitle: '',
        data         : {},
        target       : ''
    }) {
        element_btnSaveDokumen.attr('data-template-id', params.templateId)
        element_modalDialog.addClass('modal-xl')
        element_modalFooter.removeClass('d-none')
        element_modalFormChooseTemplate.addClass('d-none')
        element_modalFormMakeDokumen.removeClass('d-none')
        element_modalFormBackChooseTemplate.removeClass('d-none')

        if (params.hasOwnProperty('templateTitle')) {
            element_modalFormTitle.html(`
                <h6>Perjanjian Kinerja Tahun <?php echo $sessionYear ?></h6>
                <small>${params.templateTitle}</small>
            `)
        }
        
        renderFormTemplate(params.data, params.target)
    }



    function render_prepare_btnSubmitToRevision(params = {
        dokumenID: '',
        dokumenMasterID: '',
        buttonType: '',
        buttonText: ''
    }) {
        let buttonType = params.hasOwnProperty('buttonType') ? 'btn-'+params.buttonType : 'btn-danger',
            buttonText = params.hasOwnProperty('buttonText') ? params.buttonText : 'Simpan Koreksi'

        element_btnSaveDokumen.attr('data-dokumen-id', params.dokumenID)
        element_btnSaveDokumen.attr('data-dokumen-master-id', params.dokumenMasterID)

        element_btnSaveDokumen.addClass(buttonType)
        element_btnSaveDokumen.text(buttonText)
    }



    function render_reset_btnSubmitToRevision() {
        element_btnSaveDokumen.removeAttr('data-dokumen-id')
        element_btnSaveDokumen.removeAttr('data-dokumen-master-id')

        element_btnSaveDokumen.removeClass('btn-danger')
        element_btnSaveDokumen.removeClass('btn-warning')
        element_btnSaveDokumen.text('Simpan Dokumen')
    }



    function renderFormTemplate(_data, _target) {
        let template                          = _data.template,
            render_rowsForm                   = renderFormTemplate_rowTable(_data.templateRow),
            render_rowKegiatan                = renderFormTemplate_rowKegiatan(_data.templateKegiatan),
            render_listInfo                   = renderFormTemplate_listInfo(_data.templateInfo),
            render_ttdPihak2                  = renderFormTemplate_ttdPihak2(_data.penandatangan.pihak2),
            render_opsiKota                   = renderFormTemplate_opsiKota(_data.kota),
            render_opsiBulan                  = renderFormTemplate_opsiBulan(_data.bulan),
            render_opsiTahun                  = renderFormTemplate_opsiTahun(_data.tahun),
            render_warningDokumenYearRevisoin = '',
            inputValue_revisionSameYear       = 0
        
        if (_target == 'create' && _data.dokumenExistSameYear != null) {
            render_warningDokumenYearRevisoin = `
                <div class="bg-warning text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                    <h5 class="alert-heading">Peringatan</h5>
                    <p>membuat dokumen baru akan merevisi dokumen yang telah anda buat sebelumnya</p>
                </div>
            `
            inputValue_revisionSameYear = 1

            render_prepare_btnSubmitToRevision({
                dokumenID      : _data.dokumenExistSameYear.last_dokumen_id,
                dokumenMasterID: _data.dokumenExistSameYear.revision_master_dokumen_id,
                buttonType     : 'warning',
                buttonText     : 'Buat Revisi'
            });
        }

        let render = `
            <input type="hidden" name="revision_same_year" value="${inputValue_revisionSameYear}" />

            <div class="container-revision-alert">
                ${render_warningDokumenYearRevisoin}
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="text-center" colspan="3">Sasaran Program / Sasaran Kegiatan / Indikator</td>
                        <td class="text-center" style="width: 250px">
                            Target <?php echo $sessionYear ?>
                        </td>
                        <td class="text-center" style="width: 250px">
                            Outcome
                        </td>
                    </tr>
                    <tr style="font-size:10px">
                        <td class="text-center p-2 align-middle">
                            <input type="checkbox" name="form-checkall-row" checked />
                        </td>
                        <td class="text-center p-2" colspan="2">(1)</td>
                        <td class="text-center p-2">(2)</td>
                        <td class="text-center p-2">(3)</td>
                    </tr>
                </thead>
                <tbody>
                    ${ render_rowsForm }
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-7">
                    <div class="mt-4">
                        <h6>${ template.keterangan != '' ? 'KETERANGAN' : '' }</h6>
                        <div>${ template.keterangan }</div>
                    </div>
                    <div class="mt-5 mb-5">
                        <h6>${ template.info_title }</h6>
                        <!--
                        <ul class="list-group">
                            ${ render_listInfo }
                        </ul>
                        -->
                        <table class="table table-striped border __table-kegiatan">
                            <thead>
                                <tr>
                                    <th>Nama Kegiatan</th>
                                    <th width="250px">Anggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${render_rowKegiatan}
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 pt-4">
                        <h6 class="mb-4">Dokumen Dibuat Di</h6>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kota</label>
                            <div class="col-sm-5">
                                <select class="form-control select2" name="created-kota">
                                    ${render_opsiKota}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Bulan</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="created-bulan">
                                    ${render_opsiBulan}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tahun</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="created-tahun" data-template-id="${template.id}" data-action-target="${_target}">
                                    ${render_opsiTahun}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group mt-4">
                        <label>
                            <strong>Total Anggaran</strong>
                        </label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp. </span>
                            </div>
                            <input class="form-control" name="total-anggaran" placeholder="Nominal Total Anggaran" readonly/>
                        </div>
                    </div>
                    <div class="form-group mt-4 pt-4">
                        <div class="d-flex justify-content-between">
                            <label>
                                <strong>Pihak Pertama</strong>
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" name="ttd-pihak1-plt" type="checkbox" value="1" id="ttdPihak1Plt">
                                <label class="form-check-label" for="ttdPihak1Plt">
                                    Plt.
                                </label>
                            </div>
                        </div>
                        <div>
                            <small class="title-ttd-pihak1">
                                KEPALA ${_data.penandatangan.pihak1}
                            </small>
                        </div>
                        <input class="form-control" name="ttd-pihak1" placeholder="Masukkan Nama Penanda Tangan" required />
                    </div>
                    <div class="form-group mt-4 pt-2">
                        <div class="d-flex justify-content-between">
                            <label>
                                <strong>Pihak Kedua</strong>
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" name="ttd-pihak2-plt" type="checkbox" value="1" id="ttdPihak2Plt">
                                <label class="form-check-label" for="ttdPihak2Plt">
                                    Plt.
                                </label>
                            </div>
                        </div>
                        <div>
                            ${render_ttdPihak2}
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-revision-alert-bottom">
            </div>
        `

        $('#make-dokumen').html(render)

        $('select.select2').select2();
    }



    function renderFormTemplate_rowTable(_data) {
        let rows = '',
            rowNumber = 1

        _data.forEach((data, key) => {
            switch (data.type) {
                case 'section_title':
                    rowNumber = 1
                    rows += `
                        <tr>
                            <td colspan="2"><strong>SK</strong></td>
                            <td colspan="3">
                                <strong>${ data.title }</strong>
                            </td>
                        </tr>
                    `
                    break;

                case 'form':
                    rows += `
                        <tr>
                            <td class="text-center align-middle">
                                <input type="checkbox" name="form-check-row" checked />
                            </td>
                            <td class="align-middle">${ rowNumber++ }</td>
                            <td class="align-middle">${ data.title }</td>
                            <td>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-target" 
                                        placeholder="Masukkan Nilai"
                                        value="${ data.targetDefualtValue }"
                                        data-row-id="${ data.id }"
                                        onkeypress="return isNumberKey(this, event);"
                                    >
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.target_satuan }</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-outcome" 
                                        placeholder="Masukkan Nilai"
                                        value="0"
                                        data-row-id="${ data.id }"
                                        onkeypress="return isNumberKey(this, event);"
                                    >
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.outcome_satuan }</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `
                    break;
            }
        });

        return rows
    }



    function renderFormTemplate_rowKegiatan(_data) {
        let list = ''
        _data.forEach((data, key) => {
            list += `
                <tr
                    data-kegiatan-id="${data.id}"
                    data-kegiatan-nama="${data.nama}"
                >
                    <td class="align-middle">
                        ${data.nama}
                    </td>
                    <td class="align-middle">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp. </span>
                            </div>
                            <input 
                                class="form-control" 
                                name="kegiatan-anggaran" 
                                value="0" 
                                placeholder="Nominal Anggaran"
                            >
                        </div>
                    </td>
                </tr>
            `
        });

        return list
    }



    function renderFormTemplate_listInfo(_data) {
        let list = ''
        _data.forEach((data, key) => {
            list += `
                <li class="list-group-item border-0 py-1 px-4">
                    ${ data.info }
                </li>
            `
        });

        return list
    }



    function renderFormTemplate_ttdPihak2(_dataPenandatanganPihak2) {
        let prefixJabatanPenandatangan = '', //_dataPenandatanganPihak2.includes('KEPALA') ? '' : 'KEPALA',
            renderJalabatan = `<div><small class="title-ttd-pihak2">${prefixJabatanPenandatangan + ' ' + _dataPenandatanganPihak2}</small></div>`

        if (_dataPenandatanganPihak2 == '') {
            renderJalabatan = `
                <input class="form-control" name="ttd-pihak2-jabatan" placeholder="Jabatan Penanda Tangan" />
            `
        }

        return `
            ${renderJalabatan}
            <input class="form-control" name="ttd-pihak2" placeholder="Masukkan Nama Penanda Tangan" />
        `
    }
    
    
    
    function renderFormTemplate_opsiKota(_dataKota) {
        let renderOptions = ''

        _dataKota.forEach((data, key) => {
            renderOptions += `<option value='${data.kdlokasi + '-' + data.kdkabkota}'>${data.nmkabkota}</option>`
        });

        return renderOptions
    }
    
    
    
    function renderFormTemplate_opsiBulan(_data) {
        let renderOptions = ''

        _data.forEach((data, key) => {
            renderOptions += `<option value='${key+1}'>${data}</option>`
        });

        return renderOptions
    }
    
    
    
    function renderFormTemplate_opsiTahun(_data) {
        let renderOptions = ''

        for (let iTahun = (parseInt(_data)-3); iTahun < _data; iTahun++) {
            renderOptions += `<option>${iTahun}</option>`
        }

        for (let iTahun = _data; iTahun <= (parseInt(_data)+3); iTahun++) {
            let selected = iTahun == _data ? 'selected=selected' : ''
            renderOptions += `<option ${selected}>${iTahun}</option>`
        }

        return renderOptions
    }
</script>
<?= $this->endSection() ?>