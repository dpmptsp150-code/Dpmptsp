<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "arsip_dpmptsp";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

$jenis_id = intval($_GET['jenis_id']);
$result = $conn->query("SELECT id, nama FROM bentuk_izin WHERE jenis_izin_id=$jenis_id ORDER BY nama ASC");
$bentuk_list = [];
while($row = $result->fetch_assoc()) $bentuk_list[] = $row;

header('Content-Type: application/json');
echo json_encode($bentuk_list);
