<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    td.disabled {
        background: #a8a8a8;
    }

    td.disabled input[readonly],
    td.disabled select[disabled],
    td.disabled span {
        background: #a8a8a8;
    }

    /* table {
        width: 100%;
        border-collapse: collapse;
    } */

    .sticky-header-1 {
        position: sticky;
        top: -15px;
        background-color: #f9f9f9;
        z-index: 2;
        /* #a8b0ed */
        /* Menentukan tingkat tumpukan */
    }

    .sticky-header-2 {
        position: sticky;
        top: 30px;
        /* Sesuaikan dengan tinggi tiga baris */
        background-color: #f9f9f9;
        z-index: 1;
        /* Menentukan tingkat tumpukan */
    }

    .sticky-header-3 {
        position: sticky;
        top: 69px;
        /* Sesuaikan dengan tinggi tiga baris */
        background-color: #f9f9f9;
        z-index: 1;
        /* Menentukan tingkat tumpukan */
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
</style>

<!-- Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex justify-content-start">
                    <h3 class="kt-subheader__title" style="width: 280px">
                        <?php echo $pageTitle ?? 'Input Renstra' ?>
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
                            // matikan membuat revisi 18012024
                            $disabled = $dataDokumen[0]->status != "setuju" ? "disabled" : "";
                            // $disabled = (($dataDokumen[0]->status == "setuju" || $dataDokumen[0]->status == "hold" || $dataDokumen[0]->status == "tolak") and session('userData.tahun') == 2024) ? "disabled" : "";
                        } else {
                            $disabled = "";
                        }
                    }
                    ?>
                    <?php if (!$isBalai) { ?>
                        <button
                            class="btn btn-primary __opsi-tahun-renstra"
                            data-available="<?php echo $templateAvailable ?>"
                            <?php if (isset($balaiCreateForSatker)) { ?> data-balai-create-satker="<?php echo $balaiCreateForSatker ?>" <?php } ?>
                            data-type="uptBalai-add"><i class="fas fa-plus"></i> Input Renstra
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
                        <th width="8%" class="text-center">No.</td>
                        <th width="40%" class="text-center">Dokumen</td>
                        <th width="10%" class="text-center">Tahun</td>
                        <th width="10%" class="text-center">Tanggal Kirim</th>
                        <th width="10%" class="text-center">Tanggal Disetujui / Ditolak</th>
                        <th width="5%" class="text-center">Status</th>
                        <th width="17%" class="text-center">Aksi</th>
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
                                <?php echo "RENSTRA " . $data->dokumenTitle ?>



                                <?php if (property_exists($data, 'userCreatedName')) { ?>
                                    <div class="mt-2">
                                        Instansi : <strong><?php echo instansi_name($data->satkerid)->nama_instansi ?></strong><br>
                                        Di buat oleh : <strong><?php echo $data->userCreatedName ?></strong>

                                    </div>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php echo $data->tahun ?>
                            </td>
                            <td class="text-center">
                                <?php echo date_indo($data->created_at) ?>
                            </td>
                            <td class="text-center">
                                <?php echo $data->status != "hold" ? date_indo($data->change_status_at) : '-' ?>
                            </td>
                            <td class="pr-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pill px-3 font-weight-bold <?php echo $dokumenStatus[$data->status]['color'] ?>">
                                        <?= $dokumenStatus[$data->status]['message'] . ($data->status != "hold" ? " Oleh " . ($data->verif_by == 'admin' ? "admin" : "UPT Balai") : " ")  ?>
                                    </span>


                                    <?php if ($data->status == 'tolak'  and (!isset($balaiCreateForSatker))) : ?>
                                        <button class="ml-2 btn btn-sm btn-outline-danger __prepare-revisi-dokumen" data-id="<?php echo $data->id ?>" data-template-id="<?php echo $data->template_id ?>">
                                            <i class="fas fa-edit"></i>Koreksi
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="d-flex justify-content-center">
                                <div class="btn-load mt-3">
                                    <button class="btn btn-sm __lihat-dokumen btn-outline-secondary" data-id="<?php echo $data->id ?>" data-template-id="<?php echo $data->template_id ?>" data-select-top="true" data-type="lihat" title="Lihat" data-status=<?= $data->status ?> data-tahun="<?php echo $data->tahun ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- <?php if ($data->status == 'hold') { ?>
                                        <button class="btn btn-sm btn-warning __edit-dokumen btn-outline-secondary" data-id="<?php echo $data->id ?>" data-template-id="<?php echo $data->template_id ?>" data-select-top="true" data-type="<?= $user_type ?>" data-status=<?= $data->status ?> title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    <?php } ?>
                                    <?php if ($data->status == 'setuju'  and (!isset($balaiCreateForSatker))) { ?>
                                        <button class="btn btn-sm btn-warning __edit-dokumen btn-outline-secondary" data-id="<?php echo $data->id ?>" data-template-id="<?php echo $data->template_id ?>" data-type=<?= (!isset($balaiCreateForSatker) and empty(session('userData.satker_id')) ? "uptBalai-add" : session('userData')['group_id'] ?? '') ?> data-status='<?= $data->status ?>' title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    <?php } ?>
                                </div> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end-of: Content -->

<!-- Modal Tahun-->
<div class="modal fade" id="modalOpsiTahun" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <button type="button" class="btn btn-default pr-2 d-none __back-pilih-dokumen">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h5 class="modal-title pt-2 pl-2">Pilih Tahun</h5>
                </div>
                <div class="float-right">
                    <button type="button" class="btn btn-modal-full text-right" style="margin: -10px;"><i class="fas fa-external-link-alt"></i></button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <select class="form-control mr-2" name="tahun_anggaran" id="tahunAnggaran">
                            <?php
                            $currentYear = date("Y");
                            for ($i = 2020; $i <= $currentYear; $i++) {
                                $selected = ($i == $currentYear) ? "selected" : "";
                            ?>
                                <option value="<?= $i ?>" <?= $selected ?>><?= $i ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <button type="button" class="btn btn-primary __opsi-template" data-available="<?php echo $templateAvailable ?>" <?php if (isset($balaiCreateForSatker)) { ?> data-balai-create-satker="<?php echo $balaiCreateForSatker ?>" <?php }
                                                                                                                                                                                                                                                if (!isset($balaiCreateForSatker) and empty(session('userData.satker_id'))) {
                                                                                                                                                                                                                                                    echo "data-type=uptBalai-add";
                                                                                                                                                                                                                                                } ?>>Pilih</button>
                    </div>
                </div>
            </div>

            <!-- <div class="modal-footer d-none">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Batal</button>
                <button type="button" class="btn btn-primary __save-dokumen">Simpan Dokumen</button>
                <button type="button" class="btn btn-success __save-update-dokumen d-none">Simpan Dokumen</button>
            </div> -->
        </div>
    </div>
</div>
<!-- end-of: Modal Tahun-->

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex">
                    <button type="button" class="btn btn-default pr-2 d-none __back-pilih-dokumen">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h5 class="modal-title pt-2 pl-2">Pilih Dokumen</h5>
                </div>
                <div class="float-right">
                    <button type="button" class="btn btn-modal-full text-right" style="margin: -10px;"><i class="fas fa-external-link-alt"></i></button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Batal</button>
                <button type="button" class="btn btn-primary __save-dokumen">Simpan Dokumen</button>
                <button type="button" class="btn btn-success __save-update-dokumen d-none">Simpan Dokumen</button>
                <div id="parentModalFooter"></div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Form -->

<!-- Modal output kegiatan -->
<div class="modal fade" id="modalPilihOutputKegiatan" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content" style="height: 100% !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihOutputKegiatan-title">
                    Pilih Output Kegiatan
                </h5>
                <div>
                    <div class="d-flex">
                        <button type="button" class="btn btn-modal-full ml-auto"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close  ml-0 text-right pl-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>

            </div>
            <div class="modal-body">

                <!-- <div class="col-12">
                    <Label>Satuan</Label>
                    <input type="text" id="satuan" name="satuan">
                </div> -->


                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr class="text-center theader sticky-header-1" style="background-color: #a8b0ed;">
                            <th></th>
                            <th class="tdKode text-center">Kegiatan</th>
                            <th class="tdLabel text-center">Output</th>
                            <th class="tdPersen text-center">Outcome</th>
                            <th class="tdPersen outcome2-kegiatan text-center d-none">Tipe</th>
                            <th class="tdPersen outcome3-kegiatan  text-center d-none">Kategori</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyOutputKegiatan">
                        <!-- Data rows go here -->
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <!-- Footer Modal Pertama -->
                <button class="btn btn-success save-btn-output-kegiatan"> Simpan Output Kegiatan</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal output kegiatan -->


<!-- Modal Pilih Paket -->
<div class="modal fade" id="modalPilihPaket" tabindex="-1" role="dialog" aria-labelledby="modalFormTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content" style="height: 100% !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormTitlePaket">
                    Pilih Paket
                </h5>
                <div>
                    <div class="d-flex">
                        <button type="button" class="btn btn-modal-full ml-auto"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="close  ml-0 text-right pl-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <small><b>* Terakhir Update Data Emon : </b></small> <small class="text-danger"><i> <?= (getLastUpdateData() ? getLastUpdateData() . " WIB" : 0);  ?></i></small>
                </div>

            </div>
            <div class="modal-body">

                <!-- <div class="col-12">
                    <Label>Satuan</Label>
                    <input type="text" id="satuan" name="satuan">
                </div> -->

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr class="text-center theader sticky-header-1" style="background-color: #a8b0ed;">
                                <th></th>
                                <th class="text-center" width="10%">Kode</th>
                                <th class="text-center" width="20%">Paket</th>
                                <th class="text-center" width="5%">Vol</th>
                                <th class="text-center" width="10%">Satuan</th>
                                <!-- <th class="tdNilai">Pagu Dipa</th>
                            <th class="tdNilai">Realisasi</th> -->
                                <th class="min-w-10px text-center" width="5%">%keu</th>
                                <th class="min-w-200px text-center" width="5%">%fisik</th>
                                <th class="min-w-200px text-center" width="10%">Output</th>
                                <th class="min-w-200px text-center" width="10%">Outcome</th>
                                <th class="min-w-200px text-center outcome2 d-none " width="10%">Tipe</th>
                                <th class="min-w-200px text-center outcome3 d-none" width="5%">Kategori</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <!-- Data rows go here -->
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <!-- Footer Modal Pertama -->
                <button class="btn btn-success save-btn-paket"> Simpan Paket</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Pilih Paket -->





<!-- Modal Opsi Cetak Dokumen Terevisi -->
<div class="modal fade" id="modal-cetak-dokumen-revisioned" tabindex="-1" role="dialog" aria-labelledby="modal-cetak-dokumen-revisionedTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Pilih Dokumen :</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Opsi Cetak Dokumen Terevisi -->



<!-- Modal Preview Cetak Dokumen -->
<div class="modal fade" id="modal-preview-cetak" tabindex="-1" role="dialog" aria-labelledby="modal-preview-cetakTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" style="height: 100% !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preview Dokumen</h5>
                <div class="d-flex">
                    <button type="button" class="btn btn-modal-full"><i class="fas fa-external-link-alt"></i></button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="container-revision-alert-cetak" style="max-height: 30vh;overflow-y: auto;"></div>
                <iframe width="100%" style="height: 100vh !important" frameborder="0"></iframe>
            </div>

            <?php if ($isCanConfirm) { ?>
                <div class="modal-footer p-0"></div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- end-of: Modal Preview Cetak Dokumen -->



<!-- Modal satker list created -->
<div class="modal fade" id="modalSatkerListCreated" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
<script>
    $(document).on('click', '.__lihat-dokumen', function() {
        let _dokumenID = $(this).data('id')
        let status = $(this).data('status')
        if (status == "hold") {
            let html = `
            <button class="btn btn-sm btn-outline-danger mr-2 __tolak-dokumen" data-id="${_dokumenID}">
                <i class="fa fa-ban"></i> Tolak
            </button>
            <button class="btn btn-sm btn-success __setujui-dokumen" data-id="${_dokumenID}">
                <i class="fa fa-check"></i> Setujui
            </button>`
            $("#parentModalFooter").html(html)
        }
    })
</script>
<?php echo $this->include('jspages/renstra_js') ?>
<?= $this->endSection() ?>