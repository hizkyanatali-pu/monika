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
    .card{
        border: none;
    }
    .icon-group{
        padding: 0 30px;
        text-align: center;
        cursor: pointer;
    }
    .icon-group .icon:hover{
        background-color: #6b7ae8;
        transition: .5s;
    }
    .icon-group .icon{
        background-color: #f3cb3a;
        color: white;
        padding: 20px 27px 25px 27px;
        border-radius: 50%;
        padding-top: 35px;
    }
    .icon-group p{
        margin-top: 40px ;
        font-weight: 600;
    }
    .icon-group2 div{
        padding: 30px 30px;
        margin: 20px;
        background-color: #6b7ae8;
        text-align: center;
        border-radius: 20px;
    }
    .icon-group2 div .icon{
        color: white;
        padding: 20px;
        border-radius: 50%;
        padding-top: 35px;
    }
    .icon-group2 div p{
        margin-top: 20px !important;
        font-weight: 600;
        color: white;
        margin:0;
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
                <h3 class="mb-4">Dashboard</h3>
                <div class="card py-5 px-4">
                    <div class="row icon-row">
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-file-invoice-dollar fa-2x"></i>
                            </span>
                            <p>Progres Fisik & Keuangan Kementrian PUPR</p>
                        </div>
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </span>
                            <p>Progres Program Padat Karya Perkegiatan</p>
                        </div>
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-file-invoice-dollar fa-2x"></i>
                            </span>
                            <p>Progres Kegiatan Tematik Direktorat Jendral Sumber Daya Air T.A.2023</p>
                        </div>
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-box fa-2x"></i>
                            </span>
                            <p>Postur Paket Kontraktual T.A. 2023</p>
                        </div>
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-file-invoice-dollar fa-2x"></i>
                            </span>
                            <p>Postur Paket Belum Lelang TA 2023</p>
                        </div>
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-file-invoice-dollar fa-2x"></i>
                            </span>
                            <p>Daftar Paket Belum Lelang RPM - SYC per Kegiatan</p>
                        </div>
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-calculator fa-2x"></i>
                            </span>
                            <p>Daftar Paket Belum Lelang MYC per Kegiatan</p>
                        </div>
                        <div class="col-sm-4 col-xl-3 icon-group" data-toggle="modal" data-target="#dashboardModal">
                            <br>
                            <span class="icon">
                                <i class="fas fa-calculator fa-2x"></i>
                            </span>
                            <p>Paket Belum Lelang PHLN - MYC PROJECT LOAN</p>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <h3 class="mb-4">Rencana Tender</h3>
                    <div class="container">
                        <canvas id="planChart"></canvas>
                    </div>
                </div>
                <div class="card mt-5">
                    <h3>Progres Keuangan & Fisik</h3>
                    <div class="row icon-row2">
                        <div class="col-sm-6 col-xl-3 icon-group2">
                            <div>
                                <span class="icon">
                                    <i class="fas fa-water fa-4x"></i>
                                </span>
                                <p>DITJEN SDA</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3 icon-group2">
                            <div>
                                <span class="icon">
                                    <i class="fas fa-water fa-4x"></i>
                                </span>
                                <p>PER KEGIATAN</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3 icon-group2">
                            <div>
                                <span class="icon">
                                    <i class="fas fa-water fa-4x"></i>
                                </span>
                                <p>BBWS</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3 icon-group2">
                            <div>
                                <span class="icon">
                                    <i class="fas fa-desktop fa-4x"></i>
                                </span>
                                <p>BWS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Section-->
        </div>
        <!--end::Form-->

        <!-- Modal -->
        <div class="modal fade" id="dashboardModal" tabindex="-1" aria-labelledby="dashboardModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dashboardModalLabel">Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered rounded mb-5">
                            <thead>
                                <tr>
                                    <th>Head</th>
                                    <th>Head</th>
                                    <th>Head</th>
                                    <th>Head</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                </tr>
                                <tr>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                </tr>
                                <tr>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                </tr>
                                <tr>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                </tr>
                                <tr>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                    <td>Value</td>
                                </tr>
                            </tbody>
                        </table>
                        <canvas id="detailChart"></canvas>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <script>
        const ctx = document.getElementById('planChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    customCanvasBackgroundColor: {
                        color: 'blue',
                    }
                },
                chartArea: {
                    backgroundColor: 'blue'
                }
            }
        });

        const ctx2 = document.getElementById('detailChart');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    customCanvasBackgroundColor: {
                        color: 'blue',
                    }
                },
                chartArea: {
                    backgroundColor: 'blue'
                }
            }
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
        max_file_count: 5,
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
        flash_swf_url: BASE_URL + "/js/plupload/Moxie.swf",

        // Silverlight settings
        silverlight_xap_url: BASE_URL + "/js/plupload/Moxie.xap",
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