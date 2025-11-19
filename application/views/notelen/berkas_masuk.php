<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= $title ?> | Notelen System</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/dist/css/bootstrap.min.css') ?>">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url('assets/plugins/font-awesome/css/font-awesome.min.css') ?>">
	<!-- AdminLTE -->
	<link rel="stylesheet" href="<?= base_url('assets/build/css/AdminLTE.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/build/css/skins/_all-skins.min.css') ?>">
	<!-- DatePicker -->
	<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

	<!-- Custom Notelen CSS -->
	<style>
		.box-stats {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			border-radius: 10px;
		}

		.stats-icon {
			font-size: 3em;
			opacity: 0.8;
		}

		.berkas-card {
			border: 1px solid #ddd;
			border-radius: 8px;
			margin-bottom: 15px;
			transition: all 0.3s;
		}

		.berkas-card:hover {
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			border-color: #3c8dbc;
		}

		.status-badge {
			font-size: 0.85em;
			padding: 4px 8px;
			border-radius: 12px;
		}

		.status-MASUK {
			background: #5cb85c;
			color: white;
		}

		.status-PROSES {
			background: #f0ad4e;
			color: white;
		}

		.status-SELESAI {
			background: #5bc0de;
			color: white;
		}

		.status-KELUAR {
			background: #d9534f;
			color: white;
		}

		.popup-form .form-group {
			margin-bottom: 15px;
		}

		.inventaris-list {
			max-height: 300px;
			overflow-y: auto;
		}

		.inventaris-item {
			padding: 8px;
			border: 1px solid #ddd;
			border-radius: 4px;
			margin-bottom: 5px;
			background: #f9f9f9;
		}

		.btn-floating {
			position: fixed;
			bottom: 30px;
			right: 30px;
			z-index: 1000;
			width: 60px;
			height: 60px;
			border-radius: 50%;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
		}
	</style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<!-- Header -->
		<header class="main-header">
			<a href="<?= base_url() ?>" class="logo">
				<span class="logo-mini"><b>N</b>TL</span>
				<span class="logo-lg"><b>Notelen</b> System</span>
			</a>

			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-user"></i>
								<span class="hidden-xs">Admin Notelen</span>
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<!-- Sidebar -->
		<aside class="main-sidebar">
			<section class="sidebar">
				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">MENU NOTELEN</li>

					<li class="<?= ($sidebar_active == 'notelen') ? 'active' : '' ?>">
						<a href="<?= base_url('notelen') ?>">
							<i class="fa fa-folder-open"></i>
							<span>Berkas Masuk</span>
						</a>
					</li>

					<li>
						<a href="<?= base_url('reminder_logging') ?>">
							<i class="fa fa-bell"></i>
							<span>BHT Reminder</span>
						</a>
					</li>

					<li class="treeview">
						<a href="#">
							<i class="fa fa-cog"></i>
							<span>Master Data</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="#" onclick="openMasterBarangModal()"><i class="fa fa-circle-o"></i> Master Barang</a></li>
						</ul>
					</li>
				</ul>
			</section>
		</aside>

		<!-- Content Wrapper -->
		<div class="content-wrapper">
			<!-- Content Header -->
			<section class="content-header">
				<h1>
					<?= $page_title ?>
					<small>Sistem inventaris berkas perkara putus</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Berkas Masuk Notelen</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">

				<!-- Stats Dashboard -->
				<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box box-stats">
							<span class="info-box-icon"><i class="fa fa-folder"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Total Berkas</span>
								<span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] ? $stats['berkas']->total_berkas : 0 ?></span>
							</div>
						</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box" style="background: #00a65a; color: white;">
							<span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Status Masuk</span>
								<span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] ? $stats['berkas']->status_masuk : 0 ?></span>
							</div>
						</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box" style="background: #f39c12; color: white;">
							<span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Status Proses</span>
								<span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] ? $stats['berkas']->status_proses : 0 ?></span>
							</div>
						</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box" style="background: #00c0ef; color: white;">
							<span class="info-box-icon"><i class="fa fa-archive"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Total Inventaris</span>
								<span class="info-box-number"><?= isset($stats['inventaris']) && $stats['inventaris'] ? $stats['inventaris']->total_barang : 0 ?></span>
							</div>
						</div>
					</div>
				</div>

				<!-- Filter & Controls -->
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Filter & Kontrol</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-success btn-sm" onclick="openNewBerkasModal()">
										<i class="fa fa-plus"></i> Tambah Berkas
									</button>
									<button type="button" class="btn btn-info btn-sm" onclick="syncFromSipp()">
										<i class="fa fa-refresh"></i> Sync SIPP
									</button>
								</div>
							</div>
							<div class="box-body">
								<form method="GET" class="form-inline">
									<div class="form-group">
										<label>Status:</label>
										<select name="status" class="form-control">
											<option value="">Semua Status</option>
											<option value="MASUK" <?= ($filters['status_berkas'] == 'MASUK') ? 'selected' : '' ?>>Masuk</option>
											<option value="PROSES" <?= ($filters['status_berkas'] == 'PROSES') ? 'selected' : '' ?>>Proses</option>
											<option value="SELESAI" <?= ($filters['status_berkas'] == 'SELESAI') ? 'selected' : '' ?>>Selesai</option>
											<option value="KELUAR" <?= ($filters['status_berkas'] == 'KELUAR') ? 'selected' : '' ?>>Keluar</option>
										</select>
									</div>

									<div class="form-group">
										<label>Nomor Perkara:</label>
										<input type="text" name="nomor" class="form-control"
											value="<?= $filters['nomor_perkara'] ?>"
											placeholder="Masukkan nomor perkara...">
									</div>

									<div class="form-group">
										<label>Tanggal:</label>
										<input type="date" name="dari" class="form-control" value="<?= $filters['tanggal_dari'] ?>">
										<span>s/d</span>
										<input type="date" name="sampai" class="form-control" value="<?= $filters['tanggal_sampai'] ?>">
									</div>

									<button type="submit" class="btn btn-primary">
										<i class="fa fa-search"></i> Filter
									</button>

									<a href="<?= base_url('notelen') ?>" class="btn btn-default">
										<i class="fa fa-refresh"></i> Reset
									</a>
								</form>
							</div>
						</div>
					</div>
				</div>

				<!-- Berkas List -->
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">
									Daftar Berkas Masuk Notelen
									<small>(<?= number_format($total_berkas) ?> berkas)</small>
								</h3>
								<div class="box-tools pull-right">
									<a href="<?= base_url('notelen/export?format=excel') ?>" class="btn btn-success btn-sm">
										<i class="fa fa-file-excel-o"></i> Export Excel
									</a>
								</div>
							</div>
							<div class="box-body">

								<?php if (!isset($berkas_list) || empty($berkas_list)): ?>
									<div class="text-center" style="padding: 40px;">
										<i class="fa fa-folder-open fa-3x text-muted"></i>
										<h4 class="text-muted">Belum ada berkas masuk</h4>
										<p class="text-muted">Klik tombol "Tambah Berkas" atau "Sync SIPP" untuk menambah data</p>
									</div>
								<?php else: ?>

									<div class="row">
										<?php foreach ($berkas_list as $berkas): ?>
											<div class="col-md-6 col-lg-4">
												<div class="berkas-card">
													<div class="box box-widget">
														<div class="box-header">
															<div class="user-block">
																<span class="username">
																	<strong><?= $berkas->nomor_perkara ?></strong>
																	<span class="status-badge status-<?= $berkas->status_berkas ?>">
																		<?= $berkas->status_berkas ?>
																	</span>
																</span>
																<span class="description"><?= $berkas->jenis_perkara ?: 'Perkara Umum' ?></span>
															</div>
															<div class="box-tools">
																<button type="button" class="btn btn-box-tool"
																	onclick="openBerkasDetail(<?= $berkas->id ?>)">
																	<i class="fa fa-eye"></i>
																</button>
															</div>
														</div>

														<div class="box-body" style="padding: 10px 15px;">
															<p class="text-sm">
																<i class="fa fa-calendar"></i>
																Putusan: <?= isset($berkas->tanggal_putusan) && $berkas->tanggal_putusan ? date('d/m/Y', strtotime($berkas->tanggal_putusan)) : '-' ?>
															</p>
															<p class="text-sm">
																<i class="fa fa-clock-o"></i>
																Masuk: <?= isset($berkas->tanggal_masuk_notelen) && $berkas->tanggal_masuk_notelen ? date('d/m/Y', strtotime($berkas->tanggal_masuk_notelen)) : '-' ?>
															</p>

															<?php if (!empty($berkas->majelis_hakim) && $berkas->majelis_hakim != '-'): ?>
																<p class="text-sm text-muted">
																	<i class="fa fa-user"></i>
																	<?= substr($berkas->majelis_hakim, 0, 30) ?>
																	<?= (strlen($berkas->majelis_hakim) > 30) ? '...' : '' ?>
																</p>
															<?php endif; ?>

															<div class="row text-center" style="margin-top: 10px;">
																<div class="col-xs-6">
																	<small class="text-muted">Inventaris</small><br>
																	<strong class="text-primary">
																		<?= $berkas->total_inventaris ?: 0 ?> jenis
																	</strong>
																</div>
																<div class="col-xs-6">
																	<small class="text-muted">Total Barang</small><br>
																	<strong class="text-success">
																		<?= $berkas->total_barang ?: 0 ?> item
																	</strong>
																</div>
															</div>
														</div>

														<div class="box-footer">
															<div class="row">
																<div class="col-xs-6">
																	<button type="button" class="btn btn-primary btn-sm btn-block"
																		onclick="openInventarisModal(<?= $berkas->id ?>, '<?= $berkas->nomor_perkara ?>')">
																		<i class="fa fa-plus"></i> Inventaris
																	</button>
																</div>
																<div class="col-xs-6">
																	<button type="button" class="btn btn-info btn-sm btn-block"
																		onclick="openBerkasDetail(<?= $berkas->id ?>)">
																		<i class="fa fa-eye"></i> Detail
																	</button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>

									<!-- Pagination -->
									<?php if ($total_pages > 1): ?>
										<div class="text-center">
											<ul class="pagination">
												<?php for ($i = 1; $i <= $total_pages; $i++): ?>
													<li class="<?= ($i == $current_page) ? 'active' : '' ?>">
														<a href="?page=<?= $i ?>&status=<?= $filters['status_berkas'] ?>&nomor=<?= $filters['nomor_perkara'] ?>&dari=<?= $filters['tanggal_dari'] ?>&sampai=<?= $filters['tanggal_sampai'] ?>">
															<?= $i ?>
														</a>
													</li>
												<?php endfor; ?>
											</ul>
										</div>
									<?php endif; ?>

								<?php endif; ?>

							</div>
						</div>
					</div>
				</div>

			</section>
		</div>

		<!-- Footer -->
		<footer class="main-footer">
			<strong>Copyright &copy; 2025 Notelen System.</strong>
			Sistem inventaris berkas perkara putus.
		</footer>
	</div>

	<!-- MODAL: New Berkas -->
	<div class="modal fade" id="newBerkasModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
						<i class="fa fa-plus"></i> Tambah Berkas Masuk Perkara
					</h4>
				</div>
				<form id="newBerkasForm" class="popup-form">
					<div class="modal-body">
						<div class="form-group">
							<label>Nomor Perkara *</label>
							<input type="text" name="nomor_perkara" class="form-control"
								placeholder="Contoh: 123/Pdt.G/2024/PA.Smg" required>
						</div>

						<div class="form-group">
							<label>Tanggal Putusan *</label>
							<input type="date" name="tanggal_putusan" class="form-control" required>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Jenis Perkara</label>
									<select name="jenis_perkara" class="form-control">
										<option value="">Pilih Jenis Perkara</option>
										<option value="Cerai Gugat">Cerai Gugat</option>
										<option value="Cerai Talak">Cerai Talak</option>
										<option value="Isbat Nikah">Isbat Nikah</option>
										<option value="Waris">Waris</option>
										<option value="Wakaf">Wakaf</option>
										<option value="Ekonomi Syariah">Ekonomi Syariah</option>
										<option value="Lainnya">Lainnya</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Majelis Hakim</label>
							<input type="text" name="majelis_hakim" class="form-control"
								placeholder="Nama majelis hakim">
						</div>

						<div class="form-group">
							<label>Panitera Pengganti</label>
							<input type="text" name="panitera_pengganti" class="form-control"
								placeholder="Nama panitera pengganti">
						</div>

						<div class="form-group">
							<label>Catatan Notelen</label>
							<textarea name="catatan_notelen" class="form-control" rows="3"
								placeholder="Catatan khusus untuk notelen..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-save"></i> Simpan Berkas
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- MODAL: Berkas Detail -->
	<div class="modal fade" id="berkasDetailModal" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
						<i class="fa fa-folder-open"></i> Detail Berkas:
						<span id="detailNomorPerkara">-</span>
					</h4>
				</div>
				<div class="modal-body">

					<!-- Info Berkas -->
					<div class="row">
						<div class="col-md-6">
							<h5><i class="fa fa-info-circle"></i> Informasi Berkas</h5>
							<table class="table table-borderless">
								<tr>
									<td><strong>Jenis Perkara:</strong></td>
									<td id="detailJenisPerkara">-</td>
								</tr>
								<tr>
									<td><strong>Tanggal Putusan:</strong></td>
									<td id="detailTanggalPutusan">-</td>
								</tr>
								<tr>
									<td><strong>Tanggal Masuk:</strong></td>
									<td id="detailTanggalMasuk">-</td>
								</tr>
								<tr>
									<td><strong>Status:</strong></td>
									<td>
										<span id="detailStatusBerkas" class="status-badge">-</span>
									</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<h5><i class="fa fa-users"></i> Petugas</h5>
							<table class="table table-borderless">
								<tr>
									<td><strong>Majelis Hakim:</strong></td>
									<td id="detailMajelisHakim">-</td>
								</tr>
								<tr>
									<td><strong>Panitera Pengganti:</strong></td>
									<td id="detailPaniteraPengganti">-</td>
								</tr>
							</table>

							<div style="margin-top: 15px;">
								<button type="button" class="btn btn-warning btn-sm" onclick="openUpdateStatusModal()">
									<i class="fa fa-edit"></i> Update Status
								</button>
							</div>
						</div>
					</div>

					<hr>

					<!-- Inventaris -->
					<div class="row">
						<div class="col-md-12">
							<h5>
								<i class="fa fa-archive"></i> Inventaris Berkas
								<button type="button" class="btn btn-success btn-sm pull-right" onclick="openAddInventarisInline()">
									<i class="fa fa-plus"></i> Tambah
								</button>
							</h5>

							<div id="inventarisList" class="inventaris-list">
								<!-- Akan diisi via JavaScript -->
							</div>
						</div>
					</div>

					<!-- Catatan -->
					<div class="row" style="margin-top: 15px;">
						<div class="col-md-12">
							<h5><i class="fa fa-sticky-note"></i> Catatan</h5>
							<p id="detailCatatan" class="text-muted">-</p>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL: Add Inventaris -->
	<div class="modal fade" id="inventarisModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
						<i class="fa fa-plus"></i> Tambah Inventaris
						<small id="inventarisModalTitle">-</small>
					</h4>
				</div>
				<form id="inventarisForm" class="popup-form">
					<input type="hidden" name="berkas_id" id="inventarisBerkasId">
					<div class="modal-body">
						<div class="form-group">
							<label>Jenis Barang *</label>
							<select name="master_barang_id" id="inventarisBarangSelect" class="form-control" required>
								<option value="">Pilih jenis barang...</option>
								<?php if (isset($master_barang) && is_array($master_barang)): ?>
									<?php foreach ($master_barang as $barang): ?>
										<option value="<?= $barang->id ?>"><?= $barang->nama_barang ?> (<?= $barang->satuan_barang ?>)</option>
									<?php endforeach; ?>
								<?php else: ?>
									<option value="" disabled>Belum ada master barang</option>
								<?php endif; ?>
							</select>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Jumlah *</label>
									<input type="number" name="jumlah" class="form-control" min="1" value="1" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Kondisi Barang</label>
									<select name="kondisi_barang" class="form-control">
										<option value="BAIK">Baik</option>
										<option value="RUSAK">Rusak</option>
										<option value="HILANG">Hilang</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Keterangan</label>
							<textarea name="keterangan" class="form-control" rows="3"
								placeholder="Keterangan tambahan..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-save"></i> Simpan Inventaris
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- MODAL: Update Status -->
	<div class="modal fade" id="updateStatusModal" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
						<i class="fa fa-edit"></i> Update Status
					</h4>
				</div>
				<form id="updateStatusForm">
					<input type="hidden" name="berkas_id" id="updateStatusBerkasId">
					<div class="modal-body">
						<div class="form-group">
							<label>Status Baru *</label>
							<select name="status" class="form-control" required>
								<option value="MASUK">Masuk</option>
								<option value="PROSES">Proses</option>
								<option value="SELESAI">Selesai</option>
								<option value="KELUAR">Keluar</option>
							</select>
						</div>

						<div class="form-group">
							<label>Catatan</label>
							<textarea name="catatan" class="form-control" rows="3"
								placeholder="Catatan perubahan status..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-warning">
							<i class="fa fa-save"></i> Update Status
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- MODAL: Master Barang -->
	<div class="modal fade" id="masterBarangModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
						<i class="fa fa-cog"></i> Master Data Barang
					</h4>
				</div>
				<div class="modal-body">
					<form id="masterBarangForm" class="popup-form">
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label>Nama Barang *</label>
									<input type="text" name="nama_barang" class="form-control"
										placeholder="Contoh: Stofmap Folio" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Satuan *</label>
									<input type="text" name="satuan_barang" class="form-control"
										placeholder="pcs/lembar" required>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Barcode</label>
							<input type="text" name="barcode" class="form-control"
								placeholder="Barcode barang (opsional)">
						</div>

						<button type="submit" class="btn btn-primary">
							<i class="fa fa-plus"></i> Tambah Barang
						</button>
					</form>

					<hr>

					<div id="masterBarangList">
						<!-- Akan diisi via JavaScript -->
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Floating Action Button -->
	<button type="button" class="btn btn-primary btn-floating" onclick="openNewBerkasModal()" title="Tambah Berkas Baru">
		<i class="fa fa-plus fa-2x"></i>
	</button>

	<!-- Scripts -->
	<script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js') ?>"></script>
	<script src="<?= base_url('assets/plugins/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/build/js/adminlte.min.js') ?>"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Notelen JavaScript -->
	<script>
		$(document).ready(function() {
			// Load master barang untuk select dropdown
			loadMasterBarangSelect();

			// Auto-refresh stats setiap 30 detik
			setInterval(refreshStats, 30000);
		});

		// =====================================
		// NEW BERKAS FUNCTIONS
		// =====================================

		function openNewBerkasModal() {
			$('#newBerkasModal').modal('show');
			$('#newBerkasForm')[0].reset();
		}

		$('#newBerkasForm').submit(function(e) {
			e.preventDefault();

			$.ajax({
				url: '<?= base_url("notelen/ajax_insert_berkas") ?>',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil!',
							text: response.message,
							timer: 1500
						}).then(() => {
							location.reload();
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: 'Terjadi kesalahan koneksi'
					});
				}
			});
		});

		// =====================================
		// BERKAS DETAIL FUNCTIONS
		// =====================================

		let currentBerkasId = null;

		function openBerkasDetail(id) {
			currentBerkasId = id;

			$.ajax({
				url: '<?= base_url("notelen/ajax_get_berkas") ?>',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						const berkas = response.berkas;

						// Fill berkas info
						$('#detailNomorPerkara').text(berkas.nomor_perkara);
						$('#detailJenisPerkara').text(berkas.jenis_perkara || '-');
						$('#detailTanggalPutusan').text(formatDate(berkas.tanggal_putusan));
						$('#detailTanggalMasuk').text(formatDate(berkas.tanggal_masuk_notelen));
						$('#detailStatusBerkas').text(berkas.status_berkas).attr('class', 'status-badge status-' + berkas.status_berkas);
						$('#detailMajelisHakim').text(berkas.majelis_hakim || '-');
						$('#detailPaniteraPengganti').text(berkas.panitera_pengganti || '-');
						$('#detailCatatan').text(berkas.catatan_notelen || 'Tidak ada catatan');

						// Fill inventaris
						loadInventarisDetail(response.inventaris);

						$('#updateStatusBerkasId').val(berkas.id);
						$('#berkasDetailModal').modal('show');
					} else {
						Swal.fire('Error!', response.message, 'error');
					}
				}
			});
		}

		function loadInventarisDetail(inventaris) {
			let html = '';

			if (inventaris.length === 0) {
				html = '<p class="text-muted text-center">Belum ada inventaris</p>';
			} else {
				inventaris.forEach(function(item) {
					html += `
                <div class="inventaris-item">
                    <div class="row">
                        <div class="col-md-8">
                            <strong>${item.nama_barang}</strong><br>
                            <small class="text-muted">
                                ${item.jumlah} ${item.satuan_barang} - ${item.kondisi_barang}
                            </small>
                            ${item.keterangan ? '<br><small>' + item.keterangan + '</small>' : ''}
                        </div>
                        <div class="col-md-4 text-right">
                            <small class="text-muted">${formatDate(item.tanggal_masuk)}</small><br>
                            <button type="button" class="btn btn-danger btn-xs" onclick="deleteInventaris(${item.id})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
				});
			}

			$('#inventarisList').html(html);
		}

		// =====================================
		// INVENTARIS FUNCTIONS
		// =====================================

		function openInventarisModal(berkasId, nomorPerkara) {
			$('#inventarisBerkasId').val(berkasId);
			$('#inventarisModalTitle').text('untuk ' + nomorPerkara);
			$('#inventarisModal').modal('show');
			$('#inventarisForm')[0].reset();
			$('#inventarisBerkasId').val(berkasId);
		}

		function openAddInventarisInline() {
			openInventarisModal(currentBerkasId, $('#detailNomorPerkara').text());
		}

		$('#inventarisForm').submit(function(e) {
			e.preventDefault();

			$.ajax({
				url: '<?= base_url("notelen/ajax_add_inventaris") ?>',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil!',
							text: response.message,
							timer: 1500
						});
						$('#inventarisModal').modal('hide');

						// Reload detail jika modal detail sedang terbuka
						if (currentBerkasId) {
							openBerkasDetail(currentBerkasId);
						}

						// Refresh stats
						refreshStats();
					} else {
						Swal.fire('Error!', response.message, 'error');
					}
				}
			});
		});

		function deleteInventaris(id) {
			Swal.fire({
				title: 'Hapus inventaris?',
				text: 'Data yang sudah dihapus tidak dapat dikembalikan',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?= base_url("notelen/ajax_delete_inventaris") ?>',
						type: 'POST',
						data: {
							id: id
						},
						dataType: 'json',
						success: function(response) {
							if (response.success) {
								Swal.fire('Berhasil!', response.message, 'success');
								openBerkasDetail(currentBerkasId);
							} else {
								Swal.fire('Error!', response.message, 'error');
							}
						}
					});
				}
			});
		}

		// =====================================
		// STATUS UPDATE
		// =====================================

		function openUpdateStatusModal() {
			$('#updateStatusModal').modal('show');
		}

		$('#updateStatusForm').submit(function(e) {
			e.preventDefault();

			$.ajax({
				url: '<?= base_url("notelen/ajax_update_status") ?>',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						Swal.fire('Berhasil!', response.message, 'success');
						$('#updateStatusModal').modal('hide');
						openBerkasDetail(currentBerkasId);
						refreshStats();
					} else {
						Swal.fire('Error!', response.message, 'error');
					}
				}
			});
		});

		// =====================================
		// MASTER BARANG
		// =====================================

		function openMasterBarangModal() {
			$('#masterBarangModal').modal('show');
			loadMasterBarangList();
		}

		function loadMasterBarangSelect() {
			$.ajax({
				url: '<?= base_url("notelen/ajax_get_master_barang") ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						let options = '<option value="">Pilih jenis barang...</option>';
						response.data.forEach(function(item) {
							options += `<option value="${item.id}">${item.nama_barang} (${item.satuan_barang})</option>`;
						});
						$('#inventarisBarangSelect').html(options);
					}
				}
			});
		}

		function loadMasterBarangList() {
			$.ajax({
				url: '<?= base_url("notelen/ajax_get_master_barang") ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						let html = '<table class="table table-striped"><thead><tr><th>Nama Barang</th><th>Satuan</th><th>Barcode</th></tr></thead><tbody>';

						response.data.forEach(function(item) {
							html += `
                        <tr>
                            <td>${item.nama_barang}</td>
                            <td>${item.satuan_barang}</td>
                            <td>${item.barcode || '-'}</td>
                        </tr>
                    `;
						});

						html += '</tbody></table>';
						$('#masterBarangList').html(html);
					}
				}
			});
		}

		$('#masterBarangForm').submit(function(e) {
			e.preventDefault();

			$.ajax({
				url: '<?= base_url("notelen/ajax_add_master_barang") ?>',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						Swal.fire('Berhasil!', response.message, 'success');
						$('#masterBarangForm')[0].reset();
						loadMasterBarangList();
						loadMasterBarangSelect();
					} else {
						Swal.fire('Error!', response.message, 'error');
					}
				}
			});
		});

		// =====================================
		// SYNC & UTILITIES
		// =====================================

		function syncFromSipp() {
			Swal.fire({
				title: 'Sync dari SIPP?',
				text: 'Akan mengambil data perkara putus terbaru dari database SIPP',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Sync!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					Swal.fire({
						title: 'Syncing...',
						text: 'Sedang mengambil data dari SIPP',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});

					$.ajax({
						url: '<?= base_url("notelen/ajax_sync_sipp") ?>',
						type: 'POST',
						data: {
							limit: 100
						},
						dataType: 'json',
						success: function(response) {
							Swal.close();

							if (response.success) {
								Swal.fire('Berhasil!', response.message, 'success').then(() => {
									location.reload();
								});
							} else {
								Swal.fire('Error!', response.message, 'error');
							}
						}
					});
				}
			});
		}

		function refreshStats() {
			$.ajax({
				url: '<?= base_url("notelen/ajax_get_stats") ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						const stats = response.stats;
						// Update stats di dashboard
						// (implementasi update real-time stats)
					}
				}
			});
		}

		// =====================================
		// HELPER FUNCTIONS
		// =====================================

		function formatDate(dateString) {
			if (!dateString) return '-';
			const date = new Date(dateString);
			return date.toLocaleDateString('id-ID');
		}

		// Auto-save filters
		$('input[name="nomor"], select[name="status"]').on('change', function() {
			// Auto-submit filter after 1 second delay
			setTimeout(function() {
				$('form.form-inline').submit();
			}, 1000);
		});
	</script>

</body>

</html>
