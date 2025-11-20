<?php
// Test script untuk delete berkas
require_once 'index.php';

// Test insert dummy data
$CI = &get_instance();
$CI->load->model('Notelen_model', 'notelen');

echo "<h2>Test Delete Berkas</h2>";

// Insert dummy data
$dummy_data = array(
    'nomor_perkara' => 'TEST/001/PDT.G/2025/PA.Test',
    'tanggal_putusan' => '2025-01-01',
    'jenis_perkara' => 'Perceraian',
    'status_masuk' => 'Masuk',
    'majelis_hakim' => 'Dr. H. Test Hakim, S.H., M.H.',
    'panitera' => 'Test Panitera, S.H.',
    'catatan_notelen' => 'Test data untuk testing delete function',
    'created_at' => date('Y-m-d H:i:s'),
    'created_by' => 'test_user'
);

$result = $CI->notelen->insert_berkas_masuk($dummy_data);

if ($result) {
    echo "<p>✅ Dummy data berhasil ditambahkan dengan ID: " . $result . "</p>";
    echo "<p><a href='" . base_url('notelen/berkas_template') . "'>Lihat Daftar Berkas</a></p>";
    echo "<p><a href='" . base_url('notelen/ajax_delete_berkas?id=' . $result . '&redirect=1') . "'>Test Delete Berkas ID: " . $result . "</a></p>";
} else {
    echo "<p>❌ Gagal menambahkan dummy data</p>";
}

// Show existing data
echo "<h3>Data Berkas Existing:</h3>";
$berkas_list = $CI->notelen->get_berkas_masuk(10, 0);
if ($berkas_list && count($berkas_list) > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Nomor Perkara</th><th>Tanggal Putusan</th><th>Action</th></tr>";
    foreach ($berkas_list as $berkas) {
        echo "<tr>";
        echo "<td>" . $berkas->id . "</td>";
        echo "<td>" . $berkas->nomor_perkara . "</td>";
        echo "<td>" . $berkas->tanggal_putusan . "</td>";
        echo "<td><a href='" . base_url('notelen/ajax_delete_berkas?id=' . $berkas->id . '&redirect=1') . "' onclick=\"return confirm('Hapus berkas " . $berkas->nomor_perkara . "?')\">Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Tidak ada data berkas</p>";
}
