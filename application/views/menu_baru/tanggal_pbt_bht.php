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
                                    <label for="bulan" class="mr-2">Bulan:</label>
                                    <input type="month" class="form-control" id="bulan" name="bulan" value="<?= $bulan ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="<?= site_url('Menu_baru/export_excel/tanggal_pbt_bht') ?>?bulan=<?= $bulan ?>" class="btn btn-success ml-2">
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
                            <h3><?= count($data_pbt_bht) ?></h3>
                            <p>Total Perkara</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= count(array_filter($data_pbt_bht, function ($p) {
                                    return $p->status_proses == 'Belum PBT';
                                })) ?></h3>
                            <p>Belum PBT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= count(array_filter($data_pbt_bht, function ($p) {
                                    return $p->status_proses == 'Sudah PBT, Belum BHT';
                                })) ?></h3>
                            <p>Sudah PBT, Belum BHT</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= count(array_filter($data_pbt_bht, function ($p) {
                                    return $p->status_proses == 'Selesai';
                                })) ?></h3>
                            <p>Selesai (PBT & BHT)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar"></i> Kalender PBT dan BHT - <?= date('F Y', strtotime($bulan . '-01')) ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
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
                                <i class="fas fa-list"></i> Detail Tanggal PBT dan BHT
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data_pbt_bht)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada data perkara pada bulan <?= date('F Y', strtotime($bulan . '-01')) ?>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="pbt-bht-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Nomor Perkara</th>
                                                <th width="15%">Jenis Perkara</th>
                                                <th width="12%">Tgl Putusan</th>
                                                <th width="12%">Tgl PBT</th>
                                                <th width="12%">Tgl BHT</th>
                                                <th width="8%">Selisih Putus-PBT</th>
                                                <th width="8%">Selisih PBT-BHT</th>
                                                <th width="13%">Status Proses</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($data_pbt_bht as $perkara): ?>
                                                <tr class="<?=
                                                            $perkara->status_proses == 'Belum PBT' ? 'table-warning' : ($perkara->status_proses == 'Sudah PBT, Belum BHT' ? 'table-info' : ($perkara->status_proses == 'Selesai' ? 'table-success' : ''))
                                                            ?>">
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($perkara->nomor_perkara) ?></td>
                                                    <td><?= htmlspecialchars($perkara->jenis_perkara) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($perkara->tanggal_putusan)) ?></td>
                                                    <td>
                                                        <?= $perkara->tanggal_pbt ? date('d/m/Y', strtotime($perkara->tanggal_pbt)) : '<span class="text-muted">-</span>' ?>
                                                    </td>
                                                    <td>
                                                        <?= $perkara->tanggal_bht ? date('d/m/Y', strtotime($perkara->tanggal_bht)) : '<span class="text-muted">-</span>' ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($perkara->selisih_putus_pbt !== null): ?>
                                                            <span class="badge <?= $perkara->selisih_putus_pbt > 3 ? 'badge-danger' : 'badge-success' ?>">
                                                                <?= $perkara->selisih_putus_pbt ?> hari
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($perkara->selisih_pbt_bht !== null): ?>
                                                            <span class="badge <?= $perkara->selisih_pbt_bht > 7 ? 'badge-danger' : 'badge-success' ?>">
                                                                <?= $perkara->selisih_pbt_bht ?> hari
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($perkara->status_proses == 'Belum PBT'): ?>
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-clock"></i> <?= $perkara->status_proses ?>
                                                            </span>
                                                        <?php elseif ($perkara->status_proses == 'Sudah PBT, Belum BHT'): ?>
                                                            <span class="badge badge-info">
                                                                <i class="fas fa-hourglass-half"></i> <?= $perkara->status_proses ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check"></i> <?= $perkara->status_proses ?>
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
        </div>
    </section>
</div>

<!-- FullCalendar -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/fullcalendar/main.css') ?>">
<script src="<?= base_url('assets/plugins/fullcalendar/main.js') ?>"></script>

<script>
    $(document).ready(function() {
        // DataTable
        $('#pbt-bht-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [3, "desc"]
            ], // Sort by tanggal putusan desc
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Calendar
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            initialDate: '<?= $bulan ?>-01',
            locale: 'id',
            events: [
                <?php foreach ($kalender_data as $kal): ?> {
                        title: 'P:<?= $kal->total_putus ?> | PBT:<?= $kal->total_pbt ?> | BHT:<?= $kal->total_bht ?>',
                        date: '<?= $kal->tanggal ?>',
                        backgroundColor: '<?= $kal->total_bht == $kal->total_putus ? "#28a745" : ($kal->total_pbt == $kal->total_putus ? "#17a2b8" : "#ffc107") ?>',
                        borderColor: '<?= $kal->total_bht == $kal->total_putus ? "#28a745" : ($kal->total_pbt == $kal->total_putus ? "#17a2b8" : "#ffc107") ?>',
                        textColor: '<?= $kal->total_bht == $kal->total_putus ? "#fff" : "#000" ?>'
                    },
                <?php endforeach; ?>
            ],
            eventClick: function(info) {
                var date = info.event.startStr;
                window.location.href = '<?= site_url("Menu_baru/perkara_putus_harian") ?>?tanggal=' + date;
            }
        });
        calendar.render();
    });
</script>
