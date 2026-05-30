<?php
session_start();

// 1. LOAD CONFIG & KONEKSI
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/SettingModel.php';

// Tarik Pengaturan Toko Buat Header Sidebar
$db = (new Database())->getConnection();
$appSettings = (new SettingModel($db))->getAllSettings();

// 2. CEK GEMBOK UTAMA (LOGIN)
if (!isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../app/views/login.php';
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pageTitle = 'YoriPOS';

// 3. CEK KUNCI RUANGAN (RBAC)
$permissions = $_SESSION['permissions'] ?? [];
$page_mapping = [
    'dashboard'    => 'dashboard',
    'pos'          => 'pos',
    'menus'        => 'products',
    'products'     => 'products',
    'categories'   => 'products',
    'stocks'       => 'stocks',
    'transactions' => 'transactions',
    'receivables'  => 'transactions',
    'orders'       => 'transactions',
    'expenses'     => 'transactions',
    'report_pl'    => 'dashboard', 
    'print_pl'     => 'dashboard',
    'closing'      => 'settings',
    'system_logs'  => 'settings',
    'settings'     => 'settings',
    'users'        => 'users'
];

$required_permission = $page_mapping[$page] ?? '';

// Kalau halamannya butuh kunci, tapi *user* nggak punya kuncinya: TENDANG!
if ($required_permission && !in_array($required_permission, $permissions)) {
    die("
        <div style='text-align:center; padding:100px; font-family:sans-serif; background:#f8fafc; height:100vh;'>
            <h1 style='color:#ef4444; font-size:4rem; margin:0;'>🛑 403</h1>
            <h2>AKSES DITOLAK!</h2>
            <p style='color:#64748b;'>Lu nggak punya kunci (akses) buat masuk ke ruangan ini.</p>
            <a href='".APP_URL."/admin/' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#4f46e5; color:white; text-decoration:none; border-radius:8px;'>Kembali ke Beranda</a>
        </div>
    ");
}

// 4. JIKA AMAN, RENDER HALAMAN SEPERTI BIASA
$contentFile = '';

switch ($page) {
    case 'dashboard': 
        $contentFile = __DIR__ . '/../app/views/admin/dashboard.php'; 
        $pageTitle = 'Dashboard Overview';
        break;
    case 'pos': 
        $contentFile = __DIR__ . '/../app/views/pos.php'; 
        $pageTitle = 'Mesin Kasir (POS)';
        break;
    case 'menus':
        $contentFile = __DIR__ . '/../app/views/admin/menus.php';
        $pageTitle = 'Master Menu POS';
        break;
    case 'products': 
        $contentFile = __DIR__ . '/../app/views/admin/products.php'; 
        $pageTitle = 'Master Produk';
        break;
    case 'categories': 
        $contentFile = __DIR__ . '/../app/views/admin/categories.php'; 
        $pageTitle = 'Master Kategori';
        break;
    case 'stocks': 
        $contentFile = __DIR__ . '/../app/views/admin/stocks.php'; 
        $pageTitle = 'Stok Masuk (FIFO)';
        break;
    case 'transactions': 
        $contentFile = __DIR__ . '/../app/views/admin/transactions.php'; 
        $pageTitle = 'Riwayat Transaksi';
        break;
    case 'receivables': 
        $contentFile = __DIR__ . '/../app/views/admin/receivables.php'; 
        $pageTitle = 'Monitoring Piutang';
        break;
    case 'expenses': 
        $contentFile = __DIR__ . '/../app/views/admin/expenses.php'; 
        $pageTitle = 'Pengeluaran Operasional';
        break;
    case 'report_pl':
        $contentFile = __DIR__ . '/../app/views/admin/report_pl.php'; 
        $pageTitle = 'Laporan Laba Rugi (P&L)';
        break;
    case 'print_pl':
        require_once __DIR__ . '/../app/views/admin/print_pl.php';
        exit;
    case 'orders': 
        $contentFile = __DIR__ . '/../app/views/admin/orders.php'; 
        $pageTitle = 'Pesanan & Delivery';
        break;
    case 'settings': 
        $contentFile = __DIR__ . '/../app/views/admin/settings.php'; 
        $pageTitle = 'Pengaturan Toko';
        break;
    case 'closing':
        $contentFile = __DIR__ . '/../app/views/admin/closing.php'; 
        $pageTitle = 'Tutup Buku Bulanan';
        break;
    case 'system_logs': 
        $contentFile = __DIR__ . '/../app/views/admin/logs.php'; 
        $pageTitle = 'System Error Logs';
        break;
    case 'users': 
        $contentFile = __DIR__ . '/../app/views/admin/users.php'; 
        $pageTitle = 'Kelola Staf & Akses';
        break;
    default: 
        $contentFile = __DIR__ . '/../app/views/admin/dashboard.php'; 
        $pageTitle = 'Dashboard Overview';
        break;
}

if (file_exists($contentFile)) {
    require_once __DIR__ . '/../app/views/layout.php';
} else {
    echo "<h2 style='text-align:center; padding:50px; font-family:sans-serif; color:#64748b;'>🛠️ Fitur <b>{$page}</b> Under Construction</h2>";
}
?>