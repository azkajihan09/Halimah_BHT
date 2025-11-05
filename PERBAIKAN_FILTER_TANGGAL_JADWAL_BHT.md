# PERBAIKAN FILTER TANGGAL JADWAL BHT HARIAN

## Masalah yang Ditemukan
Parameter `$tanggal` pada fungsi `get_jadwal_bht_harian()` tidak digunakan dalam WHERE clause, sehingga filter tanggal tidak berfungsi. Semua pencarian berdasarkan tanggal mengembalikan hasil yang sama karena hanya filter tahun dan jenis yang digunakan.

## Solusi Perbaikan

### 1. Modifikasi Query di `get_jadwal_bht_harian()`
```php
// FILTER TANGGAL: Menampilkan perkara berdasarkan tanggal parameter
if ($tanggal) {
    // Jika tanggal spesifik diberikan, filter berdasarkan:
    // 1. Tanggal putusan yang sama dengan parameter tanggal, ATAU
    // 2. Perkara yang PBT-nya pada tanggal tersebut, ATAU  
    // 3. Perkara yang target BHT-nya pada tanggal tersebut
    $this->db->group_start();
    // Perkara yang diputus pada tanggal tersebut
    $this->db->or_where('DATE(pp.tanggal_putusan)', $tanggal);
    // Perkara yang PBT pada tanggal tersebut
    $this->db->or_where('DATE(pppp.tanggal_pemberitahuan_putusan)', $tanggal);
    // Perkara yang target BHT pada tanggal tersebut
    $this->db->or_where('DATE(DATE_ADD(COALESCE(pppp.tanggal_pemberitahuan_putusan, pp.tanggal_putusan), INTERVAL 15 DAY)) =', $tanggal);
    $this->db->group_end();
} else {
    // Jika tidak ada filter tanggal, tampilkan berdasarkan logika lama
    // ... logika existing untuk menampilkan perkara dalam rentang waktu tertentu
}
```

### 2. Update Fungsi `count_jadwal_bht_harian()`
Disesuaikan agar konsisten dengan logika filter tanggal yang sama.

## Hasil Testing

### ✅ Filter Tanggal Berfungsi
- **2025-10-29**: 20 records (✓ Ada yang match tanggal putusan)
- **2025-10-28**: 7 records (✓ Ada yang match tanggal putusan)  
- **2025-11-01**: 5 records (Target BHT atau PBT)
- **2024-12-01**: 0 records (Tidak ada data)

### ✅ Logika Pencarian
1. **Tanggal Putusan**: Perkara yang diputus pada tanggal tersebut
2. **Tanggal PBT**: Perkara yang PBT pada tanggal tersebut
3. **Target BHT**: Perkara yang deadline BHT-nya pada tanggal tersebut

### ✅ Backward Compatibility
- Tanggal kosong tetap menggunakan logika lama (80 records)
- Filter jenis dan tahun tetap berfungsi normal

## Interface User
Filter tanggal di interface sudah tersedia dengan:
- **Input type="date"** untuk memilih tanggal spesifik
- **Form method="GET"** mengirim parameter `tanggal`
- **Kombinasi filter** dengan jenis perkara dan tahun

## Status
**✅ FIXED - Filter tanggal jadwal_bht_harian sekarang berfungsi dengan sempurna!**

Pengguna sekarang dapat:
- Memilih tanggal spesifik untuk melihat jadwal BHT
- Melihat perkara yang diputus pada tanggal tersebut
- Melihat perkara yang PBT atau target BHT pada tanggal tersebut
- Mengkombinasikan dengan filter jenis dan tahun

## File yang Dimodifikasi
1. `application/models/Menu_baru_model.php`
   - Fungsi `get_jadwal_bht_harian()`
   - Fungsi `count_jadwal_bht_harian()`

## Testing Files
1. `test_tanggal_filter_bug.php` - Membuktikan masalah
2. `test_perbaikan_tanggal.php` - Validasi perbaikan
