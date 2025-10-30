<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-bell text-warning"></i> <?= $title ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Alert for urgent reminders -->
            <?php if (!empty($urgent_reminders)): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan Urgent!</h5>
                    <ul class="mb-0">
                        <?php foreach ($urgent_reminders as $urgent): ?>
                            <li><?= $urgent['message'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Filter Row -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filter Reminder
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-3">
                                    <label for="tanggal" class="mr-2">Tanggal:</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggal ?>">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="periode" class="mr-2">Periode:</label>
                                    <input type="month" class="form-control" id="periode" name="periode" value="<?= $periode ?>">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="jenis" class="mr-2">Jenis Perkara:</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="semua" <?= $jenis == 'semua' ? 'selected' : '' ?>>Semua Jenis</option>
                                        <?php if (!empty($kategori_jenis)): ?>
                                            <?php foreach ($kategori_jenis as $kategori): ?>
                                                <option value="<?= $kategori->jenis_perkara_nama ?>" <?= $jenis == $kategori->jenis_perkara_nama ? 'selected' : '' ?>>
                                                    <?= $kategori->jenis_perkara_nama ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <button type="button" class="btn btn-info ml-2" onclick="refreshData()">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Row -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= count($urgent_reminders) ?></h3>
                            <p>Reminder Urgent</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= count($jadwal_bht_hari_ini) ?></h3>
                            <p>Jadwal BHT Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= count($perkara_tanpa_pbt) ?></h3>
                            <p>Perkara Tanpa PBT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= count($berkas_pending) ?></h3>
                            <p>Berkas Pending BHT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tasks"></i> Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?= site_url('Menu_baru/jadwal_bht_harian') ?>" class="btn btn-warning btn-block">
                                        <i class="fas fa-calendar-check"></i> Jadwal BHT Hari Ini
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= site_url('Menu_baru/perkara_putus_tanpa_pbt') ?>" class="btn btn-info btn-block">
                                        <i class="fas fa-exclamation-circle"></i> Perkara Tanpa PBT
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= site_url('Menu_baru/berkas_menu_bht') ?>" class="btn btn-success btn-block">
                                        <i class="fas fa-file-alt"></i> Kelola Berkas BHT
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= site_url('bht_reminder/export_report/excel') ?>?periode=<?= $periode ?>" class="btn btn-primary btn-block">
                                        <i class="fas fa-download"></i> Export Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Overview Row -->
            <?php if (isset($statistik_reminder)): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar"></i> Overview Bulan <?= date('F Y', strtotime($periode . '-01')) ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-primary"><i class="fas fa-gavel"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Perkara</span>
                                                <span class="info-box-number"><?= $statistik_reminder->total_perkara ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Sudah PBT</span>
                                                <span class="info-box-number"><?= $statistik_reminder->sudah_pbt ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-success"><i class="fas fa-file-alt"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Sudah BHT</span>
                                                <span class="info-box-number"><?= $statistik_reminder->sudah_bht ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-danger"><i class="fas fa-clock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Belum Selesai</span>
                                                <span class="info-box-number"><?= $statistik_reminder->total_perkara - $statistik_reminder->sudah_bht ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5>Progress Completion Rate</h5>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: <?= $statistik_reminder->total_perkara > 0 ? round(($statistik_reminder->sudah_bht / $statistik_reminder->total_perkara) * 100, 1) : 0 ?>%"
                                                aria-valuenow="<?= $statistik_reminder->total_perkara > 0 ? round(($statistik_reminder->sudah_bht / $statistik_reminder->total_perkara) * 100, 1) : 0 ?>"
                                                aria-valuemin="0" aria-valuemax="100">
                                                <?= $statistik_reminder->total_perkara > 0 ? round(($statistik_reminder->sudah_bht / $statistik_reminder->total_perkara) * 100, 1) : 0 ?>%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Urgent Cases Table -->
            <?php if (!empty($urgent_reminders)): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-exclamation-triangle text-danger"></i> Perkara yang Memerlukan Perhatian Segera
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="urgent-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Nomor Perkara</th>
                                                <th width="20%">Jenis Perkara</th>
                                                <th width="15%">Tanggal Putus</th>
                                                <th width="10%">Priority</th>
                                                <th width="10%">Hari Tertunda</th>
                                                <th width="20%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($urgent_reminders as $urgent): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $urgent['data']->nomor_perkara ?></td>
                                                    <td><?= $urgent['data']->jenis_perkara ?></td>
                                                    <td><?= date('d/m/Y', strtotime($urgent['data']->tanggal_putusan)) ?></td>
                                                    <td>
                                                        <?php if ($urgent['priority'] == 'high'): ?>
                                                            <span class="badge badge-danger">HIGH</span>
                                                        <?php elseif ($urgent['priority'] == 'medium'): ?>
                                                            <span class="badge badge-warning">MEDIUM</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-info">LOW</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= isset($urgent['data']->hari_tertunda) ? $urgent['data']->hari_tertunda . ' hari' : '-' ?></td>
                                                    <td>
                                                        <?php if ($urgent['type'] == 'overdue_pbt'): ?>
                                                            <button class="btn btn-sm btn-warning" onclick="markPBTDone(<?= $urgent['data']->perkara_id ?>)">
                                                                <i class="fas fa-check"></i> Mark PBT Done
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-success" onclick="markBHTDone(<?= $urgent['data']->perkara_id ?>)">
                                                                <i class="fas fa-check"></i> Mark BHT Done
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#urgent-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [5, "desc"]
            ], // Sort by hari tertunda desc
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Auto refresh every 5 minutes
        setInterval(function() {
            location.reload();
        }, 5 * 60 * 1000);
    });

    function refreshData() {
        location.reload();
    }

    function markPBTDone(perkaraId) {
        if (confirm('Apakah Anda yakin perkara ini sudah selesai PBT?')) {
            $.ajax({
                url: '<?= site_url("bht_reminder/mark_handled") ?>',
                type: 'POST',
                data: {
                    perkara_id: perkaraId,
                    action_type: 'pbt_done'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('PBT berhasil ditandai selesai');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan data');
                }
            });
        }
    }

    function markBHTDone(perkaraId) {
        if (confirm('Apakah Anda yakin perkara ini sudah selesai BHT?')) {
            $.ajax({
                url: '<?= site_url("bht_reminder/mark_handled") ?>',
                type: 'POST',
                data: {
                    perkara_id: perkaraId,
                    action_type: 'bht_done'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('BHT berhasil ditandai selesai');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan data');
                }
            });
        }
    }
</script>
