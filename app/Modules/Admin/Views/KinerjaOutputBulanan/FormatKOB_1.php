<?=
$this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<!-- <style>
    .tableFixHead1 {
        overflow-y: auto;
        height: 600px;
    }

    .tableFixHead1 thead th {
        position: sticky;
        top: 0;
        border: 1px solid #333;
    }


    .table td,
    th {
        padding: 2px;
        font-size: 12px;
        border: 1px solid #333;
    }

    table a {
        background-color: #68218b;
    }

    table tr:hover {
        color: #000000;
        text-decoration: underline;
        font-weight: bold;
    }

    .tdKodeLabel {
        width: 500px;
    }



    .thNo {
        width: 10px;
    }

    .thKode {
        width: 200px;
    }

    .thLabel {
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

    .thNilai {
        width: 100px;
    }

    .thPersen {
        width: 50px;
    }

</style> -->

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
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="mb-0">Pilih Bulan</label>
                        <div class="input-group">
                            <select class="form-control" id="listmonth" name="month">
                                <?php
                                $namaBulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                                $noBulan = 1;
                                $uri = current_url(true);
                                $bulanberjalan = decrypt_url($uri->getSegment(2)); // 3
                                $bulansekarang = date('n');
                                for ($index = 0; $index < 12; $index++) {
                                ?>
                                    <option value="<?= encrypt_url($noBulan) ?>" <?= ($bulanberjalan ==  $noBulan ? 'selected="selected"' : '') ?>> <?= $namaBulan[$index] ?> </option>;
                                <?php
                                    $noBulan++;
                                }
                                ?>

                            </select>
                            <!-- <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="search">
                                    <div class="fa fa-search"></div>
                                </button>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-md-8 text-right mt-3">
                        <div class="form-group">
                            <a href="<?= site_url('Kinerja-Output-Bulanan/') . $uri->getSegment(2) . ($uri->getSegment(3) ? "/" . $uri->getSegment(3) : '') . "?exp=xlxs" ?>" class="btn btn-success btn-sm text-white" target="_blank"><i class="fa fa-file-excel"></i>Excel</a>
                            <b>*Dalam Ribuan</b>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="mb-0">Keyword</label>
                        <div class="input-group">
                            <select class="form-control" id="keyword" name="keyword">
                                <?php $key = $uri->getSegment(3); ?>
                                <option value="" <?= ($key ==  '' ? 'selected="selected"' : '') ?>> Semua </option>
                                <option value="dibangun" <?= ($key ==  'dibangun' ? 'selected="selected"' : '') ?>> dibangun </option>
                                <option value="direhabilitasi" <?= ($key ==  'direhabilitasi' ? 'selected="selected"' : '') ?>> direhabilitasi </option>
                                <option value="ditingkatkan" <?= ($key ==  'ditingkatkan' ? 'selected="selected"' : '') ?>> ditingkatkan </option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="searchkeyword">
                                    <div class="fa fa-search"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive tableFixHead">

                    <?php $colspan = 8; ?>
                    <table class="table table-bordered mb-0 table-striped" id="table">
                        <thead>
                            <tr class=" text-center bg-purple">
                                <th class="thNo" rowspan="2">No</th>
                                <th class="thKode" rowspan="2">Kode</th>
                                <th class="thLabel" rowspan="2">Program/Kegiatan/KRO/RO</th>
                                <th class="thNilai" rowspan="2">Target</th>
                                <th class="thNilai" rowspan="2">Satuan</th>
                                <th class="thNilai" rowspan="2">Pagu (Rp Ribu)</th>
                                <th class="thNilai" rowspan="2">Realisasi (Rp Ribu)</th>
                                <th class="thPersen" colspan="2">Keuangan (%)</th>
                                <th class="thPersen" colspan="3">Fisik (%)</th>
                            </tr>
                            <tr class=" text-center bg-purple">
                                <th class="thPersen">RN</th>
                                <th class="thPersen">RL</th>
                                <th class="thPersen">RN</th>
                                <th class="thPersen">RL</th>
                                <th class="thPersen">Kinerja</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php if ($qdata) : ?>
                                <?php $idp = [];
                                $idg = [];
                                $ido = [];
                                $idso = [];
                                $idkk = [];
                                $noprog = 1;
                                $nogiat = 1;
                                $nooutput = 1;
                                $nosoutput = 1;

                                $total_pagu_soutput = array();

                                ?>
                                <?php foreach ($qdata as $key => $d) :



                                ?>
                                    <?php $idk = $d['kdprogram']; ?>
                                    <?php if (!in_array($idk, $idp)) :

                                        $sum_prog = sum_data($bulanberjalan, $d['kdprogram']);
                                    ?>
                                        <tr>
                                            <td class="tdprogram"><?= $noprog++ ?></td>
                                            <td class="tdprogram"><?php echo $idk; ?></td>
                                            <td class="tdprogram"><?php echo $d['nmprogram']; ?></td>
                                            <td class="tdprogram"><?php echo number_format($sum_prog->vol, 0, ',', '.'); ?></td>
                                            <td class="tdprogram"><?php echo "Paket Program"; ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->renk_b, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>'  . number_format($sum_prog->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->renf_b, 2, ',', '.') .  '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right "><?php echo '<b>' . ($sum_prog->renf_b == 0 ? "-" : number_format($sum_prog->rl_fis / $sum_prog->renf_b * 100, 2, ',', '.'))  . '</b>' ?></td>

                                        </tr>
                                        <?php $idp = array_merge([$idk], $idp); ?>
                                    <?php endif; ?>
                                    <?php $idk = $idk . '.' . $d['kdgiat']; ?>
                                    <?php if (!in_array($idk, $idp)) :

                                        $sum_giat = sum_data($bulanberjalan, $d['kdprogram'], $d['kdgiat']);
                                    ?>
                                        <tr>
                                            <td class="tdgiat"><b><?= $nogiat++ ?></b></td>
                                            <td class="tdgiat"><b><?php echo $idk; ?></b></td>
                                            <td class="tdgiat"><b><?php echo $d['nmgiat']; ?></b></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->vol, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat"><?php echo "<b>Paket Kegiatan</b>"; ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' .  number_format($sum_giat->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->renk_b, 2, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>'  . number_format($sum_giat->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->renf_b, 2, ',', '.')  .  '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right "><?php echo '<b>' . ($sum_giat->renf_b == 0 ? "-" : number_format($sum_giat->rl_fis / $sum_giat->renf_b * 100, 2, ',', '.'))  . '</b>' ?></td>
                                        </tr>
                                        <?php $idp = array_merge([$idk], $idp); ?>
                                    <?php endif; ?>

                                    <?php $idk = $idk . '.' . $d['kdoutput']; ?>
                                    <?php if (!in_array($idk, $ido)) :
                                        $sum_output = sum_data($bulanberjalan, $d['kdprogram'], $d['kdgiat'], $d['kdoutput']);

                                    ?>
                                        <tr>
                                            <td class="tdoutput"><b><?= $nooutput++ ?></b></td>
                                            <td class="tdoutput"><b><?php echo $idk; ?></b></td>
                                            <td class="tdoutput"><b><?php echo $d['nmoutput']; ?></b></td>
                                            <td class="tdoutput" style="text-align: right"><b><?php echo number_format($sum_output->vol, 0, ',', '.'); ?></b></td>
                                            <td class="tdoutput"><b><?php echo $d['toutputsat']; ?></b></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .   number_format($sum_output->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .   number_format($sum_output->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .    number_format($sum_output->renk_b, 2, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>'  . number_format($sum_output->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .   number_format($sum_output->renf_b, 2, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_output->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right "><?php echo '<b>' . ($sum_output->rl_fis == 0 ? "-" : number_format($sum_output->rl_fis / sum_data($bulanberjalan, $d['kdprogram'], $d['kdgiat'], $d['kdoutput'])->renf_b * 100, 2, ',', '.')) . '</b>' ?></td>
                                        </tr>
                                        <?php $ido = array_merge([$idk], $ido); ?>
                                    <?php endif; ?>

                                    <?php $idk = $idk . '.' . $d['kdsoutput']; ?>
                                    <?php if (!in_array($idk, $idso)) :
                                        $sum_soutput = sum_data($bulanberjalan, $d['kdprogram'], $d['kdgiat'], $d['kdoutput'], $d['kdsoutput']); ?>
                                        <tr>
                                            <td class="tdsoutput"><b><?= $nosoutput++ ?></b></td>
                                            <td class="tdsoutput"><b><?php echo $idk; ?></b></td>
                                            <td class="tdsoutput"><b><?php echo $d['nmro']; ?></b></td>
                                            <td class="tdsoutput" style="text-align: right"><b><?php echo number_format($sum_soutput->vol, 0, ',', '.'); ?></b></td>
                                            <td class="tdsoutput"><b><?php echo $d['sat']; ?></b></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_soutput->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_soutput->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' .  number_format($sum_soutput->renk_b, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_soutput->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' .  number_format($sum_soutput->renf_b, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' .  number_format($sum_soutput->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' . ($sum_soutput->renf_b == 0 ? "-" : number_format($sum_soutput->rl_fis / $sum_soutput->renf_b * 100, 2, ',', '.'))  . '</b>' ?></td>
                                        </tr>
                                        <?php $idso = array_merge([$idk], $idso); ?>
                                    <?php endif;

                                    ?>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
    var $th = $('.tableFixHead1').find('thead th')
    $('.tableFixHead1').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    })

    // $("#search").click(function() {
    //     window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
    // });

    $("#searchkeyword").click(function() {
        window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val() + "/" + $('#keyword').val();
    });
</script>
</script>
<?= $this->endSection() ?>