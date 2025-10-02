<?php
session_start();
include 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];

    // cek username ada atau tidak
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // reset password sementara menjadi 123456
        $new_password = password_hash("123456", PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si", $new_password, $user['id']);
        $update->execute();

        $message = "Password berhasil direset menjadi '123456'. Silakan login dan ganti password di profil.";
    } else {
        $message = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Sandi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4">
                <h4 class="mb-3">Lupa Password</h4>
                <?php if($message) echo "<div class='alert alert-info'>$message</div>"; ?>
                <form method="POST">
                    <input type="text" name="username" class="form-control mb-3" placeholder="Masukkan username" required>
                    <button class="btn btn-primary w-100">Reset Password</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="login.php">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
