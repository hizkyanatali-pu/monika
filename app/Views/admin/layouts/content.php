<?php
$title = $title ?? '';
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - <?= $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <!--end::Fonts -->
    <?php echo link_tag('plugins/flaticon/flaticon.css'); ?>

    <?php echo link_tag('plugins/flaticon2/flaticon.css'); ?>

    <?php echo link_tag('plugins/line-awesome/css/line-awesome.css'); ?>

    <?php echo link_tag('plugins/@fortawesome/fontawesome-free/css/all.min.css'); ?>

    <?php echo link_tag('plugins/perfect-scrollbar/css/perfect-scrollbar.css'); ?>

    <?php echo link_tag('plugins/sweetalert2/dist/sweetalert2.css'); ?>

    <?php echo link_tag('css/style.bundle.min.css??_=' . uniqid()); ?>

    <?php echo link_tag('css/styles.css??_=' . uniqid()); ?>

    <!--begin::Layout Skins(used by all pages) -->

    <?php echo link_tag('css/skins/header/base/light.css'); ?>

    <?php echo link_tag('css/skins/header/menu/light.css'); ?>

    <?php echo link_tag('css/skins/brand/light.css'); ?>

    <?php echo link_tag('css/skins/aside/light.css'); ?>
    <!--end::Layout Skins -->
</head>

<body class="">
    <!-- end:: Header Mobile -->
    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

                <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                    <?php echo $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>
    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>

    <!-- end::Scrolltop -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#ffb822",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#ffb822",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": [
                        "#c5cbe3",
                        "#a1a8c3",
                        "#3d4465",
                        "#3e4466"
                    ],
                    "shape": [
                        "#f0f3ff",
                        "#d9dffa",
                        "#afb4d4",
                        "#646c9a"
                    ]
                }
            }
        };
    </script>

    <?php echo script_tag('js/jquery.js'); ?>

    <?php echo script_tag('plugins/popper.js/dist/umd/popper.js'); ?>

    <?php echo script_tag('plugins/bootstrap/dist/js/bootstrap.min.js'); ?>

    <?php echo script_tag('plugins/perfect-scrollbar/dist/perfect-scrollbar.js'); ?>

    <?php echo script_tag('plugins/sticky-js/dist/sticky.min.js'); ?>

    <?php echo script_tag('plugins/js-cookie/src/js.cookie.js'); ?>

    <?php echo script_tag('plugins/sweetalert2/dist/sweetalert2.min.js'); ?>

    <?php echo script_tag('js/scripts.bundle.js'); ?>

    <?php echo $this->renderSection('footer_js') ?>
</body>

</html>