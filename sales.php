<?php
require_once 'config.php'; require_once 'functions.php'; require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$customer=(int)($_POST['customer_id']??0);$productIds=$_POST['product_id']??[];$qtys=$_POST['qty']??[];
 $items=[];$total=0;
 try{
  $pdo->beginTransaction();
  foreach($productIds as $i=>$pid){$pid=(int)$pid;$qty=(int)($qtys[$i]??0);if($pid<1||$qty<1)continue;
   $stmt=$pdo->prepare("SELECT id,name,price,stock FROM products WHERE id=? FOR UPDATE");$stmt->execute([$pid]);$p=$stmt->fetch();
   if(!$p)throw new Exception('Barang tidak ditemukan.');
   if($p['stock']<$qty)throw new Exception('Stok '.$p['name'].' tidak mencukupi.');
   $subtotal=$p['price']*$qty;$total+=$subtotal;$items[]=['product'=>$p,'qty'=>$qty,'subtotal'=>$subtotal];
  }
  if(!$items)throw new Exception('Pilih minimal satu barang dan jumlahnya.');
  $invoice='INV'.date('YmdHis').random_int(10,99);
  $stmt=$pdo->prepare("INSERT INTO sales(invoice_no,customer_id,total) VALUES(?,?,?)");$stmt->execute([$invoice,$customer?:null,$total]);$saleId=(int)$pdo->lastInsertId();
  foreach($items as $it){$stmt=$pdo->prepare("INSERT INTO sale_items(sale_id,product_id,quantity,price,subtotal) VALUES(?,?,?,?,?)");$stmt->execute([$saleId,$it['product']['id'],$it['qty'],$it['product']['price'],$it['subtotal']]);$stmt=$pdo->prepare("UPDATE products SET stock=stock-? WHERE id=?");$stmt->execute([$it['qty'],$it['product']['id']]);}
  $pdo->commit();flash("Transaksi berhasil disimpan. Invoice: $invoice");redirect('sales.php');
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();flash($e->getMessage(),'error');redirect('sales.php');}
}
$products=$pdo->query("SELECT id,sku,name,price,stock FROM products WHERE stock>0 ORDER BY name")->fetchAll();
$customers=$pdo->query("SELECT id,name FROM customers ORDER BY name")->fetchAll();
$recent=$pdo->query("SELECT s.*,c.name customer_name FROM sales s LEFT JOIN customers c ON c.id=s.customer_id ORDER BY s.id DESC LIMIT 10")->fetchAll();
$page_title='Penjualan';include 'header.php';?>
<section class="panel"><h3>Buat Transaksi Penjualan</h3><?php if(!$products):?><p class="empty">Belum ada barang dengan stok tersedia. Tambahkan barang terlebih dahulu.</p><?php else:?>
<form method="post" id="sale-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<label>Pelanggan</label><select name="customer_id"><option value="">Pelanggan Umum</option><?php foreach($customers as $c):?><option value="<?=(int)$c['id']?>"><?=e($c['name'])?></option><?php endforeach;?></select>
<div id="items"><div class="sale-line"><div><label>Barang</label><select name="product_id[]" required class="product-select"><option value="">-- Pilih barang --</option><?php foreach($products as $p):?><option value="<?=(int)$p['id']?>" data-price="<?=$p['price']?>" data-stock="<?=$p['stock']?>"><?=e($p['sku'].' - '.$p['name'].' (stok '.$p['stock'].')')?></option><?php endforeach;?></select></div><div><label>Jumlah</label><input type="number" name="qty[]" min="1" value="1" required></div><button type="button" class="remove-line" onclick="this.parentElement.remove()">×</button></div></div>
<button type="button" class="btn btn-light" onclick="addLine()">+ Tambah Barang</button><p class="muted">Total akan dihitung dan disimpan berdasarkan harga barang saat transaksi.</p><button class="btn btn-primary">Simpan Transaksi</button>
</form><?php endif;?></section>
<section class="panel"><h3>Transaksi Terbaru</h3><div class="table-wrap"><table><thead><tr><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Detail</th></tr></thead><tbody><?php foreach($recent as $r):?><tr><td><?=e($r['invoice_no'])?></td><td><?=e($r['sale_date'])?></td><td><?=e($r['customer_name']?:'Umum')?></td><td><?=rupiah($r['total'])?></td><td><a href="invoice.php?id=<?=(int)$r['id']?>">Lihat</a></td></tr><?php endforeach;?><?php if(!$recent):?><tr><td colspan="5" class="empty">Belum ada transaksi.</td></tr><?php endif;?></tbody></table></div></section>
<script>
function addLine(){
 const first=document.querySelector('.sale-line'); const clone=first.cloneNode(true);
 clone.querySelector('select').selectedIndex=0; clone.querySelector('input').value=1;
 document.getElementById('items').appendChild(clone);
}
</script>
<?php include 'footer.php';?>
