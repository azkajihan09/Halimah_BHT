<?php
// Test simple sync query to find problematic table
define('BASEPATH', TRUE);
define('ENVIRONMENT', 'development');

// Load database config
require_once 'application/config/database.php';

try {
    // Connect to SIPP database (using default config)
    $sipp_pdo = new PDO(
        "mysql:host={$db['default']['hostname']};dbname={$db['default']['database']};charset=utf8",
        $db['default']['username'],
        $db['default']['password']
    );

    echo "✅ SIPP Database Connected: {$db['default']['database']}\n";

    // Test if table perkara_putusan_pemberitahuan_putusan exists
    $check_table = "SHOW TABLES LIKE 'perkara_putusan_pemberitahuan_putusan'";
    $stmt = $sipp_pdo->prepare($check_table);
    $stmt->execute();
    $table_exists = $stmt->fetch();

    if ($table_exists) {
        echo "✅ Table perkara_putusan_pemberitahuan_putusan EXISTS\n";
    } else {
        echo "❌ Table perkara_putusan_pemberitahuan_putusan DOES NOT EXIST\n";

        // Show similar tables
        echo "🔍 Checking for similar tables:\n";
        $similar_tables = "SHOW TABLES LIKE '%pemberitahuan%'";
        $stmt = $sipp_pdo->prepare($similar_tables);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_NUM);
        foreach ($tables as $table) {
            echo "- " . $table[0] . "\n";
        }
    }

    // Test basic query
    $test_query = "
        SELECT COUNT(*) as total
        FROM perkara p
        INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE pp.tanggal_putusan IS NOT NULL
        AND pp.tanggal_cabut IS NULL
        AND YEAR(pp.tanggal_putusan) >= 2024
    ";

    $stmt = $sipp_pdo->prepare($test_query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    echo "✅ Total perkara 2024+ (not cancelled): " . $result->total . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
