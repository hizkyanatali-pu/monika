<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                <?= $title; ?>
            </h5>
            <!-- <small>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Library</li>
                    </ol>
                    </nav>
                <?php $l = '';
                foreach ($posisi as $key => $data) : ?>
                    <?php $l .= ($l ? ' <i class="fa fa-angle-double-right"></i> ' : '') . $data; ?>
                <?php endforeach;
                echo $l; ?>
            </small> -->
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>
         <marquee direction="scroll" style="font-size: medium;color: firebrick;"><b><i>* Update Data Terakhir Pada Tanggal <?= (getLastUpdateData() ? getLastUpdateData():0 );  ?> </i></b></marquee>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">

                <h6 class="pb-2 mb-0"><a id="a-go-pagu" data-toggle="collapse" href="#go-pagu" aria-expanded="true" aria-controls="go-pagu">Progres Keuangan dan Fisik SDA <i class="fa fa-angle-double-down"></i></a></h6>
                <div id="go-pagu" class="row collapse in show" role="tabpanel" aria-labelledby="a-go-pagu">
                    <?php /*
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu RPM (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_rpm'] /1000,0,',','.');?></strong>
                                </p>
                            </div>

                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu SBSN (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_sbsn'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu PLN (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_phln'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Pagu TOTAL (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_pagu_total'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Realisasi (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_real_total'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Keungan (%)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_progres_keuangan'] ,2,',','.');?></strong>
                                </p>
                            </div>

                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Fisik (%)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_progres_fisik'] ,2,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Deviasi (%)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_persen_deviasi'] ,2,',','.');?></strong>
                                </p>
                            </div>
                            <div class="media text-muted pt-3">
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Deviasi (Rp)
                                <strong class="d-block text-gray-dark"><?=number_format( $qdata[0]['pagusda_nilai_deviasi'] /1000,0,',','.');?></strong>
                                </p>
                            </div>
                        </div>
                    */ ?>

                    <div class="col-md-6">
                        <div class="card card-body text-white bg-primary">
                            <div class="clearfix">
                                <div class="float-left">
                                    <h3><?= number_format($qdata[0]['pagusda_progres_keuangan'], 2, ',', '.'); ?>% </h3>
                                </div>
                                <div class="float-right text-right">
                                    <h6> Progres Keuangan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body text-white bg-success">
                            <div class="clearfix">
                                <div class="float-left">
                                    <h3><?= number_format($qdata[0]['pagusda_progres_fisik'], 2, ',', '.'); ?>% </h3>
                                </div>
                                <div class="float-right text-right">
                                    <h6>Progres Fisik</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <h3>
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Keuangan (%)
                                <strong class="d-block text-gray-dark"><?= number_format($qdata[0]['pagusda_progres_keuangan'], 2, ',', '.'); ?></strong>
                                </p>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="media text-muted pt-3">
                                <h3>
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                Progres Fisik (%)
                                <strong class="d-block text-gray-dark"><?= number_format($qdata[0]['pagusda_progres_fisik'], 2, ',', '.'); ?></strong>
                                </p>
                                </h3>
                            </div>
                        </div>
                    -->

                </div>

                <!-- <div class="bg-white rounded box-shadow"> -->
                <!-- <a id="a-go-" data-toggle="collapse" href="#go-" aria-expanded="true" aria-controls="go-">
                        menu</a>
                        <div id="go-" class="collapse in" role="tabpanel" aria-labelledby="a-go-" >
                        box-Konten
                        </div> -->

                <!-- <small class="d-block text-right mt-3">
                        <a href="#">All updates</a>
                        </small> -->
                <!-- </div> -->

                <hr>

                <div class="clearfix mb-3">
                    <div class="float-left">
                        <h6>Progres Keuangan dan Fisik <?= $title; ?></h6>
                        <div class="kt-section__content dropdown dropright">
                            <button type="button" class="btn btn-warning btn-icon" data-toggle="kt-popover" data-trigger="focus" title="" data-html="true" data-content="a. Deviasi <b>(-)</b> : keuangan lebih besar dari pada fisik. <br> <br>b. Data yang berwarna <b>Merah</b> menandakan dibawah nilai rata-rata." data-original-title="Petunjuk !"><i class="la la-lightbulb-o"></i></button>
                            <button type="button" class="btn btn-primary btn-icon" dropdown-toggle data-toggle="dropdown"><i class="la la-filter"></i></button>
                            <div class="dropdown-menu" style="overflow-y: auto; height: 200px; z-index: 5;">
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="unit_kerja" disabled><?= $title ?>
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="paket" disabled>Jml Paket
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_rpm">Pagu (RPM)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_sbsn">Pagu (SBSN)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_phln">Pagu (PHLN)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_total">Pagu (Total)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="pagu_realisasi">Pagu (Realisasi)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="keu">Progres % (Keu)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="fisik">Progres % (Fisik)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="percentage">Deviasi (%)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="rp">Deviasi (Rp)
                                        </label>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <a class="btn btn-warning btn-sm text-white pdf-report"><i class="fa fa-file-pdf"></i>PDF</a>
                        <a target="_blank" href="<?php echo site_url('pulldata/rekap/' . $rekap) . "?idk=" . $idk . "&label=" . $label; ?>" class="btn btn-success btn-sm text-white"><i class="fa fa-file-excel"></i>Rekap</a>
                        <?PHP if (!in_array($rekap, array("satkerpagu100m"))) : ?>
                            <a target="_blank" href="<?php echo site_url('pulldata/rekap/paket') . "?idk=" . $idk . "&label=" . $label . "&label2=&idks=" . (!empty($idk) ? $idk : 'all') . "&rekap=" . $rekap; ?>" class="btn btn-info btn-sm text-white"><i class="fa fa-file-excel"></i>Rekap <?= ($rekap == "unitkerja" ? "SDA" : $rekap); ?></a>
                            <a target="_blank" href="<?php echo site_url('pulldata/rekap/paket') . "?idk=" . $idk . "&label=" . $label . "&label2=&idks=" . (!empty($idk) ? $idk : 'all') . "&rekap=" . $rekap; ?>&format=db" class="btn btn-info btn-sm text-white"><i class="fa fa-file-excel"></i>Rekap <?= ($rekap == "unitkerja" ? "SDA" : $rekap); ?> - DB</a>
                        <?PHP endif; ?>
                        <b>*Dalam Ribuan</b>
                    </div>
                </div>

                <div class="table-responsive tableFixHead">
                    <table class="table table-bordered w-100 mb-0">
                        <thead class="bg-white">
                            <tr class="text-center">
                                <!-- <th colspan="2">&nbsp;</th> -->
                                <th class="unit_kerja">&nbsp;</th>
                                <th class="paket">&nbsp;</th>
                                <?= ($title == 'Semua Satker' ?    '<th class="satker_">&nbsp;</th>' :'')?>
                                <th class="pagu-main" colspan="5">Pagu (Rp)</th>
                                <th class="progres" colspan="2">Progres (%)</th>
                                <th class="deviasi" colspan="2">Deviasi</th>
                            </tr>
                            <tr class="text-center">
                                <th class="unit_kerja"><?= $title; ?></th>
                                <?= ($title == 'Semua Satker' ?    '<th class="satker_">Satker</th>' :'')?>
                                <th class="tdNilai paket">Jml&nbsp;Paket
                                    <!-- <br /><small title="Pagu SDA">Total SDA <i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-right"></i></small> -->
                                </th>

                                <th class="tdNilai pagu_rpm pagu">RPM
                                    <!-- <br /><small title="Pagu SDA"><?= number_format($qdata[0]['pagusda_pagu_rpm'] / 1000, 0, ',', '.'); ?></small> -->
                                </th>
                                <th class="tdNilai pagu_sbsn pagu">SBSN
                                    <!-- <br /><small title="Pagu SDA"><?= number_format($qdata[0]['pagusda_pagu_sbsn'] / 1000, 0, ',', '.'); ?></small> -->
                                </th>
                                <th class="tdNilai pagu_phln pagu">PHLN
                                    <!-- <br /><small title="Pagu SDA"><?= number_format($qdata[0]['pagusda_pagu_phln'] / 1000, 0, ',', '.'); ?></small> -->
                                </th>
                                <th class="tdNilai pagu_total pagu">TOTAL
                                    <!-- <br /><small title="Pagu SDA"><?= number_format($qdata[0]['pagusda_pagu_total'] / 1000, 0, ',', '.'); ?></small> -->
                                </th>

                                <th class="tdNilai pagu_realisasi pagu">Realisasi
                                    <!-- <br /><small title="Pagu SDA"><?= number_format($qdata[0]['pagusda_real_total'] / 1000, 0, ',', '.'); ?></small> -->
                                </th>

                                <th class="tdPersen keu">keu
                                    <!-- <br /><small title="Pagu SDA"><b><?= number_format($qdata[0]['pagusda_progres_keuangan'], 2, ',', '.'); ?></b></small> -->
                                </th>
                                <th class="tdPersen fisik">fisik
                                    <!-- <br /><small title="Pagu SDA"><b><?= number_format($qdata[0]['pagusda_progres_fisik'], 2, ',', '.'); ?></b></small> -->
                                </th>

                                <th class="tdPersen percentage">%
                                    <!-- <br /><small title="Pagu SDA"><?= number_format($qdata[0]['pagusda_persen_deviasi'], 2, ',', '.'); ?></small> -->
                                </th>
                                <th class="tdNilai rp">Rp
                                    <!-- <br /><small title="Pagu SDA"><?= number_format($qdata[0]['pagusda_nilai_deviasi'], 0, ',', '.'); ?></small> -->
                                </th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php if ($qdata) : ?>
                                <?php
                                $total_pagu_rpm = 0;
                                $total_pagu_sbsn = 0;
                                $total_pagu_phln = 0;
                                $total_pagu_total = 0;
                                $total_real_total = 0;
                                ?>
                                <?php
                                foreach ($qdata as $key => $data) : ?>

                                    <!-- balai -->
                                    <tr class="stw<?= $data['stw']; ?>">
                                        <td class="tdKodeLabel col-unit_kerja">
                                            <a class="card-link text-dark" href="<?php echo site_url('pulldata/' . $nextlink . '/' . ($idk ? $idk . '/' : '') . $data['id']); ?>/<?php echo $label; ?>/<?php echo $data['label']; ?>"><?php echo $data['label']; ?></a>
                                        </td>
                                        <?= ($title == 'Semua Satker' ? '<td class="tdNilai text-center col-paket">'.$data['st'].'</td>' :'')?>
                                        <td class="tdNilai text-center col-paket"><?php echo $data['jml_paket']; ?></td>

                                        <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($data['jml_pagu_rpm'] / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($data['jml_pagu_sbsn'] / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($data['jml_pagu_phln'] / 1000, 0, ',', '.'); ?></td>
                                        <td class="tdNilai text-right col-pagu_total"><?php echo number_format($data['jml_pagu_total'] / 1000, 0, ',', '.'); ?></td>

                                        <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($data['jml_real_total'] / 1000, 0, ',', '.'); ?></td>

                                        <td class="tdPersen text-right col-keu"><?php echo number_format($data['jml_progres_keuangan'], 2, ',', '.'); ?></td>
                                        <td class="tdPersen text-right col-fisik"><?php echo number_format($data['jml_progres_fisik'], 2, ',', '.'); ?></td>

                                        <td class="tdPersen text-right col-percentage"><?php echo ($data['jml_progres_fisik'] >= $data['jml_progres_keuangan'] ? number_format($data['jml_persen_deviasi'], 2, ',', '.') : '-'); ?></td>
                                        <td class="tdPersen text-right col-rp"><?php echo ($data['jml_progres_fisik'] >= $data['jml_progres_keuangan'] ? number_format($data['jml_nilai_deviasi'] / 1000, 0, ',', '.') : '-'); ?></td>
                                    </tr>
                                    <?php
                                    $total_pagu_rpm += $data['jml_pagu_rpm'];
                                    $total_pagu_sbsn += $data['jml_pagu_sbsn'];
                                    $total_pagu_phln += $data['jml_pagu_phln'];
                                    $total_pagu_total += $data['jml_pagu_total'];
                                    $total_real_total += $data['jml_real_total'];
                                    ?>
                                <?php endforeach; ?>
                                <tr style="background-color:#ccb3ff; border:2px solid #ccc;">
                                    <td class="text-center">TOTAL</td>
                                    <td></td>
                                    <?= ($title == 'Semua Satker' ?    '<th class="satker_">&nbsp;</th>' :'')?>
                                    <td class="tdNilai text-right col-pagu_rpm"><?php echo number_format($total_pagu_rpm / 1000, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_sbsn"><?php echo number_format($total_pagu_sbsn / 1000, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_phln"><?php echo number_format($total_pagu_phln / 1000, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_total"><?php echo number_format($total_pagu_total / 1000, 0, ',', '.'); ?></td>
                                    <td class="tdNilai text-right col-pagu_realisasi"><?php echo number_format($total_real_total / 1000, 0, ',', '.'); ?></td>

                                    <td colspan="4" class="tdPersen text-right last-col">&nbsp;</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <!--end::Section-->
        </div>

        <!--end::Form-->
    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<script>
    console.log('additional footer js')
    
    $("input:checkbox").prop("checked", true)
    
    let report_open = true
    let checkbox = $("input:checkbox")
    $("input:checkbox").click(function(){
        
        //checking checked checkbox for report button
        if((checkbox.length - checkbox.filter(":checked").length) == checkbox.length){
            
            report_open = false
        }else{

            report_open = true
        }
        
        var column = "table ." + $(this).attr("name")
        var columns = "table .col-" + $(this).attr("name")
        $(column).toggle();
        $(columns).toggle();

        //managing filter column start
        //pagu section
        let pagu_counter = $("table .pagu").length

        if ($("table .pagu_rpm").is(":hidden")) {

            pagu_counter--;
        }
        if ($("table .pagu_sbsn").is(":hidden")) {

            pagu_counter--;
        }
        if ($("table .pagu_phln").is(":hidden")) {

            pagu_counter--;
        }
        if ($("table .pagu_total").is(":hidden")) {

            pagu_counter--;
        }
        if ($("table .pagu_realisasi").is(":hidden")) {

            pagu_counter--;
        }
        if($("table .pagu_rpm").is(":hidden") && $("table .pagu_sbsn").is(":hidden") && $("table .pagu_phln").is(":hidden") && $("table .pagu_total").is(":hidden") && $("table .pagu_realisasi").is(":hidden")){

            $(".pagu-main").hide()
        }else{

            $(".pagu-main").show()
            $(".pagu-main").attr("colspan", pagu_counter)
        }
        //pagu end section

        //progres section
        if ($("table .keu").is(":hidden") || $("table .fisik").is(":hidden")) {
            
            $("table .progres").attr("colspan", 1);
        }else{

            $("table .progres").attr("colspan", 2);
        }
        if ($("table .keu").is(":hidden") && $("table .fisik").is(":hidden")) {
            
            $("table .progres").hide();
        }else{

            $("table .progres").show();
        }
        //progres end section

        //deviasi section
        if ($("table .percentage").is(":hidden") || $("table .rp").is(":hidden")) {
            
            $("table .deviasi").attr("colspan", 1);
        }else{

            $("table .deviasi").attr("colspan", 2);
        }
        if ($("table .percentage").is(":hidden") && $("table .rp").is(":hidden")) {
            
            $("table .deviasi").hide();
        }else{

            $("table .deviasi").show();
        }
        //deviasi end section
        if($("table .keu").is(":hidden") && $("table .fisik").is(":hidden") && $("table .percentage").is(":hidden") && $("table .rp").is(":hidden")){

            $("table .last-col").hide()
        }else{

            $("table .last-col").show()
        }
        //managing filter column end
    });

    $(".pdf-report").click(function(){
        
        let arr = [];
        
        if(!$("input[name=pagu_rpm]").prop("checked")){

            arr.push("pagu_rpm")
        }
        if(!$("input[name=pagu_sbsn]").prop("checked")){

            arr.push("pagu_sbsn")
        }
        if(!$("input[name=pagu_phln]").prop("checked")){

            arr.push("pagu_phln")
        }
        if(!$("input[name=pagu_total]").prop("checked")){

            arr.push("pagu_total")
        }
        if(!$("input[name=pagu_realisasi]").prop("checked")){

            arr.push("pagu_realisasi")
        }
        if(!$("input[name=keu]").prop("checked")){

            arr.push("keu")
        }
        if(!$("input[name=fisik]").prop("checked")){

            arr.push("fisik")
        }
        if(!$("input[name=percentage]").prop("checked")){

            arr.push("percentage")
        }
        if(!$("input[name=rp]").prop("checked")){

            arr.push("rp")
        }
        

        //condition for report button
        if(report_open){

            $(this).attr("href", "<?= $id_report ?>?filter="+arr.join(','))
            $(this).attr("target", "_blank")
        }else{

            $(this).removeAttr("href")
            $(this).removeAttr("target")
        }
    })
</script>
<?= $this->endSection() ?>