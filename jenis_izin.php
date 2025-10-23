<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "arsip_dpmptsp"; 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// Tambah/Edit Jenis Izin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitJenisIzin'])) {
    $id = intval($_POST['id']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    if ($id > 0) {
        $conn->query("UPDATE jenis_izin SET nama='$nama', deskripsi='$deskripsi' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO jenis_izin (nama, deskripsi) VALUES ('$nama', '$deskripsi')");
    }
    header("Location: jenis_izin.php");
    exit;
}

// Hapus Jenis Izin
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM jenis_izin WHERE id=$id");
    header("Location: jenis_izin.php");
    exit;
}

// Tambah/Edit Bentuk Izin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitBentukIzin'])) {
    $id = intval($_POST['bentuk_id']);
    $jenis_izin = intval($_POST['jenis_izin']);
    $nama = $conn->real_escape_string($_POST['bentuk_nama']);
    $deskripsi = $conn->real_escape_string($_POST['bentuk_deskripsi']);
    if ($id > 0) {
        $conn->query("UPDATE bentuk_izin SET nama='$nama', deskripsi='$deskripsi' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO bentuk_izin (jenis_izin, nama, deskripsi) VALUES ($jenis_izin,'$nama','$deskripsi')");
    }
    header("Location: jenis_izin.php?izin=$jenis_izin");
    exit;
}

// Hapus Bentuk Izin
if (isset($_GET['hapus_bentuk'])) {
    $id = intval($_GET['hapus_bentuk']);
    $jenis_izin = intval($_GET['jenis_izin']);
    $conn->query("DELETE FROM bentuk_izin WHERE id=$id");
    header("Location: jenis_izin.php?izin=$jenis_izin");
    exit;
}

// Ambil semua Jenis Izin
$result = $conn->query("SELECT * FROM jenis_izin ORDER BY nama ASC");
$jenis_izin_list = [];
while ($row = $result->fetch_assoc()) $jenis_izin_list[] = $row;

// Jika ada Jenis Izin terpilih
$selectedIzin = null;
$bentuk_izin_list = [];
if (isset($_GET['izin'])) {
    $izinId = intval($_GET['izin']);
    $selectedIzin = $conn->query("SELECT * FROM jenis_izin WHERE id=$izinId")->fetch_assoc();
    if($selectedIzin){
        $subResult = $conn->query("SELECT * FROM bentuk_izin WHERE jenis_izin=$izinId ORDER BY nama ASC");
        while ($row = $subResult->fetch_assoc()) $bentuk_izin_list[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jenis Izin</title>
<link rel="icon" type="image/png" href="images/icon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f6f5f0; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }

/* Sidebar */
.sidebar { width: 240px; height: 100vh; background: #13223c; color: white; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; padding-top: 20px; }
.sidebar .brand { text-align: center; margin-bottom: 20px; }
.sidebar .brand img { width: 120px; }
.sidebar a { display: block; padding: 12px 20px; color: white; text-decoration: none; border-radius: 10px; margin: 4px 12px; }
.sidebar a:hover, .sidebar a.active { background: #5f6c81ff; transform: translateX(4px); }
.sidebar .logout-container { margin-top: auto; margin-bottom: 20px; }
.logout-item { color: #ff4d4d; background-color: #800000; margin: 12px; border-radius: 10px; padding: 12px 20px; display: flex; align-items: center; text-decoration: none; }
.logout-item:hover { background-color: #990000; transform: translateX(4px); }

/* Main content */
.main-content { margin-left: 240px; padding: 30px; padding-top: 100px; flex: 1; display: flex; flex-direction: column; }

/* Topbar */
.topbar { position: fixed; top: 0; left: 240px; right: 0; background: #13223c; color: white; height: 70px; padding: 0 25px; display: flex; align-items: center; justify-content: space-between; z-index: 1000; }
.topbar .left { display: flex; align-items: center; }
.topbar .left img { height: 40px; margin-right: 12px; }
.topbar .right { position: relative; display: flex; align-items: center; font-size: 16px; cursor: pointer; }
.topbar .right i { margin-left: 8px; }
.dropdown-admin { position: absolute; top: 50px; right: 0; background: white; color: black; min-width: 150px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: none; flex-direction: column; z-index: 1001; }
.dropdown-admin a { padding: 10px 15px; text-decoration: none; color: black; }
.dropdown-admin a:hover { background-color: #800000; color: white; }

/* Card modern */
.card-cat, .card-sub { background: white; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; position: relative; transition: all 0.3s; }
.card-cat:hover, .card-sub:hover { transform: translateY(-5px); box-shadow: 0 6px 16px rgba(0,0,0,0.2); }
.card-cat .icon, .card-sub .icon { width: 60px; height: 60px; margin: 0 auto 10px; border-radius: 50%; background: #13223c; display: flex; align-items: center; justify-content: center; color: #f0c040; font-size: 24px; }
.card-cat h6, .card-sub h6 { margin: 0; font-size: 16px; font-weight: bold; }
.card-cat small, .card-sub small { display: block; font-size: 13px; color: #555; margin-top: 4px; }
.card-cat .actions, .card-sub .actions { position: absolute; top: 8px; right: 8px; display: flex; gap: 4px; opacity: 0; transition: opacity 0.3s; }
.card-cat:hover .actions, .card-sub:hover .actions { opacity: 1; }
.card-cat .btn, .card-sub .btn { padding: 4px 8px; font-size: 12px; }

/* Footer modern rapat sidebar */
.footer { background-color: #13223c; color: #f0c040; font-size: 14px; padding: 15px 0; text-align: center; margin-left: 240px; }
.footer p { margin: 0; }
.footer .social-links { display: flex; justify-content: center; gap: 20px; margin-top: 5px; flex-wrap: wrap; }
.footer .social-links a { color: #f0c040; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: all 0.3s; font-weight: 500; }
.footer .social-links a:hover { color: #ffffff; transform: translateY(-2px); }
.footer .social-links i { font-size: 16px; }
</style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="brand">
        <img src="https://dpmptsp.kupangkota.go.id/wp-content/uploads/2024/06/FINAL-DPMPTSP-Typography-Logo-1024x403.png" alt="Logo">
        <h4>Dpmptsp Kota Kupang</h4>
        <small>Dashboard Arsip</small>
    </div>
    <a href="index.php"><i class="fa fa-arrow-left me-2"></i>Kembali ke Dashboard</a>
    <a href="dokumen.php" class="active"><i class="fa fa-folder me-2"></i>Dokumen</a>
    <a href="jenis_izin.php"><i class="fa fa-tags me-2"></i>Jenis Izin</a>
    <a href="tentang.php"><i class="fa fa-info-circle me-2"></i>Tentang</a>
    <div class="logout-container">
        <a href="#" class="logout-item" onclick="confirmLogout();"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="left">
            <img src="https://dpmptsp.kupangkota.go.id/wp-content/uploads/2024/06/FINAL-DPMPTSP-Typography-Logo-1024x403.png" alt="Logo">
            <span>Home » Jenis Izin<?= $selectedIzin ? " » " . htmlspecialchars($selectedIzin['nama']) : "" ?></span>
        </div>
        <div class="right" onclick="toggleDropdown();">
            <i class="fa fa-user-circle"></i>
            <span class="ms-2"><?= htmlspecialchars($_SESSION['username']); ?></span>
            <div class="dropdown-admin" id="adminDropdown">
                <a href="#" onclick="confirmLogout(); return false;"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
    </div>

    <div class="card p-3 mt-4">
        <?php if (!$selectedIzin): ?>
            <h5>Data Jenis Izin
                <button class="btn btn-primary btn-sm float-end" onclick="openModalJenisIzin(0,'','');">
                    <i class="fa fa-plus"></i> Tambah Jenis Izin
                </button>
            </h5>
            <div class="row mt-3">
                <?php foreach ($jenis_izin_list as $izin): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card-cat" onclick="window.location.href='jenis_izin.php?izin=<?= $izin['id'] ?>'">
                            <div class="icon"><i class="fa fa-folder"></i></div>
                            <h6><?= htmlspecialchars($izin['nama']) ?></h6>
                            <small><?= htmlspecialchars($izin['deskripsi']) ?></small>
                            <div class="actions">
                                <button class="btn btn-success" onclick="openModalJenisIzin(event,'<?= $izin['id'] ?>','<?= htmlspecialchars($izin['nama']) ?>','<?= htmlspecialchars($izin['deskripsi']) ?>')"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" onclick="event.stopPropagation(); if(confirm('Yakin ingin hapus jenis izin <?= htmlspecialchars($izin['nama']) ?>?')) location.href='?hapus=<?= $izin['id'] ?>';"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <h5>Bentuk Izin dari <?= htmlspecialchars($selectedIzin['nama']) ?>
                <a href="jenis_izin.php" class="btn btn-secondary btn-sm float-start"><i class="fa fa-arrow-left"></i> Kembali</a>
                <button class="btn btn-primary btn-sm float-end" onclick="openModalBentukIzin(0,'','');"><i class="fa fa-plus"></i> Tambah Bentuk Izin</button>
            </h5>
            <div class="row mt-3">
                <?php foreach($bentuk_izin_list as $bentuk): ?>
                <div class="col-md-3 mb-3">
                    <div class="card-sub">
                        <div class="icon"><i class="fa fa-folder-open"></i></div>
                        <h6><?= htmlspecialchars($bentuk['nama']) ?></h6>
                        <small><?= htmlspecialchars($bentuk['deskripsi']) ?></small>
                        <div class="actions">
                            <button class="btn btn-success" onclick="openModalBentukIzin(event,'<?= $bentuk['id'] ?>','<?= htmlspecialchars($bentuk['nama']) ?>','<?= htmlspecialchars($bentuk['deskripsi']) ?>')"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" onclick="event.stopPropagation(); if(confirm('Yakin ingin hapus bentuk izin <?= htmlspecialchars($bentuk['nama']) ?>?')) location.href='?izin=<?= $selectedIzin['id'] ?>&hapus_bentuk=<?= $bentuk['id'] ?>&jenis_izin=<?= $selectedIzin['id'] ?>';"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Jenis Izin -->
<div class="modal fade" id="modalJenisIzin" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
<form method="post" class="modal-content">
    <input type="hidden" name="id" id="jenisIzinId">
    <div class="modal-header">
        <h5 class="modal-title" id="modalJenisIzinLabel">Tambah/Edit Jenis Izin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nama Jenis Izin</label>
            <input type="text" name="nama" id="jenisIzinNama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="jenisIzinDeskripsi" class="form-control"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" name="submitJenisIzin" class="btn btn-primary">Simpan</button>
    </div>
</form>
</div>
</div>

<!-- Modal Bentuk Izin -->
<div class="modal fade" id="modalBentukIzin" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
<form method="post" class="modal-content">
    <input type="hidden" name="bentuk_id" id="bentukIzinId">
    <input type="hidden" name="jenis_izin" value="<?= $selectedIzin ? $selectedIzin['id'] : 0 ?>">
    <div class="modal-header">
        <h5 class="modal-title" id="modalBentukIzinLabel">Tambah/Edit Bentuk Izin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nama Bentuk Izin</label>
            <input type="text" name="bentuk_nama" id="bentukIzinNama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="bentuk_deskripsi" id="bentukIzinDeskripsi" class="form-control"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" name="submitBentukIzin" class="btn btn-primary">Simpan</button>
    </div>
</form>
</div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>© 2025 DPMPTSP Kota Kupang – Website Arsip Digital</p>
    <div class="social-links">
        <a href="https://instagram.com/mpp_kotakupang" target="_blank"><i class="fab fa-instagram"></i> @mpp_kotakupang</a>
        <a href="https://facebook.com/people/Mal-Pelayanan-Publik-Kota-Kupang/61559717212597/" target="_blank"><i class="fab fa-facebook"></i> Mal Pelayanan Publik Kota Kupang</a>
        <a href="https://wa.me/6281554444888" target="_blank"><i class="fab fa-whatsapp"></i> 081554444888</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmLogout(){ if(confirm("Apakah Anda yakin ingin logout?")) window.location.href="logout.php"; }
function toggleDropdown(){ document.getElementById("adminDropdown").style.display =
    (document.getElementById("adminDropdown").style.display === "flex" ? "none" : "flex"); }
function openModalJenisIzin(e,id,nama,deskripsi){
    if(e) e.stopPropagation();
    document.getElementById('jenisIzinId').value = id;
    document.getElementById('jenisIzinNama').value = nama;
    document.getElementById('jenisIzinDeskripsi').value = deskripsi;
    new bootstrap.Modal(document.getElementById('modalJenisIzin')).show();
}
function openModalBentukIzin(e,id,nama,deskripsi){
    if(e) e.stopPropagation();
    document.getElementById('bentukIzinId').value = id;
    document.getElementById('bentukIzinNama').value = nama;
    document.getElementById('bentukIzinDeskripsi').value = deskripsi;
    new bootstrap.Modal(document.getElementById('modalBentukIzin')).show();
}
</script>
</body>
</html>
