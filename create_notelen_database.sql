-- Database untuk Sistem Notelen
CREATE DATABASE IF NOT EXISTS `catatan_bht` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `catatan_bht`;

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
-- VIEW UNTUK DASHBOARD BERKAS
-- =============================================
CREATE VIEW `v_dashboard_summary` AS
SELECT
    -- Statistik Berkas Masuk
    COUNT(*) as total_berkas_masuk,
    COUNT(
        CASE
            WHEN status_berkas = 'MASUK' THEN 1
        END
    ) as berkas_masuk_baru,
    COUNT(
        CASE
            WHEN status_berkas = 'PROSES' THEN 1
        END
    ) as berkas_dalam_proses,
    COUNT(
        CASE
            WHEN status_berkas = 'SELESAI' THEN 1
        END
    ) as berkas_selesai,
    COUNT(
        CASE
            WHEN status_berkas = 'ARSIP' THEN 1
        END
    ) as berkas_diarsip,
    COUNT(
        CASE
            WHEN DATE(tanggal_masuk_notelen) = CURDATE() THEN 1
        END
    ) as berkas_hari_ini,
    COUNT(
        CASE
            WHEN WEEK(tanggal_masuk_notelen) = WEEK(CURDATE()) THEN 1
        END
    ) as berkas_minggu_ini,

-- Statistik Berkas PBT dari subquery
(
    SELECT COUNT(*)
    FROM berkas_pbt
) as total_berkas_pbt,
(
    SELECT COUNT(*)
    FROM berkas_pbt
    WHERE
        status_proses = 'Belum PBT'
) as pbt_belum_proses,
(
    SELECT COUNT(*)
    FROM berkas_pbt
    WHERE
        status_proses = 'Sudah PBT Belum BHT'
) as pbt_menunggu_bht,
(
    SELECT COUNT(*)
    FROM berkas_pbt
    WHERE
        status_proses = 'Selesai'
) as pbt_selesai,
(
    SELECT COUNT(*)
    FROM berkas_pbt
    WHERE
        is_duplicate_berkas = 1
) as pbt_duplikat_berkas,
(
    SELECT COUNT(*)
    FROM berkas_pbt
    WHERE
        DATE(created_at) = CURDATE()
) as pbt_hari_ini,

-- Statistik Waktu Proses
(
    SELECT AVG(
            DATEDIFF(tanggal_pbt, tanggal_putusan)
        )
    FROM berkas_pbt
    WHERE
        tanggal_pbt IS NOT NULL
) as avg_hari_putus_ke_pbt,
(
    SELECT AVG(
            DATEDIFF(tanggal_bht, tanggal_pbt)
        )
    FROM berkas_pbt
    WHERE
        tanggal_bht IS NOT NULL
        AND tanggal_pbt IS NOT NULL
) as avg_hari_pbt_ke_bht
FROM berkas_masuk;

-- =============================================
-- VIEW UNTUK ANALISIS PBT
-- =============================================
CREATE VIEW `v_pbt_analysis` AS
SELECT bp.id, bp.nomor_perkara, bp.jenis_perkara, bp.tanggal_putusan, bp.tanggal_pbt, bp.tanggal_bht, bp.status_proses, bp.is_duplicate_berkas,

-- Perhitungan selisih hari
CASE
    WHEN bp.tanggal_pbt IS NOT NULL THEN DATEDIFF(
        bp.tanggal_pbt,
        bp.tanggal_putusan
    )
    ELSE NULL
END as hari_putus_ke_pbt,
CASE
    WHEN bp.tanggal_bht IS NOT NULL
    AND bp.tanggal_pbt IS NOT NULL THEN DATEDIFF(
        bp.tanggal_bht,
        bp.tanggal_pbt
    )
    ELSE NULL
END as hari_pbt_ke_bht,

-- Status keterlambatan (standar: PBT max 14 hari dari putusan)
CASE
    WHEN bp.tanggal_pbt IS NULL
    AND DATEDIFF(CURDATE(), bp.tanggal_putusan) > 14 THEN 'TERLAMBAT_PBT'
    WHEN bp.tanggal_pbt IS NOT NULL
    AND DATEDIFF(
        bp.tanggal_pbt,
        bp.tanggal_putusan
    ) > 14 THEN 'PBT_TERLAMBAT'
    WHEN bp.tanggal_bht IS NULL
    AND bp.tanggal_pbt IS NOT NULL
    AND DATEDIFF(CURDATE(), bp.tanggal_pbt) > 30 THEN 'TERLAMBAT_BHT'
    ELSE 'NORMAL'
END as status_keterlambatan,

-- Cek duplikasi dengan berkas_masuk
CASE
    WHEN bm.nomor_perkara IS NOT NULL THEN 'ADA_DI_BERKAS_MASUK'
    ELSE 'HANYA_PBT'
END as status_duplikasi,
bp.created_at,
bp.updated_at
FROM
    berkas_pbt bp
    LEFT JOIN berkas_masuk bm ON bp.nomor_perkara = bm.nomor_perkara;

-- =============================================
-- COMMIT
-- =============================================
COMMIT;
