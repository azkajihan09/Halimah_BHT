<?php

/**
 * Simple test controller access
 */

echo "Testing Controller Access...\n";

// Test if we can load CodeIgniter and access controller
$_SERVER['REQUEST_URI'] = '/Halimah_BHT/index.php/reminder_logging';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SCRIPT_NAME'] = '/Halimah_BHT/index.php';

// Try to include index.php and catch any errors
ob_start();
try {
    include 'index.php';
    $output = ob_get_contents();
    echo "✅ Controller loaded successfully\n";
    echo "Output length: " . strlen($output) . " characters\n";
} catch (Exception $e) {
    echo "❌ Error loading controller: " . $e->getMessage() . "\n";
} catch (Throwable $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "\n";
}
ob_end_clean();
