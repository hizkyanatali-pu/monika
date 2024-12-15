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
                EXPORT DATA TO EXCEL</h3>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->


<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
    <form id="exportForm" action="" method="get">
        <div class="row">
            <div class="col-md-2 d-flex align-items-center">
                <select class="form-control mr-2" name="tahun_anggaran" id="tahunAnggaran">
                    <?php
                    $currentYear = date("Y");
                    for ($i = 2020; $i <= $currentYear; $i++) {
                        $selected = ($i == $currentYear) ? "selected" : "";
                    ?>
                        <option value="<?= $i ?>" <?= $selected ?>><?= $i ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <button type="button" class="btn btn-success" id="exportButton">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
    </form>
</div>





<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>


<script>
    $(document).ready(function() {
        // Ketika tombol Export diklik
        $('#exportButton').click(function() {
            // Ambil nilai tahun dari dropdown
            const tahun = $('#tahunAnggaran').val();

            // Redirect ke URL yang diinginkan
            window.location.href = `/renstra/rekap-renstra/${tahun}`;
        });
    });
</script>
<?= $this->endSection() ?>