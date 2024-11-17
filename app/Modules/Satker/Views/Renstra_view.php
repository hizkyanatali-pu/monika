<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    td.disabled {
        background: #a8a8a8;
    }

    td.disabled input[readonly],
    td.disabled select[disabled],
    td.disabled span {
        background: #a8a8a8;
    }

    /* table {
        width: 100%;
        border-collapse: collapse;
    } */

    .sticky-header-1 {
        position: sticky;
        top: -15px;
        background-color: #f9f9f9;
        z-index: 2;
        /* #a8b0ed */
        /* Menentukan tingkat tumpukan */
    }

    .sticky-header-2 {
        position: sticky;
        top: 30px;
        /* Sesuaikan dengan tinggi tiga baris */
        background-color: #f9f9f9;
        z-index: 1;
        /* Menentukan tingkat tumpukan */
    }

    .sticky-header-3 {
        position: sticky;
        top: 69px;
        /* Sesuaikan dengan tinggi tiga baris */
        background-color: #f9f9f9;
        z-index: 1;
        /* Menentukan tingkat tumpukan */
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
</style>

<!-- Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex justify-content-start">
                    <h3 class="kt-subheader__title" style="width: 280px">
                        <?php echo $pageTitle ?? 'Input Renstra' ?>
                    </h3>



                    <?= csrf_field() ?>
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
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="sticky-header-1">
                        <td class="text-center" colspan="2">Sasaran Program / Sasaran Kegiatan / Indikator</td>
                        <td class="text-center">
                            Output
                        </td>
                        <td class="text-center">
                            Outcome1
                        </td>
                        <td class="text-center">
                            Outcome2
                        </td>
                        <td class="text-center">
                            Outcome3
                        </td>

                    </tr>
                    <!-- <tr style="font-size:10px" class="sticky-header-2">
                        <td class="text-center p-2 align-middle">
                            <input type="checkbox" name="form-checkall-row" checked />
                        </td>
                        <td class="text-center p-2" colspan="2">(1)</td>
                        <td class="text-center p-2">(2)</td>
                        ${theadBalaiTargetNumber}
                        <td class="text-center p-2 ${classDNoneOutcome}">(3)</td>
                    </tr> -->
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end-of: Content -->


<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php echo script_tag('plugins/inputmask/jquery.inputmask.js'); ?>
<?php echo script_tag('plugins/inputmask/inputmask.binding.js'); ?>


<?= $this->endSection() ?>