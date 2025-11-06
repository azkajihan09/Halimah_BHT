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

    <!-- Custom CSS for Reminder System -->
    <style>
        .reminder-card {
            transition: transform 0.2s;
        }

        .reminder-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .urgent-alert {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .sync-status {
            font-size: 0.8em;
        }

        .priority-critical {
            border-left: 4px solid #dc3545;
        }

        .priority-kritis {
            border-left: 4px solid #fd7e14;
        }

        .priority-peringatan {
            border-left: 4px solid #ffc107;
        }

        .priority-normal {
            border-left: 4px solid #28a745;
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
                        <i class="fas fa-database"></i>
                        <strong>Database Reminder:</strong> bht_reminder_system
                    </span>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="<?= base_url('reminder_logging') ?>" class="brand-link">
                <i class="fas fa-bell brand-image"></i>
                <span class="brand-text font-weight-light">Reminder BHT</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="<?= base_url('reminder_logging') ?>" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('reminder_logging/perkara_list') ?>" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Daftar Perkara</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-sync"></i>
                                <p>Sinkronisasi</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" onclick="syncManual()">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sync Manual</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" onclick="updateFromSipp()">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Update dari SIPP</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('reminder_logging/config') ?>" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
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
                            <h1 class="m-0"><?= $title ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('reminder_logging') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Sistem Reminder</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Sync Status Alert -->
                    <div class="row">
                        <div class="col-12">
                            <?php if ($last_sync): ?>
                                <div class="alert alert-info alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <i class="icon fas fa-info"></i>
                                    <strong>Sinkronisasi Terakhir:</strong>
                                    <?= date('d/m/Y H:i:s', strtotime($last_sync)) ?>
                                    <?php if ($auto_sync_enabled): ?>
                                        | Auto-sync: <span class="badge badge-success">AKTIF</span> (<?= $sync_interval ?> menit)
                                    <?php else: ?>
                                        | Auto-sync: <span class="badge badge-warning">NONAKTIF</span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <i class="icon fas fa-exclamation-triangle"></i>
                                    <strong>Belum ada sinkronisasi!</strong>
                                    Silakan lakukan sinkronisasi manual untuk mengisi data.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row">
                        <!-- Total Perkara -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info reminder-card">
                                <div class="inner">
                                    <h3><?= $stats->total_perkara ?></h3>
                                    <p>Total Perkara Reminder</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Belum PBT -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning reminder-card">
                                <div class="inner">
                                    <h3><?= $stats->total_belum_pbt ?></h3>
                                    <p>Belum PBT</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Sudah PBT Belum BHT -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary reminder-card">
                                <div class="inner">
                                    <h3><?= $stats->total_sudah_pbt_belum_bht ?></h3>
                                    <p>Sudah PBT, Belum BHT</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success reminder-card">
                                <div class="inner">
                                    <h3><?= $stats->total_selesai ?></h3>
                                    <p>Selesai BHT</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Priority Level Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger reminder-card urgent-alert">
                                <div class="inner">
                                    <h3><?= $stats->total_critical ?></h3>
                                    <p>CRITICAL (>21 hari)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-fire"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-orange reminder-card">
                                <div class="inner">
                                    <h3><?= $stats->total_kritis ?></h3>
                                    <p>KRITIS (15-21 hari)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning reminder-card">
                                <div class="inner">
                                    <h3><?= $stats->total_peringatan ?></h3>
                                    <p>PERINGATAN (11-14 hari)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success reminder-card">
                                <div class="inner">
                                    <h3><?= $stats->total_normal ?></h3>
                                    <p>NORMAL (0-10 hari)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-smile"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Urgent Alerts -->
                    <?php if (!empty($urgent_alerts)): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Peringatan Urgent - Perlu Tindakan Segera!
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nomor Perkara</th>
                                                        <th>Jenis Perkara</th>
                                                        <th>Tanggal Putusan</th>
                                                        <th>Hari Tertunda</th>
                                                        <th>Level Urgency</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($urgent_alerts as $alert): ?>
                                                        <tr class="priority-<?= strtolower($alert->level_urgency) ?>">
                                                            <td>
                                                                <strong><?= $alert->nomor_perkara ?></strong>
                                                            </td>
                                                            <td><?= $alert->jenis_perkara ?></td>
                                                            <td><?= date('d/m/Y', strtotime($alert->tanggal_putusan)) ?></td>
                                                            <td>
                                                                <span class="badge badge-danger"><?= $alert->hari_sejak_putusan ?> hari</span>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $badge_class = '';
                                                                switch ($alert->level_urgency) {
                                                                    case 'CRITICAL':
                                                                        $badge_class = 'badge-danger';
                                                                        break;
                                                                    case 'KRITIS':
                                                                        $badge_class = 'badge-warning';
                                                                        break;
                                                                    case 'PERINGATAN':
                                                                        $badge_class = 'badge-info';
                                                                        break;
                                                                    default:
                                                                        $badge_class = 'badge-success';
                                                                        break;
                                                                }
                                                                ?>
                                                                <span class="badge <?= $badge_class ?>"><?= $alert->level_urgency ?></span>
                                                            </td>
                                                            <td><?= $alert->status_reminder ?></td>
                                                            <td>
                                                                <a href="<?= base_url('reminder_logging/perkara_detail/' . urlencode($alert->nomor_perkara)) ?>"
                                                                    class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-eye"></i> Detail
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-3">
                                            <a href="<?= base_url('reminder_logging/perkara_list?prioritas=CRITICAL') ?>"
                                                class="btn btn-danger">
                                                <i class="fas fa-list"></i> Lihat Semua Perkara Critical
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-sync"></i>
                                        Sinkronisasi Data
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p>Sinkronisasi data perkara dari database SIPP ke sistem reminder.</p>
                                    <button type="button" class="btn btn-primary" onclick="syncManual()">
                                        <i class="fas fa-sync"></i> Sync Manual
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="updateFromSipp()">
                                        <i class="fas fa-refresh"></i> Update dari SIPP
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-download"></i>
                                        Export Data
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p>Export data reminder untuk reporting dan analisis.</p>
                                    <a href="<?= base_url('reminder_logging/export_excel') ?>" class="btn btn-success">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Sistem Reminder BHT &copy; 2025</strong>
            Database: bht_reminder_system | Dual Database System
        </footer>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>

    <script>
        // Sync Manual Function
        function syncManual() {
            if (confirm('Lakukan sinkronisasi manual dari database SIPP?')) {
                $.ajax({
                    url: '<?= base_url("reminder_logging/sync_manual") ?>',
                    type: 'POST',
                    data: {
                        limit: 100
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('body').append('<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>');
                    },
                    success: function(response) {
                        $('.overlay').remove();
                        if (response.success) {
                            alert('Sinkronisasi berhasil: ' + response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        $('.overlay').remove();
                        alert('Terjadi kesalahan saat sinkronisasi');
                    }
                });
            }
        }

        // Update from SIPP Function
        function updateFromSipp() {
            if (confirm('Update data existing dari database SIPP?')) {
                $.ajax({
                    url: '<?= base_url("reminder_logging/update_from_sipp") ?>',
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        $('body').append('<div class="overlay"><i class="fas fa-2x fa-refresh fa-spin"></i></div>');
                    },
                    success: function(response) {
                        $('.overlay').remove();
                        if (response.success) {
                            alert('Update berhasil: ' + response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        $('.overlay').remove();
                        alert('Terjadi kesalahan saat update');
                    }
                });
            }
        }

        // Auto refresh urgent alerts every 5 minutes
        setInterval(function() {
            location.reload();
        }, 5 * 60 * 1000);

        // Show loading overlay
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Animate statistics cards
            $('.reminder-card').each(function(index) {
                $(this).delay(index * 100).fadeIn();
            });
        });
    </script>

</body>

</html>