<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Tutup Buku Bulanan</h2>
                <p class="text-sm text-slate-500 mt-1">Kunci periode akuntansi untuk mencegah perubahan data P&L pada bulan yang telah berlalu.</p>
            </div>
            <button onclick="openClosingModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-red-500/30 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Eksekusi Tutup Buku
            </button>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Riwayat Penutupan Periode</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="text-slate-500 uppercase border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="pb-3 font-bold">Periode Buku</th>
                                <th class="pb-3 font-bold text-center">Status</th>
                                <th class="pb-3 font-bold">Dieksekusi Oleh</th>
                                <th class="pb-3 font-bold text-right">Waktu Eksekusi</th>
                            </tr>
                        </thead>
                        <tbody id="closingTableBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            <tr><td colspan="4" class="text-center py-8 text-slate-400">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- ANNUAL CLOSING SECTION -->
        <div class="bg-red-50 dark:bg-red-900/10 rounded-2xl shadow-sm border border-red-200 dark:border-red-800/30 overflow-hidden mt-8">
            <div class="px-6 py-4 border-b border-red-200 dark:border-red-800/30 bg-red-100/50 dark:bg-red-900/30 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-red-800 dark:text-red-400">Tutup Buku Tahunan (Annual Closing)</h3>
                    <p class="text-xs text-red-600 dark:text-red-500 mt-0.5">Kunci mati seluruh data finansial pada tahun tertentu untuk keperluan audit final.</p>
                </div>
            </div>
            <div class="p-6 flex flex-col md:flex-row items-center gap-4">
                <select id="annualCloseYear" class="w-full md:w-48 bg-white dark:bg-slate-900 border border-red-300 dark:border-red-700 rounded-xl px-4 py-3 outline-none font-bold text-red-700 dark:text-red-400 text-lg text-center">
                    <?php $cy = date('Y'); for($i=$cy-2; $i<=$cy; $i++): ?>
                        <option value="<?= $i ?>" <?= $cy-1 == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <button onclick="executeAnnualClosing()" class="w-full md:w-auto bg-red-700 hover:bg-red-800 text-white font-bold px-8 py-3 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Kunci Permanen Tahun Ini
                </button>
            </div>
            
            <!-- Tabel Riwayat Annual -->
            <div class="border-t border-red-200 dark:border-red-800/30">
                <table class="min-w-full text-left text-sm">
                    <tbody id="annualClosingTableBody" class="divide-y divide-red-100 dark:divide-red-800/20 text-red-700 dark:text-red-400">
                        <tr><td class="text-center py-4 text-red-400/70 text-xs italic">Memuat data tahunan...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
<div id="closingModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl p-6 transform transition-all scale-95" id="closingModalContent">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-black text-slate-800 dark:text-slate-100">Peringatan Tutup Buku!</h3>
            <p class="text-sm text-slate-500 mt-2">Setelah periode dikunci, transaksi dan pengeluaran pada bulan tersebut tidak dapat diubah atau ditambahkan lagi.</p>
        </div>
        
        <div class="space-y-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Bulan</label>
                    <select id="closeMonth" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 outline-none font-bold text-indigo-700">
                        <?php for($i=1; $i<=12; $i++): ?>
                            <option value="<?= $i ?>" <?= date('n') == $i ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tahun</label>
                    <select id="closeYear" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 outline-none font-bold text-indigo-700">
                        <?php $cy = date('Y'); for($i=$cy-1; $i<=$cy+1; $i++): ?>
                            <option value="<?= $i ?>" <?= $cy == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="closeClosingModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-3 rounded-xl transition duration-200">Batal</button>
            <button onclick="executeClosing()" id="btnConfirmClose" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-200">Kunci Periode</button>
        </div>
    </div>
</div>

<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    async function loadClosedPeriods() {
        try {
            const res = await fetch('/yoripos/api/?action=get_closed_periods');
            const result = await res.json();
            const tbody = document.getElementById('closingTableBody');
            
            if (result.status === 'success') {
                tbody.innerHTML = '';
                if(result.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-slate-400 italic">Belum ada periode yang ditutup.</td></tr>';
                    return;
                }

                result.data.forEach(cp => {
                    const dateObj = new Date(cp.closed_at);
                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="py-4 font-black text-indigo-600 dark:text-indigo-400 text-lg">${monthNames[cp.period_month - 1]} ${cp.period_year}</td>
                            <td class="py-4 text-center"><span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1 w-max mx-auto"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg> TERKUNCI</span></td>
                            <td class="py-4 font-bold">${cp.closed_by}</td>
                            <td class="py-4 text-right font-mono text-xs text-slate-500">${dateObj.toLocaleString('id-ID')}</td>
                        </tr>`;
                });
            }
        } catch (error) { console.error(error); }
    }

    function openClosingModal() {
        const modal = document.getElementById('closingModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('closingModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeClosingModal() {
        const modal = document.getElementById('closingModal');
        modal.classList.add('opacity-0'); document.getElementById('closingModalContent').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    async function executeClosing() {
        const month = document.getElementById('closeMonth').value;
        const year = document.getElementById('closeYear').value;
        const btn = document.getElementById('btnConfirmClose');

        btn.disabled = true; btn.innerText = 'Mengunci...';

        try {
            const res = await fetch('/yoripos/api/?action=do_closing', { 
                method: 'POST', 
                body: JSON.stringify({ month: month, year: year }) 
            });
            const data = await res.json();
            
            if(data.status === 'success') { 
                Swal.fire('Berhasil!', data.message, 'success'); 
                closeClosingModal(); 
                loadClosedPeriods(); 
            } else { 
                Swal.fire('Gagal', data.message, 'error'); 
            }
        } catch(e) { 
            Swal.fire('Error', 'Kesalahan jaringan', 'error'); 
        } finally { 
            btn.disabled = false; btn.innerText = 'Kunci Periode'; 
        }
    }

    async function loadAnnualClosedYears() {
        try {
            const res = await fetch('/yoripos/api/?action=get_closed_years');
            const result = await res.json();
            const tbody = document.getElementById('annualClosingTableBody');
            if (result.status === 'success') {
                tbody.innerHTML = '';
                if(result.data.length === 0) {
                    tbody.innerHTML = '<tr><td class="text-center py-4 text-red-400/70 text-xs italic">Belum ada tahun yang dikunci permanen.</td></tr>'; return;
                }
                result.data.forEach(cy => {
                    tbody.innerHTML += `
                        <tr class="hover:bg-red-100/50 dark:hover:bg-red-900/20">
                            <td class="px-6 py-3 font-black text-lg">TAHUN ${cy.period_year}</td>
                            <td class="px-6 py-3 text-center"><span class="bg-red-700 text-white px-3 py-1 rounded text-[10px] font-black uppercase">AUDIT LOCKED</span></td>
                            <td class="px-6 py-3 font-bold text-xs">${cy.closed_by}</td>
                        </tr>`;
                });
            }
        } catch (e) {}
    }

    function executeAnnualClosing() {
        const year = document.getElementById('annualCloseYear').value;
        Swal.fire({
            title: `Kunci Permanen Tahun ${year}?`,
            text: "Tindakan ini tidak bisa dibatalkan. Semua transaksi backdate ke tahun ini akan ditolak otomatis!",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#b91c1c', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Kunci Permanen!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const res = await fetch('/yoripos/api/?action=do_annual_closing', { method: 'POST', body: JSON.stringify({ year: year }) });
                    const data = await res.json();
                    if(data.status === 'success') { Swal.fire('Terkunci!', data.message, 'success'); loadAnnualClosedYears(); } 
                    else { Swal.fire('Gagal', data.message, 'error'); }
                } catch(e) {}
            }
        });
    }

    // UPDATE INITIALIZER
    document.addEventListener('DOMContentLoaded', () => {
        loadClosedPeriods();
        loadAnnualClosedYears();
    });
</script>