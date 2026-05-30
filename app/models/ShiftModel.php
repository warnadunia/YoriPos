<?php
// app/models/ShiftModel.php

class ShiftModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getShiftStats() {
        $user_id = $_SESSION['user_id'] ?? 1; 
        
        try {
            $stmt = $this->db->prepare("SELECT * FROM cashier_shifts WHERE user_id = ? AND status = 'open' ORDER BY id DESC LIMIT 1");
            $stmt->execute([$user_id]);
            $shift = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($shift) {
                // [Hitung Uang] Cash, QRIS, Transfer
                $stmtCash = $this->db->prepare("SELECT SUM(total_amount) as cash_sales FROM sales WHERE payment_method = 'CASH' AND status = 'lunas' AND created_at >= ?");
                $stmtCash->execute([$shift['open_time']]);
                $cash_sales = $stmtCash->fetch(PDO::FETCH_ASSOC)['cash_sales'] ?? 0;

                $stmtQris = $this->db->prepare("SELECT SUM(total_amount) as qris_sales FROM sales WHERE payment_method = 'QRIS' AND status = 'lunas' AND created_at >= ?");
                $stmtQris->execute([$shift['open_time']]);
                $qris_sales = $stmtQris->fetch(PDO::FETCH_ASSOC)['qris_sales'] ?? 0;

                $stmtTransfer = $this->db->prepare("SELECT SUM(total_amount) as transfer_sales FROM sales WHERE payment_method = 'TRANSFER' AND status = 'lunas' AND created_at >= ?");
                $stmtTransfer->execute([$shift['open_time']]);
                $transfer_sales = $stmtTransfer->fetch(PDO::FETCH_ASSOC)['transfer_sales'] ?? 0;
                
                // [Hitung Dokumen] KWI, ORD, INV selama shift berlangsung
                $stmtKwi = $this->db->prepare("SELECT COUNT(id) as count_kwi FROM sales WHERE invoice_no LIKE 'KWI-%' AND created_at >= ?");
                $stmtKwi->execute([$shift['open_time']]);
                $count_kwi = $stmtKwi->fetch(PDO::FETCH_ASSOC)['count_kwi'] ?? 0;

                $stmtOrd = $this->db->prepare("SELECT COUNT(id) as count_ord FROM sales WHERE invoice_no LIKE 'ORD-%' AND created_at >= ?");
                $stmtOrd->execute([$shift['open_time']]);
                $count_ord = $stmtOrd->fetch(PDO::FETCH_ASSOC)['count_ord'] ?? 0;

                $stmtInv = $this->db->prepare("SELECT COUNT(id) as count_inv FROM sales WHERE invoice_no LIKE 'INV-%' AND created_at >= ?");
                $stmtInv->execute([$shift['open_time']]);
                $count_inv = $stmtInv->fetch(PDO::FETCH_ASSOC)['count_inv'] ?? 0;

                $expected = $shift['starting_cash'] + $cash_sales;

                return [
                    'status' => 'success', 
                    'data' => [
                        'starting_cash' => $shift['starting_cash'],
                        'cash_sales' => $cash_sales,
                        'qris_sales' => $qris_sales,
                        'transfer_sales' => $transfer_sales,
                        'expected_cash' => $expected,
                        'count_kwi' => $count_kwi,
                        'count_ord' => $count_ord,
                        'count_inv' => $count_inv
                    ]
                ];
            }
            return ['status' => 'error', 'message' => 'Tidak ada shift aktif'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function openShift($starting_cash, $cashier_name = 'Kasir') {
        $user_id = $_SESSION['user_id'] ?? 1;
        $user_name = $_SESSION['name'] ?? $cashier_name;
        
        try {
            $stmt = $this->db->prepare("INSERT INTO cashier_shifts (user_id, user_name, starting_cash, status) VALUES (?, ?, ?, 'open')");
            $stmt->execute([$user_id, $user_name, $starting_cash]);
            return ['status' => 'success'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()];
        }
    }

    public function closeShift($actual_cash, $notes) {
        $user_id = $_SESSION['user_id'] ?? 1;
        
        try {
            $stmt = $this->db->prepare("SELECT * FROM cashier_shifts WHERE user_id = ? AND status = 'open' ORDER BY id DESC LIMIT 1");
            $stmt->execute([$user_id]);
            $shift = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($shift) {
                // FIX: Hapus pengecekan 'user_id' dari tabel sales di sini juga!
                $stmtSales = $this->db->prepare("SELECT SUM(total_amount) as cash_sales FROM sales WHERE payment_method = 'CASH' AND status = 'lunas' AND created_at >= ?");
                $stmtSales->execute([$shift['open_time']]);
                $sales = $stmtSales->fetch(PDO::FETCH_ASSOC);
                
                $cash_sales = $sales['cash_sales'] ?? 0;
                $expected = $shift['starting_cash'] + $cash_sales;
                $actual = floatval($actual_cash);
                $diff = $actual - $expected;

                $stmtUpdate = $this->db->prepare("UPDATE cashier_shifts SET close_time = NOW(), actual_cash = ?, expected_cash = ?, notes = ?, status = 'closed' WHERE id = ?");
                $stmtUpdate->execute([$actual, $expected, $notes, $shift['id']]);

                $printData = [
                    'user_name' => $shift['user_name'],
                    'open_time' => $shift['open_time'],
                    'close_time' => date('Y-m-d H:i:s'),
                    'starting_cash' => $shift['starting_cash'],
                    'cash_sales' => $cash_sales,
                    'expected_cash' => $expected,
                    'actual_cash' => $actual,
                    'difference' => $diff,
                    'notes' => $notes
                ];
                return ['status' => 'success', 'print_data' => $printData];
            }
            return ['status' => 'error', 'message' => 'Shift tidak ditemukan!'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
?>