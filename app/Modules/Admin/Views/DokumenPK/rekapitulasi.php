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

    h1 {
        text-align: center;
    }

    /* table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    thead {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    tbody {
        display: block;
        height: 300px;
        overflow-y: scroll;
        width: 100%;
    }

    tr {
        width: 100%;
        display: table;
        table-layout: fixed;
    } */
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

                    <!-- <select name="filter-jenis-dokumen" class="form-control filter-dokumen">
                        <option value="all">SEMUA</option>
                        <option value="satker">SATKER</option>
                        <option value="balai">BALAI</option>
                        <option value="skpd">SKPD TP-OP</option>
                        <option value="satker_pusat">SATKER PUSAT</option>
                        <option value="eselon2">ESELON 2</option>
                        <option value="balai_teknik">BALAI TEKNIK</option>
                    </select>
                    <?= csrf_field() ?> -->
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
                    <a href="<?php echo site_url('dokumenpk/rekapitulasi/export-excel'); ?>" target="_blank" class="btn btn-success btn-sm btn-rekap-all"><i class="fa fa-file-excel"></i> Rekap Excel</a>
                </div>
            </div>
            <!-- <table>
                <tr>
                    <th rowspan="2">Satker</th>
                    <th colspan="2">Target</th>
                    <th colspan="2">Outcome</th>
                </tr>
                <tr>
                    <th>Indikator</th>
                    <th>Value</th>
                    <th>Value</th>
                    <th>Satuan</th>
                </tr>
                <?php foreach ($data as $satkerData) : ?>
                    <tr>
                        <td rowspan="<?= count($satkerData['indikators']) + 1 ?>"><?= $satkerData['satker'] ?></td>
                    </tr>
                    <?php foreach ($satkerData['indikators'] as $indikator) : ?>
                        <tr>
                            <td><?= $indikator['indikator'] ?></td>
                            <td><?= $indikator['target_value'] ?></td>
                            <td><?= $indikator['outcome_value'] ?></td>
                            <td><?= $indikator['outcome_satuan'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </table> -->
            <div class="table-responsive tableFixHead">
                <table class="table table-bordered w-100 mb-0">
                    <thead class="table-primary">
                        <tr class="text-center">

                            <th colspan="2" width="60%">&nbsp;</th>

                            <th colspan="2">Output</th>
                            <th colspan="4">Outcome</th>



                        </tr>
                        <tr class="text-center">
                            <th width="10%">Unit Kerja</th>
                            <th width="50%">Indikator SK</th>
                            <th width="5%">Nilai</th>
                            <th width="10%">Satuan</th>
                            <th width="10%">Nilai</th>
                            <th width="5%">Satuan</th>
                            <th>Jenis PK</th>
                            <th>Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $satkerData) : ?>
                            <tr>
                                <td style="background-color:#f9f970;" colspan="8"><strong><?= ($satkerData['satkerid'] ? $satkerData['satkerid'] . " - " : '') . $satkerData['satker'] ?></strong>
                                    <a href="<?= base_url() . '/api/showpdf/tampilkan/' . $satkerData['idDoc'] . '?preview=true' ?>" target="_blank"><img src="https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png" style="width:42px;height:42px;"></a>
                                </td>
                            </tr>
                            <?php foreach ($satkerData['indikators'] as $indikator) : ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td width="50%"><?= $indikator['indikator'] ?></td>
                                    <td class="text-right"><?= $indikator['target_value'] ?></td>
                                    <td><?= $indikator['target_satuan'] ?></td>
                                    <td class="text-right"><?= $indikator['outcome_value'] ?></td>
                                    <td><?= $indikator['outcome_satuan'] ?></td>
                                    <td><?= $indikator['is_revision_same_year'] > 0 ? "REVISI" : "AWAL" ?></td>
                                    <td><?= $indikator['status'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
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