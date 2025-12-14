-- =============================================
-- FIX DATABASE STRUCTURE: TANGGAL_MASUK_NOTELEN
-- Masalah: Kolom tanggal_masuk_notelen NOT NULL tapi kode insert NULL
-- Solusi: Ubah kolom menjadi nullable sesuai desain
-- =============================================

USE catatan_bht;

-- Ubah kolom tanggal_masuk_notelen menjadi NULL (sesuai desain)
ALTER TABLE `berkas_masuk` 
MODIFY COLUMN `tanggal_masuk_notelen` date NULL COMMENT 'Tanggal berkas masuk notelen - harus diisi manual, tidak auto-fill';

-- Verifikasi perubahan
DESCRIBE berkas_masuk;

-- Test insert untuk memastikan tidak error
-- INSERT INTO `berkas_masuk` 
-- (`nomor_perkara`, `perkara_id_sipp`, `jenis_perkara`, `tanggal_putusan`, `tanggal_register`, `tanggal_masuk_notelen`, `status_berkas`)
-- VALUES ('TEST/123/2025/PA.Test', 1, 'Test', '2025-12-14', '2025-12-14', NULL, 'PANITERA_PENGGANTI');

-- Jika test berhasil, hapus data test:
-- DELETE FROM berkas_masuk WHERE nomor_perkara = 'TEST/123/2025/PA.Test';

SELECT 'Database structure fixed successfully!' as status;
