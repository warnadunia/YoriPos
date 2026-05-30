<?php
// app/models/SaleModel.php

class SaleModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function processSale($data) {
        try {
            $this->conn->beginTransaction();

            // Ekstrak data dari array API
            $customer_id = $data['customer_id'] ?? null;
            $total_amount = $data['total_amount'] ?? 0;
            $payment_method = $data['payment_method'] ?? 'CASH';
            $cart_items = $data['cart'] ?? [];
            $type = $data['order_type'] ?? 'direct';
            
            // Tangkap tanggal dari API (mendukung Backdate)
            $created_at = $data['created_at'] ?? date('Y-m-d H:i:s'); 

            // Penentuan Kodifikasi Struk
            $status = 'lunas';
            $prefix = 'KWI-';
            if ($type === 'order') {
                $status = 'proses';
                $prefix = 'ORD-';
            } else if ($payment_method === 'PIUTANG') {
                $status = 'proses';
                $prefix = 'INV-';
            }
            $invoice_no = $prefix . date('YmdHis') . rand(10,99);

            // 1. Simpan Header Transaksi (Menggunakan $created_at)
            $stmt = $this->conn->prepare("INSERT INTO sales (invoice_no, customer_id, total_amount, payment_method, type, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$invoice_no, $customer_id, $total_amount, $payment_method, $type, $status, $created_at]);
            $sale_id = $this->conn->lastInsertId();

            // 2. Bongkar Isi Keranjang (Cek Resep & Potong Stok)
            foreach ($cart_items as $item) {
                $menu_id = $item['product_id'];
                $sell_qty = intval($item['qty']);
                $price_sell = floatval($item['price_sell']);
                
                $total_hpp_for_this_item = 0; 
                
                $stmtRecipe = $this->conn->prepare("SELECT material_id, qty_required FROM product_recipes WHERE menu_id = ?");
                $stmtRecipe->execute([$menu_id]);
                $recipes = $stmtRecipe->fetchAll(PDO::FETCH_ASSOC);

                if (count($recipes) > 0) {
                    foreach ($recipes as $mat) {
                        $material_id = $mat['material_id'];
                        $total_material_needed = floatval($mat['qty_required']) * $sell_qty;
                        $hpp_material = $this->deductFifoStock($material_id, $total_material_needed);
                        $total_hpp_for_this_item += $hpp_material;
                    }
                    $hpp_per_unit = $total_hpp_for_this_item / $sell_qty;
                } else {
                    $total_hpp_for_this_item = $this->deductFifoStock($menu_id, $sell_qty);
                    $hpp_per_unit = $total_hpp_for_this_item / $sell_qty;
                }

                $stmtDetail = $this->conn->prepare("INSERT INTO sale_details (sale_id, product_id, stock_batch_id, qty, price_buy_at_sale, price_sell_at_sale) VALUES (?, ?, 0, ?, ?, ?)");
                $stmtDetail->execute([$sale_id, $menu_id, $sell_qty, $hpp_per_unit, $price_sell]);
            }

            $this->conn->commit();
            return ["status" => "success", "invoice" => $invoice_no];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Mesin Inti: Memotong Stok berdasarkan Batch terlama (FIFO)
     */
    private function deductFifoStock($product_id, $qty_needed) {
        $total_hpp_calculated = 0;
        $remaining_to_deduct = $qty_needed;

        // Ambil batch stok yang masih ada isinya, urutkan dari yang paling tua
        $stmt = $this->conn->prepare("SELECT id, price_buy, qty_remaining FROM stock_batches WHERE product_id = ? AND qty_remaining > 0 ORDER BY date_received ASC");
        $stmt->execute([$product_id]);
        $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($batches as $batch) {
            if ($remaining_to_deduct <= 0) break;

            $qty_available = floatval($batch['qty_remaining']);
            $price_buy = floatval($batch['price_buy']); // Ini adalah HPP Modal Ecer dari nota belanja

            if ($qty_available >= $remaining_to_deduct) {
                // Skenario A: Batch ini sisa banyak, potong sebagian aja
                $total_hpp_calculated += ($remaining_to_deduct * $price_buy);
                
                $new_qty = $qty_available - $remaining_to_deduct;
                $updateBatch = $this->conn->prepare("UPDATE stock_batches SET qty_remaining = ? WHERE id = ?");
                $updateBatch->execute([$new_qty, $batch['id']]);
                
                $remaining_to_deduct = 0;
            } else {
                // Skenario B: Batch ini kurang, habisin semuanya dan lanjut cari di batch berikutnya
                $total_hpp_calculated += ($qty_available * $price_buy);
                $remaining_to_deduct -= $qty_available;
                
                $updateBatch = $this->conn->prepare("UPDATE stock_batches SET qty_remaining = 0 WHERE id = ?");
                $updateBatch->execute([$batch['id']]);
            }
        }

        if ($remaining_to_deduct > 0) {
            // Error handling khusus kalau stok di gudang benar-benar kurang
            throw new Exception("Stok Gudang (BOM/Retail) tidak mencukupi untuk Produk ID: " . $product_id . ". Kurang: " . $remaining_to_deduct);
        }

        // Update saldo akhir total_stock di master tabel
        $updateProduct = $this->conn->prepare("UPDATE products SET total_stock = total_stock - ? WHERE id = ?");
        $updateProduct->execute([$qty_needed, $product_id]);

        return $total_hpp_calculated;
    }

    // Fungsi tambahan untuk History Transaksi dengan Filter Tanggal
    public function getSalesData($filter = 'completed', $date = null) {
        // Default ke hari ini jika tidak ada tanggal yang dikirim
        $date = $date ?? date('Y-m-d');

        $query = "SELECT s.*, c.name as customer_name, c.phone as customer_phone FROM sales s LEFT JOIN customers c ON s.customer_id = c.id ";
        $query .= "WHERE DATE(s.created_at) = :filter_date "; // Filter Tanggal Harian
        
        if ($filter === 'completed') { $query .= "AND s.status = 'lunas' "; } 
        else if ($filter === 'receivable') { $query .= "AND s.payment_method = 'PIUTANG' AND s.status = 'proses' "; } 
        else if ($filter === 'active_order') { $query .= "AND s.type = 'order' AND s.status = 'proses' "; }
        
        $query .= "ORDER BY s.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':filter_date', $date);
        $stmt->execute();
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tarik detail item
        foreach ($sales as &$sale) {
            $sale['invoice_number'] = $sale['invoice_no'];
            $stmtDetail = $this->conn->prepare("SELECT sd.*, p.name as product_name FROM sale_details sd JOIN products p ON sd.product_id = p.id WHERE sd.sale_id = ?");
            $stmtDetail->execute([$sale['id']]);
            $details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);
            foreach ($details as &$d) { $d['price_sell'] = $d['price_sell_at_sale']; }
            $sale['items'] = $details;
        }
        return $sales;
    }

    public function completeOrder($id, $payment_method) {
        try {
            // Ambil kode invoice saat ini (bisa ORD- atau INV-)
            // TYPO FIXED: $this->db diganti jadi $this->conn
            $stmt = $this->conn->prepare("SELECT invoice_no FROM sales WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $current_invoice = $stmt->fetchColumn();

            $new_invoice = $current_invoice;
            $status = 'lunas';

            if ($payment_method === 'PIUTANG') {
                $status = 'proses';
                $new_invoice = str_replace('ORD-', 'INV-', $current_invoice);
            } else {
                // Kalau lunas, ubah awalan ORD- atau INV- menjadi KWI-
                $new_invoice = str_replace(['ORD-', 'INV-'], 'KWI-', $current_invoice);
            }

            // Update database, jadikan kode sebelumnya sebagai referensi!
            $stmt = $this->conn->prepare("
                UPDATE sales 
                SET status = :status, 
                    payment_method = :payment_method,
                    reference_no = :old_invoice,
                    invoice_no = :new_invoice
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':status' => $status,
                ':payment_method' => $payment_method,
                ':old_invoice' => $current_invoice, // KUNCI: Selalu simpan kode terakhir sebagai referensi
                ':new_invoice' => $new_invoice,
                ':id' => $id
            ]);

            return ['status' => 'success', 'message' => 'Pesanan berhasil diselesaikan!'];
        } catch (Exception $e) { // Diganti jadi Exception umum biar error apa aja ketangkep
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
?>