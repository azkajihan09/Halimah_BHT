-- ===============================================
-- DATABASE SETUP: BHT REMINDER SYSTEM
-- ===============================================
-- Database terpisah untuk sistem pencatatan reminder
-- Tujuan: Menyimpan data perkara yang perlu follow-up PBT/BHT
-- ===============================================

-- 1. Buat database baru
CREATE DATABASE IF NOT EXISTS `bht_reminder_system` CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `bht_reminder_system`;

-- ===============================================
-- TABEL UTAMA UNTUK REMINDER SYSTEM
-- ===============================================

-- 2. Tabel utama perkara reminder
CREATE TABLE `perkara_reminder` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nomor_perkara` varchar(100) NOT NULL,
    `perkara_id_sipp` int(11) NOT NULL COMMENT 'ID perkara dari database SIPP',
    `jenis_perkara` varchar(100) DEFAULT NULL,
    `tanggal_putusan` date DEFAULT NULL,
    `tanggal_registrasi` date DEFAULT NULL,
    `status_reminder` enum(
        'BELUM_PBT',
        'SUDAH_PBT_BELUM_BHT',
        'SELESAI',
        'CANCELLED'
    ) DEFAULT 'BELUM_PBT',
    `level_prioritas` enum(
        'NORMAL',
        'PERINGATAN',
        'KRITIS',
        'CRITICAL'
    ) DEFAULT 'NORMAL',
    `hari_sejak_putusan` int(11) DEFAULT 0,
    `tanggal_target_bht` date GENERATED ALWAYS AS (
        DATE_ADD(
            `tanggal_putusan`,
            INTERVAL 14 DAY
        )
    ) STORED,
    `majelis_hakim` varchar(200) DEFAULT NULL,
    `jurusita_1` varchar(100) DEFAULT NULL,
    `jurusita_2` varchar(100) DEFAULT NULL,
    `catatan_reminder` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `last_sync_sipp` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `nomor_perkara` (`nomor_perkara`),
    KEY `idx_perkara_id` (`perkara_id_sipp`),
    KEY `idx_status` (`status_reminder`),
    KEY `idx_prioritas` (`level_prioritas`),
    KEY `idx_tanggal_putusan` (`tanggal_putusan`),
    KEY `idx_target_bht` (`tanggal_target_bht`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

-- 3. Tabel log reminder activities
CREATE TABLE `reminder_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `perkara_reminder_id` int(11) NOT NULL,
    `nomor_perkara` varchar(100) NOT NULL,
    `activity_type` enum(
        'CREATED',
        'STATUS_CHANGE',
        'PBT_UPDATE',
        'BHT_UPDATE',
        'MANUAL_NOTE',
        'SYNC_UPDATE'
    ) NOT NULL,
    `old_status` varchar(50) DEFAULT NULL,
    `new_status` varchar(50) DEFAULT NULL,
    `description` text,
    `user_id` varchar(50) DEFAULT 'SYSTEM',
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_perkara_reminder_id` (`perkara_reminder_id`),
    KEY `idx_nomor_perkara` (`nomor_perkara`),
    KEY `idx_activity_type` (`activity_type`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_reminder_log_perkara` FOREIGN KEY (`perkara_reminder_id`) REFERENCES `perkara_reminder` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

-- 4. Tabel untuk tracking biaya PBT
CREATE TABLE `pbt_tracking` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `perkara_reminder_id` int(11) NOT NULL,
    `nomor_perkara` varchar(100) NOT NULL,
    `tanggal_bayar_pbt` date DEFAULT NULL,
    `jumlah_biaya` decimal(15, 2) DEFAULT NULL,
    `uraian_biaya` text,
    `pihak_id` varchar(10) DEFAULT NULL,
    `tanggal_pemberitahuan_putusan` date DEFAULT NULL,
    `status_pbt` enum(
        'BELUM_BAYAR',
        'SUDAH_BAYAR_BELUM_PBT',
        'SUDAH_PBT'
    ) DEFAULT 'BELUM_BAYAR',
    `catatan` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_perkara_reminder_id` (`perkara_reminder_id`),
    KEY `idx_nomor_perkara` (`nomor_perkara`),
    KEY `idx_status_pbt` (`status_pbt`),
    KEY `idx_tanggal_bayar` (`tanggal_bayar_pbt`),
    CONSTRAINT `fk_pbt_tracking_perkara` FOREIGN KEY (`perkara_reminder_id`) REFERENCES `perkara_reminder` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

-- 5. Tabel untuk statistik dan dashboard
CREATE TABLE `reminder_statistics` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `tanggal_laporan` date NOT NULL,
    `total_perkara_reminder` int(11) DEFAULT 0,
    `total_belum_pbt` int(11) DEFAULT 0,
    `total_sudah_pbt_belum_bht` int(11) DEFAULT 0,
    `total_selesai` int(11) DEFAULT 0,
    `total_normal` int(11) DEFAULT 0,
    `total_peringatan` int(11) DEFAULT 0,
    `total_kritis` int(11) DEFAULT 0,
    `total_critical` int(11) DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `tanggal_laporan` (`tanggal_laporan`),
    KEY `idx_tanggal` (`tanggal_laporan`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

-- 6. Tabel konfigurasi sistem
CREATE TABLE `reminder_config` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `config_key` varchar(100) NOT NULL,
    `config_value` text,
    `description` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `config_key` (`config_key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

-- ===============================================
-- DATA AWAL KONFIGURASI
-- ===============================================

INSERT INTO
    `reminder_config` (
        `config_key`,
        `config_value`,
        `description`
    )
VALUES (
        'auto_sync_enabled',
        '1',
        'Enable/disable auto sync dari database SIPP'
    ),
    (
        'sync_interval_minutes',
        '60',
        'Interval sync otomatis dalam menit'
    ),
    (
        'critical_days_threshold',
        '21',
        'Batas hari untuk status CRITICAL'
    ),
    (
        'kritis_days_threshold',
        '14',
        'Batas hari untuk status KRITIS'
    ),
    (
        'peringatan_days_threshold',
        '10',
        'Batas hari untuk status PERINGATAN'
    ),
    (
        'target_bht_days',
        '14',
        'Target hari untuk penyelesaian BHT'
    ),
    (
        'last_sync_timestamp',
        NULL,
        'Timestamp sync terakhir dari SIPP'
    ),
    (
        'enable_email_notification',
        '0',
        'Enable notifikasi email untuk reminder'
    ),
    (
        'admin_email',
        'admin@pengadilan.com',
        'Email admin untuk notifikasi'
    );

-- ===============================================
-- VIEWS UNTUK KEMUDAHAN QUERY
-- ===============================================

-- View untuk dashboard summary
CREATE VIEW `v_reminder_dashboard` AS
SELECT
    status_reminder,
    level_prioritas,
    COUNT(*) as jumlah_perkara,
    AVG(hari_sejak_putusan) as rata_hari_tertunda
FROM perkara_reminder
WHERE
    status_reminder != 'SELESAI'
GROUP BY
    status_reminder,
    level_prioritas;

-- View untuk perkara urgent
CREATE VIEW `v_perkara_urgent` AS
SELECT
    pr.*,
    pt.status_pbt,
    pt.tanggal_bayar_pbt,
    pt.tanggal_pemberitahuan_putusan,
    CASE
        WHEN pr.hari_sejak_putusan > 21 THEN 'CRITICAL'
        WHEN pr.hari_sejak_putusan > 14 THEN 'KRITIS'
        WHEN pr.hari_sejak_putusan > 10 THEN 'PERINGATAN'
        ELSE 'NORMAL'
    END as level_urgency
FROM
    perkara_reminder pr
    LEFT JOIN pbt_tracking pt ON pr.id = pt.perkara_reminder_id
WHERE
    pr.status_reminder != 'SELESAI'
    AND pr.hari_sejak_putusan > 10
ORDER BY pr.hari_sejak_putusan DESC;

-- ===============================================
-- STORED PROCEDURES UNTUK MAINTENANCE
-- ===============================================

DELIMITER / /

-- Procedure untuk update level prioritas berdasarkan hari
CREATE PROCEDURE `sp_update_prioritas_level`()
BEGIN
    UPDATE perkara_reminder SET
        level_prioritas = CASE 
            WHEN hari_sejak_putusan > 21 THEN 'CRITICAL'
            WHEN hari_sejak_putusan > 14 THEN 'KRITIS'
            WHEN hari_sejak_putusan > 10 THEN 'PERINGATAN'
            ELSE 'NORMAL'
        END,
        hari_sejak_putusan = DATEDIFF(CURDATE(), tanggal_putusan)
    WHERE status_reminder != 'SELESAI';
END //

-- Procedure untuk generate statistik harian
CREATE PROCEDURE `sp_generate_daily_stats`(IN p_tanggal DATE)
BEGIN
    INSERT INTO reminder_statistics (
        tanggal_laporan, 
        total_perkara_reminder,
        total_belum_pbt,
        total_sudah_pbt_belum_bht,
        total_selesai,
        total_normal,
        total_peringatan,
        total_kritis,
        total_critical
    ) VALUES (
        p_tanggal,
        (SELECT COUNT(*) FROM perkara_reminder),
        (SELECT COUNT(*) FROM perkara_reminder WHERE status_reminder = 'BELUM_PBT'),
        (SELECT COUNT(*) FROM perkara_reminder WHERE status_reminder = 'SUDAH_PBT_BELUM_BHT'),
        (SELECT COUNT(*) FROM perkara_reminder WHERE status_reminder = 'SELESAI'),
        (SELECT COUNT(*) FROM perkara_reminder WHERE level_prioritas = 'NORMAL'),
        (SELECT COUNT(*) FROM perkara_reminder WHERE level_prioritas = 'PERINGATAN'),
        (SELECT COUNT(*) FROM perkara_reminder WHERE level_prioritas = 'KRITIS'),
        (SELECT COUNT(*) FROM perkara_reminder WHERE level_prioritas = 'CRITICAL')
    ) ON DUPLICATE KEY UPDATE
        total_perkara_reminder = VALUES(total_perkara_reminder),
        total_belum_pbt = VALUES(total_belum_pbt),
        total_sudah_pbt_belum_bht = VALUES(total_sudah_pbt_belum_bht),
        total_selesai = VALUES(total_selesai),
        total_normal = VALUES(total_normal),
        total_peringatan = VALUES(total_peringatan),
        total_kritis = VALUES(total_kritis),
        total_critical = VALUES(total_critical);
END //

DELIMITER;

-- ===============================================
-- TRIGGERS UNTUK AUTO-LOGGING
-- ===============================================

DELIMITER / /

-- Trigger untuk log ketika ada perubahan status
CREATE TRIGGER `tr_perkara_reminder_status_change`
AFTER UPDATE ON `perkara_reminder`
FOR EACH ROW
BEGIN
    IF OLD.status_reminder != NEW.status_reminder THEN
        INSERT INTO reminder_log (
            perkara_reminder_id,
            nomor_perkara,
            activity_type,
            old_status,
            new_status,
            description
        ) VALUES (
            NEW.id,
            NEW.nomor_perkara,
            'STATUS_CHANGE',
            OLD.status_reminder,
            NEW.status_reminder,
            CONCAT('Status berubah dari ', OLD.status_reminder, ' ke ', NEW.status_reminder)
        );
    END IF;
END //

DELIMITER;

-- ===============================================
-- INDEXES UNTUK PERFORMANCE
-- ===============================================

-- Composite indexes untuk query yang sering digunakan
CREATE INDEX `idx_status_prioritas` ON `perkara_reminder` (
    `status_reminder`,
    `level_prioritas`
);

CREATE INDEX `idx_tanggal_putusan_status` ON `perkara_reminder` (
    `tanggal_putusan`,
    `status_reminder`
);

CREATE INDEX `idx_hari_sejak_putusan` ON `perkara_reminder` (`hari_sejak_putusan` DESC);

-- ===============================================
-- COMPLETED: BHT REMINDER DATABASE SETUP
-- ===============================================
-- Database: bht_reminder_system siap digunakan
-- Tables: 6 tabel utama dengan relasi yang tepat
-- Views: 2 view untuk kemudahan query
-- Procedures: 2 stored procedure untuk maintenance
-- Triggers: 1 trigger untuk auto-logging
-- Indexes: Optimized untuk performance
-- ===============================================