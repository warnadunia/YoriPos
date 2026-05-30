<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Pengaturan Toko</h2>
                <p class="text-sm text-slate-500">Kelola identitas, struk, dan metode pembayaran.</p>
            </div>
            <button onclick="saveSettings()" id="btnSaveSettings" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition duration-200 shadow-sm">
                Simpan Pengaturan
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- IDENTITAS TOKO -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 space-y-4">
                <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100 border-b pb-2 mb-4">Identitas & Struk</h3>
                
                <!-- UPLOADER LOGO -->
                <div class="flex items-center gap-4 mb-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="relative group cursor-pointer" onclick="document.getElementById('inputStoreLogo').click()">
                        <img id="previewStoreLogo" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23f1f5f9'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12' font-weight='bold' fill='%2394a3b8'>LOGO</text></svg>" class="w-16 h-16 rounded-xl object-contain border border-slate-200 shadow-sm bg-white p-1">
                        <div class="absolute inset-0 bg-slate-900/50 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Logo Toko</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Klik untuk upload. Max otomatis 400px.</p>
                        <input type="file" id="inputStoreLogo" accept="image/*" class="hidden">
                        <input type="hidden" id="set_store_logo">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Toko</label>
                    <input type="text" id="set_store_name" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 rounded-xl text-sm" placeholder="Toko Contoh Sejahtera">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Alamat Toko</label>
                    <textarea id="set_store_address" rows="2" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 rounded-xl text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">No. Telp / WA</label>
                        <input type="text" id="set_store_phone" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Sosial Media</label>
                        <input type="text" id="set_store_sosmed" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 rounded-xl text-sm" placeholder="@yoripos">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Pesan Footer Struk (Max 150 Karakter)</label>
                    <textarea id="set_receipt_footer" rows="2" maxlength="150" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 rounded-xl text-sm" placeholder="Terima kasih telah berbelanja!"></textarea>
                </div>
            </div>

            <!-- TEMPLATE WA & QRIS -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 space-y-4">
                <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100 border-b pb-2">Digital Receipt & QRIS</h3>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Teks Template WhatsApp</label>
                    <textarea id="set_wa_template" rows="4" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 rounded-xl text-sm" placeholder="Halo kak! Berikut adalah nota: {link}"></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">Gunakan tag: {invoice}, {link}, {store_name}</p>
                </div>
                <div class="pt-2 border-t">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-slate-500">Daftar QRIS Aktif</label>
                        <button onclick="addQris()" class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-bold hover:bg-indigo-200">+ Tambah QRIS</button>
                    </div>
                    <div id="qrisListContainer" class="space-y-3 max-h-40 overflow-y-auto pr-1"></div>
                </div>
            </div>
        </div>

        <!-- DAFTAR REKENING BANK -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">Daftar Rekening Transfer</h3>
                <button onclick="addBank()" class="text-sm bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg font-bold hover:bg-indigo-200 transition-colors">+ Tambah Rekening</button>
            </div>
            <div id="bankListContainer" class="space-y-3"></div>
        </div>
    </div>
</div>

<script>
    let bankList = [];
    let qrisList = [];

    async function loadSettings() {
        try {
            const res = await fetch('/yoripos/api/?action=get_settings');
            const result = await res.json();
            if (result.status === 'success') {
                const data = result.data;
                ['store_name', 'store_address', 'store_phone', 'store_sosmed', 'receipt_footer', 'wa_template', 'store_logo'].forEach(key => {
                    if (data[key] && document.getElementById('set_' + key)) document.getElementById('set_' + key).value = data[key];
                });

                if(data['store_logo']) document.getElementById('previewStoreLogo').src = data['store_logo'];
                
                if (data.payment_transfer_list) bankList = JSON.parse(data.payment_transfer_list);
                if (data.payment_qris_list) qrisList = JSON.parse(data.payment_qris_list);
                
                renderBanks(); renderQris();
            }
        } catch (e) { console.error(e); }
    }

    function renderBanks() {
        const container = document.getElementById('bankListContainer');
        container.innerHTML = bankList.length === 0 ? '<p class="text-sm text-slate-400 italic text-center py-4">Belum ada rekening yang ditambahkan.</p>' : '';
        
        bankList.forEach((bank, index) => {
            container.innerHTML += `
                <div class="flex gap-3 items-end p-3 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200">
                    <div class="flex-1"><label class="text-[10px] font-bold text-slate-500 uppercase">Nama Bank</label><input type="text" value="${bank.bank}" onchange="updateBank(${index}, 'bank', this.value)" class="w-full px-3 py-2 bg-white rounded-lg text-sm border border-slate-200" placeholder="Misal: BCA"></div>
                    <div class="flex-1"><label class="text-[10px] font-bold text-slate-500 uppercase">No. Rekening</label><input type="text" value="${bank.number}" onchange="updateBank(${index}, 'number', this.value)" class="w-full px-3 py-2 bg-white rounded-lg text-sm font-mono border border-slate-200"></div>
                    <div class="flex-1"><label class="text-[10px] font-bold text-slate-500 uppercase">Atas Nama</label><input type="text" value="${bank.holder}" onchange="updateBank(${index}, 'holder', this.value)" class="w-full px-3 py-2 bg-white rounded-lg text-sm border border-slate-200"></div>
                    <button onclick="removeBank(${index})" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 mb-0.5"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                </div>`;
        });
    }

    function renderQris() {
        const container = document.getElementById('qrisListContainer');
        container.innerHTML = qrisList.length === 0 ? '<p class="text-xs text-slate-400 italic text-center py-2">Belum ada QRIS.</p>' : '';
        
        qrisList.forEach((qris, index) => {
            container.innerHTML += `
                <div class="flex gap-3 items-center p-2 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200">
                    <img src="${qris.image}" class="w-12 h-12 object-contain bg-white rounded border">
                    <div class="flex-1"><input type="text" value="${qris.name}" onchange="updateQris(${index}, this.value)" class="w-full px-2 py-1.5 bg-white rounded-lg text-sm border border-slate-200" placeholder="Nama QRIS (Misal: OVO/Gopay)"></div>
                    <button onclick="removeQris(${index})" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>`;
        });
    }

    function addBank() { bankList.push({bank: '', number: '', holder: ''}); renderBanks(); }
    function removeBank(index) { bankList.splice(index, 1); renderBanks(); }
    function updateBank(index, field, value) { bankList[index][field] = value; }

    function addQris() {
        const input = document.createElement('input'); input.type = 'file'; input.accept = 'image/*';
        input.onchange = e => {
            const file = e.target.files[0];
            if(!file) return;
            const reader = new FileReader();
            reader.onload = ev => {
                // Kompresi mini untuk QRIS
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const size = 300; // QRIS tidak butuh resolusi raksasa
                    canvas.width = size; canvas.height = size;
                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = 'white'; ctx.fillRect(0, 0, size, size);
                    ctx.drawImage(img, 0, 0, size, size);
                    qrisList.push({ name: 'QRIS Baru', image: canvas.toDataURL('image/jpeg', 0.8) });
                    renderQris();
                };
                img.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        };
        input.click();
    }
    function removeQris(index) { qrisList.splice(index, 1); renderQris(); }
    function updateQris(index, value) { qrisList[index].name = value; }

    document.getElementById('inputStoreLogo').addEventListener('change', function(e) {
        const file = e.target.files[0]; if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const MAX_SIZE = 400; let w = img.width, h = img.height;
                if (w > h) { if (w > MAX_SIZE) { h *= MAX_SIZE / w; w = MAX_SIZE; } } 
                else { if (h > MAX_SIZE) { w *= MAX_SIZE / h; h = MAX_SIZE; } }
                canvas.width = w; canvas.height = h;
                const ctx = canvas.getContext('2d'); ctx.drawImage(img, 0, 0, w, h);
                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                document.getElementById('previewStoreLogo').src = dataUrl;
                document.getElementById('set_store_logo').value = dataUrl;
            }; img.src = ev.target.result;
        }; reader.readAsDataURL(file);
    });

    async function saveSettings() {
        const btn = document.getElementById('btnSaveSettings');
        btn.innerText = 'Menyimpan...'; btn.disabled = true;

        const payload = {
            store_name: document.getElementById('set_store_name').value,
            store_logo: document.getElementById('set_store_logo').value,
            store_address: document.getElementById('set_store_address').value,
            store_phone: document.getElementById('set_store_phone').value,
            store_sosmed: document.getElementById('set_store_sosmed').value,
            receipt_footer: document.getElementById('set_receipt_footer').value,
            wa_template: document.getElementById('set_wa_template').value,
            payment_transfer_list: JSON.stringify(bankList),
            payment_qris_list: JSON.stringify(qrisList)
        };

        try {
            const res = await fetch('/yoripos/api/?action=save_settings', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (result.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Pengaturan toko disimpan.', timer: 1500, showConfirmButton: false });
            }
        } catch (e) { console.error(e); } finally { btn.innerText = 'Simpan Pengaturan'; btn.disabled = false; }
    }

    document.addEventListener('DOMContentLoaded', loadSettings);
</script>