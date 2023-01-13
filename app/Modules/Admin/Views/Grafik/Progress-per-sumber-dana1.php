<?= $this->extend('admin/layouts/grafik') ?>
<?= $this->section('content') ?>

    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h5 class="kt-subheader__title">
                    <?= $title; ?>
                </h5>
                <span class="kt-subheader__separator kt-hidden"></span>

            </div>

        </div>
    </div>

    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid"">
        <div class="kt-portlet">
            <div class="kt-portlet__body" style="padding:0px;">

                <!--begin::Section-->
                <div class="kt-section">

                    <div class="kt-section__content">
                        <div class="table-responsive tableFixHead">
                            <div class="col-md-12">
                            <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                                <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                    <tr>
                                        <td width="5%">&nbsp;</td>
                                        <td colspan="13">
                                            <div class="card-body">
                                                <div id="line-chart" style="height: 300px;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-12" style="padding:3px; background-color:#a6a6a6; ">Rencana</div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="col-md-12" style="padding:3px; background-color:#0066ff;">Realisasi</div>
                                        </td>
                                    </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
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
    </script>
   
<?= $this->endSection() ?>