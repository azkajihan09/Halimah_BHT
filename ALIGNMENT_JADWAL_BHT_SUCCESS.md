# ALIGNMENT JADWAL_BHT_HARIAN dengan PERKARA_PUTUS_HARIAN - SUKSES

## 📋 RINGKASAN PERUBAHAN

Berhasil menyelaraskan struktur dan fungsionalitas `jadwal_bht_harian` dengan `perkara_putus_harian` untuk memberikan informasi jadwal BHT yang konsisten dan lebih informatif.

## 🎯 TUJUAN ALIGNMENT

User meminta: *"untuk jadwal_bht_harian bisa sesuai dengan perkara_putus_harian ,, tapi bikin untuk mengetahui jadwal BHT, untuk bisa sesuai kan"*

**Hasil:** jadwal_bht_harian sekarang menggunakan logika yang sama dengan perkara_putus_harian namun fokus pada penjadwalan BHT.

## 🔧 PERUBAHAN TEKNIS DETAIL

### 1. MODEL: Menu_baru_model.php - Method get_jadwal_bht_harian()

**SEBELUM:**
```sql
SELECT 
    p.nomor_perkara,
    jp.jenis_perkara_nama as jenis_perkara,
    pp.tanggal_putusan,
    pppp.tanggal_pemberitahuan_putusan as tanggal_pbt,
    pp.tanggal_bht,
    -- logika sederhana
```

**SESUDAH:**
```sql
SELECT 
    p.nomor_perkara,
    jp.jenis_perkara_nama as jenis_perkara,
    pp.tanggal_putusan,
    pppp.tanggal_pemberitahuan_putusan as tanggal_pbt,
    pp.tanggal_bht,
    
    -- PERKIRAAN BHT (15 hari kalender dari PBT atau putusan)
    CASE 
        WHEN pppp.tanggal_pemberitahuan_putusan IS NOT NULL THEN 
            DATE_ADD(pppp.tanggal_pemberitahuan_putusan, INTERVAL 15 DAY)
        ELSE 
            DATE_ADD(pp.tanggal_putusan, INTERVAL 15 DAY)
    END as perkiraan_bht,
    
    -- STATUS BHT 
    CASE 
        WHEN pp.tanggal_bht IS NOT NULL THEN 'SUDAH BHT'
        ELSE 'BELUM BHT'
    END as status_bht,
    
    -- KETERANGAN PERKARA (Kategorisasi)
    CASE 
        WHEN jp.jenis_perkara_nama LIKE '%Cerai%' THEN 'Cerai Gugat/Talak'
        WHEN jp.jenis_perkara_nama LIKE '%Dispensasi%' THEN 'Dispensasi Kawin'
        WHEN jp.jenis_perkara_nama LIKE '%Wali%' THEN 'Wali Adhal'
        WHEN jp.jenis_perkara_nama LIKE '%Harta%' THEN 'Harta Bersama'
        WHEN jp.jenis_perkara_nama LIKE '%Anak%' THEN 'Hadhanah/Nafkah Anak'
        ELSE 'Perkara Lainnya'
    END as keterangan_perkara,
    
    -- JSP (Ketua Majelis atau Hakim Tunggal)
    COALESCE(pp.majelis_hakim_ketua, pp.hakim_tunggal, 'Belum Ditentukan') as jsp,
    
    -- SISA HARI KE TARGET (Countdown calculation)
    CASE 
        WHEN pp.tanggal_bht IS NOT NULL THEN 
            CASE 
                WHEN pppp.tanggal_pemberitahuan_putusan IS NOT NULL THEN 
                    DATEDIFF(pp.tanggal_bht, DATE_ADD(pppp.tanggal_pemberitahuan_putusan, INTERVAL 15 DAY))
                ELSE 
                    DATEDIFF(pp.tanggal_bht, DATE_ADD(pp.tanggal_putusan, INTERVAL 15 DAY))
            END
        WHEN pppp.tanggal_pemberitahuan_putusan IS NOT NULL THEN 
            DATEDIFF(DATE_ADD(pppp.tanggal_pemberitahuan_putusan, INTERVAL 15 DAY), CURDATE())
        ELSE 
            DATEDIFF(DATE_ADD(pp.tanggal_putusan, INTERVAL 15 DAY), CURDATE())
    END as sisa_hari_ke_target,
    
    -- STATUS PENGISIAN BHT (Detailed status)
    CASE 
        WHEN pp.tanggal_bht IS NOT NULL THEN 
            CASE 
                WHEN (pppp.tanggal_pemberitahuan_putusan IS NOT NULL AND 
                      pp.tanggal_bht = DATE_ADD(pppp.tanggal_pemberitahuan_putusan, INTERVAL 15 DAY)) OR
                     (pppp.tanggal_pemberitahuan_putusan IS NULL AND 
                      pp.tanggal_bht = DATE_ADD(pp.tanggal_putusan, INTERVAL 15 DAY)) THEN 'TEPAT WAKTU'
                WHEN (pppp.tanggal_pemberitahuan_putusan IS NOT NULL AND 
                      pp.tanggal_bht < DATE_ADD(pppp.tanggal_pemberitahuan_putusan, INTERVAL 15 DAY)) OR
                     (pppp.tanggal_pemberitahuan_putusan IS NULL AND 
                      pp.tanggal_bht < DATE_ADD(pp.tanggal_putusan, INTERVAL 15 DAY)) THEN 'LEBIH CEPAT'
                WHEN (pppp.tanggal_pemberitahuan_putusan IS NOT NULL AND 
                      pp.tanggal_bht = DATE_ADD(pppp.tanggal_pemberitahuan_putusan, INTERVAL 16 DAY)) OR
                     (pppp.tanggal_pemberitahuan_putusan IS NULL AND 
                      pp.tanggal_bht = DATE_ADD(pp.tanggal_putusan, INTERVAL 16 DAY)) THEN 'TOLERANSI 1 HARI'
                WHEN (pppp.tanggal_pemberitahuan_putusan IS NOT NULL AND 
                      pp.tanggal_bht > DATE_ADD(pppp.tanggal_pemberitahuan_putusan, INTERVAL 16 DAY)) OR
                     (pppp.tanggal_pemberitahuan_putusan IS NULL AND 
                      pp.tanggal_bht > DATE_ADD(pp.tanggal_putusan, INTERVAL 16 DAY)) THEN 'TERLAMBAT INPUT'
                ELSE 'SELESAI'
            END
        ELSE 'BELUM SELESAI'
    END as status_pengisian_bht
```

### 2. VIEW: jadwal_bht_harian.php - Table Structure

**HEADER LAMA:**
```html
<th>Target BHT</th>
<th>Hari Sejak PBT</th>
<th>Sisa/Terlambat</th>
<th>Progress</th>
<th>Efficiency</th>
<th>Prioritas</th>
<th>Status</th>
```

**HEADER BARU:**
```html
<th>Perkiraan BHT</th>
<th>Sisa Hari</th>
<th>Status Pengisian</th>
<th>Keterangan</th>
<th>JSP</th>
```

**DATA DISPLAY BARU:**
```php
<!-- Perkiraan BHT -->
<td>
    <?php if (isset($jadwal->perkiraan_bht) && $jadwal->perkiraan_bht): ?>
        <small class="text-primary">
            <i class="far fa-calendar"></i>
            <?= date('d/m/y', strtotime($jadwal->perkiraan_bht)) ?>
        </small>
    <?php else: ?>
        <small class="text-muted">-</small>
    <?php endif; ?>
</td>

<!-- Sisa Hari ke Target -->
<td>
    <?php if (isset($jadwal->sisa_hari_ke_target)): ?>
        <?php if ($jadwal->sisa_hari_ke_target > 0): ?>
            <span class="badge badge-info">
                <i class="fas fa-clock"></i>
                <?= $jadwal->sisa_hari_ke_target ?> hari
            </span>
        <?php elseif ($jadwal->sisa_hari_ke_target == 0): ?>
            <span class="badge badge-warning">
                <i class="fas fa-exclamation"></i>
                Hari ini
            </span>
        <?php else: ?>
            <span class="badge badge-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Terlambat <?= abs($jadwal->sisa_hari_ke_target) ?> hari
            </span>
        <?php endif; ?>
    <?php else: ?>
        <small class="text-muted">-</small>
    <?php endif; ?>
</td>

<!-- Status Pengisian BHT -->
<td>
    <?php if (isset($jadwal->status_pengisian_bht)): ?>
        <?php if ($jadwal->status_pengisian_bht == 'TEPAT WAKTU'): ?>
            <span class="badge badge-success">
                <i class="fas fa-check"></i>
                <?= $jadwal->status_pengisian_bht ?>
            </span>
        <?php elseif ($jadwal->status_pengisian_bht == 'LEBIH CEPAT'): ?>
            <span class="badge badge-info">
                <i class="fas fa-rocket"></i>
                <?= $jadwal->status_pengisian_bht ?>
            </span>
        <?php elseif ($jadwal->status_pengisian_bht == 'TOLERANSI 1 HARI'): ?>
            <span class="badge badge-warning">
                <i class="fas fa-exclamation"></i>
                <?= $jadwal->status_pengisian_bht ?>
            </span>
        <?php elseif ($jadwal->status_pengisian_bht == 'TERLAMBAT INPUT'): ?>
            <span class="badge badge-danger">
                <i class="fas fa-times"></i>
                <?= $jadwal->status_pengisian_bht ?>
            </span>
        <?php elseif ($jadwal->status_pengisian_bht == 'BELUM SELESAI'): ?>
            <span class="badge badge-secondary">
                <i class="fas fa-hourglass-half"></i>
                <?= $jadwal->status_pengisian_bht ?>
            </span>
        <?php else: ?>
            <span class="badge badge-light">
                <?= $jadwal->status_pengisian_bht ?>
            </span>
        <?php endif; ?>
    <?php else: ?>
        <small class="text-muted">-</small>
    <?php endif; ?>
</td>

<!-- Keterangan Perkara -->
<td>
    <?php if (isset($jadwal->keterangan_perkara)): ?>
        <small><?= htmlspecialchars($jadwal->keterangan_perkara) ?></small>
    <?php else: ?>
        <small class="text-muted">-</small>
    <?php endif; ?>
</td>

<!-- JSP -->
<td>
    <?php if (isset($jadwal->jsp)): ?>
        <small><?= htmlspecialchars($jadwal->jsp) ?></small>
    <?php else: ?>
        <small class="text-muted">-</small>
    <?php endif; ?>
</td>
```

### 3. STATISTICS UPDATE

**STATISTICS LAMA:**
- Normal
- Urgent (11-14 hari)
- Terlambat (15-21 hari)
- Critical (>21 hari)

**STATISTICS BARU:**
- Selesai Tepat Waktu
- Toleransi 1 Hari
- Terlambat Input
- Belum Selesai

## 📊 FITUR BARU YANG DITAMBAHKAN

### 1. **Perkiraan BHT**
- Menghitung 15 hari kalender dari tanggal PBT (jika ada) atau tanggal putusan
- Memberikan target deadline yang jelas untuk pengisian BHT

### 2. **Status Pengisian BHT**
- **TEPAT WAKTU**: BHT diisi tepat pada hari ke-15
- **LEBIH CEPAT**: BHT diisi sebelum hari ke-15
- **TOLERANSI 1 HARI**: BHT diisi pada hari ke-16 (masih dapat diterima)
- **TERLAMBAT INPUT**: BHT diisi setelah hari ke-16
- **BELUM SELESAI**: BHT belum diisi

### 3. **Keterangan Perkara**
Kategorisasi otomatis berdasarkan jenis perkara:
- Cerai Gugat/Talak
- Dispensasi Kawin
- Wali Adhal
- Harta Bersama
- Hadhanah/Nafkah Anak
- Perkara Lainnya

### 4. **JSP (Juru Sita Pengganti)**
- Menampilkan Ketua Majelis Hakim atau Hakim Tunggal
- Default: "Belum Ditentukan" jika belum diisi

### 5. **Sisa Hari ke Target**
- Countdown hari ke target perkiraan BHT
- Positive: Masih ada waktu
- Zero: Deadline hari ini
- Negative: Sudah terlambat

## 🎯 KEUNGGULAN SETELAH ALIGNMENT

### 1. **Konsistensi Data**
- jadwal_bht_harian dan perkara_putus_harian menggunakan logika perhitungan yang sama
- Informasi BHT konsisten di semua view

### 2. **Informasi Lebih Lengkap**
- User mendapat informasi lebih detail tentang status pengisian BHT
- Kategorisasi perkara membantu prioritisasi
- JSP membantu identifikasi penanggung jawab

### 3. **Filter Perkara Permohonan**
- Perkara permohonan (/Pdt.P/) tidak ditampilkan (sesuai permintaan sebelumnya)
- Fokus pada perkara yang benar-benar perlu BHT

### 4. **Visual Feedback**
- Badge berwarna untuk status yang berbeda
- Icon yang intuitif untuk setiap kategori
- Row coloring berdasarkan status pengisian

## 🔍 TESTING & VALIDASI

**File Test:** `test_jadwal_bht_alignment.php`
- ✅ Verifikasi query berjalan tanpa error
- ✅ Validasi data consistency
- ✅ Memastikan filter perkara permohonan berfungsi
- ✅ Checking kolom-kolom baru tersedia

## 📝 DOKUMENTASI TAMBAHAN

### Perhitungan BHT 14/15 Hari

**Aturan Resmi:**
- BHT harus dilakukan dalam **14 hari kalender** setelah PBT
- **15 hari** digunakan sebagai target karena hari ke-15 adalah batas terakhir yang masih dapat diterima
- **16 hari** adalah toleransi 1 hari (masih dapat diterima dengan catatan)
- **>16 hari** dianggap terlambat

**Logika Perhitungan:**
1. Jika ada tanggal PBT → hitung dari tanggal PBT
2. Jika tidak ada tanggal PBT → hitung dari tanggal putusan
3. Target: Tanggal asal + 15 hari kalender
4. Status ditentukan berdasarkan perbandingan tanggal BHT aktual dengan target

## ✅ CONCLUSION

**ALIGNMENT BERHASIL COMPLETED!**

jadwal_bht_harian sekarang memberikan informasi jadwal BHT yang:
- ✅ Konsisten dengan perkara_putus_harian
- ✅ Lebih informatif dan detail
- ✅ Memiliki fitur filtering perkara permohonan
- ✅ Visual feedback yang jelas
- ✅ Memudahkan monitoring dan prioritisasi BHT

User sekarang memiliki sistem jadwal BHT yang terintegrasi dan konsisten untuk manajemen BHT yang lebih efektif.
