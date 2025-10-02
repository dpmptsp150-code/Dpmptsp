<?php
session_start();
include "config.php"; // koneksi ke DB

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'];
$konfirmasi = $_POST['konfirmasi_password'];

// Ambil password lama dari database
$stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($hash_lama);
$stmt->fetch();
$stmt->close();

if (!$hash_lama || !password_verify($password_lama, $hash_lama)) {
    echo "<script>alert('Password lama salah!'); window.location.href='profil.php';</script>";
    exit;
}

if ($password_baru !== $konfirmasi) {
    echo "<script>alert('Konfirmasi password tidak cocok!'); window.location.href='profil.php';</script>";
    exit;
}

// Hash password baru
$hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE users SET password=? WHERE username=?");
$update->bind_param("ss", $hash_baru, $username);

if ($update->execute()) {
    echo "<script>alert('Password berhasil diganti!'); window.location.href='profil.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan, coba lagi.'); window.location.href='profil.php';</script>";
}
$update->close();
$conn->close();
?>
