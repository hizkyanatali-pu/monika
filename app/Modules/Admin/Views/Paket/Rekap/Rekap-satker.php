<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap-Monika</title>
    <style>
        .tableFixHead {
            overflow-y: auto;
            height: 600px;
        }

        .tableFixHead thead th {
            position: sticky;
            top: 0;
            border: 1px solid #333;
        }

        /* Just common table stuff. Really. */
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        /*th, td { padding: 8px 16px; }*/
        .table th {
            background: #f5f5f5;
        }
    </style>
    <style>
        
        .table td,
        th {
            padding: 2px;
            font-size: 12px;
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

        .stw0 {
            background-color: #ff4da6;
            color: #FFF;
        }

        .stw1 {}

        .section-table thead tr th {
            background: #1562aa;
            color: #FFFFFF;
        }
        
        .section-table tbody tr td { 
            border: 1px solid #000; }
    </style>
</head>

<body>
    <?php $colspan = 11; ?>

    <table class="table table-bordered">
        <tr>
            <th align="center" colspan="<?= $colspan; ?>" style="border:0px;">
                <h3>Progres Keuangan dan Fisik - <?= $title; ?></h3>
            </th>
        </tr>
        <tr>
            <th align="center" colspan="<?= $colspan; ?>" style="border:0px;">
                <h3><?= $label; ?></h3>
            </th>
        </tr>
        <tr>
            <td colspan="<?= $colspan; ?>">&nbsp;</td>
        </tr>
    </table>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Progres Keungan SDA</td>
                <th><?= number_format($qdata[0]['data'][0]['pagusda_progres_keuangan'], 2, ',', '.'); ?>%</th>
            </tr>
            <tr>
                <td>Progres Fisik SDA</td>
                <th><?= number_format($qdata[0]['data'][0]['pagusda_progres_fisik'], 2, ',', '.'); ?>%</th>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <?php 
        foreach ($qdata as $keyQdata => $dataQdata) : 
            $isTemplateSatkerTertinggi = $dataQdata['template'] == "satker_tertinggi";
    ?>
        <div class="section-table">
            <div style="text-align: center;">
                <h2><?php echo $dataQdata['title'] ?></h2>
            </div>
            <table style="margin-bottom: 50px;">
                <thead class="text-center text-white">
                    <tr class="text-center">
                        <!-- <th colspan="2">&nbsp;</th> -->
                        <th class="">&nbsp;</th>
                        <th class="">&nbsp;</th>
                        <th class="">&nbsp;</th>
                        <th class="" colspan="<?php echo $isTemplateSatkerTertinggi ? '5' : '4'; ?>">Pagu (Rp)</th>
                        <?php if (! $isTemplateSatkerTertinggi) { ?>
                            <th class="" colspan="4">Realisasi (Rp)</th>
                        <?php } ?>
                        <th class="progres" colspan="2">Progres (%)</th>
                        <th class="deviasi" colspan="2">Deviasi</th>
                    </tr>
                    <tr class="text-center">
                        <th class="unit_kerja"><?php echo $dataQdata['firstFieldTitle'] ?></th>
                        <th class="satker_">Satker</th>
                        <th class="tdNilai paket">Jml Paket</th>
                        <th class="tdNilai pagu_rpm pagu">RPM</th>
                        <th class="tdNilai pagu_sbsn pagu">SBSN</th>
                        <th class="tdNilai pagu_phln pagu">PHLN </th>
                        <th class="tdNilai pagu_total pagu">TOTAL </th>
                        <?php if ($isTemplateSatkerTertinggi) { ?>
                            <th class="tdNilai pagu_total pagu">Realisasi </th>
                        <?php } ?>
                        <?php if (! $isTemplateSatkerTertinggi) { ?>
                            <th class="tdNilai pagu_rpm pagu">RPM </th>
                            <th class="tdNilai pagu_sbsn pagu">SBSN </th>
                            <th class="tdNilai pagu_phln pagu">PHLN </th>
                            <th class="tdNilai pagu_total pagu">TOTAL </th>
                        <?php } ?>
                        <th class="tdPersen keu">keu</th>
                        <th class="tdPersen fisik">fisik</th>
                        <th class="tdPersen percentage">%</th>
                        <th class="tdNilai rp">Rp</th>
                    </tr>
                </thead>

                <tbody id="tbody-utama">
                    <?php
                        $total_pagu_rpm = 0;
                        $total_pagu_sbsn = 0;
                        $total_pagu_phln = 0;
                        $total_pagu_total = 0;

                        $total_real_rpm = 0;
                        $total_real_sbsn = 0;
                        $total_real_phln = 0;
                        $total_real_total = 0;
                        $total_paket = 0;
                    ?>
                    <?php
                    foreach ($dataQdata['data'] as $key => $data) : ?>
                        <!-- balai -->
                        <tr class="stw<?= $data['stw']; ?>">
                            <td class="tdKodeLabel col-unit_kerja">
                                <?php echo $data['label']; ?>
                            </td>
                            <?= '<td class="tdNilai text-center col-paket">' . $data['st'] . '</td>' ?>
                            <td class="tdNilai text-center col-paket"><?php echo $data['jml_paket']; ?></td>

                            <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_pagu_rpm'] / 1000, 0, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_pagu_sbsn'] / 1000, 0, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_pagu_phln'] / 1000, 0, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_pagu_total'] / 1000, 0, ',', '.'); ?></td>

                            <?php if ($isTemplateSatkerTertinggi) { ?>
                                <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>
                            <?php } ?>

                            <?php if (! $isTemplateSatkerTertinggi) { ?>
                                <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_real_rpm'] / 1000, 0, ',', '.'); ?></td>
                                <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_real_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_real_phln'] / 1000, 0, ',', '.'); ?></td>
                                <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>
                            <?php } ?>

                            <td class="tdPersen text-right col-keu"><?php echo number_format($data['jml_progres_keuangan'], 2, ',', '.'); ?></td>
                            <td class="tdPersen text-right col-fisik"><?php echo number_format($data['jml_progres_fisik'], 2, ',', '.'); ?></td>

                            <td class="tdPersen text-right col-percentage"><?php echo  number_format($data['jml_persen_deviasi'], 2, ',', '.'); ?></td>
                            <td class="tdPersen text-right col-rp"><?php echo  number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                            $total_pagu_rpm += $data['jml_pagu_rpm'];
                            $total_pagu_sbsn += $data['jml_pagu_sbsn'];
                            $total_pagu_phln += $data['jml_pagu_phln'];
                            $total_pagu_total += $data['jml_pagu_total'];

                            $total_real_rpm += $data['jml_real_rpm'];
                            $total_real_sbsn += $data['jml_real_sbsn'];
                            $total_real_phln += $data['jml_real_phln'];
                            $total_real_total += $data['jml_real_total'];
                            $total_paket += $data['jml_paket'];
                        ?>
                    <?php endforeach; ?>
                    <tr class="text-center text-white" style="background-color: #1562aa;">
                        <td class="text-center">TOTAL</td>
                        <th class="satker_">&nbsp;</th>
                        <td class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td>
                        <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_pagu_rpm / 1000, 0, ',', '.'); ?></td>
                        <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_pagu_sbsn / 1000, 0, ',', '.'); ?></td>
                        <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_pagu_phln / 1000, 0, ',', '.'); ?></td>
                        <td class="tdNilai text-right col-pagu_total"><?php echo number_format($total_pagu_total / 1000, 0, ',', '.'); ?></td>

                        <?php if ($isTemplateSatkerTertinggi) { ?>
                            <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>
                        <?php } ?>

                        <?php if (! $isTemplateSatkerTertinggi) { ?>
                        <!-- <td class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td> -->
                            <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_real_rpm / 1000, 0, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_real_sbsn / 1000, 0, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_real_phln / 1000, 0, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>
                        <?php } ?>

                        <?php if ($isTemplateSatkerTertinggi) { ?>
                            <td class="tdNilai text-right col-prog"><?php echo number_format($total_real_total / $total_pagu_total * 100, 2, ',', '.'); ?></td>
                        <?php } ?>

                        <td colspan="<?php echo $isTemplateSatkerTertinggi ? '3' : '4'; ?>" class="tdPersen text-right last-col">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</body>

</html>