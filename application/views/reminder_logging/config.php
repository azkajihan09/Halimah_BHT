<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?> - Sistem Reminder BHT</title>

	<!-- AdminLTE CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">

	<style>
		.config-section {
			border-left: 4px solid #007bff;
			margin-bottom: 2rem;
		}

		.config-description {
			font-size: 0.9em;
			color: #6c757d;
			margin-top: 0.25rem;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<span class="navbar-text">
						<i class="fas fa-clock"></i> <?= date('d/m/Y H:i:s') ?>
					</span>
				</li>
			</ul>
		</nav>

		<!-- Sidebar -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<a href="<?= base_url('index.php/reminder_logging') ?>" class="brand-link">
				<i class="fas fa-bell brand-image"></i>
				<span class="brand-text font-weight-light">Reminder BHT</span>
			</a>

			<div class="sidebar">
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
						<li class="nav-item">
							<a href="<?= base_url('index.php/reminder_logging') ?>" class="nav-link">
								<i class="nav-icon fas fa-tachometer-alt"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('index.php/reminder_logging/perkara_list') ?>" class="nav-link">
								<i class="nav-icon fas fa-list"></i>
								<p>Daftar Perkara</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('index.php/reminder_logging/sync_manual') ?>" class="nav-link">
								<i class="nav-icon fas fa-sync"></i>
								<p>Sync Manual</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('index.php/reminder_logging/config') ?>" class="nav-link active">
								<i class="nav-icon fas fa-cogs"></i>
								<p>Konfigurasi</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('index.php/bht_reminder') ?>" class="nav-link">
								<i class="nav-icon fas fa-arrow-left"></i>
								<p>Kembali ke SIPP</p>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</aside>

		<!-- Content Wrapper -->
		<div class="content-wrapper">
			<!-- Content Header -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0">⚙️ <?= $title ?></h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= base_url('index.php/reminder_logging') ?>">Dashboard</a></li>
								<li class="breadcrumb-item active">Konfigurasi</li>
							</ol>
						</div>
					</div>
				</div>
			</div>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">

					<!-- Flash Messages -->
					<?php if (isset($flash_success) && $flash_success): ?>
						<div class="alert alert-success alert-dismissible fade show">
							<i class="fas fa-check-circle"></i> <?= $flash_success ?>
							<button type="button" class="close" data-dismiss="alert">×</button>
						</div>
					<?php endif; ?>

					<?php if (isset($flash_error) && $flash_error): ?>
						<div class="alert alert-danger alert-dismissible fade show">
							<i class="fas fa-exclamation-triangle"></i> <?= $flash_error ?>
							<button type="button" class="close" data-dismiss="alert">×</button>
						</div>
					<?php endif; ?>

					<!-- Configuration Form -->
					<form method="post" action="<?= base_url('index.php/reminder_logging/config') ?>">

						<!-- Sinkronisasi -->
						<div class="card config-section">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-sync"></i> Pengaturan Sinkronisasi</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="auto_sync_enabled">
												<input type="checkbox" id="auto_sync_enabled" name="auto_sync_enabled" value="1"
													<?= (isset($config['auto_sync_enabled']) ? $config['auto_sync_enabled'] : '0') == '1' ? 'checked' : '' ?>>
												Auto Sync Enabled
											</label>
											<div class="config-description">
												Aktifkan sinkronisasi otomatis data dari SIPP
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="sync_interval_minutes">Interval Sync (Menit)</label>
											<input type="number" class="form-control" id="sync_interval_minutes"
												name="sync_interval_minutes" value="<?= isset($config['sync_interval_minutes']) ? $config['sync_interval_minutes'] : '60' ?>"
												min="5" max="1440">
											<div class="config-description">
												Interval waktu untuk sinkronisasi otomatis (5-1440 menit)
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Priority Thresholds -->
						<div class="card config-section">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Threshold Level Prioritas</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="critical_days_threshold">
												<span class="badge badge-danger">CRITICAL</span> Days Threshold
											</label>
											<input type="number" class="form-control" id="critical_days_threshold"
												name="critical_days_threshold" value="<?= isset($config['critical_days_threshold']) ? $config['critical_days_threshold'] : '21' ?>"
												min="1" max="100">
											<div class="config-description">
												Batas hari untuk status CRITICAL (default: 21 hari)
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="kritis_days_threshold">
												<span class="badge badge-warning">KRITIS</span> Days Threshold
											</label>
											<input type="number" class="form-control" id="kritis_days_threshold"
												name="kritis_days_threshold" value="<?= isset($config['kritis_days_threshold']) ? $config['kritis_days_threshold'] : '14' ?>"
												min="1" max="100">
											<div class="config-description">
												Batas hari untuk status KRITIS (default: 14 hari)
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="peringatan_days_threshold">
												<span class="badge badge-info">PERINGATAN</span> Days Threshold
											</label>
											<input type="number" class="form-control" id="peringatan_days_threshold"
												name="peringatan_days_threshold" value="<?= isset($config['peringatan_days_threshold']) ? $config['peringatan_days_threshold'] : '7' ?>"
												min="1" max="100">
											<div class="config-description">
												Batas hari untuk status PERINGATAN (default: 7 hari)
											</div>
										</div>
									</div>
								</div>

								<div class="alert alert-info">
									<i class="fas fa-info-circle"></i>
									<strong>Logika Priority:</strong>
									<ul class="mb-0 mt-2">
										<li>Hari ≥ Critical → <span class="badge badge-danger">CRITICAL</span></li>
										<li>Hari ≥ Kritis → <span class="badge badge-warning">KRITIS</span></li>
										<li>Hari ≥ Peringatan → <span class="badge badge-info">PERINGATAN</span></li>
										<li>Hari < Peringatan → <span class="badge badge-success">NORMAL</span></li>
									</ul>
								</div>
							</div>
						</div>

						<!-- Email Notifications -->
						<div class="card config-section">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-envelope"></i> Notifikasi Email</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="enable_email_notification">
												<input type="checkbox" id="enable_email_notification" name="enable_email_notification" value="1"
													<?= (isset($config['enable_email_notification']) ? $config['enable_email_notification'] : '0') == '1' ? 'checked' : '' ?>>
												Enable Email Notification
											</label>
											<div class="config-description">
												Aktifkan notifikasi email untuk reminder penting
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="admin_email">Admin Email</label>
											<input type="email" class="form-control" id="admin_email"
												name="admin_email" value="<?= isset($config['admin_email']) ? $config['admin_email'] : '' ?>"
												placeholder="admin@example.com">
											<div class="config-description">
												Alamat email admin untuk menerima notifikasi
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Save Button -->
						<div class="card">
							<div class="card-body text-center">
								<button type="submit" class="btn btn-primary btn-lg">
									<i class="fas fa-save"></i> Simpan Konfigurasi
								</button>
								<a href="<?= base_url('index.php/reminder_logging') ?>" class="btn btn-secondary btn-lg ml-2">
									<i class="fas fa-times"></i> Batal
								</a>
							</div>
						</div>

					</form>

				</div>
			</section>
		</div>

		<!-- Footer -->
		<footer class="main-footer">
			<strong>Copyright &copy; 2025 <a href="#">Sistem Reminder BHT</a>.</strong>
			All rights reserved.
			<div class="float-right d-none d-sm-inline-block">
				<b>Version</b> 1.0.0
			</div>
		</footer>
	</div>

	<!-- Scripts -->
	<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>

	<script>
		$(document).ready(function() {
			// Auto-enable sync interval when auto sync is checked
			$('#auto_sync_enabled').change(function() {
				if ($(this).is(':checked')) {
					$('#sync_interval_minutes').prop('disabled', false).focus();
				} else {
					// $('#sync_interval_minutes').prop('disabled', true);
				}
			}).trigger('change');

			// Validate thresholds
			$('form').submit(function() {
				var critical = parseInt($('#critical_days_threshold').val());
				var kritis = parseInt($('#kritis_days_threshold').val());
				var peringatan = parseInt($('#peringatan_days_threshold').val());

				if (critical <= kritis || kritis <= peringatan) {
					alert('Error: Critical threshold harus lebih besar dari Kritis, dan Kritis harus lebih besar dari Peringatan!');
					return false;
				}

				return true;
			});
		});
	</script>
</body>

</html>