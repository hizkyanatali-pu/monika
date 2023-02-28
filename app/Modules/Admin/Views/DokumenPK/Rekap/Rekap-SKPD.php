<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rekap-Monika</title>
    </head>

    <body>
        <table class="table table-bordered" border=1>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Tahun</th>
                    <th>Balai</th>
                    <th>SP</th>
                    <th>Indikator SP</th>
                    <th>Satker</th>
                    <th>SK</th>
                    <th>Indikator SK</th>
                    <th>Output</th>
                    <th>Satuan</th>
                    <th>Outcome</th>
                    <th>Satuan</th>
                </tr>
            </thead>
            <tbody class="all-data">
                <?php if($dataskpd) { ?>
                            <?php $value = $dataskpd[0]; $no = 1; ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] + 1 ?>">
                                        <?php echo $no ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] + 1 ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] + 1 ?>">
                                        <?php echo $value['nama_balai'] ?>
                                    </td>
                                <?php foreach($value['sp'] as $keySp => $valueSp) { $rowspan_sp = $valueSp['rowspan'] + 1; ?>
                                    <?php if ($keySp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $rowspan_sp <= 0 ? 1 : $rowspan_sp ?>">
                                                <?php echo $valueSp['namaSp'] ?>
                                            </td>
                                    <?php foreach($valueSp['indikatorSkSKPD'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                        <?php if ($keyIndicatorSp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                                -
                                            </td>
                                        <?php foreach($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { $ke ?>
                                                <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                        <?php echo $valueSatker['nama_satker'] ?>
                                                    </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                <?php if($valueSk['indikatorSk'] != NULL) { 
                                                    if ($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        <?php echo $valueSk['namaSk'] ?>
                                                    </td>
                                                <?php } ?>
                                                <?php foreach($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                                    <?php if ($keySkIndikator >= 1) { ?> </tr><tr> <?php } ?>
                                                        <td>
                                                            <?php echo $valueSkIndikator['title'] ?>
                                                        </td>
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>