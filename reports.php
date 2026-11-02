<?php
require_once 'config.php';require_once 'functions.php';require_login();
$from=$_GET['from']??date('Y-m-01');$to=$_GET['to']??date('Y-m-d');
$stmt=$pdo->prepare("SELECT s.*,c.name customer_name FROM sales s LEFT JOIN customers c ON c.id=s.customer_id WHERE DATE(s.sale_date) BETWEEN ? AND ? ORDER BY s.id DESC");$stmt->execute([$from,$to]);$rows=$stmt->fetchAll();
$total=array_sum(array_column($rows,'total'));$page_title='Laporan Penjualan';include 'header.php';?>
<section class="panel"><form method="get" class="filter-form"><div><label>Dari Tanggal</label><input type="date" name="from" value="<?=e($from)?>"></div><div><label>Sampai Tanggal</label><input type="date" name="to" value="<?=e($to)?>"></div><button class="btn btn-primary">Tampilkan</button><button type="button" class="btn btn-light" onclick="window.print()">Cetak</button></form>
<div class="report-total"><span>Total penjualan pada periode ini</span><strong><?=rupiah($total)?></strong><small><?=count($rows)?> transaksi</small></div>
<div class="table-wrap"><table><thead><tr><th>No.</th><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Detail</th></tr></thead><tbody><?php foreach($rows as $i=>$r):?><tr><td><?=$i+1?></td><td><?=e($r['invoice_no'])?></td><td><?=e($r['sale_date'])?></td><td><?=e($r['customer_name']?:'Umum')?></td><td><?=rupiah($r['total'])?></td><td><a href="invoice.php?id=<?=(int)$r['id']?>">Lihat</a></td></tr><?php endforeach;?><?php if(!$rows):?><tr><td colspan="6" class="empty">Tidak ada transaksi pada periode ini.</td></tr><?php endif;?></tbody></table></div></section>
<?php include 'footer.php';?>
