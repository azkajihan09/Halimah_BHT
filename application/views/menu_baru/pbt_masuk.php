<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0"><?= $title ?></h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Home</a></li>
						<li class="breadcrumb-item active"><?= $title ?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">

			<!-- Filter Row -->
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-filter"></i> Filter Data
							</h3>
						</div>
						<div class="card-body">
							<form method="GET" class="form-inline">
								<div class="form-group mr-3">
									<label for="tanggal" class="mr-2">Tanggal PBT:</label>
									<input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggal ?>">
								</div>
								<div class="form-group mr-3">
									<label for="status" class="mr-2">Status BHT:</label>
									<select class="form-control" id="status" name="status">
										<option value="semua" <?= $status == 'semua' ? 'selected' : '' ?>>Semua Status</option>
										<option value="sudah_bht" <?= $status == 'sudah_bht' ? 'selected' : '' ?>>Sudah BHT</option>
										<option value="belum_bht" <?= $status == 'belum_bht' ? 'selected' : '' ?>>Belum BHT</option>
									</select>
								</div>
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-search"></i> Filter
								</button>
								<a href="<?= site_url('Menu_baru/export_excel/pbt_masuk') ?>?tanggal=<?= $tanggal ?>&status=<?= $status ?>" class="btn btn-success ml-2">
									<i class="fas fa-file-excel"></i> Export Excel
								</a>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Penjelasan Aturan 14 Hari -->
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="alert alert-info">
						<h5><i class="fas fa-info-circle"></i> <strong>Aturan 14 Hari Kalender PBT-BHT</strong></h5>
						<p class="mb-2">Berdasarkan ketentuan hukum acara perdata di Pengadilan Agama:</p>
						<ul class="mb-2">
							<li><strong>Kasus Umum (Waris, Wasiat, Hibah, Isbat Nikah):</strong> 14 hari kalender dari PBT → BHT</li>
							<li><strong>Cerai Gugat (Diajukan Istri):</strong> 14 hari kalender dari PBT → BHT → Akta Cerai</li>
							<li><strong>Cerai Talak (Diajukan Suami):</strong> 14 hari → BHT Izin Talak → Ikrar Talak (max 6 bulan) → BHT Final</li>
							<li>Perhitungan menggunakan <strong>hari kalender</strong>, bukan hari kerja</li>
							<li>Jika hari ke-14 jatuh pada hari libur, diperpanjang sampai hari kerja berikutnya</li>
						</ul>
						<div class="row">
							<div class="col-md-4"><span class="badge badge-success mr-2"></span><strong>1-10 Hari:</strong> Normal/Cepat</div>
							<div class="col-md-4"><span class="badge badge-warning mr-2"></span><strong>11-14 Hari:</strong> Sesuai Aturan</div>
							<div class="col-md-4"><span class="badge badge-danger mr-2"></span><strong>> 14 Hari:</strong> Perlu Perhatian</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Statistics Row -->
			<div class="row">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-primary">
						<div class="inner">
							<h3><?= $total_pbt ?></h3>
							<p>Total PBT Masuk</p>
						</div>
						<div class="icon">
							<i class="fas fa-calendar-check"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?= $statistik_pbt->sudah_bht ?></h3>
							<p>Sudah BHT</p>
						</div>
						<div class="icon">
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= $statistik_pbt->belum_bht ?></h3>
							<p>Belum BHT</p>
						</div>
						<div class="icon">
							<i class="fas fa-clock"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= $statistik_pbt->total_pbt > 0 ? round(($statistik_pbt->sudah_bht / $statistik_pbt->total_pbt) * 100, 1) : 0 ?>%</h3>
							<p>Persentase Selesai</p>
						</div>
						<div class="icon">
							<i class="fas fa-chart-pie"></i>
						</div>
					</div>
				</div>
			</div>

			<!-- Data Table -->
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-list"></i> Daftar PBT Masuk Tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>
							</h3>
						</div>
						<div class="card-body">
							<?php if (empty($pbt_masuk)): ?>
								<div class="alert alert-info">
									<i class="fas fa-info-circle"></i> Tidak ada PBT masuk pada tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>
									<?= $status != 'semua' ? 'dengan status ' . str_replace('_', ' ', strtoupper($status)) : '' ?>
								</div>
							<?php else: ?>
								<div class="table-responsive">
									<table class="table table-bordered table-striped" id="pbt-table">
										<thead>
											<tr>
												<th width="5%">No</th>
												<th width="20%">Nomor Perkara</th>
												<th width="20%">Jenis Perkara</th>
												<th width="12%">Tanggal Putus</th>
												<th width="12%">Tanggal PBT</th>
												<th width="12%">Tanggal BHT</th>
												<th width="8%">Selisih Hari</th>
												<th width="15%">Target BHT</th>
												<th width="11%">Status BHT</th>
											</tr>
										</thead>
										<tbody>
											<?php $no = 1;
											foreach ($pbt_masuk as $pbt): ?>
												<tr>
													<td><?= $no++ ?></td>
													<td><?= htmlspecialchars($pbt->nomor_perkara) ?></td>
													<td><?= htmlspecialchars($pbt->jenis_perkara) ?></td>
													<td><?= $pbt->tanggal_putusan ? date('d/m/Y', strtotime($pbt->tanggal_putusan)) : '-' ?></td>
													<td><?= $pbt->tanggal_pbt ? date('d/m/Y', strtotime($pbt->tanggal_pbt)) : '-' ?></td>
													<td><?= $pbt->tanggal_bht ? date('d/m/Y', strtotime($pbt->tanggal_bht)) : '-' ?></td>
													<td class="text-center">
														<?php if ($pbt->selisih_hari !== null): ?>
															<span class="badge <?= $pbt->selisih_hari > 14 ? 'badge-danger' : ($pbt->selisih_hari > 10 ? 'badge-warning' : 'badge-success') ?>">
																<?= $pbt->selisih_hari ?> hari
															</span>
														<?php else: ?>
															<span class="badge badge-secondary">-</span>
														<?php endif; ?>
													</td>
													<td class="small">
														<?php if (isset($pbt->target_bht_info)): ?>
															<?= $pbt->target_bht_info ?>
														<?php else: ?>
															<?= date('d/m/Y', strtotime($pbt->tanggal_pbt . ' +14 days')) ?>
														<?php endif; ?>
													</td>
													<td>
														<?php if ($pbt->status_bht == 'Sudah BHT'): ?>
															<span class="badge badge-success">
																<i class="fas fa-check"></i> <?= $pbt->status_bht ?>
															</span>
														<?php else: ?>
															<span class="badge badge-warning">
																<i class="fas fa-clock"></i> <?= $pbt->status_bht ?>
															</span>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<!-- Chart Row -->
			<div class="row">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-chart-pie"></i> Status BHT
							</h3>
						</div>
						<div class="card-body">
							<canvas id="bhtStatusChart" width="400" height="200"></canvas>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-chart-bar"></i> Analisis Waktu Proses
							</h3>
						</div>
						<div class="card-body">
							<div class="progress-group">
								<span class="progress-text">Proses 1-7 Hari (Normal)</span>
								<span class="float-right"><b><?= array_reduce($pbt_masuk, function ($carry, $item) {
																	return $carry + (($item->selisih_hari >= 1 && $item->selisih_hari <= 7) ? 1 : 0);
																}, 0) ?></b>/<?= count($pbt_masuk) ?></span>
								<div class="progress progress-sm">
									<div class="progress-bar bg-success" style="width: <?= count($pbt_masuk) > 0 ? (array_reduce($pbt_masuk, function ($carry, $item) {
																							return $carry + (($item->selisih_hari >= 1 && $item->selisih_hari <= 7) ? 1 : 0);
																						}, 0) / count($pbt_masuk)) * 100 : 0 ?>%"></div>
								</div>
							</div>
							<div class="progress-group">
								<span class="progress-text">Proses 8-14 Hari (Sesuai Aturan)</span>
								<span class="float-right"><b><?= array_reduce($pbt_masuk, function ($carry, $item) {
																	return $carry + (($item->selisih_hari >= 8 && $item->selisih_hari <= 14) ? 1 : 0);
																}, 0) ?></b>/<?= count($pbt_masuk) ?></span>
								<div class="progress progress-sm">
									<div class="progress-bar bg-info" style="width: <?= count($pbt_masuk) > 0 ? (array_reduce($pbt_masuk, function ($carry, $item) {
																						return $carry + (($item->selisih_hari >= 8 && $item->selisih_hari <= 14) ? 1 : 0);
																					}, 0) / count($pbt_masuk)) * 100 : 0 ?>%"></div>
								</div>
							</div>
							<div class="progress-group">
								<span class="progress-text">Proses > 14 Hari (Terlambat)</span>
								<span class="float-right"><b><?= array_reduce($pbt_masuk, function ($carry, $item) {
																	return $carry + (($item->selisih_hari > 14) ? 1 : 0);
																}, 0) ?></b>/<?= count($pbt_masuk) ?></span>
								<div class="progress progress-sm">
									<div class="progress-bar bg-danger" style="width: <?= count($pbt_masuk) > 0 ? (array_reduce($pbt_masuk, function ($carry, $item) {
																							return $carry + (($item->selisih_hari > 14) ? 1 : 0);
																						}, 0) / count($pbt_masuk)) * 100 : 0 ?>%"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>

<script>
	$(document).ready(function() {
		$('#pbt-table').DataTable({
			"responsive": true,
			"lengthChange": false,
			"autoWidth": false,
			"order": [
				[4, "desc"]
			], // Sort by tanggal PBT desc
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
			}
		});

		// Initialize BHT status chart
		var ctx = document.getElementById('bhtStatusChart').getContext('2d');
		var bhtStatusChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: ['Sudah BHT', 'Belum BHT'],
				datasets: [{
					data: [<?= $statistik_pbt->sudah_bht ?>, <?= $statistik_pbt->belum_bht ?>],
					backgroundColor: ['#28a745', '#ffc107'],
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					position: 'bottom'
				}
			}
		});

		// Auto refresh every 5 minutes
		setInterval(function() {
			location.reload();
		}, 5 * 60 * 1000);
	});
</script>