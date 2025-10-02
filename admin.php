<?php
session_start();
include 'config.php';

// Proteksi: hanya admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Tambah akun baru
if (isset($_POST['tambah'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name     = $_POST['name'];
    $role     = $_POST['role'];

    // Cek username sudah ada
    $check = $conn->prepare("SELECT id FROM users WHERE username=?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $error = "Username sudah ada!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $name, $role);
        $stmt->execute();
        $success = "Akun baru berhasil ditambahkan!";
    }
}

// Hapus akun
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("SELECT role FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['role'] == 'admin') {
        $error = "Tidak bisa menghapus akun admin!";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $success = "Akun berhasil dihapus!";
    }
}

// Ambil semua akun
$result = $conn->query("SELECT id, username, name, role, created_at FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Panel - DPMPTSP</title>
<link rel="icon" type="image/png" href="images/icon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body, html {
    margin:0; padding:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #063c69ff, #13223cff, #0d47a1);
    color: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header */
.header {
    text-align: center;
    padding: 40px 20px 20px;
    position: relative;
}
.header img { height: 90px; margin-bottom: 15px; }
.header h1 { font-size: 32px; font-weight: 700; margin:10px 0; }
.header h2 { font-size: 18px; font-weight: 400; opacity: 0.9; }

/* Logout button */
.logout-btn {
    position: absolute;
    top:20px; right:20px;
}
.logout-btn .btn { font-size:15px; padding:10px 18px; border-radius:8px; }

/* Menu grid */
.menu-container {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    max-width:1000px;
    margin:20px auto 40px;
}
.menu-item {
    text-align:center;
    padding:40px 20px;
    border-radius:16px;
    color:#fff;
    font-size:18px;
    font-weight:600;
    text-decoration:none;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    box-shadow:0 8px 18px rgba(0,0,0,0.15);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.menu-item i { font-size:40px; margin-bottom:12px; }
.menu-item:hover { transform: translateY(-8px); box-shadow:0 12px 24px rgba(0,0,0,0.25); }
.red { background: #962523ff; }
.blue { background: #10416bff; }
.green { background: #357b3aff; }
.orange { background: #b39f31ff; }

/* Card */
.card { border-radius:16px; background: rgba(255,255,255,0.95); color:#000; padding:20px; margin-bottom:30px; box-shadow:0 8px 18px rgba(0,0,0,0.2); }

/* Table */
.table thead th { background-color:#007bff; color:#fff; font-weight:600; }
.table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,0.05); }

/* Footer */
.footer { text-align:center; padding:20px; background: rgba(0,0,0,0.2); color:#ffc107; font-size:14px; }
.footer .social-links { display:flex; justify-content:center; gap:15px; flex-wrap: wrap; }
.footer a { color:#ffc107; text-decoration:none; font-weight:500; }
.footer a i { margin-right:6px; }
.footer a:hover { text-decoration: underline; }
</style>
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="logout-btn">
        <a href="logout.php" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin logout?');">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    </div>
    <img src="https://dpmptsp.kupangkota.go.id/wp-content/uploads/2024/06/FINAL-DPMPTSP-Typography-Logo-1024x403.png" alt="Logo">
    <h1>Pemerintah Kota Kupang</h1>
    <h2>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu</h2>
</div>

<!-- Menu -->
<div class="menu-container">
    <a href="index.php" class="menu-item blue"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
    <a class="menu-item green" data-bs-toggle="collapse" href="#collapseTambahAkun" role="button" aria-expanded="false" aria-controls="collapseTambahAkun">
        <i class="fa fa-user-plus"></i> Tambah Akun
    </a>
    <a class="menu-item orange" data-bs-toggle="collapse" href="#collapseDaftarAkun" role="button" aria-expanded="false" aria-controls="collapseDaftarAkun">
        <i class="fa fa-users"></i> Daftar Akun
    </a>
    <a href="profil.php" class="menu-item red"><i class="fa fa-user"></i> Profil</a>
</div>

<div class="container">
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <!-- Collapse Tambah Akun -->
    <div class="collapse" id="collapseTambahAkun">
        <div class="card">
            <h4>Tambah Akun Baru</h4>
            <form method="POST" class="row g-2">
                <div class="col-md-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
                <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required></div>
                <div class="col-md-2"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                <div class="col-md-2">
                    <select name="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" name="tambah" class="btn btn-success w-100">Tambah</button></div>
            </form>
        </div>
    </div>

    <!-- Collapse Daftar Akun -->
    <div class="collapse" id="collapseDaftarAkun">
        <div class="card">
            <h4>Daftar Akun</h4>
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>Username</th><th>Nama Lengkap</th><th>Role</th><th>Tanggal Dibuat</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td>
                            <?php if($user['role'] != 'admin'): ?>
                                <a href="admin.php?hapus=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</a>
                            <?php else: ?>
                                <span class="text-muted">Admin</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<div class="footer">
    <p>© 2025 DPMPTSP Kota Kupang – Website Arsip Digital </p>
    <div class="social-links">
        <a href="https://instagram.com/mpp_kotakupang" target="_blank"><i class="fab fa-instagram"></i> @mpp_kotakupang</a>
        <a href="https://facebook.com/people/Mal-Pelayanan-Publik-Kota-Kupang/61559717212597/" target="_blank"><i class="fab fa-facebook"></i> Mal Pelayanan Publik Kota Kupang</a>
        <a href="https://wa.me/6281554444888" target="_blank"><i class="fab fa-whatsapp"></i> 081554444888</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
