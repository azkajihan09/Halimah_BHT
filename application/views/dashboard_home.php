<!-- Custom CSS for Dashboard Home -->
<style>
	/* Sidebar Fix for Dashboard Home */
	.main-sidebar {
		position: fixed;
		top: 0;
		left: 0;
		height: 100vh;
		z-index: 1040;
		transition: margin-left 0.3s ease-in-out;
	}

	.content-wrapper {
		margin-left: 250px;
		transition: margin-left 0.3s ease-in-out;
	}

	/* When sidebar is collapsed */
	.sidebar-collapse .main-sidebar {
		margin-left: -250px;
	}

	.sidebar-collapse .content-wrapper {
		margin-left: 0;
	}

	/* Mobile responsiveness */
	@media (max-width: 767.98px) {
		.main-sidebar {
			margin-left: -250px;
		}

		.content-wrapper {
			margin-left: 0;
		}

		.sidebar-open .main-sidebar {
			margin-left: 0;
			z-index: 1045;
		}

		/* Mobile overlay */
		.sidebar-open::before {
			content: '';
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.5);
			z-index: 1044;
		}
	}

	/* Ensure pushmenu button works */
	[data-widget="pushmenu"] {
		cursor: pointer;
	}

	/* AdminLTE Standard Dashboard Styling */
	.content-wrapper {
		background-color: #f4f4f4;
		min-height: calc(100vh - 50px);
		overflow-y: auto;
	}

	.content-header {
		padding: 15px;
		margin: 0;
	}

	.content-header h1 {
		margin: 0;
		font-size: 24px;
	}

	.breadcrumb {
		background: transparent;
		padding: 8px 0;
		margin: 0;
	}

	/* AdminLTE Standard Small Boxes */
	.small-box {
		border-radius: 2px;
		position: relative;
		display: block;
		margin-bottom: 20px;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
	}

	.small-box .inner {
		padding: 10px 15px;
	}

	.small-box .inner h3 {
		font-size: 38px;
		font-weight: bold;
		margin: 0 0 10px 0;
		white-space: nowrap;
		padding: 0;
	}

	.small-box .inner p {
		font-size: 15px;
	}

	.small-box .icon {
		position: absolute;
		top: auto;
		bottom: 10px;
		right: 10px;
		z-index: 0;
		font-size: 90px;
		color: rgba(0, 0, 0, 0.15);
	}

	.small-box .small-box-footer {
		position: relative;
		text-align: center;
		padding: 3px 0;
		color: #fff;
		color: rgba(255, 255, 255, 0.8);
		display: block;
		z-index: 10;
		background: rgba(0, 0, 0, 0.1);
		text-decoration: none;
	}

	.small-box .small-box-footer:hover {
		color: #fff;
		background: rgba(0, 0, 0, 0.15);
	}

	/* AdminLTE Standard Cards */
	.card,
	.box {
		position: relative;
		border-radius: 3px;
		background: #ffffff;
		border-top: 3px solid #d2d6de;
		margin-bottom: 20px;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
	}

	.card-header,
	.box-header {
		border-bottom: 1px solid #f4f4f4;
		color: #444;
		display: block;
		padding: 10px 15px;
		position: relative;
	}

	.card-title,
	.box-title {
		font-size: 18px;
		margin: 0 0 0 0;
		line-height: 1.8;
	}

	.card-body,
	.box-body {
		padding: 10px;
	}

	/* Chart Containers */
	.chart-container {
		height: 350px;
		position: relative;
		background: #fafbfc;
		border-radius: 10px;
		padding: 15px;
	}

	.chart-container-small {
		height: 250px;
		position: relative;
		background: #fafbfc;
		border-radius: 10px;
		padding: 10px;
	}

	/* AdminLTE Standard Content */
	.content {
		min-height: calc(100vh - 100px);
		padding: 15px;
	}

	.container-fluid {
		padding-right: 15px;
		padding-left: 15px;
		margin-right: auto;
		margin-left: auto;
	}

	/* Counter Animation */
	.counter {
		display: inline-block;
	}

	/* AdminLTE Statistics Cards */
	.stats-card {
		background: white;
		border-radius: 3px;
		padding: 15px;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
		text-align: center;
		margin-bottom: 20px;
		border-top: 3px solid #3c8dbc;
	}

	.stats-card .stats-icon {
		font-size: 40px;
		margin-bottom: 15px;
		color: #3c8dbc;
	}

	.stats-card .stats-number {
		font-size: 28px;
		font-weight: bold;
		color: #333;
		margin-bottom: 5px;
	}

	.stats-card .stats-label {
		font-size: 14px;
		color: #666;
	}

	/* Performance Cards */
	.performance-card {
		background: #f4f4f4;
		border: 1px solid #ddd;
		border-radius: 3px;
		padding: 15px;
		margin: 10px 0;
	}

	.performance-card .judge-name {
		font-size: 16px;
		font-weight: 600;
		margin-bottom: 10px;
	}

	.performance-card .performance-stats {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.performance-card .completion-rate {
		font-size: 24px;
		font-weight: 700;
	}

	/* Recent Activity List */
	.activity-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.activity-list li {
		padding: 15px 0;
		border-bottom: 1px solid #eee;
		display: flex;
		align-items: center;
		transition: all 0.3s ease;
	}

	.activity-list li:hover {
		background: #f8f9fa;
		transform: translateX(5px);
	}

	.activity-list li:last-child {
		border-bottom: none;
	}

	.activity-icon {
		width: 40px;
		height: 40px;
		border-radius: 50%;
		background: linear-gradient(135deg, #6aea66ff 0%, #4ba26fff 100%);
		display: flex;
		align-items: center;
		justify-content: center;
		color: white;
		margin-right: 15px;
		font-size: 16px;
	}

	.activity-content {
		flex: 1;
	}

	.activity-title {
		font-weight: 600;
		color: #333;
		margin-bottom: 5px;
	}

	.activity-meta {
		font-size: 12px;
		color: #666;
	}

	/* Filter Section */
	.filter-section {
		background: white;
		border-radius: 15px;
		padding: 20px;
		margin-bottom: 25px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
	}

	.form-control {
		border-radius: 8px;
		border: 2px solid #e9ecef;
		transition: all 0.3s ease;
	}

	.form-control:focus {
		border-color: #667eea;
		box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
	}

	.btn-modern {
		border-radius: 8px;
		font-weight: 600;
		padding: 10px 20px;
		transition: all 0.3s ease;
	}

	.btn-primary {
		background: linear-gradient(135deg, #0faf1cff 0%, #4ba28c21 100%);
		border: none;
	}

	.btn-primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
	}

	.btn-success {
		background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
		border: none;
	}

	.btn-success:hover {
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
	}

	/* Responsive Design */
	@media (max-width: 768px) {
		.content-wrapper {
			min-height: 100vh;
		}

		.content-header {
			padding: 10px 15px;
		}

		.content {
			padding: 10px 0;
		}

		.small-box .inner h3 {
			font-size: 28px;
		}

		.chart-container {
			height: 250px;
		}

		.col-lg-3 {
			margin-bottom: 15px;
		}
	}

	/* Scrollbar styling for better UX */
	.content-wrapper::-webkit-scrollbar {
		width: 8px;
	}

	.content-wrapper::-webkit-scrollbar-track {
		background: #f1f1f1;
	}

	.content-wrapper::-webkit-scrollbar-thumb {
		background: #888;
		border-radius: 4px;
	}

	.content-wrapper::-webkit-scrollbar-thumb:hover {
		background: #555;
	}

	/* Loading Spinner */
	.loading-spinner {
		display: inline-block;
		width: 20px;
		height: 20px;
		border: 3px solid rgba(102, 126, 234, 0.3);
		border-radius: 50%;
		border-top-color: #66eaa8ff;
		animation: spin 1s ease-in-out infinite;
	}

	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Dashboard Home</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
						<li class="breadcrumb-item active">Dashboard Home</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<section class="content">
		<div class="container-fluid">
			<!-- Filter Section -->
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-filter"></i> Filter Dashboard
							</h3>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>Tahun:</label>
										<select id="filter_tahun" class="form-control">
											<?php foreach ($available_years as $avail_year): ?>
												<option value="<?php echo $avail_year; ?>" <?php echo ($avail_year == $year) ? 'selected' : ''; ?>><?php echo $avail_year; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Filter Tahun Minimum:</label>
										<select id="tahun_filter" class="form-control">
											<option value="">Semua Tahun</option>
											<option value="2024" <?php echo ($tahun_filter == '2024') ? 'selected' : ''; ?>>2024 ke atas</option>
											<option value="2023">2023 ke atas</option>
											<option value="2022">2022 ke atas</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>&nbsp;</label>
										<button id="btn_update" type="button" class="btn btn-primary btn-block">
											<i class="fa fa-refresh"></i> Update Data
										</button>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>&nbsp;</label>
										<button id="btn_export" type="button" class="btn btn-success btn-block">
											<i class="fa fa-download"></i> Export Excel
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Main Statistics -->
			<div class="row">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3 id="perkara_diterima"><?php echo $perkara_diterima; ?></h3>
							<p>Perkara Diterima</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<a href="#" class="small-box-footer">
							Info lengkap <i class="fas fa-arrow-circle-right"></i>
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3 id="perkara_putus"><?php echo $perkara_putus; ?></h3>
							<p>Perkara Putus</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="#" class="small-box-footer">
							Info lengkap <i class="fas fa-arrow-circle-right"></i>
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3 id="perkara_minutasi"><?php echo $perkara_minutasi; ?></h3>
							<p>Perkara Minutasi</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="#" class="small-box-footer">
							Info lengkap <i class="fas fa-arrow-circle-right"></i>
						</a>
					</div>
				</div>

				<div class="col-lg-3 col-6">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3 id="perkara_sisa"><?php echo $perkara_sisa; ?></h3>
							<p>Perkara Sisa</p>
						</div>
						<div class="icon">
							<i class="ion ion-pie-graph"></i>
						</div>
						<a href="#" class="small-box-footer">
							Info lengkap <i class="fas fa-arrow-circle-right"></i>
						</a>
					</div>
				</div>
			</div>

			<!-- Additional Statistics -->
			<div class="row">
				<div class="col-md-3">
					<div class="stats-card">
						<div class="stats-icon">
							<i class="fa fa-user-tie"></i>
						</div>
						<div class="stats-number" id="total_hakim"><?php echo $total_hakim; ?></div>
						<div class="stats-label">Total Hakim</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-card">
						<div class="stats-icon">
							<i class="fa fa-calendar-day"></i>
						</div>
						<div class="stats-number" id="perkara_hari_ini"><?php echo $perkara_hari_ini; ?></div>
						<div class="stats-label">Perkara Hari Ini</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-card">
						<div class="stats-icon">
							<i class="fa fa-calendar-alt"></i>
						</div>
						<div class="stats-number" id="perkara_bulan_ini"><?php echo $perkara_bulan_ini; ?></div>
						<div class="stats-label">Perkara Bulan Ini</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-card">
						<div class="stats-icon">
							<i class="fa fa-percentage"></i>
						</div>
						<div class="stats-number" id="tingkat_penyelesaian"><?php echo $tingkat_penyelesaian; ?>%</div>
						<div class="stats-label">Tingkat Penyelesaian</div>
					</div>
				</div>
			</div>

			<!-- Charts Row -->
			<div class="row">
				<!-- Monthly Trend Chart -->
				<div class="col-md-8">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-chart-line"></i> Trend Bulanan Perkara</h3>
							<div class="box-tools pull-right">
								<span class="label label-primary">Tahun <?php echo $year; ?></span>
							</div>
						</div>
						<div class="box-body">
							<div class="chart-container">
								<canvas id="monthlyChart"></canvas>
							</div>
						</div>
					</div>
				</div>

				<!-- Case Types Chart -->
				<div class="col-md-4">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-pie-chart"></i> Distribusi Jenis Perkara</h3>
						</div>
						<div class="box-body">
							<div class="chart-container-small">
								<canvas id="caseTypesChart"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Performance and Activities Row -->
			<div class="row">
				<!-- Judge Performance -->
				<div class="col-md-6">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-users"></i> Kinerja Hakim</h3>
						</div>
						<div class="box-body" id="judge_performance">
							<?php if (!empty($hakim_performance)): ?>
								<?php foreach ($hakim_performance as $hakim): ?>
									<div class="performance-card">
										<div class="judge-name"><?php echo $hakim->hakim_nama; ?></div>
										<div class="performance-stats">
											<div>
												<small>Total: <?php echo $hakim->total_perkara; ?> | Putus: <?php echo $hakim->perkara_putus; ?></small>
											</div>
											<div class="completion-rate"><?php echo $hakim->completion_rate; ?>%</div>
										</div>
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<p class="text-center text-muted">Tidak ada data kinerja hakim</p>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Recent Activities -->
				<div class="col-md-6">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title"><i class="fa fa-clock"></i> Aktivitas Terbaru</h3>
						</div>
						<div class="box-body">
							<ul class="activity-list" id="recent_activities">
								<?php if (!empty($recent_cases)): ?>
									<?php foreach ($recent_cases as $case): ?>
										<li>
											<div class="activity-icon">
												<i class="fa fa-file"></i>
											</div>
											<div class="activity-content">
												<div class="activity-title"><?php echo $case->nomor_perkara; ?></div>
												<div class="activity-meta">
													<?php echo $case->jenis_perkara_nama; ?> |
													<?php echo date('d M Y', strtotime($case->tanggal_pendaftaran)); ?> |
													Hakim: <?php echo $case->hakim_nama; ?>
												</div>
											</div>
										</li>
									<?php endforeach; ?>
								<?php else: ?>
									<li class="text-center text-muted">Tidak ada aktivitas terbaru</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
	$(document).ready(function() {
		// Initialize charts
		var monthlyChart, caseTypesChart;

		// Month names
		var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
			'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
		];

		// Initialize charts with data
		initializeCharts();

		// Update button click
		$('#btn_update').click(function() {
			updateDashboard();
		});

		// Export button click
		$('#btn_export').click(function() {
			var tahun = $('#filter_tahun').val();
			var tahun_filter = $('#tahun_filter').val();
			window.location.href = '<?php echo base_url("dashboard_home/export_summary"); ?>?year=' + tahun + '&tahun_filter=' + tahun_filter;
		});

		function initializeCharts() {
			// Monthly Chart
			var ctx1 = document.getElementById('monthlyChart').getContext('2d');
			monthlyChart = new Chart(ctx1, {
				type: 'line',
				data: {
					labels: monthNames,
					datasets: [{
						label: 'Perkara Diterima',
						data: <?php echo json_encode($monthly_stats['received']); ?>,
						borderColor: '#17a2b8',
						backgroundColor: 'rgba(23, 162, 184, 0.1)',
						borderWidth: 3,
						fill: true,
						tension: 0.4
					}, {
						label: 'Perkara Putus',
						data: <?php echo json_encode($monthly_stats['decided']); ?>,
						borderColor: '#28a745',
						backgroundColor: 'rgba(40, 167, 69, 0.1)',
						borderWidth: 3,
						fill: true,
						tension: 0.4
					}, {
						label: 'Perkara Minutasi',
						data: <?php echo json_encode($monthly_stats['minutasi']); ?>,
						borderColor: '#ffc107',
						backgroundColor: 'rgba(255, 193, 7, 0.1)',
						borderWidth: 3,
						fill: true,
						tension: 0.4
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							position: 'top',
						}
					},
					scales: {
						y: {
							beginAtZero: true,
							grid: {
								color: 'rgba(0,0,0,0.1)'
							}
						},
						x: {
							grid: {
								color: 'rgba(0,0,0,0.1)'
							}
						}
					}
				}
			});

			// Case Types Chart
			var ctx2 = document.getElementById('caseTypesChart').getContext('2d');
			var caseTypeLabels = [];
			var caseTypeData = [];
			var caseTypeColors = ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40', '#ff6384', '#c9cbcf'];

			<?php foreach ($case_types as $index => $type): ?>
				caseTypeLabels.push('<?php echo $type->jenis_perkara_nama; ?>');
				caseTypeData.push(<?php echo $type->count; ?>);
			<?php endforeach; ?>

			caseTypesChart = new Chart(ctx2, {
				type: 'doughnut',
				data: {
					labels: caseTypeLabels,
					datasets: [{
						data: caseTypeData,
						backgroundColor: caseTypeColors.slice(0, caseTypeData.length),
						borderWidth: 2,
						borderColor: '#fff'
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							position: 'bottom',
							labels: {
								padding: 20,
								usePointStyle: true
							}
						}
					}
				}
			});
		}

		function updateDashboard() {
			var tahun = $('#filter_tahun').val();
			var tahun_filter = $('#tahun_filter').val();

			// Show loading state
			showLoading();

			// Update statistics
			$.ajax({
				url: '<?php echo base_url("dashboard_home/ajax_update_stats"); ?>',
				method: 'POST',
				data: {
					year: tahun,
					tahun_filter: tahun_filter
				},
				dataType: 'json',
				success: function(data) {
					updateStatistics(data);
				},
				error: function() {
					console.error('Error updating statistics');
				}
			});

			// Update charts
			$.ajax({
				url: '<?php echo base_url("dashboard_home/ajax_chart_data"); ?>',
				method: 'POST',
				data: {
					year: tahun,
					tahun_filter: tahun_filter
				},
				dataType: 'json',
				success: function(data) {
					updateCharts(data);
				},
				error: function() {
					console.error('Error updating charts');
				}
			});
		}

		function showLoading() {
			$('#perkara_diterima, #perkara_putus, #perkara_minutasi, #perkara_sisa').html('<div class="loading-spinner"></div>');
		}

		function updateStatistics(data) {
			// Update main stats with animation
			animateCounter('#perkara_diterima', data.perkara_diterima);
			animateCounter('#perkara_putus', data.perkara_putus);
			animateCounter('#perkara_minutasi', data.perkara_minutasi);
			animateCounter('#perkara_sisa', data.perkara_sisa);

			// Update additional stats
			animateCounter('#perkara_hari_ini', data.perkara_hari_ini);
			animateCounter('#perkara_bulan_ini', data.perkara_bulan_ini);
			$('#tingkat_penyelesaian').text(data.case_completion_rate + '%');
		}

		function animateCounter(selector, endValue) {
			var $element = $(selector);
			$({
				counter: 0
			}).animate({
				counter: endValue
			}, {
				duration: 1500,
				easing: 'swing',
				step: function() {
					$element.text(Math.ceil(this.counter));
				},
				complete: function() {
					$element.text(endValue);
				}
			});
		}

		function updateCharts(data) {
			// Update monthly chart
			if (monthlyChart) {
				monthlyChart.data.datasets[0].data = data.monthly_stats.received;
				monthlyChart.data.datasets[1].data = data.monthly_stats.decided;
				monthlyChart.data.datasets[2].data = data.monthly_stats.minutasi;
				monthlyChart.update();
			}

			// Update case types chart
			if (caseTypesChart && data.case_types) {
				var labels = [];
				var chartData = [];
				data.case_types.forEach(function(item) {
					labels.push(item.jenis_perkara_nama);
					chartData.push(item.count);
				});

				caseTypesChart.data.labels = labels;
				caseTypesChart.data.datasets[0].data = chartData;
				caseTypesChart.update();
			}
		}

		// Auto refresh every 10 minutes
		setInterval(function() {
			updateDashboard();
		}, 600000);
	});
</script>

<!-- Additional Chart.js if not loaded in header -->
<script>
	// Ensure Chart.js is loaded
	if (typeof Chart === 'undefined') {
		console.log('Loading Chart.js...');
		var script = document.createElement('script');
		script.src = '<?php echo base_url('assets/plugins/Chart.js/Chart.min.js'); ?>';
		script.onload = function() {
			initializeCharts();
		};
		document.head.appendChild(script);
	}

	// Ensure sidebar functionality works
	$(document).ready(function() {
		console.log('Dashboard Home: jQuery loaded', typeof $ !== 'undefined');
		console.log('Dashboard Home: AdminLTE loaded', typeof AdminLTE !== 'undefined');
		console.log('Dashboard Home: Body classes', $('body').attr('class'));

		// Force AdminLTE initialization for sidebar
		if (typeof AdminLTE !== 'undefined') {
			AdminLTE.init();
			console.log('Dashboard Home: AdminLTE initialized');
		} else {
			console.error('Dashboard Home: AdminLTE not loaded!');
		}

		// Enable pushmenu functionality
		$('[data-widget="pushmenu"]').on('click', function(e) {
			e.preventDefault();
			console.log('Dashboard Home: Manual pushmenu clicked');

			// For mobile, use sidebar-open instead of sidebar-collapse
			if ($(window).width() <= 767) {
				$('body').toggleClass('sidebar-open');
			} else {
				$('body').toggleClass('sidebar-collapse');
			}

			// Store sidebar state
			if ($('body').hasClass('sidebar-collapse') || !$('body').hasClass('sidebar-open')) {
				localStorage.setItem('sidebar-collapsed', 'true');
			} else {
				localStorage.setItem('sidebar-collapsed', 'false');
			}
		});

		// Restore sidebar state
		if (localStorage.getItem('sidebar-collapsed') === 'true') {
			$('body').addClass('sidebar-collapse');
		}

		// Enable treeview functionality
		$('.nav-sidebar .nav-item > .nav-link').on('click', function(e) {
			var $this = $(this);
			var $parent = $this.parent();

			if ($parent.hasClass('nav-item') && $this.siblings('.nav-treeview').length > 0) {
				e.preventDefault();

				if ($parent.hasClass('menu-open')) {
					$parent.removeClass('menu-open');
					$this.siblings('.nav-treeview').slideUp();
				} else {
					$parent.addClass('menu-open');
					$this.siblings('.nav-treeview').slideDown();

					// Close other open menus
					$parent.siblings('.menu-open').removeClass('menu-open').children('.nav-treeview').slideUp();
				}
			}
		});

		// Set active menu
		var currentUrl = window.location.href;
		$('.nav-sidebar a').each(function() {
			if ($(this).attr('href') === currentUrl) {
				$(this).addClass('active');
				$(this).parents('.nav-item').addClass('menu-open');
			}
		});

		// Additional debugging
		console.log('Dashboard Home: Pushmenu button count', $('[data-widget="pushmenu"]').length);
		console.log('Dashboard Home: Sidebar element count', $('.main-sidebar').length);
		console.log('Dashboard Home: Nav items count', $('.nav-sidebar .nav-item').length);

		// Force sidebar functionality even if AdminLTE fails
		setTimeout(function() {
			if (!$('[data-widget="pushmenu"]').hasClass('adminlte-initialized')) {
				console.log('Dashboard Home: Manually initializing sidebar');

				$('[data-widget="pushmenu"]').off('click.pushmenu').on('click.pushmenu', function(e) {
					e.preventDefault();
					console.log('Dashboard Home: Sidebar toggle clicked');
					$('body').toggleClass('sidebar-collapse');
				});

				$('[data-widget="pushmenu"]').addClass('adminlte-initialized');
			}
		}, 1000);

		// Mobile overlay click to close sidebar
		$(document).on('click', function(e) {
			if ($(window).width() <= 767 && $('body').hasClass('sidebar-open')) {
				if (!$(e.target).closest('.main-sidebar').length && !$(e.target).is('[data-widget="pushmenu"]')) {
					$('body').removeClass('sidebar-open');
				}
			}
		});
	});
</script>
