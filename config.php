<?php
// Cek kalau ada environment variable dari Railway
$host = getenv("MYSQLHOST") ?: "localhost";
$port = getenv("MYSQLPORT") ?: 3306;
$user = getenv("MYSQLUSER") ?: "root";
$pass = getenv("MYSQLPASSWORD") ?: "";
$db   = getenv("MYSQLDATABASE") ?: "dpmptsp";

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully!"; // optional untuk test
?>
