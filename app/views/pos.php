<div class="flex flex-col md:flex-row w-full flex-1 overflow-hidden">
    
    <main class="flex-1 flex flex-col h-full relative border-r border-slate-200 dark:border-slate-800">
        <div class="px-4 md:px-6 pt-5 pb-1 grid grid-cols-2 lg:grid-cols-4 gap-3 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
            <div class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center">
                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">Modal Laci</span>
                <span class="font-black text-slate-800 dark:text-slate-100 text-lg" id="statModal">Rp 0</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center">
                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">Cash Masuk</span>
                <span class="font-black text-emerald-600 dark:text-emerald-400 text-lg" id="statCash">Rp 0</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center">
                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">QRIS</span>
                <span class="font-black text-sky-600 dark:text-sky-400 text-lg" id="statQris">Rp 0</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center">
                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">Transfer</span>
                <span class="font-black text-amber-600 dark:text-amber-400 text-lg" id="statTransfer">Rp 0</span>
            </div>
        </div>
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-4 bg-white dark:bg-slate-900 z-10">
            <div class="relative flex-1">
                <input type="text" id="searchInput" placeholder="Cari nama produk atau SKU..." class="w-full bg-slate-100 dark:bg-slate-800 border border-transparent dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-lg px-5 py-3.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-lg placeholder-slate-500 shadow-inner">
            </div>

                
                <div onclick="window.location.href='<?= APP_URL ?? '/yoripos' ?>/admin/?page=transactions'" title="Lihat Riwayat Transaksi" class="cursor-pointer hover:scale-105 hover:shadow-md active:scale-95 transition-all bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 px-3 py-2 rounded-xl text-xs font-bold border border-emerald-200 dark:border-emerald-800 flex flex-col items-center min-w-[70px]">
                    <span class="text-[9px] uppercase tracking-wider opacity-70">Lunas</span>
                    <span class="text-lg leading-none mt-0.5"><span id="badgeKwi">0</span> <span class="text-[10px]">KWI</span></span>
                </div>
                
                <div onclick="window.location.href='<?= APP_URL ?? '/yoripos' ?>/admin/?page=orders'" title="Lihat Antrean Pesanan" class="cursor-pointer hover:scale-105 hover:shadow-md active:scale-95 transition-all bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 px-3 py-2 rounded-xl text-xs font-bold border border-amber-200 dark:border-amber-800 flex flex-col items-center min-w-[70px]">
                    <span class="text-[9px] uppercase tracking-wider opacity-70">Pesanan</span>
                    <span class="text-lg leading-none mt-0.5"><span id="badgeOrd">0</span> <span class="text-[10px]">ORD</span></span>
                </div>
                
                <div onclick="window.location.href='<?= APP_URL ?? '/yoripos' ?>/admin/?page=receivables'" title="Lihat Tagihan Piutang" class="cursor-pointer hover:scale-105 hover:shadow-md active:scale-95 transition-all bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 px-3 py-2 rounded-xl text-xs font-bold border border-red-200 dark:border-red-800 flex flex-col items-center min-w-[70px]">
                    <span class="text-[9px] uppercase tracking-wider opacity-70">Piutang</span>
                    <span class="text-lg leading-none mt-0.5"><span id="badgeInv">0</span> <span class="text-[10px]">INV</span></span>
                </div>

            <button onclick="checkShiftForClose()" class="p-3 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition-all shadow-sm font-bold text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="hidden sm:inline">Tutup Kasir</span>
            </button>
            <button id="themeToggle" class="p-3 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shadow-sm">
                <svg id="themeToggleDarkIcon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                <svg id="themeToggleLightIcon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
            <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5"></div>
        </div>
    </main>

    <aside class="w-full md:w-[400px] bg-white dark:bg-slate-800 flex flex-col h-full z-20 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.1)] transition-colors duration-300">
        <div class="p-4 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors duration-300">
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 tracking-wide">Daftar Pesanan</h2>
            <button class="text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-400 text-sm font-medium transition-colors" onclick="clearCart()">Kosongkan</button>
        </div>
        
        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 transition-colors duration-300">
            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Pelanggan Transaksi</label>
            <div class="flex gap-2">
                <div onclick="openCustomerSelectModal()" class="flex-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-sm px-4 py-2.5 focus:ring-1 focus:ring-indigo-500 cursor-pointer transition-all text-slate-700 dark:text-slate-200 font-bold flex justify-between items-center shadow-sm hover:border-indigo-400 group">
                    <span id="customerSelectedNameDisplay">-- Pelanggan Umum --</span>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <input type="hidden" id="customerSelect" value="">
            </div>
        </div>
        <div id="cartItems" class="flex-1 overflow-y-auto p-4 flex flex-col gap-3"></div>
        
        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700 transition-colors duration-300 flex flex-col gap-4">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Tagihan</p>
                <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400" id="payTotalDisplay">Rp 0</p>
            </div>

            <?php 
            // MENU RAHASIA: HANYA MUNCUL JIKA USER PUNYA AKSES 'settings'
            $perms = $_SESSION['permissions'] ?? [];
            if(in_array('settings', $perms)): 
            ?>
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 rounded-xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 opacity-10 rotate-12 pointer-events-none">
                    <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                </div>
                <label class="block text-xs font-black text-amber-700 dark:text-amber-400 mb-2 uppercase tracking-wider">🔥 VIP MODE: Backdate Transaksi</label>
                <input type="datetime-local" id="inputBackdate" class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 border border-amber-300 dark:border-amber-700 rounded-lg text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-amber-500 outline-none relative z-10">
                <p class="text-[10px] text-amber-600 dark:text-amber-500 mt-2 font-medium relative z-10">*Kosongkan jika transaksi realtime. Pastikan periode bulan tsb belum di-closing!</p>
            </div>
            <?php endif; ?>
            
            <button id="btnOpenPayment" onclick="openCheckoutMenuModal()" class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold py-4 rounded-xl text-lg tracking-widest shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] transition duration-200">
                CHECKOUT
            </button>
        </div>
    </aside>

</div>

<!-- MODAL MENU CHECKOUT (LEVEL 1: Pilih Metode Pesanan) -->
<div id="checkoutMenuModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] hidden flex items-end sm:items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full sm:w-[500px] sm:rounded-3xl rounded-t-3xl shadow-2xl overflow-hidden transform transition-all translate-y-full sm:translate-y-0 sm:scale-95 flex flex-col max-h-[95vh]" id="checkoutMenuModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center bg-white dark:bg-slate-800 sticky top-0 z-10">
            <button onclick="closeCheckoutMenuModal()" class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Checkout</h3>
        </div>
        <div class="p-6 overflow-y-auto flex-1 space-y-6">
            <div>
                <h4 class="font-bold text-slate-800 dark:text-slate-100 mb-3 text-lg">Metode Pemesanan</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div onclick="selectOrderMethod('bayar_langsung')" id="om_bayar_langsung" class="border-2 border-red-500 rounded-xl p-4 cursor-pointer transition-colors bg-red-50 dark:bg-red-900/20">
                        <h5 class="font-bold text-sm text-slate-800 dark:text-slate-100">Bayar Langsung</h5>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pembeli langsung melakukan pembayaran.</p>
                    </div>
                    <div onclick="selectOrderMethod('piutang')" id="om_piutang" class="border border-slate-200 dark:border-slate-600 rounded-xl p-4 cursor-pointer hover:border-red-300 transition-colors">
                        <h5 class="font-bold text-sm text-slate-800 dark:text-slate-100">Piutang</h5>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pembeli berhutang kepada penjual.</p>
                    </div>
                    <div onclick="selectOrderMethod('buat_pesanan')" id="om_buat_pesanan" class="border border-slate-200 dark:border-slate-600 rounded-xl p-4 cursor-pointer hover:border-red-300 transition-colors">
                        <h5 class="font-bold text-sm text-slate-800 dark:text-slate-100">Buat Pesanan</h5>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pembayaran dilakukan setelah proses pemesanan selesai.</p>
                    </div>
                    <div onclick="selectOrderMethod('gabung_pesanan')" id="om_gabung_pesanan" class="border border-slate-200 dark:border-slate-600 rounded-xl p-4 cursor-pointer hover:border-red-300 transition-colors">
                        <h5 class="font-bold text-sm text-slate-800 dark:text-slate-100">Gabungkan Pesanan</h5>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Penggabungan dengan pesanan yang sudah ada.</p>
                    </div>
                </div>
            </div>
            <hr class="border-slate-200 dark:border-slate-700">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 dark:text-slate-100">Diskon Transaksi</span>
                        <span class="text-[10px] bg-slate-200 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 px-1.5 py-0.5 rounded text-slate-600 dark:text-slate-300">PRO</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500">Tidak</span>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full relative cursor-not-allowed opacity-60">
                            <div class="w-5 h-5 bg-white rounded-full absolute left-0.5 top-0.5 shadow-sm"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-800 dark:text-slate-100">Biaya Tambahan</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500">Tidak</span>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full relative cursor-not-allowed opacity-60">
                            <div class="w-5 h-5 bg-white rounded-full absolute left-0.5 top-0.5 shadow-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="border-slate-200 dark:border-slate-700">
            <div>
                <h4 class="font-bold text-slate-800 dark:text-slate-100 mb-4 text-lg">Rincian Pesanan</h4>
                <div id="checkoutMenuCartItems" class="space-y-4 mb-5 max-h-48 overflow-y-auto pr-2"></div>
                <div class="space-y-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex justify-between items-center font-bold text-sm text-slate-800 dark:text-slate-100">
                        <span>Total Pesanan</span>
                        <span id="checkoutMenuTotalPesanan">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center font-black text-xl">
                        <span class="text-slate-800 dark:text-slate-100">Total</span>
                        <span id="checkoutMenuTotalAwal" class="text-emerald-500">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6 pt-4 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700">
            <button id="btnProceedCheckoutMethod" onclick="proceedCheckoutMethod()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl text-lg shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] transition duration-200">
                Lanjut Pembayaran
            </button>
        </div>
    </div>
</div>

<!-- MODAL PEMBAYARAN (LEVEL 2) -->
<div id="paymentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full sm:w-[450px] sm:rounded-3xl rounded-t-3xl shadow-2xl overflow-hidden transform transition-all translate-y-full sm:translate-y-0 sm:scale-95" id="paymentModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <button onclick="backToCheckoutMenu()" class="text-slate-400 hover:text-slate-600 transition-colors mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                Pembayaran
            </h3>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Tagihan</p>
                <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400" id="payTotalDisplay">Rp 0</p>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-3">Metode Pembayaran</p>
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" onclick="selectMethod('CASH')" id="btnMethodCASH" class="method-btn active bg-indigo-50 border-indigo-500 text-indigo-700 border-2 py-3 rounded-xl text-sm font-bold transition-all">Tunai</button>
                    <button type="button" onclick="selectMethod('QRIS')" id="btnMethodQRIS" class="method-btn bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all">QRIS</button>
                    <button type="button" onclick="selectMethod('TRANSFER')" id="btnMethodTRANSFER" class="method-btn bg-white border-slate-200 text-slate-600 border py-3 rounded-xl text-sm font-bold transition-all">Transfer</button>
                </div>
            </div>
            <div id="cashInputSection" class="space-y-4 pt-2 border-t border-slate-100 dark:border-slate-700">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Jumlah Uang Diterima</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 font-bold">Rp</span>
                            <input type="number" id="inputCash" oninput="calculateChange()" class="w-full pl-9 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Kembalian</label>
                        <input type="text" id="inputChange" readonly class="w-full px-3 py-3 bg-slate-100 border border-transparent rounded-xl font-bold text-lg text-slate-600 outline-none" value="Rp 0">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2" id="quickCashButtons">
                    <button type="button" onclick="setExactCash()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 rounded-lg text-sm transition-colors">Uang Pas</button>
                </div>
            </div>
            
            <div id="nonCashSection" class="hidden space-y-5 pt-4 border-t border-slate-100 dark:border-slate-700">
                <div id="qrisView" class="hidden flex-col gap-3">
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Pilih QRIS Tujuan</p>
                    <div id="qrisListContainer" class="flex flex-wrap gap-3">
                        <p class="text-sm text-slate-400 italic">Memuat data QRIS...</p>
                    </div>
                    <div class="flex justify-center mt-2">
                        <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200">
                            <img id="qrisDisplayImage" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'><rect width='200' height='200' fill='%23f1f5f9'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' font-weight='bold' fill='%2394a3b8'>Pilih QRIS</text></svg>" alt="QRIS" class="w-48 h-48 object-contain rounded-lg">
                        </div>
                    </div>
                </div>
                <div id="transferView" class="hidden flex-col gap-3">
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Pilih Rekening Tujuan</p>
                    <div id="transferListContainer" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        <p class="text-sm text-slate-400 italic">Memuat data rekening...</p>
                    </div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-3 rounded-xl flex gap-3 items-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-blue-800 dark:text-blue-300">Pastikan mengecek mutasi masuk sebelum menekan Konfirmasi Pembayaran.</p>
                </div>
                <div class="pt-2">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Upload Bukti Bayar <span class="text-red-500">*</span></label>
                    <input type="file" id="inputPaymentProof" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer bg-slate-50 border border-slate-200 rounded-xl">
                    <div id="proofPreviewContainer" class="mt-3 hidden text-center bg-slate-50 p-2 rounded-xl border border-slate-200">
                        <img id="proofPreview" src="" class="h-32 object-contain mx-auto rounded-lg" />
                    </div>
                    <input type="hidden" id="proofBase64" value="">
                </div>
            </div>
        </div>
        <div class="p-6 pt-0">
            <button id="btnConfirmPay" onclick="executeCheckout()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl text-lg shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                Konfirmasi Pembayaran
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah Pelanggan Baru -->
<div id="customerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-95" id="customerModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Tambah Pelanggan</h3>
            <button onclick="closeCustomerModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="custName" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-1 focus:ring-indigo-500 outline-none text-sm dark:text-slate-100" placeholder="Misal: John Doe">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">No. WhatsApp</label>
                <input type="number" id="custPhone" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-1 focus:ring-indigo-500 outline-none text-sm dark:text-slate-100" placeholder="Misal: 08123456789">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Alamat (Untuk Delivery)</label>
                <textarea id="custAddress" rows="3" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-1 focus:ring-indigo-500 outline-none text-sm dark:text-slate-100" placeholder="Jalan, RT/RW, Patokan..."></textarea>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 flex gap-3">
            <button onclick="closeCustomerModal()" class="flex-1 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-bold py-2.5 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
            <button onclick="submitNewCustomer()" id="btnSaveCust" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition duration-200 shadow-sm">Simpan</button>
        </div>
    </div>
</div>

<!-- MODAL POPUP PILIH PELANGGAN -->
<div id="customerSelectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-95 flex flex-col max-h-[85vh]" id="customerSelectModalContent">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Pilih Konsumen
            </h3>
            <button onclick="closeCustomerSelectModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                <input type="text" id="popupSearchCustomer" oninput="filterPopupCustomers()" placeholder="Ketik nama atau nomor WA..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl pl-9 pr-4 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all">
            </div>
            <button onclick="triggerCreateFromPopup()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-xl text-sm transition duration-200 flex items-center gap-1 flex-shrink-0 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Baru
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-2 bg-slate-50/50 dark:bg-slate-900/20" id="popupCustomerContainer"></div>
    </div>
</div>

<!-- MODAL SHIFT KASIR (BARU) -->
<div id="shiftModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[999] hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl p-6 transform transition-all scale-95" id="shiftModalContent">
        <!-- Shift Open View -->
        <div id="shiftOpenView" class="hidden">
            <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-2 text-center">Buka Shift Kasir</h3>
            <p class="text-sm text-slate-500 text-center mb-6">Masukkan jumlah uang modal awal (kembalian) yang ada di laci saat ini.</p>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Uang Modal Awal (Rp)</label>
                    <input type="number" id="inputStartingCash" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-xl font-bold focus:ring-2 focus:ring-indigo-500" value="0">
                </div>
                <button onclick="submitOpenShift()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition duration-200">
                    Buka Kasir & Mulai Jualan
                </button>
            </div>
        </div>

        <!-- Shift Close View -->
        <div id="shiftCloseView" class="hidden">
            <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-4 text-center">Tutup Shift Kasir</h3>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 mb-4">
                <p class="text-xs font-bold text-blue-800 dark:text-blue-400 mb-1">Uang Seharusnya di Laci (Sistem)</p>
                <p id="shiftExpectedCash" class="text-2xl font-black text-blue-900 dark:text-blue-300">Rp 0</p>
                <p class="text-[10px] text-blue-600 mt-1">Modal Awal + Transaksi Tunai</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Uang Fisik Sebenarnya (Hitung Manual) <span class="text-red-500">*</span></label>
                    <input type="number" id="inputActualCash" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-xl font-bold text-emerald-600 focus:ring-2 focus:ring-emerald-500" placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Catatan (Opsional)</label>
                    <textarea id="inputShiftNotes" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm" rows="2" placeholder="Tulis alasan jika ada selisih uang..."></textarea>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button onclick="hideShiftModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-3 rounded-xl transition duration-200">Batal</button>
                    <button onclick="submitCloseShift()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-200">Akhiri Shift</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- LOGIKA TEMA KHUSUS KASIR ---
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeToggleDarkIcon = document.getElementById('themeToggleDarkIcon');
    const themeToggleLightIcon = document.getElementById('themeToggleLightIcon');

    function updateIcon() {
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden'); themeToggleDarkIcon.classList.add('hidden');
        } else {
            themeToggleLightIcon.classList.add('hidden'); themeToggleDarkIcon.classList.remove('hidden');
        }
    }
    updateIcon();
    themeToggleBtn.addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        updateIcon();
    });

    // --- LOGIKA UPDATE MANUAL KUANTITAS PRODUK ---
    function manualUpdateQty(productId, value) {
        const itemIndex = cart.findIndex(item => item.product_id == productId);
        if (itemIndex > -1) {
            let newQty = parseInt(value);
            if (isNaN(newQty) || newQty < 1) newQty = 1;
            
            if (newQty <= cart[itemIndex].max_stock) {
                cart[itemIndex].qty = newQty;
            } else {
                cart[itemIndex].qty = cart[itemIndex].max_stock;
                Toast.fire({ icon: 'warning', title: `Stok maksimal tercapai! Sisa: ${cart[itemIndex].max_stock}` });
            }
            renderCart();
        }
    }

    // --- STATE & DATA ---
    let products = [];
    let cart = [];
    let appSettings = {};
    let currentPaymentMethod = 'CASH';
    let totalTagihanCheckout = 0;
    let rawMaterialStocks = {}; 
    let originalCustomers = [];
    let popupCustomersArray = [];

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true });

    // --- FUNGSI CUSTOMER ---
    async function fetchCustomers() {
        try {
            const response = await fetch('../api/?action=get_customers');
            const result = await response.json();
            if (result.status === 'success') {
                originalCustomers = result.data;
            }
        } catch (error) { console.error('Gagal fetch pelanggan:', error); }
    }

    function openCustomerSelectModal() {
        const modal = document.getElementById('customerSelectModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            document.getElementById('customerSelectModalContent').classList.remove('scale-95');
        }, 10);
        document.getElementById('popupSearchCustomer').value = ''; 
        loadPopupCustomersData(); 
    }

    function closeCustomerSelectModal() {
        const modal = document.getElementById('customerSelectModal');
        modal.classList.add('opacity-0');
        document.getElementById('customerSelectModalContent').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    async function loadPopupCustomersData() {
        const container = document.getElementById('popupCustomerContainer');
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                <svg class="animate-spin h-8 w-8 text-indigo-500 mb-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p class="text-sm">Memuat database konsumen...</p>
            </div>`;

        try {
            const response = await fetch('../api/?action=get_customers');
            const result = await response.json();
            if (result.status === 'success') {
                popupCustomersArray = result.data;
                renderPopupCustomerList(popupCustomersArray);
            }
        } catch (error) { console.error(error); }
    }

    function renderPopupCustomerList(data) {
        const container = document.getElementById('popupCustomerContainer');
        container.innerHTML = `
            <div onclick="executeSelectCustomer('', '-- Pelanggan Umum --')" class="m-2 p-3 bg-white dark:bg-slate-800 rounded-xl border border-dashed border-slate-300 dark:border-slate-600 hover:border-indigo-500 flex justify-between items-center cursor-pointer transition-all shadow-sm">
                <div><p class="font-bold text-sm text-indigo-600 dark:text-indigo-400">-- Pelanggan Umum --</p><p class="text-xs text-slate-400 mt-0.5">Tanpa pencatatan piutang khusus</p></div>
                <span class="text-xs font-bold text-slate-400 uppercase bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded">Default</span>
            </div>`;

        data.forEach(cust => {
            container.innerHTML += `
                <div onclick="executeSelectCustomer('${cust.id}', '${cust.name}')" class="m-2 p-3.5 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 flex justify-between items-center cursor-pointer transition-all shadow-sm group">
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-100 text-sm group-hover:text-indigo-600">${cust.name}</p>
                        <p class="text-xs text-slate-400 font-mono mt-1">${cust.phone || 'Tidak ada nomor'}</p>
                    </div>
                </div>`;
        });
    }

    function filterPopupCustomers() {
        const keyword = document.getElementById('popupSearchCustomer').value.toLowerCase();
        const filtered = popupCustomersArray.filter(c => c.name.toLowerCase().includes(keyword) || (c.phone && c.phone.includes(keyword)));
        renderPopupCustomerList(filtered);
    }

    function executeSelectCustomer(id, name) {
        document.getElementById('customerSelect').value = id;
        document.getElementById('customerSelectedNameDisplay').innerText = name;
        closeCustomerSelectModal();
    }

    function triggerCreateFromPopup() {
        closeCustomerSelectModal();
        setTimeout(() => { openCustomerModal(); }, 200);
    }

    function openCustomerModal() {
        document.getElementById('custName').value = '';
        document.getElementById('custPhone').value = '';
        document.getElementById('custAddress').value = '';
        const modal = document.getElementById('customerModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('customerModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeCustomerModal() {
        const modal = document.getElementById('customerModal');
        modal.classList.add('opacity-0');
        document.getElementById('customerModalContent').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    async function submitNewCustomer() {
        const name = document.getElementById('custName').value;
        const phone = document.getElementById('custPhone').value;
        const address = document.getElementById('custAddress').value;
        const btn = document.getElementById('btnSaveCust');

        if(!name) { Toast.fire({ icon: 'warning', title: 'Nama pelanggan wajib diisi!' }); return; }

        btn.disabled = true; btn.innerText = 'Menyimpan...';
        try {
            const response = await fetch('../api/?action=save_customer', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, phone, address })
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                Toast.fire({ icon: 'success', title: result.message });
                closeCustomerModal();
                await fetchCustomers();
                document.getElementById('customerSelect').value = result.data.id; 
                document.getElementById('customerSelectedNameDisplay').innerText = result.data.name;
            } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message }); }
        } catch (error) { console.error(error); } 
        finally { btn.disabled = false; btn.innerText = 'Simpan'; }
    }

    // --- FUNGSI PRODUK & CART ---
    async function fetchProducts() {
        try {
            const response = await fetch('../api/?action=get_products');
            const result = await response.json();
            if (result.status === 'success') { 
                products = result.data.filter(p => p.type === 'produk_jual'); 
                rawMaterialStocks = {};
                products.forEach(p => {
                    if (p.recipe_details && p.recipe_details.length > 0) {
                        p.recipe_details.forEach(mat => { rawMaterialStocks[mat.material_id] = mat.material_stock; });
                    }
                });
                updateDynamicStocks(); renderProducts(products); 
            }
        } catch (error) { console.error('Gagal mengambil data:', error); }
    }

    function updateDynamicStocks() {
        let usedMaterials = {};
        cart.forEach(item => {
            const product = products.find(p => p.id == item.product_id);
            if (product && product.recipe_details && product.recipe_details.length > 0) {
                product.recipe_details.forEach(mat => {
                    if (!usedMaterials[mat.material_id]) usedMaterials[mat.material_id] = 0;
                    usedMaterials[mat.material_id] += (mat.qty_required * item.qty);
                });
            }
        });

        products.forEach(p => {
            const isRecipe = parseInt(p.has_recipe) > 0;
            if (isRecipe && p.recipe_details.length > 0) {
                let max_possible = 999999;
                p.recipe_details.forEach(mat => {
                    const originalStock = rawMaterialStocks[mat.material_id] || 0;
                    const usedStock = usedMaterials[mat.material_id] || 0;
                    const remainingStock = originalStock - usedStock;
                    const possible = Math.floor(remainingStock / mat.qty_required);
                    if (possible < max_possible) max_possible = possible;
                });
                p.calculated_stock = max_possible < 0 ? 0 : max_possible;
            } else {
                const usedQty = cart.find(i => i.product_id == p.id)?.qty || 0;
                p.calculated_stock = p.total_stock - usedQty;
            }
        });
    }

    function renderProducts(items) {
        const grid = document.getElementById('productGrid');
        grid.innerHTML = ''; 
        items.forEach(product => {
            const isRecipe = parseInt(product.has_recipe) > 0;
            const availableToSell = parseInt(product.calculated_stock); 
            let stockBadge, clickEvent, opacityClass;

            if (availableToSell > 0) {
                stockBadge = isRecipe ? `<span class="text-[10px] px-2 py-1 bg-amber-100 text-amber-700 rounded-md font-bold">🛠️ BISA: ${availableToSell}</span>` : `<span class="text-xs text-slate-500 font-bold bg-slate-100 px-2 py-1 rounded-md">Sisa: ${availableToSell}</span>`;
                clickEvent = `onclick="addToCart(${product.id})"`;
                opacityClass = 'cursor-pointer active:scale-95 hover:border-indigo-500 hover:shadow-lg';
            } else {
                stockBadge = `<span class="text-[10px] px-2 py-1 bg-red-50 text-red-500 rounded-md font-bold border border-red-200">HABIS</span>`;
                clickEvent = `onclick="Toast.fire({ icon: 'error', title: 'Stok/Bahan Habis!' })"`;
                opacityClass = 'opacity-50 grayscale-[40%] cursor-not-allowed';
            }

            const imgHtml = product.image_url ? `<img src="${product.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-slate-100"></div>`;
            grid.innerHTML += `
                <div ${clickEvent} class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 transition-all flex flex-col group overflow-hidden ${opacityClass}">
                    <div class="h-32 w-full bg-slate-50 relative">${imgHtml}</div>
                    <div class="p-4 flex flex-col justify-between flex-1">
                        <h3 class="font-medium text-sm text-slate-800 dark:text-slate-200">${product.name}</h3>
                        <div class="mt-3 flex justify-between items-end"><p class="font-bold text-lg text-indigo-600">${(product.price_sell / 1000)}K</p>${stockBadge}</div>
                    </div>
                </div>`;
        });
    }

    function addToCart(productId) {
        const product = products.find(p => p.id == productId);
        if (!product || product.calculated_stock <= 0) { Toast.fire({ icon: 'warning', title: 'Stok tidak mencukupi!' }); return; }
        const existingItem = cart.find(item => item.product_id == productId);
        if (existingItem) existingItem.qty += 1; else cart.push({ product_id: product.id, name: product.name, price_sell: product.price_sell, qty: 1 });
        updateDynamicStocks(); renderProducts(products); renderCart();
    }

    function updateQty(productId, change) {
        const itemIndex = cart.findIndex(item => item.product_id == productId);
        if (itemIndex > -1) {
            const product = products.find(p => p.id == productId);
            if (change > 0 && product.calculated_stock <= 0) { Toast.fire({ icon: 'warning', title: 'Stok tidak mencukupi!' }); return; }
            const newQty = cart[itemIndex].qty + change;
            if (newQty > 0) cart[itemIndex].qty = newQty; else cart.splice(itemIndex, 1); 
            updateDynamicStocks(); renderProducts(products); renderCart();
        }
    }

    function clearCart() { cart = []; updateDynamicStocks(); renderProducts(products); renderCart(); }

    function renderCart() {
        const cartContainer = document.getElementById('cartItems');
        const cartTotal = document.getElementById('payTotalDisplay');
        cartContainer.innerHTML = ''; let total = 0;
        if (cart.length === 0) { cartContainer.innerHTML = '<div class="text-center text-slate-400 mt-10 text-sm">Keranjang kosong</div>'; cartTotal.innerText = 'Rp 0'; return; }

        cart.forEach(item => {
            total += item.price_sell * item.qty;
            cartContainer.innerHTML += `
                <div class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-xl border border-slate-200 dark:border-slate-600 flex justify-between items-center">
                    <div class="flex-1 pr-2">
                        <h4 class="text-slate-800 dark:text-slate-200 font-medium text-sm line-clamp-1">${item.name}</h4>
                        <p class="text-indigo-600 dark:text-indigo-400 font-bold text-sm mt-1">${formatRupiah(item.price_sell)}</p>
                    </div>
                    <div class="flex items-center bg-white dark:bg-slate-800 rounded-lg border border-slate-200 shadow-sm p-1">
                        <button onclick="updateQty(${item.product_id}, -1)" class="text-slate-500 hover:text-red-500 px-2 text-lg font-bold">−</button>
                        <input type="number" value="${item.qty}" min="1" onchange="manualUpdateQty(${item.product_id}, this.value)" class="w-12 text-center bg-transparent border-0 font-bold">
                        <button onclick="updateQty(${item.product_id}, 1)" class="text-slate-500 hover:text-indigo-500 px-2 text-lg font-bold">+</button>
                    </div>
                </div>`;
        });
        cartTotal.innerText = formatRupiah(total);
    }

    // --- LOGIKA SHIFT KASIR ---
    async function checkOpenShift() {
        try {
            const res = await fetch('../api/?action=shift_status');
            const result = await res.json();
            
            if (result.status === 'error') {
                if (result.message === 'Tidak ada shift aktif') {
                    showShiftModal('open');
                } else {
                    console.error("System Error Shift:", result.message);
                }
            } else if (result.status === 'success') {
                // UPDATE ANGKA KEUANGAN
                document.getElementById('statModal').innerText = formatRupiah(result.data.starting_cash);
                document.getElementById('statCash').innerText = formatRupiah(result.data.cash_sales);
                document.getElementById('statQris').innerText = formatRupiah(result.data.qris_sales);
                document.getElementById('statTransfer').innerText = formatRupiah(result.data.transfer_sales);
                
                // UPDATE ANGKA DOKUMEN (BARU)
                document.getElementById('badgeKwi').innerText = result.data.count_kwi;
                document.getElementById('badgeOrd').innerText = result.data.count_ord;
                document.getElementById('badgeInv').innerText = result.data.count_inv;
                
                if(document.getElementById('shiftExpectedCash')) {
                    document.getElementById('shiftExpectedCash').innerText = formatRupiah(result.data.expected_cash);
                }
            }
        } catch (e) { console.error('Gagal cek shift', e); }
    }

    async function checkShiftForClose() {
        try {
            const res = await fetch('../api/?action=shift_status');
            const result = await res.json();
            if (result.status === 'success') {
                document.getElementById('shiftExpectedCash').innerText = formatRupiah(result.data.expected_cash);
                showShiftModal('close');
            } else { Swal.fire('Oops', 'Tidak ada shift aktif!', 'warning'); }
        } catch (e) { console.error(e); }
    }

    function showShiftModal(type) {
        document.getElementById('shiftOpenView').classList.add('hidden');
        document.getElementById('shiftCloseView').classList.add('hidden');
        if(type === 'open') document.getElementById('shiftOpenView').classList.remove('hidden');
        else document.getElementById('shiftCloseView').classList.remove('hidden');

        const modal = document.getElementById('shiftModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('shiftModalContent').classList.remove('scale-95'); }, 10);
    }

    function hideShiftModal() {
        const modal = document.getElementById('shiftModal');
        modal.classList.add('opacity-0');
        document.getElementById('shiftModalContent').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    async function submitOpenShift() {
        const startingCash = document.getElementById('inputStartingCash').value || 0;
        try {
            const res = await fetch('../api/?action=shift_open', {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ starting_cash: startingCash })
            });
            const result = await res.json();
            
            if(result.status === 'success') { 
                Swal.fire('Shift Dibuka', 'Selamat bertugas!', 'success'); 
                hideShiftModal(); 
            } else {
                // TAMBAHAN BARU: Nampilin error asli dari backend!
                Swal.fire('Gagal Buka Shift', result.message, 'error'); 
            }
        } catch (e) { 
            Swal.fire('Error System', 'Gagal memproses permintaan ke server.', 'error'); 
        }
    }

    async function submitCloseShift() {
        const actualCash = document.getElementById('inputActualCash').value;
        const notes = document.getElementById('inputShiftNotes').value;
        if(actualCash === '') { Swal.fire('Peringatan', 'Harap masukkan uang fisik sebenarnya!', 'warning'); return; }

        try {
            const res = await fetch('../api/?action=shift_close', {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ actual_cash: actualCash, notes: notes })
            });
            const result = await res.json();
            
            if(result.status === 'success') {
                Swal.fire({
                    title: 'Shift Ditutup!',
                    text: 'Silakan ambil dan serahkan struk rekap ke Finance.',
                    icon: 'success',
                    confirmButtonText: 'Cetak Rekap & Reload',
                    allowOutsideClick: false
                }).then(() => {
                    printShiftRecap(result.print_data);
                    setTimeout(() => { window.location.reload(); }, 1500);
                });
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        } catch (e) { Swal.fire('Error', 'Gagal menutup shift', 'error'); }
    }

    // FUNGSI CETAK STRUK REKAP END OF DAY (58mm)
    function printShiftRecap(data) {
        let receiptHTML = `
        <!DOCTYPE html><html><head><style>
            body { font-family: monospace; font-size: 12px; margin: 0; padding: 0; width: 58mm; } 
            h2 { font-size: 14px; margin: 0; } 
            p { margin: 0; font-size: 10px; } 
            table { width: 100%; font-size: 10px; border-collapse: collapse; margin-top:5px; margin-bottom:5px;} 
            .center { text-align: center; } .right { text-align: right; } 
            .line { border-bottom: 1px dashed black; margin: 5px 0; } 
            @page { margin: 0; size: 80mm auto; }
        </style></head><body>
            <div class="center" style="margin-bottom: 10px;">
                <h2>${appSettings.store_name || 'NAMA TOKO'}</h2>
                <p>REKAP TUTUP KASIR (END OF DAY)</p>
            </div>
            <div class="line"></div>
            <p>Kasir : ${data.user_name}</p>
            <p>Buka  : ${data.open_time}</p>
            <p>Tutup : ${data.close_time}</p>
            <div class="line"></div>
            <table>
                <tr><td>Modal Awal Laci</td><td class="right">${formatRupiah(data.starting_cash)}</td></tr>
                <tr><td>Penjualan Tunai</td><td class="right">${formatRupiah(data.cash_sales)}</td></tr>
            </table>
            <div class="line"></div>
            <table>
                <tr><td><strong>TOTAL SEHARUSNYA</strong></td><td class="right"><strong>${formatRupiah(data.expected_cash)}</strong></td></tr>
                <tr><td><strong>UANG FISIK DI LACI</strong></td><td class="right"><strong>${formatRupiah(data.actual_cash)}</strong></td></tr>
            </table>
            <div class="line"></div>
            <table>
                <tr>
                    <td>SELISIH (Over/Short)</td>
                    <td class="right" style="font-weight:bold;">${formatRupiah(data.difference)}</td>
                </tr>
            </table>
            <div class="line"></div>
            <p>Catatan: ${data.notes || '-'}</p>
            <div class="line"></div>
            <div class="center" style="margin-top: 15px; margin-bottom: 30px;">
                <p>Diserahkan Oleh,</p><br><br><br><p>( ${data.user_name} )</p>
            </div>
        </body></html>`;

        const iframe = document.createElement('iframe'); 
        iframe.style.display = 'none'; 
        document.body.appendChild(iframe);
        iframe.contentDocument.open(); 
        iframe.contentDocument.write(receiptHTML); 
        iframe.contentDocument.close();
        
        setTimeout(() => { 
            iframe.contentWindow.focus(); 
            iframe.contentWindow.print(); 
            setTimeout(() => document.body.removeChild(iframe), 1000); 
        }, 250);
    }

    // --- CHECKOUT LEVEL 1 & 2 LOGIC ---
    let currentOrderMethod = 'bayar_langsung';

    function openCheckoutMenuModal() {
        if (cart.length === 0) { Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Keranjang kosong!' }); return; }
        const container = document.getElementById('checkoutMenuCartItems');
        container.innerHTML = ''; totalTagihanCheckout = 0;

        cart.forEach(item => {
            const subtotal = item.price_sell * item.qty; totalTagihanCheckout += subtotal;
            container.innerHTML += `
                <div class="flex justify-between items-start"><div class="flex-1 pr-4"><p class="text-sm font-medium text-slate-800 dark:text-slate-200">${item.name}</p></div>
                <div class="text-right"><span class="text-sm text-slate-500">${formatRupiah(item.price_sell)} x ${item.qty}</span><span class="font-bold ml-2">${formatRupiah(subtotal)}</span></div></div>`;
        });
        document.getElementById('checkoutMenuTotalPesanan').innerText = formatRupiah(totalTagihanCheckout);
        document.getElementById('checkoutMenuTotalAwal').innerText = formatRupiah(totalTagihanCheckout);

        selectOrderMethod('bayar_langsung');
        const modal = document.getElementById('checkoutMenuModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('checkoutMenuModalContent').classList.remove('translate-y-full', 'sm:scale-95'); }, 10);
    }

    function closeCheckoutMenuModal() {
        const modal = document.getElementById('checkoutMenuModal');
        modal.classList.add('opacity-0'); document.getElementById('checkoutMenuModalContent').classList.add('translate-y-full', 'sm:scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function selectOrderMethod(method) {
        currentOrderMethod = method;
        ['bayar_langsung', 'piutang', 'buat_pesanan', 'gabung_pesanan'].forEach(m => {
            const el = document.getElementById('om_' + m);
            el.className = m === method ? 'border-2 border-red-500 rounded-xl p-4 cursor-pointer bg-red-50 dark:bg-red-900/20' : 'border border-slate-200 rounded-xl p-4 cursor-pointer hover:border-red-300';
        });
        const btn = document.getElementById('btnProceedCheckoutMethod');
        btn.innerText = method === 'bayar_langsung' ? 'Lanjut Pembayaran' : method.replace('_', ' ').toUpperCase();
        btn.className = method === 'bayar_langsung' ? 'w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg' : 'w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg';
    }

    function proceedCheckoutMethod() {
        const custId = document.getElementById('customerSelect').value;
        if(currentOrderMethod === 'bayar_langsung') { closeCheckoutMenuModal(); setTimeout(() => { openPaymentModal(); }, 300); } 
        else if (currentOrderMethod === 'buat_pesanan') { if(!custId) { Swal.fire({icon: 'warning', text: 'Pilih pelanggan dulu!'}); return; } executeOrderCheckout(); } 
        else if (currentOrderMethod === 'piutang') { if(!custId) { Swal.fire({icon: 'warning', text: 'Pilih pelanggan dulu!'}); return; } executePiutangCheckout(); }
    }

    async function executePiutangCheckout() {
        const btn = document.getElementById('btnProceedCheckoutMethod'); btn.disabled = true;
        try {
            const res = await fetch('../api/?action=checkout', { method: 'POST', body: JSON.stringify({ cart: cart, total_amount: totalTagihanCheckout, payment_method: 'PIUTANG', customer_id: document.getElementById('customerSelect').value }) });
            const result = await res.json();
            if (result.status === 'success') { closeCheckoutMenuModal(); Swal.fire({icon: 'success', title: 'Piutang Dicatat!'}).then(() => { clearCart(); document.getElementById('customerSelect').value = ''; document.getElementById('customerSelectedNameDisplay').innerText = '-- Pelanggan Umum --'; }); }
        } catch (error) { console.error(error); } finally { btn.disabled = false; }
    }

    async function executeOrderCheckout() {
        const btn = document.getElementById('btnProceedCheckoutMethod'); btn.disabled = true;
        try {
            const res = await fetch('../api/?action=checkout', { method: 'POST', body: JSON.stringify({ cart: cart, total_amount: totalTagihanCheckout, order_type: 'order', customer_id: document.getElementById('customerSelect').value }) });
            const result = await res.json();
            if (result.status === 'success') { closeCheckoutMenuModal(); Swal.fire({icon: 'success', title: 'Pesanan Dibuat!'}).then(() => { clearCart(); document.getElementById('customerSelect').value = ''; document.getElementById('customerSelectedNameDisplay').innerText = '-- Pelanggan Umum --'; }); }
        } catch (error) { console.error(error); } finally { btn.disabled = false; }
    }

    // --- MODAL PEMBAYARAN ---
    function openPaymentModal() {
        totalTagihanCheckout = cart.reduce((sum, item) => sum + (item.price_sell * item.qty), 0);
        document.getElementById('payTotalDisplay').innerText = formatRupiah(totalTagihanCheckout);
        selectMethod('CASH'); document.getElementById('inputCash').value = ''; document.getElementById('inputChange').value = 'Rp 0';
        const modal = document.getElementById('paymentModal'); modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('paymentModalContent').classList.remove('translate-y-full'); }, 10);
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal'); modal.classList.add('opacity-0'); document.getElementById('paymentModalContent').classList.add('translate-y-full');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
    function backToCheckoutMenu() { closePaymentModal(); setTimeout(() => { openCheckoutMenuModal(); }, 300); }

    function selectMethod(method) {
        currentPaymentMethod = method;
        ['CASH', 'QRIS', 'TRANSFER'].forEach(m => { document.getElementById(`btnMethod${m}`).className = m === method ? `method-btn active bg-indigo-50 border-indigo-500 text-indigo-700 border-2 py-3 rounded-xl font-bold` : `method-btn bg-white border-slate-200 text-slate-600 border py-3 rounded-xl font-bold`; });

        const cashSection = document.getElementById('cashInputSection'); const nonCashSection = document.getElementById('nonCashSection');
        const qrisView = document.getElementById('qrisView'); const transferView = document.getElementById('transferView');
        const btnConfirm = document.getElementById('btnConfirmPay');

        cashSection.classList.add('hidden'); nonCashSection.classList.add('hidden'); qrisView.classList.add('hidden'); transferView.classList.add('hidden');

        if (method === 'CASH') { cashSection.classList.remove('hidden'); calculateChange(); } 
        else {
            nonCashSection.classList.remove('hidden'); btnConfirm.disabled = false;
            if (method === 'QRIS') { qrisView.classList.remove('hidden'); qrisView.classList.add('flex'); } 
            else if (method === 'TRANSFER') { transferView.classList.remove('hidden'); }
        }
    }

    function calculateChange() {
        if (currentPaymentMethod !== 'CASH') return;
        const uangDiterima = parseInt(document.getElementById('inputCash').value) || 0;
        const btnConfirm = document.getElementById('btnConfirmPay');
        if (uangDiterima >= totalTagihanCheckout) { document.getElementById('inputChange').value = formatRupiah(uangDiterima - totalTagihanCheckout); btnConfirm.disabled = false; } 
        else { document.getElementById('inputChange').value = "Uang Kurang!"; btnConfirm.disabled = true; }
    }
    function setExactCash() { document.getElementById('inputCash').value = totalTagihanCheckout; calculateChange(); }

    async function executeCheckout() {
        if (cart.length === 0) return;
        let paymentProof = '';
        if (currentPaymentMethod !== 'CASH') {
            paymentProof = document.getElementById('proofBase64').value;
            if (!paymentProof) { Swal.fire('Bukti Bayar Wajib!', 'Silakan upload bukti transfer/QRIS.', 'warning'); return; }
        }

        const btnConfirm = document.getElementById('btnConfirmPay'); btnConfirm.disabled = true; btnConfirm.innerText = 'MEMPROSES...';
        try {
            const response = await fetch('../api/?action=checkout', { method: 'POST', body: JSON.stringify({ cart: cart, total_amount: totalTagihanCheckout, payment_method: currentPaymentMethod, customer_id: document.getElementById('customerSelect').value, payment_proof: paymentProof }) });
            const result = await response.json();
            if (result.status === 'success') {
                const savedCart = [...cart]; const savedTotal = totalTagihanCheckout; closePaymentModal();
                Swal.fire({icon: 'success', title: 'Transaksi Berhasil!', html: `No: <b>${result.invoice}</b>`, confirmButtonText: 'Cetak Struk & Tutup'}).then((res) => { if(res.isConfirmed) { printReceipt(result.invoice, savedCart, savedTotal); clearCart(); fetchProducts(); }});
            } else { Swal.fire('Gagal', result.message, 'error'); }
        } catch (error) { Swal.fire('Error', 'Kesalahan jaringan.', 'error'); } finally { btnConfirm.innerText = 'Konfirmasi Pembayaran'; btnConfirm.disabled = false; }
    }

    function printReceipt(invoice, cartData, totalAmount) {
        const date = new Date().toLocaleString('id-ID');
        let receiptHTML = `<!DOCTYPE html><html><head><style>body { font-family: monospace; font-size: 12px; margin: 0; padding: 0; width: 58mm; } h2 { font-size: 14px; margin: 0; } p { margin: 0; font-size: 10px; } table { width: 100%; font-size: 10px; border-collapse: collapse; } .center { text-align: center; } .right { text-align: right; } .line { border-bottom: 1px dashed black; margin: 5px 0; } @page { margin: 0; size: 80mm auto; }</style></head><body><div class="center" style="margin-bottom: 10px;"><h2>${appSettings.store_name || 'NAMA TOKO'}</h2><p>${appSettings.store_address || ''}</p><p>WA: ${appSettings.store_phone || ''}</p></div><div class="line"></div><p>No: ${invoice}</p><p>Tgl: ${date}</p><p>Pembayaran: ${currentPaymentMethod}</p><div class="line"></div><table>`;
        cartData.forEach(item => { receiptHTML += `<tr><td colspan="3">${item.name}</td></tr><tr><td style="width: 20%;">${item.qty}x</td><td style="width: 40%;">${formatRupiah(item.price_sell)}</td><td style="width: 40%;" class="right">${formatRupiah(item.price_sell * item.qty)}</td></tr>`; });
        receiptHTML += `</table><div class="line"></div><table style="font-weight: bold;"><tr><td>TOTAL</td><td class="right">${formatRupiah(totalAmount)}</td></tr></table><div class="line"></div><div class="center" style="margin-top: 10px;"><p>${(appSettings.receipt_footer || 'Terima Kasih').replace(/\n/g, '<br>')}</p><p>YoriPOS</p></div></body></html>`;

        const iframe = document.createElement('iframe'); iframe.style.display = 'none'; document.body.appendChild(iframe);
        iframe.contentDocument.open(); iframe.contentDocument.write(receiptHTML); iframe.contentDocument.close();
        setTimeout(() => { iframe.contentWindow.focus(); iframe.contentWindow.print(); setTimeout(() => document.body.removeChild(iframe), 1000); }, 250);
    }

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
            if(qrisArr.length > 0) document.getElementById('qrisDisplayImage').src = qrisArr[0].image;
            qrisArr.forEach((q, i) => { qrisContainer.innerHTML += `<label class="flex-1 text-center py-2 px-3 border rounded-xl"><input type="radio" name="qrisSelect" class="hidden" ${i===0?'checked':''} onchange="document.getElementById('qrisDisplayImage').src='${q.image}'"> <span class="font-bold text-sm block">${q.name}</span></label>`; });
        }
    }

    async function fetchAppSettings() {
        try { const res = await fetch('../api/?action=get_settings'); const data = await res.json(); if (data.status === 'success') { appSettings = data.data; renderDynamicPaymentMethods(); } } catch (e) { console.error('Gagal settings'); }
    }

    document.getElementById('inputPaymentProof').addEventListener('change', function(e) {
        const file = e.target.files[0]; if (!file) return;
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas'); const MAX_WIDTH = 600; const MAX_HEIGHT = 600; let width = img.width; let height = img.height;
                if (width > height) { if (width > MAX_WIDTH) { height *= MAX_WIDTH / width; width = MAX_WIDTH; } } else { if (height > MAX_HEIGHT) { width *= MAX_HEIGHT / height; height = MAX_HEIGHT; } }
                canvas.width = width; canvas.height = height; const ctx = canvas.getContext('2d'); ctx.drawImage(img, 0, 0, width, height);
                const dataUrl = canvas.toDataURL('image/jpeg', 0.6);
                document.getElementById('proofPreview').src = dataUrl; document.getElementById('proofPreviewContainer').classList.remove('hidden'); document.getElementById('proofBase64').value = dataUrl; 
            }; img.src = event.target.result;
        }; reader.readAsDataURL(file);
    });

    // INISIALISASI UTAMA
    document.addEventListener('DOMContentLoaded', () => { 
        fetchAppSettings(); 
        fetchProducts(); 
        fetchCustomers(); 
        renderCart(); 
        checkOpenShift(); // <-- VALIDASI SHIFT JALAN DI SINI
    });
</script>