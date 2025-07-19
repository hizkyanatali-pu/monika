<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Users Management </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                
            </div>
           
        </div>
    </div>

    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        <?= $title; ?>
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            
                            &nbsp;
                            <a href="<?= site_url('users'); ?>" class="btn btn-brand btn-elevate btn-icon-sm">
                                
                               Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="<?= route_to('users/updatechangepassword'); ?>" method="POST">
                <div class="kt-portlet__body">
                    <!--begin::Section-->
                    <div class="kt-section">
                        <div class="kt-section__info">
                        
                        </div>
                        <div class="kt-section__content">
                                <?= view('admin/partials/notifications') ?>
                                
                                <?= csrf_field() ?>
                                <input name="uid" class="form-control" type="hidden" value="<?= $user['uid']; ?>">
                                
                                <div class="form-group row">
                                    <label for="sandi-input" class="col-3 col-form-label">Sandi </label>
                                    <div class="col-6">
                                        <input name="sandi" class="form-control" type="password" value="" id="sandi-input">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sandi_konfirm-input" class="col-3 col-form-label">Sandi Konfirm</label>
                                    <div class="col-6">
                                        <input name="sandi_konfirm" class="form-control" type="password" value="" id="sandi_konfirm-input">
                                    </div>
                                </div>
                               
                        </div>
                        
                    </div>

                    <!--end::Section-->
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-2">
                            </div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="<?= route_to('users');?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>

    <!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
  
<?= $this->endSection() ?>