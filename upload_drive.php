<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "arsip_dpmptsp";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

use Google\Client;
use Google\Service\Drive;

// Konfigurasi Google Client
$client = new Client();
$client->setAuthConfig(__DIR__ . '/credentials/credentials.json');
$client->addScope(Drive::DRIVE);

// Path token OAuth
$tokenPath = __DIR__ . '/credentials/token.json';

// Cek apakah token sudah ada
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
}

// Jika token belum ada atau expired
if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    } else {
        $authUrl = $client->createAuthUrl();
        echo "<h3>🔑 Autentikasi Diperlukan</h3>";
        echo "<p>Silakan buka link di bawah untuk login ke akun Google Anda:</p>";
        echo "<a href='$authUrl' target='_blank'>$authUrl</a>";
        exit;
    }
    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}

// Buat service Google Drive
$service = new Drive($client);

// Cek apakah ada file yang diupload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    try {
        $filePath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $mimeType = $_FILES['file']['type'];

        // Metadata file
        $fileMetadata = new Drive\DriveFile([
            'name' => $fileName,
            'parents' => ['1APUnrCvVZqz1UQAAutWfKzk6aqtpDIea'] // Folder Drive ID
        ]);

        // Upload file ke Google Drive
        $file = $service->files->create($fileMetadata, [
            'data' => file_get_contents($filePath),
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        $driveFileId = $file->id;
        $driveFileLink = "https://drive.google.com/file/d/$driveFileId/view";

        // Simpan ke database
        $nama_pemilik = "Tidak Diketahui";
        $nama_perusahaan = "Tidak Diketahui";
        $tanggal = date('Y-m-d');
        $tahun = date('Y');
        $nomor_surat = "-";
        $kategori = "Umum";
        $jenis_izin = 1;
        $bentuk_izin_id = 1;
        $file_path = "uploads/" . $fileName;

        $stmt = $conn->prepare("INSERT INTO dokumen 
            (nama_pemilik, nama_perusahaan, tanggal, tahun, nomor_surat, kategori, jenis_izin, bentuk_izin_id, file, file_path, drive_file_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "ssssssiisss",
            $nama_pemilik,
            $nama_perusahaan,
            $tanggal,
            $tahun,
            $nomor_surat,
            $kategori,
            $jenis_izin,
            $bentuk_izin_id,
            $fileName,
            $file_path,
            $driveFileId
        );

        if ($stmt->execute()) {
            echo "<p>✅ File <b>$fileName</b> berhasil diupload ke Google Drive dan disimpan di database.</p>";
            echo "<p>🔗 <a href='$driveFileLink' target='_blank'>Lihat di Google Drive</a></p>";
        } else {
            echo "<p style='color:red;'>❌ Gagal menyimpan ke database: " . htmlspecialchars($stmt->error) . "</p>";
        }

    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Gagal upload ke Google Drive: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload ke Google Drive</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9fafc;
            padding: 40px;
            text-align: center;
        }
        form {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        button {
            background: #1a73e8;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #1558b0;
        }
    </style>
</head>
<body>
    <h2>📤 Upload File ke Google Drive</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
