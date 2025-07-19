<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Postur Rencana Tender TA. <?= session("userData.tahun") ?>
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>
            <div>
                <select class="form-control" name="filter_paguAnggaran">
                    <option value="RPM">RPM</option>
                    <option value="SBSN">SBSN</option>
                    <option value="PHLN">PHLN</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary mt-3" onclick="capture('#diagram-section', 'Rencana Tender')">
            <i class="fas fa-image"></i> Download Diagram
        </button>
    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" id="diagram-section">
    <div class=" kt-portlet">
    <div class="kt-portlet__body" style="padding:0px;">

        <!--begin::Section-->
        <div class="kt-section  pb-4 pt-3">

            <div class="kt-section__content" style="overflow-x: auto;">
                <!-- <div class="text-center mt-4">
                        <h4 class="text-dark"><b><?= $title; ?></b></h4>
                        <hr class="w-75 mb-0">
                    </div> -->
                <div class="" style="width: 1400px; margin: 0px auto">
                    <div class="tree ml--105 pr-4">
                        <ul id="_main-tree">
                            <li class="w-100">
                                <a href="#" class="w-25" id="_container_cardParent">
                                    
                                </a>
                                <ul id="_container_cardChildren">
                                    
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<script>
    $(document).ready(function() {
        getData($('select[name=filter_paguAnggaran]').val());
    });

    $(document).on('change', 'select[name=filter_paguAnggaran]', function() {
        getData($(this).val());
    });



    function getData(_pagu) {
        $.ajax({
            url  : "<?php echo site_url('api/posturanggaran/get-data-rencana-tender/'); ?>" + _pagu,
            type : 'GET',
            success: function (res) {
                $('#_container_cardParent').empty();
                $('#_container_cardChildren').empty();

                res.data.perBulan.forEach((data, key) => {
                    $('#_container_cardChildren').append(Render.childCard({
                        bulan: data.bulan,
                        jumlahPaket: data.jml_paket,
                        niliaiKontrak: data.nilai_kontrak
                    }))
                });

                $('#_container_cardParent').html(Render.parentCard({
                    namaPagu: res.pagu,
                    jumlahPaket: res.data.pagu.jml_paket, 
                    niliaiKontrak: res.data.pagu.nilai_kontrak
                }));
            }
        });
    }





    var Render = new class {
        parentCard(params = {
            namaPagu: '',
            jumlahPaket: 0,
            niliaiKontrak: 0
        }) {
            let nominal = this.toMilyar(params.niliaiKontrak)
            return `
                <div class="tree-content">
                    <div class="card card-body bg-tree-2">
                        <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                        <h4 class="mb-0"><b> ${params.namaPagu} </b></h4>
                        <label> ${params.jumlahPaket} Paket</label>
                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                            <h5 class="mb-0">
                                ${nominal}
                            </h5>
                        </div>
                    </div>
                </div>
            `;
        }


        childCard(params = {
            bulan: '',
            jumlahPaket: 0,
            niliaiKontrak: 0
        }) {
            let nominal = this.toMilyar(params.niliaiKontrak)
            return `
                <li class="" style="width: 25% !important">
                    <a href="#" class="w-75">
                        <div class="tree-content">
                            <div class="card card-body bg-tree-yellow">
                                <h4 class="mb-0"><b> ${params.bulan} </b></h4>
                                <label> ${params.jumlahPaket} Paket</label>
                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                    <h5 class="mb-0">
                                        ${nominal}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            `;
        }


        toMilyar(_number) {
            let stringLength = _number > 0 ? _number.length - 3 :  0;
            let pembagi = 1000000000;
            let suffix = 'M';

            if (stringLength >= 13) {
                pembagi = 1000000000000;
                suffix = 'T';
            }
            return "Rp. " + this.toFixed((_number / pembagi), 2) + " " + suffix;
        }

        toFixed(num, fixed) {
            var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
            return num.toString().match(re)[0];
        }
    }
</script>
<?= $this->endSection() ?>