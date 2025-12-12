-- =============================================
-- SCRIPT CLEANUP TANGGAL_MASUK_NOTELEN
-- Script khusus untuk mengatasi bug auto-fill tanggal_masuk_notelen
-- =============================================

-- BACKUP dahulu sebelum menjalankan cleanup
-- CREATE TABLE berkas_masuk_backup AS SELECT * FROM berkas_masuk;

-- =============================================
-- CLEANUP AUTO-FILL PATTERNS
-- =============================================

-- 1. Reset tanggal_masuk_notelen yang sama dengan tanggal_register
UPDATE `berkas_masuk`
SET
    `tanggal_masuk_notelen` = NULL
WHERE
    `tanggal_masuk_notelen` = `tanggal_register`
    AND `tanggal_masuk_notelen` IS NOT NULL;

-- 2. Reset tanggal_masuk_notelen yang sama dengan created_at date
UPDATE `berkas_masuk`
SET
    `tanggal_masuk_notelen` = NULL
WHERE
    `tanggal_masuk_notelen` = DATE(`created_at`)
    AND `tanggal_masuk_notelen` IS NOT NULL;

-- 3. Reset tanggal_masuk_notelen yang sama dengan tanggal_putusan
UPDATE `berkas_masuk`
SET
    `tanggal_masuk_notelen` = NULL
WHERE
    `tanggal_masuk_notelen` = `tanggal_putusan`
    AND `tanggal_masuk_notelen` IS NOT NULL;

-- 4. Reset tanggal_masuk_notelen untuk data auto-insert
UPDATE `berkas_masuk`
SET
    `tanggal_masuk_notelen` = NULL
WHERE
    `catatan_notelen` LIKE '%Auto-insert%'
    AND `tanggal_masuk_notelen` IS NOT NULL;

-- 5. Reset tanggal_masuk_notelen untuk data created hari ini yang sama dengan hari ini
UPDATE `berkas_masuk`
SET
    `tanggal_masuk_notelen` = NULL
WHERE
    `tanggal_masuk_notelen` = CURDATE()
    AND DATE(`created_at`) = CURDATE();

-- 6. Reset tanggal_masuk_notelen untuk data created kemarin yang tanggal_masuk_notelen = kemarin
UPDATE `berkas_masuk`
SET
    `tanggal_masuk_notelen` = NULL
WHERE
    `tanggal_masuk_notelen` = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
    AND DATE(`created_at`) = DATE_SUB(CURDATE(), INTERVAL 1 DAY);

-- 7. Reset tanggal_masuk_notelen yang dibuat dalam 7 hari terakhir dengan pattern mencurigakan
UPDATE `berkas_masuk`
SET
    `tanggal_masuk_notelen` = NULL
WHERE
    `created_at` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    AND (
        `tanggal_masuk_notelen` = `tanggal_register`
        OR `tanggal_masuk_notelen` = `tanggal_putusan`
        OR `tanggal_masuk_notelen` = DATE(`created_at`)
        OR `tanggal_masuk_notelen` = DATE(`updated_at`)
    );

-- =============================================
-- VALIDASI HASIL CLEANUP
-- =============================================

-- Lihat berapa data yang berhasil di-reset
SELECT 'Total berkas' as keterangan, COUNT(*) as jumlah
FROM berkas_masuk
UNION ALL
SELECT 'Berkas dengan tanggal_masuk_notelen NULL (benar)' as keterangan, COUNT(*) as jumlah
FROM berkas_masuk
WHERE
    tanggal_masuk_notelen IS NULL
UNION ALL
SELECT 'Berkas dengan tanggal_masuk_notelen terisi (perlu validasi manual)' as keterangan, COUNT(*) as jumlah
FROM berkas_masuk
WHERE
    tanggal_masuk_notelen IS NOT NULL;

-- =============================================
-- CEK DATA YANG MASIH PERLU VALIDASI MANUAL
-- =============================================

-- Tampilkan data yang masih memiliki tanggal_masuk_notelen untuk dicek manual
SELECT
    id,
    nomor_perkara,
    tanggal_putusan,
    tanggal_register,
    tanggal_masuk_notelen,
    DATE(created_at) as created_date,
    catatan_notelen
FROM berkas_masuk
WHERE
    tanggal_masuk_notelen IS NOT NULL
ORDER BY created_at DESC
LIMIT 20;

-- =============================================
-- UPDATE STRUKTUR TABEL
-- =============================================

-- Pastikan komentar kolom jelas
ALTER TABLE `berkas_masuk`
MODIFY COLUMN `tanggal_masuk_notelen` date NULL COMMENT 'Tanggal berkas masuk notelen - HARUS DIISI MANUAL, TIDAK AUTO-FILL. Bug auto-fill sudah diperbaiki.';

-- =============================================
-- SELESAI
-- =============================================
SELECT 'Cleanup tanggal_masuk_notelen selesai. Silakan cek hasil validasi di atas.' as status;