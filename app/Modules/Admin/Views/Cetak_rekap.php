<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Rekap Dokumen PK</title>
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

</script>     -->
    <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
            page-break-inside: auto
        }

        .header th {
            background-color: #3E7899;
            color: #ffffff;
            border: 1px solid white;

        }

        /* thead tr {
        border: 1px solid white;
    } */

        .ket-satker {
            text-align: left;
            background: #d4d700;
            border: 1px solid black;
        }

        th {
            background-color: #3E7899;
            color: #ffffff;
            border: 1px solid black;

        }

        td {
            border: 1px solid black;
            font-family: Arial;
            /* text-align: center; */
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        .kop-rekap {
            text-align: center;
            font-weight: bold;
        }

        .col-number,
        .melapor,
        .belum-melapor,
        .link-document,
        .checklist-verif,
        .date {
            text-align: center;
            content: "\2714";
        }

        .page-break {
            page-break-after: always;
            page-break-inside: avoid;
        }

        .title-chart {
            font-size: 30px;
            text-align: center;
        }

        .chart-td {
            border: 1px solid white;
            font-family: Arial;
        }

        .card-cart {
            text-align: center;
        }

        .chart {
            margin: 0px 80px;
            display: flex;
        }

        #flotcontainer {
            width: 500px;
            height: 500px;
            font-size: 13px;
            margin-left: 70%;
        }

        .pieLabel div {
            font-weight: bold !important;
            font-size: 15px !important;
        }

        .ket-chart,
        .td-chart {
            border: 1px solid white;
        }

        .tabel-keterangan {
            margin-left: 25%;
    
        }

        .btn {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 4px 2px;
            cursor: pointer;
        }

        .hidden-print {
            font-size: 15px;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }

            #flotcontainer {
                margin-left: 5%;
            }

            .tabel-keterangan {
                margin-left: 25%;
            }

            .hidden-back,
            .hidden-back * {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <?php
    $this->db = \Config\Database::connect();
    $tb_balai  = $this->db->table('m_balai');
    $tb_satker  = $this->db->table('m_satker');
    $no = 1;
    $melapor = 0;
    $belum_lapor = 0;
    $terverifikasi = 0;
    $menunggu_konfir = 0;
    $acc = 0;
    $reject = 0;
    $revisi_terverifikasi = 0;
    $session = session();
    $this->user = $session->get('userData');

    ?>

    <h5 class="kop-rekap">REKAPITULASI PENGIRIMAN DOKUMEN PERJANJIAN KINERJA (PK) ES II / UPT / SATUAN KERJA TA <?= $session_year ?>
        <br>DITJEN SUMBER DAYA AIR
        <br>STATUS
        <?= $tanggal ?> <?= $bulan ?> <?= $tahun ?>
        , <?= $jam ?> WIB
    </h5>

    <div>
        <button class="btn hidden-print" id="btn_print">Cetak</button>
    </div>

    <table width="100%">
        <!-- <thead class="head-table"> -->
        <tr>
            <th width="5%" rowspan="3">No</th>
            <th width="20%" rowspan="3">Nama Unit / Satker</th>
            <th width="15%" colspan="2">Entitas Kerja</th>
            <th width="60%" colspan="6">Form</th>
        </tr>
        <tr>
            <th width="10%" rowspan="2">Melapor</th>
            <th width="10%" rowspan="2">Belum Melapor</th>
            <th width="30%" colspan="3" rowspan="1">PK Awal</th>
            <th width="30%" colspan="3" rowspan="1">PK Revisi</th>
        </tr>
        <tr>
            <th width="10%" rowspan="1">Dokumen</th>
            <th width="10%" rowspan="1">Tanggal Kirim</th>
            <th width="10%" rowspan="1">Verifikasi</th>
            <th width="10%" rowspan="1">Dokumen</th>
            <th width="10%" rowspan="1">Tanggal Kirim</th>
            <th width="10%" rowspan="1">Verifikasi</th>
        </tr>
        <!-- </thead> -->
        <?php foreach ($group_jabatan as $key => $grup) {
            switch ($grup) {
                case 'ESELON II':
                    # code...
                    $satker_s =  $tb_satker->select('satker, satkerid')->where('grup_jabatan', "eselon2")->get()->getResult();
                    break;

                case 'UPT/BALAI':
                    # code...
                    $satker_s =  $tb_balai->select('balai as satker, balaiid')->where("jabatan_penanda_tangan_pihak_1 !=", "")->get()->getResult();
                    break;

                case 'BALAI TEKNIK':
                    # code...
                    $satker_s =  $tb_satker->select('satker, satkerid')->where('balaiid', "97")->get()->getResult();
                    break;

                case 'SATKER PUSAT':
                    # code...
                    $satker_s =  $tb_satker->select('satker, satkerid')->whereIn('satkerid', [654098, 403477])->get()->getResult();
                    break;

                case 'SKPD TP-OP':
                    # code...
                    $satker_s =  $tb_satker->select('satker, satkerid')->where('balaiid', "98")->Orwhere('balaiid', "100")->get()->getResult();
                    break;

                default:
                    # code...
                    break;
            }
        ?>
            <tr>
                <td class="ket-satker" width="100%" colspan="10"><?= $grup ?></td>
            </tr>
            <?php
            foreach ($satker_s as $view) { 

                if ($grup == 'UPT/BALAI') {
                    $query_satker = $tb_balai->select('dokumenpk_satker.id, dokumenpk_satker.created_at, dokumenpk_satker.status, dokumenpk_satker.deleted_at,  dokumenpk_satker.acc_date, dokumenpk_satker.reject_date, dokumenpk_satker.is_revision_same_year, dokumenpk_satker.revision_same_year_number, dokumenpk_satker.change_status_at')
                        ->join('dokumenpk_satker', 'dokumenpk_satker.balaiid = m_balai.balaiid', 'left')
                        ->where('m_balai.balaiid', $view->balaiid)
                        ->where('dokumenpk_satker.deleted_at IS NULL')
                        ->where('dokumenpk_satker.satkerid IS NULL')
                        ->orderBy('dokumenpk_satker.id', 'DESC')
                        ->get()->getRowArray();

                    $count = $tb_balai->select("m_balai.balai")
                        ->where("(SELECT count(id) FROM dokumenpk_satker WHERE balaiid=m_balai.balaiid and tahun={$this->user['tahun']} AND deleted_at IS NULL AND dokumen_type = 'balai' ORDER BY id DESC) < 1 AND balaiid = $view->balaiid")
                        ->get()->getRowArray();
                    
                    $query_dokumen = $tb_balai->select('dokumenpk_satker.id, dokumenpk_satker.created_at, dokumenpk_satker.revision_master_dokumen_id, dokumenpk_satker.revision_master_number, dokumenpk_satker.is_revision_same_year')
                        ->join('dokumenpk_satker', 'dokumenpk_satker.balaiid = m_balai.balaiid', 'left')
                        ->where('m_balai.balaiid', $view->balaiid)
                        ->where('dokumenpk_satker.deleted_at IS NULL')
                        ->where('dokumenpk_satker.satkerid IS NULL')
                        ->where('dokumenpk_satker.revision_master_dokumen_id IS NULL')
                        ->where('dokumenpk_satker.revision_master_number IS NULL')
                        ->orderBy('dokumenpk_satker.id', 'DESC')
                        ->get()->getRowArray();

                } else {
                    $query_satker = $tb_satker->select('dokumenpk_satker.id, dokumenpk_satker.created_at, dokumenpk_satker.status, dokumenpk_satker.deleted_at, dokumenpk_satker.acc_date, dokumenpk_satker.reject_date, dokumenpk_satker.is_revision_same_year, dokumenpk_satker.revision_same_year_number, dokumenpk_satker.change_status_at')
                        ->join('dokumenpk_satker', 'dokumenpk_satker.satkerid = m_satker.satkerid', 'left')
                        ->where('m_satker.satkerid', $view->satkerid)
                        ->where('dokumenpk_satker.deleted_at IS NULL')
                        ->orderBy('dokumenpk_satker.id', 'DESC')
                        ->get()->getRowArray();

                        
                    $count = $tb_satker->select('m_satker.satker')
                        ->where("(SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and tahun= {$this->user['tahun']} AND deleted_at IS NULL ORDER BY id DESC) < 1 and satkerid = $view->satkerid")
                        ->get()->getRowArray();

                    $query_dokumen = $tb_satker->select('dokumenpk_satker.id, dokumenpk_satker.revision_master_dokumen_id, dokumenpk_satker.revision_master_number, dokumenpk_satker.is_revision_same_year, dokumenpk_satker.created_at')
                        ->join('dokumenpk_satker', 'dokumenpk_satker.satkerid = m_satker.satkerid', 'left')
                        ->where('m_satker.satkerid', $view->satkerid)
                        ->where('dokumenpk_satker.deleted_at IS NULL')
                        ->where('dokumenpk_satker.revision_master_dokumen_id IS NULL')
                        ->where('dokumenpk_satker.revision_master_number IS NULL')
                        ->orderBy('dokumenpk_satker.id', 'DESC')
                        ->get()->getRowArray();
                    }
                // var_dump($query_dokumen); 
                // die;


                if (empty($count)) {
                    $month = date('m', strtotime($query_satker['created_at']));

                    if ($month == '01') {
                        $bulan = 'Januari';
                    } else if ($month == '02') {
                        $bulan = 'Februari';
                    } else if ($month == '03') {
                        $bulan = 'Maret';
                    } else if ($month == '04') {
                        $bulan = 'April';
                    } else if ($month == '05') {
                        $bulan = 'Mei';
                    } else if ($month == '06') {
                        $bulan = 'Juni';
                    } else if ($month == '07') {
                        $bulan = 'Juli';
                    } else if ($month == '08') {
                        $bulan = 'Agustus';
                    } else if ($month == '09') {
                        $bulan = 'September';
                    } else if ($month == '10') {
                        $bulan = 'Oktober';
                    } else if ($month == '11') {
                        $bulan = 'November';
                    } else if ($month == '12') {
                        $bulan = 'Desember';
                    }
                }

                #kondisi
                if (empty($count)) {
                    if ($query_satker['status'] == 'tolak') {
                        $reject += 1;
                    }
                }

                if (empty($count)) {
                    if ($query_satker['acc_date'] == NULL && $query_satker['reject_date'] == NULL) {
                        $menunggu_konfir += 1;
                    }
                }

                if (!empty($query_satker['acc_date'])) {
                    $acc += 1;
                }


            ?>
                <tr>
                    <td class="col-number"><?= $no++ ?></td>
                    <td><?= $view->satker ?></td>
                    <?php if (empty($count) && $query_satker != 'revision') {
                        $melapor += 1 ?>
                        <td class="melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                    <?php } else { ?>
                        <td class="melapor"></td>
                    <?php  } ?>
                    <?php if (!empty($count) || $query_satker == 'revision') {
                        $belum_lapor += 1 ?>
                        <td class="belum-melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                    <?php } else { ?>
                        <td class="belum-melapor"></td>
                    <?php  } ?>
                    <?php if (empty($count)) { ?>
                        <td class="link-document"><a href="<?= base_url() ?>/api/showpdf/tampilkan/<?= $query_dokumen['id'] ?>?preview=true" target="_blank"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                    <?php } else {
                        echo '<td class="link-document">-</td>';
                    } ?>
                    <td class="date"><?php if (empty($query_satker['created_at'])) {
                                            echo '';
                                        } else {
                                            echo date('d', strtotime($query_dokumen['created_at'])) ?> <?= $bulan ?> <?= date('Y', strtotime($query_dokumen['created_at']));
                                                                                                                } ?></td>
                    <?php if (isset($query_satker['acc_date']) != NULL || isset($query_satker['reject_date']) != NULL) {
                        $terverifikasi += 1;
                    ?>
                        <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                    <?php } else { ?>
                        <td class="checklist-verif"></td>
                    <?php } ?>
                    <?php if(empty($count)) {
                    if ($query_satker['revision_same_year_number'] != 0) { ?>
                        <td class="link-document"><a href="<?= base_url() ?>/api/showpdf/tampilkan/<?= $query_satker['id'] ?>?preview=true" target="_blank"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                        <td class="date"><?php echo date('d', strtotime($query_satker['change_status_at'])) ?> <?= $bulan ?> <?= date('Y', strtotime($query_satker['change_status_at'])) ?></td>
                        <?php if ($query_satker['acc_date'] != NULL || $query_satker['reject_date'] != NULL) {
                            $revisi_terverifikasi += 1;
                        ?>
                            <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:20px;height:20px;"></td>
                        <?php } else { ?>
                            <td class="checklist-verif"></td>
                        <?php } ?>
                    <?php  } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php } ?>
                    <?php } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php } ?>

                </tr>
            <?php } ?>
        <?php }
        // var_dump($acc);
        // die;
        ?>

        <?php foreach ($balai_s as $balai) {
            $satker_ss =  $tb_satker->select('satker, satkerid')->where('balaiid', $balai->balaiid)->get()->getResult();
        ?>
            <tr>
                <td class="ket-satker" width="100%" colspan="10"><?= $balai->balai ?></td>
            </tr>
            <?php
            foreach ($satker_ss as $satker) {
                $query_balai = $tb_satker->select('dokumenpk_satker.id, dokumenpk_satker.created_at, dokumenpk_satker.status, dokumenpk_satker.deleted_at, dokumenpk_satker.acc_date, dokumenpk_satker.reject_date, dokumenpk_satker.revision_same_year_number, dokumenpk_satker.change_status_at')
                    ->join('dokumenpk_satker', 'dokumenpk_satker.satkerid = m_satker.satkerid', 'left')
                    ->where('m_satker.satkerid', $satker->satkerid)
                    ->where('dokumenpk_satker.deleted_at IS NULL')
                    ->orderBy('dokumenpk_satker.id', 'DESC')
                    ->get()->getRowArray();
                $count_balai = $tb_satker->select('m_satker.satker')
                    ->where("(SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and tahun= {$this->user['tahun']} AND deleted_at IS NULL ORDER BY id DESC) < 1 and satkerid = $satker->satkerid")
                    ->get()->getRowArray();

                $query_dokumen_balai = $tb_satker->select('dokumenpk_satker.id, dokumenpk_satker.created_at, dokumenpk_satker.revision_master_dokumen_id, dokumenpk_satker.revision_master_number, dokumenpk_satker.is_revision_same_year')
                    ->join('dokumenpk_satker', 'dokumenpk_satker.satkerid = m_satker.satkerid', 'left')
                    ->where('m_satker.satkerid', $satker->satkerid)
                    ->where('dokumenpk_satker.deleted_at IS NULL')
                    ->where('dokumenpk_satker.revision_master_dokumen_id IS NULL')
                    ->where('dokumenpk_satker.revision_master_number IS NULL')
                    ->orderBy('dokumenpk_satker.id', 'ASC')
                    ->get()->getRowArray();
                
                // var_dump($query_dokumen_balai);
                // die;

                $month = date('m', strtotime($query_balai['created_at']));
                if ($month == '01') {
                    $bulan = 'Januari';
                } else if ($month == '02') {
                    $bulan = 'Februari';
                } else if ($month == '03') {
                    $bulan = 'Maret';
                } else if ($month == '04') {
                    $bulan = 'April';
                } else if ($month == '05') {
                    $bulan = 'Mei';
                } else if ($month == '06') {
                    $bulan = 'Juni';
                } else if ($month == '07') {
                    $bulan = 'Juli';
                } else if ($month == '08') {
                    $bulan = 'Agustus';
                } else if ($month == '09') {
                    $bulan = 'September';
                } else if ($month == '10') {
                    $bulan = 'Oktober';
                } else if ($month == '11') {
                    $bulan = 'November';
                } else if ($month == '12') {
                    $bulan = 'Desember';
                }

                if ($query_balai['status'] == 'tolak' || $query_balai['reject_date'] != NULL) {
                    $reject += 1;
                }

                if (empty($count_balai)) {
                    if ($query_balai['acc_date'] == NULL && $query_balai['reject_date'] == NULL) {
                        $menunggu_konfir += 1;
                    }
                }

                if (!empty($query_balai['acc_date'])) {
                    $acc += 1;
                }
                // var_dump($count_balai);
                // die;
            ?>

                <tr>
                    <td class="col-number"><?= $no++ ?></td>
                    <td><?= $satker->satker ?></td>
                    <?php if (empty($count_balai) && $query_balai != 'revision') {
                        $melapor += 1 ?>
                        <td class="melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                    <?php } else {
                        $belum_lapor += 1 ?>
                        <td class="melapor"></td>
                    <?php  } ?>
                    <?php if (!empty($count_balai) || $query_balai == 'revision') { ?>
                        <td class="belum-melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                    <?php } else { ?>
                        <td class="belum-melapor"></td>
                    <?php  } ?>
                    <?php if (empty($count_balai)) { ?>
                        <?php if(!empty($query_dokumen_balai)) { ?>
                            <td class="link-document"><a href="<?= base_url() ?>/api/showpdf/tampilkan/<?= $query_dokumen_balai['id'] ?>?preview=true" target="_blank"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                        <?php } else{ ?>
                            <td class="link-document"><a href="<?= base_url() ?>/api/showpdf/tampilkan/<?= $query_balai['id'] ?>?preview=true" target="_blank"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                        <?php } ?>
                    <?php } else {
                        echo '<td class="link-document">-</td>';
                    } ?>
                    <td class="date"><?php if (empty($query_balai['created_at'])) {
                                            echo '';
                                        } else {
                                            if(!empty($query_dokumen_balai['created_at'])) {
                                                echo date('d', strtotime($query_dokumen_balai['created_at'])) ?> <?= $bulan ?> <?= date('Y', strtotime($query_dokumen_balai['created_at']));
                                            } else {
                                                echo date('d', strtotime($query_balai['created_at'])) ?> <?= $bulan ?> <?= date('Y', strtotime($query_balai['created_at']));
                                            }
                                        } ?></td>
                    <?php if ($query_balai['acc_date'] != NULL || $query_balai['reject_date'] != NULL) {
                        $terverifikasi += 1;
                    ?>
                        <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                    <?php } else { ?>
                        <td class="checklist-verif"></td>
                    <?php } ?>
                    <?php if(empty($count_balai)) {
                    if ($query_balai['revision_same_year_number'] != 0) { ?>
                        <td class="link-document"><a href="<?= base_url() ?>/api/showpdf/tampilkan/<?= $query_balai['id'] ?>?preview=true" target="_blank"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                        <td class="date"><?php echo date('d', strtotime($query_balai['change_status_at'])) ?> <?= $bulan ?> <?= date('Y', strtotime($query_balai['change_status_at'])) ?></td>
                        <?php if ($query_balai['acc_date'] != NULL || $query_balai['reject_date'] != NULL) {
                            $revisi_terverifikasi += 1;
                        ?>
                            <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:20px;height:20px;"></td>
                        <?php } else { ?>
                            <td class="checklist-verif"></td>
                        <?php } ?>
                    <?php  } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php } ?>
                    <?php } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr>
            <td class="col-number">-</td>
            <td>Total <?= $no - 1 ?></td>
            <td class="melapor"><?= $melapor ?></td>
            <td class="melapor"><?= $belum_lapor ?></td>
            <td class="melapor"><?= '-' ?></td>
            <td class="melapor"><?= '-' ?></td>
            <td class="melapor"><?= $acc ?></td>
            <td class="melapor"><?= '-' ?></td>
            <td class="melapor"><?= '-' ?></td>
            <td class="melapor"><?= $revisi_terverifikasi ?></td>
        </tr>
    </table>
    <br>
    <!-- <div style="page-break-after: always;">
        <table width="50%">
            <tr>
                <th width="50%">Keterangan</th>
                <th width="50%">Jumlah</th>
            </tr>
            <tr>
                <td>Jumlah Total</td>
                <?php $total_jumlah = $no - 1; ?>
                <td><?= $total_jumlah ?></td>
            </tr>
            <tr>
                <td>Jumlah Satker Melapor</td>
                <td><?= $melapor ?></td>
            </tr>
            <tr>
                <td>Jumlah Satker Belum Melapor</td>
                <td><?= $belum_lapor ?></td>
            </tr>
            <tr>
                <td>Jumlah Menunggu Verifikasi</td>
                <td><?= $menunggu_konfir ?></td>
            </tr>
            <tr>
                <td>Jumlah Terverifikasi</td>
                <td><?= $terverifikasi ?></td>
            </tr>
        </table>
    </div> -->
    <?php
    $chart_belum_lapor = ($belum_lapor / $total_jumlah) * 100;
    $chart_menunggu_konfir = ($menunggu_konfir / $total_jumlah) * 100;
    $chart_acc = ($acc / $total_jumlah) * 100;
    $chart_reject = ($reject / $total_jumlah) * 100;
    ?>
    <br>
    <h5 class="title-chart">Chart Rekap</h5>
    <div class="chart">
        <div>
            <div class="ket-chart">
                <div class="card-cart">
                    <!-- <div id="piechart" style="width: 100%; max-width:900px; height: 500px; "></div> -->
                    <div id="flotcontainer"></div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="keterangan">
        <div class="div-keterangan">
            <table width="50%" class="tabel-keterangan">
                <tr>
                    <th width="30%">Keterangan</th>
                    <th width="30%">Persentase</th>
                    <th width="40%">Jumlah Instansi</th>
                </tr>
                <tr>
                    <td class="d-flex">
                        <div style="width: 15px; height: 15px; background: #807d7e; margin-top: 2px; margin-right: 10px"></div>
                        Belum Lapor
                    </td>
                    <td><?php echo round($chart_belum_lapor, 2) ?>%</td>
                    <td><?php echo $belum_lapor ?></td>
                </tr>
                <tr>
                    <td class="d-flex">
                        <div style="width: 15px; height: 15px; background: #1c81b0; margin-top: 2px; margin-right: 10px"></div>
                        Menunggu Verifikasi
                    </td>
                    <td><?php echo round($chart_menunggu_konfir, 2) ?>%</td>
                    <td><?php echo $menunggu_konfir ?></td>
                </tr>
                <tr>
                    <td class="d-flex">
                        <div style="width: 15px; height: 15px; background: #1cb02d; margin-top: 2px; margin-right: 10px"></div>
                        Terverifikasi
                    </td>
                    <td><?php echo round($chart_acc, 2) ?>%</td>
                    <td><?php echo $acc ?></td>
                </tr>
                <tr>
                    <td class="d-flex">
                        <div style="width: 15px; height: 15px; background: #a8163d; margin-top: 2px; margin-right: 10px"></div>
                        Ditolak
                    </td>
                    <td><?php echo round($chart_reject, 2) ?>%</td>
                    <td><?php echo $reject ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<?php echo script_tag('plugins/flot/jquery.flot.js'); ?>
<?php echo script_tag('plugins/flot/jquery.flot.pie.js'); ?>
<script>
    var data = [{
            label: "Belum Melapor",
            data: <?php echo $chart_belum_lapor ?>,
            color: '#807d7e'
        },
        {
            label: "Menunggu Verifikasi",
            data: <?php echo $chart_menunggu_konfir ?>,
            color: '#1c81b0'
        },
        {
            label: "Terverifikasi",
            data: <?php echo $chart_acc ?>,
            color: '#1cb02d'
        },
        {
            label: "Ditolak",
            data: <?php echo $chart_reject ?>,
            color: '#a8163d'
        }
    ];

    var options = {
        series: {
            pie: {
                show: true
            }
        },
        legend: {
            show: false
        }
    };

    $.plot($("#flotcontainer"), data, options);

    const $btnPrint = document.querySelector("#btn_print");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });
</script>

</html>