<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Dashboard</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
						<li class="breadcrumb-item active">Dashboard</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
			<div class="row">
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= $perkara_diterima ?></h3>
							<p>Perkara Diterima</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<a href="<?= site_url('masuk') ?>" class="small-box-footer">Info lengkap <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?= $perkara_putus ?></h3>
							<p>Perkara Putus</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="<?= site_url('putus') ?>" class="small-box-footer">Info lengkap <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= $perkara_minutasi ?></h3>
							<p>Perkara Minutasi</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="#" class="small-box-footer">Info lengkap <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-danger">
						<div class="inner">
							<h3><?= $perkara_sisa ?></h3>
							<p>Perkara Sisa</p>
						</div>
						<div class="icon">
							<i class="ion ion-pie-graph"></i>
						</div>
						<a href="<?= site_url('sisa_bulan_ini') ?>" class="small-box-footer">Info lengkap <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
			</div>
			<!-- /.row -->

			<!-- Chart Row -->
			<div class="row">
				<div class="col-md-12">
					<!-- Chart Card -->
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-chart-line mr-1"></i>
								Statistik Perkara Tahun <?= $year ?>
							</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body">
							<!-- Chart container with fixed height -->
							<div style="height: 300px; position: relative;">
								<canvas id="mainChart" style="height: 300px;"></canvas>
								<!-- Fallback message if chart fails to load -->
								<div id="chartError" class="text-center" style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
									<i class="fas fa-exclamation-circle text-warning fa-3x"></i>
									<h5 class="mt-2">Gagal memuat grafik</h5>
									<p>Silakan refresh halaman atau periksa konsol untuk detail.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Summary Row -->
			<div class="row">
				<!-- Left col -->
				<section class="col-lg-7 connectedSortable">
					<!-- Case Type Card -->
					<div class="card direct-chat direct-chat-primary">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-balance-scale mr-1"></i> Distribusi Jenis Perkara</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-8">
									<div style="height: 250px;">
										<canvas id="pieChart" style="height: 250px;"></canvas>
									</div>
								</div>
								<div class="col-md-4">
									<div class="chart-legend">
										<?php foreach ($case_types as $index => $type):
											$colors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'];
											$color = isset($colors[$index]) ? $colors[$index] : '#' . substr(md5($type->jenis_perkara_nama), 0, 6);
										?>
											<div class="mt-2">
												<i class="fas fa-square" style="color: <?= $color ?>"></i>
												<?= $type->jenis_perkara_nama ?>: <?= $type->count ?>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>

				<!-- Right col -->
				<section class="col-lg-5 connectedSortable">
					<!-- Yearly Summary -->
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Ringkasan Tahunan</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body p-0">
							<table class="table table-striped">
								<tr>
									<th>Bulan</th>
									<th class="text-center">Diterima</th>
									<th class="text-center">Putus</th>
									<th class="text-center">Minutasi</th>
								</tr>
								<?php
								$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
								for ($i = 0; $i < 12; $i++):
								?>
									<tr>
										<td><?= $months[$i] ?></td>
										<td class="text-center"><?= isset($monthly_stats['received'][$i]) ? $monthly_stats['received'][$i] : 0 ?></td>
										<td class="text-center"><?= isset($monthly_stats['decided'][$i]) ? $monthly_stats['decided'][$i] : 0 ?></td>
										<td class="text-center"><?= isset($monthly_stats['minutasi'][$i]) ? $monthly_stats['minutasi'][$i] : 0 ?></td>
									</tr>
								<?php endfor; ?>
								<tr class="bg-light font-weight-bold">
									<td>Total</td>
									<td class="text-center"><?= array_sum($monthly_stats['received']) ?></td>
									<td class="text-center"><?= array_sum($monthly_stats['decided']) ?></td>
									<td class="text-center"><?= array_sum($monthly_stats['minutasi']) ?></td>
								</tr>
							</table>
						</div>
					</div>
				</section>
			</div>
			<!-- /.row -->
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Directly try to initialize the chart without dependencies
		try {
			// Check if Chart object exists
			if (typeof Chart === 'undefined') {
				console.error('Chart.js is not loaded!');
				document.getElementById('chartError').style.display = 'block';
				return;
			}

			console.log('Initializing charts with Chart.js version:', Chart.version);

			// LINE CHART - Monthly statistics
			var ctx = document.getElementById('mainChart').getContext('2d');
			if (ctx) {
				new Chart(ctx, {
					type: 'line',
					data: {
						labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
						datasets: [{
								label: 'Diterima',
								borderColor: '#007bff',
								backgroundColor: 'rgba(0, 123, 255, 0.1)',
								data: <?= json_encode($monthly_stats['received']) ?>,
								borderWidth: 2,
								fill: true,
								pointRadius: 3
							},
							{
								label: 'Putus',
								borderColor: '#28a745',
								backgroundColor: 'rgba(40, 167, 69, 0.1)',
								data: <?= json_encode($monthly_stats['decided']) ?>,
								borderWidth: 2,
								fill: true,
								pointRadius: 3
							},
							{
								label: 'Minutasi',
								borderColor: '#ffc107',
								backgroundColor: 'rgba(255, 193, 7, 0.1)',
								data: <?= json_encode($monthly_stats['minutasi']) ?>,
								borderWidth: 2,
								fill: true,
								pointRadius: 3
							}
						]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend: {
							display: true,
							position: 'top'
						},
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true,
									precision: 0
								},
								gridLines: {
									color: 'rgba(0,0,0,0.05)'
								}
							}],
							xAxes: [{
								gridLines: {
									color: 'rgba(0,0,0,0.05)'
								}
							}]
						},
						tooltips: {
							mode: 'index',
							intersect: false
						},
						animation: {
							duration: 1000
						}
					}
				});
				console.log('Main chart initialized');
			} else {
				console.error('Main chart canvas not found');
			}

			// PIE CHART - Case types
			var ctxPie = document.getElementById('pieChart').getContext('2d');
			if (ctxPie) {
				// Prepare data from case_types
				var pieLabels = [];
				var pieData = [];
				var pieColors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'];

				<?php foreach ($case_types as $index => $type): ?>
					pieLabels.push('<?= $type->jenis_perkara_nama ?>');
					pieData.push(<?= $type->count ?>);
				<?php endforeach; ?>

				new Chart(ctxPie, {
					type: 'pie',
					data: {
						labels: pieLabels,
						datasets: [{
							data: pieData,
							backgroundColor: pieColors.slice(0, pieData.length)
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend: {
							display: false
						}
					}
				});
				console.log('Pie chart initialized');
			} else {
				console.error('Pie chart canvas not found');
			}

		} catch (error) {
			console.error('Error initializing charts:', error);
			document.getElementById('chartError').style.display = 'block';
		}
	});
</script>
</body>

</html>