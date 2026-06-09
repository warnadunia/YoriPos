<?php
// Cek hak akses untuk tombol VIP Add Order
$perms = $_SESSION['permissions'] ?? [];
$isSuperAdmin = in_array('settings', $perms); // Cuma Super Admin yang punya menu settings

// Tarik Pengaturan Toko dari index.php
$storeName = $appSettings['store_name'] ?? 'Yori App';
$storeLogo = $appSettings['store_logo'] ?? '';

// Logika Sapaan Waktu Otomatis
date_default_timezone_set('Asia/Jakarta');
$hour = (int)date('H');
if ($hour >= 5 && $hour < 11) { $greeting = 'Selamat pagi'; }
elseif ($hour >= 11 && $hour < 15) { $greeting = 'Selamat siang'; }
elseif ($hour >= 15 && $hour < 18) { $greeting = 'Selamat sore'; }
else { $greeting = 'Selamat malam'; }

// Nama User yang Login
$userName = $_SESSION['username'] ?? 'Pengguna';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $storeName ?> - Dashboard Mobile</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if ('serviceWorker' in navigator) { navigator.serviceWorker.register('/sw.js'); }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-900 pb-24"> 
    
    <div class="bg-indigo-600 rounded-b-3xl shadow-lg relative overflow-hidden z-20">
        <div class="absolute top-0 right-0 opacity-10 scale-150 transform translate-x-5 -translate-y-5 pointer-events-none">
            <svg class="w-40 h-40 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3z"></path></svg>
        </div>
        
        <div class="px-6 pt-8 pb-6 relative z-10">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                    <?php if($storeLogo): ?>
                        <img src="<?= $storeLogo ?>" alt="Logo" class="w-11 h-11 rounded-full object-cover border-2 border-white/50 shadow-sm">
                    <?php else: ?>
                        <div class="w-11 h-11 rounded-full bg-white/20 flex justify-center items-center text-white font-black text-lg border-2 border-white/50 shadow-sm">
                            <?= strtoupper(substr($storeName, 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <h1 class="text-xl font-black text-white tracking-wide leading-tight"><?= $storeName ?></h1>
                        <p class="text-indigo-200 text-[10px] uppercase font-bold tracking-widest mt-0.5">Executive Dashboard</p>
                    </div>
                </div>
                <a href="../api/?action=logout" class="w-10 h-10 bg-white/10 hover:bg-white/20 active:scale-95 rounded-full flex justify-center items-center text-white transition-all backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
            
            <div class="mt-7">
                <h2 class="text-indigo-100 text-sm font-medium mb-0.5"><?= $greeting ?>,</h2>
                <p class="text-white text-2xl font-black truncate"><?= $userName ?> 👋</p>
            </div>
        </div>
    </div>

    <?php if($isSuperAdmin): ?>
    <div class="px-5 mt-5 max-w-md mx-auto relative z-10 flex gap-3">
        <a href="?page=mobile_pos" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm active:scale-95 transition-transform border border-indigo-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span class="text-xs tracking-wide">Order</span>
        </a>
        <a href="?page=mobile_expenses" class="flex-1 bg-orange-500 text-white font-bold py-3 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm active:scale-95 transition-transform border border-orange-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span class="text-xs tracking-wide">Pengeluaran</span>
        </a>
    </div>
    <?php endif; ?>

    <div class="p-5 max-w-md mx-auto relative z-10 space-y-4 <?= !$isSuperAdmin ? 'mt-1' : '' ?>">
        <div class="flex justify-between items-end mb-2 px-1">
            <h2 class="font-bold text-slate-800 text-sm">Ringkasan Hari Ini</h2>
            <p class="text-xs text-indigo-600 font-bold" id="todayDate">Hari ini</p>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Penjualan</p>
                <h3 class="font-black text-lg text-emerald-600" id="statPenjualan">...</h3>
                <p class="text-xs font-bold text-slate-500 mt-1"><span id="statTrxCount">0</span> Transaksi</p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pesanan Aktif</p>
                <h3 class="font-black text-lg text-indigo-600" id="statPesanan">...</h3>
                <p class="text-xs font-bold text-slate-500 mt-1"><span id="statOrderCount">0</span> Antrean</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Pemasukan Kasir</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-sm font-bold text-slate-600">Tunai (Cash)</span>
                    </div>
                    <span class="font-black text-slate-800" id="statCash">...</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-sm font-bold text-slate-600">QRIS</span>
                    </div>
                    <span class="font-black text-slate-800" id="statQris">...</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        <span class="text-sm font-bold text-slate-600">Transfer Bank</span>
                    </div>
                    <span class="font-black text-slate-800" id="statTransfer">...</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pengeluaran (Expenses)</p>
                <h3 class="font-black text-lg text-red-500" id="statExpenses">...</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-50 flex justify-center items-center text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>
    </div>
    
    <?php include 'components/bottomnav.php'; ?>
    
    <script>
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        
        // Set Tanggal Hari Ini
        document.getElementById('todayDate').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' });

        async function loadDashboardStats() {
            try {
                const todayStr = new Date(new Date().toLocaleString("en-US", {timeZone: "Asia/Jakarta"})).toISOString().split('T')[0];

                const [resTrx, resOrd, resExp] = await Promise.all([
                    fetch('../api/?action=get_transactions').catch(() => ({json: () => ({status: 'error'})})),
                    fetch('../api/?action=get_orders').catch(() => ({json: () => ({status: 'error'})})),
                    fetch('../api/?action=get_expenses').catch(() => ({json: () => ({status: 'error'})}))
                ]);

                const dataTrx = await resTrx.json();
                const dataOrd = await resOrd.json();
                const dataExp = await resExp.json();

                let totalCash = 0, totalQris = 0, totalTransfer = 0, totalSales = 0, trxCount = 0;
                if(dataTrx.status === 'success') {
                    const todayTrx = dataTrx.data.filter(t => t.created_at && t.created_at.startsWith(todayStr));
                    trxCount = todayTrx.length;
                    todayTrx.forEach(t => {
                        const amt = parseFloat(t.total_amount || 0);
                        totalSales += amt;
                        if(t.payment_method === 'CASH') totalCash += amt;
                        else if(t.payment_method === 'QRIS') totalQris += amt;
                        else if(t.payment_method === 'TRANSFER') totalTransfer += amt;
                    });
                }

                let totalOrderAmount = 0, orderCount = 0;
                if(dataOrd.status === 'success') {
                    const todayOrd = dataOrd.data.filter(o => o.created_at && o.created_at.startsWith(todayStr));
                    orderCount = todayOrd.length;
                    todayOrd.forEach(o => { totalOrderAmount += parseFloat(o.total_amount || 0); });
                }

                let totalExpenses = 0;
                if(dataExp.status === 'success') {
                    const todayExp = dataExp.data.filter(e => {
                        const dateField = e.expense_date || e.created_at || '';
                        return dateField.startsWith(todayStr);
                    });
                    todayExp.forEach(e => { totalExpenses += parseFloat(e.amount || 0); });
                }

                document.getElementById('statPenjualan').innerText = formatRupiah(totalSales);
                document.getElementById('statTrxCount').innerText = trxCount;
                
                document.getElementById('statCash').innerText = formatRupiah(totalCash);
                document.getElementById('statQris').innerText = formatRupiah(totalQris);
                document.getElementById('statTransfer').innerText = formatRupiah(totalTransfer);
                
                document.getElementById('statPesanan').innerText = formatRupiah(totalOrderAmount);
                document.getElementById('statOrderCount').innerText = orderCount;
                
                document.getElementById('statExpenses').innerText = formatRupiah(totalExpenses);

            } catch (error) {
                console.error('Gagal memuat statistik:', error);
                ['statPenjualan','statCash','statQris','statTransfer','statPesanan','statExpenses'].forEach(id => document.getElementById(id).innerText = 'Rp 0');
            }
        }

        document.addEventListener('DOMContentLoaded', loadDashboardStats);
    </script>
</body>
</html>