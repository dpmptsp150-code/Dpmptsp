<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Pengguna - DPMPTSP</title>
<link rel="icon" type="image/png" href="images/icon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #063c69ff, #13223cff, #0d47a1);
    color: #fff;
    display: flex;
    flex-direction: column;
}
.header {
    text-align: center;
    padding: 50px 20px 30px;
    position: relative;
}
.header img {
    height: 90px;
    margin-bottom: 15px;
}
.header h1 {
    font-size: 34px;
    font-weight: 700;
    margin: 10px 0;
}
.header h2 {
    font-size: 18px;
    font-weight: 400;
    opacity: 0.9;
}
.logout-btn {
    position: absolute;
    top: 20px;
    right: 20px;
}
.logout-btn .btn {
    font-size: 15px;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 8px;
}
.content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 20px;
}
.profile-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 30px;
    max-width: 500px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 18px rgba(0,0,0,0.3);
}
.profile-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid #ffc107;
}
.profile-card h3 {
    font-size: 22px;
    margin-bottom: 10px;
}
.profile-card p {
    font-size: 15px;
    opacity: 0.9;
}
.footer {
    text-align: center;
    padding: 20px;
    background: rgba(0,0,0,0.2);
    font-size: 14px;
    color: #ffc107;
}
.footer p {
    margin: 5px 0 10px;
    font-weight: 500;
}
.footer .social-links {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
}
.footer a {
    color: #ffc107;
    text-decoration: none;
    font-weight: 500;
}
.footer a i {
    margin-right: 6px;
}
.footer a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<!-- Header -->
<div class="header">
    <!-- Tombol Logout -->
    <div class="logout-btn">
        <a href="logout.php" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin logout?');">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    </div>
    <img src="https://dpmptsp.kupangkota.go.id/wp-content/uploads/2024/06/FINAL-DPMPTSP-Typography-Logo-1024x403.png" alt="Logo">
    <h1>Pemerintah Kota Kupang</h1>
    <h2>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu</h2>
</div>

<!-- Konten Profil -->
<div class="content">
    <div class="profile-card">
        <img src="images/1.jpg" alt="Foto Profil">
        <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        <p><i class="fa fa-user-circle"></i> Web Developer </p>
        <p><i class="fa fa-envelope"></i> kingmozes25@gmail.com</p>
        <hr style="border-color: rgba(255,255,255,0.2); margin: 20px 0;">
        <a href="#" class="btn btn-warning"><i class="fa fa-key"></i> Ganti Password</a>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>© 2025 DPMPTSP Kota Kupang – Website Arsip Digital </p>
    <div class="social-links">
        <a href="https://instagram.com/mpp_kotakupang" target="_blank">
            <i class="fab fa-instagram"></i> @mpp_kotakupang
        </a>
        <a href="https://facebook.com/people/Mal-Pelayanan-Publik-Kota-Kupang/61559717212597/" target="_blank">
            <i class="fab fa-facebook"></i> Mal Pelayanan Publik Kota Kupang
        </a>
        <a href="https://wa.me/6281554444888" target="_blank">
            <i class="fab fa-whatsapp"></i> 081554444888
        </a>
    </div>
</div>

</body>
</html>
