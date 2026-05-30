<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
   <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="searchProduct" placeholder="Cari nama produk atau SKU..." 
                   class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all shadow-sm">
        </div>
        
        <button onclick="openModal()" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Produk
        </button>
    </div>

    <!-- Table Container (Timpa bagian ini di app/views/admin/products.php) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-1">
        <div class="overflow-x-auto h-full">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">SKU</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">HPP</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Jual</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Profit</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Stok</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="bg-white divide-y divide-slate-200">
                    <!-- Data akan dirender lewat JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Produk -->
<div id="productModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden transform transition-all scale-95" id="modalContent">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Tambah Produk Baru</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Form Input -->
        <form id="productForm" onsubmit="saveProduct(event)">
            <div class="p-6 space-y-4">
                <input type="hidden" id="formId">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">SKU / Kode</label>
                        <input type="text" id="formSku" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Opsional">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                        <select id="formCategory" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-white">
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 border-y border-slate-100 py-3 my-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Item</label>
                        <select id="formType" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-white font-bold text-indigo-700">
                            <option value="produk_jual">🛒 Produk Jual (Menu)</option>
                            <option value="bahan_baku">📦 Bahan Baku (Gudang)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Satuan (UoM)</label>
                        <input type="text" id="formUnit" list="unitList" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cth: pcs, gram, ml...">
                        <datalist id="unitList">
                            <option value="pcs"><option value="porsi"><option value="gram"><option value="kg"><option value="ml"><option value="liter">
                        </datalist>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Foto Produk <span class="font-normal text-slate-400">(Opsional)</span></label>
                    <div class="flex items-center gap-4">
                        <input type="file" id="prodImageFile" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-xl">
                        <input type="hidden" id="prodImageUrl" value="">
                        
                        <!-- Preview Foto -->
                        <div id="imagePreviewBox" class="hidden w-12 h-12 rounded-xl border border-slate-200 overflow-hidden flex-shrink-0 shadow-sm">
                            <img id="imagePreviewImg" src="" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="formName" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Cth: Caffe Latte">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="formPrice" required min="0" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Cth: 25000">
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" id="btnSave" class="px-5 py-2 border border-transparent rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    let currentProducts = []; 
    
    // TAMBAHKAN BARIS INI BRO:
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });

    // --- LOAD DYNAMIC CATEGORIES UNTUK DROPDOWN FORM ---
    async function fetchCategoriesForSelect() {
        const select = document.getElementById('formCategory');
        try {
            const response = await fetch('/yoripos/api/?action=get_categories');
            const result = await response.json();
            if (result.status === 'success') {
                select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                result.data.forEach(cat => {
                    select.innerHTML += `<option value="${cat.name}">${cat.name}</option>`;
                });
            }
        } catch (error) {
            console.error('Gagal mengambil kategori untuk form');
        }
    }

    // --- READ: Load Data (Timpa fungsi ini) ---
    async function loadProducts() {
        const tbody = document.getElementById('productTableBody');
        // Colspan diubah jadi 8 karena kolom kita nambah
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-slate-400">Memuat data...</td></tr>`;
        
        try {
            const response = await fetch('/yoripos/api/?action=get_products');
            const result = await response.json();

            if (result.status === 'success') {
                currentProducts = result.data;
                tbody.innerHTML = '';
                
                if (currentProducts.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-slate-500">Belum ada produk. Silakan tambah data.</td></tr>`;
                    return;
                }

                currentProducts.forEach(product => {
                    const stockClass = product.total_stock > 10 ? 'text-emerald-600 bg-emerald-50' : (product.total_stock > 0 ? 'text-amber-600 bg-amber-50' : 'text-red-600 bg-red-50');
                    const catName = product.category ? product.category : '-';
                    
                    // Logika Kalkulasi HPP, Harga Jual, dan Profit
                    const hargaBeli = product.last_price_buy ? parseFloat(product.last_price_buy) : 0;
                    const hargaJual = parseFloat(product.price_sell);
                    
                    let hppText = `<span class="text-slate-400 italic">-</span>`;
                    let profitHtml = `<span class="text-xs text-slate-400 italic">Belum ada HPP</span>`;
                    
                    if (hargaBeli > 0) {
                        hppText = formatRp(hargaBeli);
                        const profitNominal = hargaJual - hargaBeli;
                        const profitMargin = Math.round((profitNominal / hargaJual) * 100);
                        const marginClass = profitNominal > 0 ? 'text-emerald-600' : 'text-red-600';
                        
                        // Render UI Profit sesuai request lu
                        profitHtml = `
                            <div class="text-sm font-bold text-slate-800">${formatRp(profitNominal)}</div>
                            <div class="text-xs ${marginClass} font-bold mt-0.5">${profitMargin}%</div>
                        `;
                    }
                    
                    const typeBadge = product.type === 'bahan_baku' 
                        ? `<span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded border border-amber-200">Bahan Baku</span>`
                        : '';
                        
                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-slate-500">${product.sku || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-800 flex items-center gap-2">${product.name} ${typeBadge}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full ${stockClass}">${product.total_stock} ${product.unit || 'pcs'}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600"><span class="bg-slate-100 px-2 py-1 rounded-md border border-slate-200">${catName}</span></td>
                            
                            <!-- 3 Kolom Finansial Baru -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-slate-600">${hppText}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-800">${formatRp(hargaJual)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right bg-slate-50/50">
                                ${profitHtml}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-center"><span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full ${stockClass}">${product.total_stock}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium opacity-50 group-hover:opacity-100 transition-opacity">
                                <!-- UBAH BARIS INI: -->
                                <button onclick="editProduct(${product.id})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors mr-1" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="deleteProduct(${product.id})" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-red-500">Gagal mengambil data dari server.</td></tr>`;
        }
    }

    // 1. Live Preview saat pilih file
    document.getElementById('prodImageFile').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            const src = URL.createObjectURL(e.target.files[0]);
            document.getElementById('imagePreviewImg').src = src;
            document.getElementById('imagePreviewBox').classList.remove('hidden');
        }
    });

    // 2. Fungsi Eksekusi ke Vercel Blob
    async function uploadToBlob(file) {
        const formData = new FormData();
        formData.append('image', file);
        try {
            const res = await fetch('/yoripos/api/?action=upload_blob', { method: 'POST', body: formData });
            const data = await res.json();
            if(data.status === 'success') return data.url;
            Swal.fire('Upload Gagal', data.message, 'error'); return '';
        } catch (error) {
            Swal.fire('Error', 'Gagal menghubungi server Vercel.', 'error'); return '';
        }
    }

    // 3. Update Fungsi saveProduct (Perbaikan ID HTML)
    async function saveProduct(event) {
        if(event) event.preventDefault(); // Mencegah form reload page

        const name = document.getElementById('formName').value;
        const price = document.getElementById('formPrice').value;
        if(!name || !price) { Toast.fire({ icon: 'warning', title: 'Nama & Harga wajib diisi!' }); return; }

        const btn = document.getElementById('btnSave');
        const originalText = btn.innerText;
        btn.disabled = true;

        let finalImageUrl = document.getElementById('prodImageUrl').value;
        const fileInput = document.getElementById('prodImageFile');

        if(fileInput.files.length > 0) {
            btn.innerText = 'Mengunggah Foto...';
            const uploadedUrl = await uploadToBlob(fileInput.files[0]);
            if(uploadedUrl) { finalImageUrl = uploadedUrl; } 
            else { btn.innerText = originalText; btn.disabled = false; return; }
        }

        btn.innerText = 'Menyimpan...';

        const payload = {
            id: document.getElementById('formId').value,
            sku: document.getElementById('formSku').value,
            category: document.getElementById('formCategory').value,
            type: document.getElementById('formType').value,  // <--- TAMBAHAN
            unit: document.getElementById('formUnit').value,  // <--- TAMBAHAN
            name: name,
            price_sell: price,
            image_url: finalImageUrl
        };

        try {
            const response = await fetch('/yoripos/api/?action=save_product', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (result.status === 'success') {
                Toast.fire({ icon: 'success', title: result.message });
                closeModal(); 
                loadProducts(); // Panggil loadProducts, bukan fetchProducts untuk halaman Admin
            } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message }); }
        } catch (error) { console.error(error); } 
        finally { btn.innerText = originalText; btn.disabled = false; }
    }

    // 4. Update fungsi editProduct (Perbaikan ID HTML)
    function editProduct(id) {
        // Cari data produk dari array currentProducts berdasarkan ID
        const prod = currentProducts.find(p => p.id == id);
        if (!prod) return;

        document.getElementById('modalTitle').innerText = 'Edit Produk';
        document.getElementById('formId').value = prod.id;
        document.getElementById('formSku').value = prod.sku || '';
        document.getElementById('formName').value = prod.name;
        document.getElementById('formPrice').value = prod.price_sell;
        document.getElementById('formCategory').value = prod.category || '';
        document.getElementById('formType').value = prod.type || 'produk_jual'; // <--- TAMBAHAN
        document.getElementById('formUnit').value = prod.unit || 'pcs';         // <--- TAMBAHAN
        
        document.getElementById('prodImageUrl').value = prod.image_url || '';
        document.getElementById('prodImageFile').value = ''; 
        
        if (prod.image_url) {
            document.getElementById('imagePreviewImg').src = prod.image_url;
            document.getElementById('imagePreviewBox').classList.remove('hidden');
        } else {
            document.getElementById('imagePreviewBox').classList.add('hidden');
        }
        
        const modal = document.getElementById('productModal');
        const modalContent = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); }, 10);
    }

    // --- DELETE: Hapus Data ---
    function deleteProduct(id) {
        Swal.fire({
            title: 'Yakin hapus produk ini?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('/yoripos/api/?action=delete_product', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    });
                    const res = await response.json();
                    
                    if (res.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.message, timer: 1500, showConfirmButton: false });
                        loadProducts(); // Refresh tabel
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
                }
            }
        });
    }

    // --- UI Modal Controller ---
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modalContent');

    function openModal() {
        // Kalau Id kosong, berarti Mode Tambah (Reset Form)
        if (!document.getElementById('formId').value) {
            document.getElementById('modalTitle').innerText = 'Tambah Produk Baru';
            document.getElementById('productForm').reset();
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); }, 10);
    }

    function closeModal() {
        modal.classList.add('opacity-0'); modalContent.classList.add('scale-95');
        setTimeout(() => { 
            modal.classList.add('hidden'); 
            document.getElementById('productForm').reset();
            document.getElementById('formId').value = ''; // Reset ID penting!
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadProducts();
        fetchCategoriesForSelect(); // Tarik master kategori untuk dropdown modal
    });
</script>