<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

$client = new Client();

// Pastikan menggunakan file credentials.json dari Google Cloud Console (OAuth client ID)
$client->setAuthConfig(__DIR__ . '/credentials/credentials.json');

// Redirect URI harus sama dengan yang didaftarkan di Google Cloud Console
$client->setRedirectUri('http://localhost/dpmptsp-dashboard/oauth2callback.php');

// Scope Drive penuh
$client->addScope(Drive::DRIVE);

// Pastikan access type offline agar dapat refresh_token
$client->setAccessType('offline');

// Agar token tetap bisa direfresh walaupun user sudah pernah login
$client->setPrompt('consent');

if (!isset($_GET['code'])) {
    // STEP 1: arahkan user untuk login ke akun Google
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
} else {
    // STEP 2: tukar authorization code dengan access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Cek error dari Google
    if (isset($token['error'])) {
        echo "❌ Error saat mendapatkan token: " . htmlspecialchars($token['error_description'] ?? $token['error']);
        exit;
    }

    // Simpan token ke file agar bisa digunakan ulang
    if (!file_exists(__DIR__ . '/credentials')) {
        mkdir(__DIR__ . '/credentials', 0777, true);
    }

    file_put_contents(__DIR__ . '/credentials/token.json', json_encode($token, JSON_PRETTY_PRINT));

    // Simpan token ke session (optional)
    $_SESSION['access_token'] = $token;

    echo "<h3>✅ Berhasil login ke Google Drive!</h3>";
    echo "<p>Token berhasil disimpan ke <code>credentials/token.json</code></p>";
    echo "<a href='dokumen.php'>➡️ Lanjut ke Dashboard</a>";
    exit;
}
