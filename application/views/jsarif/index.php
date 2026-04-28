<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-print mr-2"></i> <?= $page_title ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a class="btn btn-success btn-sm" href="<?= site_url('jsarif/export_excel') ?>">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>
                    <button class="btn btn-danger btn-sm" type="button" onclick="exportPDF()">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div id="jsarifAlert" class="alert d-none" role="alert"></div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-keyboard mr-1"></i> Input Data (Gunakan Enter)</h3>
                </div>
                <form id="formTambah" autocomplete="off">
                    <input type="hidden" name="id" id="perkara_id">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group position-relative">
                                    <label>No. Perkara</label>
                                    <input type="text" name="no_perkara" id="input_no_perkara" class="form-control nav-input" placeholder="Ketik nomor (mis. 245)" autocomplete="off" required>
                                    <div id="suggestPerkara" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1050; max-height: 260px; overflow-y: auto; display: none;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Klasifikasi</label>
                                    <select name="klasifikasi" class="form-control nav-input" id="kategori" onchange="filterJenis()">
                                        <option value="Gugatan">Gugatan</option>
                                        <option value="Permohonan">Permohonan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <select name="jenis" id="jenis_perkara" class="form-control nav-input"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Para Pihak</label>
                                    <input type="text" name="pihak" class="form-control nav-input" placeholder="Nama P vs T" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Alamat Objek/Pihak</label>
                                    <input type="text" name="alamat" class="form-control nav-input" placeholder="Jl. Raya..." required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" id="btnSimpan"><i class="fas fa-save mr-1"></i> Simpan</button>
                    </div>
                </form>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0" id="tabelPerkara">
                            <thead class="bg-light">
                                <tr>
                                    <th>No. Perkara</th>
                                    <th>Jenis</th>
                                    <th>Pihak</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Update</th>
                                    <th style="width: 90px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header navbar-green text-white">
                <h4 class="modal-title"><i class="fas fa-tasks mr-2"></i> Update Status</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select id="selectStatus" class="form-control form-control-lg nav-input">
                    <?php foreach ($status_options as $status_option): ?>
                        <option value="<?= htmlspecialchars($status_option, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($status_option, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-block" onclick="simpanStatus()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<style>
    .status-clickable {
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .status-clickable:hover {
        transform: scale(1.05);
    }

    .form-control:focus {
        border-color: #046354;
        box-shadow: 0 0 5px rgba(4, 99, 84, 0.5);
    }

    #tabelPerkara td,
    #tabelPerkara th {
        vertical-align: middle;
    }

    #jsarifAlert {
        position: sticky;
        top: 1rem;
        z-index: 1030;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
    const dataJenis = {
        Gugatan: ['Cerai Gugat', 'Cerai Talak', 'Gugatan Harta Bersama', 'Hadhanah', 'Nafkah', 'Wali Adhal', 'Gugatan Waris', 'Ekonomi Syariah', 'Lain-lain'],
        Permohonan: ['Dispensasi kawin', 'Isbat nikah', 'Izin poligami', 'Penetapan ahli waris', 'Perwalian anak', 'Pengampuan', 'Lain-lain']
    };

    const badgeClassMap = {
        'Dipanggil': 'badge-warning',
        'Sidang Pertama': 'badge-primary',
        'Sidang Lanjutan': 'badge-info',
        'Putusan': 'badge-success',
        'PBT': 'badge-danger'
    };

    const endpoints = {
        list: '<?= site_url('jsarif/ajax_list') ?>',
        save: '<?= site_url('jsarif/ajax_save') ?>',
        updateStatus: '<?= site_url('jsarif/ajax_update_status') ?>',
        delete: '<?= site_url('jsarif/ajax_delete') ?>',
        searchPerkara: '<?= site_url('jsarif/ajax_search_perkara') ?>'
    };

    let suggestRequest = null;
    let suggestTimer = null;

    function fetchSuggestions(keyword) {
        const box = $('#suggestPerkara');
        if (!keyword || keyword.length < 2) {
            box.hide().empty();
            return;
        }

        if (suggestRequest && suggestRequest.readyState !== 4) {
            suggestRequest.abort();
        }

        suggestRequest = $.ajax({
            url: endpoints.searchPerkara,
            method: 'GET',
            dataType: 'json',
            data: { q: keyword }
        }).done(function(response) {
            const items = (response && response.data) || [];
            if (!items.length) {
                box.html('<div class="list-group-item text-muted small">Tidak ada nomor perkara yang cocok</div>').show();
                return;
            }

            let html = '';
            items.forEach(function(item) {
                const jenis = item.jenis_perkara_nama || '';
                const klas = item.klasifikasi || '';
                html += '<button type="button" class="list-group-item list-group-item-action py-2 px-3 suggest-item"' +
                    ' data-no="' + escapeHtml(item.nomor_perkara) + '"' +
                    ' data-jenis="' + escapeHtml(jenis) + '"' +
                    ' data-klasifikasi="' + escapeHtml(klas) + '">' +
                    '<div><b>' + escapeHtml(item.nomor_perkara) + '</b></div>' +
                    '<small class="text-muted">' + escapeHtml(jenis) + (klas ? ' &middot; ' + escapeHtml(klas) : '') + '</small>' +
                    '</button>';
            });
            box.html(html).show();
        });
    }

    $(document).on('input', '#input_no_perkara', function() {
        const keyword = $(this).val();
        window.clearTimeout(suggestTimer);
        suggestTimer = window.setTimeout(function() {
            fetchSuggestions(keyword);
        }, 250);
    });

    $(document).on('click', '.suggest-item', function() {
        const $this = $(this);
        const no = $this.data('no');
        const jenis = $this.data('jenis');
        const klas = $this.data('klasifikasi');

        $('#input_no_perkara').val(no);
        if (klas === 'Gugatan' || klas === 'Permohonan') {
            $('#kategori').val(klas);
        }
        filterJenis(jenis);
        $('#suggestPerkara').hide().empty();
        $('input[name="pihak"]').focus();
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#input_no_perkara, #suggestPerkara').length) {
            $('#suggestPerkara').hide();
        }
    });

    let daftarPerkara = [];
    let activeStatusId = null;

    $(document).on('keydown', '.nav-input', function(event) {
        if (event.which === 13) {
            event.preventDefault();
            const inputs = $('.nav-input:visible');
            const currentIndex = inputs.index(this);
            if (currentIndex === inputs.length - 1) {
                $('#formTambah').trigger('submit');
                return;
            }
            inputs.eq(currentIndex + 1).focus();
        }
    });

    function filterJenis(selectedValue) {
        const klasifikasi = $('#kategori').val();
        const jenisSelect = $('#jenis_perkara');
        const options = dataJenis[klasifikasi] || [];

        jenisSelect.empty();
        options.forEach(function(item) {
            const isSelected = selectedValue && selectedValue === item ? ' selected' : '';
            jenisSelect.append('<option value="' + escapeHtml(item) + '"' + isSelected + '>' + escapeHtml(item) + '</option>');
        });
    }

    function loadData() {
        $.getJSON(endpoints.list)
            .done(function(response) {
                daftarPerkara = response.data || [];
                renderTabel();
            })
            .fail(function() {
                showAlert('Gagal memuat data perkara dari server', 'danger');
            });
    }

    function renderTabel() {
        const tbody = $('#tabelPerkara tbody');
        if (!daftarPerkara.length) {
            tbody.html('<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data perkara</td></tr>');
            return;
        }

        let rows = '';
        daftarPerkara.forEach(function(item) {
            const badgeClass = badgeClassMap[item.status] || 'badge-secondary';
            rows += '<tr>' +
                '<td><b>' + escapeHtml(item.no_perkara) + '</b></td>' +
                '<td><small>' + escapeHtml(item.jenis) + '</small></td>' +
                '<td>' + escapeHtml(item.pihak) + '</td>' +
                '<td><small>' + escapeHtml(item.alamat) + '</small></td>' +
                '<td><span class="badge ' + badgeClass + ' status-clickable p-2" onclick="openStatus(' + item.id + ')">' + escapeHtml(item.status) + '</span></td>' +
                '<td><small>' + escapeHtml(item.last_update_label || '-') + '</small></td>' +
                '<td>' +
                '<button class="btn btn-xs btn-info mr-1" type="button" onclick="prepareEdit(' + item.id + ')"><i class="fas fa-edit"></i></button>' +
                '<button class="btn btn-xs btn-danger" type="button" onclick="hapusData(' + item.id + ')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
                '</tr>';
        });

        tbody.html(rows);
    }

    $('#formTambah').on('submit', function(event) {
        event.preventDefault();
        const form = $(this);

        $.ajax({
            url: endpoints.save,
            method: 'POST',
            dataType: 'json',
            data: form.serialize()
        }).done(function(response) {
            showAlert(response.message, 'success');
            resetForm();
            loadData();
        }).fail(function(xhr) {
            const response = xhr.responseJSON || {};
            showAlert(response.message || 'Gagal menyimpan data perkara', 'danger');
        });
    });

    function prepareEdit(id) {
        const item = daftarPerkara.find(function(row) {
            return Number(row.id) === Number(id);
        });

        if (!item) {
            showAlert('Data perkara tidak ditemukan', 'warning');
            return;
        }

        $('#perkara_id').val(item.id);
        $('input[name="no_perkara"]').val(item.no_perkara);
        $('#kategori').val(item.klasifikasi);
        filterJenis(item.jenis);
        $('input[name="pihak"]').val(item.pihak);
        $('input[name="alamat"]').val(item.alamat);
        $('#btnSimpan').html('<i class="fas fa-sync mr-1"></i> Update');
        window.scrollTo(0, 0);
        $('.nav-input').first().focus();
    }

    function resetForm() {
        $('#formTambah')[0].reset();
        $('#perkara_id').val('');
        $('#btnSimpan').html('<i class="fas fa-save mr-1"></i> Simpan');
        $('#kategori').val('Gugatan');
        filterJenis();
        $('.nav-input').first().focus();
    }

    function openStatus(id) {
        const item = daftarPerkara.find(function(row) {
            return Number(row.id) === Number(id);
        });

        if (!item) {
            showAlert('Data perkara tidak ditemukan', 'warning');
            return;
        }

        activeStatusId = id;
        $('#selectStatus').val(item.status);
        $('#statusModal').modal('show');
    }

    function simpanStatus() {
        if (!activeStatusId) {
            showAlert('Tidak ada data yang dipilih untuk update status', 'warning');
            return;
        }

        $.ajax({
            url: endpoints.updateStatus,
            method: 'POST',
            dataType: 'json',
            data: {
                id: activeStatusId,
                status: $('#selectStatus').val()
            }
        }).done(function(response) {
            $('#statusModal').modal('hide');
            showAlert(response.message, 'success');
            activeStatusId = null;
            loadData();
        }).fail(function(xhr) {
            const response = xhr.responseJSON || {};
            showAlert(response.message || 'Gagal memperbarui status', 'danger');
        });
    }

    function hapusData(id) {
        if (!window.confirm('Hapus data?')) {
            return;
        }

        $.ajax({
            url: endpoints.delete,
            method: 'POST',
            dataType: 'json',
            data: {
                id: id
            }
        }).done(function(response) {
            showAlert(response.message, 'success');
            loadData();
        }).fail(function(xhr) {
            const response = xhr.responseJSON || {};
            showAlert(response.message || 'Gagal menghapus data perkara', 'danger');
        });
    }

    function exportPDF() {
        const rows = daftarPerkara.map(function(item) {
            return [item.no_perkara, item.jenis, item.pihak, item.alamat, item.status, item.last_update_label || '-'];
        });

        const jsPDF = window.jspdf.jsPDF;
        const doc = new jsPDF({
            orientation: 'landscape'
        });
        doc.text('Laporan Monitoring Perkara JSP', 14, 15);
        doc.autoTable({
            head: [
                ['No Perkara', 'Jenis', 'Pihak', 'Alamat', 'Status', 'Update']
            ],
            body: rows,
            startY: 20,
            styles: {
                fontSize: 8
            }
        });
        doc.save('Laporan_JSP.pdf');
    }

    function showAlert(message, type) {
        const alertBox = $('#jsarifAlert');
        alertBox.removeClass('d-none alert-success alert-danger alert-warning alert-info');
        alertBox.addClass('alert-' + type).text(message);
        window.setTimeout(function() {
            alertBox.addClass('d-none').text('');
        }, 3000);
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    $(document).ready(function() {
        filterJenis();
        loadData();
    });
</script>