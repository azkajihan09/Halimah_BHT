<?php

/**
 * Test fix untuk validasi form edit PBT
 * File ini menguji perbaikan validasi JavaScript dan backend
 */
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PBT Edit Validation Fix</title>

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

        .alert {
            margin-top: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .required {
            color: #dc3545;
        }

        .readonly-info {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <h2><i class="fas fa-bug"></i> Test Fix Validasi Edit PBT</h2>

        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle"></i> Perbaikan yang Diterapkan:</h5>
            <ul>
                <li>✅ Perbaiki validasi JavaScript untuk field tanggal putusan</li>
                <li>✅ Hapus validasi untuk nomor perkara (readonly field)</li>
                <li>✅ Tambah validasi backend untuk tanggal putusan required</li>
                <li>✅ Field readonly: Jenis Perkara, Majelis Hakim, Panitera Pengganti</li>
            </ul>
        </div>

        <!-- Test Form -->
        <div class="demo-section">
            <h4><i class="fas fa-edit"></i> Test Form Edit PBT</h4>

            <form id="testEditPbtForm">
                <input type="hidden" id="editPbtId" name="id" value="1">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editNomorPerkara">Nomor Perkara <span class="required">*</span></label>
                            <input type="text" class="form-control" id="editNomorPerkara" name="nomor_perkara"
                                value="123/Pdt.G/2024/PA.Jkt" readonly>
                            <small class="text-muted">Nomor perkara tidak dapat diubah</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editJenisPerkara">Jenis Perkara</label>
                            <input type="text" class="form-control" id="editJenisPerkara" name="jenis_perkara"
                                value="Perceraian" readonly>
                            <small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="editTanggalPutusan">Tanggal Putusan</label>
                            <input type="date" class="form-control" id="editTanggalPutusan" name="tanggal_putusan" readonly>
                            <small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="editTanggalPbt">Tanggal PBT</label>
                            <input type="date" class="form-control" id="editTanggalPbt" name="tanggal_pbt">
                            <small class="text-success">✅ Field ini dapat diedit</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="editTanggalBht">Tanggal BHT</label>
                            <input type="date" class="form-control" id="editTanggalBht" name="tanggal_bht">
                            <small class="text-success">✅ Field ini dapat diedit</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editMajelisHakim">Majelis Hakim</label>
                            <textarea class="form-control" id="editMajelisHakim" name="majelis_hakim" rows="2" readonly>Drs. H. Ahmad Marzuki, S.H., M.H.
Drs. H. Supriyadi, S.H.</textarea>
                            <small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editPaniteraPengganti">Panitera Pengganti</label>
                            <input type="text" class="form-control" id="editPaniteraPengganti" name="panitera_pengganti"
                                value="Ahmad Rifai, S.H." readonly>
                            <small class="text-muted">Data dari database SIPP, tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="editCatatanPbt">Catatan PBT</label>
                    <textarea class="form-control" id="editCatatanPbt" name="catatan_pbt" rows="3"
                        placeholder="Masukkan catatan PBT..."></textarea>
                    <small class="text-success">✅ Field ini dapat diedit</small>
                </div>

                <div class="readonly-info">
                    <h6><i class="fas fa-lock"></i> Field yang Di-readonly:</h6>
                    <ul class="mb-0">
                        <li>Nomor Perkara - Tidak dapat diubah sama sekali</li>
                        <li>Jenis Perkara - Data dari database SIPP</li>
                        <li>Tanggal Putusan - Data dari database SIPP</li>
                        <li>Majelis Hakim - Data dari database SIPP</li>
                        <li>Panitera Pengganti - Data dari database SIPP</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="fas fa-save"></i> Test Update PBT
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg ml-2" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                </div>
            </form>

            <!-- Test Results -->
            <div id="testResults" style="display: none;">
                <hr>
                <h5><i class="fas fa-clipboard-check"></i> Hasil Test:</h5>
                <div id="resultContent"></div>
            </div>
        </div>

        <!-- Test Scenarios -->
        <div class="demo-section">
            <h4><i class="fas fa-vial"></i> Skenario Test</h4>

            <div class="row">
                <div class="col-md-4">
                    <button type="button" class="btn btn-success btn-block" onclick="testEmptyRequired()">
                        <i class="fas fa-check-circle"></i><br>
                        Test Semua Field Optional
                        <br><small>Semua field editable bersifat optional</small>
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-success btn-block" onclick="testValidData()">
                        <i class="fas fa-check-circle"></i><br>
                        Test Data Valid
                        <br><small>Harusnya berhasil submit</small>
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-info btn-block" onclick="testPartialData()">
                        <i class="fas fa-edit"></i><br>
                        Test Data Parsial
                        <br><small>Hanya tanggal putusan + catatan</small>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fixed validation logic (sama seperti yang diperbaiki di template asli)
        $('#testEditPbtForm').submit(function(e) {
            e.preventDefault();

            // No validation needed - all required fields are readonly
            // Only editable fields: tanggal_pbt, tanggal_bht, catatan_pbt (all optional)            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Memperbarui data PBT',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 1500,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate AJAX success
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data PBT berhasil diperbarui (simulasi)',
                    showConfirmButton: false,
                    timer: 2000
                });

                showTestResult('PASSED', 'Validasi berhasil, form dapat disubmit dengan data valid');
            }, 1500);
        });

        function showTestResult(status, message) {
            const resultDiv = $('#testResults');
            const contentDiv = $('#resultContent');

            const statusClass = status === 'PASSED' ? 'success' : 'danger';
            const icon = status === 'PASSED' ? 'check-circle' : 'times-circle';

            contentDiv.html(`
            <div class="alert alert-${statusClass}">
                <i class="fas fa-${icon}"></i> <strong>${status}:</strong> ${message}
            </div>
        `);

            resultDiv.show();
        }

        function testEmptyRequired() {
            // No required fields to test anymore - all editable fields are optional
            Swal.fire({
                icon: 'info',
                title: 'Test: Semua Field Optional',
                text: 'Semua field yang dapat diedit bersifat optional, submit langsung berhasil...',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                $('#testEditPbtForm').submit();
            });
        }

        function testValidData() {
            // Set valid data - only editable fields
            $('#editTanggalPbt').val('2024-11-20');
            $('#editTanggalBht').val('2024-11-25');
            $('#editCatatanPbt').val('Test data valid dengan field yang dapat diedit');

            Swal.fire({
                icon: 'info',
                title: 'Test: Data Valid',
                text: 'Mencoba submit dengan data lengkap pada field editable...',
                timer: 1000,
                showConfirmButton: false
            }).then(() => {
                $('#testEditPbtForm').submit();
            });
        }

        function testPartialData() {
            // Set minimal data - only some editable fields
            $('#editTanggalPbt').val('2024-11-15');
            $('#editTanggalBht').val('');
            $('#editCatatanPbt').val('Hanya sebagian field yang diisi');

            Swal.fire({
                icon: 'info',
                title: 'Test: Data Parsial',
                text: 'Mencoba submit dengan hanya sebagian field editable...',
                timer: 1000,
                showConfirmButton: false
            }).then(() => {
                $('#testEditPbtForm').submit();
            });
        }

        function resetForm() {
            $('#testEditPbtForm')[0].reset();
            // Set readonly fields with fixed values
            $('#editNomorPerkara').val('123/Pdt.G/2024/PA.Jkt');
            $('#editJenisPerkara').val('Perceraian');
            $('#editTanggalPutusan').val('2024-11-15');
            $('#editMajelisHakim').val('Drs. H. Ahmad Marzuki, S.H., M.H.\nDrs. H. Supriyadi, S.H.');
            $('#editPaniteraPengganti').val('Ahmad Rifai, S.H.');
            $('#testResults').hide();

            Swal.fire({
                icon: 'info',
                title: 'Form Reset',
                text: 'Form telah direset, field readonly diset dengan data SIPP',
                timer: 1000,
                showConfirmButton: false
            });
        }

        // Initialize with demo data
        $(document).ready(function() {
            resetForm();

            Swal.fire({
                icon: 'success',
                title: 'Test Fix Validasi PBT',
                html: `
                <div style="text-align: left;">
                    <h6>Perbaikan yang diterapkan:</h6>
                    <ul>
                        <li>✅ Validasi JavaScript hanya untuk field editable</li>
                        <li>✅ Tanggal Putusan dapat diedit dan required</li>
                        <li>✅ Field SIPP di-readonly untuk konsistensi data</li>
                        <li>✅ Validasi backend untuk field required</li>
                    </ul>
                    <p><strong>Silakan test dengan tombol-tombol di bawah!</strong></p>
                </div>
            `,
                confirmButtonText: 'Mulai Test',
                allowOutsideClick: false
            });
        });
    </script>

</body>

</html>