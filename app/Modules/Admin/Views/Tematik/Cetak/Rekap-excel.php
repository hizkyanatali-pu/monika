<?php 
    ob_start();
    $tahunSelected = session('userData.tahun');
    $new_file  = "Rekap $tahunSelected.xls";
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
                    <h2>Rekap</h2>
                </td>
            </tr>
            <tr>
                <td colspan='22' class="JUDUL">
                </td>
            </tr>
        </table>

        <table width="100%" border="1" cellpadding="2" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th colspan="6" rowspan="2">Tematik</th>
                    <th rowspan="2">Pagu (dalam Milyar)</th>
                    <th rowspan="2">Realisasi Keu</th>
                    <th colspan="2">Progres (%)</th>
                    <th colspan="8" rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Keu</th>
                    <th>Fis</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no = 1;
                    foreach($data as $key => $value): 
                ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td colspan="6"><?php echo $value['title'] ?></td>
                        <td><?php echo toRupiah($value['totalPagu'], false) ?></td>
                        <td><?php echo toRupiah($value['totalRealisasi'], false) ?></td>
                        <td><?php echo onlyTwoDecimal($value['totalProgKeu']) ?></td>
                        <td><?php echo onlyTwoDecimal($value['totalProgFis']) ?></td>
                        <td colspan="8"></td>
                    </tr>
                    <?php foreach($value['list'] as $key2 => $value2): ?>
                        <tr>
                            <td></td>
                            <td colspan="6"><?php echo $value2->tematik ?></td>
                            <td><?php echo toRupiah($value2->pagu, false) ?></td>
                            <td><?php echo toRupiah($value2->realisasi, false) ?></td>
                            <td><?php echo onlyTwoDecimal($value2->prog_keu) ?></td>
                            <td><?php echo onlyTwoDecimal($value2->prog_fis) ?></td>
                            <td colspan="8"><?php echo $value2->ket ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </body>
</html>
