<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    System Error Logs
                </h2>
                <p class="text-sm text-slate-500 mt-1">Pantau bug dan crash (PHP & JS) secara real-time dari database TiDB.</p>
            </div>
            <button onclick="clearLogs()" class="bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800 font-bold px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Bersihkan Logs
            </button>
        </div>

        <div class="bg-[#1e1e1e] rounded-2xl shadow-xl border border-slate-700 overflow-hidden font-mono text-sm">
            <div class="px-4 py-3 bg-[#2d2d2d] border-b border-slate-700 flex gap-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
            </div>
            <div class="p-4 overflow-x-auto max-h-[70vh] overflow-y-auto" id="logContainer">
                <div class="text-center py-12 text-slate-500">Memuat log sistem...</div>
            </div>
        </div>

    </div>
</div>

<script>
    async function loadLogs() {
        const container = document.getElementById('logContainer');
        try {
            const res = await fetch('../api/?action=get_logs');
            const result = await res.json();
            
            if (result.status === 'success') {
                container.innerHTML = '';
                if(result.data.length === 0) {
                    container.innerHTML = '<div class="text-center py-12 text-green-500">✅ Sistem berjalan mulus. Tidak ada error log!</div>';
                    return;
                }

                result.data.forEach(log => {
                    const badgeColor = log.source.includes('PHP') ? 'bg-indigo-900 text-indigo-300' : 'bg-amber-900 text-amber-300';
                    const time = new Date(log.created_at).toLocaleString('id-ID');
                    
                    container.innerHTML += `
                        <div class="mb-4 border-b border-slate-700/50 pb-4 last:border-0 hover:bg-white/5 p-2 rounded transition-colors">
                            <div class="flex items-center gap-3 mb-1.5">
                                <span class="text-slate-400 text-xs">[${time}]</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded ${badgeColor}">${log.source}</span>
                            </div>
                            <div class="text-red-400 font-bold mb-1">${log.message}</div>
                            ${log.stack_trace ? `<pre class="text-slate-500 text-xs overflow-x-auto bg-black/30 p-2 rounded mt-2">${log.stack_trace}</pre>` : ''}
                        </div>`;
                });
            }
        } catch (error) { container.innerHTML = '<div class="text-red-500">Gagal memuat log dari server.</div>'; }
    }

    function clearLogs() {
        Swal.fire({
            title: 'Bersihkan Log?', text: "Semua riwayat error akan dihapus permanen dari database.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Bersihkan!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const res = await fetch('../api/?action=clear_logs', { method: 'POST' });
                    const data = await res.json();
                    if(data.status === 'success') { loadLogs(); Swal.fire('Berhasil!', data.message, 'success'); }
                } catch(e) {}
            }
        });
    }

    document.addEventListener('DOMContentLoaded', loadLogs);
</script>