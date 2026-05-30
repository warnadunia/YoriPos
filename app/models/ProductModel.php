<?php
// app/models/ProductModel.php

class ProductModel {
    private $conn;

    // Konstruktor menerima injeksi koneksi PDO
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Mengambil semua produk beserta total stok yang masih ada.
     */
    public function getAllProducts() {
        // Raw query disesuaikan agar narik semua kolom baru (type, unit, image_url)
        $query = "SELECT * FROM products ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Menambah produk baru ke Master Data.
     * Hanya menyimpan data statis dan harga jual, BUKAN harga beli.
     */
    public function addProduct($sku, $name, $price_sell, $category = null) {
        $query = "INSERT INTO products (sku, name, price_sell, category) 
                  VALUES (:sku, :name, :price_sell, :category)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sku', $sku);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price_sell', $price_sell);
        $stmt->bindParam(':category', $category);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Menambah stok baru (Menciptakan Batch untuk FIFO).
     */
    public function receiveStock($product_id, $batch_no, $price_buy, $qty) {
        $query = "INSERT INTO stock_batches (product_id, batch_no, price_buy, qty_initial, qty_remaining, date_received) 
                  VALUES (:product_id, :batch_no, :price_buy, :qty_initial, :qty_remaining, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':batch_no', $batch_no);
        $stmt->bindParam(':price_buy', $price_buy);
        
        $stmt->bindParam(':qty_initial', $qty);
        $stmt->bindParam(':qty_remaining', $qty);
        
        return $stmt->execute();
    }
}
?>