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
            <div class="kt-portlet__body">
                <!--begin::Section-->
                <div class="kt-section">
                    <div class="card-body pt-5">
                        <div class="chart-container mt-2" style="height: 500px">
                        	<div id="bar-legend" class="chart-legend"></div>
						  <div id="placeholder-bar-chart" class="mychart"></div>
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
	<?php echo script_tag('plugins/flot-old/jquery.flot.js'); ?>
	<?php echo script_tag('plugins/flot-old/jquery.flot.time.min.js'); ?>
	<?php echo script_tag('plugins/flot-old/jquery.flot.orderBars.js'); ?>

    <script>
		var d1_1 = [
		  [1325376000000, 10],
		  [1328054400000, 20],
		  [1330560000000, 30]
		];

		var d1_2 = [
		  [1325376000000, 80],
		  [1328054400000, 60],
		  [1330560000000, 20]
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
		var p = $.plot($("#placeholder-bar-chart"), data1, {
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
			font:{
			    size: 14,
			    color: '#444',
			},
		    ticks: [
		      [1325376000000, '<p style="width: 170px">Pengembangan dan Rehabilitasi Jaringan Irigasi Permukaan, Rawa dan Non-Padi</p>'],
		      [1328054400000, '<p style="width: 170px">Pengendalian Banjir, Lahar, Pengelolaan Drainase Utama Perkotaan, dan Pengaman Pantai Pembangunan Bendungan, Danau, dan Bangunan Penampung Air Lainnya</p>'],
		      [1330560000000, '<p style="width: 170px">Penyediaan dan Pengelolaan Air Tanah dan Air Baku</p>']
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
		  $('<div class="data-point-label">' + el[1] + '</div>').css({
		    position: 'absolute',
		    left: o.left - 95,
		    top: o.top - 20,
		    display: 'none'
		  }).appendTo(p.getPlaceholder()).fadeIn('slow');
		});

		$.each(p.getData()[1].data, function(i, el) {
		  var o = p.pointOffset({
		    x: el[0],
		    y: el[1]
		  });
		  $('<div class="data-point-label">' + el[1] + '</div>').css({
		    position: 'absolute',
		    left: o.left + 4,
		    top: o.top - 20,
		    display: 'none'
		  }).appendTo(p.getPlaceholder()).fadeIn('slow');
		});
    </script>
<?= $this->endSection() ?>


<!--https://github.com/cleroux/flot.barlabels -->