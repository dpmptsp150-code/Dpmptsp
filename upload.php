<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

$client = new Client();
$client->setClientId('1096997148105-nua44ejrij790a2nrq3qpcqb1uvaod7o.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/your_project/oauth2callback.php'); 
$client->addScope(Drive::DRIVE);

// Cek token
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} elseif (file_exists('token.json')) {
    $accessToken = json_decode(file_get_contents('token.json'), true);
    $client->setAccessToken($accessToken);
    $_SESSION['access_token'] = $accessToken;
} else {
    // kalau belum login, arahkan ke oauth2callback.php
    header('Location: oauth2callback.php');
    exit;
}

// Cek kalau token expired
if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents('token.json', json_encode($client->getAccessToken()));
    } else {
        header('Location: oauth2callback.php');
        exit;
    }
}

// --- Proses upload ---
$service = new Drive($client);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filePath = $_FILES['file']['tmp_name'];
    $fileName = basename($_FILES['file']['name']);

    $fileMetadata = new Drive\DriveFile([
        'name' => $fileName,
        // kalau mau simpan ke folder tertentu, tambahkan 'parents' => ['FOLDER_ID']
    ]);

    $content = file_get_contents($filePath);

    $file = $service->files->create($fileMetadata, [
        'data' => $content,
        'uploadType' => 'multipart',
        'fields' => 'id'
    ]);

    echo "✅ File berhasil diupload ke Google Drive. File ID: " . $file->id;
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
