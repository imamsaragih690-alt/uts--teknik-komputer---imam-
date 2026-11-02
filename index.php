<?php
require_once 'config.php';
require_once 'functions.php';
require_login();

$products = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$customers = (int)$pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$sales = (int)$pdo->query("SELECT COUNT(*) FROM sales")->fetchColumn();
$income = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM sales")->fetchColumn();
$recent = $pdo->query("SELECT s.id,s.invoice_no,s.sale_date,s.total,c.name customer_name
                       FROM sales s LEFT JOIN customers c ON c.id=s.customer_id
                       ORDER BY s.id DESC LIMIT 5")->fetchAll();
$page_title = 'Dashboard';
include 'header.php';
?>
<div class="welcome">
  <div><h2>Selamat datang di MY STORE!</h2><p>Kelola toko, barang, pelanggan, dan transaksi dalam satu aplikasi.</p></div>
  <span class="welcome-icon">🛍️</span>
</div>
<div class="stats">
  <div class="stat-card"><span>Total Barang</span><strong><?= $products ?></strong><small>Produk terdaftar</small></div>
  <div class="stat-card"><span>Total Pelanggan</span><strong><?= $customers ?></strong><small>Pelanggan terdaftar</small></div>
  <div class="stat-card"><span>Transaksi</span><strong><?= $sales ?></strong><small>Penjualan tercatat</small></div>
  <div class="stat-card"><span>Total Penjualan</span><strong><?= rupiah($income) ?></strong><small>Akumulasi transaksi</small></div>
</div>
<section class="panel">
  <div class="panel-head"><h3>Transaksi Terbaru</h3><a class="btn btn-primary" href="sales.php">+ Transaksi Baru</a></div>
  <div class="table-wrap"><table><thead><tr><th>No. Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th></tr></thead><tbody>
  <?php if (!$recent): ?><tr><td colspan="4" class="empty">Belum ada transaksi.</td></tr><?php endif; ?>
  <?php foreach ($recent as $r): ?><tr><td><?= e($r['invoice_no']) ?></td><td><?= e($r['sale_date']) ?></td><td><?= e($r['customer_name'] ?: 'Umum') ?></td><td><?= rupiah($r['total']) ?></td></tr><?php endforeach; ?>
  </tbody></table></div>
</section>
<?php include 'footer.php'; ?>
