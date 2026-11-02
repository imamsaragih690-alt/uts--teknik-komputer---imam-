<?php
$page_title = $page_title ?? 'MY STORE';
$flash_data = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?> - MY STORE</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
<aside class="sidebar">
  <div class="brand"><span class="brand-mark">M</span><div><b>MY STORE</b><small>Management System</small></div></div>
  <nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF'])==='index.php'?'active':'' ?>">▦ Dashboard</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF'])==='products.php'?'active':'' ?>">▣ Data Barang</a>
    <a href="categories.php" class="<?= basename($_SERVER['PHP_SELF'])==='categories.php'?'active':'' ?>">▤ Kategori</a>
    <a href="customers.php" class="<?= basename($_SERVER['PHP_SELF'])==='customers.php'?'active':'' ?>">♙ Pelanggan</a>
    <a href="sales.php" class="<?= basename($_SERVER['PHP_SELF'])==='sales.php'?'active':'' ?>">＋ Penjualan</a>
    <a href="reports.php" class="<?= basename($_SERVER['PHP_SELF'])==='reports.php'?'active':'' ?>">▥ Laporan</a>
  </nav>
  <div class="sidebar-bottom"><small>Login sebagai</small><b><?= e($_SESSION['full_name'] ?? 'Admin') ?></b><a href="logout.php">↪ Keluar</a></div>
</aside>
<main class="main">
  <header class="topbar"><div><span class="eyebrow">TOKO / <?= strtoupper(e($page_title)) ?></span><h1><?= e($page_title) ?></h1></div><div class="user-pill">👤 <?= e($_SESSION['full_name'] ?? 'Admin') ?></div></header>
  <?php if ($flash_data): ?><div class="alert <?= e($flash_data['type']) ?>"><?= e($flash_data['message']) ?></div><?php endif; ?>
