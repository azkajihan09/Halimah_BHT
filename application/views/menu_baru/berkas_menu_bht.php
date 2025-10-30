<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
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

            <!-- Filter Row -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filter Data
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-3">
                                    <label for="periode" class="mr-2">Periode:</label>
                                    <input type="month" class="form-control" id="periode" name="periode" value="<?= $periode ?>">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="jenis" class="mr-2">Jenis Perkara:</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="semua" <?= $jenis == 'semua' ? 'selected' : '' ?>>Semua Jenis</option>
                                        <?php foreach ($kategori_berkas as $kategori): ?>
                                            <option value="<?= $kategori->jenis_perkara_nama ?>" <?= $jenis == $kategori->jenis_perkara_nama ? 'selected' : '' ?>>
                                                <?= $kategori->jenis_perkara_nama ?> (<?= $kategori->jumlah ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="<?= site_url('Menu_baru/export_excel/berkas_menu_bht') ?>?periode=<?= $periode ?>&jenis=<?= $jenis ?>" class="btn btn-success ml-2">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Row -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $progress_bht->total_perkara ?></h3>
                            <p>Total Perkara Putus</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $progress_bht->sudah_pbt ?></h3>
                            <p>Sudah PBT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $progress_bht->sudah_bht ?></h3>
                            <p>Sudah BHT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= $progress_bht->total_perkara > 0 ? round(($progress_bht->sudah_bht / $progress_bht->total_perkara) * 100, 1) : 0 ?>%</h3>
                            <p>Progress BHT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tasks"></i> Progress Overview BHT Bulan <?= date('F Y', strtotime($periode . '-01')) ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Progress PBT ke BHT</h5>
                                    <div class="progress progress-lg">
                                        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar"
                                            style="width: <?= $progress_bht->sudah_pbt > 0 ? round(($progress_bht->sudah_bht / $progress_bht->sudah_pbt) * 100, 1) : 0 ?>%"
                                            aria-valuenow="<?= $progress_bht->sudah_pbt > 0 ? round(($progress_bht->sudah_bht / $progress_bht->sudah_pbt) * 100, 1) : 0 ?>"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <?= $progress_bht->sudah_pbt > 0 ? round(($progress_bht->sudah_bht / $progress_bht->sudah_pbt) * 100, 1) : 0 ?>%
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= $progress_bht->sudah_bht ?> dari <?= $progress_bht->sudah_pbt ?> perkara yang sudah PBT telah selesai BHT
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <h5>Progress Keseluruhan</h5>
                                    <div class="progress progress-lg">
                                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar"
                                            style="width: <?= $progress_bht->total_perkara > 0 ? round(($progress_bht->sudah_bht / $progress_bht->total_perkara) * 100, 1) : 0 ?>%"
                                            aria-valuenow="<?= $progress_bht->total_perkara > 0 ? round(($progress_bht->sudah_bht / $progress_bht->total_perkara) * 100, 1) : 0 ?>"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <?= $progress_bht->total_perkara > 0 ? round(($progress_bht->sudah_bht / $progress_bht->total_perkara) * 100, 1) : 0 ?>%
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= $progress_bht->sudah_bht ?> dari <?= $progress_bht->total_perkara ?> total perkara putus telah selesai BHT
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Daftar Berkas BHT Periode <?= date('F Y', strtotime($periode . '-01')) ?>
                                <?= $jenis != 'semua' ? '- ' . $jenis : '' ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($berkas_bht)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada berkas BHT pada periode <?= date('F Y', strtotime($periode . '-01')) ?>
                                    <?= $jenis != 'semua' ? 'untuk jenis perkara ' . $jenis : '' ?>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="bht-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="18%">Nomor Perkara</th>
                                                <th width="20%">Jenis Perkara</th>
                                                <th width="10%">Tgl Putus</th>
                                                <th width="10%">Tgl PBT</th>
                                                <th width="10%">Tgl BHT</th>
                                                <th width="12%">Status BHT</th>
                                                <th width="15%">Progress</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($berkas_bht as $berkas): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($berkas->nomor_perkara) ?></td>
                                                    <td><?= htmlspecialchars($berkas->jenis_perkara) ?></td>
                                                    <td><?= $berkas->tanggal_putusan ? date('d/m/Y', strtotime($berkas->tanggal_putusan)) : '-' ?></td>
                                                    <td><?= $berkas->tanggal_pbt ? date('d/m/Y', strtotime($berkas->tanggal_pbt)) : '-' ?></td>
                                                    <td><?= $berkas->tanggal_bht ? date('d/m/Y', strtotime($berkas->tanggal_bht)) : '-' ?></td>
                                                    <td>
                                                        <?php if ($berkas->status_bht == 'SELESAI'): ?>
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check"></i> <?= $berkas->status_bht ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-clock"></i> <?= $berkas->status_bht ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($berkas->progress_display == 'Selesai'): ?>
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check-circle"></i> <?= $berkas->progress_display ?>
                                                            </span>
                                                        <?php elseif ($berkas->progress_display == 'Menunggu PBT'): ?>
                                                            <span class="badge badge-danger">
                                                                <i class="fas fa-exclamation-triangle"></i> <?= $berkas->progress_display ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-info">
                                                                <i class="fas fa-cogs"></i> <?= $berkas->progress_display ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie"></i> Distribusi Jenis Perkara
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="jenisPerkaraChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar"></i> Status Progress
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="progressChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        $('#bht-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [3, "desc"]
            ], // Sort by tanggal putus desc
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Initialize Jenis Perkara chart
        var ctx1 = document.getElementById('jenisPerkaraChart').getContext('2d');
        var jenisPerkaraChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: [
                    <?php foreach ($kategori_berkas as $kategori): ?> '<?= $kategori->jenis_perkara_nama ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    data: [
                        <?php foreach ($kategori_berkas as $kategori): ?> <?= $kategori->jumlah ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                        '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                }
            }
        });

        // Initialize Progress chart
        var ctx2 = document.getElementById('progressChart').getContext('2d');

        // Count progress statuses
        var selesai = <?= array_reduce($berkas_bht, function ($carry, $item) {
                            return $carry + (($item->progress_display == 'Selesai') ? 1 : 0);
                        }, 0) ?>;
        var dalam_proses = <?= array_reduce($berkas_bht, function ($carry, $item) {
                                return $carry + (($item->progress_display == 'Dalam Proses') ? 1 : 0);
                            }, 0) ?>;
        var menunggu_pbt = <?= array_reduce($berkas_bht, function ($carry, $item) {
                                return $carry + (($item->progress_display == 'Menunggu PBT') ? 1 : 0);
                            }, 0) ?>;

        var progressChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Selesai', 'Dalam Proses', 'Menunggu PBT'],
                datasets: [{
                    label: 'Jumlah Perkara',
                    data: [selesai, dalam_proses, menunggu_pbt],
                    backgroundColor: ['#28a745', '#17a2b8', '#dc3545'],
                    borderColor: ['#28a745', '#17a2b8', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                },
                legend: {
                    display: false
                }
            }
        });

        // Auto refresh every 10 minutes
        setInterval(function() {
            location.reload();
        }, 10 * 60 * 1000);
    });
</script>
