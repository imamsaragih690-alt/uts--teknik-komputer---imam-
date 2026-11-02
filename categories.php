<?php
require_once 'config.php'; require_once 'functions.php'; require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$action=$_POST['action']??'';
 if($action==='delete'){$stmt=$pdo->prepare("DELETE FROM categories WHERE id=?");$stmt->execute([(int)$_POST['id']]);flash('Kategori dihapus.');}
 else {$name=trim($_POST['name']??'');if($name!==''){try{$stmt=$pdo->prepare("INSERT INTO categories(name) VALUES(?)");$stmt->execute([$name]);flash('Kategori ditambahkan.');}catch(PDOException $e){flash('Kategori sudah ada atau gagal disimpan.','error');}}}
 redirect('categories.php');}
$rows=$pdo->query("SELECT c.*,COUNT(p.id) product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name")->fetchAll();
$page_title='Kategori';include 'header.php';?>
<div class="content-grid compact-grid"><section class="panel form-panel"><h3>Tambah Kategori</h3><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Nama Kategori</label><input name="name" required placeholder="Contoh: Makanan"><button class="btn btn-primary">Simpan Kategori</button></form></section>
<section class="panel"><h3>Daftar Kategori</h3><div class="table-wrap"><table><thead><tr><th>Nama Kategori</th><th>Jumlah Barang</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><b><?=e($r['name'])?></b></td><td><?= (int)$r['product_count']?></td><td><form method="post" onsubmit="return confirm('Hapus kategori? Barang terkait akan menjadi tanpa kategori.')"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=(int)$r['id']?>"><button class="link-danger">Hapus</button></form></td></tr><?php endforeach;?><?php if(!$rows):?><tr><td colspan="3" class="empty">Belum ada kategori.</td></tr><?php endif;?></tbody></table></div></section></div>
<?php include 'footer.php';?>
