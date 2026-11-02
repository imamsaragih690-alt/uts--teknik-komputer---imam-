<?php
require_once 'config.php';
require_once 'functions.php';
if (!empty($_SESSION['user_id'])) redirect('index.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = $pdo->prepare("SELECT id, full_name, username, password FROM users WHERE username=? LIMIT 1");
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        redirect('index.php');
    }
    $error = 'Username atau password salah.';
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login - MY STORE</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="login-body"><div class="login-card">
  <div class="login-logo">M</div><h1>MY STORE</h1><p class="muted">Masuk ke sistem manajemen toko</p>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label>Username</label><input name="username" required autofocus placeholder="Masukkan username">
    <label>Password</label><input type="password" name="password" required placeholder="Masukkan password">
    <button class="btn btn-primary btn-full">Masuk</button>
  </form>
  <p class="login-hint">Akun awal: <b>admin</b> / <b>admin123</b></p>
</div></body></html>
