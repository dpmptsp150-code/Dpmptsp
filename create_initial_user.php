<?php
include 'config.php'; // pastikan koneksi ke dpmptsp-dashboard

$users = [
    ['username' => 'devweb', 'password' => 'dev12345', 'name' => 'Pengembang Web', 'role' => 'admin'],
    ['username' => 'adminarsip', 'password' => 'arsip123', 'name' => 'Admin Arsip', 'role' => 'admin'],
    ['username' => 'user1', 'password' => 'user123', 'name' => 'User 1', 'role' => 'user'],
    ['username' => 'user2', 'password' => 'user234', 'name' => 'User 2', 'role' => 'user']
];

foreach ($users as $u) {
    $username = $u['username'];
    $password = password_hash($u['password'], PASSWORD_DEFAULT);
    $name     = $u['name'];
    $role     = $u['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $name, $role);
    $stmt->execute();
}

echo "4 akun awal berhasil dibuat!";
?>
