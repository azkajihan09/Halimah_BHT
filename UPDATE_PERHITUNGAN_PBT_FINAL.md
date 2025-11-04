# UPDATE SISTEM PERHITUNGAN PBT-BHT
## Integrasi Tabel perkara_putusan_pemberitahuan_putusan

**Tanggal:** 4 November 2025  
**Status:** ✅ IMPLEMENTASI SELESAI

---

## 🎯 OVERVIEW PERUBAHAN

### Latar Belakang
User meminta integrasi dengan tabel `perkara_putusan_pemberitahuan_putusan` untuk perhitungan PBT yang lebih akurat. Sebelumnya sistem menghitung dari tanggal sidang atau tanggal putusan, namun sekarang prioritas diberikan pada tanggal pemberitahuan putusan yang sebenarnya.

### Logika Perhitungan Baru
```sql
-- Prioritas Tanggal PBT:
1. tanggal_pemberitahuan_putusan (dari tabel perkara_putusan_pemberitahuan_putusan)
2. tanggal_putusan (jika tidak ada pemberitahuan)

-- Rumus Selisih Hari:
CASE 
    WHEN tanggal_pemberitahuan_putusan IS NOT NULL THEN 
        DATEDIFF(CURDATE(), tanggal_pemberitahuan_putusan)
    ELSE 
        DATEDIFF(CURDATE(), tanggal_putusan)
END
```

---

## 🔧 PERUBAHAN DETAIL

### 1. Model Updates (Menu_baru_model.php)

#### A. Fungsi `get_pbt_masuk()`
**Perubahan Utama:**
- ✅ Tambah join dengan `perkara_putusan_pemberitahuan_putusan pppp`
- ✅ Logika prioritas tanggal PBT
- ✅ Perhitungan selisih hari dari tanggal PBT yang sebenarnya
- ✅ Target BHT dihitung dari tanggal PBT efektif
- ✅ Kolom `sumber_pbt` untuk transparansi

**Query Baru:**
```sql
-- Tanggal PBT efektif
COALESCE(DATE(pppp.tanggal_pemberitahuan_putusan), DATE(pjs.tanggal_sidang)) as tanggal_pbt,

-- Selisih hari berdasarkan PBT sebenarnya
CASE 
    WHEN pp.tanggal_bht IS NOT NULL THEN 
        CASE 
            WHEN pppp.tanggal_pemberitahuan_putusan IS NOT NULL THEN 
                DATEDIFF(pp.tanggal_bht, pppp.tanggal_pemberitahuan_putusan)
            ELSE DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan)
        END
    ELSE 
        CASE 
            WHEN pppp.tanggal_pemberitahuan_putusan IS NOT NULL THEN 
                DATEDIFF(CURDATE(), pppp.tanggal_pemberitahuan_putusan)
            ELSE DATEDIFF(CURDATE(), pp.tanggal_putusan)
        END
END as selisih_hari
```

#### B. Fungsi `get_jadwal_bht_harian()`
**Perubahan Utama:**
- ✅ Tambah join dengan `perkara_putusan_pemberitahuan_putusan pppp`
- ✅ Logika perhitungan hari sejak PBT yang sebenarnya
- ✅ Target BHT berdasarkan tanggal pemberitahuan

#### C. Fungsi `get_perkara_putus_harian()`
**Perubahan Besar:**
- ✅ Tambah join dengan `perkara_putusan_pemberitahuan_putusan pppp`
- ✅ Status BHT dengan kekhususan PA dan logika PBT baru
- ✅ Kolom `hari_sejak_pbt_efektif` untuk logika baru
- ✅ Backward compatibility dengan `hari_sejak_putus`
- ✅ Target BHT dihitung dari tanggal PBT efektif

### 2. View Updates

#### A. pbt_masuk.php
**Perubahan UI:**
- ✅ Tambah kolom "Sumber PBT" dengan indikator visual
- ✅ Badge berbeda untuk sumber pemberitahuan vs putusan
- ✅ Update penjelasan logika perhitungan
- ✅ Tooltip untuk kejelasan sumber data

**Visual Indicators:**
```php
// Badge untuk sumber PBT
<span class="badge badge-primary"><i class="fas fa-bell"></i> PBT</span>  // Dari pemberitahuan
<span class="badge badge-secondary"><i class="fas fa-gavel"></i> Putus</span>  // Dari putusan
```

#### B. perkara_putus_harian.php
**Perubahan UI:**
- ✅ Tambah kolom "Sumber PBT"
- ✅ Update header "Hari Sejak PBT" (dari "Hari Sejak Putus")
- ✅ Logika tampilan menggunakan `hari_sejak_pbt_efektif`
- ✅ Penjelasan komprehensif logika perhitungan baru
- ✅ Backward compatibility untuk data lama

---

## 📋 FITUR BARU

### 1. Prioritas Sumber Tanggal PBT
```sql
-- Priority Logic:
1. tanggal_pemberitahuan_putusan (PRIORITY 1 - Paling Akurat)
2. tanggal_putusan (FALLBACK - Jika tidak ada pemberitahuan)
```

### 2. Visual Indicators
- **Badge Biru** 🔵 = Data dari tabel pemberitahuan putusan (lebih akurat)
- **Badge Abu** ⚫ = Data dari tanggal putusan (fallback)

### 3. Transparansi Data
- Kolom `sumber_pbt` menunjukkan dari mana data PBT diambil
- Tooltip memberikan penjelasan lengkap sumber data
- Penjelasan logika di setiap halaman

### 4. Kekhususan Pengadilan Agama Tetap Dipertahankan
- ✅ Cerai Talak: 2-fase BHT (Izin + Ikrar)
- ✅ Cerai Gugat: Standar 14 hari + Akta Cerai
- ✅ Kasus Umum: Standar 14 hari

---

## 🔍 CONTOH IMPLEMENTASI

### Query Contoh (dari user):
```sql
SELECT A.uraian,
       DATE_FORMAT(A.tanggal_transaksi,'%d/%m/%Y') AS tanggal_transaksi,
       nomor_perkara,
       pihak_id,
       DATE_FORMAT(C.tanggal_putusan,'%d/%m/%Y') AS tanggal_putusan2,
       C.tanggal_putusan,
       (SELECT tanggal_pemberitahuan_putusan 
        FROM perkara_putusan_pemberitahuan_putusan D 
        WHERE D.perkara_id=A.perkara_id LIMIT 1) AS tanggal_pbt
FROM perkara_biaya A 
LEFT JOIN perkara B USING(perkara_id) 
LEFT JOIN perkara_putusan C USING(perkara_id)
WHERE tanggal_transaksi>='$tgl_lht' 
  AND tanggal_transaksi<='$tgl_lht2' 
  AND kategori_id='6'
ORDER BY tanggal_putusan
```

### Implementasi di Model:
```sql
-- Join dengan tabel pemberitahuan
$this->db->join('perkara_putusan_pemberitahuan_putusan pppp', 'p.perkara_id = pppp.perkara_id', 'left');

-- Tanggal PBT efektif
COALESCE(DATE(pppp.tanggal_pemberitahuan_putusan), DATE(pp.tanggal_putusan)) as tanggal_pbt_efektif,

-- Selisih hari dengan prioritas
CASE 
    WHEN pppp.tanggal_pemberitahuan_putusan IS NOT NULL THEN 
        DATEDIFF(CURDATE(), pppp.tanggal_pemberitahuan_putusan)
    ELSE 
        DATEDIFF(CURDATE(), pp.tanggal_putusan)
END as hari_sejak_pbt_efektif
```

---

## ✅ VALIDASI & TESTING

### 1. Backward Compatibility
- ✅ Data lama tanpa pemberitahuan putusan tetap berfungsi
- ✅ Kolom `hari_sejak_putus` dipertahankan untuk compatibility
- ✅ Logika fallback ke tanggal putusan jika tidak ada pemberitahuan

### 2. Data Integrity
- ✅ LEFT JOIN memastikan tidak ada data yang hilang
- ✅ COALESCE memberikan fallback yang aman
- ✅ Indikator sumber data untuk transparansi

### 3. User Experience
- ✅ Visual indicators yang jelas
- ✅ Tooltip informatif
- ✅ Penjelasan logika di setiap halaman
- ✅ Konsistensi UI across views

---

## 🎯 DAMPAK PERUBAHAN

### 1. Akurasi Perhitungan
- **Sebelum:** Menghitung dari tanggal sidang/putusan
- **Sesudah:** Menghitung dari tanggal pemberitahuan putusan yang sebenarnya
- **Hasil:** Perhitungan BHT yang lebih akurat sesuai hukum acara

### 2. Transparansi Data
- User dapat melihat sumber data PBT
- Indikator visual untuk membedakan sumber data
- Penjelasan logika yang komprehensif

### 3. Compliance Hukum
- Perhitungan sesuai dengan prosedur pemberitahuan putusan
- 14 hari dihitung dari tanggal PBT yang sebenarnya
- Kekhususan PA tetap dipertahankan

---

## 📊 SUMMARY IMPLEMENTASI

| Komponen                               | Status | Perubahan                                        |
| -------------------------------------- | ------ | ------------------------------------------------ |
| **Model - get_pbt_masuk()**            | ✅      | Integrasi tabel pemberitahuan + logika prioritas |
| **Model - get_jadwal_bht_harian()**    | ✅      | Join pemberitahuan + perhitungan akurat          |
| **Model - get_perkara_putus_harian()** | ✅      | Complete overhaul dengan backward compatibility  |
| **View - pbt_masuk.php**               | ✅      | Kolom sumber PBT + visual indicators             |
| **View - perkara_putus_harian.php**    | ✅      | Update UI + penjelasan logika                    |
| **Backward Compatibility**             | ✅      | Data lama tetap berfungsi                        |
| **Kekhususan PA**                      | ✅      | Cerai Talak 2-fase tetap dipertahankan           |

---

**KESIMPULAN:** Sistem sekarang menghitung selisih hari PBT-BHT berdasarkan tanggal pemberitahuan putusan yang sebenarnya (jika ada), dengan fallback ke tanggal putusan untuk backward compatibility. Logika ini memberikan akurasi perhitungan yang lebih baik sesuai dengan prosedur hukum acara perdata di Pengadilan Agama.
