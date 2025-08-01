<!-- begin:: Header Menu -->


<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>

<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
	<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
		<ul class="kt-menu__nav ">
			<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle" style="cursor: none;"><span class="kt-menu__link-text">Selamat datang di Aplikasi Monika - <b> &nbsp; Tahun <?= session('userData.tahun') ?> </b></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
				<div class="kt-menu__submenu  kt-menu__submenu--fixed kt-menu__submenu--left" style="width:1000px">
				</div>
			</li>

		</ul>
	</div>

</div>

<!-- end:: Header Menu -->

<!-- begin:: Header Topbar -->
<div class="kt-header__topbar">

	<!--begin: User Bar -->
	<div class="kt-header__topbar-item kt-header__topbar-item--user">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
			<div class="kt-header__topbar-user">
				<span class="kt-header__topbar-username kt-hidden-mobile"><?= session('userData.nama'); ?></span>
				<!-- <img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" /> -->

				<span class="kt-badge kt-badge--username kt-badge--unified-primary kt-badge--lg kt-badge--rounded kt-badge--bold"><?= substr(session('userData.nama'), 0, 1); ?></span>
			</div>
		</div>
		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl" style="border-radius: 22px;">

			<!--begin: Head -->
			<div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x" style="background-image: url(../images/bg-1.jpg); border-radius: 22px 22px 0 0;">
				<div class="kt-user-card__avatar">
					<!-- <img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" /> -->

					<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
					<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-light"><?= substr(session('userData.nama'), 0, 1); ?></span>
				</div>
				<div class="kt-user-card__name text-light">
					<?= session('userData.nama'); ?>
				</div>
			</div>

			<!--end: Head -->

			<!--begin: Navigation -->
			<div class="kt-notification">
				<a href="/account" class="kt-notification__item">
					<div class="kt-notification__item-icon">
						<i class="flaticon2-calendar-3 kt-font-primary"></i>
					</div>
					<div class="kt-notification__item-details">
						<div class="kt-notification__item-title kt-font-bold">
							Profil
						</div>
					</div>
				</a>
				<a href="/change-password" class="kt-notification__item">
					<div class="kt-notification__item-icon">
						<i class="flaticon2-lock kt-font-primary"></i>
					</div>
					<div class="kt-notification__item-details">
						<div class="kt-notification__item-title kt-font-bold">
							Ganti Password
						</div>
					</div>
				</a>
				<div class="kt-notification__custom kt-space-between text-right">
					<a href="<?= site_url('logout') ?>" class="btn btn-label btn-label-brand btn-sm"><i class="fas fa-sign-out-alt"></i> Keluar</a>
				</div>
			</div>

			<!--end: Navigation -->
		</div>
	</div>

	<!--end: User Bar -->
</div>

<!-- end:: Header Topbar -->