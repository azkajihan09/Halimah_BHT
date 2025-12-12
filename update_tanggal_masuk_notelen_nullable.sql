-- Update kolom tanggal_masuk_notelen agar bisa NULL (kosong)
-- Sesuai permintaan: field tanggal_masuk_notelen bisa kosong dulu saat input

USE `catatan_bht`;

-- Ubah kolom tanggal_masuk_notelen menjadi nullable
ALTER TABLE `berkas_masuk`
MODIFY COLUMN `tanggal_masuk_notelen` date NULL COMMENT 'Tanggal berkas masuk ke notelen - bisa kosong jika belum diisi';

-- Verifikasi perubahan
DESCRIBE `berkas_masuk`;