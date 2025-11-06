<?php

/**
 * Simple Data Sync - Populate reminder database with recent SIPP data
 */

// Define BASEPATH to bypass CI restriction
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__ . '/system/');
}
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

include('application/config/database.php');

function calculate_days_since($date)
{
    if (!$date) return 0;
    $putusan = new DateTime($date);
    $now = new DateTime();
    return $now->diff($putusan)->days;
}

function calculate_priority_level($days)
{
    if ($days > 21) return 'CRITICAL';
    if ($days > 14) return 'KRITIS';
    if ($days > 10) return 'PERINGATAN';
    return 'NORMAL';
}

try {
    // Connect to both databases
    $sipp_host = $db['default']['hostname'];
    $sipp_user = $db['default']['username'];
    $sipp_pass = $db['default']['password'];
    $sipp_db_name = $db['default']['database'];

    $reminder_host = $db['reminder_db']['hostname'];
    $reminder_user = $db['reminder_db']['username'];
    $reminder_pass = $db['reminder_db']['password'];
    $reminder_db_name = $db['reminder_db']['database'];

    $pdo_sipp = new PDO("mysql:host=$sipp_host;dbname=$sipp_db_name;charset=utf8", $sipp_user, $sipp_pass);
    $pdo_sipp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo_reminder = new PDO("mysql:host=$reminder_host;dbname=$reminder_db_name;charset=utf8", $reminder_user, $reminder_pass);
    $pdo_reminder->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== SIMPLE DATA SYNC - POPULATE REMINDER ===\n";

    // Get recent cases with putusan but no BHT from SIPP
    $sipp_query = "
        SELECT p.perkara_id, p.nomor_perkara, p.jenis_perkara_nama, p.tanggal_pendaftaran,
               pp.tanggal_putusan, pp.tanggal_bht,
               CASE WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI' ELSE 'BELUM_PBT' END as status_reminder
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE pp.tanggal_putusan IS NOT NULL 
        AND pp.tanggal_putusan >= DATE_SUB(NOW(), INTERVAL 60 DAY)
        AND pp.tanggal_bht IS NULL
        ORDER BY pp.tanggal_putusan DESC
        LIMIT 20
    ";

    $result = $pdo_sipp->query($sipp_query);
    $sipp_data = $result->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($sipp_data) . " cases to sync from SIPP\n";

    $synced = 0;
    $skipped = 0;

    foreach ($sipp_data as $row) {
        // Check if already exists in reminder
        $check_stmt = $pdo_reminder->prepare("SELECT id FROM perkara_reminder WHERE nomor_perkara = ?");
        $check_stmt->execute([$row['nomor_perkara']]);

        if ($check_stmt->fetchColumn()) {
            echo "SKIP: {$row['nomor_perkara']} (already exists)\n";
            $skipped++;
            continue;
        }

        // Calculate fields
        $days_since = calculate_days_since($row['tanggal_putusan']);
        $priority = calculate_priority_level($days_since);

        // Insert to reminder database
        $insert_stmt = $pdo_reminder->prepare("
            INSERT INTO perkara_reminder 
            (nomor_perkara, perkara_id_sipp, jenis_perkara, tanggal_putusan, tanggal_registrasi, 
             status_reminder, level_prioritas, hari_sejak_putusan, last_sync_sipp) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $insert_stmt->execute([
            $row['nomor_perkara'],
            $row['perkara_id'],
            $row['jenis_perkara_nama'],
            $row['tanggal_putusan'],
            $row['tanggal_pendaftaran'],
            $row['status_reminder'],
            $priority,
            $days_since
        ]);

        echo "SYNC: {$row['nomor_perkara']} - {$priority} ({$days_since} days)\n";
        $synced++;
    }

    echo "\n=== SYNC COMPLETED ===\n";
    echo "Synced: $synced records\n";
    echo "Skipped: $skipped records\n";

    // Show final count
    $final_count = $pdo_reminder->query("SELECT COUNT(*) FROM perkara_reminder")->fetchColumn();
    echo "Total records in reminder: $final_count\n";

    echo "\n✅ Now try accessing: http://localhost/Halimah_BHT/index.php/reminder_logging/perkara_list\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
