<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Perkara Reminder - Sistem Reminder BHT</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">

    <style>
        .priority-badge {
            font-size: 0.9em;
            padding: 0.4rem 0.8rem;
        }

        .priority-CRITICAL {
            background-color: #dc3545 !important;
        }

        .priority-KRITIS {
            background-color: #fd7e14 !important;
        }

        .priority-PERINGATAN {
            background-color: #ffc107 !important;
            color: #000;
        }

        .priority-NORMAL {
            background-color: #28a745 !important;
        }

        .status-badge {
            font-size: 0.9em;
            padding: 0.4rem 0.8rem;
        }

        .status-BELUM_PBT {
            background-color: #dc3545 !important;
        }

        .status-SUDAH_PBT_BELUM_BHT {
            background-color: #ffc107 !important;
            color: #000;
        }

        .status-SELESAI {
            background-color: #28a745 !important;
        }

        .info-card {
            border-left: 4px solid #007bff;
        }

        .timeline-item {
            border-left: 3px solid #007bff;
            padding-left: 1rem;
            margin-bottom: 1rem;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: -6px;
            width: 12px;
            height: 12px;
            background: #007bff;
            border-radius: 50%;
        }

        .timeline-item {
            position: relative;
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
                            <h1 class="m-0">📋 Detail Perkara Reminder</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('index.php/reminder_logging') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('index.php/reminder_logging/perkara_list') ?>">Daftar Perkara</a></li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <?php if (isset($perkara) && $perkara): ?>

                        <!-- Header Card -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-gavel"></i> <?= htmlspecialchars($perkara->nomor_perkara) ?>
                                </h3>
                                <div class="card-tools">
                                    <span class="badge priority-badge priority-<?= $perkara->level_prioritas ?>">
                                        <?= $perkara->level_prioritas ?>
                                    </span>
                                    <span class="badge status-badge status-<?= $perkara->status_reminder ?>">
                                        <?= str_replace('_', ' ', $perkara->status_reminder) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-balance-scale"></i> Jenis Perkara:</strong>
                                        <p class="text-muted"><?= htmlspecialchars($perkara->jenis_perkara ?: '-') ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-calendar-plus"></i> Tanggal Registrasi:</strong>
                                        <p class="text-muted"><?= $perkara->tanggal_registrasi ? date('d/m/Y', strtotime($perkara->tanggal_registrasi)) : '-' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Detail Info -->
                            <div class="col-md-8">
                                <!-- Informasi Putusan -->
                                <div class="card info-card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-gavel"></i> Informasi Putusan</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Tanggal Putusan:</strong>
                                                <p><?= $perkara->tanggal_putusan ? date('d/m/Y', strtotime($perkara->tanggal_putusan)) : '-' ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Hari Sejak Putusan:</strong>
                                                <p>
                                                    <span class="badge badge-info badge-lg">
                                                        <?= $perkara->hari_sejak_putusan ?> hari
                                                    </span>
                                                </p>
                                            </div>
                                        </div>

                                        <?php if (isset($perkara->majelis_hakim) && $perkara->majelis_hakim): ?>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <strong>Majelis Hakim:</strong>
                                                    <p><?= nl2br(htmlspecialchars(str_replace('</br>', "\n", $perkara->majelis_hakim))) ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Informasi Jurusita -->
                                <?php if (isset($perkara->jurusita_1) || isset($perkara->jurusita_2)): ?>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-user-tie"></i> Jurusita</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <?php if (isset($perkara->jurusita_1) && $perkara->jurusita_1): ?>
                                                    <div class="col-md-6">
                                                        <strong>Jurusita 1:</strong>
                                                        <p><?= htmlspecialchars($perkara->jurusita_1) ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (isset($perkara->jurusita_2) && $perkara->jurusita_2): ?>
                                                    <div class="col-md-6">
                                                        <strong>Jurusita 2:</strong>
                                                        <p><?= htmlspecialchars($perkara->jurusita_2) ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Catatan Reminder -->
                                <?php if (isset($perkara->catatan_reminder) && $perkara->catatan_reminder): ?>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-sticky-note"></i> Catatan Reminder</h3>
                                        </div>
                                        <div class="card-body">
                                            <p><?= nl2br(htmlspecialchars($perkara->catatan_reminder)) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Sidebar Info -->
                            <div class="col-md-4">
                                <!-- Quick Stats -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Quick Info</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Status Reminder</span>
                                            <span class="info-box-number">
                                                <span class="badge status-badge status-<?= $perkara->status_reminder ?>">
                                                    <?= str_replace('_', ' ', $perkara->status_reminder) ?>
                                                </span>
                                            </span>
                                        </div>
                                        <hr>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Level Prioritas</span>
                                            <span class="info-box-number">
                                                <span class="badge priority-badge priority-<?= $perkara->level_prioritas ?>">
                                                    <?= $perkara->level_prioritas ?>
                                                </span>
                                            </span>
                                        </div>
                                        <hr>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Last Sync SIPP</span>
                                            <span class="info-box-number small">
                                                <?= $perkara->last_sync_sipp ? date('d/m/Y H:i', strtotime($perkara->last_sync_sipp)) : 'Belum sync' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-tools"></i> Aksi</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="btn-group-vertical w-100">
                                            <a href="<?= base_url('index.php/reminder_logging/perkara_edit/' . $perkara->id) ?>"
                                                class="btn btn-warning btn-sm mb-2">
                                                <i class="fas fa-edit"></i> Edit Perkara
                                            </a>
                                            <button type="button" class="btn btn-info btn-sm mb-2" onclick="updateFromSipp('<?= htmlspecialchars($perkara->nomor_perkara) ?>')">
                                                <i class="fas fa-sync"></i> Update dari SIPP
                                            </button>
                                            <a href="<?= base_url('index.php/reminder_logging/perkara_list') ?>"
                                                class="btn btn-secondary btn-sm">
                                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Timeline -->
                        <?php if (isset($activities) && !empty($activities)): ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Aktivitas</h3>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($activities as $activity): ?>
                                        <div class="timeline-item">
                                            <strong><?= htmlspecialchars($activity->activity_type) ?></strong>
                                            <small class="text-muted float-right">
                                                <?= date('d/m/Y H:i', strtotime($activity->created_at)) ?>
                                            </small>
                                            <p class="mb-1"><?= htmlspecialchars($activity->description) ?></p>
                                            <?php if ($activity->old_value || $activity->new_value): ?>
                                                <small class="text-muted">
                                                    <?php if ($activity->old_value): ?>
                                                        Dari: <?= htmlspecialchars($activity->old_value) ?>
                                                    <?php endif; ?>
                                                    <?php if ($activity->new_value): ?>
                                                        → Ke: <?= htmlspecialchars($activity->new_value) ?>
                                                    <?php endif; ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>

                        <!-- Perkara Not Found -->
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h4>Perkara Tidak Ditemukan</h4>
                                <p class="text-muted">Perkara yang Anda cari tidak ditemukan dalam sistem reminder.</p>
                                <a href="<?= base_url('index.php/reminder_logging/perkara_list') ?>" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Perkara
                                </a>
                            </div>
                        </div>

                    <?php endif; ?>

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
        function updateFromSipp(nomorPerkara) {
            if (confirm('Update data perkara dari SIPP?')) {
                $.post('<?= base_url("index.php/reminder_logging/update_from_sipp") ?>', {
                    nomor_perkara: nomorPerkara
                }, function(response) {
                    if (response.success) {
                        alert('Update berhasil!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }).fail(function() {
                    alert('Terjadi kesalahan saat update data.');
                });
            }
        }
    </script>
</body>

</html>