<!DOCTYPE html>
<html>

<head>
    <title>Test Form Edit - Field Readonly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container py-4">

    <h2>🔒 TEST FIELD READONLY DI FORM EDIT</h2>

    <div class="alert alert-success">
        <h5><i class="fas fa-check-circle"></i> Perbaikan Field Readonly Berhasil</h5>
        <p>Field-field berikut telah dinonaktifkan di form edit berkas:</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5><i class="fas fa-edit"></i> Preview Form Edit Berkas</h5>
                </div>
                <div class="card-body">
                    <!-- Simulasi Form Edit -->
                    <form>
                        <div class="form-group">
                            <label>Nomor Perkara *</label>
                            <input type="text" value="621/Pdt.G/2025/PA.Amt" class="form-control" readonly>
                            <small class="form-text text-muted">Nomor perkara tidak dapat diubah</small>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Putusan * <span class="badge badge-danger">READONLY</span></label>
                            <input type="date" value="2025-11-20" class="form-control" readonly disabled>
                            <small class="form-text text-muted">Tanggal putusan tidak dapat diubah</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Perkara <span class="badge badge-danger">READONLY</span></label>
                                    <input type="text" value="Cerai Gugat" class="form-control" readonly disabled>
                                    <small class="form-text text-muted">Jenis perkara tidak dapat diubah</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Berkas <span class="badge badge-success">EDITABLE</span></label>
                                    <select class="form-control">
                                        <option value="MASUK">Masuk</option>
                                        <option value="PROSES" selected>Proses</option>
                                        <option value="SELESAI">Selesai</option>
                                        <option value="ARSIP">Arsip</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Majelis Hakim <span class="badge badge-danger">READONLY</span></label>
                            <input type="text" value="Mursidah</br>Taufik Rahman</br>Merita Selvina" class="form-control" readonly disabled>
                            <small class="form-text text-muted">Majelis hakim tidak dapat diubah</small>
                        </div>

                        <div class="form-group">
                            <label>Panitera Pengganti <span class="badge badge-danger">READONLY</span></label>
                            <input type="text" value="Panitera Pengganti: Fithria Utami, S.H.I." class="form-control" readonly disabled>
                            <small class="form-text text-muted">Panitera pengganti tidak dapat diubah</small>
                        </div>

                        <div class="form-group">
                            <label>Catatan Notelen <span class="badge badge-success">EDITABLE</span></label>
                            <textarea class="form-control" rows="3" placeholder="Catatan khusus untuk notelen...">Catatan khusus untuk notelen...</textarea>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary">Batal</button>
                            <button type="button" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update Berkas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6><i class="fas fa-info-circle"></i> Status Field</h6>
                </div>
                <div class="card-body">
                    <h6>🔒 Field Readonly (Nonaktif):</h6>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            Nomor Perkara
                            <span class="badge badge-secondary">Always Readonly</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Tanggal Putusan
                            <span class="badge badge-danger">NEW: Readonly</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Jenis Perkara
                            <span class="badge badge-danger">NEW: Readonly</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Majelis Hakim
                            <span class="badge badge-danger">NEW: Readonly</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Panitera Pengganti
                            <span class="badge badge-danger">NEW: Readonly</span>
                        </li>
                    </ul>

                    <h6>✏️ Field Editable (Aktif):</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Status Berkas
                            <span class="badge badge-success">Editable</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Catatan Notelen
                            <span class="badge badge-success">Editable</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-dark text-white">
                    <h6><i class="fas fa-code"></i> Implementasi</h6>
                </div>
                <div class="card-body">
                    <small>
                        <strong>HTML Attributes:</strong><br>
                        <code>readonly disabled</code>
                        <br><br>
                        <strong>JavaScript:</strong><br>
                        <code>.prop('readonly', true)<br>.prop('disabled', true)</code>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="alert alert-info">
        <h5><i class="fas fa-lightbulb"></i> Catatan Penting:</h5>
        <ul>
            <li><strong>Data Integritas:</strong> Field yang dinonaktifkan masih akan mengirim data ke server saat update</li>
            <li><strong>User Experience:</strong> User hanya bisa mengubah Status Berkas dan Catatan Notelen</li>
            <li><strong>Konsistensi Data:</strong> Data master dari SIPP tetap terjaga</li>
        </ul>
    </div>

    <p class="mt-3">
        <a href="../index.php/notelen/berkas_template" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
        </a>
    </p>

</body>

</html>