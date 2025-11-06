<?php
// Check reminder_log table structure
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

    echo "✅ Reminder Database Connected: {$db['reminder_db']['database']}\n";

    // Check reminder_log table structure
    $desc_query = "DESCRIBE reminder_log";
    $stmt = $reminder_pdo->prepare($desc_query);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "📋 reminder_log table structure:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} | {$column['Type']} | {$column['Null']} | {$column['Key']}\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
