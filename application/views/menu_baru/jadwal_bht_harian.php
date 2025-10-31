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
							<h3><?= count(array_filter($jadwal_bht, function ($j) {
									return $j->status == 'Normal';
								})) ?></h3>
							<p>Normal</p>
						</div>
						<div class="icon">
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= count(array_filter($jadwal_bht, function ($j) {
									return $j->status == 'Urgent';
								})) ?></h3>
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
							<h3><?= count(array_filter($jadwal_bht, function ($j) {
									return $j->status == 'Terlambat';
								})) ?></h3>
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
							<h3><?= count(array_filter($jadwal_bht, function ($j) {
									return $j->prioritas == 'CRITICAL';
								})) ?></h3>
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
												<th width="12%">Nomor Perkara</th>
												<th width="15%">Jenis Perkara</th>
												<th width="8%">Tgl Putusan</th>
												<th width="8%">Tgl PBT</th>
												<th width="8%">Target BHT</th>
												<th width="8%">Tanggal BHT</th>
												<th width="6%">Hari Sejak PBT</th>
												<th width="6%">Sisa/Terlambat</th>
												<th width="6%">Progress</th>
												<th width="6%">Efficiency</th>
												<th width="6%">Prioritas</th>
												<th width="7%">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php $no = 1;
											foreach ($jadwal_bht as $jadwal): ?>
												<tr class="<?php
															if (strpos($jadwal->prioritas, 'CRITICAL') !== false) echo 'table-dark';
															elseif (strpos($jadwal->prioritas, 'HIGH') !== false) echo 'table-danger';
															elseif (strpos($jadwal->prioritas, 'MEDIUM') !== false) echo 'table-warning';
															elseif ($jadwal->prioritas == 'COMPLETED') echo 'table-success';
															else echo 'table-info';
															?>">
													<td><?= $no++ ?></td>
													<td>
														<small><?= htmlspecialchars($jadwal->nomor_perkara) ?></small>
													</td>
													<td>
														<small><?= htmlspecialchars($jadwal->jenis_perkara) ?></small>
													</td>
													<td>
														<small><?= date('d/m/y', strtotime($jadwal->tanggal_putusan)) ?></small>
													</td>
													<td>
														<?php if ($jadwal->tanggal_pbt): ?>
															<small><?= date('d/m/y', strtotime($jadwal->tanggal_pbt)) ?></small>
														<?php else: ?>
															<small class="text-muted">-</small>
														<?php endif; ?>
													</td>
													<td>
														<?php if ($jadwal->target_bht): ?>
															<small class="text-primary">
																<i class="far fa-calendar"></i>
																<?= date('d/m/y', strtotime($jadwal->target_bht)) ?>
															</small>
														<?php else: ?>
															<small class="text-muted">-</small>
														<?php endif; ?>
													</td>
													<td>
														<?php if ($jadwal->tanggal_bht): ?>
															<small class="badge badge-success">
																<i class="fas fa-check"></i>
																<?= date('d/m/y', strtotime($jadwal->tanggal_bht)) ?>
															</small>
														<?php else: ?>
															<small class="text-muted">Belum BHT</small>
														<?php endif; ?>
													</td>
													<td>
														<span class="badge <?=
																			$jadwal->hari_sejak_pbt > 21 ? 'badge-dark' : ($jadwal->hari_sejak_pbt > 14 ? 'badge-danger' : ($jadwal->hari_sejak_pbt > 10 ? 'badge-warning' : 'badge-info'))
																			?>">
															<?= $jadwal->hari_sejak_pbt ?> hr
														</span>
														<br><small class="text-muted"><?= $jadwal->hari_kerja_sejak_pbt ?> hr kerja</small>
													</td>
													<td>
														<?php if ($jadwal->tanggal_bht): ?>
															<small class="badge badge-success">Selesai</small>
														<?php elseif ($jadwal->hari_terlambat > 0): ?>
															<small class="badge badge-danger">
																<i class="fas fa-exclamation-triangle"></i>
																+<?= $jadwal->hari_terlambat ?> hr
															</small>
														<?php else: ?>
															<small class="badge badge-info">
																<i class="fas fa-clock"></i>
																<?= $jadwal->sisa_hari_target ?> hr
															</small>
														<?php endif; ?>
													</td>
													<td>
														<div class="progress" style="height: 15px;">
															<div class="progress-bar <?=
																						$jadwal->progress_percentage >= 100 ? 'bg-success' : ($jadwal->progress_percentage >= 75 ? 'bg-warning' : 'bg-info')
																						?>" role="progressbar"
																style="width: <?= $jadwal->progress_percentage ?>%">
																<?= $jadwal->progress_percentage ?>%
															</div>
														</div>
													</td>
													<td>
														<span class="badge <?=
																			$jadwal->efficiency_score >= 80 ? 'badge-success' : ($jadwal->efficiency_score >= 60 ? 'badge-warning' : 'badge-danger')
																			?>">
															<?= $jadwal->efficiency_score ?>
														</span>
													</td>
													<td>
														<?php if (strpos($jadwal->prioritas, 'CRITICAL') !== false): ?>
															<span class="badge badge-dark">
																<i class="fas fa-skull-crossbones"></i> CRITICAL
															</span>
														<?php elseif (strpos($jadwal->prioritas, 'HIGH') !== false): ?>
															<span class="badge badge-danger">
																<i class="fas fa-fire"></i> HIGH
															</span>
														<?php elseif (strpos($jadwal->prioritas, 'MEDIUM') !== false): ?>
															<span class="badge badge-warning">
																<i class="fas fa-exclamation"></i> MEDIUM
															</span>
														<?php elseif ($jadwal->prioritas == 'COMPLETED'): ?>
															<span class="badge badge-success">
																<i class="fas fa-check-circle"></i> DONE
															</span>
														<?php else: ?>
															<span class="badge badge-info">
																<i class="fas fa-clock"></i> LOW
															</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if (strpos($jadwal->status, 'Critical') !== false): ?>
															<span class="badge badge-dark">
																<i class="fas fa-skull-crossbones"></i>
																<small><?= str_replace(['Critical - ', 'Critical'], ['', 'CRITICAL'], $jadwal->status) ?></small>
															</span>
														<?php elseif (strpos($jadwal->status, 'Terlambat') !== false): ?>
															<span class="badge badge-danger">
																<i class="fas fa-exclamation-triangle"></i>
																<small><?= str_replace('Terlambat ', '', $jadwal->status) ?></small>
															</span>
														<?php elseif (strpos($jadwal->status, 'Urgent') !== false): ?>
															<span class="badge badge-warning">
																<i class="fas fa-clock"></i>
																<small><?= str_replace('Urgent ', '', $jadwal->status) ?></small>
															</span>
														<?php elseif (strpos($jadwal->status, 'Selesai') !== false): ?>
															<span class="badge badge-success">
																<i class="fas fa-check-circle"></i>
																<small>SELESAI</small>
															</span>
														<?php else: ?>
															<span class="badge badge-info">
																<i class="fas fa-hourglass-half"></i>
																<small><?= $jadwal->status ?></small>
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
		$('#jadwal-table').DataTable({
			"responsive": true,
			"lengthChange": false,
			"autoWidth": false,
			"order": [
				[6, "desc"]
			], // Sort by hari sejak PBT desc
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
			},
			"columnDefs": [{
				"targets": [6], // Hari sejak PBT column
				"type": "num"
			}]
		});

		// Auto refresh every 2 minutes
		setInterval(function() {
			refreshData();
		}, 2 * 60 * 1000);

		// Check for urgent alerts every 30 seconds
		setInterval(checkUrgentAlerts, 30 * 1000);
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