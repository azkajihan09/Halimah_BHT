<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>BHT Perkara Putus | SIPP</title>

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
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/adminlte.min.css">

	<style>
		.table th {
			background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
							<h1 class="m-0 text-dark"><i class="fas fa-file-signature mr-2"></i> BHT Perkara Putus</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Home</a></li>
								<li class="breadcrumb-item active">BHT Perkara Putus</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<!-- Filter Card -->
					<div class="card card-success card-outline">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body">
							<form action="<?php echo base_url() ?>index.php/Bht_putus" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Perkara:</label>
									<div class="col-sm-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
											</div>
											<select name="jenis_perkara" class="form-control select2" required>
												<option value="Pdt.G" <?php echo ($jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Perkara Gugatan (Pdt.G)</option>
												<option value="Pdt.P" <?php echo ($jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Perkara Permohonan (Pdt.P)</option>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Periode:</label>
									<div class="col-sm-10">
										<div class="row">
											<div class="col-md-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<select name="lap_bulan" class="form-control select2" required>
														<option value="">-- Pilih Bulan --</option>
														<?php
														$months = [
															'01' => 'Januari',
															'02' => 'Februari',
															'03' => 'Maret',
															'04' => 'April',
															'05' => 'Mei',
															'06' => 'Juni',
															'07' => 'Juli',
															'08' => 'Agustus',
															'09' => 'September',
															'10' => 'Oktober',
															'11' => 'November',
															'12' => 'Desember'
														];
														foreach ($months as $value => $label) {
															$selected = ($lap_bulan === $value) ? 'selected' : '';
															echo "<option value=\"$value\" $selected>$label</option>";
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-check"></i></span>
													</div>
													<select name="lap_tahun" class="form-control select2" required>
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
								</div>

								<div class="form-group row">
									<div class="col-sm-10 offset-sm-2">
										<button type="submit" name="btn" value="Tampilkan" class="btn btn-success">
											<i class="fas fa-search mr-2"></i> Tampilkan Data
										</button>
										<button type="reset" class="btn btn-secondary">
											<i class="fas fa-undo mr-2"></i> Reset
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

					<!-- Main Data Table -->
					<div class="row">
						<div class="col-12">
							<div class="card card-outline card-success">
								<div class="card-header">
									<h3 class="card-title">
										<i class="fas fa-table mr-1"></i> Detail BHT Perkara Putus
										<?= isset($months) ? $months[$lap_bulan] : '' ?> <?= $lap_tahun ?>
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
											<table id="bhtTable" class="table table-bordered table-striped table-hover">
												<thead>
													<tr>
														<th class="text-center" style="width: 3%;">No</th>
														<th style="width: 10%;">Tanggal Putus</th>
														<th style="width: 12%;">Nomor Perkara</th>
														<th style="width: 10%;">Jenis Perkara</th>
														<th style="width: 15%;">Panitera Pengganti</th>
														<th style="width: 15%;">Juru Sita Pengganti</th>
														<th style="width: 8%;">PBT</th>
														<th style="width: 8%;">BHT</th>
														<th style="width: 8%;">Ikrar</th>
														<th style="width: 8%;">Status BHT</th>
														<th style="width: 8%;">Status</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$no = 1;
													foreach ($bht_putus as $row) {
														// Format tanggal Indonesia
														$tanggal_putus = $row->tanggal_putus ? date('d/m/Y', strtotime($row->tanggal_putus)) : '-';
														$pbt = $row->pbt ? date('d/m/Y', strtotime($row->pbt)) : 'Tidak';
														$bht = $row->bht ? date('d/m/Y', strtotime($row->bht)) : '-';
														$ikrar = $row->ikrar ? date('d/m/Y', strtotime($row->ikrar)) : '-';

														// Status badge
														$status_class = $row->status == 'SELESAI' ? 'badge-success' : 'badge-warning';
														$bht_status_class = $row->status_bht == 'SUDAH BHT' ? 'badge-success' : ($row->status_bht == 'BELUM BHT' ? 'badge-warning' : 'badge-secondary');
													?>
														<tr>
															<td class="text-center"><?= $no++ ?></td>
															<td><?= $tanggal_putus ?></td>
															<td class="font-weight-bold"><?= $row->nomor_perkara ?></td>
															<td>
																<span class="badge badge-info badge-status"><?= $row->jenis_perkara ?></span>
															</td>
															<td><?= $row->panitera_pengganti_nama ?: '-' ?></td>
															<td><?= $row->jurusita_pengganti_nama ?: '-' ?></td>
															<td class="text-center">
																<?php if ($row->pbt): ?>
																	<span class="text-success"><?= $pbt ?></span>
																<?php else: ?>
																	<span class="text-muted">Tidak</span>
																<?php endif; ?>
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
	<!-- AdminLTE App -->
	<script src="<?php echo base_url() ?>assets/dist/js/adminlte.min.js"></script>

	<script>
		$(function() {
			// Initialize Select2
			$('.select2').select2({
				theme: 'bootstrap4'
			});

			// Initialize DataTable
			$("#bhtTable").DataTable({
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
						title: 'BHT Perkara Putus <?= isset($months) ? $months[$lap_bulan] : '' ?> <?= $lap_tahun ?>'
					},
					{
						extend: 'pdf',
						text: '<i class="fas fa-file-pdf"></i> PDF',
						className: 'btn btn-danger btn-sm',
						title: 'BHT Perkara Putus <?= isset($months) ? $months[$lap_bulan] : '' ?> <?= $lap_tahun ?>',
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
		});
	</script>
</body>

</html>