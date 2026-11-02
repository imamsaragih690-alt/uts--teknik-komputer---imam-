<?php
require_once 'config.php'; require_once 'functions.php'; require_login();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $action=$_POST['action']??'';
    if ($action==='delete') {
        $stmt=$pdo->prepare("DELETE FROM products WHERE id=?"); $stmt->execute([(int)$_POST['id']]);
        flash('Barang berhasil dihapus.'); redirect('products.php');
    }
    $name=trim($_POST['name']??''); $sku=trim($_POST['sku']??'');
    $category=(int)($_POST['category_id']??0); $price=(float)($_POST['price']??0); $stock=(int)($_POST['stock']??0);
    if ($name==='' || $sku==='' || $price<0 || $stock<0) { flash('Periksa kembali data barang.','error'); redirect('products.php'); }
    if ($action==='add') {
        try {$stmt=$pdo->prepare("INSERT INTO products(sku,name,category_id,price,stock) VALUES(?,?,?,?,?)");$stmt->execute([$sku,$name,$category?:null,$price,$stock]);flash('Barang berhasil ditambahkan.');}
        catch(PDOException $e){flash('Gagal menyimpan. Pastikan kode barang belum digunakan.','error');}
    } elseif ($action==='edit') {
        try {$stmt=$pdo->prepare("UPDATE products SET sku=?,name=?,category_id=?,price=?,stock=? WHERE id=?");$stmt->execute([$sku,$name,$category?:null,$price,$stock,(int)$_POST['id']]);flash('Barang berhasil diperbarui.');}
        catch(PDOException $e){flash('Gagal memperbarui barang. Periksa kode barang.','error');}
    }
    redirect('products.php');
}
$edit=null;
if(isset($_GET['edit'])){$stmt=$pdo->prepare("SELECT * FROM products WHERE id=?");$stmt->execute([(int)$_GET['edit']]);$edit=$stmt->fetch();}
$categories=$pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$rows=$pdo->query("SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC")->fetchAll();
$page_title='Data Barang'; include 'header.php';
?>
<div class="content-grid">
<section class="panel form-panel"><h3><?= $edit?'Edit Barang':'Tambah Barang' ?></h3>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="<?= $edit?'edit':'add' ?>"><?php if($edit):?><input type="hidden" name="id" value="<?= (int)$edit['id']?>"><?php endif;?>
<label>Kode Barang (SKU)</label><input name="sku" required value="<?= e($edit['sku']??'') ?>" placeholder="BRG001">
<label>Nama Barang</label><input name="name" required value="<?= e($edit['name']??'') ?>" placeholder="Nama barang">
<label>Kategori</label><select name="category_id"><option value="">-- Pilih kategori --</option><?php foreach($categories as $c):?><option value="<?= (int)$c['id']?>" <?= (int)($edit['category_id']??0)===(int)$c['id']?'selected':''?>><?= e($c['name'])?></option><?php endforeach;?></select>
<label>Harga (Rp)</label><input type="number" name="price" min="0" step="1" required value="<?= e($edit['price']??'') ?>">
<label>Stok</label><input type="number" name="stock" min="0" required value="<?= e($edit['stock']??'0') ?>">
<button class="btn btn-primary"><?= $edit?'Simpan Perubahan':'Tambah Barang' ?></button><?php if($edit):?> <a class="btn btn-light" href="products.php">Batal</a><?php endif;?>
</form></section>
<section class="panel"><div class="panel-head"><h3>Daftar Barang</h3><span class="count"><?= count($rows) ?> barang</span></div><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=e($r['sku'])?></td><td><b><?=e($r['name'])?></b></td><td><?=e($r['category_name']?:'-')?></td><td><?=rupiah($r['price'])?></td><td><?= (int)$r['stock']?></td><td class="actions"><a href="?edit=<?=(int)$r['id']?>">Edit</a><form method="post" onsubmit="return confirm('Hapus barang ini?')"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=(int)$r['id']?>"><button class="link-danger">Hapus</button></form></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="6" class="empty">Belum ada barang.</td></tr><?php endif;?>
</tbody></table></div></section></div>
<?php include 'footer.php'; ?>
