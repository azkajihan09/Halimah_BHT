<?php
/**
 * Fixed Database Install dengan SQL Parser
 */

// Define constants untuk CodeIgniter compatibility
if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(__FILE__) . '/system/');
}
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

require_once('application/config/database.php');

// Konfigurasi database
$host = $db['default']['hostname'];
$user = $db['default']['username'];
$pass = $db['default']['password'];
$db_name = 'bht_reminder_system';

echo "<h2>🔧 FIXED DATABASE INSTALLER</h2>\n";
echo "<p>Installing reminder database dengan SQL parser</p>\n";
echo "<hr>\n";

try {
    // Connect dan create database
    echo "<h3>1. Database Setup</h3>\n";
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name`");
    echo "✅ Database '$db_name' created<br>\n";
    
    $pdo->exec("USE `$db_name`");
    echo "✅ Using database '$db_name'<br>\n";
    
    // Read and parse SQL file
    echo "<h3>2. Parsing SQL File</h3>\n";
    
    $sql_file = 'database_reminder_setup.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("SQL file not found: $sql_file");
    }
    
    $sql_content = file_get_contents($sql_file);
    echo "✅ SQL file loaded (" . strlen($sql_content) . " bytes)<br>\n";
    
    // Simple SQL statement splitter
    $statements = explode(';', $sql_content);
    $executed = 0;
    $skipped = 0;
    
    echo "<h3>3. Executing SQL Statements</h3>\n";
    
    foreach ($statements as $i => $statement) {
        $statement = trim($statement);
        
        // Skip empty statements, comments, and USE statements
        if (empty($statement) || 
            substr($statement, 0, 2) === '--' || 
            substr($statement, 0, 2) === '/*' ||
            stripos($statement, 'USE ') === 0) {
            $skipped++;
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $executed++;
            
            // Show progress for major statements
            if (stripos($statement, 'CREATE TABLE') === 0) {
                preg_match('/CREATE TABLE\s+`?(\w+)`?/i', $statement, $matches);
                $table_name = isset($matches[1]) ? $matches[1] : 'unknown';
                echo "✅ Table '$table_name' created<br>\n";
            } elseif (stripos($statement, 'CREATE VIEW') === 0) {
                preg_match('/CREATE VIEW\s+`?(\w+)`?/i', $statement, $matches);
                $view_name = isset($matches[1]) ? $matches[1] : 'unknown';
                echo "✅ View '$view_name' created<br>\n";
            } elseif (stripos($statement, 'INSERT INTO') === 0) {
                preg_match('/INSERT INTO\s+`?(\w+)`?/i', $statement, $matches);
                $table_name = isset($matches[1]) ? $matches[1] : 'unknown';
                echo "✅ Data inserted into '$table_name'<br>\n";
            }
            
        } catch (PDOException $e) {
            // Log error but continue
            echo "<div style='color: orange; margin: 5px 0;'>⚠️ Warning (Statement " . ($i+1) . "): " . $e->getMessage() . "</div>\n";
            echo "<details><summary>Statement content:</summary><pre>" . htmlspecialchars(substr($statement, 0, 200)) . "...</pre></details>\n";
        }
    }
    
    echo "<h3>4. Installation Summary</h3>\n";
    echo "✅ Executed: $executed statements<br>\n";
    echo "⏭️ Skipped: $skipped statements<br>\n";
    
    // Verify tables created
    echo "<h3>5. Verification</h3>\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p><strong>Created Tables:</strong></p>\n<ul>\n";
    foreach ($tables as $table) {
        echo "<li>✅ $table</li>\n";
    }
    echo "</ul>\n";
    
    echo "<hr>\n";
    echo "<h3>🎉 INSTALLATION COMPLETED!</h3>\n";
    echo "<p><strong>Database '$db_name' is ready to use!</strong></p>\n";
    
    echo "<p><strong>Next Steps:</strong></p>\n";
    echo "<ul>\n";
    echo "<li>🔗 <a href='test_reminder_connection.php'>Test Connections</a></li>\n";
    echo "<li>🏠 <a href='index.php/reminder_logging'>Access Dashboard</a></li>\n";
    echo "<li>🔄 <a href='index.php/reminder_logging/sync_manual'>Manual Sync from SIPP</a></li>\n";
    echo "</ul>\n";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 10px; background-color: #ffe6e6; border-radius: 5px;'>\n";
    echo "<strong>❌ Installation Failed:</strong> " . $e->getMessage() . "\n";
    echo "</div>\n";
    
    echo "<p><strong>Troubleshooting:</strong></p>\n";
    echo "<ul>\n";
    echo "<li>Ensure XAMPP MySQL service is running</li>\n";
    echo "<li>Check database credentials in config/database.php</li>\n";
    echo "<li>Verify MySQL user has CREATE DATABASE privileges</li>\n";
    echo "<li>Try the simple installer: <a href='simple_db_install.php'>simple_db_install.php</a></li>\n";
    echo "</ul>\n";
}

echo "<hr>\n";
echo "<p><em>Installation completed at " . date('Y-m-d H:i:s') . "</em></p>\n";
?>