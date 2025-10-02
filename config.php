<?php
// Cek kalau ada environment variable dari Railway, kalau tidak ada pakai default lokal (XAMPP)
$host = getenv("MYSQLHOST") ?: "shuttle.proxy.rlwy.net";
$port = getenv("MYSQLPORT") ?: "12790";
$user = getenv("MYSQLUSER") ?: "root";
$pass = getenv("MYSQLPASSWORD") ?: "BeRapxGNNIiFbrBsyNSOjMbmyZFXUlSl";
$db   = getenv("MYSQLDATABASE") ?: "railway";

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully!"; // optional untuk test
?>
