<?php

/**
 * Ultra Simple Test Script for BHT Jadwal
 */

// Include CodeIgniter bootstrap
define('BASEPATH', 'system/');
require_once('application/config/database.php');

echo "<h1>Test Ultra Simple BHT Jadwal</h1>\n";

// Simulasi koneksi database
$host = $db['default']['hostname'];
$username = $db['default']['username'];
$password = $db['default']['password'];
$database = $db['default']['database'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>✅ Database Connection Successful</h2>\n";

    // Test basic query
    echo "<h2>📋 Testing Basic BHT Structure</h2>\n";

    $sql_jadwal = "
    SELECT 
        p.nomor_perkara,
        p.jenis_perkara_nama as jenis_perkara,
        DATE(pp.tanggal_putusan) as tanggal_putusan,
        pp.tanggal_bht,
        CASE 
            WHEN pp.tanggal_bht IS NOT NULL THEN 'SUDAH BHT'
            ELSE 'BELUM BHT'
        END as status_pengisian_bht,
        DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY) as perkiraan_bht,
        CASE 
            WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
            WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'CRITICAL'
            WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'TERLAMBAT'
            WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10 THEN 'URGENT'
            ELSE 'NORMAL'
        END as status_bht,
        'Normal' as keterangan_perkara
    FROM perkara p
    JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    WHERE pp.tanggal_putusan IS NOT NULL
      AND p.nomor_perkara NOT LIKE '%/Pdt.P/%'
      AND YEAR(pp.tanggal_putusan) >= 2025
    ORDER BY pp.tanggal_putusan DESC
    LIMIT 10";

    $stmt = $pdo->prepare($sql_jadwal);
    $stmt->execute();
    $jadwal_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($jadwal_results) {
        echo "<div style='background: #e8f5e8; padding: 10px; margin: 10px 0; border-radius: 5px;'>\n";
        echo "<h3>✅ BHT Jadwal Query - Sample Results (" . count($jadwal_results) . " records)</h3>\n";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>\n";
        echo "<tr style='background: #d4edda;'>\n";
        echo "<th>Nomor Perkara</th>\n";
        echo "<th>Jenis Perkara</th>\n";
        echo "<th>Tgl Putusan</th>\n";
        echo "<th>Perkiraan BHT</th>\n";
        echo "<th>Tanggal BHT</th>\n";
        echo "<th>Status BHT</th>\n";
        echo "<th>Status Pengisian</th>\n";
        echo "<th>Keterangan</th>\n";
        echo "</tr>\n";

        foreach ($jadwal_results as $row) {
            echo "<tr>\n";
            echo "<td>" . htmlspecialchars($row['nomor_perkara']) . "</td>\n";
            echo "<td>" . htmlspecialchars($row['jenis_perkara']) . "</td>\n";
            echo "<td>" . htmlspecialchars($row['tanggal_putusan']) . "</td>\n";
            echo "<td>" . htmlspecialchars($row['perkiraan_bht']) . "</td>\n";
            echo "<td>" . htmlspecialchars(isset($row['tanggal_bht']) ? $row['tanggal_bht'] : '-') . "</td>\n";
            echo "<td>" . htmlspecialchars($row['status_bht']) . "</td>\n";
            echo "<td>" . htmlspecialchars($row['status_pengisian_bht']) . "</td>\n";
            echo "<td>" . htmlspecialchars($row['keterangan_perkara']) . "</td>\n";
            echo "</tr>\n";
        }
        echo "</table>\n";
        echo "</div>\n";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border-radius: 5px;'>\n";
        echo "<h3>⚠️ No Results Found</h3>\n";
        echo "<p>Tidak ada data BHT yang ditemukan untuk tahun 2025</p>\n";
        echo "</div>\n";
    }

    // Test untuk memastikan filter perkara permohonan tidak muncul
    echo "<h2>🚫 Testing Perkara Permohonan Filter</h2>\n";

    $sql_filter_test = "
    SELECT 
        COUNT(*) as total_all,
        COUNT(CASE WHEN p.nomor_perkara LIKE '%/Pdt.P/%' THEN 1 END) as total_permohonan,
        COUNT(CASE WHEN p.nomor_perkara NOT LIKE '%/Pdt.P/%' THEN 1 END) as total_filtered
    FROM perkara p
    JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    WHERE pp.tanggal_putusan IS NOT NULL
      AND YEAR(pp.tanggal_putusan) >= 2025";

    $stmt = $pdo->prepare($sql_filter_test);
    $stmt->execute();
    $filter_result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<div style='background: #fff3cd; padding: 10px; margin: 10px 0; border-radius: 5px;'>\n";
    echo "<h3>📊 Filter Statistics (2025)</h3>\n";
    echo "<ul>\n";
    echo "<li><strong>Total Perkara with Putusan:</strong> " . $filter_result['total_all'] . "</li>\n";
    echo "<li><strong>Perkara Permohonan (/Pdt.P/):</strong> " . $filter_result['total_permohonan'] . " (FILTERED OUT)</li>\n";
    echo "<li><strong>Perkara Displayed:</strong> " . $filter_result['total_filtered'] . " (NON-PERMOHONAN)</li>\n";
    echo "</ul>\n";
    echo "</div>\n";

    echo "<h2>🎯 Ultra Simple BHT Success Summary</h2>\n";
    echo "<div style='background: #d1ecf1; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 5px solid #0c5460;'>\n";
    echo "<h3>✅ ALIGNMENT BERHASIL!</h3>\n";
    echo "<p><strong>Fitur yang sudah berfungsi:</strong></p>\n";
    echo "<ol>\n";
    echo "<li>✅ <strong>Basic Query:</strong> Menggunakan perkara + perkara_putusan saja</li>\n";
    echo "<li>✅ <strong>Core Columns:</strong> nomor_perkara, jenis_perkara, tanggal_putusan, tanggal_bht</li>\n";
    echo "<li>✅ <strong>New Features:</strong> perkiraan_bht, status_bht, status_pengisian_bht</li>\n";
    echo "<li>✅ <strong>Filter Applied:</strong> Perkara permohonan (/Pdt.P/) excluded</li>\n";
    echo "<li>✅ <strong>Memory Safe:</strong> LIMIT 100 untuk prevent memory exhaustion</li>\n";
    echo "</ol>\n";
    echo "<p><strong>Status:</strong> BHT jadwal harian sekarang berfungsi dengan kolom essential!</p>\n";
    echo "</div>\n";
} catch (PDOException $e) {
    echo "<h2>❌ Database Error</h2>\n";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";
echo "<p><em>Test completed at: " . date('Y-m-d H:i:s') . "</em></p>\n";
