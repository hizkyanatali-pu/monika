<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>



<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Data iemon</h3>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->

<!-- pesan -->

<!-- end pesan -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="background-color: white;">
    <table style="width:100%" id="tbl-emon" class="table table-bordered table-responsive dataTable no-footer">
        <thead>
            <tr>
                <th>No</th>
                <?php foreach ($thead as $field) {
                    echo "<th>" . $field . "</th>";
                } ?>
            </tr>
        </thead>
        <tbody>
        </tbody>


    </table>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script>
    var site_url = "<?php echo site_url(); ?>";

    $(document).ready(function() {
        $('#tbl-emon').DataTable({
            processing: true,
            serverSide: true,
            order: [], //init datatable not ordering
            ajax: {
                url: "<?php echo site_url('dataemon') ?>",
                method: 'POST',
                data: {
                    csrf_test_name: "<?= csrf_hash(); ?>"
                }

            },
            columnDefs: [{
                    targets: 0,
                    orderable: false
                }, //first column is not orderable.
            ]
        });
    });
</script>
<?= $this->endSection() ?>


</html>