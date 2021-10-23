<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap-Monika</title>
    <style>
        .tableFixHead          { overflow-y: auto; height: 600px; }
        .tableFixHead thead th { position: sticky; top: 0; border:1px solid #333;}

        /* Just common table stuff. Really. */
        .table  { border-collapse: collapse; width: 100%; }
        /*th, td { padding: 8px 16px; }*/
        .table th     { background:#f5f5f5; }
    </style>
    <style>
    .table td, th{
        padding:2px;
        font-size:12px;
    }
    table a{color:#000099;}
    table tr:hover{color:#000000; text-decoration: underline; font-weight: bold;}

    .tdKodeLabel{width:500px;}
    .tdLabelFull{}
    .tdKode{width:200px;}
    .tdLabel{width:300px;}
    .tdTV{width:90px;}
    .tdLokasi{width:120px;}
    .tdJP{width:40px;}
    .tdMP{width:40px;}
    .tdNilai{width:100px;}
    .tdPersen{width:50px;}
    .tdPersen{width:50px;}

    .tdsatker{background-color:#eee6ff;}
    .tdgiat{background-color:#ddccff;}
    .tdoutput{background-color:#ccb3ff;}
    .tdsoutput{background-color:#bb99ff;}
    .tdkomponen{background-color:#aa80ff;}

    .stw0{background-color:#ff4da6; color:#FFF;}
    .stw1{}

    </style>
</head>
<body>
    <?php $colspan=11; ?>

    <table class="table table-bordered">
        <tr>
            <th align="center" colspan="<?=$colspan;?>" style="border:0px;">
                <h3>Progres Keuangan dan Fisik - <?=$title;?></h3>
            </th>
        </tr>
        <tr>
            <th align="center" colspan="<?=$colspan;?>" style="border:0px;">
                <h3><?=$label;?></h3>
            </th>
        </tr>
        <tr><td colspan="<?=$colspan;?>">&nbsp;</td></tr>
    </table>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Progres Keungan SDA</td><th><?=number_format( $qdata[0]['pagusda_progres_keuangan'],2,',','.');?>%</th>
            </tr>
            <tr>
                <td>Progres Fisik SDA</td><th><?=number_format( $qdata[0]['pagusda_progres_fisik'] ,2,',','.');?>%</th>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="1">
        <thead>
            <tr class="text-center theader">
                <th colspan="2">&nbsp;</th>
                <th colspan="5">Pagu (Rp)</th>
                <th colspan="2">Progres (%)</th>
                <th colspan="2">Deviasi</th>
            </tr>
            <tr class="text-center theader">
                <th class=""><?=$title;?></th>
                <th class="tdNilai">Jml&nbsp;Paket
                    <!-- <br /><small title="Pagu SDA">Total SDA <i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-right"></i></small> -->
                    </th>

                <th class="tdNilai">RPM</th>
                <th class="tdNilai">SBSN</th>
                <th class="tdNilai">PLN</th>
                <th class="tdNilai">TOTAL</th>

                <th class="tdNilai">Realisasi</th>

                <th class="tdPersen">keu</th>
                <th class="tdPersen">fisik</th>

                <th class="tdPersen">%</th>
                <th class="tdNilai">Rp</th>
            </tr>
        </thead>
        <tbody id="tbody-utama">
            <?php if($qdata): ?>
            <?php
            $total_pagu_rpm=0;
            $total_pagu_sbsn=0;
            $total_pagu_phln=0;
            $total_pagu_total=0;
            $total_real_total=0;
            ?>
            <?php
            foreach($qdata as $key => $data): ?>

            <!-- balai -->
            <tr class="stw<?=$data['stw'];?>">
                <td class="tdKodeLabel"><?php echo $data['label']; ?></td>
                <td class="tdNilai text-center"><?php echo $data['jml_paket']; ?></td>

                <td class="tdNilai text-right"><?php echo $data['jml_pagu_rpm']; ?></td>
                <td class="tdNilai text-right"><?php echo $data['jml_pagu_sbsn']; ?></td>
                <td class="tdNilai text-right"><?php echo $data['jml_pagu_phln']; ?></td>
                <td class="tdNilai text-right"><?php echo $data['jml_pagu_total']; ?></td>

                <td class="tdNilai text-right"><?php echo $data['jml_real_total']; ?></td>

                <td class="tdPersen text-right"><?php echo number_format($data['jml_progres_keuangan'] ,2,',','.'); ?></td>
                <td class="tdPersen text-right"><?php echo number_format($data['jml_progres_fisik'],2,',','.'); ?></td>

                <td class="tdPersen text-right"><?php echo ( $data['jml_progres_fisik']>$data['jml_progres_keuangan'] ?  number_format($data['jml_persen_deviasi'],2,',','.') :'-' ); ?></td>
                <td class="tdPersen text-right"><?php echo ( $data['jml_progres_fisik']>$data['jml_progres_keuangan'] ? $data['jml_nilai_deviasi'] :'-' ); ?></td>
            </tr>
            <?php
            $total_pagu_rpm+= $data['jml_pagu_rpm'];
            $total_pagu_sbsn+= $data['jml_pagu_sbsn'];
            $total_pagu_phln+= $data['jml_pagu_phln'];
            $total_pagu_total+= $data['jml_pagu_total'];
            $total_real_total+= $data['jml_real_total'];
            ?>
            <?php endforeach; ?>
            <tr style="background-color:#ccb3ff; border:2px solid #ccc;">
                <td colspan="2" class="text-center">TOTAL</td>
                <td class="tdNilai text-right"><?php echo $total_pagu_rpm; ?></td>
                <td class="tdNilai text-right"><?php echo $total_pagu_sbsn; ?></td>
                <td class="tdNilai text-right"><?php echo $total_pagu_phln; ?></td>
                <td class="tdNilai text-right"><?php echo $total_pagu_total; ?></td>
                <td class="tdNilai text-right"><?php echo $total_real_total; ?></td>

                <td colspan="4" class="tdPersen text-right">&nbsp;</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>