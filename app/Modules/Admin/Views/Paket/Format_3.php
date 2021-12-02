<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<style>
    .table td,
    th {
        padding: 2px;
        font-size: 12px;
        border: 1px solid #333;
    }

    table a {
        color: #000099;
    }

    table tr:hover {
        color: #000000;
        text-decoration: underline;
        font-weight: bold;
    }

    .tdKodeLabel {
        width: 500px;
    }

    .tdLabelFull {}

    .tdKode {
        width: 200px;
    }

    .tdLabel {
        width: 300px;
    }

    .tdTV {
        width: 90px;
    }

    .tdLokasi {
        width: 120px;
    }

    .tdJP {
        width: 40px;
    }

    .tdMP {
        width: 40px;
    }

    .tdNilai {
        width: 100px;
    }

    .tdPersen {
        width: 50px;
    }

    .tdPersen {
        width: 50px;
    }

    .tdsatker {
        background-color: #eee6ff;
    }

    .tdgiat {
        background-color: #ddccff;
    }

    .tdoutput {
        background-color: #ccb3ff;
    }

    .tdsoutput {
        background-color: #bb99ff;
    }

    .tdkomponen {
        background-color: #aa80ff;
    }

    .theader th {
        background-color: #66ccff;
        color: #000;
    }
</style>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                <?= $title; ?> </h5>
            <small>
                <?php $l = '';
                foreach ($posisi as $key => $data) : ?>
                    <?php $l .= ($l ? ' <i class="fa fa-angle-double-right"></i> ' : '') . $data; ?>
                <?php endforeach;
                echo $l; ?>
            </small>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="padding:0px; margin:0px;">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="padding:0px;">

            <!--begin::Section-->
            <div class="kt-section">

                <div class="kt-section__content">
                    <div class="table-responsive tableFixHead">

                        <!-- <a id="a-go-" data-toggle="collapse" href="#go-" aria-expanded="true" aria-controls="go-">
                        menu</a>
                        <div id="go-" class="collapse in" role="tabpanel" aria-labelledby="a-go-" >
                        box-Konten
                        </div> -->
                        <?php $colspan = 11; ?>
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-right text-danger" colspan="<?= $colspan; ?>" style="border:0px;">
                                        <a target="_blank" href="<?php echo site_url('pulldata/rekap/' . $rekap) . "?idk=" . $idk . "&label=" . $label . "&label2=" . (!empty($label2) ? $label2 : '') . "&idks=" . (!empty($idks) ? $idks : '') . "&rekap="; ?>" class="btn btn-success btn-sm text-white"><i class="fa fa-file-excel"></i>Rekap Pagu</a>
                                        <a target="_blank" href="<?php echo site_url('pulldata/rekap/' . $rekap) . "?idk=" . $idk . "&label=" . $label . "&label2=" . (!empty($label2) ? $label2 : '') . "&idks=" . (!empty($idks) ? $idks : '') . "&rekap="; ?>" class="btn btn-success btn-sm text-white"><i class="fa fa-file-excel"></i>Rekap SDA - DB</a>
                                        <b>*Dalam Ribuan</b>
                                    </th>
                                </tr>
                                </tr>
                                <tr class=" text-center theader">
                                    <th colspan="2">&nbsp;</th>
                                    <th colspan="4">Pagu (Rp)</th>
                                    <th>&nbsp;</th>
                                    <th colspan="2">Progres (%)</th>
                                    <th colspan="2">Deviasi</th>
                                </tr>
                                <tr class=" text-center theader">
                                    <th class="tdKode">Kode</th>
                                    <th class="tdLabel">Paket</th>

                                    <!-- <th class="tdTV">Target volume</th>
                                        <th class="tdLokasi">Lokasi</th>
                                        <th class="tdJP" title="Jenis Paket">JP</th>
                                        <th class="tdMP" title="Metode pemilihan">MP</th> -->

                                    <th class="tdNilai">RPM</th>
                                    <th class="tdNilai">SBSN</th>
                                    <th class="tdNilai">PLN</th>
                                    <th class="tdNilai">TOTAL</th>

                                    <th class="tdNilai">Realisasi</th>

                                    <th class="tdPersen">keu</th>
                                    <th class="tdPersen">fisik</th>

                                    <th class="tdPersen">%</th>
                                    <th class="tdPersen">Rp</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-utama">
                                <?php if ($qdata) : ?>
                                    <?php $idp = [];
                                    $idg = [];
                                    $ido = [];
                                    $idso = [];
                                    $idkk = [];
                                    $colspan = 11; ?>
                                    <?php foreach ($qdata as $key => $d) : ?>

                                        <?php $idk = $d['programid']; ?>

                                        <?php $idk = $idk . '.' . $d['giatid']; ?>
                                        <?php if (!in_array($idk, $idp)) : ?>
                                            <tr>
                                                <td class="tdgiat" colspan="<?php echo $colspan; ?>"><?php echo $idk; ?></td>
                                            </tr>
                                            <?php $idp = array_merge([$idk], $idp); ?>
                                        <?php endif; ?>

                                        <?php $idk = $idk . '.' . $d['outputid']; ?>
                                        <?php if (!in_array($idk, $ido)) : ?>
                                            <tr>
                                                <td class="tdoutput" colspan="<?php echo $colspan; ?>"><?php echo $idk; ?></td>
                                            </tr>
                                            <?php $ido = array_merge([$idk], $ido); ?>
                                        <?php endif; ?>

                                        <?php $idk = $idk . '.' . $d['soutputid']; ?>
                                        <?php if (!in_array($idk, $idso)) : ?>
                                            <tr>
                                                <td class="tdsoutput" colspan="<?php echo $colspan; ?>"><?php echo $idk; ?></td>
                                            </tr>
                                            <?php $idso = array_merge([$idk], $idso); ?>
                                        <?php endif; ?>

                                        <?php $idk = $idk . '.' . $d['komponenid']; ?>
                                        <?php if (!in_array($idk, $idkk)) : ?>
                                            <tr>
                                                <td class="tdkomponen" colspan="<?php echo $colspan; ?>"><?php echo $idk; ?></td>
                                            </tr>
                                            <?php $idkk = array_merge([$idk], $idkk); ?>
                                        <?php endif; ?>

                                        <tr>
                                            <td class="tdKode"><?php echo $d['id']; ?></td>
                                            <td class="tdLabel"><?php echo $d['label']; ?></td>
                                            <?PHP
                                            /*
                                        <td class="tdTV"><?php echo $d['vol'];?></td>
                                        <td class="tdLokasi"><?php echo $d['lokasi'];?></td>
                                        <td class="tdJP text-center"><?php echo $d['jenis_paket'];?></td>
                                        <td class="tdMP text-center"><?php echo $d['metode_pemilihan'];?></td>
                                        */
                                            ?>

                                            <td class="tdNilai text-right"><?php echo number_format($d['pagu_rpm'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right"><?php echo number_format($d['pagu_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right"><?php echo number_format($d['pagu_phln'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right"><?php echo number_format($d['pagu_total'] / 1000, 0, ',', '.'); ?></td>

                                            <td class="tdNilai text-right"><?php echo number_format($d['real_total'] / 1000, 0, ',', '.'); ?></td>

                                            <td class="tdPersen text-right"><?php echo number_format($d['progres_keuangan'], 2, ',', '.'); ?></td>
                                            <td class="tdPersen text-right"><?php echo number_format($d['progres_fisik'], 2, ',', '.'); ?></td>

                                            <td class="tdPersen text-right"><?php echo ($d['progres_fisik'] > $d['progres_keuangan'] ? number_format($d['persen_deviasi'], 2, ',', '.') : '-'); ?></td>
                                            <td class="tdNilai text-right"><?php echo ($d['progres_fisik'] > $d['progres_keuangan'] ? number_format($d['nilai_deviasi'] / 1000, 0, ',', '.') : '-'); ?></td>
                                        </tr>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
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
    console.log('additional footer js')
</script>
<?= $this->endSection() ?>