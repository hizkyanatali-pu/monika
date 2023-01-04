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
            <th class="no" rowspan="2">No</th>
            <th class="tematik" rowspan="2">Tematik</th>
            <th class="pagu" rowspan="2">Pagu (dalam Milyar)</th>
            <th class="realisasi" rowspan="2">Realisasi Keu</th>
            <th class="progres-main" colspan="2">Progres (%)</th>
            <th class="keterangan" rowspan="2">Keterangan</th>
        </tr>
        <tr class="bg-purple">
            <th class="progres_keu progres">Keu</th>
            <th class="progres_fis progres">Fis</th>
        </tr>
    </thead>

    <tbody id="tbody-utama">
        <?php 
            $no = 1;
            $totalPagu      = 0;
            $totalRealisasi = 0;
            $totalKeu       = 0;
            $totalFis       = 0;
            foreach($data as $key => $value): 
                $totalPagu      += $value['totalPagu'];
                $totalRealisasi += $value['totalRealisasi'];
                $totalKeu       += $value['totalProgKeu'];
                $totalFis       += $value['totalProgFis'];
        ?>
            <tr>
                <td class="tdprogram"><?php echo $no++ ?></td>
                <td class="col-tematik tdprogram"><?php echo $value['title'] ?></td>
                <td class="col-pagu tdprogram"><?php echo toMilyar($value['totalPagu'], false) ?></td>
                <td class="col-realisasi tdprogram"><?php echo toMilyar($value['totalRealisasi'], false) ?></td>
                <td class="col-progres_keu tdprogram"><?php echo onlyTwoDecimal($value['totalProgKeu']) ?></td>
                <td class="col-progres_fis tdprogram"><?php echo onlyTwoDecimal($value['totalProgFis']) ?></td>
                <td class="col-keterangan tdprogram"></td>
            </tr>
            <?php foreach($value['list'] as $key2 => $value2): ?>
                <tr>
                    <td></td>
                    <td class="col-tematik"><?php echo $value2->tematik ?></td>
                    <td class="col-pagu"><?php echo toMilyar($value2->pagu, false) ?></td>
                    <td class="col-realisasi"><?php echo toMilyar($value2->realisasi, false) ?></td>
                    <td class="col-progres_keu"><?php echo onlyTwoDecimal($value2->prog_keu) ?></td>
                    <td class="col-progres_fis"><?php echo onlyTwoDecimal($value2->prog_fis) ?></td>
                    <td class="col-keterangan"><?php echo $value2->ket ?></td>

                    <!--<td><?php echo  "- ". str_replace("||","<br> - ",str_replace(", ", ",", $value2->ket))  ?></td>-->
                </tr>
            <?php endforeach ?>
        <?php endforeach ?>
    </tbody>

    <tfoor>
        <tr>
            <th colspan="2">TOTAL</th>
            <th><?php echo toMilyar($totalPagu, false) ?></th>
            <th><?php echo toMilyar($totalRealisasi, false) ?></th>
            <th><?php echo onlyTwoDecimal($totalKeu) ?></th>
            <th><?php echo onlyTwoDecimal($totalFis) ?></th>
            <th>&nbsp</th>
        </tr>
    </tfoor>
</table>

<?php echo script_tag('js/jquery.js'); ?>
<script>
    const filter = <?= json_encode($filter) ?>;
    filter.forEach((val, key) => {

        $("table ."+val).hide()
        $("table .col-"+val).hide()
    })

    //progres section
    let progres_counter = $("table .progres").length

    if ($("table .progres_keu").is(":hidden")) {

        progres_counter--;
    }
    if ($("table .progres_fis").is(":hidden")) {

        progres_counter--;
    }
    if($("table .progres_keu").is(":hidden") && $("table .progres_fis").is(":hidden")){

        $(".progres-main").hide()
    }else{

        $(".progres-main").show()
        $(".progres-main").attr("colspan", progres_counter)
    }
    window.print()
</script>