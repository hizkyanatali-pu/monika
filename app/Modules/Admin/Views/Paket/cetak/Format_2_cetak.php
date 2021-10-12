<?php

$data_filter = $_GET['filter'];
$filter = explode(",", $data_filter);

?>
<style>
    .stw0 {
        background-color: #ff4da6;
        color: #FFF !important;
    }
    table, th, td{
        text-align: center;
        border: 1px solid #000;
    }
    #toolbarContainer{

        display: none !important;
    }
    /* @media print{
        .no-print, .no-print *{
            display: none !important;
        }
    } */
</style>
<table>
    <thead>
        <tr style="background-color: #6ab6e6; font-color: #000;">
            <th rowspan="2">No.</th>
            <th class="unit_kerja" rowspan="2">Nama Unit Kerja</th>
            <th class="paket" rowspan="2">Jml&nbsp;Paket</th>
            <th class="pagu_total" rowspan="2">PAGU (Rp. Milyar)</th>
            <th class="pagu_realisasi" rowspan="2">Realisasi (Rp. Milyar)</th>
            <th class="progres" colspan="2">Progres</th>
            <th class="deviasi" colspan="2">Deviasi</th>
        </tr>
        <tr style="background-color: #6ab6e6; font-color: #000;">
            <th class="keu">KEU (%)</th>
            <th class="fisik">FIS (%)</th>
            <th class="percentage">%</th>
            <th class="rp">Rp</th>
        </tr>
    </thead>

    <tbody>
        <?php if ($qdata) : ?>
            <?php
            $total_real_total = $total_pagu_total = $total_deviasi = $keu = $fis = $total_deviasi_percentage = $no =  0;
            ?>
            <?php
            foreach ($qdata as $key => $data) : ?>

                <!-- balai -->
                <tr class="stw<?= $data['stw']; ?>">
                    <td><?= ++$no; ?></td>
                    <td class="col-unit_kerja" style="text-align: left;">
                        <?php echo $data['label']; ?>
                    </td>
                    <td class="col-paket"><?php echo $data['jml_paket']; ?></td>
                    <td class="col-pagu_total"><?php echo number_format($data['jml_pagu_total'] / 1000, 0, ',', '.'); ?></td>
                    <td class="col-pagu_realisasi"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>
                    <td class="col-keu"><?php echo number_format($data['jml_progres_keuangan'], 2, ',', '.'); ?></td>
                    <td class="col-fisik"><?php echo number_format($data['jml_progres_fisik'], 2, ',', '.'); ?></td>
                    <td class="col-percentage"><?php echo ($data['jml_progres_fisik'] > $data['jml_progres_keuangan'] ? number_format($data['jml_persen_deviasi'], 2, ',', '.') : '-'); ?></td>
                    <td class="col-rp"><?php echo ($data['jml_progres_fisik'] > $data['jml_progres_keuangan'] ? number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.') : '-'); ?></td>
                </tr>
                <?php
                $total_pagu_total += $data['jml_pagu_total'];
                $total_real_total += $data['jml_real_total'];
                $total_deviasi += $data['jml_nilai_deviasi'];
                $total_deviasi_percentage += $data['jml_persen_deviasi'];
                $keu += $data['jml_progres_keuangan'];
                $fis += $data['jml_progres_fisik'];
                ?>
            <?php endforeach; ?>
            <tr style="background-color: #6ab6e6; font-color: #000;">
                <td colspan="2"><b>TOTAL</b></td>
                <td class="col-last_col"></td>
                <td class="col-pagu_total"><b><?php echo number_format($total_pagu_total / 1000, 0, ',', '.'); ?></b></td>
                <td class="col-pagu_realisasi"><b><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></b></td>
                <td class="col-keu"><b><?php echo number_format($keu/ 100, 2, ',', '.'); ?></b></td>
                <td class="col-fisik"><b><?php echo number_format($fis/ 100, 2, ',', '.'); ?></b></td>
                <td class="col-percentage"><b><?php echo number_format($total_deviasi_percentage/ 100, 2, ',', '.'); ?></b></td>
                <td class="col-rp"><b><?php echo number_format($total_deviasi / 1000, 0, ',', '.'); ?></b></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo script_tag('js/jquery.js'); ?>
<script>
    const filter = <?= json_encode($filter) ?>;
    filter.forEach((val, key) => {

        $("table ."+val).hide()
        $("table .col-"+val).hide()
    })
    if($("table .keu").is(":hidden") && $("table .fisik").is(":hidden")){

        $("table .progres").hide()
    }
    if($("table .percentage").is(":hidden") && $("table .rp").is(":hidden")){

        $("table .deviasi").hide()
    }
    if($("table .unit_kerja").is(":hidden") || $("table .paket").is(":hidden")){

        $("table .col-last_col").hide()
    }
    // window.print()
</script>