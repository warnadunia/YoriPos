<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
   <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Daftar Kategori</h3>
        <button onclick="openModal()" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Kategori
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-1">
        <div class="overflow-x-auto h-full">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-16">ID</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Kategori</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody" class="bg-white divide-y divide-slate-200">
                    <!-- Data AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Kategori -->
<div id="catModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden transform transition-all scale-95" id="catModalContent">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800" id="catModalTitle">Tambah Kategori</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="catForm" onsubmit="saveCategory(event)">
            <div class="p-6">
                <input type="hidden" id="catId">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="catName" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Cth: Minuman Dingin">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" id="catBtnSave" class="px-5 py-2 border border-transparent rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentCategories = [];

    async function loadCategories() {
        const tbody = document.getElementById('categoryTableBody');
        tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-4 text-center text-slate-400">Memuat data...</td></tr>`;
        try {
            const response = await fetch('/yoripos/api/?action=get_categories');
            const result = await response.json();
            if (result.status === 'success') {
                currentCategories = result.data;
                tbody.innerHTML = '';
                if (currentCategories.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-4 text-center text-slate-500">Belum ada kategori.</td></tr>`; return;
                }
                currentCategories.forEach(cat => {
                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">${cat.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-800">${cat.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium opacity-50 group-hover:opacity-100 transition-opacity">
                                <button onclick="editCategory(${cat.id})" class="text-indigo-600 hover:bg-indigo-100 p-2 rounded-lg mr-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                <button onclick="deleteCategory(${cat.id})" class="text-red-600 hover:bg-red-100 p-2 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </td>
                        </tr>`;
                });
            }
        } catch (error) { tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-4 text-center text-red-500">Gagal mengambil data.</td></tr>`; }
    }

    async function saveCategory(event) {
        event.preventDefault();
        const btn = document.getElementById('catBtnSave');
        const ogText = btn.innerText; btn.innerText = 'Menyimpan...'; btn.disabled = true;

        try {
            const response = await fetch('/yoripos/api/?action=save_category', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: document.getElementById('catId').value, name: document.getElementById('catName').value })
            });
            const result = await response.json();
            if (result.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: result.message, timer: 1500, showConfirmButton: false });
                closeModal(); loadCategories();
            } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message }); }
        } catch (error) { Swal.fire({ icon: 'error', title: 'Error', text: 'Jaringan bermasalah.' }); }
        finally { btn.innerText = ogText; btn.disabled = false; }
    }

    function editCategory(id) {
        const cat = currentCategories.find(c => c.id == id);
        if (!cat) return;
        document.getElementById('catModalTitle').innerText = 'Edit Kategori';
        document.getElementById('catId').value = cat.id;
        document.getElementById('catName').value = cat.name;
        openModal();
    }

    function deleteCategory(id) {
        Swal.fire({ title: 'Hapus kategori?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, Hapus!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch('/yoripos/api/?action=delete_category', { method: 'POST', body: JSON.stringify({ id: id }) });
                const res = await response.json();
                if (res.status === 'success') { loadCategories(); Swal.fire('Terhapus!', '', 'success'); } 
                else { Swal.fire('Gagal', res.message, 'error'); }
            }
        });
    }

    const modal = document.getElementById('catModal');
    const modalContent = document.getElementById('catModalContent');
    function openModal() {
        if (!document.getElementById('catId').value) { document.getElementById('catModalTitle').innerText = 'Tambah Kategori'; document.getElementById('catForm').reset(); }
        modal.classList.remove('hidden'); setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); }, 10);
    }
    function closeModal() {
        modal.classList.add('opacity-0'); modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); document.getElementById('catForm').reset(); document.getElementById('catId').value = ''; }, 300);
    }

    document.addEventListener('DOMContentLoaded', loadCategories);
</script>