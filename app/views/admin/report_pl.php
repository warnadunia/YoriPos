<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 no-print">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Laporan Laba Rugi (P&L)</h2>
                <p class="text-sm text-slate-500">Standar PSAK - Realtime FIFO Method</p>
            </div>
            <div class="flex items-center gap-2">
                <input type="date" id="filterStart" class="px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm dark:text-white outline-none focus:border-indigo-500">
                <span class="text-slate-400 font-bold">-</span>
                <input type="date" id="filterEnd" class="px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm dark:text-white outline-none focus:border-indigo-500">
                <button onclick="loadReport()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-lg transition-colors shadow-sm ml-1">Tarik Data</button>
                <button onclick="bukaHalamanCetak()" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-4 py-2 rounded-lg transition-colors ml-1">🖨️ Cetak Dokumen</button>
            </div>
        </div>

        <div id="printArea" class="bg-white dark:bg-slate-800 p-8 md:p-12 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden">
            
            <div class="text-center mb-10 border-b-2 border-slate-800 dark:border-slate-200 pb-6">
                <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest" id="reportStoreName">NAMA TOKO</h1>
                <h2 class="text-lg font-bold text-slate-600 dark:text-slate-400 mt-1">LAPORAN LABA RUGI KOMPREHENSIF</h2>
                <p class="text-sm text-slate-500 mt-1" id="reportPeriod">Periode: -</p>
            </div>

            <div class="space-y-6 text-slate-800 dark:text-slate-200 font-medium">
                
                <div>
                    <h3 class="font-black text-lg mb-2 uppercase text-indigo-600 dark:text-indigo-400">1. Pendapatan</h3>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700/50 pl-4">
                        <span>Penjualan Bersih (Omzet)</span>
                        <span id="plRevenue">Rp 0</span>
                    </div>
                </div>

                <div>
                    <h3 class="font-black text-lg mb-2 uppercase text-indigo-600 dark:text-indigo-400">2. Harga Pokok Penjualan</h3>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700/50 pl-4">
                        <span>Harga Pokok Penjualan (HPP FIFO)</span>
                        <span id="plCogs" class="text-red-500">(Rp 0)</span>
                    </div>
                    <div class="flex justify-between items-center py-3 mt-2 bg-slate-50 dark:bg-slate-900/50 px-4 rounded-lg border border-slate-200 dark:border-slate-700 font-bold">
                        <span>LABA KOTOR (Gross Profit)</span>
                        <span id="plGrossProfit" class="text-emerald-600 dark:text-emerald-400">Rp 0</span>
                    </div>
                </div>

                <div>
                    <h3 class="font-black text-lg mb-2 uppercase text-indigo-600 dark:text-indigo-400">3. Beban Operasional</h3>
                    <div id="plExpensesContainer" class="space-y-1">
                        </div>
                    <div class="flex justify-between items-center py-2 mt-2 border-t border-slate-300 dark:border-slate-600 px-4 font-bold">
                        <span>Total Beban Operasional</span>
                        <span id="plTotalOpex" class="text-red-500">(Rp 0)</span>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flex justify-between items-center py-4 bg-indigo-600 text-white px-6 rounded-xl font-black text-xl shadow-lg">
                        <span>LABA BERSIH OPERASIONAL (Net Profit)</span>
                        <span id="plNetProfit">Rp 0</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);

    function initDates() {
        const date = new Date();
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).toISOString().split('T')[0];
        const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).toISOString().split('T')[0];
        document.getElementById('filterStart').value = firstDay;
        document.getElementById('filterEnd').value = lastDay;
    }

    async function loadReport() {
        const start = document.getElementById('filterStart').value;
        const end = document.getElementById('filterEnd').value;
        document.getElementById('reportPeriod').innerText = `Periode: ${start} s/d ${end}`;
        
        try {
            // Ambil Nama Toko
            const setRes = await fetch('/yoripos/api/?action=get_settings');
            const setDat = await setRes.json();
            if(setDat.status === 'success' && setDat.data.store_name) {
                document.getElementById('reportStoreName').innerText = setDat.data.store_name;
            }

            // Ambil Data Laba Rugi
            const res = await fetch(`/yoripos/api/?action=get_profit_loss&start=${start}&end=${end}`);
            const result = await res.json();
            
            if (result.status === 'success') {
                const data = result.data;
                
                document.getElementById('plRevenue').innerText = formatRp(data.revenue);
                document.getElementById('plCogs').innerText = `(${formatRp(data.cogs)})`;
                document.getElementById('plGrossProfit').innerText = formatRp(data.gross_profit);
                
                const expContainer = document.getElementById('plExpensesContainer');
                expContainer.innerHTML = '';
                
                if(data.expenses_detail.length === 0) {
                    expContainer.innerHTML = `<div class="py-2 pl-4 text-slate-500 italic">Tidak ada catatan beban operasional.</div>`;
                } else {
                    data.expenses_detail.forEach(e => {
                        expContainer.innerHTML += `
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-700/50 pl-4 text-sm">
                            <span>Beban ${e.category}</span>
                            <span>(${formatRp(e.total)})</span>
                        </div>`;
                    });
                }
                
                document.getElementById('plTotalOpex').innerText = `(${formatRp(data.total_opex)})`;
                document.getElementById('plNetProfit').innerText = formatRp(data.net_profit);
            }
        } catch (error) {
            console.error('Gagal mengambil laporan', error);
        }
    }

    function bukaHalamanCetak() {
        const start = document.getElementById('filterStart').value;
        const end = document.getElementById('filterEnd').value;
        // Buka halaman di tab baru dengan lemparan parameter tanggal
        window.open(`<?= APP_URL ?>/admin/?page=print_pl&start=${start}&end=${end}`, '_blank');
    }

    document.addEventListener('DOMContentLoaded', () => {
        initDates();
        loadReport();
    });
</script>