<?php 
    ob_start();
    $tahunSelected = session('userData.tahun');
    $new_file  = "$filterTitle $tahunSelected.xls";
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=$new_file");
    header("Pragma: no-cache");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Expires: 0");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>stusho</title>
        <style>
            table {
              border-collapse: collapse;
              border: 1px solid #000;
              margin: 0 auto;
              padding:10px;
              font-size:9px;
              font-family: Arial Narrow,Arial,sans-serif;

            }
            @page 
            {
                size: auto;
                margin: 10mm;
            }
            @media print {
               body {
                  -webkit-print-color-adjust: exact;
               }
            }
        </style>
    </head>

    <body onload="window.print()">

        <table width="100%" border="0px" cellspacing="0px" cellpadding="0px" style="border:none;">
            <tr>
                <td colspan='22' class="JUDUL">
                    <h2><?php echo $title ?></h2>
                </td>
            </tr>
            <tr>
                <td colspan='22' class="JUDUL">
                    <section>
                        <h4><?php echo $filterTitle . ' ' . $tahunSelected ?></h4>
                    </section>
                </td>
            </tr>
            <tr>
                <td colspan='22' class="JUDUL">
                </td>
            </tr>
        </table>

        <table width="100%" border="1" cellpadding="2" cellspacing="0">
            <thead>
                <tr class=" text-center bg-purple">
                    <th class="" rowspan="2">No</th>
                    <th class="" colspan="6" rowspan="2">Satker / Paket</th>
                    <th class="" colspan="2">Target</th>
                    <th class="" rowspan="2">Lokasi</th>
                    <th class="" colspan="3">Pagu</th>
                    <th class="" colspan="3">Realisasi</th>
                    <th class="" colspan="2">Progres (%)</th>
                </tr>
                <tr class=" text-center bg-purple">
                    <th class="">Vol</th>
                    <th class="">Satuan</th>
                    <th class="">RPM</th>
                    <th class="">PHLN</th>
                    <th class="">Total</th>
                    <th class="">RPM</th>
                    <th class="">PHLN</th>
                    <th class="">Total</th>
                    <th class="">Keu</th>
                    <th class="">Fis</th>
                </tr>
            </thead>
            <tbody>
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
                        <td colspan="18"><?php echo $value->satker ?></td>
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
                            <td colspan="6" ><?php echo $value->nmpaket ?></td>
                            <td><?php echo onlyTwoDecimal($vol) ?></td>
                            <td><?php echo $value->satuan ?></td>
                            <td><?php echo $value->lokasi ?></td>
                            <td><?php echo toRupiah($value->pagu_rpm, false) ?></td>
                            <td><?php echo toRupiah($value->pagu_phln, false) ?></td>
                            <td><?php echo toRupiah($value->pagu_total, false) ?></td>
                            <td><?php echo toRupiah($value->realisasi_rpm, false) ?></td>
                            <td><?php echo toRupiah($value->realisasi_phln, false) ?></td>
                            <td><?php echo toRupiah($value->realisasi_total, false) ?></td>
                            <td><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                            <td><?php echo onlyTwoDecimal($value->persen_fi) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endforeach ?>
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="7">TOTAL</th>
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
    </body>
</html>
