<?php
// Cek hak akses untuk tombol VIP
$perms = $_SESSION['permissions'] ?? [];
$isSuperAdmin = in_array('settings', $perms); // Cuma Super Admin yang punya menu settings

// Tarik Pengaturan Toko
$storeName = $appSettings['store_name'] ?? 'Yori App';
$userName = $_SESSION['name'] ?? $_SESSION['username'] ?? 'Johanna Doe';
if (trim($userName) == '') $userName = 'Bosku';

// Badge Otomatis
$roleBadge = $isSuperAdmin ? 'Owner' : 'Kasir / Kurir';
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
<body class="bg-[#e4f4ed] text-slate-800 antialiased selection:bg-teal-100 selection:text-teal-900 pb-28 min-h-screen"> 
    
    <div class="px-6 pt-8 pb-4">
        <p class="text-[#0ba37f] font-medium text-lg tracking-wide"><?= $storeName ?></p>
        
        <div class="flex justify-between items-start mt-5">
            <div>
                <h1 class="text-[22px] font-medium text-[#295c5a]">Halo, <?= $userName ?></h1>
                <span class="inline-block px-2.5 py-0.5 mt-1 bg-[#86c576] text-white text-[10px] font-bold rounded shadow-sm tracking-wide uppercase"><?= $roleBadge ?></span>
            </div>
            <a href="../api/?action=logout" class="w-11 h-11 bg-[#c5e6d8] hover:bg-[#aed8c6] active:scale-95 rounded-full flex justify-center items-center text-[#295c5a] transition-all shadow-sm">
                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </a>
        </div>
        
        <h2 class="text-[22px] font-bold text-[#0ba37f] mt-8 mb-4">Executive Dashboard</h2>
    </div>

    <div class="px-5">
        <div class="bg-[#0ba37f] rounded-[2rem] p-5 pb-12 relative text-white shadow-xl shadow-[#0ba37f]/20">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2.5 bg-white/10 px-3 py-1.5 rounded-full backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span id="todayDate" class="text-xs font-medium tracking-wide">Memuat tanggal...</span>
                </div>
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>

            <div class="flex justify-between gap-3">
                <div class="w-[55%] flex flex-col justify-center">
                    <p class="text-[11px] font-bold tracking-widest mb-1 text-white/90">PENJUALAN</p>
                    <div class="flex items-start gap-1">
                        <span class="text-sm font-bold mt-1">Rp</span>
                        <span class="text-4xl font-black tracking-tight leading-none" id="statPenjualan">0</span>
                    </div>
                    <p class="text-[11px] mt-2 font-medium text-white/80"><span id="statTrxCount">0</span> Transaksi</p>
                </div>
                <div class="w-[45%] bg-[#04ce9f] rounded-2xl p-3.5 shadow-inner flex flex-col justify-center">
                    <p class="text-[10px] font-bold tracking-widest mb-1 text-white/90">PESANAN AKTIF</p>
                    <div class="flex items-baseline gap-1">
                        <span class="font-black text-2xl leading-none" id="statOrderCount">0</span>
                        <span class="text-[10px] font-medium">Antrian</span>
                    </div>
                    <p class="text-[9px] mt-2 font-medium text-white/90">Nilai Rp <span id="statPesanan">0</span></p>
                </div>
            </div>
        </div>
    </div>

    <?php if($isSuperAdmin): ?>
    <div class="px-10 -mt-6 relative z-10 flex gap-4">
        <a href="?page=mobile_pos" class="flex-1 bg-[#7bc27b] shadow-lg shadow-[#7bc27b]/40 rounded-2xl py-3.5 flex items-center justify-center gap-2.5 text-white font-bold text-lg transition-transform active:scale-95 border border-white/20">
            <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#7bc27b]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            </div>
            Order
        </a>
        <a href="?page=mobile_expenses" class="flex-1 bg-[#dda02a] shadow-lg shadow-[#dda02a]/40 rounded-2xl py-3.5 flex items-center justify-center gap-2.5 text-white font-bold text-lg transition-transform active:scale-95 border border-white/20">
            <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#dda02a]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            </div>
            Biaya
        </a>
    </div>
    <?php endif; ?>

    <div class="px-5 mt-6 space-y-4">
        
        <div class="bg-[#eff7f2] border border-white/60 shadow-[0_4px_15px_rgba(0,0,0,0.02)] rounded-2xl p-5">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Rekap Pemasukan Kasir</p>
            <div class="space-y-3.5">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-[#4ade80]"></span>
                        <span class="text-sm font-bold text-[#295c5a]">Tunai (Cash)</span>
                    </div>
                    <span class="font-black text-[#295c5a] text-[15px]" id="statCash">Rp 0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-[#60a5fa]"></span>
                        <span class="text-sm font-bold text-[#295c5a]">QRIS</span>
                    </div>
                    <span class="font-black text-[#295c5a] text-[15px]" id="statQris">Rp 0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-[#f87171]"></span>
                        <span class="text-sm font-bold text-[#295c5a]">Transfer Bank</span>
                    </div>
                    <span class="font-black text-[#295c5a] text-[15px]" id="statTransfer">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="bg-[#eff7f2] border border-white/60 shadow-[0_4px_15px_rgba(0,0,0,0.02)] rounded-2xl p-5 flex justify-between items-end">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pengeluaran (Biaya)</p>
                <h3 class="font-black text-xl text-[#c53030]" id="statExpenses">Rp 0</h3>
            </div>
            <div class="text-[#c53030] mb-1">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>
        
    </div>
    
    <?php include __DIR__ . '/components/bottomnav.php'; ?>
    
    <script>
        // 2 Format Angka berbeda sesuai desain UI
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        const formatNominalOnly = (number) => new Intl.NumberFormat('id-ID').format(number); // Tanpa "Rp" di depannya
        
        // Format Tanggal ala Indonesia yang cantik (Contoh: Selasa, 9 Juni 2026)
        const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('todayDate').innerText = new Date().toLocaleDateString('id-ID', dateOptions);

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

                // Injeksi Data ke DOM dengan Format Masing-masing
                document.getElementById('statPenjualan').innerText = formatNominalOnly(totalSales);
                document.getElementById('statTrxCount').innerText = trxCount;
                
                document.getElementById('statPesanan').innerText = formatNominalOnly(totalOrderAmount);
                document.getElementById('statOrderCount').innerText = orderCount;

                document.getElementById('statCash').innerText = formatRupiah(totalCash);
                document.getElementById('statQris').innerText = formatRupiah(totalQris);
                document.getElementById('statTransfer').innerText = formatRupiah(totalTransfer);
                document.getElementById('statExpenses').innerText = formatRupiah(totalExpenses);

            } catch (error) {
                console.error('Gagal memuat statistik:', error);
                document.getElementById('statPenjualan').innerText = '0';
                document.getElementById('statPesanan').innerText = '0';
                ['statCash','statQris','statTransfer','statExpenses'].forEach(id => document.getElementById(id).innerText = 'Rp 0');
            }
        }

        document.addEventListener('DOMContentLoaded', loadDashboardStats);
    </script>
</body>
</html>