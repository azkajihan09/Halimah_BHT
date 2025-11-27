<?php

/**
 * Test file untuk demonstrasi fungsi detail dan edit PBT
 * File ini menunjukkan implementasi modal detail dan edit untuk berkas PBT
 */

// Include jQuery dan AdminLTE untuk styling
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PBT Detail & Edit Functions</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }

        .demo-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn-group .btn {
            margin: 0 2px;
        }

        .info-box {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #e3e6f0;
        }

        .info-box-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 5px;
            margin-right: 15px;
            color: white;
            font-size: 24px;
        }

        .info-box-content {
            flex: 1;
        }

        .info-box-text {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
        }

        .info-box-number {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <h2><i class="fas fa-gavel"></i> Test Fungsi Detail & Edit PBT</h2>

        <!-- Demo Data PBT -->
        <div class="demo-section">
            <h4><i class="fas fa-list"></i> Demo Data Berkas PBT</h4>
            <p>Klik tombol action untuk melihat fungsi detail dan edit.</p>

            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th>Nomor Perkara</th>
                        <th>Tanggal Putusan</th>
                        <th>Jenis Perkara</th>
                        <th>Status Proses</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>123/Pdt.G/2024/PA.Jkt</td>
                        <td>15/11/2024</td>
                        <td>Perceraian</td>
                        <td><span class="badge badge-warning">Belum PBT</span></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info" onclick="testPbtDetail(1)" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="testEditPbt(1)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="testDeletePbt(1)" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>124/Pdt.G/2024/PA.Jkt</td>
                        <td>20/11/2024</td>
                        <td>Waris</td>
                        <td><span class="badge badge-primary">Sudah PBT Belum BHT</span></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info" onclick="testPbtDetail(2)" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="testEditPbt(2)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="testDeletePbt(2)" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>125/Pdt.G/2024/PA.Jkt</td>
                        <td>25/11/2024</td>
                        <td>Hibah</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info" onclick="testPbtDetail(3)" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="testEditPbt(3)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="testDeletePbt(3)" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Detail PBT -->
        <div class="modal fade" id="pbtDetailModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">
                            <i class="fas fa-eye"></i> Detail Berkas PBT
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-gavel"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Nomor Perkara</span>
                                        <span class="info-box-number" id="detailNomorPerkara">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tanggal Putusan</span>
                                        <span class="info-box-number" id="detailTanggalPutusan">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Perkara</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Jenis Perkara:</strong></td>
                                                        <td id="detailJenisPerkara">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status Proses:</strong></td>
                                                        <td><span class="badge" id="detailStatusProses">-</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Perkara ID SIPP:</strong></td>
                                                        <td id="detailPerkaraIdSipp">-</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Tanggal PBT:</strong></td>
                                                        <td id="detailTanggalPbt">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal BHT:</strong></td>
                                                        <td id="detailTanggalBht">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Selisih Hari:</strong></td>
                                                        <td><span class="badge" id="detailSelisihHari">-</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-users"></i> Petugas</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Majelis Hakim:</strong></td>
                                                <td id="detailMajelisHakim">-</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Panitera Pengganti:</strong></td>
                                                <td id="detailPaniteraPengganti">-</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-outline card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-sticky-note"></i> Catatan</h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="detailCatatanPbt" class="text-muted">Tidak ada catatan</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-clock"></i> Timeline</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Dibuat:</strong></td>
                                                        <td id="detailCreatedAt">-</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Terakhir Update:</strong></td>
                                                        <td id="detailUpdatedAt">-</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="testEditFromView()">
                            <i class="fas fa-edit"></i> Edit Data
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit PBT -->
        <div class="modal fade" id="editPbtModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h4 class="modal-title">
                            <i class="fas fa-edit"></i> Edit Berkas PBT
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editPbtForm">
                            <input type="hidden" id="editPbtId" name="id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editNomorPerkara">Nomor Perkara *</label>
                                        <input type="text" class="form-control" id="editNomorPerkara" name="nomor_perkara" required readonly>
                                        <small class="text-muted">Nomor perkara tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editJenisPerkara">Jenis Perkara</label>
                                        <input type="text" class="form-control" id="editJenisPerkara" name="jenis_perkara" readonly>
                                        <small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editTanggalPutusan">Tanggal Putusan *</label>
                                        <input type="date" class="form-control" id="editTanggalPutusan" name="tanggal_putusan" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editTanggalPbt">Tanggal PBT</label>
                                        <input type="date" class="form-control" id="editTanggalPbt" name="tanggal_pbt">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editTanggalBht">Tanggal BHT</label>
                                        <input type="date" class="form-control" id="editTanggalBht" name="tanggal_bht">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editMajelisHakim">Majelis Hakim</label>
                                        <textarea class="form-control" id="editMajelisHakim" name="majelis_hakim" rows="2" readonly></textarea>
                                        <small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editPaniteraPengganti">Panitera Pengganti</label>
                                        <input type="text" class="form-control" id="editPaniteraPengganti" name="panitera_pengganti" readonly>
                                        <small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="editCatatanPbt">Catatan PBT</label>
                                <textarea class="form-control" id="editCatatanPbt" name="catatan_pbt" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" form="editPbtForm" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sample data untuk testing
        const samplePbtData = {
            1: {
                id: 1,
                nomor_perkara: '123/Pdt.G/2024/PA.Jkt',
                jenis_perkara: 'Perceraian',
                tanggal_putusan: '2024-11-15',
                tanggal_pbt: null,
                tanggal_bht: null,
                majelis_hakim: 'Drs. H. Ahmad Marzuki, S.H., M.H.\nDrs. H. Supriyadi, S.H.\nDra. Hj. Siti Nurjanah, S.H., M.H.',
                panitera_pengganti: 'Ahmad Rifai, S.H.',
                catatan_pbt: '',
                status_proses: 'Belum PBT',
                selisih_putus_pbt: null,
                perkara_id_sipp: 'SP001',
                created_at: '2024-11-15 10:30:00',
                updated_at: '2024-11-15 10:30:00'
            },
            2: {
                id: 2,
                nomor_perkara: '124/Pdt.G/2024/PA.Jkt',
                jenis_perkara: 'Waris',
                tanggal_putusan: '2024-11-20',
                tanggal_pbt: '2024-11-25',
                tanggal_bht: null,
                majelis_hakim: 'Drs. H. Muhammad Yusuf, S.H., M.H.\nDrs. H. Bambang Hermanto, S.H.',
                panitera_pengganti: 'Siti Aminah, S.H.',
                catatan_pbt: 'PBT telah diselesaikan dengan baik',
                status_proses: 'Sudah PBT Belum BHT',
                selisih_putus_pbt: 5,
                perkara_id_sipp: 'SP002',
                created_at: '2024-11-20 14:15:00',
                updated_at: '2024-11-25 16:45:00'
            },
            3: {
                id: 3,
                nomor_perkara: '125/Pdt.G/2024/PA.Jkt',
                jenis_perkara: 'Hibah',
                tanggal_putusan: '2024-11-25',
                tanggal_pbt: '2024-11-30',
                tanggal_bht: '2024-12-05',
                majelis_hakim: 'Drs. H. Abdul Rahman, S.H., M.H.',
                panitera_pengganti: 'Muhammad Fadil, S.H.',
                catatan_pbt: 'Proses PBT dan BHT telah selesai. Berkas sudah lengkap.',
                status_proses: 'Selesai',
                selisih_putus_pbt: 5,
                perkara_id_sipp: 'SP003',
                created_at: '2024-11-25 09:20:00',
                updated_at: '2024-12-05 11:10:00'
            }
        };

        // Function untuk format date display
        function formatDate(dateString) {
            if (!dateString || dateString === '0000-00-00' || dateString === null) return '-';

            try {
                const date = new Date(dateString);
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();

                return `${day}/${month}/${year}`;
            } catch (e) {
                return dateString;
            }
        }

        // Function untuk format datetime display
        function formatDateTime(dateTimeString) {
            if (!dateTimeString || dateTimeString === '0000-00-00 00:00:00' || dateTimeString === null) return '-';

            try {
                const date = new Date(dateTimeString);
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                const hours = date.getHours().toString().padStart(2, '0');
                const minutes = date.getMinutes().toString().padStart(2, '0');

                return `${day}/${month}/${year} ${hours}:${minutes}`;
            } catch (e) {
                return dateTimeString;
            }
        }

        function testPbtDetail(pbt_id) {
            // Simulate loading
            Swal.fire({
                title: 'Memuat Detail...',
                text: 'Mengambil data PBT',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 800,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate AJAX delay
            setTimeout(() => {
                const data = samplePbtData[pbt_id];

                if (data) {
                    Swal.close();

                    // Populate modal dengan data PBT
                    $('#detailNomorPerkara').text(data.nomor_perkara || '-');
                    $('#detailTanggalPutusan').text(formatDate(data.tanggal_putusan));
                    $('#detailJenisPerkara').text(data.jenis_perkara || '-');
                    $('#detailTanggalPbt').text(formatDate(data.tanggal_pbt));
                    $('#detailTanggalBht').text(formatDate(data.tanggal_bht));
                    $('#detailMajelisHakim').html(data.majelis_hakim ? data.majelis_hakim.replace(/\n/g, '<br>') : '-');
                    $('#detailPaniteraPengganti').text(data.panitera_pengganti || '-');
                    $('#detailPerkaraIdSipp').text(data.perkara_id_sipp || '-');
                    $('#detailCreatedAt').text(formatDateTime(data.created_at));
                    $('#detailUpdatedAt').text(formatDateTime(data.updated_at));

                    // Status proses dengan badge warna
                    const statusBadge = $('#detailStatusProses');
                    statusBadge.text(data.status_proses || '-');
                    statusBadge.removeClass('badge-warning badge-primary badge-success');

                    switch (data.status_proses) {
                        case 'Belum PBT':
                            statusBadge.addClass('badge-warning');
                            break;
                        case 'Sudah PBT Belum BHT':
                            statusBadge.addClass('badge-primary');
                            break;
                        case 'Selesai':
                            statusBadge.addClass('badge-success');
                            break;
                        default:
                            statusBadge.addClass('badge-secondary');
                    }

                    // Selisih hari dengan badge warna
                    const selisihBadge = $('#detailSelisihHari');
                    if (data.selisih_putus_pbt && data.selisih_putus_pbt > 0) {
                        selisihBadge.text(data.selisih_putus_pbt + ' hari');
                        selisihBadge.removeClass('badge-info badge-danger');
                        selisihBadge.addClass(data.selisih_putus_pbt > 14 ? 'badge-danger' : 'badge-info');
                    } else {
                        selisihBadge.text('-').removeClass('badge-info badge-danger').addClass('badge-secondary');
                    }

                    // Catatan PBT
                    if (data.catatan_pbt && data.catatan_pbt.trim()) {
                        $('#detailCatatanPbt').html(data.catatan_pbt.replace(/\n/g, '<br>')).removeClass('text-muted');
                    } else {
                        $('#detailCatatanPbt').text('Tidak ada catatan').addClass('text-muted');
                    }

                    // Store ID untuk edit function
                    $('#pbtDetailModal').data('pbt-id', pbt_id);

                    // Show modal
                    $('#pbtDetailModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Ditemukan',
                        text: 'PBT tidak ditemukan atau terjadi kesalahan'
                    });
                }
            }, 800);
        }

        function testEditPbt(pbt_id) {
            // Simulate loading
            Swal.fire({
                title: 'Memuat Data...',
                text: 'Mengambil data untuk edit',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 600,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate AJAX delay
            setTimeout(() => {
                const data = samplePbtData[pbt_id];

                if (data) {
                    Swal.close();

                    // Populate form edit dengan data PBT
                    $('#editPbtId').val(data.id);
                    $('#editNomorPerkara').val(data.nomor_perkara || '');
                    $('#editJenisPerkara').val(data.jenis_perkara || '');
                    $('#editTanggalPutusan').val(data.tanggal_putusan || '');
                    $('#editTanggalPbt').val(data.tanggal_pbt || '');
                    $('#editTanggalBht').val(data.tanggal_bht || '');
                    $('#editMajelisHakim').val(data.majelis_hakim || '');
                    $('#editPaniteraPengganti').val(data.panitera_pengganti || '');
                    $('#editCatatanPbt').val(data.catatan_pbt || '');

                    // Show modal edit
                    $('#editPbtModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Ditemukan',
                        text: 'PBT tidak ditemukan atau terjadi kesalahan'
                    });
                }
            }, 600);
        }

        function testEditFromView() {
            const pbtId = $('#pbtDetailModal').data('pbt-id');
            if (pbtId) {
                $('#pbtDetailModal').modal('hide');
                setTimeout(() => {
                    testEditPbt(pbtId);
                }, 300);
            }
        }

        function testDeletePbt(pbt_id) {
            const data = samplePbtData[pbt_id];

            Swal.fire({
                title: 'Hapus PBT?',
                text: 'Apakah Anda yakin ingin menghapus PBT ' + data.nomor_perkara + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'PBT ' + data.nomor_perkara + ' berhasil dihapus (simulasi)',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Submit handler untuk form edit PBT
        $('#editPbtForm').submit(function(e) {
            e.preventDefault();

            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Memperbarui data PBT',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 1200,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate AJAX update
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data PBT berhasil diperbarui (simulasi)',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    $('#editPbtModal').modal('hide');
                });
            }, 1200);
        });

        // Demo info
        $(document).ready(function() {
            Swal.fire({
                icon: 'info',
                title: 'Demo Fungsi PBT',
                html: `
                <p>Ini adalah demo fungsi detail dan edit untuk berkas PBT.</p>
                <ul style="text-align: left;">
                    <li><strong>View Detail:</strong> Klik tombol mata untuk melihat detail lengkap</li>
                    <li><strong>Edit Data:</strong> Klik tombol edit untuk mengubah data</li>
                    <li><strong>Delete:</strong> Klik tombol hapus untuk menghapus data</li>
                </ul>
                <p><small>Data yang ditampilkan adalah data sample untuk demonstrasi.</small></p>
            `,
                confirmButtonText: 'Mengerti',
                allowOutsideClick: false
            });
        });
    </script>

</body>

</html>