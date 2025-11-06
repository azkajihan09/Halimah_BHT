<?php

/**
 * Test koneksi database reminder system
 * Memastikan kedua database (SIPP dan Reminder) dapat terhubung
 */

require_once('application/config/database.php');

echo "<h2>🔍 TEST KONEKSI DATABASE REMINDER SYSTEM</h2>";
echo "<hr>";

// Test Database SIPP (default)
echo "<h3>1. Test Database SIPP (Primary)</h3>";
try {
    $sipp_host = $db['default']['hostname'];
    $sipp_user = $db['default']['username'];
    $sipp_pass = $db['default']['password'];
    $sipp_db = $db['default']['database'];

    $pdo_sipp = new PDO("mysql:host=$sipp_host;dbname=$sipp_db;charset=utf8", $sipp_user, $sipp_pass);
    $pdo_sipp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test query
    $stmt = $pdo_sipp->query("SELECT COUNT(*) as total FROM perkara LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "✅ <strong>Koneksi SIPP berhasil!</strong><br>";
    echo "   Database: <strong>{$sipp_db}</strong><br>";
    echo "   Host: {$sipp_host}<br>";
    echo "   Status: Connected<br>";
    echo "   Test query: OK<br>";
} catch (Exception $e) {
    echo "❌ <strong>Koneksi SIPP gagal:</strong> " . $e->getMessage() . "<br>";
}

echo "<br>";

// Test Database Reminder
echo "<h3>2. Test Database Reminder (Secondary)</h3>";
try {
    $reminder_host = $db['reminder_db']['hostname'];
    $reminder_user = $db['reminder_db']['username'];
    $reminder_pass = $db['reminder_db']['password'];
    $reminder_db = $db['reminder_db']['database'];

    $pdo_reminder = new PDO("mysql:host=$reminder_host;dbname=$reminder_db;charset=utf8", $reminder_user, $reminder_pass);
    $pdo_reminder->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test query dan cek struktur tabel
    $tables = array('perkara_reminder', 'reminder_log', 'pbt_tracking', 'reminder_statistics', 'reminder_config');

    echo "✅ <strong>Koneksi Reminder berhasil!</strong><br>";
    echo "   Database: <strong>{$reminder_db}</strong><br>";
    echo "   Host: {$reminder_host}<br>";
    echo "   Status: Connected<br>";

    echo "<br><strong>📋 Struktur Tabel:</strong><br>";
    foreach ($tables as $table) {
        try {
            $stmt = $pdo_reminder->query("SELECT COUNT(*) as count FROM `$table`");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "   ✅ $table: {$result['count']} records<br>";
        } catch (Exception $e) {
            echo "   ❌ $table: Table tidak ditemukan<br>";
        }
    }

    // Test views
    echo "<br><strong>📊 Views:</strong><br>";
    $views = array('v_reminder_dashboard', 'v_perkara_urgent');
    foreach ($views as $view) {
        try {
            $stmt = $pdo_reminder->query("SELECT COUNT(*) as count FROM `$view`");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "   ✅ $view: {$result['count']} records<br>";
        } catch (Exception $e) {
            echo "   ❌ $view: View tidak ditemukan<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ <strong>Koneksi Reminder gagal:</strong> " . $e->getMessage() . "<br>";
    echo "<p><strong>Solusi:</strong> Jalankan <a href='install_reminder_database.php'>install_reminder_database.php</a> terlebih dahulu</p>";
}

echo "<hr>";

// Test Dual Database Functionality
echo "<h3>3. Test Dual Database Functionality</h3>";

if (isset($pdo_sipp) && isset($pdo_reminder)) {
    try {
        // Test query dari SIPP
        $sipp_query = "
            SELECT COUNT(*) as total_perkara
            FROM perkara p
            INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            WHERE pp.tanggal_putusan IS NOT NULL
            AND YEAR(pp.tanggal_putusan) >= 2024
        ";

        $stmt = $pdo_sipp->prepare($sipp_query);
        $stmt->execute();
        $sipp_result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Test query dari Reminder
        $reminder_query = "SELECT COUNT(*) as total_reminder FROM perkara_reminder";
        $stmt = $pdo_reminder->prepare($reminder_query);
        $stmt->execute();
        $reminder_result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "✅ <strong>Dual Database Test berhasil!</strong><br>";
        echo "   SIPP - Total perkara (2024+): <strong>{$sipp_result['total_perkara']}</strong><br>";
        echo "   Reminder - Total reminder: <strong>{$reminder_result['total_reminder']}</strong><br>";

        // Cek status sinkronisasi
        if ($reminder_result['total_reminder'] == 0) {
            echo "<br><div style='background-color: #fff3cd; padding: 10px; border-radius: 5px;'>";
            echo "⚠️  <strong>Perlu Sinkronisasi:</strong><br>";
            echo "Database reminder masih kosong. Lakukan sinkronisasi data dari SIPP:<br>";
            echo "<a href='index.php/reminder_logging' class='btn'>Dashboard Reminder</a>";
            echo "</div>";
        } else {
            echo "<br>✅ Data sudah tersinkronisasi";
        }
    } catch (Exception $e) {
        echo "❌ <strong>Dual Database Test gagal:</strong> " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Tidak dapat melakukan dual database test - koneksi gagal<br>";
}

echo "<hr>";

// Test Configuration
echo "<h3>4. Test Konfigurasi Sistem</h3>";

if (isset($pdo_reminder)) {
    try {
        $stmt = $pdo_reminder->query("SELECT config_key, config_value FROM reminder_config");
        $configs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "✅ <strong>Konfigurasi sistem:</strong><br>";
        foreach ($configs as $config) {
            echo "   • {$config['config_key']}: <strong>{$config['config_value']}</strong><br>";
        }
    } catch (Exception $e) {
        echo "❌ <strong>Test konfigurasi gagal:</strong> " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";

// Summary dan Recommendations
echo "<h3>🎯 RINGKASAN & REKOMENDASI</h3>";

echo "<div style='background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h4>✅ Status Sistem:</h4>";
if (isset($pdo_sipp) && isset($pdo_reminder)) {
    echo "• Koneksi Database SIPP: <span style='color: green;'>✅ OK</span><br>";
    echo "• Koneksi Database Reminder: <span style='color: green;'>✅ OK</span><br>";
    echo "• Dual Database System: <span style='color: green;'>✅ Ready</span><br>";
} else {
    echo "• Ada masalah dengan koneksi database<br>";
}
echo "</div>";

echo "<div style='background-color: #cce7ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h4>🔗 Quick Actions:</h4>";
echo "• <a href='index.php/reminder_logging'>🏠 Dashboard Sistem Reminder</a><br>";
echo "• <a href='index.php/reminder_logging/perkara_list'>📋 Daftar Perkara Reminder</a><br>";
echo "• <a href='index.php/bht_reminder'>📊 Dashboard BHT (SIPP)</a><br>";
if (isset($reminder_result) && $reminder_result['total_reminder'] == 0) {
    echo "• <strong>🔄 Lakukan Sync Manual dari Dashboard Reminder</strong><br>";
}
echo "</div>";

echo "<div style='background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h4>📝 Catatan Sistem:</h4>";
echo "• Database utama (SIPP): <strong>{$sipp_db}</strong> - Read Only Access<br>";
echo "• Database reminder: <strong>bht_reminder_system</strong> - Full Access<br>";
echo "• Sistem pencatatan terpisah untuk tracking reminder BHT<br>";
echo "• Data akan disinkronisasi otomatis/manual dari SIPP<br>";
echo "</div>";

echo "<br><p><em>Test completed at: " . date('Y-m-d H:i:s') . "</em></p>";
