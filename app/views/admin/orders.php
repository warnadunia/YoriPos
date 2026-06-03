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


<div id="paymentModalWeb" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-center justify-center">
    <div class="bg-white w-full max-w-md mx-4 rounded-3xl shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 flex flex-col max-h-[95vh]" id="paymentModalWebContent">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
            <h3 class="text-xl font-black text-slate-800">Pembayaran Lunas</h3>
            <button onclick="closePaymentModalWeb()" class="text-slate-500 hover:text-red-500 bg-slate-100 p-2 rounded-full active:scale-90 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="p-6 space-y-6 overflow-y-auto">
            <div class="text-center bg-slate-50 py-4 rounded-2xl border border-slate-100">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Tagihan</p>
                <p class="text-4xl font-black text-emerald-500" id="payTotalDisplayWeb">Rp 0</p>
            </div>
            <div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="selectMethodWeb('CASH')" id="btnMethodCASHWeb" class="method-btn-web active bg-indigo-50 border-indigo-500 text-indigo-700 border-2 py-3 rounded-xl text-sm font-bold transition-all">Tunai</button>
                    <button type="button" onclick="selectMethodWeb('QRIS')" id="btnMethodQRISWeb" class="method-btn-web bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all hover:bg-slate-50">QRIS</button>
                    <button type="button" onclick="selectMethodWeb('TRANSFER')" id="btnMethodTRANSFERWeb" class="method-btn-web bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all hover:bg-slate-50">Transfer</button>
                </div>
            </div>
            
            <div id="cashInputSectionWeb" class="space-y-4 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Uang Diterima</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-black">Rp</span>
                        <input type="number" id="inputCashWeb" oninput="calculateChangeWeb()" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-black text-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="setExactCashWeb()" class="flex-1 bg-slate-100 font-bold py-2.5 rounded-lg text-sm text-slate-700 border border-slate-200 active:bg-slate-200 hover:bg-slate-200 transition-colors">Uang Pas</button>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Kembalian</label>
                    <input type="text" id="inputChangeWeb" readonly class="w-full px-4 py-3 bg-red-50 border border-red-100 rounded-xl font-black text-xl text-red-600 outline-none" value="Rp 0">
                </div>
            </div>

            <div id="nonCashSectionWeb" class="hidden space-y-5 pt-4 border-t border-slate-100">
                <div id="qrisViewWeb" class="hidden flex-col gap-3">
                    <p class="text-sm font-bold text-slate-700">Pilih QRIS Tujuan</p>
                    <div id="qrisListContainerWeb" class="flex flex-wrap gap-2"></div>
                    <div class="flex justify-center mt-2">
                        <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200">
                            <img id="qrisDisplayImageWeb" src="" alt="QRIS" class="w-48 h-48 object-contain rounded-lg">
                        </div>
                    </div>
                </div>
                <div id="transferViewWeb" class="hidden flex-col gap-3">
                    <p class="text-sm font-bold text-slate-700">Pilih Rekening Tujuan</p>
                    <div id="transferListContainerWeb" class="space-y-2 max-h-48 overflow-y-auto pr-1"></div>
                </div>
                
                <div class="pt-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Upload Bukti Bayar <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                    <input type="file" id="inputPaymentProofWeb" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer bg-slate-50 border border-slate-200 rounded-xl">
                    <input type="hidden" id="proofBase64Web" value="">
                </div>
            </div>
            
            <button id="btnConfirmPayWeb" onclick="submitWebPayment()" class="w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-black py-4 rounded-xl text-lg shadow-lg mt-4 disabled:opacity-50 transition-all">
                Konfirmasi Mutasi
            </button>
        </div>
    </div>
</div>

<style> .hide-scrollbar::-webkit-scrollbar { display: none; } .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; } </style>

<script>
    const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
    
    let currentDate = new Date().toLocaleDateString('en-CA'); 

    // --- VARIABEL & FUNGSI SETTINGS (LOAD QRIS & BANK) ---
    let webAppSettings = {};
    let webCurrentMethod = 'CASH';
    let webTotalTagihan = 0;
    let webActiveOrderId = null;
    let activeOrdersList = []; 

    async function fetchWebSettings() {
        try {
            const res = await fetch('../api/?action=get_settings');
            const data = await res.json();
            if(data.status === 'success') {
                // FIX: Pakai cara mobile_pos persis, langsung assign Object!
                webAppSettings = data.data; 
                renderWebDynamicMethods();
            }
        } catch(e) { console.error('Gagal memuat setting:', e); }
    }

    function renderWebDynamicMethods() {
        // Tembak Data Transfer Bank
        const tContainer = document.getElementById('transferListContainerWeb');
        if (tContainer) {
            let banks = []; try { banks = JSON.parse(webAppSettings.payment_transfer_list || '[]'); } catch(e){}
            tContainer.innerHTML = '';
            banks.forEach((b, i) => { 
                tContainer.innerHTML += `
                <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="radio" name="bankTransferWeb" value="${b.bank}" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500" ${i===0?'checked':''}> 
                    <span class="ml-3 font-bold text-sm text-slate-700 leading-tight">${b.bank} <br><span class="font-mono text-indigo-600">${b.number}</span> <span class="text-[10px] text-slate-400 font-normal">(${b.holder||'Perusahaan'})</span></span>
                </label>`; 
            });
        }
        
        // Tembak Data QRIS
        const qContainer = document.getElementById('qrisListContainerWeb');
        if (qContainer) {
            let qrisArr = []; try { qrisArr = JSON.parse(webAppSettings.payment_qris_list || '[]'); } catch(e){}
            qContainer.innerHTML = '';
            if(qrisArr.length > 0 && document.getElementById('qrisDisplayImageWeb')) document.getElementById('qrisDisplayImageWeb').src = qrisArr[0].image;
            qrisArr.forEach((q, i) => { 
                qContainer.innerHTML += `
                <label class="flex-1 text-center py-2 px-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="radio" name="qrisSelectWeb" class="hidden" ${i===0?'checked':''} onchange="document.getElementById('qrisDisplayImageWeb').src='${q.image}'"> 
                    <span class="font-bold text-sm block text-slate-700">${q.name}</span>
                </label>`; 
            });
        }
    }


    // --- FUNGSI KALENDER & GRID ---
    async function generateDays() {
        const m = document.getElementById('filterMonth').value;
        const y = document.getElementById('filterYear').value;
        const daysInMonth = new Date(y, m, 0).getDate();
        const container = document.getElementById('daysContainer');
        
        container.innerHTML = '<span class="text-xs text-slate-400 py-2 font-bold animate-pulse">Memuat kalender...</span>';

        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page');
        let apiType = 'completed';
        if (currentPage === 'orders') apiType = 'active_order';
        if (currentPage === 'receivables') apiType = 'receivable';

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
            const isSunday = new Date(y, m - 1, i).getDay() === 0; 

            let btnClass = '';
            let textColor = isSunday ? 'text-red-500' : 'text-slate-600 dark:text-slate-300';
            let dayLabel = isSunday ? 'Min' : 'Tgl';
            
            if (isActive) {
                btnClass = 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30';
                textColor = 'text-white font-black';
            } else if (!hasData) {
                btnClass = 'bg-slate-50 dark:bg-slate-900/30 border-transparent opacity-40 cursor-not-allowed grayscale';
            } else {
                btnClass = 'bg-white dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 border border-slate-200 dark:border-slate-600 shadow-sm cursor-pointer';
                if(isSunday) textColor = 'text-red-600 font-black'; 
            }

            const disabledAttr = (!hasData && !isActive) ? 'disabled' : '';

            container.innerHTML += `
                <button ${disabledAttr} onclick="selectDate('${dateStr}')" class="px-4 py-2.5 rounded-xl min-w-[50px] transition-all flex flex-col items-center flex-shrink-0 ${btnClass}">
                    <span class="text-xs font-medium opacity-80 mb-0.5 ${textColor}">${dayLabel}</span>
                    <span class="text-lg font-bold leading-none ${textColor}">${i}</span>
                </button>`;
        }
        
        setTimeout(() => {
            const activeBtn = container.querySelector('.bg-indigo-600');
            if(activeBtn) activeBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }, 100);

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
                activeOrdersList = result.data; 
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
                        <button onclick="processOrderCompletion(${ord.id}, '${ord.invoice_number}', ${ord.total_amount})" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition duration-200 shadow-sm flex items-center gap-1 mx-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Selesaikan
                        </button>
                    </td>
                </tr>`;
        });

        document.getElementById('sumCount').innerHTML = `${orders.length} <span class="text-sm font-medium text-slate-400">Nota</span>`;
        document.getElementById('sumTotal').innerText = formatRp(totalAmount);
    }


    // --- LOGIKA PENYELESAIAN (TAB PEMBAYARAN KASIR) ---

    function processOrderCompletion(id, invoiceStr, totalAmount = null) {
        Swal.fire({
            title: 'Selesaikan Pesanan?', 
            html: `Pesanan <b>${invoiceStr}</b> siap diselesaikan.<br>Pilih tipe penyelesaian:`, 
            icon: 'question',
            showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#94a3b8', 
            confirmButtonText: 'LUNAS', cancelButtonText: 'Batal',
            showDenyButton: true, denyButtonText: 'PIUTANG', denyButtonColor: '#ef4444'
        }).then((result) => {
            if (result.isDenied) {
                // Piutang
                executeCompleteOrderApi(id, 'PIUTANG');
            } else if (result.isConfirmed) {
                Swal.close();
                setTimeout(() => {
                    openPaymentModalWeb(id, totalAmount, invoiceStr);
                }, 350);
            }
        });
    }

    function openPaymentModalWeb(id, totalAmount, invoiceStr) {
        webActiveOrderId = id;
        
        webTotalTagihan = parseFloat(totalAmount) || 0;
        if(!webTotalTagihan) {
            const o = activeOrdersList.find(x => x.id == id);
            if(o) webTotalTagihan = parseFloat(o.total_amount);
        }

        document.getElementById('payTotalDisplayWeb').innerText = formatRp(webTotalTagihan);
        selectMethodWeb('CASH');
        document.getElementById('inputCashWeb').value = '';
        document.getElementById('proofBase64Web').value = '';
        
        const proofInput = document.getElementById('inputPaymentProofWeb');
        if(proofInput) proofInput.value = '';
        
        calculateChangeWeb();

        const m = document.getElementById('paymentModalWeb');
        const c = document.getElementById('paymentModalWebContent');
        
        m.style.zIndex = '9999';
        m.classList.remove('hidden');
        
        setTimeout(() => { 
            c.classList.remove('opacity-0', 'scale-95'); 
        }, 10);
    }

    function closePaymentModalWeb() {
        const m = document.getElementById('paymentModalWeb');
        const c = document.getElementById('paymentModalWebContent');
        c.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { m.classList.add('hidden'); }, 300);
    }

    // FIX: Hard-Reset Semua class biar nggak bentrok CSS-nya!
    function selectMethodWeb(m) {
        webCurrentMethod = m;
        ['CASH','QRIS','TRANSFER'].forEach(id => {
            document.getElementById('btnMethod'+id+'Web').className = id===m 
                ? `method-btn-web active bg-indigo-50 border-indigo-500 text-indigo-700 border-2 py-3 rounded-xl text-sm font-bold transition-all` 
                : `method-btn-web bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all hover:bg-slate-50`;
        });
        
        // Reset Paksa Semua ke Hidden
        document.getElementById('cashInputSectionWeb').className = 'hidden'; 
        document.getElementById('nonCashSectionWeb').className = 'hidden';
        document.getElementById('qrisViewWeb').className = 'hidden';
        document.getElementById('transferViewWeb').className = 'hidden';

        // Munculin Sesuai Tab yang Dipilih
        if(m === 'CASH') { 
            document.getElementById('cashInputSectionWeb').className = 'block space-y-4 pt-2'; 
            calculateChangeWeb(); 
        } else { 
            document.getElementById('nonCashSectionWeb').className = 'block space-y-5 pt-4 border-t border-slate-100'; 
            document.getElementById('btnConfirmPayWeb').disabled = false; 
            
            if (m === 'QRIS') { 
                document.getElementById('qrisViewWeb').className = 'flex flex-col gap-3'; 
            }
            if (m === 'TRANSFER') { 
                document.getElementById('transferViewWeb').className = 'flex flex-col gap-3'; 
            }
        }
    }

    function calculateChangeWeb() {
        if(webCurrentMethod !== 'CASH') return;
        const cash = parseFloat(document.getElementById('inputCashWeb').value) || 0;
        const btn = document.getElementById('btnConfirmPayWeb');
        if(cash >= webTotalTagihan) { 
            document.getElementById('inputChangeWeb').value = formatRp(cash - webTotalTagihan); 
            btn.disabled = false; 
        } else { 
            document.getElementById('inputChangeWeb').value = 'Uang Kurang'; 
            btn.disabled = true; 
        }
    }

    function setExactCashWeb() { 
        document.getElementById('inputCashWeb').value = webTotalTagihan; 
        calculateChangeWeb(); 
    }

    document.getElementById('inputPaymentProofWeb')?.addEventListener('change', function(e) {
        const file = e.target.files[0]; if (!file) return;
        const reader = new FileReader(); reader.onload = function(event) {
            const img = new Image(); img.onload = function() {
                const canvas = document.createElement('canvas'); const MAX_WIDTH = 600; const MAX_HEIGHT = 600; let width = img.width; let height = img.height;
                if (width > height) { if (width > MAX_WIDTH) { height *= MAX_WIDTH / width; width = MAX_WIDTH; } } else { if (height > MAX_HEIGHT) { width *= MAX_HEIGHT / height; height = MAX_HEIGHT; } }
                canvas.width = width; canvas.height = height; const ctx = canvas.getContext('2d'); ctx.drawImage(img, 0, 0, width, height);
                document.getElementById('proofBase64Web').value = canvas.toDataURL('image/jpeg', 0.6); 
            }; img.src = event.target.result;
        }; reader.readAsDataURL(file);
    });

    function submitWebPayment() {
        let bankInfo = '';
        if (webCurrentMethod === 'TRANSFER') {
            const selectedBank = document.querySelector('input[name="bankTransferWeb"]:checked');
            if(selectedBank) bankInfo = selectedBank.value;
        }
        executeCompleteOrderApi(webActiveOrderId, webCurrentMethod, document.getElementById('proofBase64Web').value, bankInfo);
    }

    async function executeCompleteOrderApi(id, method, proofBase64 = null, bank = null) {
        closePaymentModalWeb(); 
        Swal.fire({
            title: 'Memproses...', text: 'Mencatat Mutasi: ' + method, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }
        });

        try {
            let payload = { id: id, payment_method: method };
            if (proofBase64) payload.payment_proof = proofBase64;
            if (bank) payload.bank = bank;

            const response = await fetch('../api/?action=complete_order', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' }, 
                body: JSON.stringify(payload) 
            });
            
            const res = await response.json();
            
            if (res.status === 'success') { 
                Swal.fire({ icon: 'success', title: 'Mutasi Berhasil!', confirmButtonColor: '#4f46e5' }); 
                fetchActiveOrders(); 
            } else { 
                Swal.fire('Gagal', res.message, 'error'); 
            }
        } catch (error) { 
            Swal.fire('Error', 'Gagal memproses ke server.', 'error'); 
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchWebSettings();
        generateDays();
    });
</script>