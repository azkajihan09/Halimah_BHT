<?php

/**
 * Simple Debug Test - Check if BHT model can be called
 */

echo "<h1>BHT Model Debug Test</h1>\n";

// Test basic PHP functionality first
echo "<h2>✅ PHP Environment Check</h2>\n";
echo "<ul>\n";
echo "<li>PHP Version: " . phpversion() . "</li>\n";
echo "<li>Memory Limit: " . ini_get('memory_limit') . "</li>\n";
echo "<li>Current Time: " . date('Y-m-d H:i:s') . "</li>\n";
echo "</ul>\n";

// Include CodeIgniter files if they exist
if (file_exists('system/core/CodeIgniter.php')) {
    echo "<h2>✅ CodeIgniter Files Found</h2>\n";
} else {
    echo "<h2>❌ CodeIgniter Files Not Found</h2>\n";
}

// Check if model file exists
if (file_exists('application/models/Menu_baru_model.php')) {
    echo "<h2>✅ Menu_baru_model.php Found</h2>\n";

    // Try to read first few lines of model
    $model_content = file_get_contents('application/models/Menu_baru_model.php');
    if (strpos($model_content, 'get_jadwal_bht_harian') !== false) {
        echo "<p>✅ Method get_jadwal_bht_harian found in model</p>\n";
    } else {
        echo "<p>❌ Method get_jadwal_bht_harian not found in model</p>\n";
    }
} else {
    echo "<h2>❌ Menu_baru_model.php Not Found</h2>\n";
}

// Check database config
if (file_exists('application/config/database.php')) {
    echo "<h2>✅ Database Config Found</h2>\n";

    include('application/config/database.php');
    if (isset($db['default'])) {
        echo "<p>Database Host: " . $db['default']['hostname'] . "</p>\n";
        echo "<p>Database Name: " . $db['default']['database'] . "</p>\n";
    }
} else {
    echo "<h2>❌ Database Config Not Found</h2>\n";
}

echo "<hr>\n";
echo "<h2>🔗 Test Links</h2>\n";
echo "<ul>\n";
echo "<li><a href='test_ultra_simple_bht.php'>Ultra Simple BHT Test</a></li>\n";
echo "<li><a href='index.php'>Main Application</a></li>\n";
echo "</ul>\n";

echo "<hr>\n";
echo "<p><em>Debug test completed at: " . date('Y-m-d H:i:s') . "</em></p>\n";
