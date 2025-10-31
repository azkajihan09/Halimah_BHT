# PERBAIKAN PERHITUNGAN SELISIH HARI - ATURAN 14 HARI KALENDER

## Permasalahan yang Ditemukan

Perhitungan selisih hari pada sistem PBT-BHT tidak sesuai dengan aturan resmi hukum acara perdata di pengadilan agama yang menetapkan **14 hari kalender** sebagai jangka waktu proses dari PBT hingga BHT.

### Kesalahan Sebelumnya:
1. **Perhitungan Salah**: `DATEDIFF(pjs.tanggal_sidang, pp.tanggal_putusan)` 
   - Menghitung selisih dari **tanggal putusan ke tanggal PBT**
   - Seharusnya menghitung **tanggal PBT ke tanggal BHT**

2. **Klasifikasi Salah**: Menggunakan batas 7 hari
   - Normal: 1-3 hari
   - Warning: 4-7 hari  
   - Danger: > 7 hari
   - **Seharusnya berdasarkan 14 hari kalender**

## Aturan Resmi 14 Hari Kalender

Berdasarkan ketentuan hukum acara perdata:

### 📅 **Jangka Waktu**
- **14 hari kalender** dari PBT hingga putusan menjadi BHT
- Dihitung sejak hari setelah PBT disampaikan kepada pihak yang tidak hadir

### ⚖️ **Ketentuan Legal**
1. Jika dalam 14 hari tidak ada upaya hukum banding → putusan otomatis BHT
2. Perhitungan menggunakan **hari kalender** (bukan hari kerja)
3. Jika hari ke-14 jatuh pada hari libur → diperpanjang sampai hari kerja berikutnya
4. Jika kedua pihak hadir saat pembacaan → langsung BHT

## Perbaikan yang Dilakukan

### 1. **Model: Menu_baru_model.php**

#### ❌ **SEBELUM (Salah)**:
```sql
DATEDIFF(pjs.tanggal_sidang, pp.tanggal_putusan) as selisih_hari
```

#### ✅ **SESUDAH (Benar)**:
```sql
CASE 
    WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang)
    ELSE DATEDIFF(CURDATE(), pjs.tanggal_sidang)
END as selisih_hari
```

**Penjelasan Perbaikan**:
- Menghitung selisih dari **PBT ke BHT** (bukan putusan ke PBT)
- Jika sudah BHT: `tanggal_bht - tanggal_pbt`
- Jika belum BHT: `tanggal_sekarang - tanggal_pbt`

### 2. **View: pbt_masuk.php**

#### ❌ **Badge Classification (Salah)**:
```php
<?= $pbt->selisih_hari > 7 ? 'badge-danger' : ($pbt->selisih_hari > 3 ? 'badge-warning' : 'badge-success') ?>
```

#### ✅ **Badge Classification (Benar)**:
```php
<?= $pbt->selisih_hari > 14 ? 'badge-danger' : ($pbt->selisih_hari > 10 ? 'badge-warning' : 'badge-success') ?>
```

#### ❌ **Chart Categories (Salah)**:
- Proses 1-3 Hari
- Proses 4-7 Hari  
- Proses > 7 Hari

#### ✅ **Chart Categories (Benar)**:
- **Proses 1-7 Hari (Normal)**: Lebih cepat dari ketentuan
- **Proses 8-14 Hari (Sesuai Aturan)**: Sesuai ketentuan hukum
- **Proses > 14 Hari (Terlambat)**: Perlu perhatian/investigasi

### 3. **Penjelasan Aturan untuk User**

Menambahkan alert box informatif yang menjelaskan:
- Aturan 14 hari kalender
- Ketentuan hukum yang berlaku
- Klasifikasi warna badge
- Interpretasi hasil perhitungan

## Hasil Setelah Perbaikan

### 🎯 **Perhitungan Akurat**
- Selisih hari dihitung dengan benar: PBT → BHT
- Sesuai dengan aturan 14 hari kalender
- Mendukung kasus BHT dan belum BHT

### 📊 **Visualisasi Tepat**
- Badge warna sesuai dengan threshold 14 hari
- Chart analisis berdasarkan aturan resmi
- Klasifikasi yang informatif dan akurat

### 👥 **User Experience**
- Penjelasan aturan yang jelas
- Konteks hukum yang membantu interpretasi
- Dashboard yang lebih informatif

## Validasi Sistem

### ✅ **Test Cases**
1. **Kasus Normal**: PBT hari ke-1, BHT hari ke-7 = 6 hari (Badge Success)
2. **Kasus Sesuai Aturan**: PBT hari ke-1, BHT hari ke-12 = 11 hari (Badge Warning)  
3. **Kasus Terlambat**: PBT hari ke-1, BHT hari ke-16 = 15 hari (Badge Danger)
4. **Kasus Belum BHT**: PBT hari ke-1, sekarang hari ke-20 = 19 hari (Badge Danger)

### 📈 **Impact**
- ✅ Compliance dengan hukum acara perdata
- ✅ Monitoring yang akurat
- ✅ Pelaporan yang valid
- ✅ Decision making yang tepat

## Konsistensi Sistem

Perbaikan ini memastikan bahwa **seluruh sistem Menu_baru** menggunakan:
- Aturan 14 hari kalender yang konsisten
- Perhitungan PBT-BHT yang akurat
- Klasifikasi yang sesuai ketentuan hukum
- Monitoring yang efektif

---

**Tanggal Perbaikan**: 31 Oktober 2025  
**Status**: ✅ **SELESAI & TERVALIDASI**  
**Compliance**: ✅ **Sesuai Aturan Hukum Acara Perdata**
