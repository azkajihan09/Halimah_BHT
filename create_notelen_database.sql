-- Database untuk Sistem Notelen
CREATE DATABASE IF NOT EXISTS `notelen_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `notelen_system`;

-- =============================================
-- TABEL MASTER DATA BARANG
-- =============================================
CREATE TABLE `master_barang` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nama_barang` varchar(255) NOT NULL,
    `barcode` varchar(100) NULL,
    `satuan_barang` varchar(50) NOT NULL DEFAULT 'Buah',
    `peringatan_stok` int(11) NOT NULL DEFAULT 10,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_nama_barang` (`nama_barang`),
    INDEX `idx_barcode` (`barcode`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- =============================================
-- TABEL BERKAS MASUK (PERKARA PUTUS)
-- =============================================
CREATE TABLE `berkas_masuk` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nomor_perkara` varchar(100) NOT NULL,
    `perkara_id_sipp` int(11) NOT NULL,
    `jenis_perkara` varchar(100) NULL,
    `tanggal_putusan` date NOT NULL,
    `tanggal_masuk_notelen` date NOT NULL,
    `majelis_hakim` text NULL,
    `panitera_pengganti` varchar(255) NULL,
    `status_berkas` enum(
        'MASUK',
        'PROSES',
        'SELESAI',
        'ARSIP'
    ) NOT NULL DEFAULT 'MASUK',
    `catatan_notelen` text NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `nomor_perkara` (`nomor_perkara`),
    INDEX `idx_perkara_id` (`perkara_id_sipp`),
    INDEX `idx_tanggal_putusan` (`tanggal_putusan`),
    INDEX `idx_status` (`status_berkas`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- =============================================
-- TABEL BERKAS PBT (PEMBACAAN BERITA TALAK)
-- =============================================
CREATE TABLE `berkas_pbt` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nomor_perkara` varchar(100) NOT NULL,
    `perkara_id_sipp` int(11) NOT NULL,
    `jenis_perkara` varchar(100) NULL,
    `tanggal_putusan` date NOT NULL,
    `tanggal_pbt` date NULL,
    `tanggal_bht` date NULL,
    `selisih_putus_pbt` int(11) NULL COMMENT 'Selisih hari antara putusan dan PBT',
    `selisih_pbt_bht` int(11) NULL COMMENT 'Selisih hari antara PBT dan BHT',
    `status_proses` enum(
        'Belum PBT',
        'Sudah PBT Belum BHT',
        'Selesai'
    ) NOT NULL DEFAULT 'Belum PBT',
    `majelis_hakim` text NULL,
    `panitera_pengganti` varchar(255) NULL,
    `catatan_pbt` text NULL,
    `is_duplicate_berkas` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Flag jika nomor perkara sudah ada di berkas_masuk',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `nomor_perkara_pbt` (`nomor_perkara`),
    INDEX `idx_perkara_id_pbt` (`perkara_id_sipp`),
    INDEX `idx_tanggal_putusan_pbt` (`tanggal_putusan`),
    INDEX `idx_tanggal_pbt` (`tanggal_pbt`),
    INDEX `idx_status_proses` (`status_proses`),
    INDEX `idx_duplicate_flag` (`is_duplicate_berkas`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- =============================================
-- TABEL INVENTARIS BARANG PER BERKAS
-- =============================================
CREATE TABLE `berkas_inventaris` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `berkas_masuk_id` int(11) NOT NULL,
    `master_barang_id` int(11) NOT NULL,
    `jumlah` int(11) NOT NULL DEFAULT 0,
    `kondisi_barang` enum('BAIK', 'RUSAK', 'HILANG') NOT NULL DEFAULT 'BAIK',
    `keterangan` varchar(500) NULL,
    `tanggal_masuk` date NOT NULL,
    `tanggal_keluar` date NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`berkas_masuk_id`) REFERENCES `berkas_masuk` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`master_barang_id`) REFERENCES `master_barang` (`id`) ON DELETE RESTRICT,
    INDEX `idx_berkas_barang` (
        `berkas_masuk_id`,
        `master_barang_id`
    ),
    INDEX `idx_tanggal_masuk` (`tanggal_masuk`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- =============================================
-- TABEL LOG AKTIVITAS NOTELEN
-- =============================================
CREATE TABLE `notelen_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `berkas_masuk_id` int(11) NULL,
    `activity_type` varchar(50) NOT NULL,
    `description` text NOT NULL,
    `old_value` varchar(500) NULL,
    `new_value` varchar(500) NULL,
    `user_name` varchar(100) NOT NULL DEFAULT 'SYSTEM',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`berkas_masuk_id`) REFERENCES `berkas_masuk` (`id`) ON DELETE CASCADE,
    INDEX `idx_activity` (`activity_type`),
    INDEX `idx_created` (`created_at`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- =============================================
-- TABEL KONFIGURASI NOTELEN
-- =============================================
CREATE TABLE `notelen_config` (
    `config_key` varchar(100) NOT NULL,
    `config_value` text NULL,
    `description` varchar(255) NULL,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`config_key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- =============================================
-- VIEW UNTUK DASHBOARD
-- =============================================
CREATE VIEW `v_berkas_dashboard` AS
SELECT
    COUNT(*) as total_berkas,
    COUNT(
        CASE
            WHEN status_berkas = 'MASUK' THEN 1
        END
    ) as berkas_masuk,
    COUNT(
        CASE
            WHEN status_berkas = 'PROSES' THEN 1
        END
    ) as berkas_proses,
    COUNT(
        CASE
            WHEN status_berkas = 'SELESAI' THEN 1
        END
    ) as berkas_selesai,
    COUNT(
        CASE
            WHEN status_berkas = 'ARSIP' THEN 1
        END
    ) as berkas_arsip,
    COUNT(
        CASE
            WHEN DATE(tanggal_masuk_notelen) = CURDATE() THEN 1
        END
    ) as berkas_hari_ini,
    COUNT(
        CASE
            WHEN WEEK(tanggal_masuk_notelen) = WEEK(CURDATE()) THEN 1
        END
    ) as berkas_minggu_ini
FROM berkas_masuk;

-- =============================================
-- VIEW UNTUK INVENTARIS SUMMARY
-- =============================================
CREATE VIEW `v_inventaris_summary` AS
SELECT
    mb.nama_barang,
    mb.satuan_barang,
    COUNT(bi.id) as total_transaksi,
    SUM(bi.jumlah) as total_jumlah,
    SUM(
        CASE
            WHEN bi.kondisi_barang = 'BAIK' THEN bi.jumlah
            ELSE 0
        END
    ) as jumlah_baik,
    SUM(
        CASE
            WHEN bi.kondisi_barang = 'RUSAK' THEN bi.jumlah
            ELSE 0
        END
    ) as jumlah_rusak,
    SUM(
        CASE
            WHEN bi.kondisi_barang = 'HILANG' THEN bi.jumlah
            ELSE 0
        END
    ) as jumlah_hilang,
    MAX(bi.tanggal_masuk) as terakhir_masuk
FROM
    master_barang mb
    LEFT JOIN berkas_inventaris bi ON mb.id = bi.master_barang_id
GROUP BY
    mb.id,
    mb.nama_barang,
    mb.satuan_barang;

-- =============================================
-- INSERT DATA AWAL
-- =============================================

-- Master Barang Default (dari gambar)
INSERT INTO
    `master_barang` (
        `nama_barang`,
        `satuan_barang`,
        `peringatan_stok`
    )
VALUES ('Stofmap Folio', 'Buah', 10),
    (
        'Instrumen Penambahan Biaya Panjar',
        'Buku',
        10
    ),
    (
        'Instrumen PBT Putusan',
        'Buku',
        10
    ),
    (
        'Nota Instrumen Relaas panggila Ikrar Talak',
        'Buku',
        10
    ),
    (
        'Instrumen Pernyataan Pihak Penjelasan Mediasi',
        'Buku',
        25
    ),
    (
        'Instrumen Data Saksi',
        'Buku',
        25
    ),
    (
        'Tinta PIXMA 790 Warna',
        'Botol',
        3
    ),
    ('Stiker Biru Merah', 'Pcs', 1),
    (
        'Spidol Snowman Boardmaker',
        'Pcs',
        5
    ),
    (
        'Spidol Snowman Permanent',
        'Pcs',
        5
    );

-- Konfigurasi Default
INSERT INTO
    `notelen_config` (
        `config_key`,
        `config_value`,
        `description`
    )
VALUES (
        'auto_sync_enabled',
        '1',
        'Auto sync perkara putus dari SIPP'
    ),
    (
        'sync_interval_hours',
        '24',
        'Interval sync dalam jam'
    ),
    (
        'default_status_berkas',
        'MASUK',
        'Status default berkas baru'
    ),
    (
        'max_berkas_per_page',
        '20',
        'Jumlah berkas per halaman'
    );

-- =============================================
-- COMMIT
-- =============================================
COMMIT;