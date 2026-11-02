MY STORE - PHP + MYSQL (XAMPP)
================================

ISI PROJECT
- Login admin
- Dashboard ringkasan
- CRUD data barang
- Kelola kategori
- Kelola pelanggan
- Transaksi penjualan dengan pengecekan stok
- Invoice transaksi dan cetak
- Laporan penjualan berdasarkan tanggal
- Database MySQL

CARA INSTALASI
1. Pastikan XAMPP dan Visual Studio Code sudah terpasang.
2. Ekstrak folder my_store ke:
   C:\xampp\htdocs\my_store
3. Buka XAMPP Control Panel, klik Start pada Apache dan MySQL.
4. Buka browser:
   http://localhost/phpmyadmin
5. Pilih menu Import, pilih file database.sql dari folder project,
   lalu klik Import/Go. Database my_store akan dibuat.
6. Buka:
   http://localhost/my_store/setup_admin.php
   Halaman ini mengatur password awal admin menjadi admin123.
7. Setelah muncul pesan berhasil, HAPUS file setup_admin.php dari folder project.
8. Buka:
   http://localhost/my_store
9. Login:
   Username: admin
   Password: admin123

CARA MEMBUKA DI VISUAL STUDIO CODE
1. Buka VS Code.
2. Pilih File > Open Folder.
3. Pilih C:\xampp\htdocs\my_store.
4. Edit file PHP/CSS sesuai kebutuhan.
5. Jalankan aplikasi melalui browser, bukan tombol Run Code.

JIKA KONEKSI DATABASE GAGAL
- Pastikan MySQL di XAMPP berstatus Running.
- Periksa config.php. Default XAMPP:
  host localhost, database my_store, user root, password kosong.
- Pastikan database.sql sudah diimpor.

CATATAN
- Aplikasi ini merupakan project pembelajaran/lokal.
- Ganti password admin setelah instalasi dan jangan unggah setup_admin.php ke hosting.
- Untuk penggunaan online, aktifkan HTTPS, buat kredensial database khusus,
  dan lakukan pengujian keamanan tambahan.
