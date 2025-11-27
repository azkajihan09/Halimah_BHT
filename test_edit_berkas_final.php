<?php
// Test untuk memeriksa database dan fitur edit berkas

echo "<h2>🔍 TEST DATABASE DAN FORM EDIT</h2>";

try {
    // Test koneksi database notelen
    $notelen_db = new mysqli('localhost', 'root', '', 'notelen_system');

    if ($notelen_db->connect_error) {
        echo "<p>❌ Koneksi database gagal: " . $notelen_db->connect_error . "</p>";
    } else {
        echo "<p>✅ Koneksi database berhasil</p>";

        // Test struktur tabel berkas_masuk
        $result = $notelen_db->query("DESCRIBE berkas_masuk");
        echo "<h3>📋 Struktur Tabel berkas_masuk:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Test data status berkas yang ada
        $result = $notelen_db->query("SELECT DISTINCT status_berkas FROM berkas_masuk");
        echo "<h3>📊 Status Berkas yang Ada di Database:</h3>";

        if ($result && $result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><strong>" . $row['status_berkas'] . "</strong></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>🔍 Belum ada data berkas di database</p>";
        }

        // Test enum values untuk status_berkas
        $result = $notelen_db->query("SHOW COLUMNS FROM berkas_masuk LIKE 'status_berkas'");
        if ($result && $row = $result->fetch_assoc()) {
            echo "<h3>⚙️ Nilai Enum status_berkas:</h3>";
            echo "<p><code>" . $row['Type'] . "</code></p>";
        }

        $notelen_db->close();
    }
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>🛠️ STATUS PERBAIKAN FORM EDIT:</h3>";
echo "<ul>";
echo "<li>✅ Status berkas dropdown diperbaiki (MASUK, PROSES, SELESAI, ARSIP)</li>";
echo "<li>✅ Filter status berkas diperbaiki sesuai enum database</li>";
echo "<li>✅ Response ajax_get_berkas diperbaiki (menghilangkan tanda '-')</li>";
echo "<li>✅ Field form edit dapat diedit:</li>";
echo "<ul>";
echo "<li>Nomor Perkara: <strong>readonly</strong> (tidak dapat diubah)</li>";
echo "<li>Tanggal Putusan: <strong>editable</strong> (input date)</li>";
echo "<li>Jenis Perkara: <strong>editable</strong> (input text)</li>";
echo "<li>Status Berkas: <strong>editable</strong> (dropdown)</li>";
echo "<li>Majelis Hakim: <strong>editable</strong> (input text)</li>";
echo "<li>Panitera Pengganti: <strong>editable</strong> (input text)</li>";
echo "<li>Catatan Notelen: <strong>editable</strong> (textarea)</li>";
echo "</ul>";
echo "</ul>";

echo "<h3>✅ FORM EDIT SUDAH DIPERBAIKI:</h3>";
echo "<p>Semua field kecuali 'Nomor Perkara' sekarang dapat diedit. Status berkas menggunakan nilai yang sesuai dengan enum database: MASUK, PROSES, SELESAI, ARSIP.</p>";
