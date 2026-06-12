<?php
// Proteksi: Hanya yang punya akses settings (VIP/Super Admin) yang bisa buka
$perms = $_SESSION['permissions'] ?? [];
if(!in_array('settings', $perms)) {
    header("Location: ?page=mobile");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>VIP POS - Mobile</title>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased pb-32">
    
    <div class="bg-white px-5 py-4 border-b border-slate-200 sticky top-0 z-40 flex items-center gap-3 shadow-sm">
        <a href="?page=mobile" class="w-10 h-10 bg-slate-100 rounded-full flex justify-center items-center text-slate-600 hover:bg-slate-200 active:scale-90 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-lg font-black text-slate-800 leading-none">Add Order (VIP)</h1>
            <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider mt-1">Mobile POS Mode</p>
        </div>
    </div>

    <div class="p-5 bg-white border-b border-slate-200 space-y-4 shadow-sm">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Pelanggan</label>
            <div onclick="openCustomerSelectModal()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 flex justify-between items-center shadow-inner active:bg-slate-100 transition-all">
                <span id="customerSelectedNameDisplay">-- Pelanggan Umum --</span>
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <input type="hidden" id="customerSelect" value="">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 flex justify-between">
                <span>Tanggal Transaksi</span>
                <span class="text-amber-500">Backdate Aktif</span>
            </label>
            <input type="datetime-local" id="inputBackdate" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500 shadow-inner">
            <p class="text-[10px] text-slate-400 mt-1">*Kosongkan jika transaksi realtime hari ini.</p>
        </div>
    </div>

    <div class="p-5">
        <button onclick="openProductModal()" class="w-full bg-indigo-50 border-2 border-dashed border-indigo-300 text-indigo-600 hover:bg-indigo-100 active:scale-95 transition-all font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 mb-4 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Menu Produk
        </button>

        <div id="mobileCartList" class="flex flex-col gap-3">
            <div class="text-center py-10 text-slate-400 font-bold text-sm bg-white rounded-xl border border-slate-200 border-dashed">Keranjang masih kosong</div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-5 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] z-40">
        <div class="flex justify-between items-center mb-3">
            <span class="text-sm font-bold text-slate-500 uppercase tracking-widest">Total Tagihan</span>
            <span class="text-2xl font-black text-emerald-600" id="payTotalDisplay">Rp 0</span>
        </div>
        <button onclick="openCheckoutMenuModal()" class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-black py-4 rounded-xl text-lg tracking-widest shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] transition-all">
            CHECK OUT
        </button>
    </div>

    <!-- ========================================== -->
    <!-- SEMUA MODAL DI BAWAH INI (VERSI ANTI-GHOIB) -->
    <!-- ========================================== -->

    <!-- Modal 1: Pilih Produk (FULL SCREEN MOBILE) -->
    <div id="productModal" class="fixed inset-0 bg-slate-50 z-50 hidden flex-col transition-transform transform translate-y-full duration-300">
        <div class="bg-white px-5 py-4 border-b border-slate-200 flex justify-between items-center shadow-sm">
            <h3 class="text-lg font-black text-slate-800">Pilih Menu</h3>
            <button onclick="closeProductModal()" class="text-slate-400 hover:text-red-500 bg-slate-100 p-2 rounded-full active:scale-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 bg-white border-b border-slate-200">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                <input type="text" id="searchProductInput" oninput="filterProducts()" placeholder="Cari nama menu..." class="w-full bg-slate-100 border border-slate-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-bold transition-all">
            </div>
            <!-- Filter Kategori Kapsul -->
            <div id="categoryFilterContainer" class="flex gap-2 overflow-x-auto hide-scrollbar pt-3 pb-1"></div>
        </div>
        <div class="flex-1 overflow-y-auto p-4 bg-slate-50 flex flex-col gap-3" id="mobileProductList">
            <p class="text-center text-slate-400 text-sm py-10 animate-pulse">Memuat menu...</p>
        </div>
    </div>

    <!-- MODAL CUSTOMER (Pilih & Tambah) -->
    <div id="customerSelectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-end justify-center">
        <div class="bg-white w-full rounded-t-[2rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 flex flex-col max-h-[90vh]" id="customerSelectModalContent">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 sticky top-0 z-10">
                <h3 class="text-lg font-bold text-slate-800">Pilih Konsumen</h3>
                <button onclick="closeCustomerSelectModal()" class="text-slate-400 hover:text-slate-600 transition-colors bg-slate-200 p-1.5 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 bg-white border-b border-slate-100 flex gap-2">
                <input type="text" id="popupSearchCustomer" oninput="filterPopupCustomers()" placeholder="Cari nama/WA..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                <button onclick="triggerCreateFromPopup()" class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-xl text-sm flex items-center gap-1 shadow-sm">Baru</button>
            </div>
            <div class="flex-1 overflow-y-auto p-2 bg-slate-50" id="popupCustomerContainer"></div>
        </div>
    </div>

    <!-- Modal Tambah Customer -->
    <div id="customerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[80] hidden flex items-center justify-center">
        <div class="bg-white w-full max-w-sm mx-4 rounded-3xl shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="customerModalContent">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800">Tambah Pelanggan</h3>
                <button onclick="closeCustomerModal()" class="text-slate-400 bg-slate-200 p-1.5 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-5 space-y-4">
                <div><label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap</label><input type="text" id="custName" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm"></div>
                <div><label class="block text-xs font-bold text-slate-500 mb-1">No. WhatsApp</label><input type="number" id="custPhone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm"></div>
                <div><label class="block text-xs font-bold text-slate-500 mb-1">Alamat</label><textarea id="custAddress" rows="2" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm"></textarea></div>
            </div>
            <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex gap-3">
                <button onclick="closeCustomerModal()" class="flex-1 bg-white border border-slate-200 font-bold py-2.5 rounded-xl">Batal</button>
                <button onclick="submitNewCustomer()" id="btnSaveCust" class="flex-1 bg-indigo-600 text-white font-bold py-2.5 rounded-xl">Simpan</button>
            </div>
        </div>
    </div>

    <!-- MODAL CHECKOUT MENU (LEVEL 1) -->
    <div id="checkoutMenuModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] hidden flex items-end justify-center">
        <div class="bg-white w-full rounded-t-[2rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 flex flex-col max-h-[90vh]" id="checkoutMenuModalContent">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-black text-slate-800">Checkout</h3>
                <button onclick="closeCheckoutMenuModal()" class="text-slate-400 bg-slate-100 p-2 rounded-full active:scale-90 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 space-y-6 bg-slate-50">
                <div>
                    <h4 class="font-bold text-slate-800 mb-3">Metode Pemesanan</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div onclick="selectOrderMethod('bayar_langsung')" id="om_bayar_langsung" class="border-2 border-red-500 rounded-xl p-3 cursor-pointer bg-red-50 text-center">
                            <h5 class="font-bold text-sm text-slate-800">Bayar Langsung</h5>
                        </div>
                        <div onclick="selectOrderMethod('piutang')" id="om_piutang" class="border border-slate-200 rounded-xl p-3 cursor-pointer bg-white text-center">
                            <h5 class="font-bold text-sm text-slate-800">Piutang</h5>
                        </div>
                        <div onclick="selectOrderMethod('buat_pesanan')" id="om_buat_pesanan" class="border border-slate-200 rounded-xl p-3 cursor-pointer bg-white text-center">
                            <h5 class="font-bold text-sm text-slate-800">Buat Pesanan</h5>
                        </div>
                        <div onclick="selectOrderMethod('gabung_pesanan')" id="om_gabung_pesanan" class="border border-slate-200 rounded-xl p-3 cursor-pointer bg-white text-center">
                            <h5 class="font-bold text-sm text-slate-800">Gabung Pesanan</h5>
                        </div>
                    </div>
                </div>
                <hr class="border-slate-200">
                <div>
                    <h4 class="font-bold text-slate-800 mb-4">Rincian Pesanan</h4>
                    <div id="checkoutMenuCartItems" class="space-y-3 mb-4 text-sm font-medium text-slate-600"></div>
                    <div class="flex justify-between items-center font-black text-xl pt-3 border-t border-slate-200">
                        <span class="text-slate-800">Total</span>
                        <span id="checkoutMenuTotalAwal" class="text-emerald-500">Rp 0</span>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white border-t border-slate-200">
                <button id="btnProceedCheckoutMethod" onclick="proceedCheckoutMethod()" class="w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-black py-4 rounded-xl text-lg shadow-lg transition-all">
                    Lanjut Pembayaran
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL PEMBAYARAN (LEVEL 2) -->
    <div id="paymentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-end justify-center">
        <div class="bg-white w-full rounded-t-[2rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 max-h-[95vh] flex flex-col" id="paymentModalContent">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3 sticky top-0 bg-white z-10">
                <button onclick="backToCheckoutMenu()" class="text-slate-500 bg-slate-100 p-2 rounded-full active:scale-90 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <h3 class="text-xl font-black text-slate-800">Pembayaran</h3>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto">
                <div class="text-center bg-slate-50 py-4 rounded-2xl border border-slate-100">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Tagihan</p>
                    <p class="text-4xl font-black text-emerald-500" id="payTotalDisplay2">Rp 0</p>
                </div>
                <div>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="selectMethod('CASH')" id="btnMethodCASH" class="method-btn active bg-indigo-50 border-indigo-500 text-indigo-700 border-2 py-3 rounded-xl text-sm font-bold transition-all">Tunai</button>
                        <button type="button" onclick="selectMethod('QRIS')" id="btnMethodQRIS" class="method-btn bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all">QRIS</button>
                        <button type="button" onclick="selectMethod('TRANSFER')" id="btnMethodTRANSFER" class="method-btn bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all">Transfer</button>
                    </div>
                </div>
                <div id="cashInputSection" class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Uang Diterima</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-black">Rp</span>
                            <input type="number" id="inputCash" oninput="calculateChange()" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-black text-xl outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="setExactCash()" class="flex-1 bg-slate-100 font-bold py-2.5 rounded-lg text-sm text-slate-700 border border-slate-200 active:bg-slate-200">Uang Pas</button>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Kembalian</label>
                        <input type="text" id="inputChange" readonly class="w-full px-4 py-3 bg-red-50 border border-red-100 rounded-xl font-black text-xl text-red-600 outline-none" value="Rp 0">
                    </div>
                </div>
                <div id="nonCashSection" class="hidden space-y-5 pt-4 border-t border-slate-100">
                    <div id="qrisView" class="hidden flex-col gap-3">
                        <p class="text-sm font-bold text-slate-700">Pilih QRIS Tujuan</p>
                        <div id="qrisListContainer" class="flex flex-wrap gap-3">
                            <p class="text-sm text-slate-400 italic">Memuat data QRIS...</p>
                        </div>
                        <div class="flex justify-center mt-2">
                            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200">
                                <img id="qrisDisplayImage" src="" alt="QRIS" class="w-48 h-48 object-contain rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div id="transferView" class="hidden flex-col gap-3">
                        <p class="text-sm font-bold text-slate-700">Pilih Rekening Tujuan</p>
                        <div id="transferListContainer" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            <p class="text-sm text-slate-400 italic">Memuat data rekening...</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 p-3 rounded-xl flex gap-3 items-center">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-blue-800">Pastikan mengecek mutasi masuk sebelum menekan Konfirmasi Pembayaran.</p>
                    </div>
                    <div class="pt-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Upload Bukti Bayar <span class="text-red-500">*</span></label>
                        <input type="file" id="inputPaymentProof" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer bg-slate-50 border border-slate-200 rounded-xl">
                        <div id="proofPreviewContainer" class="mt-3 hidden text-center bg-slate-50 p-2 rounded-xl border border-slate-200">
                            <img id="proofPreview" src="" class="h-32 object-contain mx-auto rounded-lg" />
                        </div>
                        <input type="hidden" id="proofBase64" value="">
                    </div>
                </div>
                
                <button id="btnConfirmPay" onclick="executeCheckout()" class="w-full bg-emerald-500 active:bg-emerald-700 text-white font-black py-4 rounded-xl text-lg shadow-lg mt-4 disabled:opacity-50 transition-all">
                    Konfirmasi Pembayaran
                </button>
            </div>
        </div>
    </div>

    <style> .hide-scrollbar::-webkit-scrollbar { display: none; } .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; } </style>
    <script>
        // Data State
        let products = [];
        let categoriesList = [];
        let activeCategory = 'all';
        let cart = [];
        let rawMaterialStocks = {}; 
        let originalCustomers = [];
        let popupCustomersArray = [];
        let currentOrderMethod = 'bayar_langsung';
        let currentPaymentMethod = 'CASH';
        let totalTagihanCheckout = 0;
        let appSettings = {};

        const formatRupiah = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });

        // --- FETCH DATA ---// --- FETCH DATA ---
        async function fetchInitData() {
            try {
                const [resCat, resProd, resSet] = await Promise.all([
                    fetch('../api/?action=get_categories'),
                    fetch('../api/?action=get_products'),
                    fetch('../api/?action=get_settings')
                ]);
                const dataCat = await resCat.json();
                const dataProd = await resProd.json();
                const dataSet = await resSet.json();

                if(dataCat.status === 'success') { categoriesList = dataCat.data; renderCategoryFilter(); }
                if(dataSet.status === 'success') { 
                    appSettings = dataSet.data; 
                    renderDynamicPaymentMethods(); // FIX: Dinyalain lagi biar narik QRIS
                }
                if(dataProd.status === 'success') {
                    products = dataProd.data.filter(p => p.type === 'produk_jual');
                    products.forEach(p => {
                        if (p.recipe_details) p.recipe_details.forEach(mat => { rawMaterialStocks[mat.material_id] = mat.material_stock; });
                    });
                    updateDynamicStocks(); filterProducts();
                }
                fetchCustomers();
            } catch (e) { console.error('Init Error', e); }
        }

        // --- RENDER METODE PEMBAYARAN DINAMIS ---
        function renderDynamicPaymentMethods() {
            const transferContainer = document.getElementById('transferListContainer');
            if (transferContainer) {
                let banks = []; try { banks = JSON.parse(appSettings.payment_transfer_list || '[]'); } catch(e){}
                transferContainer.innerHTML = '';
                banks.forEach((b, i) => { transferContainer.innerHTML += `<label class="flex items-center p-3 border rounded-xl"><input type="radio" name="bankTransfer" value="${b.bank}" ${i===0?'checked':''}> <span class="ml-3 font-bold text-sm">${b.bank} <br><span class="font-mono text-indigo-600">${b.number}</span></span></label>`; });
            }
            const qrisContainer = document.getElementById('qrisListContainer');
            if (qrisContainer) {
                let qrisArr = []; try { qrisArr = JSON.parse(appSettings.payment_qris_list || '[]'); } catch(e){}
                qrisContainer.innerHTML = '';
                if(qrisArr.length > 0 && document.getElementById('qrisDisplayImage')) document.getElementById('qrisDisplayImage').src = qrisArr[0].image;
                qrisArr.forEach((q, i) => { qrisContainer.innerHTML += `<label class="flex-1 text-center py-2 px-3 border rounded-xl"><input type="radio" name="qrisSelect" class="hidden" ${i===0?'checked':''} onchange="document.getElementById('qrisDisplayImage').src='${q.image}'"> <span class="font-bold text-sm block">${q.name}</span></label>`; });
            }
        }

        // --- PRODUCT MODAL LOGIC ---
        function openProductModal() {
            document.getElementById('productModal').classList.remove('hidden');
            setTimeout(() => { document.getElementById('productModal').classList.remove('translate-y-full'); }, 10);
        }
        function closeProductModal() {
            document.getElementById('productModal').classList.add('translate-y-full');
            setTimeout(() => { document.getElementById('productModal').classList.add('hidden'); }, 300);
        }
        
        function renderCategoryFilter() {
            const container = document.getElementById('categoryFilterContainer');
            let html = `<button onclick="setCategory('all')" class="px-5 py-2 rounded-full font-bold text-sm whitespace-nowrap shadow-sm border ${activeCategory === 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-slate-200 text-slate-600'}">Semua</button>`;
            categoriesList.forEach(c => {
                const isActive = activeCategory === c.name;
                html += `<button onclick="setCategory('${c.name}')" class="px-5 py-2 rounded-full font-bold text-sm whitespace-nowrap shadow-sm border ${isActive ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-slate-200 text-slate-600'}">${c.name}</button>`;
            });
            container.innerHTML = html;
        }
        function setCategory(cat) { activeCategory = cat; renderCategoryFilter(); filterProducts(); }

        function filterProducts() {
            const keyword = document.getElementById('searchProductInput').value.toLowerCase();
            const filtered = products.filter(p => {
                const mKey = p.name.toLowerCase().includes(keyword);
                const mCat = activeCategory === 'all' || p.category === activeCategory;
                return mKey && mCat;
            });
            renderMobileProductList(filtered);
        }

        function renderMobileProductList(items) {
            const container = document.getElementById('mobileProductList');
            container.innerHTML = '';
            items.forEach(p => {
                const isAvailable = p.calculated_stock > 0;
                const img = p.image_url ? `<img src="${p.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-slate-200 flex justify-center items-center"><span class="text-2xl text-slate-400">🍽️</span></div>`;
                const action = isAvailable ? `onclick="addToCart(${p.id})"` : `onclick="Toast.fire({icon:'error', title:'Habis!'})"`;
                const opacity = isAvailable ? '' : 'opacity-50 grayscale';
                const badge = isAvailable ? `Sisa: ${p.calculated_stock}` : `HABIS`;
                
                container.innerHTML += `
                    <div ${action} class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex gap-4 items-center active:scale-95 transition-transform ${opacity}">
                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 border border-slate-100">${img}</div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800 leading-tight mb-1">${p.name}</h4>
                            <div class="flex justify-between items-center">
                                <span class="font-black text-indigo-600">${formatRupiah(p.price_sell)}</span>
                                <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded">${badge}</span>
                            </div>
                        </div>
                        ${isAvailable ? `<div class="w-8 h-8 rounded-full bg-indigo-50 flex justify-center items-center text-indigo-600 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg></div>` : ''}
                    </div>
                `;
            });
        }

        // --- CART LOGIC ---
        function addToCart(id) {
            const p = products.find(x => x.id == id);
            if(p.calculated_stock <= 0) return;
            const exist = cart.find(x => x.product_id == id);
            if(exist) exist.qty++; else cart.push({product_id: p.id, name: p.name, price_sell: p.price_sell, qty: 1, image_url: p.image_url, max_stock: p.calculated_stock});
            Toast.fire({icon: 'success', title: 'Ditambahkan'});
            updateDynamicStocks(); filterProducts(); renderCart();
        }
        function updateQty(id, change) {
            const idx = cart.findIndex(x => x.product_id == id);
            if(idx > -1) {
                const newQty = cart[idx].qty + change;
                if(newQty > 0 && newQty <= cart[idx].max_stock) { cart[idx].qty = newQty; }
                else if (newQty > cart[idx].max_stock) { Toast.fire({icon: 'warning', title: 'Stok Mentok!'}); }
                else { cart.splice(idx, 1); }
                updateDynamicStocks(); filterProducts(); renderCart();
            }
        }
        function manualUpdateQty(id, val) {
            const idx = cart.findIndex(x => x.product_id == id);
            if(idx > -1) {
                let v = parseInt(val); if(isNaN(v) || v<1) v=1;
                if(v <= cart[idx].max_stock) cart[idx].qty = v; else cart[idx].qty = cart[idx].max_stock;
                updateDynamicStocks(); filterProducts(); renderCart();
            }
        }
        function updateDynamicStocks() {
            // Kalkulasi ulang stok
            products.forEach(p => {
                const used = cart.find(i => i.product_id == p.id)?.qty || 0;
                p.calculated_stock = p.total_stock - used; 
            });
        }

        function renderCart() {
            const container = document.getElementById('mobileCartList');
            let total = 0;
            if(cart.length === 0) {
                container.innerHTML = `<div class="text-center py-10 text-slate-400 font-bold text-sm bg-white rounded-xl border border-slate-200 border-dashed">Keranjang masih kosong</div>`;
                document.getElementById('payTotalDisplay').innerText = 'Rp 0';
                return;
            }
            container.innerHTML = '';
            cart.forEach(item => {
                const subtotal = item.qty * item.price_sell; total += subtotal;
                const img = item.image_url ? `<img src="${item.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-slate-100"></div>`;
                container.innerHTML += `
                <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-200 flex gap-3 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500"></div>
                    <div class="w-16 h-16 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0 ml-1 border border-slate-100">${img}</div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-slate-800 text-sm leading-tight pr-2">${item.name}</h4>
                            <div class="flex items-center bg-slate-100 rounded-lg p-0.5 border border-slate-200">
                                <button onclick="updateQty(${item.product_id}, -1)" class="w-7 h-7 text-slate-600 font-black text-lg flex justify-center items-center active:bg-slate-200 rounded-md">−</button>
                                <input type="number" value="${item.qty}" onchange="manualUpdateQty(${item.product_id}, this.value)" class="w-8 text-center bg-transparent text-sm font-black p-0 outline-none text-indigo-700">
                                <button onclick="updateQty(${item.product_id}, 1)" class="w-7 h-7 text-indigo-600 font-black text-lg flex justify-center items-center active:bg-indigo-100 rounded-md">+</button>
                            </div>
                        </div>
                        <div class="flex justify-between items-end mt-1">
                            <div>
                                <p class="text-xs font-black text-slate-500">${formatRupiah(item.price_sell)}</p>
                                <p class="text-[10px] text-slate-400 font-bold">Stok: ${item.max_stock}</p>
                            </div>
                            <p class="text-base font-black text-slate-800">${formatRupiah(subtotal)}</p>
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('payTotalDisplay').innerText = formatRupiah(total);
        }

        // --- CUSTOMER LOGIC ---
        async function fetchCustomers() {
            try { const res = await fetch('../api/?action=get_customers'); const data = await res.json();
                if(data.status === 'success') popupCustomersArray = data.data;
            } catch(e) {}
        }
        function openCustomerSelectModal() { 
            document.getElementById('customerSelectModal').classList.remove('hidden'); 
            setTimeout(() => document.getElementById('customerSelectModalContent').classList.remove('translate-y-full'), 10); 
            renderPopupCustomerList(popupCustomersArray); 
        }
        function closeCustomerSelectModal() { 
            document.getElementById('customerSelectModalContent').classList.add('translate-y-full'); 
            setTimeout(() => document.getElementById('customerSelectModal').classList.add('hidden'), 300); 
        }
        function filterPopupCustomers() { const kw = document.getElementById('popupSearchCustomer').value.toLowerCase(); renderPopupCustomerList(popupCustomersArray.filter(c => c.name.toLowerCase().includes(kw) || (c.phone&&c.phone.includes(kw)))); }
        function renderPopupCustomerList(data) {
            const cont = document.getElementById('popupCustomerContainer');
            cont.innerHTML = `<div onclick="executeSelectCustomer('', '-- Pelanggan Umum --')" class="m-2 p-4 bg-white rounded-xl border border-dashed border-slate-300 font-bold text-sm text-indigo-600 flex justify-between">-- Pelanggan Umum -- <span class="bg-slate-100 text-slate-400 px-2 rounded">Default</span></div>`;
            data.forEach(c => { cont.innerHTML += `<div onclick="executeSelectCustomer('${c.id}', '${c.name}')" class="m-2 p-4 bg-white rounded-xl border border-slate-200 font-bold text-sm text-slate-800 active:bg-slate-50">${c.name} <br><span class="text-xs text-slate-400 font-mono">${c.phone||'-'}</span></div>`; });
        }
        function executeSelectCustomer(id, name) { document.getElementById('customerSelect').value = id; document.getElementById('customerSelectedNameDisplay').innerText = name; closeCustomerSelectModal(); }
        
        function triggerCreateFromPopup() { 
            closeCustomerSelectModal(); 
            setTimeout(() => { 
                const m = document.getElementById('customerModal'); m.classList.remove('hidden'); 
                setTimeout(() => { m.classList.remove('opacity-0'); document.getElementById('customerModalContent').classList.remove('scale-95'); }, 10); 
            }, 300); 
        }
        function closeCustomerModal() { 
            const m = document.getElementById('customerModal'); document.getElementById('customerModalContent').classList.add('scale-95'); m.classList.add('opacity-0');
            setTimeout(() => m.classList.add('hidden'), 300); 
        }
        
        async function submitNewCustomer() {
            const name = document.getElementById('custName').value, phone = document.getElementById('custPhone').value, addr = document.getElementById('custAddress').value;
            if(!name) return Toast.fire({icon:'warning', title:'Nama wajib!'});
            try {
                const res = await fetch('../api/?action=save_customer', {method:'POST', body:JSON.stringify({name, phone, address:addr})}); const data = await res.json();
                if(data.status==='success') { Toast.fire({icon:'success', title:'Tersimpan'}); fetchCustomers(); executeSelectCustomer(data.data.id, data.data.name); closeCustomerModal(); }
            } catch(e) {}
        }

        // --- CHECKOUT LOGIC ---
        function openCheckoutMenuModal() {
            if(cart.length===0) return Toast.fire({icon:'warning', title:'Keranjang kosong'});
            totalTagihanCheckout = cart.reduce((s, i) => s + (i.price_sell * i.qty), 0);
            const cont = document.getElementById('checkoutMenuCartItems'); cont.innerHTML = '';
            cart.forEach(i => { cont.innerHTML += `<div class="flex justify-between items-start mb-2 border-b border-slate-200 pb-2"><div class="flex-1 pr-2"><p class="text-sm font-bold text-slate-800">${i.name}</p><p class="text-[10px] text-slate-500">${formatRupiah(i.price_sell)} x ${i.qty}</p></div><span class="font-black">${formatRupiah(i.price_sell*i.qty)}</span></div>`; });
            document.getElementById('checkoutMenuTotalAwal').innerText = formatRupiah(totalTagihanCheckout);
            selectOrderMethod('bayar_langsung');
            
            document.getElementById('checkoutMenuModal').classList.remove('hidden'); 
            setTimeout(() => document.getElementById('checkoutMenuModalContent').classList.remove('translate-y-full'), 10);
        }
        function closeCheckoutMenuModal() { 
            document.getElementById('checkoutMenuModalContent').classList.add('translate-y-full'); 
            setTimeout(() => document.getElementById('checkoutMenuModal').classList.add('hidden'), 300); 
        }
        function selectOrderMethod(m) {
            currentOrderMethod = m;
            ['bayar_langsung','piutang','buat_pesanan','gabung_pesanan'].forEach(id => {
                document.getElementById('om_'+id).className = id===m ? 'border-2 border-red-500 rounded-xl p-3 cursor-pointer bg-red-50 text-center' : 'border border-slate-200 rounded-xl p-3 cursor-pointer bg-white text-center';
            });
            const btn = document.getElementById('btnProceedCheckoutMethod');
            btn.innerText = m==='bayar_langsung' ? 'Lanjut Pembayaran' : m.replace('_',' ').toUpperCase();
            btn.className = m==='bayar_langsung' ? 'w-full bg-emerald-500 active:bg-emerald-700 text-white font-black py-4 rounded-xl text-lg shadow-lg transition-all' : 'w-full bg-red-600 active:bg-red-800 text-white font-black py-4 rounded-xl text-lg shadow-lg transition-all';
        }
        
        function proceedCheckoutMethod() {
            const cust = document.getElementById('customerSelect').value;
            if(currentOrderMethod==='bayar_langsung') { 
                closeCheckoutMenuModal(); setTimeout(openPaymentModal, 300); 
            }
            else { 
                if(!cust) return Swal.fire('Oops','Pilih pelanggan untuk sistem piutang/pesanan','warning');
                if(currentOrderMethod==='buat_pesanan') executeOrderCheckout();
                if(currentOrderMethod==='piutang') executePiutangCheckout(); 
            }
        }
        
        // --- PAYMENT LOGIC ---
        function openPaymentModal() {
            document.getElementById('payTotalDisplay2').innerText = formatRupiah(totalTagihanCheckout);
            selectMethod('CASH'); document.getElementById('inputCash').value=''; calculateChange();
            
            document.getElementById('paymentModal').classList.remove('hidden'); 
            setTimeout(() => document.getElementById('paymentModalContent').classList.remove('translate-y-full'), 10);
        }
        function closePaymentModal() { 
            document.getElementById('paymentModalContent').classList.add('translate-y-full'); 
            setTimeout(() => document.getElementById('paymentModal').classList.add('hidden'), 300); 
        }
        function backToCheckoutMenu() { closePaymentModal(); setTimeout(openCheckoutMenuModal, 300); }
        
        // FIX: Toggle tampilan QRIS & Transfer
        function selectMethod(m) {
            currentPaymentMethod = m;
            ['CASH','QRIS','TRANSFER'].forEach(id => document.getElementById('btnMethod'+id).className = id===m ? `method-btn active bg-indigo-50 border-indigo-500 text-indigo-700 border-2 py-3 rounded-xl text-sm font-bold transition-all` : `method-btn bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all`);
            
            document.getElementById('cashInputSection').classList.add('hidden'); 
            document.getElementById('nonCashSection').classList.add('hidden');
            document.getElementById('qrisView').classList.add('hidden');
            document.getElementById('transferView').classList.add('hidden');

            if(m==='CASH') { 
                document.getElementById('cashInputSection').classList.remove('hidden'); 
                calculateChange(); 
            } else { 
                document.getElementById('nonCashSection').classList.remove('hidden'); 
                document.getElementById('btnConfirmPay').disabled=false; 
                if (m === 'QRIS') { document.getElementById('qrisView').classList.remove('hidden'); document.getElementById('qrisView').classList.add('flex'); }
                if (m === 'TRANSFER') { document.getElementById('transferView').classList.remove('hidden'); document.getElementById('transferView').classList.add('flex'); }
            }
        }

        function calculateChange() {
            if(currentPaymentMethod!=='CASH') return;
            const cash = parseInt(document.getElementById('inputCash').value)||0;
            const btn = document.getElementById('btnConfirmPay');
            if(cash >= totalTagihanCheckout) { document.getElementById('inputChange').value = formatRupiah(cash-totalTagihanCheckout); btn.disabled=false; }
            else { document.getElementById('inputChange').value = 'Uang Kurang'; btn.disabled=true; }
        }
        function setExactCash() { document.getElementById('inputCash').value = totalTagihanCheckout; calculateChange(); }
        
        async function executeCheckout() {
            const btn = document.getElementById('btnConfirmPay'); btn.disabled=true; btn.innerText='Memproses...';
            const payload = {
                cart: cart, total_amount: totalTagihanCheckout, payment_method: currentPaymentMethod, 
                customer_id: document.getElementById('customerSelect').value, payment_proof: document.getElementById('proofBase64').value,
                backdate_time: document.getElementById('inputBackdate').value 
            };
            try {
                const res = await fetch('../api/?action=checkout', {method:'POST', body:JSON.stringify(payload)});
                const data = await res.json();
                if(data.status==='success') { 
                    closePaymentModal(); Swal.fire('Berhasil', 'Transaksi Sukses: '+data.invoice, 'success').then(()=>window.location.reload()); 
                } else { Swal.fire('Gagal', data.message, 'error'); btn.disabled=false; btn.innerText='Konfirmasi Pembayaran';}
            } catch(e) { Swal.fire('Error', 'Jaringan putus', 'error'); btn.disabled=false; }
        }

        // FIX: Eksekusi Beneran untuk Buat Pesanan
        async function executeOrderCheckout() {
            const btn = document.getElementById('btnProceedCheckoutMethod'); btn.disabled = true; btn.innerText='Memproses...';
            try {
                const res = await fetch('../api/?action=checkout', { method: 'POST', body: JSON.stringify({ cart: cart, total_amount: totalTagihanCheckout, order_type: 'order', customer_id: document.getElementById('customerSelect').value, backdate_time: document.getElementById('inputBackdate').value }) });
                const result = await res.json();
                if (result.status === 'success') { closeCheckoutMenuModal(); Swal.fire({icon: 'success', title: 'Pesanan Dibuat!'}).then(() => { clearCart(); document.getElementById('customerSelect').value = ''; document.getElementById('customerSelectedNameDisplay').innerText = '-- Pelanggan Umum --'; }); }
            } catch (error) { console.error(error); } finally { btn.disabled = false; btn.innerText='Buat Pesanan';}
        }
        
        // FIX: Eksekusi Beneran untuk Piutang
        async function executePiutangCheckout() {
            const btn = document.getElementById('btnProceedCheckoutMethod'); btn.disabled = true; btn.innerText='Memproses...';
            try {
                const res = await fetch('../api/?action=checkout', { method: 'POST', body: JSON.stringify({ cart: cart, total_amount: totalTagihanCheckout, payment_method: 'PIUTANG', customer_id: document.getElementById('customerSelect').value, backdate_time: document.getElementById('inputBackdate').value }) });
                const result = await res.json();
                if (result.status === 'success') { closeCheckoutMenuModal(); Swal.fire({icon: 'success', title: 'Piutang Dicatat!'}).then(() => { clearCart(); document.getElementById('customerSelect').value = ''; document.getElementById('customerSelectedNameDisplay').innerText = '-- Pelanggan Umum --'; }); }
            } catch (error) { console.error(error); } finally { btn.disabled = false; btn.innerText='Piutang';}
        }

        document.getElementById('inputPaymentProof').addEventListener('change', function(e) {
            const file = e.target.files[0]; if (!file) return;
            const reader = new FileReader(); reader.onload = function(event) {
                const img = new Image(); img.onload = function() {
                    const canvas = document.createElement('canvas'); const MAX_WIDTH = 600; const MAX_HEIGHT = 600; let width = img.width; let height = img.height;
                    if (width > height) { if (width > MAX_WIDTH) { height *= MAX_WIDTH / width; width = MAX_WIDTH; } } else { if (height > MAX_HEIGHT) { width *= MAX_HEIGHT / height; height = MAX_HEIGHT; } }
                    canvas.width = width; canvas.height = height; const ctx = canvas.getContext('2d'); ctx.drawImage(img, 0, 0, width, height);
                    document.getElementById('proofPreview').src = canvas.toDataURL('image/jpeg', 0.6); document.getElementById('proofPreviewContainer').classList.remove('hidden');
                    document.getElementById('proofBase64').value = canvas.toDataURL('image/jpeg', 0.6); Toast.fire({icon:'success',title:'Bukti siap!'});
                }; img.src = event.target.result;
            }; reader.readAsDataURL(file);
        });

        fetchInitData();
    </script>
</body>
</html>