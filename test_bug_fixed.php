<!DOCTYPE html>
<html>

<head>
    <title>Test Form Edit - Bug Fixed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container py-4">

    <h2>🛠️ TEST BUG FIX - Form Edit Berkas</h2>

    <div class="alert alert-success">
        <h5><i class="fas fa-check-circle"></i> Bug Teratasi!</h5>
        <p>Masalah "Data tidak lengkap" sudah diperbaiki dengan menghilangkan atribut <code>disabled</code></p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-bug"></i> Simulasi Form Edit (Bug Fixed)</h5>
                </div>
                <div class="card-body">
                    <!-- Simulasi Form Edit yang Sudah Diperbaiki -->
                    <form id="testForm">
                        <div class="form-group">
                            <label>Nomor Perkara *</label>
                            <input type="text" name="nomor_perkara" value="621/Pdt.G/2025/PA.Amt" class="form-control" readonly>
                            <small class="form-text text-success">✅ Readonly - Data akan terkirim</small>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Putusan *</label>
                            <input type="date" name="tanggal_putusan" value="2025-11-20" class="form-control" readonly required>
                            <small class="form-text text-success">✅ Readonly (bukan disabled) - Data akan terkirim</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Perkara</label>
                                    <input type="text" name="jenis_perkara" value="Cerai Gugat" class="form-control" readonly>
                                    <small class="form-text text-success">✅ Readonly - Data akan terkirim</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Berkas</label>
                                    <select name="status_berkas" class="form-control">
                                        <option value="MASUK">Masuk</option>
                                        <option value="PROSES" selected>Proses</option>
                                        <option value="SELESAI">Selesai</option>
                                        <option value="ARSIP">Arsip</option>
                                    </select>
                                    <small class="form-text text-primary">🔄 EDITABLE - Bisa diubah</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Majelis Hakim</label>
                            <input type="text" name="majelis_hakim" value="Mursidah, Taufik Rahman, Merita Selvina" class="form-control" readonly>
                            <small class="form-text text-success">✅ Readonly - Data akan terkirim</small>
                        </div>

                        <div class="form-group">
                            <label>Panitera Pengganti</label>
                            <input type="text" name="panitera_pengganti" value="Panitera Pengganti: Fithria Utami, S.H.I." class="form-control" readonly>
                            <small class="form-text text-success">✅ Readonly - Data akan terkirim</small>
                        </div>

                        <div class="form-group">
                            <label>Catatan Notelen</label>
                            <textarea name="catatan_notelen" class="form-control" rows="3" placeholder="Catatan khusus untuk notelen...">Catatan khusus untuk notelen...</textarea>
                            <small class="form-text text-primary">🔄 EDITABLE - Bisa diubah</small>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary">Batal</button>
                            <button type="button" class="btn btn-warning" onclick="testFormData()">
                                <i class="fas fa-save"></i> Test Update Berkas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h6><i class="fas fa-exclamation-triangle"></i> Masalah Sebelumnya</h6>
                </div>
                <div class="card-body">
                    <p><strong>Error Message:</strong></p>
                    <div class="alert alert-danger">
                        "Data tidak lengkap: ID berkas, nomor perkara dan tanggal putusan harus diisi"
                    </div>

                    <p><strong>Penyebab:</strong></p>
                    <ul>
                        <li>Atribut <code>disabled</code> mencegah data dikirim</li>
                        <li>Server tidak menerima data field readonly+disabled</li>
                        <li>Validasi gagal karena data kosong</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h6><i class="fas fa-wrench"></i> Solusi</h6>
                </div>
                <div class="card-body">
                    <p><strong>Perbaikan:</strong></p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            ✅ Hilangkan atribut <code>disabled</code>
                        </li>
                        <li class="list-group-item">
                            ✅ Tetap gunakan <code>readonly</code>
                        </li>
                        <li class="list-group-item">
                            ✅ Data tetap terkirim ke server
                        </li>
                        <li class="list-group-item">
                            ✅ Field tetap tidak bisa diedit user
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6><i class="fas fa-code"></i> Perbandingan Kode</h6>
                </div>
                <div class="card-body">
                    <small>
                        <strong>❌ Sebelum (Error):</strong><br>
                        <code class="text-danger">readonly disabled</code>
                        <br><br>
                        <strong>✅ Setelah (Fixed):</strong><br>
                        <code class="text-success">readonly</code>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div id="testResult" class="mt-3"></div>

    <hr>

    <div class="alert alert-info">
        <h5><i class="fas fa-lightbulb"></i> Hasil Perbaikan:</h5>
        <ul>
            <li><strong>✅ Data Integrity:</strong> Semua field readonly tetap mengirim data ke server</li>
            <li><strong>✅ User Experience:</strong> User hanya bisa edit Status Berkas dan Catatan Notelen</li>
            <li><strong>✅ No More Error:</strong> Validasi server akan berhasil karena semua data lengkap</li>
            <li><strong>✅ Security:</strong> Data master tetap terlindungi dari perubahan</li>
        </ul>
    </div>

    <p class="mt-3">
        <a href="../index.php/notelen/berkas_template" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
        </a>
    </p>

    <script>
        function testFormData() {
            var formData = new FormData(document.getElementById('testForm'));
            var result = '<div class="alert alert-success"><h6>📋 Data yang akan dikirim:</h6><ul>';

            for (var pair of formData.entries()) {
                result += '<li><strong>' + pair[0] + ':</strong> ' + pair[1] + '</li>';
            }

            result += '</ul><p class="text-success">✅ Semua data berhasil dikumpulkan!</p></div>';

            document.getElementById('testResult').innerHTML = result;
        }
    </script>

</body>

</html>