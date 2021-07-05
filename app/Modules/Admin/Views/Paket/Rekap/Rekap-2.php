<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap-Monika</title>
    <style>
    .table td, th{
        padding:2px;
        font-size:12px;
        border:1px solid #333;
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

    .tdsbalai{background-color:#ffb366; color:#000;}
    .tdsatker{background-color:#ffcc99;}
    .tdgiat{background-color:#ddccff;}
    .tdoutput{background-color:#ccb3ff;}
    .tdsoutput{background-color:#bb99ff;}
    .tdkomponen{background-color:#aa80ff;}

    .theader th{background-color:#66ccff; color:#000;}
    </style>
</head>
<body>
    <?php $colspan=11; ?>

    <table class="table table-bordered">
        <tr>
            <th align="center" colspan="<?=$colspan;?>" style="border:0px;">
                <h3><?=$title;?> - Progres Keuangan dan Fisik</h3>
            </th>
        </tr>
        <tr>
            <th align="center" colspan="<?=$colspan;?>" style="border:0px;">
                <h3><?=$label;?></h3>
            </th>
        </tr>
        <tr>
            <th align="center" colspan="<?=$colspan;?>" style="border:0px;">
                <h3><?=$label2;?></h3>
            </th>
        </tr>
        <tr><td colspan="<?=$colspan;?>">&nbsp;</td></tr>
    </table>

    <table class="table table-bordered table-striped" border="1">
        <thead>
            <tr class=" text-center theader">
                <th colspan="2">&nbsp;</th>
                <th colspan="4">Pagu (Rp)</th>
                <th>&nbsp;</th>
                <th colspan="2">Progres (%)</th>
                <th colspan="2">Deviasi</th>
            </tr>
            <tr class=" text-center theader">
                <th class="tdKode">Kode</th>
                <th class="tdLabel">Paket</th>

                <!-- <th class="tdTV">Target volume</th>
                <th class="tdLokasi">Lokasi</th>
                <th class="tdJP" title="Jenis Paket">JP</th>
                <th class="tdMP" title="Metode pemilihan">MP</th> -->

                <th class="tdNilai">RPM</th>
                <th class="tdNilai">SBSN</th>
                <th class="tdNilai">PLN</th>
                <th class="tdNilai">TOTAL</th>

                <th class="tdNilai">Realisasi</th>

                <th class="tdPersen">keu</th>
                <th class="tdPersen">fisik</th>

                <th class="tdPersen">%</th>
                <th class="tdPersen">Rp</th>
            </tr>
        </thead>
        <tbody id="tbody-utama">
            <?php if($qdata): ?>
            <?php $balaiid=[]; $satkerid=[]; $idp=[]; $idg=[]; $ido=[]; $idso=[]; $idkk=[]; $colspan=11; ?>
            <?php foreach($qdata as $key => $d): ?>

                <?php $balaiK=$d['balaiid'];?>
                <?php if( !in_array( $balaiK, $balaiid ) ):?>
                    <tr><td class="tdsbalai" colspan="<?php echo $colspan; ?>"><?php echo $d['balai'];?></td></tr>
                    <?php $balaiid = array_merge( [ $balaiK ], $balaiid );?>
                <?php endif; ?>

                <?php $satkerK=$d['satkerid'];?>
                <?php if( !in_array( $satkerK, $satkerid ) ):?>
                    <tr><td class="tdsatker" colspan="<?php echo $colspan; ?>"><?php echo $d['satkerid'] ." ". $d['satker'];?></td></tr>
                    <?php $satkerid = array_merge( [ $satkerK ], $satkerid );?>
                <?php endif; ?>

                <?php $idk=$d['programid']; ?>

                <?php $idk=$idk.'.'.$d['giatid'];?>
                <?php if( !in_array( $idk, $idp ) ):?>
                    <tr><td class="tdgiat" colspan="<?php echo $colspan; ?>"><?php echo $idk;?></td></tr>
                    <?php $idp = array_merge( [ $idk ], $idp );?>
                <?php endif; ?>

                <?php $idk=$idk.'.'.$d['outputid'];?>
                <?php if( !in_array( $idk, $ido ) ):?>
                    <tr><td class="tdoutput" colspan="<?php echo $colspan; ?>"><?php echo $idk;?></td></tr>
                    <?php $ido = array_merge( [ $idk ], $ido );?>
                <?php endif; ?>

                <?php $idk=$idk.'.'.$d['soutputid'];?>
                <?php if( !in_array( $idk, $idso ) ):?>
                    <tr><td class="tdsoutput" colspan="<?php echo $colspan; ?>"><?php echo $idk;?></td></tr>
                    <?php $idso = array_merge( [ $idk ], $idso );?>
                <?php endif; ?>

                <?php $idk=$idk.'.'.$d['komponenid'];?>
                <?php if( !in_array( $idk, $idkk ) ):?>
                    <tr><td class="tdkomponen" colspan="<?php echo $colspan; ?>"><?php echo $idk;?></td></tr>
                    <?php $idkk = array_merge( [ $idk ], $idkk );?>
                <?php endif; ?>

                <tr>
                <td class="tdKode"><?php echo $d['id'];?></td>
                <td class="tdLabel"><?php echo $d['label'];?></td>

                <td class="tdNilai text-right"><?php echo $d['pagu_rpm'];?></td>
                <td class="tdNilai text-right"><?php echo $d['pagu_sbsn'];?></td>
                <td class="tdNilai text-right"><?php echo $d['pagu_phln'];?></td>
                <td class="tdNilai text-right"><?php echo $d['pagu_total'];?></td>

                <td class="tdNilai text-right"><?php echo $d['real_total'];?></td>

                <td class="tdPersen text-right"><?php echo $d['progres_keuangan'];?></td>
                <td class="tdPersen text-right"><?php echo $d['progres_fisik'];?></td>

                <td class="tdPersen text-right"><?php echo ( $d['progres_fisik']>$d['progres_keuangan'] ? $d['persen_deviasi'] :'-' );?></td>
                <td class="tdNilai text-right"><?php echo ( $d['progres_fisik']>$d['progres_keuangan'] ? $d['nilai_deviasi'] :'-' );?></td>
                </tr>

            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>