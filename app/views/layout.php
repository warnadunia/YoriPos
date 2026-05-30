<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($pageTitle ?? 'YoriPOS') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'], } } } }
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMatchMedia('(prefers-color-scheme: dark)').matches)) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
        /* CSS KHUSUS PRINTER THERMAL (ANTI JEPAT) */
        @media print {
            body * { visibility: hidden; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left: 0; top: 0; width: 58mm; padding: 0; margin: 0; }
            body { margin: 0; padding: 0; background: white; }
        }
    </style>
    <!-- GLOBAL ERROR CATCHER (Vercel Database Logger) -->
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            const traceInfo = error ? error.stack : `File: ${source} | Line: ${lineno}, Col: ${colno}`;
            fetch('../api/?action=log_error', {
                method: 'POST',
                body: JSON.stringify({ source: 'JS_GLOBAL', message: message, trace: traceInfo })
            }).catch(e => console.log("Logger failed"));
            return false; 
        };
        window.addEventListener('unhandledrejection', function(event) {
            const msg = event.reason ? (event.reason.message || event.reason.toString()) : 'Unhandled Promise Error';
            const trace = event.reason && event.reason.stack ? event.reason.stack : '';
            fetch('../api/?action=log_error', {
                method: 'POST',
                body: JSON.stringify({ source: 'JS_PROMISE', message: msg, trace: trace })
            }).catch(e => console.log("Logger failed"));
        });
    </script>
</head>
<body class="bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased h-screen overflow-hidden flex selection:bg-indigo-500 selection:text-white transition-colors duration-300">

    <!-- Panggil File Sidebar Terpisah -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative transition-all duration-300">
        
        <?php if($page !== 'pos'): ?>
        <!-- HEADER (Hanya muncul jika bukan halaman POS) -->
        <header class="h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 md:px-8 sticky top-0 z-40 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <!-- Hamburger Menu Mobile/Desktop -->
                <button id="sidebarToggle" class="p-2 -ml-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"><?= htmlspecialchars($pageTitle ?? 'AsayoriPOS') ?></h1>
            </div>
            
            <div class="flex items-center gap-3">
                <button id="themeToggle" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <svg id="themeToggleDarkIcon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="themeToggleLightIcon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>
                
                <div class="relative">
                    <button onclick="document.getElementById('profileDropdownMenu').classList.toggle('hidden')" id="profileDropdownBtn" class="flex items-center gap-2 p-1.5 pr-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shadow-inner">
                            <?= strtoupper(substr($userName ?? 'S', 0, 1)) ?>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-none"><?= htmlspecialchars($userName ?? 'Staf') ?></p>
                            <p class="text-[10px] text-slate-500 font-mono mt-0.5"><?= htmlspecialchars($roleName ?? 'Unknown') ?></p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div id="profileDropdownMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 py-2 hidden z-50 transform origin-top-right transition-all">
                        <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 sm:hidden">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200"><?= htmlspecialchars($userName ?? 'Staf') ?></p>
                            <p class="text-[10px] text-slate-500 font-mono"><?= htmlspecialchars($roleName ?? 'Unknown') ?></p>
                        </div>
                        <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">⚙️ Pengaturan Profil</a>
                        <hr class="my-1 border-slate-100 dark:border-slate-700">
                        <a href="<?= APP_URL ?>/api/?action=logout" class="block px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">🚪 Logout Sistem</a>
                    </div>
                </div>
            </div>
        </header>
        <?php endif; ?>

        <main class="flex-1 flex flex-col overflow-x-hidden overflow-y-auto bg-slate-50 dark:bg-slate-900 transition-colors duration-300 relative">
            <?php 
                if (isset($contentFile) && file_exists($contentFile)) {
                    require_once $contentFile;
                } else {
                    echo "<div class='p-8 text-center text-red-500'>File konten tidak ditemukan: " . htmlspecialchars($contentFile) . "</div>";
                }
            ?>
        </main>

        <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-3 px-4 md:px-8 flex flex-col sm:flex-row items-center justify-between transition-colors duration-300 z-10 text-xs">
            <div class="text-slate-500 dark:text-slate-400 mb-2 sm:mb-0">
                &copy; <?= date('Y') ?> <b class="text-slate-700 dark:text-slate-300"><?= htmlspecialchars($pageTitle ?? 'Toko Saya') ?></b>. Hak Cipta Dilindungi.
            </div>
            
            <a href="#" target="_blank" class="flex items-center gap-2 opacity-60 hover:opacity-100 transition-opacity cursor-pointer group">
                <span class="text-slate-500 dark:text-slate-400 italic group-hover:text-indigo-500 transition-colors">Ingin sistem kasir digital cerdas seperti ini?</span>
                <div class="w-4 h-4 bg-indigo-600 text-white rounded flex items-center justify-center text-[8px] font-black shadow-sm">Y</div>
                <span class="font-black tracking-widest text-slate-700 dark:text-slate-300 uppercase text-[10px]">POWERED BY YORIPOS</span>
            </a>
        </footer>

    </div> <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity opacity-0"></div>

    <script>
        // Logika Toggle Sidebar
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Fungsi Collapse / Expand (Hanya untuk Desktop)
        function toggleSidebarDesktop() {
            if (window.innerWidth >= 768) {
                sidebar.classList.toggle('md:w-64');
                sidebar.classList.toggle('md:w-20');
            }
        }

        // Fungsi Open / Close (Hanya untuk Mobile)
        function toggleSidebarMobile() {
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('hidden');
                
                if(sidebar.classList.contains('hidden')) {
                    sidebarOverlay.classList.add('opacity-0');
                    setTimeout(() => { sidebarOverlay.classList.add('hidden'); }, 300);
                } else {
                    sidebarOverlay.classList.remove('hidden');
                    setTimeout(() => { sidebarOverlay.classList.remove('opacity-0'); }, 10);
                }
            }
        }

        if(sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                if (window.innerWidth >= 768) toggleSidebarDesktop();
                else toggleSidebarMobile();
            });
        }
        
        if(sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebarMobile);
        }

        // Pastikan sidebar hidden di mobile saat pertama kali load
        if (window.innerWidth < 768) { sidebar.classList.add('hidden'); }
    </script>
</body>
</html>