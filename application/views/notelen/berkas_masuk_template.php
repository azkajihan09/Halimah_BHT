<?php $this->load->view('template/new_header'); ?>

<?php $this->load->view('template/new_sidebar'); ?>

<!-- Include AJAX Config for Server Compatibility -->
<?php $this->load->view('notelen/ajax_config'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
	<!-- Content Header -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0"><?= isset($page_title) ? $page_title : 'Berkas Masuk Notelen 2' ?></h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?= base_url('notelen') ?>">Notelen</a></li>
						<li class="breadcrumb-item active">Berkas Masuk 2</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">

			<!-- Flash Messages -->
			<?php if ($this->session->flashdata('success')): ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php endif; ?>

			<?php if ($this->session->flashdata('error')): ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php endif; ?>

			<!-- Statistics Cards -->
			<div class="row">
				<div class="col-md-3 col-sm-6">
					<div class="info-box bg-primary">
						<span class="info-box-icon"><i class="fas fa-folder"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Total Berkas</span>
							<span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->total_berkas) ? $stats['berkas']->total_berkas : 0 ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="info-box bg-success">
						<span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Status Masuk</span>
							<span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->status_masuk) ? $stats['berkas']->status_masuk : 0 ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="info-box bg-warning">
						<span class="info-box-icon"><i class="fas fa-clock"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Status Proses</span>
							<span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->status_proses) ? $stats['berkas']->status_proses : 0 ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="info-box bg-info">
						<span class="info-box-icon"><i class="fas fa-archive"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Total Inventaris</span>
							<span class="info-box-number"><?= isset($stats['inventaris']) && $stats['inventaris'] && isset($stats['inventaris']->total_barang) ? $stats['inventaris']->total_barang : 0 ?></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Filter & Controls -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-filter"></i> Filter Data</h3>
						</div>
						<div class="card-body">
							<form method="GET" action="<?= base_url('notelen/berkas_template') ?>" class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>Status Berkas:</label>
										<select name="status" class="form-control">
											<option value="">Semua Status</option>
											<option value="MASUK" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'MASUK') ? 'selected' : '' ?>>Masuk</option>
											<option value="PROSES" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'PROSES') ? 'selected' : '' ?>>Proses</option>
											<option value="SELESAI" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'SELESAI') ? 'selected' : '' ?>>Selesai</option>
											<option value="ARSIP" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'ARSIP') ? 'selected' : '' ?>>Arsip</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Nomor Perkara:</label>
										<input type="text" name="nomor" class="form-control" placeholder="Cari nomor perkara..."
											value="<?= isset($filters['nomor_perkara']) ? $filters['nomor_perkara'] : '' ?>">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>&nbsp;</label><br>
										<button type="submit" class="btn btn-primary">
											<i class="fas fa-search"></i> Filter
										</button>
										<a href="<?= base_url('notelen/reset_filters') ?>" class="btn btn-secondary">
											<i class="fas fa-refresh"></i> Reset
										</a>
									</div>
								</div>
								<div class="col-md-3 text-right">
									<label>&nbsp;</label><br>
									<div class="btn-group">
										<button type="button" class="btn btn-success" onclick="openNewBerkasModal()">
											<i class="fas fa-plus"></i> Tambah Berkas
										</button>
										<button type="button" class="btn btn-info" onclick="syncFromSipp()">
											<i class="fas fa-sync"></i> Sync SIPP
										</button>
										<a href="<?= base_url('notelen/export?format=excel') ?>" class="btn btn-warning">
											<i class="fas fa-download"></i> Export Excel
										</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Table Data -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-list"></i> Daftar Berkas Masuk Notelen (<?= isset($berkas_list) ? count($berkas_list) : 0 ?> berkas)</h3>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-striped table-hover mb-0">
									<thead class="thead-dark">
										<tr>
											<th width="4%">No</th>
											<th width="15%">Nomor Perkara *</th>
											<th width="10%">Tanggal Putusan *</th>
											<th width="10%">Jenis Perkara</th>
											<th width="8%">Masuk</th>
											<th width="8%">Status Berkas</th>
											<th width="10%">Majelis Hakim</th>
											<th width="8%">Panitera</th>
											<th width="12%">Catatan Notelen</th>
											<th width="10%">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php if (isset($berkas_list) && !empty($berkas_list)): ?>
											<?php foreach ($berkas_list as $index => $berkas): ?>
												<tr>
													<td><?= $index + 1 ?></td>
													<td class="text-left">
														<div class="font-weight-bold text-primary"><?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '-' ?></div>
													</td>
													<td>
														<?php if (isset($berkas->tanggal_putusan)): ?>
															<?= date('d/m/Y', strtotime($berkas->tanggal_putusan)) ?>
														<?php else: ?>
															<span class="text-muted">-</span>
														<?php endif; ?>
													</td>
													<td><?= isset($berkas->jenis_perkara) ? $berkas->jenis_perkara : '-' ?></td>
													<td>
														<?php if (isset($berkas->tanggal_masuk_notelen)): ?>
															<?= date('d/m/Y', strtotime($berkas->tanggal_masuk_notelen)) ?>
														<?php else: ?>
															<?= date('d/m/Y') ?>
														<?php endif; ?>
													</td>
													<td>
														<?php
														$status = isset($berkas->status_berkas) ? $berkas->status_berkas : 'MASUK';
														$badge_color = '';
														switch ($status) {
															case 'MASUK':
																$badge_color = 'badge-primary';
																break;
															case 'PROSES':
																$badge_color = 'badge-warning';
																break;
															case 'SELESAI':
																$badge_color = 'badge-success';
																break;
															case 'ARSIP':
																$badge_color = 'badge-secondary';
																break;
															default:
																$badge_color = 'badge-light';
																break;
														}
														?>
														<span class="badge <?= $badge_color ?>"><?= $status ?></span>
													</td>
													<td>
														<small class="text-muted" style="font-size: 0.85em; max-width: 150px; word-wrap: break-word;">
															<?= isset($berkas->majelis_hakim) ? $berkas->majelis_hakim : '-' ?>
														</small>
													</td>
													<td>
														<small class="text-muted">
															<?= isset($berkas->panitera_pengganti) ? $berkas->panitera_pengganti : '-' ?>
														</small>
													</td>
													<td>
														<small class="text-muted" style="max-width: 200px; word-wrap: break-word;">
															<?= isset($berkas->catatan_notelen) ? $berkas->catatan_notelen : '-' ?>
														</small>
													</td>
													<td>
														<div class="btn-group" role="group">
															<button type="button" class="btn btn-info btn-sm"
																onclick="viewBerkasDetail(<?= $berkas->id ?>)"
																title="Lihat Detail">
																<i class="fas fa-eye"></i>
															</button>
															<button type="button" class="btn btn-warning btn-sm"
																onclick="openEditBerkasModal(<?= $berkas->id ?>)"
																title="Edit berkas">
																<i class="fas fa-edit"></i>
															</button>
															<button type="button" class="btn btn-danger btn-sm"
																onclick="deleteBerkas(<?= $berkas->id ?>, '<?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '' ?>')"
																title="Hapus berkas">
																<i class="fas fa-trash"></i>
															</button>
														</div>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="10" class="text-center text-muted py-4">
													<i class="fas fa-folder-open fa-3x mb-3"></i><br>
													<h4>Belum Ada Data Berkas</h4>
													<p>Klik tombol "Tambah Berkas" atau "Sync SIPP" untuk menambah data</p>
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>

							<!-- Pagination -->
							<?php if (isset($total_pages) && $total_pages > 1): ?>
								<div class="card-footer">
									<div class="row">
										<div class="col-md-6">
											<p class="text-muted mb-0">
												Menampilkan <?= isset($berkas_list) ? count($berkas_list) : 0 ?> dari <?= isset($total_berkas) ? $total_berkas : 0 ?> data berkas
											</p>
										</div>
										<div class="col-md-6">
											<div class="d-flex justify-content-end">
												<nav>
													<ul class="pagination pagination-sm mb-0">
														<!-- Previous Button -->
														<?php if (isset($current_page) && $current_page > 1): ?>
															<li class="page-item">
																<a class="page-link" href="<?= base_url('notelen/berkas_template') ?>?page=<?= $current_page - 1 ?><?= isset($filters['status_berkas']) && $filters['status_berkas'] ? '&status=' . $filters['status_berkas'] : '' ?><?= isset($filters['nomor_perkara']) && $filters['nomor_perkara'] ? '&nomor=' . $filters['nomor_perkara'] : '' ?>">
																	<i class="fas fa-angle-left"></i>
																</a>
															</li>
														<?php endif; ?>

														<!-- Page Numbers -->
														<?php
														$start_page = max(1, (isset($current_page) ? $current_page : 1) - 2);
														$end_page = min($total_pages, $start_page + 4);
														if ($end_page - $start_page < 4) {
															$start_page = max(1, $end_page - 4);
														}
														?>

														<?php for ($i = $start_page; $i <= $end_page; $i++): ?>
															<li class="page-item <?= (isset($current_page) && $i == $current_page) ? 'active' : '' ?>">
																<a class="page-link" href="<?= base_url('notelen/berkas_template') ?>?page=<?= $i ?><?= isset($filters['status_berkas']) && $filters['status_berkas'] ? '&status=' . $filters['status_berkas'] : '' ?><?= isset($filters['nomor_perkara']) && $filters['nomor_perkara'] ? '&nomor=' . $filters['nomor_perkara'] : '' ?>">
																	<?= $i ?>
																</a>
															</li>
														<?php endfor; ?>

														<!-- Next Button -->
														<?php if (isset($current_page) && $current_page < $total_pages): ?>
															<li class="page-item">
																<a class="page-link" href="<?= base_url('notelen/berkas_template') ?>?page=<?= $current_page + 1 ?><?= isset($filters['status_berkas']) && $filters['status_berkas'] ? '&status=' . $filters['status_berkas'] : '' ?><?= isset($filters['nomor_perkara']) && $filters['nomor_perkara'] ? '&nomor=' . $filters['nomor_perkara'] : '' ?>">
																	<i class="fas fa-angle-right"></i>
																</a>
															</li>
														<?php endif; ?>
													</ul>
												</nav>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<!-- Modal Tambah Berkas -->
<div class="modal fade" id="newBerkasModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title">
					<i class="fas fa-plus"></i> Tambah Berkas Masuk Baru
				</h4>
				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
			</div>
			<form id="newBerkasForm">
				<div class="modal-body">
					<div class="form-group">
						<label>Nomor Perkara *</label>
						<div class="input-group">
							<input type="text" name="nomor_perkara" id="nomorPerkara" class="form-control"
								placeholder="Ketik nomor perkara..." required autocomplete="off">
							<div class="input-group-append">
								<button type="button" class="btn btn-outline-secondary" id="clearPerkara" title="Clear">
									<i class="fas fa-times"></i>
								</button>
							</div>
						</div>
						<div id="perkaraSuggestions" class="list-group" style="position: absolute; z-index: 1050; max-height: 300px; overflow-y: auto; display: none;"></div>
						<input type="hidden" name="perkara_id_sipp" id="perkaraIdSipp">
						<small class="form-text text-muted">Ketik minimal 2 karakter untuk mencari dari database SIPP</small>
					</div>

					<div class="form-group">
						<label>Tanggal Putusan *</label>
						<input type="date" name="tanggal_putusan" id="tanggalPutusan" class="form-control" readonly required>
						<small id="tanggalHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Jenis Perkara</label>
								<input type="text" name="jenis_perkara" id="jenisPerkara" class="form-control" readonly>
								<small id="jenisHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Status Berkas *</label>
								<select name="status_berkas" id="statusBerkas" class="form-control" required>
									<option value="MASUK" selected>Masuk</option>
									<option value="PROSES">Proses</option>
									<option value="SELESAI">Selesai</option>
									<option value="ARSIP">Arsip</option>
								</select>
								<small class="form-text text-primary">Pilih status berkas</small>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Majelis Hakim</label>
						<input type="text" name="majelis_hakim" id="majelisHakim" class="form-control" readonly>
						<small id="majelisHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
					</div>

					<div class="form-group">
						<label>Panitera Pengganti</label>
						<input type="text" name="panitera_pengganti" id="paniteraPengganti" class="form-control" readonly>
						<small id="paniteraHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
					</div>

					<div class="form-group">
						<label>Catatan Notelen</label>
						<textarea name="catatan_notelen" class="form-control" rows="3"
							placeholder="Catatan khusus untuk notelen..."></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-save"></i> Simpan Berkas
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Edit Berkas -->
<div class="modal fade" id="editBerkasModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<h4 class="modal-title">
					<i class="fas fa-edit"></i> Edit Berkas Masuk
				</h4>
				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
			</div>
			<form id="editBerkasForm">
				<input type="hidden" name="berkas_id" id="editBerkasId">
				<div class="modal-body">
					<div class="form-group">
						<label>Nomor Perkara *</label>
						<input type="text" name="nomor_perkara" id="editNomorPerkara" class="form-control" readonly>
						<small class="form-text text-muted">Nomor perkara tidak dapat diubah</small>
					</div>

					<div class="form-group">
						<label>Tanggal Putusan *</label>
						<input type="date" name="tanggal_putusan" id="editTanggalPutusan" class="form-control" readonly required>
						<small class="form-text text-muted">Tanggal putusan tidak dapat diubah</small>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Jenis Perkara</label>
								<input type="text" name="jenis_perkara" id="editJenisPerkara" class="form-control" readonly>
								<small class="form-text text-muted">Jenis perkara tidak dapat diubah</small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Status Berkas</label>
								<select name="status_berkas" id="editStatusBerkas" class="form-control">
									<option value="MASUK">Masuk</option>
									<option value="PROSES">Proses</option>
									<option value="SELESAI">Selesai</option>
									<option value="ARSIP">Arsip</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Majelis Hakim</label>
						<input type="text" name="majelis_hakim" id="editMajelisHakim" class="form-control" readonly>
						<small class="form-text text-muted">Majelis hakim tidak dapat diubah</small>
					</div>

					<div class="form-group">
						<label>Panitera Pengganti</label>
						<input type="text" name="panitera_pengganti" id="editPaniteraPengganti" class="form-control" readonly>
						<small class="form-text text-muted">Panitera pengganti tidak dapat diubah</small>
					</div>

					<div class="form-group">
						<label>Catatan Notelen</label>
						<textarea name="catatan_notelen" id="editCatatanNotelen" class="form-control" rows="3"
							placeholder="Catatan khusus untuk notelen..."></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-warning">
						<i class="fas fa-save"></i> Update Berkas
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Lihat Detail Berkas -->
<div class="modal fade" id="viewBerkasModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h4 class="modal-title">
					<i class="fas fa-eye"></i> Detail Berkas Masuk
				</h4>
				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-primary"><i class="fas fa-gavel"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Nomor Perkara</span>
								<span class="info-box-number" id="viewNomorPerkara">-</span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-warning"><i class="fas fa-calendar"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Tanggal Putusan</span>
								<span class="info-box-number" id="viewTanggalPutusan">-</span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-success"><i class="fas fa-balance-scale"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Jenis Perkara</span>
								<span class="info-box-number" id="viewJenisPerkara">-</span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-info"><i class="fas fa-flag"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Status Berkas</span>
								<span class="info-box-number" id="viewStatusBerkas">-</span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-secondary"><i class="fas fa-calendar-alt"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Tanggal Masuk Notelen</span>
								<span class="info-box-number" id="viewTanggalMasuk">-</span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-purple"><i class="fas fa-database"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">ID SIPP</span>
								<span class="info-box-number" id="viewPerkaraIdSipp">-</span>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<h5><i class="fas fa-users"></i> Informasi Persidangan</h5>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label><strong>Majelis Hakim:</strong></label>
							<p id="viewMajelisHakim" class="text-muted">-</p>
						</div>
						<div class="form-group">
							<label><strong>Panitera Pengganti:</strong></label>
							<p id="viewPaniteraPengganti" class="text-muted">-</p>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<h5><i class="fas fa-sticky-note"></i> Catatan Notelen</h5>
					</div>
					<div class="card-body">
						<p id="viewCatatanNotelen" class="text-muted">-</p>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<h5><i class="fas fa-clock"></i> Informasi Sistem</h5>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<strong>Dibuat:</strong>
								<p id="viewCreatedAt" class="text-muted">-</p>
							</div>
							<div class="col-md-6">
								<strong>Terakhir Diupdate:</strong>
								<p id="viewUpdatedAt" class="text-muted">-</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" onclick="editFromView()">
					<i class="fas fa-edit"></i> Edit Berkas
				</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>

<!-- JavaScript untuk SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(document).ready(function() {
		console.log('Notelen System loaded successfully!');

		// Setup autocomplete nomor perkara (saat ready)
		setupNomorPerkaraAutocomplete();
	});

	function openNewBerkasModal() {
		$('#newBerkasModal').modal('show');
		$('#newBerkasForm')[0].reset();
		$('#perkaraSuggestions').hide();

		// Reset form state
		$('#nomorPerkara').data('selected-from-sipp', false);
		$('#perkaraIdSipp').val('');

		// Reset visual indicators
		updateFieldLabels(false);

		// Focus ke nomor perkara
		setTimeout(function() {
			$('#nomorPerkara').focus();
		}, 500);
	}

	// Setup autocomplete untuk nomor perkara (improved version from berkas_pbt)
	function setupNomorPerkaraAutocomplete() {
		let searchTimeout;
		let isLoading = false;

		$('#nomorPerkara').on('input', function() {
			const search = $(this).val();

			if (search.length < 2) {
				$('#perkaraSuggestions').hide();
				return;
			}

			// Clear previous timeout
			clearTimeout(searchTimeout);

			// Set new timeout untuk menghindari terlalu banyak request
			searchTimeout = setTimeout(function() {
				if (isLoading) return;

				isLoading = true;
				$('#perkaraSuggestions').html(`
					<div class="list-group-item">
						<i class="fas fa-spinner fa-spin"></i> Mencari data perkara...
					</div>
				`).show();

				$.ajax({
					url: getAjaxUrl('notelen/ajax_get_perkara_dropdown'),
					type: 'GET',
					data: {
						search: search
					},
					dataType: 'json',
					success: function(response) {
						if (response.success && response.data.length > 0) {
							let html = '';
							response.data.forEach(function(item) {
								html += `
									<a href="#" class="list-group-item list-group-item-action perkara-suggestion"
										data-perkara-id="${item.perkara_id}"
										data-nomor="${item.nomor_perkara}"
										data-jenis="${item.jenis_perkara || ''}"
										data-tanggal="${item.tanggal_putusan || ''}"
										data-majelis="${item.majelis_hakim || ''}"
										data-panitera="${item.panitera_pengganti || ''}">
										<div class="d-flex w-100 justify-content-between">
											<h6 class="mb-1 text-primary">${item.nomor_perkara}</h6>
											<small class="text-muted">${item.tanggal_putusan || 'N/A'}</small>
										</div>
										<p class="mb-1">${item.jenis_perkara || 'Jenis tidak diketahui'}</p>
										<small class="text-muted">
											${item.majelis_hakim ? 'Hakim: ' + item.majelis_hakim.substring(0, 30) + '...' : 'Data hakim tidak tersedia'}
										</small>
									</a>
								`;
							});
							$('#perkaraSuggestions').html(html).show();
						} else {
							$('#perkaraSuggestions').html(`
								<div class="list-group-item text-muted">
									<i class="fas fa-exclamation-circle"></i> Tidak ada data perkara ditemukan
								</div>
							`).show();
						}
					},
					error: function() {
						$('#perkaraSuggestions').html(`
							<div class="list-group-item text-danger">
								<i class="fas fa-exclamation-triangle"></i> Error loading data
							</div>
						`).show();
					},
					complete: function() {
						isLoading = false;
					}
				});
			}, 300);
		});

		// Handle click pada suggestion
		$(document).on('click', '.perkara-suggestion', function(e) {
			e.preventDefault();

			const data = $(this).data();

			// Fill form dengan data dari SIPP
			$('#perkaraIdSipp').val(data.perkaraId);
			$('#nomorPerkara').val(data.nomor);
			$('#jenisPerkara').val(data.jenis);
			$('#tanggalPutusan').val(data.tanggal);
			$('#majelisHakim').val(data.majelis);
			$('#paniteraPengganti').val(data.panitera);

			// Mark as selected from SIPP
			$('#nomorPerkara').data('selected-from-sipp', true);

			// Update visual indicators to show auto-filled
			updateFieldLabels(true);

			// Hide suggestions
			$('#perkaraSuggestions').hide();

			// Show success notification
			Swal.fire({
				icon: 'success',
				title: 'Data Ditemukan!',
				text: 'Form telah diisi otomatis dengan data dari SIPP',
				showConfirmButton: false,
				timer: 1500
			});
		});

		// Clear button
		$('#clearPerkara').click(function() {
			$('#perkaraIdSipp').val('');
			$('#nomorPerkara').val('').data('selected-from-sipp', false);
			$('#jenisPerkara').val('');
			$('#tanggalPutusan').val('');
			$('#majelisHakim').val('');
			$('#paniteraPengganti').val('');
			$('#perkaraSuggestions').hide();

			// Update visual indicators to show manual input
			updateFieldLabels(false);

			$('#nomorPerkara').focus();
		});

		// Hide suggestions when click outside
		$(document).click(function(e) {
			if (!$(e.target).closest('#nomorPerkara, #perkaraSuggestions').length) {
				$('#perkaraSuggestions').hide();
			}
		});
	}

	// Function to update field labels based on source
	function updateFieldLabels(fromSipp) {
		if (fromSipp) {
			$('#jenisHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
			$('#tanggalHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
			$('#majelisHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
			$('#paniteraHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');

			// Add success icons
			$('#jenisPerkara, #tanggalPutusan, #majelisHakim, #paniteraPengganti')
				.addClass('border-success');
		} else {
			$('#jenisHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
			$('#tanggalHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
			$('#majelisHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
			$('#paniteraHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');

			// Remove success styling
			$('#jenisPerkara, #tanggalPutusan, #majelisHakim, #paniteraPengganti')
				.removeClass('border-success');
		}
	}

	// Function untuk view detail berkas
	function viewBerkasDetail(berkas_id) {
		$('#viewBerkasModal').modal('show');

		// Clear previous data
		$('#viewNomorPerkara').text('-');
		$('#viewTanggalPutusan').text('-');
		$('#viewJenisPerkara').text('-');
		$('#viewStatusBerkas').text('-');
		$('#viewTanggalMasuk').text('-');
		$('#viewPerkaraIdSipp').text('-');
		$('#viewMajelisHakim').text('-');
		$('#viewPaniteraPengganti').text('-');
		$('#viewCatatanNotelen').text('-');
		$('#viewCreatedAt').text('-');
		$('#viewUpdatedAt').text('-');

		// Store berkas_id for edit button
		$('#viewBerkasModal').data('berkas-id', berkas_id);

		// Load data berkas
		$.ajax({
			url: getAjaxUrl('notelen/ajax_get_berkas'),
			type: 'POST',
			data: {
				id: berkas_id
			},
			dataType: 'json',
			success: function(response) {
				console.log('View response:', response);
				if (response.success && response.berkas) {
					var berkas = response.berkas;

					// Populate data
					$('#viewNomorPerkara').text(berkas.nomor_perkara || '-');
					$('#viewTanggalPutusan').text(berkas.tanggal_putusan ? formatDate(berkas.tanggal_putusan) : '-');
					$('#viewJenisPerkara').text(berkas.jenis_perkara || '-');

					// Status dengan badge
					var statusHtml = '';
					switch (berkas.status_berkas) {
						case 'MASUK':
							statusHtml = '<span class="badge badge-primary">Masuk</span>';
							break;
						case 'PROSES':
							statusHtml = '<span class="badge badge-warning">Proses</span>';
							break;
						case 'SELESAI':
							statusHtml = '<span class="badge badge-success">Selesai</span>';
							break;
						case 'ARSIP':
							statusHtml = '<span class="badge badge-secondary">Arsip</span>';
							break;
						default:
							statusHtml = '<span class="badge badge-light">' + (berkas.status_berkas || '-') + '</span>';
					}
					$('#viewStatusBerkas').html(statusHtml);

					$('#viewTanggalMasuk').text(berkas.tanggal_masuk_notelen ? formatDate(berkas.tanggal_masuk_notelen) : '-');
					$('#viewPerkaraIdSipp').text(berkas.perkara_id_sipp || '-');
					$('#viewMajelisHakim').text(berkas.majelis_hakim || '-');
					$('#viewPaniteraPengganti').text(berkas.panitera_pengganti || '-');
					$('#viewCatatanNotelen').text(berkas.catatan_notelen || 'Tidak ada catatan');
					$('#viewCreatedAt').text(berkas.created_at ? formatDateTime(berkas.created_at) : '-');
					$('#viewUpdatedAt').text(berkas.updated_at ? formatDateTime(berkas.updated_at) : '-');
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: 'Gagal memuat detail berkas'
					});
				}
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan saat memuat detail berkas'
				});
			}
		});
	}

	// Function untuk edit dari view modal
	function editFromView() {
		var berkas_id = $('#viewBerkasModal').data('berkas-id');
		$('#viewBerkasModal').modal('hide');

		setTimeout(function() {
			openEditBerkasModal(berkas_id);
		}, 500);
	}

	// Helper functions untuk format date
	function formatDate(dateString) {
		if (!dateString) return '-';
		var date = new Date(dateString);
		return date.toLocaleDateString('id-ID', {
			day: '2-digit',
			month: '2-digit',
			year: 'numeric'
		});
	}

	function formatDateTime(dateTimeString) {
		if (!dateTimeString) return '-';
		var date = new Date(dateTimeString);
		return date.toLocaleDateString('id-ID', {
			day: '2-digit',
			month: '2-digit',
			year: 'numeric',
			hour: '2-digit',
			minute: '2-digit'
		});
	}

	function openEditBerkasModal(berkas_id) {
		$('#editBerkasModal').modal('show');
		$('#editBerkasForm')[0].reset();
		$('#editBerkasId').val(berkas_id);

		// Pastikan field readonly tetap readonly (tanpa disabled agar data terkirim)
		$('#editTanggalPutusan').prop('readonly', true);
		$('#editJenisPerkara').prop('readonly', true);
		$('#editMajelisHakim').prop('readonly', true);
		$('#editPaniteraPengganti').prop('readonly', true);

		// Load data berkas untuk edit
		loadBerkasForEdit(berkas_id);
	}

	// Load data berkas untuk form edit
	function loadBerkasForEdit(berkas_id) {
		$.ajax({
			url: getAjaxUrl('notelen/ajax_get_berkas'),
			type: 'POST',
			data: {
				id: berkas_id
			},
			dataType: 'json',
			success: function(response) {
				console.log('Response berkas:', response);
				if (response.success && response.berkas) {
					var berkas = response.berkas;

					// Pastikan field readonly tetap readonly (tanpa disabled agar data terkirim)
					$('#editTanggalPutusan').prop('readonly', true);
					$('#editJenisPerkara').prop('readonly', true);
					$('#editMajelisHakim').prop('readonly', true);
					$('#editPaniteraPengganti').prop('readonly', true);

					// Set data
					$('#editNomorPerkara').val(berkas.nomor_perkara || '');
					$('#editTanggalPutusan').val(berkas.tanggal_putusan || '');
					$('#editJenisPerkara').val(berkas.jenis_perkara || '');
					$('#editStatusBerkas').val(berkas.status_berkas || 'MASUK');
					$('#editMajelisHakim').val(berkas.majelis_hakim || '');
					$('#editPaniteraPengganti').val(berkas.panitera_pengganti || '');
					$('#editCatatanNotelen').val(berkas.catatan_notelen || '');
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: 'Gagal memuat data berkas'
					});
				}
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan saat memuat data berkas'
				});
			}
		});
	}

	// Load data perkara untuk dropdown
	function loadPerkaraDropdown() {
		$.ajax({
			url: getAjaxUrl('notelen/ajax_get_perkara_dropdown'),
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					var select = $('#nomorPerkaraSelect');
					select.empty().append('<option value="">Pilih Nomor Perkara...</option>');

					$.each(response.data, function(index, item) {
						var option = $('<option></option>')
							.attr('value', item.nomor_perkara)
							.text(item.nomor_perkara + ' - ' + item.jenis_perkara)
							.data('detail', item);
						select.append(option);
					});
				}
			},
			error: function() {
				console.log('Error loading perkara dropdown');
			}
		});
	}

	// Auto-fill ketika nomor perkara dipilih
	$(document).on('change', '#nomorPerkaraSelect', function() {
		var selectedOption = $(this).find('option:selected');
		var detail = selectedOption.data('detail');

		if (detail) {
			// Format tanggal dari YYYY-MM-DD
			var tanggal = detail.tanggal_putusan;
			if (tanggal) {
				$('#tanggalPutusan').val(tanggal);
			}

			$('#jenisPerkara').val(detail.jenis_perkara || '-');
			$('#majelisHakim').val(detail.majelis_hakim || '-');
			$('#paniteraPengganti').val(detail.panitera_pengganti || '-');
		} else {
			// Clear fields if no selection
			$('#tanggalPutusan').val('');
			$('#jenisPerkara').val('');
			$('#majelisHakim').val('');
			$('#paniteraPengganti').val('');
		}
	});

	$('#newBerkasForm').submit(function(e) {
		e.preventDefault();

		// Disable submit button during processing
		var submitBtn = $(this).find('button[type="submit"]');
		var originalText = submitBtn.html();
		submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

		$.ajax({
			url: getAjaxUrl('notelen/ajax_insert_berkas_direct'),
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			timeout: 30000, // 30 second timeout
			success: function(response) {
				console.log('Success response:', response);

				if (response && response.success) {
					$('#newBerkasModal').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Berhasil!',
						text: response.message || 'Berkas berhasil disimpan',
						timer: 2000
					}).then(() => {
						location.reload();
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: response.message || 'Terjadi kesalahan saat menyimpan data'
					});
				}
			},
			error: function(xhr, status, error) {
				console.log('AJAX Error Details:');
				console.log('Status:', status);
				console.log('Error:', error);
				console.log('Response Text:', xhr.responseText);
				console.log('Status Code:', xhr.status);

				var errorMessage = 'Terjadi kesalahan koneksi';

				// Try to parse error response
				try {
					if (xhr.responseText) {
						var errorResponse = JSON.parse(xhr.responseText);
						if (errorResponse && errorResponse.message) {
							errorMessage = errorResponse.message;
						}
					}
				} catch (e) {
					// If response is not JSON, show part of the response text
					if (xhr.responseText && xhr.responseText.length > 0) {
						errorMessage = 'Server Error: ' + xhr.responseText.substring(0, 200);
					} else if (status === 'timeout') {
						errorMessage = 'Request timeout - coba lagi';
					} else if (status === 'parsererror') {
						errorMessage = 'Server mengirim response yang tidak valid';
					}
				}

				Swal.fire({
					icon: 'error',
					title: 'Error Koneksi!',
					text: errorMessage,
					footer: 'Jika data sudah tersimpan, silakan refresh halaman'
				});
			},
			complete: function() {
				// Re-enable submit button
				submitBtn.prop('disabled', false).html(originalText);
			}
		});
	});

	$('#editBerkasForm').submit(function(e) {
		e.preventDefault();

		// Disable submit button during processing
		var submitBtn = $(this).find('button[type="submit"]');
		var originalText = submitBtn.html();
		submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

		$.ajax({
			url: getAjaxUrl('notelen/ajax_update_berkas'),
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			timeout: 30000,
			success: function(response) {
				console.log('Update response:', response);

				if (response && response.success) {
					$('#editBerkasModal').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Berhasil!',
						text: response.message || 'Berkas berhasil diupdate',
						timer: 2000
					}).then(() => {
						location.reload();
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: response.message || 'Terjadi kesalahan saat update data'
					});
				}
			},
			error: function(xhr, status, error) {
				console.log('AJAX Error Details:');
				console.log('Status:', status);
				console.log('Error:', error);
				console.log('Response Text:', xhr.responseText);

				var errorMessage = 'Terjadi kesalahan koneksi';

				try {
					if (xhr.responseText) {
						var errorResponse = JSON.parse(xhr.responseText);
						if (errorResponse && errorResponse.message) {
							errorMessage = errorResponse.message;
						}
					}
				} catch (e) {
					if (xhr.responseText && xhr.responseText.length > 0) {
						errorMessage = 'Server Error: ' + xhr.responseText.substring(0, 200);
					} else if (status === 'timeout') {
						errorMessage = 'Request timeout - coba lagi';
					}
				}

				Swal.fire({
					icon: 'error',
					title: 'Error Update!',
					text: errorMessage
				});
			},
			complete: function() {
				// Re-enable submit button
				submitBtn.prop('disabled', false).html(originalText);
			}
		});
	});

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
				$.ajax({
					url: getAjaxUrl('notelen/ajax_sync_sipp'),
					type: 'POST',
					data: {
						limit: 100
					},
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							Swal.fire({
								icon: 'success',
								title: 'Sync Berhasil!',
								text: response.message,
								timer: 3000
							}).then(() => {
								location.reload();
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Sync Gagal!',
								text: response.message
							});
						}
					},
					error: function() {
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: 'Terjadi kesalahan saat sync data'
						});
					}
				});
			}
		});
	}

	function openInventarisModal(berkas_id, nomor_perkara) {
		Swal.fire({
			title: 'Inventaris Berkas',
			text: 'Fitur inventaris untuk berkas ' + nomor_perkara + ' akan segera tersedia',
			icon: 'info'
		});
	}

	function openBerkasDetail(berkas_id) {
		Swal.fire({
			title: 'Detail Berkas',
			text: 'Fitur detail berkas akan segera tersedia',
			icon: 'info'
		});
	}

	function deleteBerkas(berkas_id, nomor_perkara) {
		Swal.fire({
			title: 'Hapus Berkas?',
			text: 'Apakah Anda yakin ingin menghapus berkas ' + nomor_perkara + '?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#dc3545',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				// Simple approach - just redirect with delete parameter
				window.location.href = getAjaxUrl('notelen/ajax_delete_berkas') + '?id=' + berkas_id + '&redirect=1';
			}
		});
	}
</script>

<?php $this->load->view('template/new_footer'); ?>