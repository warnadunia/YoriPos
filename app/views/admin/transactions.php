<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Riwayat Transaksi</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Pantau transaksi lunas harian dan cetak ulang struk penjualan.</p>
                </div>
                <div class="flex gap-2">
                    <select id="filterMonth" onchange="generateDays()" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 outline-none font-bold text-indigo-600 text-sm">
                        <?php 
                        $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                        foreach($months as $k => $v): $m = $k+1; 
                        ?>
                            <option value="<?= $m ?>" <?= date('n') == $m ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filterYear" onchange="generateDays()" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 outline-none font-bold text-indigo-600 text-sm">
                        <?php $cy = date('Y'); for($i=$cy-1; $i<=$cy+1; $i++): ?>
                            <option value="<?= $i ?>" <?= $cy == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div id="daysContainer" class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar scroll-smooth"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase">Total Penjualan</p>
                <p class="text-2xl font-black text-indigo-600 mt-1" id="sumCount">0 <span class="text-sm font-medium text-slate-400">Nota</span></p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase">Pendapatan Lunas</p>
                <p class="text-2xl font-black text-emerald-600 mt-1" id="sumTotal">Rp 0</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Informasi Nota</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Rincian Items</th>
                            <th class="px-6 py-4 text-right">Tagihan Lunas</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="transactionGridBody" class="divide-y divide-slate-100 dark:divide-slate-700/50 text-slate-700 dark:text-slate-300">
                        <tr><td colspan="5" class="text-center py-12 text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style> .hide-scrollbar::-webkit-scrollbar { display: none; } .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; } </style>

<script>
    const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
    let currentDate = new Date().toLocaleDateString('en-CA');

    async function generateDays() {
        const m = document.getElementById('filterMonth').value;
        const y = document.getElementById('filterYear').value;
        const daysInMonth = new Date(y, m, 0).getDate();
        const container = document.getElementById('daysContainer');
        
        // Tampilkan loading state
        container.innerHTML = '<span class="text-xs text-slate-400 py-2 font-bold animate-pulse">Memuat kalender...</span>';

        // Auto-detect tipe data berdasarkan URL page saat ini
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page');
        let apiType = 'completed';
        if (currentPage === 'orders') apiType = 'active_order';
        if (currentPage === 'receivables') apiType = 'receivable';

        // Fetch "Bocoran" tanggal yang ada transaksinya dari server
        let activeDates = [];
        try {
            const res = await fetch(`../api/?action=get_active_dates&month=${m}&year=${y}&type=${apiType}`);
            const data = await res.json();
            if(data.status === 'success') activeDates = data.data;
        } catch(e) {}

        container.innerHTML = '';
        
        for(let i = 1; i <= daysInMonth; i++) {
            const dayStr = i.toString().padStart(2, '0');
            const monthStr = m.toString().padStart(2, '0');
            const dateStr = `${y}-${monthStr}-${dayStr}`;
            
            const isActive = dateStr === currentDate;
            const hasData = activeDates.includes(dateStr);
            const isSunday = new Date(y, m - 1, i).getDay() === 0; // 0 = Hari Minggu

            let btnClass = '';
            let textColor = isSunday ? 'text-red-500' : 'text-slate-600 dark:text-slate-300';
            let dayLabel = isSunday ? 'Min' : 'Tgl';
            
            if (isActive) {
                // Style Aktif (Terpilih)
                btnClass = 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30';
                textColor = 'text-white font-black';
            } else if (!hasData) {
                // Style Kosong (Disabled) - Dibuat pudar & kursor not-allowed
                btnClass = 'bg-slate-50 dark:bg-slate-900/30 border-transparent opacity-40 cursor-not-allowed grayscale';
            } else {
                // Style Ada Data (Bisa diklik)
                btnClass = 'bg-white dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 border border-slate-200 dark:border-slate-600 shadow-sm cursor-pointer';
                if(isSunday) textColor = 'text-red-600 font-black'; // Penegasan hari minggu yang bisa diklik
            }

            const disabledAttr = (!hasData && !isActive) ? 'disabled' : '';

            container.innerHTML += `
                <button ${disabledAttr} onclick="selectDate('${dateStr}')" class="px-4 py-2.5 rounded-xl min-w-[50px] transition-all flex flex-col items-center flex-shrink-0 ${btnClass}">
                    <span class="text-xs font-medium opacity-80 mb-0.5 ${textColor}">${dayLabel}</span>
                    <span class="text-lg font-bold leading-none ${textColor}">${i}</span>
                </button>`;
        }
        
        // Auto scroll horizontal ke tanggal yang aktif
        setTimeout(() => {
            const activeBtn = container.querySelector('.bg-indigo-600');
            if(activeBtn) activeBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }, 100);

        // Eksekusi fungsi datagrid sesuai halaman tempat script ini numpang
        if (currentPage === 'orders') fetchActiveOrders();
        else if (currentPage === 'receivables') fetchReceivables();
        else fetchTransactions();
    }

    function selectDate(date) { currentDate = date; generateDays(); }

    async function fetchTransactions() {
        const tbody = document.getElementById('transactionGridBody');
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-12 text-indigo-500 font-bold animate-pulse">Memuat data transaksi...</td></tr>';
        
        try {
            const response = await fetch(`../api/?action=get_transactions&date=${currentDate}`);
            const result = await response.json();
            if (result.status === 'success') { renderTransactions(result.data); }
        } catch (error) { tbody.innerHTML = '<tr><td colspan="5" class="text-center py-12 text-red-500">Gagal terhubung ke server.</td></tr>'; }
    }

    function renderTransactions(transactions) {
        const tbody = document.getElementById('transactionGridBody');
        tbody.innerHTML = '';
        let totalAmount = 0;

        if (transactions.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12"><p class="font-bold text-slate-400">Tidak ada transaksi lunas pada tanggal ini.</p></td></tr>`;
            document.getElementById('sumCount').innerHTML = `0 <span class="text-sm font-medium text-slate-400">Nota</span>`;
            document.getElementById('sumTotal').innerText = 'Rp 0';
            return;
        }

        transactions.forEach(trx => {
            totalAmount += parseFloat(trx.total_amount);
            
            let itemsHtml = `<div class="flex flex-col gap-1">`;
            trx.items.forEach(item => { itemsHtml += `<span class="text-xs bg-slate-100 dark:bg-slate-700/50 px-2 py-1 rounded w-max">${item.qty}x ${item.product_name}</span>`; });
            itemsHtml += `</div>`;

            // Badge Metode Bayar
            let badgeColor = trx.payment_method === 'QRIS' ? 'bg-sky-100 text-sky-700 border-sky-200' : (trx.payment_method === 'TRANSFER' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-emerald-100 text-emerald-700 border-emerald-200');

            tbody.innerHTML += `
                <tr class="hover:bg-slate-50 dark:bg-slate-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-mono text-sm font-bold text-emerald-600 dark:text-emerald-400">${trx.invoice_number}</div>
                        <div class="text-xs text-slate-400 mt-1">${trx.created_at.split(' ')[1]} WIB</div>
                        ${trx.reference_no ? `<div class="text-[10px] mt-1 text-slate-400">Ref: ${trx.reference_no}</div>` : ''}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800 dark:text-slate-200">${trx.customer_name || 'UMUM'}</div>
                        <div class="text-[10px] font-bold border px-1.5 py-0.5 rounded uppercase mt-1 w-max ${badgeColor}">${trx.payment_method}</div>
                    </td>
                    <td class="px-6 py-4">${itemsHtml}</td>
                    <td class="px-6 py-4 text-right font-black text-emerald-600 dark:text-emerald-400">${formatRp(trx.total_amount)}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="window.open('../api/?action=view_receipt&invoice=${trx.invoice_number}', '_blank', 'width=450,height=700')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-lg text-xs transition duration-200 shadow-sm flex items-center gap-2 mx-auto">
                            🖨️ Struk
                        </button>
                    </td>
                </tr>`;
        });

        document.getElementById('sumCount').innerHTML = `${transactions.length} <span class="text-sm font-medium text-slate-400">Nota</span>`;
        document.getElementById('sumTotal').innerText = formatRp(totalAmount);
    }

    document.addEventListener('DOMContentLoaded', generateDays);
</script>