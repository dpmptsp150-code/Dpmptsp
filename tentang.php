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
<title>Tentang - DPMPTSP Kota Kupang</title>
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
    padding: 40px 20px 20px;
}
.header img {
    height: 80px;
    margin-bottom: 10px;
}
.header h1 {
    font-size: 30px;
    font-weight: 700;
    margin: 8px 0;
}
.header h2 {
    font-size: 16px;
    font-weight: 400;
    opacity: 0.9;
}

/* ===== Konten ===== */
.content {
    flex: 1;
    max-width: 1000px;
    margin: 30px auto;
    padding: 0 20px 20px;
}
.card-custom {
    background: rgba(255,255,255,0.1);
    border-radius: 14px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 6px 14px rgba(0,0,0,0.2);
}
.card-custom h3 {
    font-size: 20px;
    margin-bottom: 12px;
    font-weight: 700;
    color: #ffc107;
}
.card-custom p, .card-custom ul {
    margin-bottom: 8px;
    line-height: 1.6;
}
.card-custom ul {
    margin-left: 20px;
}

/* ===== Peta & Alamat ===== */
.map-container {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 15px;
}
.address-box {
    font-size: 15px;
    line-height: 1.7;
}

/* ===== Tombol kembali ===== */
.back-btn {
    display: flex;
    justify-content: flex-end;
    margin: 20px 0;
}

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
    <img src="https://dpmptsp.kupangkota.go.id/wp-content/uploads/2024/06/FINAL-DPMPTSP-Typography-Logo-1024x403.png" alt="Logo">
    <h1>Pemerintah Kota Kupang</h1>
    <h2>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu</h2>
</div>

<!-- Konten -->
<div class="content">

    <div class="card-custom">
        <h3><i class="fa fa-building"></i> Profil Kantor</h3>
        <p>DPMPTSP Kota Kupang adalah lembaga yang memberikan layanan perizinan dan penanaman modal secara terpadu dengan prinsip cepat, mudah, transparan, dan akuntabel.</p>
    </div>

    <div class="card-custom">
        <h3><i class="fa fa-bullseye"></i> Visi</h3>
        <p>“Terwujudnya pelayanan perizinan dan penanaman modal yang profesional, transparan, dan terpercaya dalam mendukung pembangunan Kota Kupang.”</p>
        <h3><i class="fa fa-flag"></i> Misi</h3>
        <ul>
            <li>Meningkatkan kualitas pelayanan perizinan yang cepat, mudah, dan transparan.</li>
            <li>Mendukung iklim investasi yang kondusif di Kota Kupang.</li>
            <li>Mendorong digitalisasi layanan publik untuk efisiensi dan efektivitas.</li>
        </ul>
    </div>

    <div class="card-custom">
        <h3><i class="fa fa-handshake"></i> Maklumat Pelayanan</h3>
        <p>“Kami siap memberikan pelayanan terbaik, cepat, transparan, dan tanpa pungutan liar demi kepuasan masyarakat.”</p>
    </div>

    <div class="card-custom">
        <h3><i class="fa fa-map-marker-alt"></i> Alamat Kantor & Peta</h3>
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.456789012345!2d123.6073112!3d-10.1497283!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2c569d0b0998bb49%3A0x1bf8e80b5135dd34!2sDPMPTSP%20Kota%20Kupang!5e0!3m2!1sid!2sid!4v1000000000000"
                width="100%"
                height="350"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="address-box">
            <p><strong>Alamat:</strong><br>
            Jl. Frans Seda, Kelurahan Fatululi,<br>
            Kecamatan Oebobo, Kota Kupang,<br>
            Nusa Tenggara Timur</p>
        </div>
    </div>

    <!-- Tombol kembali -->
    <div class="back-btn">
        <a href="index.php" class="btn btn-outline-light">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

</div>

<!-- Footer -->
<div class="footer">
    <p>© 2025 DPMPTSP Kota Kupang – Website Arsip Digital</p>
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
