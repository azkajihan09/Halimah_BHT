<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Perkara Reminder - Sistem Reminder BHT</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

    <style>
        .priority-badge {
            font-size: 0.8em;
            padding: 0.25rem 0.5rem;
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
            font-size: 0.8em;
            padding: 0.25rem 0.5rem;
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

        .table-responsive {
            font-size: 0.9em;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .filters-section {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Content Wrapper -->
        <div class="content-wrapper" style="margin-left: 0;">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">📋 Daftar Perkara Reminder BHT</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('reminder_logging') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Daftar Perkara</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Summary Cards -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?= isset($summary->total_critical) ? $summary->total_critical : 0 ?></h3>
                                    <p>Critical</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= isset($summary->total_kritis) ? $summary->total_kritis : 0 ?></h3>
                                    <p>Kritis</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= isset($summary->total_peringatan) ? $summary->total_peringatan : 0 ?></h3>
                                    <p>Peringatan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= isset($summary->total_normal) ? $summary->total_normal : 0 ?></h3>
                                    <p>Normal</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">🔍 Filter Perkara</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="<?= base_url('reminder_logging/perkara_list') ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status Reminder:</label>
                                            <select name="status_reminder" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="BELUM_PBT" <?= $this->input->get('status_reminder') == 'BELUM_PBT' ? 'selected' : '' ?>>Belum PBT</option>
                                                <option value="SUDAH_PBT_BELUM_BHT" <?= $this->input->get('status_reminder') == 'SUDAH_PBT_BELUM_BHT' ? 'selected' : '' ?>>Sudah PBT Belum BHT</option>
                                                <option value="SELESAI" <?= $this->input->get('status_reminder') == 'SELESAI' ? 'selected' : '' ?>>Selesai</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Level Prioritas:</label>
                                            <select name="level_prioritas" class="form-control">
                                                <option value="">Semua Level</option>
                                                <option value="CRITICAL" <?= $this->input->get('level_prioritas') == 'CRITICAL' ? 'selected' : '' ?>>Critical</option>
                                                <option value="KRITIS" <?= $this->input->get('level_prioritas') == 'KRITIS' ? 'selected' : '' ?>>Kritis</option>
                                                <option value="PERINGATAN" <?= $this->input->get('level_prioritas') == 'PERINGATAN' ? 'selected' : '' ?>>Peringatan</option>
                                                <option value="NORMAL" <?= $this->input->get('level_prioritas') == 'NORMAL' ? 'selected' : '' ?>>Normal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jenis Perkara:</label>
                                            <input type="text" name="jenis_perkara" class="form-control"
                                                placeholder="Cari jenis perkara..."
                                                value="<?= $this->input->get('jenis_perkara') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Filter
                                                </button>
                                                <a href="<?= base_url('reminder_logging/perkara_list') ?>" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> Reset
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📊 Data Perkara (<?= isset($total_records) ? $total_records : 0 ?> records)</h3>
                            <div class="card-tools">
                                <a href="<?= base_url('reminder_logging/export_excel') ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                                <a href="<?= base_url('reminder_logging/sync_manual') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sync"></i> Sync Manual
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Perkara</th>
                                            <th>Jenis Perkara</th>
                                            <th>Tanggal Putusan</th>
                                            <th>Hari Sejak Putusan</th>
                                            <th>Status</th>
                                            <th>Prioritas</th>
                                            <th>Level Urgency</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($perkara_list) && is_array($perkara_list)): ?>
                                            <?php $no = 1;
                                            foreach ($perkara_list as $perkara): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <a href="<?= base_url('reminder_logging/perkara_detail/' . urlencode($perkara->nomor_perkara)) ?>"
                                                            class="text-primary">
                                                            <?= htmlspecialchars($perkara->nomor_perkara) ?>
                                                        </a>
                                                    </td>
                                                    <td><?= htmlspecialchars($perkara->jenis_perkara ?: '-') ?></td>
                                                    <td><?= $perkara->tanggal_putusan ? date('d/m/Y', strtotime($perkara->tanggal_putusan)) : '-' ?></td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            <?= $perkara->hari_sejak_putusan ?> hari
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge status-badge status-<?= $perkara->status_reminder ?>">
                                                            <?= str_replace('_', ' ', $perkara->status_reminder) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge priority-badge priority-<?= $perkara->level_prioritas ?>">
                                                            <?= $perkara->level_prioritas ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if (isset($perkara->level_urgency)): ?>
                                                            <span class="badge priority-badge priority-<?= $perkara->level_urgency ?>">
                                                                <?= $perkara->level_urgency ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge priority-badge priority-<?= $perkara->level_prioritas ?>">
                                                                <?= $perkara->level_prioritas ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="<?= base_url('reminder_logging/perkara_detail/' . urlencode($perkara->nomor_perkara)) ?>"
                                                                class="btn btn-info btn-sm" title="Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="<?= base_url('reminder_logging/perkara_edit/' . $perkara->id) ?>"
                                                                class="btn btn-warning btn-sm" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle"></i>
                                                        Tidak ada data perkara reminder.
                                                        <a href="<?= base_url('reminder_logging/sync_manual') ?>" class="btn btn-primary btn-sm ml-2">
                                                            <i class="fas fa-sync"></i> Sync Sekarang
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if (isset($pagination_links)): ?>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <?= $pagination_links ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>

    <!-- DataTables Scripts -->
    <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[title]').tooltip();

            // Auto-refresh every 5 minutes
            setTimeout(function() {
                location.reload();
            }, 300000); // 5 minutes
        });
    </script>
</body>

</html>