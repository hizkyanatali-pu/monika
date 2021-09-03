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
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="">

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
                                for ($index = 0; $index < $bulansekarang; $index++) { ?>
                                    <option value="<?= encrypt_url($noBulan) ?>" <?= ($bulanberjalan ==  $noBulan ? 'selected="selected"' : '') ?>> <?= $namaBulan[$index] ?> </option>;
                                <?php
                                    $noBulan++;
                                }
                                ?>
    
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="search"><div class="fa fa-search"></div> </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 text-right mt-3">
                        <div class="form-group">
                            <a href="<?= site_url('Kinerja-Output-Bulanan/') . $uri->getSegment(2) . "?exp=xlxs" ?>" class="btn btn-success btn-sm text-white" target="_blank"><i class="fa fa-file-excel"></i>Excel</a>
                            <b>*Dalam Ribuan</b>
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
                                $colspan = 12;
                                $noprogram = 1;
                                $nogiat = 1;
                                $nooutput = 1;
                                $nosoutput = 1;
                                $nArraysoutput =  unique_multidim_array($qdata, "kode", "kode", "vol", "pg", "rtot", "rr_b", "renk_b", "renf_b", "ff_b", 'jumlah_data', 'ufis');
    
                                // print_r($nArraysoutput);
                                // exit;
                                ?>
    
    
                                <?php foreach ($nArraysoutput as $key => $d) :    ?>
    
                                    <?php $idk = $d['kdprogram'];
                                    $jumlah_data = isset($d['jumlah_data']) ? $d['jumlah_data'] : 1;
                                    ?>
    
                                    <?php if (!in_array($idk, $idp)) :
    
                                        $pg_program = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'programpg');
                                        $realisasi_program = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'programrealisasi', ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                                        $keu_rn_program = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'programkeu_rn', '0', 'pkt.renk_b' . $bulanberjalan) / $pg_program * 100;
                                        $keu_rl_program = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'programkeu_rl', ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                                        $fis_rn_program = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'programfis_rn', 'pkt.renf_b' . $bulanberjalan);
                                        $fis_rl_program =  gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'programfis_rl', ($bulanberjalan == $bulansekarang ?  'pkt.ufis'  : 'pkt.ff_b' . $bulanberjalan));
    
                                    ?>
                                        <tr>
                                            <td class="tdprogram"><?= $noprogram ?></td>
                                            <td class="tdprogram"><?php echo '<b>' . $idk . '</b>'; ?></td>
                                            <td class="tdprogram"><?= '<b>' . $d['nmprogram'] . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right;"><?= '<b>' .   str_replace('.', ',', round(gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'programvol'), 2))  . '</b>' ?></td>
                                            <td class="tdprogram"><?= '<b>' . "Paket Program" . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right;"><?php echo '<b>' . number_format($pg_program / 1000, 0, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right;"><?php echo '<b>' . number_format($realisasi_program / 1000, 0, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($keu_rn_program, 2)) . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($keu_rl_program, 2)) . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($fis_rn_program, 2)) . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($fis_rl_program, 2)) . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right "><?php echo '<b>' .   ($fis_rn_program != 0 ?  str_replace('.', ',', round($fis_rl_program / $fis_rn_program, 2)) : '~') . '</b>' ?></td>
    
                                        </tr>
                                        <?php $idp = array_merge([$idk], $idp); ?>
                                    <?php endif; ?>
    
                                    <?php $idk =  $d['kdgiat'];   ?>
                                    <?php if (!in_array($idk, $ido)) :
    
                                        $pg_giat = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'kegiatanpg');
                                        $realisasi_giat = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'kegiatanrealisasi', ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                                        $keu_rn_giat = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'kegiatankeu_rn', 'pkt.renk_b' . $bulanberjalan);
                                        $keu_rl_giat = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'kegiatankeu_rl', ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                                        $fis_rn_giat = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'kegiatanfis_rn', 'pkt.renf_b' . $bulanberjalan);
                                        $fis_rl_giat =  gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'kegiatanfis_rl', ($bulanberjalan == $bulansekarang ?  'pkt.ufis'  : 'pkt.ff_b' . $bulanberjalan));
    
                                    ?>
                                        <tr>
                                            <td class="tdgiat"><?= $nogiat++ ?></td>
                                            <td class="tdgiat"><?php echo $idk; ?></td>
                                            <td class="tdgiat"><?= '<b>' . $d['nmgiat'] . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?= '<b>' . str_replace('.', ',', round(gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'kegiatanvol'), 2))  . '</b>' ?></td>
                                            <td class="tdgiat"><?= '<b>' . "Paket Kegiatan" . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($pg_giat / 1000, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($realisasi_giat / 1000, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($keu_rn_giat, 2)) . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($keu_rl_giat, 2)) . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($fis_rn_giat, 2)) . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($fis_rl_giat, 2)) . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right "><?php echo '<b>' .   ($fis_rn_giat != 0 ?  str_replace('.', ',', round($fis_rl_giat / $fis_rn_giat, 2)) : '~') . '</b>' ?></td>
                                        </tr>
                                        <?php $ido = array_merge([$idk], $ido); ?>
                                    <?php endif; ?>
    
    
                                    <?php $idk = $idk . '.' . $d['kdoutput']; ?>
                                    <?php if (!in_array($idk, $ido)) :
                                        $pg_output = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'outputpg');
                                        $realisasi_output = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'outputrealisasi', ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                                        $keu_rn = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'outputkeu_rn', 'pkt.renk_b' . $bulanberjalan);
                                        $keu_rl = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'outputkeu_rl', ($bulanberjalan == $bulansekarang ?  'pkt.rtot'  : 'rr_b' . $bulanberjalan));
                                        $fis_rn = gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'outputfis_rn', 'pkt.renf_b' . $bulanberjalan);
                                        $fis_rl =  gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'outputfis_rl', ($bulanberjalan == $bulansekarang ?  'pkt.ufis'  : 'pkt.ff_b' . $bulanberjalan));
    
                                    ?>
                                        <tr>
                                            <td class="tdoutput"><?= $nooutput++ ?></td>
                                            <td class="tdoutput"><?php echo $idk; ?></td>
                                            <td class="tdoutput"><?= '<b>' . $d['nmoutput'] . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?= '<b>' . str_replace('.', ',', round(gettotal($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], 'outputvol'), 2)) . '</b>' ?></td>
                                            <td class="tdoutput"><?= '<b>' . $d['sat'] . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' . number_format($pg_output / 1000, 0, ',', '.') . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' . number_format($realisasi_output / 1000, 0, ',', '.') . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($keu_rn, 2)) . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($keu_rl, 2)) . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($fis_rn, 2)) . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .  str_replace('.', ',', round($fis_rl, 2)) . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right "><?php echo '<b>' .   ($fis_rn != 0 ?  str_replace('.', ',', round($fis_rl / $fis_rn, 2)) : '~') . '</b>' ?></td>
                                        </tr>
                                        <?php $ido = array_merge([$idk], $ido); ?>
                                    <?php endif; ?>
    
                                    <?php $idk = $idk . '.' . $d['kdsoutput']; ?>
                                    <?php if (!in_array($idk, $ido)) :
                                        $realisasi = ($bulanberjalan == $bulansekarang ?  $d['rtot']  : $d['rr_b']);
                                        $rl_keu = ($bulanberjalan == $bulansekarang ?  round($d['rtot'] / $d['pg'] * 100 / $jumlah_data, 2) : round($d['rr_b'] / $d['pg'] * 100 / $jumlah_data, 2));
                                        $rl_fis = ($bulanberjalan == $bulansekarang ?  round($d['ufis'] / $d['pg'] * 100 / $jumlah_data, 2)  : round($d['ff_b'] / $d['pg'] * 100 / $jumlah_data, 2));
    
                                    ?>
                                        <tr>
                                            <td class="tdsoutput"><?= $nosoutput++ ?></td>
                                            <td class="tdsoutput"><?php echo $idk; ?></td>
                                            <td class="tdsoutput"><?= '<b>' . getoutputname($dbuse, 'FC', $d['kdgiat'], $d['kdoutput'], $d['kdsoutput']) . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right;"><?= '<b>' . str_replace('.', ',', round(str_replace(",", ".", $d['vol']), 2)) . '</b>' ?></td>
                                            <td class=" tdsoutput"><?= '<b>' . $d['sat'] . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' . number_format($d['pg'] / 1000, 0, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' . number_format($realisasi / 1000, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' . str_replace('.', ',', round($d['renk_b'] / $d['pg'] * 100 / $jumlah_data, 2))  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' .   str_replace('.', ',', $rl_keu)  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' .   str_replace('.', ',', round($d['renf_b'] / $jumlah_data, 2))   . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' .    str_replace('.', ',', $rl_fis)  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' .   ($d['renf_b'] != 0 ?  str_replace('.', ',', round($rl_fis / $d['renf_b'], 2)) : '~') . '</b>' ?></td>
    
                                        </tr>
                                        <?php $ido = array_merge([$idk], $ido); ?>
                                    <?php endif; ?>
    
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

    $("#search").click(function() {
        window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
    });
</script>
</script>
<?= $this->endSection() ?>