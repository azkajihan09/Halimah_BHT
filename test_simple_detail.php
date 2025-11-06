<?php
// Test akses direct ke method tanpa framework
require_once 'application/config/database.php';

echo "Testing direct access...\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current directory: " . __DIR__ . "\n";

// Test nomor perkara decoding
$encoded = "288%2FPdt.P%2F2024%2FPA.Amt";
$decoded = urldecode($encoded);
echo "Encoded: $encoded\n";
echo "Decoded: $decoded\n";

// Test koneksi database langsung
try {
    if (isset($db['default'])) {
        $host = $db['default']['hostname'];
        $user = $db['default']['username'];
        $pass = $db['default']['password'];
        $dbname = $db['default']['database'];

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        echo "Database connection: OK\n";

        // Test query sederhana
        $stmt = $pdo->prepare("SELECT nomor_perkara FROM perkara WHERE nomor_perkara = ? LIMIT 1");
        $stmt->execute([$decoded]);
        $result = $stmt->fetch();

        if ($result) {
            echo "Found perkara: " . $result['nomor_perkara'] . "\n";
        } else {
            echo "Perkara not found in database\n";
        }
    } else {
        echo "Database config not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
