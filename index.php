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
<title>Dashboard DPMPTSP</title>
<link rel="icon" type="image/png" href="images/icon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
/* ===== Layout dasar ===== */
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

/* ===== Header ===== */
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

/* ===== Tombol logout ===== */
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

/* ===== Konten utama (menu) ===== */
.menu-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px 60px;
    flex: 1; /* agar mengambil ruang tersisa */
}
.menu-item {
    padding: 40px 20px;
    border-radius: 16px;
    color: white;
    font-size: 18px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.menu-item i {
    font-size: 40px;
    margin-bottom: 12px;
}
.menu-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.25);
}

/* ===== Warna kotak ===== */
.red { background: #c7221fff; }
.blue { background: #034279ff; }
.green { background: #1da826ff; }
.orange { background: #ddc019ff; }

/* ===== Footer ===== */
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

<!-- Menu Grid -->
<div class="menu-container">
    <a href="dokumen.php" class="menu-item red"><i class="fa fa-file-alt"></i> Dokumen</a>
    <a href="jenis_izin.php" class="menu-item blue"><i class="fa fa-list"></i> Jenis Izin</a>
    <a href="tentang.php" class="menu-item green"><i class="fa fa-info-circle"></i> Tentang</a>
    <a href="profil.php" class="menu-item orange"><i class="fa fa-user"></i> Profil</a>
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
