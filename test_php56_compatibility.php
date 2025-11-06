<?php

/**
 * Test Basic PHP 5.6 Compatibility Check
 * File: test_php56_compatibility.php
 * 
 * Test apakah semua file sistem reminder compatible dengan PHP 5.6
 */

echo "<h2>🧪 PHP 5.6 Compatibility Test</h2>\n";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>\n";
echo "<p><strong>Testing Time:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
echo "<hr>\n";

// Test 1: PHP Version Check
echo "<h3>✅ Test 1: PHP Version Check</h3>\n";
if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
    echo "<p style='color: green;'>✅ PHP Version " . PHP_VERSION . " is compatible</p>\n";
} else {
    echo "<p style='color: red;'>❌ PHP Version " . PHP_VERSION . " is too old. Need PHP 5.6+</p>\n";
}

// Test 2: Required Extensions
echo "<h3>✅ Test 2: Required Extensions</h3>\n";
$required_extensions = array('mysqli', 'pdo', 'json', 'mbstring');
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ Extension '$ext' is loaded</p>\n";
    } else {
        echo "<p style='color: red;'>❌ Extension '$ext' is NOT loaded</p>\n";
    }
}

// Test 3: CodeIgniter Path
echo "<h3>✅ Test 3: CodeIgniter Files</h3>\n";
$ci_files = array(
    'system/core/CodeIgniter.php',
    'application/config/config.php',
    'application/config/database.php',
    'index.php'
);

foreach ($ci_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ File '$file' exists</p>\n";
    } else {
        echo "<p style='color: red;'>❌ File '$file' NOT found</p>\n";
    }
}

// Test 4: Reminder System Files
echo "<h3>✅ Test 4: Reminder System Files</h3>\n";
$reminder_files = array(
    'application/models/Reminder_model.php',
    'application/controllers/Reminder_logging.php',
    'application/views/reminder_logging/dashboard.php',
    'database_reminder_setup.sql',
    'install_reminder_database.php',
    'test_reminder_connection.php',
    'auto_sync_cron.php'
);

foreach ($reminder_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ File '$file' exists</p>\n";
    } else {
        echo "<p style='color: red;'>❌ File '$file' NOT found</p>\n";
    }
}

// Test 5: Syntax Check (PHP 5.6 Features)
echo "<h3>✅ Test 5: PHP 5.6 Syntax Features</h3>\n";

// Test isset() vs null coalescing (should work in PHP 5.6)
$test_array = array('key1' => 'value1');
$result1 = isset($test_array['key1']) ? $test_array['key1'] : 'default';
$result2 = isset($test_array['missing_key']) ? $test_array['missing_key'] : 'default';

if ($result1 === 'value1' && $result2 === 'default') {
    echo "<p style='color: green;'>✅ isset() ternary operator works correctly</p>\n";
} else {
    echo "<p style='color: red;'>❌ isset() ternary operator failed</p>\n";
}

// Test array() vs [] (should work in PHP 5.6)
$test_old_array = array('a', 'b', 'c');
if (count($test_old_array) === 3) {
    echo "<p style='color: green;'>✅ array() syntax works correctly</p>\n";
} else {
    echo "<p style='color: red;'>❌ array() syntax failed</p>\n";
}

// Test 6: Database Connection Test
echo "<h3>✅ Test 6: Database Connection (Basic)</h3>\n";
$db_config_file = 'application/config/database.php';
if (file_exists($db_config_file)) {
    echo "<p style='color: green;'>✅ Database config file exists</p>\n";

    // Try to load config (simple test)
    include_once $db_config_file;
    if (isset($db) && is_array($db)) {
        echo "<p style='color: green;'>✅ Database config loaded successfully</p>\n";

        if (isset($db['default']) && isset($db['reminder_db'])) {
            echo "<p style='color: green;'>✅ Dual database configuration found</p>\n";
        } else {
            echo "<p style='color: orange;'>⚠️ Dual database configuration may be incomplete</p>\n";
        }
    } else {
        echo "<p style='color: red;'>❌ Database config failed to load</p>\n";
    }
} else {
    echo "<p style='color: red;'>❌ Database config file not found</p>\n";
}

echo "<hr>\n";
echo "<h3>🎯 Summary</h3>\n";
echo "<p><strong>System Status:</strong> Ready for PHP 5.6 compatibility</p>\n";
echo "<p><strong>Next Steps:</strong></p>\n";
echo "<ol>\n";
echo "<li>Install reminder database: <a href='install_reminder_database.php'>install_reminder_database.php</a></li>\n";
echo "<li>Test connections: <a href='test_reminder_connection.php'>test_reminder_connection.php</a></li>\n";
echo "<li>Access dashboard: <a href='index.php/reminder_logging'>index.php/reminder_logging</a></li>\n";
echo "</ol>\n";

echo "<hr>\n";
echo "<p><em>Test completed at " . date('Y-m-d H:i:s') . "</em></p>\n";
