<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Riwayat Transaksi - Yori App</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Sembunyikan scrollbar bawaan browser biar UI kalender lebih clean */
        .hide-scrollbar::-webkit-scrollbar { display: none; } 
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased pb-24">

    <div class="bg-white px-5 py-4 border-b border-slate-200 sticky top-0 z-40 flex items-center justify-between shadow-sm">
        <div>
            <h1 class="text-lg font-black text-slate-800 leading-none">Riwayat Transaksi</h1>
            <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mt-1">TRANSAKSI LUNAS</p>
        </div>
    </div>

    <div class="p-4 bg-white border-b border-slate-200 shadow-sm">
        <div class="flex gap-3 mb-3">
            <select id="filterMonth" onchange="generateDays()" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-indigo-600 outline-none focus:ring-2 focus:ring-indigo-500">
                <?php 
                $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                foreach($months as $k => $v): $m = $k+1; 
                ?>
                    <option value="<?= $m ?>" <?= date('n') == $m ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <select id="filterYear" onchange="generateDays()" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-indigo-600 outline-none focus:ring-2 focus:ring-indigo-500">
                <?php $cy = date('Y'); for($i=$cy-1; $i<=$cy+1; $i++): ?>
                    <option value="<?= $i ?>" <?= $cy == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div id="daysContainer" class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar scroll-smooth">
            </div>
    </div>

    <div class="px-4 mt-4 grid grid-cols-2 gap-3">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Nota</p>
            <p class="text-xl font-black text-indigo-600 mt-1" id="sumCount">0</p>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pendapatan</p>
            <p class="text-xl font-black text-emerald-600 mt-1" id="sumTotal">Rp 0</p>
        </div>
    </div>

    <div class="p-4 flex flex-col gap-3" id="transactionList">
        </div>

    <?php include 'components/bottomnav.php'; ?>

    <script>
        const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        
        let currentDate = new Date().toLocaleDateString('en-CA'); 

        // Fungsi Load Kalender Swipe Dinamis (Diadaptasi dari versi Web)
        async function generateDays() {
            const m = document.getElementById('filterMonth').value;
            const y = document.getElementById('filterYear').value;
            const daysInMonth = new Date(y, m, 0).getDate();
            const container = document.getElementById('daysContainer');
            
            container.innerHTML = '<span class="text-xs text-slate-400 py-2 font-bold animate-pulse">Memuat kalender...</span>';

            let activeDates = [];
            try {
                // Tembak data 'completed' buat highlight tanggal yang ada transaksi lunasnya
                const res = await fetch(`../api/?action=get_active_dates&month=${m}&year=${y}&type=completed`);
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
                const isSunday = new Date(y, m - 1, i).getDay() === 0; 

                let btnClass = '';
                let textColor = isSunday ? 'text-red-500' : 'text-slate-600';
                let dayLabel = isSunday ? 'Min' : 'Tgl';
                
                if (isActive) {
                    btnClass = 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30';
                    textColor = 'text-white font-black';
                } else if (!hasData) {
                    btnClass = 'bg-slate-50 border border-transparent opacity-40 cursor-not-allowed grayscale';
                } else {
                    btnClass = 'bg-white border border-slate-200 shadow-sm cursor-pointer hover:bg-slate-50';
                    if(isSunday) textColor = 'text-red-600 font-black'; 
                }

                const disabledAttr = (!hasData && !isActive) ? 'disabled' : '';

                container.innerHTML += `
                    <button ${disabledAttr} onclick="selectDate('${dateStr}')" class="px-3.5 py-2 rounded-xl min-w-[55px] transition-all flex flex-col items-center flex-shrink-0 ${btnClass}">
                        <span class="text-[10px] font-medium opacity-80 mb-0.5 ${textColor}">${dayLabel}</span>
                        <span class="text-base font-bold leading-none ${textColor}">${i}</span>
                    </button>`;
            }
            
            // Auto scroll ke tanggal yang dipilih
            setTimeout(() => {
                const activeBtn = container.querySelector('.bg-indigo-600');
                if(activeBtn) activeBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }, 100);

            fetchTransactions();
        }

        function selectDate(date) {
            currentDate = date;
            generateDays(); 
        }

        // Fungsi Load Transaksi ke UI Mobile
        async function fetchTransactions() {
            const container = document.getElementById('transactionList');
            container.innerHTML = '<div class="text-center py-10 text-indigo-500 font-bold text-sm animate-pulse">Mencari transaksi...</div>';
            
            try {
                // Tembak endpoint API order berdasarkan tanggal
                const response = await fetch(`../api/?action=get_orders&date=${currentDate}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    // Filter murni yang statusnya KWI- (Kwitansi Lunas)
                    const trxLunas = result.data.filter(ord => ord.invoice_number.startsWith('KWI-'));
                    renderTransactions(trxLunas);
                }
            } catch (error) { 
                container.innerHTML = '<div class="text-center text-red-500 py-10 font-bold text-sm">Gagal memuat data.</div>'; 
            }
        }

        function renderTransactions(transactions) {
            const container = document.getElementById('transactionList');
            container.innerHTML = '';
            let totalAmount = 0;

            if (transactions.length === 0) {
                container.innerHTML = `<div class="bg-white p-8 rounded-2xl text-center border border-slate-200 shadow-sm"><span class="text-4xl block mb-3 opacity-50">📭</span><p class="text-slate-500 font-bold text-sm">Tidak ada transaksi lunas di tanggal ini.</p></div>`;
                document.getElementById('sumCount').innerText = `0`;
                document.getElementById('sumTotal').innerText = 'Rp 0';
                return;
            }

            transactions.forEach(trx => {
                totalAmount += parseFloat(trx.total_amount);
                
                // Format Waktu
                const timeStr = trx.created_at.split(' ')[1].substring(0, 5); // Ambil Jam:Menit saja
                
                // Rincian Item (Bentuk Badge Compact)
                let itemsHtml = `<div class="flex flex-wrap gap-1.5 mt-2">`;
                trx.items.forEach(item => { 
                    itemsHtml += `<span class="text-[10px] bg-slate-100 px-2 py-1 rounded-lg text-slate-600 border border-slate-200">${item.qty}x ${item.product_name}</span>`; 
                });
                itemsHtml += `</div>`;

                // Badge Warna berdasarkan metode pembayaran
                let badgeColor = 'bg-slate-100 text-slate-600 border-slate-200';
                if (trx.payment_method === 'CASH') badgeColor = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                if (trx.payment_method === 'QRIS') badgeColor = 'bg-blue-50 text-blue-600 border-blue-200';
                if (trx.payment_method === 'TRANSFER') badgeColor = 'bg-purple-50 text-purple-600 border-purple-200';

                // Tampilan Card Transaksi Mobile
                container.innerHTML += `
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
                        
                        <div class="flex justify-between items-start pl-1">
                            <div>
                                <h3 class="font-black text-sm text-slate-800">${trx.customer_name || 'Pelanggan Umum'}</h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[11px] font-bold text-indigo-600">${trx.invoice_number}</p>
                                    <span class="text-slate-300">•</span>
                                    <p class="text-[10px] text-slate-500 font-medium">${timeStr} WIB</p>
                                </div>
                            </div>
                            <span class="text-[9px] font-black uppercase px-2 py-1 rounded-md tracking-wider border ${badgeColor}">${trx.payment_method}</span>
                        </div>

                        <div class="pl-1">
                            ${itemsHtml}
                        </div>
                        
                        <div class="pl-1 mt-3 pt-3 border-t border-slate-100 flex justify-between items-center">
                            <p class="font-black text-lg text-slate-800">${formatRp(trx.total_amount)}</p>
                            
                            <button onclick="window.open('../api/?action=view_receipt&invoice=${trx.invoice_number}', '_blank')" class="bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-bold py-2 px-3 rounded-xl text-xs transition-transform shadow-sm flex items-center gap-1.5 border border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Struk
                            </button>
                        </div>
                    </div>`;
            });

            // Update Summary
            document.getElementById('sumCount').innerText = `${transactions.length}`;
            document.getElementById('sumTotal').innerText = formatRp(totalAmount);
        }

        // Jalankan saat load
        document.addEventListener('DOMContentLoaded', generateDays);
    </script>
</body>
</html>