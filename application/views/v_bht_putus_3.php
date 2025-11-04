<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>BHT Perkara Putus 3 | SIPP</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<!-- DateRangePicker -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/daterangepicker/daterangepicker.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/adminlte.min.css">

	<style>
		.table th {
			background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
			color: white;
			border: none;
			font-weight: 600;
			font-size: 12px;
		}

		.table td {
			font-size: 11px;
			vertical-align: middle;
		}

		.badge-status {
			font-size: 10px;
			padding: 4px 8px;
		}

		.stat-card {
			border-radius: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		.multiple-dates {
			line-height: 1.4;
			font-size: 11px;
		}

		.multiple-dates .date-item {
			display: block;
			margin-bottom: 2px;
			padding: 1px 4px;
			background-color: rgba(40, 167, 69, 0.1);
			border-radius: 3px;
			border-left: 3px solid #28a745;
		}

		.search-section {
			background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
			border-radius: 10px;
			padding: 20px;
			margin-bottom: 20px;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-file-invoice mr-2"></i> BHT Perkara Putus 3</h1>
							<small class="text-muted">Pencarian Berdasarkan Tanggal & Nomor Perkara</small>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Home</a></li>
								<li class="breadcrumb-item active">BHT Perkara Putus 3</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<!-- Filter Card -->
					<div class="card card-danger card-outline">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-search mr-1"></i> Pencarian & Filter Data</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body search-section">
							<form action="<?php echo base_url() ?>index.php/Bht_putus_3" method="POST" class="form-horizontal">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label><i class="fas fa-balance-scale mr-1"></i> Jenis Perkara:</label>
											<select name="jenis_perkara" class="form-control select2" required>
												<option value="Pdt.G" <?php echo ($jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Perkara Gugatan (Pdt.G)</option>
												<option value="Pdt.P" <?php echo ($jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Perkara Permohonan (Pdt.P)</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label><i class="fas fa-search mr-1"></i> Nomor Perkara:</label>
											<input type="text" name="nomor_perkara" class="form-control"
												placeholder="Cari berdasarkan nomor perkara..."
												value="<?= htmlspecialchars($nomor_perkara) ?>">
											<small class="text-muted">Kosongkan jika ingin menampilkan semua perkara</small>
										</div>
									</div>
								</div>

								<!-- Tab untuk memilih jenis pencarian -->
								<div class="row">
									<div class="col-12">
										<ul class="nav nav-tabs" id="searchTabs" role="tablist">
											<li class="nav-item">
												<a class="nav-link <?= empty($tanggal_awal) && empty($tanggal_akhir) ? 'active' : '' ?>"
													id="monthly-tab" data-toggle="tab" href="#monthly" role="tab">
													<i class="fas fa-calendar-alt mr-1"></i> Berdasarkan Bulan
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link <?= !empty($tanggal_awal) && !empty($tanggal_akhir) ? 'active' : '' ?>"
													id="daterange-tab" data-toggle="tab" href="#daterange" role="tab">
													<i class="fas fa-calendar-week mr-1"></i> Berdasarkan Range Tanggal
												</a>
											</li>
										</ul>
										<div class="tab-content mt-3" id="searchTabsContent">
											<!-- Tab Pencarian Bulanan -->
											<div class="tab-pane fade <?= empty($tanggal_awal) && empty($tanggal_akhir) ? 'show active' : '' ?>"
												id="monthly" role="tabpanel">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label><i class="far fa-calendar-alt mr-1"></i> Bulan:</label>
															<select name="lap_bulan" class="form-control select2">
																<option value="">-- Pilih Bulan --</option>
																<?php
																foreach ($months as $value => $label) {
																	$selected = ($lap_bulan === $value) ? 'selected' : '';
																	echo "<option value=\"$value\" $selected>$label</option>";
																}
																?>
															</select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label><i class="far fa-calendar-check mr-1"></i> Tahun:</label>
															<select name="lap_tahun" class="form-control select2">
																<option value="">-- Pilih Tahun --</option>
																<?php
																$currentYear = date('Y');
																for ($year = 2016; $year <= $currentYear + 1; $year++) {
																	$selected = ($lap_tahun == $year) ? 'selected' : '';
																	echo "<option value=\"$year\" $selected>$year</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<!-- Tab Pencarian Range Tanggal -->
											<div class="tab-pane fade <?= !empty($tanggal_awal) && !empty($tanggal_akhir) ? 'show active' : '' ?>"
												id="daterange" role="tabpanel">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label><i class="fas fa-calendar-day mr-1"></i> Tanggal Awal:</label>
															<input type="date" name="tanggal_awal" class="form-control"
																value="<?= $tanggal_awal ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label><i class="fas fa-calendar-day mr-1"></i> Tanggal Akhir:</label>
															<input type="date" name="tanggal_akhir" class="form-control"
																value="<?= $tanggal_akhir ?>">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row mt-3">
									<div class="col-12">
										<button type="submit" name="btn" value="Tampilkan" class="btn btn-danger">
											<i class="fas fa-search mr-2"></i> Cari Data
										</button>
										<button type="reset" class="btn btn-secondary" onclick="resetForm()">
											<i class="fas fa-undo mr-2"></i> Reset + Clear Cache
										</button>
										<button type="button" class="btn btn-warning" onclick="hardResetForm()" title="Reset lengkap dengan refresh halaman">
											<i class="fas fa-sync-alt mr-2"></i> Hard Reset
										</button>
										<button type="button" class="btn btn-info btn-sm" onclick="showCacheInfo()" title="Lihat informasi cache browser">
											<i class="fas fa-info-circle mr-1"></i> Info Cache
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<!-- Statistik Cards -->
					<?php if (isset($statistik)): ?>
						<div class="row">
							<div class="col-lg-3 col-md-6">
								<div class="info-box stat-card">
									<span class="info-box-icon bg-info"><i class="fas fa-gavel"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Total Perkara Putus</span>
										<span class="info-box-number"><?= number_format($statistik['total_putus']) ?></span>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="info-box stat-card">
									<span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Sudah BHT</span>
										<span class="info-box-number"><?= number_format($statistik['sudah_bht']) ?></span>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="info-box stat-card">
									<span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Belum BHT</span>
										<span class="info-box-number"><?= number_format($statistik['belum_bht']) ?></span>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6">
								<div class="info-box stat-card">
									<span class="info-box-icon bg-primary"><i class="fas fa-percentage"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Persentase BHT</span>
										<span class="info-box-number"><?= $statistik['persentase_bht'] ?>%</span>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<!-- Informasi Sistem PBT Terintegrasi -->
					<div class="row">
						<div class="col-12">
							<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h5><i class="icon fas fa-info-circle"></i> Sistem PBT Terintegrasi dengan Tabel Pemberitahuan Putusan</h5>
								<div class="row">
									<div class="col-md-6">
										<strong>Priority Logic PBT:</strong>
										<ul class="mb-2">
											<li><span class="badge badge-primary">PP</span> <strong>Prioritas Utama:</strong> Tanggal dari tabel <code>pemberitahuan_putusan</code></li>
											<li><span class="badge badge-info">JS</span> <strong>Fallback:</strong> Tanggal dari <code>jadwal_sidang</code> jika PP tidak ada</li>
										</ul>
									</div>
									<div class="col-md-6">
										<strong>Fitur Baru:</strong>
										<ul class="mb-2">
											<li><strong>Target BHT:</strong> Dihitung +14 hari dari PBT yang akurat</li>
											<li><strong>Jangka Waktu:</strong> Periode dari PBT ke target BHT (selalu 14 hari)</li>
											<li><strong>Sisa Hari:</strong> Monitoring real-time sisa waktu ke target BHT</li>
											<li><strong>Sumber PBT:</strong> Transparansi data (PP/JS) untuk akurasi</li>
										</ul>
									</div>
								</div>
								<div class="text-center mt-2">
									<small class="text-muted">
										<i class="fas fa-calendar-check"></i> <strong>Aturan 14 Hari Kalender:</strong> Target BHT dihitung berdasarkan tanggal PBT efektif + 14 hari kalender sesuai regulasi pengadilan
									</small>
								</div>
							</div>
						</div>
					</div>

					<!-- Main Data Table -->
					<div class="row">
						<div class="col-12">
							<div class="card card-outline card-danger">
								<div class="card-header">
									<h3 class="card-title">
										<i class="fas fa-table mr-1"></i> Detail BHT Perkara Putus 3
										<?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>
											(<?= date('d/m/Y', strtotime($tanggal_awal)) ?> - <?= date('d/m/Y', strtotime($tanggal_akhir)) ?>)
										<?php else: ?>
											<?= isset($months) ? $months[$lap_bulan] : '' ?> <?= $lap_tahun ?>
										<?php endif; ?>
										<?php if (!empty($nomor_perkara)): ?>
											<span class="badge badge-secondary">Filter: <?= htmlspecialchars($nomor_perkara) ?></span>
										<?php endif; ?>
									</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
										<button type="button" class="btn btn-tool" data-card-widget="maximize">
											<i class="fas fa-expand"></i>
										</button>
									</div>
								</div>

								<div class="card-body">
									<?php if (!empty($bht_putus)): ?>
										<div class="table-responsive">
											<table id="bhtTable3" class="table table-bordered table-striped table-hover">
												<thead>
													<tr>
														<th class="text-center" style="width: 3%;">No</th>
														<th style="width: 8%;">Tanggal Putus</th>
														<th style="width: 12%;">Nomor Perkara</th>
														<th style="width: 8%;">Jenis Perkara</th>
														<th style="width: 12%;">Panitera Pengganti</th>
														<th style="width: 12%;">Juru Sita Pengganti</th>
														<th style="width: 8%;">PBT</th>
														<th style="width: 6%;">Sumber PBT</th>
														<th style="width: 8%;">Target BHT</th>
														<th style="width: 6%;">Jangka Waktu</th>
														<th style="width: 6%;">Sisa Hari</th>
														<th style="width: 8%;">BHT</th>
														<th style="width: 8%;">Ikrar</th>
														<th style="width: 6%;">Status BHT</th>
														<th style="width: 6%;">Status</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$no = 1;
													foreach ($bht_putus as $row) {
														// Format tanggal Indonesia
														$tanggal_putus = $row->tanggal_putus ? date('d/m/Y', strtotime($row->tanggal_putus)) : '-';
														$bht = $row->bht ? date('d/m/Y', strtotime($row->bht)) : '-';
														$ikrar = $row->ikrar ? date('d/m/Y', strtotime($row->ikrar)) : '-';
														$target_bht = isset($row->target_bht) && $row->target_bht ? date('d/m/Y', strtotime($row->target_bht)) : '-';

														// Pemrosesan PBT dengan priority logic (pemberitahuan putusan > jadwal sidang)
														$pbt_display = '-';
														if (isset($row->tanggal_pemberitahuan_putusan) && $row->tanggal_pemberitahuan_putusan) {
															// Priority: Gunakan tanggal pemberitahuan putusan
															$pbt_display = '<span class="badge badge-primary badge-sm" title="Dari Pemberitahuan Putusan">' .
																date('d/m/Y', strtotime($row->tanggal_pemberitahuan_putusan)) . '</span>';
														} elseif ($row->pbt) {
															// Fallback: Gunakan jadwal sidang (multiple dates possible)
															$pbt_dates = explode('<br>', $row->pbt);
															if (count($pbt_dates) > 1) {
																$pbt_display = '<div class="multiple-dates">';
																foreach ($pbt_dates as $date) {
																	if (!empty($date)) {
																		$pbt_display .= '<span class="date-item badge badge-info badge-sm" title="Dari Jadwal Sidang">' .
																			date('d/m/Y', strtotime($date)) . '</span><br>';
																	}
																}
																$pbt_display .= '</div>';
															} else {
																$pbt_display = '<span class="badge badge-info badge-sm" title="Dari Jadwal Sidang">' .
																	date('d/m/Y', strtotime($row->pbt)) . '</span>';
															}
														}

														// Sumber PBT badge
														$sumber_pbt_badge = '';
														if (isset($row->sumber_pbt)) {
															if ($row->sumber_pbt == 'Dari Pemberitahuan Putusan') {
																$sumber_pbt_badge = '<span class="badge badge-primary badge-status" title="Data dari tabel pemberitahuan putusan"><i class="fas fa-certificate"></i> PP</span>';
															} else {
																$sumber_pbt_badge = '<span class="badge badge-info badge-status" title="Data dari jadwal sidang"><i class="fas fa-calendar-alt"></i> JS</span>';
															}
														}

														// Hari sejak PBT ke target BHT (selalu 14 hari)
														$hari_sejak_pbt_display = '-';
														if (isset($row->hari_sejak_pbt_ke_target)) {
															$hari = $row->hari_sejak_pbt_ke_target;
															// Untuk hari sejak PBT ke target, selalu hijau karena menunjukkan jangka waktu tetap 14 hari
															$badge_class = 'badge-primary';

															$hari_sejak_pbt_display = '<span class="badge ' . $badge_class . ' badge-status" title="Jangka waktu dari PBT ke target BHT: ' . $hari . ' hari">' . $hari . ' hari</span>';
														}

														// Sisa hari ke target BHT dari hari ini
														$sisa_hari_display = '-';
														if (isset($row->sisa_hari_ke_target)) {
															$sisa = $row->sisa_hari_ke_target;
															$badge_class = 'badge-secondary';
															if ($sisa > 0) $badge_class = 'badge-success';      // Masih ada waktu
															elseif ($sisa == 0) $badge_class = 'badge-warning'; // Hari ini deadline
															elseif ($sisa >= -7) $badge_class = 'badge-danger'; // Terlambat 1-7 hari
															else $badge_class = 'badge-dark';                   // Sangat terlambat

															if ($sisa > 0) {
																$sisa_hari_display = '<span class="badge ' . $badge_class . ' badge-status" title="Sisa ' . $sisa . ' hari ke target BHT">' . $sisa . ' hari lagi</span>';
															} elseif ($sisa == 0) {
																$sisa_hari_display = '<span class="badge ' . $badge_class . ' badge-status" title="Hari ini adalah deadline target BHT">Hari ini</span>';
															} else {
																$sisa_hari_display = '<span class="badge ' . $badge_class . ' badge-status" title="Terlambat ' . abs($sisa) . ' hari dari target BHT">Terlambat ' . abs($sisa) . ' hari</span>';
															}
														}

														// Status badge
														$status_class = $row->status == 'SELESAI' ? 'badge-success' : 'badge-warning';
														$bht_status_class = $row->status_bht == 'SUDAH BHT' ? 'badge-success' : ($row->status_bht == 'BELUM BHT' ? 'badge-warning' : 'badge-secondary');
													?>
														<tr>
															<td class="text-center"><?= $no++ ?></td>
															<td><?= $tanggal_putus ?></td>
															<td class="font-weight-bold text-primary"><?= $row->nomor_perkara ?></td>
															<td>
																<span class="badge badge-info badge-status"><?= $row->jenis_perkara ?></span>
															</td>
															<td><?= $row->panitera_pengganti_nama ?: '-' ?></td>
															<td><?= $row->jurusita_pengganti_nama ?: '-' ?></td>
															<td class="text-center">
																<?= $pbt_display ?>
															</td>
															<td class="text-center">
																<?= $sumber_pbt_badge ?>
															</td>
															<td class="text-center">
																<?php if ($target_bht != '-'): ?>
																	<span class="badge badge-warning badge-sm"><?= $target_bht ?></span>
																<?php else: ?>
																	<span class="text-muted">-</span>
																<?php endif; ?>
															</td>
															<td class="text-center">
																<?= $hari_sejak_pbt_display ?>
															</td>
															<td class="text-center">
																<?= $sisa_hari_display ?>
															</td>
															<td class="text-center">
																<?php if ($row->bht): ?>
																	<span class="text-success font-weight-bold"><?= $bht ?></span>
																<?php else: ?>
																	<span class="text-muted">-</span>
																<?php endif; ?>
															</td>
															<td class="text-center"><?= $ikrar ?></td>
															<td class="text-center">
																<span class="badge <?= $bht_status_class ?> badge-status"><?= $row->status_bht ?></span>
															</td>
															<td class="text-center">
																<span class="badge <?= $status_class ?> badge-status"><?= $row->status ?></span>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									<?php else: ?>
										<div class="alert alert-info text-center">
											<i class="fas fa-info-circle fa-2x mb-3"></i>
											<h5>Belum Ada Data</h5>
											<p>Silakan pilih filter periode dan jenis perkara untuk menampilkan data BHT perkara putus.</p>
											<?php if (!empty($nomor_perkara)): ?>
												<p class="text-muted">Filter pencarian: <strong>"<?= htmlspecialchars($nomor_perkara) ?>"</strong></p>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<!-- jQuery -->
	<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- DataTables  & Plugins -->
	<script src="<?php echo base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/jszip/jszip.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
	<!-- Select2 -->
	<script src="<?php echo base_url() ?>assets/plugins/select2/js/select2.full.min.js"></script>
	<!-- DateRangePicker -->
	<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url() ?>assets/dist/js/adminlte.min.js"></script>

	<script>
		$(function() {
			// Initialize Select2
			$('.select2').select2({
				theme: 'bootstrap4'
			});

			// Initialize DataTable
			$("#bhtTable3").DataTable({
				"responsive": true,
				"lengthChange": true,
				"autoWidth": false,
				"pageLength": 25,
				"lengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "Semua"]
				],
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
				},
				"buttons": [{
						extend: 'excel',
						text: '<i class="fas fa-file-excel"></i> Excel',
						className: 'btn btn-success btn-sm',
						title: 'BHT Perkara Putus 3 <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>(<?= date("d-m-Y", strtotime($tanggal_awal)) ?> - <?= date("d-m-Y", strtotime($tanggal_akhir)) ?>)<?php else: ?><?= isset($months) ? $months[$lap_bulan] : "" ?> <?= $lap_tahun ?><?php endif; ?>'
					},
					{
						extend: 'pdf',
						text: '<i class="fas fa-file-pdf"></i> PDF',
						className: 'btn btn-danger btn-sm',
						title: 'BHT Perkara Putus 3 <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>(<?= date("d-m-Y", strtotime($tanggal_awal)) ?> - <?= date("d-m-Y", strtotime($tanggal_akhir)) ?>)<?php else: ?><?= isset($months) ? $months[$lap_bulan] : "" ?> <?= $lap_tahun ?><?php endif; ?>',
						orientation: 'landscape',
						pageSize: 'A4'
					},
					{
						extend: 'print',
						text: '<i class="fas fa-print"></i> Print',
						className: 'btn btn-info btn-sm'
					}
				],
				"dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
					'<"row"<"col-sm-12"B>>' +
					'<"row"<"col-sm-12"tr>>' +
					'<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
			});

			// ✅ PERBAIKAN 1: Enhanced Tab Switching Logic
			$('#searchTabs a').on('click', function(e) {
				e.preventDefault();

				var targetTab = $(this).attr('href');
				console.log('🔄 Switching to tab:', targetTab); // Debug log

				// Show the selected tab
				$(this).tab('show');

				// Smart form field management
				if (targetTab === '#monthly') {
					// Switching to Monthly tab - clear date inputs and enable month selectors
					console.log('📅 Activating Monthly Mode');

					// Clear date range inputs
					$('input[name="tanggal_awal"]').val('').removeClass('is-invalid');
					$('input[name="tanggal_akhir"]').val('').removeClass('is-invalid');

					// Enable and set default values for month/year if empty
					var monthSelect = $('select[name="lap_bulan"]');
					var yearSelect = $('select[name="lap_tahun"]');

					// Remove disabled state if any
					monthSelect.prop('disabled', false);
					yearSelect.prop('disabled', false);

					// Set current month/year if empty
					if (!monthSelect.val()) {
						monthSelect.val('<?= date('m') ?>').trigger('change');
					}
					if (!yearSelect.val()) {
						yearSelect.val('<?= date('Y') ?>').trigger('change');
					}

				} else if (targetTab === '#daterange') {
					// Switching to Date Range tab - clear month selectors
					console.log('📊 Activating Date Range Mode');

					// Clear month/year selections
					$('select[name="lap_bulan"]').val('').trigger('change');
					$('select[name="lap_tahun"]').val('').trigger('change');

					// Focus on first date input for better UX
					setTimeout(function() {
						$('input[name="tanggal_awal"]').focus();
					}, 300);
				}

				// Visual feedback
				showTabSwitchFeedback(targetTab);
			});

			// ✅ PERBAIKAN 2: Form State Validation
			$('form').on('submit', function(e) {
				var activeTab = $('.nav-tabs .nav-link.active').attr('href');
				var isValid = true;
				var errorMsg = '';

				if (activeTab === '#monthly') {
					// Validate monthly form
					var bulan = $('select[name="lap_bulan"]').val();
					var tahun = $('select[name="lap_tahun"]').val();

					if (!bulan || !tahun) {
						isValid = false;
						errorMsg = 'Harap pilih bulan dan tahun untuk pencarian!';

						// Highlight empty fields
						if (!bulan) $('select[name="lap_bulan"]').addClass('is-invalid');
						if (!tahun) $('select[name="lap_tahun"]').addClass('is-invalid');
					}

				} else if (activeTab === '#daterange') {
					// Validate date range form
					var tanggalAwal = $('input[name="tanggal_awal"]').val();
					var tanggalAkhir = $('input[name="tanggal_akhir"]').val();

					if (!tanggalAwal || !tanggalAkhir) {
						isValid = false;
						errorMsg = 'Harap isi tanggal awal dan tanggal akhir!';

						// Highlight empty fields
						if (!tanggalAwal) $('input[name="tanggal_awal"]').addClass('is-invalid');
						if (!tanggalAkhir) $('input[name="tanggal_akhir"]').addClass('is-invalid');
					} else if (new Date(tanggalAwal) > new Date(tanggalAkhir)) {
						isValid = false;
						errorMsg = 'Tanggal awal tidak boleh lebih besar dari tanggal akhir!';
						$('input[name="tanggal_awal"], input[name="tanggal_akhir"]').addClass('is-invalid');
					}
				}

				if (!isValid) {
					e.preventDefault();
					showAlert('warning', 'Validasi Form', errorMsg);
					return false;
				}

				// Show loading indicator
				showAlert('info', 'Memproses', 'Sedang memuat data...');
			});

			// Remove validation classes when user starts typing/selecting
			$('input, select').on('input change', function() {
				$(this).removeClass('is-invalid');
			});
		});

		// ✅ PERBAIKAN 3: Enhanced Reset Function with Cache Clearing
		function resetForm() {
			console.log('🔄 Resetting form and clearing browser cache...');

			// Enhanced confirmation with cache clearing info
			if (confirm('Apakah Anda yakin ingin mereset form dan menghapus cache browser?\n\nIni akan:\n• Reset semua form ke nilai default\n• Hapus autocomplete history\n• Clear local storage\n• Force refresh halaman')) {

				// Show loading indicator
				showAlert('info', 'Membersihkan Cache', 'Sedang mereset form dan menghapus cache browser...');

				// ✅ STEP 1: Clear Form Data Cache
				console.log('🧹 Step 1: Clearing form data...');
				clearFormDataCache();

				// ✅ STEP 2: Clear Browser Storage Cache
				console.log('🧹 Step 2: Clearing browser storage...');
				clearBrowserStorageCache();

				// ✅ STEP 3: Clear Autocomplete Cache
				console.log('🧹 Step 3: Clearing autocomplete cache...');
				clearAutocompleteCache();

				// ✅ STEP 4: Reset Form to Default Values
				console.log('🧹 Step 4: Resetting to defaults...');
				resetToDefaultValues();

				// ✅ STEP 5: Clear DOM Cache & Memory
				console.log('🧹 Step 5: Clearing DOM cache...');
				clearDOMCache();

				// ✅ STEP 6: Force Page Refresh (Optional)
				setTimeout(function() {
					if (confirm('Apakah Anda ingin me-refresh halaman untuk pembersihan cache yang lebih menyeluruh?')) {
						// Add timestamp to URL to prevent cache
						var currentUrl = window.location.href;
						var separator = (currentUrl.indexOf('?') === -1) ? '?' : '&';
						var newUrl = currentUrl + separator + '_cache_bust=' + new Date().getTime();

						console.log('🔄 Force refreshing page...');
						window.location.href = newUrl;
					} else {
						showAlert('success', 'Reset & Cache Clear Berhasil', 'Form telah direset dan cache browser telah dibersihkan!');
					}
				}, 1500);

				console.log('✅ Form reset and cache clearing completed');
			}
		}

		// ✅ STEP 1 FUNCTION: Clear Form Data Cache
		function clearFormDataCache() {
			try {
				// Clear all form inputs
				$('input[name="nomor_perkara"]').val('');
				$('input[name="tanggal_awal"]').val('');
				$('input[name="tanggal_akhir"]').val('');

				// Clear all select elements first (before setting defaults)
				$('select[name="jenis_perkara"]').val('');
				$('select[name="lap_bulan"]').val('');
				$('select[name="lap_tahun"]').val('');

				// Force clear Select2 cache if exists
				if (typeof $.fn.select2 !== 'undefined') {
					$('.select2').select2('destroy').select2({
						theme: 'bootstrap4'
					});
				}

				// Clear any hidden inputs that might store cache
				$('input[type="hidden"]').val('');

				// Remove any validation error classes
				$('.is-invalid').removeClass('is-invalid');

				console.log('✅ Form data cache cleared');
			} catch (e) {
				console.error('❌ Error clearing form data cache:', e);
			}
		}

		// ✅ STEP 2 FUNCTION: Clear Browser Storage Cache
		function clearBrowserStorageCache() {
			try {
				// Clear localStorage for this domain
				if (typeof(Storage) !== "undefined") {
					// Get all localStorage keys related to this form
					var keysToRemove = [];
					for (var i = 0; i < localStorage.length; i++) {
						var key = localStorage.key(i);
						// Remove keys related to form, BHT, or autocomplete
						if (key && (key.includes('bht') || key.includes('form') || key.includes('search') || key.includes('filter'))) {
							keysToRemove.push(key);
						}
					}

					// Remove identified keys
					keysToRemove.forEach(function(key) {
						localStorage.removeItem(key);
						console.log('🗑️ Removed localStorage key:', key);
					});

					// Clear sessionStorage for this session
					if (sessionStorage) {
						var sessionKeysToRemove = [];
						for (var i = 0; i < sessionStorage.length; i++) {
							var key = sessionStorage.key(i);
							if (key && (key.includes('bht') || key.includes('form') || key.includes('search') || key.includes('filter'))) {
								sessionKeysToRemove.push(key);
							}
						}

						sessionKeysToRemove.forEach(function(key) {
							sessionStorage.removeItem(key);
							console.log('🗑️ Removed sessionStorage key:', key);
						});
					}
				}

				console.log('✅ Browser storage cache cleared');
			} catch (e) {
				console.error('❌ Error clearing browser storage cache:', e);
			}
		}

		// ✅ STEP 3 FUNCTION: Clear Autocomplete Cache
		function clearAutocompleteCache() {
			try {
				// Method 1: Set autocomplete="off" and then back to "on"
				$('input, select').attr('autocomplete', 'off');

				setTimeout(function() {
					$('input, select').attr('autocomplete', 'on');
				}, 100);

				// Method 2: Clear input history by changing name temporarily
				$('input').each(function() {
					var originalName = $(this).attr('name');
					if (originalName) {
						$(this).attr('name', originalName + '_temp_' + Math.random());
						setTimeout(() => {
							$(this).attr('name', originalName);
						}, 50);
					}
				});

				// Method 3: Force clear browser form data
				if (document.forms && document.forms.length > 0) {
					for (var i = 0; i < document.forms.length; i++) {
						try {
							document.forms[i].reset();
						} catch (e) {
							console.log('Form reset attempt:', e);
						}
					}
				}

				console.log('✅ Autocomplete cache cleared');
			} catch (e) {
				console.error('❌ Error clearing autocomplete cache:', e);
			}
		}

		// ✅ STEP 4 FUNCTION: Reset to Default Values
		function resetToDefaultValues() {
			try {
				// Set default values (after clearing cache)
				$('select[name="jenis_perkara"]').val('Pdt.G').trigger('change');
				$('select[name="lap_bulan"]').val('<?= date('m') ?>').trigger('change');
				$('select[name="lap_tahun"]').val('<?= date('Y') ?>').trigger('change');

				// Reset to monthly tab
				$('#monthly-tab').tab('show');

				// Trigger change events to update any dependent elements
				$('select').trigger('change');

				console.log('✅ Default values restored');
			} catch (e) {
				console.error('❌ Error setting default values:', e);
			}
		}

		// ✅ STEP 5 FUNCTION: Clear DOM Cache & Memory
		function clearDOMCache() {
			try {
				// Clear jQuery cache
				if ($ && $.cache) {
					$.cache = {};
				}

				// Force garbage collection if available (Chrome DevTools)
				if (window.gc) {
					window.gc();
				}

				// Clear any DataTable cache if exists
				if (typeof $.fn.dataTable !== 'undefined') {
					$.fn.dataTable.tables({
						visible: false,
						api: true
					}).columns.adjust();
				}

				// Clear any cached AJAX requests
				if ($ && $.ajaxSettings) {
					$.ajaxSettings.cache = false;
				}

				// Force DOM reflow
				document.body.style.display = 'none';
				document.body.offsetHeight; // Trigger reflow
				document.body.style.display = '';

				console.log('✅ DOM cache cleared');
			} catch (e) {
				console.error('❌ Error clearing DOM cache:', e);
			}
		}

		// ✅ UTILITY FUNCTIONS for Better UX
		function showTabSwitchFeedback(targetTab) {
			var tabName = (targetTab === '#monthly') ? 'Pencarian Bulanan' : 'Pencarian Range Tanggal';

			// Create temporary feedback element
			var feedback = $('<div class="alert alert-info alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
				'<i class="fas fa-info-circle"></i> Beralih ke mode: <strong>' + tabName + '</strong>' +
				'<button type="button" class="close" data-dismiss="alert">' +
				'<span>&times;</span></button></div>');

			$('body').append(feedback);

			// Auto remove after 3 seconds
			setTimeout(function() {
				feedback.fadeOut(function() {
					$(this).remove();
				});
			}, 3000);
		}

		function showAlert(type, title, message) {
			var alertClass = 'alert-' + type;
			var iconClass = {
				'success': 'fas fa-check-circle',
				'info': 'fas fa-info-circle',
				'warning': 'fas fa-exclamation-triangle',
				'danger': 'fas fa-times-circle'
			};

			var alert = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 350px;">' +
				'<i class="' + iconClass[type] + '"></i> <strong>' + title + ':</strong> ' + message +
				'<button type="button" class="close" data-dismiss="alert">' +
				'<span>&times;</span></button></div>');

			$('body').append(alert);

			// Auto remove after 5 seconds for info/success, 8 seconds for warning/danger
			var timeout = (type === 'info' || type === 'success') ? 5000 : 8000;
			setTimeout(function() {
				alert.fadeOut(function() {
					$(this).remove();
				});
			}, timeout);
		}

		// ✅ ADDITIONAL FUNCTION: Hard Reset with Immediate Page Refresh
		function hardResetForm() {
			console.log('💥 Hard reset initiated...');

			if (confirm('HARD RESET akan:\n\n• Menghapus SEMUA cache browser\n• Me-refresh halaman secara paksa\n• Kembali ke pengaturan awal\n\nLanjutkan?')) {

				// Show loading
				showAlert('warning', 'Hard Reset', 'Menghapus semua cache dan me-refresh halaman...');

				// Clear all possible caches immediately
				try {
					// Clear all storage
					if (typeof(Storage) !== "undefined") {
						localStorage.clear();
						sessionStorage.clear();
					}

					// Clear cookies related to this page
					document.cookie.split(";").forEach(function(c) {
						document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
					});

					console.log('💥 All cache cleared, refreshing page...');

					// Force refresh with cache busting
					setTimeout(function() {
						// Multiple methods to ensure cache is cleared
						window.location.reload(true); // Force reload from server

						// Fallback if above doesn't work
						window.location.href = window.location.href.split('?')[0] + '?cache_bust=' + Date.now();
					}, 1000);

				} catch (e) {
					console.error('❌ Hard reset error:', e);
					// Fallback: simple page refresh
					window.location.reload(true);
				}
			}
		}

		// ✅ ADDITIONAL FUNCTION: Show Cache Information
		function showCacheInfo() {
			try {
				var cacheInfo = {
					localStorage: 0,
					sessionStorage: 0,
					cookies: 0,
					formData: 0
				};

				// Count localStorage items
				if (typeof(Storage) !== "undefined" && localStorage) {
					cacheInfo.localStorage = localStorage.length;
				}

				// Count sessionStorage items
				if (typeof(Storage) !== "undefined" && sessionStorage) {
					cacheInfo.sessionStorage = sessionStorage.length;
				}

				// Count cookies
				cacheInfo.cookies = document.cookie.split(';').length;

				// Count form inputs with values
				$('input, select, textarea').each(function() {
					if ($(this).val() && $(this).val() !== '') {
						cacheInfo.formData++;
					}
				});

				var infoMessage =
					'📊 INFORMASI CACHE BROWSER:\n\n' +
					'🗄️ Local Storage: ' + cacheInfo.localStorage + ' items\n' +
					'📝 Session Storage: ' + cacheInfo.sessionStorage + ' items\n' +
					'🍪 Cookies: ' + cacheInfo.cookies + ' items\n' +
					'📋 Form Data: ' + cacheInfo.formData + ' fields\n\n' +
					'💡 Gunakan "Reset + Clear Cache" untuk membersihkan cache\n' +
					'💥 Gunakan "Hard Reset" untuk pembersihan menyeluruh + refresh halaman';

				alert(infoMessage);

				// Log details to console for debugging
				console.log('📊 Cache Information Details:');
				console.log('localStorage items:', cacheInfo.localStorage);
				console.log('sessionStorage items:', cacheInfo.sessionStorage);
				console.log('cookies:', document.cookie);
				console.log('form data count:', cacheInfo.formData);

			} catch (e) {
				console.error('❌ Error getting cache info:', e);
				alert('❌ Tidak dapat mengambil informasi cache: ' + e.message);
			}
		}

		// ✅ ADDITIONAL FUNCTION: Auto Cache Monitoring (Optional)
		function startCacheMonitoring() {
			// Monitor form changes and auto-clear old cache periodically
			setInterval(function() {
				try {
					// Clear old localStorage items (older than 1 day)
					if (typeof(Storage) !== "undefined") {
						var now = new Date().getTime();
						var oneDay = 24 * 60 * 60 * 1000; // 1 day in milliseconds

						for (var i = localStorage.length - 1; i >= 0; i--) {
							var key = localStorage.key(i);
							if (key && key.includes('_timestamp_')) {
								try {
									var timestamp = parseInt(localStorage.getItem(key));
									if (timestamp < (now - oneDay)) {
										localStorage.removeItem(key);
										console.log('🧹 Auto-cleared old cache:', key);
									}
								} catch (e) {
									// If parsing fails, remove the item
									localStorage.removeItem(key);
								}
							}
						}
					}
				} catch (e) {
					console.log('Cache monitoring error:', e);
				}
			}, 30000); // Check every 30 seconds
		}

		// Start cache monitoring when page loads
		$(document).ready(function() {
			startCacheMonitoring();

			// Store page load timestamp for cache monitoring
			if (typeof(Storage) !== "undefined") {
				localStorage.setItem('bht_page_load_timestamp_' + Date.now(), Date.now().toString());
			}
		});
	</script>
</body>

</html>