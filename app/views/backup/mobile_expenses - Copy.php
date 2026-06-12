<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pengeluaran - Yori App</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; } 
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased pb-24">

    <!-- Header Dashboard (Aksen Oranye) -->
    <div class="bg-white px-5 py-4 border-b border-slate-200 sticky top-0 z-40 flex items-center justify-between shadow-sm">
        <div>
            <h1 class="text-lg font-black text-slate-800 leading-none">Pengeluaran</h1>
            <p class="text-[10px] text-orange-500 font-bold uppercase tracking-wider mt-1">BIAYA OPERASIONAL</p>
        </div>
        <button onclick="openExpenseModal()" class="w-10 h-10 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-full flex justify-center items-center shadow-sm active:scale-90 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
        </button>
    </div>

    <!-- Summary Box -->
    <div class="p-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between border-l-4 border-l-orange-500">
            <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Pengeluaran</p>
                <p class="text-2xl font-black text-red-500 mt-1" id="sumTotal">Rp 0</p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-full flex justify-center items-center text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>
    </div>

    <!-- Daftar Pengeluaran Cards -->
    <div class="px-4 pb-4 flex flex-col gap-3" id="expenseList">
        <div class="text-center py-10 text-orange-500 font-bold text-sm animate-pulse">Memuat data...</div>
    </div>

    <!-- PWA Bottom Navigation Modular -->
    <?php include 'components/bottomnav.php'; ?>

    <!-- MODAL FORM PENGELUARAN (Bottom Sheet) -->
    <div id="expenseModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-end justify-center">
        <div class="bg-white w-full rounded-t-[2rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 flex flex-col max-h-[90vh]" id="expenseModalContent">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-black text-slate-800" id="modalTitle">Catat Pengeluaran</h3>
                <button onclick="closeExpenseModal()" class="text-slate-400 bg-slate-100 p-2 rounded-full active:scale-90 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1 space-y-4 bg-slate-50">
                <input type="hidden" id="formId">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="formDate" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-500 text-sm font-bold text-slate-700 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="formCategory" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-500 text-sm font-bold text-orange-600 shadow-sm">
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
                        <input type="number" id="formAmount" class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-500 font-black text-xl text-slate-800 shadow-sm" placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Keterangan</label>
                    <textarea id="formDesc" rows="2" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-500 text-sm shadow-sm" placeholder="Contoh: Token listrik, ATK..."></textarea>
                </div>
            </div>
            
            <div class="p-6 bg-white border-t border-slate-200">
                <button id="btnSaveExpense" onclick="saveExpense()" class="w-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-black py-4 rounded-xl text-lg shadow-lg transition-all">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
<?php include 'components/bottomnav.php'; ?>
    <script>
        const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true });
        
        let expensesData = [];

        function getTodayDate() {
            const today = new Date();
            // Penyesuaian zona waktu lokal untuk input kalender
            today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
            return today.toISOString().split('T')[0];
        }

        async function loadExpenses() {
            const container = document.getElementById('expenseList');
            try {
                const res = await fetch('../api/?action=get_expenses');
                const result = await res.json();
                
                if (result.status === 'success') {
                    expensesData = result.data;
                    container.innerHTML = '';
                    let totalAmount = 0;
                    
                    if(expensesData.length === 0) {
                        container.innerHTML = `<div class="bg-white p-8 rounded-2xl text-center border border-slate-200 shadow-sm"><span class="text-4xl block mb-3 opacity-50">📂</span><p class="text-slate-500 font-bold text-sm">Belum ada catatan pengeluaran.</p></div>`;
                        document.getElementById('sumTotal').innerText = 'Rp 0';
                        return;
                    }

                    expensesData.forEach(exp => {
                        totalAmount += parseFloat(exp.amount);
                        
                        container.innerHTML += `
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-500"></div>
                                
                                <div class="flex justify-between items-start pl-1">
                                    <div>
                                        <span class="text-[9px] font-black uppercase px-2 py-1 rounded-md tracking-wider border bg-orange-50 text-orange-600 border-orange-200">${exp.category}</span>
                                        <p class="text-xs text-slate-500 font-medium mt-1.5">${exp.expense_date}</p>
                                    </div>
                                    <p class="font-black text-base text-red-500">${formatRp(exp.amount)}</p>
                                </div>
                                
                                <div class="pl-1 mt-2 mb-1">
                                    <p class="text-xs text-slate-600 font-medium leading-relaxed line-clamp-2">${exp.description || '<i class="text-slate-400">Tidak ada keterangan</i>'}</p>
                                </div>
                                
                                <div class="pl-1 mt-3 pt-3 border-t border-slate-100 flex gap-2">
                                    <button onclick="editExpense(${exp.id})" class="flex-1 bg-slate-50 text-slate-600 font-bold py-2.5 rounded-xl text-xs active:scale-95 transition-transform border border-slate-200 flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </button>
                                    <button onclick="deleteExpense(${exp.id})" class="flex-1 bg-red-50 text-red-600 font-bold py-2.5 rounded-xl text-xs active:scale-95 transition-transform border border-red-100 flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>`;
                    });

                    document.getElementById('sumTotal').innerText = formatRp(totalAmount);
                }
            } catch (error) { console.error(error); }
        }

        // --- KONTROL MODAL ---
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
            const exp = expensesData.find(e => e.id == id);
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

        // --- AKSI API ---
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
                    loadExpenses(); 
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
                            loadExpenses(); 
                        }
                    } catch(e) {}
                }
            });
        }

        document.addEventListener('DOMContentLoaded', loadExpenses);
    </script>
</body>
</html>