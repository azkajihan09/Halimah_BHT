# 📊 **PENJELASAN DETAIL "HARI SEJAK PBT"**

## 🔍 **1. KONSEP DASAR (ATURAN RESMI)**

### **Apa itu PBT?**
- **PBT** = **Pemberitahuan Isi Putusan**
- Disampaikan kepada pihak yang tidak hadir saat pembacaan putusan
- Dilakukan oleh juru sita pengadilan
- Ini adalah awal perhitungan waktu menuju BHT

### **Apa itu BHT?**
- **BHT** = **Berkekuatan Hukum Tetap**  
- Putusan yang sudah tidak bisa diajukan banding lagi
- **Standar waktu resmi: 14 hari kalender** setelah PBT
- Menggunakan hari kalender, bukan hari kerja
- Jika hari ke-14 jatuh pada libur, diperpanjang ke hari kerja berikutnya

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
    DATE_ADD(pjs.tanggal_sidang, INTERVAL 14 DAY) as target_bht, -- Target 14 hari kalender setelah PBT
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
Target BHT: 24/03/2025 (14 hari setelah PBT)
Hari ini: 31/10/2025

Perhitungan:
DATEDIFF('2025-10-31', '2025-03-10') = 235 hari
Keterlambatan: 235 - 14 = 221 hari terlambat

Status: SANGAT TERLAMBAT (>14 hari)
Prioritas: CRITICAL
```

**Perkara 2:**
```
Tanggal PBT: 20/03/2025
Target BHT: 03/04/2025 (14 hari setelah PBT)
Hari ini: 31/10/2025

Perhitungan:
DATEDIFF('2025-10-31', '2025-03-20') = 225 hari
Keterlambatan: 225 - 14 = 211 hari terlambat

Status: SANGAT TERLAMBAT (>14 hari)
Prioritas: CRITICAL
```

---

## 🚦 **5. SISTEM KATEGORI STATUS**

### **Logic dalam Kode (ATURAN RESMI 14 HARI):**
```sql
CASE 
    WHEN pp.tanggal_bht IS NOT NULL THEN 'Selesai BHT'       -- Sudah BHT
    WHEN pjs.tanggal_sidang IS NULL THEN 'Menunggu PBT'      -- Belum PBT
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'Terlambat'  -- >14 hari
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'Urgent'     -- 11-14 hari
    ELSE 'Normal'                                            -- 0-10 hari
END as status
```

### **Prioritas (BERDASARKAN ATURAN 14 HARI):**
```sql
CASE 
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 'CRITICAL'   -- >21 hari (1 minggu lebih)
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'HIGH'       -- >14 hari (melewati batas)
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'MEDIUM'     -- 11-14 hari (mendekati batas)
    ELSE 'LOW'                                                          -- 0-10 hari (aman)
END as prioritas
```

---

## 📈 **6. VISUALISASI TIMELINE**

```
Timeline Proses Perkara (ATURAN RESMI):
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Pendaftaran│ -> │   Putusan   │ -> │ PBT (Juru   │ -> │ BHT (14 hari│
│             │    │  Dibacakan  │    │ Sita)       │    │  kalender)  │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
                                              │
                                              ↓
                                      ⏰ Mulai hitung 14 hari kalender
                                      
Hari ke-0 sampai 10:  🟢 NORMAL
Hari ke-11 sampai 14: 🟡 URGENT (mendekati batas)
Hari ke-15 sampai 21: 🔴 TERLAMBAT  
Hari ke-22 keatas:    ⚫ CRITICAL (sangat terlambat)

CATATAN KHUSUS:
- Jika kedua pihak hadir saat putusan dibacakan = LANGSUNG BHT
- Jika hari ke-14 jatuh pada libur = diperpanjang ke hari kerja berikutnya
- Setelah BHT, baru bisa terbitkan akta cerai
```

---

## 💡 **7. CONTOH KASUS KONKRET**

### **Kasus Normal (ATURAN RESMI 14 HARI):**
```
Tanggal PBT: 25 Oktober 2025
Hari ini: 31 Oktober 2025
Hari sejak PBT: 6 hari
Status: NORMAL (hijau)
Target BHT: 08 November 2025 (masih 8 hari lagi)
```

### **Kasus Urgent:**
```
Tanggal PBT: 18 Oktober 2025  
Hari ini: 31 Oktober 2025
Hari sejak PBT: 13 hari
Status: URGENT (kuning)
Target BHT: 01 November 2025 (tinggal 1 hari lagi!)
```

### **Kasus Terlambat:**
```
Tanggal PBT: 15 Oktober 2025
Hari ini: 31 Oktober 2025
Hari sejak PBT: 16 hari
Status: TERLAMBAT (merah)
Target BHT: 29 Oktober 2025 (sudah terlewat 2 hari)
```

### **Kasus Critical (seperti di screenshot):**
```
Tanggal PBT: 10 Maret 2025
Hari ini: 31 Oktober 2025
Hari sejak PBT: 235 hari
Status: CRITICAL (hitam)
Target BHT: 24 Maret 2025 (sudah terlewat 221 hari!)
Keterangan: Perlu investigasi mengapa sangat terlambat
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
