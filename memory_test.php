<?php
// Simple test for jadwal_bht_harian with limited data
ini_set('memory_limit', '256M'); // Increase memory limit

echo "<h1>Jadwal BHT Test - Memory Issue Fix</h1>\n";

// Include CodeIgniter
define('BASEPATH', TRUE);
require_once('application/config/database.php');

try {
	$pdo = new PDO(
		"mysql:host={$db['default']['hostname']};dbname={$db['default']['database']}",
		$db['default']['username'],
		$db['default']['password']
	);

	echo "<h2>✅ Testing Simplified Query</h2>\n";

	// Simplified query with LIMIT
	$sql = "
    SELECT 
        p.nomor_perkara,
        p.jenis_perkara_nama as jenis_perkara,
        DATE(pp.tanggal_putusan) as tanggal_putusan,
        pp.tanggal_bht,
        CASE 
            WHEN pp.tanggal_bht IS NOT NULL THEN 'SUDAH BHT'
            ELSE 'BELUM BHT'
        END as status_bht
    FROM perkara p
    JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    WHERE pp.tanggal_putusan IS NOT NULL
      AND p.nomor_perkara NOT LIKE '%/Pdt.P/%'
      AND YEAR(pp.tanggal_putusan) >= 2024
    ORDER BY pp.tanggal_putusan DESC
    LIMIT 10";

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo "<p><strong>Found " . count($results) . " records (limited to 10)</strong></p>\n";

	if ($results) {
		echo "<table border='1'>\n";
		echo "<tr><th>Nomor Perkara</th><th>Jenis</th><th>Tanggal Putusan</th><th>Tanggal BHT</th><th>Status</th></tr>\n";
		foreach ($results as $row) {
			echo "<tr>\n";
			echo "<td>" . htmlspecialchars($row['nomor_perkara']) . "</td>\n";
			echo "<td>" . htmlspecialchars($row['jenis_perkara']) . "</td>\n";
			echo "<td>" . htmlspecialchars($row['tanggal_putusan']) . "</td>\n";
			echo "<td>" . htmlspecialchars($row['tanggal_bht'] ? $row['tanggal_bht'] : '-') . "</td>\n";
			echo "<td>" . htmlspecialchars($row['status_bht']) . "</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}

	// Test count
	$count_sql = "
    SELECT COUNT(*) as total
    FROM perkara p
    JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    WHERE pp.tanggal_putusan IS NOT NULL
      AND p.nomor_perkara NOT LIKE '%/Pdt.P/%'
      AND YEAR(pp.tanggal_putusan) >= 2024";

	$stmt = $pdo->prepare($count_sql);
	$stmt->execute();
	$count = $stmt->fetch(PDO::FETCH_ASSOC);

	echo "<p><strong>Total Records: " . $count['total'] . "</strong></p>\n";

	if ($count['total'] > 1000) {
		echo "<div style='background: #ffeeee; padding: 10px; border: 1px solid red;'>\n";
		echo "<h3>⚠️ WARNING: Too Many Records</h3>\n";
		echo "<p>Database contains " . $count['total'] . " records. This may cause memory issues.</p>\n";
		echo "<p><strong>Solutions:</strong></p>\n";
		echo "<ul>\n";
		echo "<li>Add pagination (LIMIT/OFFSET)</li>\n";
		echo "<li>Add date filters</li>\n";
		echo "<li>Increase PHP memory limit</li>\n";
		echo "<li>Simplify SELECT columns</li>\n";
		echo "</ul>\n";
		echo "</div>\n";
	}
} catch (Exception $e) {
	echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
}

echo "<p><em>Test completed: " . date('Y-m-d H:i:s') . "</em></p>\n";
