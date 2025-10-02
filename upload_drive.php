<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

// Konfigurasi Google Client
$client = new Client();
$client->setAuthConfig(__DIR__ . '/credentials/credentials.json');
$client->addScope(Drive::DRIVE_FILE); // Akses untuk upload file

// Token path
$tokenPath = __DIR__ . '/credentials/token.json';

// Cek apakah sudah ada token
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
}

// Kalau token expired → refresh
if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    } else {
        // Belum ada token → login manual
        $authUrl = $client->createAuthUrl();
        echo "🔑 Silakan buka link ini untuk autentikasi:<br>";
        echo "<a href='$authUrl' target='_blank'>$authUrl</a>";
        exit;
    }
    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}

// Buat service Drive
$service = new Drive($client);

// Jika ada file yang diupload dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filePath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];

    $fileMetadata = new Drive\DriveFile([
        'name' => $fileName,
        // Bisa tentukan folder tujuan dengan 'parents' => ['FOLDER_ID']
    ]);

    $content = file_get_contents($filePath);

    $file = $service->files->create($fileMetadata, [
        'data' => $content,
        'mimeType' => $_FILES['file']['type'],
        'uploadType' => 'multipart',
        'fields' => 'id'
    ]);

    echo "✅ File berhasil diupload ke Google Drive.<br>";
    echo "File ID: " . $file->id;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload ke Google Drive</title>
</head>
<body>
    <h2>Upload File ke Google Drive</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
