<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?= $title ?>
                        <span class="badge badge-danger ml-2" id="live-count"><?= $total_tanpa_pbt ?></span>
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

            <!-- Alert untuk peringatan kritis -->
            <?php if ($alert_level->kritis > 0): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> KRITIS!</h5>
                    Ada <strong><?= $alert_level->kritis ?></strong> perkara yang sudah lebih dari 14 hari sejak putus dan belum ada PBT!
                </div>
            <?php endif; ?>

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
                                    <label for="tanggal" class="mr-2">Tanggal:</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggal ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="<?= site_url('Menu_baru/export_excel/perkara_tanpa_pbt') ?>?tanggal=<?= $tanggal ?>" class="btn btn-success ml-2">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
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
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $total_tanpa_pbt ?></h3>
                            <p>Total Tanpa PBT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $alert_level->normal ?></h3>
                            <p>Normal (≤7 hari)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $alert_level->peringatan ?></h3>
                            <p>Peringatan (7-14 hari)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= $alert_level->kritis ?></h3>
                            <p>Kritis (>14 hari)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-fire"></i>
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
                                <i class="fas fa-list"></i> Perkara Putus yang Belum Ada PBT
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-danger">Perlu Perhatian Segera!</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($perkara_tanpa_pbt)): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Tidak ada perkara putus yang belum PBT. Bagus!
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="tanpa-pbt-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Nomor Perkara</th>
                                                <th width="20%">Jenis Perkara</th>
                                                <th width="15%">Tanggal Putus</th>
                                                <th width="15%">Hakim</th>
                                                <th width="10%">Hari Sejak Putus</th>
                                                <th width="10%">Level Peringatan</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($perkara_tanpa_pbt as $perkara): ?>
                                                <tr class="<?=
                                                            $perkara->level_peringatan == 'KRITIS' ? 'table-danger' : ($perkara->level_peringatan == 'PERINGATAN' ? 'table-warning' : 'table-success')
                                                            ?>">
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($perkara->nomor_perkara) ?></td>
                                                    <td><?= htmlspecialchars($perkara->jenis_perkara) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($perkara->tanggal_putus)) ?></td>
                                                    <td><?= htmlspecialchars($perkara->hakim) ?></td>
                                                    <td>
                                                        <span class="badge <?=
                                                                            $perkara->hari_sejak_putus > 14 ? 'badge-danger' : ($perkara->hari_sejak_putus > 7 ? 'badge-warning' : 'badge-success')
                                                                            ?>">
                                                            <?= $perkara->hari_sejak_putus ?> hari
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($perkara->level_peringatan == 'KRITIS'): ?>
                                                            <span class="badge badge-danger">
                                                                <i class="fas fa-fire"></i> KRITIS
                                                            </span>
                                                        <?php elseif ($perkara->level_peringatan == 'PERINGATAN'): ?>
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-exclamation-triangle"></i> PERINGATAN
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check"></i> NORMAL
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-info" onclick="showPerkaraDetail('<?= $perkara->nomor_perkara ?>')">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-warning" onclick="sendReminder('<?= $perkara->nomor_perkara ?>')">
                                                                <i class="fas fa-bell"></i>
                                                            </button>
                                                        </div>
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
                                <i class="fas fa-chart-pie"></i> Distribusi Level Peringatan
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="peringatanChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line"></i> Trend Perkara Tanpa PBT (7 Hari Terakhir)
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="trendChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detail Perkara -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Perkara</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Sound notification for critical alerts -->
<audio id="critical-alert-sound" preload="auto">
    <source src="<?= base_url('assets/sounds/critical.mp3') ?>" type="audio/mpeg">
    <source src="<?= base_url('assets/sounds/critical.wav') ?>" type="audio/wav">
</audio>

<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        $('#tanpa-pbt-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [5, "desc"]
            ], // Sort by hari sejak putus desc
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "columnDefs": [{
                "targets": [5], // Hari sejak putus column
                "type": "num"
            }]
        });

        // Initialize charts
        initializeCharts();

        // Auto refresh every 3 minutes
        setInterval(function() {
            refreshData();
        }, 3 * 60 * 1000);

        // Check for critical alerts every minute
        setInterval(checkCriticalAlerts, 60 * 1000);
    });

    function initializeCharts() {
        // Pie Chart for Peringatan Distribution
        var ctx1 = document.getElementById('peringatanChart').getContext('2d');
        var peringatanChart = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Normal', 'Peringatan', 'Kritis'],
                datasets: [{
                    data: [<?= $alert_level->normal ?>, <?= $alert_level->peringatan ?>, <?= $alert_level->kritis ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderColor: ['#28a745', '#ffc107', '#dc3545'],
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

        // Line Chart for Trend (placeholder - you can implement actual trend data)
        var ctx2 = document.getElementById('trendChart').getContext('2d');
        var trendChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['7 hari lalu', '6 hari lalu', '5 hari lalu', '4 hari lalu', '3 hari lalu', '2 hari lalu', 'Kemarin', 'Hari ini'],
                datasets: [{
                    label: 'Perkara Tanpa PBT',
                    data: [5, 7, 6, 8, 9, 7, 6, <?= $total_tanpa_pbt ?>],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function refreshData() {
        location.reload();
    }

    function checkCriticalAlerts() {
        $.ajax({
            url: '<?= site_url("Menu_baru/api_notifikasi") ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    var totalTanpaPbt = response.data.perkara_putus_tanpa_pbt;
                    $('#live-count').text(totalTanpaPbt);

                    if (totalTanpaPbt > 0) {
                        $('#live-count').removeClass('badge-success').addClass('badge-danger');

                        // Play critical alert sound
                        var alertSound = document.getElementById('critical-alert-sound');
                        if (alertSound && totalTanpaPbt > parseInt($('#live-count').data('last-count') || 0)) {
                            alertSound.play().catch(function(error) {
                                console.log('Audio play failed:', error);
                            });
                        }

                        // Show browser notification
                        if ("Notification" in window && Notification.permission === "granted") {
                            new Notification("Perkara Tanpa PBT - Perhatian!", {
                                body: "Ada " + totalTanpaPbt + " perkara putus yang belum ada PBT!",
                                icon: "<?= base_url('assets/dist/img/logo-mahkamah-agung.png') ?>",
                                tag: "tanpa-pbt-alert"
                            });
                        }
                    } else {
                        $('#live-count').removeClass('badge-danger').addClass('badge-success');
                    }

                    $('#live-count').data('last-count', totalTanpaPbt);
                }
            }
        });
    }

    function showPerkaraDetail(nomorPerkara) {
        $('#detailContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        $('#detailModal').modal('show');

        // Load detail data via AJAX
        setTimeout(function() {
            $('#detailContent').html('<p>Detail untuk perkara: <strong>' + nomorPerkara + '</strong></p><p>Fitur ini akan dikembangkan lebih lanjut untuk menampilkan detail lengkap perkara.</p>');
        }, 1000);
    }

    function sendReminder(nomorPerkara) {
        if (confirm('Kirim pengingat untuk perkara ' + nomorPerkara + '?')) {
            // Implementation for sending reminder
            alert('Pengingat telah dikirim untuk perkara ' + nomorPerkara);
        }
    }

    // Request notification permission
    if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
    }
</script>
