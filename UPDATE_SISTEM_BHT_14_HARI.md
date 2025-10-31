# 🔄 **PEMBARUAN SISTEM BHT - ATURAN RESMI 14 HARI**

## 📋 **RINGKASAN PERUBAHAN**

Sistem telah diperbarui untuk mengikuti **aturan resmi pengadilan agama** yang menetapkan bahwa proses dari **PBT hingga BHT adalah 14 hari kalender**, bukan 7 hari seperti sebelumnya.

---

## ⚖️ **DASAR HUKUM**

Berdasarkan ketentuan resmi pengadilan agama:

1. **PBT (Pemberitahuan Isi Putusan)** disampaikan kepada pihak yang tidak hadir saat pembacaan putusan
2. **Juru sita pengadilan** bertugas menyampaikan isi putusan
3. **14 hari kalender** dihitung sejak hari setelah PBT disampaikan
4. Jika dalam 14 hari tidak ada upaya hukum banding → putusan **BHT (Berkekuatan Hukum Tetap)**
5. Menggunakan **hari kalender**, bukan hari kerja
6. Jika hari ke-14 jatuh pada libur → diperpanjang ke hari kerja berikutnya
7. Jika kedua pihak hadir saat putusan dibacakan → **langsung BHT**

---

## 🔧 **PERUBAHAN TEKNIS**

### **1. Database Query (Menu_baru_model.php)**

**SEBELUM (7 hari):**
```sql
DATE_ADD(pjs.tanggal_sidang, INTERVAL 7 DAY) as target_bht
WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 7 THEN 'Terlambat'
WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 5 THEN 'Urgent'
```

**SESUDAH (14 hari):**
```sql
DATE_ADD(pjs.tanggal_sidang, INTERVAL 14 DAY) as target_bht
WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'Terlambat'
WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'Urgent'
```

### **2. Kategori Status Baru**

| Hari Sejak PBT | Status Lama | Status Baru  | Warna    | Prioritas    |
| -------------- | ----------- | ------------ | -------- | ------------ |
| 0-5 hari       | Normal      | Normal       | 🟢 Hijau  | LOW          |
| 6-7 hari       | Urgent      | Normal       | 🟢 Hijau  | LOW          |
| 8-10 hari      | Terlambat   | Normal       | 🟢 Hijau  | LOW          |
| 11-14 hari     | Terlambat   | Urgent       | 🟡 Kuning | MEDIUM       |
| 15-21 hari     | Terlambat   | Terlambat    | 🔴 Merah  | HIGH         |
| >21 hari       | Terlambat   | **Critical** | ⚫ Hitam  | **CRITICAL** |

### **3. Tampilan UI (jadwal_bht_harian.php)**

**Perubahan Statistik:**
- Normal: 0-10 hari
- Urgent: 11-14 hari
- Terlambat: 15-21 hari
- **[BARU] Critical: >21 hari**

**Perubahan Alert:**
- Threshold berubah dari >5 hari menjadi >10 hari
- Pesan disesuaikan dengan aturan 14 hari

---

## 📊 **DAMPAK PADA DATA EXISTING**

### **Contoh Perkara dari Screenshot:**

**Perkara: 116/Pdt.G/2025/PA.Amt (235 hari sejak PBT)**

**Klasifikasi Lama:**
- Status: Terlambat (>7 hari)
- Prioritas: HIGH
- Keterlambatan: 235-7 = 228 hari

**Klasifikasi Baru:**
- Status: **CRITICAL** (>21 hari)
- Prioritas: **CRITICAL**
- Keterlambatan: 235-14 = 221 hari
- **Keterangan:** Perlu investigasi mengapa sangat terlambat

---

## 🎯 **MANFAAT PERUBAHAN**

### **1. Akurasi Hukum**
- Sesuai dengan aturan resmi pengadilan agama
- Perhitungan yang tepat untuk monitoring BHT
- Dasar hukum yang jelas untuk evaluasi

### **2. Monitoring yang Lebih Baik**
- Kategori **CRITICAL** untuk kasus sangat terlambat
- Prioritas yang lebih realistis
- Alert yang lebih akurat

### **3. Pelaporan yang Tepat**
- Statistik yang sesuai standar
- Target waktu yang benar
- Evaluasi kinerja yang valid

---

## 🚨 **PERHATIAN KHUSUS**

### **Kasus dengan 200+ Hari**
Perkara yang sudah 200+ hari sejak PBT seperti dalam screenshot menunjukkan:

1. **Kemungkinan Masalah:**
   - Berkas hilang atau rusak
   - Masalah administratif
   - Pihak tidak dapat ditemukan
   - Proses banding yang tertunda

2. **Tindakan yang Diperlukan:**
   - Investigasi langsung
   - Koordinasi dengan juru sita
   - Verifikasi status banding
   - Eskalasi ke kepala pengadilan

---

## 📋 **CHECKLIST IMPLEMENTASI**

- [x] Perbarui query database (14 hari)
- [x] Perbarui kategori status
- [x] Tambah kategori CRITICAL
- [x] Perbarui tampilan UI
- [x] Perbarui alert threshold
- [x] Perbarui dokumentasi
- [ ] Test dengan data real
- [ ] Training user tentang perubahan
- [ ] Update manual operasional

---

## 🔍 **TESTING**

Untuk test perubahan ini:

1. **Jalankan query:** `query_test_hari_sejak_pbt.sql`
2. **Akses halaman:** Menu Baru → Jadwal BHT Harian
3. **Periksa kategorisasi** perkara berdasarkan hari sejak PBT
4. **Verifikasi alert** dengan threshold baru

---

## 📞 **SUPPORT**

Jika ada pertanyaan tentang perubahan ini:
- Dokumentasi lengkap: `PENJELASAN_HARI_SEJAK_PBT.md`
- Query testing: `query_test_hari_sejak_pbt.sql`
- File yang diubah: lihat section "File yang Dimodifikasi"

---

## 📁 **FILE YANG DIMODIFIKASI**

1. `application/models/Menu_baru_model.php`
   - Fungsi: `get_jadwal_bht_harian()`
   - Fungsi: `get_pengingat_urgent()`

2. `application/views/menu_baru/jadwal_bht_harian.php`
   - Statistik boxes
   - Tabel kategorisasi
   - Alert messages

3. `PENJELASAN_HARI_SEJAK_PBT.md`
   - Dokumentasi lengkap

4. `query_test_hari_sejak_pbt.sql`
   - Query testing

---

**🎯 Tujuan Utama:** Memastikan sistem monitoring BHT sesuai dengan aturan resmi pengadilan agama dan memberikan monitoring yang akurat untuk efisiensi proses peradilan.
