<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-green elevation-4">
	<!-- Brand Logo -->
	<a href="<?php echo site_url('home') ?>" class="brand-link navbar-green">
		<img src="<?php echo base_url() ?>assets/dist/img/logo-mahkamah-agung.png" alt="Logo PA Amuntai" class="brand-image img-circle elevation-2" style="opacity: .8">
		<span class="brand-text font-weight-light">Monitoring BHT</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?php echo base_url() ?>assets/dist/img/Logo PA Amuntai - Trans.png" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block">Halimah, S.H</a>
				<span class="badge badge-success">Online</span>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Dashboard -->
				<li class="nav-item">
					<a href="<?= site_url('home') ?>" class="nav-link <?= $this->uri->segment(1) == '' || $this->uri->segment(1) == 'home' ? 'active' : '' ?>">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>



				<li class="nav-header">MANAJEMEN BHT</li>

				<!-- Menu Baru - Monitoring Harian -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-calendar-day text-success"></i>
						<p>
							Monitoring Harian
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Menu_baru/perkara_putus_harian') ?>" class="nav-link <?= $this->uri->segment(2) == 'perkara_putus_harian' ? 'active' : '' ?>">
								<i class="fas fa-gavel nav-icon text-info"></i>
								<p>Perkara Putus Tiap Hari</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Menu_baru/jadwal_bht_harian') ?>" class="nav-link <?= $this->uri->segment(2) == 'jadwal_bht_harian' ? 'active' : '' ?>">
								<i class="fas fa-clock nav-icon text-warning"></i>
								<p>Jadwal BHT Per Hari</p>
								<span class="badge badge-warning right" id="jadwal-alert">0</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Menu_baru/perkara_putus_tanpa_pbt') ?>" class="nav-link <?= $this->uri->segment(2) == 'perkara_putus_tanpa_pbt' ? 'active' : '' ?>">
								<i class="fas fa-exclamation-triangle nav-icon text-danger"></i>
								<p>Perkara Putus Tanpa PBT</p>
								<span class="badge badge-danger right" id="tanpa-pbt-alert">!</span>
							</a>
						</li>
					</ul>
				</li>

				<!-- Menu Baru - Manajemen Berkas -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-folder-open text-primary"></i>
						<p>
							Manajemen Berkas
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Menu_baru/berkas_masuk') ?>" class="nav-link <?= $this->uri->segment(2) == 'berkas_masuk' ? 'active' : '' ?>">
								<i class="fas fa-inbox nav-icon text-success"></i>
								<p>Berkas Masuk</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Menu_baru/pbt_masuk') ?>" class="nav-link <?= $this->uri->segment(2) == 'pbt_masuk' ? 'active' : '' ?>">
								<i class="fas fa-file-import nav-icon text-info"></i>
								<p>PBT Masuk</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Menu_baru/berkas_menu_bht') ?>" class="nav-link <?= $this->uri->segment(2) == 'berkas_menu_bht' ? 'active' : '' ?>">
								<i class="fas fa-archive nav-icon text-secondary"></i>
								<p>Berkas Menu BHT</p>
							</a>
						</li>
					</ul>
				</li>

				<!-- Notelen System -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-file-archive text-warning"></i>
						<p>
							Sistem Notelen
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('notelen/berkas_template') ?>" class="nav-link <?= $this->uri->segment(1) == 'notelen' && $this->uri->segment(2) == 'berkas_template' ? 'active' : '' ?>">
								<i class="fas fa-folder-open nav-icon text-success"></i>
								<p>Berkas Masuk</p>
								<span class="badge badge-success right">NEW</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('notelen/berkas_pbt') ?>" class="nav-link <?= $this->uri->segment(1) == 'notelen' && $this->uri->segment(2) == 'berkas_pbt' ? 'active' : '' ?>">
								<i class="fas fa-gavel nav-icon text-info"></i>
								<p>Berkas PBT</p>
								<span class="badge badge-info right">PBT</span>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-header">PENGINGAT & NOTIFIKASI</li>

				<!-- Testing & Development (Show only in development) -->
				<!-- <?php if (ENVIRONMENT === 'development'): ?>
					<li class="nav-header">TESTING & DEBUG</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-bug text-warning"></i>
							<p>
								Development Tools
								<i class="fas fa-angle-left right"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="<?php echo site_url('test/bht') ?>" class="nav-link" target="_blank">
									<i class="fas fa-vial nav-icon"></i>
									<p>Test BHT System</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('test/bht/template') ?>" class="nav-link" target="_blank">
									<i class="fas fa-code nav-icon"></i>
									<p>Test Template System</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo base_url('README_BHT_SYSTEM.md') ?>" class="nav-link" target="_blank">
									<i class="fas fa-book nav-icon text-primary"></i>
									<p>Dokumentasi System</p>
								</a>
							</li>
						</ul>
					</li>
				<?php endif; ?> -->

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>

<!-- JavaScript untuk update badge counter pengingat -->
<script>
	$(document).ready(function() {
		// Update reminder counter saat halaman dimuat
		updateReminderCounter();
		updateMenuBaruNotifications();

		// Update setiap 2 menit
		setInterval(updateReminderCounter, 2 * 60 * 1000);
		setInterval(updateMenuBaruNotifications, 2 * 60 * 1000);
	});

	function updateReminderCounter() {
		$.ajax({
			url: '<?= base_url("api/bht/reminders") ?>',
			method: 'GET',
			data: {
				status: 'URGENT'
			}, // Hanya hitung yang urgent
			dataType: 'json',
			success: function(response) {
				if (response.success && response.count !== undefined) {
					const badge = $('#reminder-count');
					const count = parseInt(response.count);

					if (count > 0) {
						badge.text(count);
						badge.removeClass('badge-secondary').addClass('badge-danger');

						// Animate badge jika ada urgent reminders
						badge.addClass('badge-pulse');
					} else {
						badge.text('0');
						badge.removeClass('badge-danger badge-pulse').addClass('badge-secondary');
					}
				}
			},
			error: function() {
				// Jika error, set badge ke tanda tanya
				$('#reminder-count').text('?').removeClass('badge-danger').addClass('badge-warning');
			}
		});
	}

	function updateMenuBaruNotifications() {
		$.ajax({
			url: '<?= base_url("Menu_baru/api_notifikasi") ?>',
			method: 'GET',
			dataType: 'json',
			success: function(response) {
				if (response.success && response.data) {
					const data = response.data;

					// Update jadwal BHT alert
					const jadwalBadge = $('#jadwal-alert');
					if (data.jadwal_bht_urgent > 0) {
						jadwalBadge.text(data.jadwal_bht_urgent);
						jadwalBadge.removeClass('badge-secondary').addClass('badge-warning badge-pulse');
					} else {
						jadwalBadge.text('0');
						jadwalBadge.removeClass('badge-warning badge-pulse').addClass('badge-secondary');
					}

					// Update perkara tanpa PBT alert
					const tanpaPbtBadge = $('#tanpa-pbt-alert');
					if (data.perkara_putus_tanpa_pbt > 0) {
						tanpaPbtBadge.text(data.perkara_putus_tanpa_pbt);
						tanpaPbtBadge.removeClass('badge-secondary').addClass('badge-danger badge-pulse');
					} else {
						tanpaPbtBadge.text('0');
						tanpaPbtBadge.removeClass('badge-danger badge-pulse').addClass('badge-secondary');
					}
				}
			},
			error: function() {
				// Jika error, set badge ke tanda tanya
				$('#jadwal-alert').text('?').removeClass('badge-warning').addClass('badge-secondary');
				$('#tanpa-pbt-alert').text('?').removeClass('badge-danger').addClass('badge-secondary');
			}
		});
	}
</script>

<!-- CSS untuk animasi badge -->
<style>
	.badge-pulse {
		animation: pulse-badge 2s infinite;
	}

	@keyframes pulse-badge {
		0% {
			transform: scale(1);
			opacity: 1;
		}

		50% {
			transform: scale(1.1);
			opacity: 0.7;
		}

		100% {
			transform: scale(1);
			opacity: 1;
		}
	}

	/* Styling khusus untuk menu BHT */
	.nav-sidebar .nav-item>.nav-link.active .nav-icon {
		color: #ffc107 !important;
	}

	/* Highlight untuk menu pengingat */
	.nav-sidebar .nav-item>.nav-link:hover .text-warning {
		color: #fff !important;
	}
</style>