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
    <title><?= $storeName ?> - Pengeluaran</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#fdfaf2] text-slate-800 antialiased selection:bg-amber-100 selection:text-amber-900 pb-32 min-h-screen font-sans"> 

    <div class="px-5 pt-7 pb-4 flex justify-between items-center">
        <h1 class="text-[22px] font-black text-[#D9A426] tracking-tight">Pengeluaran</h1>
        
        <div class="flex items-center gap-2">
            <select id="filterYear" onchange="onYearChange()" class="bg-[#fef8e7] text-[#805e12] font-bold text-sm rounded-xl px-3 py-2.5 outline-none border border-transparent focus:border-[#D9A426] shadow-sm cursor-pointer appearance-none text-center min-w-[70px]">
                </select>
            
            <button onclick="openExpenseModal()" class="w-10 h-10 bg-[#D9A426] hover:bg-[#c29122] active:scale-95 rounded-xl flex justify-center items-center text-white transition-all shadow-md shadow-[#D9A426]/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            </button>
        </div>
    </div>

    <div class="px-4 mb-4">
        <div class="bg-white border border-white/60 rounded-[1.25rem] p-4 shadow-sm shadow-[#D9A426]/5">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#D9A426]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                Tren Biaya Operasional (12 Bulan)
            </p>
            <div class="h-36 w-full relative">
                <canvas id="chart12Bulan"></canvas>
            </div>
        </div>
    </div>

    <div class="px-4 mb-5">
        <div class="bg-[#D9A426] rounded-[1.25rem] p-5 relative text-white shadow-lg shadow-[#D9A426]/20 flex justify-between items-center overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
            
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-amber-100 uppercase tracking-widest mb-1">Total Biaya Tahunan</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-sm font-bold text-amber-100">Rp</span>
                    <span class="text-[28px] font-black tracking-tight leading-none" id="summaryYearTotal">0</span>
                </div>
            </div>
            
            <div class="bg-[#c29122] rounded-xl p-3 flex flex-col justify-center items-center min-w-[75px] shadow-inner relative z-10 border border-white/10">
                <span class="font-black text-[22px] leading-none my-0.5" id="summaryYearCount">0</span>
                <p class="text-[10px] text-amber-100 mt-1 font-bold">Data</p>
            </div>
        </div>
    </div>

    <div class="px-4 mb-5">
        <div class="flex overflow-x-auto gap-3 pb-3 no-scrollbar" id="monthSlider">
            </div>
    </div>

    <div class="px-5 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-[#D9A426]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        <h2 class="text-sm font-bold text-[#805e12]">Daftar Pengeluaran</h2>
    </div>

    <div class="px-4 space-y-3 pb-12" id="expenseList">
        <div class="text-center py-10">
            <svg class="animate-spin h-8 w-8 text-[#D9A426] mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-[#b89547] font-medium">Memuat data...</p>
        </div>
    </div>

    <div id="expenseModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-end justify-center">
        <div class="bg-white w-full rounded-t-[2rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 flex flex-col max-h-[90vh]" id="expenseModalContent">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-black text-slate-800" id="modalTitle">Catat Pengeluaran</h3>
                <button onclick="closeExpenseModal()" class="text-slate-400 bg-slate-100 p-2 rounded-full active:scale-90 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1 space-y-4 bg-[#fdfaf2]">
                <input type="hidden" id="formId">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="formDate" class="w-full px-4 py-3 bg-white border border-[#f5e4ba] rounded-xl outline-none focus:ring-2 focus:ring-[#D9A426] text-sm font-bold text-slate-700 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="formCategory" class="w-full px-4 py-3 bg-white border border-[#f5e4ba] rounded-xl outline-none focus:ring-2 focus:ring-[#D9A426] text-sm font-bold text-[#D9A426] shadow-sm">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Listrik & Air">Listrik & Air</option>
                        <option value="Internet & Telepon">Internet & Telepon</option>
                        <option value="Gaji & Bonus">Gaji & Bonus</option>
                        <option value="Kertas Struk / ATK">Kertas Struk / ATK</option>
                        <option value="Pemeliharaan & Aset">Pemeliharaan & Aset</option>
                        <option value="Sewa Tempat">Sewa Tempat</option>
                        <option value="Marketing & Promo">Marketing & Promo</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-black">Rp</span>
                        <input type="number" id="formAmount" class="w-full pl-11 pr-4 py-3 bg-white border border-[#f5e4ba] rounded-xl outline-none focus:ring-2 focus:ring-[#D9A426] font-black text-xl text-slate-800 shadow-sm" placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Keterangan</label>
                    <textarea id="formDesc" rows="2" class="w-full px-4 py-3 bg-white border border-[#f5e4ba] rounded-xl outline-none focus:ring-2 focus:ring-[#D9A426] text-sm shadow-sm" placeholder="Contoh: Token listrik, ATK..."></textarea>
                </div>
            </div>
            
            <div class="p-6 bg-white border-t border-slate-200">
                <button id="btnSaveExpense" onclick="saveExpense()" class="w-full bg-[#D9A426] hover:bg-[#c29122] active:scale-95 text-white font-black py-4 rounded-xl text-lg shadow-lg shadow-[#D9A426]/30 transition-all">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/components/bottomnav.php'; ?>

    <script>
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        const formatNominalOnly = (number) => new Intl.NumberFormat('id-ID').format(number);
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
        
        let allExpenses = [];
        let myChartInstance = null;
        
        const now = new Date();
        let selectedYear = now.getFullYear();
        let selectedMonth = String(now.getMonth() + 1).padStart(2, '0'); // 01 - 12
        
        // Setup Tahun Dropdown
        const yearSelect = document.getElementById('filterYear');
        for (let y = selectedYear + 1; y >= selectedYear - 3; y--) {
            yearSelect.innerHTML += `<option value="${y}" ${y === selectedYear ? 'selected' : ''}>${y}</option>`;
        }

        // --- FETCH DATA ---
        async function fetchExpensesData() {
            try {
                const res = await fetch('../api/?action=get_expenses');
                const result = await res.json();
                
                if (result.status === 'success') {
                    allExpenses = result.data;
                    updateUI();
                }
            } catch (err) {
                console.error("Gagal load data", err);
                document.getElementById('expenseList').innerHTML = `<p class="text-center text-red-500 py-10">Gagal memuat data. Periksa koneksi.</p>`;
            }
        }

        function onYearChange() {
            selectedYear = parseInt(document.getElementById('filterYear').value);
            updateUI();
        }

        function updateUI() {
            updateYearSummary();
            buildMonthSlider();
            renderExpenses();
            render12MonthChart();
        }

        function updateYearSummary() {
            let total = 0, count = 0;
            allExpenses.forEach(e => {
                let dStr = e.expense_date || e.created_at || '';
                if(dStr.startsWith(String(selectedYear))) {
                    total += parseFloat(e.amount || 0);
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
                
                let mTotal = 0, mCount = 0;
                allExpenses.forEach(e => {
                    let dStr = e.expense_date || e.created_at || '';
                    if (dStr.startsWith(prefix)) {
                        mTotal += parseFloat(e.amount || 0);
                        mCount++;
                    }
                });
                
                let isActive = (mStr === selectedMonth);
                let baseClass = isActive 
                    ? 'bg-[#D9A426] text-white shadow-md shadow-[#D9A426]/30 border-transparent' 
                    : 'bg-white text-[#b89547] border border-[#f5e4ba] hover:bg-[#fef8e7]';

                let nameColor = isActive ? 'text-amber-100' : 'text-slate-500';
                let priceColor = isActive ? 'text-white' : 'text-[#D9A426]';

                slider.innerHTML += `
                    <div onclick="selectMonth('${mStr}')" class="min-w-[120px] flex flex-col p-3 rounded-2xl cursor-pointer transition-all duration-200 active:scale-95 border ${baseClass}">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[12px] font-bold ${nameColor}">${monthNames[i-1]}</span>
                            <span class="text-[10px] font-bold bg-black/5 px-1.5 py-0.5 rounded">${mCount} data</span>
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
            renderExpenses();
        }

        function renderExpenses() {
            const listContainer = document.getElementById('expenseList');
            const prefix = `${selectedYear}-${selectedMonth}`;
            
            const filtered = allExpenses.filter(e => {
                let dStr = e.expense_date || e.created_at || '';
                return dStr.startsWith(prefix);
            });
            
            if (filtered.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-10 px-5 bg-white rounded-2xl border border-[#f5e4ba] border-dashed">
                        <div class="w-14 h-14 bg-[#fef8e7] rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-[#D9A426]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-[13px] font-bold text-[#805e12]">Tidak Ada Pengeluaran</p>
                        <p class="text-[11px] text-[#b89547] mt-1">Bulan ini belum ada catatan biaya operasional.</p>
                    </div>`;
                return;
            }

            let html = '';
            filtered.forEach(exp => {
                html += `
                    <div class="bg-white border border-[#f5e4ba] shadow-sm rounded-2xl p-4 mb-3 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#D9A426]"></div>
                        
                        <div class="flex justify-between items-start cursor-pointer active:opacity-70 transition-opacity pl-2" onclick="toggleExpand(${exp.id})">
                            <div>
                                <h3 class="font-black text-[14px] text-slate-800 mb-0.5">${exp.category}</h3>
                                <div class="flex items-center gap-1.5 text-[10px] text-[#b89547] font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    ${exp.expense_date}
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-1.5 mt-0.5">
                                <p class="font-black text-[15px] text-[#D9A426]">${formatRupiah(exp.amount)}</p>
                                <svg id="icon-${exp.id}" class="w-4 h-4 text-[#b89547] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <div id="expand-${exp.id}" class="hidden pl-2 mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-600 font-medium leading-relaxed bg-[#fdfaf2] p-2.5 rounded-xl border border-[#f5e4ba]/50 mb-3">
                                ${exp.description || '<i class="text-slate-400">Tidak ada keterangan spesifik.</i>'}
                            </p>
                            
                            <div class="flex gap-2">
                                <button onclick="editExpense(${exp.id})" class="flex-1 bg-white hover:bg-[#fdfaf2] text-[#805e12] font-bold py-2.5 rounded-xl text-xs active:scale-95 transition-transform border border-[#f5e4ba] flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </button>
                                <button onclick="deleteExpense(${exp.id})" class="flex-1 bg-red-50 text-red-600 font-bold py-2.5 rounded-xl text-xs active:scale-95 transition-transform border border-red-100 flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
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

        // --- KONTROL MODAL ---
        function getTodayDate() {
            const today = new Date();
            today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
            return today.toISOString().split('T')[0];
        }

        function openExpenseModal() {
            document.getElementById('modalTitle').innerText = 'Catat Pengeluaran';
            document.getElementById('formId').value = '';
            document.getElementById('formDate').value = getTodayDate();
            document.getElementById('formCategory').value = '';
            document.getElementById('formAmount').value = '';
            document.getElementById('formDesc').value = '';
            
            const m = document.getElementById('expenseModal');
            const c = document.getElementById('expenseModalContent');
            m.classList.remove('hidden');
            setTimeout(() => { c.classList.remove('translate-y-full'); }, 10);
        }

        function editExpense(id) {
            const exp = allExpenses.find(e => e.id == id);
            if(!exp) return;

            document.getElementById('modalTitle').innerText = 'Edit Pengeluaran';
            document.getElementById('formId').value = exp.id;
            document.getElementById('formDate').value = exp.expense_date;
            document.getElementById('formCategory').value = exp.category;
            document.getElementById('formAmount').value = exp.amount;
            document.getElementById('formDesc').value = exp.description;
            
            const m = document.getElementById('expenseModal');
            const c = document.getElementById('expenseModalContent');
            m.classList.remove('hidden');
            setTimeout(() => { c.classList.remove('translate-y-full'); }, 10);
        }

        function closeExpenseModal() {
            const m = document.getElementById('expenseModal');
            const c = document.getElementById('expenseModalContent');
            c.classList.add('translate-y-full');
            setTimeout(() => { m.classList.add('hidden'); }, 300);
        }

        async function saveExpense() {
            const payload = {
                id: document.getElementById('formId').value,
                expense_date: document.getElementById('formDate').value,
                category: document.getElementById('formCategory').value,
                amount: document.getElementById('formAmount').value,
                description: document.getElementById('formDesc').value
            };

            if(!payload.expense_date || !payload.category || !payload.amount) {
                Swal.fire('Peringatan', 'Tanggal, Kategori, dan Nominal wajib diisi!', 'warning'); return;
            }

            const btn = document.getElementById('btnSaveExpense');
            btn.disabled = true; btn.innerText = 'Menyimpan...';

            try {
                const res = await fetch('../api/?action=save_expense', { method: 'POST', body: JSON.stringify(payload) });
                const data = await res.json();
                if(data.status === 'success') { 
                    Toast.fire({icon: 'success', title: data.message}); 
                    closeExpenseModal(); 
                    fetchExpensesData(); 
                } else { 
                    Swal.fire('Gagal', data.message, 'error'); 
                }
            } catch(e) { Swal.fire('Error', 'Kesalahan jaringan', 'error'); }
            finally { btn.disabled = false; btn.innerText = 'Simpan Data'; }
        }

        function deleteExpense(id) {
            Swal.fire({
                title: 'Hapus Data?', text: "Data pengeluaran akan dihapus permanen!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!',
                customClass: { popup: 'rounded-3xl' }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch('../api/?action=delete_expense', { method: 'POST', body: JSON.stringify({ id: id }) });
                        const data = await res.json();
                        if(data.status === 'success') { 
                            Toast.fire({icon: 'success', title: data.message}); 
                            fetchExpensesData(); 
                        }
                    } catch(e) {}
                }
            });
        }

        function render12MonthChart() {
            let labelBulan = [];
            let prefixBulan = [];
            const namaBulanPendek = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            
            for (let i = 11; i >= 0; i--) {
                let targetDate = new Date(selectedYear, parseInt(selectedMonth) - 1 - i, 1);
                let namaB = namaBulanPendek[targetDate.getMonth()];
                let tahunB = String(targetDate.getFullYear()).substring(2);
                
                labelBulan.push(`${namaB} '${tahunB}`);
                
                let pMonth = String(targetDate.getMonth() + 1).padStart(2, '0');
                prefixBulan.push(`${targetDate.getFullYear()}-${pMonth}`);
            }

            let dataBiaya = prefixBulan.map(prefix => {
                let total = 0;
                allExpenses.forEach(e => {
                    let dStr = e.expense_date || e.created_at || '';
                    if (dStr.startsWith(prefix)) {
                        total += parseFloat(e.amount || 0);
                    }
                });
                return total;
            });

            if (myChartInstance) { myChartInstance.destroy(); }

            const ctx = document.getElementById('chart12Bulan').getContext('2d');
            myChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelBulan,
                    datasets: [{
                        label: 'Pengeluaran',
                        data: dataBiaya,
                        backgroundColor: '#D9A426',
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
                        x: { grid: { display: false }, ticks: { font: { size: 9 }, color: '#b89547' } },
                        y: { grid: { color: '#f5e4ba' }, ticks: { display: false } }
                    }
                }
            });
        }

        // Init Load
        fetchExpensesData();
    </script>
</body>
</html>