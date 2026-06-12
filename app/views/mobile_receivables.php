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
    <title><?= $storeName ?> - Monitor Piutang</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#fdf3f3] text-slate-800 antialiased selection:bg-red-100 selection:text-red-900 pb-32 min-h-screen font-sans"> 

    <div class="px-5 pt-7 pb-4 flex justify-between items-center">
        <h1 class="text-[22px] font-black text-[#E55B5B] tracking-tight">Monitor Piutang</h1>
        
        <select id="filterYear" onchange="onYearChange()" class="bg-[#fcdcdc] text-[#802c2c] font-bold text-sm rounded-xl px-3 py-2 outline-none border border-transparent focus:border-[#E55B5B] shadow-sm cursor-pointer appearance-none text-center min-w-[80px]">
            </select>
    </div>

    <div class="px-4 mb-5">
        <div class="bg-[#E55B5B] rounded-[1.25rem] p-5 relative text-white shadow-lg shadow-[#E55B5B]/20 flex justify-between items-center overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
            
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-red-100 uppercase tracking-widest mb-1">Total Uang Tertahan</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-sm font-bold text-red-100">Rp</span>
                    <span class="text-[28px] font-black tracking-tight leading-none" id="summaryYearTotal">0</span>
                </div>
            </div>
            
            <div class="bg-[#db4d4d] rounded-xl p-3 flex flex-col justify-center items-center min-w-[75px] shadow-inner relative z-10 border border-white/10">
                <span class="font-black text-[22px] leading-none my-0.5" id="summaryYearCount">0</span>
                <p class="text-[10px] text-red-100 mt-1 font-bold">Inv</p>
            </div>
        </div>
    </div>

    <div class="px-4 mb-5">
        <div class="flex overflow-x-auto gap-3 pb-3 no-scrollbar" id="monthSlider">
            </div>
    </div>

    <div class="px-5 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-[#E55B5B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <h2 class="text-sm font-bold text-[#802c2c]">Tabel Daftar Piutang</h2>
    </div>

    <div class="px-4 space-y-3 pb-12" id="receivableList">
        <div class="text-center py-10">
            <svg class="animate-spin h-8 w-8 text-[#E55B5B] mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-[#b86a6a] font-medium">Memuat piutang...</p>
        </div>
    </div>

    <?php include __DIR__ . '/components/bottomnav.php'; ?>

    <script>
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        const formatNominalOnly = (number) => new Intl.NumberFormat('id-ID').format(number);
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
        
        let allReceivables = [];
        
        const now = new Date();
        let selectedYear = now.getFullYear();
        let selectedMonth = String(now.getMonth() + 1).padStart(2, '0'); // 01 - 12
        
        // Setup Tahun Dropdown
        const yearSelect = document.getElementById('filterYear');
        for (let y = selectedYear + 1; y >= selectedYear - 3; y--) {
            yearSelect.innerHTML += `<option value="${y}" ${y === selectedYear ? 'selected' : ''}>${y}</option>`;
        }

        async function fetchReceivablesData() {
            try {
                const res = await fetch('../api/?action=get_receivables');
                const result = await res.json();
                
                if (result.status === 'success') {
                    allReceivables = result.data;
                    updateUI();
                }
            } catch (err) {
                console.error("Gagal load data", err);
                document.getElementById('receivableList').innerHTML = `<p class="text-center text-red-500 py-10">Gagal memuat data. Periksa koneksi.</p>`;
            }
        }

        function onYearChange() {
            selectedYear = parseInt(document.getElementById('filterYear').value);
            updateUI();
        }

        function updateUI() {
            updateYearSummary();
            buildMonthSlider();
            renderReceivables();
        }

        function updateYearSummary() {
            let total = 0, count = 0;
            
            allReceivables.forEach(t => {
                if(t.created_at && t.created_at.startsWith(String(selectedYear))) {
                    total += parseFloat(t.remaining_amount || t.total_amount || 0);
                    count++;
                }
            });

            document.getElementById('summaryYearTotal').innerText = formatNominalOnly(total);
            document.getElementById('summaryYearCount').innerText = count;
        }

        function buildMonthSlider() {
            const slider = document.getElementById('monthSlider');
            slider.innerHTML = '';
            
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            
            for (let i = 1; i <= 12; i++) {
                let mStr = String(i).padStart(2, '0');
                let prefix = `${selectedYear}-${mStr}`;
                
                // Hitung piutang per bulan
                let mTotal = 0, mCount = 0;
                allReceivables.forEach(t => {
                    if (t.created_at && t.created_at.startsWith(prefix)) {
                        mTotal += parseFloat(t.remaining_amount || t.total_amount || 0);
                        mCount++;
                    }
                });
                
                let isActive = (mStr === selectedMonth);
                let baseClass = isActive 
                    ? 'bg-[#E55B5B] text-white shadow-md shadow-[#E55B5B]/30 border-transparent' 
                    : 'bg-white text-[#b86a6a] border border-[#f7d7d7] hover:bg-[#fdf3f3]';

                let nameColor = isActive ? 'text-red-100' : 'text-slate-500';
                let priceColor = isActive ? 'text-white' : 'text-[#E55B5B]';

                slider.innerHTML += `
                    <div onclick="selectMonth('${mStr}')" class="min-w-[120px] flex flex-col p-3 rounded-2xl cursor-pointer transition-all duration-200 active:scale-95 border ${baseClass}">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[12px] font-bold ${nameColor}">${monthNames[i-1]}</span>
                            <span class="text-[10px] font-bold bg-black/5 px-1.5 py-0.5 rounded">${mCount} inv</span>
                        </div>
                        <span class="text-[14px] font-black ${priceColor}">${formatRupiah(mTotal)}</span>
                    </div>
                `;
            }
            
            // Auto scroll ke bulan aktif
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
            renderReceivables();
        }

        function renderReceivables() {
            const listContainer = document.getElementById('receivableList');
            const prefix = `${selectedYear}-${selectedMonth}`;
            const filtered = allReceivables.filter(t => t.created_at && t.created_at.startsWith(prefix));
            
            if (filtered.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-10 px-5 bg-white rounded-2xl border border-[#f7d7d7] border-dashed">
                        <div class="w-14 h-14 bg-[#fdf3f3] rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-[#E55B5B]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-[13px] font-bold text-[#802c2c]">Tidak Ada Piutang</p>
                        <p class="text-[11px] text-[#b86a6a] mt-1">Bulan ini bersih dari tagihan kasbon.</p>
                    </div>`;
                return;
            }

            let html = '';
            filtered.forEach(inv => {
                // Bangun Rincian Item (Quantity x Harga)
                let itemsHtml = '';
                if(inv.items && inv.items.length > 0) {
                    inv.items.forEach(item => { 
                        // Fallback logika harga jika API belum nyediain item.price langsung
                        let price = item.price || Math.round((item.subtotal||0)/item.qty) || 0;
                        let subtotal = item.subtotal || (item.qty * price);
                        
                        itemsHtml += `
                            <div class="flex justify-between items-start mb-1.5 text-[11px]">
                                <div class="flex-1">
                                    <p class="font-bold text-slate-700">${item.product_name}</p>
                                    <p class="text-slate-400">${item.qty} x ${formatRupiah(price)}</p>
                                </div>
                                <span class="font-bold text-slate-700">${formatRupiah(subtotal)}</span>
                            </div>`; 
                    });
                } else {
                    itemsHtml += `<p class="text-[10px] text-slate-400 italic">Rincian item tidak tersedia di database lama.</p>`;
                }

                // Kalkulasi PPN (Jika ada di API)
                let taxHtml = '';
                let taxAmount = parseFloat(inv.tax_amount || 0);
                if(taxAmount > 0) {
                    taxHtml = `
                        <div class="flex justify-between items-center text-[11px] text-slate-500 mb-1">
                            <span>PPN ${inv.tax_percentage || 11}%</span>
                            <span>${formatRupiah(taxAmount)}</span>
                        </div>`;
                }

                // WA Button Logic
                let phone = inv.customer_phone || '';
                let waAction = phone 
                    ? `window.open('https://wa.me/${phone.replace(/^0/, '62')}?text=Halo%20${encodeURIComponent(inv.customer_name)},%20ini%20pengingat%20tagihan%20nomor%20${inv.invoice_number}.', '_blank')`
                    : `Swal.fire('Ups!', 'Nomor HP Pelanggan tidak terdaftar', 'warning')`;

                html += `
                    <div class="bg-white border border-[#f7d7d7] shadow-sm rounded-2xl p-4 mb-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-[13px] text-slate-700">${inv.customer_name || 'Pelanggan Umum'}</span>
                            <span class="text-[10px] font-bold text-slate-400">Total</span>
                        </div>
                        
                        <div class="flex justify-between items-center cursor-pointer active:opacity-70" onclick="toggleExpand(${inv.id})">
                            <span class="font-black text-[15px] text-[#E55B5B] uppercase">${inv.invoice_number}</span>
                            <div class="flex items-center gap-2">
                                <span class="font-black text-[15px] text-slate-800">${formatRupiah(inv.total_amount)}</span>
                                <svg id="icon-${inv.id}" class="w-4 h-4 text-[#b86a6a] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <div id="expand-${inv.id}" class="hidden mt-3 pt-3 border-t border-slate-100">
                            
                            <div class="flex items-start gap-2 mb-3 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <svg class="w-4 h-4 text-[#E55B5B] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <p class="text-[11px] text-slate-600 font-medium leading-relaxed">${inv.customer_address || 'Alamat kirim tidak diisi pada master data konsumen.'}</p>
                            </div>

                            <div class="mb-3 border-b border-dashed border-slate-200 pb-2">
                                ${itemsHtml}
                            </div>
                            
                            <div class="mb-4">
                                ${taxHtml}
                                <div class="flex justify-between items-center font-black text-[13px] text-slate-800">
                                    <span>Total Tagihan</span>
                                    <span>${formatRupiah(inv.total_amount)}</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button onclick="${waAction}" class="flex-1 bg-[#25D366] hover:bg-[#20b858] active:scale-95 text-white font-bold py-2.5 rounded-xl text-[11px] transition-transform shadow-sm flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"></path></svg>
                                    Tanya WA
                                </button>
                                <button onclick="processReceivableCompletion(${inv.id}, '${inv.invoice_number}')" class="flex-1 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white font-bold py-2.5 rounded-xl text-[11px] transition-transform shadow-sm flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Lunasi
                                </button>
                                <button onclick="window.open('../api/?action=view_receipt&invoice=${inv.invoice_number}', '_blank')" class="bg-slate-100 hover:bg-slate-200 active:scale-95 text-[#802c2c] font-bold py-2.5 px-3 rounded-xl text-[11px] transition-transform shadow-sm flex items-center justify-center gap-1.5 border border-slate-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Struk
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            listContainer.innerHTML = html;
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

        function processReceivableCompletion(id, invoiceStr) {
            Swal.fire({
                title: 'Terima Pelunasan?', 
                html: `Tagihan <b>${invoiceStr}</b> akan ditandai Lunas.<br>Pilih jalur uang masuk:`, 
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
                            Toast.fire({ icon: 'success', title: 'Piutang Berhasil Dilunasi!' }); 
                            fetchReceivablesData(); 
                        } else { 
                            Swal.fire('Gagal', res.message, 'error'); 
                        }
                    } catch (error) { Swal.fire('Error', 'Gagal memproses ke server.', 'error'); }
                }
            });
        }

        // Init Load
        fetchReceivablesData();
    </script>
</body>
</html>