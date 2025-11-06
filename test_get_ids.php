<?php
// Test get ID dari database reminder
require_once 'application/config/database.php';

echo "Testing database for IDs...\n";

try {
    $host = $db['default']['hostname'];
    $user = $db['default']['username'];
    $pass = $db['default']['password'];
    $dbname = 'bht_reminder_system';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    echo "Database connection: OK\n\n";

    // Get beberapa ID untuk testing
    $stmt = $pdo->query("SELECT id, nomor_perkara, status_reminder FROM perkara_reminder ORDER BY id LIMIT 5");
    $results = $stmt->fetchAll();

    echo "Available IDs for testing:\n";
    echo "ID\tNomor Perkara\tStatus\n";
    echo "---\t-------------\t------\n";

    foreach ($results as $row) {
        echo $row['id'] . "\t" . $row['nomor_perkara'] . "\t" . $row['status_reminder'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
