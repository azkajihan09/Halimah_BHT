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
					<h1 class="m-0">
						<i class="fas fa-calendar-day text-primary"></i>
						Berkas Masuk Otomatis Harian
					</h1>
					<p class="text-muted">
						<i class="fas fa-info-circle"></i>
						Perkara putus otomatis masuk berkas per hari
					</p>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?= base_url('notelen') ?>">Notelen</a></li>
						<li class="breadcrumb-item active">Berkas Otomatis Harian</li>
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
					<i class="fas fa-check-circle"></i>
					<?= $this->session->flashdata('success') ?>
					<button type="button" class="close" data-dismiss="alert">
						<span>&times;</span>
					</button>
				</div>
			<?php endif; ?>

			<?php if ($this->session->flashdata('error')): ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<i class="fas fa-exclamation-circle"></i>
					<?= $this->session->flashdata('error') ?>
					<button type="button" class="close" data-dismiss="alert">
						<span>&times;</span>
					</button>
				</div>
			<?php endif; ?>

			<!-- Date Picker Card -->
			<div class="row">
				<div class="col-12">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-calendar-alt mr-2"></i>
								Pilih Tanggal Perkara Putus
							</h3>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="tanggalPerkara">
											<i class="fas fa-calendar-day"></i>
											Tanggal Perkara Putus
										</label>
										<div class="input-group">
											<input type="date" class="form-control" id="tanggalPerkara"
												value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">
											<div class="input-group-append">
												<button type="button" class="btn btn-primary" onclick="loadPerkaraPutusHarian()">
													<i class="fas fa-search"></i> Cari Perkara
												</button>
											</div>
										</div>
										<small class="text-muted">
											<i class="fas fa-info-circle"></i>
											Pilih tanggal untuk melihat perkara yang putus pada hari tersebut
										</small>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>
											<i class="fas fa-chart-line"></i>
											Quick Date Options
										</label>
										<div class="btn-group-vertical d-block">
											<button type="button" class="btn btn-outline-primary btn-sm mb-1" onclick="setTanggal('today')">
												<i class="fas fa-calendar-day"></i> Hari Ini
											</button>
											<button type="button" class="btn btn-outline-secondary btn-sm mb-1" onclick="setTanggal('yesterday')">
												<i class="fas fa-calendar-minus"></i> Kemarin
											</button>
											<button type="button" class="btn btn-outline-info btn-sm" onclick="setTanggal('week')">
												<i class="fas fa-calendar-week"></i> Minggu Lalu
											</button>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>
											<i class="fas fa-info-circle"></i>
											Informasi
										</label>
										<div class="bg-light p-3 rounded">
											<small class="text-muted">
												<i class="fas fa-lightbulb text-warning"></i>
												<strong>Fitur Auto Entry:</strong><br>
												- Data perkara putus diambil otomatis dari SIPP<br>
												- Berkas masuk dengan klik manual<br>
												- Data terbaru ditampilkan di atas
											</small>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Statistics Cards -->
			<div class="row" id="statisticsRow" style="display:none;">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3 id="totalPerkaraHari">0</h3>
							<p>Total Perkara Hari Ini</p>
						</div>
						<div class="icon">
							<i class="fas fa-gavel"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3 id="sudahMasukBerkas">0</h3>
							<p>Sudah Masuk Berkas</p>
						</div>
						<div class="icon">
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3 id="belumMasukBerkas">0</h3>
							<p>Belum Masuk Berkas</p>
						</div>
						<div class="icon">
							<i class="fas fa-clock"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3 id="perluPerhatian">0</h3>
							<p>Perlu Perhatian</p>
						</div>
						<div class="icon">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
					</div>
				</div>
			</div>

			<!-- Data Table -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-list mr-2"></i>
								Data Perkara Putus <span id="tanggalSelected">-</span>
							</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" id="refreshButton" onclick="loadPerkaraPutusHarian()">
									<i class="fas fa-sync-alt"></i>
								</button>
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body">
							<div id="loadingIndicator" class="text-center" style="display:none;">
								<div class="spinner-border text-primary" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<p class="mt-2 text-muted">Memuat data perkara putus...</p>
							</div>

							<div id="noDataIndicator" class="text-center" style="display:none;">
								<div class="alert alert-info">
									<i class="fas fa-info-circle"></i>
									<h5>Tidak ada data</h5>
									<p>Tidak ada perkara putus pada tanggal yang dipilih.</p>
								</div>
							</div>

							<div class="table-responsive" id="dataTableContainer" style="display:none;">
								<table class="table table-bordered table-striped table-hover" id="perkaraPutusTable">
									<thead class="thead-dark">
										<tr>
											<th width="5%">No</th>
											<th width="12%">Nomor Perkara</th>
											<th width="10%">Tanggal Putus</th>
											<th width="15%">Jenis Perkara</th>
											<th width="10%">Status BHT</th>
											<th width="12%">Majelis Hakim</th>
											<th width="10%">JSP</th>
											<th width="8%">Kategori</th>
											<th width="10%">Status Berkas</th>
											<th width="8%">Aksi</th>
										</tr>
									</thead>
									<tbody id="perkaraPutusTableBody">
										<!-- Data akan dimuat via AJAX -->
									</tbody>
								</table>
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-sm-6">
									<small class="text-muted" id="dataInfo">
										Menampilkan 0 dari 0 data
									</small>
								</div>
								<div class="col-sm-6 text-right">
									<button type="button" class="btn btn-success btn-sm" onclick="masukkanSemuaBerkas()" id="masukkanSemuaBtn" style="display:none;">
										<i class="fas fa-download"></i> Masukkan Semua ke Berkas
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<!-- Modal Detail Perkara -->
<div class="modal fade" id="detailPerkaraModal" tabindex="-1">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h4 class="modal-title">
					<i class="fas fa-info-circle"></i>
					Detail Perkara Putus
				</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<table class="table table-borderless">
							<tr>
								<td width="35%"><strong>Nomor Perkara:</strong></td>
								<td id="detailNomorPerkara">-</td>
							</tr>
							<tr>
								<td><strong>Jenis Perkara:</strong></td>
								<td id="detailJenisPerkara">-</td>
							</tr>
							<tr>
								<td><strong>Tanggal Putus:</strong></td>
								<td id="detailTanggalPutus">-</td>
							</tr>
							<tr>
								<td><strong>Majelis Hakim:</strong></td>
								<td id="detailHakim">-</td>
							</tr>
							<tr>
								<td><strong>JSP:</strong></td>
								<td id="detailJsp">-</td>
							</tr>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table table-borderless">
							<tr>
								<td width="35%"><strong>Status BHT:</strong></td>
								<td id="detailStatusBht">-</td>
							</tr>
							<tr>
								<td><strong>Kategori:</strong></td>
								<td id="detailKategori">-</td>
							</tr>
							<tr>
								<td><strong>Perkiraan BHT:</strong></td>
								<td id="detailPerkiraanBht">-</td>
							</tr>
							<tr>
								<td><strong>Target BHT:</strong></td>
								<td id="detailTargetBht">-</td>
							</tr>
							<tr>
								<td><strong>Sumber PBT:</strong></td>
								<td id="detailSumberPbt">-</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col-12">
						<h6><strong>Informasi Tambahan:</strong></h6>
						<div class="alert alert-light">
							<div class="row">
								<div class="col-md-4">
									<small><strong>Tanggal PBT Efektif:</strong></small><br>
									<span id="detailTanggalPbtEfektif" class="badge badge-info">-</span>
								</div>
								<div class="col-md-4">
									<small><strong>Hari Sejak Putus:</strong></small><br>
									<span id="detailHariSejakPutus" class="badge badge-secondary">-</span>
								</div>
								<div class="col-md-4">
									<small><strong>Sisa Hari ke Target:</strong></small><br>
									<span id="detailSisaHari" class="badge badge-warning">-</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> Tutup
				</button>
				<button type="button" class="btn btn-success" onclick="masukkanKeBerkas()" id="masukkanBerkasBtn">
					<i class="fas fa-download"></i> Masukkan ke Berkas
				</button>
			</div>
		</div>
	</div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(document).ready(function() {
		console.log('Berkas Masuk Otomatis loaded successfully!');

		// Load data untuk hari ini secara default
		loadPerkaraPutusHarian();
	});

	// Set tanggal quick options
	function setTanggal(option) {
		let tanggal = new Date();

		switch (option) {
			case 'today':
				// Already set to today
				break;
			case 'yesterday':
				tanggal.setDate(tanggal.getDate() - 1);
				break;
			case 'week':
				tanggal.setDate(tanggal.getDate() - 7);
				break;
		}

		const tanggalStr = tanggal.toISOString().split('T')[0];
		$('#tanggalPerkara').val(tanggalStr);

		// Auto load data
		loadPerkaraPutusHarian();
	}

	// Load data perkara putus harian
	function loadPerkaraPutusHarian() {
		const tanggal = $('#tanggalPerkara').val();

		if (!tanggal) {
			Swal.fire({
				icon: 'warning',
				title: 'Pilih Tanggal',
				text: 'Silakan pilih tanggal terlebih dahulu'
			});
			return;
		}

		// Update tanggal selected
		$('#tanggalSelected').text(formatDateIndonesia(tanggal));

		// Show loading
		showLoadingState();

		$.ajax({
			url: getAjaxUrl('notelen/ajax_get_perkara_putus_harian'),
			type: 'POST',
			data: {
				tanggal: tanggal
			},
			dataType: 'json',
			timeout: 30000,
			success: function(response) {
				if (response.success) {
					displayPerkaraData(response.data);
					updateStatistics(response.stats);
					showDataState();
				} else {
					showNoDataState();
					Swal.fire({
						icon: 'error',
						title: 'Gagal Memuat Data',
						text: response.message || 'Terjadi kesalahan saat memuat data'
					});
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', error);
				showNoDataState();
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Terjadi kesalahan koneksi ke server'
				});
			}
		});
	}

	// Display data di table
	function displayPerkaraData(data) {
		const tbody = $('#perkaraPutusTableBody');
		tbody.empty();

		if (data.length === 0) {
			showNoDataState();
			return;
		}

		data.forEach((item, index) => {
			const statusBadge = getStatusBadge(item.kategori_status);
			const statusBerkasBadge = getStatusBerkasBadge(item.status_berkas || 'Belum Masuk');

			const row = `
			<tr>
				<td>${index + 1}</td>
				<td>
					<strong>${item.nomor_perkara}</strong><br>
					<small class="text-muted">ID: ${item.perkara_id}</small>
				</td>
				<td>
					${formatDate(item.tanggal_putus)}<br>
					<small class="text-muted">${item.hari_sejak_putus} hari lalu</small>
				</td>
				<td>
					<span class="badge badge-secondary">${item.jenis_perkara}</span>
				</td>
				<td>
					<span class="badge ${getStatusBhtClass(item.status_bht)}">${item.status_bht}</span>
				</td>
				<td>
					<small>${item.hakim || '-'}</small>
				</td>
				<td>
					<span class="badge badge-light">${item.jsp || '-'}</span>
				</td>
				<td>
					${statusBadge}
				</td>
				<td>
					${statusBerkasBadge}
				</td>
				<td>
					<div class="btn-group-vertical btn-group-sm">
						<button type="button" class="btn btn-info btn-xs" onclick="lihatDetailPerkara('${item.perkara_id}', ${index})" title="Lihat Detail">
							<i class="fas fa-eye"></i>
						</button>
						<button type="button" class="btn btn-success btn-xs" onclick="masukkanKeBerkasLangsung('${item.perkara_id}', '${item.nomor_perkara}')" title="Masukkan ke Berkas" ${item.status_berkas === 'Sudah Masuk' ? 'disabled' : ''}>
							<i class="fas fa-download"></i>
						</button>
					</div>
				</td>
			</tr>
		`;
			tbody.append(row);
		});

		// Store data untuk keperluan lain
		window.currentPerkaraData = data;

		// Update info
		$('#dataInfo').text(`Menampilkan ${data.length} data perkara putus`);

		// Show button masukkan semua jika ada data belum masuk
		const belumMasuk = data.filter(item => item.status_berkas === 'Belum Masuk');
		if (belumMasuk.length > 0) {
			$('#masukkanSemuaBtn').show();
		} else {
			$('#masukkanSemuaBtn').hide();
		}
	}

	// Update statistics cards
	function updateStatistics(stats) {
		$('#totalPerkaraHari').text(stats.total || 0);
		$('#sudahMasukBerkas').text(stats.sudah_masuk || 0);
		$('#belumMasukBerkas').text(stats.belum_masuk || 0);
		$('#perluPerhatian').text(stats.perlu_perhatian || 0);

		$('#statisticsRow').show();
	}

	// State management functions
	function showLoadingState() {
		$('#loadingIndicator').show();
		$('#noDataIndicator').hide();
		$('#dataTableContainer').hide();
		$('#statisticsRow').hide();
		$('#refreshButton').find('i').addClass('fa-spin');
	}

	function showDataState() {
		$('#loadingIndicator').hide();
		$('#noDataIndicator').hide();
		$('#dataTableContainer').show();
		$('#refreshButton').find('i').removeClass('fa-spin');
	}

	function showNoDataState() {
		$('#loadingIndicator').hide();
		$('#noDataIndicator').show();
		$('#dataTableContainer').hide();
		$('#statisticsRow').hide();
		$('#refreshButton').find('i').removeClass('fa-spin');
	}

	// Lihat detail perkara
	function lihatDetailPerkara(perkaraId, index) {
		if (!window.currentPerkaraData || !window.currentPerkaraData[index]) {
			Swal.fire('Error', 'Data perkara tidak ditemukan', 'error');
			return;
		}

		const data = window.currentPerkaraData[index];

		// Fill modal dengan data
		$('#detailNomorPerkara').text(data.nomor_perkara);
		$('#detailJenisPerkara').text(data.jenis_perkara);
		$('#detailTanggalPutus').text(formatDate(data.tanggal_putus));
		$('#detailHakim').html(data.hakim || '-');
		$('#detailJsp').text(data.jsp || '-');
		$('#detailStatusBht').html(`<span class="badge ${getStatusBhtClass(data.status_bht)}">${data.status_bht}</span>`);
		$('#detailKategori').html(`<span class="badge ${getKategoriClass(data.kategori_status)}">${data.kategori_status}</span>`);
		$('#detailPerkiraanBht').text(formatDate(data.perkiraan_bht));
		$('#detailTargetBht').text(formatDate(data.target_bht));
		$('#detailSumberPbt').text(data.sumber_pbt || '-');
		$('#detailTanggalPbtEfektif').text(formatDate(data.tanggal_pbt_efektif));
		$('#detailHariSejakPutus').text(data.hari_sejak_putus + ' hari');
		$('#detailSisaHari').text(data.sisa_hari_ke_target + ' hari');

		// Store perkara ID for masukkan berkas button
		$('#detailPerkaraModal').data('perkara-id', perkaraId);
		$('#detailPerkaraModal').data('nomor-perkara', data.nomor_perkara);

		// Show/hide masukkan berkas button
		if (data.status_berkas === 'Sudah Masuk') {
			$('#masukkanBerkasBtn').hide();
		} else {
			$('#masukkanBerkasBtn').show();
		}

		$('#detailPerkaraModal').modal('show');
	}

	// Masukkan ke berkas dari modal detail
	function masukkanKeBerkas() {
		const perkaraId = $('#detailPerkaraModal').data('perkara-id');
		const nomorPerkara = $('#detailPerkaraModal').data('nomor-perkara');

		masukkanKeBerkasLangsung(perkaraId, nomorPerkara);
		$('#detailPerkaraModal').modal('hide');
	}

	// Masukkan ke berkas langsung
	function masukkanKeBerkasLangsung(perkaraId, nomorPerkara) {
		Swal.fire({
			title: 'Masukkan ke Berkas?',
			text: `Apakah Anda yakin ingin memasukkan perkara ${nomorPerkara} ke berkas notelen?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#dc3545',
			confirmButtonText: 'Ya, Masukkan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				prosesmasukkanKeBerkas(perkaraId, nomorPerkara);
			}
		});
	}

	// Proses masukkan ke berkas
	function prosesmasukkanKeBerkas(perkaraId, nomorPerkara) {
		$.ajax({
			url: getAjaxUrl('notelen/ajax_masukkan_berkas_otomatis'),
			type: 'POST',
			data: {
				perkara_id: perkaraId,
				nomor_perkara: nomorPerkara
			},
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil!',
						text: `Perkara ${nomorPerkara} berhasil dimasukkan ke berkas`,
						timer: 2000,
						showConfirmButton: false
					});

					// Refresh data
					loadPerkaraPutusHarian();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Gagal!',
						text: response.message || 'Terjadi kesalahan saat memasukkan berkas'
					});
				}
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan koneksi ke server'
				});
			}
		});
	}

	// Masukkan semua berkas
	function masukkanSemuaBerkas() {
		if (!window.currentPerkaraData) {
			Swal.fire('Error', 'Tidak ada data perkara', 'error');
			return;
		}

		const belumMasuk = window.currentPerkaraData.filter(item => item.status_berkas === 'Belum Masuk');

		if (belumMasuk.length === 0) {
			Swal.fire('Info', 'Semua perkara sudah masuk berkas', 'info');
			return;
		}

		Swal.fire({
			title: 'Masukkan Semua ke Berkas?',
			text: `Akan memasukkan ${belumMasuk.length} perkara ke berkas notelen`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#dc3545',
			confirmButtonText: 'Ya, Masukkan Semua!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				prosesMasukkanSemuaBerkas(belumMasuk);
			}
		});
	}

	// Proses masukkan semua berkas
	function prosesMasukkanSemuaBerkas(dataArray) {
		const perkaraIds = dataArray.map(item => item.perkara_id);

		$.ajax({
			url: getAjaxUrl('notelen/ajax_masukkan_berkas_bulk'),
			type: 'POST',
			data: {
				perkara_ids: perkaraIds
			},
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil!',
						text: `${response.total_inserted || 0} perkara berhasil dimasukkan ke berkas`,
						timer: 2000,
						showConfirmButton: false
					});

					// Refresh data
					loadPerkaraPutusHarian();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Gagal!',
						text: response.message || 'Terjadi kesalahan saat memasukkan berkas'
					});
				}
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: 'Terjadi kesalahan koneksi ke server'
				});
			}
		});
	}

	// Helper functions
	function formatDate(dateString) {
		if (!dateString) return '-';
		const date = new Date(dateString);
		return date.toLocaleDateString('id-ID', {
			day: '2-digit',
			month: '2-digit',
			year: 'numeric'
		});
	}

	function formatDateIndonesia(dateString) {
		if (!dateString) return '-';
		const date = new Date(dateString);
		const options = {
			weekday: 'long',
			year: 'numeric',
			month: 'long',
			day: 'numeric'
		};
		return date.toLocaleDateString('id-ID', options);
	}

	function getStatusBadge(status) {
		const badges = {
			'SELESAI': '<span class="badge badge-success">SELESAI</span>',
			'NORMAL': '<span class="badge badge-primary">NORMAL</span>',
			'URGENT': '<span class="badge badge-warning">URGENT</span>',
			'TERLAMBAT': '<span class="badge badge-danger">TERLAMBAT</span>',
			'CRITICAL': '<span class="badge badge-dark">CRITICAL</span>'
		};
		return badges[status] || '<span class="badge badge-secondary">' + status + '</span>';
	}

	function getStatusBerkasBadge(status) {
		const badges = {
			'PANITERA_PENGGANTI': '<span class="badge badge-primary">Panitera Pengganti</span>',
			'ALIH_MEDIA': '<span class="badge badge-info">Alih Media</span>',
			'BELUM_ADA_PBT': '<span class="badge badge-warning">Belum Ada PBT</span>',
			'MENUNGGU_BHT': '<span class="badge badge-warning">Menunggu BHT</span>',
			'SELESAI_ARSIP': '<span class="badge badge-success">Selesai Arsip</span>',
			'Belum Masuk': '<span class="badge badge-secondary">Belum Masuk</span>',
			'Sudah Masuk': '<span class="badge badge-success">Sudah Masuk</span>'
		};
		return badges[status] || '<span class="badge badge-secondary">' + status + '</span>';
	}

	function getStatusBhtClass(status) {
		if (status.includes('Critical') || status.includes('CRITICAL')) return 'badge-danger';
		if (status.includes('Terlambat') || status.includes('TERLAMBAT')) return 'badge-warning';
		if (status.includes('Urgent') || status.includes('URGENT')) return 'badge-info';
		if (status.includes('Sudah BHT') || status.includes('SELESAI')) return 'badge-success';
		return 'badge-secondary';
	}

	function getKategoriClass(kategori) {
		const classes = {
			'SELESAI': 'badge-success',
			'NORMAL': 'badge-primary',
			'URGENT': 'badge-warning',
			'TERLAMBAT': 'badge-danger',
			'CRITICAL': 'badge-dark'
		};
		return classes[kategori] || 'badge-secondary';
	}
</script>

<?php $this->load->view('template/new_footer'); ?>