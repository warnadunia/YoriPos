<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Monitoring Piutang - Yori App</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; } 
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased pb-24">

    <!-- Header Dashboard (Aksen Merah Piutang) -->
    <div class="bg-white px-5 py-4 border-b border-slate-200 sticky top-0 z-40 flex items-center justify-between shadow-sm">
        <div>
            <h1 class="text-lg font-black text-slate-800 leading-none">Monitoring Piutang</h1>
            <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider mt-1">TAGIHAN KASBON AKTIF</p>
        </div>
    </div>

    <!-- Filter Bulan & Tahun -->
    <div class="p-4 bg-white border-b border-slate-200 shadow-sm">
        <div class="flex gap-3 mb-3">
            <select id="filterMonth" onchange="generateDays()" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-red-600 outline-none focus:ring-2 focus:ring-red-500">
                <?php 
                $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                foreach($months as $k => $v): $m = $k+1; 
                ?>
                    <option value="<?= $m ?>" <?= date('n') == $m ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <select id="filterYear" onchange="generateDays()" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-red-600 outline-none focus:ring-2 focus:ring-red-500">
                <?php $cy = date('Y'); for($i=$cy-1; $i<=$cy+1; $i++): ?>
                    <option value="<?= $i ?>" <?= $cy == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Kalender Horizontal Swipe -->
        <div id="daysContainer" class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar scroll-smooth">
            <!-- Render via JS -->
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="px-4 mt-4 grid grid-cols-2 gap-3">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center border-t-4 border-t-red-500">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Kasbon</p>
            <p class="text-xl font-black text-red-600 mt-1" id="sumCount">0</p>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm text-center border-t-4 border-t-red-500">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Uang Tertahan</p>
            <p class="text-xl font-black text-red-600 mt-1" id="sumTotal">Rp 0</p>
        </div>
    </div>

    <!-- Daftar Piutang Cards -->
    <div class="p-4 flex flex-col gap-3" id="receivableList">
        <!-- Render via JS -->
    </div>

    <!-- PWA Bottom Navigation Modular -->
    <?php include 'components/bottomnav.php'; ?>

    <script>
        const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
        
        let currentDate = new Date().toLocaleDateString('en-CA'); 

        // Fungsi Load Kalender
        async function generateDays() {
            const m = document.getElementById('filterMonth').value;
            const y = document.getElementById('filterYear').value;
            const daysInMonth = new Date(y, m, 0).getDate();
            const container = document.getElementById('daysContainer');
            
            container.innerHTML = '<span class="text-xs text-slate-400 py-2 font-bold animate-pulse">Memuat kalender...</span>';

            let activeDates = [];
            try {
                const res = await fetch(`../api/?action=get_active_dates&month=${m}&year=${y}&type=receivable`);
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
                    btnClass = 'bg-red-600 text-white shadow-md shadow-red-500/30';
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
            
            setTimeout(() => {
                const activeBtn = container.querySelector('.bg-red-600');
                if(activeBtn) activeBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }, 100);

            fetchReceivables();
        }

        function selectDate(date) {
            currentDate = date;
            generateDays(); 
        }

        // Fungsi Load Data Piutang
        async function fetchReceivables() {
            const container = document.getElementById('receivableList');
            container.innerHTML = '<div class="text-center py-10 text-red-500 font-bold text-sm animate-pulse">Mencari piutang...</div>';
            
            try {
                const response = await fetch(`../api/?action=get_receivables&date=${currentDate}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    renderReceivables(result.data);
                }
            } catch (error) { 
                container.innerHTML = '<div class="text-center text-red-500 py-10 font-bold text-sm">Gagal memuat data.</div>'; 
            }
        }

        function renderReceivables(receivables) {
            const container = document.getElementById('receivableList');
            container.innerHTML = '';
            let totalAmount = 0;

            if (receivables.length === 0) {
                container.innerHTML = `<div class="bg-white p-8 rounded-2xl text-center border border-slate-200 shadow-sm"><span class="text-4xl block mb-3 opacity-50">🎉</span><p class="text-slate-500 font-bold text-sm">Bagus! Tidak ada piutang aktif.</p></div>`;
                document.getElementById('sumCount').innerText = `0`;
                document.getElementById('sumTotal').innerText = 'Rp 0';
                return;
            }

            receivables.forEach(inv => {
                totalAmount += parseFloat(inv.total_amount);
                const timeStr = inv.created_at.split(' ')[1].substring(0, 5); 
                
                let itemsHtml = `<div class="flex flex-col gap-1.5 mt-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">`;
                inv.items.forEach(item => { 
                    itemsHtml += `
                        <div class="flex justify-between items-center">
                            <p class="text-[11px] font-bold text-slate-700">${item.qty}x ${item.product_name}</p>
                        </div>`; 
                });
                itemsHtml += `</div>`;

                container.innerHTML += `
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden mb-2">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>
                        
                        <div onclick="toggleExpand(${inv.id})" class="flex justify-between items-start pl-1 cursor-pointer active:opacity-70 transition-opacity">
                            <div>
                                <h3 class="font-black text-sm text-slate-800">${inv.customer_name || 'Pelanggan Umum'}</h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[11px] font-bold text-red-600">${inv.invoice_number}</p>
                                    <span class="text-slate-300">•</span>
                                    <p class="text-[10px] text-slate-500 font-medium">${timeStr} WIB</p>
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-2">
                                <p class="font-black text-base text-slate-800">${formatRp(inv.total_amount)}</p>
                                <svg id="icon-${inv.id}" class="w-5 h-5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <div id="expand-${inv.id}" class="hidden pl-1 mt-3 pt-2 border-t border-slate-100">
                            ${inv.reference_no ? `<p class="text-[10px] text-slate-400 font-medium mb-1">Ref: ${inv.reference_no}</p>` : ''}
                            ${itemsHtml}
                            
                            <div class="flex gap-2 mt-4">
                                <button onclick="processReceivableCompletion(${inv.id}, '${inv.invoice_number}')" class="flex-1 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white font-bold py-2.5 rounded-xl text-xs transition-transform shadow-sm flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Lunasi
                                </button>
                                <button onclick="window.open('../api/?action=view_receipt&invoice=${inv.invoice_number}', '_blank')" class="bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-bold py-2.5 px-3 rounded-xl text-xs transition-transform shadow-sm flex items-center justify-center gap-1.5 border border-slate-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Struk
                                </button>
                            </div>
                        </div>
                    </div>`;
            });

            document.getElementById('sumCount').innerText = `${receivables.length}`;
            document.getElementById('sumTotal').innerText = formatRp(totalAmount);
        }

        function toggleExpand(id) {
            const exp = document.getElementById(`expand-${id}`);
            const icon = document.getElementById(`icon-${id}`);
            if (exp.classList.contains('hidden')) {
                exp.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                exp.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Eksekutor API Lunasi Piutang
        function processReceivableCompletion(id, invoiceStr) {
            Swal.fire({
                title: 'Lunasi Piutang?', 
                html: `Tagihan <b>${invoiceStr}</b> akan ditandai Lunas.<br>Pilih metode pembayaran:`, 
                icon: 'question',
                showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#94a3b8', confirmButtonText: 'TUNAI (Cash)', cancelButtonText: 'Batal',
                showDenyButton: true, denyButtonText: 'QRIS / TRANSFER', denyButtonColor: '#3b82f6',
                customClass: { popup: 'rounded-3xl', actions: 'flex-col gap-2' }
            }).then(async (result) => {
                if (result.isConfirmed || result.isDenied) {
                    const method = result.isConfirmed ? 'CASH' : 'QRIS'; 
                    try {
                        const response = await fetch('../api/?action=complete_order', { 
                            method: 'POST', 
                            headers: { 'Content-Type': 'application/json' }, 
                            body: JSON.stringify({ id: id, payment_method: method }) 
                        });
                        const res = await response.json();
                        if (res.status === 'success') { 
                            Toast.fire({ icon: 'success', title: 'Piutang Dilunasi!' }); 
                            fetchReceivables(); 
                        } else { 
                            Swal.fire('Gagal', res.message, 'error'); 
                        }
                    } catch (error) { Swal.fire('Error', 'Gagal memproses ke server.', 'error'); }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', generateDays);
    </script>
</body>
</html>