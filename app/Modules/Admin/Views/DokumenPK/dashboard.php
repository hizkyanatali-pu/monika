<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style type="text/css">
#flotcontainer {
    width: 35vw;
    height: 35vw;
    text-align: left;
}

.pieLabel div {
    font-weight: bold !important;
    font-size: 15px !important;
}
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

            $componen .= '<tr>
                            <td class="ket-satker" width="100%" colspan="10"><?= $grup ?></td>
                        </tr>'
?>
<?php } ?>

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
<div class="row" style="margin: 0px 80px;">
    <div class="col-md-6">
        <div id="flotcontainer"></div>
    </div>
    <div class="col-md-6">
        <div class="kt-portlet kt-portlet--tab">
            <div class="kt-portlet__body">
                <div class="kt-section mb-0">
                    <div class="kt-section__content">
                        <div class="row">
                            <div class="col-md-12">
                                <?php //echo $piechart['jumlahall'] ?>
                                <table class="table table-striped">
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
                                                Belum Menginputkan
                                            </td>
                                            <td><?php echo $piechart['belumMenginputkan']['persentase'] ?>%</td>
                                            <td><?php echo $piechart['belumMenginputkan']['jumlah'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="d-flex">
                                                <div style="width: 15px; height: 15px; background: #1c81b0; margin-top: 2px; margin-right: 10px"></div>
                                                Menunggu Konfirmasi
                                            </td>
                                            <td><?php echo $piechart['menungguKonfirmasi']['persentase'] ?>%</td>
                                            <td><?php echo $piechart['menungguKonfirmasi']['jumlah'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="d-flex">
                                                <div style="width: 15px; height: 15px; background: #1cb02d; margin-top: 2px; margin-right: 10px"></div>
                                                Terverifikasi
                                            </td>
                                            <td><?php echo $piechart['terverifikasi']['persentase'] ?>%</td>
                                            <td><?php echo $piechart['terverifikasi']['jumlah'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="d-flex">
                                                <div style="width: 15px; height: 15px; background: #a8163d; margin-top: 2px; margin-right: 10px"></div>
                                                Ditolak
                                            </td>
                                            <td><?php echo $piechart['ditolak']['persentase'] ?>%</td>
                                            <td><?php echo $piechart['ditolak']['jumlah'] ?></td>
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
</div>


<!-- <div class="container row">
    <div class="card">
        <table class="table table-striped">
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
        </table>
    </div>
</div> -->
<!-- end:: Content -->
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<?php echo script_tag('plugins/flot/jquery.flot.js'); ?>
<?php echo script_tag('plugins/flot/jquery.flot.pie.js'); ?>


<!-- <script src="http://static.pureexample.com/js/flot/excanvas.min.js"></script>
<script src="http://static.pureexample.com/js/flot/jquery.flot.min.js"></script>
<script src="http://static.pureexample.com/js/flot/jquery.flot.pie.min.js"></script> -->

<script>
    var data = [
        {label: "Belum Menginputkan", data:<?php echo $piechart['belumMenginputkan']['persentase'] ?>, color: '#807d7e'},
        {label: "Menunggu Konfirmasi", data: <?php echo $piechart['menungguKonfirmasi']['persentase'] ?>, color: '#1c81b0'},
        {label: "Terverifikasi", data: <?php echo $piechart['terverifikasi']['persentase'] ?>, color: '#1cb02d'},
        {label: "Ditolak", data: <?php echo $piechart['ditolak']['persentase'] ?>, color: '#a8163d'}
    ];
 
    var options = {
            series: {
                pie: {show: true}
                    },
            legend: {
                show: false
            }
         };
 
    $.plot($("#flotcontainer"), data, options);  
</script>
<?= $this->endSection() ?>