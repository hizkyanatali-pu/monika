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
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="padding:0px; margin:0px;">
        <div class="kt-portlet">
            <?php /*
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    <?= $title; ?> List
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            
                            &nbsp;
                            <a href="<?= route_to('usulan/tambah-baru') ?>" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                              Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </div> 
            */?>
            <div class="kt-portlet__body" style="padding:0px;">
                <?php /*
                <div class="kt-form kt-form--label-right kt-margin-b-10">
                    <div class="row align-items-center">
                        <div class="col-xl-12 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    
                                </div>
                                <!-- <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    
                                </div> -->
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">
                                            <label>Urutkan:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control bootstrap-select" onchange="javascript:location.href = this.value;" id="kt_form_type">
                                                <option value="<?= site_url('datapaket'); ?>" <?php if($sort ==='desc') echo 'selected' ?>>Terbaru</option>
                                                <option value="<?= site_url('datapaket?sort=asc'); ?>"  <?php if($sort ==='asc') echo 'selected' ?>>Terlama</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                 */?>

                <!--begin::Section-->
                <div class="kt-section">
                    <?php /*
                    <div class="kt-section__info">
                    <?= view('admin/partials/notifications') ?>
                    </div>
                    */ ?>
                    <div class="kt-section__content">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Satker</th>
                                        <th colspan="7">Pagu</th>
                                        <th colspan="7">Real</th>
                                    </tr>
                                    <tr>
                                        <th>51</th>
                                        <th>52</th>
                                        <th>53</th>
                                        <th>RPM</th>
                                        <th>PHLN</th>
                                        <th>SBSN</th>
                                        <th>Total</th>

                                        
                                        <th>51</th>
                                        <th>52</th>
                                        <th>53</th>
                                        <th>RPM</th>
                                        <th>PHLN</th>
                                        <th>SBSN</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($datapaket): ?>
                                    <?php 
                                    foreach($datapaket as $key => $data): ?>
                                    <tr>
                                        
                                        <td><?php echo $data['kdsatker']; ?> <?php echo $data['satker']; ?></td>

                                        <td><?php echo $data['pagu_51']; ?></td>
                                        <td><?php echo $data['pagu_52']; ?></td>
                                        <td><?php echo $data['pagu_53']; ?></td>
                                        <td><?php echo $data['pagu_rpm']; ?></td>
                                        <td><?php echo $data['pagu_phln']; ?></td>
                                        <td><?php echo $data['pagu_sbsn']; ?></td>
                                        <td><?php echo $data['pagu_total']; ?></td>

                                        <td><?php echo $data['real_51']; ?></td>
                                        <td><?php echo $data['real_52']; ?></td>
                                        <td><?php echo $data['real_53']; ?></td>
                                        <td><?php echo $data['real_rpm']; ?></td>
                                        <td><?php echo $data['real_phln']; ?></td>
                                        <td><?php echo $data['real_sbsn']; ?></td>
                                        <td><?php echo $data['real_total']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= $pager->links() ?>
                    </div>
                </div>

                <!--end::Section-->
            </div>

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