<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
   <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Master Bahan Baku</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data mentah untuk kebutuhan gudang dan resep.</p>
        </div>
        <button onclick="openMaterialModal()" class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-all font-bold">
            + Tambah Bahan Baku
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex-1">
        <div class="overflow-x-auto h-full">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">SKU</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Nama Bahan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Kategori</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Satuan (UoM)</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Stok Gudang</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="materialTableBody" class="divide-y divide-slate-200 dark:divide-slate-700">
                    <tr><td colspan="6" class="text-center py-8 text-slate-400">Memuat...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="materialModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all scale-95" id="materialModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100" id="modalTitle">Tambah Bahan Baku</h3>
            <button onclick="closeMaterialModal()" class="text-slate-400 hover:text-red-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <input type="hidden" id="formId">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">SKU / Kode</label>
                    <input type="text" id="formSku" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl outline-none bg-slate-50 dark:bg-slate-900 dark:text-white" placeholder="Cth: RAW-001">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                    <select id="formCategory" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl outline-none bg-slate-50 dark:bg-slate-900 dark:text-white">
                        </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Bahan Baku <span class="text-red-500">*</span></label>
                    <input type="text" id="formName" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl outline-none dark:text-white" placeholder="Cth: Gula Aren Cair">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Satuan Dasar (UoM) <span class="text-red-500">*</span></label>
                    <input type="text" id="formUnit" list="unitList" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl outline-none dark:text-white" placeholder="Cth: gram, ml, pcs">
                    <datalist id="unitList">
                        <option value="gram"><option value="kg"><option value="ml"><option value="liter"><option value="pcs"><option value="pack">
                    </datalist>
                    <p class="text-[10px] text-slate-400 mt-1">Gunakan satuan terkecil agar perhitungan resep akurat.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Foto <span class="font-normal text-slate-400">(Opsional)</span></label>
                <div class="flex items-center gap-4">
                    <input type="file" id="materialImageFile" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 cursor-pointer border border-slate-200 dark:border-slate-600 rounded-xl">
                    <input type="hidden" id="materialImageUrl" value="">
                    
                    <div id="imagePreviewBox" class="hidden w-12 h-12 rounded-xl border border-slate-200 overflow-hidden flex-shrink-0 shadow-sm">
                        <img id="imagePreviewImg" src="" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <button onclick="saveMaterial()" id="btnSaveMaterial" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl mt-2 transition-colors">Simpan Data Gudang</button>
        </div>
    </div>
</div>

<script>
    const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
    
    let allMaterials = []; 

    // --- INISIALISASI DATA ---
    async function loadData() {
        fetchCategoriesForSelect();
        try {
            const response = await fetch('/yoripos/api/?action=get_products');
            const result = await response.json();
            if (result.status === 'success') {
                // HANYA AMBIL BAHAN BAKU
                allMaterials = result.data.filter(p => p.type === 'bahan_baku');
                renderMaterialTable();
            }
        } catch (error) { 
            document.getElementById('materialTableBody').innerHTML = '<tr><td colspan="6" class="text-center py-8 text-red-500 font-bold">Gagal mengambil data dari server!</td></tr>';
        }
    }

    async function fetchCategoriesForSelect() {
        const select = document.getElementById('formCategory');
        try {
            const response = await fetch('/yoripos/api/?action=get_categories');
            const result = await response.json();
            if (result.status === 'success') {
                select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                result.data.forEach(cat => { select.innerHTML += `<option value="${cat.name}">${cat.name}</option>`; });
            }
        } catch (error) {}
    }

    function renderMaterialTable() {
        const tbody = document.getElementById('materialTableBody');
        tbody.innerHTML = '';
        
        if(allMaterials.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-slate-500">Belum ada data Bahan Baku.</td></tr>';
            return;
        }

        allMaterials.forEach(mat => {
            const stockClass = mat.total_stock > 0 ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30' : 'text-red-500 bg-red-50 dark:bg-red-900/30';
            
            tbody.innerHTML += `
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-mono text-slate-500">${mat.sku || '-'}</td>
                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                        ${mat.image_url ? `<img src="${mat.image_url}" class="w-8 h-8 rounded-lg object-cover">` : `<div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>`}
                        ${mat.name}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">${mat.category || '-'}</td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-slate-600">${mat.unit || '-'}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full border border-current ${stockClass}">${mat.total_stock}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="editMaterial(${mat.id})" class="text-emerald-500 hover:text-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 p-1.5 rounded-lg transition-colors"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                    </td>
                </tr>`;
        });
    }

    // --- UPLOAD GAMBAR KE VERCEL BLOB ---
    document.getElementById('materialImageFile').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            const src = URL.createObjectURL(e.target.files[0]);
            document.getElementById('imagePreviewImg').src = src;
            document.getElementById('imagePreviewBox').classList.remove('hidden');
        }
    });

    async function uploadToBlob(file) {
        const formData = new FormData(); formData.append('image', file);
        try {
            const res = await fetch('/yoripos/api/?action=upload_blob', { method: 'POST', body: formData });
            const data = await res.json();
            if(data.status === 'success') return data.url;
            Swal.fire('Upload Gagal', data.message, 'error'); return '';
        } catch (error) { Swal.fire('Error', 'Gagal menghubungi server.', 'error'); return ''; }
    }

    // --- LOGIKA CRUD BAHAN BAKU ---
    function openMaterialModal() {
        document.getElementById('formId').value = '';
        document.getElementById('formSku').value = '';
        document.getElementById('formName').value = '';
        document.getElementById('formCategory').value = '';
        document.getElementById('formUnit').value = '';
        document.getElementById('materialImageUrl').value = '';
        document.getElementById('materialImageFile').value = '';
        document.getElementById('imagePreviewBox').classList.add('hidden');
        document.getElementById('modalTitle').innerText = 'Tambah Bahan Baku';
        
        const modal = document.getElementById('materialModal');
        modal.classList.remove('hidden'); setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('materialModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeMaterialModal() {
        document.getElementById('materialModal').classList.add('opacity-0'); document.getElementById('materialModalContent').classList.add('scale-95');
        setTimeout(() => { document.getElementById('materialModal').classList.add('hidden'); }, 300);
    }

    function editMaterial(id) {
        const mat = allMaterials.find(m => m.id == id);
        if(!mat) return;

        document.getElementById('formId').value = mat.id;
        document.getElementById('formSku').value = mat.sku || '';
        document.getElementById('formName').value = mat.name;
        document.getElementById('formCategory').value = mat.category || '';
        document.getElementById('formUnit').value = mat.unit || '';
        document.getElementById('materialImageUrl').value = mat.image_url || '';
        document.getElementById('materialImageFile').value = '';
        
        if(mat.image_url) {
            document.getElementById('imagePreviewImg').src = mat.image_url;
            document.getElementById('imagePreviewBox').classList.remove('hidden');
        } else {
            document.getElementById('imagePreviewBox').classList.add('hidden');
        }

        document.getElementById('modalTitle').innerText = 'Edit Bahan Baku';
        document.getElementById('materialModal').classList.remove('hidden'); setTimeout(() => { document.getElementById('materialModal').classList.remove('opacity-0'); document.getElementById('materialModalContent').classList.remove('scale-95'); }, 10);
    }

    async function saveMaterial() {
        const name = document.getElementById('formName').value;
        const unit = document.getElementById('formUnit').value;
        if(!name || !unit) { Toast.fire({icon: 'warning', title: 'Nama dan Satuan wajib diisi!'}); return; }

        const btn = document.getElementById('btnSaveMaterial');
        const originalText = btn.innerText;
        btn.disabled = true;

        let finalImageUrl = document.getElementById('materialImageUrl').value;
        const fileInput = document.getElementById('materialImageFile');

        if(fileInput.files.length > 0) {
            btn.innerText = 'Mengunggah Foto...';
            const uploadedUrl = await uploadToBlob(fileInput.files[0]);
            if(uploadedUrl) { finalImageUrl = uploadedUrl; } 
            else { btn.innerText = originalText; btn.disabled = false; return; }
        }

        btn.innerText = 'Menyimpan Data...';

        // PAYLOAD KHUSUS GUDANG
        const payload = {
            id: document.getElementById('formId').value,
            sku: document.getElementById('formSku').value,
            name: name,
            price_sell: 0, // Kunci permanen Harga Jual = 0 untuk gudang
            category: document.getElementById('formCategory').value,
            image_url: finalImageUrl,
            type: 'bahan_baku', // Kunci permanen tipe item = bahan baku
            unit: unit
        };

        try {
            const res = await fetch('/yoripos/api/?action=save_product', { method: 'POST', body: JSON.stringify(payload) });
            const data = await res.json();
            if(data.status === 'success') { Toast.fire({icon:'success', title: data.message}); closeMaterialModal(); loadData(); }
            else { Swal.fire('Gagal', data.message, 'error'); }
        } catch(e) { Swal.fire('Error', 'Kesalahan jaringan', 'error'); }
        finally { btn.innerText = originalText; btn.disabled = false; }
    }

    document.addEventListener('DOMContentLoaded', loadData);
</script>