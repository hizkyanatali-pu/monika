<style>
    .stw0 {
        background-color: #ff4da6;
        color: #FFF !important;
    }

    table,
    th,
    td {
        text-align: center;
        border: 1px solid #000;
    }

    #toolbarContainer {

        display: none !important;
    }

    @media print{
        @page {
            size: landscape
        }

        .section-table { 
            page-break-after: always; }
    }
</style>

<?php 
    foreach ($qdata as $keyQdata => $dataQdata) : 
        $isTemplateSatkerTertinggi = $dataQdata['template'] == "satker_tertinggi";
?>
    <div class="section-table">
        <div style="text-align: center;">
            <h2><?php echo $dataQdata['title'] ?></h2>
        </div>
        <table style="margin-bottom: 50px;">
            <thead class="text-center text-white" style="background-color: #1562aa;">
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

<?php echo script_tag('js/jquery.js'); ?>
<script>
    window.print()
</script>