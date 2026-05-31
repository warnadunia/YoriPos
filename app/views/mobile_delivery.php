<div class="bg-slate-50 min-h-screen flex flex-col max-w-md mx-auto shadow-2xl relative pb-10">
    
    <div class="bg-white px-5 py-4 border-b border-slate-200 sticky top-0 z-50 flex items-center gap-4">
        <a href="?page=mobile" class="w-10 h-10 bg-slate-100 rounded-full flex justify-center items-center text-slate-600 hover:bg-slate-200 active:scale-90 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-lg font-black text-slate-800 leading-none">Daftar Kiriman</h1>
            <p class="text-xs text-emerald-600 font-bold mt-1" id="todayDate">Hari ini</p>
        </div>
    </div>

    <div class="p-5 flex flex-col gap-4" id="deliveryList">
        <div class="text-center py-10 text-slate-400 animate-pulse font-bold text-sm">Mencari pesanan aktif...</div>
    </div>
</div>

<script>
    document.getElementById('todayDate').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    async function fetchDeliveries() {
        const container = document.getElementById('deliveryList');
        try {
            // Ambil data order hari ini (asumsi menggunakan date hari ini default API lu)
            const response = await fetch('../api/?action=get_orders');
            const result = await response.json();
            
            if (result.status === 'success') {
                if (result.data.length === 0) {
                    container.innerHTML = `<div class="bg-white p-8 rounded-2xl text-center border border-slate-100"><span class="text-4xl block mb-3">☕</span><p class="text-slate-500 font-bold text-sm">Belum ada pesanan untuk dikirim hari ini.</p></div>`;
                    return;
                }
                
                container.innerHTML = '';
                result.data.forEach(ord => {
                    // Coba ambil nomor WA asli (jika ada data dari join table customer), atau pakai default kosong
                    let rawPhone = ord.customer_phone || ''; 
                    // Format 08 jadi 628 untuk link WA
                    let waLink = '';
                    if (rawPhone.startsWith('0')) { rawPhone = '62' + rawPhone.substring(1); }
                    if (rawPhone.length > 8) { waLink = `https://wa.me/${rawPhone}`; }
                    
                    const time = ord.created_at.split(' ')[1].substring(0,5); // Ambil jam:menit

                    container.innerHTML += `
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col gap-4 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
                            
                            <div class="flex justify-between items-start pl-2">
                                <div>
                                    <h3 class="font-black text-lg text-slate-800">${ord.customer_name || 'Pelanggan Umum'}</h3>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5"><span class="font-bold text-indigo-600">${ord.invoice_number}</span> • ${time} WIB</p>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase px-2 py-1 rounded-md tracking-wider">Dikemas</span>
                            </div>
                            
                            <div class="pl-2 flex items-start gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <p class="text-sm font-medium text-slate-600 leading-relaxed">${ord.customer_address || '<i class="text-slate-400">Alamat tidak dicatat di sistem</i>'}</p>
                            </div>
                            
                            <div class="pl-2 pt-2">
                                ${waLink ? 
                                    `<a href="${waLink}" target="_blank" class="w-full bg-[#25D366] hover:bg-[#1ebd5a] active:scale-95 transition-all text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 shadow-sm">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                        Hubungi WA Pembeli
                                    </a>` 
                                    : 
                                    `<button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-3 rounded-xl flex items-center justify-center gap-2 cursor-not-allowed border border-slate-200">Tidak ada nomor HP</button>`
                                }
                            </div>
                        </div>`;
                });
            }
        } catch (error) { container.innerHTML = `<div class="text-center text-red-500 py-10 font-bold">Gagal memuat data.</div>`; }
    }

    document.addEventListener('DOMContentLoaded', fetchDeliveries);
</script>