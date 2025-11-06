<?php

/**
 * Quick check database reminder data
 */

// Define BASEPATH to bypass CI restriction
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__ . '/system/');
}
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

include('application/config/database.php');

try {
    $reminder_host = $db['reminder_db']['hostname'];
    $reminder_user = $db['reminder_db']['username'];
    $reminder_pass = $db['reminder_db']['password'];
    $reminder_db_name = $db['reminder_db']['database'];

    $pdo = new PDO("mysql:host=$reminder_host;dbname=$reminder_db_name;charset=utf8", $reminder_user, $reminder_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== CHECK DATABASE REMINDER DATA ===\n";

    // Check perkara_reminder table
    $result = $pdo->query("SELECT COUNT(*) FROM perkara_reminder");
    $count = $result->fetchColumn();
    echo "Total records in perkara_reminder: $count\n";

    if ($count > 0) {
        echo "\n=== SAMPLE DATA ===\n";
        $result = $pdo->query("SELECT * FROM perkara_reminder LIMIT 3");
        $samples = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($samples as $row) {
            echo "ID: {$row['id']}, Nomor: {$row['nomor_perkara']}, Status: {$row['status_reminder']}\n";
        }
    }

    // Check view v_reminder_dashboard
    echo "\n=== CHECK VIEW DASHBOARD ===\n";
    $result = $pdo->query("SELECT * FROM v_reminder_dashboard");
    $dashboard_data = $result->fetchAll(PDO::FETCH_ASSOC);

    if (empty($dashboard_data)) {
        echo "View v_reminder_dashboard is empty (no data to aggregate)\n";
    } else {
        foreach ($dashboard_data as $row) {
            echo "Status: {$row['status_reminder']}, Level: {$row['level_prioritas']}, Count: {$row['jumlah_perkara']}\n";
        }
    }

    // Sample SIPP data that could be synced
    echo "\n=== SAMPLE SIPP DATA FOR SYNC ===\n";
    $sipp_host = $db['default']['hostname'];
    $sipp_user = $db['default']['username'];
    $sipp_pass = $db['default']['password'];
    $sipp_db_name = $db['default']['database'];

    $pdo_sipp = new PDO("mysql:host=$sipp_host;dbname=$sipp_db_name;charset=utf8", $sipp_user, $sipp_pass);
    $pdo_sipp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $result = $pdo_sipp->query("
        SELECT p.nomor_perkara, p.jenis_perkara_nama, pp.tanggal_putusan, pp.tanggal_bht,
               CASE WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI' ELSE 'BELUM_PBT' END as status_reminder
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE pp.tanggal_putusan IS NOT NULL 
        AND pp.tanggal_putusan >= DATE_SUB(NOW(), INTERVAL 90 DAY)
        ORDER BY pp.tanggal_putusan DESC
        LIMIT 5
    ");

    $sipp_samples = $result->fetchAll(PDO::FETCH_ASSOC);

    foreach ($sipp_samples as $row) {
        echo "Nomor: {$row['nomor_perkara']}, Putusan: {$row['tanggal_putusan']}, BHT: " . ($row['tanggal_bht'] ?: 'NULL') . ", Status: {$row['status_reminder']}\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
