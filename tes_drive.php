<?php
require_once 'vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig('credentials.json');
$client->addScope(Google\Service\Drive::DRIVE);
$client->setAccessType('offline');

$service = new Google\Service\Drive($client);
$files = $service->files->listFiles();
foreach($files->getFiles() as $f) {
    echo $f->getName() . "<br>";
}
