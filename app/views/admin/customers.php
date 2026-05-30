<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Master Pelanggan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola data konsumen untuk keperluan Piutang & Membership.</p>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                    <input type="text" id="searchCustomer" onkeyup="filterCustomers()" placeholder="Cari nama atau no. WA..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm">
                </div>
                <button onclick="openCustomerModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl transition duration-200 shadow-sm flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Tambah
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Pelanggan</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">No. WhatsApp</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat Lengkap</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Terdaftar</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody" class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <tr><td colspan="5" class="py-8 text-center text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="formCustomerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-95" id="formCustomerContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100" id="modalCustomerTitle">Tambah Pelanggan</h3>
            <button onclick="closeCustomerModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <input type="hidden" id="custId">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="custName" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-1 focus:ring-indigo-500 outline-none text-sm dark:text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">No. WhatsApp</label>
                <input type="number" id="custPhone" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-1 focus:ring-indigo-500 outline-none text-sm dark:text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Alamat Lengkap</label>
                <textarea id="custAddress" rows="3" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-1 focus:ring-indigo-500 outline-none text-sm dark:text-slate-100"></textarea>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 flex gap-3">
            <button onclick="closeCustomerModal()" class="flex-1 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-bold py-2.5 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
            <button onclick="saveCustomer()" id="btnSaveCust" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition duration-200 shadow-sm">Simpan</button>
        </div>
    </div>
</div>

<script>
    let allCustomers = [];
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true });

    async function fetchCustomers() {
        try {
            const response = await fetch('/yoripos/api/?action=get_customers');
            const result = await response.json();
            if (result.status === 'success') {
                allCustomers = result.data;
                renderTable(allCustomers);
            }
        } catch (error) { console.error('Fetch error:', error); }
    }

    function renderTable(data) {
        const tbody = document.getElementById('customerTableBody');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada data pelanggan.</td></tr>'; return;
        }

        data.forEach(cust => {
            const dateStr = cust.created_at ? new Date(cust.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'}) : '-';
            tbody.innerHTML += `
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                    <td class="py-4 px-6 font-bold text-slate-800 dark:text-slate-200">${cust.name}</td>
                    <td class="py-4 px-6 text-sm text-slate-600 dark:text-slate-400 font-mono">${cust.phone || '-'}</td>
                    <td class="py-4 px-6 text-sm text-slate-600 dark:text-slate-400 max-w-xs truncate" title="${cust.address || ''}">${cust.address || '-'}</td>
                    <td class="py-4 px-6 text-sm text-slate-500">${dateStr}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick='editCustomer(${JSON.stringify(cust)})' class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                            <button onclick="deleteCustomer(${cust.id})" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                    </td>
                </tr>`;
        });
    }

    function filterCustomers() {
        const keyword = document.getElementById('searchCustomer').value.toLowerCase();
        const filtered = allCustomers.filter(c => c.name.toLowerCase().includes(keyword) || (c.phone && c.phone.includes(keyword)));
        renderTable(filtered);
    }

    function openCustomerModal() {
        document.getElementById('custId').value = '';
        document.getElementById('custName').value = '';
        document.getElementById('custPhone').value = '';
        document.getElementById('custAddress').value = '';
        document.getElementById('modalCustomerTitle').innerText = 'Tambah Pelanggan';
        
        const modal = document.getElementById('formCustomerModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('formCustomerContent').classList.remove('scale-95'); }, 10);
    }

    function editCustomer(cust) {
        openCustomerModal();
        document.getElementById('modalCustomerTitle').innerText = 'Edit Pelanggan';
        document.getElementById('custId').value = cust.id;
        document.getElementById('custName').value = cust.name;
        document.getElementById('custPhone').value = cust.phone;
        document.getElementById('custAddress').value = cust.address;
    }

    function closeCustomerModal() {
        const modal = document.getElementById('formCustomerModal');
        modal.classList.add('opacity-0'); document.getElementById('formCustomerContent').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    async function saveCustomer() {
        const id = document.getElementById('custId').value;
        const name = document.getElementById('custName').value;
        const phone = document.getElementById('custPhone').value;
        const address = document.getElementById('custAddress').value;

        if(!name) { Toast.fire({ icon: 'warning', title: 'Nama pelanggan wajib diisi!' }); return; }

        try {
            const response = await fetch('/yoripos/api/?action=save_customer', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, name, phone, address })
            });
            const result = await response.json();
            if (result.status === 'success') {
                Toast.fire({ icon: 'success', title: result.message });
                closeCustomerModal(); fetchCustomers();
            } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message }); }
        } catch (error) { console.error(error); }
    }

    function deleteCustomer(id) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('/yoripos/api/?action=delete_customer', {
                        method: 'POST', headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    });
                    const res = await response.json();
                    if (res.status === 'success') {
                        Toast.fire({ icon: 'success', title: res.message }); fetchCustomers();
                    } else { Swal.fire({ icon: 'error', title: 'Gagal', text: res.message }); }
                } catch (error) { console.error(error); }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', fetchCustomers);
</script>