<?= $this->extend('admin/layouts/auth') ?>
<?= $this->section('content') ?>

<div class="">

	<div class="bg-login">
		<img src="<?= base_url('images/background-login.png'); ?>" alt="">
	</div>

	<div class="form-login">
		<div class="form-group text-center text-dark">
			<img src="<?= base_url('images/logo_pu.jpg') ?>" width="10%" class="mb-2">
			<h4 class="mb-0">DIREKTORAT JENDERAL SUMBER DAYA AIR</h4>
			<h6 class="mb-0">Kementerian Pekerjaan Umum dan Perumahan Rakyat</h6>
		</div>

		<div class="card shadow card-login">
			<div class="card-body p-4">

				<div class="mb-4 text-center">
					<h1 class="mb-0 text-green">MONIKA</h1>
					<h5 class="mb-0 text-green">MONITORING KEGIATAN SDA</h5>
				</div>

				<?php if (session()->has('error')) : ?>
					<div class="alert alert-danger error">
						<?= session('error') ?>
					</div>
				<?php endif ?>

				<div class="card card-input-login">
					<div class="card-body p-5">
						<form class="kt-form" action="<?= site_url('auth'); ?>" method="POST">
							<div class="form-group <?php if (session('errors.idpengguna')) echo 'is-invalid'; ?>">
								<div class="input-group">
									<div class="input-group-text bg-green mr-3">
										<i class="text-white fas fa-user"></i>
									</div>
									<input style="border:thin solid #c5c5c5;" class="form-control" type="text" placeholder="ID Pengguna" name="idpengguna" autocomplete="off" value="">
								</div>
								<div id="idpengguna-error" class="error invalid-feedback"><?= session('errors.idpengguna') ?></div>
							</div>
							<div class="form-group <?php if (session('errors.sandi')) echo 'is-invalid'; ?>">
								<div class="input-group">
									<div class="input-group-text bg-green mr-3">
										<i class="text-white fas fa-lock"></i>
									</div>
									<input style="border:thin solid #c5c5c5;" class="form-control form-control-last" type="password" placeholder="Kata Sandi" name="sandi" value="">
								</div>
								<div id="idpengguna-error" class="error invalid-feedback"><?= session('errors.sandi') ?></div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-text bg-green mr-3">
										<i class="text-white fas fa-calendar"></i>
									</div>
									<select class="form-control" name="tahun">
										<?php for ($date = 2020; $date <= date("Y")+1; $date++) {
										?>
											<option value="<?= $date ?>" <?=($date == date("Y"))?'selected':'';?>><?= $date ?></option>
										<?php } ?>
									</select>
								</div>
								<div id="idpengguna-error" class="error invalid-feedback"><?= session('errors.sandi') ?></div>
							</div>
							<?= csrf_field() ?>

							<div class="text-center">
								<button id="kt_login_signin_submit" class="btn btn-green btn-login"> Masuk </button>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>

</div>

<!-- <div class="kt-grid kt-grid--ver kt-grid--root">
	<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
			<div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside" style="background-color:rgb(0,0,0,0.2);">
				<div class="kt-login__wrapper">
					<div class="kt-login__container">
						<div class="kt-login__body">
							<div class="kt-login__logo">
								<a href="#">
									<img src="<?= base_url('images/logo_pu.jpg') ?>" style="max-width:100px">
								</a>
							</div>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h4>DIREKTORAT JENDERAL SUMBER DAYA AIR</h4>
									<h5>Kementerian Pekerjaan Umum dan Perumahan Rakyat</h5>
								</div>


								<div class="kt-login__form">
									<?php if (session()->has('error')) : ?>
										<div class="alert alert-danger error">
											<?= session('error') ?>
										</div>
									<?php endif ?>
									<form class="kt-form" action="<?= site_url('auth'); ?>" method="POST">

										<div class="form-group <?php if (session('errors.idpengguna')) echo 'is-invalid'; ?>">
											<input style="border:thin solid #c5c5c5;" class="form-control text-center" type="text" placeholder="ID Pengguna" name="idpengguna" autocomplete="off" value="">
											<div id="idpengguna-error" class="error invalid-feedback"><?= session('errors.idpengguna') ?></div>
										</div>
										<div class="form-group <?php if (session('errors.sandi')) echo 'is-invalid'; ?>">
											<input style="border:thin solid #c5c5c5;" class="form-control form-control-last text-center" type="password" placeholder="Kata Sandi" name="sandi" value="">
											<div id="idpengguna-error" class="error invalid-feedback"><?= session('errors.sandi') ?></div>
										</div>
										<div class="kt-login__extra">
											<label class="kt-checkbox">
												<input type="checkbox" name="remember"> Remember me
												<span></span>
											</label>

										</div>
										<?= csrf_field() ?>

										<div class="kt-login__actions">
											<button id="kt_login_signin_submit" class="btn btn-primary btn-pill btn-elevate">Masuk</button>
										</div>
									</form>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
			<div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content" style="background-image: url('<?= base_url('images/bg2.jpg'); ?>');">
				<div class="kt-login__section">
					<div class="kt-login__block" style="background-color:rgb(0,0,0,0.5); padding:20px;">
						<h3 class="kt-login__title">Monika</h3>
						<div class="kt-login__desc">
							(Monitoring Kegiatan SDA)
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->

<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>


<?= $this->endSection() ?>