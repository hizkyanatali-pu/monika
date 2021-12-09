<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet">

        <ul class="nav nav-pills nav-justified mb-0">
            <li class="nav-item mr-0 <?php echo ($current == "paket" ? 'bg-primary' : 'bg-light-primary'); ?>">
                <a class="nav-link" href="<?= site_url('preferensi/tarik-data-emon/paket'); ?>" class=""><span class="<?php echo ($current == "paket" ? 'text-light' : ''); ?>">Paket</span>
                    <!-- <i class="kt-menu__ver-arrow la la-angle-right"></i> -->
                </a>
            </li>
            <li class="nav-item mr-0 <?php echo ($current == "kontrak" ? 'bg-primary' : 'bg-light-primary'); ?>">
                <a class="nav-link" href="<?= site_url('preferensi/tarik-data-emon/kontrak'); ?>" class=""><span class="<?php echo ($current == "kontrak" ? 'text-light' : ''); ?>">Kontrak</span>
                    <!-- <i class="kt-menu__ver-arrow la la-angle-right"></i> -->
                </a>
            </li>
            <li class="nav-item mr-0 <?php echo ($current == "rekap_unor" ? 'bg-primary' : 'bg-light-primary'); ?>">
                <a class="nav-link" href="<?= site_url('preferensi/tarik-data-emon/rekap_unor'); ?>" class=""><span class="<?php echo ($current == "rekap_unor" ? 'text-light' : ''); ?>">Rekap Unor</span>
                    <!-- <i class="kt-menu__ver-arrow la la-angle-right"></i> -->
                </a>
            </li>
            <li class="nav-item mr-0 <?php echo ($current == "paket_register" ? 'bg-primary' : 'bg-light-primary'); ?>">
                <a class="nav-link" href="<?= site_url('preferensi/tarik-data-emon/paket_register'); ?>" class=""><span class="<?php echo ($current == "paket_register" ? 'text-light' : ''); ?>">Paket Register</span>
                    <!-- <i class="kt-menu__ver-arrow la la-angle-right"></i> -->
                </a>
            </li>
        </ul>
        <div class="kt-portlet__head row pt-3">
            <div class="col-md-3">
                <a href="<?= site_url('importdata/pullimport/' . $current) ?>" class="btn btn-brand btn-elevate btn-sm">
                    <i class="la la-plus"></i>
                    Pull data
                </a>
            </div>
        </div>

        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">

                <div class="kt-section__content">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Waktu / File</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($qdata) : ?>
                                    <?php
                                    foreach ($qdata as $k => $d) : ?>
                                        <tr>
                                            <td>
                                                <?php echo "Txt : " . '<a href="' . site_url("importdata/unduh/" . $d['idpull'] . "/txt") . '">Unduh</a> [ ' . $d['in_dt'] . '  size: ' . number_format($d['sizefile'] / 1000000, '2', ',', '.') . " Mb ]"; ?>
                                                <?php echo '<br />' . ($d['sqlfile_size'] != NULL ? "Sql : " . '<a href="' . site_url("importdata/unduh/" . $d['idpull'] . "/sql") . '">Unduh</a> [ ' . $d['sqlfile_dt'] . '  size: ' . number_format($d['sqlfile_size'] / 1000000, '2', ',', '.') . " Mb ]" : ''); ?>
                                            </td>
                                            <td>
                                                <?PHP
                                                $st = " Text " . '<i class="fa fa-angle-double-right"></i> ';
                                                $st .= ($d['st'] == 0 ? '<a href="' . site_url("importdata/imdata/" . $d['idpull']) . '" class="btn btn-brand btn-elevate btn-sm">SQL</a>' : ' <span class="text-">SQL</span> ') . ' <i class="fa fa-angle-double-right"></i> ';
                                                $st .= ($d['st'] == 1 ? '<a href="' . site_url("importdata/imdata/" . $d['idpull']) . '" class="btn btn-brand btn-elevate btn-sm">DB</a>'  : " DB ") . ' <i class="fa fa-angle-double-right"></i> ';
                                                $st .= ($d['st'] == 2 ? '<a href="' . site_url("importdata/imdata/" . $d['idpull']) . '" class="btn btn-brand btn-elevate btn-sm">Aktif</a>'  : " Aktif ");
                                                echo $st;
                                                ?>
                                            </td>
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
<?= $this->endSection() ?>