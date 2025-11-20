<?php
// Simple test untuk delete function
echo "<h2>Test Delete Function</h2>";

// Test manual delete dengan menghitung berapa data yang akan dihapus
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'notelen_system';

try {
	$pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Cek jumlah data
	$stmt = $pdo->query("SELECT COUNT(*) as total FROM berkas_masuk");
	$count = $stmt->fetch(PDO::FETCH_ASSOC);
	echo "<p>Total berkas saat ini: " . $count['total'] . "</p>";

	// Ambil sample data
	$stmt = $pdo->query("SELECT * FROM berkas_masuk LIMIT 5");
	$berkas_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo "<h3>Sample Data:</h3>";
	echo "<table border='1' cellpadding='5'>";
	echo "<tr><th>ID</th><th>Nomor Perkara</th><th>Tanggal Putusan</th><th>Action</th></tr>";

	foreach ($berkas_list as $berkas) {
		echo "<tr>";
		echo "<td>" . $berkas['id'] . "</td>";
		echo "<td>" . $berkas['nomor_perkara'] . "</td>";
		echo "<td>" . $berkas['tanggal_putusan'] . "</td>";
		echo "<td>";
		echo "<a href='notelen/ajax_delete_berkas?id=" . $berkas['id'] . "&redirect=1' onclick=\"return confirm('Hapus berkas ini?')\">Delete</a>";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";

	// Insert dummy data untuk testing
	if (isset($_GET['insert_dummy'])) {
		$dummy_data = array(
			'nomor_perkara' => 'TEST/' . time() . '/PDT.G/2025/PA.Test',
			'tanggal_putusan' => date('Y-m-d'),
			'jenis_perkara' => 'Test Case',
			'status_masuk' => 'Masuk',
			'majelis_hakim' => 'Test Hakim',
			'panitera' => 'Test Panitera',
			'catatan_notelen' => 'Data testing untuk delete function',
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => 'test_user'
		);

		$sql = "INSERT INTO berkas_masuk (nomor_perkara, tanggal_putusan, jenis_perkara, status_masuk, majelis_hakim, panitera, catatan_notelen, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $pdo->prepare($sql);
		$result = $stmt->execute(array_values($dummy_data));

		if ($result) {
			echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
			echo "✅ Dummy data berhasil ditambahkan!";
			echo "</div>";
			echo "<script>setTimeout(() => window.location.reload(), 1000);</script>";
		}
	}

	echo "<p><a href='?insert_dummy=1'>Insert Dummy Data</a></p>";
	echo "<p><a href='notelen/berkas_template'>Ke Halaman Berkas Template</a></p>";
} catch (PDOException $e) {
	echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
	echo "❌ Database Error: " . $e->getMessage();
	echo "</div>";
}
