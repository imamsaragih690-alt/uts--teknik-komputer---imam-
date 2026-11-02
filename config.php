<?php
// Konfigurasi koneksi database MY STORE
$host = 'localhost';
$db   = 'my_store';
$user = 'root';
$pass = ''; // Default XAMPP biasanya kosong
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit('Koneksi database gagal. Pastikan MySQL aktif dan database my_store sudah diimpor. Detail: ' .
         htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
