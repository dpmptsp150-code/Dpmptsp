<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$host="localhost"; $user="root"; $pass=""; $db="arsip_dpmptsp";
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

// Ambil jenis_izin_id
$jenis_izin_id = intval($_GET['jenis_izin_id']);
$jenis_izin = $conn->query("SELECT * FROM jenis_izin WHERE id=$jenis_izin_id")->fetch_assoc();

// Tambah/Edit Bentuk Izin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitBentukIzin'])) {
    $id = intval($_POST['id']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    if($id>0){
        $conn->query("UPDATE bentuk_izin SET nama='$nama', deskripsi='$deskripsi' WHERE id=$id");
    }else{
        $conn->query("INSERT INTO bentuk_izin (jenis_izin_id,nama,deskripsi) VALUES ($jenis_izin_id,'$nama','$deskripsi')");
    }
    header("Location: bentuk_izin.php?jenis_izin_id=$jenis_izin_id");
    exit;
}

// Hapus Bentuk Izin
if(isset($_GET['hapus'])){
    $id=intval($_GET['hapus']);
    $conn->query("DELETE FROM bentuk_izin WHERE id=$id");
    header("Location: bentuk_izin.php?jenis_izin_id=$jenis_izin_id");
    exit;
}

// Ambil semua Bentuk Izin
$result=$conn->query("SELECT * FROM bentuk_izin WHERE jenis_izin_id=$jenis_izin_id ORDER BY nama ASC");
$bentuk_izin_list=[];
while($row=$result->fetch_assoc()) $bentuk_izin_list[]=$row;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Bentuk Izin <?= htmlspecialchars($jenis_izin['nama']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
.card-bentuk { background:white; border-radius:12px; padding:20px; position:relative; cursor:pointer; box-shadow:0 4px 10px rgba(0,0,0,0.1); transition:0.3s; }
.card-bentuk:hover { transform:translateY(-5px); box-shadow:0 6px 16px rgba(0,0,0,0.2); }
.card-bentuk .actions { position:absolute; top:8px; right:8px; display:flex; gap:4px; opacity:0; transition:0.3s; }
.card-bentuk:hover .actions{opacity:1;}
.card-bentuk .btn{padding:4px 8px;font-size:12px;}
</style>
</head>
<body>
<div class="container mt-5">
<h3>Bentuk Izin: <?= htmlspecialchars($jenis_izin['nama']) ?></h3>
<a href="jenis_izin.php" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Kembali</a>
<button class="btn btn-primary mb-3 float-end" onclick="openModal(0,'','');"><i class="fa fa-plus"></i> Tambah Bentuk Izin</button>
<div class="row mt-3">
<?php foreach($bentuk_izin_list as $bentuk): ?>
<div class="col-md-3 mb-3">
<div class="card-bentuk">
<h6><?= htmlspecialchars($bentuk['nama']) ?></h6>
<small><?= htmlspecialchars($bentuk['deskripsi']) ?></small>
<div class="actions">
<button class="btn btn-success" onclick="openModal(event,'<?= $bentuk['id'] ?>','<?= htmlspecialchars($bentuk['nama']) ?>','<?= htmlspecialchars($bentuk['deskripsi']) ?>')"><i class="fa fa-edit"></i></button>
<button class="btn btn-danger" onclick="event.stopPropagation(); if(confirm('Yakin ingin hapus bentuk izin <?= htmlspecialchars($bentuk['nama']) ?>?')) location.href='?jenis_izin_id=<?= $jenis_izin_id ?>&hapus=<?= $bentuk['id'] ?>';"><i class="fa fa-trash"></i></button>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalBentukIzin" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
<form method="post" class="modal-content">
<input type="hidden" name="id" id="bentukIzinId">
<div class="modal-header">
<h5 class="modal-title" id="modalBentukIzinLabel">Tambah/Edit Bentuk Izin</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<div class="mb-3">
<label class="form-label">Nama Bentuk Izin</label>
<input type="text" name="nama" id="bentukIzinNama" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Deskripsi</label>
<textarea name="deskripsi" id="bentukIzinDeskripsi" class="form-control"></textarea>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" name="submitBentukIzin" class="btn btn-primary">Simpan</button>
</div>
</form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openModal(eventOrId,id=0,nama='',deskripsi=''){
if(eventOrId.stopPropagation) eventOrId.stopPropagation();
document.getElementById('bentukIzinId').value=id;
document.getElementById('bentukIzinNama').value=nama;
document.getElementById('bentukIzinDeskripsi').value=deskripsi;
document.getElementById('modalBentukIzinLabel').innerText=id>0?"Edit Bentuk Izin":"Tambah Bentuk Izin";
new bootstrap.Modal(document.getElementById('modalBentukIzin')).show();
}
</script>
</body>
</html>
