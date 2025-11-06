<?php
// Simple test for perkara detail URLs
define('BASEPATH', TRUE);
define('ENVIRONMENT', 'development');

require_once 'application/config/database.php';

try {
    // Connect to reminder database
    $reminder_pdo = new PDO(
        "mysql:host={$db['reminder_db']['hostname']};dbname={$db['reminder_db']['database']};charset=utf8",
        $db['reminder_db']['username'],
        $db['reminder_db']['password']
    );

    echo "<h2>Test Perkara Detail URLs</h2>";

    // Get some perkara data
    $query = "SELECT nomor_perkara, jenis_perkara, status_reminder, level_prioritas FROM perkara_reminder LIMIT 5";
    $stmt = $reminder_pdo->prepare($query);
    $stmt->execute();
    $perkaras = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Nomor Perkara</th><th>Jenis</th><th>Status</th><th>Prioritas</th><th>URL Test</th></tr>";

    foreach ($perkaras as $perkara) {
        $encoded_nomor = urlencode($perkara->nomor_perkara);
        $url = "index.php/reminder_logging/perkara_detail/{$encoded_nomor}";

        echo "<tr>";
        echo "<td>" . htmlspecialchars($perkara->nomor_perkara) . "</td>";
        echo "<td>" . htmlspecialchars($perkara->jenis_perkara) . "</td>";
        echo "<td>" . htmlspecialchars($perkara->status_reminder) . "</td>";
        echo "<td>" . htmlspecialchars($perkara->level_prioritas) . "</td>";
        echo "<td><a href='{$url}' target='_blank'>Test Link</a></td>";
        echo "</tr>";
    }

    echo "</table>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
