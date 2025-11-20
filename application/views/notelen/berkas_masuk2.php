<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Berkas Masuk Notelen' ?> - Sistem Notelen</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-nav>li>a {
            color: white;
        }

        .navbar-custom .navbar-nav>.active>a {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .stats-box {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }

        .stats-box h3 {
            margin: 0 0 10px 0;
            font-size: 2em;
        }

        .stats-box h4 {
            margin: 0 0 5px 0;
            font-size: 1.8em;
            font-weight: bold;
        }

        .stats-box p {
            margin: 0;
            font-size: 0.9em;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .table-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            margin: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            text-align: center;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }

        .table tbody td:first-child,
        .table tbody td:nth-child(2) {
            text-align: left;
        }

        .badge-custom {
            padding: 5px 10px;
            font-size: 0.75em;
            border-radius: 12px;
        }

        .badge-masuk {
            background-color: #28a745;
            color: white;
        }

        .badge-proses {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-selesai {
            background-color: #17a2b8;
            color: white;
        }

        .badge-keluar {
            background-color: #6c757d;
            color: white;
        }

        .badge-sudah-bht {
            background-color: #28a745;
            color: white;
        }

        .badge-deadline {
            background-color: #dc3545;
            color: white;
        }

        .badge-tepat-waktu {
            background-color: #28a745;
            color: white;
        }

        .badge-terlambat {
            background-color: #dc3545;
            color: white;
        }

        .badge-h14 {
            background-color: #17a2b8;
            color: white;
        }

        .badge-hari {
            background-color: #6c757d;
            color: white;
        }

        .btn-action {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 4px;
            font-size: 0.8em;
        }

        .pagination {
            margin: 20px 0;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-header .close {
            color: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .nomor-perkara {
            font-weight: bold;
            color: #007bff;
        }

        .majelis-hakim {
            font-size: 0.85em;
            color: #6c757d;
            max-width: 150px;
            word-wrap: break-word;
        }

        .tanggal {
            font-size: 0.9em;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <i class="fa fa-gavel"></i> PA Amuntai - Sistem Notelen
                </a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?= base_url('notelen') ?>"><i class="fa fa-folder"></i> Berkas Masuk</a></li>
                    <li><a href="<?= base_url('reminder_logging') ?>"><i class="fa fa-bell"></i> BHT Reminder</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><i class="fa fa-user"></i> Admin Notelen</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 70px;">
        <!-- Header -->
        <div class="row">
            <div class="col-md-12">
                <h1><i class="fa fa-folder-open"></i> <?= isset($page_title) ? $page_title : 'Berkas Masuk Notelen' ?></h1>
                <p class="text-muted">Kelola berkas masuk perkara yang sudah putus untuk keperluan notelen</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stats-box">
                    <h3><i class="fa fa-folder"></i></h3>
                    <h4><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->total_berkas) ? $stats['berkas']->total_berkas : 0 ?></h4>
                    <p>Total Berkas</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-box" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h3><i class="fa fa-check-circle"></i></h3>
                    <h4><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->status_masuk) ? $stats['berkas']->status_masuk : 0 ?></h4>
                    <p>Status Masuk</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-box" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                    <h3><i class="fa fa-clock-o"></i></h3>
                    <h4><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->status_proses) ? $stats['berkas']->status_proses : 0 ?></h4>
                    <p>Status Proses</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-box" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                    <h3><i class="fa fa-archive"></i></h3>
                    <h4><?= isset($stats['inventaris']) && $stats['inventaris'] && isset($stats['inventaris']->total_barang) ? $stats['inventaris']->total_barang : 0 ?></h4>
                    <p>Total Inventaris</p>
                </div>
            </div>
        </div>

        <!-- Filter & Controls -->
        <div class="row">
            <div class="col-md-12">
                <div class="filter-section">
                    <div class="row">
                        <div class="col-md-8">
                            <h4><i class="fa fa-filter"></i> Filter Data</h4>
                            <form method="GET" class="form-inline">
                                <div class="form-group" style="margin-right: 10px;">
                                    <label>Status:</label>
                                    <select name="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="MASUK" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'MASUK') ? 'selected' : '' ?>>Masuk</option>
                                        <option value="PROSES" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'PROSES') ? 'selected' : '' ?>>Proses</option>
                                        <option value="SELESAI" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'SELESAI') ? 'selected' : '' ?>>Selesai</option>
                                        <option value="KELUAR" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'KELUAR') ? 'selected' : '' ?>>Keluar</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-right: 10px;">
                                    <label>Nomor Perkara:</label>
                                    <input type="text" name="nomor" class="form-control" placeholder="Cari nomor perkara..."
                                        value="<?= isset($filters['nomor_perkara']) ? $filters['nomor_perkara'] : '' ?>">
                                </div>
                                <div class="form-group" style="margin-right: 10px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Filter
                                    </button>
                                    <a href="<?= base_url('notelen') ?>" class="btn btn-default">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success" onclick="openNewBerkasModal()">
                                    <i class="fa fa-plus"></i> Tambah Berkas
                                </button>
                                <button type="button" class="btn btn-info" onclick="syncFromSipp()">
                                    <i class="fa fa-refresh"></i> Sync SIPP
                                </button>
                                <a href="<?= base_url('notelen/export?format=excel') ?>" class="btn btn-warning">
                                    <i class="fa fa-download"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Data -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-section">
                    <div class="table-header">
                        <h4><i class="fa fa-list"></i> Daftar Berkas Masuk Notelen Harian (<?= isset($berkas_list) ? count($berkas_list) : 0 ?> berkas)</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="16%">Nomor Perkara</th>
                                    <th width="12%">Jenis Perkara</th>
                                    <th width="10%">Tanggal Putusan</th>
                                    <th width="15%">Majelis Hakim</th>
                                    <th width="10%">Status BHT</th>
                                    <th width="8%">Hari ini</th>
                                    <th width="9%">Tepat Waktu</th>
                                    <th width="8%">H-14</th>
                                    <th width="8%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($berkas_list) && !empty($berkas_list)): ?>
                                    <?php foreach ($berkas_list as $index => $berkas): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="text-left">
                                                <div class="nomor-perkara"><?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '-' ?></div>
                                            </td>
                                            <td><?= isset($berkas->jenis_perkara) ? $berkas->jenis_perkara : '-' ?></td>
                                            <td>
                                                <?php if (isset($berkas->tanggal_putusan)): ?>
                                                    <span class="tanggal">
                                                        <?= date('d/m/Y', strtotime($berkas->tanggal_putusan)) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="majelis-hakim"><?= isset($berkas->majelis_hakim) ? $berkas->majelis_hakim : '-' ?></div>
                                            </td>
                                            <td>
                                                <?php
                                                $hari_sejak_putusan = isset($berkas->tanggal_putusan) ?
                                                    floor((time() - strtotime($berkas->tanggal_putusan)) / (60 * 60 * 24)) : 0;

                                                if ($hari_sejak_putusan <= 14): ?>
                                                    <span class="badge badge-custom badge-sudah-bht">✓ Sudah BHT</span>
                                                <?php else: ?>
                                                    <span class="badge badge-custom badge-deadline">Hari ini (Deadline)</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-custom badge-hari">
                                                    (+<?= $hari_sejak_putusan ?> hari kerja)
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($hari_sejak_putusan <= 14): ?>
                                                    <span class="badge badge-custom badge-tepat-waktu">✓ Tepat Waktu</span>
                                                <?php else: ?>
                                                    <span class="badge badge-custom badge-terlambat">✗ Terlambat</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $tanggal_14_hari = isset($berkas->tanggal_putusan) ?
                                                    date('d/m/Y', strtotime($berkas->tanggal_putusan . ' + 14 days')) : '-';
                                                ?>
                                                <span class="badge badge-custom badge-h14">
                                                    📅 <?= $tanggal_14_hari ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $status = isset($berkas->status_berkas) ? $berkas->status_berkas : 'MASUK';
                                                $badge_class = '';
                                                switch ($status) {
                                                    case 'MASUK':
                                                        $badge_class = 'badge-masuk';
                                                        break;
                                                    case 'PROSES':
                                                        $badge_class = 'badge-proses';
                                                        break;
                                                    case 'SELESAI':
                                                        $badge_class = 'badge-selesai';
                                                        break;
                                                    case 'KELUAR':
                                                        $badge_class = 'badge-keluar';
                                                        break;
                                                    default:
                                                        $badge_class = 'badge-masuk';
                                                }
                                                ?>
                                                <span class="badge badge-custom <?= $badge_class ?>"><?= $status ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical btn-group-xs">
                                                    <button type="button" class="btn btn-primary btn-action"
                                                        onclick="openInventarisModal(<?= $berkas->id ?>, '<?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '' ?>')">
                                                        <i class="fa fa-plus"></i> Inventaris
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-action"
                                                        onclick="openBerkasDetail(<?= $berkas->id ?>)">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center text-muted" style="padding: 40px;">
                                            <i class="fa fa-folder-open-o fa-3x"></i><br><br>
                                            <h4>Belum Ada Data Berkas</h4>
                                            <p>Klik tombol "Tambah Berkas" atau "Sync SIPP" untuk menambah data</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($total_pages) && $total_pages > 1): ?>
                        <div class="text-center" style="margin: 20px 0;">
                            <nav>
                                <ul class="pagination">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="<?= (isset($current_page) && $i == $current_page) ? 'active' : '' ?>">
                                            <a href="?page=<?= $i ?><?= isset($filters['status_berkas']) && $filters['status_berkas'] ? '&status=' . $filters['status_berkas'] : '' ?><?= isset($filters['nomor_perkara']) && $filters['nomor_perkara'] ? '&nomor=' . $filters['nomor_perkara'] : '' ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Berkas -->
    <div class="modal fade" id="newBerkasModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <i class="fa fa-plus"></i> Tambah Berkas Masuk Baru
                    </h4>
                </div>
                <form id="newBerkasForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nomor Perkara *</label>
                            <select name="nomor_perkara" id="nomorPerkaraSelect" class="form-control" required>
                                <option value="">Pilih Nomor Perkara...</option>
                            </select>
                            <small class="help-block">Pilih dari daftar perkara yang sudah putus</small>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Putusan *</label>
                            <input type="date" name="tanggal_putusan" id="tanggalPutusan" class="form-control" readonly required>
                            <small class="help-block">Otomatis terisi dari data SIPP</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Perkara</label>
                                    <input type="text" name="jenis_perkara" id="jenisPerkara" class="form-control" readonly>
                                    <small class="help-block">Otomatis terisi dari data SIPP</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Majelis Hakim</label>
                            <input type="text" name="majelis_hakim" id="majelisHakim" class="form-control" readonly>
                            <small class="help-block">Otomatis terisi dari data SIPP</small>
                        </div>

                        <div class="form-group">
                            <label>Panitera Pengganti</label>
                            <input type="text" name="panitera_pengganti" id="paniteraPengganti" class="form-control" readonly>
                            <small class="help-block">Otomatis terisi dari data SIPP</small>
                        </div>

                        <div class="form-group">
                            <label>Catatan Notelen</label>
                            <textarea name="catatan_notelen" class="form-control" rows="3"
                                placeholder="Catatan khusus untuk notelen..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Berkas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            console.log('Notelen System loaded successfully!');
        });

        function openNewBerkasModal() {
            $('#newBerkasModal').modal('show');
            $('#newBerkasForm')[0].reset();

            // Load dropdown perkara
            loadPerkaraDropdown();
        }

        // Load data perkara untuk dropdown
        function loadPerkaraDropdown() {
            $.ajax({
                url: '<?= base_url("notelen/ajax_get_perkara_dropdown") ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var select = $('#nomorPerkaraSelect');
                        select.empty().append('<option value="">Pilih Nomor Perkara...</option>');

                        $.each(response.data, function(index, item) {
                            var option = $('<option></option>')
                                .attr('value', item.nomor_perkara)
                                .text(item.nomor_perkara + ' - ' + item.jenis_perkara)
                                .data('detail', item);
                            select.append(option);
                        });
                    }
                },
                error: function() {
                    console.log('Error loading perkara dropdown');
                }
            });
        }

        // Auto-fill ketika nomor perkara dipilih
        $(document).on('change', '#nomorPerkaraSelect', function() {
            var selectedOption = $(this).find('option:selected');
            var detail = selectedOption.data('detail');

            if (detail) {
                // Format tanggal dari YYYY-MM-DD
                var tanggal = detail.tanggal_putusan;
                if (tanggal) {
                    $('#tanggalPutusan').val(tanggal);
                }

                $('#jenisPerkara').val(detail.jenis_perkara || '-');
                $('#majelisHakim').val(detail.majelis_hakim || '-');
                $('#paniteraPengganti').val(detail.panitera_pengganti || '-');
            } else {
                // Clear fields if no selection
                $('#tanggalPutusan').val('');
                $('#jenisPerkara').val('');
                $('#majelisHakim').val('');
                $('#paniteraPengganti').val('');
            }
        });

        $('#newBerkasForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url("notelen/ajax_insert_berkas") ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#newBerkasModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan koneksi'
                    });
                }
            });
        });

        function syncFromSipp() {
            Swal.fire({
                title: 'Sync dari SIPP?',
                text: 'Akan mengambil data perkara putus terbaru dari database SIPP',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Sync!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("notelen/ajax_sync_sipp") ?>',
                        type: 'POST',
                        data: {
                            limit: 100
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sync Berhasil!',
                                    text: response.message,
                                    timer: 3000
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Sync Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat sync data'
                            });
                        }
                    });
                }
            });
        }

        function openInventarisModal(berkas_id, nomor_perkara) {
            Swal.fire({
                title: 'Inventaris Berkas',
                text: 'Fitur inventaris untuk berkas ' + nomor_perkara + ' akan segera tersedia',
                icon: 'info'
            });
        }

        function openBerkasDetail(berkas_id) {
            Swal.fire({
                title: 'Detail Berkas',
                text: 'Fitur detail berkas akan segera tersedia',
                icon: 'info'
            });
        }
    </script>
</body>

</html>
