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
            
            <ul class="nav nav-pills nav-justified mb-0">
                <li class="nav-item mr-0 <?php echo ($current=="keuangan"?'bg-primary':'bg-light-primary');?>">
                    <a class="nav-link" href="<?= site_url('grafikdata/keuangan'); ?>"class=""><span class="<?php echo ($current=="keuangan"?'text-light':'');?>">Keuangan</span>
                        <!-- <i class="kt-menu__ver-arrow la la-angle-right"></i> -->
                    </a>
                </li>
                <li class="nav-item mr-0 <?php echo ($current=="fisik"?'bg-primary':'bg-light-primary');?>">
                    <a class="nav-link" href="<?= site_url('grafikdata/fisik'); ?>"class=""><span class="<?php echo ($current=="fisik"?'text-light':'');?>">Fisik</span>
                        <!-- <i class="kt-menu__ver-arrow la la-angle-right"></i> -->
                    </a>
                </li>
            </ul>

            <div class="kt-portlet__body">


                <!--begin::Section-->
                <div class="kt-section">
                    <input type="hidden" class="arrayget" value="<?= date("n") ?>">
                    
                    <div class="card-body">
                        <div id="line-chart" style="height: 300px;"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td width="5%">
                                    <div class="badge text-white" style="font-size:13px; padding:3px; background-color:#0abb87; ">Rencana</div>
                                </td>
                                <?php foreach($qdata['rencana'] as  $v): ?>
    
                                    <?php if ($v[1]) { ?>
                                        <td class="text-right"><?php echo ($v[0]==0?'&nbsp;':$v[1]);?></td>
                                    <?php } ?>
    
                                <?php endforeach; ?>
                            </tr>
    
                            <tr>
                                <td width="5%">
                                    <div class="badge text-white" style="font-size:13px; padding:3px; background-color:#fd397a;">Realisasi</div>
                                </td>
                                <?php foreach($qdata['realisasi'] as  $v): ?>
                                    <?php if ($v[1]) { ?>
                                        <td class="text-right"><?php echo ($v[0]==0?'&nbsp;':$v[1]);?></td>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- <div class="card-body">
                        <div id="line-chart" style="height: 300px;"></div>
                    </div>

                    <table class="table">
                        <tr>
                            <td width="5px">
                                Rencana
                            </td>
                            <?php foreach($qdata['rencana'] as  $v): ?>
                            <td class="text-right"><?php echo ($v[0]==0?'':$v[1]);?></td>
                            <?php endforeach; ?>
                        </tr>

                        <tr>
                            <td width="5px">
                                Realisasi
                            </td>
                            <?php foreach($qdata['realisasi'] as  $v): ?>
                            <td class="text-right"><?php echo ($v[0]==0?'':$v[1]);?></td>
                            <?php endforeach; ?>
                        </tr>
                    </table> -->

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
        color: '#0abb87'
        }
        var line_data2 = {
        data : <?php echo json_encode($qdata['realisasi']);?>,
        color: '#fd397a'
        }
        x = $("input.arrayget").val();
        yfrom = line_data2.data.reverse()[0][1];
        yto = line_data1.data[x][1];
        
        plot = $.plot('#line-chart', [line_data1, line_data2], {
        grid  : {
            hoverable  : true,
            borderColor: '#f3f3f3',
            borderWidth: 1,
            tickColor  : '#f3f3f3',
            markings: [{ lineWidth: 2,  xaxis: { from: x, to: x }, yaxis: { from: yfrom, to: yto}, color: "#00000"}],
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

        // $('<div class="data-point-label">' + el[1] + '%</div>').css( {
        //     position: 'absolute',
        //     left: o.left - 25,
        //     top: o.top - 20,
        //     display: 'none'
        // }).appendTo(p.getPlaceholder()).fadeIn('slow');

        //Initialize tooltip on hover
        $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
        position: 'absolute',
        display: 'none',
        border: '2px solid #4572A7',
        padding: '5px',     
        size: '10',   
        'background-color': '#fff',
        opacity: 0.80
        }).appendTo('body');

        var o = plot.pointOffset({ x: 1, y: parseFloat(yto)+20});
        var orealisasi = plot.pointOffset({ x:  x, y: parseFloat(yfrom)});
        var orencana = plot.pointOffset({ x:  x, y: parseFloat(yto)});

        //deviasi
        let var_date = new Date();
        let this_year = var_date.getFullYear();
        let this_month = var_date.getMonth();
        let this_day = var_date.getDate();
        let days = new Date(this_year, this_month, 0).getDate();
        let deviasi = (yfrom - yto)/days*this_day;

        $('#line-chart').append("<div class='badge badge-secondary text-center shadow' style='position:absolute; left:" + (o.left + 4) + "px; top:" + o.top + "px;'><h3 class='mb-0'><b>Deviasi</b> " + (deviasi).toFixed(2) + "%</h3></div>");

        $('#line-chart').append("<div class='badge badge-success ml-2' style='position:absolute;left:" + (orencana.left) + "px;top:" + (orencana.top) + "px;'><h3 class='mb-0'>" + yto + "%</h3></div>");
        $('#line-chart').append("<div class='badge badge-danger ml-2' style='position:absolute;left:" + (orealisasi.left) + "px;top:" + (orealisasi.top) + "px;'><h3 class='mb-0'>" + yfrom + "%</h3></div>");
      


        $('#line-chart').bind('plothover', function (event, pos, item) {

        var bulan = <?php echo json_encode($qdata['bln']);?>;

        if (item) {
            // var x = item.datapoint[0].toFixed(2),
            //     y = item.datapoint[1].toFixed(2)

            var x = item.datapoint[0],
                y = item.datapoint[1].toFixed(2)
            //$('#line-chart-tooltip').html(item.series.label + ' of ' + x + ' = ' + y)
            //$('#line-chart-tooltip').html(bulan[(parseInt(x)-1)][1] + ' of ' + x + ' = ' + y + '%')
            $('#line-chart-tooltip').html(bulan[(parseInt(x))][1] + ' : ' + y + '%')
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