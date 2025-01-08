<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Pantauan Pelaksanaan Anggaran TA <?= date('Y') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php echo link_tag('css/tree.css'); ?>
    <style>
        body{
            width: 100%;
            overflow-x: hidden;
        }
        section{
            height: 95vh;
            position: relative;
            margin-bottom: 20vh;
        }
        @media print {
            section {
                clear: both;
                page-break-after: always;
            }
        }
        .page-break {
            clear: both;
            page-break-after: always;
        }
        .img-footer{
            position: absolute;
            bottom: 0;
            right: 0;
            width: 200px;
            z-index: -1;
        }
        .text-footer{
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .fs-50{
            font-size: 50px;
        }
        .fs-30{
            font-size: 30px;
        }
        .fs-15{
            font-size: 15px;
        }

        .card-1{
            background: url("<?= base_url('images/laporan/9.png'); ?>") ;
            background-size: cover;
            background-position: center center;
        }

        .card-2{
            background: url("<?= base_url('images/laporan/9.png'); ?>") ;
            background-size: cover;
            background-position: center center;
        }

        .card-3{
            background: url("<?= base_url('images/laporan/10.png'); ?>") ;
            background-size: 100% ;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .card-4{
            background: url("<?= base_url('images/laporan/11.png'); ?>") ;
            background-size: 100% ;
            padding:25px 0;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .card-5{
            background: url("<?= base_url('images/laporan/12.png'); ?>") ;
            background-size: 100% ;
            background-repeat: no-repeat;
            background-position: bottom;
            padding-bottom: 30px;
        }

        th{
            text-align: center; 
            vertical-align: middle;
        }
        .table-1>thead>*>*, .table-1>tfoot>*>*{
            background-color: #ffc000 !important;
        }
        .table-2>thead>*>*, .table-2>tfoot>*>*{
            background-color: #5e767e !important;
            color: white;
        }
        .table-2>tbody>tr.head>*{
            background-color: #ffc000 !important;
        }
        .table-2>tbody>tr.subhead>*{
            background-color: #66ff99 !important;
        }
        .table-3>thead>*>*{
            background-color: #074f69 !important;
            color: white;
        }
        .table-3>tbody>tr.head>*{
            background-color: #caedfb !important;
        }
        .table-4>thead>*>*{
            background-color: #224e7b !important;
            color: white;
        }
        .table-4>tbody>tr.head>*{
            background-color: #bfbfbe !important;
        }
        .table-5>thead>*>*{
            background-color: #ffc000 !important;
        }
        .table-5>tbody>tr.head>*{
            background-color: #caeefb !important;
        }
        .table-6>thead>*>*{
            background-color: #ffc000 !important;
        }
        .table-7>thead>*>*{
            background-color: #caeefb !important;
        }
        .table-7>tbody>tr.head>*{
            background-color: #ffc000 !important;
        }
    </style>
</head>

<body>
    <section class="d-flex align-items-center ">
        <div>
            <h1 class="fs-50">Pantauan <br>Pelaksanaan <br>Anggaran TA <?= date('Y') ?></h1>
            <b class="fs-15">Status <?= date("d F Y, h:i") ?></b>
            <p class="text-footer fs-15">
                KEMENTERIAN PEKERJAAN UMUM DAN PERUMAHAN RAKYAT <br>
                DIREKTORAT JENDERAL SUMBER DAYA AIR
            </p>
        </div>
    </section>
    <?php foreach ($report_list_selected as $value): ?>
        <?php if ($value == "PROGRES ANGGARAN PER SUMBER DANA"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <?php $totalPagu = 0 ?>
                    <?php foreach ($pagu as $key => $value) : ?>
                        <?php $totalPagu += $value->totalPagu ?>
                    <?php endforeach ?>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="tree  pr-4">
                            <ul>
                                <li class="w-100">
                                    <a href="#" class="w-25">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-1">
                                                <h4 class="mb-0"><b> Total Pagu </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toRupiah($totalPagu) ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul>
                                        <li class="" style="width: 33% !important">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-secondary text-white">
                                                        <h4 class="mb-0"><b>RPM</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_rpm, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card bg-tree-3  text-white bg-tree-footer card-body shadow text-left">
                                                        <h4 class="mb-0"><b>Realisasi</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_real_rpm, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card bg-danger  text-white bg-tree-footer card-body shadow text-left">
                                                        <h4 class="mb-0"><b>Sisa Anggaran</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_rpm-$pagu_all->total_real_rpm, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="" style="width: 33% !important">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-secondary text-white">
                                                        <h4 class="mb-0"><b>SBSN</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_sbsn, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card bg-tree-3  text-white bg-tree-footer card-body shadow text-left">
                                                        <h4 class="mb-0"><b>Realisasi</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_real_sbsn, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card bg-danger  text-white bg-tree-footer card-body shadow text-left">
                                                        <h4 class="mb-0"><b>Sisa Anggaran</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_sbsn-$pagu_all->total_real_sbsn, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="" style="width: 33% !important">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-secondary text-white">
                                                        <h4 class="mb-0"><b>PLN</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_phln, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card bg-tree-3  text-white bg-tree-footer card-body shadow text-left">
                                                        <h4 class="mb-0"><b>Realisasi</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_real_phln, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card bg-danger  text-white bg-tree-footer card-body shadow text-left">
                                                        <h4 class="mb-0"><b>Sisa Anggaran</b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_phln-$pagu_all->total_real_phln, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-1">
                            <div class="card-body font-weight-bold">
                                <p>PROGRES </p>
                                <p>KEUANGAN</p>
                                <p><?= isset($keuProgressSda) ? number_format($keuProgressSda, 2, ',', '.') : 0 ?></p>
                                <p>Deviasi</p>
                                <p><?= $total_deviasi ?></p>
                            </div>
                        </div>
                        <div class="card border-0 text-center card-1">
                            <div class="card-body font-weight-bold">
                                <p>PROGRES </p>
                                <p>FISIK</p>
                                <p><?= isset($fisikProgressSda) ? number_format($fisikProgressSda, 2, ',', '.') : 0 ?></p>
                                <p>Deviasi</p>
                                <p><?= $total_deviasi ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-footer fs-15">
                    Progres Realisasi sedikit terhambat karena adanya penutupan akses iEmonitoring - MONSAKTI, sementara dilakukan secara manual
                </div>
                <img class="img-footer" src="<?= base_url('images/laporan/5.png'); ?>" />
            </section>
        <?php }if ($value == "PROGRES PUPR PER UNIT ORGANISASI"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-1 table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Unit Organisasi</th>
                            <th colspan="4">Pagu (Rp M)</th>
                            <th colspan="4">Realisasi (Rp M)</th>
                            <th colspan="2">Progres (%)</th>
                        </tr>
                        <tr>
                            <th>RPM</th>
                            <th>SBSN</th>
                            <th>PLN</th>
                            <th>Total</th>
                            <th>RPM</th>
                            <th>SBSN</th>
                            <th>PLN</th>
                            <th>Total</th>
                            <th>Keu</th>
                            <th>Fisik</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rekapunor['unor'] as $key => $val) { ?>
                            <tr <?= ($val['kdunit'] == 06 ? "class='tdprogram font-weight-bold'" : "") ?>>
                                <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" scope="row"><?= ++$key ?></th>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px"><?= $val['nmsingkat']; ?></td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_rpm'], 2, ',', '.'); ?></td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_sbsn'], 2, ',', '.'); ?></td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_phln'], 2, ',', '.'); ?></td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['pagu_total'], 2, ',', '.'); ?></td>

                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_rpm"], 2, ',', '.'); ?></td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_sbsn"], 2, ',', '.'); ?></td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_phln"], 2, ',', '.'); ?></td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val["real_total"], 2, ',', '.'); ?></td>

                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_keu'], 2, ',', '.'); ?> %</td>
                                <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_fisik'], 2, ',', '.'); ?> %</td>
                            </tr>
                        <?php   } ?>

                        <tr class="text-center text-white" style="background-color: #1562aa;">
                            <td colspan="2">TOTAL</td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_rpm'], 2, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_sbsn'], 2, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_phln'], 2, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['pagu_total'], 2, ',', '.'); ?></td>

                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_rpm"], 2, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_sbsn"], 2, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_phln"], 2, ',', '.'); ?></td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']["real_total"], 2, ',', '.'); ?></td>

                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['progres_keu'], 2, ',', '.'); ?> %</td>
                            <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['progres_fisik'], 2, ',', '.'); ?> %</td>
                        </tr>
                    </tbody>
                </table>
                <p class="fs-30">KINERJA & BLOKIR PER KEGIATAN TA <?= date('Y') ?></p>
                <table class="table table-1 table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kegiatan</th>
                            <th rowspan="2">Pagu Rp Miliar</th>
                            <th rowspan="2">Blokir Rp Miliar</th>
                            <th rowspan="2">Realisasi Rp Miliar</th>
                            <th colspan="3">Keuangan %</th>
                            <th colspan="3">Fisik %</th>
                        </tr>
                        <tr>
                            <th>Progres</th>
                            <th>Rencana</th>
                            <th>Deviasi</th>
                            <th>Progres</th>
                            <th>Rencana</th>
                            <th>Deviasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $sum_total_pagu = 0;
                            $sum_total_blokir = 0;
                            $sum_total_real = 0;

                            foreach ($kegiatan as $key => $value):

                            $sum_total_pagu += $value->total_pagu;
                            $sum_total_blokir += $value->total_blokir;
                            $sum_total_real += $value->total_real; 
                        ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $value->nmgiat ?></td>
                            <td><?= toMilyar($value->total_pagu, false, 2) ?></td>
                            <td><?= toMilyar($value->total_blokir, false, 2) ?></td>
                            <td><?= toMilyar($value->total_real, false, 2) ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <th colspan="2">Total</th>
                        <th><?= toMilyar($sum_total_pagu, false, 2) ?></th>
                        <th><?= toMilyar($sum_total_blokir, false, 2) ?></th>
                        <th><?= toMilyar($sum_total_real, false, 2) ?></th>
                        <th>Data</th>
                        <th>Data</th>
                        <th>Data</th>
                        <th>Data</th>
                        <th>Data</th>
                        <th>Data</th>
                    </tfoot>
                </table>
            </section>
            <section></section>
        <?php }if ($value == "PAKET KEGIATAN KONTRAKTUAL TA TAHUN"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <div class="tree">
                    <ul>
                        <li class="w-100">
                            <a href="#viewkontraktual" class="">
                                <div class="tree-content">
                                    <div class="card card-body bg-tree-1">
                                        <h4 class="mb-0"><b> KONTRAKTUAL </b></h4>

                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                            <h6><?= number_format(($terkontrak['jml_paket'] + ($proseslelang['jml_paket'] + $belumlelang['jml_paket'] + $persiapankontrak['jml_paket'] + $gagallelang['jml_paket'])), 0, ',', '.'); ?> Paket</h6>
                                            <h5 class="mb-0">
                                                <?= toMilyar($terkontrak['nilai_kontrak'] + ($proseslelang['nilai_kontrak'] + $belumlelang['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'] + $gagallelang['nilai_kontrak']), true, 2); ?> M
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                            </a>
                            <ul>
                                <li class="" style="width: 60% !important">
                                    <a href="#viewkontraktual" class="">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> BELUM KONTRAK </b></h4>

                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h6><?= number_format(($belumlelang['jml_paket'] + $proseslelang['jml_paket'] + $gagallelang['jml_paket']), 0, ',', '.'); ?> Paket</h6>
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($belumlelang['nilai_kontrak'] + $proseslelang['nilai_kontrak'] + $gagallelang['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>

                                    </a>
                                    <ul>
                                        <li class="w-30">
                                            <a href="#viewkontraktual" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-4">
                                                        <h4 class="mb-0"><b> GAGAL LELANG </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h6><?= number_format($gagallelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($gagallelang['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                        </li>
                                        <li class="w-30">
                                            <a href="#viewkontraktual" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-4">
                                                        <h4 class="mb-0"><b> PROSES LELANG </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h6><?= number_format($proseslelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($proseslelang['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                        </li>
                                        <li class="w-40">
                                            <a href="#belum-lelang" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-4">
                                                        <h4 class="mb-0"><b> BELUM LELANG </b></h4>

                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h6><?= number_format($belumlelang['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="" style="width: 40% !important">
                                    <a href="#viewkontraktual" class="">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> TERKONTRAK * </b></h4>

                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h6> <?= number_format($terkontrak['jml_paket'] + $persiapankontrak['jml_paket'], 0, ',', '.'); ?> Paket</h6>
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($terkontrak['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <b><i>* Termasuk MYC lanjutan</i></b>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </div>
                <img class="img-footer" src="<?= base_url('images/laporan/5.png'); ?>" />
            </section>
        <?php }if ($value == "PAKET KEGIATAN BELUM LELANG TA TAHUN"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <div class="tree ml--105 pr-4">
                    <ul>
                        <li class="w-100">
                            <a href="#" class="w-25">
                                <div class="tree-content">
                                    <div class="card card-body bg-tree-1">
                                        <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                        <label> <?= formatNumber($nilai_rpm['jml_paket'] + $nilai_sbsn['jml_paket'] + $nilai_phln['jml_paket']); ?> Paket</label>
                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                            <h5 class="mb-0">
                                                <?= toMilyar($nilai_rpm['nilai_kontrak'] + $nilai_sbsn['nilai_kontrak'] + $nilai_phln['nilai_kontrak'], true, 2); ?> M
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <ul>
                                <li class="" style="width: 50% !important">
                                    <a href="#" class="w-50">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> RPM </b></h4>
                                                <label> <?= formatNumber($nilai_rpm['jml_paket']) ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($nilai_rpm['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul>
                                        <li class="w-50">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-3">
                                                        <h4 class="mb-0"><b> SYC </b></h4>
                                                        <label> <?= formatNumber($rpmSyc['jml_paket']); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($rpmSyc['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card text-dark bg-tree-footer card-body shadow text-left">
                                                        <h6>Antara Lain :</h6>
                                                        <?php foreach ($rpmSycList as $key => $daftarsyc) { ?>
                                                            <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="w-50">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-3">
                                                        <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                        <label> <?= formatNumber($rpmMyc['jml_paket']); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($rpmMyc['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card text-dark bg-tree-footer card-body shadow text-left">
                                                        <h6>Antara Lain :</h6>
                                                        <?php foreach ($rpmMycList as $key => $daftarsyc) { ?>
                                                            <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="" style="width: 20% !important">
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> SBSN </b></h4>
                                                <label><?= formatNumber($nilai_sbsn['jml_paket']); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($nilai_sbsn['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="" style="width: 30% !important">
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> PHLN </b></h4>
                                                <label><?= formatNumber($nilai_phln['jml_paket']); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($nilai_phln['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-3">
                                                <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                <label><?= formatNumber($phlnMyc['jml_paket']); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($phlnMyc['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card text-dark bg-tree-footer card-body shadow text-left">
                                                <h6>Antara Lain :</h6>
                                                <?php foreach ($phlnMycList as $key => $daftarsyc) { ?>
                                                    <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </section>
        <?php }if ($value == "PROGRES PAKET BELUM LELANG TA TAHUN"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <div class="tree ml--105 pr-4">
                    <ul>
                        <li class="w-100">
                            <a href="#" class="w-25">
                                <div class="tree-content">
                                    <div class="card card-body bg-tree-1">
                                        <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                        <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                        <label> <?= formatNumber($nilai_rpm['jml_paket'] + $nilai_sbsn['jml_paket'] + $nilai_phln['jml_paket']); ?> Paket</label>
                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                            <h5 class="mb-0">
                                                <?= toMilyar($nilai_rpm['nilai_kontrak'] + $nilai_sbsn['nilai_kontrak'] + $nilai_phln['nilai_kontrak'], true, 2); ?> M
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <ul>
                                <li class="" style="width: 50% !important">
                                    <a href="#" class="w-50">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> RPM </b></h4>
                                                <label> <?= formatNumber($nilai_rpm['jml_paket']) ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($nilai_rpm['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul>
                                        <li class="w-50">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-3">
                                                        <h4 class="mb-0"><b> SYC </b></h4>
                                                        <label> <?= formatNumber($rpmSyc['jml_paket']); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($rpmSyc['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card text-dark bg-tree-footer card-body shadow text-left">
                                                        <h6>Antara Lain :</h6>
                                                        <?php foreach ($rpmSycList as $key => $daftarsyc) { ?>
                                                            <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="w-50">
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-3">
                                                        <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                        <label> <?= formatNumber($rpmMyc['jml_paket']); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($rpmMyc['nilai_kontrak'], true, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="#" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card text-dark bg-tree-footer card-body shadow text-left">
                                                        <h6>Antara Lain :</h6>
                                                        <?php foreach ($rpmMycList as $key => $daftarsyc) { ?>
                                                            <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="" style="width: 20% !important">
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> SBSN </b></h4>
                                                <label><?= formatNumber($nilai_sbsn['jml_paket']); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($nilai_sbsn['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="" style="width: 30% !important">
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> PHLN </b></h4>
                                                <label><?= formatNumber($nilai_phln['jml_paket']); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($nilai_phln['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-3">
                                                <h4 class="mb-0"><b> MYC Baru </b></h4>
                                                <label><?= formatNumber($phlnMyc['jml_paket']); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($phlnMyc['nilai_kontrak'], true, 2); ?> M
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="#" class="w-75">
                                        <div class="tree-content">
                                            <div class="card text-dark bg-tree-footer card-body shadow text-left">
                                                <h6>Antara Lain :</h6>
                                                <?php foreach ($phlnMycList as $key => $daftarsyc) { ?>
                                                    <p><?= ++$key . ". " . $daftarsyc['nmpaket'] ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </section>
        <?php }if ($value == "PAKET BELUM LELANG TA TAHUN (RPM-SYC)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-2">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">SATKER/PAKET PEKERJAAN</th>
                            <th rowspan="2">Jenis Kontrak</th>
                            <th>Pagu DIPA</th>
                            <th>Pagu Pengadaan</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>(Rp Ribu)</th>
                            <th>(Rp Ribu)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th>A</th>
                            <th>RPM - SYC</th>
                            <th></th>
                            <th>Data</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                        <tr class="subhead">
                            <td></td>
                            <td colspan="5">SNVT PEMBANGUNAN BENDUNGAN BBWS CILIWUNG CISADANE</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "PAKET BELUM LELANG TA TAHUN (RPM-MYC)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-2">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">SATKER/PAKET PEKERJAAN</th>
                            <th rowspan="2">Jenis Kontrak</th>
                            <th>Pagu DIPA</th>
                            <th>Pagu Pengadaan</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>(Rp Ribu)</th>
                            <th>(Rp Ribu)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th>B</th>
                            <th>RPM – MYC BARU</th>
                            <th></th>
                            <th>Data</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                        <tr class="subhead">
                            <td></td>
                            <td colspan="5">SNVT PEMBANGUNAN BENDUNGAN BWS SULAWESI IV</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "PAKET BELUM LELANG TA TAHUN (PLN SYC)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-2">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">SATKER/PAKET PEKERJAAN</th>
                            <th rowspan="2">Jenis Kontrak</th>
                            <th>Pagu DIPA</th>
                            <th>Pagu Pengadaan</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>(Rp Ribu)</th>
                            <th>(Rp Ribu)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th>A</th>
                            <th>PAKET PLN SYC</th>
                            <th></th>
                            <th>Data</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                        <tr class="subhead">
                            <td></td>
                            <td colspan="5">BALAI WILAYAH SUNGAI SUMATERA II</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "PAKET BELUM LELANG TA TAHUN (PLN-MYC)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-2">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">SATKER/PAKET PEKERJAAN</th>
                            <th rowspan="2">Jenis Kontrak</th>
                            <th colspan="2">Pagu (Rp.Ribu)</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>DIPA</th>
                            <th>Pengadaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th>B</th>
                            <th>PLN MYC BARU</th>
                            <th></th>
                            <th>Data</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                        <tr class="subhead">
                            <td></td>
                            <td colspan="5">SNVT PEMBANGUNAN BENDUNGAN BWS SULAWESI IV</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "PAKET BELUM LELANG TA TAHUN (PLN-MYC MENDAHULUI DIPA)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-2">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">SATKER/PAKET PEKERJAAN</th>
                            <th rowspan="2">Jenis Kontrak</th>
                            <th colspan="2">Pagu (Rp.Ribu)</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>DIPA</th>
                            <th>Pengadaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th>C</th>
                            <th>PLN - MYC MENDAHULUI DIPA</th>
                            <th></th>
                            <th>Data</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                        <tr class="subhead">
                            <td></td>
                            <td colspan="5">SNVT PELAKSANAAN JARINGAN SUMBER AIR BRANTAS</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "PAKET DENGAN SISA BELUM TERSERAP TERTINGGI"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Satker</th>
                            <th>Paket</th>
                            <th>Pagu DIPA</th>
                            <th>Realisasi</th>
                            <th>Sisa Belum Terserap</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <td></td>
                            <td align="center" colspan="5">Paket Kegiatan Bendungan dan Danau</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "PROGRES PELAKSANAAN IKN"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <img height="90%" src="<?= base_url('images/laporan/diagram5.png'); ?>" />
            </section>
        <?php }if ($value == "REKAPITULASI PAKET KEGIATAN BIDANG SDA DI KAWASAN IKN"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <p class="fs-15 font-weight-bold">REKAPITULASI KEGIATAN TERKONTRAK (1/2)</p>
                <table class="table table-bordered table-4">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Paket Pekerjaan</th>
                            <th rowspan="2">SYC / MYC</th>
                            <th>PAGU DIPA X Rp1000</th>
                            <th colspan="2">PROGRES TERHADAP DIPA TA <?= date('Y') ?></th>
                            <th colspan="2">PROGRES TERHADAP KONTRAK</th>
                        </tr>
                        <tr>
                            <th>TA <?= date('Y') ?></th>
                            <th>Keu (%)</th>
                            <th>Fisik (%)</th>
                            <th>Keu (%)</th>
                            <th>Fisik (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th colspan="2">TERKONTRAK (15 Paket)</th>
                            <th></th>
                            <th>Data</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "REKAPITULASI PAKET KEGIATAN BIDANG SDA DI KAWASAN IKN (selesai)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <p class="fs-15 font-weight-bold">PAKET SELESAI TA <?= date("Y") ?> (4 PAKET)</p>
                <table class="table table-bordered table-4">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Paket Pekerjaan</th>
                            <th rowspan="2">SYC / MYC</th>
                            <th>PAGU DIPA X Rp1000</th>
                            <th colspan="2">PROGRES TERHADAP DIPA TA <?= date('Y') ?></th>
                            <th colspan="2">PROGRES TERHADAP KONTRAK</th>
                        </tr>
                        <tr>
                            <th>TA <?= date('Y') ?></th>
                            <th>Keu (%)</th>
                            <th>Fisik (%)</th>
                            <th>Keu (%)</th>
                            <th>Fisik (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th colspan="2">PEKERJAAN SELESAI 2024 (4 Paket)</th>
                            <th></th>
                            <th>Data</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "PROGNOSIS PENYERAPAN ANGGARAN TA TAHUN (1)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-2">
                            <div class="card-body font-weight-bold">
                                <p>PROGNOSIS</p>
                                <p>DITJEN SDA</p>
                                <p>IEMONITORING</p>
                                <p>88,24%</p>
                                <p>Rp43.404,42 M</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-3">
                            <div class="card-body font-weight-bold">
                                <p>RENCANA</p>
                                <p>PEMANFAATAN RPM</p>
                                <p>8,86%</p>
                                <p>Rp4.355,66 M + SBSN</p>
                                <p>0,49%</p>
                                <p>Rp241,75 M</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-4">
                            <div class="card-body font-weight-bold">
                                <p>PROGNOSIS</p>
                                <p>AKHIR</p>
                                <p>97,59%</p>
                                <p>Rp48.001,78 M</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-5">
                            <div class="card-body font-weight-bold">
                                <p>ESTIMASI SISA</p>
                                <p>ANGGARAN AKHIR</p>
                                <p>(TIDAK TERMANFAATKAN)</p>
                                <p>2,41%</p>
                                <p>Rp1.186,82 M</p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center">REKAPITULASI DATA PELAPORAN PROGNOSIS PER KEGIATAN</p>
                <table class="table table-bordered table-5">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Kegiatan</th>
                            <th rowspan="2">Alokasi Anggaran</th>
                            <th colspan="2">Realisasi</th>
                            <th colspan="2">Prognosis</th>
                            <th colspan="2">Potensi Sisa</th>
                        </tr>
                        <tr>
                            <th>Rp Miliar</th>
                            <th>%</th>
                            <th>Rp Miliar</th>
                            <th>%</th>
                            <th>Rp Miliar</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th></th>
                            <th></th>
                            <th>DITJEN SDA</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "RENCANA PEMANFAATAN RUPIAH MURNI TA TAHUN (1)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <img height="90%" src="<?= base_url('images/laporan/diagram6.png'); ?>" />
            </section>
        <?php }if ($value == "PROGNOSIS PENYERAPAN ANGGARAN TA TAHUN (2)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <textarea class="form-control border-0 " readonly rows="20" style="overflow: hidden;resize: none">
                    1. Berdasarkan data pada sistem I-emonitoring per 15 Agustus 2024, prognosis penyerapan Ditjen
                        SDA adalah 88,24% atau ada anggaran sebesar Rp5.78 Triliun yang berpotensi tidak terserap,
                        terdiri dari:
                        a. Rupiah Murni, sebesar Rp5,14 Triliun,
                        b. SBSN, sebesar RpO,24Tri1iun,
                        c. PLN dan RM Pendamping, sebesar RpO,41 Triliun,.
                    2. Dari total potensi anggaran tidak terserap dengan sumber pendanaan Rupiah Murni sebesar
                        Rp5,14 Triliun, kami mengusulkan pemanfaatan sebesar Rp4,35 Triliun, dengan rincian
                        sebagai berikut:
                        a. Penyelesaian MYC, sebesar Rp1,35 Triliun,
                        b. Pemanfaatan Blokir Automatic Adjustment (AA), sebesar RPI ,76 Triliun,
                        c. Tindak lanjut arahan/direktif baru, sebesar RpO,51 Triliun,
                        d. Pembayaran eskalasi dan tunggakan, sebesar RpO,25Triliun,
                        e. Status Revisi pemanfaatan untuk Kegiatan Mendesak, sebesar Rp480,71 Miliar telah selesai.
                    3. Dengan demikian terdapat total potensi anggaran RM tidak terserap sebesar Rp780,67 M
                        (2,05%), dengan total Rp1.186,82 M untuk seluruh sumber dana
                    4. Dengan proyeksi usulan pemanfaatan sisa anggaran SBSN disetujui BAPPENAS (telah
                        diusulkan sejak April 2024), maka prognosis dengan sumber pendanaan RPM menjadi
                        98,13%, SBSN menjadi 100%, dan PLN menjadi 92,44%.
                    5. Sehubungan butir 3 dan 4, diharapkan prognosis SDA dapat mencapai 97,59%. Namun jika
                        pemanfaatan sisa anggaran SBSN tidak disetujui, prognosis SDA berubah menjadi 97, 10%
                </textarea>
                <img class="img-footer" src="<?= base_url('images/laporan/5.png'); ?>" />
            </section>
        <?php }if ($value == "PROYEK BENDUNGAN PERLU PERHATIAN KHUSUS (1)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-6">
                    <thead>
                        <tr>
                            <th rowspan="2">No </th>
                            <th rowspan="2">Proyek / Paket Pembangunan</th>
                            <th rowspan="2">Fisik Kumulatif (%)</th>
                            <th colspan="2">TA <?= date('Y') ?></th>
                        </tr>
                        <tr>
                            <th>Pagu (Rp Miliar)</th>
                            <th>Belum Terserap (Rp Miliar) </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <img width="100%" src="<?= base_url('images/laporan/diagram7.png'); ?>" />
                <img class="img-footer" src="<?= base_url('images/laporan/5.png'); ?>" />
            </section>
        <?php }if ($value == "PROGNOSIS SESUAI IEMONITORING SEBELUM PEMANFAATAN ANGGARAN TA TAHUN PER UNIT KERJA"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-7">
                    <thead>
                        <tr>
                            <th rowspan="2">No </th>
                            <th rowspan="2">Balai </th>
                            <th rowspan="2">Alokasi Anggaran </th>
                            <th colspan="2">Realisasi </th>
                            <th colspan="2">Prognosis </th>
                            <th rowspan="2">Belum Terserap </th>
                        </tr>
                        <tr>
                            <th>Rp Miliar</th>
                            <th>%</th>
                            <th>Rp Miliar</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th></th>
                            <th>DITJEN SDA</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                        </tr>
                    </tbody>
                </table>
                <img class="img-footer" src="<?= base_url('images/laporan/5.png'); ?>" />
            </section>
        <?php }if ($value == "PROYEK BENDUNGAN PERLU PERHATIAN KHUSUS (2)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <table class="table table-bordered table-6">
                    <thead>
                        <tr>
                            <th rowspan="2">No </th>
                            <th rowspan="2">Proyek / Paket Pembangunan</th>
                            <th rowspan="2">Fisik Kumulatif (%)</th>
                            <th colspan="2">TA <?= date('Y') ?></th>
                        </tr>
                        <tr>
                            <th>Pagu (Rp Miliar)</th>
                            <th>Belum Terserap (Rp Miliar) </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <img width="100%" src="<?= base_url('images/laporan/diagram9.png'); ?>" />
            </section>
        <?php }if ($value == "PROGNOSIS PENYERAPAN ANGGARAN TA TAHUN (3)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-2">
                            <div class="card-body font-weight-bold">
                                <p>PROGNOSIS</p>
                                <p>DITJEN SDA</p>
                                <p>IEMONITORING</p>
                                <p>88,24%</p>
                                <p>Rp43.404,42 M</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-3">
                            <div class="card-body font-weight-bold">
                                <p>RENCANA</p>
                                <p>PEMANFAATAN</p>
                                <p>8,39%</p>
                                <p>Rp 4.127,30 M</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-4">
                            <div class="card-body font-weight-bold">
                                <p>PROGNOSIS</p>
                                <p>AKHIR</p>
                                <p>97,12%</p>
                                <p>Rp 47.773,42 M</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-0 text-center card-5">
                            <div class="card-body font-weight-bold">
                                <p>ESTIMASI SISA</p>
                                <p>ANGGARAN AKHIR</p>
                                <p>(TIDAK TERMANFAATKAN)</p>
                                <p>2,88%</p>
                                <p>Rp1.415,18 M</p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center">REKAPITULASI DATA PELAPORAN PROGNOSIS PER KEGIATAN</p>
                <table class="table table-bordered table-5">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Kegiatan</th>
                            <th rowspan="2">Alokasi Anggaran</th>
                            <th colspan="2">Realisasi</th>
                            <th colspan="2">Prognosis</th>
                            <th colspan="2">Potensi Sisa</th>
                        </tr>
                        <tr>
                            <th>Rp Miliar</th>
                            <th>%</th>
                            <th>Rp Miliar</th>
                            <th>%</th>
                            <th>Rp Miliar</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="head">
                            <th></th>
                            <th></th>
                            <th>DITJEN SDA</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                            <th>Data</th>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php }if ($value == "RENCANA PEMANFAATAN RUPIAH MURNI TA TAHUN (2)"){ ?>
            <section class="">
                <h3><?= $value ?></h3>
                <p>Status <?= date("d F Y, h:i") ?></p>
                <img height="90%" src="<?= base_url('images/laporan/diagram8.png'); ?>" />
            </section>
        <?php } ?>
    <?php endforeach ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php echo view('Modules\Admin\Views\Dashboard\js\ChartPersumberDana'); ?>
    <script>
        window.print()
    </script>
</body>
</html>