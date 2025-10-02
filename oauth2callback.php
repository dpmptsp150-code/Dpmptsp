<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

$client = new Client();

// Ambil konfigurasi dari credentials.json
$client->setAuthConfig(__DIR__ . '/credentials/credentials.json');

// Redirect URI harus sama dengan yang ada di Google Cloud Console
$client->setRedirectUri('http://localhost/dpmptsp-dashboard/oauth2callback.php');

// Tambahkan scope Drive
$client->addScope(Drive::DRIVE);

if (!isset($_GET['code'])) {
    // Langkah 1: arahkan user ke Google untuk login
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
} else {
    // Langkah 2: tukar authorization code dengan access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Cek kalau ada error dari Google
    if (isset($token['error'])) {
        echo "Error saat mendapatkan token: " . htmlspecialchars($token['error_description'] ?? $token['error']);
        exit;
    }

    // Simpan token ke session
    $_SESSION['access_token'] = $token;

    // Opsional: simpan token ke file supaya persistent
    file_put_contents(__DIR__ . '/credentials/token.json', json_encode($token));

    // Redirect ke halaman utama
    header('Location: dokumen.php');
    exit;
}
