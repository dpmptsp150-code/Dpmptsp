<?php
$host = "shinkansen.proxy.rlwy.net"; 
$user = "root"; 
$pass = "XrixTnXcQGPthYtspDSWmzFXBmcIvqdi"; 
$db   = "railway"; 
$port = 52190; // ini integer, bukan string

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
echo "Koneksi berhasil ke Railway MySQL!";
?>
