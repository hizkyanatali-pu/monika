<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    td.disabled {
        background: #a8a8a8;
    }

    td.disabled input[readonly],
    td.disabled span {
        background: #a8a8a8;
    }
</style>

<!-- Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex justify-content-start">
                    <h3 class="kt-subheader__title" style="width: 280px">
                        <?php echo $pageTitle ?? 'Input Perjanjian Kinerja' ?>
                    </h3>

                    <?php if (isset($filterSatker)) { ?>
                        <select name="filter-satker" class="form-control">
                            <option value="all">SEMUA SATKER</option>
                            <?php foreach ($filterSatker as $key => $data) { ?>
                                <option value="<?php echo $data->satkerid ?>" <?php if ($data->satkerid == $filterSatker_selected) echo 'selected' ?>>
                                    <?php echo $data->satker ?>
                                </option>
                            <?php  } ?>
                        </select>
                    <?php } ?>

                    <?= csrf_field() ?>
                </div>

                <div>
                    <?php if (isset($listSatkerCreateCokumen)) { ?>
                        <?php if ($listSatkerCreateCokumen == true) { ?>
                            <button class="btn btn-info __list-satker-telah-membuat-dokumen" data-available="<?php echo $templateAvailable ?>" <?php if (isset($balaiCreateForSatker)) { ?> data-balai-create-satker="<?php echo $balaiCreateForSatker ?>" <?php } ?>>
                                <i class="fas fa-list"></i> Daftar Satker Membuat Dokumen
                            </button>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($isEselon1) { ?>
                        <a href="<?php echo site_url('dokumenpk/eselon1/export-rekap-excel'); ?>" target="_blank" class="btn btn-success mr-4">
                            <i class="fas fa-file"></i> Rekap
                        </a>
                    <?php } ?>

                    <?php if ($isCanCreated) {
                        if (count($dataDokumen) > 0) {
                            $disabled = $dataDokumen[0]->status != "setuju" ? "disabled" : "";
                        } else {
                            $disabled = "";
                        }
                    ?>
                        <button 
                            class="btn btn-primary __opsi-template" 
                            data-available="<?php echo $templateAvailable ?>" <?php if (isset($balaiCreateForSatker)) { ?> data-balai-create-satker="<?php echo $balaiCreateForSatker ?>" <?php } ?> <?= $disabled ?>
                        >
                            <i class="fas fa-plus"></i> Buat Dokumen
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Subheader -->



<!-- Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--tab">
        <div class="kt-portlet__body">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th width="100px">No. Dokumen</td>
                        <th>Dokumen</td>
                        <th width="150px">Tanggal Kirim</th>
                        <th width="150px">Tanggal Disetujui / Ditolak</th>
                        <th width="250px">Status</th>
                        <th width="70px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dataDokumen as $key => $data) :
                        $dokumenMasterID = $data->revision_master_dokumen_id ?? $data->id;
                    ?>
                        <tr>
                            <td>
                                <?php echo $dokumenMasterID ?>
                            </td>
                            <td>
                                <?php echo "PERJANJIAN KINERJA " . $data->dokumenTitle ?>

                                <?php if ($data->revision_master_number || $data->is_revision_same_year) :
                                    $badgeText  = ($data->is_revision_same_year) ? 'Revisi' : 'Koreksi Ke ' . $data->revision_number;
                                    $badgeColor = ($data->is_revision_same_year) ? 'bg-danger' : 'bg-warning'
                                ?>
                                    <div>
                                        <span class="badge badge-sm <?php echo $badgeColor ?> text-white __cetak-dokumen" data-dokumen-master-id="<?php echo $dokumenMasterID ?>" data-number-revisioned="<?php echo $data->revision_master_number ?>">
                                            <?php echo $badgeText ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if (property_exists($data, 'userCreatedName')) { ?>
                                    <div class="mt-2">
                                        Instansi : <strong><?php echo instansi_name($data->satkerid)->nama_instansi ?></strong><br>
                                        Di buat oleh : <strong><?php echo $data->userCreatedName ?></strong>

                                    </div>
                                <?php } ?>
                            </td>
                            <td>
                                <?php echo date_indo($data->created_at) ?>
                            </td>
                            <td>
                                <?php echo $data->status != "hold" ? date_indo($data->change_status_at) : '-' ?>
                            </td>
                            <td class="pr-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pill px-3 font-weight-bold <?php echo $dokumenStatus[$data->status]['color'] ?>">
                                        <?= $dokumenStatus[$data->status]['message'] . ($data->status != "hold" ? " Oleh " . ($data->verif_by == 'admin' ? "admin" : "UPT Balai") : " ")  ?>
                                    </span>


                                    <?php if ($data->status == 'tolak') : ?>
                                        <button class="btn btn-sm btn-outline-danger __prepare-revisi-dokumen" data-id="<?php echo $data->id ?>" data-template-id="<?php echo $data->template_id ?>">
                                            <i class="fas fa-edit"></i> Koreksi
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="d-flex justify-content-start">
                                <button class="btn btn-sm __lihat-dokumen btn-outline-secondary" data-id="<?php echo $data->id ?>" data-template-id="<?php echo $data->template_id ?>" data-select-top="true">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <button class="btn btn-sm __cetak-dokumen <?php echo $data->status == 'setuju' ? 'btn-outline-success' : 'btn-outline-secondary' ?>" data-dokumen-master-id="<?php echo $dokumenMasterID ?>" data-number-revisioned="<?php echo $data->revision_master_number ?>" data-select-top="true">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end-of: Content -->



<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <button type="button" class="btn btn-default pr-2 d-none __back-pilih-dokumen">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h5 class="modal-title pt-2 pl-2">Pilih Dokumen</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group" id="choose-template">
                    <?php foreach ($templateDokumen as $keyTemplate => $dataTemplate) : ?>
                        <a class="list-group-item list-group-item-action __buat-dokumen-pilih-template" href="javascript:void(0)" data-id="<?php echo $dataTemplate->id ?>">
                            <?php echo $dataTemplate->title ?>
                        </a>
                    <?php endforeach ?>
                </div>
                <div class="p-4 d-none" id="make-dokumen">
                </div>


                <?php /*if (! $valudasiCreatedDokumen) { ?>
                        <div class="list-group" id="choose-template">
                            <?php foreach ($balaiChecklistSatker as $keyChecklistBalai => $dataChecklistBalai) : ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <label><?php echo $dataChecklistBalai->satker ?></label>
                                    
                                    <?php if ($dataChecklistBalai->iscreatedPK > 0) { ?>
                                        <i class="fas fa-check mt-2"></i>
                                    <?php } ?>
                                </li>
                            <?php endforeach ?>
                        </div>
                    <?php }*/ ?>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-primary __save-dokumen">Simpan Dokumen</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Form -->



<!-- Modal Opsi Cetak Dokumen Terevisi -->
<div class="modal fade" id="modal-cetak-dokumen-revisioned" tabindex="-1" role="dialog" aria-labelledby="modal-cetak-dokumen-revisionedTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="list-group">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Opsi Cetak Dokumen Terevisi -->



<!-- Modal Preview Cetak Dokumen -->
<div class="modal fade" id="modal-preview-cetak" tabindex="-1" role="dialog" aria-labelledby="modal-preview-cetakTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="container-revision-alert-cetak"></div>
                <iframe width="100%" style="height: 80vh" frameborder="0"></iframe>
            </div>

            <?php if ($isCanConfirm) { ?>
                <div class="modal-footer p-0"></div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- end-of: Modal Preview Cetak Dokumen -->



<!-- Modal satker list created -->
<div class="modal fade" id="modalSatkerListCreated" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Satker Membuat Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <ul class="list-group">
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal satker list created -->
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php echo script_tag('plugins/inputmask/jquery.inputmask.js'); ?>
<?php echo script_tag('plugins/inputmask/inputmask.binding.js'); ?>

<?php echo $this->include('jspages/dokumenpk') ?>
<?= $this->endSection() ?>