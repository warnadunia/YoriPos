<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Dashboard Overview</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ringkasan performa penjualan, piutang, dan stok.</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Tanggal</p>
                <p id="currentDate" class="text-sm font-medium text-indigo-600 dark:text-indigo-400"></p>
            </div>
        </div>

        <!-- BARIS 1: KARTU METRIK UTAMA -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Omzet Hari Ini -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-500 transform translate-x-[-4px] translate-y-[4px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Omzet Hari Ini</p>
                <h3 id="statTodayRevenue" class="text-2xl font-black text-slate-800 dark:text-slate-100">Rp 0</h3>
            </div>

            <!-- Profit Hari Ini -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-500 transform translate-x-[-4px] translate-y-[4px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Laba Kotor Hari Ini</p>
                <h3 id="statTodayProfit" class="text-2xl font-black text-emerald-600 dark:text-emerald-400">Rp 0</h3>
            </div>

            <!-- Omzet Bulan Ini -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-indigo-500 transform translate-x-[-4px] translate-y-[4px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Omzet Bulan Ini</p>
                <h3 id="statMonthRevenue" class="text-2xl font-black text-slate-800 dark:text-slate-100">Rp 0</h3>
            </div>

            <!-- Profit Bulan Ini -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-purple-50 dark:bg-purple-900/30 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-500 transform translate-x-[-4px] translate-y-[4px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1">Laba Kotor Bulan Ini</p>
                <h3 id="statMonthProfit" class="text-2xl font-black text-purple-600 dark:text-purple-400">Rp 0</h3>
            </div>
        </div>

        <!-- BARIS 2: STATUS PIUTANG & PESANAN AKTIF -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Piutang Alert -->
            <div class="bg-amber-50 dark:bg-amber-900/20 p-6 rounded-2xl border border-amber-200 dark:border-amber-800/50 shadow-sm flex justify-between items-center transition-all hover:shadow-md">
                <div>
                    <p class="text-sm font-bold text-amber-700 dark:text-amber-400 mb-1">Total Piutang Berjalan</p>
                    <h3 id="statPiutangTotal" class="text-2xl font-black text-amber-800 dark:text-amber-300">Rp 0</h3>
                    <p id="statPiutangCount" class="text-xs font-bold text-amber-600 dark:text-amber-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>0 Invoice Belum Lunas</span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-amber-200/50 dark:bg-amber-800/50 rounded-full flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>

            <!-- Pesanan Aktif Alert -->
            <div class="bg-sky-50 dark:bg-sky-900/20 p-6 rounded-2xl border border-sky-200 dark:border-sky-800/50 shadow-sm flex justify-between items-center transition-all hover:shadow-md">
                <div>
                    <p class="text-sm font-bold text-sky-700 dark:text-sky-400 mb-1">Pesanan Aktif (Proses)</p>
                    <h3 id="statOrderTotal" class="text-2xl font-black text-sky-800 dark:text-sky-300">Rp 0</h3>
                    <p id="statOrderCount" class="text-xs font-bold text-sky-600 dark:text-sky-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>0 Antrean Pesanan</span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-sky-200/50 dark:bg-sky-800/50 rounded-full flex items-center justify-center text-sky-600 dark:text-sky-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        <!-- BARIS 3: KOLOM RINCIAN LIST -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Saldo per Metode -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100 mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">Saldo Pembayaran (Bulan Ini)</h3>
                <div id="methodsContainer" class="space-y-3">
                    <p class="text-sm text-slate-400 text-center py-4">Memuat data...</p>
                </div>
            </div>

            <!-- Stok Hampir Habis -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100 mb-4 border-b border-slate-100 dark:border-slate-700 pb-2 flex justify-between items-center">
                    Peringatan Stok Tipis
                    <span class="bg-red-100 text-red-600 text-[10px] px-2 py-0.5 rounded-full">&le; 10 Qty</span>
                </h3>
                <div id="lowStockContainer" class="space-y-3">
                    <p class="text-sm text-slate-400 text-center py-4">Memuat data...</p>
                </div>
            </div>

            <!-- Top 5 Produk -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100 mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">Top 5 Produk Terlaris</h3>
                <div id="topProductsContainer" class="space-y-3">
                    <p class="text-sm text-slate-400 text-center py-4">Memuat data...</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);

    document.getElementById('currentDate').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    async function loadDashboardStats() {
        try {
            const res = await fetch('../api/?action=get_dashboard_stats');
            const result = await res.json();
            
            if (result.status === 'success') {
                const data = result.data;
                
                // 1. Render Kartu Metrik Utama
                document.getElementById('statTodayRevenue').innerText = formatRp(data.today.revenue);
                document.getElementById('statTodayProfit').innerText = formatRp(data.today.profit);
                document.getElementById('statMonthRevenue').innerText = formatRp(data.month.revenue);
                document.getElementById('statMonthProfit').innerText = formatRp(data.month.profit);

                // 2. Render Piutang & Order
                document.getElementById('statPiutangTotal').innerText = formatRp(data.piutang.total);
                document.getElementById('statPiutangCount').innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span>${data.piutang.count} Invoice Belum Lunas</span>`;
                
                document.getElementById('statOrderTotal').innerText = formatRp(data.orders.total);
                document.getElementById('statOrderCount').innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> <span>${data.orders.count} Antrean Pesanan</span>`;

                // 3. Render Top Produk
                const topContainer = document.getElementById('topProductsContainer');
                topContainer.innerHTML = '';
                if (data.top_products.length === 0) {
                    topContainer.innerHTML = '<p class="text-sm text-slate-500 italic text-center">Belum ada penjualan bulan ini.</p>';
                } else {
                    data.top_products.forEach((prod, idx) => {
                        let rankColor = idx === 0 ? 'bg-amber-100 text-amber-600' : (idx === 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-800');
                        if(idx > 2) rankColor = 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400';
                        topContainer.innerHTML += `
                            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full ${rankColor} flex items-center justify-center font-black text-xs">#${idx + 1}</div>
                                    <p class="font-bold text-sm text-slate-700 dark:text-slate-200 line-clamp-1">${prod.name}</p>
                                </div>
                                <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded whitespace-nowrap">${prod.total_qty} x</span>
                            </div>
                        `;
                    });
                }

                // 4. Render Peringatan Stok
                const stockContainer = document.getElementById('lowStockContainer');
                stockContainer.innerHTML = '';
                if (data.low_stock.length === 0) {
                    stockContainer.innerHTML = '<p class="text-sm text-emerald-500 font-bold text-center">Semua stok produk aman!</p>';
                } else {
                    data.low_stock.forEach(stock => {
                        stockContainer.innerHTML += `
                            <div class="flex items-center justify-between p-2 rounded-lg bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <p class="font-bold text-sm text-slate-700 dark:text-slate-200 line-clamp-1">${stock.name}</p>
                                </div>
                                <span class="text-xs font-black text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/50 px-2 py-1 rounded">Sisa: ${stock.total_stock}</span>
                            </div>
                        `;
                    });
                }

                // 5. Render Saldo per Metode Pembayaran
                const methodContainer = document.getElementById('methodsContainer');
                methodContainer.innerHTML = '';
                if (data.methods.length === 0) {
                    methodContainer.innerHTML = '<p class="text-sm text-slate-500 italic text-center">Belum ada pendapatan lunas.</p>';
                } else {
                    data.methods.forEach(m => {
                        methodContainer.innerHTML += `
                            <div class="flex items-center justify-between p-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                                <p class="font-bold text-sm text-slate-700 dark:text-slate-200 uppercase">${m.payment_method}</p>
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">${formatRp(m.total)}</span>
                            </div>
                        `;
                    });
                }
            }
        } catch (error) {
            console.error('Gagal memuat statistik:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadDashboardStats);
</script>