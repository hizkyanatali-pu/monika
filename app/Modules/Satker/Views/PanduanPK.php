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
                <div class="d-flex justify-content-start">
                    <h3 class="kt-subheader__title" style="width: 280px">
                        <?php echo $pageTitle ?? 'Panduan Input Perjanjian Kinerja' ?>
                    </h3>

                </div>

                <div>


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

            <center>
                <iframe src="<?= ($session == 'balai' ? 'manual_book/MANUAL-BOOK-INPUT-PK-(UPT-BALAI)_v1.pdf' : '/manual_book/MANUAL-BOOK-INPUT-PK-(SATKER-BALAI-TEKNIK-ESELON-2)_v1.pdf')  ?>" width="70%" height="700vh"></iframe>
            </center>


        </div>
    </div>
</div>
<!-- end-of: Content -->




<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/imask"></script>

<?php echo script_tag('plugins/inputmask/jquery.inputmask.js'); ?>
<?php echo script_tag('plugins/inputmask/inputmask.binding.js'); ?>

<?php echo $this->include('jspages/dokumenpk') ?>
<?= $this->endSection() ?>