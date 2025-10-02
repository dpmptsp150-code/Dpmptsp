<?php
session_start();
include 'config.php'; // koneksi database

$error = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name']     = $row['name'];
            $_SESSION['role']     = $row['role'];

            // redirect sesuai role
            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - Aplikasi Arsip</title>
<link rel="icon" type="image/png" href="images/icon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* style login seperti sebelumnya */
body, html { height: 100%; margin: 0; font-family: Arial, sans-serif; }
.login-container { display: flex; height: 100vh; }
.login-left { flex: 7; background-image: url('images/kantor.jpg'); background-size: cover; background-position: center; position: relative; }
.login-left::before { content: ''; position: absolute; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.4); }
.login-right { flex: 3; display: flex; justify-content: center; align-items: center; background-color: #f6f5f0ff; padding: 20px; }
.card-login { width: 100%; max-width: 360px; padding: 20px; text-align: center; border-radius: 8px; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
.card-login img { display: block; margin: 0 auto 15px; width: 100%; max-width: 200px; height: auto; }
.card-login input { border-radius: 5px; border: 1px solid #ccc; padding: 10px; width: 100%; margin-bottom: 15px; }
.login-extra a { text-decoration: none; }
.login-extra a:hover { text-decoration: underline; }
.or-divider { margin: 20px 0; text-align: center; position: relative; }
.or-divider::before, .or-divider::after { content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: #ccc; }
.or-divider::before { left: 0; } .or-divider::after { right: 0; }
.or-divider span { padding: 0 10px; background: #fff; position: relative; }
@media (max-width: 768px) { .login-container { flex-direction: column; } .login-left { flex: none; height: 40vh; } .login-right { flex: none; height: 60vh; } }
</style>
</head>
<body>
<div class="login-container">
    <div class="login-left"></div>
    <div class="login-right">
        <div class="card-login">
            <img src="https://dpmptsp.kupangkota.go.id/wp-content/uploads/2024/06/FINAL-DPMPTSP-Typography-Logo-1024x403.png" alt="Logo DPMPTSP">
            <h4 class="mb-4">Login</h4>
            <?php if($error){ echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Masukkan username" required>
                <input type="password" name="password" placeholder="Masukkan password" required>
                <button class="btn btn-primary w-100 mb-2">Login</button>
            </form>

            <!-- Lupa Password -->
            <div class="login-extra mt-2">
                <a href="lupa_password.php">Lupa Sandi?</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
