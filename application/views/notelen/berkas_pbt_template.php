<?php $this->load->view('template/new_header'); ?>

<?php $this->load->view('template/new_sidebar'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
	<!-- Content Header -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Berkas PBT</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?= base_url('notelen') ?>">Notelen</a></li>
						<li class="breadcrumb-item active">Berkas PBT</li>
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
					<div class="info-box bg-info">
						<span class="info-box-icon"><i class="fas fa-gavel"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Total PBT</span>
							<span class="info-box-number"><?= isset($stats['pbt']) && $stats['pbt'] && isset($stats['pbt']->total_pbt) ? $stats['pbt']->total_pbt : 0 ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="info-box bg-warning">
						<span class="info-box-icon"><i class="fas fa-clock"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Belum PBT</span>
							<span class="info-box-number"><?= isset($stats['pbt']) && $stats['pbt'] && isset($stats['pbt']->belum_pbt) ? $stats['pbt']->belum_pbt : 0 ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="info-box bg-primary">
						<span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Sudah PBT Belum BHT</span>
							<span class="info-box-number"><?= isset($stats['pbt']) && $stats['pbt'] && isset($stats['pbt']->sudah_pbt_belum_bht) ? $stats['pbt']->sudah_pbt_belum_bht : 0 ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="info-box bg-success">
						<span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Selesai</span>
							<span class="info-box-number"><?= isset($stats['pbt']) && $stats['pbt'] && isset($stats['pbt']->selesai) ? $stats['pbt']->selesai : 0 ?></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Filter Card -->
			<div class="card card-outline card-primary">
				<div class="card-header">
					<h3 class="card-title">
						<i class="fas fa-filter"></i> Filter Data
					</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-sm btn-success" onclick="openNewPbtModal()">
							<i class="fas fa-plus"></i> Tambah PBT Manual
						</button>
						<button type="button" class="btn btn-sm btn-info" onclick="syncFromSipp()">
							<i class="fas fa-sync"></i> Sync dari SIPP
						</button>
					</div>
				</div>
				<div class="card-body">
					<form method="get" action="<?= base_url('notelen/berkas_pbt') ?>">
						<div class="row">
							<div class="col-md-3">
								<label>Status Proses:</label>
								<select name="status" class="form-control">
									<option value="">Semua Status</option>
									<option value="Belum PBT" <?= (isset($filters['status_proses']) ? $filters['status_proses'] : '') == 'Belum PBT' ? 'selected' : '' ?>>Belum PBT</option>
									<option value="Sudah PBT Belum BHT" <?= (isset($filters['status_proses']) ? $filters['status_proses'] : '') == 'Sudah PBT Belum BHT' ? 'selected' : '' ?>>Sudah PBT Belum BHT</option>
									<option value="Selesai" <?= (isset($filters['status_proses']) ? $filters['status_proses'] : '') == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
								</select>
							</div>
							<div class="col-md-3">
								<label>Nomor Perkara:</label>
								<input type="text" name="nomor" class="form-control" placeholder="Cari nomor perkara..." value="<?= isset($filters['nomor_perkara']) ? $filters['nomor_perkara'] : '' ?>">
							</div>
							<div class="col-md-2">
								<label>Dari Tanggal:</label>
								<input type="date" name="dari" class="form-control" value="<?= isset($filters['tanggal_dari']) ? $filters['tanggal_dari'] : '' ?>">
							</div>
							<div class="col-md-2">
								<label>Sampai Tanggal:</label>
								<input type="date" name="sampai" class="form-control" value="<?= isset($filters['tanggal_sampai']) ? $filters['tanggal_sampai'] : '' ?>">
							</div>
							<div class="col-md-2">
								<label>&nbsp;</label><br>
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-search"></i> Filter
								</button>
								<a href="<?= base_url('notelen/reset_pbt_filters') ?>" class="btn btn-secondary">Reset</a>
							</div>
						</div>
					</form>
				</div>
			</div>

			<!-- Data Table -->
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">
						<i class="fas fa-list"></i> Data Berkas PBT
					</h3>
					<div class="card-tools">
						<span class="badge badge-info">Total: <?= $total_pbt ?></span>
					</div>
				</div>
				<div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap">
						<thead>
							<tr>
								<th>No</th>
								<th>Nomor Perkara</th>
								<th>Tanggal Putusan</th>
								<th>Jenis Perkara</th>
								<th>Tanggal PBT</th>
								<th>Majelis Hakim</th>
								<th>Panitera</th>
								<th>Selisih Hari</th>
								<th>Status Proses</th>
								<th>Catatan PBT</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (isset($pbt_list) && count($pbt_list) > 0): ?>
								<?php foreach ($pbt_list as $index => $pbt): ?>
									<?php
									$row_class = '';
									if ($pbt->is_duplicate_berkas) {
										$row_class = 'table-danger'; // Red background for duplicates
									}
									?>
									<tr class="<?= $row_class ?>">
										<td><?= $offset + $index + 1 ?></td>
										<td>
											<?= $pbt->nomor_perkara ?>
											<?php if ($pbt->is_duplicate_berkas): ?>
												<span class="badge badge-danger" title="Nomor perkara sudah ada di Berkas Masuk">
													<i class="fas fa-exclamation-triangle"></i> DUPLIKAT
												</span>
											<?php endif; ?>
										</td>
										<td><?= date('d/m/Y', strtotime($pbt->tanggal_putusan)) ?></td>
										<td><?= $pbt->jenis_perkara ?></td>
										<td>
											<?php if ($pbt->tanggal_pbt): ?>
												<?= date('d/m/Y', strtotime($pbt->tanggal_pbt)) ?>
											<?php else: ?>
												<span class="text-muted">-</span>
											<?php endif; ?>
										</td>
										<td><?= $pbt->majelis_hakim ?: '-' ?></td>
										<td><?= $pbt->panitera_pengganti ?: '-' ?></td>
										<td>
											<?php if ($pbt->selisih_putus_pbt): ?>
												<span class="badge badge-<?= $pbt->selisih_putus_pbt > 14 ? 'danger' : 'info' ?>">
													<?= $pbt->selisih_putus_pbt ?> hari
												</span>
											<?php else: ?>
												<span class="text-muted">-</span>
											<?php endif; ?>
										</td>
										<td>
											<?php
											$status_class = '';
											switch ($pbt->status_proses) {
												case 'Belum PBT':
													$status_class = 'warning';
													break;
												case 'Sudah PBT Belum BHT':
													$status_class = 'primary';
													break;
												case 'Selesai':
													$status_class = 'success';
													break;
											}
											?>
											<span class="badge badge-<?= $status_class ?>">
												<?= $pbt->status_proses ?>
											</span>
										</td>
										<td><?= $pbt->catatan_pbt ?: '-' ?></td>
										<td>
											<div class="btn-group">
												<button type="button" class="btn btn-sm btn-info" onclick="openPbtDetail(<?= $pbt->id ?>)" title="Lihat Detail">
													<i class="fas fa-eye"></i>
												</button>
												<button type="button" class="btn btn-sm btn-warning" onclick="editPbt(<?= $pbt->id ?>)" title="Edit">
													<i class="fas fa-edit"></i>
												</button>
												<button type="button" class="btn btn-sm btn-danger" onclick="deletePbt(<?= $pbt->id ?>, '<?= $pbt->nomor_perkara ?>')" title="Hapus">
													<i class="fas fa-trash"></i>
												</button>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="11" class="text-center">
										<div class="p-3">
											<i class="fas fa-inbox fa-3x text-muted mb-3"></i>
											<h5 class="text-muted">Tidak ada data berkas PBT</h5>
											<p class="text-muted">Silakan sync dari SIPP atau tambah manual</p>
											<button class="btn btn-primary" onclick="syncFromSipp()">
												<i class="fas fa-sync"></i> Sync dari SIPP
											</button>
										</div>
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<!-- Pagination -->
				<?php if ($total_pages > 1): ?>
					<div class="card-footer clearfix">
						<ul class="pagination pagination-sm m-0 float-right">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?= base_url('notelen/berkas_pbt?page=' . ($current_page - 1)) ?>">&laquo; Previous</a>
								</li>
							<?php endif; ?>

							<?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
								<li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
									<a class="page-link" href="<?= base_url('notelen/berkas_pbt?page=' . $i) ?>"><?= $i ?></a>
								</li>
							<?php endfor; ?>

							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?= base_url('notelen/berkas_pbt?page=' . ($current_page + 1)) ?>">Next &raquo;</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</section>
</div>

<!-- Modal Tambah PBT -->
<div class="modal fade" id="newPbtModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title">
					<i class="fas fa-plus"></i> Tambah Berkas PBT Manual
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="newPbtForm">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="nomorPerkaraPbt">Nomor Perkara *
									<small class="text-muted">(Ketik untuk mencari dari SIPP)</small>
								</label>
								<div class="input-group">
									<input type="hidden" id="perkaraIdSipp" name="perkara_id_sipp">
									<input type="text" class="form-control" id="nomorPerkaraPbt" name="nomor_perkara"
										placeholder="Ketik nomor perkara untuk mencari..."
										autocomplete="off" required>
									<div class="input-group-append">
										<button type="button" class="btn btn-outline-secondary" id="clearPerkara" title="Bersihkan">
											<i class="fas fa-times"></i>
										</button>
									</div>
								</div>
								<div id="perkaraSuggestions" class="list-group" style="position: absolute; z-index: 1000; max-height: 200px; overflow-y: auto; display: none; width: 100%;"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="jenisPerkaraPbt">Jenis Perkara
									<small class="text-muted" id="jenisHelp">(Manual input atau auto-fill dari SIPP)</small>
								</label>
								<input type="text" class="form-control" id="jenisPerkaraPbt" name="jenis_perkara">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="tanggalPutusan">Tanggal Putusan *
									<small class="text-muted" id="tanggalHelp">(Manual input atau auto-fill dari SIPP)</small>
								</label>
								<input type="date" class="form-control" id="tanggalPutusan" name="tanggal_putusan" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="tanggalPbt">Tanggal PBT</label>
								<input type="date" class="form-control" id="tanggalPbt" name="tanggal_pbt">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="tanggalBht">Tanggal BHT</label>
								<input type="date" class="form-control" id="tanggalBht" name="tanggal_bht">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="majelisHakim">Majelis Hakim
									<small class="text-muted" id="majelisHelp">(Manual input atau auto-fill dari SIPP)</small>
								</label>
								<textarea class="form-control" id="majelisHakim" name="majelis_hakim" rows="2"></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="paniteraPengganti">Panitera Pengganti
									<small class="text-muted" id="paniteraHelp">(Manual input atau auto-fill dari SIPP)</small>
								</label>
								<input type="text" class="form-control" id="paniteraPengganti" name="panitera_pengganti">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="catatanPbt">Catatan PBT</label>
						<textarea class="form-control" id="catatanPbt" name="catatan_pbt" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> Batal
				</button>
				<button type="submit" form="newPbtForm" class="btn btn-primary">
					<i class="fas fa-save"></i> Simpan
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Detail PBT -->
<div class="modal fade" id="pbtDetailModal" tabindex="-1">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h4 class="modal-title">
					<i class="fas fa-eye"></i> Detail Berkas PBT
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<!-- Basic Information -->
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-info"><i class="fas fa-gavel"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Nomor Perkara</span>
								<span class="info-box-number" id="detailNomorPerkara">-</span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="info-box">
							<span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Tanggal Putusan</span>
								<span class="info-box-number" id="detailTanggalPutusan">-</span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="card card-outline card-info">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Perkara</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<table class="table table-sm">
											<tr>
												<td><strong>Jenis Perkara:</strong></td>
												<td id="detailJenisPerkara">-</td>
											</tr>
											<tr>
												<td><strong>Status Proses:</strong></td>
												<td><span class="badge" id="detailStatusProses">-</span></td>
											</tr>
											<tr>
												<td><strong>Perkara ID SIPP:</strong></td>
												<td id="detailPerkaraIdSipp">-</td>
											</tr>
										</table>
									</div>
									<div class="col-md-6">
										<table class="table table-sm">
											<tr>
												<td><strong>Tanggal PBT:</strong></td>
												<td id="detailTanggalPbt">-</td>
											</tr>
											<tr>
												<td><strong>Tanggal BHT:</strong></td>
												<td id="detailTanggalBht">-</td>
											</tr>
											<tr>
												<td><strong>Selisih Hari:</strong></td>
												<td><span class="badge" id="detailSelisihHari">-</span></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="card card-outline card-secondary">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-users"></i> Petugas</h3>
							</div>
							<div class="card-body">
								<table class="table table-sm">
									<tr>
										<td><strong>Majelis Hakim:</strong></td>
										<td id="detailMajelisHakim">-</td>
									</tr>
									<tr>
										<td><strong>Panitera Pengganti:</strong></td>
										<td id="detailPaniteraPengganti">-</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card card-outline card-warning">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-sticky-note"></i> Catatan</h3>
							</div>
							<div class="card-body">
								<div id="detailCatatanPbt" class="text-muted">Tidak ada catatan</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="card card-outline card-success">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-clock"></i> Timeline</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<table class="table table-sm">
											<tr>
												<td><strong>Dibuat:</strong></td>
												<td id="detailCreatedAt">-</td>
											</tr>
										</table>
									</div>
									<div class="col-md-6">
										<table class="table table-sm">
											<tr>
												<td><strong>Terakhir Update:</strong></td>
												<td id="detailUpdatedAt">-</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" onclick="editFromPbtView()">
					<i class="fas fa-edit"></i> Edit Data
				</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Edit PBT -->
<div class="modal fade" id="editPbtModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<h4 class="modal-title">
					<i class="fas fa-edit"></i> Edit Berkas PBT
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="editPbtForm">
					<input type="hidden" id="editPbtId" name="id">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="editNomorPerkara">Nomor Perkara *</label>
								<input type="text" class="form-control" id="editNomorPerkara" name="nomor_perkara" required readonly>
								<small class="text-muted">Nomor perkara tidak dapat diubah</small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="editJenisPerkara">Jenis Perkara</label>
								<input type="text" class="form-control" id="editJenisPerkara" name="jenis_perkara" readonly>
								<small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="editTanggalPutusan">Tanggal Putusan</label>
								<input type="date" class="form-control" id="editTanggalPutusan" name="tanggal_putusan" readonly>
								<small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="editTanggalPbt">Tanggal PBT</label>
								<input type="date" class="form-control" id="editTanggalPbt" name="tanggal_pbt">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="editTanggalBht">Tanggal BHT</label>
								<input type="date" class="form-control" id="editTanggalBht" name="tanggal_bht">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="editMajelisHakim">Majelis Hakim</label>
								<textarea class="form-control" id="editMajelisHakim" name="majelis_hakim" rows="2" readonly></textarea>
								<small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="editPaniteraPengganti">Panitera Pengganti</label>
								<input type="text" class="form-control" id="editPaniteraPengganti" name="panitera_pengganti" readonly>
								<small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="editCatatanPbt">Catatan PBT</label>
						<textarea class="form-control" id="editCatatanPbt" name="catatan_pbt" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> Batal
				</button>
				<button type="submit" form="editPbtForm" class="btn btn-warning">
					<i class="fas fa-save"></i> Update
				</button>
			</div>
		</div>
	</div>
</div>

<!-- JavaScript untuk SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(document).ready(function() {
		console.log('Berkas PBT System loaded successfully!');

		// Auto-complete nomor perkara dari SIPP
		let searchTimeout;
		let isLoading = false;

		$('#nomorPerkaraPbt').on('input', function() {
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
                        <i class="fas fa-spinner fa-spin"></i> Mencari perkara...
                    </div>
                `).show();

				$.ajax({
					url: '<?= base_url("notelen/ajax_get_perkara_dropdown") ?>',
					type: 'GET',
					data: {
						search: search
					},
					dataType: 'json',
					success: function(response) {
						if (response.success && response.data.length > 0) {
							let suggestions = '';
							response.data.forEach(function(item) {
								suggestions += `
                                    <a href="#" class="list-group-item list-group-item-action perkara-suggestion" 
                                       data-perkara-id="${item.perkara_id}"
                                       data-nomor="${item.nomor_perkara}"
                                       data-jenis="${item.jenis_perkara || ''}"
                                       data-tanggal="${item.tanggal_putusan || ''}"
                                       data-majelis="${item.majelis_hakim || ''}"
                                       data-panitera="${item.panitera_pengganti || ''}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">${item.nomor_perkara}</h6>
                                            <small class="text-primary">${item.tanggal_putusan || ''}</small>
                                        </div>
                                        <p class="mb-1 text-muted">${item.jenis_perkara || 'Tidak ada jenis'}</p>
                                        <small class="text-secondary">Majelis: ${item.majelis_hakim || 'Tidak ada data'}</small>
                                    </a>
                                `;
							});
							$('#perkaraSuggestions').html(suggestions).show();
						} else {
							$('#perkaraSuggestions').html(`
                                <div class="list-group-item text-muted">
                                    <i class="fas fa-search"></i> Tidak ada data perkara ditemukan untuk "${search}"
                                </div>
                            `).show();
						}
					},
					error: function() {
						$('#perkaraSuggestions').html(`
                            <div class="list-group-item text-danger">
                                <i class="fas fa-exclamation-circle"></i> Error mencari data perkara dari SIPP
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
			$('#nomorPerkaraPbt').val(data.nomor);
			$('#jenisPerkaraPbt').val(data.jenis);
			$('#tanggalPutusan').val(data.tanggal);
			$('#majelisHakim').val(data.majelis);
			$('#paniteraPengganti').val(data.panitera);

			// Mark as selected from SIPP
			$('#nomorPerkaraPbt').data('selected-from-sipp', true);

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
			$('#nomorPerkaraPbt').val('').data('selected-from-sipp', false);
			$('#jenisPerkaraPbt').val('');
			$('#tanggalPutusan').val('');
			$('#majelisHakim').val('');
			$('#paniteraPengganti').val('');
			$('#perkaraSuggestions').hide();

			// Update visual indicators to show manual input
			updateFieldLabels(false);

			$('#nomorPerkaraPbt').focus();
		});

		// Function to update field labels based on source
		function updateFieldLabels(fromSipp) {
			if (fromSipp) {
				$('#jenisHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
				$('#tanggalHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
				$('#majelisHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
				$('#paniteraHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');

				// Add success icons
				$('#jenisPerkaraPbt, #tanggalPutusan, #majelisHakim, #paniteraPengganti')
					.addClass('border-success');
			} else {
				$('#jenisHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
				$('#tanggalHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
				$('#majelisHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
				$('#paniteraHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');

				// Remove success styling
				$('#jenisPerkaraPbt, #tanggalPutusan, #majelisHakim, #paniteraPengganti')
					.removeClass('border-success');
			}
		}

		// Manual typing validation - simplified
		$('#nomorPerkaraPbt').on('blur', function() {
			const search = $(this).val();
			const selectedFromSipp = $(this).data('selected-from-sipp');

			if (search && !selectedFromSipp) {
				// Remove success styling if manually typed
				updateFieldLabels(false);

				// Optional: Show info toast instead of warning
				if (search.length > 5) {
					Swal.fire({
						icon: 'info',
						title: 'Input Manual',
						text: 'Anda dapat melengkapi data lainnya secara manual atau cari dari SIPP',
						showConfirmButton: false,
						timer: 2000,
						toast: true,
						position: 'top-end'
					});
				}
			}
		}); // Hide suggestions when click outside
		$(document).click(function(e) {
			if (!$(e.target).closest('#nomorPerkaraPbt, #perkaraSuggestions').length) {
				$('#perkaraSuggestions').hide();
			}
		});
	});

	function openNewPbtModal() {
		$('#newPbtModal').modal('show');
		$('#newPbtForm')[0].reset();
		$('#perkaraSuggestions').hide();

		// Reset form state
		$('#nomorPerkaraPbt').data('selected-from-sipp', false);
		$('#perkaraIdSipp').val('');

		// Reset visual indicators
		updateFieldLabels(false);

		// Focus ke nomor perkara
		setTimeout(function() {
			$('#nomorPerkaraPbt').focus();
		}, 500);
	}
	$('#newPbtForm').submit(function(e) {
		e.preventDefault();

		Swal.fire({
			title: 'Menyimpan...',
			text: 'Sedang menyimpan data PBT',
			icon: 'info',
			allowOutsideClick: false,
			showConfirmButton: false,
			willOpen: () => {
				Swal.showLoading();
			}
		});

		$.ajax({
			url: '<?= base_url("notelen/ajax_insert_pbt") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil!',
						text: response.message,
						showConfirmButton: false,
						timer: 1500
					}).then(() => {
						window.location.reload();
					});
					$('#newPbtModal').modal('hide');
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: response.message,
						confirmButtonText: 'OK'
					});
				}
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan saat menyimpan data',
					confirmButtonText: 'OK'
				});
			}
		});
	});

	function syncFromSipp() {
		Swal.fire({
			title: 'Sync dari SIPP?',
			text: 'Proses ini akan mengambil data perkara putus dari database SIPP',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#007bff',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Ya, Sync!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: 'Syncing...',
					text: 'Mengambil data dari SIPP, mohon tunggu...',
					icon: 'info',
					allowOutsideClick: false,
					showConfirmButton: false,
					willOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: '<?= base_url("notelen/ajax_sync_pbt") ?>',
					type: 'POST',
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							Swal.fire({
								icon: 'success',
								title: 'Sync Berhasil!',
								text: response.message,
								showConfirmButton: false,
								timer: 2000
							}).then(() => {
								window.location.reload();
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Sync Gagal!',
								text: response.message,
								confirmButtonText: 'OK'
							});
						}
					},
					error: function() {
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: 'Terjadi kesalahan saat sync data',
							confirmButtonText: 'OK'
						});
					}
				});
			}
		});
	}

	// Function untuk format date display
	function formatDate(dateString) {
		if (!dateString || dateString === '0000-00-00' || dateString === null) return '-';

		try {
			const date = new Date(dateString);
			const day = date.getDate().toString().padStart(2, '0');
			const month = (date.getMonth() + 1).toString().padStart(2, '0');
			const year = date.getFullYear();

			return `${day}/${month}/${year}`;
		} catch (e) {
			return dateString;
		}
	}

	// Function untuk format datetime display
	function formatDateTime(dateTimeString) {
		if (!dateTimeString || dateTimeString === '0000-00-00 00:00:00' || dateTimeString === null) return '-';

		try {
			const date = new Date(dateTimeString);
			const day = date.getDate().toString().padStart(2, '0');
			const month = (date.getMonth() + 1).toString().padStart(2, '0');
			const year = date.getFullYear();
			const hours = date.getHours().toString().padStart(2, '0');
			const minutes = date.getMinutes().toString().padStart(2, '0');

			return `${day}/${month}/${year} ${hours}:${minutes}`;
		} catch (e) {
			return dateTimeString;
		}
	}

	function openPbtDetail(pbt_id) {
		// Show loading state
		Swal.fire({
			title: 'Memuat Detail...',
			text: 'Mengambil data PBT',
			icon: 'info',
			allowOutsideClick: false,
			showConfirmButton: false,
			willOpen: () => {
				Swal.showLoading();
			}
		});

		// Get PBT data via AJAX
		$.ajax({
			url: '<?= base_url("notelen/ajax_get_pbt") ?>',
			type: 'GET',
			data: {
				id: pbt_id
			},
			dataType: 'json',
			success: function(response) {
				Swal.close();

				if (response.success && response.data) {
					const data = response.data;

					// Populate modal dengan data PBT
					$('#detailNomorPerkara').text(data.nomor_perkara || '-');
					$('#detailTanggalPutusan').text(formatDate(data.tanggal_putusan));
					$('#detailJenisPerkara').text(data.jenis_perkara || '-');
					$('#detailTanggalPbt').text(formatDate(data.tanggal_pbt));
					$('#detailTanggalBht').text(formatDate(data.tanggal_bht));
					$('#detailMajelisHakim').text(data.majelis_hakim || '-');
					$('#detailPaniteraPengganti').text(data.panitera_pengganti || '-');
					$('#detailPerkaraIdSipp').text(data.perkara_id_sipp || '-');
					$('#detailCreatedAt').text(formatDateTime(data.created_at));
					$('#detailUpdatedAt').text(formatDateTime(data.updated_at));

					// Status proses dengan badge warna
					const statusBadge = $('#detailStatusProses');
					statusBadge.text(data.status_proses || '-');
					statusBadge.removeClass('badge-warning badge-primary badge-success');

					switch (data.status_proses) {
						case 'Belum PBT':
							statusBadge.addClass('badge-warning');
							break;
						case 'Sudah PBT Belum BHT':
							statusBadge.addClass('badge-primary');
							break;
						case 'Selesai':
							statusBadge.addClass('badge-success');
							break;
						default:
							statusBadge.addClass('badge-secondary');
					}

					// Selisih hari dengan badge warna
					const selisihBadge = $('#detailSelisihHari');
					if (data.selisih_putus_pbt && data.selisih_putus_pbt > 0) {
						selisihBadge.text(data.selisih_putus_pbt + ' hari');
						selisihBadge.removeClass('badge-info badge-danger');
						selisihBadge.addClass(data.selisih_putus_pbt > 14 ? 'badge-danger' : 'badge-info');
					} else {
						selisihBadge.text('-').removeClass('badge-info badge-danger').addClass('badge-secondary');
					}

					// Catatan PBT
					if (data.catatan_pbt && data.catatan_pbt.trim()) {
						$('#detailCatatanPbt').html(data.catatan_pbt.replace(/\n/g, '<br>')).removeClass('text-muted');
					} else {
						$('#detailCatatanPbt').text('Tidak ada catatan').addClass('text-muted');
					}

					// Store ID untuk edit function
					$('#pbtDetailModal').data('pbt-id', pbt_id);

					// Show modal
					$('#pbtDetailModal').modal('show');
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Data Tidak Ditemukan',
						text: response.message || 'PBT tidak ditemukan atau terjadi kesalahan'
					});
				}
			},
			error: function(xhr, status, error) {
				Swal.close();
				console.error('AJAX Error:', error);
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan saat mengambil data PBT'
				});
			}
		});
	}

	function editPbt(pbt_id) {
		// Show loading state
		Swal.fire({
			title: 'Memuat Data...',
			text: 'Mengambil data untuk edit',
			icon: 'info',
			allowOutsideClick: false,
			showConfirmButton: false,
			willOpen: () => {
				Swal.showLoading();
			}
		});

		// Get PBT data untuk edit
		$.ajax({
			url: '<?= base_url("notelen/ajax_get_pbt") ?>',
			type: 'GET',
			data: {
				id: pbt_id
			},
			dataType: 'json',
			success: function(response) {
				Swal.close();

				if (response.success && response.data) {
					const data = response.data;

					// Populate form edit dengan data PBT
					$('#editPbtId').val(data.id);
					$('#editNomorPerkara').val(data.nomor_perkara || '');
					$('#editJenisPerkara').val(data.jenis_perkara || '');
					$('#editTanggalPutusan').val(data.tanggal_putusan || '');
					$('#editTanggalPbt').val(data.tanggal_pbt || '');
					$('#editTanggalBht').val(data.tanggal_bht || '');
					$('#editMajelisHakim').val(data.majelis_hakim || '');
					$('#editPaniteraPengganti').val(data.panitera_pengganti || '');
					$('#editCatatanPbt').val(data.catatan_pbt || '');

					// Show modal edit
					$('#editPbtModal').modal('show');
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Data Tidak Ditemukan',
						text: response.message || 'PBT tidak ditemukan atau terjadi kesalahan'
					});
				}
			},
			error: function(xhr, status, error) {
				Swal.close();
				console.error('AJAX Error:', error);
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan saat mengambil data PBT'
				});
			}
		});
	}

	// Function untuk edit dari modal detail
	function editFromPbtView() {
		const pbtId = $('#pbtDetailModal').data('pbt-id');
		if (pbtId) {
			$('#pbtDetailModal').modal('hide');
			setTimeout(() => {
				editPbt(pbtId);
			}, 300);
		}
	}

	// Submit handler untuk form edit PBT
	$('#editPbtForm').submit(function(e) {
		e.preventDefault();

		// No validation needed - all required fields are readonly
		// Only editable fields: tanggal_pbt, tanggal_bht, catatan_pbt (all optional)

		// Show loading
		Swal.fire({
			title: 'Menyimpan...',
			text: 'Memperbarui data PBT',
			icon: 'info',
			allowOutsideClick: false,
			showConfirmButton: false,
			willOpen: () => {
				Swal.showLoading();
			}
		});

		// Submit via AJAX
		$.ajax({
			url: '<?= base_url("notelen/ajax_update_pbt") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil!',
						text: response.message || 'Data PBT berhasil diperbarui',
						showConfirmButton: false,
						timer: 1500
					}).then(() => {
						$('#editPbtModal').modal('hide');
						window.location.reload();
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Gagal Update!',
						text: response.message || 'Terjadi kesalahan saat memperbarui data'
					});
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', error);
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan saat memperbarui data PBT'
				});
			}
		});
	});

	function deletePbt(pbt_id, nomor_perkara) {
		Swal.fire({
			title: 'Hapus PBT?',
			text: 'Apakah Anda yakin ingin menghapus PBT ' + nomor_perkara + '?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#dc3545',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				// Simple approach - just redirect with delete parameter
				window.location.href = '<?= base_url("notelen/ajax_delete_pbt") ?>?id=' + pbt_id + '&redirect=1';
			}
		});
	}
</script>

<?php $this->load->view('template/new_footer'); ?>