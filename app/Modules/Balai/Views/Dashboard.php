<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<style>
    footer {
        display: none;
    }

    @media print {
        .pagebreak {
            clear: both;
            page-break-after: always;
        }

        @page {
            size: landscape;
            background-color: white;
            margin-top: 0;
            width: 290mm;
            height: 420mm;
        }

        .tableFixHead {
            height: auto !important;
        }

        .table-bordered thead td,
        .table-bordered thead th {
            padding: 8px !important;
        }

        #kt_subheader {
            display: none;
        }

        #kt_scrolltop {
            display: none;
        }

        #kt_header_mobile {

            display: none;
        }

        .kt-header__topbar-item.kt-header__topbar-item--user {
            display: none;
        }

        .kt-grid.kt-grid--hor.kt-grid--root {

            display: none;

        }

        .kt-container.kt-container--fluid.kt-grid__item.kt-grid__item--fluid {
            background-color: white;

        }

        .kt-portlet.kt-portlet--tab {
            color: black;
            font-size: 16px;
            margin-top: 0.9cm;
            font-family: Arial, Helvetica, sans-serif;
            /* background-color: #fff;
            font-family: Arial, Helvetica, sans-serif;
            color: #424849;
            font-size: 12px;
            zoom: 1.5;
            -moz-transform: scale(1.5); */

        }

        #tabletematik {
            width: 100%;
            zoom: 0.7;
            -moz-transform: scale(0.7);
        }

        .kt-footer {
            display: none;
        }

        .footer {
            display: block;
            /* position: absolute; */
            /* bottom: 0px; */
        }
    }
</style>
<!-- begin:: Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Balai
            </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__body" style="height: 65vh">
        </div>
    </div>
</div>

<footer>
    <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
</footer>


<?= $this->endSection() ?>