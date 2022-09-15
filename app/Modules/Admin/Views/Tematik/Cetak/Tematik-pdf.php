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
            <th>No</th>
            <th class="satker">Satker / Paket</th>
            <th class="vol">Vol</th>
            <th class="satuan">Satuan</th>
            <th class="provinsi">Provinsi</th>
            <th class="lokasi">Lokasi</th>
            <th class="pengadaan">Pengadaan</th>
            <th class="pagu">Pagu</th>
            <th class="realisasi">Realisasi</th>
            <th class="p_keu">% Keu</th>
            <th class="p_fis">% Fis</th>
        </tr>
    </thead>

    <tbody id="tbody-utama">
        <?php 
            $no = 1;
            $totalVol       = 0;
            $totalPagu      = 0;
            $totalRealisasi = 0;
            $totalKeu       = 0;
            $totalFis       = 0;
            foreach($data as $key => $value) : 
        ?>
            <tr>
                <td colspan="11" class="tdprogram" style="text-align: left !important;"><?php echo $value->satker ?></td>
            </tr>
            <?php 
                foreach ($value->paketList as $key => $value) : 
                    $totalVol       += $value->vol;
                    $totalPagu      += $value->pagu;
                    $totalRealisasi += $value->realisasi;
                    $totalKeu       += $value->persen_keu;
                    $totalFis       += $value->persen_fis;
            ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td class="col-satker" style="text-align: left !important;"><?php echo $value->nmpaket ?></td>
                    <td class="col-vol" style="text-align: left !important;"><?php echo ($value->vol) ?></td>
                    <td class="col-satuan" style="text-align: left !important;"><?php echo $value->satuan ?></td>
                    <td class="col-provinsi" style="text-align: left !important;"><?php echo $value->provinsi ?></td>
                    <td class="col-lokasi" style="text-align: left !important;"><?php echo $value->lokasi ?></td>
                    <td class="col-pengadaan" style="text-align: left !important;"><?php echo $value->pengadaan ?></td>
                    <td class="col-pagu" style="text-align: left !important;"><?php echo toRupiah($value->pagu, false) ?></td>
                    <td class="col-realisasi" style="text-align: left !important;"><?php echo toRupiah($value->realisasi, false) ?></td>
                    <td class="col-p_keu" style="text-align: left !important;"><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                    <td class="col-p_fis" style="text-align: left !important;"><?php echo onlyTwoDecimal($value->persen_fis) ?></td>
                </tr>
            <?php endforeach ?>
        <?php endforeach ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="2">TOTAL</th>
            <th><?php echo $totalVol ?></th>
            <th colspan="4">&nbsp</th>
            <th><?php echo toRupiah($totalPagu, false) ?></th>
            <th><?php echo toRupiah($totalRealisasi, false) ?></th>
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
    window.print()
</script>