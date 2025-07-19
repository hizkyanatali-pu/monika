<script>
    var line_data1 = {
        data: <?php echo json_encode($keu['rencana']); ?>,
        color: '#0abb87'
    }
    var line_data2 = {
        data: <?php echo json_encode($keu['realisasi']); ?>,
        color: '#fd397a'
    }

    x = $("input.arrayget").val();
    yfrom = line_data2.data.reverse()[0][1];
    yto = line_data1.data[x][1];

    //mark new variables
    //bagian untuk marking pada grafik
    let x2 = 0,
        yto2 = 0;
    let tooltip_date = new Date();
    let this_year_tooltip = tooltip_date.getFullYear();
    let this_month_tooltip = tooltip_date.getMonth() + 1;
    let this_day_tooltip = tooltip_date.getDate();
    let days_tooltip = new Date(this_year_tooltip, this_month_tooltip, 0).getDate();
    if ((days_tooltip - this_day_tooltip) != 0) {

        x2 = line_data2.data[0][0];
        yto2 = line_data1.data[x][1];
    }

    plot = $.plot('div#line-chart', [line_data1, line_data2], {
        grid: {
            hoverable: true,
            borderColor: '#f3f3f3',
            borderWidth: 1,
            tickColor: '#f3f3f3',
            markings: [{
                lineWidth: 2,
                xaxis: {
                    from: x2,
                    to: x2
                },
                yaxis: {
                    from: yfrom,
                    to: yto2
                },
                color: "#00000"
            }],
        },
        series: {
            shadowSize: 0,
            lines: {
                show: true
            },
            points: {
                show: true
            }
        },
        lines: {
            fill: false,
            color: ['#3c8dbc', '#f56954']
        },
        yaxis: {
            show: true,
            labelWidth: 25,
        },
        xaxis: {
            //show: true
            ticks: <?php echo json_encode($keu['bln']); ?>
        }
    })

    var o = plot.pointOffset({
        x: 1,
        y: parseFloat(yto2) + 20
    });
    var orealisasi = plot.pointOffset({
        x: x2,
        y: parseFloat(yfrom)
    });
    var orencana = plot.pointOffset({
        x: x2,
        y: parseFloat(yto2)
    });

    //deviasi
    //bagian untuk badge deviasi
    let deviasi = yfrom - yto;

    $('div#line-chart').append("<div class='badge badge-secondary text-center shadow' style='position:absolute; left:" + (o.left + 4) + "px; top:" + 10 + "%;'><h3 class='mb-0'><b>Deviasi</b> " + (deviasi).toFixed(2) + "%</h3></div>");

    // $('div#line-chart').append("<div class='badge badge-success ml-3' style='position:absolute;left:" + (orencana.left) + "px;top:" + (orencana.top) + "px;'><h3 class='mb-0'>" + yto2 + "%</h3></div>");
    // $('div#line-chart').append("<div class='badge badge-danger ml-3 mt-4' style='position:absolute;left:" + (orealisasi.left) + "px;top:" + (orealisasi.top) + "px;'><h3 class='mb-0'>" + yfrom + "%</h3></div>");

    $('div#line-chart').append("<div class='badge badge-success ml-3' style='position:absolute;left:85%;top:55%;'><h5 class='mb-0'>" + yto2 + "%</h5></div>");
    $('div#line-chart').append("<div class='badge badge-danger ml-3 mt-4' style='position:absolute;left:85%;top:65%;'><h5 class='mb-0'>" + yfrom + "%</h5></div>");




    // $('div#line-chart').bind('plothover', function(event, pos, item) {

    $.each(plot.getData()[0].data, function(i, el) {


        var bulan = <?php echo json_encode($keu['bln']); ?>;

        var o = plot.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + "%" + '</div>').css({
            position: 'absolute',
            left: o.left - 4,
            top: o.top - 30,
            display: 'none',
            fontSize: 9
        }).appendTo(plot.getPlaceholder()).fadeIn('slow');

    })

    $.each(plot.getData()[1].data, function(i, el) {


        var bulan = <?php echo json_encode($keu['bln']); ?>;

        var o = plot.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + "%" + '</div>').css({
            position: 'absolute',
            left: o.left - 4,
            top: o.top + 15,
            display: 'none',
            fontSize: 9
        }).appendTo(plot.getPlaceholder()).fadeIn('slow');

    })

    /* END LINE CHART */




    var line_data1_1 = {
        data: <?php echo json_encode($fis['rencana']); ?>,
        color: '#0abb87'
    }
    var line_data2_1 = {
        data: <?php echo json_encode($fis['realisasi']); ?>,
        color: '#fd397a'
    }

    x_1 = $("input.arrayget").val();
    yfrom_1 = line_data2_1.data.reverse()[0][1];
    yto_1 = line_data1_1.data[x_1][1];

    //mark new variables
    //bagian untuk marking pada grafik
    let x2_1 = 0,
        yto2_1 = 0;
    let tooltip_date_1 = new Date();
    let this_year_tooltip_1 = tooltip_date.getFullYear();
    let this_month_tooltip_1 = tooltip_date.getMonth() + 1;
    let this_day_tooltip_1 = tooltip_date.getDate();
    let days_tooltip_1 = new Date(this_year_tooltip_1, this_month_tooltip_1, 0).getDate();
    if ((days_tooltip_1 - this_day_tooltip_1) != 0) {

        x2_1 = line_data2_1.data[0][0];
        yto2_1 = line_data1_1.data[x_1][1];
    }

    plot_1 = $.plot('div#line-chart2', [line_data1_1, line_data2_1], {
        grid: {
            hoverable: true,
            borderColor: '#f3f3f3',
            borderWidth: 1,
            tickColor: '#f3f3f3',
            markings: [{
                lineWidth: 2,
                xaxis: {
                    from: x2_1,
                    to: x2_1
                },
                yaxis: {
                    from: yfrom_1,
                    to: yto2_1
                },
                color: "#00000"
            }],
        },
        series: {
            shadowSize: 0,
            lines: {
                show: true
            },
            points: {
                show: true
            }
        },
        lines: {
            fill: false,
            color: ['#3c8dbc', '#f56954']
        },
        yaxis: {
            show: true,
            labelWidth: 25,
        },
        xaxis: {
            //show: true
            ticks: <?php echo json_encode($fis['bln']); ?>
        }
    })

    var o2 = plot.pointOffset({
        x: 1,
        y: parseFloat(yto2_1) + 20
    });
    var orealisasi2 = plot.pointOffset({
        x: x2_1,
        y: parseFloat(yfrom_1)
    });
    var orencana2 = plot.pointOffset({
        x: x2_1,
        y: parseFloat(yto2_1)
    });

    //deviasi
    //bagian untuk badge deviasi
    let deviasi1 = yfrom_1 - yto_1;

    $('div#line-chart2').append("<div class='badge badge-secondary text-center shadow' style='position:absolute; left:" + (o2.left + 4) + "px; top:" + 10 + "%;'><h3 class='mb-0'><b>Deviasi</b> " + (deviasi1).toFixed(2) + "%</h3></div>");

    // $('div#line-chart2').append("<div class='badge badge-success ml-3' style='position:absolute;left:" + (orencana2.left) + "px;top:" + (orencana2.top) + "px;'><h3 class='mb-0'>" + yto_1 + "%</h3></div>");
    // $('div#line-chart2').append("<div class='badge badge-danger ml-3 mt-4' style='position:absolute;left:" + (orealisasi2.left) + "px;top:" + (orealisasi2.top) + "px;'><h3 class='mb-0'>" + yfrom_1 + "%</h3></div>");

    $('div#line-chart2').append("<div class='badge badge-success ml-3' style='position:absolute;left:85%;top:55%;'><h5 class='mb-0'>" + yto_1 + "%</h5></div>");
    $('div#line-chart2').append("<div class='badge badge-danger ml-3 mt-4' style='position:absolute;left:85%;top:65%;'><h5 class='mb-0'>" + yfrom_1 + "%</h5></div>");




    // $('div#line-chart').bind('plothover', function(event, pos, item) {

    $.each(plot_1.getData()[0].data, function(i, el) {


        var bulan = <?php echo json_encode($fis['bln']); ?>;

        var o = plot_1.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + "%" + '</div>').css({
            position: 'absolute',
            left: o.left - 4,
            top: o.top - 30,
            display: 'none',
            fontSize: 9
        }).appendTo(plot_1.getPlaceholder()).fadeIn('slow');

    })

    $.each(plot_1.getData()[1].data, function(i, el) {


        var bulan = <?php echo json_encode($fis['bln']); ?>;

        var o = plot_1.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + "%" + '</div>').css({
            position: 'absolute',
            left: o.left - 4,
            top: o.top + 15,
            display: 'none',
            fontSize: 9
        }).appendTo(plot_1.getPlaceholder()).fadeIn('slow');

    })

    /* END LINE CHART */
</script>