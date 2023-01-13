<?=
$this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<!-- <style>
    .tableFixHead1 {
        overflow-y: auto;
        height: 600px;
    }

    .tableFixHead1 thead th {
        position: sticky;
        top: 0;
        border: 1px solid #333;
    }


    .table td,
    th {
        padding: 2px;
        font-size: 12px;
        border: 1px solid #333;
    }

    table a {
        background-color: #68218b;
    }

    table tr:hover {
        color: #000000;
        text-decoration: underline;
        font-weight: bold;
    }

    .tdKodeLabel {
        width: 500px;
    }



    .thNo {
        width: 10px;
    }

    .thKode {
        width: 200px;
    }

    .thLabel {
        width: 300px;
    }

    .tdTV {
        width: 90px;
    }

    .tdLokasi {
        width: 120px;
    }

    .tdJP {
        width: 40px;
    }

    .tdMP {
        width: 40px;
    }

    .thNilai {
        width: 100px;
    }

    .thPersen {
        width: 50px;
    }

</style> -->

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                <?= $title; ?>
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
                    <div class="col-md-4">
                        <label class="mb-0">Pilih Bulan</label>
                        <div class="input-group">
                            <select class="form-control" id="listmonth" name="month">
                                <?php
                                $namaBulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                                $noBulan = 1;
                                $uri = current_url(true);
                                $bulanberjalan = decrypt_url($uri->getSegment(2)); // 3
                                $bulansekarang = date('n');
                                for ($index = 0; $index < 12; $index++) {
                                ?>
                                    <option value="<?= encrypt_url($noBulan) ?>" <?= ($bulanberjalan ==  $noBulan ? 'selected="selected"' : '') ?>> <?= $namaBulan[$index] ?> </option>;
                                <?php
                                    $noBulan++;
                                }
                                ?>

                            </select>
                            <!-- <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="search">
                                    <div class="fa fa-search"></div>
                                </button>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-md-8 text-right mt-3">
                        <div class="form-group">
                            <a href="<?= site_url('Kinerja-Output-Bulanan/') . $uri->getSegment(2) . ($uri->getSegment(3) ? "/" . $uri->getSegment(3) : '') . "?exp=xlxs" ?>" class="btn btn-success btn-sm text-white" target="_blank"><i class="fa fa-file-excel"></i>Excel</a>
                            <b>*Dalam Ribuan</b>
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-4">
                        <label class="mb-0">Keyword</label>
=======
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="mb-0">Kode Program</label>
                        <div class="input-group">
                            <select class="form-control select-opt" id="kd_program">
                                <option value="FC" selected>FC</option>
                                <option value="WA">WA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 kegiatan-component">
                        <label class="mb-0">Kode Kegiatan</label>
                        <div class="input-group">
                            <select class="form-control select-opt" id="kd_kegiatan" multiple>
                                <option></option>
                                <?php
                                foreach ($dataKegiatan as $data) { ?>
                                    <option value="<?= $data->kode ?>"> <?= $data->kode ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 output-component">
                        <label class="mb-0">Kode RO</label>
                        <div class="input-group">
                            <select class="form-control select-opt" id="kd_ro" multiple>
                                <option value="dibangun">Dibangun</option>
                                <option value="direhabilitasi">Direhabilitasi</option>
                                <option value="ditingkatkan">Ditingkatkan</option>
                                <option value="dikembangkan">Dikembangkan</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-warning btn-reset text-right">Reset Tabel</button>
                    <!-- <div class="col-md-3">
                        <label class="mb-0">Kode KRO</label>
                        <div class="input-group">
                            <select class="form-control select-opt" id="kd_output" multiple>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="mb-0">Kode SOutput</label>
>>>>>>> 49bd92a322b513c092a991bea33097f4ffd1790d
                        <div class="input-group">
                            <select class="form-control" id="keyword" name="keyword">
                                <?php $key = $uri->getSegment(3); ?>
                                <option value="" <?= ($key ==  '' ? 'selected="selected"' : '') ?>> Semua </option>
                                <option value="dibangun" <?= ($key ==  'dibangun' ? 'selected="selected"' : '') ?>> dibangun </option>
                                <option value="direhabilitasi" <?= ($key ==  'direhabilitasi' ? 'selected="selected"' : '') ?>> direhabilitasi </option>
                                <option value="ditingkatkan" <?= ($key ==  'ditingkatkan' ? 'selected="selected"' : '') ?>> ditingkatkan </option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="searchkeyword">
                                    <div class="fa fa-search"></div>
                                </button>
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="table-responsive tableFixHead">

                    <?php $colspan = 8; ?>
                    <table class="table table-bordered mb-0 table-striped" id="table">
                        <thead>
                            <tr class=" text-center bg-purple">
                                <th class="thNo" rowspan="2">No</th>
                                <th class="thKode" rowspan="2">Kode</th>
                                <th class="thLabel" rowspan="2">Program/Kegiatan/KRO/RO</th>
                                <th class="thNilai" rowspan="2">Target</th>
                                <th class="thNilai" rowspan="2">Satuan</th>
                                <th class="thNilai" rowspan="2">Pagu (Rp Ribu)</th>
                                <th class="thNilai" rowspan="2">Realisasi (Rp Ribu)</th>
                                <th class="thPersen" colspan="2">Keuangan (%)</th>
                                <th class="thPersen" colspan="3">Fisik (%)</th>
                            </tr>
                            <tr class=" text-center bg-purple">
                                <th class="thPersen">RN</th>
                                <th class="thPersen">RL</th>
                                <th class="thPersen">RN</th>
                                <th class="thPersen">RL</th>
                                <th class="thPersen">Kinerja</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
                            <?php if ($qdata) : ?>
                                <?php $idp = [];
                                $idg = [];
                                $ido = [];
                                $idso = [];
                                $idkk = [];
                                $noprog = 1;
                                $nogiat = 1;
                                $nooutput = 1;
                                $nosoutput = 1;

                                $total_pagu_soutput = array();

                                ?>
                                <?php foreach ($qdata as $key => $d) :



                                ?>
                                    <?php $idk = $d['kdprogram']; ?>
                                    <?php if (!in_array($idk, $idp)) :

                                        $sum_prog = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram']);
                                    ?>
                                        <tr>
                                            <td class="tdprogram"><?= $noprog++ ?></td>
                                            <td class="tdprogram"><?php echo $idk; ?></td>
                                            <td class="tdprogram"><?php echo $d['nmprogram']; ?></td>
                                            <td class="tdprogram"><?php echo number_format($sum_prog->vol, 0, ',', '.'); ?></td>
                                            <td class="tdprogram"><?php echo "Paket Program"; ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->renk_b, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>'  . number_format($sum_prog->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->renf_b, 2, ',', '.') .  '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right"><?php echo '<b>' . number_format($sum_prog->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdprogram" style="text-align: right "><?php echo '<b>' . ($sum_prog->renf_b == 0 ? "-" : number_format($sum_prog->rl_fis / $sum_prog->renf_b * 100, 2, ',', '.'))  . '</b>' ?></td>

                                        </tr>
                                        <?php $idp = array_merge([$idk], $idp); ?>
                                    <?php endif; ?>
                                    <?php $idk = $idk . '.' . $d['kdgiat']; ?>
                                    <?php if (!in_array($idk, $idp)) :

                                        $sum_giat = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram'], $d['kdgiat']);
                                    ?>
                                        <tr>
                                            <td class="tdgiat"><b><?= $nogiat++ ?></b></td>
                                            <td class="tdgiat"><b><?php echo $idk; ?></b></td>
                                            <td class="tdgiat"><b><?php echo $d['nmgiat']; ?></b></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->vol, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat"><?php echo "<b>Paket Kegiatan</b>"; ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' .  number_format($sum_giat->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->renk_b, 2, ',', '.')  . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>'  . number_format($sum_giat->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->renf_b, 2, ',', '.')  .  '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right"><?php echo '<b>' . number_format($sum_giat->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdgiat" style="text-align: right "><?php echo '<b>' . ($sum_giat->renf_b == 0 ? "-" : number_format($sum_giat->rl_fis / $sum_giat->renf_b * 100, 2, ',', '.'))  . '</b>' ?></td>
                                        </tr>
                                        <?php $idp = array_merge([$idk], $idp); ?>
                                    <?php endif; ?>

                                    <?php $idk = $idk . '.' . $d['kdoutput']; ?>
                                    <?php if (!in_array($idk, $ido)) :
                                        $sum_output = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram'], $d['kdgiat'], $d['kdoutput']);

                                    ?>
                                        <tr>
                                            <td class="tdoutput"><b><?= $nooutput++ ?></b></td>
                                            <td class="tdoutput"><b><?php echo $idk; ?></b></td>
                                            <td class="tdoutput"><b><?php echo $d['nmoutput']; ?></b></td>
                                            <td class="tdoutput" style="text-align: right"><b><?php echo number_format($sum_output->vol, 0, ',', '.'); ?></b></td>
                                            <td class="tdoutput"><b><?php echo $d['toutputsat']; ?></b></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .   number_format($sum_output->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .   number_format($sum_output->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .    number_format($sum_output->renk_b, 2, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>'  . number_format($sum_output->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' .   number_format($sum_output->renf_b, 2, ',', '.')  . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_output->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdoutput" style="text-align: right "><?php echo '<b>' . ($sum_output->renf_b == 0 ? "-" : number_format($sum_output->rl_fis / $sum_output->renf_b * 100, 2, ',', '.')) . '</b>' ?></td>
                                        </tr>
                                        <?php $ido = array_merge([$idk], $ido); ?>
                                    <?php endif; ?>

                                    <?php $idk = $idk . '.' . $d['kdsoutput']; ?>
                                    <?php if (!in_array($idk, $idso)) :
                                        $sum_soutput = sum_data(session('userData.tahun'), $bulanberjalan, $d['kdprogram'], $d['kdgiat'], $d['kdoutput'], $d['kdsoutput']); ?>
                                        <tr>
                                            <td class="tdsoutput"><b><?= $nosoutput++ ?></b></td>
                                            <td class="tdsoutput"><b><?php echo $idk; ?></b></td>
                                            <td class="tdsoutput"><b><?php echo $d['nmro']; ?></b></td>
                                            <td class="tdsoutput" style="text-align: right"><b><?php echo number_format($sum_soutput->vol, 0, ',', '.'); ?></b></td>
                                            <td class="tdsoutput"><b><?php echo $d['sat']; ?></b></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_soutput->pagu, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_soutput->rtot, 0, ',', '.')  . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' .  number_format($sum_soutput->renk_b, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' . number_format($sum_soutput->rl_keu, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' .  number_format($sum_soutput->renf_b, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right"><?php echo '<b>' .  number_format($sum_soutput->rl_fis, 2, ',', '.') . '</b>' ?></td>
                                            <td class="tdsoutput" style="text-align: right "><?php echo '<b>' . ($sum_soutput->renf_b == 0 ? "-" : number_format($sum_soutput->rl_fis / $sum_soutput->renf_b * 100, 2, ',', '.'))  . '</b>' ?></td>
                                        </tr>
                                        <?php $idso = array_merge([$idk], $idso); ?>
                                    <?php endif;

                                    ?>

                                <?php endforeach; ?>
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
<<<<<<< HEAD
    var $th = $('.tableFixHead1').find('thead th')
    $('.tableFixHead1').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    })

    // $("#search").click(function() {
    //     window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
    // });

    $("#searchkeyword").click(function() {
        window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val() + "/" + $('#keyword').val();
=======
    $(".select-opt").select2();

    let uri = window.location.pathname.split('/');
    let url = "<?= site_url('Kinerja-Output-Bulanan/sendDataKinerja') ?>";
    let temp_kegiatan, temp_output = false;
    var cell_data;
    var inputs = {
        bulan: uri[2],
        kode_program: null,
        kode_kegiatan: null,
        kode_output: null,
        kode_ro: null,
        kode_komponen: null
    };
    
    let csrfName = $("#__csrf").attr("name")
    let csrfHash = $("#__csrf").val()
    inputs.kode_program = $('#kd_program').val()
    var table = $("#table").DataTable({
        dom: 'lrtip',
        processing: true,
        ordering: false,
        searching: false,
        deferRender: true,
        ajax: {
            url: "<?= site_url('Kinerja-Output-Bulanan/sendDataKinerja') ?>",
            type: 'post',
            data : {
                inputs,
                [csrfName] : csrfHash
            },
        },
        scrollX: true,
        scrollY: 350,
        scrollCollapse: true,
        scroller: {
            loadingIndicator: true,
            boundaryScale: 0.5,
            displayBuffer: 2
        },
        createdRow: function(row, data, rowIndex){
            $.each($('td', row), function (colIndex) {
                $(this).attr("class", data.class)
            });
        },
        columns: [
            {data: "no"},
            {data: "kode"},
            {data: "nama"},
            {data: "target"},
            {data: "satuan"},
            {data: "pagu"},
            {data: "realisasi"},
            {data: "keuangan_rn"},
            {data: "keuangan_rl"},
            {data: "fisik_rn"},
            {data: "fisik_rl"},
            {data: "fisik_kinerja"}
        ],
    })

    $("#kd_output").prop("disabled", true)
    $("#kd_komponen").prop("disabled", true)
    // $("#kd_ro").prop("disabled", true)
    $('.kegiatan-component').hide()
    $('.output-component').hide()

    var param;
    let program, kegiatan;
    $("#table tbody").on('click', 'tr', function(){
        
        // $("#kd_kegiatan").val('').trigger('change')
        cell_data = table.row(this).data()
        kode = cell_data.kode.split('.')
        if(kode.length == 1){

            if(kode[0].length === 2){
                
                temp_kegiatan = true
                temp_output = false
                param = { program: kode[0] };
                program = kode[0];
            }else{

                temp_kegiatan = false
                temp_output = false
                param = { 
                    program: program,
                    kegiatan: kode[0]
                };
                kegiatan = kode[0];
            }
        }else{

            temp_kegiatan = false
            temp_output = true
            param = {
                program: program,
                kegiatan: kegiatan,
                output: kode[1]
            };
        }
        table.destroy();
        Datatable2nd(param, inputs, url);

        if(temp_kegiatan){
            
            $('.kegiatan-component').show()
        }else{

            $('.kegiatan-component').hide()
        }

        if(temp_output){
            
            $('.output-component').show()
        }else{

            $('.output-component').hide()
        }
        // console.log(typeof kode[0])
    })

    //bulan
    inputs.bulan = $("#listmonth").val()
    $("#listmonth").change(function(){

        inputs.bulan = $(this).val()
        table.destroy();
        Datatable(inputs, url);
    })

    //kode program
    $("#kd_program").change(function(){

        inputs.kode_program = $(this).val()
        table.destroy();
        Datatable(inputs, url);
    })

    //kode kegiatan
    $("#kd_kegiatan").change(function(){

        // param = { program: inputs.kode_program };
        inputs.kode_kegiatan = $(this).val()
        if(inputs.kode_kegiatan.length != 0){

            $("#kd_output").prop("disabled", false)
            // $("#kd_ro").prop("disabled", false)
            $.post("<?= site_url('Kinerja-Output-Bulanan/sendDataKegiatan') ?>",
            {
                kode_kegiatan: inputs.kode_kegiatan,
                [csrfName] : csrfHash
            },
            function(data){

                var opt = ''
                $.each(JSON.parse(data), function(i, val){
                    
                    opt += `<option values="`+val.kode+`">`+val.kode+`</option>`;
                })
                $("#kd_output").html(opt)
            })
        }else{

            $("#kd_output").prop("disabled", true)
            // $("#kd_ro").prop("disabled", true)
        }
        table.destroy();
        Datatable(inputs, url);
    })

    //kode ro
    $("#kd_ro").on("change", function(){

        //backup for single select - simple code
        // table.search(this.value).draw()

        //multiple select
        var data = $.map( $(this).select2('data'), function( value, key ) {
        return value.text ? $.fn.dataTable.util.escapeRegex(value.text) : null;
                    });

        if (data.length === 0) {
        data = [""];
        }
        
        var val = data.join('|');
        console.log(val)
        
        table.search( val ? val : '', true, false ).draw();
    })

    //kode output
    // $("#kd_output").change(function(){

    //     inputs.kode_output = $(this).val()
    //     if(inputs.kode_kegiatan.length != 0 && inputs.kode_output.length != 0){

    //         $("#kd_komponen").prop("disabled", false)
    //         $.post("<?= site_url('Kinerja-Output-Bulanan/sendDataOutput') ?>",
    //         {
    //             kode_kegiatan: inputs.kode_kegiatan,
    //             kode_output: inputs.kode_output,
    //             [csrfName] : csrfHash
    //         },
    //         function(data){

    //             var opt = `<option value="" selected disabled>Pilih kode soutput</option>`
    //             $.each(JSON.parse(data), function(i, val){
                    
    //                 opt += `<option values="`+val.kode+`">`+val.kode+`</option>`;
    //             })
    //             $("#kd_komponen").html(opt)
    //         })
    //     }else{

    //         $("#kd_komponen").prop("disabled", true)
    //     }
    //     table.destroy();
    //     Datatable(inputs, url);
    // })

    //kode komponen
    // $("#kd_komponen").change(function(){

    //     inputs.kode_komponen = $(this).val()
    //     table.destroy();
    //     Datatable(inputs, url);
    // })

    function Datatable(inputs, url){
        
        table = $("#table").DataTable({
            dom: 'lrtip',
            processing: true,
            ordering: false,
            // searching: false,
            deferRender: true,
            ajax: {
                url: url,
                type: 'post',
                data : {
                    inputs,
                    [csrfName] : csrfHash
                },
            },
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            scroller: {
                loadingIndicator: true,
                boundaryScale: 0.5,
                displayBuffer: 2
            },
            createdRow: function(row, data, rowIndex){
                $.each($('td', row), function (colIndex) {
                    $(this).attr("class", data.class)
                });
            },
            columns: [
                {data: "no"},
                {data: "kode"},
                {data: "nama"},
                {data: "target"},
                {data: "satuan"},
                {data: "pagu"},
                {data: "realisasi"},
                {data: "keuangan_rn"},
                {data: "keuangan_rl"},
                {data: "fisik_rn"},
                {data: "fisik_rl"},
                {data: "fisik_kinerja"}
            ],
        })
    }

    function Datatable2nd(param, inputs, url){
        
        table = $("#table").DataTable({
            dom: 'lrtip',
            processing: true,
            ordering: false,
            // searching: false,
            deferRender: true,
            ajax: {
                url: url,
                type: 'post',
                data : {
                    param,
                    inputs,
                    [csrfName] : csrfHash
                },
            },
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            scroller: {
                loadingIndicator: true,
                boundaryScale: 0.5,
                displayBuffer: 2
            },
            createdRow: function(row, data, rowIndex){
                $.each($('td', row), function (colIndex) {
                    $(this).attr("class", data.class)
                });
            },
            columns: [
                {data: "no"},
                {data: "kode"},
                {data: "nama"},
                {data: "target"},
                {data: "satuan"},
                {data: "pagu"},
                {data: "realisasi"},
                {data: "keuangan_rn"},
                {data: "keuangan_rl"},
                {data: "fisik_rn"},
                {data: "fisik_rl"},
                {data: "fisik_kinerja"}
            ],
        })
    }

    $("button.btn-reset").click(function(){

        // $("#kd_kegiatan").val('').trigger('change')
        // $("#kd_ro").val('').trigger('change')
        table.destroy()
        Datatable(inputs, url)
    })

    var $th = $('.tableFixHead1').find('thead th')
    $('.tableFixHead1').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    })

    $("#search").click(function() {
        window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
>>>>>>> 49bd92a322b513c092a991bea33097f4ffd1790d
    });
</script>
</script>
<?= $this->endSection() ?>