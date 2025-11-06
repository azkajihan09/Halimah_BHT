# ANALISIS SISTEM PERINGATAN URGENT BHT

## 🚨 MASALAH YANG DITEMUKAN

### 1. **Inkonsistensi Kriteria Urgent**

#### A. Di `get_pengingat_urgent()`: ❌ SALAH
```php
$this->db->where('pjs.tanggal_sidang IS NULL');      // Belum ada PBT
$this->db->where('pp.tanggal_bht IS NULL');          // Belum ada BHT  
$this->db->where('DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10'); // ❌ Kondisi KONTRADIKSI!
```
**MASALAH:** Kondisi `pjs.tanggal_sidang IS NULL` dan `DATEDIFF(pjs.tanggal_sidang) > 10` BERTENTANGAN!

#### B. Di `get_perkara_putus_tanpa_pbt()`: ✅ BENAR
```php
CASE 
    WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'CRITICAL'
    WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'KRITIS'  
    WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10 THEN 'PERINGATAN'
    ELSE 'NORMAL'
END as level_peringatan
```

#### C. Di `get_urgent_reminders()`: ❌ TIDAK KONSISTEN
```php
// Cases without PBT after more than 7 days - ❌ Berbeda dari standar 10-14 hari
$overdue_pbt = $this->Menu_baru_model->get_overdue_pbt_cases(7, $tahun_filter);

// Cases with PBT but no BHT after more than 14 days - ✅ Konsisten
$overdue_bht = $this->Menu_baru_model->get_overdue_bht_cases(14, $tahun_filter);
```

### 2. **Standar Sistem BHT yang Benar**
Berdasarkan diskusi sebelumnya:
- **NORMAL**: 0-10 hari sejak putusan
- **PERINGATAN**: 11-14 hari sejak putusan  
- **KRITIS**: 15-21 hari sejak putusan
- **CRITICAL**: >21 hari sejak putusan

## 🔧 SOLUSI YANG DIPERLUKAN

### 1. **Perbaiki fungsi `get_pengingat_urgent()`**
- Hapus kondisi kontradiksi 
- Sesuaikan dengan standar level peringatan
- Fokus pada perkara tanpa PBT > 10 hari sejak putusan

### 2. **Harmonisasi parameter `get_urgent_reminders()`**
- Ubah threshold PBT dari 7 hari ke 10 hari (sesuai standar PERINGATAN)
- Konsistensi dengan sistem level peringatan yang sudah ada

### 3. **Update logika peringatan urgent**
- Urgent = PERINGATAN + KRITIS + CRITICAL (> 10 hari)
- Fokus pada perkara yang butuh tindakan segera

## 📊 IMPACT ANALYSIS
- ✅ Konsistensi sistem peringatan
- ✅ Akurasi alert urgent
- ✅ Mengurangi false positive
- ✅ Sesuai dengan diskusi requirement sebelumnya

---
**Status:** PERLU PERBAIKAN SEGERA untuk konsistensi sistem
