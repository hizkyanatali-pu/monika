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
        <div class=" kt-portlet">
	<div class="kt-portlet__body">
		<!--begin::Section-->
		<div class="kt-section">
			<div class="card-body pt-5" style="height: 450px;">
				<div class="chart-container mt-2">
					<div class="form-group">
						<!-- <label for=""></label> -->
						<select id="choices" class="form-control col-1">
							<option value="0" selected>RPM</option>
							<option value="1">SBSN</option>
							<option value="2">PHLN</option>
						</select>
					</div>
					<div id="bar-legend" class="chart-legend">
					</div>
					<div id="placeholder-bar-chart" class="mychart" style="width:100%;height:100%;"></div>
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
<script>
	$(document).ready(function() {
		let data_pagu = <?= json_encode($pagu) ?>;
		var d1_1 = [];
		let index = 1325376000000;
		let choices = 0;

		$.each(data_pagu, function(key, val) {
			if (key == choices) {
				d1_1.push([index, val.progresKeu.toFixed(2)])
				index += 2678400000;
			}
		})

		var d1_2 = [];
		let index2 = 1325376000000;

		$.each(data_pagu, function(key, val) {
			if (key == choices) {
				d1_2.push([index2, val.progresFis.toFixed(2)])
				index2 += 2678400000;
			}
		})

		let index_ticks = 1325376000000;
		let ticks = [];

		$.each(data_pagu, function(key, val) {
			if (key == choices) {
				nilai_total_pagu = val.totalPagu;
				console.log(nilai_total_pagu)
				ticks.push(index_ticks,
					val.title +
					`<div class="title-chart-important">
					<a href="#">` +

					(nilai_total_pagu.length >= 17 ? (nilai_total_pagu / 1000000000000).toFixed(2) + " T" : (nilai_total_pagu.length >= 14 && nilai_total_pagu.length < 17 ? (nilai_total_pagu / 1000000000).toFixed(2) + " M" : nilai_total_pagu.toFixed(2))) + `
			
					<span>RP. ` + val.totalPagu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + `</span>
					</a>
				</div>`
				)
				index += 2678400000;
			}
		})

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

		graph(data1, ticks)
		$("#choices").change(function() {

			choices = $(this).val()

			var d1_1 = [];
			let index = 1325376000000;

			$.each(data_pagu, function(key, val) {
				if (key == choices) {
					d1_1.push([index, val.progresKeu.toFixed(2)])
					index += 2678400000;
				}
			})

			var d1_2 = [];
			let index2 = 1325376000000;

			$.each(data_pagu, function(key, val) {
				if (key == choices) {
					d1_2.push([index2, val.progresFis.toFixed(2)])
					index2 += 2678400000;
				}
			})

			let ticks = [];
			let index_ticks = 1325376000000;

			$.each(data_pagu, function(key, val) {
				if (key == choices) {
					nilai_total_pagu = val.totalPagu;
					ticks.push(index_ticks,
						val.title +
						`<div class="title-chart-important">
						<a href="#">` + (nilai_total_pagu.length >= 17 ? (nilai_total_pagu / 1000000000000).toFixed(2) + " T" : (nilai_total_pagu.length >= 14 && nilai_total_pagu.length < 17 ? (nilai_total_pagu / 1000000000).toFixed(2) + " M" : nilai_total_pagu.toFixed(2))) + `
						<span>RP. ` + val.totalPagu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + `</span>
						</a>
					</div>`
					)
					index += 2678400000;
				}
			})

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
			graph(data1, ticks)
		})

		function graph(data, choices = '') {
			index = 1325376000000;
			var p = $.plot($("#placeholder-bar-chart"), data, {
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
						choices
					]
				},
				yaxis: {
					//if want to yaxis set to 0/ none (backup)
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
			});
			$.each(p.getData()[0].data, function(i, el) {
				var o = p.pointOffset({
					x: el[0],
					y: el[1]
				});
				$('<div class="data-point-label">' + el[1] + " %" + '</div>').css({
					position: 'absolute',
					left: o.left - 250,
					top: o.top - 20,
					display: 'none'
				}).appendTo(p.getPlaceholder()).fadeIn('slow');
			});

			$.each(p.getData()[1].data, function(i, el) {
				var o = p.pointOffset({
					x: el[0],
					y: el[1]
				});
				$('<div class="data-point-label">' + el[1] + " %" + '</div>').css({
					position: 'absolute',
					left: o.left + 250,
					top: o.top - 20,
					display: 'none'
				}).appendTo(p.getPlaceholder()).fadeIn('slow');
			});
		}

	});
</script>
<?= $this->endSection() ?>


<!--https://github.com/cleroux/flot.barlabels -->