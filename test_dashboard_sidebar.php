<?php
// Test untuk mengecek apakah sidebar berfungsi di dashboard_home
echo "<!DOCTYPE html>";
echo "<html><head><title>Test Sidebar Dashboard Home</title></head><body>";

echo "<h2>Test Dashboard Home Sidebar</h2>";

// Cek apakah file controller ada
$controller_file = 'application/controllers/Dashboard_home.php';
if (file_exists($controller_file)) {
    echo "<p>✅ Controller Dashboard_home.php ada</p>";
} else {
    echo "<p>❌ Controller Dashboard_home.php tidak ada</p>";
}

// Cek apakah file view ada
$view_file = 'application/views/dashboard_home.php';
if (file_exists($view_file)) {
    echo "<p>✅ View dashboard_home.php ada</p>";
} else {
    echo "<p>❌ View dashboard_home.php tidak ada</p>";
}

// Cek apakah file model ada
$model_file = 'application/models/Dashboard_home_model.php';
if (file_exists($model_file)) {
    echo "<p>✅ Model Dashboard_home_model.php ada</p>";
} else {
    echo "<p>❌ Model Dashboard_home_model.php tidak ada</p>";
}

// Cek apakah template ada
$sidebar_template = 'application/views/template/new_sidebar.php';
if (file_exists($sidebar_template)) {
    echo "<p>✅ Template new_sidebar.php ada</p>";
} else {
    echo "<p>❌ Template new_sidebar.php tidak ada</p>";
}

$header_template = 'application/views/template/new_header.php';
if (file_exists($header_template)) {
    echo "<p>✅ Template new_header.php ada</p>";
} else {
    echo "<p>❌ Template new_header.php tidak ada</p>";
}

// Cek apakah file AdminLTE ada
$adminlte_js = 'assets/dist/js/adminlte.min.js';
if (file_exists($adminlte_js)) {
    echo "<p>✅ AdminLTE JS ada</p>";
} else {
    echo "<p>❌ AdminLTE JS tidak ada</p>";
}

$adminlte_css = 'assets/dist/css/adminlte.min.css';
if (file_exists($adminlte_css)) {
    echo "<p>✅ AdminLTE CSS ada</p>";
} else {
    echo "<p>❌ AdminLTE CSS tidak ada</p>";
}

echo "<hr>";
echo "<p><strong>Langkah debugging:</strong></p>";
echo "<ol>";
echo "<li>Buka browser DevTools (F12)</li>";
echo "<li>Pergi ke tab Console</li>";
echo "<li>Akses halaman dashboard_home</li>";
echo "<li>Lihat pesan error atau warning</li>";
echo "<li>Cek apakah script AdminLTE dimuat</li>";
echo "</ol>";

echo "<hr>";
echo "<p><a href='index.php/dashboard_home' target='_blank'>Test Dashboard Home</a></p>";
echo "<p><a href='index.php/home' target='_blank'>Test Dashboard Biasa</a></p>";

echo "</body></html>";
