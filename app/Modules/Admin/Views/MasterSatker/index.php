<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>

<style>
    body.swal2-shown>[aria-hidden="true"] {
        filter: blur(10px);
    }

    body>* {
        transition: 0.1s filter linear;
    }
</style>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Master Satker <?php echo $tahunAnggaran ?>
            </h5>
        </div>

    </div>
</div>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <!--begin::Section-->
            <div class="kt-section">
                <div id="body-form">
                    <div id="container-form">
                        <div class="form-group">
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalKelolaDataExcel"> 
                                <i class="fas fa-file-excel mr-2"></i> Kelola Data Dengan Excel
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="mt-0">

                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Satker Id</th>
                            <th>Balai</th>
                            <th>Satker</th>
                            <th>KD KPPN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = 1;
                            foreach ($mainData as $key => $data) : 
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $data->satkerid ?></td>
                                <td><?php echo $data->balai ?></td>
                                <td><?php echo $data->satker ?></td>
                                <td><?php echo $data->kdkppn ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <!--end::Section-->
        </div>
        <!--end::Form-->
    </div>
</div>


<!-- Modal Kelola Data Excel -->
<div class="modal fade" id="modalKelolaDataExcel" tabindex="-1" role="dialog" aria-labelledby="modalKelolaDataExcelTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Kelola Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 mt-2">
                    <p>Unduh data dalam bentul file excel</p>
                    <p>Pastikan anda tidak mengubah nama file apabila anada ingin mengupload kembali file tersebut</p>
                    <div>
                        <a class="btn btn-default" href="<?php echo site_url('master-data/master-satker-export-excel') ?>">
                            <i class="fas fa-download"></i> Unduh Data
                        </a>
                    </div>
                </div>
                <hr/>
                <div class="p-3">
                    <p>Unggah kembali file yang sudah anda kelola</p>
                    <form 
                        action="<?php echo base_url('master-data/master-satker-import-excel') ?>" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        class="dropzone"
                        id='my-dropzone'
                    >
                        <?= csrf_field() ?>
                    </form>
                    <div class="upload-status">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Kelola Data Excel -->

<!-- end:: Content -->
<?= $this->endSection() ?>




<?= $this->section('footer_js') ?>

<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable()
    })
    Dropzone.options.myDropzone = {
        init: function() {
            this.on("success", (file, response) => {
                $('.upload-status').find('.uploading').remove()
                $('.upload-status').prepend('<div>235 Data Terupload</div>');
            });

            this.on("processing", (file, response) => {
                $(".upload-status").prepend('<div class="uploading"><i class="fas fa-spinner fa-spin"></i> Mengunggah File</div>')
            });
        }
    };
</script>

<?= $this->endSection() ?>