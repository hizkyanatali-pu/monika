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
            <form action="<?= route_to('users/update'); ?>" method="POST">
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
                                    <label for="nama-input" class="col-3 col-form-label">Nama*</label>
                                    <div class="col-6">
                                        <input name="nama" class="form-control" type="text" value="<?= $user['nama']; ?>" id="nama-input">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="idpengguna-input" class="col-3 col-form-label">Id Pengguna*</label>
                                    <div class="col-6">
                                        <input name="idpengguna" class="form-control" type="text" value="<?= $user['idpengguna']; ?>" id="idpengguna-input">
                                    </div>
                                </div>
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
                                <div class="form-group row">
                                    <label for="email-input" class="col-3 col-form-label">Email </label>
                                    <div class="col-6">
                                        <input name="email" class="form-control" type="email" value="<?= $user['email']; ?>" id="email-input">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="telpon-input" class="col-3 col-form-label">Telpon</label>
                                    <div class="col-6">
                                        <input name="telpon" class="form-control" type="text" value="<?= $user['telpon']; ?>" id="telpon-input">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nip-input" class="col-3 col-form-label">NIP</label>
                                    <div class="col-6">
                                        <input name="nip" class="form-control" type="text" value="<?= $user['nip']; ?>" id="nip-input">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="group-input" class="col-3 col-form-label">Group *</label>
                                    <div class="col-6">
                                        <select name="group_id" class="form-control" id="group-input">
                                            <option>--Pilih--</option>
                                            <?php 
                                                foreach($usergroups as $key => $group): ?>
                                                <option value="<?= $group['group_id']?>" <?= ($user['group_id']===$group['group_id']) ? 'selected="selected"':'';?> ><?= $group['nama']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="aktif-input" class="col-3 col-form-label">Aktif *</label>
                                    <div class="col-6">
                                        <select name="aktif" class="form-control" id="aktif-input">
                                            <option>--Pilih--</option>
                                            <option value="1" <?= ($user['aktif']==='1') ? 'selected="selected"':'';?> >Aktif</option>
                                            <option value="0" <?= ($user['aktif']==='0') ? 'selected="selected"':'';?> >Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-3 col-form-label">Balai *</label>
                                    <div class="col-6">
                                        <select name="balaiid"  class="form-control">
                                        <option>--Pilih--</option>
                                        <?php 
                                                foreach($balai as $key => $mbalai): ?>
                                                <option value="<?= $mbalai['balaiid']?>" <?= ($user['balaiid']===$mbalai['balaiid']) ? 'selected="selected"':'';?> >
                                                    <?= $mbalai['balai']?>
                                                </option>
                                            <?php endforeach; ?>
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-3 col-form-label">Satker</label>
                                    <div class="col-6">
                                        <select name="satkerid" class="form-control">
                                            <option value="">--Pilih--</option>
                                            <?php foreach($satker as $keySatker => $valueSatker): ?>
                                                <option 
                                                    class="
                                                        <?php if ($user['balaiid'] != $valueSatker['balaiid']) echo 'd-none' ?>
                                                        _option-select-satker
                                                    " 
                                                    data-balai-id="<?= $valueSatker['balaiid'] ?>"
                                                    value="<?= $valueSatker['satkerid']?>"
                                                    <?php if ($user['satkerid'] == $valueSatker['satkerid']) : ?> selected="selected" <?php endif; ?>
                                                >
                                                    <?= $valueSatker['satker']?>
                                                </option>
                                            <?php endforeach; ?>
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



        $(document).on('change', 'select[name=balaiid]', function() {
            $('._option-select-satker').addClass('d-none')
            $('._option-select-satker[data-balai-id='+$(this).val()+']').removeClass('d-none')
        })
    </script>
<?= $this->endSection() ?>