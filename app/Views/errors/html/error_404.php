<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>404 Page Not Found</title>
	<link href="/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/css/error-1.css" rel="stylesheet" type="text/css" />

	<!--begin::Layout Skins(used by all pages) -->
	<link href="/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
	
    <!--end::Layout Skins -->
</head>
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v1" style="background-image: url(/images/bg1.jpg);">
				<div class="kt-error-v1__container">
					<h1 class="kt-error-v1__number">404</h1>
					<p class="kt-error-v1__desc">
					<?php if (! empty($message) && $message !== '(null)') : ?>
						<?= esc($message) ?>
					<?php else : ?>
						Sorry! Cannot seem to find the page you were looking for.
					<?php endif ?>
					</p>
				</div>
			</div>
		</div>

		<!-- end:: Page -->

		<!-- begin::Global Config(global config for global JS sciprts) -->
	
</body>
</html>
