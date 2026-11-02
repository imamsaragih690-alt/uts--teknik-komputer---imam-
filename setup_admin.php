<?php
// Jalankan sekali setelah mengimpor database, lalu hapus file ini.
require_once 'config.php';
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password=? WHERE username='admin'");
$stmt->execute([$hash]);
echo "Password admin berhasil diatur menjadi admin123. Demi keamanan, hapus file setup_admin.php sekarang.";
