<?php
// File untuk mengecek informasi PHP
echo "<h1>Informasi PHP XAMPP</h1>";
echo "<h2>Versi PHP: " . PHP_VERSION . "</h2>";
echo "<h3>Versi Major: " . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . "</h3>";
echo "<h3>OS: " . PHP_OS . "</h3>";
echo "<h3>SAPI: " . php_sapi_name() . "</h3>";

echo "<hr>";
echo "<h2>Detail Lengkap:</h2>";

// Tampilkan phpinfo lengkap
phpinfo();
