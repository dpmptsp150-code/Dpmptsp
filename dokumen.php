<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
if (file_exists(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
} else {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "arsip_dpmptsp";
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);
}

// --- Google Drive Client Setup ---
function getDriveService() {
    $client = new Google\Client();
    $client->setAuthConfig(__DIR__ . '/credentials/dpmptsp-arsip-4a43103520cc.json');
    $client->addScope(Google\Service\Drive::DRIVE);
    return new Google\Service\Drive($client);
}

// ID folder Shared Drive
$sharedDriveId = '1aB2C3D4EfGhIjKlMnOp'; // Ganti dengan ID Shared Drive kamu

// Ambil semua jenis izin
$jenis_izin_list = [];
$res = $conn->query("SELECT id, nama FROM jenis_izin ORDER BY nama");
while ($row = $res->fetch_assoc()) $jenis_izin_list[] = $row;

// --- AJAX: Ambil bentuk izin berdasarkan jenis ---
if (isset($_GET['get_bentuk']) && $_GET['get_bentuk'] != '') {
    $id_jenis = intval($_GET['get_bentuk']);
    $bentuk_list = [];
    $res = $conn->query("SELECT id, nama FROM bentuk_izin WHERE jenis_izin_id=$id_jenis ORDER BY nama");
    while ($row = $res->fetch_assoc()) $bentuk_list[] = $row;
    header('Content-Type: application/json');
    echo json_encode($bentuk_list);
    exit;
}

// --- Upload dokumen ---
$upload_error = '';
if (isset($_POST['upload'])) {
    $nama_pemilik = trim($_POST['nama_pemilik'] ?? '');
    $nama_perusahaan = trim($_POST['nama_perusahaan'] ?? '');
    $tanggal = $_POST['tanggal'] ?? null;
    $tahun = trim($_POST['tahun'] ?? '');
    $nomor_surat = trim($_POST['nomor_surat'] ?? '');
    $jenis_izin = trim($_POST['jenis_izin'] ?? '');
    $bentuk_izin = trim($_POST['bentuk_izin'] ?? '');

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $upload_error = 'File tidak dipilih atau error upload.';
    } else {
        try {
            $file_tmp = $_FILES['file']['tmp_name'];
            $safe_name = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($_FILES['file']['name']));

            $drive = getDriveService();

            // Folder ID dari link Shared Drive
            $folderId = '19tO7PFbFfEBpvujePvQU-jCYDoEXM_tG';

            // Metadata file
            $fileMetadata = new Google\Service\Drive\DriveFile([
                'name' => $safe_name,
                'parents' => [$folderId] // Folder tujuan
            ]);

            $content = file_get_contents($file_tmp);

            // Upload file ke Google Drive (Shared Drive)
            $uploadedFile = $drive->files->create(
                $fileMetadata,
                [
                    'data' => $content,
                    'mimeType' => mime_content_type($file_tmp),
                    'uploadType' => 'multipart',
                    'fields' => 'id, name, webViewLink',
                    'supportsAllDrives' => true // HARUS ADA untuk Shared Drive
                ]
            );

            $file_link = $uploadedFile->webViewLink; // link file untuk database

            // Simpan ke database
            $sql = "INSERT INTO dokumen 
                (nama_pemilik, nama_perusahaan, tanggal, tahun, nomor_surat, jenis_izin, bentuk_izin, file) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss",
                $nama_pemilik,
                $nama_perusahaan,
                $tanggal,
                $tahun,
                $nomor_surat,
                $jenis_izin,
                $bentuk_izin,
                $file_link
            );
            $stmt->execute();
            $stmt->close();

            header("Location: dokumen.php");
            exit;

        } catch (Exception $e) {
            $upload_error = 'Gagal upload ke Google Drive: ' . $e->getMessage();
        }
    }
}

// --- Hapus dokumen ---
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM dokumen WHERE id = $id");
    header("Location: dokumen.php");
    exit;
}

// --- Ambil data dokumen ---
$where = [];
$params = [];
$types = '';
if(!empty($_GET['cari_pemilik'])) {
    $where[] = "nama_pemilik LIKE ?";
    $params[] = '%'.$_GET['cari_pemilik'].'%';
    $types .= 's';
}
if(!empty($_GET['cari_perusahaan'])) {
    $where[] = "nama_perusahaan LIKE ?";
    $params[] = '%'.$_GET['cari_perusahaan'].'%';
    $types .= 's';
}
if(!empty($_GET['cari_tahun'])) {
    $where[] = "tahun = ?";
    $params[] = $_GET['cari_tahun'];
    $types .= 's';
}
$sql = "SELECT * FROM dokumen";
if($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY tgl_upload DESC";
$stmt = $conn->prepare($sql);
if($where) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dokumen Arsip</title>
<link rel="icon" type="image/png" href="images/icon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f6f5f0ff; margin:0; }

/* Sidebar */
.sidebar { width: 240px; height: 100vh; background: #13223cff; color:white; position: fixed; top:0; left:0; display:flex; flex-direction:column; padding-top:20px;}
.sidebar .brand { text-align:center; margin-bottom:20px;}
.sidebar .brand img { width:120px; }
.sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; margin:4px 12px; border-radius:10px; transition:0.2s; }
.sidebar a:hover, .sidebar a.active { background:#5f6c81ff; transform: translateX(4px);}
.sidebar .logout-container { margin-top:auto; margin-bottom:20px;}
.logout-item { color:#ff4d4d; background-color:#800000; margin:12px; border-radius:10px; padding:12px 20px; display:flex; align-items:center; text-decoration:none; transition:0.2s; }
.logout-item:hover { background-color:#990000; transform:translateX(4px); }

/* Main Content */
.main-content { margin-left:240px; padding:30px; padding-top:100px; }

/* Topbar */
.topbar { position:fixed; top:0; left:240px; right:0; background: #13223cff; color:white; height:70px; padding:0 25px; display:flex; align-items:center; justify-content:space-between; z-index:1000;}
.topbar .left { display:flex; align-items:center;}
.topbar .left img { height:40px; margin-right:12px;}
.topbar .right { position:relative; display:flex; align-items:center; font-size:16px; cursor:pointer; }
.topbar .right i { margin-left:8px;}
.dropdown-admin { position:absolute; top:50px; right:0; background:white; color:black; min-width:150px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15); display:none; flex-direction:column; z-index:1001;}
.dropdown-admin a { padding:10px 15px; text-decoration:none; color:black; transition:0.2s;}
.dropdown-admin a:hover { background-color:#800000; color:white;}

/* Card */
.card { border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
.card-dashboard { transition: transform 0.2s, box-shadow 0.2s; }
.card-dashboard:hover { transform: translateY(-5px); box-shadow:0 6px 15px rgba(0,0,0,0.2); }
.icon-circle { font-size:2rem; opacity:0.75; }

/* Tabel */
.table td, .table th { vertical-align: middle; }
.table .col-file { width: 120px; }
.table .col-tgl { width: 140px; }
.table .tgl-upload { display: flex; flex-direction: column; font-size: 0.85rem; }

/* Footer */
.footer {
    text-align: center;
    padding: 20px;
    background: #13223cff;
    font-size: 14px;
    color: #ffc107;
    margin-top: 30px;

    /* agar rata ke sidebar */
    width: calc(100% - 240px);
    margin-left: 240px;
    box-sizing: border-box;
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

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="left">
            <img src="https://dpmptsp.kupangkota.go.id/wp-content/uploads/2024/06/FINAL-DPMPTSP-Typography-Logo-1024x403.png" alt="Logo">
            <span>Home » Dokumen</span>
        </div>
        <div class="right" onclick="toggleDropdown();">
            <i class="fa fa-user-circle"></i>
            <span class="ms-2"><?=htmlspecialchars($_SESSION['username']);?></span>
            <div class="dropdown-admin" id="adminDropdown">
                <a href="#" onclick="confirmLogout(); return false;"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
    </div>

    <!-- Card Tambah Dokumen -->
    <div class="card-dashboard text-white d-block text-decoration-none p-4 mt-4" 
        style="background: #13223c; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.2);" 
        onclick="scrollToForm(event)">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold">Tambah Dokumen Arsip Digital</h3>
                <p class="mb-0">Klik di sini untuk menambahkan arsip baru ke sistem</p>
            </div>
            <div class="icon-circle">
                <i class="fa fa-plus-square fa-lg"></i>
            </div>
        </div>
    </div>

    <!-- Form Upload -->
    <div class="card p-3 mt-4" id="formUpload">
        <h5>Tambah Dokumen Baru</h5>
        <?php if ($upload_error): ?>
            <div class="alert alert-danger"><?=htmlspecialchars($upload_error)?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3"><label>Nama Pemilik</label><input type="text" name="nama_pemilik" class="form-control"></div>
            <div class="mb-3"><label>Nama Perusahaan</label><input type="text" name="nama_perusahaan" class="form-control"></div>
            <div class="mb-3"><label>Tahun</label><input type="number" name="tahun" class="form-control"></div>
            <div class="mb-3"><label>Nomor Surat</label><input type="text" name="nomor_surat" class="form-control"></div>

            <div class="mb-3">
                <label>Jenis Izin</label>
                <select name="jenis_izin" id="jenisIzin" class="form-control" required onchange="loadBentuk(this.value)">
                    <option value="">-- Pilih Jenis Izin --</option>
                    <?php foreach($jenis_izin_list as $j): ?>
                        <option value="<?= $j['id']; ?>"><?= htmlspecialchars($j['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Bentuk Izin</label>
                <select name="bentuk_izin" id="bentukIzin" class="form-control" required>
                    <option value="">-- Pilih Bentuk Izin --</option>
                </select>
            </div>

            <div class="mb-3"><label>Pilih File</label><input type="file" name="file" class="form-control" required></div>
            <button type="submit" name="upload" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Tabel Dokumen -->
    <div class="card p-3 mt-4">
        <h5>Data Dokumen</h5>

        <!-- Form Filter Pencarian -->
        <div class="mb-3">
            <form method="get" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="cari_pemilik" class="form-control" placeholder="Cari Nama Pemilik" value="<?=htmlspecialchars($_GET['cari_pemilik'] ?? '')?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="cari_perusahaan" class="form-control" placeholder="Cari Nama Perusahaan" value="<?=htmlspecialchars($_GET['cari_perusahaan'] ?? '')?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="cari_tahun" class="form-control" placeholder="Cari Tahun" value="<?=htmlspecialchars($_GET['cari_tahun'] ?? '')?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Cari</button>
                </div>
                <div class="col-md-2">
                    <a href="dokumen.php" class="btn btn-secondary w-100"><i class="fa fa-refresh"></i> Reset</a>
                </div>
            </form>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pemilik</th>
                    <th>Nama Perusahaan</th>
                    <th>Tahun</th>
                    <th>Nomor Surat</th>
                    <th>Jenis Izin</th>
                    <th>File</th>
                    <th>Tgl Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if($result->num_rows>0): $no=1; while($row=$result->fetch_assoc()): ?>
                <tr>
                    <td><?=$no++;?></td>
                    <td><?=htmlspecialchars($row['nama_pemilik']);?></td>
                    <td><?=htmlspecialchars($row['nama_perusahaan']);?></td>
                    <td><?=htmlspecialchars($row['tahun']);?></td>
                    <td><?=htmlspecialchars($row['nomor_surat']);?></td>
                    <td><?=htmlspecialchars($row['kategori']);?></td>
                    <td class="col-file"><?= $row['file'] ? htmlspecialchars(basename($row['file'])) : '-'; ?></td>
                    <td class="col-tgl">
                        <div class="tgl-upload">
                            <?php 
                            $dt = strtotime($row['tgl_upload']);
                            echo date("d M Y", $dt); // tanggal
                            echo date("<br>H:i:s", $dt); // waktu
                            ?>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <?php if($row['file']): ?>
                                <a href="<?=htmlspecialchars($row['file']);?>" target="_blank" class="btn btn-info btn-sm" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a href="download.php?file=<?=urlencode(basename($row['file']));?>" class="btn btn-secondary btn-sm" title="Download"><i class="fa fa-download"></i></a>
                            <?php endif; ?>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?=$row['id'];?>" title="Edit"><i class="fa fa-edit"></i></button>
                            <a href="?hapus=<?=$row['id'];?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?');" title="Hapus"><i class="fa fa-trash"></i></a>
                        </div>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal<?=$row['id'];?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="post" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Dokumen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="edit_id" value="<?=$row['id'];?>">
                                    <div class="mb-3"><label>Nama Pemilik</label><input type="text" name="nama_pemilik" class="form-control" value="<?=htmlspecialchars($row['nama_pemilik']);?>"></div>
                                    <div class="mb-3"><label>Nama Perusahaan</label><input type="text" name="nama_perusahaan" class="form-control" value="<?=htmlspecialchars($row['nama_perusahaan']);?>"></div>
                                    <div class="mb-3"><label>Tahun</label><input type="number" name="tahun" class="form-control" min="1900" max="2099" value="<?=htmlspecialchars($row['tahun']);?>"></div>
                                    <div class="mb-3"><label>Nomor Surat</label><input type="text" name="nomor_surat" class="form-control" value="<?=htmlspecialchars($row['nomor_surat']);?>"></div>
                                    <div class="mb-3">
                                        <label>Kategori</label>
                                        <select name="kategori" class="form-control" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Trayek" <?=$row['kategori']=='Trayek'?'selected':''?>>Trayek</option>
                                            <option value="Pendidikan" <?=$row['kategori']=='Pendidikan'?'selected':''?>>Pendidikan</option>
                                            <option value="Kesehatan" <?=$row['kategori']=='Kesehatan'?'selected':''?>>Kesehatan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>File</label><br>
                                        <?= $row['file'] ? htmlspecialchars(basename($row['file'])) : 'Tidak ada file'; ?><br>
                                        <input type="file" name="file" class="form-control mt-2">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endwhile; else: ?>
                <tr><td colspan="10" class="text-center text-muted">Belum ada dokumen</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleDropdown(){
    let dd = document.getElementById("adminDropdown");
    dd.style.display = dd.style.display==='flex'?'none':'flex';
}
function confirmLogout(){if(confirm("Yakin logout?")) window.location="logout.php";}

function scrollToForm(e){e.preventDefault(); document.getElementById("formUpload").scrollIntoView({behavior:"smooth"});}

// AJAX untuk load bentuk izin
function loadBentuk(id_jenis){
    let bentukSelect = document.getElementById('bentukIzin');
    bentukSelect.innerHTML = '<option value="">Memuat...</option>';
    if(!id_jenis){
        bentukSelect.innerHTML = '<option value="">-- Pilih Bentuk Izin --</option>';
        return;
    }
    fetch('dokumen.php?get_bentuk=' + id_jenis)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Pilih Bentuk Izin --</option>';
            data.forEach(item => {
                options += `<option value="${item.id}">${item.nama}</option>`;
            });
            bentukSelect.innerHTML = options;
        })
        .catch(err => {
            bentukSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            console.error(err);
        });
}
</script>
<!-- Footer -->
<div class="footer mt-4">
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