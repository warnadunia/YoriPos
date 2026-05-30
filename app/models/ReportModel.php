<?php
class ReportModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDashboardStats() {
        try {
            // 1. Omzet & Laba HARI INI
            $stmtToday = $this->db->query("
                SELECT 
                    COALESCE(SUM(sd.qty * sd.price_sell_at_sale), 0) as revenue,
                    COALESCE(SUM(sd.qty * (sd.price_sell_at_sale - sd.price_buy_at_sale)), 0) as profit
                FROM sales s 
                JOIN sale_details sd ON s.id = sd.sale_id
                WHERE s.status = 'lunas' AND DATE(s.created_at) = CURDATE()
            ");
            $today = $stmtToday->fetch(PDO::FETCH_ASSOC);

            // 2. Omzet & Laba BULAN INI
            $stmtMonth = $this->db->query("
                SELECT 
                    COALESCE(SUM(sd.qty * sd.price_sell_at_sale), 0) as revenue,
                    COALESCE(SUM(sd.qty * (sd.price_sell_at_sale - sd.price_buy_at_sale)), 0) as profit
                FROM sales s 
                JOIN sale_details sd ON s.id = sd.sale_id
                WHERE s.status = 'lunas' 
                AND MONTH(s.created_at) = MONTH(CURDATE()) 
                AND YEAR(s.created_at) = YEAR(CURDATE())
            ");
            $month = $stmtMonth->fetch(PDO::FETCH_ASSOC);

            // 3. 5 Produk Terlaris Bulan Ini
            $stmtTop = $this->db->query("
                SELECT p.name, SUM(sd.qty) as total_qty
                FROM sale_details sd
                JOIN sales s ON s.id = sd.sale_id
                JOIN products p ON sd.product_id = p.id
                WHERE s.status = 'lunas' AND MONTH(s.created_at) = MONTH(CURDATE())
                GROUP BY p.id 
                ORDER BY total_qty DESC 
                LIMIT 5
            ");
            $top_products = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

            // 4. Stok Hampir Habis (Peringatan Dini)
            $stmtLowStock = $this->db->query("
                SELECT name, total_stock 
                FROM products 
                WHERE type = 'produk_jual' AND total_stock <= 10 
                ORDER BY total_stock ASC 
                LIMIT 5
            ");
            $low_stock = $stmtLowStock->fetchAll(PDO::FETCH_ASSOC);

            // 5. Saldo per Metode Pembayaran (Bulan Ini)
            $stmtMethod = $this->db->query("
                SELECT payment_method, COALESCE(SUM(total_amount), 0) as total 
                FROM sales 
                WHERE status = 'lunas' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
                GROUP BY payment_method
                ORDER BY total DESC
            ");
            $methods = $stmtMethod->fetchAll(PDO::FETCH_ASSOC);

            // 6. Piutang (Belum Lunas)
            $stmtPiutang = $this->db->query("
                SELECT COUNT(id) as count, COALESCE(SUM(total_amount), 0) as total 
                FROM sales 
                WHERE status = 'proses' AND payment_method = 'PIUTANG'
            ");
            $piutang = $stmtPiutang->fetch(PDO::FETCH_ASSOC);

            // 7. Pesanan Aktif
            $stmtOrder = $this->db->query("
                SELECT COUNT(id) as count, COALESCE(SUM(total_amount), 0) as total 
                FROM sales 
                WHERE status = 'proses' AND payment_method = 'PENDING'
            ");
            $orders = $stmtOrder->fetch(PDO::FETCH_ASSOC);

            return [
                'today' => $today,
                'month' => $month,
                'top_products' => $top_products,
                'low_stock' => $low_stock,
                'methods' => $methods,
                'piutang' => $piutang,
                'orders' => $orders
            ];
        } catch (\PDOException $e) {
            throw new Exception("Error kalkulasi: " . $e->getMessage());
        }
    }

    // --- LAPORAN LABA RUGI (PROFIT & LOSS) ---
    public function getProfitLossReport($startDate, $endDate) {
        try {
            // 1. Ambil Pendapatan (Revenue) & HPP (COGS)
            $stmtSales = $this->db->prepare("
                SELECT 
                    COALESCE(SUM(sd.qty * sd.price_sell_at_sale), 0) as total_revenue,
                    COALESCE(SUM(sd.qty * sd.price_buy_at_sale), 0) as total_cogs
                FROM sales s
                JOIN sale_details sd ON s.id = sd.sale_id
                WHERE s.status = 'lunas' AND DATE(s.created_at) >= ? AND DATE(s.created_at) <= ?
            ");
            $stmtSales->execute([$startDate, $endDate]);
            $salesData = $stmtSales->fetch(PDO::FETCH_ASSOC);

            // 2. Ambil Pengeluaran (OPEX) dikelompokkan per Kategori
            $stmtExp = $this->db->prepare("
                SELECT category, SUM(amount) as total 
                FROM expenses 
                WHERE expense_date >= ? AND expense_date <= ?
                GROUP BY category
                ORDER BY total DESC
            ");
            $stmtExp->execute([$startDate, $endDate]);
            $expenses = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

            $total_opex = 0;
            foreach($expenses as $e) { $total_opex += $e['total']; }

            $gross_profit = $salesData['total_revenue'] - $salesData['total_cogs'];
            $net_profit = $gross_profit - $total_opex;

            return [
                'period' => ['start' => $startDate, 'end' => $endDate],
                'revenue' => $salesData['total_revenue'],
                'cogs' => $salesData['total_cogs'],
                'gross_profit' => $gross_profit,
                'expenses_detail' => $expenses,
                'total_opex' => $total_opex,
                'net_profit' => $net_profit
            ];
        } catch (\PDOException $e) {
            throw new Exception("Error kalkulasi P&L: " . $e->getMessage());
        }
    }
    
    public function getSummaryReport() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    COUNT(id) as total_transactions,
                    COALESCE(SUM(total_amount), 0) as total_revenue
                FROM sales 
                WHERE status = 'lunas'
            ");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: ['total_transactions' => 0, 'total_revenue' => 0];
        } catch (\PDOException $e) {
            return ['total_transactions' => 0, 'total_revenue' => 0];
        }
    }
}