<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Profile </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                
            </div>
        
        </div>
    </div>

    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
            <div class="row">
                <div class="col-xl-12">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Personal Information <small>update your personal
                                        informaiton</small></h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-wrapper">
                                    
                                </div>
                            </div>
                        </div>
                        <form class="kt-form kt-form--label-right" action="/account" method="POST">
                        <?= csrf_field() ?>
                            <div class="kt-portlet__body">
                                <div class="kt-section kt-section--first">
                                    <?= view('admin/partials/notifications') ?>
                                    
                                    <div class="kt-section__body">
                                        <!-- <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar">
                                                    <div class="kt-avatar__holder"
                                                        style="background-image: url(/images/default.jpg)"></div>
                                                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title=""
                                                        data-original-title="Change avatar">
                                                        <i class="fa fa-pen"></i>
                                                        <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg">
                                                    </label>
                                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title=""
                                                        data-original-title="Cancel avatar">
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="form-group row <?php if(session('errors.idpengguna')) echo 'is-invalid'; ?>">
                                            <label class="col-xl-3 col-lg-3 col-form-label">ID Pengguna</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input class="form-control" name="idpengguna" type="text" value="<?= $userData['idpengguna']; ?>">
                                                <div id="idpengguna-error" class="error invalid-feedback"><?= session('errors.idpengguna') ?></div>
                                            </div>
                                        </div>
                                        <div class="form-group row <?php if(session('errors.nama')) echo 'is-invalid'; ?>">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Nama</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input class="form-control" name="nama" type="text" value="<?= $userData['nama']; ?>">
                                                <div id="nama-error" class="error invalid-feedback"><?= session('errors.nama') ?></div>
                                            </div>
                                        </div>
                                        <div class="form-group row  <?php if(session('errors.telpon')) echo 'is-invalid'; ?>">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Contact Phone</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                                class="la la-phone"></i></span></div>
                                                    <input type="text" name="telpon" class="form-control" value="<?= $userData['telpon']; ?>"
                                                        placeholder="Phone" aria-describedby="basic-addon1">
                                                    <div id="telpon-error" class="error invalid-feedback"><?= session('errors.telpon') ?></div>
                                                </div>
                                                <span class="form-text text-muted">We'll never share your email with anyone
                                                    else.</span>
                                            </div>
                                        </div>
                                        <div class="form-group row <?php if(session('errors.email')) echo 'is-invalid'; ?>">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                                class="la la-at"></i></span></div>
                                                    <input type="text" name="email" class="form-control" value="<?= $userData['email']; ?>"
                                                        placeholder="Email" aria-describedby="basic-addon1">
                                                    <div id="email-error" class="error invalid-feedback"><?= session('errors.email') ?></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-lg-3 col-xl-3">
                                        </div>
                                        <div class="col-lg-9 col-xl-9">
                                            <button type="submit" class="btn btn-success">Submit</button>&nbsp;
                                            <a href="<?= site_url('dashboard'); ?>" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
    </div>

    <!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
    <script>
        console.log('additional footer js')
    </script>
<?= $this->endSection() ?>
