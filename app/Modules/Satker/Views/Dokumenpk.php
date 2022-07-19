<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

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
        <div class="kt-portlet__body" style="height: 65vh">
        </div>
    </div>
</div>
<!-- end-of: Content -->



<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                <div class="d-none" id="make-dokumen">
                    123
                </div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Form -->
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<script>
    let element_modalForm                   = $('#modalForm'),
        element_modalDialog                 = element_modalForm.find('.modal-dialog')
        element_modalFormChooseTemplate     = element_modalForm.find('#choose-template'),
        element_modalFormMakeDokumen        = element_modalForm.find('#make-dokumen'),
        element_modalFormTitle              = element_modalForm.find('.modal-title'),
        element_modalFormBackChooseTemplate = element_modalForm.find('.__back-pilih-dokumen')


    $(document).ready(function() {
        $('#modalForm').on('hidden.bs.modal', function () {
            prepareForm_reset();
        })
    })



    $(document).on('click', '.__buat-dokumen-pilih-template', function() {
        preapreForm_afterChooseTemplate({
            templateId: $(this).data('id'),
            templateTitle: $(this).text()
        })
    })



    element_modalFormBackChooseTemplate.on('click', function() {
        prepareForm_reset();
    })



    function prepareForm_reset() {
        element_modalDialog.removeClass('modal-xl')
        element_modalFormMakeDokumen.addClass('d-none')
        element_modalFormChooseTemplate.removeClass('d-none')
        element_modalFormBackChooseTemplate.addClass('d-none')
        element_modalFormTitle.text('Pilih Dokumen')
    }



    function preapreForm_afterChooseTemplate(params = {
        templateId: '',
        templateTitle: ''
    }) {
        element_modalDialog.addClass('modal-xl')
        element_modalFormChooseTemplate.addClass('d-none')
        element_modalFormMakeDokumen.removeClass('d-none')
        element_modalFormBackChooseTemplate.removeClass('d-none')
        element_modalFormTitle.text(params.templateTitle)
    }
</script>
<?= $this->endSection() ?>