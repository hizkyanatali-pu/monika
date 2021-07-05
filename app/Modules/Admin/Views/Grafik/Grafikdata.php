<?= $this->extend('admin/layouts/grafik') ?>
<?= $this->section('content') ?>

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
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid"">
        <div class="kt-portlet">
            <div class="kt-portlet__body" style="padding:0px;">

                <!--begin::Section-->
                <div class="kt-section">

                    <div class="kt-section__content">
                        <div class="table-responsive tableFixHead">
                            <div class="col-md-12">
                            <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                                <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
                                    <ul class="kt-menu__nav ">
                                        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo ($current=="keuangan"?'kt-menu__item--active  bg-primary':'');?>">
                                            <a href="<?= site_url('grafikdata/keuangan'); ?>"
                                                class="kt-menu__link"><span class="kt-menu__link-text <?php echo ($current=="keuangan"?'text-light':'');?>">Keuangan</span>
                                                <i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        </li>
                                        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo ($current=="fisik"?'kt-menu__item--active  bg-primary':'');?>">
                                            <a href="<?= site_url('grafikdata/fisik'); ?>"
                                                class="kt-menu__link"><span class="kt-menu__link-text <?php echo ($current=="fisik"?'text-light':'');?>">Fisik</span>
                                                <i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                    <tr>
                                        <td width="5%">&nbsp;</td>
                                        <td colspan="13">
                                            <div class="card-body">
                                                <div id="line-chart" style="height: 300px;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-12" style="padding:3px; background-color:#a6a6a6; ">Rencana</div>
                                        </td>
                                        <?php foreach($qdata['rencana'] as  $v): ?>
                                        <td class="text-right"><?php echo ($v[0]==0?'&nbsp;':$v[1]);?></td>
                                        <?php endforeach; ?>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="col-md-12" style="padding:3px; background-color:#0066ff;">Realisasi</div>
                                        </td>
                                        <?php foreach($qdata['realisasi'] as  $v): ?>
                                        <td class="text-right"><?php echo ($v[0]==0?'&nbsp;':$v[1]);?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
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
    </script>
    <script>
        // var sin = [],
        //     cos = []
        // for (var i = 1; i <= 12; i += 1) {
        // sin.push([i, Math.sin(i)])
        // cos.push([i, Math.cos(i)])
        // }
        var line_data1 = {
        data : <?php echo json_encode($qdata['rencana']);?>,
        color: '#a6a6a6'
        }
        var line_data2 = {
        data : <?php echo json_encode($qdata['realisasi']);?>,
        color: '#0066ff'
        }
        $.plot('#line-chart', [line_data1, line_data2], {
        grid  : {
            hoverable  : true,
            borderColor: '#f3f3f3',
            borderWidth: 1,
            tickColor  : '#f3f3f3'
        },
        series: {
            shadowSize: 0,
            lines     : {
            show: true
            },
            points    : {
            show: true
            }
        },
        lines : {
            fill : true,
            color: ['#3c8dbc', '#f56954']
        },
        yaxis : {
            show: true
        },
        xaxis : {
            //show: true
            ticks: <?php echo json_encode($qdata['bln']);?>
        }
        })
        //Initialize tooltip on hover
        $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
        position: 'absolute',
        display : 'none',
        opacity : 0.8
        }).appendTo('body')
        $('#line-chart').bind('plothover', function (event, pos, item) {

        var bulan = <?php echo json_encode($qdata['bln']);?>;

        if (item) {
            // var x = item.datapoint[0].toFixed(2),
            //     y = item.datapoint[1].toFixed(2)

            var x = item.datapoint[0],
                y = item.datapoint[1].toFixed(2)
            //$('#line-chart-tooltip').html(item.series.label + ' of ' + x + ' = ' + y)
            //$('#line-chart-tooltip').html(bulan[(parseInt(x)-1)][1] + ' of ' + x + ' = ' + y + '%')
            $('#line-chart-tooltip').html(bulan[(parseInt(x)-1)][1] + ' : ' + y + '%')
            .css({
                top : item.pageY + 5,
                left: item.pageX + 5
            })
            .fadeIn(200)
        } else {
            $('#line-chart-tooltip').hide()
        }

        })
        /* END LINE CHART */
    </script>
<?= $this->endSection() ?>