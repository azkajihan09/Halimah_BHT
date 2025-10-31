# 📊 **PENJELASAN DETAIL "HARI SEJAK PBT"**

## 🔍 **1. KONSEP DASAR**

### **Apa itu PBT?**
- **PBT** = **Pemberitahuan Berkas Telah lengkap**
- Ini adalah tahap dalam proses pengadilan setelah perkara diputus
- Setelah PBT, berkas harus diselesaikan (BHT) dalam waktu tertentu

### **Apa itu BHT?**
- **BHT** = **Berkas Harus selesai/lengkap**  
- Target waktu penyelesaian berkas setelah PBT
- Standar waktu: **7 hari** setelah PBT

---

## 🗄️ **2. STRUKTUR DATABASE**

### **Tabel yang Terlibat:**
```sql
1. perkara (p)
   - perkara_id
   - nomor_perkara
   - jenis_perkara_nama
   - tanggal_pendaftaran

2. perkara_putusan (pp)
   - perkara_id
   - tanggal_putusan
   - tanggal_bht (NULL jika belum selesai)

3. perkara_jadwal_sidang (pjs)
   - perkara_id
   - tanggal_sidang (ini adalah tanggal PBT)

4. perkara_penetapan (pen)
   - perkara_id
   - majelis_hakim_nama
```

---

## 🧮 **3. FORMULA PERHITUNGAN**

### **Query SQL Lengkap:**
```sql
SELECT 
    p.nomor_perkara,
    p.jenis_perkara_nama as jenis_perkara,
    DATE(pp.tanggal_putusan) as tanggal_putusan,
    DATE(pjs.tanggal_sidang) as tanggal_pbt,                    -- Tanggal PBT
    DATE_ADD(pjs.tanggal_sidang, INTERVAL 7 DAY) as target_bht, -- Target 7 hari setelah PBT
    DATE(pp.tanggal_bht) as tanggal_bht,                        -- Tanggal selesai BHT (NULL jika belum)
    DATEDIFF(CURDATE(), pjs.tanggal_sidang) as hari_sejak_pbt   -- PERHITUNGAN UTAMA
FROM perkara p
LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE pp.tanggal_putusan IS NOT NULL    -- Sudah ada putusan
  AND pjs.tanggal_sidang IS NOT NULL    -- Sudah ada PBT
  AND pp.tanggal_bht IS NULL            -- Belum selesai BHT
```

### **Formula Kunci:**
```sql
DATEDIFF(CURDATE(), pjs.tanggal_sidang) as hari_sejak_pbt
```

**Penjelasan:**
- `CURDATE()` = Tanggal hari ini (31 Oktober 2025)
- `pjs.tanggal_sidang` = Tanggal PBT dari database
- `DATEDIFF()` = Menghitung selisih hari antara 2 tanggal

---

## 📊 **4. CONTOH PERHITUNGAN PRAKTIS**

### **Berdasarkan Screenshot Anda:**

**Perkara 1: 116/Pdt.G/2025/PA.Amt**
```
Tanggal Putusan: 02/05/2025
Tanggal PBT: 10/03/2025
Hari ini: 31/10/2025

Perhitungan:
DATEDIFF('2025-10-31', '2025-03-10') = 235 hari

Status: TERLAMBAT (>7 hari)
Prioritas: HIGH
```

**Perkara 2:**
```
Tanggal PBT: 20/03/2025
Hari ini: 31/10/2025

Perhitungan:
DATEDIFF('2025-10-31', '2025-03-20') = 225 hari

Status: TERLAMBAT (>7 hari)
Prioritas: HIGH
```

---

## 🚦 **5. SISTEM KATEGORI STATUS**

### **Logic dalam Kode:**
```sql
CASE 
    WHEN pp.tanggal_bht IS NOT NULL THEN 'Selesai'           -- Sudah BHT
    WHEN pjs.tanggal_sidang IS NULL THEN 'Menunggu PBT'      -- Belum PBT
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 7 THEN 'Terlambat'   -- >7 hari
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 5 THEN 'Urgent'      -- 6-7 hari
    ELSE 'Normal'                                            -- 0-5 hari
END as status
```

### **Prioritas:**
```sql
CASE 
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 7 THEN 'HIGH'    -- >7 hari
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 5 THEN 'MEDIUM'  -- 6-7 hari
    ELSE 'LOW'                                                      -- 0-5 hari
END as prioritas
```

---

## 📈 **6. VISUALISASI TIMELINE**

```
Timeline Proses Perkara:
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Pendaftaran│ -> │   Putusan   │ -> │     PBT     │ -> │     BHT     │
│             │    │             │    │             │    │  (Target)   │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
                                              │
                                              ↓
                                      ⏰ Mulai hitung hari
                                      
Hari ke-0 sampai 5:  🟢 NORMAL
Hari ke-6 sampai 7:  🟡 URGENT  
Hari ke-8 keatas:    🔴 TERLAMBAT
```

---

## 💡 **7. CONTOH KASUS KONKRET**

### **Kasus Normal:**
```
Tanggal PBT: 28 Oktober 2025
Hari ini: 31 Oktober 2025
Hari sejak PBT: 3 hari
Status: NORMAL (hijau)
Target BHT: 04 November 2025
```

### **Kasus Urgent:**
```
Tanggal PBT: 25 Oktober 2025  
Hari ini: 31 Oktober 2025
Hari sejak PBT: 6 hari
Status: URGENT (kuning)
Target BHT: 01 November 2025 (sudah terlewat 1 hari)
```

### **Kasus Terlambat (seperti di screenshot):**
```
Tanggal PBT: 10 Maret 2025
Hari ini: 31 Oktober 2025
Hari sejak PBT: 235 hari
Status: TERLAMBAT (merah)
Target BHT: 17 Maret 2025 (sudah terlewat 228 hari!)
```

---

## 🎯 **8. TUJUAN SISTEM**

### **Monitoring:**
- Memantau berkas yang sudah terlalu lama tidak diselesaikan
- Memberikan peringatan dini untuk berkas yang mendekati batas waktu
- Prioritas penanganan berdasarkan lama keterlambatan

### **Manajemen:**
- Membantu kepala pengadilan memantau kinerja
- Identifikasi bottleneck dalam proses
- Laporan untuk evaluasi dan perbaikan

---

## 🔧 **9. IMPLEMENTASI DALAM KODE**

### **Di Model (Menu_baru_model.php):**
```php
// Perhitungan hari sejak PBT
DATEDIFF(CURDATE(), pjs.tanggal_sidang) as hari_sejak_pbt

// Target BHT (7 hari setelah PBT)
DATE_ADD(pjs.tanggal_sidang, INTERVAL 7 DAY) as target_bht
```

### **Di View (jadwal_bht_harian.php):**
```php
// Warna badge berdasarkan jumlah hari
<?= $jadwal->hari_sejak_pbt > 7 ? 'badge-danger' : 
    ($jadwal->hari_sejak_pbt > 5 ? 'badge-warning' : 'badge-success') ?>

// Tampilan jumlah hari
<?= $jadwal->hari_sejak_pbt ?> hari
```

---

## ❓ **10. FAQ**

**Q: Mengapa ada perkara yang sudah 235 hari?**
A: Kemungkinan ada masalah administratif, berkas hilang, atau proses terhambat

**Q: Apakah 7 hari itu wajib?**
A: Ya, ini standar operasional pengadilan untuk efisiensi

**Q: Bagaimana jika hari libur?**
A: Sistem menghitung hari kalender, bukan hari kerja

**Q: Apakah bisa diubah target dari 7 hari?**
A: Bisa, dengan mengubah `INTERVAL 7 DAY` di query

---

**📝 Ringkasan:**
"Hari Sejak PBT" = Berapa hari sudah berlalu sejak tanggal PBT sampai hari ini, dihitung dengan `DATEDIFF(hari_ini, tanggal_PBT)`. Digunakan untuk monitoring ketepatan waktu penyelesaian berkas dengan target 7 hari.
