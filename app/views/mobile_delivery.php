<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kurir Delivery - Yori App</title>
    <link rel="manifest" href="/manifest.json">
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased pb-10">

    <div class="max-w-md mx-auto relative shadow-xl min-h-screen bg-slate-50">
        
        <div class="bg-white px-5 py-4 border-b border-slate-200 sticky top-0 z-40 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-lg font-black text-slate-800 leading-none">Daftar Kiriman</h1>
                <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mt-1">SELURUH ANTREAN</p>
            </div>
        </div>

        <div class="p-4 flex flex-col gap-3" id="deliveryList">
            <div class="text-center py-10 text-slate-400 animate-pulse font-bold text-sm">Mencari pesanan aktif...</div>
        </div>
    </div>

    <div id="orderDetailModal" class="fixed inset-0 bg-slate-50 z-[60] hidden flex-col transition-transform transform translate-y-full duration-300">
        <div class="bg-white px-5 py-4 border-b border-slate-200 flex justify-between items-center shadow-sm sticky top-0">
            <h3 class="text-lg font-black text-slate-800">Detail Pengiriman</h3>
            <button onclick="closeOrderDetail()" class="text-slate-400 hover:text-red-500 bg-slate-100 p-2 rounded-full active:scale-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-5 space-y-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                    <div class="w-12 h-12 bg-indigo-50 rounded-full flex justify-center items-center text-indigo-600 text-xl border border-indigo-100">👤</div>
                    <div>
                        <p class="font-black text-slate-800 text-lg" id="detailCust">-</p>
                        <p class="text-xs font-bold text-indigo-600" id="detailInv">-</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-0.5"><svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg></div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-600 leading-relaxed font-medium" id="detailAddress">-</p>
                        <div id="detailMapContainer" class="mt-2"></div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-slate-400 mb-2 text-[10px] uppercase tracking-widest pl-2">Rincian Menu</h4>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 space-y-3" id="detailItems"></div>
                
                <div class="mt-3 bg-indigo-50 p-5 rounded-2xl flex justify-between items-center border border-indigo-100 shadow-sm">
                    <span class="font-bold text-indigo-800 text-sm">TOTAL TAGIHAN</span>
                    <span class="font-black text-2xl text-indigo-700" id="detailTotal">Rp 0</span>
                </div>

                <button onclick="sendReceiptWA()" class="w-full mt-3 bg-[#25D366] hover:bg-[#1ebd5a] text-white shadow-sm active:scale-95 transition-transform font-bold py-3.5 rounded-xl text-sm flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Kirim Nota via WA
                </button>
            </div>

            <div class="mt-2 pt-5 border-t border-slate-200">
                <div class="flex gap-3">
                    <button onclick="processPaymentApi('PIUTANG')" class="w-1/3 bg-orange-50 text-orange-600 border border-orange-200 hover:bg-orange-100 active:bg-orange-200 font-bold py-3.5 rounded-xl text-sm transition-all flex flex-col justify-center items-center">
                        <span class="block">PIUTANG</span>
                    </button>
                    <button onclick="showLunasOptions()" class="w-2/3 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-black py-3.5 rounded-xl text-lg shadow-lg shadow-emerald-200 transition-all flex justify-center items-center gap-2 tracking-wide">
                        LUNAS
                    </button>
                </div>
            </div>
            <div class="h-6"></div>
        </div>
    </div>
<?php include 'components/bottomnav.php'; ?>
    <script>
        const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

        let currentOrders = [];
        let activeOrderId = null; 
        let appSettings = {}; 

        // 1. Tarik Setting
        async function fetchSettings() {
            try {
                const res = await fetch('../api/?action=get_settings');
                const data = await res.json();
                
                if(data.status === 'success') {
                    if (Array.isArray(data.data)) {
                        data.data.forEach(row => { appSettings[row.setting_key] = row.setting_value; });
                    } else {
                        appSettings = data.data;
                    }
                }
            } catch(e) { console.error('Gagal narik setting:', e); }
        }

        // 2. Tarik Daftar Orderan Kurir
        async function fetchDeliveries() {
            await fetchSettings();

            const container = document.getElementById('deliveryList');
            try {
                const response = await fetch('../api/?action=get_orders');
                const result = await response.json();
                
                if (result.status === 'success') {
                    // FILTER SAKTI: Kurir cuma boleh lihat yang depannya ORD-
                    currentOrders = result.data.filter(ord => ord.invoice_number.startsWith('ORD-')); 
                    
                    if (currentOrders.length === 0) {
                        container.innerHTML = `<div class="bg-white p-8 rounded-2xl text-center border border-slate-200 shadow-sm"><span class="text-4xl block mb-3 opacity-50">🎉</span><p class="text-slate-500 font-bold text-sm">Mantap! Semua pesanan sudah terkirim.</p></div>`;
                        return;
                    }
                    
                    container.innerHTML = '';
                    currentOrders.forEach(ord => {
                        let rawPhone = ord.customer_phone || ''; 
                        let waLink = '';
                        if (rawPhone.startsWith('0')) { rawPhone = '62' + rawPhone.substring(1); }
                        if (rawPhone.length > 8) { waLink = `https://wa.me/${rawPhone}`; }
                        
                        let itemsHtmlExpanded = '';
                        ord.items.forEach(item => {
                            itemsHtmlExpanded += `
                                <div class="flex justify-between items-center py-1.5 border-b border-slate-100/70 last:border-0">
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-700 text-[11px]">${item.product_name}</p>
                                        <p class="text-[9px] text-slate-500">${item.qty} x ${formatRupiah(item.price_sell)}</p>
                                    </div>
                                    <span class="font-black text-slate-800 text-[11px]">${formatRupiah(item.price_sell * item.qty)}</span>
                                </div>`;
                        });

                        container.innerHTML += `
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-3">
                                <div onclick="toggleExpand(${ord.id})" class="p-4 flex justify-between items-center cursor-pointer active:bg-slate-50 transition-colors">
                                    <div class="flex-1">
                                        <h3 class="font-black text-[15px] text-slate-800">${ord.customer_name || 'Pelanggan Umum'}</h3>
                                        <p class="text-[11px] text-slate-500 font-medium mt-0.5"><span class="font-bold text-indigo-600">${ord.invoice_number}</span></p>
                                    </div>
                                    <div class="text-right flex items-center gap-3">
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-semibold uppercase">Total</p>
                                            <p class="font-black text-emerald-600 text-sm">${formatRupiah(ord.total_amount)}</p>
                                        </div>
                                        <svg id="icon-${ord.id}" class="w-5 h-5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                
                                <div id="expand-${ord.id}" class="hidden px-4 pb-4 pt-1 border-t border-slate-100 bg-slate-50/50">
                                    <div class="flex items-start gap-2 mb-3 mt-2">
                                        <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <p class="text-xs text-slate-600 font-medium line-clamp-2">${ord.customer_address || '<i class="text-slate-400">Alamat tidak dicatat</i>'}</p>
                                    </div>
                                    
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-100 mb-3 shadow-[0_2px_4px_rgba(0,0,0,0.02)]">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 border-b border-slate-100 pb-1">Daftar Item</p>
                                        ${itemsHtmlExpanded}
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        ${waLink ? `<a href="${waLink}" target="_blank" class="w-12 h-10 bg-[#25D366] text-white rounded-xl flex items-center justify-center shrink-0 shadow-sm active:scale-95 transition-transform"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg></a>` : ``}
                                        <button onclick="openOrderDetail(${ord.id})" class="flex-1 bg-indigo-600 text-white text-sm font-bold rounded-xl h-10 shadow-sm active:scale-95 transition-transform">PROSES SEKARANG</button>
                                    </div>
                                </div>
                            </div>`;
                    });
                }
            } catch (error) { container.innerHTML = `<div class="text-center text-red-500 py-10 font-bold">Gagal memuat data.</div>`; }
        }

        function toggleExpand(id) {
            const exp = document.getElementById(`expand-${id}`);
            const icon = document.getElementById(`icon-${id}`);
            if (exp.classList.contains('hidden')) {
                exp.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                exp.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        function openOrderDetail(id) {
            const ord = currentOrders.find(o => o.id == id);
            if(!ord) return;
            activeOrderId = id; 

            document.getElementById('detailInv').innerText = ord.invoice_number;
            document.getElementById('detailCust').innerText = ord.customer_name || 'Pelanggan Umum';
            document.getElementById('detailAddress').innerText = ord.customer_address || 'Alamat tidak dicatat di sistem.';
            document.getElementById('detailTotal').innerText = formatRupiah(ord.total_amount);

            // FIX: Perbaikan Link Google Maps Universal
            let mapContainer = document.getElementById('detailMapContainer');
            if (ord.customer_latitude && ord.customer_longitude && ord.customer_latitude !== "0" && ord.customer_longitude !== "0") {
                // Pakai URL standar https://www.google.com/maps?q=LAT,LONG
                mapContainer.innerHTML = `<a href="https://www.google.com/maps?q=${ord.customer_latitude},${ord.customer_longitude}" target="_blank" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold border border-blue-100 hover:bg-blue-100 active:scale-95 transition-all mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Buka di Google Maps</a>`;
            } else {
                mapContainer.innerHTML = `<span class="inline-block text-[10px] text-slate-400 italic mt-1 bg-slate-100 px-2 py-1 rounded">📍 Pin lokasi tidak tersedia</span>`;
            }

            let itemsHtml = '';
            ord.items.forEach(item => {
                itemsHtml += `
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2 last:border-0 last:pb-0">
                        <div class="flex-1">
                            <p class="font-bold text-slate-800 text-sm">${item.product_name}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">${item.qty} x ${formatRupiah(item.price_sell)}</p>
                        </div>
                        <span class="font-black text-slate-700">${formatRupiah(item.price_sell * item.qty)}</span>
                    </div>`;
            });
            document.getElementById('detailItems').innerHTML = itemsHtml;

            const m = document.getElementById('orderDetailModal');
            m.classList.remove('hidden');
            setTimeout(() => m.classList.remove('translate-y-full'), 10);
        }

        function closeOrderDetail() {
            activeOrderId = null;
            const m = document.getElementById('orderDetailModal');
            m.classList.add('translate-y-full');
            setTimeout(() => m.classList.add('hidden'), 300);
        }

        function sendReceiptWA() {
            if(!activeOrderId) return;
            const ord = currentOrders.find(o => o.id == activeOrderId);
            if(!ord) return;

            let rawPhone = ord.customer_phone || ''; 
            // Format angka 0 jadi 62 untuk API WhatsApp
            if (rawPhone.startsWith('0')) { rawPhone = '62' + rawPhone.substring(1); }
            
            if (rawPhone.length < 8) {
                Swal.fire('Info', 'Nomor WA pelanggan tidak tercatat di sistem.', 'info');
                return;
            }

            const invoice = ord.invoice_number;
            
            // Baca domain sistem (localhost/domain lu) dinamis agar linknya valid
            const baseUrl = window.location.origin;
            const linkUrl = `${baseUrl}/api/?action=view_receipt&invoice=${invoice}`;
            
            // Sedot nama toko dan format template WA dari appSettings (database settings lu)
            let storeName = appSettings.store_name || 'PT Jogjatama Vishesha';
            let template = appSettings.wa_receipt_format || "Halo kak ini {invoice} yang bisa di buka / download di {link}.\nTerima kasih sudah berbelanja di {store_name}";

            // Replace wildcard {invoice}, {link}, {store_name} dengan data aslinya
            let message = template
                .replace(/{invoice}/g, invoice)
                .replace(/{link}/g, linkUrl)
                .replace(/{store_name}/g, storeName);

            // Buka link WhatsApp 
            const waUrl = `https://wa.me/${rawPhone}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        // ==========================================
        // ALUR PEMBAYARAN KASIR
        // ==========================================

        function showLunasOptions() {
            if(!activeOrderId) return;
            Swal.fire({
                title: '<span class="text-lg font-black text-slate-800">Pilih Metode Lunas</span>',
                html: `
                    <div class="grid grid-cols-1 gap-3 mt-2">
                        <button onclick="Swal.close(); showCashInput()" class="bg-[#10b981] hover:bg-[#059669] text-white p-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm transition-colors text-sm">
                            💵 TUNAI / CASH
                        </button>
                        <button onclick="Swal.close(); showQrisUpload()" class="bg-[#3b82f6] hover:bg-[#2563eb] text-white p-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm transition-colors text-sm">
                            📱 SCAN QRIS
                        </button>
                        <button onclick="Swal.close(); showTransferUpload()" class="bg-[#8b5cf6] hover:bg-[#7c3aed] text-white p-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm transition-colors text-sm">
                            💳 TRANSFER BANK
                        </button>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: { popup: 'rounded-3xl' }
            });
        }

        // BARU: Alur Khusus Hitung Kembalian CASH
        function showCashInput() {
            const ord = currentOrders.find(o => o.id == activeOrderId);
            const totalTagihan = parseFloat(ord.total_amount);

            Swal.fire({
                title: 'Pembayaran Tunai',
                html: `
                    <div class="space-y-4 text-left pt-2">
                        <div class="text-center bg-slate-50 py-4 rounded-2xl border border-slate-100 mb-2">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Tagihan</p>
                            <p class="text-3xl font-black text-emerald-500">${formatRupiah(totalTagihan)}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Uang Diterima dari Pelanggan</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-black">Rp</span>
                                <input type="number" id="kurirInputCash" oninput="kurirCalcChange(${totalTagihan})" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-black text-xl outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('kurirInputCash').value = ${totalTagihan}; kurirCalcChange(${totalTagihan});" class="flex-1 bg-slate-100 font-bold py-2.5 rounded-lg text-sm text-slate-700 border border-slate-200 active:bg-slate-200">Uang Pas</button>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Kembalian</label>
                            <input type="text" id="kurirInputChange" readonly class="w-full px-4 py-3 bg-red-50 border border-red-100 rounded-xl font-black text-xl text-red-600 outline-none" value="Rp 0">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Selesaikan Pesanan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                didOpen: () => {
                    // Tombol Konfirmasi disabled pas baru buka (uang belum cukup)
                    Swal.getConfirmButton().disabled = true;
                },
                preConfirm: () => {
                    const cash = parseFloat(document.getElementById('kurirInputCash').value) || 0;
                    if (cash < totalTagihan) {
                        Swal.showValidationMessage('Uang tidak cukup!');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    processPaymentApi('CASH');
                }
            });
        }

        // BARU: Logika Hitung Kembalian Kurir Realtime
        function kurirCalcChange(total) {
            const cash = parseFloat(document.getElementById('kurirInputCash').value) || 0;
            const changeField = document.getElementById('kurirInputChange');
            const confirmBtn = Swal.getConfirmButton();
            
            if (cash >= total) {
                changeField.value = formatRupiah(cash - total);
                confirmBtn.disabled = false;
            } else {
                changeField.value = 'Uang Kurang';
                confirmBtn.disabled = true;
            }
        }

        // Alur Khusus QRIS
        function showQrisUpload() {
            let qrisData = [];
            try { qrisData = JSON.parse(appSettings.payment_qris_list || '[]'); } catch(e){}

            let qrisOptionsHtml = '';
            let defaultImage = "data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22200%22%20height%3D%22200%22%20viewBox%3D%220%200%20200%20200%22%3E%3Crect%20fill%3D%22%23e2e8f0%22%20width%3D%22200%22%20height%3D%22200%22%2F%3E%3Ctext%20fill%3D%22%2364748b%22%20font-family%3D%22sans-serif%22%20font-size%3D%2214%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3EQRIS%20KOSONG%3C%2Ftext%3E%3C%2Fsvg%3E";

            if(qrisData.length > 0) {
                defaultImage = qrisData[0].image;
                if(qrisData.length > 1) {
                    qrisOptionsHtml = `<select id="qrisSelect" onchange="document.getElementById('qrisImgDisplay').src=this.value" class="w-full mb-3 border border-slate-300 rounded-xl p-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">`;
                    qrisData.forEach(q => { qrisOptionsHtml += `<option value="${q.image}">${q.name}</option>`; });
                    qrisOptionsHtml += `</select>`;
                } else {
                    qrisOptionsHtml = `<p class="font-bold text-slate-700 mb-2">${qrisData[0].name}</p>`;
                }
            }

            Swal.fire({
                title: 'Pembayaran QRIS',
                html: `
                    <p class="text-xs text-slate-500 mb-3">Persilakan konsumen scan QRIS di bawah ini:</p>
                    ${qrisOptionsHtml}
                    <img src="${defaultImage}" id="qrisImgDisplay" alt="QRIS" class="w-48 h-48 mx-auto mb-4 border-4 border-white shadow-md rounded-xl object-contain bg-slate-200">
                    
                    <div class="text-left mt-4 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Foto Bukti Berhasil dari HP Konsumen</label>
                        <input type="file" accept="image/*" capture="environment" id="qrisProofFile" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Selesaikan & Upload',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4f46e5',
                preConfirm: () => {
                    const file = document.getElementById('qrisProofFile').files[0];
                    if (!file) { Swal.showValidationMessage('Foto bukti QRIS wajib diupload!'); return false; }
                    return file;
                }
            }).then((result) => {
                if (result.isConfirmed) { processPaymentApi('QRIS', result.value); }
            });
        }

        // UPDATE: Transfer Rekening jadi model Tombol/Checkbox (Radio Buttons)
        function showTransferUpload() {
            let transferData = [];
            try { transferData = JSON.parse(appSettings.payment_transfer_list || '[]'); } catch(e){}

            // Buat HTML radio buttons ala "Cards"
            let optionsHtml = '<div class="space-y-2 max-h-48 overflow-y-auto pr-1 mt-2">';
            if(transferData.length > 0) {
                transferData.forEach((b, i) => {
                    // Pakai <input type="radio"> biar kurir cuma bisa milih 1 bank tujuan
                    optionsHtml += `
                    <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer bg-white active:bg-slate-50 transition-colors">
                        <input type="radio" name="bankTransferKurir" value="${b.bank}" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300" ${i === 0 ? 'checked' : ''}>
                        <span class="ml-3 font-bold text-sm text-slate-700 leading-tight">
                            ${b.bank} <br>
                            <span class="font-mono text-indigo-600 text-[13px]">${b.number}</span> 
                            <span class="text-[10px] text-slate-400 font-normal">(${b.holder || 'Perusahaan'})</span>
                        </span>
                    </label>`;
                });
            } else {
                optionsHtml += `<p class="text-sm text-slate-500 italic p-2 border border-dashed rounded-xl text-center">Belum ada data rekening</p>`;
            }
            optionsHtml += '</div>';

            Swal.fire({
                title: 'Transfer Bank',
                html: `
                    <div class="text-left mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Rekening Tujuan (Tap untuk milih)</label>
                        ${optionsHtml}
                    </div>
                    
                    <div class="text-left mt-2 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Foto Bukti Transfer (Resi / Layar HP)</label>
                        <input type="file" accept="image/*" capture="environment" id="transferProofFile" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Selesaikan & Upload',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4f46e5',
                preConfirm: () => {
                    // Cari radio mana yang dicentang
                    const selectedBank = document.querySelector('input[name="bankTransferKurir"]:checked');
                    const bank = selectedBank ? selectedBank.value : '';
                    const file = document.getElementById('transferProofFile').files[0];
                    
                    if (!bank) { Swal.showValidationMessage('Pilih rekening tujuan!'); return false; }
                    if (!file) { Swal.showValidationMessage('Foto bukti Transfer wajib diupload!'); return false; }
                    
                    return { file: file, bank: bank };
                }
            }).then((result) => {
                if (result.isConfirmed) { processPaymentApi('TRANSFER', result.value.file, result.value.bank); }
            });
        }

        async function processPaymentApi(method, file = null, bankInfo = null) {
            if(!activeOrderId) return;
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Mencatat Mutasi: ' + method + (bankInfo ? ` (${bankInfo})` : ''),
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                // 1. Siapkan payload yang strukturnya SAMA PERSIS kayak orders.php
                let payload = {
                    id: activeOrderId,
                    payment_method: method
                };
                if (bankInfo) payload.bank = bankInfo;

                // 2. Kalau Kurir nge-foto bukti (QRIS/Transfer), convert ke Base64 biar bisa dikirim via JSON
                if (file) {
                    payload.payment_proof = await new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = new Image();
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                const MAX = 600;
                                let w = img.width, h = img.height;
                                if (w > h) { if (w > MAX) { h *= MAX/w; w = MAX; } } 
                                else { if (h > MAX) { w *= MAX/h; h = MAX; } }
                                canvas.width = w; canvas.height = h;
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0, w, h);
                                resolve(canvas.toDataURL('image/jpeg', 0.6));
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                }

                // 3. Tembak ke endpoint API yang UDAH TERBUKTI JALAN di orders.php
                const response = await fetch('../api/?action=complete_order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                // 4. Tangkap balasan dari server
                const rawText = await response.text();
                let result;
                try {
                    result = JSON.parse(rawText);
                } catch (e) {
                    console.error("Backend Error:", rawText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Backend Error!',
                        html: `<p class="text-sm">Server tidak merespon dengan JSON.</p><div class="bg-slate-100 p-2 text-left text-xs text-red-600 mt-2 rounded overflow-auto max-h-32">${rawText || '<i>(Blank)</i>'}</div>`
                    });
                    return;
                }
                
                // 5. Eksekusi kalau berhasil
                if(result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pesanan selesai dan berhasil dimutasi!',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        closeOrderDetail();
                        fetchDeliveries(); // Reload list kurir
                    });
                } else {
                    Swal.fire('Gagal', result.message || 'Terjadi kesalahan di server', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Gagal terhubung ke server', 'error');
            }
        }

        // 3. Jalankan Hanya Satu Fungsi Utama Saat Load
        document.addEventListener('DOMContentLoaded', fetchDeliveries);
    </script>
</body>
</html>