<?php
$host = "localhost";
$user = "root";   // ganti kalau user MySQL kamu beda
$pass = "";       // isi kalau MySQL pakai password
$db   = "arsip_dpmptsp"; // database kamu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
