<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

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
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid"">
    <div class="kt-portlet">
        <div class="kt-portlet__body" style="padding:0px;">

            <!--begin::Section-->
            <div class="kt-section  pb-4 pt-3">

                <div class="kt-section__content">

                    <div class="kt-portlet__body">

                        <table class="table w-100 table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center bg-success" colspan="6">
                                        DITJEN SDA
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center bg-secondary"></th>
                                    <th class="text-center bg-secondary">Pagu</th>
                                    <th class="text-center bg-secondary">Realisasi</th>
                                    <th class="text-center bg-secondary">Total Sisa Pagu</th>
                                    <th class="text-center bg-secondary">Sisa Pagu Terserap</th>
                                    <th class="text-center bg-warning" style="border-left: 3px solid #C90000; border-top: 3px solid #C90000; border-right: 3px solid #C90000;">Sisa Pagu Tidak Terserap</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center" style="border-left: 3px solid #C90000; border-right: 3px solid #C90000;">52.692,60</td>
                                </tr>
                                <tr>
                                    <td>RPM</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center" style="border-left: 3px solid #C90000; border-right: 3px solid #C90000;">52.692,60</td>
                                </tr>
                                <tr>
                                    <td>SBSN</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center" style="border-left: 3px solid #C90000; border-right: 3px solid #C90000;">52.692,60</td>
                                </tr>
                                <tr>
                                    <td>PHLN</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center">52.692,60</td>
                                    <td class="text-center" style="border-left: 3px solid #C90000; border-right: 3px solid #C90000; border-bottom: 3px solid #C90000;">52.692,60</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="tree ml--60 pr-4">
                            <ul class="dana-tidak-terserap">
                                <li class="w-100">
                                    <ul>
                                        <li class="" style="width: 25% !important">
                                            <table class="table table-bordered mb-0" style="">
                                                <thead>
                                                    <tr style="background-color: #f5b184; ">
                                                        <th colspan="2" style="border: 2px solid #c88a5b;"><h5 class="mb-0"><b> SISA LELANG </b></h5></th>
                                                    </tr>
                                                    <tr style="background-color: #facaac;">
                                                        <th style="border: 2px solid #c88a5b;"><b> Total </b></th>
                                                        <th style="border: 2px solid #c88a5b;"><b> 1.080,90 </b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: 2px solid #c88a5b;">RPM</td>
                                                        <td style="border: 2px solid #c88a5b;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #c88a5b;">SBSN</td>
                                                        <td style="border: 2px solid #c88a5b;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #c88a5b;">PHLN</td>
                                                        <td style="border: 2px solid #c88a5b;"><b> 579,02 </b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </li>
                                        
                                        <li class="" style="width: 25% !important">
                                            <table class="table table-bordered mb-0" style="">
                                                <thead>
                                                    <tr style="background-color: #f3f3f3; ">
                                                        <th colspan="2" style="border: 2px solid #e5e5e5;"><h5 class="mb-0"><b> PAKET DITUNDA/DIDROP </b></h5></th>
                                                    </tr>
                                                    <tr style="background-color: #f1f1f1;">
                                                        <th style="border: 2px solid #e5e5e5;"><b> Total </b></th>
                                                        <th style="border: 2px solid #e5e5e5;"><b> 1.080,90 </b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: 2px solid #e5e5e5;">RPM</td>
                                                        <td style="border: 2px solid #e5e5e5;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #e5e5e5;">SBSN</td>
                                                        <td style="border: 2px solid #e5e5e5;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #e5e5e5;">PHLN</td>
                                                        <td style="border: 2px solid #e5e5e5;"><b> 579,02 </b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <small class="text-left">
                                                Catatan* : <br>
                                                Masih terdapat Dana Drop Food Estate Kalteng yang akan direalokasi ke Bencana NTB&NTT(sedang revisi DIPA).
                                            </small>
                                        </li>
    
                                        <li class="" style="width: 25% !important">
                                            <table class="table table-bordered mb-0" style="">
                                                <thead>
                                                    <tr style="background-color: #1ab7d5; ">
                                                        <th colspan="2" style="border: 2px solid #74ACB9;"><h5 class="mb-0"><b> SISA SWAKELOLA </b></h5></th>
                                                    </tr>
                                                    <tr style="background-color: #7DC5D3;">
                                                        <th style="border: 2px solid #74ACB9;"><b> Total </b></th>
                                                        <th style="border: 2px solid #74ACB9;"><b> 1.080,90 </b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: 2px solid #74ACB9;">RPM</td>
                                                        <td style="border: 2px solid #74ACB9;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #74ACB9;">SBSN</td>
                                                        <td style="border: 2px solid #74ACB9;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #74ACB9;">PHLN</td>
                                                        <td style="border: 2px solid #74ACB9;"><b> 579,02 </b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <small class="text-left">
                                                **Catatan : <br>
                                                Masih terdapat Dana PEN Sungai yang akan direalokasi ke Bendungan (sedang revisi DIPA) dan SIsa Tanah untuk Tambahan Eskalasi dan Dana Bencana
                                            </small>
                                        </li>
                                        
                                        <li class="" style="width: 25% !important">
                                            <table class="table table-bordered mb-0" style="">
                                                <thead>
                                                    <tr style="background-color: #C1C1C1; ">
                                                        <th colspan="2" style="border: 2px solid #7E7E7E;"><h5 class="mb-0"><b> POTENSI MYC TIDAK TERSERAP </b></h5></th>
                                                    </tr>
                                                    <tr style="background-color: #D9D9D9;">
                                                        <th style="border: 2px solid #7E7E7E;"><b> Total </b></th>
                                                        <th style="border: 2px solid #7E7E7E;"><b> 1.080,90 </b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: 2px solid #7E7E7E;">RPM</td>
                                                        <td style="border: 2px solid #7E7E7E;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #7E7E7E;">SBSN</td>
                                                        <td style="border: 2px solid #7E7E7E;"><b> 579,02 </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: 2px solid #7E7E7E;">PHLN</td>
                                                        <td style="border: 2px solid #7E7E7E;"><b> 579,02 </b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </li>
    
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