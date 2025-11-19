<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= isset($title) ? $title : 'Notelen System' ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap CSS dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome dari CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .stats-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .berkas-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            background: white;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .berkas-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-color: #007cba;
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .status-MASUK {
            background: #5cb85c;
            color: white;
        }

        .status-PROSES {
            background: #f0ad4e;
            color: white;
        }

        .status-SELESAI {
            background: #5bc0de;
            color: white;
        }

        .status-KELUAR {
            background: #d9534f;
            color: white;
        }

        .btn-floating {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <i class="fa fa-folder-open"></i> Notelen System
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
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
                <p class="text-muted">Sistem inventaris berkas perkara putus</p>
                <hr>
            </div>
        </div>

        <!-- Statistics Dashboard -->
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
                                    <input type="text" name="nomor" class="form-control"
                                        value="<?= isset($filters['nomor_perkara']) ? $filters['nomor_perkara'] : '' ?>"
                                        placeholder="Cari nomor perkara...">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Filter
                                </button>

                                <a href="<?= base_url('notelen') ?>" class="btn btn-default">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </form>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-success" onclick="openNewBerkasModal()">
                                <i class="fa fa-plus"></i> Tambah Berkas
                            </button>
                            <button type="button" class="btn btn-info" onclick="syncFromSipp()">
                                <i class="fa fa-refresh"></i> Sync SIPP
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berkas List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-header" style="padding: 15px;">
                        <h4>
                            <i class="fa fa-list"></i> Daftar Berkas Masuk Notelen
                            <small>(<?= isset($total_berkas) ? number_format($total_berkas) : 0 ?> berkas)</small>
                            <div class="pull-right">
                                <a href="<?= base_url('notelen/export?format=excel') ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </a>
                            </div>
                        </h4>
                    </div>
                    <div class="panel-body">

                        <?php if (!isset($berkas_list) || empty($berkas_list)): ?>
                            <div class="text-center" style="padding: 60px;">
                                <i class="fa fa-folder-open fa-5x text-muted"></i>
                                <h3 class="text-muted">Belum ada berkas masuk</h3>
                                <p class="text-muted">Klik tombol "Tambah Berkas" atau "Sync SIPP" untuk menambah data</p>
                                <button type="button" class="btn btn-success btn-lg" onclick="openNewBerkasModal()">
                                    <i class="fa fa-plus"></i> Tambah Berkas Pertama
                                </button>
                            </div>
                        <?php else: ?>

                            <div class="row">
                                <?php foreach ($berkas_list as $berkas): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="berkas-card">
                                            <div class="panel panel-default" style="margin: 0;">
                                                <div class="panel-heading">
                                                    <h5 style="margin: 0;">
                                                        <strong><?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '-' ?></strong>
                                                        <span class="status-badge status-<?= isset($berkas->status_berkas) ? $berkas->status_berkas : 'MASUK' ?> pull-right">
                                                            <?= isset($berkas->status_berkas) ? $berkas->status_berkas : 'MASUK' ?>
                                                        </span>
                                                    </h5>
                                                    <small class="text-muted"><?= isset($berkas->jenis_perkara) ? $berkas->jenis_perkara : 'Perkara Umum' ?></small>
                                                </div>

                                                <div class="panel-body" style="padding: 15px;">
                                                    <p class="text-sm">
                                                        <i class="fa fa-calendar"></i>
                                                        Putusan: <?= isset($berkas->tanggal_putusan) && $berkas->tanggal_putusan ? date('d/m/Y', strtotime($berkas->tanggal_putusan)) : '-' ?>
                                                    </p>
                                                    <p class="text-sm">
                                                        <i class="fa fa-clock-o"></i>
                                                        Masuk: <?= isset($berkas->tanggal_masuk_notelen) && $berkas->tanggal_masuk_notelen ? date('d/m/Y', strtotime($berkas->tanggal_masuk_notelen)) : '-' ?>
                                                    </p>

                                                    <?php if (isset($berkas->majelis_hakim) && !empty($berkas->majelis_hakim) && $berkas->majelis_hakim != '-'): ?>
                                                        <p class="text-sm text-muted">
                                                            <i class="fa fa-user"></i>
                                                            <?= substr($berkas->majelis_hakim, 0, 30) ?>
                                                            <?= (strlen($berkas->majelis_hakim) > 30) ? '...' : '' ?>
                                                        </p>
                                                    <?php endif; ?>

                                                    <div class="row text-center" style="margin-top: 10px;">
                                                        <div class="col-xs-6">
                                                            <small class="text-muted">Inventaris</small><br>
                                                            <strong class="text-primary">
                                                                <?= isset($berkas->total_inventaris) ? $berkas->total_inventaris : 0 ?> jenis
                                                            </strong>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <small class="text-muted">Total Barang</small><br>
                                                            <strong class="text-success">
                                                                <?= isset($berkas->total_barang) ? $berkas->total_barang : 0 ?> item
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="panel-footer">
                                                    <div class="btn-group btn-group-justified">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                onclick="openInventarisModal(<?= $berkas->id ?>, '<?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '' ?>')">
                                                                <i class="fa fa-plus"></i> Inventaris
                                                            </button>
                                                        </div>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info btn-sm"
                                                                onclick="openBerkasDetail(<?= $berkas->id ?>)">
                                                                <i class="fa fa-eye"></i> Detail
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Pagination -->
                            <?php if (isset($total_pages) && $total_pages > 1): ?>
                                <div class="text-center" style="margin-top: 20px;">
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

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button type="button" class="btn btn-success btn-floating" onclick="openNewBerkasModal()" title="Tambah Berkas Baru">
        <i class="fa fa-plus fa-2x"></i>
    </button>

    <!-- MODAL: New Berkas -->
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
                    Swal.fire({
                        title: 'Syncing...',
                        text: 'Sedang mengambil data dari SIPP',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '<?= base_url("notelen/ajax_sync_sipp") ?>',
                        type: 'POST',
                        data: {
                            limit: 50
                        },
                        dataType: 'json',
                        success: function(response) {
                            Swal.close();

                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire('Error!', 'Terjadi kesalahan saat sync', 'error');
                        }
                    });
                }
            });
        }

        function openInventarisModal(berkasId, nomorPerkara) {
            Swal.fire('Info', 'Fitur inventaris akan segera hadir!', 'info');
        }

        function openBerkasDetail(berkasId) {
            Swal.fire('Info', 'Fitur detail berkas akan segera hadir!', 'info');
        }
    </script>
</body>

</html>