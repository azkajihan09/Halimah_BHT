<?php

/**
 * CLI Version of Database Installation
 * File: install_reminder_database_cli.php
 */

// Define BASEPATH to bypass CI restriction
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__ . '/system/');
}

require_once('application/config/database.php');

// CLI Output functions
function cli_echo($message, $color = 'white')
{
    echo strip_tags($message) . "\n";
}

function cli_success($message)
{
    echo "✅ " . strip_tags($message) . "\n";
}

function cli_error($message)
{
    echo "❌ " . strip_tags($message) . "\n";
}

function cli_info($message)
{
    echo "ℹ️  " . strip_tags($message) . "\n";
}

function cli_header($message)
{
    echo "\n" . str_repeat("=", 50) . "\n";
    echo $message . "\n";
    echo str_repeat("=", 50) . "\n";
}

cli_header("🔧 SETUP DATABASE REMINDER SYSTEM - CLI VERSION");
cli_echo("Membuat database dan tabel untuk sistem pencatatan reminder BHT");

// Define ENVIRONMENT if not defined
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// Konfigurasi database
$host = $db['default']['hostname'];
$user = $db['default']['username'];
$pass = $db['default']['password'];

try {
    // Connect tanpa database untuk create database
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    cli_header("1. MEMBUAT DATABASE");

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS `bht_reminder_system` CHARACTER SET utf8 COLLATE utf8_general_ci";
    $pdo->exec($sql);
    cli_success("Database 'bht_reminder_system' berhasil dibuat");

    // Use the new database
    $pdo->exec("USE bht_reminder_system");
    cli_success("Menggunakan database 'bht_reminder_system'");

    $current_db = $pdo->query('SELECT DATABASE()')->fetchColumn();
    cli_info("Current database: $current_db");

    cli_header("2. MEMBUAT TABEL-TABEL");

    // 1. Tabel perkara_reminder
    $sql = "
    CREATE TABLE IF NOT EXISTS `perkara_reminder` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nomor_perkara` varchar(100) NOT NULL,
      `perkara_id_sipp` int(11) NOT NULL COMMENT 'ID perkara dari database SIPP',
      `jenis_perkara` varchar(100) DEFAULT NULL,
      `tanggal_putusan` date DEFAULT NULL,
      `tanggal_registrasi` date DEFAULT NULL,
      `status_reminder` enum('BELUM_PBT','SUDAH_PBT_BELUM_BHT','SELESAI','CANCELLED') DEFAULT 'BELUM_PBT',
      `level_prioritas` enum('NORMAL','PERINGATAN','KRITIS','CRITICAL') DEFAULT 'NORMAL',
      `hari_sejak_putusan` int(11) DEFAULT 0,
      `tanggal_target_bht` date DEFAULT NULL COMMENT 'Target BHT = tanggal_putusan + 14 hari',
      `majelis_hakim` varchar(200) DEFAULT NULL,
      `jurusita_1` varchar(100) DEFAULT NULL,
      `jurusita_2` varchar(100) DEFAULT NULL,
      `catatan_reminder` text,
      `last_sync_sipp` datetime DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `nomor_perkara` (`nomor_perkara`),
      KEY `status_reminder` (`status_reminder`),
      KEY `level_prioritas` (`level_prioritas`),
      KEY `tanggal_putusan` (`tanggal_putusan`),
      KEY `perkara_id_sipp` (`perkara_id_sipp`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabel utama reminder perkara BHT';
    ";

    $pdo->exec($sql);
    cli_success("Tabel 'perkara_reminder' berhasil dibuat");

    // 2. Tabel pbt_tracking
    $sql = "
    CREATE TABLE IF NOT EXISTS `pbt_tracking` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `perkara_reminder_id` int(11) NOT NULL,
      `nomor_perkara` varchar(100) NOT NULL,
      `tanggal_bayar_pbt` date DEFAULT NULL,
      `jumlah_biaya` decimal(15,2) DEFAULT NULL,
      `uraian_biaya` text,
      `pihak_id` int(11) DEFAULT NULL,
      `tanggal_pemberitahuan_putusan` date DEFAULT NULL,
      `status_pbt` enum('BELUM_BAYAR','SUDAH_BAYAR','DIBEBASKAN') DEFAULT 'BELUM_BAYAR',
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `perkara_reminder_id` (`perkara_reminder_id`),
      KEY `nomor_perkara` (`nomor_perkara`),
      KEY `status_pbt` (`status_pbt`),
      CONSTRAINT `fk_pbt_perkara_reminder` FOREIGN KEY (`perkara_reminder_id`) REFERENCES `perkara_reminder` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tracking pembayaran biaya PBT';
    ";

    $pdo->exec($sql);
    cli_success("Tabel 'pbt_tracking' berhasil dibuat");

    // 3. Tabel reminder_log
    $sql = "
    CREATE TABLE IF NOT EXISTS `reminder_log` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `perkara_reminder_id` int(11) NOT NULL,
      `activity_type` enum('CREATED','STATUS_CHANGE','PBT_UPDATE','NOTE_ADDED','SYNC_UPDATE') NOT NULL,
      `description` text NOT NULL,
      `old_value` text,
      `new_value` text,
      `user_id` int(11) DEFAULT NULL,
      `user_name` varchar(100) DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `perkara_reminder_id` (`perkara_reminder_id`),
      KEY `activity_type` (`activity_type`),
      KEY `created_at` (`created_at`),
      CONSTRAINT `fk_log_perkara_reminder` FOREIGN KEY (`perkara_reminder_id`) REFERENCES `perkara_reminder` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Log aktivitas reminder system';
    ";

    $pdo->exec($sql);
    cli_success("Tabel 'reminder_log' berhasil dibuat");

    // 4. Tabel reminder_statistics
    $sql = "
    CREATE TABLE IF NOT EXISTS `reminder_statistics` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tanggal_laporan` date NOT NULL,
      `total_perkara_reminder` int(11) DEFAULT 0,
      `total_belum_pbt` int(11) DEFAULT 0,
      `total_sudah_pbt_belum_bht` int(11) DEFAULT 0,
      `total_selesai` int(11) DEFAULT 0,
      `total_cancelled` int(11) DEFAULT 0,
      `total_normal` int(11) DEFAULT 0,
      `total_peringatan` int(11) DEFAULT 0,
      `total_kritis` int(11) DEFAULT 0,
      `total_critical` int(11) DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `tanggal_laporan` (`tanggal_laporan`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Statistik harian reminder system';
    ";

    $pdo->exec($sql);
    cli_success("Tabel 'reminder_statistics' berhasil dibuat");

    // 5. Tabel reminder_config
    $sql = "
    CREATE TABLE IF NOT EXISTS `reminder_config` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `config_key` varchar(100) NOT NULL,
      `config_value` text NOT NULL,
      `description` varchar(255) DEFAULT NULL,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `config_key` (`config_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Konfigurasi reminder system';
    ";

    $pdo->exec($sql);
    cli_success("Tabel 'reminder_config' berhasil dibuat");

    cli_header("3. MEMBUAT VIEWS");

    // View untuk dashboard summary
    $sql = "
    CREATE OR REPLACE VIEW `v_reminder_dashboard` AS
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
    ";

    $pdo->exec($sql);
    cli_success("View 'v_reminder_dashboard' berhasil dibuat");

    // View untuk perkara urgent
    $sql = "
    CREATE OR REPLACE VIEW `v_perkara_urgent` AS
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
    ";

    $pdo->exec($sql);
    cli_success("View 'v_perkara_urgent' berhasil dibuat");

    cli_header("4. INSERT KONFIGURASI DEFAULT");

    // Insert default configurations
    $configs = array(
        array('auto_sync_enabled', '1', 'Enable/disable auto sync'),
        array('sync_interval_minutes', '60', 'Interval sync dalam menit'),
        array('critical_days_threshold', '21', 'Batas hari CRITICAL'),
        array('kritis_days_threshold', '14', 'Batas hari KRITIS'),
        array('peringatan_days_threshold', '10', 'Batas hari PERINGATAN'),
        array('target_bht_days', '14', 'Target hari BHT'),
        array('enable_email_notification', '0', 'Email notification'),
        array('admin_email', 'admin@pengadilan.com', 'Admin email')
    );

    foreach ($configs as $config) {
        $sql = "INSERT INTO reminder_config (config_key, config_value, description) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE config_value = VALUES(config_value), description = VALUES(description)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($config);
        cli_success("Config '{$config[0]}' = '{$config[1]}'");
    }

    cli_header("5. VERIFIKASI INSTALASI");

    // Verify tables
    $result = $pdo->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);

    cli_info("Tables created: " . count($tables));
    foreach ($tables as $table) {
        cli_echo("  - $table");
    }

    // Verify views
    $result = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
    $views = $result->fetchAll(PDO::FETCH_COLUMN);

    cli_info("Views created: " . count($views));
    foreach ($views as $view) {
        cli_echo("  - $view");
    }

    cli_header("✅ INSTALASI BERHASIL");
    cli_success("Database reminder system berhasil disetup");
    cli_info("Next: Jalankan test_reminder_connection.php untuk verifikasi");
} catch (Exception $e) {
    cli_error("ERROR: " . $e->getMessage());
    cli_error("Line: " . $e->getLine());
    cli_error("File: " . $e->getFile());
    exit(1);
}
