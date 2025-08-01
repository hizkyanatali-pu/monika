<?php
$title = $title ?? '';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PU SDA-Monika - <?= $title; ?></title>
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

    <?php echo link_tag('css/style.bundle.min.css'); ?>

    <?php echo link_tag('css/styles.css'); ?>

    <!--begin::Layout Skins(used by all pages) -->

    <?php echo link_tag('css/skins/header/base/light.css'); ?>

    <?php echo link_tag('css/skins/header/menu/light.css'); ?>

    <?php echo link_tag('css/skins/brand/light.css'); ?>

    <?php echo link_tag('css/skins/aside/light.css'); ?>
    <!--end::Layout Skins -->

    <style>
        .tableFixHead          { overflow-y: auto; height: 600px; }
        .tableFixHead thead th { position: sticky; top: 0; border:1px solid #333;}

        /* Just common table stuff. Really. */
        .table  { border-collapse: collapse; width: 100%; }
        /*th, td { padding: 8px 16px; }*/
        .table th     { background:#f5f5f5; }
    </style>
</head>

<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize">
<!-- <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"> -->
    <!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="index.html">
                <img alt="Logo" src="<?= base_url('images/pu.png'); ?>" />
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
            <!-- <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button> -->
            <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
        </div>
    </div>

<!-- end:: Header Mobile -->
    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

            <!-- begin:: Aside -->
            <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
                <?php echo $this->include('admin/partials/sidebar') ?>
            </div>
            <!-- end:: Aside -->

            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                <!-- begin:: Header -->
                <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">
                    <?php echo $this->include('admin/partials/header') ?>
                </div>
                <!-- end:: Header -->

                <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                    <?php echo $this->renderSection('content') ?>
                </div>

                <!-- begin:: Footer -->
                <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop p-0" id="kt_footer">
                    <?php echo $this->include('admin/partials/footer') ?>
                </div>
                <!-- end:: Footer -->
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

    <?php echo script_tag('plugins/flot/jquery.flot.js'); ?>
    <?php echo script_tag('plugins/flot-old/jquery.flot.resize.min.js'); ?>
    <?php echo script_tag('plugins/flot-old/jquery.flot.pie.min.js'); ?>

    <?php echo $this->renderSection('footer_js') ?>
</body>

</html>