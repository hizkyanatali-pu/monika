<?= $this->extend('admin/layouts/default') ?>



<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .paginate_button.page-item {
        padding: 0px !important;
    }

    .paginate_button.page-item:hover {
        background-color: #FFF !important;
    }

    ._remove-row-item {
        width: 20px;
        height: 20px;
        position: absolute;
        right: -12.9px;
        top: 18px;
        padding: 0px 0px 19px 6px !important
    }

    ._table-form ._remove-row-item {
        padding: 0px 0px 19px 5px !important
    }

    ._remove-row-item .fas {
        font-size: 10px
    }

    .select2-dropdown {
        z-index: 1061;
    }

</style>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex justify-content-start">
                    <h3 class="kt-subheader__title" style="width: 430px">
                        Rekapitulasi
                    </h3>

                    <select name="filter-jenis-dokumen" class="form-control">
                        <option value="all">SEMUA</option>
                        <option value="satker">SATKER</option>
                        <option value="balai">BALAI</option>
                        <option value="eselon2">ESELON 2</option>
                        <option value="eselon1">ESELON 1</option>
                    </select>

                    <?= csrf_field() ?>
                </div>
            </div>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
    </div>
</div>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="clearfix mb-3">
                <h5 class="kop-rekap">Rekapitulasi Dokumen Perjanjian Kerja</h5>
            </div>
            <div class="clearfix mb-3">
                <div class="float-right">
                    <a href="<?php echo site_url('dokumenpk/eselon1/export-rekap-excel'); ?>" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Rekap</a>
                </div>
            </div>
            <div class="tabel-rekap tableFixHead card row">
                <table class="table table-bordered" border=1>
                    <thead class="table-primary text-dark">
                        <tr>
                            <th>NO</th>
                            <th>Tahun</th>
                            <th>Balai</th>
                            <th>SP</th>
                            <th>Indikator SP</th>
                            <th>Satker</th>
                            <th>SK</th>
                            <th>Indikator SK</th>
                            <th>Output</th>
                            <th>Satuan</th>
                            <th>Outcome</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <?php if($data) { ?>
                            <?php foreach($data as $key => $value) { ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $key+1 ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaBalai'] ?>
                                    </td>
                                <?php foreach($value['sp'] as $keySp => $valueSp) { ?>
                                    <?php if ($keySp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                                                <?php echo $valueSp['namaSp'] ?>
                                            </td>
                                    <?php foreach($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                        <?php if ($keyIndicatorSp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                                <?php echo $valueIndicatorSp['title'] ?>
                                            </td>
                                        <?php foreach($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                                                <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                        <?php if(!empty($valueSatker['namaSatker'])) {
                                                            echo $valueSatker['namaSatker'];
                                                        } else {
                                                            echo '-';
                                                        } ?>
                                                    </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                <?php if ($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        <?php if(!empty($valueSk['namaSk'])) {
                                                                echo $valueSk['namaSk'];
                                                            } else {
                                                                echo '-';
                                                            } ?>
                                                    </td>
                                                <?php foreach($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                                    <?php if ($keySkIndikator >= 1) { ?> </tr><tr> <?php } ?>
                                                        <td>
                                                            <?php echo $valueSkIndikator['title'] ?>
                                                        </td>
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?> -->
                        <?php if($databalai) { ?>
                            <?php foreach($databalai as $key => $value) { ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $key+1 ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['nama_balai'] ?>
                                    </td>
                                <?php foreach($value['sp'] as $keySp => $valueSp) { ?>
                                    <?php if ($keySp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                                                <?php echo $valueSp['nama_sp'] ?>
                                            </td>
                                    <?php foreach($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                        <?php if ($keyIndicatorSp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                                <?php echo $valueIndicatorSp['title'] ?>
                                            </td>
                                        <?php foreach($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                                                <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                        <?php if(!empty($valueSatker['namaSatker'])) {
                                                            echo $valueSatker['namaSatker'];
                                                        } ?>
                                                    </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                <?php if ($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        <?php if(!empty($valueSk['namaSk'])) {
                                                                echo $valueSk['namaSk'];
                                                            } else {
                                                                echo '-';
                                                            } ?>
                                                    </td>
                                                <?php foreach($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                                    <?php if ($keySkIndikator >= 1) { ?> </tr><tr> <?php } ?>
                                                        <td>
                                                            <?php echo $valueSkIndikator['title'] ?>
                                                        </td>
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                                                        <!-- <td><?php echo $valueSkIndikator['outcome'] ?></td> -->
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>







<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $this->endSection() ?>