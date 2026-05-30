<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
   <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Master Menu & Resep</h2>
        <button onclick="openMenuModal()" class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all font-bold">
            + Tambah Menu POS
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex-1">
        <div class="overflow-x-auto h-full">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">SKU</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Nama Menu</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Kategori</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">Harga Jual</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Aksi & Resep</th>
                    </tr>
                </thead>
                <tbody id="menuTableBody" class="divide-y divide-slate-200 dark:divide-slate-700">
                    <tr><td colspan="5" class="text-center py-8 text-slate-400">Memuat...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="menuModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all scale-95" id="menuModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100" id="modalTitle">Tambah Menu Baru</h3>
            <button onclick="closeMenuModal()" class="text-slate-400 hover:text-red-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <input type="hidden" id="formId">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">SKU / Kode</label>
                    <input type="text" id="formSku" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl outline-none bg-slate-50 dark:bg-slate-900 dark:text-white" placeholder="Opsional">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                    <select id="formCategory" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl outline-none bg-slate-50 dark:bg-slate-900 dark:text-white">
                        </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Foto Menu <span class="font-normal text-slate-400">(Opsional)</span></label>
                <div class="flex items-center gap-4">
                    <input type="file" id="menuImageFile" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 cursor-pointer border border-slate-200 dark:border-slate-600 rounded-xl">
                    <input type="hidden" id="menuImageUrl" value="">
                    
                    <div id="imagePreviewBox" class="hidden w-12 h-12 rounded-xl border border-slate-200 overflow-hidden flex-shrink-0 shadow-sm">
                        <img id="imagePreviewImg" src="" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                <input type="text" id="formName" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl outline-none dark:text-white" placeholder="Cth: Caffe Latte">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="formPrice" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl outline-none dark:text-white" placeholder="Cth: 25000">
            </div>

            <button onclick="saveMenu()" id="btnSaveMenu" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl mt-2 transition-colors">Simpan Menu</button>
        </div>
    </div>
</div>

<div id="recipeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all scale-95" id="recipeModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-indigo-600">
            <h3 class="text-lg font-bold text-white" id="recipeModalTitle">Resep: -</h3>
            <button onclick="closeRecipeModal()" class="text-white/70 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        
        <div class="p-5 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Tambahkan Bahan Baku</label>
            <div class="flex gap-2">
                <select id="recipeMaterialSelect" onchange="updateUnitLabel()" class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg outline-none bg-white dark:bg-slate-800 dark:text-white text-sm">
                    <option value="">-- Pilih Bahan Baku --</option>
                </select>
                <div class="relative w-28">
                    <input type="number" id="recipeQty" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg outline-none bg-white dark:bg-slate-800 dark:text-white text-sm" placeholder="Qty">
                    <span id="recipeUnitLabel" class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">...</span>
                </div>
                <button onclick="addMaterialToTemp()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg font-bold shadow-sm">+</button>
            </div>
        </div>

        <div class="p-5 overflow-y-auto max-h-60">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase border-b border-slate-200 dark:border-slate-700">
                    <tr><th>Bahan Baku</th><th class="text-center">Kebutuhan</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody id="recipeTableBody" class="divide-y divide-slate-100 dark:divide-slate-700">
                </tbody>
            </table>
        </div>

        <div class="p-5 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
            <button onclick="saveRecipeToDB()" id="btnSaveRecipe" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-md transition-colors">Simpan Komposisi Resep</button>
        </div>
    </div>
</div>

<script>
    const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
    
    let allMenus = [];
    let allMaterials = []; 
    let currentActiveMenuId = null;
    let tempRecipeMaterials = []; 

    // --- INISIALISASI DATA ---
    async function loadData() {
        fetchCategoriesForSelect(); // Tarik dropdown kategori
        try {
            const response = await fetch('../api/?action=get_products');
            const result = await response.json();
            if (result.status === 'success') {
                allMenus = result.data.filter(p => p.type === 'produk_jual');
                allMaterials = result.data.filter(p => p.type === 'bahan_baku');
                renderMenuTable();
                populateMaterialSelect();
            }
        } catch (error) { 
            document.getElementById('menuTableBody').innerHTML = '<tr><td colspan="5" class="text-center py-8 text-red-500 font-bold">Gagal mengambil data dari server!</td></tr>';
        }
    }

    async function fetchCategoriesForSelect() {
        const select = document.getElementById('formCategory');
        try {
            const response = await fetch('../api/?action=get_categories');
            const result = await response.json();
            if (result.status === 'success') {
                select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                result.data.forEach(cat => { select.innerHTML += `<option value="${cat.name}">${cat.name}</option>`; });
            }
        } catch (error) { console.error('Gagal memuat kategori'); }
    }

    function renderMenuTable() {
        const tbody = document.getElementById('menuTableBody');
        tbody.innerHTML = '';
        
        if(allMenus.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-500">Belum ada Menu POS. Silakan tambah data.</td></tr>';
            return;
        }

        allMenus.forEach(menu => {
            tbody.innerHTML += `
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-mono text-slate-500">${menu.sku || '-'}</td>
                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100">${menu.name}</td>
                    <td class="px-6 py-4 text-sm text-slate-500"><span class="bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-md border border-slate-200 dark:border-slate-600">${menu.category || '-'}</span></td>
                    <td class="px-6 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400">${formatRp(menu.price_sell)}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="openRecipeModal(${menu.id}, '${menu.name}')" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-3 py-1.5 rounded-lg text-xs font-bold mr-2 shadow-sm transition-colors">🍳 Atur Resep</button>
                        <button onclick="editMenu(${menu.id})" class="text-indigo-500 hover:text-indigo-700 bg-indigo-50 dark:bg-indigo-900/30 p-1.5 rounded-lg transition-colors mr-1"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                        <button onclick="deleteMenu(${menu.id})" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/30 p-1.5 rounded-lg transition-colors"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </td>
                </tr>`;
        });
    }

    // --- UPLOAD GAMBAR KE VERCEL BLOB ---
    document.getElementById('menuImageFile').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            const src = URL.createObjectURL(e.target.files[0]);
            document.getElementById('imagePreviewImg').src = src;
            document.getElementById('imagePreviewBox').classList.remove('hidden');
        }
    });

    async function uploadToBlob(file) {
        const formData = new FormData(); formData.append('image', file);
        try {
            const res = await fetch('../api/?action=upload_blob', { method: 'POST', body: formData });
            const data = await res.json();
            if(data.status === 'success') return data.url;
            Swal.fire('Upload Gagal', data.message, 'error'); return '';
        } catch (error) { Swal.fire('Error', 'Gagal menghubungi server.', 'error'); return ''; }
    }

    // --- LOGIKA CRUD MENU ---
    function openMenuModal() {
        document.getElementById('formId').value = '';
        document.getElementById('formSku').value = '';
        document.getElementById('formName').value = '';
        document.getElementById('formPrice').value = '';
        document.getElementById('formCategory').value = '';
        document.getElementById('menuImageUrl').value = '';
        document.getElementById('menuImageFile').value = '';
        document.getElementById('imagePreviewBox').classList.add('hidden');
        document.getElementById('modalTitle').innerText = 'Tambah Menu Baru';
        
        const modal = document.getElementById('menuModal');
        modal.classList.remove('hidden'); setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('menuModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeMenuModal() {
        document.getElementById('menuModal').classList.add('opacity-0'); document.getElementById('menuModalContent').classList.add('scale-95');
        setTimeout(() => { document.getElementById('menuModal').classList.add('hidden'); }, 300);
    }

    function editMenu(id) {
        const menu = allMenus.find(m => m.id == id);
        if(!menu) return;

        document.getElementById('formId').value = menu.id;
        document.getElementById('formSku').value = menu.sku || '';
        document.getElementById('formName').value = menu.name;
        document.getElementById('formPrice').value = menu.price_sell;
        document.getElementById('formCategory').value = menu.category || '';
        document.getElementById('menuImageUrl').value = menu.image_url || '';
        document.getElementById('menuImageFile').value = '';
        
        if(menu.image_url) {
            document.getElementById('imagePreviewImg').src = menu.image_url;
            document.getElementById('imagePreviewBox').classList.remove('hidden');
        } else {
            document.getElementById('imagePreviewBox').classList.add('hidden');
        }

        document.getElementById('modalTitle').innerText = 'Edit Menu';
        document.getElementById('menuModal').classList.remove('hidden'); setTimeout(() => { document.getElementById('menuModal').classList.remove('opacity-0'); document.getElementById('menuModalContent').classList.remove('scale-95'); }, 10);
    }

    async function saveMenu() {
        const name = document.getElementById('formName').value;
        const price = document.getElementById('formPrice').value;
        if(!name || !price) { Toast.fire({icon: 'warning', title: 'Nama & Harga wajib diisi!'}); return; }

        const btn = document.getElementById('btnSaveMenu');
        const originalText = btn.innerText;
        btn.disabled = true;

        let finalImageUrl = document.getElementById('menuImageUrl').value;
        const fileInput = document.getElementById('menuImageFile');

        if(fileInput.files.length > 0) {
            btn.innerText = 'Mengunggah Foto...';
            const uploadedUrl = await uploadToBlob(fileInput.files[0]);
            if(uploadedUrl) { finalImageUrl = uploadedUrl; } 
            else { btn.innerText = originalText; btn.disabled = false; return; }
        }

        btn.innerText = 'Menyimpan Menu...';

        const payload = {
            id: document.getElementById('formId').value,
            sku: document.getElementById('formSku').value,
            name: name,
            price_sell: price,
            category: document.getElementById('formCategory').value,
            image_url: finalImageUrl,
            type: 'produk_jual', // Kunci sebagai Menu POS
            unit: 'porsi'
        };

        try {
            const res = await fetch('../api/?action=save_product', { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if(data.status === 'success') { Toast.fire({icon:'success', title: data.message}); closeMenuModal(); loadData(); }
            else { Swal.fire('Gagal', data.message, 'error'); }
        } catch(e) { Swal.fire('Error', 'Kesalahan jaringan', 'error'); }
        finally { btn.innerText = originalText; btn.disabled = false; }
    }

    function deleteMenu(id) {
        Swal.fire({
            title: 'Hapus Menu?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const res = await fetch('../api/?action=delete_product', {
                        method: 'POST', headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    });
                    const data = await res.json();
                    if(data.status === 'success') { Toast.fire({icon: 'success', title: data.message}); loadData(); } 
                    else { Swal.fire('Gagal', data.message, 'error'); }
                } catch(e) { Swal.fire('Error', 'Kesalahan jaringan', 'error'); }
            }
        });
    }

    // --- LOGIKA BUILDER RESEP (BOM) ---
    function populateMaterialSelect() {
        const select = document.getElementById('recipeMaterialSelect');
        select.innerHTML = '<option value="">-- Pilih Bahan Baku --</option>';
        allMaterials.forEach(m => {
            select.innerHTML += `<option value="${m.id}" data-unit="${m.unit || 'pcs'}">[${m.sku || '-'}] ${m.name}</option>`;
        });
    }

    function updateUnitLabel() {
        const select = document.getElementById('recipeMaterialSelect');
        const selectedOption = select.options[select.selectedIndex];
        document.getElementById('recipeUnitLabel').innerText = selectedOption.value ? selectedOption.getAttribute('data-unit') : '...';
    }

    async function openRecipeModal(menuId, menuName) {
        currentActiveMenuId = menuId;
        document.getElementById('recipeModalTitle').innerText = `Resep: ${menuName}`;
        tempRecipeMaterials = []; 
        
        try {
            const res = await fetch(`../api/?action=get_recipe&menu_id=${menuId}`);
            const result = await res.json();
            if(result.status === 'success' && result.data.length > 0) {
                result.data.forEach(r => {
                    tempRecipeMaterials.push({ material_id: r.material_id, material_name: r.material_name, qty: r.qty_required, unit: r.unit });
                });
            }
        } catch(e) {}

        renderRecipeTable();
        const modal = document.getElementById('recipeModal');
        modal.classList.remove('hidden'); setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('recipeModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeRecipeModal() {
        document.getElementById('recipeModal').classList.add('opacity-0'); document.getElementById('recipeModalContent').classList.add('scale-95');
        setTimeout(() => { document.getElementById('recipeModal').classList.add('hidden'); }, 300);
    }

    function addMaterialToTemp() {
        const select = document.getElementById('recipeMaterialSelect');
        const matId = select.value;
        const qty = parseFloat(document.getElementById('recipeQty').value);
        
        if(!matId || !qty || qty <= 0) { Toast.fire({icon:'warning', title:'Pilih bahan & masukkan Qty valid!'}); return; }

        const matName = select.options[select.selectedIndex].text.replace(/\[.*?\] /, ''); // Hilangkan kode SKU dari nama buat ditampilin di tabel
        const matUnit = select.options[select.selectedIndex].getAttribute('data-unit');

        const existing = tempRecipeMaterials.find(m => m.material_id == matId);
        if(existing) { existing.qty = parseFloat(existing.qty) + qty; } 
        else { tempRecipeMaterials.push({ material_id: matId, material_name: matName, qty: qty, unit: matUnit }); }

        document.getElementById('recipeQty').value = '';
        select.value = ''; updateUnitLabel();
        renderRecipeTable();
    }

    function removeMaterialFromTemp(index) {
        tempRecipeMaterials.splice(index, 1); renderRecipeTable();
    }

    function renderRecipeTable() {
        const tbody = document.getElementById('recipeTableBody');
        tbody.innerHTML = '';
        if(tempRecipeMaterials.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-6 text-slate-400 italic bg-slate-50 dark:bg-slate-900/30">Belum ada bahan baku untuk resep ini.</td></tr>'; return;
        }

        tempRecipeMaterials.forEach((item, index) => {
            tbody.innerHTML += `
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="py-3 font-bold text-slate-800 dark:text-slate-200">${item.material_name}</td>
                    <td class="py-3 text-center"><span class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-400 font-bold px-3 py-1 rounded-lg">${item.qty} <span class="text-xs font-normal text-indigo-500">${item.unit}</span></span></td>
                    <td class="py-3 text-right">
                        <button onclick="removeMaterialFromTemp(${index})" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 p-2 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </td>
                </tr>`;
        });
    }

    async function saveRecipeToDB() {
        const btn = document.getElementById('btnSaveRecipe');
        btn.innerText = 'Menyimpan...'; btn.disabled = true;

        const payload = { menu_id: currentActiveMenuId, materials: tempRecipeMaterials };

        try {
            const res = await fetch('../api/?action=save_recipe', { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if(data.status === 'success') { Toast.fire({icon: 'success', title: data.message}); closeRecipeModal(); } 
            else { Swal.fire('Gagal', data.message, 'error'); }
        } catch(e) { Swal.fire('Error', 'Kesalahan jaringan', 'error'); }
        finally { btn.innerText = 'Simpan Komposisi Resep'; btn.disabled = false; }
    }

    document.addEventListener('DOMContentLoaded', loadData);
</script>