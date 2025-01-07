<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
<style>
    footer {
        display: none;
    }
    html { scroll-behavior: smooth; }
    .card{
        border: none;
    }
    .icon-group{
        padding: 0 30px;
        margin-bottom: 30px;
        text-align: center;
        cursor: pointer;
    }
    .icon-group .icon:hover{
        background-color: #5867dd;
        transition: .5s;
    }
    .icon-group .icon{
        background-color: #f3cb3a;
        color: white;
        padding: 20px 27px 25px 27px;
        border-radius: 50%;
        padding-top: 35px;
    }
    .icon-group p{
        margin-top: 40px ;
        font-weight: 600;
    }
    .icon-group2 div{
        /* padding: 30px 30px; */
        margin-bottom: 20px;
        background-color: #5867dd;
        text-align: center;
        border-radius: 20px;
        cursor: pointer;
        color: white;
    }
    .icon-group2 div:hover{
        background-color: white;
        border: 1px solid #5867dd;
        color: #5867dd;
        transition: .3s;
    }
    .icon-group2 div .icon{
        padding: 20px;
        border-radius: 50%;
        padding-top: 35px;
    }
    .icon-group2 div p{
        margin-top: 20px !important;
        font-weight: 600;
        margin:0;
    }

    .text-ellipsis-2{
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .modal-header{
        background-color: #5867dd;
        color: white !important;
        border: 1px solid #5867dd;
    }
    .modal-header h5{
        color: white !important;
    }
    .modal-header button{
        color: white !important;
    }
    .card-1{
        background: url("<?= base_url('images/laporan/9.png'); ?>") ;
        background-size: 36%;
        background-position: center center;
        background-repeat: no-repeat;
    }
    @media (min-width: 1050px) {
        .tree li .tree-mobile, .tree-mobile{
            display: none;
        }
        .w-flexible{
            width: 25%
        }
    }
    @media (max-width: 1050px) {
        .tree li .tree-mobile, .tree-mobile{
            display: block !important;
        }
        .tree-desktop{
            display: none !important;
        }
        .w-flexible{
            width: 100%
        }
        .card-1{
            background-size: 100%;
        }
    }

    /*new*/
    .btn-progres{
        display: block;
        cursor: pointer;
    }
    .btn-lelang{
        display: block;
        cursor: pointer;
    }
    .centered-xy{
        height: 90vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 20px;
    }
    .height-100{
        height: 100vh;
    }
    .height-150{
        height: 150vh;
    }
    .height-210{
        height: 240vh;
    }
    .height-120{
        height: 120vh;
    }
    .height-170{
        height: 170vh;
    }
    .height-130{
        height: 130vh;
    }
    .bg-blue{
        background-color: #00b0f0;
    }
    .bg-yellow{
        background-color: #ffc000;
    }
</style>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title text-center">
                Dasboard Progres Keu & Fisik
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>
    </div>
</div>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <?php if (!empty(session()->getFlashdata('error'))) : ?>
            <div class="alert alert-danger" role="alert">
                <h4>Error !</h4>
                </hr />
                <?php echo session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>


        <div class="kt-portlet__body" style="padding:0 !important">
            <!--begin::Section-->
            <div class="kt-section">

                <div class="card">
                    <div class="centered-xy">
                        <div class="w-100">
                            <a href="#progress-section" class="btn btn-primary mb-3 w-100 py-4 btn-progres">Progres</a>
                            <a href="#lelang-section" class="btn btn-warning mb-3 w-100 py-4 btn-lelang">Lelang</a>
                        </div>
                    </div>
                    <div class="centered-xy bg-blue" id="progress-section">
                        <div class="w-100">
                            <?php $totalPagu = 0 ?>
                            <?php foreach ($pagu as $key => $value) : ?>
                                <?php $totalPagu += $value->totalPagu ?>
                            <?php endforeach ?>
                            <div class="tree">
                                <div class="row ">
                                    <div class="col-6">
                                        <div class="card border-0 text-center card-1">
                                            <div class="card-body font-weight-bold text-white" style="padding: 2rem;line-height: 12px;">
                                                <p>PROGRES (%) </p>
                                                <p>KEU</p>
                                                <p><?= isset($keuProgressSda) ? number_format($keuProgressSda, 2, ',', '.') : 0 ?></p>
                                                <p>Deviasi (%)</p>
                                                <p><?= $total_deviasi ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border-0 text-center card-1">
                                            <div class="card-body font-weight-bold text-white" style="padding: 2rem;line-height: 12px;">
                                                <p>PROGRES (%) </p>
                                                <p>FISIK</p>
                                                <p><?= isset($fisikProgressSda) ? number_format($fisikProgressSda, 2, ',', '.') : 0 ?></p>
                                                <p>Deviasi (%)</p>
                                                <p><?= $total_deviasi ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul style="padding:0">
                                    <li class="w-100 pb-5">
                                        <a href="javascript:;" class="w-flexible">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-1">
                                                    <h4 class="mb-0"><b> Total Pagu </b></h4>
                                                    <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($pagu_total->total_pagu, true, 2) ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="tree-mobile">
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-1">
                                                        <h4 class="mb-0"><b> Total Realisasi </b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($real_total->total_real, true, 2) ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-1">
                                                        <h4 class="mb-0"><b> Total Belum Realisasi </b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($selisih_total->selisih, true, 2) ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <ul class="tree-desktop">
                                            <?php foreach ($pagu as $key => $value) : ?>
                                            <li class="" style="width: 33% !important">
                                                <a href="javascript:;" class="w-100">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-secondary text-white">
                                                            <h4 class="mb-0"><b><?= $value->title ?></b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    <?= toMilyar($value->totalPagu, true, 2) ?>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100">
                                                    <div class="tree-content">
                                                        <div class="card bg-tree-3  text-white bg-tree-footer card-body shadow">
                                                            <h4 class="mb-0"><b>Realisasi</b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100">
                                                    <div class="tree-content">
                                                        <div class="card bg-danger  text-white bg-tree-footer card-body shadow">
                                                            <h4 class="mb-0"><b>Belum Realisasi</b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <?php endforeach ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="centered-xy bg-blue">
                        <div class="">
                            <div class="tree">
                                <ul style="padding:0">
                                    <li class="mb-5" style="width: 100%">
                                        <a href="javascript:;" class="w-flexible">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-1">
                                                    <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                    <h4 class="mb-0"><b> TOTAL PAGU </b></h4>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($pagu_all->total_pagu, true, 2); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="tree-mobile">
                                            <div class="border-single-tree-down"></div>
                                            <a href="" class="w-100 mb-2" data-toggle="modal" data-target="#RPMModal">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_rpm, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="" class="w-100 mb-2" data-toggle="modal" data-target="#SBSNModal">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_sbsn, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="" class="w-100 mb-2" data-toggle="modal" data-target="#PLNModal">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PLN </b></h4>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($pagu_all->total_phln, true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <ul class="tree-desktop">
                                            <li class="" style="width: 33% !important">
                                                <a href="javascript:;" class="w-100">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> RPM </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    <?= toMilyar($nilai_rpm['nilai_kontrak'], true, 2); ?>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100 mb-2">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> REALISASI </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100 mb-2">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> BELUM REALISASI </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="" style="width: 33% !important">
                                                <a href="javascript:;" class="w-75">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> SBSN </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    <?= toMilyar($nilai_sbsn['nilai_kontrak'], true, 2); ?>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100 mb-2">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> REALISASI </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100 mb-2">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> BELUM REALISASI </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="" style="width: 33% !important">
                                                <a href="javascript:;" class="w-75">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> PLN </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    <?= toMilyar($nilai_phln['nilai_kontrak'], true, 2); ?>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100 mb-2">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> REALISASI </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="border-single-tree-down"></div>
                                                <a href="javascript:;" class="w-100 mb-2">
                                                    <div class="tree-content">
                                                        <div class="card card-body bg-tree-2">
                                                            <h4 class="mb-0"><b> BELUM REALISASI </b></h4>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
                                                                </h5>
                                                            </div>
                                                            <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                                <h5 class="mb-0">
                                                                    0
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
                    </div>
                    <div class="centered-xy bg-blue">
                        <div class="w-100">
                            <div class="kt-portlet kt-portlet--tab" id="progres_keuangan_fisik_per_kegiatan">
                                <div class="kt-section mb-0">
                                    <div class="kt-section__content">
                                        <div class="float-left">
                                            <i><b>Status : <?= $rekapunor['total']['status'] ?></b></i>
                                        </div>

                                        <div class="float-right">
                                            <i><b>*Dalam Miliar</b></i>
                                        </div>

                                        <table class="table table-bordered" style="width: 100%;">
                                            <thead class="text-center text-white" style="background-color: #1562aa;">
                                                <tr>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" rowspan="2">No</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" rowspan="2">Unit Organisasi</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="1">Pagu</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="1">Realisasi</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="2">Progres</th>

                                                </tr>
                                                <tr>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Total</th>

                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Total</th>

                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keu</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Fisik</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($rekapunor['unor'] as $key => $val) { ?>

                                                    <tr <?= ($val['kdunit'] == 06 ? "class='tdprogram font-weight-bold' data-toggle='modal' data-target='#KegiatanModal'" : "") ?>>
                                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" scope="row"><?= ++$key ?></th>
                                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px"><?= $val['nmsingkat']; ?></td>
                                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= toMilyar($val['pagu_total'], true, 2); ?></td>
                                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= toMilyar($val["real_total"], true, 2); ?></td>

                                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_keu'], 2, ',', '.'); ?> %</td>
                                                        <td style="padding: 0px 4px 0px 4px !important; font-size: 12px" class="tdNilai text-right col-pagu_phln"><?= number_format($val['progres_fisik'], 2, ',', '.'); ?> %</td>

                                                    </tr>


                                                <?php   } ?>

                                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                                    <td colspan="2">TOTAL</td>
                                                    <td class="tdNilai text-right col-pagu_phln"><?= toMilyar($rekapunor['total']['pagu_total'], true, 2); ?></td>

                                                    <td class="tdNilai text-right col-pagu_phln"><?= toMilyar($rekapunor['total']["real_total"], true, 2); ?></td>

                                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['progres_keu'], 2, ',', '.'); ?> %</td>
                                                    <td class="tdNilai text-right col-pagu_phln"><?= number_format($rekapunor['total']['progres_fisik'], 2, ',', '.'); ?> %</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="centered-xy height-210 bg-blue">
                        <div class="w-100 table-responsive">
                            <h3 class="text-center text-white">Satker dengan Progres Tertinggi</h3>
                            <table class="table table-bordered">
                                <thead class="text-white text-center" style="background-color: #1562aa;">
                                    <tr>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">No</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Satker</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Blok</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keu %</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Fis %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sum_total_progres = 0;
                                        $sum_total_keu_progres = 0;
                                        $sum_total_fis_progres = 0;
                                        $sum_total_blokir = 0;

                                        foreach ($satker_desc as $key => $value): 

                                        $sum_total_progres += $value->total_progres;
                                        $sum_total_blokir += $value->total_blokir;
                                        $sum_total_keu_progres += $value->total_keu_progres;
                                        $sum_total_fis_progres += $value->total_fis_progres;
                                    ?>  
                                        <tr class="text-center text-white" style="background-color: #1562aa;">
                                            <td><?= $key+1 ?></td>
                                            <td><?= $value->satker ?></td>
                                            <td><?= number_format($value->total_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_blokir, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_keu_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_fis_progres, 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <tr class="text-center text-white" style="background-color: #1562aa;">
                                        <td>11</td>
                                        <td>Total</td>
                                        <td><?= number_format($sum_total_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_blokir, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_keu_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_fis_progres, 2, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="centered-xy height-210 bg-blue">
                        <div class="w-100 table-responsive">
                            <h3 class="text-center text-white">Satker dengan Progres Terendah</h3>
                            <table class="table table-bordered">
                                <thead class="text-white text-center" style="background-color: #1562aa;">
                                    <tr>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">No</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Satker</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Blok</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keu %</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Fis %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sum_total_progres = 0;
                                        $sum_total_keu_progres = 0;
                                        $sum_total_fis_progres = 0;
                                        $sum_total_blokir = 0;

                                        foreach ($satker_asc as $key => $value): 

                                        $sum_total_progres += $value->total_progres;
                                        $sum_total_blokir += $value->total_blokir;
                                        $sum_total_keu_progres += $value->total_keu_progres;
                                        $sum_total_fis_progres += $value->total_fis_progres;
                                    ?>  
                                        <tr class="text-center text-white" style="background-color: #1562aa;">
                                            <td><?= $key+1 ?></td>
                                            <td><?= $value->satker ?></td>
                                            <td><?= number_format($value->total_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_blokir, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_keu_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_fis_progres, 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <tr class="text-center text-white" style="background-color: #1562aa;">
                                        <td>11</td>
                                        <td>Total</td>
                                        <td><?= number_format($sum_total_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_blokir, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_keu_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_fis_progres, 2, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="centered-xy height-210 bg-blue">
                        <div class="w-100 table-responsive">
                            <h3 class="text-center text-white">Balai dengan Progres Tertinggi</h3>
                            <table class="table table-bordered">
                                <thead class="text-white text-center" style="background-color: #1562aa;">
                                    <tr>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">No</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Satker</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Blok</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keu %</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Fis %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sum_total_progres = 0;
                                        $sum_total_keu_progres = 0;
                                        $sum_total_fis_progres = 0;
                                        $sum_total_blokir = 0;

                                        foreach ($balai_desc as $key => $value): 

                                        $sum_total_progres += $value->total_progres;
                                        $sum_total_blokir += $value->total_blokir;
                                        $sum_total_keu_progres += $value->total_keu_progres;
                                        $sum_total_fis_progres += $value->total_fis_progres;
                                    ?>  
                                        <tr class="text-center text-white" style="background-color: #1562aa;">
                                            <td><?= $key+1 ?></td>
                                            <td><?= $value->balai ?></td>
                                            <td><?= number_format($value->total_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_blokir, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_keu_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_fis_progres, 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <tr class="text-center text-white" style="background-color: #1562aa;">
                                        <td>11</td>
                                        <td>Total</td>
                                        <td><?= number_format($sum_total_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_blokir, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_keu_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_fis_progres, 2, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="centered-xy height-170 bg-blue">
                        <div class="w-100 table-responsive">
                            <h3 class="text-center text-white">Balai dengan Progres Terendah</h3>
                            <table class="table table-bordered">
                                <thead class="text-white text-center" style="background-color: #1562aa;">
                                    <tr>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">No</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Satker</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Blok</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keu %</th>
                                        <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Fis %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sum_total_progres = 0;
                                        $sum_total_keu_progres = 0;
                                        $sum_total_fis_progres = 0;
                                        $sum_total_blokir = 0;

                                        foreach ($balai_asc as $key => $value): 

                                        $sum_total_progres += $value->total_progres;
                                        $sum_total_blokir += $value->total_blokir;
                                        $sum_total_keu_progres += $value->total_keu_progres;
                                        $sum_total_fis_progres += $value->total_fis_progres;
                                    ?>  
                                        <tr class="text-center text-white" style="background-color: #1562aa;">
                                            <td><?= $key+1 ?></td>
                                            <td><?= $value->balai ?></td>
                                            <td><?= number_format($value->total_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_blokir, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_keu_progres, 2, ',', '.') ?></td>
                                            <td><?= number_format($value->total_fis_progres, 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <tr class="text-center text-white" style="background-color: #1562aa;">
                                        <td>11</td>
                                        <td>Total</td>
                                        <td><?= number_format($sum_total_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_blokir, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_keu_progres, 2, ',', '.') ?></td>
                                        <td><?= number_format($sum_total_fis_progres, 2, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="centered-xy bg-yellow" id="lelang-section">
                        <div class="">
                            <div class="tree">
                                <ul style="padding:0">
                                    <li class="mb-5" style="width: 100%">
                                        <a href="javascript:;" class="w-flexible">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-1">
                                                    <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                    <h4 class="mb-0"><b> KONTRAKTUAL </b></h4>
                                                    <label><?= number_format(($terkontrak['jml_paket'] + ($proseslelang['jml_paket'] + $belumlelang['jml_paket'] + $persiapankontrak['jml_paket'] + $gagallelang['jml_paket'])), 0, ',', '.'); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($terkontrak['nilai_kontrak'] + ($proseslelang['nilai_kontrak'] + $belumlelang['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'] + $gagallelang['nilai_kontrak']), true, 2); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="tree-mobile">
                                            <div class="border-single-tree-down"></div>
                                            <a class="w-100 mb-2" data-toggle="modal" data-target="#TerkontrakModal">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> TERKONTRAK </b></h4>
                                                        <label><?= number_format($terkontrak['jml_paket'] + $persiapankontrak['jml_paket'], 0, ',', '.'); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($terkontrak['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'], true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="w-100 mb-2" data-toggle="modal" data-target="#ProsesLelangModal">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PROSES LELANG </b></h4>
                                                        <label><?= number_format($proseslelang['jml_paket'], 0, ',', '.'); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($proseslelang['nilai_kontrak'], true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="w-100 mb-2" data-toggle="modal" data-target="#BelumLelangModal">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                                        <label><?= number_format($belumlelang['jml_paket'], 0, ',', '.'); ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="centered-xy bg-yellow">
                        <div class="w-100">
                            <div class="tree">
                                <ul style="padding:0">
                                    <li class="mb-5" style="width: 100%">
                                        <a href="javascript:;" class="w-100">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-1">
                                                    <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                    <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                                    <label><?= number_format($belumlelang['jml_paket'], 0, ',', '.'); ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="border-single-tree-down"></div>
                                        <a class="w-100 mb-2" data-toggle="modal" data-target="#SYCModal">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> SYC </b></h4>
                                                    <label><?= $sub_belum_lelang->count_syc ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($sub_belum_lelang->sum_syc, true, 2); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="w-100 mb-2" data-toggle="modal" data-target="#MYCModal">
                                            <div class="tree-content">
                                                <div class="card card-body bg-tree-2">
                                                    <h4 class="mb-0"><b> MYC BARU </b></h4>
                                                    <label><?= $sub_belum_lelang->count_myc ?> Paket</label>
                                                    <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                        <h5 class="mb-0">
                                                            <?= toMilyar($sub_belum_lelang->sum_myc, true, 2); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Section-->
        </div>
        <!--end::Form-->


        <div class="modal fade" id="RPMModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">RPM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tree">
                            <ul style="padding:0">
                                <li class="mb-5" style="width: 100%">
                                    <a href="javascript:;" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-1">
                                                <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                <h4 class="mb-0"><b> RPM </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_rpm, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="javascript:;" class="w-100 mb-2">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> REALISASI </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_real_rpm, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:;" class="w-100 mb-2">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> BELUM REALISASI </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_rpm-$pagu_all->total_real_rpm, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="SBSNModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">SBSN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tree">
                            <ul style="padding:0">
                                <li class="mb-5" style="width: 100%">
                                    <a href="javascript:;" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-1">
                                                <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                <h4 class="mb-0"><b> SBSN </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_sbsn, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="javascript:;" class="w-100 mb-2">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> REALISASI </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_real_sbsn, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:;" class="w-100 mb-2">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> BELUM REALISASI </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_sbsn-$pagu_all->total_real_sbsn, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="PLNModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">PLN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tree">
                            <ul style="padding:0">
                                <li class="mb-5" style="width: 100%">
                                    <a href="javascript:;" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-1">
                                                <!-- <h6 class="mb-0 tree-dot"><i class="fas fa-circle"></i></h6> -->
                                                <h4 class="mb-0"><b> PLN </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_phln, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="border-single-tree-down"></div>
                                    <a href="javascript:;" class="w-100 mb-2">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> REALISASI </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">                                                        
                                                        <?= toMilyar($pagu_all->total_real_phln, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:;" class="w-100 mb-2">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> BELUM REALISASI </b></h4>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($pagu_all->total_phln-$pagu_all->total_real_phln, true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="KegiatanModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kegiatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-white text-center" style="background-color: #1562aa;">
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">NO</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keg</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Blok</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Real</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kegiatan as $key => $value): ?>
                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                    <td><?= $key+1 ?></td>
                                    <td><?= $value->nmgiat ?></td>
                                    <td><?= toMilyar($value->total_pagu, false, 2) ?></td>
                                    <td><?= toMilyar($value->total_blokir, false, 2) ?></td>
                                    <td><?= toMilyar($value->total_real, false, 2) ?></td>
                                    <td><?= toMilyar($value->total_pagu - $value->total_real, false, 2) ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="TerkontrakModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Terkontrak</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tree">
                            <ul style="padding:0">
                                <li class="" style="width: 100% !important">
                                    <a href="javascript:;" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> TERKONTRAK </b></h4>
                                                <label><?= number_format($terkontrak['jml_paket'] + $persiapankontrak['jml_paket'], 0, ',', '.'); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($terkontrak['nilai_kontrak'] + $persiapankontrak['nilai_kontrak'], true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul style="padding:0">
                                        <li class="" style="width: 50% !important">
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SYC </b></h4>
                                                        <label><?= $sub_terkontrak->count_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <label><?= $sub_terkontrak->count_rpm_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_rpm_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <label><?= $sub_terkontrak->count_sbsn_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_sbsn_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PLN </b></h4>
                                                        <label><?= $sub_terkontrak->count_phln_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_phln_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="" style="width: 50% !important">
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> MYC </b></h4>
                                                        <label><?= $sub_terkontrak->count_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <label><?= $sub_terkontrak->count_rpm_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_rpm_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <label><?= $sub_terkontrak->count_sbsn_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_sbsn_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PLN </b></h4>
                                                        <label><?= $sub_terkontrak->count_phln_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_terkontrak->sum_phln_myc, false, 2); ?> M
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
                </div>
            </div>
        </div>

        <div class="modal fade" id="ProsesLelangModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Proses Lelang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tree">
                            <ul style="padding:0">
                                <li class="" style="width: 100% !important">
                                    <a href="javascript:;" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> PROSES LELANG </b></h4>
                                                <label><?= number_format($proseslelang['jml_paket'], 0, ',', '.'); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($proseslelang['nilai_kontrak'], true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul style="padding:0">
                                        <li class="" style="width: 50% !important">
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SYC </b></h4>
                                                        <label><?= $sub_proses_lelang->count_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <label><?= $sub_proses_lelang->count_rpm_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_rpm_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <label><?= $sub_proses_lelang->count_sbsn_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_sbsn_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PLN </b></h4>
                                                        <label><?= $sub_proses_lelang->count_phln_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_phln_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="" style="width: 50% !important">
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> MYC </b></h4>
                                                        <label><?= $sub_proses_lelang->count_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <label><?= $sub_proses_lelang->count_rpm_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_rpm_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <label><?= $sub_proses_lelang->count_sbsn_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_sbsn_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PLN </b></h4>
                                                        <label><?= $sub_proses_lelang->count_phln_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_proses_lelang->sum_phln_myc, false, 2); ?> M
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
                </div>
            </div>
        </div>

        <div class="modal fade" id="BelumLelangModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Belum Lelang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tree">
                            <ul style="padding:0">
                                <li class="" style="width: 100% !important">
                                    <a href="javascript:;" class="w-100">
                                        <div class="tree-content">
                                            <div class="card card-body bg-tree-2">
                                                <h4 class="mb-0"><b> BELUM LELANG </b></h4>
                                                <label><?= number_format($belumlelang['jml_paket'], 0, ',', '.'); ?> Paket</label>
                                                <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                    <h5 class="mb-0">
                                                        <?= toMilyar($belumlelang['nilai_kontrak'], true, 2); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul style="padding:0">
                                        <li class="" style="width: 50% !important">
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SYC </b></h4>
                                                        <label><?= $sub_belum_lelang->count_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <label><?= $sub_belum_lelang->count_rpm_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_rpm_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <label><?= $sub_belum_lelang->count_sbsn_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_sbsn_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PLN </b></h4>
                                                        <label><?= $sub_belum_lelang->count_phln_syc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_phln_syc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="" style="width: 50% !important">
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> MYC </b></h4>
                                                        <label><?= $sub_belum_lelang->count_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="border-single-tree-down"></div>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> RPM </b></h4>
                                                        <label><?= $sub_belum_lelang->count_rpm_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_rpm_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100 mb-2">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> SBSN </b></h4>
                                                        <label><?= $sub_belum_lelang->count_sbsn_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_sbsn_myc, false, 2); ?> M
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:;" class="w-100">
                                                <div class="tree-content">
                                                    <div class="card card-body bg-tree-2">
                                                        <h4 class="mb-0"><b> PLN </b></h4>
                                                        <label><?= $sub_belum_lelang->count_phln_myc; ?> Paket</label>
                                                        <div class="card card-body p-1 bg-tree-footer bg-secondary text-dark mt-2">
                                                            <h5 class="mb-0">
                                                                <?= toMilyar($sub_belum_lelang->sum_phln_myc, false, 2); ?> M
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
                </div>
            </div>
        </div>

        <div class="modal fade" id="SYCModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">SYC</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="text-white text-center" style="background-color: #1562aa;">
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">No</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Nama Paket</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu Dipa</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu Pengadaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                    $value_total_pagu = 0;
                                    $value_total_real = 0;
                                    foreach ($kegiatan_syc as $key => $value): 
                                ?>
                                    <tr class="text-center text-white" style="background-color: #1562aa;">
                                        <td><?= $key+1 ?></td>
                                        <td><?= $value->nmgiat ?></td>
                                        <td><?= toMilyar($value->total_pagu, false, 2) ?></td>
                                        <td><?= toMilyar($value->total_real, false, 2) ?></td>
                                    </tr>
                                <?php  
                                    $value_total_pagu += $value->total_pagu;
                                    $value_total_real += $value->total_real;
                                    endforeach ;
                                ?>
                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                    <td>11</td>
                                    <td>Total</td>
                                    <td><?= toMilyar($value_total_pagu, false, 2) ?></td>
                                    <td><?= toMilyar($value_total_real, false, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="MYCModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">MYC</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="text-white text-center" style="background-color: #1562aa;">
                                <tr>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">No</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Nama Paket</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu Dipa</th>
                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Pagu Pengadaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                    $value_total_pagu = 0;
                                    $value_total_real = 0;
                                    foreach ($kegiatan_myc as $key => $value): 
                                ?>
                                    <tr class="text-center text-white" style="background-color: #1562aa;">
                                        <td><?= $key+1 ?></td>
                                        <td><?= $value->nmgiat ?></td>
                                        <td><?= toMilyar($value->total_pagu, false, 2) ?></td>
                                        <td><?= toMilyar($value->total_real, false, 2) ?></td>
                                    </tr>
                                <?php  
                                    $value_total_pagu += $value->total_pagu;
                                    $value_total_real += $value->total_real;
                                    endforeach ;
                                ?>
                                <tr class="text-center text-white" style="background-color: #1562aa;">
                                    <td>11</td>
                                    <td>Total</td>
                                    <td><?= toMilyar($value_total_pagu, false, 2) ?></td>
                                    <td><?= toMilyar($value_total_real, false, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




        <!-- Modal gaperlu -->
        <div class="modal fade" id="dashboard9Modal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">PROGRES Keu & FISIK DITJEN SDA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="" id="progres_keuangan_fisik_ditjen_sda">
                            <div class="row">
                                <div class="col-md-6">
                                    <!--begin::Portlet-->
                                    <div class="kt-portlet">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon kt-hidden">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Progres Keu
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body pl-0" style="overflow-x: auto">
                                            <input type="hidden" class="arrayget" value="<?= date("n") ?>">
                                            <div id="line-chart" style="height: 250px;width: 560px;"></div>
                                        </div>
                                    </div>
                                    <!--end::Portlet-->
                                    <!--begin::Portlet-->
                                    <div class="kt-portlet">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon kt-hidden">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    Progres FISIK
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body" style="overflow-x: auto">
                                            <div id="line-chart2" style="height: 250px;width: 560px;"></div>
                                        </div>
                                    </div>
                                    <!--end::Portlet-->
                                </div>
                                <div class="col-md-6">
                                    <!--begin::Portlet-->
                                    <div class="kt-portlet">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon kt-hidden">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    PROGRES PER SUMBER DANA
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body pt-0 pb-0" style="overflow-x: auto">
                                            <div class="chart-container mt-2">
                                                <div id="bar-legend" class="chart-legend"></div>
                                                <div id="persumberdana" style="height: 250px;width: 560px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Portlet-->
                                    <!--begin::Portlet-->
                                    <div class="kt-portlet">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon kt-hidden">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">
                                                    PROGRES PER JENIS BELANJA
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body pt-0" style="overflow-x: auto">
                                            <div id="bar-legend-jenis-belanja" class="chart-legend"></div>
                                            <div id="chatperjenisbelanja" style="height: 250px;width: 560px;"></div>
                                        </div>
                                    </div>
                                    <!--end::Portlet-->
                                </div>
                            </div>
                            <!-- <div class="footer">
                                <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="dashboard10Modal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">PROGRES Keu & FISIK PER KEGIATAN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="kt-portlet kt-portlet--tab" id="progres_keuangan_fisik_per_kegiatan">
                            <div class="kt-portlet__body pt-1">
                                <div class="kt-section">
                                    <div class="chart-container mt-2 mb-5" style="height: 400px; overflow-x: auto">
                                        <div id="bar-legend-perkegiatan" class="chart-legend"></div>
                                        <div id="perkegiatan" class="mychart" style="height: 250px;width: 1100px;"></div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="padding: 0px 4px 0px 4px !important">No</th>
                                                    <th style="padding: 0px 4px 0px 4px !important">Kode Kegiatan</th>
                                                    <th style="padding: 0px 4px 0px 4px !important" style="text-align: center;">Kegiatan</th>
                                                    <th style="padding: 0px 4px 0px 4px !important">Keu %</th>
                                                    <th style="padding: 0px 4px 0px 4px !important">Fisik %</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php foreach ($perkegiatan as $key => $value) {

                                                    if ($value->kdgiat != '-') {
                                                ?>
                                                        <tr>
                                                            <th style="padding: 0px 4px 0px 4px !important" scope="row"><?= ++$key ?></th>
                                                            <td style="padding: 0px 4px 0px 4px !important"> <?= $value->kdgiat; ?></td>
                                                            <td style="padding: 0px 4px 0px 4px !important"> <?= $value->nmgiat; ?></td>
                                                            <td style="padding: 0px 4px 0px 4px !important"> <?= onlyTwoDecimal($value->keu); ?></td>
                                                            <td style="padding: 0px 4px 0px 4px !important"> <?= onlyTwoDecimal($value->fis); ?></td>

                                                        </tr>

                                                <?php  }
                                                } ?>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <!-- <div class="footer">
                                <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="dashboard1Modal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Progres Fisik & Keu Kementrian PUPR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="" id="progres_fisik_keuangan_kementerian_pupr">
                            <div class="">
                                <div class="kt-section mb-0">
                                    <div class="kt-section__content">
                                        <div class="float-left">
                                            <i><b>Status : <?= $rekapunor['total']['status'] ?></b></i>
                                        </div>

                                        <div class="float-right">
                                            <i><b>*Dalam Ribu Rupiah</b></i>
                                        </div>

                                        <table class="table-bordered table-responsive   " style="width: 100%;">
                                            <thead class="text-center text-white" style="background-color: #1562aa;">
                                                <tr>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" rowspan="2">No</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" rowspan="2">Unit Organisasi</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="4">Pagu</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="4">Realisasi</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px" colspan="2">Progres</th>

                                                </tr>
                                                <tr>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">RPM</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">SBSN</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">PLN</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Total</th>

                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">RPM</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">SBSN</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">PLN</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Total</th>

                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Keu</th>
                                                    <th style="padding: 0px 4px 0px 4px !important; font-size: 12px">Fisik</th>

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

                                    </div>
                                    <div class="chart-container mt-5" style="height: 500px; overflow-x: auto;">
                                        <div id="placeholder-bar-chart" class="mychart" style="height: 250px;width: 1200px;"></div>
                                        <div id="bar-legend" class="chart-legend mt-5"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="footer">
                                <img src="<?= base_url('images/footer.jpg'); ?>" class="w-100" alt="">
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>



<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.js'); ?>
<?php echo script_tag('plugins/flot-old/jquery.flot.time.min.js'); ?>


<!-- CHART REKAP REKAP UNOR -->
<?php echo view('Modules\Admin\Views\Dashboard\js\Dashboard'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartRekapUnor'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartProgresKeuFis'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartPersumberDana'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartPerJenisBelanja'); ?>
<?php echo view('Modules\Admin\Views\Dashboard\js\ChartPerkegiatan'); ?>


<script>
    $(document).ready(function() {
        checkDefaultFilterMenu()
        actionFilterMenu()
    })

    $(".button-rpmModal").click(function(e){
        e.preventDefault()
        $("#RPMModal").modal('show')
    })
    $(".button-sbsnModal").click(function(e){
        e.preventDefault()
        $("#SBSNModal").modal('show')
    })
    $(".button-plnModal").click(function(e){
        e.preventDefault()
        $("#PLNModal").modal('show')
    })

    $('input:checkbox[name=filter-menu-all]').change(function() {
        let checked = true
        if (! this.checked) checked = false
        $('input:checkbox[name=filter-menu]').prop('checked', checked)
    })

    $(document).on('click', 'button[name=action-filter-menu]', function() {
        actionFilterMenu(true)

        $('#modalFilterMenu').modal('hide')
    })



    function actionFilterMenu(saveOptions = false) {
        tempSave = []

        $('input:checkbox[name=filter-menu]').each((index, element) => {
            let menuElement = $('#'+$(element).val())
            
            if($(element).is(':checked')) {
                menuElement.removeClass('d-none')
            }
            else {
                menuElement.addClass('d-none')

                if ($(element).data('always-show') != '1') {
                    tempSave.push({
                        'value': $(element).val(),
                        'checked': false
                    })
                }
            }
        })

        if (saveOptions) localStorage.setItem("filter-menu-dashboard", JSON.stringify(tempSave));
    }



    function checkDefaultFilterMenu() {
        JSON.parse(localStorage.getItem("filter-menu-dashboard")).forEach((data, index) => {
            let checkElement = $('input:checkbox[name=filter-menu][value='+data.value+']')
            
            if (checkElement.data('always-show') != '1') checkElement.removeAttr('checked')
        })
    }
</script>


<!-- END CHART REKAP REKAP UNOR -->

<?= $this->endSection() ?>