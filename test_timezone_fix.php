<?php
/**
 * Test file untuk memverifikasi pengaturan timezone
 * File ini menguji apakah warning timezone sudah diperbaiki
 */

echo "=== TEST TIMEZONE SETTING ===\n\n";

// Test 1: Cek current timezone
echo "1. Current Timezone: " . date_default_timezone_get() . "\n";

// Test 2: Test fungsi date() tanpa warning
echo "2. Current Date/Time: " . date('Y-m-d H:i:s') . "\n";

// Test 3: Test berbagai format tanggal
echo "3. Formatted Dates:\n";
echo "   - Indonesian format: " . date('d-m-Y H:i:s') . "\n";
echo "   - Day name: " . date('l, d F Y') . "\n";
echo "   - Timestamp: " . time() . "\n";

// Test 4: Test DateTime class
echo "4. DateTime Object:\n";
$datetime = new DateTime();
echo "   - DateTime now: " . $datetime->format('Y-m-d H:i:s') . "\n";
echo "   - Timezone: " . $datetime->getTimezone()->getName() . "\n";

// Test 5: Test dengan berbagai fungsi terkait waktu
echo "5. Other Time Functions:\n";
echo "   - strtotime('now'): " . date('Y-m-d H:i:s', strtotime('now')) . "\n";
echo "   - mktime(): " . date('Y-m-d H:i:s', mktime()) . "\n";

echo "\n=== TEST COMPLETED ===\n";

// Test untuk CodeIgniter (jika diakses via browser)
if (!defined('BASEPATH')) {
    echo "\nNote: Jika tidak ada warning timezone di atas, maka pengaturan sudah berhasil!\n";
}
?>