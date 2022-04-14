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
                <tr>
                    <th>No</th>
                    <th colspan="6">Satker / Paket</th>
                    <th>Vol</th>
                    <th>Satuan</th>
                    <th>Provinsi</th>
                    <th>Lokasi</th>
                    <th>Pengadaan</th>
                    <th>Pagu</th>
                    <th>Realisasi</th>
                    <th>% Keu</th>
                    <th>% Fis</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no = 1;
                    foreach($data as $key => $value) : 
                ?>
                    <tr>
                        <td colspan="16"><?php echo $value->satker ?></td>
                    </tr>
                    <?php foreach ($value->paketList as $key => $value) : ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td colspan="6"><?php echo $value->nmpaket ?></td>
                            <td><?php echo ($value->vol) ?></td>
                            <td><?php echo $value->satuan ?></td>
                            <td><?php echo $value->provinsi ?></td>
                            <td><?php echo $value->lokasi ?></td>
                            <td><?php echo $value->pengadaan ?></td>
                            <td><?php echo toRupiah($value->pagu, false) ?></td>
                            <td><?php echo toRupiah($value->realisasi, false) ?></td>
                            <td><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                            <td><?php echo onlyTwoDecimal($value->persen_fis) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </body>
</html>
