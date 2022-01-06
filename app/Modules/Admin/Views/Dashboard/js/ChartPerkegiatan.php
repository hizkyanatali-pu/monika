<script>
    let data_pagu1 = <?= json_encode($perkegiatan) ?>;
    var d1_1 = [
        <?php
        $index = 1325376000000;
        foreach ($perkegiatan as $key => $value) :
            // if ($value->kdgiat == 2408 or $value->kdgiat ==  5036 or $value->kdgiat ==  5037 or $value->kdgiat == 5039 or $value->kdgiat == 5040 or $value->kdgiat == 5300 or $value->kdgiat == "-") {
        ?>[
                <?php echo $index ?>,
                <?php echo onlyTwoDecimal($value->keu) ?>
            ],
        <?php
            $index += 2678400000;
        // }
        endforeach
        ?>
    ];

    var d1_2 = [
        <?php
        $index = 1325376000000;
        foreach ($perkegiatan as $key => $value) :
            // if ($value->kdgiat == 2408 or $value->kdgiat ==  5036 or $value->kdgiat ==  5037 or $value->kdgiat == 5039 or $value->kdgiat == 5040 or $value->kdgiat == 5300 or $value->kdgiat == "-") {
        ?>[
                <?php echo $index ?>,
                <?php echo onlyTwoDecimal($value->fis) ?>
            ],
        <?php
            $index += 2678400000;
        // }
        endforeach
        ?>
    ];
    var data1 = [{
            label: "KEU",
            data: d1_1,
            bars: {
                show: true,
                barWidth: 12 * 44 * 60 * 60 * 300,
                fill: true,
                lineWidth: 0,
                order: 1,
                align: 'right',
                fillColor: {
                    colors: ["#80C3FD", "#0089FF"]
                }
            },
            color: "#0089FF"
        },
        {
            label: "FISIK",
            data: d1_2,
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
        },

    ];
    var perkegiatan = $.plot($("div#perkegiatan"), data1, {
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
                size: 14,
                color: '#444',
            },
            ticks: [
                <?php
                $index = 1325376000000;
                foreach ($perkegiatan as $key => $value) :
                    // if ($value->kdgiat == 2408 or $value->kdgiat ==  5036 or $value->kdgiat ==  5037 or $value->kdgiat == 5039 or $value->kdgiat == 5040 or $value->kdgiat == 5300 or $value->kdgiat == "-") {
                ?>[
                        <?php echo $index ?>,
                        `
	    					<div style="width: 150px;">
	    						<?php echo $value->kdgiat ?>
	    					</div>
	    				`
                    ],
                <?php
                    $index += 2678400000;
                // }
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
            tickSize: 20,

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
            container: "#bar-legend-perkegiatan"
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
    });

    $.each(perkegiatan.getData()[0].data, function(i, el) {
        var o = perkegiatan.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + "%" + '</div>').css({
            position: 'absolute',
            left: o.left - 45,
            top: o.top - 20,
            display: 'none'
        }).appendTo(perkegiatan.getPlaceholder()).fadeIn('slow');
    });

    $.each(perkegiatan.getData()[1].data, function(i, el) {
        var o = perkegiatan.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + "%" + '</div>').css({
            position: 'absolute',
            left: o.left + 8,
            top: o.top - 20,
            display: 'none'
        }).appendTo(perkegiatan.getPlaceholder()).fadeIn('slow');
    });
</script>