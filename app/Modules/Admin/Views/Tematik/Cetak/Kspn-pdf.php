<?php

$data_filter = $_GET['filter'];
$filter = explode(",", $data_filter);

?>
<style>
    .tdprogram {
        background-color: gold;
    }
    .bg-purple {
        background-color: #68218b;
        color: #fff;
    }
    table, th, td{
        text-align: center;
        border: 1px solid #000;
        font-size: 11pt;
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

<table style="width: 100%;">
    <thead>
        <tr class="bg-purple">
            <th rowspan="2">No</th>
            <th class="satker" rowspan="2">Satker / Paket</th>
            <th class="target-main" colspan="2">Target</th>
            <th class="lokasi" rowspan="2">Lokasi</th>
            <th class="pagu-main" colspan="3">Pagu</th>
            <th class="realisasi-main" colspan="3">Realisasi</th>
            <th class="progres-main" colspan="2">Progres (%)</th>
        </tr>
        <tr class="bg-purple">
            <th class="target_vol target">Vol</th>
            <th class="target_satuan target">Satuan</th>
            <th class="pagu_rpm pagu">RPM</th>
            <th class="pagu_phln pagu">PHLN</th>
            <th class="pagu_total pagu">Total</th>
            <th class="realisasi_rpm realisasi">RPM</th>
            <th class="realisasi_phln realisasi">PHLN</th>
            <th class="realisasi_total realisasi">Total</th>
            <th class="progres_keu progres">Keu</th>
            <th class="progres_fis progres">Fis</th>
        </tr>
    </thead>

    <tbody id="tbody-utama">
        <?php 
            $no = 1;
            $totalVol            = 0;
            $totalPaguRpm        = 0;
            $totalPaguPhln       = 0;
            $totalPaguTotal      = 0;
            $totalRealisasiRpm   = 0;
            $totalRealisasiPhln  = 0;
            $totalRealisasiTotal = 0;
            $totalKeu            = 0;
            $totalFis            = 0;
            foreach($data as $key => $value) : 
        ?>
            <tr>
                <td colspan="13" class="tdprogram"><?php echo $value->satker ?></td>
            </tr>
            <?php 
                foreach ($value->paketList as $key => $value) : 
                    $vol = str_replace(',', '.', $value->vol);

                    $totalVol            += $vol;
                    $totalPaguRpm        += $value->pagu_rpm;
                    $totalPaguPhln       += $value->pagu_phln;
                    $totalPaguTotal      += $value->pagu_total;
                    $totalRealisasiRpm   += $value->realisasi_rpm;
                    $totalRealisasiPhln  += $value->realisasi_phln;
                    $totalRealisasiTotal += $value->realisasi_total;
                    $totalKeu            += $value->persen_keu;
                    $totalFis            += $value->persen_fi;
            ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td class="col-satker"><?php echo $value->nmpaket ?></td>
                    <td class="col-target_vol"><?php echo ($vol) ?></td>
                    <td class="col-target_satuan"><?php echo $value->satuan ?></td>
                    <td class="col-lokasi"><?php echo $value->lokasi ?></td>
                    <td class="col-pagu_rpm"><?php echo toRupiah($value->pagu_rpm, false) ?></td>
                    <td class="col-pagu_phln"><?php echo toRupiah($value->pagu_phln, false) ?></td>
                    <td class="col-pagu_total"><?php echo toRupiah($value->pagu_total, false) ?></td>
                    <td class="col-realisasi_rpm"><?php echo toRupiah($value->realisasi_rpm, false) ?></td>
                    <td class="col-realisasi_phln"><?php echo toRupiah($value->realisasi_phln, false) ?></td>
                    <td class="col-realisasi_total"><?php echo toRupiah($value->realisasi_total, false) ?></td>
                    <td class="col-progres_keu"><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                    <td class="col-progres_fis"><?php echo onlyTwoDecimal($value->persen_fi) ?></td>
                </tr>
            <?php endforeach ?>
        <?php endforeach ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="2">TOTAL</th>
            <th><?php echo $totalVol ?></th>
            <th colspan="2">&nbsp</th>
            <th><?php echo toRupiah($totalPaguRpm, false) ?></th>
            <th><?php echo toRupiah($totalPaguPhln, false) ?></th>
            <th><?php echo toRupiah($totalPaguTotal, false) ?></th>
            <th><?php echo toRupiah($totalRealisasiRpm, false) ?></th>
            <th><?php echo toRupiah($totalRealisasiPhln, false) ?></th>
            <th><?php echo toRupiah($totalRealisasiTotal, false) ?></th>
            <th><?php echo onlyTwoDecimal($totalKeu) ?></th>
            <th><?php echo onlyTwoDecimal($totalFis) ?></th>
        </tr>
    </tfoot>
</table>

<?php echo script_tag('js/jquery.js'); ?>
<script>
    const filter = <?= json_encode($filter) ?>;
    filter.forEach((val, key) => {

        $("table ."+val).hide()
        $("table .col-"+val).hide()
    })

    //target section
    if($("table .target_vol").is(":hidden") && $("table .target_satuan").is(":hidden")){

        $(".target-main").hide()
    }else{

        $(".target-main").show()
    }
    //target end section

    //pagu section
    let pagu_counter = $("table .pagu").length

    if ($("table .pagu_rpm").is(":hidden")) {

        pagu_counter--;
    }
    if ($("table .pagu_phln").is(":hidden")) {

        pagu_counter--;
    }
    if ($("table .pagu_total").is(":hidden")) {

        pagu_counter--;
    }
    if($("table .pagu_rpm").is(":hidden") && $("table .pagu_phln").is(":hidden") && $("table .pagu_total").is(":hidden")){

        $(".pagu-main").hide()
    }else{

        $(".pagu-main").show()
        $(".pagu-main").attr("colspan", pagu_counter)
    }
    //pagu end section

    //realisasi section
    let realisasi_counter = $("table .realisasi").length

    if ($("table .realisasi_rpm").is(":hidden")) {

        realisasi_counter--;
    }
    if ($("table .realisasi_phln").is(":hidden")) {

        realisasi_counter--;
    }
    if ($("table .realisasi_total").is(":hidden")) {

        realisasi_counter--;
    }
    if($("table .realisasi_rpm").is(":hidden") && $("table .realisasi_phln").is(":hidden") && $("table .realisasi_total").is(":hidden")){

        $(".realisasi-main").hide()
    }else{

        $(".realisasi-main").show()
        // $(".realisasi-main").attr("colspan", realisasi_counter)
    }
    //realisasi end section

    //progres section
    if($("table .progres_keu").is(":hidden") && $("table .progres_fis").is(":hidden")){

        $(".progres-main").hide()
    }else{

        $(".progres-main").show()
    }
    //progres end section
    window.print()
</script>