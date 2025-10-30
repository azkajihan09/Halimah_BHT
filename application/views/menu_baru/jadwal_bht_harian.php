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
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h5><i class="icon fas fa-ban"></i> Perhatian!</h5>
					Ada <strong><?= $pengingat_urgent ?></strong> perkara yang sudah lebih dari 5 hari sejak PBT dan belum BHT!
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
							<p>Urgent (5-7 hari)</p>
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
							<p>Terlambat (>7 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-times-circle"></i>
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
												<th width="5%">No</th>
												<th width="15%">Nomor Perkara</th>
												<th width="20%">Jenis Perkara</th>
												<th width="12%">Tgl Putusan</th>
												<th width="12%">Tgl PBT</th>
												<th width="12%">Target BHT</th>
												<th width="8%">Hari Sejak PBT</th>
												<th width="8%">Prioritas</th>
												<th width="8%">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php $no = 1;
											foreach ($jadwal_bht as $jadwal): ?>
												<tr class="<?=
															$jadwal->status == 'Terlambat' ? 'table-danger' : ($jadwal->status == 'Urgent' ? 'table-warning' : 'table-info')
															?>">
													<td><?= $no++ ?></td>
													<td><?= htmlspecialchars($jadwal->nomor_perkara) ?></td>
													<td><?= htmlspecialchars($jadwal->jenis_perkara) ?></td>
													<td><?= date('d/m/Y', strtotime($jadwal->tanggal_putusan)) ?></td>
													<td><?= date('d/m/Y', strtotime($jadwal->tanggal_pbt)) ?></td>
													<td><?= date('d/m/Y', strtotime($jadwal->target_bht)) ?></td>
													<td>
														<span class="badge <?=
																			$jadwal->hari_sejak_pbt > 7 ? 'badge-danger' : ($jadwal->hari_sejak_pbt > 5 ? 'badge-warning' : 'badge-success')
																			?>">
															<?= $jadwal->hari_sejak_pbt ?> hari
														</span>
													</td>
													<td>
														<?php if ($jadwal->prioritas == 'HIGH'): ?>
															<span class="badge badge-danger">
																<i class="fas fa-fire"></i> TINGGI
															</span>
														<?php elseif ($jadwal->prioritas == 'MEDIUM'): ?>
															<span class="badge badge-warning">
																<i class="fas fa-exclamation"></i> SEDANG
															</span>
														<?php else: ?>
															<span class="badge badge-success">
																<i class="fas fa-check"></i> RENDAH
															</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if ($jadwal->status == 'Terlambat'): ?>
															<span class="badge badge-danger">
																<i class="fas fa-times"></i> <?= $jadwal->status ?>
															</span>
														<?php elseif ($jadwal->status == 'Urgent'): ?>
															<span class="badge badge-warning">
																<i class="fas fa-exclamation-triangle"></i> <?= $jadwal->status ?>
															</span>
														<?php else: ?>
															<span class="badge badge-info">
																<i class="fas fa-clock"></i> <?= $jadwal->status ?>
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
