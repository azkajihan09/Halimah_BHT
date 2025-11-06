<?php

/**
 * Quick check SIPP table structure
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
    $sipp_host = $db['default']['hostname'];
    $sipp_user = $db['default']['username'];
    $sipp_pass = $db['default']['password'];
    $sipp_db = $db['default']['database'];

    $pdo = new PDO("mysql:host=$sipp_host;dbname=$sipp_db;charset=utf8", $sipp_user, $sipp_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== STRUKTUR TABEL PERKARA ===\n";
    $result = $pdo->query("DESCRIBE perkara");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }

    echo "\n=== STRUKTUR TABEL PERKARA_PUTUSAN ===\n";
    try {
        $result = $pdo->query("DESCRIBE perkara_putusan");
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $col) {
            echo $col['Field'] . " - " . $col['Type'] . "\n";
        }
    } catch (Exception $e) {
        echo "Table perkara_putusan not found or error: " . $e->getMessage() . "\n";
    }

    echo "\n=== SAMPLE DATA PERKARA (5 RECORDS) ===\n";
    $result = $pdo->query("SELECT * FROM perkara LIMIT 5");
    $samples = $result->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($samples)) {
        // Show column names
        echo "Columns: " . implode(", ", array_keys($samples[0])) . "\n\n";

        foreach ($samples as $i => $row) {
            echo "Record " . ($i + 1) . ":\n";
            foreach ($row as $key => $value) {
                echo "  $key: " . (is_null($value) ? 'NULL' : $value) . "\n";
            }
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
