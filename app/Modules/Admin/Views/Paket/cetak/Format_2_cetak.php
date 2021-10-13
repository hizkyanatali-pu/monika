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
    <thead class="bg-white">
        <tr class="text-center">
            <th>&nbsp;</th>
            <th class="unit_kerja">&nbsp;</th>
            <th class="paket">&nbsp;</th>
            <th class="pagu-main" colspan="5">Pagu (Rp)</th>
            <th class="progres" colspan="2">Progres (%)</th>
            <th class="deviasi" colspan="2">Deviasi</th>
        </tr>
        <tr class="text-center">
            <th>No.</th>
            <th class="unit_kerja"><?= $title; ?></th>
            <th class="paket">Jml&nbsp;Paket</th>
            <th class="pagu_rpm pagu">RPM</th>
            <th class="pagu_sbsn pagu">SBSN</th>
            <th class="pagu_phln pagu">PHLN</th>
            <th class="pagu_total pagu">TOTAL</th>
            <th class="pagu_realisasi pagu">Realisasi</th>
            <th class="keu">keu</th>
            <th class="fisik">fisik</th>
            <th class="percentage">%</th>
            <th class="rp">Rp</th>
        </tr>
    </thead>

    <tbody>
        <?php if ($qdata) : ?>
            <?php
            $total_real_total = $total_pagu_total = $total_pagu_rpm = $total_pagu_sbsn = $total_pagu_phln = $no =  0;
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
                    <td class="col-pagu_rpm"><?php echo number_format($data['jml_pagu_rpm'] / 1000, 0, ',', '.'); ?></td>
                    <td class="col-pagu_sbsn"><?php echo number_format($data['jml_pagu_sbsn'] / 1000, 0, ',', '.'); ?></td>
                    <td class="col-pagu_phln"><?php echo number_format($data['jml_pagu_phln'] / 1000, 0, ',', '.'); ?></td>
                    <td class="col-pagu_total"><?php echo number_format($data['jml_pagu_total'] / 1000, 0, ',', '.'); ?></td>
                    <td class="col-pagu_realisasi"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>
                    <td class="col-keu"><?php echo number_format($data['jml_progres_keuangan'], 2, ',', '.'); ?></td>
                    <td class="col-fisik"><?php echo number_format($data['jml_progres_fisik'], 2, ',', '.'); ?></td>
                    <td class="col-percentage"><?php echo ($data['jml_progres_fisik'] > $data['jml_progres_keuangan'] ? number_format($data['jml_persen_deviasi'], 2, ',', '.') : '-'); ?></td>
                    <td class="col-rp"><?php echo ($data['jml_progres_fisik'] > $data['jml_progres_keuangan'] ? number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.') : '-'); ?></td>
                </tr>
                <?php
                $total_pagu_rpm += $data['jml_pagu_rpm'];
                $total_pagu_sbsn += $data['jml_pagu_sbsn'];
                $total_pagu_phln += $data['jml_pagu_phln'];
                $total_pagu_total += $data['jml_pagu_total'];
                $total_real_total += $data['jml_real_total'];
                ?>
            <?php endforeach; ?>
            <tr style="background-color: #6ab6e6; font-color: #000;">
                <td colspan="2"><b>TOTAL</b></td>
                <td></td>
                <td class="col-pagu_rpm"><b><?php echo number_format($total_pagu_rpm / 1000, 0, ',', '.'); ?></b></td>
                <td class="col-pagu_sbsn"><b><?php echo number_format($total_pagu_sbsn / 1000, 0, ',', '.'); ?></b></td>
                <td class="col-pagu_phln"><b><?php echo number_format($total_pagu_phln / 1000, 0, ',', '.'); ?></b></td>
                <td class="col-pagu_total"><b><?php echo number_format($total_pagu_total / 1000, 0, ',', '.'); ?></b></td>
                <td class="col-pagu_realisasi"><b><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></b></td>
                <td colspan="4" class="last-col"></td>
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
    //pagu section
    let pagu_counter = $("table .pagu").length

    if ($("table .pagu_rpm").is(":hidden")) {

        pagu_counter--;
    }
    if ($("table .pagu_sbsn").is(":hidden")) {

        pagu_counter--;
    }
    if ($("table .pagu_phln").is(":hidden")) {

        pagu_counter--;
    }
    if ($("table .pagu_total").is(":hidden")) {

        pagu_counter--;
    }
    if ($("table .pagu_realisasi").is(":hidden")) {

        pagu_counter--;
    }
    if($("table .pagu_rpm").is(":hidden") && $("table .pagu_sbsn").is(":hidden") && $("table .pagu_phln").is(":hidden") && $("table .pagu_total").is(":hidden") && $("table .pagu_realisasi").is(":hidden")){

        $(".pagu-main").hide()
    }else{

        $(".pagu-main").show()
        $(".pagu-main").attr("colspan", pagu_counter)
    }
    //pagu end section
    if($("table .keu").is(":hidden") && $("table .fisik").is(":hidden")){

        $("table .progres").hide()
    }
    if($("table .percentage").is(":hidden") && $("table .rp").is(":hidden")){

        $("table .deviasi").hide()
    }
    if($("table .keu").is(":hidden") && $("table .fisik").is(":hidden") && $("table .percentage").is(":hidden") && $("table .rp").is(":hidden")){

        $("table .last-col").hide()
    }else{

        $("table .last-col").show()
    }
    // window.print()
</script>