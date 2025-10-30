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
                                    <label for="tanggal" class="mr-2">Tanggal:</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggal ?>">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="status" class="mr-2">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="semua" <?= $status == 'semua' ? 'selected' : '' ?>>Semua Status</option>
                                        <option value="baru" <?= $status == 'baru' ? 'selected' : '' ?>>Baru</option>
                                        <option value="proses" <?= $status == 'proses' ? 'selected' : '' ?>>Proses</option>
                                        <option value="putus" <?= $status == 'putus' ? 'selected' : '' ?>>Putus</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="<?= site_url('Menu_baru/export_excel/berkas_masuk') ?>?tanggal=<?= $tanggal ?>&status=<?= $status ?>" class="btn btn-success ml-2">
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
                            <h3><?= $total_berkas ?></h3>
                            <p>Total Berkas Masuk</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                    </div>
                </div>
                <?php foreach ($statistik_berkas as $stat): ?>
                    <div class="col-lg-3 col-6">
                        <div class="small-box <?=
                                                $stat->status_perkara == 'BARU' ? 'bg-warning' : ($stat->status_perkara == 'PROSES' ? 'bg-info' : 'bg-success')
                                                ?>">
                            <div class="inner">
                                <h3><?= $stat->jumlah ?></h3>
                                <p><?= ucfirst(strtolower($stat->status_perkara)) ?></p>
                            </div>
                            <div class="icon">
                                <i class="fas <?=
                                                $stat->status_perkara == 'BARU' ? 'fa-file-plus' : ($stat->status_perkara == 'PROSES' ? 'fa-cogs' : 'fa-check-circle')
                                                ?>"></i>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Data Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Daftar Berkas Masuk Tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($berkas_masuk)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada berkas masuk pada tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>
                                    <?= $status != 'semua' ? 'dengan status ' . strtoupper($status) : '' ?>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="berkas-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Nomor Perkara</th>
                                                <th width="25%">Jenis Perkara</th>
                                                <th width="15%">Tanggal Daftar</th>
                                                <th width="15%">Hakim</th>
                                                <th width="10%">Status</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($berkas_masuk as $berkas): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($berkas->nomor_perkara) ?></td>
                                                    <td><?= htmlspecialchars($berkas->jenis_perkara) ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($berkas->tanggal_pendaftaran)) ?></td>
                                                    <td><?= htmlspecialchars($berkas->hakim) ?></td>
                                                    <td>
                                                        <?php if ($berkas->status_perkara == 'BARU'): ?>
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-file-plus"></i> <?= $berkas->status_display ?>
                                                            </span>
                                                        <?php elseif ($berkas->status_perkara == 'PROSES'): ?>
                                                            <span class="badge badge-info">
                                                                <i class="fas fa-cogs"></i> <?= $berkas->status_display ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check"></i> <?= $berkas->status_display ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-info" onclick="showBerkasDetail('<?= $berkas->nomor_perkara ?>')">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <?php if ($berkas->status_perkara == 'BARU'): ?>
                                                                <button type="button" class="btn btn-success" onclick="processberkas('<?= $berkas->nomor_perkara ?>')">
                                                                    <i class="fas fa-play"></i>
                                                                </button>
                                                            <?php endif; ?>
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
                                <i class="fas fa-chart-pie"></i> Distribusi Status Berkas
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock"></i> Timeline Berkas Masuk Hari Ini
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <?php
                                $timeline = array_slice($berkas_masuk, 0, 5); // Show last 5 entries
                                foreach ($timeline as $item):
                                ?>
                                    <div class="time-label">
                                        <span class="bg-primary"><?= date('H:i', strtotime($item->tanggal_pendaftaran)) ?></span>
                                    </div>
                                    <div>
                                        <i class="fas fa-file bg-info"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header"><?= htmlspecialchars($item->nomor_perkara) ?></h3>
                                            <div class="timeline-body">
                                                <strong><?= htmlspecialchars($item->jenis_perkara) ?></strong><br>
                                                Hakim: <?= htmlspecialchars($item->hakim) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detail Berkas -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Berkas</h5>
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

<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        $('#berkas-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [3, "desc"]
            ], // Sort by tanggal daftar desc
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Initialize status chart
        var ctx = document.getElementById('statusChart').getContext('2d');
        var statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    <?php foreach ($statistik_berkas as $stat): ?> '<?= ucfirst(strtolower($stat->status_perkara)) ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    data: [
                        <?php foreach ($statistik_berkas as $stat): ?>
                            <?= $stat->jumlah ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        <?php foreach ($statistik_berkas as $stat): ?> '<?= $stat->status_perkara == "BARU" ? "#ffc107" : ($stat->status_perkara == "PROSES" ? "#17a2b8" : "#28a745") ?>',
                        <?php endforeach; ?>
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

        // Auto refresh every 5 minutes
        setInterval(function() {
            location.reload();
        }, 5 * 60 * 1000);
    });

    function showBerkasDetail(nomorPerkara) {
        $('#detailContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        $('#detailModal').modal('show');

        // Load detail data via AJAX
        setTimeout(function() {
            $('#detailContent').html('<p>Detail untuk berkas: <strong>' + nomorPerkara + '</strong></p><p>Fitur ini akan dikembangkan lebih lanjut untuk menampilkan detail lengkap berkas.</p>');
        }, 1000);
    }

    function processberkas(nomorPerkara) {
        if (confirm('Mulai proses untuk berkas ' + nomorPerkara + '?')) {
            // Implementation for processing berkas
            alert('Berkas ' + nomorPerkara + ' telah dimulai prosesnya');
            location.reload();
        }
    }
</script>
