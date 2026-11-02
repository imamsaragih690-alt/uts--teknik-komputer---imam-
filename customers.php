<?php
require_once 'config.php'; require_once 'functions.php'; require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$action=$_POST['action']??'';
 if($action==='delete'){$stmt=$pdo->prepare("DELETE FROM customers WHERE id=?");$stmt->execute([(int)$_POST['id']]);flash('Pelanggan dihapus.');}
 else {$name=trim($_POST['name']??'');$phone=trim($_POST['phone']??'');$address=trim($_POST['address']??'');if($name!==''){$stmt=$pdo->prepare("INSERT INTO customers(name,phone,address) VALUES(?,?,?)");$stmt->execute([$name,$phone,$address]);flash('Pelanggan ditambahkan.');}}
 redirect('customers.php');}
$rows=$pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll();$page_title='Pelanggan';include 'header.php';?>
<div class="content-grid"><section class="panel form-panel"><h3>Tambah Pelanggan</h3><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Nama Pelanggan</label><input name="name" required><label>No. Telepon</label><input name="phone" placeholder="08xxxxxxxxxx"><label>Alamat</label><textarea name="address" rows="3"></textarea><button class="btn btn-primary">Simpan Pelanggan</button></form></section>
<section class="panel"><h3>Daftar Pelanggan</h3><div class="table-wrap"><table><thead><tr><th>Nama</th><th>Telepon</th><th>Alamat</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><b><?=e($r['name'])?></b></td><td><?=e($r['phone'])?></td><td><?=e($r['address'])?></td><td><form method="post" onsubmit="return confirm('Hapus pelanggan?')"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=(int)$r['id']?>"><button class="link-danger">Hapus</button></form></td></tr><?php endforeach;?><?php if(!$rows):?><tr><td colspan="4" class="empty">Belum ada pelanggan.</td></tr><?php endif;?></tbody></table></div></section></div>
<?php include 'footer.php';?>
