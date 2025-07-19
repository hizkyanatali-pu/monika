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
    </style>
</head>

<body>
    <?php $colspan = 11; ?>

    <table class="table table-bordered">
        <tr>
            <th align="center" colspan="<?= $colspan; ?>" style="border:0px;">
                <h3>Progres Keuangan dan Fisik - Progres Per Provinsi</h3>
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
                <th><?= number_format($pagusda_progres_keuangan, 2, ',', '.'); ?>%</th>
            </tr>
            <tr>
                <td>Progres Fisik SDA</td>
                <th><?= number_format($pagusda_progres_fisik, 2, ',', '.'); ?>%</th>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="1">
        <thead>
            <tr class="text-center theader">
                <th rowspan="2">Progres Per Provinsi</th>
                <th rowspan="2">Satker</th>
                <th class="tdNilai" rowspan="2">Jml&nbsp;Paket
                    <!-- <br /><small title="Pagu SDA">Total SDA <i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-right"></i></small> -->
                </th>

                <th colspan="4">Pagu (Rp)</th>
                <th colspan="4">Realisasi (Rp)</th>
                <th colspan="2">Progres (%)</th>
                <th colspan="2">Deviasi</th>
            </tr>
            <tr class="text-center theader">

                <th class="tdNilai">RPM</th>
                <th class="tdNilai">SBSN</th>
                <th class="tdNilai">PLN</th>
                <th class="tdNilai">TOTAL</th>

                <th class="tdNilai">RPM</th>
                <th class="tdNilai">SBSN</th>
                <th class="tdNilai">PLN</th>
                <th class="tdNilai">TOTAL</th>

                <th class="tdPersen">keu</th>
                <th class="tdPersen">fisik</th>

                <th class="tdPersen">%</th>
                <th class="tdNilai">Rp</th>
            </tr>
        </thead>
        <tbody id="tbody-utama">
                            <?php if ($qdata) : ?>
                                <?php
                                $total_paket = 0;
                                $total_pagu_rpm = 0;
                                $total_pagu_sbsn = 0;
                                $total_pagu_phln = 0;
                                $total_pagu_total = 0;

                                $total_real_rpm = 0;
                                $total_real_sbsn = 0;
                                $total_real_phln = 0;
                                $total_real_total = 0;
                                $total_deviasi = 0;

                                ?>
                                <?php
                                foreach ($qdata as $key => $data) : ?>

                                    <!-- balai -->
                                    <tr class="stw<?= $data['stw']; ?>  <?php if (array_key_exists('is_subheader', $data)) echo 'bg-secondary font-weight-bold' ?>">
                                        <td class="tdKodeLabel col-unit_kerja">
                                            <?php echo $data['label']; ?>
                                        </td>
                                        <td class="tdNilai text-center col-paket"><?= $data['st'] ?></td>
                                        <td class="tdNilai text-center col-paket"><?php echo $data['jml_paket']; ?></td>

                                        <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_pagu_rpm'] / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_pagu_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_pagu_phln'] / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_pagu_total'] / 1000, 0, ',', '.'); ?></td>

                                            <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_real_rpm'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_real_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_real_phln'] / 1000, 0, ',', '.'); ?></td>
                                            <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>


                                        <td class="tdPersen text-right col-keu">
                                            <?php echo number_format($data['jml_progres_keuangan'], 2, ',', '.'); ?>
                                        </td>
                                        <td class="tdPersen text-right col-fisik"><?php echo number_format($data['jml_progres_fisik'], 2, ',', '.'); ?></td>

                                        <td class="tdPersen text-right col-percentage">
                                            <?php echo number_format($data['jml_persen_deviasi'], 2, ',', '.'); ?>
                                        </td>
                                        <td class="tdPersen text-right col-rp">
                                            <?php echo number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $total_paket += $data['jml_paket'];
                                    $total_pagu_rpm += $data['jml_pagu_rpm'];
                                    $total_pagu_sbsn += $data['jml_pagu_sbsn'];
                                    $total_pagu_phln += $data['jml_pagu_phln'];
                                    $total_pagu_total += $data['jml_pagu_total'];

                                    $total_real_rpm += $data['jml_real_rpm'];
                                    $total_real_sbsn += $data['jml_real_sbsn'];
                                    $total_real_phln += $data['jml_real_phln'];
                                    $total_real_total += $data['jml_real_total'];

                                    $total_deviasi += $data['jml_nilai_deviasi'];


                                    ?>
                                <?php endforeach; ?>
                                <tr style="background-color:#ccb3ff; border:2px solid #ccc;">
                                    <td class="text-center">TOTAL</td>
                                    <th class="satker_">&nbsp;</th>
                                    <td class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_pagu_rpm / 1000, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_pagu_sbsn / 1000, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_pagu_phln / 1000, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_total"><?php echo number_format($total_pagu_total / 1000, 0, ',', '.'); ?></td>

                                        <!-- <td class="text-right"><?php echo number_format($total_paket, 0, ',', '.'); ?></td> -->
                                        <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_real_rpm / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_real_sbsn / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_real_phln / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>
                                    



                                    <td colspan="4" class="tdPersen text-right last-col">&nbsp;</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
    </table>

    <?php if ($dokumen_target == "pdf") { ?>
        <script>
            window.print();
        </script>
    <?php } ?>
</body>

</html>