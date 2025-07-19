<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<style>
    footer {
        display: none;
    }
</style>

<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main d-flex justify-content-between w-100">
            <div>
                <h3 class="kt-subheader__title">
                    Laporan 
                </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
            </div>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--tab" id="progres_fisik_keuangan_kementerian_pupr">
        <form target="_blank" action="<?= base_url("laporan/cetak") ?>" method="post" class="mt-4 ml-3">
            <button type="submit" class="btn btn-dark mb-3">Cetak</button>
            <table class="table">
                <tr class="form-group">
                    <th><input type="checkbox" id="checkAll"></th>
                    <th><label>Check All</label></th>
                </tr>
            <?php foreach ($report_list as $value): ?>
                <tr class="form-group">
                    <td><input type="checkbox" class="report_list" name="report_list[]" value="<?= $value ?>"></td>
                    <td><label><?= $value ?></label></td>
                </tr>
            <?php endforeach ?>
                
            </table>
        </form>
    </div>
</div>


<footer>
    <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
</footer>

<?= $this->endSection() ?>



<?= $this->section('footer_js') ?>

<?php echo script_tag('plugins/flot-old/jquery.flot.js'); ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.time.min.js'); ?>
<script>
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>

<?= $this->endSection() ?>