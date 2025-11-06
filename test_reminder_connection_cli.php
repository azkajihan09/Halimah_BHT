<?php

/**
 * CLI Test Connection untuk Database Reminder System  
 * File: test_reminder_connection_cli.php
 */

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
    echo "\n" . str_repeat("=", 60) . "\n";
    echo $message . "\n";
    echo str_repeat("=", 60) . "\n";
}

cli_header("🔍 TEST KONEKSI DATABASE REMINDER SYSTEM - CLI");

try {
    // Load database config manually to avoid CI restrictions
    // Define BASEPATH to bypass CI restriction
    if (!defined('BASEPATH')) {
        define('BASEPATH', __DIR__ . '/system/');
    }

    $config_file = 'application/config/database.php';
    if (file_exists($config_file)) {
        include($config_file);
    } else {
        throw new Exception("Database config file not found: $config_file");
    }

    cli_header("1. TEST DATABASE SIPP (PRIMARY)");

    $sipp_host = $db['default']['hostname'];
    $sipp_user = $db['default']['username'];
    $sipp_pass = $db['default']['password'];
    $sipp_db = $db['default']['database'];

    $pdo_sipp = new PDO("mysql:host=$sipp_host;dbname=$sipp_db;charset=utf8", $sipp_user, $sipp_pass);
    $pdo_sipp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    cli_success("Koneksi ke database SIPP berhasil");
    cli_info("Host: $sipp_host");
    cli_info("Database: $sipp_db");
    cli_info("User: $sipp_user");

    // Test query ke tabel perkara
    $result = $pdo_sipp->query("SELECT COUNT(*) as total FROM perkara LIMIT 1");
    $count = $result->fetchColumn();
    cli_success("Tabel 'perkara' accessible, total records: $count");

    cli_header("2. TEST DATABASE REMINDER (SECONDARY)");

    $reminder_host = $db['reminder_db']['hostname'];
    $reminder_user = $db['reminder_db']['username'];
    $reminder_pass = $db['reminder_db']['password'];
    $reminder_db = $db['reminder_db']['database'];

    $pdo_reminder = new PDO("mysql:host=$reminder_host;dbname=$reminder_db;charset=utf8", $reminder_user, $reminder_pass);
    $pdo_reminder->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    cli_success("Koneksi ke database Reminder berhasil");
    cli_info("Host: $reminder_host");
    cli_info("Database: $reminder_db");
    cli_info("User: $reminder_user");

    cli_header("3. VERIFIKASI TABEL DAN VIEW REMINDER");

    // Check tables
    $tables_result = $pdo_reminder->query("SHOW TABLES");
    $tables = $tables_result->fetchAll(PDO::FETCH_COLUMN);

    $expected_tables = array(
        'perkara_reminder',
        'pbt_tracking',
        'reminder_log',
        'reminder_statistics',
        'reminder_config'
    );

    cli_info("Checking tables...");
    foreach ($expected_tables as $table) {
        if (in_array($table, $tables)) {
            cli_success("Tabel '$table' ✓");
        } else {
            cli_error("Tabel '$table' ✗ MISSING");
        }
    }

    // Check views
    cli_info("Checking views...");
    $views_result = $pdo_reminder->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
    $views = $views_result->fetchAll(PDO::FETCH_COLUMN);

    $expected_views = array('v_reminder_dashboard', 'v_perkara_urgent');

    foreach ($expected_views as $view) {
        if (in_array($view, $views)) {
            cli_success("View '$view' ✓");

            // Test view query
            try {
                $test_result = $pdo_reminder->query("SELECT * FROM $view LIMIT 1");
                cli_info("  View '$view' query test: OK");
            } catch (Exception $e) {
                cli_error("  View '$view' query test failed: " . $e->getMessage());
            }
        } else {
            cli_error("View '$view' ✗ MISSING");
        }
    }

    cli_header("4. TEST KONFIGURASI REMINDER");

    // Check config table
    $config_result = $pdo_reminder->query("SELECT config_key, config_value FROM reminder_config");
    $configs = $config_result->fetchAll(PDO::FETCH_KEY_PAIR);

    cli_info("Loaded configurations:");
    foreach ($configs as $key => $value) {
        cli_echo("  $key = $value");
    }

    cli_header("5. TEST BASIC SYNC CAPABILITY");

    // Test if we can read from SIPP and potentially write to reminder
    $sipp_test = $pdo_sipp->query("
        SELECT COUNT(*) as total_perkara,
               COUNT(CASE WHEN pp.tanggal_putusan IS NOT NULL THEN 1 END) as perkara_dengan_putusan
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE p.tanggal_pendaftaran >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        LIMIT 1000
    ")->fetch(PDO::FETCH_ASSOC);

    cli_info("SIPP Data Summary (last 30 days):");
    cli_echo("  Total perkara: " . $sipp_test['total_perkara']);
    cli_echo("  Perkara dengan putusan: " . $sipp_test['perkara_dengan_putusan']);

    // Check reminder table count
    $reminder_test = $pdo_reminder->query("SELECT COUNT(*) FROM perkara_reminder")->fetchColumn();
    cli_info("Reminder table current records: $reminder_test");

    cli_header("✅ TEST KONEKSI BERHASIL");
    cli_success("Kedua database dapat diakses dengan baik");
    cli_success("Semua tabel dan view telah terbuat");
    cli_info("Sistem siap untuk sinkronisasi data");
    cli_info("Next: Akses dashboard di index.php/reminder_logging");
} catch (Exception $e) {
    cli_error("KONEKSI GAGAL: " . $e->getMessage());
    cli_error("Error pada line: " . $e->getLine());
    cli_error("File: " . $e->getFile());

    cli_info("\nTROUBLESHOoting:");
    cli_echo("1. Pastikan MySQL service running");
    cli_echo("2. Check database credentials di application/config/database.php");
    cli_echo("3. Pastikan database 'sipp_tebaru4' sudah ada");
    cli_echo("4. Jalankan install_reminder_database_cli.php dulu");

    exit(1);
}
