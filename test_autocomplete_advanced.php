<!DOCTYPE html>
<html>

<head>
    <title>Test Autocomplete SIPP Style - Improved</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="container py-4">

    <h2>🎯 TEST AUTOCOMPLETE SIPP STYLE - IMPROVED VERSION</h2>

    <div class="alert alert-success">
        <h5><i class="fas fa-rocket"></i> Form Tambah Berkas - Premium Autocomplete!</h5>
        <p>Mengadopsi sistem autocomplete yang bagus dari berkas_pbt_template.php dengan fitur canggih!</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5><i class="fas fa-magic"></i> Form Tambah Berkas - Advanced Autocomplete</h5>
                </div>
                <div class="card-body">
                    <!-- Simulasi Form Tambah Berkas dengan Autocomplete Canggih -->
                    <form id="testForm">
                        <div class="form-group">
                            <label>Nomor Perkara * <span class="badge badge-primary">ADVANCED SEARCH</span></label>
                            <div class="input-group">
                                <input type="text" name="nomor_perkara" id="nomorPerkara" class="form-control"
                                    placeholder="Ketik nomor perkara..." required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="clearPerkara" title="Clear">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="perkaraSuggestions" class="list-group" style="position: absolute; z-index: 1050; max-height: 300px; overflow-y: auto; display: none; width: 100%; margin-top: 2px; border-radius: 0.25rem; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);"></div>
                            <input type="hidden" name="perkara_id_sipp" id="perkaraIdSipp">
                            <small class="form-text text-muted">Ketik minimal 2 karakter untuk mencari dari database SIPP</small>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Putusan *</label>
                            <input type="date" name="tanggal_putusan" id="tanggalPutusan" class="form-control" readonly required>
                            <small id="tanggalHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Perkara</label>
                                    <input type="text" name="jenis_perkara" id="jenisPerkara" class="form-control" readonly>
                                    <small id="jenisHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Berkas *</label>
                                    <select name="status_berkas" id="statusBerkas" class="form-control" required>
                                        <option value="MASUK" selected>Masuk</option>
                                        <option value="PROSES">Proses</option>
                                        <option value="SELESAI">Selesai</option>
                                        <option value="ARSIP">Arsip</option>
                                    </select>
                                    <small class="form-text text-primary">Pilih status berkas</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Majelis Hakim</label>
                            <input type="text" name="majelis_hakim" id="majelisHakim" class="form-control" readonly>
                            <small id="majelisHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
                        </div>

                        <div class="form-group">
                            <label>Panitera Pengganti</label>
                            <input type="text" name="panitera_pengganti" id="paniteraPengganti" class="form-control" readonly>
                            <small id="paniteraHelp" class="form-text text-muted">Manual input atau auto-fill dari SIPP</small>
                        </div>

                        <div class="form-group">
                            <label>Catatan Notelen</label>
                            <textarea name="catatan_notelen" class="form-control" rows="3" placeholder="Catatan khusus untuk notelen..."></textarea>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="testFormData()">
                                <i class="fas fa-save"></i> Test Simpan Berkas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6><i class="fas fa-star"></i> Premium Features</h6>
                </div>
                <div class="card-body">
                    <h6>🎯 Advanced Autocomplete:</h6>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            ✅ <strong>Beautiful UI</strong> - Styled suggestions
                        </li>
                        <li class="list-group-item">
                            ✅ <strong>Smart Search</strong> - Min 2 karakter
                        </li>
                        <li class="list-group-item">
                            ✅ <strong>Debounce</strong> - 300ms delay
                        </li>
                        <li class="list-group-item">
                            ✅ <strong>Loading State</strong> - Spinner indicator
                        </li>
                        <li class="list-group-item">
                            ✅ <strong>Clear Button</strong> - Reset dengan 1 klik
                        </li>
                        <li class="list-group-item">
                            ✅ <strong>Auto-fill Visual</strong> - Green borders
                        </li>
                        <li class="list-group-item">
                            ✅ <strong>Success Toast</strong> - SweetAlert notification
                        </li>
                        <li class="list-group-item">
                            ✅ <strong>Error Handling</strong> - User-friendly errors
                        </li>
                    </ul>

                    <h6>🎨 UI Improvements:</h6>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            ✅ Input group dengan clear button
                        </li>
                        <li class="list-group-item">
                            ✅ Styled dropdown suggestions
                        </li>
                        <li class="list-group-item">
                            ✅ Visual feedback untuk auto-fill
                        </li>
                        <li class="list-group-item">
                            ✅ Responsive design
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6><i class="fas fa-search"></i> Demo Search</h6>
                </div>
                <div class="card-body">
                    <p><small>Klik untuk test autocomplete:</small></p>
                    <button class="btn btn-sm btn-outline-primary mb-2 w-100" onclick="simulateAdvancedSearch('123')">
                        <i class="fas fa-search"></i> Test "123"
                    </button>
                    <button class="btn btn-sm btn-outline-success mb-2 w-100" onclick="simulateAdvancedSearch('Pdt')">
                        <i class="fas fa-search"></i> Test "Pdt"
                    </button>
                    <button class="btn btn-sm btn-outline-info mb-2 w-100" onclick="simulateAdvancedSearch('2024')">
                        <i class="fas fa-search"></i> Test "2024"
                    </button>
                    <button class="btn btn-sm btn-outline-warning w-100" onclick="clearForm()">
                        <i class="fas fa-eraser"></i> Clear Form
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="testResult" class="mt-3"></div>

    <hr>

    <div class="alert alert-primary">
        <h5><i class="fas fa-trophy"></i> Perbandingan Sistem:</h5>
        <table class="table table-sm table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Fitur</th>
                    <th>Sistem Lama</th>
                    <th class="text-success">Sistem Baru (berkas_pbt style)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Input Type</strong></td>
                    <td>❌ Dropdown static</td>
                    <td>✅ Input + autocomplete</td>
                </tr>
                <tr>
                    <td><strong>UI Design</strong></td>
                    <td>❌ Basic dropdown</td>
                    <td>✅ Styled suggestions + clear button</td>
                </tr>
                <tr>
                    <td><strong>Search</strong></td>
                    <td>❌ Scroll through list</td>
                    <td>✅ Smart search dengan debounce</td>
                </tr>
                <tr>
                    <td><strong>Visual Feedback</strong></td>
                    <td>❌ No indication</td>
                    <td>✅ Green borders + success toast</td>
                </tr>
                <tr>
                    <td><strong>Error Handling</strong></td>
                    <td>❌ Basic</td>
                    <td>✅ User-friendly error messages</td>
                </tr>
                <tr>
                    <td><strong>Performance</strong></td>
                    <td>❌ Load all data</td>
                    <td>✅ Load on demand dengan loading state</td>
                </tr>
                <tr>
                    <td><strong>Mobile Friendly</strong></td>
                    <td>❌ Difficult to use</td>
                    <td>✅ Touch-friendly interface</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="mt-3">
        <a href="../index.php/notelen/berkas_template" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
        </a>
    </p>

    <script>
        let isLoading = false;

        // Function to update field labels based on source (copied from berkas_pbt)
        function updateFieldLabels(fromSipp) {
            if (fromSipp) {
                $('#jenisHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
                $('#tanggalHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
                $('#majelisHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');
                $('#paniteraHelp').text('(Auto-filled dari SIPP)').removeClass('text-muted').addClass('text-success');

                // Add success icons
                $('#jenisPerkara, #tanggalPutusan, #majelisHakim, #paniteraPengganti')
                    .addClass('border-success');
            } else {
                $('#jenisHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
                $('#tanggalHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
                $('#majelisHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');
                $('#paniteraHelp').text('(Manual input atau auto-fill dari SIPP)').removeClass('text-success').addClass('text-muted');

                // Remove success styling
                $('#jenisPerkara, #tanggalPutusan, #majelisHakim, #paniteraPengganti')
                    .removeClass('border-success');
            }
        }

        // Simulasi advanced search with beautiful UI
        function simulateAdvancedSearch(searchTerm) {
            var input = $('#nomorPerkara');
            var suggestions = $('#perkaraSuggestions');

            input.val(searchTerm);
            input.focus();

            // Show loading state
            isLoading = true;
            suggestions.html(`
        <div class="list-group-item">
            <i class="fas fa-spinner fa-spin text-primary"></i> Mencari data perkara...
        </div>
    `).show();

            // Simulate AJAX delay
            setTimeout(function() {
                // Simulasi hasil search dengan UI yang bagus
                var results = [{
                        perkara_id: 1,
                        nomor_perkara: searchTerm + '/Pdt.G/2024/PA.Amt',
                        jenis_perkara: 'Cerai Gugat',
                        tanggal_putusan: '2024-11-20',
                        majelis_hakim: 'Dr. H. Ahmad Subhi, S.H., M.H., Dra. Hj. Siti Maryam, S.H., M.H., H. Muhammad Iqbal, S.H., M.H.',
                        panitera_pengganti: 'Dra. Siti Nurjanah, S.H.'
                    },
                    {
                        perkara_id: 2,
                        nomor_perkara: searchTerm + '/Pdt.P/2024/PA.Amt',
                        jenis_perkara: 'Pengesahan Nikah',
                        tanggal_putusan: '2024-11-21',
                        majelis_hakim: 'Dr. H. Ahmad Subhi, S.H., M.H., H. Taufik Rahman, S.H., M.H.',
                        panitera_pengganti: 'Fithria Utami, S.H.I.'
                    },
                    {
                        perkara_id: 3,
                        nomor_perkara: searchTerm + '/Pdt.G/2024/PA.Amt',
                        jenis_perkara: 'Cerai Talak',
                        tanggal_putusan: '2024-11-22',
                        majelis_hakim: 'Dra. Merita Selvina, S.H., M.H., H. Muhammad Iqbal, S.H., M.H.',
                        panitera_pengganti: 'Dra. Siti Nurjanah, S.H.'
                    }
                ];

                showAdvancedResults(results);
                isLoading = false;
            }, 800);
        }

        function showAdvancedResults(data) {
            var suggestions = $('#perkaraSuggestions');

            if (data.length > 0) {
                let html = '';
                data.forEach(function(item) {
                    html += `
                <a href="#" class="list-group-item list-group-item-action perkara-suggestion"
                    data-perkara-id="${item.perkara_id}"
                    data-nomor="${item.nomor_perkara}"
                    data-jenis="${item.jenis_perkara || ''}"
                    data-tanggal="${item.tanggal_putusan || ''}"
                    data-majelis="${item.majelis_hakim || ''}"
                    data-panitera="${item.panitera_pengganti || ''}">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 text-primary">${item.nomor_perkara}</h6>
                        <small class="text-muted">${item.tanggal_putusan || 'N/A'}</small>
                    </div>
                    <p class="mb-1">${item.jenis_perkara || 'Jenis tidak diketahui'}</p>
                    <small class="text-muted">
                        ${item.majelis_hakim ? 'Hakim: ' + item.majelis_hakim.substring(0, 50) + '...' : 'Data hakim tidak tersedia'}
                    </small>
                </a>
            `;
                });
                suggestions.html(html).show();
            } else {
                suggestions.html(`
            <div class="list-group-item text-muted">
                <i class="fas fa-exclamation-circle"></i> Tidak ada data perkara ditemukan
            </div>
        `).show();
            }

            // Handle item click
            $('.perkara-suggestion').off('click').on('click', function(e) {
                e.preventDefault();
                var data = $(this).data();

                // Fill form dengan data dari SIPP
                $('#perkaraIdSipp').val(data.perkaraId);
                $('#nomorPerkara').val(data.nomor).data('selected-from-sipp', true);
                $('#jenisPerkara').val(data.jenis);
                $('#tanggalPutusan').val(data.tanggal);
                $('#majelisHakim').val(data.majelis);
                $('#paniteraPengganti').val(data.panitera);

                // Update visual indicators
                updateFieldLabels(true);

                // Hide suggestions
                suggestions.hide();

                // Show success notification (berkas_pbt style)
                Swal.fire({
                    icon: 'success',
                    title: 'Data Ditemukan!',
                    text: 'Form telah diisi otomatis dengan data dari SIPP',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        }

        function clearForm() {
            $('#perkaraIdSipp').val('');
            $('#nomorPerkara').val('').data('selected-from-sipp', false);
            $('#jenisPerkara').val('');
            $('#tanggalPutusan').val('');
            $('#majelisHakim').val('');
            $('#paniteraPengganti').val('');
            $('#perkaraSuggestions').hide();

            // Update visual indicators
            updateFieldLabels(false);

            $('#nomorPerkara').focus();
        }

        function testFormData() {
            var formData = new FormData(document.getElementById('testForm'));
            var result = '<div class="alert alert-success"><h6>📋 Data yang akan dikirim:</h6><ul>';

            for (var pair of formData.entries()) {
                result += '<li><strong>' + pair[0] + ':</strong> ' + pair[1] + '</li>';
            }

            result += '</ul>';

            var selectedFromSipp = $('#nomorPerkara').data('selected-from-sipp');
            result += '<p class="text-' + (selectedFromSipp ? 'success' : 'warning') + '">✅ Data source: ' +
                (selectedFromSipp ? 'Auto-filled dari SIPP' : 'Manual input') + '</p></div>';

            document.getElementById('testResult').innerHTML = result;
        }

        // Clear button functionality
        $('#clearPerkara').click(function() {
            clearForm();
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#nomorPerkara, #perkaraSuggestions').length) {
                $('#perkaraSuggestions').hide();
            }
        });

        // Initialize on ready
        $(document).ready(function() {
            console.log('Advanced Autocomplete System loaded successfully!');
        });
    </script>

</body>

</html>