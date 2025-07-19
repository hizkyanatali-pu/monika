<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monika-Kinerja-Output-Bulanan</title>
    <style>
        .tableFixHead1 {
            overflow-y: auto;
            height: 600px;
        }

        .tableFixHead1 thead th {
            /* position: sticky; */
            top: 0;
            border: 1px solid #333;

        }


        .table td,
        th {
            padding: 2px;
            border: 1px solid #333;
        }

        th {
            background-color: #68218b;
            color: #fff;
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
            width: 25px;
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

        .tdsatker {
            background-color: #eee6ff;
        }

        .tdprogram {
            background-color: gold;
        }

        .tdgiat {
            background-color: #9bc2e6;
        }

        .tdoutput {
            background-color: #ddebf7;
        }

        .tdsoutput {
            background-color: #ffff;
        }

        .tdkomponen {
            background-color: #aa80ff;
        }
    </style>
</head>

<body>
    <?php $colspan = 12;
    $uri = current_url(true);
    $bulanberjalan = decrypt_url($uri->getSegment(2)); // 3
    $bulansekarang = date('n'); ?>

    <table class="table table-bordered" border="1">
        <thead>
            <tr border="0">
                <td colspan="<?= $colspan ?>" style="text-align: center;border:0;"><b>Kinerja Output Bulanan</b></td>
            </tr>
            <tr border="0">
                <td colspan="<?= $colspan ?>" style="text-align: center;border:0;"><b>Bulan <?= bulan($bulanberjalan) . ' ' . date('Y') ?></b></td>
            </tr>
            <tr border="0">
                <td colspan="<?= $colspan ?>" style="text-align: right;border:0;"> <b style="color: red;">*Dalam Ribuan</b></td>
            </tr>
            <tr class="text-center theader">
                <th class="thNo theader" rowspan="2">No</th>
                <th class="thKode theader" rowspan="2">Kode</th>
                <th class="thLabel theader" rowspan="2">Program/Kegiatan/KRO/RO</th>
                <th class="thNilai theader" rowspan="2">Target</th>
                <th class="thNilai theader" rowspan="2">Satuan</th>
                <th class="thNilai theader" rowspan="2">Pagu (Rp Ribu)</th>
                <th class="thNilai theader" rowspan="2">Realisasi (Rp Ribu)</th>
                <th class="thPersen theader" colspan="2">Keuangan (%)</th>
                <th class="thPersen theader" colspan="3">Fisik (%)</th>
            </tr>
            <tr class=" text-center theader">
                <th class="thPersen theader">RN</th>
                <th class="thPersen theader">RL</th>
                <th class="thPersen theader">RN</th>
                <th class="thPersen theader">RL</th>
                <th class="thPersen theader">Kinerja</th>
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

                        $sum_prog = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram']);
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

                        $sum_giat = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram'], $d['kdgiat']);
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
                        $sum_output = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram'], $d['kdgiat'], $d['kdoutput']);

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
                            <td class="tdoutput" style="text-align: right "><?php echo '<b>' . ($sum_output->renf_b == 0 ? "-" : number_format($sum_output->rl_fis / $sum_output->renf_b * 100, 2, ',', '.')) . '</b>' ?></td>

                        </tr>
                        <?php $ido = array_merge([$idk], $ido); ?>
                    <?php endif; ?>

                    <?php $idk = $idk . '.' . $d['kdsoutput']; ?>
                    <?php if (!in_array($idk, $idso)) :
                        $sum_soutput = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram'], $d['kdgiat'], $d['kdoutput'], $d['kdsoutput']); ?>
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
</body>

</html>