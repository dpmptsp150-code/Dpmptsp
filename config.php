<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "arsip_dpmptsp"; // sesuaikan dengan nama database kamu di phpMyAdmin

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
