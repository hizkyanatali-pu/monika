<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
    table {
        border-collapse: collapse;
        border-spacing: 0;
        page-break-inside:auto
    }

    .header th {
        background-color : #3E7899;
        color: #ffffff;
        border: 1px solid white;
        
    }

    /* thead tr {
        border: 1px solid white;
    } */

    .ket-satker {
        text-align: left;
        background: #d4d700;
        border: 1px solid white;
    }

    td {
        border: 1px solid black;
        font-family: Arial;
        /* text-align: center; */
    }

    tr {
        page-break-inside:avoid; page-break-after:auto
    }

    .kop-rekap {
        text-align: center;
        font-weight: bold;
    }

    .col-number, .melapor, .belum-melapor, .link-document, .checklist-verif, .date {
        text-align: center;
        content: "\2714";
    }

    .page-break {
        page-break-after: always;
        page-break-inside: avoid;
    }
</style>
</head>
<body>

<?php 
    $this->db = \Config\Database::connect();
    $tb_balai  = $this->db->table('m_balai');
    $tb_satker  = $this->db->table('m_satker');
    $no = 1;
    $session = session();
    $this->user = $session->get('userData');
   
?>

    <h5 class="kop-rekap">REKAPITULASI PENGIRIMAN DOKUMEN PERJANJIAN KINERJA (PK) ES II / UPT / SATUAN KERJA TA <?= $session_year ?>
        <br>DITJEN SUMBER DAYA AIR
        <br>STATUS
        <?= $tanggal ?> <?= $bulan ?> <?= $tahun ?>
        ; <?= $jam ?> WIB
    </h5>

    <table width="100%">
        <!-- <thead class="head-table"> -->
            <tr class="header">
                <th width="5%" rowspan="3">No</th>
                <th width="20%" rowspan="3">Nama Unit / Satker</th>
                <th width="15%" colspan="2">Entitas Kerja</th>
                <th width="60%" colspan="6">Form</th>
            </tr>
            <tr class="header">
                <th width="10%" rowspan="2">Melapor</th>
                <th width="10%" rowspan="2">Belum Melapor</th>
                <th width="30%" colspan="3" rowspan="1">PK Awal</th>
                <th width="30%" colspan="3" rowspan="1">PK Revisi</th>
            </tr>
            <tr class="header">
                <th width="10%" rowspan="1">Dokumen</th>
                <th width="10%" rowspan="1">Tanggal Kirim</th>
                <th width="10%" rowspan="1">Verifikasi</th>
                <th width="10%" rowspan="1">Dokumen</th>
                <th width="10%" rowspan="1">Tanggal Kirim</th>
                <th width="10%" rowspan="1">Verifikasi</th>
            </tr>
        <!-- </thead> -->
            <?php foreach($group_jabatan as $key => $grup) {
                switch ($grup) {
                    case 'ESELON II':
                        # code...
                        $satker_s =  $tb_satker->select('satker, satkerid')->where('grup_jabatan', "eselon2")->Orwhere('satkerid', 352611)->get()->getResult();
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
                        $satker_s =  $tb_satker->select('satker, satkerid')->where('grup_jabatan', "satker-pusat")->get()->getResult();
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
                    foreach($satker_s as $view) {
                            
                            if($grup == 'UPT/BALAI') {
                                $query_satker = $tb_balai->select('dokumenpk_satker.id, dokumenpk_satker.created_at, dokumenpk_satker.deleted_at, dokumenpk_satker.id, dokumenpk_satker.acc_date, dokumenpk_satker.reject_date, dokumenpk_satker.revision_same_year_number, dokumenpk_satker.change_status_at')
                                                ->join('dokumenpk_satker', 'dokumenpk_satker.balaiid = m_balai.balaiid', 'left')
                                                ->where('m_balai.balaiid', $view->balaiid)
                                                ->get()->getRowArray();
                                $count = $tb_balai->select("m_balai.balai")
                                        ->where("(SELECT count(id) FROM dokumenpk_satker WHERE balaiid=m_balai.balaiid and tahun={$this->user['tahun']} ORDER BY id DESC) < 1 AND balaiid = $view->balaiid")
                                        ->get()->getRowArray();

                            } else {
                                $query_satker = $tb_satker->select('dokumenpk_satker.id, dokumenpk_satker.created_at, dokumenpk_satker.deleted_at, dokumenpk_satker.id, dokumenpk_satker.acc_date, dokumenpk_satker.reject_date, dokumenpk_satker.revision_same_year_number, dokumenpk_satker.change_status_at')
                                                ->join('dokumenpk_satker', 'dokumenpk_satker.satkerid = m_satker.satkerid', 'left')
                                                ->where('m_satker.satkerid', $view->satkerid)
                                                ->get()->getRowArray();
                                $count = $tb_satker->select('m_satker.satker')
                                        ->where("(SELECT count(id) FROM dokumenpk_satker WHERE satkerid=m_satker.satkerid and tahun= {$this->user['tahun']} ORDER BY id DESC) < 1 and satkerid = $view->satkerid")
                                        ->get()->getRowArray();
                            }
                            // var_dump($count); 
                            // die;


                            $month = date('m', strtotime($query_satker['created_at']));
                            if($month == '1') {
                                $bulan = 'Januari';
                            } else if($month == '2') {
                                $bulan = 'Februari';
                            } else if($month == '3') {
                                $bulan = 'Maret';
                            } else if($month == '4') {
                                $bulan = 'April';
                            } else if($month == '5') {
                                $bulan = 'Mei';
                            } else if($month == '6') {
                                $bulan = 'Juni';
                            } else if($month == '7') {
                                $bulan = 'Juli';
                            } else if($month == '8') {
                                $bulan = 'Agustus';
                            } else if($month == '9') {
                                $bulan = 'September';
                            } else if($month == '10') {
                                $bulan = 'Oktober';
                            } else if($month == '11') {
                                $bulan = 'November';
                            } else if($month == '12') {
                                $bulan = 'Desember';
                            }
                        ?>
                        <tr>
                            <td class="col-number"><?= $no++ ?></td>
                            <td><?= $view->satker ?></td>
                            <?php if(empty($count) && $query_satker['deleted_at'] == NULL) { ?>
                                <td class="melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                            <?php } else { ?>
                                <td class="melapor"></td>
                            <?php  } ?>
                            <?php if(!empty($count) || $query_satker['deleted_at'] != NULL) { ?>
                                <td class="belum-melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                            <?php } else { ?>
                                <td class="belum-melapor"></td>
                            <?php  } ?>
                            <?php if(empty($count) && $query_satker['deleted_at'] == NULL) { ?>
                                <td class="link-document"><a href="http:/api/showpdf/tampilkan/<?= $query_satker['id'] ?>?preview=true"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                            <?php } else {
                                echo '<td class="link-document">-</td>';
                            } ?>
                            <td class="date"><?php if(empty($query_satker['created_at'])) {
                                echo '';
                                } else {
                                    echo date('d', strtotime($query_satker['created_at'])) ?> <?= $bulan ?> <?= date('Y', strtotime($query_satker['created_at']));
                                } ?></td>
                            <?php if($query_satker['acc_date'] != null || $query_satker['reject_date'] != NULL) { ?>
                                <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                            <?php } else { ?>
                                <td class="checklist-verif"></td>
                            <?php } ?>
                            <?php if($query_satker['revision_same_year_number'] != 0) { ?>
                                <td class="link-document"><a href="http:/api/showpdf/tampilkan/<?= $query_satker['id'] ?>?preview=true"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                                <td class="date"><?php echo date('d', strtotime($query_satker['change_status_at'])) ?> <?= $bulan ?> <?= date('Y', strtotime($query_satker['change_status_at'])) ?></td>
                                <?php if($query_satker['acc_date'] != null || $query_satker['reject_date'] != NULL) { ?>
                                    <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:20px;height:20px;"></td>
                                <?php } else { ?>
                                    <td class="checklist-verif"></td>
                                <?php } ?>
                            <?php } else { ?>
                                <td></td>
                                <td></td>
                                <td></td>
                            <?php } ?>
                        </tr>
                <?php } ?>
            <?php } ?>
            <?php foreach($balai_s as $balai) {
                $satker_ss =  $tb_satker->select('satker, satkerid')->where('balaiid', $balai->balaiid)->get()->getResult();
                ?>
                <tr>
                    <td class="ket-satker" width="100%" colspan="10"><?= $balai->balai ?></td>
                </tr>
                <?php
                    foreach($satker_ss as $satker) { 
                        $query_balai = $tb_satker->select('dokumenpk_satker.created_at, dokumenpk_satker.id, dokumenpk_satker.acc_date, dokumenpk_satker.reject_date, dokumenpk_satker.revision_same_year_number, dokumenpk_satker.change_status_at')
                        ->join('dokumenpk_satker', 'dokumenpk_satker.satkerid = m_satker.satkerid', 'left')
                        ->where('m_satker.satkerid', $satker->satkerid)
                        ->get()->getRow();
                        $count_balai = $tb_satker->select('m_satker.satker')
                        ->where("(SELECT count(id) FROM dokumenpk_satker WHERE dokumen_type='satker' and satkerid=m_satker.satkerid and balaiid=m_satker.balaiid and tahun= {$this->user['tahun']}) < 1 and m_satker.grup_jabatan = 'satker' and satkerid = $satker->satkerid")
                        ->get()->getRow();

                        // var_dump($count_balai);
                        // die;
                ?>
                
                        <tr>
                        <td class="col-number"><?= $no++ ?></td>
                            <td><?= $satker->satker ?></td>
                            <?php if(empty($count_balai)) { ?>
                                <td class="melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                            <?php } else { ?>
                                <td class="melapor"></td>
                            <?php  } ?>
                            <?php if(!empty($count_balai)) { ?>
                                <td class="belum-melapor"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                            <?php } else { ?>
                                <td class="belum-melapor"></td>
                            <?php  } ?>
                            <?php if(empty($count_balai)) { ?>
                                <td class="link-document"><a href="http:/api/showpdf/tampilkan/<?= $query_balai->id ?>?preview=true"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                            <?php } else {
                                echo '<td class="link-document">-</td>';
                            } ?>
                            <td class="date"><?php if(empty($query_balai->created_at)) {
                                echo '';
                                } else {
                                    echo date('d', strtotime($query_balai->created_at)) ?> <?= $bulan ?> <?= date('Y', strtotime($query_balai->created_at));
                                } ?></td>
                            <?php if($query_balai->acc_date != null || $query_balai->reject_date != NULL) { ?>
                                <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                            <?php } else { ?>
                                <td class="checklist-verif"></td>
                            <?php } ?>
                            <?php if($query_balai->revision_same_year_number != 0) { ?>
                                <td class="link-document"><a href="http:/api/showpdf/tampilkan/<?= $query_balai->id ?>?preview=true"><img src="<?= 'https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png' ?>" style="width:42px;height:42px;"></a></td>
                                <td class="date"><?php echo date('d', strtotime($query_balai->change_status_at)) ?> <?= $bulan ?> <?= date('Y', strtotime($query_balai->change_status_at)) ?></td>
                                <?php if($query_balai->acc_date != null || $query_balai->reject_date != NULL) { ?>
                                    <td class="checklist-verif"><img src="<?= 'https://cdn-icons-png.flaticon.com/512/7046/7046050.png' ?>" style="width:25px;height:25px;"></td>
                                <?php } else { ?>
                                    <td class="checklist-verif"></td>
                                <?php } ?>
                            <?php } else { ?>
                                <td></td>
                                <td></td>
                                <td></td>
                            <?php } ?>
                        </tr>
                <?php } ?>
            <?php } ?>
    </table>
</body>
</html>