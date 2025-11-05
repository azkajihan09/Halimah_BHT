<?php

/**
 * Debug Test untuk Menu_baru Controller
 */

// Include CodeIgniter bootstrap
define('BASEPATH', TRUE);
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';

require_once('index.php');

echo "<h1>Debug Menu_baru Controller Test</h1>\n";

try {
    // Load CodeIgniter
    $CI = &get_instance();
    $CI->load->model('Menu_baru_model');

    echo "<h2>✅ CodeIgniter Loaded Successfully</h2>\n";

    // Test method get_jadwal_bht_harian
    echo "<h2>📋 Testing get_jadwal_bht_harian Method</h2>\n";

    $tanggal = date('Y-m-d');
    $jenis = 'semua';
    $tahun_filter = '2024';

    $jadwal_data = $CI->Menu_baru_model->get_jadwal_bht_harian($tanggal, $jenis, $tahun_filter);

    if ($jadwal_data && count($jadwal_data) > 0) {
        echo "<div style='background: #e8f5e8; padding: 10px; margin: 10px 0; border-radius: 5px;'>\n";
        echo "<h3>✅ Method Executed Successfully (" . count($jadwal_data) . " records)</h3>\n";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>\n";
        echo "<tr style='background: #d4edda;'>\n";
        echo "<th>Nomor Perkara</th>\n";
        echo "<th>Jenis Perkara</th>\n";
        echo "<th>Tgl Putusan</th>\n";
        echo "<th>Perkiraan BHT</th>\n";
        echo "<th>Status BHT</th>\n";
        echo "<th>Status Pengisian</th>\n";
        echo "</tr>\n";

        foreach (array_slice($jadwal_data, 0, 5) as $row) {
            echo "<tr>\n";
            echo "<td>" . htmlspecialchars($row->nomor_perkara) . "</td>\n";
            echo "<td>" . htmlspecialchars($row->jenis_perkara) . "</td>\n";
            echo "<td>" . htmlspecialchars($row->tanggal_putusan) . "</td>\n";
            echo "<td>" . htmlspecialchars($row->perkiraan_bht) . "</td>\n";
            echo "<td>" . htmlspecialchars($row->status_bht) . "</td>\n";
            echo "<td>" . htmlspecialchars($row->status_pengisian_bht) . "</td>\n";
            echo "</tr>\n";
        }
        echo "</table>\n";
        echo "</div>\n";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border-radius: 5px;'>\n";
        echo "<h3>⚠️ No Data Returned</h3>\n";
        echo "<p>Method executed but returned empty result</p>\n";
        echo "</div>\n";
    }

    echo "<h2>🎯 Controller Integration Success</h2>\n";
    echo "<div style='background: #d1ecf1; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 5px solid #0c5460;'>\n";
    echo "<h3>✅ CONTROLLER TEST PASSED!</h3>\n";
    echo "<p><strong>Status:</strong> Menu_baru_model can be called from CodeIgniter framework successfully</p>\n";
    echo "<p><strong>Data Retrieved:</strong> " . (isset($jadwal_data) ? count($jadwal_data) : 0) . " records</p>\n";
    echo "<p><strong>Ready for:</strong> Web interface integration</p>\n";
    echo "</div>\n";
} catch (Exception $e) {
    echo "<h2>❌ Controller Error</h2>\n";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>\n";
}

echo "<hr>\n";
echo "<p><em>Controller test completed at: " . date('Y-m-d H:i:s') . "</em></p>\n";
