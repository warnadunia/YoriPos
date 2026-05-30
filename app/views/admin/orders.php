<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header & Date Filter Panel -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Antrean Pesanan</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Selesaikan pesanan dan mutasi ke invoice (lunas/piutang).</p>
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

            <!-- Kalender Horizontal -->
            <div id="daysContainer" class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar scroll-smooth">
                <!-- Tombol tanggal di-generate via JS -->
            </div>
        </div>

        <!-- Summary Cards Harian -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase">Total Pesanan</p>
                <p class="text-2xl font-black text-indigo-600 mt-1" id="sumCount">0 <span class="text-sm font-medium text-slate-400">Nota</span></p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase">Estimasi Tagihan</p>
                <p class="text-2xl font-black text-emerald-600 mt-1" id="sumTotal">Rp 0</p>
            </div>
        </div>

        <!-- Datagrid Compact -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Informasi Nota</th>
                            <th class="px-6 py-4">Pemesan</th>
                            <th class="px-6 py-4">Rincian Items</th>
                            <th class="px-6 py-4 text-right">Tagihan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="orderGridBody" class="divide-y divide-slate-100 dark:divide-slate-700/50 text-slate-700 dark:text-slate-300">
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
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
    
    // Default ke hari ini (Sesuai zona waktu lokal)
    let currentDate = new Date().toLocaleDateString('en-CA'); // Format YYYY-MM-DD

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

    function selectDate(date) {
        currentDate = date;
        generateDays(); 
    }

    async function fetchActiveOrders() {
        const tbody = document.getElementById('orderGridBody');
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-12 text-indigo-500 font-bold animate-pulse">Memuat data pesanan...</td></tr>';
        
        try {
            const response = await fetch(`../api/?action=get_orders&date=${currentDate}`);
            const result = await response.json();
            
            if (result.status === 'success') {
                renderOrders(result.data);
            }
        } catch (error) { tbody.innerHTML = '<tr><td colspan="5" class="text-center py-12 text-red-500">Gagal terhubung ke server.</td></tr>'; }
    }

    function renderOrders(orders) {
        const tbody = document.getElementById('orderGridBody');
        tbody.innerHTML = '';

        let totalAmount = 0;

        if (orders.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12"><p class="font-bold text-slate-400">Tidak ada pesanan aktif pada tanggal ini.</p></td></tr>`;
            document.getElementById('sumCount').innerHTML = `0 <span class="text-sm font-medium text-slate-400">Nota</span>`;
            document.getElementById('sumTotal').innerText = 'Rp 0';
            return;
        }

        orders.forEach(ord => {
            totalAmount += parseFloat(ord.total_amount);
            
            let itemsHtml = `<div class="flex flex-col gap-1">`;
            ord.items.forEach(item => { itemsHtml += `<span class="text-xs bg-slate-100 dark:bg-slate-700/50 px-2 py-1 rounded w-max">${item.qty}x ${item.product_name}</span>`; });
            itemsHtml += `</div>`;

            tbody.innerHTML += `
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-mono text-sm font-bold text-indigo-600 dark:text-indigo-400">${ord.invoice_number}</div>
                        <div class="text-xs text-slate-400 mt-1">${ord.created_at.split(' ')[1]} WIB</div>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">${ord.customer_name || 'UMUM'}</td>
                    <td class="px-6 py-4">${itemsHtml}</td>
                    <td class="px-6 py-4 text-right font-black text-emerald-600 dark:text-emerald-400">${formatRp(ord.total_amount)}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="processOrderCompletion(${ord.id}, '${ord.invoice_number}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition duration-200 shadow-sm flex items-center gap-1 mx-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Selesaikan
                        </button>
                    </td>
                </tr>`;
        });

        document.getElementById('sumCount').innerHTML = `${orders.length} <span class="text-sm font-medium text-slate-400">Nota</span>`;
        document.getElementById('sumTotal').innerText = formatRp(totalAmount);
    }

    function processOrderCompletion(id, invoiceStr) {
        Swal.fire({
            title: 'Selesaikan Pesanan?', html: `Pesanan <b>${invoiceStr}</b> akan dimutasi menjadi Invoice.<br>Pilih metode:`, icon: 'question',
            showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#94a3b8', confirmButtonText: 'LUNAS (CASH/QRIS)', cancelButtonText: 'Batal',
            showDenyButton: true, denyButtonText: 'PIUTANG', denyButtonColor: '#ef4444'
        }).then(async (result) => {
            if (result.isConfirmed || result.isDenied) {
                const method = result.isConfirmed ? 'CASH' : 'PIUTANG'; 
                try {
                    const response = await fetch('../api/?action=complete_order', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: id, payment_method: method }) });
                    const res = await response.json();
                    if (res.status === 'success') { Toast.fire({ icon: 'success', title: 'Mutasi Berhasil!' }); fetchActiveOrders(); } 
                    else { Swal.fire('Gagal', res.message, 'error'); }
                } catch (error) { Swal.fire('Error', 'Gagal memproses ke server.', 'error'); }
            }
        });
    }

    // Inisialisasi awal saat load
    document.addEventListener('DOMContentLoaded', generateDays);
</script>