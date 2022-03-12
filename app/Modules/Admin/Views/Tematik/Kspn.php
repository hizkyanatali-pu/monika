<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                KSPN
            </h5>
            <select class="form-control" id="selectKspn" style="width: 140px;">
                <option value="kspn01" <?php if ($uri->getSegment(3) == "kspn01") : ?> selected <?php endif; ?>>
                    Danau Toba
                </option>
                <option value="kspn02" <?php if ($uri->getSegment(3) == "kspn02") : ?> selected <?php endif; ?>>
                    Borobudur
                </option>
                <option value="kspn03" <?php if ($uri->getSegment(3) == "kspn03") : ?> selected <?php endif; ?>>
                    Mandalika
                </option>
                <option value="kspn04" <?php if ($uri->getSegment(3) == "kspn04") : ?> selected <?php endif; ?>>
                    Labuan Bojo
                </option>
                <option value="kspn05" <?php if ($uri->getSegment(3) == "kspn05") : ?> selected <?php endif; ?>>
                    Manado
                </option>
                <option value="kspn06" <?php if ($uri->getSegment(3) == "kspn06") : ?> selected <?php endif; ?>>
                    Tanjung Kelayang
                </option>
                <option value="kspn08" <?php if ($uri->getSegment(3) == "kspn08") : ?> selected <?php endif; ?>>
                    Wakatobi
                </option>
                <option value="kspn09" <?php if ($uri->getSegment(3) == "kspn09") : ?> selected <?php endif; ?>>
                    Morotai
                </option>
            </select>
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
                    <div class="col-md-6">
                        <label class="mb-0"><?php echo $filterTitle ?></label>
                        <div class="input-group" style="width:200px !important">
                            <select class="form-control" id="listmonth" name="month" disabled>
                                <option><?= session('userData.tahun') ?></option>
                            </select>
                            <div class="dropdown dropright">
                                <button type="button" class="btn btn-primary btn-icon ml-3" dropdown-toggle data-toggle="dropdown"><i class="la la-filter"></i></button>
                                <div class="dropdown-menu" style="overflow-y: auto; height: 200px; z-index: 5;">
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="satker">Satker / Paket
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="target_vol">Target (Vol)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="target_satuan">Target (Satuan)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="lokasi">Lokasi
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="pagu_rpm">Pagu (RPM)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="pagu_phln">Pagu (PHLN)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="pagu_total">Pagu (Total)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="realisasi_rpm">Realisasi (RPM)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="realisasi_phln">Realisasi (PHLN)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="realisasi_total">Realisasi (Total)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="progres_keu">Progres (Keu)
                                            </label>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="form-check-inline">
                                            <label for="" class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="progres_fis">Progres (Fis)
                                            </label>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right mt-3">
                        <div class="form-group">
                            <a class="btn btn-warning btn-sm text-white pdf-report"><i class="fa fa-file-pdf"></i>PDF</a>
                            <a href="<?php echo site_url('tematik/excel-kspn/' . $uri->getSegment(3)) ?>" class="btn btn-success btn-sm text-white" target="_blank">
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
                                <th class="satker" rowspan="2">Satker / Paket</th>
                                <th class="target-main" colspan="2">Target</th>
                                <th class="lokasi" rowspan="2">Lokasi</th>
                                <th class="pagu-main" colspan="3">Pagu</th>
                                <th class="realisasi-main" colspan="3">Realisasi</th>
                                <th class="progres-main" colspan="2">Progres (%)</th>
                            </tr>
                            <tr class="text-center bg-purple">
                                <th class="target_vol target">Vol</th>
                                <th class="target_satuan target">Satuan</th>
                                <th class="pagu_rpm pagu">RPM</th>
                                <th class="pagu_phln pagu">PHLN</th>
                                <th class="pagu_total pagu">Total</th>
                                <th class="realisasi_rpm realisasi">RPM</th>
                                <th class="realisasi_phln realisasi">PHLN</th>
                                <th class="realisasi_total realisasi">Total</th>
                                <th class="progres_keu progres">Keu</th>
                                <th class="progres_fis progres">Fis</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php
                            $no = 1;
                            if (empty($data)) {
                                echo "<tr><td colspan='13' class='text-center'>Tidak Ada Data</td></tr>
                                
                                ";
                            }
                            foreach ($data as $key => $value) :
                            ?>
                                <tr>
                                    <td colspan="13" class="tdprogram"><?php echo $value->satker ?></td>
                                </tr>
                                <?php foreach ($value->paketList as $key => $value) : ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td class="col-satker"><?php echo $value->nmpaket ?></td>
                                        <td class="col-target_vol"><?php echo $value->vol ?></td>
                                        <td class="col-target_satuan"><?php echo $value->satuan ?></td>
                                        <td class="col-lokasi"><?php echo $value->lokasi ?></td>
                                        <td class="col-pagu_rpm"><?php echo toRupiah($value->pagu_rpm, false) ?></td>
                                        <td class="col-pagu_phln"><?php echo toRupiah($value->pagu_phln, false) ?></td>
                                        <td class="col-pagu_total"><?php echo toRupiah($value->pagu_total, false) ?></td>
                                        <td class="col-realisasi_rpm"><?php echo toRupiah($value->realisasi_rpm, false) ?></td>
                                        <td class="col-realisasi_phln"><?php echo toRupiah($value->realisasi_phln, false) ?></td>
                                        <td class="col-realisasi_total"><?php echo toRupiah($value->realisasi_total, false) ?></td>
                                        <td class="col-progres_keu"><?php echo onlyTwoDecimal($value->persen_keu) ?></td>
                                        <td class="col-progres_fis"><?php echo onlyTwoDecimal($value->persen_fi) ?></td>
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

<?= $this->section('upper_footer_js') ?>
<script>
    $('#selectKspn').on('change', function() {

        window.location = "<?php echo site_url('tematik/kspn/') ?>" + $(this).val();
    });
</script>
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
    $("input:checkbox").click(function() {

        //checking checked checkbox for report button
        if ((checkbox.length - checkbox.filter(":checked").length) == checkbox.length) {

            report_open = false
        } else {

            report_open = true
        }

        var column = "table ." + $(this).attr("name")
        var columns = "table .col-" + $(this).attr("name")
        $(column).toggle();
        $(columns).toggle();

        //target section
        let target_counter = $("table .target").length

        if ($("table .target_vol").is(":hidden")) {

            target_counter--;
        }
        if ($("table .target_satuan").is(":hidden")) {

            target_counter--;
        }
        if ($("table .target_vol").is(":hidden") && $("table .target_satuan").is(":hidden")) {

            $(".target-main").hide()
        } else {

            $(".target-main").show()
            $(".target-main").attr("colspan", target_counter)
        }
        //target end section

        //pagu section
        let pagu_counter = $("table .pagu").length

        if ($("table .pagu_rpm").is(":hidden")) {

            pagu_counter--;
        }
        if ($("table .pagu_phln").is(":hidden")) {

            pagu_counter--;
        }
        if ($("table .pagu_total").is(":hidden")) {

            pagu_counter--;
        }
        if ($("table .pagu_rpm").is(":hidden") && $("table .pagu_phln").is(":hidden") && $("table .pagu_total").is(":hidden")) {

            $(".pagu-main").hide()
        } else {

            $(".pagu-main").show()
            $(".pagu-main").attr("colspan", pagu_counter)
        }
        //pagu end section

        //realisasi section
        let realisasi_counter = $("table .realisasi").length

        if ($("table .realisasi_rpm").is(":hidden")) {

            realisasi_counter--;
        }
        if ($("table .realisasi_phln").is(":hidden")) {

            realisasi_counter--;
        }
        if ($("table .realisasi_total").is(":hidden")) {

            realisasi_counter--;
        }
        if ($("table .realisasi_rpm").is(":hidden") && $("table .realisasi_phln").is(":hidden") && $("table .realisasi_total").is(":hidden")) {

            $(".realisasi-main").hide()
        } else {

            $(".realisasi-main").show()
            $(".realisasi-main").attr("colspan", realisasi_counter)
        }
        //realisasi end section

        //progres section
        let progres_counter = $("table .progres").length

        if ($("table .progres_rpm").is(":hidden")) {

            progres_counter--;
        }
        if ($("table .progres_phln").is(":hidden")) {

            progres_counter--;
        }
        if ($("table .progres_keu").is(":hidden") && $("table .progres_fis").is(":hidden")) {

            $(".progres-main").hide()
        } else {

            $(".progres-main").show()
            $(".progres-main").attr("colspan", progres_counter)
        }
        //progres end section
    });

    $(".pdf-report").click(function() {

        let arr = [];

        if (!$("input[name=satker]").prop("checked")) {

            arr.push("satker")
        }
        if (!$("input[name=target_vol]").prop("checked")) {

            arr.push("target_vol")
        }
        if (!$("input[name=target_satuan]").prop("checked")) {

            arr.push("target_satuan")
        }
        if (!$("input[name=lokasi]").prop("checked")) {

            arr.push("lokasi")
        }
        if (!$("input[name=pagu_rpm]").prop("checked")) {

            arr.push("pagu_rpm")
        }
        if (!$("input[name=pagu_phln]").prop("checked")) {

            arr.push("pagu_phln")
        }
        if (!$("input[name=pagu_total]").prop("checked")) {

            arr.push("pagu_total")
        }
        if (!$("input[name=realisasi_rpm]").prop("checked")) {

            arr.push("realisasi_rpm")
        }
        if (!$("input[name=realisasi_phln]").prop("checked")) {

            arr.push("realisasi_phln")
        }
        if (!$("input[name=realisasi_total]").prop("checked")) {

            arr.push("realisasi_total")
        }
        if (!$("input[name=progres_keu]").prop("checked")) {

            arr.push("progres_keu")
        }
        if (!$("input[name=progres_fis]").prop("checked")) {

            arr.push("progres_fis")
        }

        //condition for report button
        if (report_open) {

            $(this).attr("href", "<?= site_url("tematik/") . $id_report_pdf ?>/<?= $uri->getSegment(3) ?>?filter=" + arr.join(','))
            $(this).attr("target", "_blank")
        } else {

            $(this).removeAttr("href")
            $(this).removeAttr("target")
        }
    })
</script>
<?= $this->endSection() ?>