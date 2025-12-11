# IMPLEMENTASI FILTER PDT.G DAN DATE PICKER UNTUK TANGGAL MASUK NOTELEN

## OVERVIEW
Dokumen ini menjelaskan implementasi dua fitur utama yang diminta:
1. **Filter Perkara**: Hanya menampilkan perkara Pdt.G (Gugatan) dari database SIPP, tidak menampilkan Pdt.P (Penetapan)
2. **Date Picker**: Memungkinkan pengguna memilih tanggal masuk notelen secara manual saat insert berkas

## PERUBAHAN YANG DILAKUKAN

### 1. MODEL LAYER (application/models/Notelen_model.php)

#### 1.1. Method `get_perkara_putus_dropdown()`
- **Sebelum**: Menampilkan semua jenis perkara
- **Sesudah**: Ditambahkan filter `AND p.nomor_perkara LIKE 'Pdt.G%'`
- **Efek**: Dropdown di berkas_masuk_template.php hanya menampilkan perkara Pdt.G

#### 1.2. Method `get_perkara_detail_by_nomor()`
- **Sebelum**: Mengambil detail perkara tanpa filter jenis
- **Sesudah**: Ditambahkan filter `AND p.nomor_perkara LIKE 'Pdt.G%'`
- **Efek**: Validasi bahwa perkara adalah Pdt.G saat get detail

#### 1.3. Method `get_perkara_putus_harian()`
- **Sebelum**: Menampilkan semua perkara putus harian
- **Sesudah**: Ditambahkan filter `$this->sipp_db->like('p.nomor_perkara', 'Pdt.G', 'after')`
- **Efek**: Berkas_masuk_otomatis.php hanya menampilkan perkara Pdt.G

#### 1.4. Method `insert_berkas_from_perkara_otomatis()`
- **Sebelum**: Parameter: `($perkara_id, $nomor_perkara)`
- **Sesudah**: Parameter: `($perkara_id, $nomor_perkara, $tanggal_masuk_notelen = null)`
- **Logika**: Menggunakan tanggal yang dipilih atau default hari ini

#### 1.5. Method `insert_berkas_bulk_from_perkara_otomatis()`
- **Sebelum**: Parameter: `($perkara_ids)`
- **Sesudah**: Parameter: `($perkara_ids, $tanggal_masuk_notelen = null)`
- **Tambahan Filter**: Query nomor perkara ditambahkan `AND nomor_perkara LIKE 'Pdt.G%'`
- **Logika**: Menggunakan tanggal yang dipilih atau default hari ini

### 2. CONTROLLER LAYER (application/controllers/Notelen.php)

#### 2.1. Method `ajax_masukkan_berkas_otomatis()`
- **Tambahan Parameter**: `$tanggal_masuk_notelen` dari POST data
- **Update Call**: Memanggil model dengan parameter tanggal tambahan

#### 2.2. Method `ajax_masukkan_berkas_bulk()`
- **Tambahan Parameter**: `$tanggal_masuk_notelen` dari POST data
- **Update Call**: Memanggil model dengan parameter tanggal tambahan

### 3. VIEW LAYER (application/views/notelen/berkas_masuk_otomatis.php)

#### 3.1. Modal Date Picker
```html
<div class="modal fade" id="modalPilihTanggal">
    <!-- Modal untuk pilih tanggal masuk notelen -->
    <input type="date" id="tanggalMasukNotelen">
</div>
```

#### 3.2. JavaScript Functions Update

**Function `masukkanKeBerkasLangsung()`**:
- **Sebelum**: Langsung konfirmasi dengan SweetAlert
- **Sesudah**: Menampilkan modal date picker terlebih dahulu

**Function `masukkanSemuaBerkas()`**:
- **Sebelum**: Langsung konfirmasi dengan SweetAlert
- **Sesudah**: Menampilkan modal date picker terlebih dahulu

**Function `prosesmasukkanKeBerkas()`**:
- **Tambahan Parameter**: `tanggal_masuk_notelen`
- **Update AJAX**: Mengirim tanggal yang dipilih ke server

**Function `prosesMasukkanSemuaBerkas()`**:
- **Tambahan Parameter**: `tanggal_masuk_notelen` 
- **Update AJAX**: Mengirim tanggal yang dipilih ke server

#### 3.3. Event Handler Baru
```javascript
$(document).on('click', '#btnKonfirmasiMasukkan', function() {
    // Handler untuk konfirmasi pilihan tanggal
    // Mengambil tanggal dan melakukan operasi sesuai context (single/bulk)
});
```

## CARA KERJA FITUR BARU

### 1. Filter Pdt.G
1. User mengakses berkas_masuk_template.php atau berkas_masuk_otomatis.php
2. Sistem secara otomatis hanya menampilkan perkara dengan nomor yang dimulai "Pdt.G"
3. Perkara "Pdt.P" (Penetapan) tidak akan muncul dalam daftar

### 2. Date Picker untuk Insert Berkas

#### Workflow Single Insert:
1. User klik tombol "Masukkan" di baris perkara
2. Modal date picker muncul dengan tanggal hari ini sebagai default
3. User dapat:
   - Menggunakan tanggal default (hari ini)
   - Memilih tanggal lain
   - Kosongkan field untuk menggunakan tanggal hari ini
4. Klik "Masukkan ke Berkas"
5. Data tersimpan dengan tanggal yang dipilih

#### Workflow Bulk Insert:
1. User klik tombol "Masukkan Semua ke Berkas"
2. Modal date picker muncul dengan tanggal hari ini sebagai default
3. User dapat memilih tanggal yang sama untuk semua berkas
4. Klik "Masukkan ke Berkas"
5. Semua data tersimpan dengan tanggal yang sama

## VALIDASI DAN KEAMANAN

### 1. Validasi Filter Pdt.G
- Validasi di level database (LIKE query)
- Validasi di level model (multiple checkpoint)
- Validasi di level bulk insert

### 2. Validasi Date Input
- Date picker HTML5 memberikan validasi format otomatis
- Backend menggunakan date atau NULL (fallback ke today)
- Tidak ada validasi range tanggal (user bebas pilih tanggal)

## EFEK PADA SISTEM EXISTING

### 1. Berkas yang Sudah Ada
- Berkas yang sudah tersimpan dengan jenis Pdt.P tetap ada di database
- Berkas tersebut masih bisa dilihat di berkas_masuk_template dengan filter nomor perkara
- Tidak ada data yang hilang

### 2. Backward Compatibility
- Method model tetap bisa dipanggil tanpa parameter tanggal (default hari ini)
- View lain yang menggunakan method yang sama tetap berfungsi normal

## TESTING YANG DIREKOMENDASIKAN

### 1. Test Filter Pdt.G
- [ ] Akses berkas_masuk_otomatis dengan tanggal yang ada Pdt.G dan Pdt.P
- [ ] Pastikan hanya Pdt.G yang muncul
- [ ] Test dropdown di berkas_masuk_template
- [ ] Test search function

### 2. Test Date Picker
- [ ] Insert single berkas dengan tanggal hari ini
- [ ] Insert single berkas dengan tanggal kemarin
- [ ] Insert single berkas dengan tanggal masa depan
- [ ] Insert bulk berkas dengan tanggal custom
- [ ] Test dengan field tanggal kosong

### 3. Test Edge Cases
- [ ] Insert berkas Pdt.P manual (seharusnya tetap bisa jika langsung input nomor)
- [ ] Test dengan tanggal invalid
- [ ] Test dengan koneksi database lemah

## NOTES PENTING

1. **Filter Pdt.G Global**: Semua query perkara putus sekarang terfilter Pdt.G
2. **Date Picker Opsional**: User tetap bisa kosongkan untuk gunakan tanggal hari ini
3. **No Breaking Changes**: Fitur lama tetap bekerja, hanya ditambah fitur baru
4. **Info Alert**: Modal menampilkan info bahwa hanya Pdt.G yang akan diproses

---
**Dokumen dibuat pada**: Desember 2025  
**Status**: Implementasi Selesai ✅  
**Testing**: Pending User Validation
