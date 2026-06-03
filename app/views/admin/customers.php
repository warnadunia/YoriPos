<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
   <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Master Pelanggan</h3>
            <p class="text-sm text-slate-500">Kelola data konsumen dan titik lokasi GPS untuk pengiriman.</p>
        </div>
        <button onclick="openModal()" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all active:scale-95">
            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Pelanggan
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-1">
        <div class="overflow-x-auto h-full">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider">No. WhatsApp</th>
                        <th class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider">Alamat & GPS</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody id="custTableBody" class="bg-white divide-y divide-slate-100">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div id="custModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all scale-95 duration-300" id="custModalContent">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800" id="custModalTitle">Tambah Pelanggan</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 bg-slate-200 p-1.5 rounded-full transition-all active:scale-90">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="custForm" onsubmit="saveCustomer(event)">
            <div class="p-6 space-y-4">
                <input type="hidden" id="custId">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="custName" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-bold text-slate-700" placeholder="John Doe">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nomor WhatsApp</label>
                    <input type="number" id="custPhone" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-mono text-slate-700" placeholder="08123456789">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Alamat Rumah/Kantor</label>
                    <textarea id="custAddress" rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm text-slate-700 leading-relaxed" placeholder="Nama Jalan, RT/RW, Patokan..."></textarea>
                </div>

                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                    <label class="block text-xs font-black text-indigo-800 mb-2 uppercase tracking-wide flex justify-between">
                        <span>Koordinat Peta (GPS)</span>
                        <button type="button" onclick="detectGPS()" class="text-[10px] bg-indigo-600 text-white px-2 py-1 rounded shadow-sm hover:bg-indigo-700 active:scale-95 transition-all">📍 Deteksi Otomatis</button>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="custLat" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-xs font-mono text-slate-600 outline-none" placeholder="Latitude (Cth: -7.797)">
                        <input type="text" id="custLng" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-xs font-mono text-slate-600 outline-none" placeholder="Longitude (Cth: 110.370)">
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-600 bg-white hover:bg-slate-100 transition-colors">Batal</button>
                <button type="submit" id="custBtnSave" class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
    let currentCustomers = [];

    async function loadCustomers() {
        const tbody = document.getElementById('custTableBody');
        tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 font-bold animate-pulse">Memuat database konsumen...</td></tr>`;
        try {
            const response = await fetch('../api/?action=get_customers');
            const result = await response.json();
            if (result.status === 'success') {
                currentCustomers = result.data;
                tbody.innerHTML = '';
                if (currentCustomers.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-slate-500 font-bold">Belum ada pelanggan terdaftar.</td></tr>`; return;
                }
                
                currentCustomers.forEach(cust => {
                    let mapBadge = (cust.latitude && cust.longitude) 
                        ? `<a href="https://www.google.com/maps/search/?api=1&query=${cust.latitude},${cust.longitude}" target="_blank" class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded border border-emerald-200 hover:bg-emerald-100 transition-all mt-1">📍 Lihat Peta</a>` 
                        : `<span class="inline-block bg-slate-100 text-slate-400 text-[10px] font-bold px-2 py-1 rounded border border-slate-200 mt-1">Tanpa GPS</span>`;

                    let waLink = cust.phone ? `<a href="https://wa.me/${cust.phone.startsWith('0') ? '62'+cust.phone.substring(1) : cust.phone}" target="_blank" class="text-indigo-600 hover:underline font-mono">${cust.phone}</a>` : '-';

                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap font-black text-slate-800">${cust.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${waLink}</td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-600 mb-1 max-w-xs truncate">${cust.address || '-'}</p>
                                ${mapBadge}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center opacity-30 group-hover:opacity-100 transition-opacity">
                                <button onclick="editCustomer(${cust.id})" class="text-indigo-600 hover:bg-indigo-100 p-2 rounded-lg mr-1 active:scale-90 transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                            </td>
                        </tr>`;
                });
            }
        } catch (error) { tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-red-500 font-bold">Gagal mengambil data.</td></tr>`; }
    }

    async function saveCustomer(event) {
        event.preventDefault();
        const btn = document.getElementById('custBtnSave');
        const ogText = btn.innerText; btn.innerText = 'Menyimpan...'; btn.disabled = true;

        const payload = {
            id: document.getElementById('custId').value,
            name: document.getElementById('custName').value,
            phone: document.getElementById('custPhone').value,
            address: document.getElementById('custAddress').value,
            latitude: document.getElementById('custLat').value,
            longitude: document.getElementById('custLng').value
        };

        try {
            const response = await fetch('../api/?action=save_customer', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (result.status === 'success') {
                Toast.fire({ icon: 'success', title: 'Data Tersimpan!' });
                closeModal(); loadCustomers();
            } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message }); }
        } catch (error) { Swal.fire({ icon: 'error', title: 'Error', text: 'Jaringan bermasalah.' }); }
        finally { btn.innerText = ogText; btn.disabled = false; }
    }

    function editCustomer(id) {
        const cust = currentCustomers.find(c => c.id == id);
        if (!cust) return;
        document.getElementById('custModalTitle').innerText = 'Edit Pelanggan';
        document.getElementById('custId').value = cust.id;
        document.getElementById('custName').value = cust.name;
        document.getElementById('custPhone').value = cust.phone || '';
        document.getElementById('custAddress').value = cust.address || '';
        document.getElementById('custLat').value = cust.latitude || '';
        document.getElementById('custLng').value = cust.longitude || '';
        openModal();
    }

    function detectGPS() {
        if (navigator.geolocation) {
            Toast.fire({ icon: 'info', title: 'Mencari sinyal GPS...' });
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    document.getElementById('custLat').value = position.coords.latitude;
                    document.getElementById('custLng').value = position.coords.longitude;
                    Toast.fire({ icon: 'success', title: 'Kordinat Ditemukan!' });
                },
                (error) => { Swal.fire('Gagal', 'Sinyal GPS gagal diakses. Pastikan izin lokasi aktif.', 'error'); },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            Swal.fire('Oopss', 'Browser perangkat ini tidak mendukung fitur GPS.', 'warning');
        }
    }

    const modal = document.getElementById('custModal');
    const modalContent = document.getElementById('custModalContent');
    function openModal() {
        if (!document.getElementById('custId').value) { 
            document.getElementById('custModalTitle').innerText = 'Tambah Pelanggan'; 
            document.getElementById('custForm').reset(); 
        }
        modal.classList.remove('hidden'); setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); }, 10);
    }
    function closeModal() {
        modal.classList.add('opacity-0'); modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); document.getElementById('custForm').reset(); document.getElementById('custId').value = ''; }, 300);
    }

    document.addEventListener('DOMContentLoaded', loadCustomers);
</script>