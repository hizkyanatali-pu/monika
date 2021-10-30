<script>
    let data_pagu = <?= json_encode($rekapunor) ?>;


    var d1_1 = [];
    var d1_2 = [];
    var d1_kd_unit = [];


    index = 1325376000000;
    for (let i = 0; i < data_pagu.length; i++) {


        d1_1[i] = parseFloat(data_pagu[i]['progres_keu'])
        d1_2[i] = parseFloat(data_pagu[i]['progres_fisik'])
        d1_kd_unit[i] = [data_pagu[i]['kdunit']]


    }

    var data1 = [];
    inc = 10;
    inc2 = 0;


    for (var i = 0; i < d1_1.length; i++) {

        if (d1_kd_unit[i][0] == 06) {
            data1[i] = {
                label: "KEU",
                data: [
                    [index, d1_1[i]]
                ],
                bars: {
                    show: true,
                    barWidth: 12 * 44 * 60 * 60 * 300,
                    fill: true,
                    lineWidth: 0,
                    order: 1,
                    align: 'right',
                    fillColor: {
                        colors: ["#80C3FD", "#0089FF"]
                    },
                },
                color: "#0089FF"
            }

            data1[inc] = {
                label: "FISIK",
                data: [
                    [index, d1_2[i]]
                ],
                bars: {
                    show: true,
                    barWidth: 12 * 44 * 60 * 60 * 300,
                    fill: true,
                    lineWidth: 0,
                    order: 2,
                    align: 'left',
                    fillColor: {
                        colors: ["#ffd45e", "#fcba03"]
                    }
                },
                color: "#fcba03"
            }

        } else {

            data1[i] = {
                data: [
                    [index, d1_1[i]]
                ],
                bars: {
                    show: true,
                    barWidth: 12 * 44 * 60 * 60 * 300,
                    fill: true,
                    lineWidth: 0,
                    order: 1,
                    align: 'right',

                },
                color: "#0089FF"
            }

            data1[inc] = {
                data: [
                    [index, d1_2[i]]
                ],
                bars: {
                    show: true,
                    barWidth: 12 * 44 * 60 * 60 * 300,
                    fill: true,
                    lineWidth: 0,
                    order: 2,
                    align: 'left',
                },
                color: "#fcba03"
            }

        }
        index += 2678400000;
        inc++

    }



    var option = {
        xaxis: {
            mode: "time",
            timeformat: "%b",
            tickSize: [1, "month"],
            //monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            tickLength: 0, // hide gridlines
            axisLabel: 'Month',
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            axisLabelPadding: 5,
            font: {
                size: 12,
                color: '#444',
            },
            ticks: [
                <?php
                $index = 1325376000000;
                foreach ($rekapunor as $key => $value) :

                ?>[
                        <?php echo $index ?>,
                        `
	    					<div style="width: 140px;">
	    						<?php echo $value['nmsingkat'] ?>
	    					</div>
	    				`
                    ],
                <?php
                    $index += 2678400000;
                endforeach
                ?>
            ]
        },
        yaxis: {
            // if want to yaxis set to 0/ none (backup)
            // tickFormatter: function(val, axis) {
            //   return '';
            // },
            min: 0,
            max: 100,
        },
        grid: {
            hoverable: true,
            clickable: false,
            borderWidth: 0,
            borderColor: '#f0f0f0',
            labelMargin: 8,
        },
        series: {
            shadowSize: 1,

        },
        legend: {
            show: true,
            noColumns: 5,
            container: "#bar-legend"
        },
        tooltip: true,
        tooltipOpts: {
            id: "chart-tooltip",
            content: "<p><b>20</b> Outgoing Filings</p>" +
                "<p>Out of <b>10</b> committed;</p>" +
                "<br />" +
                "<p><b>30%</b>% Ratio</p>",
            shifts: {
                x: -74,
                y: -125
            },
            lines: {
                track: true
            },
            compat: true,
        },
    }


    var p = $.plot($("#placeholder-bar-chart"), data1, option);


    $.each(p.getData(), function(i, el) {

        var o = p.pointOffset({
            x: el.data[0][0],
            y: el.data[0][1]
        });

        if (i < 10) {
            $('<div class="data-point-label">' + el.data[0][1] + '</div>').css({
                position: 'absolute',
                left: o.left - 38,
                top: o.top - 20,
                display: 'none'
            }).appendTo(p.getPlaceholder()).fadeIn('slow');


        } else {

            $('<div class="data-point-label">' + el.data[0][1] + '</div>').css({
                position: 'absolute',
                left: o.left + 4,
                top: o.top - 20,
                display: 'none'
            }).appendTo(p.getPlaceholder()).fadeIn('slow');
        }
    });

    // $.each(p.getData(), function(i, el) {
    //     var o = p.pointOffset({
    //         x: el.data[0][0],
    //         y: el.data[0][1]
    //     });
    //     $('<div class="data-point-label">' + el.data[0][1] + '</div>').css({
    //         position: 'absolute',
    //         left: o.left + 4,
    //         top: o.top - 20,
    //         display: 'none'
    //     }).appendTo(p.getPlaceholder()).fadeIn('slow');
    // });



    // var series = p.getData();
    // for (var i = 0; i < series.length; ++i)
    //     // alert(series[i].color);

    // console.log()

    // p.getData()[0].bars.fillColor = '';
    // p.draw();
    // console.log(p.getData()[0].xaxis.ticks[3].v)
</script>