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

			<!-- Info Alert tentang Aturan 14 Hari -->
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="alert alert-info alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<h5><i class="icon fas fa-info-circle"></i> Informasi Aturan BHT & Logika Perhitungan</h5>
						<strong>Berdasarkan aturan resmi pengadilan agama:</strong> Putusan menjadi BHT dalam <strong>14 hari kalender</strong> setelah PBT disampaikan kepada pihak yang tidak hadir.
						Jika kedua pihak hadir saat pembacaan putusan, maka putusan langsung BHT.
						<br><br>
						<div class="row">
							<div class="col-md-4">
								<strong>Logika Perhitungan Hari Kerja ke Target BHT:</strong>
								<ul class="mb-0 mt-1">
									<li>Menggunakan <strong>14 hari kerja</strong> (Senin-Jumat, tidak termasuk weekend dan libur nasional)</li>
									<li>Jika ada data di tabel <code>perkara_putusan_pemberitahuan_putusan</code>, hitung dari tanggal pemberitahuan</li>
									<li>Jika tidak ada data pemberitahuan, hitung dari tanggal putusan</li>
									<li>Sisa hari = Target BHT - Hari ini (menunjukkan sisa hari kerja)</li>
								</ul>
							</div>
							<div class="col-md-4">
								<strong>Perkiraan BHT (Logika Baru):</strong>
								<ul class="mb-0 mt-1">
									<li><strong>Upaya Hukum:</strong> Jika ada banding/kasasi/verzet</li>
									<li><strong>Tunggu PIP:</strong> Ada transaksi PIP tapi belum ada tanggal pemberitahuan</li>
									<li><strong>Tanggal:</strong> Tanggal pemberitahuan + 15 hari (jika ada PIP)</li>
									<li><strong>Default:</strong> Tanggal putusan + 15 hari (jika tidak ada PIP)</li>
								</ul>
							</div>
							<div class="col-md-4">
								<strong>Keterangan & Indikator:</strong><br>
								<span class="badge badge-primary"><i class="fas fa-bell"></i></span> Dari Pemberitahuan Putusan<br>
								<span class="badge badge-secondary"><i class="fas fa-gavel"></i></span> Dari Tanggal Putusan<br>
								<span class="badge badge-dark"><i class="fas fa-university"></i></span> Kasasi<br>
								<span class="badge badge-warning"><i class="fas fa-balance-scale"></i></span> Banding<br>
								<span class="badge badge-info"><i class="fas fa-redo"></i></span> Verzet<br>
								<span class="badge badge-success"><i class="fas fa-check"></i></span> Normal
							</div>
						</div>
						<small class="text-muted">* PBT = Pemberitahuan Isi Putusan | BHT = Berkekuatan Hukum Tetap</small>
					</div>
				</div>
			</div>

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
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-search"></i> Filter
								</button>
								<a href="<?= site_url('Menu_baru/export_excel/perkara_putus_harian') ?>?tanggal=<?= $tanggal ?>" class="btn btn-success ml-2">
									<i class="fas fa-file-excel"></i> Export Excel
								</a>
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
							<h3><?= $total_putus ?></h3>
							<p>Total Perkara Putus</p>
						</div>
						<div class="icon">
							<i class="fas fa-gavel"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?= count(array_filter($perkara_putus, function ($p) {
									return $p->status_bht == 'Sudah BHT';
								})) ?></h3>
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
							<h3><?= count(array_filter($perkara_putus, function ($p) {
									return $p->status_bht == 'Belum BHT' && $p->hari_sejak_putus <= 14;
								})) ?></h3>
							<p>Belum BHT (≤14 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-clock"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3><?= count(array_filter($perkara_putus, function ($p) {
									return $p->hari_sejak_putus > 14;
								})) ?></h3>
							<p>Terlambat BHT (>14 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
					</div>
				</div>
			</div>

			<!-- Additional Statistics Row untuk Kategori Detail -->
			<div class="row">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-light">
						<div class="inner">
							<h3 class="text-success"><?= count(array_filter($perkara_putus, function ($p) {
															return $p->hari_sejak_putus <= 10;
														})) ?></h3>
							<p class="text-success">Normal (0-10 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-thumbs-up text-success"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-light">
						<div class="inner">
							<h3 class="text-warning"><?= count(array_filter($perkara_putus, function ($p) {
															return $p->hari_sejak_putus > 10 && $p->hari_sejak_putus <= 14;
														})) ?></h3>
							<p class="text-warning">Urgent (11-14 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-hourglass-half text-warning"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-light">
						<div class="inner">
							<h3 class="text-danger"><?= count(array_filter($perkara_putus, function ($p) {
														return $p->hari_sejak_putus > 14 && $p->hari_sejak_putus <= 21;
													})) ?></h3>
							<p class="text-danger">Terlambat (15-21 hari)</p>
						</div>
						<div class="icon">
							<i class="fas fa-times-circle text-danger"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-dark">
						<div class="inner">
							<h3><?= count(array_filter($perkara_putus, function ($p) {
									return $p->hari_sejak_putus > 21;
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
								<i class="fas fa-list"></i> Daftar Perkara Putus Tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>
							</h3>
						</div>
						<div class="card-body">
							<?php if (empty($perkara_putus)): ?>
								<div class="alert alert-info">
									<i class="fas fa-info-circle"></i> Tidak ada data perkara putus pada tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>
								</div>
							<?php else: ?>
								<div class="table-responsive">
									<table class="table table-bordered table-striped" id="perkara-table">
										<thead>
											<tr>
												<th width="3%">No</th>
												<th width="10%">Nomor Perkara</th>
												<th width="12%">Jenis Perkara</th>
												<th width="6%">Tanggal Putus</th>
												<th width="8%">Hakim</th>
												<th width="6%">Tanggal PBT</th>
												<th width="6%">Target BHT</th>
												<th width="6%">Perkiraan BHT</th>
												<th width="8%">Status BHT</th>
												<th width="6%">Sisa Hari</th>
												<th width="6%">Tanggal BHT</th>
												<th width="6%">Keterangan</th>
												<th width="4%">Sumber PBT</th>
												<th width="4%">JSP</th>
											</tr>
										</thead>
										<tbody>
											<?php $no = 1;
											foreach ($perkara_putus as $perkara): ?>
												<?php
												// Logika untuk menentukan tanggal PBT yang ditampilkan
												$tanggal_pbt_display = '-';
												$pbt_badge_class = 'badge-secondary';
												$pbt_icon = 'fas fa-gavel';
												$pbt_title = 'Dari Tanggal Putusan';

												if (isset($perkara->tanggal_pemberitahuan_putusan) && $perkara->tanggal_pemberitahuan_putusan) {
													// Priority: Tanggal dari pemberitahuan putusan
													$tanggal_pbt_display = date('d/m/Y', strtotime($perkara->tanggal_pemberitahuan_putusan));
													$pbt_badge_class = 'badge-primary';
													$pbt_icon = 'fas fa-bell';
													$pbt_title = 'Dari Pemberitahuan Putusan';
												} else {
													// Fallback: Tanggal putusan
													$tanggal_pbt_display = date('d/m/Y', strtotime($perkara->tanggal_putus));
													$pbt_badge_class = 'badge-secondary';
													$pbt_icon = 'fas fa-gavel';
													$pbt_title = 'Dari Tanggal Putusan';
												}
												?>
												<tr class="<?= $perkara->hari_sejak_putus > 21 ? 'table-dark' : ($perkara->hari_sejak_putus > 14 ? 'table-danger' : ($perkara->hari_sejak_putus > 10 ? 'table-warning' : '')) ?>">
													<td><?= $no++ ?></td>
													<td><?= htmlspecialchars($perkara->nomor_perkara) ?></td>
													<td><?= htmlspecialchars($perkara->jenis_perkara) ?></td>
													<td><?= date('d/m/Y', strtotime($perkara->tanggal_putus)) ?></td>
													<td><?= htmlspecialchars($perkara->hakim) ?></td>
													<td class="text-center">
														<span class="<?= $pbt_badge_class ?> badge-sm" title="<?= $pbt_title ?>">
															<i class="<?= $pbt_icon ?>"></i>
															<?= $tanggal_pbt_display ?>
														</span>
													</td>
													<td class="text-center">
														<small class="text-info">
															<i class="far fa-calendar-alt"></i>
															<?= date('d/m/Y', strtotime($perkara->target_bht)) ?>
														</small>
														<br>
														<small class="text-muted">(+14 hari kerja)</small>
													</td>
													<!-- Kolom Perkiraan BHT -->
													<td class="text-center">
														<?php if (isset($perkara->perkiraan_bht)): ?>
															<?php if ($perkara->perkiraan_bht == 'Cek Data Upaya Hukum'): ?>
																<span class="badge badge-warning badge-sm">
																	<i class="fas fa-balance-scale"></i>
																	Upaya Hukum
																</span>
															<?php elseif ($perkara->perkiraan_bht == 'TGL PIP belum ada'): ?>
																<span class="badge badge-info badge-sm">
																	<i class="fas fa-clock"></i>
																	Tunggu PIP
																</span>
															<?php else: ?>
																<small class="text-success">
																	<i class="far fa-calendar-check"></i>
																	<?= date('d/m/Y', strtotime($perkara->perkiraan_bht)) ?>
																</small>
															<?php endif; ?>
														<?php else: ?>
															<span class="text-muted">-</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if ($perkara->status_bht == 'Sudah BHT'): ?>
															<span class="badge badge-success">
																<i class="fas fa-check"></i> Sudah BHT
															</span>
														<?php elseif (strpos($perkara->status_bht, 'Critical') !== false): ?>
															<span class="badge badge-dark">
																<i class="fas fa-exclamation-triangle"></i> Critical
															</span>
														<?php elseif (strpos($perkara->status_bht, 'Terlambat') !== false): ?>
															<span class="badge badge-danger">
																<i class="fas fa-clock"></i> Terlambat
															</span>
														<?php elseif (strpos($perkara->status_bht, 'Urgent') !== false): ?>
															<span class="badge badge-warning">
																<i class="fas fa-clock"></i> Urgent
															</span>
														<?php else: ?>
															<span class="badge badge-info">
																<i class="fas fa-clock"></i> Normal
															</span>
														<?php endif; ?>
													</td>
													<td>
														<?php
														$sisa_hari = isset($perkara->sisa_hari_ke_target) ? $perkara->sisa_hari_ke_target : '-';
														if ($sisa_hari !== '-'):
														?>
															<?php if ($sisa_hari > 0): ?>
																<span class="badge badge-success">
																	<?= $sisa_hari ?> hari lagi
																</span>
															<?php elseif ($sisa_hari == 0): ?>
																<span class="badge badge-warning">
																	Hari ini (Deadline)
																</span>
															<?php elseif ($sisa_hari >= -7): ?>
																<span class="badge badge-danger">
																	Terlambat <?= abs($sisa_hari) ?> hari
																</span>
															<?php else: ?>
																<span class="badge badge-dark">
																	Sangat Terlambat <?= abs($sisa_hari) ?> hari
																</span>
															<?php endif; ?>
														<?php else: ?>
															<span class="badge badge-secondary">-</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if ($perkara->tanggal_bht): ?>
															<span class="badge badge-success">
																<i class="fas fa-calendar-check"></i>
																<?= date('d/m/Y', strtotime($perkara->tanggal_bht)) ?>
															</span>
														<?php else: ?>
															<span class="text-muted">
																<i class="fas fa-minus"></i> Belum BHT
															</span>
														<?php endif; ?>
													</td>
													<!-- Kolom Keterangan Perkara -->
													<td class="text-center">
														<?php if (isset($perkara->keterangan_perkara)): ?>
															<?php if ($perkara->keterangan_perkara == 'Kasasi'): ?>
																<span class="badge badge-dark badge-sm">
																	<i class="fas fa-university"></i> Kasasi
																</span>
															<?php elseif ($perkara->keterangan_perkara == 'Banding'): ?>
																<span class="badge badge-warning badge-sm">
																	<i class="fas fa-balance-scale"></i> Banding
																</span>
															<?php elseif ($perkara->keterangan_perkara == 'Verzet'): ?>
																<span class="badge badge-info badge-sm">
																	<i class="fas fa-redo"></i> Verzet
																</span>
															<?php else: ?>
																<span class="badge badge-success badge-sm">
																	<i class="fas fa-check"></i> Normal
																</span>
															<?php endif; ?>
														<?php else: ?>
															<span class="text-muted">-</span>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<?php if (isset($perkara->sumber_pbt)): ?>
															<?php if (strpos($perkara->sumber_pbt, 'Pemberitahuan') !== false): ?>
																<span class="badge badge-primary" title="<?= $perkara->sumber_pbt ?>">
																	<i class="fas fa-bell"></i>
																</span>
															<?php else: ?>
																<span class="badge badge-secondary" title="<?= $perkara->sumber_pbt ?>">
																	<i class="fas fa-gavel"></i>
																</span>
															<?php endif; ?>
														<?php else: ?>
															<span class="badge badge-light">
																<i class="fas fa-question"></i>
															</span>
														<?php endif; ?>
													</td>
													<!-- Kolom JSP (Juru Sita Pengganti) -->
													<td class="text-center">
														<?php if (isset($perkara->jsp) && $perkara->jsp != '-'): ?>
															<span class="badge badge-info badge-sm" title="Juru Sita Pengganti">
																<i class="fas fa-user-tie"></i>
																<?= htmlspecialchars(trim($perkara->jsp)) ?>
															</span>
														<?php else: ?>
															<span class="text-muted">-</span>
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

<!-- DataTables -->
<script>
	$(document).ready(function() {
		$('#perkara-table').DataTable({
			"responsive": true,
			"lengthChange": false,
			"autoWidth": false,
			"order": [
				[6, "desc"]
			], // Sort by hari sejak putus desc
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
			},
			"columnDefs": [{
				"targets": [6], // Hari sejak putus column
				"type": "num"
			}]
		});

		// Auto refresh every 5 minutes
		setInterval(function() {
			location.reload();
		}, 5 * 60 * 1000);
	});
</script>