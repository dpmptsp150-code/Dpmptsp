<?php
// Railway biasanya kasih MYSQL_URL, contoh: mysql://user:pass@host:port/dbname
$url = getenv("MYSQL_URL");

if ($url) {
    $dbparts = parse_url($url);

    $host = $dbparts['host'];
    $port = $dbparts['port'];
    $user = $dbparts['user'];
    $pass = $dbparts['pass'];
    $db   = ltrim($dbparts['path'], '/');
} else {
    // Fallback manual, bisa kamu ubah untuk XAMPP lokal
    $host = getenv("MYSQLHOST") ?: "shuttle.proxy.rlwy.net";
    $port = getenv("MYSQLPORT") ?: "12790";
    $user = getenv("MYSQLUSER") ?: "root";
    $pass = getenv("MYSQLPASSWORD") ?: "BeRapxGNNIiFbrBsyNSOjMbmyZFXUlSl";
    $db   = getenv("MYSQLDATABASE") ?: "railway";
}

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// echo "✅ Connected successfully!"; // Uncomment buat test
?>
