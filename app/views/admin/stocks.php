<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
   <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Manajemen Stok Masuk</h3>
            <p class="text-sm text-slate-500 mt-1">Catat penerimaan barang dan Harga Pokok Penjualan (HPP).</p>
        </div>
        
        <button onclick="openModal()" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Stok Masuk
        </button>
    </div>

    <!-- Info Banner -->
    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl mb-6 flex gap-3 items-start">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        <p class="text-sm leading-relaxed">
            Sistem akuntansi menggunakan metode <b>FIFO</b>. Riwayat stok masuk di bawah ini bersifat permanen (tidak bisa dihapus/diedit) untuk menjaga integritas Laporan Keuangan dan Laba Kotor Anda.
        </p>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-1">
        <div class="overflow-x-auto h-full">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Masuk</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Qty Tambah</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">HPP (Harga Beli)</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Supplier</th>
                    </tr>
                </thead>
                <tbody id="stockTableBody" class="bg-white divide-y divide-slate-200">
                    <!-- Data AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Stok Masuk -->
<div id="stockModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden transform transition-all scale-95" id="modalContent">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Form Penerimaan Barang</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="stockForm" onsubmit="saveStock(event)">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Produk / Bahan Baku <span class="text-red-500">*</span></label>
                    <select id="formProductId" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">-- Memuat Produk... --</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah (Qty) <span class="text-red-500">*</span></label>
                        <input type="number" id="formQty" required min="1" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cth: 50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Total Harga Nota (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="formTotalPriceBuy" required min="0" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cth: 55000">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Supplier (Opsional)</label>
                    <input type="text" id="formSupplier" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cth: Toko Bahan Kue Maju">
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" id="btnSave" class="px-5 py-2 border border-transparent rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Stok</button>
            </div>
        </form>
    </div>
</div>

<script>
    const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    const formatDate = (dateString) => {
        const d = new Date(dateString);
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    };

    // --- Fetch Data Produk Untuk Dropdown Form (DENGAN KATEGORI) ---
    async function fetchProductsForDropdown() {
        const select = document.getElementById('formProductId');
        try {
            const response = await fetch('../api/?action=get_products');
            const result = await response.json();
            if (result.status === 'success') {
                select.innerHTML = '<option value="">-- Pilih Barang Datang --</option>';
                
                // Pisahkan array antara bahan mentah dan barang jadi
                const bahanBaku = result.data.filter(p => p.type === 'bahan_baku');
                const produkJual = result.data.filter(p => p.type === 'produk_jual');

                if (bahanBaku.length > 0) {
                    select.innerHTML += `<optgroup label="📦 BAHAN BAKU (GUDANG)">`;
                    bahanBaku.forEach(p => {
                        select.innerHTML += `<option value="${p.id}">[${p.sku || '-'}] ${p.name} (per ${p.unit})</option>`;
                    });
                    select.innerHTML += `</optgroup>`;
                }

                if (produkJual.length > 0) {
                    select.innerHTML += `<optgroup label="🛒 PRODUK JUAL (RETAIL)">`;
                    produkJual.forEach(p => {
                        select.innerHTML += `<option value="${p.id}">[${p.sku || '-'}] ${p.name} (per ${p.unit})</option>`;
                    });
                    select.innerHTML += `</optgroup>`;
                }
            }
        } catch (error) {
            select.innerHTML = '<option value="">Gagal memuat produk</option>';
        }
    }

    // --- Load History Stok Masuk ---
    async function loadStocks() {
        const tbody = document.getElementById('stockTableBody');
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Memuat data stok...</td></tr>`;
        
        try {
            const response = await fetch('../api/?action=get_stocks');
            const result = await response.json();

            if (result.status === 'success') {
                tbody.innerHTML = '';
                
                if (result.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada riwayat stok masuk.</td></tr>`;
                    return;
                }

                result.data.forEach(stock => {
                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">${formatDate(stock.date_in)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-800">${stock.product_name}</div>
                                <div class="text-xs text-slate-500 font-mono">${stock.sku}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    + ${stock.qty}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-slate-800">${formatRp(stock.price_buy)} /satuan</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">${stock.supplier || '-'}</td>
                        </tr>
                    `;
                });
            }
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-10 text-center text-red-500">Gagal mengambil riwayat stok.</td></tr>`;
        }
    }

    // --- Submit Form Tambah Stok ---
    async function saveStock(event) {
        event.preventDefault();
        const btnSave = document.getElementById('btnSave');
        const originalText = btnSave.innerText;
        btnSave.innerText = 'Memproses...'; btnSave.disabled = true;

        // PERHATIKAN PAYLOAD INI!
        const payload = {
            product_id: document.getElementById('formProductId').value,
            qty: document.getElementById('formQty').value,
            total_price_buy: document.getElementById('formTotalPriceBuy').value, // <-- Ini yang diminta API
            supplier: document.getElementById('formSupplier').value
        };

        try {
            const response = await fetch('../api/?action=save_stock', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();

            if (result.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Stok Ditambahkan!', text: result.message, timer: 2500, showConfirmButton: false });
                closeModal();
                loadStocks(); 
            } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message }); }
        } catch (error) { Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.' }); } 
        finally { btnSave.innerText = originalText; btnSave.disabled = false; }
    }

    // --- Modal Controller ---
    const modal = document.getElementById('stockModal');
    const modalContent = document.getElementById('modalContent');

    function openModal() {
        document.getElementById('stockForm').reset();
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); }, 10);
    }

    function closeModal() {
        modal.classList.add('opacity-0'); modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // Inisialisasi saat load
    document.addEventListener('DOMContentLoaded', () => {
        loadStocks();
        fetchProductsForDropdown();
    });
</script>