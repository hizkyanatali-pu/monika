<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style type="text/css">
    .chart {
        width: 25vw;
        height: 25vw;
        text-align: left;
    }

    /* .pieLabel div {
        font-weight: bold !important;
        font-size: 10px !important;
    } */
</style>

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
$componen = '';
$total_componen = '';
?>

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

    $componen .= '<tr class="tr-body">
                            <td class="ket-satker" width="100%" colspan="10">' .  $grup  . '</td>
                        </tr>';

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

        if (empty($count) && $query_satker != 'revision') {
            $melapor += 1;
            $data_melapor = '<img src="https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
        } else {
            $data_melapor = '';
        }

        if (!empty($count) || $query_satker == 'revision') {
            $belum_lapor += 1;
            $data_belum_melapor = '<img src="https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
        } else {
            $data_belum_melapor = '';
        }

        if (empty($count)) {
            $file_dokumen = '<a href="' . base_url() . '/api/showpdf/tampilkan/' . $query_dokumen['id'] . '?preview=true" target="_blank"><img src="https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png" style="width:42px;height:42px;"></a>';
        } else {
            $file_dokumen = '-';
        }
        if (empty($query_satker['created_at'])) {
            $tanggal_kirim = '-';
        } else {
            $tanggal_kirim = date('d', strtotime($query_dokumen['created_at'])) . ' ' . $bulan . ' ' . date('Y', strtotime($query_dokumen['created_at']));
        }

        if (isset($query_satker['acc_date']) != NULL || isset($query_satker['reject_date']) != NULL) {
            $terverifikasi += 1;
            $verifikasi_satker1 = '<img src=" https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
        } else {
            $verifikasi_satker1 = '';
        }

        if (empty($count)) {
            if ($query_satker['revision_same_year_number'] != 0) {
                $file_dokumen_revisi = '<a href="' . base_url() . '/api/showpdf/tampilkan/' . $query_satker['id'] . '?preview=true" target="_blank"><img src="https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png" style="width:42px;height:42px;"></a>';
                $tanggal_kirim_revisi = date('d', strtotime($query_dokumen['created_at'])) . ' ' . $bulan . ' ' . date('Y', strtotime($query_dokumen['created_at']));

                if (isset($query_balai['acc_date']) != NULL || isset($query_balai['reject_date']) != NULL) {
                    $revisi_terverifikasi += 1;
                    $verifikasi_satker2 = '<img src=" https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
                } else {
                    $verifikasi_satker2 = '';
                }
            } else {
                $file_dokumen_revisi = '';
                $tanggal_kirim_revisi = '';
                $verifikasi_satker2 = '';
            }
        } else {
            $file_dokumen_revisi = '';
            $tanggal_kirim_revisi = '';
            $verifikasi_satker2 = '';
        }

        $componen .= '<tr class="tr-isi">
                                <td class="td-isi">' . $no++ . '</td>
                                <td class="td-satker">' . $view->satker . '</td>
                                <td class="td-isi">' . $data_melapor . '</td>
                                <td class="td-isi">' . $data_belum_melapor . '</td>
                                <td class="td-isi">' . $file_dokumen . '</td>
                                <td class="td-isi">' . $tanggal_kirim . '</td>
                                <td class="td-isi">' . $verifikasi_satker1 . '</td>
                                <td class="td-isi">' . $file_dokumen_revisi . '</td>
                                <td class="td-isi">' . $tanggal_kirim_revisi . '</td>
                                <td class="td-isi">' . $verifikasi_satker2 . '</td>
                            </tr>';
    }
}


foreach ($balai_s as $balai) {
    $satker_ss =  $tb_satker->select('satker, satkerid')->where('balaiid', $balai->balaiid)->get()->getResult();

    $componen .= '<tr>
                        <td class="ket-satker" width="100%" colspan="10">' . $balai->balai . '</td>
                    </tr>';
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


        #kondisi
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

        if (empty($count_balai) && $query_balai != 'revision') {
            $melapor += 1;
            $data_balai_melapor = '<img src="https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
        } else {
            $data_balai_melapor = '';
        }

        if (!empty($count_balai) || $query_balai == 'revision') {
            $belum_lapor += 1;
            $data_balai_belum_melapor = '<img src="https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
        } else {
            $data_balai_belum_melapor = '';
        }

        if (empty($count_balai)) {
            if (!empty($query_dokumen_balai)) {
                $file_dokumen_balai = '<a href="' . base_url() . '/api/showpdf/tampilkan/' . $query_dokumen_balai['id'] . '?preview=true" target="_blank"><img src="https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png" style="width:42px;height:42px;"></a>';
            } else {
                $file_dokumen_balai = '<a href="' . base_url() . '/api/showpdf/tampilkan/' . $query_balai['id'] . '?preview=true" target="_blank"><img src="https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png" style="width:42px;height:42px;"></a>';
            }
        } else {
            $file_dokumen_balai = '-';
        }

        if (empty($query_balai['created_at'])) {
            $tanggal_kirim_balai = '-';
        } else {
            if (!empty($query_dokumen_balai['created_at'])) {
                $tanggal_kirim_balai = date('d', strtotime($query_dokumen_balai['created_at'])) . ' ' . $bulan . ' ' . date('Y', strtotime($query_dokumen_balai['created_at']));
            } else {
                $tanggal_kirim_balai = date('d', strtotime($query_balai['created_at'])) . ' ' . $bulan . ' ' . date('Y', strtotime($query_balai['created_at']));
            }
        }

        if (isset($query_balai['acc_date']) != NULL || isset($query_balai['reject_date']) != NULL) {
            $terverifikasi += 1;
            $verifikasi_balai1 = '<img src=" https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
        } else {
            $verifikasi_balai1 = '';
        }

        if (empty($count_balai)) {
            if ($query_balai['revision_same_year_number'] != 0) {
                $file_dokumen_revisi_balai = '<a href="' . base_url() . '/api/showpdf/tampilkan/' . $query_balai['id'] . '?preview=true" target="_blank"><img src="https://icons.iconarchive.com/icons/vexels/office/256/document-search-icon.png" style="width:42px;height:42px;"></a>';
                $tanggal_kirim_revisi_balai = date('d', strtotime($query_balai['created_at'])) . ' ' . $bulan . ' ' . date('Y', strtotime($query_balai['created_at']));

                if ($query_balai['acc_date'] != NULL || $query_balai['reject_date'] != NULL) {
                    $revisi_terverifikasi += 1;
                    $verifikasi_balai2 = '<img src=" https://cdn-icons-png.flaticon.com/512/7046/7046050.png" style="width:25px;height:25px;">';
                } else {
                    $verifikasi_balai2 = '';
                }
            } else {
                $file_dokumen_revisi_balai = '';
                $tanggal_kirim_revisi_balai = '';
                $verifikasi_balai2 = '';
            }
        } else {
            $file_dokumen_revisi_balai = '';
            $tanggal_kirim_revisi_balai = '';
            $verifikasi_balai2 = '';
        }

        $jumlah_total = $no;

        $componen .= '<tr class="tr-isi">
                                    <td class="td-isi">' . $no++ . '</td>
                                    <td class="td-satker">' . $satker->satker . '</td>
                                    <td class="td-isi">' . $data_balai_melapor . '</td>
                                    <td class="td-isi">' . $data_balai_belum_melapor . '</td>
                                    <td class="td-isi">' . $file_dokumen_balai . '</td>
                                    <td class="td-isi">' . $tanggal_kirim_balai . '</td>
                                    <td class="td-isi">' . $verifikasi_balai1 . '</td>
                                    <td class="td-isi">' . $file_dokumen_revisi_balai . '</td>
                                    <td class="td-isi">' . $tanggal_kirim_revisi_balai . '</td>
                                    <td class="td-isi">' . $verifikasi_balai2 . '</td>
                                </tr>';
    }
}
$total_componen .= '<tr class="tr-isi">
                                            <td class="td-isi">-</td>
                                            <td class="td-satker">Total ' . $jumlah_total . '</td>
                                            <td class="td-isi">' . $melapor . '</td>
                                            <td class="td-isi">' . $belum_lapor . '</td>
                                            <td class="td-isi">-</td>
                                            <td class="td-isi">-</td>
                                            <td class="td-isi">' . $acc . '</td>
                                            <td class="td-isi">-</td>
                                            <td class="td-isi">-</td>
                                            <td class="td-isi">' . $revisi_terverifikasi . '</td>
                                        </tr>';

$chart_belum_lapor = ($belum_lapor / $jumlah_total) * 100;
$chart_menunggu_konfir = ($menunggu_konfir / $jumlah_total) * 100;
$chart_acc = ($acc / $jumlah_total) * 100;
$chart_reject = ($reject / $jumlah_total) * 100;

?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex justify-content-start">
                    <h5 class="kt-subheader__title">
                        Dashboard Perjanjian Kinerja
                    </h5>
                </div>
                <div>
                </div>
            </div>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid mt-3">
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-6 chart">
                    <canvas id="flotcontainer"></canvas>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //echo $piechart['jumlahall'] 
                            ?>
                            <table class="table table-striped" style="border: 1px solid #807d7e;">
                                <thead>
                                    <tr>
                                        <th>Keterangan</th>
                                        <th>Persentase</th>
                                        <th>Jumlah Instansi</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                    <tr>
                                        <td class="d-flex">
                                            Total
                                        </td>
                                        <td>
                                            <?php $jumlah_persen =  $chart_belum_lapor + $chart_menunggu_konfir + $chart_acc + $chart_reject;
                                            echo round($jumlah_persen) ?>%
                                        </td>
                                        <td>
                                            <?php $jumlah_instansi = $belum_lapor + $menunggu_konfir + $acc + $reject;
                                            echo $jumlah_instansi ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid mt-3">
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="clearfix mb-3">
                <h5 class="kop-rekap">REKAPITULASI PENGIRIMAN DOKUMEN PERJANJIAN KINERJA (PK) ES II / UPT / SATUAN KERJA TA <?= $session_year ?>
                    <br>DITJEN SUMBER DAYA AIR
                    <br>STATUS
                    <?= $tanggal ?> <?= $bulan_status ?> <?= $tahun ?>
                    , <?= $jam ?> WIB
                </h5>
                <hr>
                <div class="button-pdf text-right">
                    <a href="<?= site_url('dokumenpk/rekap') ?>" target="_blank" class="btn btn-primary btn-sm text-white"><i class="fa fa-file-pdf"> Rekap</i></a>
                </div>
                <br>
                <div class="tabel-rekap tableFixHead card row">
                    <table class="table-bordered" width="100%">
                        <thead class="table-primary text-dark">
                            <tr class="tr-head">
                                <th class="th-head" width="5%" rowspan="3">No</th>
                                <th class="th-head" width="20%" rowspan="3">Nama Unit / Satker</th>
                                <th class="th-head" width="15%" colspan="2">Entitas Kerja</th>
                                <th class="th-head" width="60%" colspan="6">Form</th>
                            </tr>
                            <tr class="tr-head">
                                <th class="th-head" width="10%" rowspan="2">Melapor</th>
                                <th class="th-head" width="10%" rowspan="2">Belum Melapor</th>
                                <th class="th-head" width="30%" colspan="3" rowspan="1">PK Awal</th>
                                <th class="th-head" width="30%" colspan="3" rowspan="1">PK Revisi</th>
                            </tr>
                            <tr class="tr-head">
                                <th class="th-head" width="10%" rowspan="1">Dokumen</th>
                                <th class="th-head" width="10%" rowspan="1">Tanggal Kirim</th>
                                <th class="th-head" width="10%" rowspan="1">Verifikasi</th>
                                <th class="th-head" width="10%" rowspan="1">Dokumen</th>
                                <th class="th-head" width="10%" rowspan="1">Tanggal Kirim</th>
                                <th class="th-head" width="10%" rowspan="1">Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $componen ?>
                            <?= $total_componen ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- end:: Content -->
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- <script src="http://static.pureexample.com/js/flot/excanvas.min.js"></script>
<script src="http://static.pureexample.com/js/flot/jquery.flot.min.js"></script>
<script src="http://static.pureexample.com/js/flot/jquery.flot.pie.min.js"></script> -->

<script>
    var data = {
        labels: ["Belum Melapor", "Menunggu Verifikasi", "Terverifikasi", "Ditolak"],
        datasets: [{
            data: [<?= round($chart_belum_lapor, 2) ?>, <?= round($chart_menunggu_konfir, 2) ?>, <?= round($chart_acc, 2) ?>, <?= round($chart_reject, 2) ?>],
            backgroundColor: ['#807d7e', '#1c81b0', '#1cb02d', '#a8163d']
        }]
    };

    var options = {
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.labels[tooltipItem.index] || '';
                    var value = data.datasets[0].data[tooltipItem.index];
                    return label + ': ' + value.toFixed(2) + '%';
                }
            }
        }
    };

    var ctx = document.getElementById('flotcontainer').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });
</script>

<?= $this->endSection() ?>