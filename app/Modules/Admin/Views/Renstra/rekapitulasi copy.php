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

                    <select name="filter-jenis-dokumen" class="form-control filter-dokumen">
                        <option value="all">SEMUA</option>
                        <option value="satker">SATKER</option>
                        <option value="balai">BALAI</option>
                        <option value="skpd">SKPD TP-OP</option>
                        <option value="satker_pusat">SATKER PUSAT</option>
                        <option value="eselon2">ESELON 2</option>
                        <option value="balai_teknik">BALAI TEKNIK</option>
                    </select>

                    <h3 class="ml-3 kt-subheader__title" style="width: 200px">
                        Bulan
                    </h3>
                    <select name="filter-bulan" class="form-control filter-bulan">
                        <?php
                        $namaBulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                        $noBulan = 1;
                        // $uri = current_url(true);
                        // $bulanberjalan = decrypt_url($uri->getSegment(2)); // 3
                        // $bulansekarang = date('n');
                        for ($index = 0; $index < 12; $index++) { ?>
                            <option value="<?= encrypt_url($noBulan) ?>" <?= ($bulanberjalan ==  $noBulan ? 'selected="selected"' : '') ?>> <?= $namaBulan[$index] ?> </option>;
                        <?php
                            $noBulan++;
                        }
                        ?>
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
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-rekap-all'); ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-all"><i class="fa fa-file-excel"></i> Rekap Semua</a>
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-rekap-satker'); ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-satker" hidden><i class="fa fa-file-excel"></i> Rekap Satker</a>
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-rekap-balai'); ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-balai" hidden><i class="fa fa-file-excel"></i> Rekap Balai</a>
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-rekap-skpd'); ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-skpd" hidden><i class="fa fa-file-excel"></i> Rekap SKPD TP-OP</a>
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-rekap-satpus'); ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-satpus" hidden><i class="fa fa-file-excel"></i> Rekap Satker Pusat</a>
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-rekap-eselon2') ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-eselon2" hidden><i class="fa fa-file-excel"></i> Rekap Eselon 2</a>
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-rekap-baltek') ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-baltek" hidden><i class="fa fa-file-excel"></i> Rekap Balai Teknik</a>
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
                    <tbody class="all-data">
                        <?php if ($data) { ?>
                            <?php foreach ($data as $key => $value) { ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $key + 1 ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaBalai'] ?>
                                    </td>
                                    <?php foreach ($value['sp'] as $keySp => $valueSp) { ?>
                                        <?php if ($keySp >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                                    <?php echo $valueSp['namaSp'] ?>
                                </td>
                                <?php foreach ($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                    <?php if ($keyIndicatorSp >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                    <?php echo $valueIndicatorSp['title'] ?>
                                </td>
                                <?php foreach ($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                                    <?php if ($keySatker >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                    <?php if (!empty($valueSatker['namaSatker'])) {
                                                    echo $valueSatker['namaSatker'];
                                                } else {
                                                    echo '-';
                                                } ?>
                                </td>
                                <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                    <?php if ($keySk >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                    <?php if (!empty($valueSk['namaSk'])) {
                                                        echo $valueSk['namaSk'];
                                                    } else {
                                                        echo '-';
                                                    } ?>
                                </td>
                                <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                    <?php if ($keySkIndikator >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
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
    <?php } ?>
    <?php if ($databalai) { ?>
        <?php foreach ($data as $key => $value) { ?>
            <tr>
                <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                    <?php echo $key + 1 ?>
                </td>
                <td rowspan="<?php echo $value['rowspan'] ?>">
                    <?php echo $tahun ?>
                </td>
                <td rowspan="<?php echo $value['rowspan'] ?>">
                    <?php echo $value['namaBalai'] ?>
                </td>
                <?php foreach ($value['sp'] as $keySp => $valueSp) { ?>
                    <?php if ($keySp >= 1) { ?>
            </tr>
            <tr> <?php } ?>
            <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                <?php echo $valueSp['namaSp'] ?>
            </td>
            <?php foreach ($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                <?php if ($keyIndicatorSp >= 1) { ?>
            </tr>
            <tr> <?php } ?>
            <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                <?php echo $valueIndicatorSp['title'] ?>
            </td>
            <?php foreach ($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                <?php if ($keySatker >= 1) { ?>
            </tr>
            <tr> <?php } ?>
            <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                -
            </td>
            <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                <?php if ($keySk >= 1) { ?>
            </tr>
            <tr> <?php } ?>
            <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                -
            </td>
            <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                <?php if ($keySkIndikator >= 1) { ?>
            </tr>
            <tr> <?php } ?>
            <td>
                -
            </td>
            <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
            <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
            <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
            <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
            </tr>
        <?php } ?>
        </tr>

    <?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
<?php if ($dataskpd) { ?>
    <?php $value = $dataskpd[0];
    $no = 1; ?>
    <tr>
        <td align="center" rowspan="<?php echo $value['rowspan'] + 1 ?>">
            <?php echo $no ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] + 1 ?>">
            <?php echo $tahun ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] + 1 ?>">
            <?php echo $value['nama_balai'] ?>
        </td>
        <?php foreach ($value['sp'] as $keySp => $valueSp) {
            $rowspan_sp = $valueSp['rowspan'] + 1; ?>
            <?php if ($keySp >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $rowspan_sp <= 0 ? 1 : $rowspan_sp ?>">
        <?php echo $valueSp['namaSp'] ?>
    </td>
    <?php foreach ($valueSp['indikatorSkSKPD'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
        <?php if ($keyIndicatorSp >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
        -
    </td>
    <?php foreach ($valueIndicatorSp['satker'] as $keySatker => $valueSatker) {
                    $ke ?>
        <?php if ($keySatker >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
        <?php echo $valueSatker['nama_satker'] ?>
    </td>
    <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
        <?php if ($valueSk['indikatorSk'] != NULL) {
                            if ($keySk >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
        <?php echo $valueSk['namaSk'] ?>
    </td>
<?php } ?>
<?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
    <?php if ($keySkIndikator >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td>
        <?php echo $valueSkIndikator['title'] ?>
    </td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
    <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
    <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
    </tr>
<?php } ?>
</tr>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
<?php if ($datasatpus) { ?>
    <?php $value = $datasatpus[0];
    $no = 1; ?>
    <tr>
        <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $no ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $tahun ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['nama_balai'] ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['namaSp'] ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['indikator_sp'] ?>
        </td>
        <?php foreach ($value['satker'] as $keySatker => $valueSatker) { ?>
            <?php if ($keySatker >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
        <?php echo $valueSatker['nama_satker'] ?>
    </td>
    <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
        <?php if ($keySk >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
        <?php echo $valueSk['namaSk'] ?>
    </td>
    <?php $valueSkIndikator = $valueSk['indikatorSk'][0];
                if ($keySkIndikator >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td>
        <?php echo $valueSkIndikator['title'] ?>
    </td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
    <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
    <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
    </tr>
    </tr>
<?php } ?>
<?php } ?>
<?php } ?>
<?php if ($dataeselon2) { ?>
    <?php $value = $dataeselon2[0];
    $no = 1; ?>
    <tr>
        <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $no ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $tahun ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['nama_balai'] ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['namaSp'] ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['indikator_sp'] ?>
        </td>
        <?php foreach ($value['satker'] as $keySatker => $valueSatker) { ?>
            <?php if ($keySatker >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
        <?php echo $valueSatker['nama_satker'] ?>
    </td>
    <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
        <?php if ($keySk >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
        <?php echo $valueSk['namaSk']; ?>
    </td>
    <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
        <?php if ($keySkIndikator >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td>
        <?php echo $valueSkIndikator['title'] ?>
    </td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
    <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
    <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
    </tr>
    </tr>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
<?php if ($databaltek) { ?>
    <?php $value = $databaltek[0];
    $no = 1; ?>
    <tr>
        <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $no ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $tahun ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['nama_balai'] ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['namaSp'] ?>
        </td>
        <td rowspan="<?php echo $value['rowspan'] ?>">
            <?php echo $value['indikator_sp'] ?>
        </td>
        <?php foreach ($value['satker'] as $keySatker => $valueSatker) { ?>
            <?php if ($keySatker >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
        <?php echo $valueSatker['nama_satker'] ?>
    </td>
    <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
        <?php if ($keySk >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
        <?php echo $valueSk['namaSk']; ?>
    </td>
    <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
        <?php if ($keySkIndikator >= 1) { ?>
    </tr>
    <tr> <?php } ?>
    <td>
        <?php echo $valueSkIndikator['title'] ?>
    </td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
    <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
    <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
    <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
    </tr>
    </tr>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>
                    </tbody>
                    <tbody class="data-satker">
                        <?php if ($data) { ?>
                            <?php foreach ($data as $key => $value) { ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $key + 1 ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaBalai'] ?>
                                    </td>
                                    <?php foreach ($value['sp'] as $keySp => $valueSp) { ?>
                                        <?php if ($keySp >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                                    <?php echo $valueSp['namaSp'] ?>
                                </td>
                                <?php foreach ($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                    <?php if ($keyIndicatorSp >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                    <?php echo $valueIndicatorSp['title'] ?>
                                </td>
                                <?php foreach ($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                                    <?php if ($keySatker >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                    <?php if (!empty($valueSatker['namaSatker'])) {
                                                    echo $valueSatker['namaSatker'];
                                                } else {
                                                    echo '-';
                                                } ?>
                                </td>
                                <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                    <?php if ($keySk >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                    <?php if (!empty($valueSk['namaSk'])) {
                                                        echo $valueSk['namaSk'];
                                                    } else {
                                                        echo '-';
                                                    } ?>
                                </td>
                                <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                    <?php if ($keySkIndikator >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
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
    <?php } ?>
                    </tbody>
                    <tbody class="data-balai">
                        <?php if ($databalai) { ?>
                            <?php foreach ($data as $key => $value) { ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $key + 1 ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaBalai'] ?>
                                    </td>
                                    <?php foreach ($value['sp'] as $keySp => $valueSp) { ?>
                                        <?php if ($keySp >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                                    <?php echo $valueSp['namaSp'] ?>
                                </td>
                                <?php foreach ($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                    <?php if ($keyIndicatorSp >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                    <?php echo $valueIndicatorSp['title'] ?>
                                </td>
                                <?php foreach ($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                                    <?php if ($keySatker >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                    -
                                </td>
                                <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                    <?php if ($keySk >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                    -
                                </td>
                                <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                    <?php if ($keySkIndikator >= 1) { ?>
                                </tr>
                                <tr> <?php } ?>
                                <td>
                                    -
                                </td>
                                <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                                <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                                <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                                <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                                </tr>
                            <?php } ?>
                            </tr>

                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
                    </tbody>
                    <tbody class="data-skpd">
                        <?php if ($dataskpd) { ?>
                            <?php $value = $dataskpd[0];
                            $no = 1; ?>
                            <tr>
                                <td align="center" rowspan="<?php echo $value['rowspan'] + 1 ?>">
                                    <?php echo $no ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] + 1 ?>">
                                    <?php echo $tahun ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] + 1 ?>">
                                    <?php echo $value['nama_balai'] ?>
                                </td>
                                <?php foreach ($value['sp'] as $keySp => $valueSp) {
                                    $rowspan_sp = $valueSp['rowspan'] + 1; ?>
                                    <?php if ($keySp >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $rowspan_sp <= 0 ? 1 : $rowspan_sp ?>">
                                <?php echo $valueSp['namaSp'] ?>
                            </td>
                            <?php foreach ($valueSp['indikatorSkSKPD'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                <?php if ($keyIndicatorSp >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                -
                            </td>
                            <?php foreach ($valueIndicatorSp['satker'] as $keySatker => $valueSatker) {
                                            $ke ?>
                                <?php if ($keySatker >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                <?php echo $valueSatker['nama_satker'] ?>
                            </td>
                            <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                <?php if ($valueSk['indikatorSk'] != NULL) {
                                                    if ($keySk >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                <?php echo $valueSk['namaSk'] ?>
                            </td>
                        <?php } ?>
                        <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                            <?php if ($keySkIndikator >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td>
                                <?php echo $valueSkIndikator['title'] ?>
                            </td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                            <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                            <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                            </tr>
                        <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
                    </tbody>
                    <tbody class="data-satpus">
                        <?php if ($datasatpus) { ?>
                            <?php $value = $datasatpus[0];
                            $no = 1; ?>
                            <tr>
                                <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $no ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $tahun ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['nama_balai'] ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['namaSp'] ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['indikator_sp'] ?>
                                </td>
                                <?php foreach ($value['satker'] as $keySatker => $valueSatker) { ?>
                                    <?php if ($keySatker >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                <?php echo $valueSatker['nama_satker'] ?>
                            </td>
                            <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                <?php if ($keySk >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                <?php echo $valueSk['namaSk'] ?>
                            </td>
                            <?php $valueSkIndikator = $valueSk['indikatorSk'][0];
                                        if ($keySkIndikator >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td>
                                <?php echo $valueSkIndikator['title'] ?>
                            </td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                            <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                            <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                            </tr>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                    </tbody>
                    <tbody class="data-eselon2">
                        <?php if ($dataeselon2) { ?>
                            <?php $value = $dataeselon2[0];
                            $no = 1; ?>
                            <tr>
                                <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $no ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $tahun ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['nama_balai'] ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['namaSp'] ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['indikator_sp'] ?>
                                </td>
                                <?php foreach ($value['satker'] as $keySatker => $valueSatker) { ?>
                                    <?php if ($keySatker >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                <?php echo $valueSatker['nama_satker'] ?>
                            </td>
                            <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                <?php if ($keySk >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                <?php echo $valueSk['namaSk']; ?>
                            </td>
                            <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                <?php if ($keySkIndikator >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td>
                                <?php echo $valueSkIndikator['title'] ?>
                            </td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                            <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                            <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                            </tr>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
                    </tbody>
                    <tbody class="data-baltek">
                        <?php if ($databaltek) { ?>
                            <?php $value = $databaltek[0];
                            $no = 1; ?>
                            <tr>
                                <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $no ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $tahun ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['nama_balai'] ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['namaSp'] ?>
                                </td>
                                <td rowspan="<?php echo $value['rowspan'] ?>">
                                    <?php echo $value['indikator_sp'] ?>
                                </td>
                                <?php foreach ($value['satker'] as $keySatker => $valueSatker) { ?>
                                    <?php if ($keySatker >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                <?php echo $valueSatker['nama_satker'] ?>
                            </td>
                            <?php foreach ($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                <?php if ($keySk >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                <?php echo $valueSk['namaSk']; ?>
                            </td>
                            <?php foreach ($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                <?php if ($keySkIndikator >= 1) { ?>
                            </tr>
                            <tr> <?php } ?>
                            <td>
                                <?php echo $valueSkIndikator['title'] ?>
                            </td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                            <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                            <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                            <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                            </tr>
                            </tr>
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
<script>
    // tbbody
    $('.data-satker').hide();
    $('.data-balai').hide();
    $('.data-skpd').hide();
    $('.data-satpus').hide();
    $('.data-eselon2').hide();
    $('.data-baltek').hide();

    //button

    $(document).on('change', 'select.filter-dokumen', function(event) {

        if ($('select.filter-dokumen').val() == 'all') {
            $('.data-balai').hide();
            $('.data-skpd').hide();
            $('.data-satpus').hide();
            $('.data-eselon2').hide();
            $('.data-baltek').hide();
            $('.data-satker').hide();
            $('.btn-rekap-balai').hide();
            $('.btn-rekap-skpd').hide();
            $('.btn-rekap-satpus').hide();
            $('.btn-rekap-eselon2').hide();
            $('.btn-rekap-baltek').hide();
            $('.btn-rekap-satker').hide();
            $('.btn-rekap-all').show();
            $('.all-data').show();
        }

        if ($('select.filter-dokumen').val() == 'satker') {
            $('.all-data').hide();
            $('.data-balai').hide();
            $('.data-skpd').hide();
            $('.data-satpus').hide();
            $('.data-eselon2').hide();
            $('.data-baltek').hide();
            $('.btn-rekap-all').hide();
            $('.btn-rekap-balai').hide();
            $('.btn-rekap-skpd').hide();
            $('.btn-rekap-satpus').hide();
            $('.btn-rekap-eselon2').hide();
            $('.btn-rekap-baltek').hide();
            $('.btn-rekap-satker').removeAttr("hidden");
            $('.btn-rekap-satker').show();
            $('.data-satker').show();
        }

        if ($('select.filter-dokumen').val() == 'balai') {
            $('.all-data').hide();
            $('.data-skpd').hide();
            $('.data-satpus').hide();
            $('.data-eselon2').hide();
            $('.data-baltek').hide();
            $('.data-satker').hide();
            $('.btn-rekap-all').hide();
            $('.btn-rekap-skpd').hide();
            $('.btn-rekap-satpus').hide();
            $('.btn-rekap-eselon2').hide();
            $('.btn-rekap-baltek').hide();
            $('.btn-rekap-satker').hide();
            $('.btn-rekap-balai').removeAttr("hidden");
            $('.btn-rekap-balai').show();
            $('.data-balai').show();
        }

        if ($('select.filter-dokumen').val() == 'skpd') {
            $('.all-data').hide();
            $('.data-satpus').hide();
            $('.data-eselon2').hide();
            $('.data-baltek').hide();
            $('.data-satker').hide();
            $('.data-balai').hide();
            $('.btn-rekap-all').hide();
            $('.btn-rekap-satpus').hide();
            $('.btn-rekap-eselon2').hide();
            $('.btn-rekap-baltek').hide();
            $('.btn-rekap-satker').hide();
            $('.btn-rekap-balai').hide();
            $('.btn-rekap-skpd').removeAttr("hidden");
            $('.btn-rekap-skpd').show();
            $('.data-skpd').show();
        }

        if ($('select.filter-dokumen').val() == 'satker_pusat') {
            $('.all-data').hide();
            $('.data-eselon2').hide();
            $('.data-baltek').hide();
            $('.data-satker').hide();
            $('.data-balai').hide();
            $('.data-skpd').hide();
            $('.btn-rekap-all').hide();
            $('.btn-rekap-eselon2').hide();
            $('.btn-rekap-baltek').hide();
            $('.btn-rekap-satker').hide();
            $('.btn-rekap-balai').hide();
            $('.btn-rekap-skpd').hide();
            $('.btn-rekap-satpus').removeAttr("hidden");
            $('.btn-rekap-satpus').show();
            $('.data-satpus').show();
        }

        if ($('select.filter-dokumen').val() == 'eselon2') {
            $('.all-data').hide();
            $('.data-baltek').hide();
            $('.data-satker').hide();
            $('.data-balai').hide();
            $('.data-skpd').hide();
            $('.data-satpus').hide();
            $('.btn-rekap-all').hide();
            $('.btn-rekap-baltek').hide();
            $('.btn-rekap-satker').hide();
            $('.btn-rekap-balai').hide();
            $('.btn-rekap-skpd').hide();
            $('.btn-rekap-satpus').hide();
            $('.btn-rekap-eselon2').removeAttr("hidden");
            $('.btn-rekap-eselon2').show()
            $('.data-eselon2').show();
        }

        if ($('select.filter-dokumen').val() == 'balai_teknik') {
            $('.all-data').hide();
            $('.data-satker').hide();
            $('.data-balai').hide();
            $('.data-skpd').hide();
            $('.data-satpus').hide();
            $('.data-eselon2').hide();
            $('.btn-rekap-all').hide();
            $('.btn-rekap-satker').hide();
            $('.btn-rekap-balai').hide();
            $('.btn-rekap-skpd').hide();
            $('.btn-rekap-satpus').hide();
            $('.btn-rekap-eselon2').hide();
            $('.btn-rekap-baltek').removeAttr("hidden");
            $('.btn-rekap-baltek').show();
            $('.data-baltek').show();
        }
    })
</script>
<?= $this->endSection() ?>