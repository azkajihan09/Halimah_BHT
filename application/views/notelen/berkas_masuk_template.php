<?php $this->load->view('template/new_header'); ?>

<?php $this->load->view('template/new_sidebar'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= isset($page_title) ? $page_title : 'Berkas Masuk Notelen 2' ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('notelen') ?>">Notelen</a></li>
                        <li class="breadcrumb-item active">Berkas Masuk 2</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-folder"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Berkas</span>
                            <span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->total_berkas) ? $stats['berkas']->total_berkas : 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Status Masuk</span>
                            <span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->status_masuk) ? $stats['berkas']->status_masuk : 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Status Proses</span>
                            <span class="info-box-number"><?= isset($stats['berkas']) && $stats['berkas'] && isset($stats['berkas']->status_proses) ? $stats['berkas']->status_proses : 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-archive"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Inventaris</span>
                            <span class="info-box-number"><?= isset($stats['inventaris']) && $stats['inventaris'] && isset($stats['inventaris']->total_barang) ? $stats['inventaris']->total_barang : 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Controls -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter"></i> Filter Data</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="<?= base_url('notelen/berkas_template') ?>" class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status Berkas:</label>
                                        <select name="status" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="MASUK" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'MASUK') ? 'selected' : '' ?>>Masuk</option>
                                            <option value="PROSES" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'PROSES') ? 'selected' : '' ?>>Proses</option>
                                            <option value="SELESAI" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'SELESAI') ? 'selected' : '' ?>>Selesai</option>
                                            <option value="KELUAR" <?= (isset($filters['status_berkas']) && $filters['status_berkas'] == 'KELUAR') ? 'selected' : '' ?>>Keluar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Nomor Perkara:</label>
                                        <input type="text" name="nomor" class="form-control" placeholder="Cari nomor perkara..."
                                            value="<?= isset($filters['nomor_perkara']) ? $filters['nomor_perkara'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="<?= base_url('notelen/reset_filters') ?>" class="btn btn-secondary">
                                            <i class="fas fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-3 text-right">
                                    <label>&nbsp;</label><br>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success" onclick="openNewBerkasModal()">
                                            <i class="fas fa-plus"></i> Tambah Berkas
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="syncFromSipp()">
                                            <i class="fas fa-sync"></i> Sync SIPP
                                        </button>
                                        <a href="<?= base_url('notelen/export?format=excel') ?>" class="btn btn-warning">
                                            <i class="fas fa-download"></i> Export Excel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Data -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list"></i> Daftar Berkas Masuk Notelen (<?= isset($berkas_list) ? count($berkas_list) : 0 ?> berkas)</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="18%">Nomor Perkara *</th>
                                            <th width="12%">Tanggal Putusan *</th>
                                            <th width="15%">Jenis Perkara</th>
                                            <th width="10%">Masuk</th>
                                            <th width="15%">Majelis Hakim</th>
                                            <th width="10%">Panitera</th>
                                            <th width="15%">Catatan Notelen</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($berkas_list) && !empty($berkas_list)): ?>
                                            <?php foreach ($berkas_list as $index => $berkas): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td class="text-left">
                                                        <div class="font-weight-bold text-primary"><?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '-' ?></div>
                                                    </td>
                                                    <td>
                                                        <?php if (isset($berkas->tanggal_putusan)): ?>
                                                            <?= date('d/m/Y', strtotime($berkas->tanggal_putusan)) ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= isset($berkas->jenis_perkara) ? $berkas->jenis_perkara : '-' ?></td>
                                                    <td>
                                                        <?php if (isset($berkas->tanggal_masuk_notelen)): ?>
                                                            <?= date('d/m/Y', strtotime($berkas->tanggal_masuk_notelen)) ?>
                                                        <?php else: ?>
                                                            <?= date('d/m/Y') ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted" style="font-size: 0.85em; max-width: 150px; word-wrap: break-word;">
                                                            <?= isset($berkas->majelis_hakim) ? $berkas->majelis_hakim : '-' ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <?= isset($berkas->panitera_pengganti) ? $berkas->panitera_pengganti : '-' ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted" style="max-width: 200px; word-wrap: break-word;">
                                                            <?= isset($berkas->catatan_notelen) ? $berkas->catatan_notelen : '-' ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="deleteBerkas(<?= $berkas->id ?>, '<?= isset($berkas->nomor_perkara) ? $berkas->nomor_perkara : '' ?>')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    <i class="fas fa-folder-open fa-3x mb-3"></i><br>
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
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted mb-0">
                                                Menampilkan <?= isset($berkas_list) ? count($berkas_list) : 0 ?> dari <?= isset($total_berkas) ? $total_berkas : 0 ?> data berkas
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-end">
                                                <nav>
                                                    <ul class="pagination pagination-sm mb-0">
                                                        <!-- Previous Button -->
                                                        <?php if (isset($current_page) && $current_page > 1): ?>
                                                            <li class="page-item">
                                                                <a class="page-link" href="<?= base_url('notelen/berkas_template') ?>?page=<?= $current_page - 1 ?><?= isset($filters['status_berkas']) && $filters['status_berkas'] ? '&status=' . $filters['status_berkas'] : '' ?><?= isset($filters['nomor_perkara']) && $filters['nomor_perkara'] ? '&nomor=' . $filters['nomor_perkara'] : '' ?>">
                                                                    <i class="fas fa-angle-left"></i>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <!-- Page Numbers -->
                                                        <?php
                                                        $start_page = max(1, (isset($current_page) ? $current_page : 1) - 2);
                                                        $end_page = min($total_pages, $start_page + 4);
                                                        if ($end_page - $start_page < 4) {
                                                            $start_page = max(1, $end_page - 4);
                                                        }
                                                        ?>

                                                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                                            <li class="page-item <?= (isset($current_page) && $i == $current_page) ? 'active' : '' ?>">
                                                                <a class="page-link" href="<?= base_url('notelen/berkas_template') ?>?page=<?= $i ?><?= isset($filters['status_berkas']) && $filters['status_berkas'] ? '&status=' . $filters['status_berkas'] : '' ?><?= isset($filters['nomor_perkara']) && $filters['nomor_perkara'] ? '&nomor=' . $filters['nomor_perkara'] : '' ?>">
                                                                    <?= $i ?>
                                                                </a>
                                                            </li>
                                                        <?php endfor; ?>

                                                        <!-- Next Button -->
                                                        <?php if (isset($current_page) && $current_page < $total_pages): ?>
                                                            <li class="page-item">
                                                                <a class="page-link" href="<?= base_url('notelen/berkas_template') ?>?page=<?= $current_page + 1 ?><?= isset($filters['status_berkas']) && $filters['status_berkas'] ? '&status=' . $filters['status_berkas'] : '' ?><?= isset($filters['nomor_perkara']) && $filters['nomor_perkara'] ? '&nomor=' . $filters['nomor_perkara'] : '' ?>">
                                                                    <i class="fas fa-angle-right"></i>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal Tambah Berkas -->
<div class="modal fade" id="newBerkasModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">
                    <i class="fas fa-plus"></i> Tambah Berkas Masuk Baru
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="newBerkasForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nomor Perkara *</label>
                        <select name="nomor_perkara" id="nomorPerkaraSelect" class="form-control" required>
                            <option value="">Pilih Nomor Perkara...</option>
                        </select>
                        <small class="form-text text-muted">Pilih dari daftar perkara yang sudah putus</small>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Putusan *</label>
                        <input type="date" name="tanggal_putusan" id="tanggalPutusan" class="form-control" readonly required>
                        <small class="form-text text-muted">Otomatis terisi dari data SIPP</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Perkara</label>
                                <input type="text" name="jenis_perkara" id="jenisPerkara" class="form-control" readonly>
                                <small class="form-text text-muted">Otomatis terisi dari data SIPP</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Majelis Hakim</label>
                        <input type="text" name="majelis_hakim" id="majelisHakim" class="form-control" readonly>
                        <small class="form-text text-muted">Otomatis terisi dari data SIPP</small>
                    </div>

                    <div class="form-group">
                        <label>Panitera Pengganti</label>
                        <input type="text" name="panitera_pengganti" id="paniteraPengganti" class="form-control" readonly>
                        <small class="form-text text-muted">Otomatis terisi dari data SIPP</small>
                    </div>

                    <div class="form-group">
                        <label>Catatan Notelen</label>
                        <textarea name="catatan_notelen" class="form-control" rows="3"
                            placeholder="Catatan khusus untuk notelen..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Berkas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk SweetAlert2 -->
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

    function deleteBerkas(berkas_id, nomor_perkara) {
        Swal.fire({
            title: 'Hapus Berkas?',
            text: 'Apakah Anda yakin ingin menghapus berkas ' + nomor_perkara + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simple approach - just redirect with delete parameter
                window.location.href = '<?= base_url("notelen/ajax_delete_berkas") ?>?id=' + berkas_id + '&redirect=1';
            }
        });
    }
</script>

<?php $this->load->view('template/new_footer'); ?>