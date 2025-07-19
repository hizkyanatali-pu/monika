<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                 <?= $title; ?> </h3>
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
                            <a href="<?= site_url('usergroups'); ?>" class="btn btn-brand btn-elevate btn-icon-sm">
                               Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="<?= route_to('usergroups/store'); ?>" method="POST">
                <div class="kt-portlet__body">
                    <!--begin::Section-->
                    <div class="kt-section">
                        <div class="kt-section__info">
                        
                        </div>
                        <div class="kt-section__content">
                                <?= view('admin/partials/notifications') ?>
                                <?= csrf_field() ?>
                                <div class="form-group row">
                                    <label for="nama-input" class="col-3 col-form-label">Nama*</label>
                                    <div class="col-6">
                                        <input name="nama" class="form-control" type="text" value="<?= old('nama') ?>" id="nama-input" require>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keterangan-input" class="col-3 col-form-label">Keterangan*</label>
                                    <div class="col-6">
                                        <input name="keterangan" class="form-control" type="text" value="<?= old('keterangan') ?>" id="keterangan-input" require>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="aktif-input" class="col-3 col-form-label">Aktif *</label>
                                    <div class="col-6">
                                        <select name="aktif" class="form-control" id="aktif-input">
                                            <option>--Pilih--</option>
                                            <option value="1" <?= (old('aktif')==='1') ?'selected="selected"':'';?>>Aktif</option>
                                            <option value="0" <?= (old('aktif')==='0') ?'selected="selected"':'';?>>Tidak Aktif</option>
                                        </select>
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
    <script>
        console.log('additional footer js')
    </script>
<?= $this->endSection() ?>