<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --s: calc(200px / 25);
        --b: calc(200px / 20);
    }

    .loader {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        transform: rotate(175deg);
        position: relative;
    }

    .loader span {
        border: var(--s) solid #e7e7e7;
        border-top: var(--s) solid hsl(calc(21 * var(--i)), 89%, 50%);
        border-left: var(--s) solid hsl(calc(21 * var(--i)), 89%, 50%);
        border-radius: 50%;
        position: absolute;
        top: calc(var(--b) * var(--i));
        bottom: calc(var(--b) * var(--i));
        left: calc(var(--b) * var(--i));
        right: calc(var(--b) * var(--i));
        animation: animate 1000ms alternate ease-in-out infinite;
        animation-delay: calc(-0.1s * var(--i));
    }

    .loader span:nth-child(1) {
        --i: 0;
    }

    .loader span:nth-child(2) {
        --i: 1;
    }

    .loader span:nth-child(3) {
        --i: 2;
    }

    .loader span:nth-child(4) {
        --i: 3;
    }

    .loader span:nth-child(5) {
        --i: 4;
    }

    .loader span:nth-child(6) {
        --i: 5;
    }

    .loader span:nth-child(7) {
        --i: 6;
    }

    .loader span:nth-child(8) {
        --i: 7;
    }

    .loader span:nth-child(9) {
        --i: 8;
    }

    .loader span:nth-child(10) {
        --i: 9;
    }

    @keyframes animate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(90deg);
        }
    }
</style>




<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Get Data From API Emon</h3>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->

<!-- pesan -->

<!-- end pesan -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <p>Sinkronisasi Terakhir Dilakukan Pada </p>
    <button class="btn btn-success" id="syc">Syc Data</button>
    <!-- <a href="#" class="btn btn-success" id="syc" data-toggle="modal" data-target="#Modal" data-backdrop="static" data-keyboard="false">Syc Data</a> -->
</div>

<!-- Modal confirm-->
<div id="Modalconfirm" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Informasi
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <!-- dialog body -->
            <div class="modal-body">
                <!-- dialog buttons -->
                <p>Pada Saat Proses Sinkrosisasi Data Silahkan Menunggu Sampai Proses Selesai</p>
                <p>Anda akan melanjutkan ke proses sinkronisasi ?</p>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                <button type="button" class="btn btn-primary" id="ya">Ya</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-->

<!-- Modal-->
<form action="" method="post">
    <div class="modal fade" id="Modalloading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- <div class="modal-content"> -->
            <!-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                </div> -->
            <div class="modal-body">
                <div class="loader">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <!-- </div> -->
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="<?= site_url('getdatafromdb') ?>" class="btn btn-success" id="syc" data-toggle="modal" data-target="#Modal">Syc Data</a>
                </div> -->
            </div>
        </div>
    </div>
</form>
<!-- End Modal-->

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>


<script>
    $(document).ready(function() {
        $("#ya").on("click", function(e) {
            e.preventDefault();
            $("#Modalconfirm").modal('hide');
            $("#Modalloading").modal({
                keyboard: false,
                backdrop: 'static'
            });

            $.get("<?= site_url('preferensi/getdatafromdb') ?>", function(data) {
                $("#Modalloading").modal('hide');

                Swal.fire(
                    'Data Berhasil Ditarik !',
                    'Total Data : ' + data,
                    'success'
                )

            });


        });

        $("#syc").on("click", function(e) {
            e.preventDefault();
            $("#Modalconfirm").modal({
                keyboard: false,
                backdrop: 'static'
            });
        });
    });

    window.setTimeout(function() {
        $(".alert-success").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 3000);

    //disabled inspect element

    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    // document.onkeydown = function(e) {
    //     if (event.keyCode == 123) {
    //         return false;
    //     }
    //     if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
    //         return false;
    //     }
    //     if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
    //         return false;
    //     }
    //     if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
    //         return false;
    //     }
    //     if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
    //         return false;
    //     }
    // }
</script>
<?= $this->endSection() ?>