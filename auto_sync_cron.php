<?php

/**
 * Auto Sync Cron Job untuk Sistem Reminder BHT
 * File ini dapat dijalankan via cron job untuk sinkronisasi otomatis
 * 
 * Setup Cron Job (contoh):
 * # Jalankan setiap 1 jam
 * 0 * * * * /usr/bin/php /path/to/Halimah_BHT/auto_sync_cron.php
 * 
 * # Atau via wget/curl
 * 0 * * * * wget -q -O - http://localhost/Halimah_BHT/auto_sync_cron.php
 */

// Set memory limit dan timeout untuk proses besar
ini_set('memory_limit', '256M');
set_time_limit(300); // 5 menit

// Load CodeIgniter environment
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

if (defined('ENVIRONMENT')) {
	$file_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/Halimah_BHT/index.php';
	if (file_exists($file_path)) {
		$_SERVER['REQUEST_URI'] = '/Halimah_BHT/index.php/reminder_logging/auto_sync';
		include $file_path;
		exit;
	}
}

// Fallback: Direct database access jika CodeIgniter tidak tersedia
require_once(__DIR__ . '/application/config/database.php');

echo "=== AUTO SYNC REMINDER SYSTEM ===\n";
echo "Started at: " . date('Y-m-d H:i:s') . "\n";
echo "==================================\n";

try {
	// Connect to databases
	$sipp_host = $db['default']['hostname'];
	$sipp_user = $db['default']['username'];
	$sipp_pass = $db['default']['password'];
	$sipp_db = $db['default']['database'];

	$reminder_host = $db['reminder_db']['hostname'];
	$reminder_user = $db['reminder_db']['username'];
	$reminder_pass = $db['reminder_db']['password'];
	$reminder_db = $db['reminder_db']['database'];

	$pdo_sipp = new PDO("mysql:host=$sipp_host;dbname=$sipp_db;charset=utf8", $sipp_user, $sipp_pass);
	$pdo_reminder = new PDO("mysql:host=$reminder_host;dbname=$reminder_db;charset=utf8", $reminder_user, $reminder_pass);

	$pdo_sipp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo_reminder->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	echo "✓ Database connections established\n";

	// Check if auto sync is enabled
	$stmt = $pdo_reminder->prepare("SELECT config_value FROM reminder_config WHERE config_key = 'auto_sync_enabled'");
	$stmt->execute();
	$auto_sync_enabled = $stmt->fetchColumn();

	if (!$auto_sync_enabled || $auto_sync_enabled == '0') {
		echo "! Auto sync is disabled. Exiting.\n";
		exit(0);
	}

	echo "✓ Auto sync is enabled\n";

	// 1. Sync new cases from SIPP
	echo "\n1. Syncing new cases from SIPP...\n";

	$sync_query = "
        SELECT 
            p.perkara_id,
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            p.tanggal_pendaftaran as tanggal_registrasi,
            pp.tanggal_putusan,
            COALESCE(pen.majelis_hakim_nama, '-') as majelis_hakim,
            pj.jurusita_1,
            pj.jurusita_2,
            pb.tanggal_transaksi as tanggal_bayar_pbt,
            pb.jumlah as jumlah_biaya,
            pb.uraian as uraian_biaya,
            pb.pihak_id,
            pppp_check.tanggal_pbt as tanggal_pemberitahuan_putusan,
            CASE 
                WHEN pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NULL THEN 'SUDAH_BAYAR_BELUM_PBT'
                WHEN pb.tanggal_transaksi IS NULL AND pppp_check.tanggal_pbt IS NULL THEN 'BELUM_BAYAR'
                WHEN pppp_check.tanggal_pbt IS NOT NULL AND pp.tanggal_bht IS NULL THEN 'SUDAH_PBT'
                ELSE 'SELESAI'
            END as status_pbt_sipp
        FROM perkara p
        INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
        LEFT JOIN perkara_biaya pb ON p.perkara_id = pb.perkara_id AND pb.kategori_id = 6
        LEFT JOIN (
            SELECT perkara_id, MIN(tanggal_pemberitahuan_putusan) as tanggal_pbt 
            FROM perkara_putusan_pemberitahuan_putusan 
            WHERE pihak = '2' 
            GROUP BY perkara_id
        ) pppp_check ON p.perkara_id = pppp_check.perkara_id
        LEFT JOIN (
            SELECT perkara_id,
                   MAX(CASE WHEN urutan = '1' THEN jurusita_nama ELSE NULL END) as jurusita_1,
                   MAX(CASE WHEN urutan = '2' THEN jurusita_nama ELSE NULL END) as jurusita_2
            FROM perkara_jurusita 
            WHERE aktif = 'Y' AND urutan IN ('1', '2')
            GROUP BY perkara_id
        ) pj ON p.perkara_id = pj.perkara_id
        WHERE pp.tanggal_putusan IS NOT NULL
        AND pp.tanggal_cabut IS NULL
        AND YEAR(pp.tanggal_putusan) >= 2024
        AND (
            (pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NULL) OR
            (pb.tanggal_transaksi IS NULL AND pppp_check.tanggal_pbt IS NULL) OR
            (pppp_check.tanggal_pbt IS NOT NULL AND pp.tanggal_bht IS NULL)
        )
        AND p.nomor_perkara NOT IN (
            SELECT nomor_perkara FROM $reminder_db.perkara_reminder
        )
        ORDER BY pp.tanggal_putusan DESC
        LIMIT 50
    ";

	$stmt = $pdo_sipp->prepare($sync_query);
	$stmt->execute();
	$new_cases = $stmt->fetchAll(PDO::FETCH_OBJ);

	$synced_count = 0;
	$errors = array();

	foreach ($new_cases as $case) {
		try {
			// Calculate priority and days
			$days_since = (new DateTime())->diff(new DateTime($case->tanggal_putusan))->days;
			$priority = 'NORMAL';
			if ($days_since > 21) $priority = 'CRITICAL';
			elseif ($days_since > 14) $priority = 'KRITIS';
			elseif ($days_since > 10) $priority = 'PERINGATAN';

			$status_reminder = 'BELUM_PBT';
			if ($case->status_pbt_sipp == 'SUDAH_PBT') $status_reminder = 'SUDAH_PBT_BELUM_BHT';
			elseif ($case->status_pbt_sipp == 'SELESAI') $status_reminder = 'SELESAI';

			// Insert into perkara_reminder
			$stmt = $pdo_reminder->prepare("
                INSERT INTO perkara_reminder (
                    nomor_perkara, perkara_id_sipp, jenis_perkara, tanggal_putusan, 
                    tanggal_registrasi, status_reminder, level_prioritas, hari_sejak_putusan,
                    majelis_hakim, jurusita_1, jurusita_2, last_sync_sipp
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");

			$stmt->execute([
				$case->nomor_perkara,
				$case->perkara_id,
				$case->jenis_perkara,
				$case->tanggal_putusan,
				$case->tanggal_registrasi,
				$status_reminder,
				$priority,
				$days_since,
				$case->majelis_hakim,
				$case->jurusita_1,
				$case->jurusita_2
			]);

			$perkara_reminder_id = $pdo_reminder->lastInsertId();

			// Insert into pbt_tracking
			$stmt = $pdo_reminder->prepare("
                INSERT INTO pbt_tracking (
                    perkara_reminder_id, nomor_perkara, tanggal_bayar_pbt, jumlah_biaya,
                    uraian_biaya, pihak_id, tanggal_pemberitahuan_putusan, status_pbt
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

			$stmt->execute([
				$perkara_reminder_id,
				$case->nomor_perkara,
				$case->tanggal_bayar_pbt,
				$case->jumlah_biaya,
				$case->uraian_biaya,
				$case->pihak_id,
				$case->tanggal_pemberitahuan_putusan,
				$case->status_pbt_sipp
			]);

			// Log activity
			$stmt = $pdo_reminder->prepare("
                INSERT INTO reminder_log (
                    perkara_reminder_id, nomor_perkara, activity_type, description, user_id
                ) VALUES (?, ?, 'CREATED', 'Auto sync from SIPP', 'CRON_JOB')
            ");

			$stmt->execute([$perkara_reminder_id, $case->nomor_perkara]);

			$synced_count++;
		} catch (Exception $e) {
			$errors[] = "Error sync {$case->nomor_perkara}: " . $e->getMessage();
		}
	}

	echo "  ✓ Synced $synced_count new cases\n";
	if (!empty($errors)) {
		echo "  ! Errors: " . count($errors) . "\n";
		foreach ($errors as $error) {
			echo "    - $error\n";
		}
	}

	// 2. Update existing cases
	echo "\n2. Updating existing cases...\n";

	$update_query = "
        SELECT 
            p.perkara_id,
            p.nomor_perkara,
            pp.tanggal_putusan,
            pp.tanggal_bht,
            pb.tanggal_transaksi as tanggal_bayar_pbt,
            pppp_check.tanggal_pbt as tanggal_pemberitahuan_putusan,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
                WHEN pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NULL THEN 'SUDAH_PBT_BELUM_BHT'
                WHEN pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NOT NULL THEN 'SUDAH_PBT_BELUM_BHT'
                ELSE 'BELUM_PBT'
            END as new_status
        FROM perkara p
        INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        LEFT JOIN perkara_biaya pb ON p.perkara_id = pb.perkara_id AND pb.kategori_id = 6
        LEFT JOIN (
            SELECT perkara_id, MIN(tanggal_pemberitahuan_putusan) as tanggal_pbt 
            FROM perkara_putusan_pemberitahuan_putusan 
            WHERE pihak = '2' 
            GROUP BY perkara_id
        ) pppp_check ON p.perkara_id = pppp_check.perkara_id
        WHERE p.nomor_perkara IN (
            SELECT nomor_perkara FROM $reminder_db.perkara_reminder 
            WHERE status_reminder != 'SELESAI'
        )
    ";

	$stmt = $pdo_sipp->prepare($update_query);
	$stmt->execute();
	$update_cases = $stmt->fetchAll(PDO::FETCH_OBJ);

	$updated_count = 0;

	foreach ($update_cases as $case) {
		try {
			$days_since = (new DateTime())->diff(new DateTime($case->tanggal_putusan))->days;
			$priority = 'NORMAL';
			if ($days_since > 21) $priority = 'CRITICAL';
			elseif ($days_since > 14) $priority = 'KRITIS';
			elseif ($days_since > 10) $priority = 'PERINGATAN';

			// Update perkara_reminder
			$stmt = $pdo_reminder->prepare("
                UPDATE perkara_reminder SET
                    status_reminder = ?,
                    level_prioritas = ?,
                    hari_sejak_putusan = ?,
                    last_sync_sipp = NOW()
                WHERE nomor_perkara = ?
            ");

			$stmt->execute([$case->new_status, $priority, $days_since, $case->nomor_perkara]);

			// Update pbt_tracking
			$stmt = $pdo_reminder->prepare("
                UPDATE pbt_tracking SET
                    tanggal_bayar_pbt = ?,
                    tanggal_pemberitahuan_putusan = ?,
                    status_pbt = ?,
                    updated_at = NOW()
                WHERE nomor_perkara = ?
            ");

			$pbt_status = 'BELUM_BAYAR';
			if ($case->new_status == 'SUDAH_PBT_BELUM_BHT') $pbt_status = 'SUDAH_PBT';
			elseif ($case->new_status == 'SELESAI') $pbt_status = 'SUDAH_PBT';

			$stmt->execute([$case->tanggal_bayar_pbt, $case->tanggal_pemberitahuan_putusan, $pbt_status, $case->nomor_perkara]);

			$updated_count++;
		} catch (Exception $e) {
			// Log error but continue
			error_log("Error updating {$case->nomor_perkara}: " . $e->getMessage());
		}
	}

	echo "  ✓ Updated $updated_count existing cases\n";

	// 3. Update configuration
	$stmt = $pdo_reminder->prepare("
        UPDATE reminder_config SET config_value = NOW() WHERE config_key = 'last_sync_timestamp'
    ");
	$stmt->execute();

	// 4. Generate daily statistics
	echo "\n3. Generating daily statistics...\n";

	$stats_query = "
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
            CURDATE(),
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
            total_critical = VALUES(total_critical)
    ";

	$pdo_reminder->exec($stats_query);
	echo "  ✓ Daily statistics updated\n";

	echo "\n==================================\n";
	echo "AUTO SYNC COMPLETED SUCCESSFULLY\n";
	echo "New cases synced: $synced_count\n";
	echo "Existing cases updated: $updated_count\n";
	echo "Completed at: " . date('Y-m-d H:i:s') . "\n";
	echo "==================================\n";
} catch (Exception $e) {
	echo "\n=== ERROR ===\n";
	echo "Auto sync failed: " . $e->getMessage() . "\n";
	echo "Time: " . date('Y-m-d H:i:s') . "\n";
	echo "=============\n";

	// Log error to file
	error_log("[" . date('Y-m-d H:i:s') . "] Auto sync error: " . $e->getMessage(), 3, __DIR__ . '/logs/auto_sync_error.log');

	exit(1);
}
