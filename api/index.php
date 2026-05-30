<?php
session_start();

// ==========================================
// 1. LOAD CONFIG & DATABASE (WAJIB PERTAMA)
// ==========================================
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';

// Bangun koneksi database duluan
$database = new Database();
$db = $database->getConnection();

// ==========================================
// 2. LOAD SEMUA MODEL (SETELAH DB KONEK)
// ==========================================
require_once __DIR__ . '/../app/models/ProductModel.php';
require_once __DIR__ . '/../app/models/SaleModel.php';
require_once __DIR__ . '/../app/models/SettingModel.php';
require_once __DIR__ . '/../app/models/ShiftModel.php';
require_once __DIR__ . '/../app/models/UserModel.php';

// Inisialisasi Model
$productModel = new ProductModel($db);
$saleModel = new SaleModel($db);
$settingModel = new SettingModel($db);
$shiftModel = new ShiftModel($db);
$userModel = new UserModel($db);

$action = isset($_GET['action']) ? $_GET['action'] : 'view';

// ==========================================
// 3. ENDPOINT API (ROUTING)
// ==========================================

// --- ENDPOINT AUTENTIKASI ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);
    echo json_encode($userModel->login($input['username'], $input['password']));
    exit;
}

if ($action === 'logout') {
    session_destroy();
    header("Location: " . APP_URL . "/admin/");
    exit;
}

// --- SHIFT KASIR ---
if ($action === 'shift_status') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($shiftModel->getShiftStats()); exit;
}
if ($action === 'shift_open' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($shiftModel->openShift($data['starting_cash'], $data['cashier_name'] ?? 'Kasir')); exit;
}
if ($action === 'shift_close' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($shiftModel->closeShift($data['actual_cash'], $data['notes'] ?? '')); exit;
}

// --- READ PRODUCTS ---
if ($action === 'get_products') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $query = "SELECT p.*, 
                 (SELECT COUNT(id) FROM product_recipes WHERE menu_id = p.id) as has_recipe 
                 FROM products p ORDER BY p.id DESC";
        $stmt = $db->query($query);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtRecipe = $db->query("SELECT r.menu_id, r.material_id, r.qty_required, p.total_stock as material_stock 
                                  FROM product_recipes r 
                                  JOIN products p ON r.material_id = p.id");
        $recipes = $stmtRecipe->fetchAll(PDO::FETCH_ASSOC);

        $recipe_map = [];
        foreach ($recipes as $r) {
            $recipe_map[$r['menu_id']][] = [
                'material_id' => $r['material_id'],
                'qty_required' => floatval($r['qty_required']),
                'material_stock' => floatval($r['material_stock'])
            ];
        }

        foreach ($products as &$prod) {
            if ($prod['has_recipe'] > 0 && isset($recipe_map[$prod['id']])) {
                $prod['recipe_details'] = $recipe_map[$prod['id']];
            } else {
                $prod['recipe_details'] = [];
            }
        }
        echo json_encode(["status" => "success", "data" => $products]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// --- PENGATURAN TOKO ---
if ($action === 'get_settings') {
    header('Content-Type: application/json; charset=utf-8');
    $data = $settingModel->getAllSettings();
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

if ($action === 'save_settings' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);
    $result = $settingModel->saveSettings($input);
    echo json_encode($result);
    exit;
}

// --- MANAJEMEN PRODUK ---
if ($action === 'save_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || empty($data['name']) || !isset($data['price_sell'])) {
        echo json_encode(['status' => 'error', 'message' => 'Nama dan Harga Jual wajib diisi!']); exit;
    }
    try {
        if (empty($data['id'])) {
            $query = "INSERT INTO products (sku, category, type, unit, image_url, name, price_sell) VALUES (:sku, :category, :type, :unit, :image_url, :name, :price_sell)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':sku' => $data['sku'] ?? '', ':category' => $data['category'] ?? 'Lainnya',
                ':type' => $data['type'] ?? 'produk_jual', ':unit' => $data['unit'] ?? 'pcs',
                ':image_url' => $data['image_url'] ?? '', ':name' => $data['name'], ':price_sell' => $data['price_sell']
            ]);
            $msg = "Data berhasil ditambahkan!";
        } else {
            $query = "UPDATE products SET sku = :sku, category = :category, type = :type, unit = :unit, image_url = :image_url, name = :name, price_sell = :price_sell WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':sku' => $data['sku'] ?? '', ':category' => $data['category'] ?? 'Lainnya',
                ':type' => $data['type'] ?? 'produk_jual', ':unit' => $data['unit'] ?? 'pcs',
                ':image_url' => $data['image_url'] ?? '', ':name' => $data['name'], ':price_sell' => $data['price_sell'], ':id' => $data['id']
            ]);
            $msg = "Data berhasil diperbarui!";
        }
        echo json_encode(['status' => 'success', 'message' => $msg]);
    } catch (PDOException $e) { echo json_encode(['status' => 'error', 'message' => 'Gagal simpan data: ' . $e->getMessage()]); }
    exit;
}

if ($action === 'delete_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $data['id']]);
        echo json_encode(['status' => 'success', 'message' => 'Data dihapus!']);
    } catch (PDOException $e) { echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']); }
    exit;
}

// --- RESEP ---
if ($action === 'get_recipe' && isset($_GET['menu_id'])) {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $db->prepare("SELECT r.*, p.name as material_name, p.unit FROM product_recipes r JOIN products p ON r.material_id = p.id WHERE r.menu_id = ?");
        $stmt->execute([$_GET['menu_id']]);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) { echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); }
    exit;
}

if ($action === 'save_recipe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $db->beginTransaction();
        $del = $db->prepare("DELETE FROM product_recipes WHERE menu_id = ?");
        $del->execute([$data['menu_id']]);
        if (!empty($data['materials'])) {
            $ins = $db->prepare("INSERT INTO product_recipes (menu_id, material_id, qty_required) VALUES (?, ?, ?)");
            foreach ($data['materials'] as $m) { $ins->execute([$data['menu_id'], $m['material_id'], $m['qty']]); }
        }
        $db->commit();
        echo json_encode(['status' => 'success', 'message' => 'Komposisi resep berhasil dikunci!']);
    } catch (PDOException $e) {
        $db->rollBack(); echo json_encode(['status' => 'error', 'message' => 'Gagal simpan resep: ' . $e->getMessage()]);
    }
    exit;
}

// --- STOK MASUK ---
if ($action === 'get_stocks') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $db->query("SELECT s.*, p.name as product_name, p.sku FROM stock_in s JOIN products p ON s.product_id = p.id ORDER BY s.date_in DESC LIMIT 100");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); }
    exit;
}

if ($action === 'save_stock' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    if (empty($data['product_id']) || empty($data['qty']) || empty($data['total_price_buy'])) {
        echo json_encode(['status' => 'error', 'message' => 'Produk, Qty, dan Total Harga Nota wajib diisi!']); exit;
    }
    try {
        $qty = floatval($data['qty']); $totalPrice = floatval($data['total_price_buy']);
        if ($qty <= 0) { echo json_encode(['status' => 'error', 'message' => 'Qty tidak boleh nol!']); exit; }
        $unitPrice = $totalPrice / $qty; 
        $db->beginTransaction();
        $stmt = $db->prepare("INSERT INTO stock_in (product_id, qty, price_buy, supplier, date_in) VALUES (:product_id, :qty, :price_buy, :supplier, NOW())");
        $stmt->execute([':product_id' => $data['product_id'], ':qty' => $qty, ':price_buy' => $unitPrice, ':supplier' => $data['supplier'] ?? '-']);
        
        $batch_no = 'BCH-' . date('YmdHis') . '-' . rand(100, 999);
        $stmtBatch = $db->prepare("INSERT INTO stock_batches (product_id, batch_no, price_buy, qty_initial, qty_remaining, date_received) VALUES (:product_id, :batch_no, :price_buy, :qty_initial, :qty_remaining, NOW())");
        $stmtBatch->execute([':product_id' => $data['product_id'], ':batch_no' => $batch_no, ':price_buy' => $unitPrice, ':qty_initial' => $qty, ':qty_remaining' => $qty]);
        
        $stmtUpdate = $db->prepare("UPDATE products SET total_stock = total_stock + :qty WHERE id = :product_id");
        $stmtUpdate->execute([':qty' => $qty, ':product_id' => $data['product_id']]);
        
        $db->commit();
        echo json_encode(['status' => 'success', 'message' => 'Stok FIFO berhasil ditambahkan!']);
    } catch (PDOException $e) { $db->rollBack(); echo json_encode(['status' => 'error', 'message' => 'Gagal simpan stok: ' . $e->getMessage()]); }
    exit;
}

// --- TRANSAKSI POS ---
if ($action === 'checkout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        // --- 1. LOGIKA BACKDATE & VALIDASI CLOSING ---
        // Cek apakah ada request backdate dari form VIP kasir
        $trx_date = !empty($data['transaction_date']) ? date('Y-m-d H:i:s', strtotime($data['transaction_date'])) : date('Y-m-d H:i:s');
        $trx_month = date('n', strtotime($trx_date));
        $trx_year = date('Y', strtotime($trx_date));

        // Validasi Softclosing Bulanan
        $stmtCheckMonth = $db->prepare("SELECT id FROM closed_periods WHERE period_month = ? AND period_year = ?");
        $stmtCheckMonth->execute([$trx_month, $trx_year]);
        if($stmtCheckMonth->fetch()) {
            throw new Exception("Bulan {$trx_month}/{$trx_year} sudah ditutup! Backdate ditolak.");
        }

        // Validasi Annual Closing Tahunan
        $stmtCheckYear = $db->prepare("SELECT id FROM closed_years WHERE period_year = ?");
        $stmtCheckYear->execute([$trx_year]);
        if($stmtCheckYear->fetch()) {
            throw new Exception("Tahun {$trx_year} sudah dikunci permanen! Transaksi ditolak.");
        }

        // Sisipkan tanggal yang sudah divalidasi ke dalam payload untuk dikirim ke Model
        $data['created_at'] = $trx_date; 

        // --- 2. LANJUT KE PROSES CHECKOUT DI MODEL ---
        require_once __DIR__ . '/../app/models/SaleModel.php';
        $saleModel = new SaleModel($db);
        
        $result = $saleModel->processSale($data);
        
        if ($result['status'] === 'success') {
            echo json_encode(['status' => 'success', 'invoice' => $result['invoice']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// --- KALENDER ACTIVE DATES ---
if ($action === 'get_active_dates') {
    header('Content-Type: application/json; charset=utf-8');
    $month = $_GET['month'] ?? date('n');
    $year = $_GET['year'] ?? date('Y');
    $type = $_GET['type'] ?? 'completed';
    
    try {
        // Ambil daftar tanggal unik yang ada transaksinya di bulan & tahun tersebut
        $query = "SELECT DISTINCT DATE(created_at) as active_date FROM sales WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?";
        
        if ($type === 'completed') { $query .= " AND status = 'lunas'"; }
        else if ($type === 'receivable') { $query .= " AND payment_method = 'PIUTANG' AND status = 'proses'"; }
        else if ($type === 'active_order') { $query .= " AND type = 'order' AND status = 'proses'"; }
        
        $stmt = $db->prepare($query);
        $stmt->execute([$month, $year]);
        
        // FETCH_COLUMN narik datanya jadi array flat: ['2026-05-30', '2026-05-31', ...]
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN); 
        
        echo json_encode(['status' => 'success', 'data' => $dates]);
    } catch (Exception $e) { 
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
    }
    exit;
}

if ($action === 'get_transactions') {
    header('Content-Type: application/json; charset=utf-8');
    try { echo json_encode(["status" => "success", "data" => $saleModel->getSalesData('completed', $_GET['date'] ?? null)]); } 
    catch (Exception $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); } exit;
}

if ($action === 'get_receivables') {
    header('Content-Type: application/json; charset=utf-8');
    try { echo json_encode(["status" => "success", "data" => $saleModel->getSalesData('receivable', $_GET['date'] ?? null)]); } 
    catch (Exception $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); } exit;
}

if ($action === 'get_orders') {
    header('Content-Type: application/json; charset=utf-8');
    try { echo json_encode(["status" => "success", "data" => $saleModel->getSalesData('active_order', $_GET['date'] ?? null)]); } 
    catch (Exception $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); } exit;
}

if ($action === 'complete_order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $success = $saleModel->completeOrder($data['id'], $data['payment_method']);
        if($success) { echo json_encode(["status" => "success", "message" => "Pesanan berhasil diselesaikan!"]); } 
        else { echo json_encode(["status" => "error", "message" => "Gagal memproses pesanan."]); }
    } catch (Exception $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); } exit;
}

// --- KATEGORI & PELANGGAN ---
if ($action === 'get_categories') {
    header('Content-Type: application/json; charset=utf-8');
    try { $stmt = $db->query("SELECT * FROM categories ORDER BY name ASC"); echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]); } 
    catch (PDOException $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); } exit;
}

if ($action === 'get_customers') {
    header('Content-Type: application/json; charset=utf-8');
    try { $stmt = $db->query("SELECT * FROM customers ORDER BY name ASC"); echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]); } 
    catch (PDOException $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); } exit;
}

if ($action === 'save_customer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    if (empty($data['name'])) { echo json_encode(['status' => 'error', 'message' => 'Nama wajib diisi!']); exit; }
    try {
        if (empty($data['id'])) {
            $stmt = $db->prepare("INSERT INTO customers (name, phone, address) VALUES (:name, :phone, :address)");
            $stmt->execute([':name' => $data['name'], ':phone' => $data['phone'] ?? '', ':address' => $data['address'] ?? '']);
            echo json_encode(['status' => 'success', 'message' => "Pelanggan ditambahkan!", 'data' => ['id' => $db->lastInsertId(), 'name' => $data['name']]]);
        } else {
            $stmt = $db->prepare("UPDATE customers SET name = :name, phone = :phone, address = :address WHERE id = :id");
            $stmt->execute([':name' => $data['name'], ':phone' => $data['phone'] ?? '', ':address' => $data['address'] ?? '', ':id' => $data['id']]);
            echo json_encode(['status' => 'success', 'message' => "Data pelanggan diperbarui!", 'data' => ['id' => $data['id'], 'name' => $data['name']]]);
        }
    } catch (PDOException $e) { echo json_encode(['status' => 'error', 'message' => 'Gagal DB: ' . $e->getMessage()]); } exit;
}

// --- PENGELUARAN (OPEX) ---
if ($action === 'get_expenses') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $db->query("SELECT * FROM expenses ORDER BY expense_date DESC, id DESC LIMIT 100");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); }
    exit;
}

if ($action === 'save_expense' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    if (empty($data['category']) || empty($data['amount']) || empty($data['expense_date'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tanggal, Kategori, dan Nominal wajib diisi!']); exit;
    }
    try {
        $amount = floatval($data['amount']);
        if (empty($data['id'])) {
            $stmt = $db->prepare("INSERT INTO expenses (expense_date, category, amount, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['expense_date'], $data['category'], $amount, $data['description'] ?? '']);
            echo json_encode(['status' => 'success', 'message' => 'Pengeluaran berhasil dicatat!']);
        } else {
            $stmt = $db->prepare("UPDATE expenses SET expense_date=?, category=?, amount=?, description=? WHERE id=?");
            $stmt->execute([$data['expense_date'], $data['category'], $amount, $data['description'] ?? '', $data['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Data pengeluaran diperbarui!']);
        }
    } catch (PDOException $e) { echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); }
    exit;
}

if ($action === 'delete_expense' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare("DELETE FROM expenses WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['status' => 'success', 'message' => 'Data dihapus!']);
    } catch (PDOException $e) { echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']); }
    exit;
}

// --- DASHBOARD STATS ---
if ($action === 'get_dashboard_stats') {
    require_once __DIR__ . '/../app/models/ReportModel.php';
    $reportModel = new ReportModel($db);
    header('Content-Type: application/json; charset=utf-8');
    try { echo json_encode(["status" => "success", "data" => $reportModel->getDashboardStats()]); } 
    catch (Exception $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); } exit;
}
// --- LAPORAN KEUANGAN (LABA RUGI) ---
if ($action === 'get_profit_loss') {
    require_once __DIR__ . '/../app/models/ReportModel.php';
    $reportModel = new ReportModel($db);
    header('Content-Type: application/json; charset=utf-8');
    
    // Default: Bulan Berjalan
    $start = $_GET['start'] ?? date('Y-m-01');
    $end = $_GET['end'] ?? date('Y-m-t');

    try { 
        echo json_encode(["status" => "success", "data" => $reportModel->getProfitLossReport($start, $end)]); 
    } catch (Exception $e) { 
        echo json_encode(["status" => "error", "message" => $e->getMessage()]); 
    } 
    exit;
}

// --- VIEW RECEIPT (DIGITAL STRUK HTML) ---
if ($action === 'view_receipt' && isset($_GET['invoice'])) {
    $invoice = $_GET['invoice'];
    
    $stmt = $db->prepare("SELECT s.*, c.name as customer_name, c.phone as customer_phone FROM sales s LEFT JOIN customers c ON s.customer_id = c.id WHERE s.invoice_no = ?");
    $stmt->execute([$invoice]);
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sale) { 
        echo "<h2 style='text-align:center; font-family:sans-serif; margin-top:50px; color:#ef4444;'>Nota tidak ditemukan!</h2>"; 
        exit; 
    }

    $stmtDet = $db->prepare("SELECT sd.*, p.name as product_name FROM sale_details sd JOIN products p ON sd.product_id = p.id WHERE sd.sale_id = ?");
    $stmtDet->execute([$sale['id']]);
    $details = $stmtDet->fetchAll(PDO::FETCH_ASSOC);
    
    $dataSettings = $settingModel->getAllSettings();
    $store_name = $dataSettings['store_name'] ?? 'YoriPOS Toko';
    $store_address = $dataSettings['store_address'] ?? 'Alamat Toko Belum Diset';
    $store_phone = $dataSettings['store_phone'] ?? '-';
    $receipt_footer = $dataSettings['receipt_footer'] ?? 'Terima kasih atas kunjungan Anda.';
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nota - <?= htmlspecialchars($invoice) ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style> @media print { body { background-color: white !important; } .no-print { display: none !important; } } </style>
    </head>
    <body class="bg-slate-100 flex justify-center p-4 md:p-10 text-slate-800 font-sans">
        <div class="bg-white w-full max-w-md p-6 sm:p-8 rounded-2xl shadow-lg relative overflow-hidden">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-black text-indigo-700 tracking-tight"><?= htmlspecialchars($store_name) ?></h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Telp: <?= htmlspecialchars($store_phone) ?></p>
                <p class="text-[10px] text-slate-400 mt-1"><?= htmlspecialchars($store_address) ?></p>
            </div>
            <div class="border-t-2 border-dashed border-slate-200 my-4"></div>
            <div class="flex justify-between items-start text-sm mb-2">
                <div>
                    <p class="text-xs text-slate-500">No. Nota</p>
                    <p class="font-bold font-mono text-indigo-600"><?= htmlspecialchars($sale['invoice_no']) ?></p>
                    <?php if(!empty($sale['reference_no'])): ?>
                        <p class="text-[10px] text-slate-400 font-mono">Ref: <?= htmlspecialchars($sale['reference_no']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500">Tanggal</p>
                    <p class="font-medium"><?= date('d M Y, H:i', strtotime($sale['created_at'])) ?></p>
                </div>
            </div>
            <div class="flex justify-between items-start text-sm mb-4">
                <div>
                    <p class="text-xs text-slate-500">Pelanggan</p>
                    <p class="font-bold"><?= htmlspecialchars($sale['customer_name'] ?? 'Pelanggan Umum') ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500">Kasir</p>
                    <p class="font-medium">Admin</p>
                </div>
            </div>
            <div class="space-y-3 mb-6">
                <?php foreach($details as $d): ?>
                <div class="flex justify-between text-sm">
                    <div class="flex-1">
                        <p class="font-bold text-slate-700"><?= htmlspecialchars($d['product_name']) ?></p>
                        <p class="text-xs text-slate-500"><?= $d['qty'] ?> x Rp <?= number_format($d['price_sell_at_sale'], 0, ',', '.') ?></p>
                    </div>
                    <div class="font-bold text-slate-800 self-end">
                        Rp <?= number_format($d['qty'] * $d['price_sell_at_sale'], 0, ',', '.') ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="border-t-2 border-dashed border-slate-200 my-4"></div>
            <div class="flex justify-between items-center mb-2">
                <p class="font-bold text-slate-600">Total Tagihan</p>
                <p class="text-xl font-black text-emerald-600">Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></p>
            </div>
            <div class="flex justify-between items-center text-sm">
                <p class="text-slate-500">Status Pembayaran</p>
                <p class="font-bold uppercase"><?= htmlspecialchars($sale['payment_method']) ?></p>
            </div>
            <div class="mt-8 text-center">
                <p class="text-xs text-slate-400 italic"><?= nl2br(htmlspecialchars($receipt_footer)) ?></p>
            </div>
            <div class="mt-8 text-center no-print flex gap-3">
                <button onclick="window.print()" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors">
                    🖨️ Cetak Struk
                </button>
                <button onclick="window.close()" class="flex-1 bg-slate-200 text-slate-700 font-bold py-3 rounded-xl hover:bg-slate-300 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- KELOLA AKSES (ROLES & USERS) ---
if ($action === 'get_roles') {
    header('Content-Type: application/json; charset=utf-8');
    $stmt = $db->query("SELECT * FROM roles ORDER BY name ASC");
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]); exit;
}

if ($action === 'save_role') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    $perms = json_encode($data['permissions'] ?? []);
    try {
        if (empty($data['id'])) {
            $stmt = $db->prepare("INSERT INTO roles (name, permissions) VALUES (?, ?)");
            $stmt->execute([$data['name'], $perms]);
        } else {
            $stmt = $db->prepare("UPDATE roles SET name = ?, permissions = ? WHERE id = ?");
            $stmt->execute([$data['name'], $perms, $data['id']]);
        }
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) { echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); }
    exit;
}

if ($action === 'get_users') {
    header('Content-Type: application/json; charset=utf-8');
    $stmt = $db->query("SELECT u.id, u.username, u.name, u.role_id, r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id");
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]); exit;
}

if ($action === 'save_user') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        if (empty($data['id'])) {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, password, name, role_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['username'], $hash, $data['name'], $data['role_id']]);
        } else {
            if (!empty($data['password'])) { // Kalau password diisi, update passwordnya
                $hash = password_hash($data['password'], PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET username=?, password=?, name=?, role_id=? WHERE id=?");
                $stmt->execute([$data['username'], $hash, $data['name'], $data['role_id'], $data['id']]);
            } else { // Kalau kosong, update data lainnya aja
                $stmt = $db->prepare("UPDATE users SET username=?, name=?, role_id=? WHERE id=?");
                $stmt->execute([$data['username'], $data['name'], $data['role_id'], $data['id']]);
            }
        }
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) { echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); }
    exit;
}

// --- TUTUP BUKU BULANAN (PERIOD CLOSING) ---
if ($action === 'get_closed_periods') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $db->query("SELECT * FROM closed_periods ORDER BY period_year DESC, period_month DESC");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); }
    exit;
}

if ($action === 'do_closing' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare("INSERT INTO closed_periods (period_month, period_year, closed_by) VALUES (?, ?, ?)");
        $stmt->execute([$data['month'], $data['year'], $_SESSION['name'] ?? 'Admin']);
        echo json_encode(['status' => 'success', 'message' => 'Buku periode ' . $data['month'] . '/' . $data['year'] . ' berhasil dikunci!']);
    } catch (PDOException $e) { 
        // Tangkap error jika periode sudah pernah ditutup (UNIQUE KEY violation)
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'Periode ini sudah ditutup sebelumnya!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
        }
    }
    exit;
}

// --- TUTUP BUKU TAHUNAN (ANNUAL CLOSING) ---
if ($action === 'get_closed_years') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $db->query("SELECT * FROM closed_years ORDER BY period_year DESC");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); }
    exit;
}

if ($action === 'do_annual_closing' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare("INSERT INTO closed_years (period_year, closed_by) VALUES (?, ?)");
        $stmt->execute([$data['year'], $_SESSION['name'] ?? 'Admin']);
        echo json_encode(['status' => 'success', 'message' => 'Buku tahun ' . $data['year'] . ' resmi dikunci secara permanen!']);
    } catch (PDOException $e) { 
        if ($e->getCode() == 23000) echo json_encode(['status' => 'error', 'message' => 'Tahun ini sudah ditutup sebelumnya!']);
        else echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
    }
    exit;
}
// --- SYSTEM ERROR LOGS ---
if ($action === 'get_logs') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        // Ambil 200 error terakhir aja biar database nggak berat saat diload
        $stmt = $db->query("SELECT * FROM system_logs ORDER BY id DESC LIMIT 200");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (Exception $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); }
    exit;
}

if ($action === 'clear_logs' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $db->query("TRUNCATE TABLE system_logs");
        echo json_encode(["status" => "success", "message" => "Semua log error berhasil dibersihkan!"]);
    } catch (Exception $e) { echo json_encode(["status" => "error", "message" => $e->getMessage()]); }
    exit;
}

// --- VERCEL BLOB UPLOAD ---
if ($action === 'upload_blob' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang diunggah.']); exit;
    }

    // Ambil Token dari Config (Aman & Sentral)
    $token = BLOB_READ_WRITE_TOKEN; 
    
    if (!$token || $token === 'ISI_TOKEN_VERCEL_BLOB_DISINI') {
        echo json_encode(['status' => 'error', 'message' => 'Token Vercel Blob belum dikonfigurasi di app/config/config.php.']); exit;
    }

    $file = $_FILES['image'];
    $cleanFilename = preg_replace("/[^a-zA-Z0-9.]/", "", basename($file['name']));
    $filename = time() . '_' . $cleanFilename;
    $url = "https://blob.vercel-storage.com/" . $filename;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [ "Authorization: Bearer " . $token, "x-api-version: 7", "content-type: " . $file['type'] ]);
    
    $tempFile = fopen($file['tmp_name'], 'r');
    curl_setopt($ch, CURLOPT_INFILE, $tempFile);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file['tmp_name']));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch); fclose($tempFile);

    $resData = json_decode($response, true);
    if ($httpCode === 200 && isset($resData['url'])) { echo json_encode(['status' => 'success', 'url' => $resData['url']]); } 
    else { echo json_encode(['status' => 'error', 'message' => 'Gagal upload ke Blob. Status: ' . $httpCode]); }
    exit;
}
?>