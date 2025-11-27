<!DOCTYPE html>
<html>

<head>
    <title>Test Status Berkas Final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">

    <h2>🔍 TEST STATUS BERKAS FINAL</h2>

    <?php
    // Database connection
    $db = new mysqli('localhost', 'root', '', 'notelen_system');
    if ($db->connect_error) {
        die("❌ Koneksi database gagal: " . $db->connect_error);
    }
    echo "<p>✅ Koneksi database berhasil</p>";

    // Check table structure
    echo "<h3>📋 Status Berkas Column:</h3>";
    $result = $db->query("SHOW COLUMNS FROM berkas_masuk WHERE Field = 'status_berkas'");
    if ($row = $result->fetch_assoc()) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Default</th></tr>";
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Default']}</td></tr>";
        echo "</table>";
    }

    // Check data
    echo "<h3>📊 Current Berkas Data:</h3>";
    $result = $db->query("SELECT id, nomor_perkara, status_berkas, tanggal_putusan, jenis_perkara FROM berkas_masuk ORDER BY id DESC LIMIT 5");

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>";
        echo "<tr><th>ID</th><th>Nomor Perkara</th><th>Status</th><th>Tanggal Putusan</th><th>Jenis Perkara</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $status = $row['status_berkas'];
            $badge_color = '';
            switch ($status) {
                case 'MASUK':
                    $badge_color = 'badge-primary';
                    break;
                case 'PROSES':
                    $badge_color = 'badge-warning';
                    break;
                case 'SELESAI':
                    $badge_color = 'badge-success';
                    break;
                case 'ARSIP':
                    $badge_color = 'badge-secondary';
                    break;
                default:
                    $badge_color = 'badge-light';
                    break;
            }

            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['nomor_perkara']}</td>";
            echo "<td><span class='badge {$badge_color}'>{$status}</span></td>";
            echo "<td>{$row['tanggal_putusan']}</td>";
            echo "<td>{$row['jenis_perkara']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>⚠️ Belum ada data berkas</p>";
    }

    // Add sample data if empty
    if ($result->num_rows == 0) {
        echo "<h3>➕ Menambah Data Sample:</h3>";
        $insert_sql = "INSERT INTO berkas_masuk (nomor_perkara, perkara_id_sipp, jenis_perkara, tanggal_putusan, tanggal_masuk_notelen, status_berkas, majelis_hakim, panitera_pengganti) VALUES 
    ('123/Pdt.G/2024/PA.Smg', 12345, 'Cerai Gugat', '2024-11-20', '2024-11-25', 'MASUK', 'Dr. H. Ahmad Subhi, S.H., M.H.', 'Dra. Siti Nurjanah'),
    ('124/Pdt.G/2024/PA.Smg', 12346, 'Cerai Talak', '2024-11-21', '2024-11-25', 'PROSES', 'Dr. H. Ahmad Subhi, S.H., M.H.', 'Dra. Siti Nurjanah'),
    ('125/Pdt.G/2024/PA.Smg', 12347, 'Dispensasi Nikah', '2024-11-22', '2024-11-25', 'SELESAI', 'Dr. H. Ahmad Subhi, S.H., M.H.', 'Dra. Siti Nurjanah')";

        if ($db->query($insert_sql)) {
            echo "<p>✅ Sample data berhasil ditambahkan</p>";

            // Show the new data
            $result = $db->query("SELECT id, nomor_perkara, status_berkas, tanggal_putusan, jenis_perkara FROM berkas_masuk ORDER BY id DESC LIMIT 5");
            echo "<h4>🔄 Data Setelah Insert:</h4>";
            echo "<table class='table table-striped'>";
            echo "<tr><th>ID</th><th>Nomor Perkara</th><th>Status</th><th>Tanggal Putusan</th><th>Jenis Perkara</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $status = $row['status_berkas'];
                $badge_color = '';
                switch ($status) {
                    case 'MASUK':
                        $badge_color = 'badge-primary';
                        break;
                    case 'PROSES':
                        $badge_color = 'badge-warning';
                        break;
                    case 'SELESAI':
                        $badge_color = 'badge-success';
                        break;
                    case 'ARSIP':
                        $badge_color = 'badge-secondary';
                        break;
                    default:
                        $badge_color = 'badge-light';
                        break;
                }

                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['nomor_perkara']}</td>";
                echo "<td><span class='badge {$badge_color}'>{$status}</span></td>";
                echo "<td>{$row['tanggal_putusan']}</td>";
                echo "<td>{$row['jenis_perkara']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>❌ Error: " . $db->error . "</p>";
        }
    }

    $db->close();
    ?>

    <hr>

    <h3>🎯 RINGKASAN PERBAIKAN TERBARU:</h3>
    <ul class="list-group">
        <li class="list-group-item list-group-item-success">
            ✅ <strong>Status Berkas Column</strong> - Kolom status dengan badge warna ditambahkan ke tabel
        </li>
        <li class="list-group-item list-group-item-success">
            ✅ <strong>Table Header</strong> - Header "Status Berkas" dengan width yang tepat
        </li>
        <li class="list-group-item list-group-item-success">
            ✅ <strong>Badge Colors</strong> - Status dengan warna: MASUK (biru), PROSES (kuning), SELESAI (hijau), ARSIP (abu-abu)
        </li>
        <li class="list-group-item list-group-item-success">
            ✅ <strong>Form Edit Fields</strong> - Semua field editable kecuali Nomor Perkara (readonly)
        </li>
        <li class="list-group-item list-group-item-success">
            ✅ <strong>Database Schema</strong> - Enum status_berkas('MASUK','PROSES','SELESAI','ARSIP') dengan default 'MASUK'
        </li>
    </ul>

    <p class="mt-3">
        <a href="../index.php/notelen/berkas_template" class="btn btn-primary">🔗 Buka Halaman Utama Notelen</a>
    </p>

</body>

</html>