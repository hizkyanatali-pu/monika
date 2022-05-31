<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Blokir
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body p-0">

            <!--begin::Section-->
            <div class="kt-section p-0">
                <div class="row mb-3 p-0">
                    <div class="col-md-12">
                        <div class="text-right mt-2 mr-3 mb-3">
                            <strong>* (Rp. Ribu)</strong>
                        </div>
                        <table class="table table-bordered mb-0 table-striped" id="table">
                            <thead>
                                <tr class=" text-center bg-purple">
                                    <th width="100px">Kode</th>
                                    <th width="250px">Kegiatan</th>
                                    <th>RPM</th>
                                    <th>SBSN</th>
                                    <th>PHLN</th>
                                    <th width="300px">TOTAL</th>
                                </tr>
                            </thead>

                            <tbody id="tbody-utama">
                                <?php 
                                    $totalRpm = 0;
                                    $totalSbsn = 0;
                                    $totalPhln = 0;
                                    foreach($dataBlokir as $key => $data) : 
                                        $totalRpm += $data['rpm'];
                                        $totalSbsn += $data['sbsn'];
                                        $totalPhln += $data['phln'];
                                ?>
                                    <tr>
                                        <td><?php echo $data['kdgiat'] ?></td>
                                        <td><?php echo $data['nmgiat'] ?></td>
                                        <td class="text-right">
                                            <?php echo str_replace(',', '.', number_format($data['rpm']/1000)) ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo str_replace(',', '.', number_format($data['sbsn']/1000)) ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo str_replace(',', '.', number_format($data['phln']/1000)) ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo str_replace(',', '.', number_format(($data['rpm'] + $data['sbsn'] + $data['phln'])/1000)) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfooter>
                                <tr>
                                    <th colspan="2">Jumlah</th>
                                    <th class="text-right">
                                        <?php echo str_replace(',', '.', number_format($totalRpm/1000)) ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo str_replace(',', '.', number_format($totalSbsn/1000)) ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo str_replace(',', '.', number_format($totalPhln/1000)) ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo str_replace(',', '.', number_format(($totalRpm+$totalSbsn+$totalPhln)/1000)) ?>
                                    </th>
                                </tr>
                            </tfooter>
                        </table>
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