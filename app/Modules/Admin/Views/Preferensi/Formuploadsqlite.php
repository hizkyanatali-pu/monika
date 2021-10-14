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
                <?= $title; ?>
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <?php if (!empty(session()->getFlashdata('error'))) : ?>
            <div class="alert alert-danger" role="alert">
                <h4>Error !</h4>
                </hr />
                <?php echo session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        
        <div class="kt-portlet__body">
            <!--begin::Section-->
            <div class="kt-section">

                
                <div id="body-form">
                    <label for="">Tambah DB SQLITE</label>
                    <div id="filelist">Browser Anda tidak memiliki dukungan Flash, Silverlight, atau HTML5.</div>
                    <div id="container-form">
                        <div class="form-group">
                            <a id="uploadFile" name="uploadFile" href="javascript:;" class="btn btn-sm btn-primary"> <i class="flaticon2-plus"></i>Pilih File</a>
                            <a id="upload" href="javascript:;" class="btn btn-success btn-sm"><i class="flaticon-upload"></i>Upload file</a>
                        </div>
                    </div>
                    <input type="hidden" id="file_ext" name="file_ext" value="<?= substr(md5(rand(10, 100)), 0, 10) ?>">
                    <div id="console"></div>
                </div>

                <hr class="mt-0">

                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu / File</th>
                            <th>Status</th>
                            <th style="display: none;">idpull</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <!-- <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Waktu / File</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($qdata) : ?>
                                <?php
                                foreach ($qdata as $k => $d) : ?>
                                    <tr>
                                        <td>
                                            <?php echo "[" . date_indo($d['in_dt'])  . '  size: ' . number_format($d['sizefile'] / 1000000, '2', ',', '.') . " Mb ]"; ?>
                                        </td>
                                        <td>
                                            <a href="<?= site_url("preferensi/showdbsqlite/" . encrypt_url($d['nmfile'])) ?>" class="btn btn-brand btn-elevate btn-icon" target="_blank" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Buka Database"> <i class="flaticon-eye"></i></a>

                                            <?php
                                            if ($d['status_aktif'] == '0') { ?>
                                                <button type="button" class="btn btn-success btn-elevate btn-icon usedatabase" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Gunakan Database" data-id="<?= $d['nmfile'] ?>"><i class="flaticon-refresh"></i></button>
                                            <?php } else { ?>
                                                <button class="btn btn-sm" disabled style="cursor: unset;">Aktif</button>
                                            <?php  } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div> -->

            </div>
            <!--end::Section-->
        </div>
        <!--end::Form-->
    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script type="text/javascript">
    BASE_URL = "<?php echo base_url(); ?>";

    Dtable = $('#table').DataTable({
        processing: true,
        serverSide: true,
        order: [
            [3, 'desc']
        ],
        ajax: "<?= site_url('preferensi/DTlistdb') ?>",
        columns: [{
                data: 'no'
            }, {
                data: 'waktu',
                orderable: false
            },
            {
                data: 'status',
                orderable: false
            },
            {
                data: 'idpull',
                orderable: false,
                visible: false
            },
        ],
        createdRow: function(row, data, dataIndex) {
            //console.log(data);
            // $(row).find('td:eq(2) button').attr('title', data["original-title"]);
            // $(row).find('td:eq(2) button').attr('data-toggle', "kt-tooltip");
            $(row).find('td:eq(2) button').attr('data-trigger', "hover");
            $(row).find('td:eq(2) a').attr('data-trigger', "hover");
            $(row).find('[data-toggle="kt-tooltip"]').tooltip();

        }
    });



    $('#table').on('length.dt', function(e, settings, len) {

        $('[data-toggle="kt-tooltip"]').tooltip('hide');
    });
</script>
<?php echo script_tag('js/plupload/plupload.full.min.js'); ?>



<script>
    function gunakandatabase(id) {
        // e.preventDefault();

        var dataid = id;

        swal.fire({
            title: 'Anda akan melanjutkan ke proses ini ?',
            text: "Anda akan beralih menggunakan database ini, semua proses yang berjalan disistem akan dialihkan ke menu login",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan !',
            cancelButtonText: 'Tidak, Batal !',
            allowOutsideClick: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "<?= site_url('preferensi/usedb') ?>",
                    method: "POST",
                    data: {
                        param: dataid,
                        csrf_test_name: "<?= csrf_hash(); ?>"
                    },
                    success: function() {

                        window.location.reload();

                    }
                })
            }
        });
    };
</script>

<script>
    //4pl0@d
    var namefile = "<?= date('Ymd-His') . "_iemonMonika.db" ?>";
    var datafile = new plupload.Uploader({
        runtimes: "html5,flash,silverlight,html4",
        browse_button: "uploadFile", // you can pass in id...
        container: document.getElementById("container-form"), // ... or DOM Element itself
        chunk_size: "10mb",
        url: BASE_URL + "/preferensi/uploadsqlitenew",
        max_file_count: 5000,
        headers: {
            "x-csrf-token": "<?= csrf_hash(); ?>"
        },
        multipart_params: {
            "namafile": namefile
        },
        //ADD FILE FILTERS HERE
        filters: {
            mime_types: [{
                title: "Db",
                extensions: "db"
            }],
        },
        // Flash settings
        flash_swf_url: "plupload/Moxie.swf",

        // Silverlight settings
        silverlight_xap_url: "plupload/Moxie.xap",
        init: {
            PostInit: function() {
                document.getElementById("filelist").innerHTML = "";
                document.getElementById("upload").onclick = function() {
                    datafile.start();
                    return false;
                };
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    document.getElementById("filelist").innerHTML = "";
                    document.getElementById("filelist").innerHTML +=
                        '<div id="' +
                        file.id +
                        '">' +
                        file.name +
                        " (" +
                        plupload.formatSize(file.size) +
                        ") <b></b></div>";
                });
            },
            UploadProgress: function(up, file) {
                document.getElementById(file.id).getElementsByTagName("b")[0].innerHTML =
                    "<span>" + file.percent + "%</span>";
            },

            Error: function(up, err) {
                document.getElementById("console").innerHTML +=
                    "\n Error #" + err.code + ": " + err.message;
            },

            FileUploaded: function(up, file, response) {

                swal.fire({
                    title: "Success !",
                    text: "Database berhasil Diupload ! Anda akan menggunakan Database ini !",
                    type: "success",
                    confirmButtonText: 'Konfirmasi !',
                    allowOutsideClick: false
                }).then(function(result) {
                    if (result.value) {
                        gunakandatabase(namefile)
                    }
                });

                Dtable.ajax.reload();

            }
        },
    });

    datafile.init();
</script>

<?= $this->endSection() ?>