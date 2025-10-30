<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">📊 BHT Perkara Putus 4 - Dengan Pengurutan Tanggal</h1>
					<p class="text-muted">Sistem monitoring BHT dengan pengurutan tanggal yang lebih baik</p>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
						<li class="breadcrumb-item active">BHT Putus 4</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">

			<!-- Filter dan Sorting Card -->
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">
						<i class="fas fa-filter"></i> Filter & Pengurutan Data
					</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="collapse">
							<i class="fas fa-minus"></i>
						</button>
					</div>
				</div>

				<div class="card-body">
					<?= form_open('bht_putus_4', array('method' => 'post', 'id' => 'filterForm')) ?>

					<div class="row">
						<!-- Jenis Perkara -->
						<div class="col-md-2">
							<div class="form-group">
								<label for="jenis_perkara">Jenis Perkara</label>
								<select name="jenis_perkara" id="jenis_perkara" class="form-control">
									<option value="Pdt.G" <?= ($jenis_perkara == 'Pdt.G') ? 'selected' : '' ?>>Pdt.G</option>
									<option value="Pdt.P" <?= ($jenis_perkara == 'Pdt.P') ? 'selected' : '' ?>>Pdt.P</option>
									<option value="Pdt.Sus" <?= ($jenis_perkara == 'Pdt.Sus') ? 'selected' : '' ?>>Pdt.Sus</option>
								</select>
							</div>
						</div>

						<!-- Bulan -->
						<div class="col-md-2">
							<div class="form-group">
								<label for="lap_bulan">Bulan</label>
								<select name="lap_bulan" id="lap_bulan" class="form-control">
									<?php foreach ($months as $key => $month): ?>
										<option value="<?= $key ?>" <?= ($lap_bulan == $key) ? 'selected' : '' ?>>
											<?= $month ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<!-- Tahun -->
						<div class="col-md-2">
							<div class="form-group">
								<label for="lap_tahun">Tahun</label>
								<select name="lap_tahun" id="lap_tahun" class="form-control">
									<?php for ($year = date('Y'); $year >= 2020; $year--): ?>
										<option value="<?= $year ?>" <?= ($lap_tahun == $year) ? 'selected' : '' ?>>
											<?= $year ?>
										</option>
									<?php endfor; ?>
								</select>
							</div>
						</div>

						<!-- Tanggal Range -->
						<div class="col-md-2">
							<div class="form-group">
								<label for="tanggal_awal">Tanggal Awal</label>
								<input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
									value="<?= $tanggal_awal ?>">
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label for="tanggal_akhir">Tanggal Akhir</label>
								<input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
									value="<?= $tanggal_akhir ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<!-- Nomor Perkara -->
						<div class="col-md-3">
							<div class="form-group">
								<label for="nomor_perkara">Nomor Perkara</label>
								<input type="text" name="nomor_perkara" id="nomor_perkara" class="form-control"
									placeholder="Cari nomor perkara..." value="<?= $nomor_perkara ?>">
							</div>
						</div>

						<!-- Urutkan Berdasarkan -->
						<div class="col-md-3">
							<div class="form-group">
								<label for="order_by">
									<i class="fas fa-sort"></i> Urutkan Berdasarkan
								</label>
								<select name="order_by" id="order_by" class="form-control">
									<?php foreach ($sort_options as $key => $option): ?>
										<option value="<?= $key ?>" <?= ($order_by == $key) ? 'selected' : '' ?>>
											<?= $option ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<!-- Arah Urutan -->
						<div class="col-md-3">
							<div class="form-group">
								<label for="order_dir">
									<i class="fas fa-sort-amount-down"></i> Arah Urutan
								</label>
								<select name="order_dir" id="order_dir" class="form-control">
									<?php foreach ($sort_directions as $key => $direction): ?>
										<option value="<?= $key ?>" <?= ($order_dir == $key) ? 'selected' : '' ?>>
											<?= $direction ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<!-- Tombol Aksi -->
						<div class="col-md-3">
							<div class="form-group">
								<label>&nbsp;</label>
								<div class="d-block">
									<button type="submit" class="btn btn-primary btn-sm">
										<i class="fas fa-search"></i> Filter
									</button>
									<button type="button" class="btn btn-secondary btn-sm" id="resetBtn" onclick="resetFormWithCache()">
										<i class="fas fa-undo"></i> Reset + Clear Cache
									</button>
									<button type="button" class="btn btn-warning btn-sm" onclick="hardResetForm()" title="Reset lengkap dengan refresh halaman">
										<i class="fas fa-sync-alt"></i> Hard Reset
									</button>
									<button type="button" class="btn btn-success btn-sm" id="exportBtn">
										<i class="fas fa-file-excel"></i> Export
									</button>
									<button type="button" class="btn btn-info btn-sm" onclick="showCacheInfo()" title="Lihat informasi cache browser">
										<i class="fas fa-info-circle"></i> Info Cache
									</button>
								</div>
							</div>
						</div>
					</div>

					<?= form_close() ?>
				</div>
			</div>

			<!-- Statistik Cards -->
			<div class="row">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= isset($statistik['total_perkara']) ? $statistik['total_perkara'] : 0 ?></h3>
							<p>Total Perkara</p>
						</div>
						<div class="icon">
							<i class="fas fa-folder"></i>
						</div>
					</div>
				</div>

				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?= isset($statistik['bht_selesai']) ? $statistik['bht_selesai'] : 0 ?></h3>
							<p>BHT Selesai</p>
						</div>
						<div class="icon">
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>

				<div class="col-lg-3 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= isset($statistik['bht_proses']) ? $statistik['bht_proses'] : 0 ?></h3>
							<p>BHT Proses</p>
						</div>
						<div class="icon">
							<i class="fas fa-clock"></i>
						</div>
					</div>
				</div>

				<div class="col-lg-3 col-6">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3><?= isset($statistik['bht_terlambat']) ? $statistik['bht_terlambat'] : 0 ?></h3>
							<p>BHT Terlambat</p>
						</div>
						<div class="icon">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
					</div>
				</div>
			</div>

			<!-- Info Periode dan Total Records -->
			<div class="row">
				<div class="col-12">
					<div class="card card-secondary">
						<div class="card-body">
							<div class="row">
								<div class="col-md-8">
									<h5>
										<i class="fas fa-calendar"></i> <?= isset($periode_text) ? $periode_text : '' ?>
									</h5>
									<p class="text-muted mb-0">
										Diurutkan berdasarkan: <strong><?= $sort_options[$order_by] ?></strong>
										(<strong><?= $sort_directions[$order_dir] ?></strong>)
									</p>
								</div>
								<div class="col-md-4 text-right">
									<h5>
										<i class="fas fa-list"></i> Total Records:
										<span class="badge badge-primary"><?= $total_records ?></span>
									</h5>
									<p class="text-muted mb-0">Data yang ditampilkan</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Tabel Data BHT -->
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">
						<i class="fas fa-table"></i> Data BHT Perkara Putus
					</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="maximize">
							<i class="fas fa-expand"></i>
						</button>
					</div>
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table id="bhtTable" class="table table-bordered table-striped table-hover">
							<thead class="thead-dark">
								<tr>
									<th style="width: 50px;">#</th>
									<th>Nomor Perkara</th>
									<th>
										<i class="fas fa-calendar"></i> Tanggal Putus
										<?php if ($order_by == 'tanggal_putus'): ?>
											<i class="fas fa-sort-<?= ($order_dir == 'DESC') ? 'down' : 'up' ?> text-warning"></i>
										<?php endif; ?>
									</th>
									<th>Jenis Perkara</th>
									<th>Panitera Pengganti</th>
									<th>Jurusita</th>
									<th>PBT</th>
									<th>
										BHT
										<?php if ($order_by == 'bht'): ?>
											<i class="fas fa-sort-<?= ($order_dir == 'DESC') ? 'down' : 'up' ?> text-warning"></i>
										<?php endif; ?>
									</th>
									<th>Ikrar</th>
									<th>Status BHT</th>
									<th>Kategori</th>
									<th>Selisih Hari</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($bht_putus) && is_array($bht_putus)): ?>
									<?php $no = 1; ?>
									<?php foreach ($bht_putus as $row): ?>
										<tr>
											<td class="text-center"><?= $no++ ?></td>
											<td>
												<strong><?= $row->nomor_perkara ?></strong>
											</td>
											<td>
												<span class="badge badge-info">
													<?= $row->tanggal_putus_formatted ?>
												</span>
											</td>
											<td>
												<span class="badge badge-secondary">
													<?= $row->jenis_perkara ?>
												</span>
											</td>
											<td><?= $row->panitera_pengganti_nama ?></td>
											<td><?= $row->jurusita_pengganti_nama ?></td>
											<td>
												<?php if (!empty($row->pbt_formatted)): ?>
													<span class="badge badge-success">
														<?= $row->pbt_formatted ?>
													</span>
												<?php else: ?>
													<span class="text-muted">-</span>
												<?php endif; ?>
											</td>
											<td>
												<?php if (!empty($row->bht_formatted)): ?>
													<span class="badge badge-primary">
														<?= $row->bht_formatted ?>
													</span>
												<?php else: ?>
													<span class="text-muted">Belum</span>
												<?php endif; ?>
											</td>
											<td>
												<?php if (!empty($row->ikrar_formatted)): ?>
													<span class="badge badge-warning">
														<?= $row->ikrar_formatted ?>
													</span>
												<?php else: ?>
													<span class="text-muted">-</span>
												<?php endif; ?>
											</td>
											<td>
												<?php
												$status_class = '';
												switch ($row->status_bht) {
													case 'Selesai':
														$status_class = 'badge-success';
														break;
													case 'Proses':
														$status_class = 'badge-warning';
														break;
													case 'Terlambat':
														$status_class = 'badge-danger';
														break;
													default:
														$status_class = 'badge-secondary';
												}
												?>
												<span class="badge <?= $status_class ?>">
													<?= $row->status_bht ?>
												</span>
											</td>
											<td>
												<?php
												$kategori_class = '';
												switch ($row->kategori_bht) {
													case 'Tepat Waktu':
														$kategori_class = 'badge-success';
														break;
													case 'Terlambat':
														$kategori_class = 'badge-danger';
														break;
													default:
														$kategori_class = 'badge-info';
												}
												?>
												<span class="badge <?= $kategori_class ?>">
													<?= $row->kategori_bht ?>
												</span>
											</td>
											<td class="text-center">
												<?php if (!empty($row->selisih_hari_bht)): ?>
													<?php
													$selisih_class = '';
													if ($row->selisih_hari_bht > 0) {
														$selisih_class = 'text-danger';
													} elseif ($row->selisih_hari_bht < 0) {
														$selisih_class = 'text-success';
													} else {
														$selisih_class = 'text-info';
													}
													?>
													<span class="<?= $selisih_class ?>">
														<strong><?= $row->selisih_hari_bht ?></strong> hari
													</span>
												<?php else: ?>
													<span class="text-muted">-</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="12" class="text-center">
											<div class="alert alert-info">
												<i class="fas fa-info-circle"></i>
												Tidak ada data yang ditemukan untuk kriteria pencarian ini.
											</div>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<p class="mt-2">Memproses data...</p>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Initialize DataTable with better options
		var table = $('#bhtTable').DataTable({
			"responsive": true,
			"lengthChange": true,
			"autoWidth": false,
			"searching": true,
			"ordering": false, // Disable DataTable ordering since we use server-side sorting
			"info": true,
			"paging": true,
			"pageLength": 25,
			"lengthMenu": [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, "Semua"]
			],
			"language": {
				"search": "Cari dalam tabel:",
				"lengthMenu": "Tampilkan _MENU_ data per halaman",
				"info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
				"infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
				"infoFiltered": "(difilter dari _MAX_ total data)",
				"paginate": {
					"first": "Pertama",
					"last": "Terakhir",
					"next": "Selanjutnya",
					"previous": "Sebelumnya"
				},
				"emptyTable": "Tidak ada data dalam tabel"
			},
			"dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
				'<"row"<"col-sm-12"tr>>' +
				'<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
		});

		// Reset button functionality - REMOVED (now using onclick in HTML)

		// Export button functionality
		$('#exportBtn').on('click', function() {
			var params = new URLSearchParams();

			// Get all form values
			params.append('jenis_perkara', $('#jenis_perkara').val());
			params.append('lap_bulan', $('#lap_bulan').val());
			params.append('lap_tahun', $('#lap_tahun').val());
			params.append('tanggal_awal', $('#tanggal_awal').val());
			params.append('tanggal_akhir', $('#tanggal_akhir').val());
			params.append('nomor_perkara', $('#nomor_perkara').val());
			params.append('order_by', $('#order_by').val());
			params.append('order_dir', $('#order_dir').val());

			// Open export URL
			var exportUrl = '<?= base_url('bht_putus_4/export_excel') ?>?' + params.toString();

			$('#loadingModal').modal('show');

			// Create temporary iframe for download
			var iframe = document.createElement('iframe');
			iframe.style.display = 'none';
			iframe.src = exportUrl;
			document.body.appendChild(iframe);

			// Hide loading modal after a delay
			setTimeout(function() {
				$('#loadingModal').modal('hide');
				document.body.removeChild(iframe);
			}, 3000);
		});

		// Auto-submit when sorting options change
		$('#order_by, #order_dir').on('change', function() {
			$('#loadingModal').modal('show');
			setTimeout(function() {
				$('#filterForm').submit();
			}, 500);
		});

		// Toggle date range vs month/year selection
		$('#tanggal_awal, #tanggal_akhir').on('change', function() {
			var tanggal_awal = $('#tanggal_awal').val();
			var tanggal_akhir = $('#tanggal_akhir').val();

			if (tanggal_awal && tanggal_akhir) {
				// Disable month/year selection when using date range
				$('#lap_bulan, #lap_tahun').prop('disabled', true).addClass('bg-light');
			} else {
				// Enable month/year selection when not using date range
				$('#lap_bulan, #lap_tahun').prop('disabled', false).removeClass('bg-light');
			}
		});

		// Initialize date range toggle on page load
		$('#tanggal_awal').trigger('change');

		// Add tooltips for better UX
		$('[data-toggle="tooltip"]').tooltip();

		// Quick sort buttons (additional feature)
		function addQuickSortButtons() {
			var quickSortHtml = `
            <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Quick Sort">
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="tanggal_putus" data-dir="DESC">
                    <i class="fas fa-calendar-alt"></i> Terbaru
                </button>
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="tanggal_putus" data-dir="ASC">
                    <i class="fas fa-calendar-alt"></i> Terlama
                </button>
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="status_bht" data-dir="ASC">
                    <i class="fas fa-exclamation-triangle"></i> Status
                </button>
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="nomor_perkara" data-dir="ASC">
                    <i class="fas fa-sort-numeric-up"></i> Nomor
                </button>
            </div>
        `;

			$('.table-responsive').before(quickSortHtml);

			// Quick sort functionality
			$('.quick-sort').on('click', function() {
				$('#order_by').val($(this).data('sort'));
				$('#order_dir').val($(this).data('dir'));

				// Visual feedback
				$('.quick-sort').removeClass('btn-primary').addClass('btn-outline-primary');
				$(this).removeClass('btn-outline-primary').addClass('btn-primary');

				$('#loadingModal').modal('show');
				setTimeout(function() {
					$('#filterForm').submit();
				}, 500);
			});

			// Highlight active quick sort button
			var currentSort = $('#order_by').val();
			var currentDir = $('#order_dir').val();
			$('.quick-sort').each(function() {
				if ($(this).data('sort') === currentSort && $(this).data('dir') === currentDir) {
					$(this).removeClass('btn-outline-primary').addClass('btn-primary');
				}
			});
		}

		// Add quick sort buttons
		addQuickSortButtons();

		// Show success message if any
		<?php if ($this->session->flashdata('success')): ?>
			toastr.success('<?= $this->session->flashdata('success') ?>');
		<?php endif; ?>

		<?php if ($this->session->flashdata('error')): ?>
			toastr.error('<?= $this->session->flashdata('error') ?>');
		<?php endif; ?>
	});

	// ✅ BHT PUTUS 4: Enhanced Reset Function with Cache Clearing
	function resetFormWithCache() {
		console.log('🔄 BHT Putus 4: Resetting form and clearing browser cache...');

		// Enhanced confirmation with cache clearing info
		if (confirm('Apakah Anda yakin ingin mereset form dan menghapus cache browser?\n\nIni akan:\n• Reset semua form ke nilai default\n• Hapus autocomplete history\n• Clear local storage\n• Force refresh halaman (opsional)')) {

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

			console.log('✅ BHT Putus 4: Form reset and cache clearing completed');
		}
	}

	// ✅ STEP 1 FUNCTION: Clear Form Data Cache (BHT Putus 4 specific)
	function clearFormDataCache() {
		try {
			// Clear all form inputs specific to BHT Putus 4
			$('#nomor_perkara').val('');
			$('#tanggal_awal').val('');
			$('#tanggal_akhir').val('');

			// Clear all select elements first (before setting defaults)
			$('#jenis_perkara').val('');
			$('#lap_bulan').val('');
			$('#lap_tahun').val('');
			$('#order_by').val('');
			$('#order_dir').val('');

			// Clear any hidden inputs that might store cache
			$('input[type="hidden"]').val('');

			// Remove any validation error classes
			$('.is-invalid').removeClass('is-invalid');

			console.log('✅ BHT Putus 4: Form data cache cleared');
		} catch (e) {
			console.error('❌ Error clearing form data cache:', e);
		}
	}

	// ✅ STEP 2 FUNCTION: Clear Browser Storage Cache
	function clearBrowserStorageCache() {
		try {
			// Clear localStorage for this domain
			if (typeof(Storage) !== "undefined") {
				// Get all localStorage keys related to BHT Putus 4
				var keysToRemove = [];
				for (var i = 0; i < localStorage.length; i++) {
					var key = localStorage.key(i);
					// Remove keys related to bht_putus_4, form, search, or filter
					if (key && (key.includes('bht_putus_4') || key.includes('bht') || key.includes('form') ||
							key.includes('search') || key.includes('filter') || key.includes('sorting'))) {
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
						if (key && (key.includes('bht_putus_4') || key.includes('bht') || key.includes('form') ||
								key.includes('search') || key.includes('filter') || key.includes('sorting'))) {
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

	// ✅ STEP 4 FUNCTION: Reset to Default Values (BHT Putus 4 specific)
	function resetToDefaultValues() {
		try {
			// Set default values (after clearing cache) - BHT Putus 4 specific
			$('#jenis_perkara').val('Pdt.G');
			$('#lap_bulan').val('<?= date('m') ?>');
			$('#lap_tahun').val('<?= date('Y') ?>');
			$('#order_by').val('tanggal_putus');
			$('#order_dir').val('DESC');

			// Clear date range inputs
			$('#tanggal_awal').val('');
			$('#tanggal_akhir').val('');
			$('#nomor_perkara').val('');

			// Trigger change events to update any dependent elements
			$('select').trigger('change');

			console.log('✅ Default values restored for BHT Putus 4');
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

	// ✅ ADDITIONAL FUNCTION: Hard Reset with Immediate Page Refresh
	function hardResetForm() {
		console.log('💥 BHT Putus 4: Hard reset initiated...');

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

			// Count form inputs with values (BHT Putus 4 specific)
			$('input, select, textarea').each(function() {
				if ($(this).val() && $(this).val() !== '') {
					cacheInfo.formData++;
				}
			});

			var infoMessage =
				'📊 BHT PUTUS 4 - INFORMASI CACHE BROWSER:\n\n' +
				'🗄️ Local Storage: ' + cacheInfo.localStorage + ' items\n' +
				'📝 Session Storage: ' + cacheInfo.sessionStorage + ' items\n' +
				'🍪 Cookies: ' + cacheInfo.cookies + ' items\n' +
				'📋 Form Data: ' + cacheInfo.formData + ' fields\n\n' +
				'💡 Gunakan "Reset + Clear Cache" untuk membersihkan cache\n' +
				'💥 Gunakan "Hard Reset" untuk pembersihan menyeluruh + refresh halaman';

			alert(infoMessage);

			// Log details to console for debugging
			console.log('📊 BHT Putus 4 Cache Information Details:');
			console.log('localStorage items:', cacheInfo.localStorage);
			console.log('sessionStorage items:', cacheInfo.sessionStorage);
			console.log('cookies:', document.cookie);
			console.log('form data count:', cacheInfo.formData);

		} catch (e) {
			console.error('❌ Error getting cache info:', e);
			alert('❌ Tidak dapat mengambil informasi cache: ' + e.message);
		}
	}

	// ✅ UTILITY FUNCTION: Show Alert Messages
	function showAlert(type, title, message) {
		var alertClass = 'alert-' + type;
		var iconClass = {
			'success': 'fas fa-check-circle',
			'info': 'fas fa-info-circle',
			'warning': 'fas fa-exclamation-triangle',
			'danger': 'fas fa-times-circle'
		};

		var alert = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 450px;">' +
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

	// ✅ ADDITIONAL FUNCTION: Auto Cache Monitoring (BHT Putus 4)
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
									console.log('🧹 BHT Putus 4: Auto-cleared old cache:', key);
								}
							} catch (e) {
								// If parsing fails, remove the item
								localStorage.removeItem(key);
							}
						}
					}
				}
			} catch (e) {
				console.log('BHT Putus 4 cache monitoring error:', e);
			}
		}, 30000); // Check every 30 seconds
	}

	// Start cache monitoring when document is ready
	$(document).ready(function() {
		startCacheMonitoring();

		// Store page load timestamp for cache monitoring
		if (typeof(Storage) !== "undefined") {
			localStorage.setItem('bht_putus_4_page_load_timestamp_' + Date.now(), Date.now().toString());
		}

		console.log('✅ BHT Putus 4: Cache monitoring system started');
	});
</script>

<style>
	.small-box .inner h3 {
		font-size: 2.2rem;
		font-weight: bold;
	}

	.badge {
		font-size: 0.75rem;
	}

	.table th {
		background-color: #343a40;
		color: white;
		font-weight: 600;
		font-size: 0.9rem;
	}

	.table-hover tbody tr:hover {
		background-color: rgba(0, 123, 255, .075);
	}

	.card-header {
		background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
		color: white;
	}

	.quick-sort {
		margin-right: 5px;
	}

	.bg-light {
		background-color: #f8f9fa !important;
	}

	/* Loading animation */
	.spinner-border {
		width: 3rem;
		height: 3rem;
	}

	/* Responsive improvements */
	@media (max-width: 768px) {
		.small-box .inner h3 {
			font-size: 1.8rem;
		}

		.btn-group-sm>.btn {
			padding: 0.25rem 0.5rem;
			font-size: 0.775rem;
		}
	}

	/* Print styles */
	@media print {

		.btn,
		.card-tools,
		.quick-sort {
			display: none !important;
		}

		.table {
			font-size: 0.8rem;
		}
	}
</style>