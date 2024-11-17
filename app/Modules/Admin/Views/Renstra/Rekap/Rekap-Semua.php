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
                        <?php if($data) { ?>
                            <?php foreach($data as $key => $value) { ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $key+1 ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaBalai'] ?>
                                    </td>
                                <?php foreach($value['sp'] as $keySp => $valueSp) { ?>
                                    <?php if ($keySp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                                                <?php echo $valueSp['namaSp'] ?>
                                            </td>
                                    <?php foreach($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                        <?php if ($keyIndicatorSp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                                <?php echo $valueIndicatorSp['title'] ?>
                                            </td>
                                        <?php foreach($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                                                <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                        <?php if(!empty($valueSatker['namaSatker'])) {
                                                            echo $valueSatker['namaSatker'];
                                                        } else {
                                                            echo '-';
                                                        } ?>
                                                    </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                <?php if ($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        <?php if(!empty($valueSk['namaSk'])) {
                                                                echo $valueSk['namaSk'];
                                                            } else {
                                                                echo '-';
                                                            } ?>
                                                    </td>
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
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        <?php if($databalai) { ?>
                            <?php $no = 38; foreach($data as $key => $value) { ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        <?= $no++ ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaBalai'] ?>
                                    </td>
                                <?php foreach($value['sp'] as $keySp => $valueSp) { ?>
                                    <?php if ($keySp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueSp['rowspan'] <= 0 ? 1 : $valueSp['rowspan'] ?>">
                                                <?php echo $valueSp['namaSp'] ?>
                                            </td>
                                    <?php foreach($valueSp['indikatorSp'] as $keyIndicatorSp => $valueIndicatorSp) { ?>
                                        <?php if ($keyIndicatorSp >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueIndicatorSp['rowspan'] <= 0 ? 1 : $valueIndicatorSp['rowspan'] ?>">
                                                <?php echo $valueIndicatorSp['title'] ?>
                                            </td>
                                        <?php foreach($valueIndicatorSp['satker'] as $keySatker => $valueSatker) { ?>
                                                <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                        -
                                                    </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                <?php if ($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        -
                                                    </td>
                                                <?php foreach($valueSk['indikatorSk'] as $keySkIndikator => $valueSkIndikator) { ?>
                                                    <?php if ($keySkIndikator >= 1) { ?> </tr><tr> <?php } ?>
                                                        <td>
                                                            -
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
                        <?php } ?>
                        <?php if($dataskpd) { ?>
                            <?php $value = $dataskpd[0]; $no = 1; ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] + 1 ?>">
                                        75
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
                        <?php if($datasatpus) { ?>
                            <?php $value = $datasatpus[0]; $no = 1; ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        76
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['nama_balai'] ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaSp'] ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['indikator_sp'] ?>
                                    </td>
                                    <?php foreach($value['satker'] as $keySatker => $valueSatker) { ?>
                                                <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                        <?php echo $valueSatker['nama_satker'] ?>
                                                    </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                    <?php if($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        <?php echo $valueSk['namaSk'] ?>
                                                    </td>
                                                    <?php $valueSkIndikator = $valueSk['indikatorSk'][0];
                                                    if ($keySkIndikator >= 1) { ?> </tr><tr> <?php } ?>
                                                        <td>
                                                            <?php echo $valueSkIndikator['title'] ?>
                                                        </td>
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['output']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outputSatuan'] ?></td>
                                                        <td><?php echo str_replace('.', ',', $valueSkIndikator['outcome']) ?></td>
                                                        <td><?php echo $valueSkIndikator['outcomeSatuan'] ?></td>
                                                    </tr>
                                                </tr>
                                            <?php } ?>
                                    <?php } ?>
                        <?php } ?>
                        <?php if($dataeselon2) { ?>
                            <?php $value = $dataeselon2[0]; $no = 1; ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        41
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['nama_balai'] ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaSp'] ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['indikator_sp'] ?>
                                    </td>
                                    <?php foreach($value['satker'] as $keySatker => $valueSatker) { ?>
                                        <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                <?php echo $valueSatker['nama_satker'] ?>
                                            </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                <?php if ($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        <?php echo $valueSk['namaSk']; ?>
                                                    </td>
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
                                                </tr>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                        <?php } ?>
                        <?php if($databaltek) { ?>
                            <?php $value = $databaltek[0]; $no = 1; ?>
                                <tr>
                                    <td align="center" rowspan="<?php echo $value['rowspan'] ?>">
                                        77
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $tahun ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['nama_balai'] ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['namaSp'] ?>
                                    </td>
                                    <td rowspan="<?php echo $value['rowspan'] ?>">
                                        <?php echo $value['indikator_sp'] ?>
                                    </td>
                                    <?php foreach($value['satker'] as $keySatker => $valueSatker) { ?>
                                        <?php if ($keySatker >= 1) { ?> </tr><tr> <?php } ?>
                                            <td rowspan="<?php echo $valueSatker['rowspan'] <= 0 ? 1 : $valueSatker['rowspan'] ?>">
                                                <?php echo $valueSatker['nama_satker'] ?>
                                            </td>
                                            <?php foreach($valueSatker['sk'] as $keySk => $valueSk) { ?>
                                                <?php if ($keySk >= 1) { ?> </tr><tr> <?php } ?>
                                                    <td rowspan="<?php echo $valueSk['rowspan'] <= 0 ? 1 : $valueSk['rowspan'] ?>">
                                                        <?php echo $valueSk['namaSk']; ?>
                                                    </td>
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
                                                </tr>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                        <?php } ?>
            </tbody>
        </table>
    </body>
</html>