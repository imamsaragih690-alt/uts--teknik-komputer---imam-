-- Database MY STORE
CREATE DATABASE IF NOT EXISTS my_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_store;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS customers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 phone VARCHAR(30) NULL,
 address TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 sku VARCHAR(40) NOT NULL UNIQUE,
 name VARCHAR(150) NOT NULL,
 category_id INT NULL,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 stock INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
   ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sales (
 id INT AUTO_INCREMENT PRIMARY KEY,
 invoice_no VARCHAR(40) NOT NULL UNIQUE,
 customer_id INT NULL,
 total DECIMAL(14,2) NOT NULL DEFAULT 0,
 sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_sales_customer FOREIGN KEY (customer_id) REFERENCES customers(id)
   ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sale_items (
 id INT AUTO_INCREMENT PRIMARY KEY,
 sale_id INT NOT NULL,
 product_id INT NOT NULL,
 quantity INT NOT NULL,
 price DECIMAL(12,2) NOT NULL,
 subtotal DECIMAL(14,2) NOT NULL,
 CONSTRAINT fk_items_sale FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
 CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products(id)
   ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Akun awal: username admin, password admin123
-- Hash password dibuat dengan password_hash() PHP.
INSERT INTO users(full_name, username, password)
VALUES ('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username=username;

INSERT IGNORE INTO categories(name) VALUES
('Makanan'),('Minuman'),('Aksesoris'),('Peralatan');

INSERT IGNORE INTO products(sku,name,category_id,price,stock) VALUES
('BRG001','Air Mineral 600ml',2,4000,50),
('BRG002','Biskuit Cokelat',1,7500,30),
('BRG003','Buku Tulis',4,5000,40);

INSERT IGNORE INTO customers(name,phone,address) VALUES
('Pelanggan Contoh','081234567890','Alamat contoh');
