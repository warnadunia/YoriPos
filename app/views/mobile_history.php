<?php
// Cek hak akses
$perms = $_SESSION['permissions'] ?? [];
$isSuperAdmin = in_array('settings', $perms);

// Tarik Pengaturan Toko
$storeName = $appSettings['store_name'] ?? 'Yori App';
$userName = $_SESSION['name'] ?? $_SESSION['username'] ?? 'Johanna Doe';
if (trim($userName) == '') $userName = 'Bosku';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $storeName ?> - Riwayat Transaksi</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#e4f4ed] text-slate-800 antialiased selection:bg-teal-100 selection:text-teal-900 pb-32 min-h-screen font-sans"> 

    <div class="px-5 pt-7 pb-4 flex justify-between items-center">
        <h1 class="text-[22px] font-bold text-[#0ba37f] tracking-tight">Riwayat Transaksi</h1>
        
        <select id="filterYear" onchange="onYearChange()" class="bg-[#c5e6d8] text-[#295c5a] font-bold text-sm rounded-xl px-3 py-2 outline-none border border-transparent focus:border-[#0ba37f] shadow-sm cursor-pointer appearance-none text-center min-w-[80px]">
            </select>
    </div>

    <div class="px-4 mb-4">
        <div class="bg-white border border-white/60 rounded-[1.25rem] p-4 shadow-sm shadow-[#0ba37f]/5">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#0ba37f]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                Grafik Pembayaran Lunas (12 Bulan)
            </p>
            <div class="h-36 w-full relative">
                <canvas id="chart12Bulan"></canvas>
            </div>
        </div>
    </div>

    <div class="px-4 mb-5">
        <div class="bg-[#0ba37f] rounded-[1.25rem] p-5 relative text-white shadow-lg shadow-[#0ba37f]/20 flex justify-between items-center overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-[11px] font-bold text-teal-100 uppercase tracking-widest">Total Bulan Ini</p>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-sm font-bold text-teal-100">Rp</span>
                    <span class="text-[26px] font-bold tracking-tight leading-none" id="summaryMonthTotal">0</span>
                </div>
            </div>
            
            <div class="bg-[#0ebf96] rounded-xl p-3 flex flex-col justify-center items-center min-w-[75px] shadow-inner relative z-10 border border-white/10">
                <span class="font-bold text-[22px] leading-none my-0.5" id="summaryMonthCount">0</span>
                <p class="text-[9px] opacity-90 mt-1 font-medium">Transaksi</p>
            </div>
        </div>
    </div>

    <div class="px-4 mb-4">
        <div class="flex overflow-x-auto gap-3 pb-3 no-scrollbar" id="monthSlider">
            </div>
    </div>

    <div class="px-4 mb-4">
        <div class="bg-[#c5e6d8] p-1 rounded-xl flex shadow-inner">
            <button id="btnViewAll" onclick="setViewMode('all')" class="flex-1 py-2 text-[11px] font-bold rounded-lg bg-[#0ba37f] text-white shadow-sm transition-all">Tampilkan Semua</button>
            <button id="btnViewWeekly" onclick="setViewMode('weekly')" class="flex-1 py-2 text-[11px] font-bold rounded-lg text-[#295c5a] hover:bg-[#aed8c6] transition-all">Mingguan</button>
        </div>
    </div>

    <div class="px-4 pb-12" id="transactionList">
        <div class="text-center py-10">
            <svg class="animate-spin h-8 w-8 text-[#0ba37f] mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-[#5a8b79] font-medium">Memuat transaksi...</p>
        </div>
    </div>

    <?php include __DIR__ . '/components/bottomnav.php'; ?>

    <script>
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        const formatNominalOnly = (number) => new Intl.NumberFormat('id-ID').format(number);
        
        let allTransactions = [];
        let myChartInstance = null;
        let viewMode = 'all'; 
        let appSettings = {}; // Wadah setting untuk nampung format WA
        
        const now = new Date(new Date().toLocaleString("en-US", {timeZone: "Asia/Jakarta"}));
        let selectedYear = now.getFullYear();
        let selectedMonth = String(now.getMonth() + 1).padStart(2, '0');
        
        // Setup Tahun Dropdown
        const yearSelect = document.getElementById('filterYear');
        for (let y = selectedYear + 1; y >= selectedYear - 3; y--) {
            yearSelect.innerHTML += `<option value="${y}" ${y === selectedYear ? 'selected' : ''}>${y}</option>`;
        }

        // Sedot settingan toko buat link WA
        async function fetchInitData() {
            try {
                const resSet = await fetch('../api/?action=get_settings');
                const dataSet = await resSet.json();
                if(dataSet.status === 'success') { 
                    if (Array.isArray(dataSet.data)) {
                        dataSet.data.forEach(row => { appSettings[row.setting_key] = row.setting_value; });
                    } else { appSettings = dataSet.data; }
                }
            } catch(e) { console.error('Gagal narik setting:', e); }
            
            // Setelah setting narik, baru load riwayat
            fetchHistory();
        }

        async function fetchHistory() {
            try {
                const res = await fetch('../api/?action=get_transactions');
                const result = await res.json();
                
                if (result.status === 'success') {
                    // Filter: Hanya transaksi lunas umum
                    allTransactions = result.data.filter(t => t.payment_method !== 'PIUTANG');
                    updateUI();
                }
            } catch (err) {
                console.error("Gagal load data", err);
                document.getElementById('transactionList').innerHTML = `<p class="text-center text-red-500 py-10">Gagal memuat data. Periksa koneksi.</p>`;
            }
        }

        function onYearChange() {
            selectedYear = parseInt(document.getElementById('filterYear').value);
            updateUI();
        }

        function updateUI() {
            buildMonthSlider();
            updateMonthSummary();
            renderTransactions();
            render12MonthChart();
        }

        function buildMonthSlider() {
            const slider = document.getElementById('monthSlider');
            slider.innerHTML = '';
            
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            
            for (let i = 1; i <= 12; i++) {
                let mStr = String(i).padStart(2, '0');
                let prefix = `${selectedYear}-${mStr}`;
                
                let mTotal = 0, mCount = 0;
                allTransactions.forEach(t => {
                    if (t.created_at && t.created_at.startsWith(prefix)) {
                        mTotal += parseFloat(t.total_amount || 0);
                        mCount++;
                    }
                });
                
                let isActive = (mStr === selectedMonth);
                let baseClass = isActive 
                    ? 'bg-[#0ba37f] text-white shadow-md shadow-[#0ba37f]/30 border-transparent' 
                    : 'bg-white text-[#5a8b79] border border-[#d1e6db] hover:bg-[#f4faf6]';

                let nameColor = isActive ? 'text-teal-100' : 'text-slate-500';
                let priceColor = isActive ? 'text-white' : 'text-[#0ba37f]';

                slider.innerHTML += `
                    <div onclick="selectMonth('${mStr}')" class="min-w-[120px] flex flex-col p-3 rounded-2xl cursor-pointer transition-all duration-200 active:scale-95 border ${baseClass}">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[12px] font-bold ${nameColor}">${monthNames[i-1]}</span>
                            <span class="text-[10px] font-bold bg-black/10 px-1.5 py-0.5 rounded">${mCount} trx</span>
                        </div>
                        <span class="text-[14px] font-black ${priceColor}">${formatRupiah(mTotal)}</span>
                    </div>
                `;
            }
            
            setTimeout(() => {
                const activeEl = slider.querySelector('.text-white');
                if (activeEl && activeEl.parentElement) {
                    activeEl.parentElement.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            }, 100);
        }

        function selectMonth(mStr) {
            selectedMonth = mStr;
            buildMonthSlider();
            updateMonthSummary();
            renderTransactions();
        }

        function updateMonthSummary() {
            const prefix = `${selectedYear}-${selectedMonth}`;
            let total = 0, count = 0;
            
            allTransactions.forEach(t => {
                if(t.created_at && t.created_at.startsWith(prefix)) {
                    total += parseFloat(t.total_amount || 0);
                    count++;
                }
            });

            document.getElementById('summaryMonthTotal').innerText = formatNominalOnly(total);
            document.getElementById('summaryMonthCount').innerText = count;
        }

        function setViewMode(mode) {
            viewMode = mode;
            if (mode === 'all') {
                document.getElementById('btnViewAll').classList.add('bg-[#0ba37f]', 'text-white', 'shadow-sm');
                document.getElementById('btnViewAll').classList.remove('text-[#295c5a]', 'hover:bg-[#aed8c6]');
                document.getElementById('btnViewWeekly').classList.remove('bg-[#0ba37f]', 'text-white', 'shadow-sm');
                document.getElementById('btnViewWeekly').classList.add('text-[#295c5a]', 'hover:bg-[#aed8c6]');
            } else {
                document.getElementById('btnViewWeekly').classList.add('bg-[#0ba37f]', 'text-white', 'shadow-sm');
                document.getElementById('btnViewWeekly').classList.remove('text-[#295c5a]', 'hover:bg-[#aed8c6]');
                document.getElementById('btnViewAll').classList.remove('bg-[#0ba37f]', 'text-white', 'shadow-sm');
                document.getElementById('btnViewAll').classList.add('text-[#295c5a]', 'hover:bg-[#aed8c6]');
            }
            renderTransactions();
        }

        function renderTransactions() {
            const listContainer = document.getElementById('transactionList');
            const prefix = `${selectedYear}-${selectedMonth}`;
            
            let filtered = allTransactions.filter(t => t.created_at && t.created_at.startsWith(prefix));
            filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            
            if (filtered.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-12 px-5 bg-[#f4faf6] rounded-2xl border border-[#d1e6db] border-dashed">
                        <div class="w-16 h-16 bg-[#e4f4ed] rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-[#0ba37f]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-[13px] font-bold text-[#3e524a]">Belum Ada Transaksi</p>
                        <p class="text-[11px] text-[#5a8b79] mt-1">Tidak ada catatan penjualan lunas pada bulan ini.</p>
                    </div>`;
                return;
            }

            let html = '';

            if (viewMode === 'all') {
                filtered.forEach(t => { html += getTransactionCardHTML(t); });
            } else {
                const weeks = {
                    1: { label: 'Minggu 1 (Tgl 1 - 7)', data: [] },
                    2: { label: 'Minggu 2 (Tgl 8 - 14)', data: [] },
                    3: { label: 'Minggu 3 (Tgl 15 - 21)', data: [] },
                    4: { label: 'Minggu 4 (Tgl 22 - Akhir Bulan)', data: [] }
                };

                filtered.forEach(t => {
                    let d = parseInt(t.created_at.split(' ')[0].split('-')[2]);
                    if (d <= 7) weeks[1].data.push(t);
                    else if (d <= 14) weeks[2].data.push(t);
                    else if (d <= 21) weeks[3].data.push(t);
                    else weeks[4].data.push(t);
                });

                [1, 2, 3, 4].forEach(w => {
                    if (weeks[w].data.length > 0) {
                        let weekTotal = weeks[w].data.reduce((sum, item) => sum + parseFloat(item.total_amount || 0), 0);
                        html += `
                            <div class="mb-2 mt-4 flex justify-between items-center px-1 border-b border-[#d1e6db]/50 pb-1.5">
                                <span class="font-bold text-[#5a8b79] text-[10px] tracking-wider uppercase">${weeks[w].label}</span>
                                <span class="font-black text-[#0ba37f] text-xs">${formatRupiah(weekTotal)}</span>
                            </div>
                        `;
                        weeks[w].data.forEach(t => { html += getTransactionCardHTML(t); });
                    }
                });
            }
            
            listContainer.innerHTML = html;
        }

        // Template HTML untuk 1 buah Kartu Transaksi
        function getTransactionCardHTML(t) {
            const time = t.created_at.split(' ')[1].substring(0,5); 
            const dateStr = t.created_at.split(' ')[0];
            const dObj = new Date(dateStr);
            const dateFormatted = dObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            
            let badgeClass = '';
            if(t.payment_method === 'CASH') badgeClass = 'bg-[#4ade80]/20 text-[#295c5a] border-[#4ade80]/30';
            else if(t.payment_method === 'QRIS') badgeClass = 'bg-[#8b9de8]/20 text-[#295c5a] border-[#8b9de8]/30';
            else if(t.payment_method === 'TRANSFER') badgeClass = 'bg-[#e09191]/20 text-[#295c5a] border-[#e09191]/30';
            else badgeClass = 'bg-slate-200 text-slate-700';

            let originalInv = t.invoice_no || 'INV-000';
            let displayInv = originalInv.replace(/^(ORD|INV)-/, 'KWI-');

            return `
                <div class="bg-[#f4faf6] border border-[#d1e6db] rounded-xl p-3.5 shadow-sm flex flex-col gap-2.5 mb-3">
                    <div class="flex justify-between items-start border-b border-[#d1e6db]/50 pb-2.5">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-[#295c5a] text-[13px] uppercase tracking-wide">${displayInv}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded border ${badgeClass}">${t.payment_method}</span>
                            </div>
                            <p class="text-[11px] text-[#5a8b79] flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                ${dateFormatted}, ${time} WIB
                            </p>
                        </div>
                        <h3 class="font-bold text-[15px] text-[#0ba37f]">${formatRupiah(t.total_amount)}</h3>
                    </div>
                    
                    <div class="flex justify-between items-center mt-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 rounded-full bg-[#c5e6d8] flex items-center justify-center text-[#0ba37f]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold text-[#3e524a] truncate max-w-[100px]">${t.customer_name || 'Pelanggan Umum'}</span>
                        </div>
                        
                        <div class="flex gap-1.5">
                            <button onclick="sendReceiptWA(${t.id}, '${originalInv}')" class="flex items-center gap-1 bg-[#25D366] hover:bg-[#20b858] text-white px-2.5 py-1.5 rounded-lg transition-colors active:scale-95 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
                                <span class="text-[10px] font-bold">Kirim WA</span>
                            </button>
                            
                            <button onclick="printReceipt('${originalInv}')" class="flex items-center gap-1 bg-white border border-[#d1e6db] hover:bg-[#e4f4ed] text-[#0ba37f] px-2.5 py-1.5 rounded-lg transition-colors active:scale-95 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span class="text-[10px] font-bold">Cetak</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // FUNGSI BARU: WA
        function sendReceiptWA(id, invoiceStr) {
            const trx = allTransactions.find(t => t.id == id);
            if(!trx) return;

            let rawPhone = trx.customer_phone || ''; 
            if (rawPhone.startsWith('0')) { rawPhone = '62' + rawPhone.substring(1); }
            
            if (rawPhone.length < 8) {
                Swal.fire('Info', 'Nomor WA pelanggan tidak tercatat di sistem.', 'info');
                return;
            }

            const baseUrl = window.location.origin;
            const linkUrl = `${baseUrl}/api/?action=view_receipt&invoice=${invoiceStr}`;
            
            let storeName = appSettings.store_name || 'Toko Kami';
            let template = appSettings.wa_receipt_format || "Halo kak ini {invoice} yang bisa di buka / download di {link}.\nTerima kasih sudah berbelanja di {store_name}";

            // Gunakan KWI- prefix untuk pesan WA jika transaksinya lunas (sesuai UI)
            let displayInv = invoiceStr.replace(/^(ORD|INV)-/, 'KWI-');

            let message = template
                .replace(/{invoice}/g, displayInv)
                .replace(/{link}/g, linkUrl)
                .replace(/{store_name}/g, storeName);

            const waUrl = `https://wa.me/${rawPhone}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        function printReceipt(invoiceStr) {
            Swal.fire({
                title: 'Sedang Memproses',
                text: 'Menyiapkan struk kwitansi lunas...',
                icon: 'info',
                timer: 1000,
                showConfirmButton: false
            }).then(() => {
                window.open(`../api/?action=view_receipt&invoice=${invoiceStr}`, '_blank');
            });
        }

        function render12MonthChart() {
            const labelsBulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            
            let dataOmzet = [];
            for (let m = 1; m <= 12; m++) {
                let pMonth = String(m).padStart(2, '0');
                let prefix = `${selectedYear}-${pMonth}`;
                
                let total = 0;
                allTransactions.forEach(t => {
                    if (t.created_at && t.created_at.startsWith(prefix)) {
                        total += parseFloat(t.total_amount || 0);
                    }
                });
                dataOmzet.push(total);
            }

            if (myChartInstance) { myChartInstance.destroy(); }

            const ctx = document.getElementById('chart12Bulan').getContext('2d');
            myChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsBulan,
                    datasets: [{
                        label: 'Omzet',
                        data: dataOmzet,
                        backgroundColor: '#0ba37f',
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 16
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: function(context) { return ' ' + formatRupiah(context.raw); } } }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 9 }, color: '#5a8b79' } },
                        y: { grid: { color: '#e2f0e9' }, ticks: { display: false } }
                    }
                }
            });
        }

        // Jalankan fetchInitData (Setting ditarik dulu)
        fetchInitData();
    </script>
</body>
</html>