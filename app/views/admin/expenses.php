<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Pengeluaran Operasional</h2>
                <p class="text-sm text-slate-500">Catat biaya listrik, gaji, ATK, dan beban operasional lainnya.</p>
            </div>
            <button onclick="openExpenseModal()" class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all font-bold">
                + Catat Pengeluaran
            </button>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Keterangan</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">Nominal</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="expenseTableBody" class="divide-y divide-slate-200 dark:divide-slate-700">
                        <tr><td colspan="5" class="text-center py-8 text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FORM PENGELUARAN -->
<div id="expenseModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all scale-95" id="expenseModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100" id="modalTitle">Catat Pengeluaran</h3>
            <button onclick="closeExpenseModal()" class="text-slate-400 hover:text-red-500 transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <input type="hidden" id="formId">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" id="formDate" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl outline-none bg-slate-50 dark:bg-slate-900 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select id="formCategory" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl outline-none bg-slate-50 dark:bg-slate-900 dark:text-white font-bold text-indigo-700">
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
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="formAmount" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl outline-none dark:text-white font-bold text-lg" placeholder="Contoh: 150000">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Keterangan Catatan</label>
                <textarea id="formDesc" rows="3" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl outline-none dark:text-white text-sm" placeholder="Contoh: Beli kertas thermal 1 slop, bayar token listrik bulanan..."></textarea>
            </div>

            <button onclick="saveExpense()" id="btnSaveExpense" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl mt-2 transition-colors shadow-sm">Simpan Data</button>
        </div>
    </div>
</div>

<script>
    const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true });
    
    let expensesData = [];

    async function loadExpenses() {
        try {
            const res = await fetch('../api/?action=get_expenses');
            const result = await res.json();
            const tbody = document.getElementById('expenseTableBody');
            
            if (result.status === 'success') {
                expensesData = result.data;
                tbody.innerHTML = '';
                
                if(expensesData.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400">Belum ada catatan pengeluaran.</td></tr>';
                    return;
                }

                expensesData.forEach(exp => {
                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-700 dark:text-slate-300">${exp.expense_date}</td>
                            <td class="px-6 py-4"><span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2 py-1 rounded text-xs font-bold border border-indigo-200 dark:border-indigo-800">${exp.category}</span></td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 line-clamp-2">${exp.description || '-'}</td>
                            <td class="px-6 py-4 text-right font-black text-red-600 dark:text-red-400">${formatRp(exp.amount)}</td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="editExpense(${exp.id})" class="text-indigo-500 hover:text-indigo-700 bg-indigo-50 dark:bg-indigo-900/30 p-1.5 rounded-lg transition-colors mr-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                <button onclick="deleteExpense(${exp.id})" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/30 p-1.5 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </td>
                        </tr>`;
                });
            }
        } catch (error) { console.error(error); }
    }

    function getTodayDate() {
        const today = new Date();
        return today.toISOString().split('T')[0];
    }

    function openExpenseModal() {
        document.getElementById('modalTitle').innerText = 'Catat Pengeluaran';
        document.getElementById('formId').value = '';
        document.getElementById('formDate').value = getTodayDate();
        document.getElementById('formCategory').value = '';
        document.getElementById('formAmount').value = '';
        document.getElementById('formDesc').value = '';
        
        const modal = document.getElementById('expenseModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('expenseModalContent').classList.remove('scale-95'); }, 10);
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
        
        const modal = document.getElementById('expenseModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('expenseModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeExpenseModal() {
        document.getElementById('expenseModal').classList.add('opacity-0'); document.getElementById('expenseModalContent').classList.add('scale-95');
        setTimeout(() => { document.getElementById('expenseModal').classList.add('hidden'); }, 300);
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
            if(data.status === 'success') { Toast.fire({icon: 'success', title: data.message}); closeExpenseModal(); loadExpenses(); } 
            else { Swal.fire('Gagal', data.message, 'error'); }
        } catch(e) { Swal.fire('Error', 'Kesalahan jaringan', 'error'); }
        finally { btn.disabled = false; btn.innerText = 'Simpan Data'; }
    }

    function deleteExpense(id) {
        Swal.fire({
            title: 'Hapus Data?', text: "Data pengeluaran akan dihapus permanen!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const res = await fetch('../api/?action=delete_expense', { method: 'POST', body: JSON.stringify({ id: id }) });
                    const data = await res.json();
                    if(data.status === 'success') { Toast.fire({icon: 'success', title: data.message}); loadExpenses(); }
                } catch(e) {}
            }
        });
    }

    document.addEventListener('DOMContentLoaded', loadExpenses);
</script>