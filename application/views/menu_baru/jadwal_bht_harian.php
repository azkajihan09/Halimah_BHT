<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">
						<?= $title ?>
						<span class="badge badge-warning ml-2" id="live-count"><?= $total_jadwal ?></span>
					</h1>
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

			<!-- Alert untuk pengingat urgent -->
			<?php if ($pengingat_urgent > 0): ?>
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
					Ada <strong><?= $pengingat_urgent ?></strong> perkara yang sudah lebih dari 10 hari sejak PBT dan belum BHT!<br>
					<small><strong>Catatan:</strong> Berdasarkan aturan resmi, perkara harus BHT dalam 14 hari kalender setelah PBT.</small>
				</div>
			<?php endif; ?>

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
									<label for="tanggal" class="mr-2">Tanggal:</label>
									<input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggal ?>">
								</div>
								<div class="form-group mr-3">
									<label for="jenis" class="mr-2">Jenis Perkara:</label>
									<select class="form-control" id="jenis" name="jenis">
										<option value="semua" <?= $jenis == 'semua' ? 'selected' : '' ?>>Semua Jenis</option>
										<?php if (!empty($kategori_jenis)): ?>
											<?php foreach ($kategori_jenis as $kategori): ?>
												<option value="<?= $kategori->jenis_perkara_nama ?>" <?= $jenis == $kategori->jenis_perkara_nama ? 'selected' : '' ?>>
													<?= $kategori->jenis_perkara_nama ?> (<?= $kategori->jumlah ?>)
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
								<div class="form-group mr-3">
									<label for="tahun" class="mr-2">Tahun Minimal:</label>
									<select class="form-control" id="tahun" name="tahun">
										<?php if (!empty($available_years)): ?>
											<?php foreach ($available_years as $year): ?>
												<option value="<?= $year->tahun ?>" <?= $tahun_filter == $year->tahun ? 'selected' : '' ?>>
													<?= $year->tahun ?> ke atas
												</option>
											<?php endforeach; ?>
										<?php else: ?>
											<option value="2024" <?= $tahun_filter == '2024' ? 'selected' : '' ?>>2024 ke atas</option>
											<option value="2023" <?= $tahun_filter == '2023' ? 'selected' : '' ?>>2023 ke atas</option>
											<option value="2022" <?= $tahun_filter == '2022' ? 'selected' : '' ?>>2022 ke atas</option>
										<?php endif; ?>
									</select>
								</div>
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-search"></i> Filter
								</button>
								<a href="<?= site_url('Menu_baru/export_excel/jadwal_bht_harian') ?>?tanggal=<?= $tanggal ?>&jenis=<?= $jenis ?>&tahun=<?= $tahun_filter ?>" class="btn btn-success ml-2">
									<i class="fas fa-file-excel"></i> Export Excel
								</a>
								<button type="button" class="btn btn-info ml-2" onclick="refreshData()">
									<i class="fas fa-sync-alt"></i> Refresh
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Statistics Row -->
			<div class="row">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= $total_jadwal ?></h3>
							<p>Total Jadwal BHT</p>
						</div>
						<div class="icon">
							<i class="fas fa-calendar-day"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<?php $normal = 0;
							foreach ($jadwal_bht as $j) {
								if ($j->status == 'Normal' || $j->status == 'Selesai') $normal++;
							} ?>
							<h3><?= $normal ?></h3>
							<p>Normal/Selesai</p>
						</div>
						<div class="icon">
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<?php $urgent = 0;
							foreach ($jadwal_bht as $j) {
								if ($j->status == 'Urgent' || $j->prioritas == 'MEDIUM') $urgent++;
							} ?>
							<h3><?= $urgent ?></h3>
							<p>Urgent (11-14 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-danger">
						<div class="inner">
							<?php $terlambat = 0;
							foreach ($jadwal_bht as $j) {
								if ($j->status == 'Terlambat' || $j->prioritas == 'HIGH') $terlambat++;
							} ?>
							<h3><?= $terlambat ?></h3>
							<p>Terlambat (15-21 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-times-circle"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-dark">
						<div class="inner">
							<?php $critical = 0;
							foreach ($jadwal_bht as $j) {
								if ($j->status == 'Critical' || $j->prioritas == 'CRITICAL') $critical++;
							} ?>
							<h3><?= $critical ?></h3>
							<p>Critical (>21 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-skull-crossbones"></i>
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
								<i class="fas fa-clock"></i> Jadwal BHT yang Harus Dikerjakan
							</h3>
							<div class="card-tools">
								<span class="badge badge-primary">Auto refresh setiap 2 menit</span>
							</div>
						</div>
						<div class="card-body">
							<?php if (empty($jadwal_bht)): ?>
								<div class="alert alert-success">
									<i class="fas fa-check-circle"></i> Semua jadwal BHT sudah selesai!
								</div>
							<?php else: ?>
								<div class="table-responsive">
									<table class="table table-bordered table-striped" id="jadwal-table">
										<thead>
											<tr>
												<th width="4%">No</th>
												<th width="15%">Nomor Perkara</th>
												<th width="18%">Jenis Perkara</th>
												<th width="10%">Tgl Putusan</th>
												<th width="10%">Tgl PBT</th>
												<th width="10%">Target BHT</th>
												<th width="8%">Hari Sejak PBT</th>
												<th width="8%">Sisa Hari</th>
												<th width="8%">Prioritas</th>
												<th width="9%">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php $no = 1;
											foreach ($jadwal_bht as $jadwal):
												// Hitung sisa hari dinamis jika tidak ada dari query
												$sisa_hari = isset($jadwal->sisa_hari_target) ? $jadwal->sisa_hari_target : (isset($jadwal->perkiraan_bht) && $jadwal->perkiraan_bht != 'Cek Data Upaya Hukum' && $jadwal->perkiraan_bht != 'TGL PIP belum ada'
														? ceil((strtotime($jadwal->perkiraan_bht) - time()) / (60 * 60 * 24)) : '-');
											?>
												<tr class="<?php
															// Simplified table row coloring
															if ($jadwal->prioritas == 'CRITICAL') echo 'table-dark';
															elseif ($jadwal->prioritas == 'HIGH') echo 'table-danger';
															elseif ($jadwal->prioritas == 'MEDIUM') echo 'table-warning';
															elseif ($jadwal->prioritas == 'COMPLETED') echo 'table-success';
															else echo 'table-info';
															?>">
													<td><?= $no++ ?></td>
													<td><?= htmlspecialchars($jadwal->nomor_perkara) ?></td>
													<td><?= htmlspecialchars($jadwal->jenis_perkara) ?></td>
													<td><?= date('d/m/y', strtotime($jadwal->tanggal_putusan)) ?></td>
													<td>
														<?= $jadwal->tanggal_pbt ? date('d/m/y', strtotime($jadwal->tanggal_pbt)) : '<span class="text-muted">-</span>' ?>
													</td>
													<td>
														<?php if (isset($jadwal->perkiraan_bht)): ?>
															<?php if ($jadwal->perkiraan_bht == 'Cek Data Upaya Hukum'): ?>
																<span class="badge badge-warning badge-sm">Upaya Hukum</span>
															<?php elseif ($jadwal->perkiraan_bht == 'TGL PIP belum ada'): ?>
																<span class="badge badge-info badge-sm">Tunggu PIP</span>
															<?php else: ?>
																<?= date('d/m/y', strtotime($jadwal->perkiraan_bht)) ?>
															<?php endif; ?>
														<?php elseif (isset($jadwal->target_bht)): ?>
															<?= date('d/m/y', strtotime($jadwal->target_bht)) ?>
														<?php else: ?>
															<span class="text-muted">-</span>
														<?php endif; ?>
													</td>
													<td>
														<span class="badge <?=
																			$jadwal->hari_sejak_pbt > 21 ? 'badge-dark' : ($jadwal->hari_sejak_pbt > 14 ? 'badge-danger' : ($jadwal->hari_sejak_pbt > 10 ? 'badge-warning' : 'badge-info'))
																			?>">
															<?= $jadwal->hari_sejak_pbt ?> hr
														</span>
													</td>
													<td>
														<?php if (isset($jadwal->tanggal_bht) && $jadwal->tanggal_bht): ?>
															<span class="badge badge-success">Selesai</span>
														<?php elseif ($sisa_hari !== '-'): ?>
															<?php if ($sisa_hari > 0): ?>
																<span class="badge badge-success"><?= $sisa_hari ?> hari</span>
															<?php elseif ($sisa_hari == 0): ?>
																<span class="badge badge-warning">Deadline</span>
															<?php else: ?>
																<span class="badge badge-danger">+<?= abs($sisa_hari) ?> hr</span>
															<?php endif; ?>
														<?php else: ?>
															<span class="badge badge-secondary">-</span>
														<?php endif; ?>
													</td>
													<td>
														<span class="badge <?=
																			$jadwal->prioritas == 'CRITICAL' ? 'badge-dark' : ($jadwal->prioritas == 'HIGH' ? 'badge-danger' : ($jadwal->prioritas == 'MEDIUM' ? 'badge-warning' : ($jadwal->prioritas == 'COMPLETED' ? 'badge-success' : 'badge-info')))
																			?>">
															<?= $jadwal->prioritas ?>
														</span>
													</td>
													<td>
														<span class="badge <?=
																			$jadwal->status == 'Critical' ? 'badge-dark' : ($jadwal->status == 'Terlambat' ? 'badge-danger' : ($jadwal->status == 'Urgent' ? 'badge-warning' : ($jadwal->status == 'Selesai' ? 'badge-success' : 'badge-info')))
																			?>">
															<?= $jadwal->status ?>
														</span>
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
		</div>
	</section>
</div>

<!-- Sound notification for urgent alerts -->
<audio id="alert-sound" preload="auto">
	<source src="<?= base_url('assets/sounds/alert.mp3') ?>" type="audio/mpeg">
	<source src="<?= base_url('assets/sounds/alert.wav') ?>" type="audio/wav">
</audio>

<script>
	$(document).ready(function() {
		// Simplified DataTable initialization
		$('#jadwal-table').DataTable({
			"responsive": false, // Disable responsive for better performance
			"lengthChange": false,
			"autoWidth": false,
			"pageLength": 50, // Show more rows by default
			"order": [
				[6, "desc"]
			], // Sort by hari sejak PBT desc
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
			},
			"columnDefs": [{
				"targets": [6], // Hari sejak PBT column
				"type": "num"
			}],
			"deferRender": true, // Improve performance for large datasets
			"processing": true
		});

		// Reduced auto refresh to every 5 minutes to reduce server load
		setInterval(function() {
			refreshData();
		}, 5 * 60 * 1000);

		// Check for urgent alerts every 2 minutes instead of 30 seconds
		setInterval(checkUrgentAlerts, 2 * 60 * 1000);
	});

	function refreshData() {
		location.reload();
	}

	function checkUrgentAlerts() {
		$.ajax({
			url: '<?= site_url("Menu_baru/api_notifikasi") ?>',
			method: 'GET',
			dataType: 'json',
			success: function(response) {
				if (response.success && response.data) {
					var urgentCount = response.data.jadwal_bht_urgent;
					$('#live-count').text(urgentCount);

					if (urgentCount > 0) {
						$('#live-count').removeClass('badge-success').addClass('badge-danger');

						// Play alert sound if there are new urgent items
						var alertSound = document.getElementById('alert-sound');
						if (alertSound) {
							alertSound.play().catch(function(error) {
								console.log('Audio play failed:', error);
							});
						}

						// Show browser notification if supported
						if ("Notification" in window && Notification.permission === "granted") {
							new Notification("Jadwal BHT Urgent!", {
								body: "Ada " + urgentCount + " perkara yang urgent untuk dikerjakan BHT!",
								icon: "<?= base_url('assets/dist/img/logo-mahkamah-agung.png') ?>"
							});
						}
					} else {
						$('#live-count').removeClass('badge-danger').addClass('badge-success');
					}
				}
			}
		});
	}

	// Request notification permission
	if ("Notification" in window && Notification.permission === "default") {
		Notification.requestPermission();
	}
</script>