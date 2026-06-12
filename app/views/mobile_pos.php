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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; } 
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased pb-36 selection:bg-indigo-100">
    
    <div class="bg-white px-5 py-4 sticky top-0 z-30 flex items-center gap-4 shadow-sm border-b border-gray-100">
        <a href="?page=mobile" class="w-10 h-10 bg-gray-50 border border-gray-100 rounded-full flex justify-center items-center text-gray-500 hover:bg-gray-100 active:scale-95 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-lg font-extrabold text-gray-900 leading-tight">Add Order (VIP)</h1>
            <p class="text-[11px] text-indigo-600 font-bold uppercase tracking-wider mt-0.5">Mobile POS Mode</p>
        </div>
    </div>

    <div class="p-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pelanggan</label>
                <div onclick="openCustomerSelectModal()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 flex justify-between items-center active:bg-gray-100 transition-colors cursor-pointer">
                    <span id="customerSelectedNameDisplay">-- Pelanggan Umum --</span>
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7 7-7"></path></svg>
                </div>
                <input type="hidden" id="customerSelect" value="">
            </div>
            <div>
                <div class="flex justify-between items-end mb-2">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">Tanggal Transaksi</label>
                    <span class="text-[10px] font-bold text-amber-500 bg-amber-50 px-2 py-0.5 rounded">Backdate Aktif</span>
                </div>
                <input type="datetime-local" id="inputBackdate" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                <p class="text-[11px] text-gray-400 mt-1.5 font-medium">*Kosongkan jika transaksi realtime hari ini.</p>
            </div>
        </div>
    </div>

    <div class="px-4">
        <button onclick="openProductModal()" class="w-full bg-indigo-50/50 border-2 border-dashed border-indigo-200 text-indigo-600 hover:bg-indigo-50 active:scale-[0.98] transition-all font-bold py-3.5 rounded-2xl flex items-center justify-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Menu Produk
        </button>

        <div id="mobileCartList" class="flex flex-col gap-3">
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <p class="text-gray-400 font-semibold text-sm">Keranjang masih kosong</p>
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-5 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.03)] z-20">
        <div class="flex justify-between items-end mb-3">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Tagihan</span>
            <span class="text-2xl font-black text-emerald-600 leading-none" id="payTotalDisplay">Rp 0</span>
        </div>
        <button onclick="openCheckoutMenuModal()" class="w-full bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-extrabold py-4 rounded-xl text-[15px] tracking-wide shadow-md shadow-indigo-600/20 transition-all">
            CHECK OUT SEKARANG
        </button>
    </div>

    <div id="productModal" class="fixed inset-0 bg-gray-50 z-50 hidden flex-col transition-transform transform translate-y-full duration-300">
        <div class="bg-white px-5 py-4 border-b border-gray-100 flex justify-between items-center shadow-sm">
            <h3 class="text-lg font-extrabold text-gray-900">Pilih Menu</h3>
            <button onclick="closeProductModal()" class="text-gray-400 hover:text-red-500 bg-gray-50 border border-gray-100 p-2 rounded-full active:scale-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 bg-white border-b border-gray-100 shadow-sm z-10">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                <input type="text" id="searchProductInput" oninput="filterProducts()" placeholder="Cari nama menu..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-semibold transition-all">
            </div>
            <div id="categoryFilterContainer" class="flex gap-2 overflow-x-auto hide-scrollbar pt-3 pb-1 mt-1"></div>
        </div>
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50 flex flex-col gap-3" id="mobileProductList">
            <p class="text-center text-gray-400 text-sm py-10 font-medium animate-pulse">Memuat menu...</p>
        </div>
    </div>

    <div id="customerSelectModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[70] hidden flex items-end justify-center transition-opacity">
        <div class="bg-white w-full rounded-t-[1.5rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 flex flex-col max-h-[85vh]" id="customerSelectModalContent">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <h3 class="text-lg font-extrabold text-gray-900">Pilih Konsumen</h3>
                <button onclick="closeCustomerSelectModal()" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 border border-gray-100 p-1.5 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 bg-white border-b border-gray-50 flex gap-2">
                <input type="text" id="popupSearchCustomer" oninput="filterPopupCustomers()" placeholder="Cari nama/WA..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                <button onclick="triggerCreateFromPopup()" class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-xl text-sm flex items-center gap-1 shadow-sm active:scale-95 transition-transform">Baru</button>
            </div>
            <div class="flex-1 overflow-y-auto p-3 bg-gray-50" id="popupCustomerContainer"></div>
        </div>
    </div>

    <div id="customerModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[80] hidden flex items-center justify-center">
        <div class="bg-white w-full max-w-sm mx-4 rounded-[1.5rem] shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="customerModalContent">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="text-lg font-extrabold text-gray-900">Tambah Pelanggan</h3>
                <button onclick="closeCustomerModal()" class="text-gray-400 bg-gray-50 border border-gray-100 hover:bg-gray-100 p-1.5 rounded-full transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-5 space-y-4 max-h-[65vh] overflow-y-auto">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="custName" placeholder="John Doe" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">Nomor WhatsApp</label>
                    <input type="number" id="custPhone" placeholder="08123456789" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">Alamat Rumah/Kantor</label>
                    <textarea id="custAddress" rows="2" placeholder="Nama Jalan, RT/RW, Patokan..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium"></textarea>
                </div>

                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-[11px] font-extrabold text-indigo-800 uppercase tracking-widest">Koordinat Peta (GPS)</label>
                        <button type="button" onclick="detectGPS()" class="text-[10px] bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-2.5 py-1.5 rounded-lg shadow-sm active:scale-95 transition-all flex items-center gap-1">
                            📍 Deteksi Otomatis
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" id="custLat" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-xs font-mono text-gray-600 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Latitude (Cth: -7.797)">
                        <input type="text" id="custLng" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-xs font-mono text-gray-600 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Longitude (Cth: 110.370)">
                    </div>
                </div>

            </div>
            <div class="px-5 py-4 bg-white border-t border-gray-100 flex gap-3">
                <button onclick="closeCustomerModal()" class="flex-1 bg-white border border-gray-200 font-bold text-gray-600 py-2.5 rounded-xl shadow-sm hover:bg-gray-50 active:scale-95 transition-all">Batal</button>
                <button onclick="submitNewCustomer()" id="btnSaveCust" class="flex-1 bg-indigo-600 text-white font-bold py-2.5 rounded-xl shadow-sm hover:bg-indigo-700 active:scale-95 transition-all">Simpan Data</button>
            </div>
        </div>
    </div>

    <div id="checkoutMenuModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[60] hidden flex items-end justify-center">
        <div class="bg-white w-full rounded-t-[1.5rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 flex flex-col max-h-[85vh]" id="checkoutMenuModalContent">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-extrabold text-gray-900">Opsi Checkout</h3>
                <button onclick="closeCheckoutMenuModal()" class="text-gray-400 bg-gray-50 border border-gray-100 p-2 rounded-full active:scale-90 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 bg-white">
                <div class="mb-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Metode Pemesanan</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div onclick="selectOrderMethod('bayar_langsung')" id="om_bayar_langsung" class="border-2 border-red-500 rounded-xl p-3.5 cursor-pointer bg-red-50 text-center transition-all">
                            <h5 class="font-bold text-sm text-gray-900">Bayar Langsung</h5>
                        </div>
                        <div onclick="selectOrderMethod('piutang')" id="om_piutang" class="border border-gray-200 rounded-xl p-3.5 cursor-pointer bg-white text-center hover:bg-gray-50 transition-all">
                            <h5 class="font-bold text-sm text-gray-600">Piutang</h5>
                        </div>
                        <div onclick="selectOrderMethod('buat_pesanan')" id="om_buat_pesanan" class="border border-gray-200 rounded-xl p-3.5 cursor-pointer bg-white text-center hover:bg-gray-50 transition-all">
                            <h5 class="font-bold text-sm text-gray-600">Buat Pesanan</h5>
                        </div>
                        <div onclick="selectOrderMethod('gabung_pesanan')" id="om_gabung_pesanan" class="border border-gray-200 rounded-xl p-3.5 cursor-pointer bg-white text-center hover:bg-gray-50 transition-all">
                            <h5 class="font-bold text-sm text-gray-600">Gabung Pesanan</h5>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Rincian Ringkas</h4>
                    <div id="checkoutMenuCartItems" class="space-y-2 mb-3 text-sm font-medium text-gray-600"></div>
                    <div class="flex justify-between items-end pt-3 border-t border-gray-200">
                        <span class="text-gray-500 font-bold">Total Tagihan</span>
                        <span id="checkoutMenuTotalAwal" class="text-emerald-600 font-black text-xl leading-none">Rp 0</span>
                    </div>
                </div>
            </div>
            <div class="p-5 bg-white border-t border-gray-100">
                <button id="btnProceedCheckoutMethod" onclick="proceedCheckoutMethod()" class="w-full bg-emerald-500 hover:bg-emerald-600 active:scale-[0.98] text-white font-extrabold py-4 rounded-xl text-lg shadow-md shadow-emerald-500/20 transition-all">
                    Lanjut Pembayaran
                </button>
            </div>
        </div>
    </div>

    <div id="paymentModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-[70] hidden flex items-end justify-center">
        <div class="bg-white w-full rounded-t-[1.5rem] shadow-2xl overflow-hidden transform transition-transform translate-y-full duration-300 max-h-[95vh] flex flex-col" id="paymentModalContent">
            <div class="px-4 py-4 border-b border-gray-100 flex items-center gap-3 sticky top-0 bg-white z-10">
                <button onclick="backToCheckoutMenu()" class="text-gray-500 bg-gray-50 border border-gray-100 p-2 rounded-full active:scale-90 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <h3 class="text-lg font-extrabold text-gray-900">Pembayaran Kasir</h3>
            </div>
            
            <div class="p-5 space-y-6 overflow-y-auto bg-white flex-1">
                <div class="text-center bg-gray-50 py-5 rounded-2xl border border-gray-100">
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Total Tagihan</p>
                    <p class="text-4xl font-black text-emerald-500" id="payTotalDisplay2">Rp 0</p>
                </div>
                
                <div>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="selectMethod('CASH')" id="btnMethodCASH" class="method-btn active bg-indigo-50 border-indigo-600 text-indigo-700 border-2 py-3 rounded-xl text-sm font-extrabold transition-all">Tunai</button>
                        <button type="button" onclick="selectMethod('QRIS')" id="btnMethodQRIS" class="method-btn bg-white border-gray-200 text-gray-600 border py-3 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all">QRIS</button>
                        <button type="button" onclick="selectMethod('TRANSFER')" id="btnMethodTRANSFER" class="method-btn bg-white border-gray-200 text-gray-600 border py-3 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all">Transfer</button>
                    </div>
                </div>
                
                <div id="cashInputSection" class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Uang Diterima Pelanggan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">Rp</span>
                            <input type="number" id="inputCash" oninput="calculateChange()" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl font-black text-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="setExactCash()" class="flex-1 bg-white font-bold py-2.5 rounded-xl text-sm text-gray-700 border border-gray-200 active:bg-gray-100 shadow-sm">Uang Pas</button>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Kembalian</label>
                        <input type="text" id="inputChange" readonly class="w-full px-4 py-3.5 bg-red-50/50 border border-red-100 rounded-xl font-black text-xl text-red-600 outline-none" value="Rp 0">
                    </div>
                </div>
                
                <div id="nonCashSection" class="hidden space-y-5 pt-4 border-t border-gray-100">
                    <div id="qrisView" class="hidden flex-col gap-3">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pilih QRIS Tujuan</p>
                        <div id="qrisListContainer" class="flex flex-wrap gap-2">
                            <p class="text-sm text-gray-400 italic font-medium">Memuat data QRIS...</p>
                        </div>
                        <div class="flex justify-center mt-3">
                            <div class="bg-gray-50 p-3 rounded-2xl border border-gray-200">
                                <img id="qrisDisplayImage" src="" alt="QRIS" class="w-48 h-48 object-contain rounded-xl bg-white mix-blend-multiply">
                            </div>
                        </div>
                    </div>
                    <div id="transferView" class="hidden flex-col gap-3">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pilih Rekening Tujuan</p>
                        <div id="transferListContainer" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            <p class="text-sm text-gray-400 italic font-medium">Memuat data rekening...</p>
                        </div>
                    </div>
                    <div class="bg-blue-50/50 border border-blue-100 p-3.5 rounded-xl flex gap-3 items-start">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-blue-800 font-medium leading-relaxed">Pastikan mengecek mutasi masuk sebelum menekan Konfirmasi Pembayaran.</p>
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-gray-500 mb-2">Upload Bukti Bayar <span class="text-red-500">*</span></label>
                        <input type="file" id="inputPaymentProof" accept="image/*" capture="environment" class="block w-full text-sm text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer bg-gray-50 border border-gray-200 rounded-xl">
                        <div id="proofPreviewContainer" class="mt-3 hidden text-center bg-gray-50 p-2.5 rounded-xl border border-gray-200">
                            <img id="proofPreview" src="" class="h-32 object-contain mx-auto rounded-lg shadow-sm" />
                        </div>
                        <input type="hidden" id="proofBase64" value="">
                    </div>
                </div>
            </div>
            <div class="p-5 bg-white border-t border-gray-100">
                <button id="btnConfirmPay" onclick="executeCheckout()" class="w-full bg-indigo-600 active:scale-[0.98] text-white font-extrabold py-4 rounded-xl text-[15px] tracking-wide shadow-md shadow-indigo-600/20 disabled:opacity-50 transition-all">
                    KONFIRMASI TRANSAKSI
                </button>
            </div>
        </div>
    </div>

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
                    renderDynamicPaymentMethods(); 
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

        function renderDynamicPaymentMethods() {
            const transferContainer = document.getElementById('transferListContainer');
            if (transferContainer) {
                let banks = []; try { banks = JSON.parse(appSettings.payment_transfer_list || '[]'); } catch(e){}
                transferContainer.innerHTML = '';
                banks.forEach((b, i) => { transferContainer.innerHTML += `<label class="flex items-center p-3.5 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer active:bg-gray-100 transition-colors"><input type="radio" name="bankTransfer" value="${b.bank}" class="text-indigo-600 focus:ring-indigo-500 w-4 h-4" ${i===0?'checked':''}> <span class="ml-3 font-bold text-sm text-gray-800">${b.bank} <br><span class="font-mono text-indigo-600 text-xs mt-0.5 inline-block">${b.number}</span></span></label>`; });
            }
            const qrisContainer = document.getElementById('qrisListContainer');
            if (qrisContainer) {
                let qrisArr = []; try { qrisArr = JSON.parse(appSettings.payment_qris_list || '[]'); } catch(e){}
                qrisContainer.innerHTML = '';
                if(qrisArr.length > 0 && document.getElementById('qrisDisplayImage')) document.getElementById('qrisDisplayImage').src = qrisArr[0].image;
                qrisArr.forEach((q, i) => { qrisContainer.innerHTML += `<label class="flex-1 text-center py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer active:bg-gray-100 transition-colors"><input type="radio" name="qrisSelect" class="hidden" ${i===0?'checked':''} onchange="document.getElementById('qrisDisplayImage').src='${q.image}'"> <span class="font-bold text-[13px] text-gray-700 block">${q.name}</span></label>`; });
            }
        }

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
            let html = `<button onclick="setCategory('all')" class="px-5 py-2 rounded-full font-bold text-[13px] whitespace-nowrap shadow-sm border transition-colors ${activeCategory === 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-gray-200 text-gray-600'}">Semua</button>`;
            categoriesList.forEach(c => {
                const isActive = activeCategory === c.name;
                html += `<button onclick="setCategory('${c.name}')" class="px-5 py-2 rounded-full font-bold text-[13px] whitespace-nowrap shadow-sm border transition-colors ${isActive ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-gray-200 text-gray-600'}">${c.name}</button>`;
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
                const img = p.image_url ? `<img src="${p.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-gray-100 flex justify-center items-center"><span class="text-2xl text-gray-300">🍽️</span></div>`;
                const action = isAvailable ? `onclick="addToCart(${p.id})"` : `onclick="Toast.fire({icon:'error', title:'Habis!'})"`;
                const opacity = isAvailable ? '' : 'opacity-50 grayscale';
                const badge = isAvailable ? `Sisa: ${p.calculated_stock}` : `HABIS`;
                
                container.innerHTML += `
                    <div ${action} class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm flex gap-4 items-center active:scale-[0.98] transition-transform cursor-pointer ${opacity}">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 border border-gray-50">${img}</div>
                        <div class="flex-1">
                            <h4 class="font-extrabold text-gray-800 leading-tight mb-1 text-[15px]">${p.name}</h4>
                            <div class="flex justify-between items-center mt-1.5">
                                <span class="font-black text-indigo-600">${formatRupiah(p.price_sell)}</span>
                                <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md">${badge}</span>
                            </div>
                        </div>
                        ${isAvailable ? `<div class="w-8 h-8 rounded-full bg-indigo-50 flex justify-center items-center text-indigo-600 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg></div>` : ''}
                    </div>
                `;
            });
        }

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
            products.forEach(p => {
                const used = cart.find(i => i.product_id == p.id)?.qty || 0;
                p.calculated_stock = p.total_stock - used; 
            });
        }

        function renderCart() {
            const container = document.getElementById('mobileCartList');
            let total = 0;
            if(cart.length === 0) {
                container.innerHTML = `<div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center"><div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3"><svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div><p class="text-gray-400 font-semibold text-sm">Keranjang masih kosong</p></div>`;
                document.getElementById('payTotalDisplay').innerText = 'Rp 0';
                return;
            }
            container.innerHTML = '';
            cart.forEach(item => {
                const subtotal = item.qty * item.price_sell; total += subtotal;
                const img = item.image_url ? `<img src="${item.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-gray-100"></div>`;
                container.innerHTML += `
                <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex gap-3 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500"></div>
                    <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 ml-1 border border-gray-100">${img}</div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <h4 class="font-extrabold text-gray-800 text-[14px] leading-tight pr-2">${item.name}</h4>
                            <div class="flex items-center bg-gray-50 rounded-lg p-0.5 border border-gray-200 shadow-sm">
                                <button onclick="updateQty(${item.product_id}, -1)" class="w-7 h-7 text-gray-500 font-bold text-lg flex justify-center items-center active:bg-gray-200 rounded-md transition-colors">−</button>
                                <input type="number" value="${item.qty}" onchange="manualUpdateQty(${item.product_id}, this.value)" class="w-8 text-center bg-transparent text-sm font-black p-0 outline-none text-indigo-600">
                                <button onclick="updateQty(${item.product_id}, 1)" class="w-7 h-7 text-indigo-600 font-bold text-lg flex justify-center items-center active:bg-indigo-100 rounded-md transition-colors">+</button>
                            </div>
                        </div>
                        <div class="flex justify-between items-end mt-1">
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 mb-0.5">${formatRupiah(item.price_sell)}</p>
                            </div>
                            <p class="text-[15px] font-black text-gray-900">${formatRupiah(subtotal)}</p>
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
            cont.innerHTML = `<div onclick="executeSelectCustomer('', '-- Pelanggan Umum --')" class="mb-2 p-4 bg-white rounded-2xl border border-dashed border-gray-300 font-bold text-sm text-indigo-600 flex justify-between cursor-pointer active:bg-gray-50">-- Pelanggan Umum -- <span class="bg-gray-100 text-gray-500 px-2.5 py-0.5 rounded-md text-xs">Default</span></div>`;
            data.forEach(c => { cont.innerHTML += `<div onclick="executeSelectCustomer('${c.id}', '${c.name}')" class="mb-2 p-4 bg-white rounded-2xl border border-gray-200 font-bold text-sm text-gray-800 active:bg-gray-50 cursor-pointer shadow-sm">${c.name} <br><span class="text-[11px] text-gray-400 font-medium mt-1 inline-block">${c.phone||'No HP tidak dicatat'}</span></div>`; });
        }
        
        function executeSelectCustomer(id, name) { document.getElementById('customerSelect').value = id; document.getElementById('customerSelectedNameDisplay').innerText = name; closeCustomerSelectModal(); }
        
        function triggerCreateFromPopup() { 
            closeCustomerSelectModal(); 
            setTimeout(() => { 
                const m = document.getElementById('customerModal'); 
                const c = document.getElementById('customerModalContent');
                m.classList.remove('hidden'); 
                setTimeout(() => { 
                    // FIX: Hapus efek ghoib di elemen Content-nya
                    c.classList.remove('opacity-0', 'scale-95'); 
                }, 10); 
            }, 300); 
        }
        
        function closeCustomerModal() { 
            const m = document.getElementById('customerModal'); 
            const c = document.getElementById('customerModalContent');
            // FIX: Kembalikan efek ghoib pas ditutup
            c.classList.add('opacity-0', 'scale-95'); 
            
            setTimeout(() => {
                m.classList.add('hidden');
                // Reset form saat ditutup
                document.getElementById('custName').value = '';
                document.getElementById('custPhone').value = '';
                document.getElementById('custAddress').value = '';
                document.getElementById('custLat').value = '';
                document.getElementById('custLng').value = '';
            }, 300); 
        }
        
        // FITUR BARU: Deteksi GPS Otomatis
        function detectGPS() {
            if (navigator.geolocation) {
                Toast.fire({ icon: 'info', title: 'Mencari sinyal GPS...' });
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById('custLat').value = position.coords.latitude;
                        document.getElementById('custLng').value = position.coords.longitude;
                        Toast.fire({ icon: 'success', title: 'Kordinat Ditemukan!' });
                    },
                    (error) => { Swal.fire('Gagal', 'Sinyal GPS gagal diakses. Pastikan izin lokasi HP aktif.', 'error'); },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                Swal.fire('Oopss', 'Browser perangkat ini tidak mendukung fitur GPS.', 'warning');
            }
        }

        // UPDATE: Payload dengan Latitude dan Longitude
        async function submitNewCustomer() {
            const name = document.getElementById('custName').value;
            const phone = document.getElementById('custPhone').value;
            const addr = document.getElementById('custAddress').value;
            const lat = document.getElementById('custLat').value;
            const lng = document.getElementById('custLng').value;

            if(!name) return Toast.fire({icon:'warning', title:'Nama wajib diisi!'});

            const btn = document.getElementById('btnSaveCust');
            const originalText = btn.innerText;
            btn.innerText = 'Menyimpan...'; btn.disabled = true;

            try {
                const payload = {
                    name: name, 
                    phone: phone, 
                    address: addr, 
                    latitude: lat, 
                    longitude: lng
                };
                
                const res = await fetch('../api/?action=save_customer', {
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                }); 
                const data = await res.json();
                
                if(data.status === 'success') { 
                    Toast.fire({icon:'success', title:'Tersimpan'}); 
                    fetchCustomers(); 
                    executeSelectCustomer(data.data.id, data.data.name); 
                    closeCustomerModal(); 
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch(e) {
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            } finally {
                btn.innerText = originalText; btn.disabled = false;
            }
        }

        // --- CHECKOUT LOGIC ---
        function openCheckoutMenuModal() {
            if(cart.length===0) return Toast.fire({icon:'warning', title:'Keranjang kosong'});
            totalTagihanCheckout = cart.reduce((s, i) => s + (i.price_sell * i.qty), 0);
            const cont = document.getElementById('checkoutMenuCartItems'); cont.innerHTML = '';
            cart.forEach(i => { cont.innerHTML += `<div class="flex justify-between items-start border-b border-gray-100 pb-2"><div class="flex-1 pr-2"><p class="text-[13px] font-bold text-gray-800">${i.name}</p><p class="text-[11px] text-gray-400 mt-0.5">${formatRupiah(i.price_sell)} x ${i.qty}</p></div><span class="font-black text-gray-700 text-sm">${formatRupiah(i.price_sell*i.qty)}</span></div>`; });
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
                const el = document.getElementById('om_'+id);
                if (id === m) {
                    el.className = 'border-2 border-indigo-500 rounded-xl p-3.5 cursor-pointer bg-indigo-50/50 text-center transition-all shadow-sm';
                    el.querySelector('h5').className = 'font-bold text-sm text-indigo-700';
                } else {
                    el.className = 'border border-gray-200 rounded-xl p-3.5 cursor-pointer bg-gray-50 text-center hover:bg-gray-100 transition-all';
                    el.querySelector('h5').className = 'font-semibold text-sm text-gray-600';
                }
            });
            const btn = document.getElementById('btnProceedCheckoutMethod');
            btn.innerText = m==='bayar_langsung' ? 'Lanjut Pembayaran' : m.replace('_',' ').toUpperCase();
            btn.className = m==='bayar_langsung' ? 'w-full bg-emerald-500 hover:bg-emerald-600 active:scale-[0.98] text-white font-extrabold py-4 rounded-xl text-lg shadow-md shadow-emerald-500/20 transition-all' : 'w-full bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-extrabold py-4 rounded-xl text-lg shadow-md shadow-indigo-600/20 transition-all';
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
        
        function selectMethod(m) {
            currentPaymentMethod = m;
            ['CASH','QRIS','TRANSFER'].forEach(id => {
                document.getElementById('btnMethod'+id).className = id===m ? `method-btn active bg-indigo-50 border-indigo-600 text-indigo-700 border-2 py-3.5 rounded-xl text-[13px] font-extrabold transition-all shadow-sm` : `method-btn bg-white border-gray-200 text-gray-500 border py-3.5 rounded-xl text-[13px] font-bold hover:bg-gray-50 transition-all`;
            });
            
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

        async function executeOrderCheckout() {
            const btn = document.getElementById('btnProceedCheckoutMethod'); btn.disabled = true; btn.innerText='Memproses...';
            try {
                const res = await fetch('../api/?action=checkout', { method: 'POST', body: JSON.stringify({ cart: cart, total_amount: totalTagihanCheckout, order_type: 'order', customer_id: document.getElementById('customerSelect').value, backdate_time: document.getElementById('inputBackdate').value }) });
                const result = await res.json();
                if (result.status === 'success') { closeCheckoutMenuModal(); Swal.fire({icon: 'success', title: 'Pesanan Dibuat!'}).then(() => { clearCart(); document.getElementById('customerSelect').value = ''; document.getElementById('customerSelectedNameDisplay').innerText = '-- Pelanggan Umum --'; }); }
            } catch (error) { console.error(error); } finally { btn.disabled = false; btn.innerText='Buat Pesanan';}
        }
        
        async function executePiutangCheckout() {
            const btn = document.getElementById('btnProceedCheckoutMethod'); btn.disabled = true; btn.innerText='Memproses...';
            try {
                const res = await fetch('../api/?action=checkout', { method: 'POST', body: JSON.stringify({ cart: cart, total_amount: totalTagihanCheckout, payment_method: 'PIUTANG', customer_id: document.getElementById('customerSelect').value, backdate_time: document.getElementById('inputBackdate').value }) });
                const result = await res.json();
                if (result.status === 'success') { closeCheckoutMenuModal(); Swal.fire({icon: 'success', title: 'Piutang Dicatat!'}).then(() => { clearCart(); document.getElementById('customerSelect').value = ''; document.getElementById('customerSelectedNameDisplay').innerText = '-- Pelanggan Umum --'; }); }
            } catch (error) { console.error(error); } finally { btn.disabled = false; btn.innerText='Piutang';}
        }

        function clearCart() { cart = []; renderCart(); }

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