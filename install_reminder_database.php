<?php

/**
 * Setup dan Instalasi Database Reminder System
 * Script untuk membuat database bht_reminder_system dan tabel-tabelnya
 */

require_once('application/config/database.php');

// Konfigurasi database
$host = $db['default']['hostname'];
$user = $db['default']['username'];
$pass = $db['default']['password'];

echo "<h2>🔧 SETUP DATABASE REMINDER SYSTEM</h2>";
echo "<p>Membuat database dan tabel untuk sistem pencatatan reminder BHT</p>";
echo "<hr>";

try {
	// Connect tanpa database untuk create database
	$pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	echo "<h3>1. Membuat Database</h3>";

	// Create database
	$sql = "CREATE DATABASE IF NOT EXISTS `bht_reminder_system` CHARACTER SET utf8 COLLATE utf8_general_ci";
	$pdo->exec($sql);
	echo "✅ Database 'bht_reminder_system' berhasil dibuat<br>";

	// Use the new database
	$pdo->exec("USE bht_reminder_system");
	echo "✅ Menggunakan database 'bht_reminder_system'<br>";
	echo "<p style='color: blue;'>ℹ️ Current database: " . $pdo->query('SELECT DATABASE()')->fetchColumn() . "</p>";

	echo "<h3>2. Membuat Tabel-Tabel</h3>";

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
      `tanggal_target_bht` date GENERATED ALWAYS AS (DATE_ADD(`tanggal_putusan`, INTERVAL 14 DAY)) STORED,
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
    ";
	$pdo->exec($sql);
	echo "✅ Tabel 'perkara_reminder' berhasil dibuat<br>";

	// 2. Tabel reminder_log
	$sql = "
    CREATE TABLE IF NOT EXISTS `reminder_log` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `perkara_reminder_id` int(11) NOT NULL,
      `nomor_perkara` varchar(100) NOT NULL,
      `activity_type` enum('CREATED','STATUS_CHANGE','PBT_UPDATE','BHT_UPDATE','MANUAL_NOTE','SYNC_UPDATE') NOT NULL,
      `old_status` varchar(50) DEFAULT NULL,
      `new_status` varchar(50) DEFAULT NULL,
      `description` text,
      `user_id` varchar(50) DEFAULT 'SYSTEM',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_perkara_reminder_id` (`perkara_reminder_id`),
      KEY `idx_nomor_perkara` (`nomor_perkara`),
      KEY `idx_activity_type` (`activity_type`),
      KEY `idx_created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
    ";
	$pdo->exec($sql);
	echo "✅ Tabel 'reminder_log' berhasil dibuat<br>";

	// 3. Tabel pbt_tracking
	$sql = "
    CREATE TABLE IF NOT EXISTS `pbt_tracking` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `perkara_reminder_id` int(11) NOT NULL,
      `nomor_perkara` varchar(100) NOT NULL,
      `tanggal_bayar_pbt` date DEFAULT NULL,
      `jumlah_biaya` decimal(15,2) DEFAULT NULL,
      `uraian_biaya` text,
      `pihak_id` varchar(10) DEFAULT NULL,
      `tanggal_pemberitahuan_putusan` date DEFAULT NULL,
      `status_pbt` enum('BELUM_BAYAR','SUDAH_BAYAR_BELUM_PBT','SUDAH_PBT') DEFAULT 'BELUM_BAYAR',
      `catatan` text,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_perkara_reminder_id` (`perkara_reminder_id`),
      KEY `idx_nomor_perkara` (`nomor_perkara`),
      KEY `idx_status_pbt` (`status_pbt`),
      KEY `idx_tanggal_bayar` (`tanggal_bayar_pbt`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
    ";
	$pdo->exec($sql);
	echo "✅ Tabel 'pbt_tracking' berhasil dibuat<br>";

	// 4. Tabel reminder_statistics
	$sql = "
    CREATE TABLE IF NOT EXISTS `reminder_statistics` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
    ";
	$pdo->exec($sql);
	echo "✅ Tabel 'reminder_statistics' berhasil dibuat<br>";

	// 5. Tabel reminder_config
	$sql = "
    CREATE TABLE IF NOT EXISTS `reminder_config` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `config_key` varchar(100) NOT NULL,
      `config_value` text,
      `description` text,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `config_key` (`config_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
    ";
	$pdo->exec($sql);
	echo "✅ Tabel 'reminder_config' berhasil dibuat<br>";

	echo "<h3>3. Menambahkan Foreign Key Constraints</h3>";

	// Add foreign key constraints
	try {
		$pdo->exec("ALTER TABLE `reminder_log` ADD CONSTRAINT `fk_reminder_log_perkara` FOREIGN KEY (`perkara_reminder_id`) REFERENCES `perkara_reminder` (`id`) ON DELETE CASCADE");
		echo "✅ Foreign key 'reminder_log' -> 'perkara_reminder' ditambahkan<br>";
	} catch (Exception $e) {
		echo "ℹ️  Foreign key sudah ada atau error: " . $e->getMessage() . "<br>";
	}

	try {
		$pdo->exec("ALTER TABLE `pbt_tracking` ADD CONSTRAINT `fk_pbt_tracking_perkara` FOREIGN KEY (`perkara_reminder_id`) REFERENCES `perkara_reminder` (`id`) ON DELETE CASCADE");
		echo "✅ Foreign key 'pbt_tracking' -> 'perkara_reminder' ditambahkan<br>";
	} catch (Exception $e) {
		echo "ℹ️  Foreign key sudah ada atau error: " . $e->getMessage() . "<br>";
	}

	echo "<h3>4. Menambahkan Data Konfigurasi Awal</h3>";

	// Insert default configurations
	$configs = array(
		array('auto_sync_enabled', '1', 'Enable/disable auto sync dari database SIPP'),
		array('sync_interval_minutes', '60', 'Interval sync otomatis dalam menit'),
		array('critical_days_threshold', '21', 'Batas hari untuk status CRITICAL'),
		array('kritis_days_threshold', '14', 'Batas hari untuk status KRITIS'),
		array('peringatan_days_threshold', '10', 'Batas hari untuk status PERINGATAN'),
		array('target_bht_days', '14', 'Target hari untuk penyelesaian BHT'),
		array('last_sync_timestamp', NULL, 'Timestamp sync terakhir dari SIPP'),
		array('enable_email_notification', '0', 'Enable notifikasi email untuk reminder'),
		array('admin_email', 'admin@pengadilan.com', 'Email admin untuk notifikasi')
	);

	$stmt = $pdo->prepare("INSERT IGNORE INTO `reminder_config` (`config_key`, `config_value`, `description`) VALUES (?, ?, ?)");

	foreach ($configs as $config) {
		$stmt->execute($config);
		echo "✅ Konfigurasi '{$config[0]}' ditambahkan<br>";
	}

	echo "<h3>5. Membuat Views dan Indexes</h3>";

	// Create view for dashboard
	$sql = "
    CREATE OR REPLACE VIEW `v_reminder_dashboard` AS
    SELECT 
        status_reminder,
        level_prioritas,
        COUNT(*) as jumlah_perkara,
        AVG(hari_sejak_putusan) as rata_hari_tertunda
    FROM perkara_reminder 
    WHERE status_reminder != 'SELESAI'
    GROUP BY status_reminder, level_prioritas
    ";
	$pdo->exec($sql);
	echo "✅ View 'v_reminder_dashboard' berhasil dibuat<br>";

	// Create view for urgent cases
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
    FROM perkara_reminder pr
    LEFT JOIN pbt_tracking pt ON pr.id = pt.perkara_reminder_id
    WHERE pr.status_reminder != 'SELESAI'
    AND pr.hari_sejak_putusan > 10
    ORDER BY pr.hari_sejak_putusan DESC
    ";
	$pdo->exec($sql);
	echo "✅ View 'v_perkara_urgent' berhasil dibuat<br>";

	// Additional indexes
	try {
		$pdo->exec("CREATE INDEX `idx_status_prioritas` ON `perkara_reminder` (`status_reminder`, `level_prioritas`)");
		echo "✅ Index 'idx_status_prioritas' ditambahkan<br>";
	} catch (Exception $e) {
		echo "ℹ️  Index sudah ada<br>";
	}

	try {
		$pdo->exec("CREATE INDEX `idx_tanggal_putusan_status` ON `perkara_reminder` (`tanggal_putusan`, `status_reminder`)");
		echo "✅ Index 'idx_tanggal_putusan_status' ditambahkan<br>";
	} catch (Exception $e) {
		echo "ℹ️  Index sudah ada<br>";
	}

	try {
		$pdo->exec("CREATE INDEX `idx_hari_sejak_putusan` ON `perkara_reminder` (`hari_sejak_putusan` DESC)");
		echo "✅ Index 'idx_hari_sejak_putusan' ditambahkan<br>";
	} catch (Exception $e) {
		echo "ℹ️  Index sudah ada<br>";
	}

	echo "<hr>";
	echo "<h3>🎉 INSTALASI BERHASIL!</h3>";
	echo "<div style='background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
	echo "<h4>✅ Database Reminder System Setup Complete:</h4>";
	echo "• Database: <strong>bht_reminder_system</strong> ✅<br>";
	echo "• Tabel: <strong>5 tabel utama</strong> dengan relasi ✅<br>";
	echo "• Views: <strong>2 view</strong> untuk dashboard ✅<br>";
	echo "• Indexes: <strong>Performance optimized</strong> ✅<br>";
	echo "• Config: <strong>Data konfigurasi awal</strong> ✅<br>";
	echo "</div>";

	echo "<div style='background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
	echo "<h4>📋 Langkah Selanjutnya:</h4>";
	echo "1. <strong>Test Database Connection:</strong> <a href='test_reminder_connection.php'>Test Connection</a><br>";
	echo "2. <strong>Sinkronisasi Data:</strong> <a href='index.php/reminder_logging'>Dashboard Reminder</a><br>";
	echo "3. <strong>Lakukan Sync Manual</strong> untuk mengisi data dari SIPP<br>";
	echo "4. <strong>Setup Cron Job</strong> untuk auto-sync (opsional)<br>";
	echo "</div>";

	echo "<div style='background-color: #cce7ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
	echo "<h4>🔗 Quick Links:</h4>";
	echo "• <a href='index.php/reminder_logging'>Dashboard Reminder System</a><br>";
	echo "• <a href='index.php/bht_reminder'>Dashboard BHT (SIPP)</a><br>";
	echo "• <a href='database_reminder_setup.sql'>SQL Script Lengkap</a><br>";
	echo "</div>";
} catch (Exception $e) {
	echo "<div style='color: red; padding: 10px; background-color: #ffe6e6; border-radius: 5px;'>";
	echo "❌ <strong>Error:</strong> " . $e->getMessage();
	echo "</div>";
	echo "<p><strong>Troubleshooting:</strong></p>";
	echo "<ul>";
	echo "<li>Pastikan MySQL service running</li>";
	echo "<li>Periksa username/password database di config/database.php</li>";
	echo "<li>Pastikan user MySQL memiliki privileges CREATE DATABASE</li>";
	echo "</ul>";
}
