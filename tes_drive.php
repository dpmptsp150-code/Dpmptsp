<?php
// Autoload Composer
require 'vendor/autoload.php';

// Gunakan namespace agar class dikenali
use Google_Client;
use Google_Service_Drive;

// Path ke file credentials JSON
putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials/credentials.json');

// Buat client Google
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope(Google_Service_Drive::DRIVE);

// Buat service Google Drive
$service = new Google_Service_Drive($client);

// ID folder yang ingin dicek
$folderId = '1APUnrCvVZqz1UQAAutWfKzk6aqtpDIea';

try {
    // Ambil daftar file di folder
    $results = $service->files->listFiles([
        'q' => "'$folderId' in parents and trashed = false",
        'fields' => 'files(id, name)'
    ]);

    if (count($results->files) === 0) {
        echo "❌ Folder kosong atau belum diakses.";
    } else {
        echo "✅ Folder bisa diakses! Isi folder:<br>";
        foreach ($results->files as $file) {
            echo "- " . htmlspecialchars($file->name) . " (" . $file->id . ")<br>";
        }
    }

} catch (Exception $e) {
    echo "❌ Terjadi error: " . $e->getMessage();
}
?>
