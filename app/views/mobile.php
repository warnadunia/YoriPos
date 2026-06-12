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
<body class="bg-[#e4f4ed] text-slate-800 antialiased selection:bg-teal-100 selection:text-teal-900 pb-32 min-h-screen font-sans"> 
    
    <div class="px-5 pt-7 pb-4 flex justify-between items-start">
        <div>
            <h1 class="text-[22px] font-bold text-[#0ba37f] tracking-tight">Executive Dashboard</h1>
            <h2 class="text-[17px] font-medium text-[#5a8b79] mt-1">Halo, <?= $userName ?></h2>
            <span class="inline-block px-2.5 py-0.5 mt-1.5 bg-[#86c576] text-white text-[9px] font-bold rounded shadow-sm uppercase tracking-wider"><?= $roleBadge ?></span>
        </div>
        <a href="../api/?action=logout" class="w-10 h-10 bg-[#c5e6d8] hover:bg-[#aed8c6] active:scale-95 rounded-full flex justify-center items-center text-[#295c5a] transition-all shadow-sm">
            <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H8.25" /></svg>
        </a>
    </div>

    <div class="px-4">
        <div class="bg-[#0ba37f] rounded-[1.25rem] p-5 pb-10 relative text-white shadow-lg shadow-[#0ba37f]/20">
            <div class="flex items-center gap-2 mb-5">
                <svg class="w-4 h-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span id="todayDate" class="text-xs font-medium opacity-90">Memuat tanggal...</span>
            </div>

            <div class="flex justify-between gap-3">
                <div class="w-[60%] flex flex-col justify-center">
                    <p class="text-[11px] font-bold mb-1 opacity-90">Penjualan</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-bold">Rp</span>
                        <span class="text-[28px] font-bold tracking-tight leading-none" id="statPenjualan">0</span>
                    </div>
                    <p class="text-[10px] mt-1.5 opacity-90"><span id="statTrxCount">0</span> Transaksi</p>
                </div>
                <div class="w-[40%] bg-[#0ebf96] rounded-xl p-3 flex flex-col justify-center items-center text-center">
                    <p class="text-[11px] font-bold mb-0.5">Order</p>
                    <span class="font-bold text-[26px] leading-none my-0.5" id="statOrderCount">0</span>
                    <p class="text-[8px] opacity-90 mt-1 leading-tight">
                        Antrian<br>
                        Nilai Rp <span id="statPesanan">0</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php if($isSuperAdmin): ?>
    <div class="px-8 -mt-5 relative z-10 flex gap-3">
        <a href="?page=mobile_pos" class="flex-1 bg-[#86c576] shadow-md rounded-xl py-3 flex items-center justify-center gap-2 text-white font-bold text-base active:scale-95 transition-transform">
            <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#86c576]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            </div>
            Order
        </a>
        <a href="?page=mobile_expenses" class="flex-1 bg-[#dda02a] shadow-md rounded-xl py-3 flex items-center justify-center gap-2 text-white font-bold text-base active:scale-95 transition-transform">
            <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#c53030]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            </div>
            Biaya
        </a>
    </div>
    <?php endif; ?>

    <div class="px-4 mt-5 space-y-3">
        <div class="bg-[#f4faf6] border border-[#d1e6db] rounded-xl p-4 shadow-sm">
            <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wide mb-3">REKAP PEMASUKAN KASIR</p>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80]"></span>
                        <span class="text-[13px] font-bold text-[#3e524a]">Tunai (Cash)</span>
                    </div>
                    <span class="font-bold text-[#3e524a] text-[13px]" id="statCash">Rp 0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#8b9de8]"></span>
                        <span class="text-[13px] font-bold text-[#3e524a]">QRIS</span>
                    </div>
                    <span class="font-bold text-[#3e524a] text-[13px]" id="statQris">Rp 0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#e09191]"></span>
                        <span class="text-[13px] font-bold text-[#3e524a]">Transfer Bank</span>
                    </div>
                    <span class="font-bold text-[#3e524a] text-[13px]" id="statTransfer">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="bg-[#f4faf6] border border-[#d1e6db] rounded-xl p-4 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[11px] text-slate-500 mb-1">Pengeluaran Hari Ini</p>
                <h3 class="font-bold text-base text-[#c53030]" id="statExpenses">Rp 0</h3>
            </div>
            <div class="text-[#c53030]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181"></path></svg>
            </div>
        </div>
    </div>

    <div class="mt-7 mb-3 px-5 flex justify-between items-center">
        <div class="flex items-center gap-2 text-[#0ba37f]">
            <div class="w-7 h-7 rounded-full bg-[#bce3d4] flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="font-bold text-[15px]">Ringkasan Bulan ini</h3>
        </div>
        <a href="#" class="text-[10px] text-[#c53030] hover:underline">lebih lengkap >></a>
    </div>

    <div class="px-4 space-y-3">
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-[#f4faf6] border border-[#d1e6db] rounded-xl p-3.5 shadow-sm">
                <p class="text-[11px] text-slate-500 mb-1">Omzet Bulan Ini</p>
                <h3 class="font-bold text-[15px] text-[#b04627]" id="statOmzetBulan">Rp 0</h3>
                <p class="text-[9px] text-slate-400 mt-1"><span id="statTrxBulanCount">0</span> - Transaksi Berhasil</p>
            </div>
            <div class="bg-[#f4faf6] border border-[#d1e6db] rounded-xl p-3.5 shadow-sm">
                <p class="text-[11px] text-slate-500 mb-1">Pengeluaran Bulan Ini</p>
                <h3 class="font-bold text-[15px] text-[#b04627]" id="statExpBulan">Rp 0</h3>
                <p class="text-[9px] text-slate-400 mt-1"><span id="statExpBulanCount">0</span> - Pengeluaran Biaya</p>
            </div>
        </div>

        <div class="bg-[#feeadd] border border-[#f5c6aa] rounded-xl p-4 shadow-sm">
            <p class="text-[11px] text-slate-500 mb-1">Total Piutang Berjalan Bulan Ini</p>
            <h3 class="font-bold text-lg text-[#b04627]" id="statPiutangBulan">Rp 0</h3>
            <p class="text-[9px] text-slate-400 mt-1"><span id="statPiutangCount">0</span> - Invoices Belum Lunas</p>
        </div>

        <div class="bg-[#f4faf6] border border-[#d1e6db] rounded-xl p-4 shadow-sm">
            <p class="text-[11px] text-slate-500 mb-3">Rekap Pembayaran Bulan ini</p>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80]"></span>
                        <span class="text-[13px] font-bold text-[#3e524a]">Tunai (Cash)</span>
                    </div>
                    <span class="font-bold text-[#3e524a] text-[13px]" id="statCashBulan">Rp 0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#8b9de8]"></span>
                        <span class="text-[13px] font-bold text-[#3e524a]">QRIS</span>
                    </div>
                    <span class="font-bold text-[#3e524a] text-[13px]" id="statQrisBulan">Rp 0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#e09191]"></span>
                        <span class="text-[13px] font-bold text-[#3e524a]">Transfer Bank</span>
                    </div>
                    <span class="font-bold text-[#3e524a] text-[13px]" id="statTransferBulan">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="bg-[#f4faf6] border border-[#d1e6db] rounded-xl p-4 shadow-sm mb-4">
            <p class="text-[11px] text-slate-500 mb-1">Laba Kotor Bulan Ini</p>
            <h3 class="font-bold text-lg text-[#b04627]" id="statLabaBulan">Rp 0</h3>
        </div>
    </div>
    
    <?php include __DIR__ . '/components/bottomnav.php'; ?>
    
    <script>
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        const formatNominalOnly = (number) => new Intl.NumberFormat('id-ID').format(number);
        
        const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('todayDate').innerText = new Date().toLocaleDateString('id-ID', dateOptions);

        async function loadDashboardStats() {
            try {
                const now = new Date(new Date().toLocaleString("en-US", {timeZone: "Asia/Jakarta"}));
                const todayStr = now.toISOString().split('T')[0]; // YYYY-MM-DD
                const monthStr = todayStr.substring(0, 7); // YYYY-MM

                const [resTrx, resOrd, resExp, resPiu] = await Promise.all([
                    fetch('../api/?action=get_transactions').catch(() => ({json: () => ({status: 'error'})})),
                    fetch('../api/?action=get_orders').catch(() => ({json: () => ({status: 'error'})})),
                    fetch('../api/?action=get_expenses').catch(() => ({json: () => ({status: 'error'})})),
                    fetch('../api/?action=get_receivables').catch(() => ({json: () => ({status: 'error'})}))
                ]);

                const dataTrx = await resTrx.json();
                const dataOrd = await resOrd.json();
                const dataExp = await resExp.json();
                const dataPiu = await resPiu.json();

                // VARIABEL HARIAN
                let totalCash = 0, totalQris = 0, totalTransfer = 0, totalSales = 0, trxCount = 0;
                let totalOrderAmount = 0, orderCount = 0;
                let totalExpenses = 0;

                // VARIABEL BULANAN
                let totalOmzetBulan = 0, trxBulanCount = 0;
                let totalCashBulan = 0, totalQrisBulan = 0, totalTransferBulan = 0;
                let totalExpBulan = 0, expBulanCount = 0;
                let totalPiutangBulan = 0, piutangCount = 0;

                // Hitung Transaksi (Harian & Bulanan)
                if(dataTrx.status === 'success') {
                    dataTrx.data.forEach(t => {
                        if(!t.created_at) return;
                        const amt = parseFloat(t.total_amount || 0);
                        
                        // Cek Bulanan
                        if(t.created_at.startsWith(monthStr)) {
                            trxBulanCount++;
                            totalOmzetBulan += amt;
                            if(t.payment_method === 'CASH') totalCashBulan += amt;
                            else if(t.payment_method === 'QRIS') totalQrisBulan += amt;
                            else if(t.payment_method === 'TRANSFER') totalTransferBulan += amt;
                        }
                        
                        // Cek Harian
                        if(t.created_at.startsWith(todayStr)) {
                            trxCount++;
                            totalSales += amt;
                            if(t.payment_method === 'CASH') totalCash += amt;
                            else if(t.payment_method === 'QRIS') totalQris += amt;
                            else if(t.payment_method === 'TRANSFER') totalTransfer += amt;
                        }
                    });
                }

                // Hitung Order Aktif (Hanya Harian)
                if(dataOrd.status === 'success') {
                    const todayOrd = dataOrd.data.filter(o => o.created_at && o.created_at.startsWith(todayStr) && o.status !== 'SELESAI');
                    orderCount = todayOrd.length;
                    todayOrd.forEach(o => { totalOrderAmount += parseFloat(o.total_amount || 0); });
                }

                // Hitung Pengeluaran (Harian & Bulanan)
                if(dataExp.status === 'success') {
                    dataExp.data.forEach(e => {
                        const dateField = e.expense_date || e.created_at || '';
                        if(!dateField) return;
                        const amt = parseFloat(e.amount || 0);

                        if(dateField.startsWith(monthStr)) {
                            expBulanCount++;
                            totalExpBulan += amt;
                        }
                        if(dateField.startsWith(todayStr)) {
                            totalExpenses += amt;
                        }
                    });
                }

                // Hitung Piutang (Bulanan)
                if(dataPiu.status === 'success') {
                    const bulanPiu = dataPiu.data.filter(p => p.created_at && p.created_at.startsWith(monthStr) && p.status !== 'LUNAS');
                    piutangCount = bulanPiu.length;
                    bulanPiu.forEach(p => { totalPiutangBulan += parseFloat(p.remaining_amount || p.total_amount || 0); });
                }

                // Hitung Laba Kotor Bulanan Sederhana
                let labaKotorBulan = totalOmzetBulan - totalExpBulan;

                // -- INJECT DOM HARIAN --
                document.getElementById('statPenjualan').innerText = formatNominalOnly(totalSales);
                document.getElementById('statTrxCount').innerText = trxCount;
                document.getElementById('statPesanan').innerText = formatNominalOnly(totalOrderAmount);
                document.getElementById('statOrderCount').innerText = orderCount;
                document.getElementById('statCash').innerText = formatRupiah(totalCash);
                document.getElementById('statQris').innerText = formatRupiah(totalQris);
                document.getElementById('statTransfer').innerText = formatRupiah(totalTransfer);
                document.getElementById('statExpenses').innerText = formatRupiah(totalExpenses);

                // -- INJECT DOM BULANAN --
                document.getElementById('statOmzetBulan').innerText = formatRupiah(totalOmzetBulan);
                document.getElementById('statTrxBulanCount').innerText = trxBulanCount;
                document.getElementById('statExpBulan').innerText = formatRupiah(totalExpBulan);
                document.getElementById('statExpBulanCount').innerText = expBulanCount;
                
                document.getElementById('statPiutangBulan').innerText = formatRupiah(totalPiutangBulan);
                document.getElementById('statPiutangCount').innerText = piutangCount;

                document.getElementById('statCashBulan').innerText = formatRupiah(totalCashBulan);
                document.getElementById('statQrisBulan').innerText = formatRupiah(totalQrisBulan);
                document.getElementById('statTransferBulan').innerText = formatRupiah(totalTransferBulan);

                document.getElementById('statLabaBulan').innerText = formatRupiah(labaKotorBulan);

            } catch (error) {
                console.error('Gagal memuat statistik:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', loadDashboardStats);
    </script>
</body>
</html>