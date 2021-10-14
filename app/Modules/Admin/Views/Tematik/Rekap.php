<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Rekap
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="row mb-3">
                    <div class="col-md-6 mt-3">
                        <div class="dropdown dropright">
                            <button type="button" class="btn btn-primary btn-icon" dropdown-toggle data-toggle="dropdown"><i class="la la-filter"></i></button>
                            <div class="dropdown-menu" style="overflow-y: auto; height: 200px; z-index: 5;">
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="tematik">Tematik
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="pagu">Pagu
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="realisasi">Realisasi
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="progres_keu">Progres (Keu)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="progres_fis">Progres (Fis)
                                        </label>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="form-check-inline">
                                        <label for="" class="form-check-label">
                                            <input type="checkbox" type="checkbox" class="form-check-input" name="keterangan">Keterangan
                                        </label>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right mt-3">
                        <div class="form-group">
                            <a class="btn btn-warning btn-sm text-white pdf-report"><i class="fa fa-file-pdf"></i>PDF</a>
                            <a href="<?php echo site_url('/tematik/excel-rekap') ?>" class="btn btn-success btn-sm text-white" target="_blank">
                                <i class="fa fa-file-excel"></i>Excel
                            </a>
                        </div>
                        <i><b>*Dalam Ribuan</b></i>
                    </div>
                </div>    

                <div class="table-responsive tableFixHead">

                    <?php $colspan = 8; ?>
                    <table class="table table-bordered mb-0 table-striped" id="table">
                        <thead>
                            <tr class="text-center bg-purple">
                                <th rowspan="2">No</th>
                                <th class="tematik" rowspan="2">Tematik</th>
                                <th class="pagu" rowspan="2">Pagu (dalam Milyar)</th>
                                <th class="realisasi" rowspan="2">Realisasi Keu</th>
                                <th class="progres-main" colspan="2">Progres (%)</th>
                                <th class="keterangan" rowspan="2">Keterangan</th>
                            </tr>
                            <tr class="text-center bg-purple">
                                <th class="progres_keu progres">Keu</th>
                                <th class="progres_fis progres">Fis</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php 
                                $no = 1;
                                foreach($data as $key => $value): 
                            ?>
                                <tr>
                                    <td class="tdprogram"><?php echo $no++ ?></td>
                                    <td class="col-tematik tdprogram"><?php echo $value['title'] ?></td>
                                    <td class="col-pagu tdprogram"><?php echo toRupiah($value['totalPagu'], false) ?></td>
                                    <td class="col-realisasi tdprogram"><?php echo toRupiah($value['totalRealisasi'], false) ?></td>
                                    <td class="col-progres_keu tdprogram"><?php echo onlyTwoDecimal($value['totalProgKeu']) ?></td>
                                    <td class="col-progres_fis tdprogram"><?php echo onlyTwoDecimal($value['totalProgFis']) ?></td>
                                    <td class="col-keterangan tdprogram"></td>
                                </tr>
                                <?php foreach($value['list'] as $key2 => $value2): ?>
                                    <tr>
                                        <td></td>
                                        <td class="col-tematik"><?php echo $value2->tematik ?></td>
                                        <td class="col-pagu"><?php echo toRupiah($value2->pagu, false) ?></td>
                                        <td class="col-realisasi"><?php echo toRupiah($value2->realisasi, false) ?></td>
                                        <td class="col-progres_keu"><?php echo onlyTwoDecimal($value2->prog_keu) ?></td>
                                        <td class="col-progres_fis"><?php echo onlyTwoDecimal($value2->prog_fis) ?></td>
                                        <td class="col-keterangan"><?php echo $value2->ket ?></td>

                                        <!--<td><?php echo  "- ". str_replace("||","<br> - ",str_replace(", ", ",", $value2->ket))  ?></td>-->
                                    </tr>
                                <?php endforeach ?>
                            <?php endforeach ?>
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
    var $th = $('.tableFixHead1').find('thead th')
    $('.tableFixHead1').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    })

    $("#search").click(function() {
        window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
    });

    let report_open = true
    let checkbox = $("input:checkbox")
    $("input:checkbox").prop("checked", true)
    $("input:checkbox").click(function(){
        
        //checking checkbox for report button
        if((checkbox.length - checkbox.filter(":checked").length) == checkbox.length){
            
            report_open = false
        }else{

            report_open = true
        }

        var column = "table ." + $(this).attr("name")
        var columns = "table .col-" + $(this).attr("name")
        $(column).toggle();
        $(columns).toggle();

        //progres section
        let progres_counter = $("table .progres").length

        if ($("table .progres_keu").is(":hidden")) {

            progres_counter--;
        }
        if ($("table .progres_fis").is(":hidden")) {

            progres_counter--;
        }
        if($("table .progres_keu").is(":hidden") && $("table .progres_fis").is(":hidden")){

            $(".progres-main").hide()
        }else{

            $(".progres-main").show()
            $(".progres-main").attr("colspan", progres_counter)
        }
        //progres end section
    });

    $(".pdf-report").click(function(){
        
        let arr = [];
        
        if(!$("input[name=tematik]").prop("checked")){

            arr.push("tematik")
        }
        if(!$("input[name=pagu]").prop("checked")){

            arr.push("pagu")
        }
        if(!$("input[name=realisasi]").prop("checked")){

            arr.push("realisasi")
        }
        if(!$("input[name=progres_keu]").prop("checked")){

            arr.push("progres_keu")
        }
        if(!$("input[name=progres_fis]").prop("checked")){

            arr.push("progres_fis")
        }
        if(!$("input[name=keterangan]").prop("checked")){

            arr.push("keterangan")
        }

        //condition for report button
        if(report_open){

            $(this).attr("href", "<?= $id_report_pdf ?>?filter="+arr.join(','))
            $(this).attr("target", "_blank")
        }else{

            $(this).removeAttr("href")
            $(this).removeAttr("target")
        }
    })
</script>
</script>
<?= $this->endSection() ?>