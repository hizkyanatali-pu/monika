<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>

<!-- Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <h3 class="kt-subheader__title">
                    Dokumen PK 
                </h3>
                <?= csrf_field() ?>
                
                <div>
                    <button 
                        class="btn btn-primary" 
                        data-toggle="modal"
                        data-target="#modalForm"
                    >
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
                        <th width="250px">Dibuat</th>
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
                                
                                <?php if ($data->revision_master_number) : ?>
                                    <div>
                                        <span class="badge badge-sm bg-warning text-white">
                                            Revisi Ke <?php echo $data->revision_master_number ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $data->created_at ?></td>
                            <td class="pr-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pill px-3 font-weight-bold <?php echo $dokumenStatus[$data->status]['color'] ?>">
                                        <?php echo $dokumenStatus[$data->status]['message'] ?>
                                    </span>
                                    

                                    <?php if ($data->status == 'tolak') : ?>
                                        <button 
                                            class="btn btn-sm btn-outline-danger __prepare-revisi-dokumen"
                                            data-id="<?php echo $data->id ?>"
                                            data-template-id="<?php echo $data->template_id ?>"
                                        >
                                            <i class="fas fa-edit"></i> Revisi
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <button 
                                    class="btn btn-sm __cetak-dokumen <?php echo $data->status == 'setuju' ? 'btn-outline-success' : 'btn-outline-secondary'?>"
                                    data-dokumen-master-id="<?php echo $dokumenMasterID ?>"
                                    data-number-revisioned="<?php echo $data->revision_master_number ?>"
                                >
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
                        <a 
                            class="list-group-item list-group-item-action __buat-dokumen-pilih-template"
                            href="javascript:void(0)" 
                            data-id="<?php echo $dataTemplate->id ?>"
                        >
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



<!-- Modal Cetak Dokumen Terevisi -->
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
<!-- end-of: Modal Cetak Dokumen Terevisi -->



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
                <iframe 
                    width="100%"
                    style="height: 80vh"
                    frameborder="0"
                ></iframe>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Preview Cetak Dokumen -->
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script>
    let element_modalForm                   = $('#modalForm'),
        element_modalDialog                 = element_modalForm.find('.modal-dialog'),
        element_modalFooter                 = element_modalForm.find('.modal-footer'),
        element_modalFormChooseTemplate     = element_modalForm.find('#choose-template'),
        element_modalFormMakeDokumen        = element_modalForm.find('#make-dokumen'),
        element_modalFormTitle              = element_modalForm.find('.modal-title'),
        element_modalFormBackChooseTemplate = element_modalForm.find('.__back-pilih-dokumen'),
        element_modalPreviewCetakDokumen    = $('#modal-preview-cetak'),
        element_btnSaveDokumen              = $('.__save-dokumen')


    $(document).ready(function() {
        $('#table').DataTable({
            ordering: false,
            scrollX: true
        })

        $('#modalForm').on('hidden.bs.modal', function () {
            prepareForm_reset();
        })
    })



    $(document).on('click', '.__buat-dokumen-pilih-template', function() {
        let dataID = $(this).data('id')
        
        $.ajax({
            url: "<?php echo site_url('dokumenpk/get-template/') ?>" + dataID,
            type: 'GET',
            data: {},
            success: (res) => {
                preapreForm_afterChooseTemplate({
                    templateId   : dataID,
                    templateTitle: $(this).text(),
                    data         : res
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



    element_btnSaveDokumen.on('click', function() {
        let formData = getFormValue();

        if ($(this).attr('data-dokumen-id')) {
            formData['revision_dokumen_id']        = $(this).data('dokumen-id')
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
    })



    $(document).on('click', '.__prepare-revisi-dokumen', function() {
        let dataID     = $(this).data('id'),
            templateID = $(this).data('template-id')

        const promiseGetTemplate = new Promise((resolve, reject) => {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/get-template/') ?>" + templateID,
                type: 'GET',
                success: (res) => {
                    preapreForm_afterChooseTemplate({
                        templateId   : templateID,
                        data         : res
                    })

                    element_modalFormTitle.html(`
                        <h6>Revisi Dokumen</h6>
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
                        let elementInput_target  = $('.__inputTemplateRow-target[data-row-id='+data.template_row_id+']'),
                            elementInput_outcome = $('.__inputTemplateRow-outcome[data-row-id='+data.template_row_id+']')

                        elementInput_target.val(data.target_value)
                        elementInput_outcome.val(data.outcome_value)
                    })

                    render_prepare_btnSubmitToRevision({
                        dokumenID      : res.dokumen.id,
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
                    res.dokumenList.forEach((data, key) => {
                        let listTitle      = 'Dokumen Awal',
                            activeClass    = '',
                            activeSubTitle = ''

                        if (data.revision_master_number) listTitle = 'Revisi #' + data.revision_master_number
                        if (data.status == 'setuju') {
                            activeClass    = 'active bg-success border-success'
                            activeSubTitle = '<div><small>Telah di setujui</small></div>'
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
                }
            })
            $('#modal-cetak-dokumen-revisioned').modal('show')  
        }
        else {
            cetakDokumen(dokumenMasterID)
        }
    })



    function getFormValue() {
        let rows = []

        $('.__inputTemplateRow-target').each((key, element) => {
            let elementInput_target  = $(element),
                elementInput_outcome = $('.__inputTemplateRow-outcome').eq(key)

            rows.push({
                id     : elementInput_target.data('row-id'),
                target : elementInput_target.val(),
                outcome: elementInput_outcome.val()
            })
        })

        return {
            csrf_test_name: $('input[name=csrf_test_name]').val(),
            templateID    : element_btnSaveDokumen.data('template-id'),
            rows          : rows
        }
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
                    element_modalPreviewCetakDokumen.find('iframe').attr('src', '<?php echo site_url('dokumen-perjanjian-kinerja.pdf') ?>')
                    element_modalPreviewCetakDokumen.modal('show')
                }, 400)
            }
        })
    }



    function preapreForm_afterChooseTemplate(params = {
        templateId: '',
        templateTitle: '',
        data: {}
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

        renderFormTemplate(params.data)
    }



    function render_prepare_btnSubmitToRevision(params = {
        dokumenID: '',
        dokumenMasterID: ''
    }) {
        element_btnSaveDokumen.attr('data-dokumen-id', params.dokumenID)
        element_btnSaveDokumen.attr('data-dokumen-master-id', params.dokumenMasterID)

        element_btnSaveDokumen.addClass('btn-danger')
        element_btnSaveDokumen.text('Simpan Revisi')
    }



    function render_reset_btnSubmitToRevision() {
        element_btnSaveDokumen.removeAttr('data-dokumen-id')
        element_btnSaveDokumen.removeAttr('data-dokumen-master-id')

        element_btnSaveDokumen.removeClass('btn-danger')
        element_btnSaveDokumen.text('Simpan Dokumen')
    }



    function renderFormTemplate(_data) {
        let template        = _data.template,
            render_rowsForm = renderFormTemplate_rowTable(_data.templateRow),
            render_listInfo = renderFormTemplate_listInfo(_data.templateInfo)
    
        let render = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="text-center" colspan="2">Sasaran Program / Sasaran Kegiatan / Indikator</td>
                        <td class="text-center" style="width: 250px">
                            Target <?php echo $sessionYear ?>
                        </td>
                        <td class="text-center" style="width: 250px">
                            Outcome
                        </td>
                    </tr>
                    <tr style="font-size:10px">
                        <td class="text-center p-2" colspan="2">(1)</td>
                        <td class="text-center p-2">(2)</td>
                        <td class="text-center p-2">(3)</td>
                    </tr>
                </thead>
                <tbody>
                    ${ render_rowsForm }
                </tbody>
            </table>
            <div class="mt-4">
                <h6>Keterangan</h6>
                <div>${ template.keterangan }</div>
            </div>
            <div class="mt-5">
                <h6>${ template.info_title }</h6>
                <ul class="list-group">
                    ${ render_listInfo }
                </ul>
            </div>
        `

        $('#make-dokumen').html(render)
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
                            <td><strong>SK</strong></td>
                            <td colspan="3">
                                <strong>${ data.title }</strong>
                            </td>
                        </tr>
                    `
                    break;
                
                case 'form':
                    rows += `
                        <tr>
                            <td class="align-middle">${ rowNumber++ }</td>
                            <td class="align-middle">${ data.title }</td>
                            <td>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-target" 
                                        placeholder="Masukkan Nilai"
                                        data-row-id="${ data.id }"
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
                                        data-row-id="${ data.id }"
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
</script>
<?= $this->endSection() ?>