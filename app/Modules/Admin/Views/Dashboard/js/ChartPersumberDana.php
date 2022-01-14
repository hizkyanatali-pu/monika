<script>
    var d1_1 = [
        <?php
        $index = 1325376000000;
        foreach ($pagu as $key => $value) :

        ?>[
                <?php echo $index ?>,
                <?php echo onlyTwoDecimal($value->progresKeu) ?>
            ],
        <?php
            $index += 2678400000;
        endforeach;
        ?>
    ];

    var d1_2 = [
        <?php
        $index = 1325376000000;
        foreach ($pagu as $key => $value) :

        ?>[
                <?php echo $index ?>,
                <?php echo onlyTwoDecimal($value->progresFis) ?>
            ],
        <?php
            $index += 2678400000;
        endforeach;
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
    var p = $.plot($("div#persumberdana"), data1, {
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
                foreach ($pagu as $key => $value) :
                    $nilai_total_pagu = strlen($value->totalPagu);
                ?>[
                        <?php echo $index ?>,
                        `
		    				<?php echo $value->title ?> 
		    				<div class="title-chart-important">
		    					<a href=""><?= ($nilai_total_pagu >= 17 ? toTriliun($value->totalPagu) : ($nilai_total_pagu >= 14 && $nilai_total_pagu < 17 ? toMilyar($value->totalPagu, false, 2) . " M" : toRupiah($value->totalPagu))) ?>
		    					<span><?php echo toRupiah($value->totalPagu) ?></span>
		    					</a>
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
            tickFormatter: function(val, axis) {
                return '';
            },
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
    });

    $.each(p.getData()[0].data, function(i, el) {
        var o = p.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + '%' + '</div>').css({
            position: 'absolute',
            left: o.left - 40,
            top: o.top - 20,
            display: 'none'
        }).appendTo(p.getPlaceholder()).fadeIn('slow');
    });

    $.each(p.getData()[1].data, function(i, el) {
        var o = p.pointOffset({
            x: el[0],
            y: el[1]
        });
        $('<div class="data-point-label">' + el[1] + '%' + '</div>').css({
            position: 'absolute',
            left: o.left + 20,
            top: o.top - 20,
            display: 'none'
        }).appendTo(p.getPlaceholder()).fadeIn('slow');
    });
</script>