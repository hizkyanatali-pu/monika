123s<?= $this->extend('admin/layouts/default') ?>
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
                Master KRO <?php echo $tahunAnggaran ?>
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
                            <th>Kode kegiatan</th>
                            <th>Kode Output</th>
                            <th style="width: 400px !important">Nama</th>
                            <th>Satuan</th>
                            <th>Kode Sum</th>
                            <th>Tahun Awal</th>
                            <th>Tahun Akhir</th>
                            <th>Kode Multi</th>
                            <th>Kode Jenis Suboutput</th>
                            <th>Kode IKK</th>
                            <th>Kode Tema</th>
                            <th>Kode PN</th>
                            <th>Kode PP</th>
                            <th>Kode KP</th>
                            <th>Kode Proy</th>
                            <th>Kode Nawacita</th>
                            <th>Kode Janpres</th>
                            <th>Kode Cttout</th>
                            <th>Kode Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = 1;
                            foreach ($mainData as $key => $data) : 
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $data->kdgiat ?></td>
                                <td><?php echo $data->kdoutput ?></td>
                                <td width="400px"><?php echo $data->nmoutput ?></td>
                                <td><?php echo $data->sat ?></td>
                                <td><?php echo $data->kdsum ?></td>
                                <td><?php echo $data->thnawal ?></td>
                                <td><?php echo $data->thnakhir ?></td>
                                <td><?php echo $data->kdmulti ?></td>
                                <td><?php echo $data->kdjnsout ?></td>
                                <td><?php echo $data->kdikk ?></td>
                                <td><?php echo $data->kdtema ?></td>
                                <td><?php echo $data->kdpn ?></td>
                                <td><?php echo $data->kdpp ?></td>
                                <td><?php echo $data->kdkp ?></td>
                                <td><?php echo $data->kdproy ?></td>
                                <td><?php echo $data->kdnawacita ?></td>
                                <td><?php echo $data->kdjanpres ?></td>
                                <td><?php echo $data->kdcttout ?></td>
                                <td><?php echo $data->kdunit ?></td>
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
                        <a class="btn btn-default" href="<?php echo site_url('master-data/master-kro-export-excel') ?>">
                            <i class="fas fa-download"></i> Unduh Data
                        </a>
                    </div>
                </div>
                <hr/>
                <div class="p-3">
                    <p>Unggah kembali file yang sudah anda kelola</p>
                    <form 
                        action="<?php echo base_url('master-data/master-kro-import-excel') ?>" 
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
        $('#table').DataTable({
            scrollX: true,
        })
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