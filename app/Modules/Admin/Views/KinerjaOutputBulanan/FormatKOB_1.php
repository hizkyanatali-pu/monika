<?php error_reporting(0); ?>
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
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="__csrf">
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
                                for ($index = 0; $index < $bulansekarang; $index++) { ?>
                                    <option value="<?= encrypt_url($noBulan) ?>" <?= ($bulanberjalan ==  $noBulan ? 'selected="selected"' : '') ?>> <?= $namaBulan[$index] ?> </option>;
                                <?php
                                    $noBulan++;
                                }
                                ?>
    
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="search"><div class="fa fa-search"></div> </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 text-right mt-3">
                        <div class="form-group">
                            <a href="<?= site_url('Kinerja-Output-Bulanan/') . $uri->getSegment(2) . "?exp=xlxs" ?>" class="btn btn-success btn-sm text-white" target="_blank"><i class="fa fa-file-excel"></i>Excel</a>
                            <b>*Dalam Ribuan</b>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="mb-0">Kode Program</label>
                        <div class="input-group">
                            <select class="form-control select-opt" id="kd_program">
                                <option></option>
                                <option value="FC" selected>FC</option>
                                <option value="WA">WA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="mb-0">Kode KRO</label>
                        <div class="input-group">
                            <select class="form-control select-opt" id="kd_output" multiple>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="mb-0">Kode SOutput</label>
                        <div class="input-group">
                            <select class="form-control select-opt" id="kd_komponen">
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="max-width; 100%;overflow-x: hidden;">

                    <?php $colspan = 8; ?>
                    <table class="table table-bordered mb-0 table-striped display nowrap" id="table" style="width: 100%;">
                        <thead>
                            <tr class=" text-center bg-purple">
                                <th class="thNo text-white" rowspan="2">No</th>
                                <th class="thKode text-white" rowspan="2">Kode</th>
                                <th class="thLabel text-white" rowspan="2">Program/Kegiatan/KRO/RO</th>
                                <th class="thNilai text-white" rowspan="2">Target</th>
                                <th class="thNilai text-white" rowspan="2">Satuan</th>
                                <th class="thNilai text-white" rowspan="2">Pagu (Rp Ribu)</th>
                                <th class="thNilai text-white" rowspan="2">Realisasi (Rp Ribu)</th>
                                <th class="thPersen text-white" colspan="2">Keuangan (%)</th>
                                <th class="thPersen text-white" colspan="3">Fisik (%)</th>
                            </tr>
                            <tr class=" text-center bg-purple">
                                <th class="thPersen text-white">RN</th>
                                <th class="thPersen text-white">RL</th>
                                <th class="thPersen text-white">RN</th>
                                <th class="thPersen text-white">RL</th>
                                <th class="thPersen text-white">Kinerja</th>
                            </tr>
                        </thead>

                        <tbody id="tbody-utama">
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
    
<?= script_tag('https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js'); ?>

<?= script_tag('https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js'); ?>

<?= script_tag('https://cdn.datatables.net/scroller/2.0.5/js/dataTables.scroller.min.js'); ?>

<?= script_tag('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'); ?>

<script>
    $(".select-opt").select2();

    let uri = window.location.pathname.split('/');
    var inputs = {
        bulan: uri[2],
        kode_program: 'FC',
        kode_kegiatan: null,
        kode_output: null,
        kode_komponen: null
    };
    
    let csrfName = $("#__csrf").attr("name")
    let csrfHash = $("#__csrf").val()
    var table = $("#table").DataTable({
        processing: true,
        ordering: false,
        searching: false,
        deferRender: true,
        // serverSide: true,
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
            loadingIndicator: true
        },
        'createdRow': function(row, data, rowIndex){
            $.each($('td', row), function (colIndex) {
                $(this).attr("class", data.class)
            });
        },
        columns: [
            {"data": "no"},
            {"data": "kode"},
            {"data": "nama_program"},
            {"data": "target"},
            {"data": "satuan"},
            {"data": "pagu"},
            {"data": "realisasi"},
            {"data": "keuangan_rn"},
            {"data": "keuangan_rl"},
            {"data": "fisik_rn"},
            {"data": "fisik_rl"},
            {"data": "fisik_kinerja"}
        ],
    })

    $("#kd_output").prop("disabled", true)
    $("#kd_komponen").prop("disabled", true)

    //bulan
    inputs.bulan = $("#listmonth").val()
    $("#listmonth").change(function(){

        inputs.bulan = $(this).val()
        table.destroy();
        Datatable(inputs);
    })
    //kode program
    $("#kd_program").change(function(){

        inputs.kode_program = $(this).val()
        table.destroy();
        Datatable(inputs);
    })
    //kode kegiatan
    $("#kd_kegiatan").change(function(){

        inputs.kode_kegiatan = $(this).val()
        if(inputs.kode_kegiatan.length != 0){

            $("#kd_output").prop("disabled", false)
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
        }
        table.destroy();
        Datatable(inputs);
    })
    //kode output
    $("#kd_output").change(function(){

        inputs.kode_output = $(this).val()
        if(inputs.kode_kegiatan.length != 0 && inputs.kode_output.length != 0){

            $("#kd_komponen").prop("disabled", false)
            $.post("<?= site_url('Kinerja-Output-Bulanan/sendDataOutput') ?>",
            {
                kode_kegiatan: inputs.kode_kegiatan,
                kode_output: inputs.kode_output,
                [csrfName] : csrfHash
            },
            function(data){

                var opt = `<option value="" selected disabled>Pilih kode soutput</option>`
                $.each(JSON.parse(data), function(i, val){
                    
                    opt += `<option values="`+val.kode+`">`+val.kode+`</option>`;
                })
                $("#kd_komponen").html(opt)
            })
        }else{

            $("#kd_komponen").prop("disabled", true)
        }
        table.destroy();
        Datatable(inputs);
    })
    //kode komponen
    $("#kd_komponen").change(function(){

        inputs.kode_komponen = $(this).val()
        table.destroy();
        Datatable(inputs);
    })

    function Datatable(inputs){
        
        table = $("#table").DataTable({
            processing: true,
            ordering: false,
            searching: false,
            deferRender: true,
            // serverSide: true,
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
                loadingIndicator: true
            },
            'createdRow': function(row, data, rowIndex){
                $.each($('td', row), function (colIndex) {
                    $(this).attr("class", data.class)
                });
            },
            columns: [
                {"data": "no"},
                {"data": "kode"},
                {"data": "nama_program"},
                {"data": "target"},
                {"data": "satuan"},
                {"data": "pagu"},
                {"data": "realisasi"},
                {"data": "keuangan_rn"},
                {"data": "keuangan_rl"},
                {"data": "fisik_rn"},
                {"data": "fisik_rl"},
                {"data": "fisik_kinerja"}
            ],
        })
    }
    // var $th = $('.tableFixHead1').find('thead th')
    // $('.tableFixHead1').on('scroll', function() {
    //     $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    // })

    $("#search").click(function() {
        window.location.href = "<?= site_url('Kinerja-Output-Bulanan/') ?>" + $('#listmonth').val();
    });
</script>
<?= $this->endSection() ?>