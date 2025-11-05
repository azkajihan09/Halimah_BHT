<?php

/**
 * Test PHP Syntax - Debug HTTP 500 Error
 */

// Test basic PHP
echo "<h1>PHP Syntax Test</h1>\n";

// Test CodeIgniter bootstrap
define('BASEPATH', TRUE);
define('ENVIRONMENT', 'development');

// Test database config
$config_path = __DIR__ . '/application/config/database.php';
if (file_exists($config_path)) {
    echo "<p>✅ Database config found</p>\n";
    try {
        include $config_path;
        echo "<p>✅ Database config loaded</p>\n";
    } catch (Exception $e) {
        echo "<p>❌ Database config error: " . $e->getMessage() . "</p>\n";
    }
} else {
    echo "<p>❌ Database config not found</p>\n";
}

// Test model syntax
$model_path = __DIR__ . '/application/models/Menu_baru_model.php';
if (file_exists($model_path)) {
    echo "<p>✅ Model file found</p>\n";
    try {
        // Only check syntax, don't execute
        $content = file_get_contents($model_path);
        if (strpos($content, '<?php') !== false) {
            echo "<p>✅ Model has PHP opening tag</p>\n";
        }

        // Basic syntax check - try to parse
        $output = shell_exec("php -l '$model_path' 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "<p>✅ Model syntax is valid</p>\n";
        } else {
            echo "<p>❌ Model syntax error: " . htmlspecialchars($output) . "</p>\n";
        }
    } catch (Exception $e) {
        echo "<p>❌ Model syntax error: " . $e->getMessage() . "</p>\n";
    }
} else {
    echo "<p>❌ Model file not found</p>\n";
}

// Test controller syntax
$controller_path = __DIR__ . '/application/controllers/Menu_baru.php';
if (file_exists($controller_path)) {
    echo "<p>✅ Controller file found</p>\n";
    try {
        $output = shell_exec("php -l '$controller_path' 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "<p>✅ Controller syntax is valid</p>\n";
        } else {
            echo "<p>❌ Controller syntax error: " . htmlspecialchars($output) . "</p>\n";
        }
    } catch (Exception $e) {
        echo "<p>❌ Controller syntax error: " . $e->getMessage() . "</p>\n";
    }
} else {
    echo "<p>❌ Controller file not found</p>\n";
}

echo "<p><strong>Test completed at: " . date('Y-m-d H:i:s') . "</strong></p>\n";

// Test simple database connection
try {
    $host = 'localhost';
    $database = 'sippi_dev';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    echo "<p>✅ Database connection successful</p>\n";
} catch (PDOException $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>\n";
}
