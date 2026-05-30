<?php
// Ambil hak akses dan data user
$perms = $_SESSION['permissions'] ?? [];
$userName = $_SESSION['name'] ?? 'Staf';
$roleName = $_SESSION['role'] ?? 'Unknown';

// Ambil Data Toko
$storeName = $appSettings['store_name'] ?? 'Toko Saya';
$storeLogo = $appSettings['store_logo'] ?? '';
$initials = strtoupper(substr($storeName, 0, 1)); // Ambil huruf pertama
?>

<!-- SIDEBAR -->
<aside id="sidebar" class="bg-[#0f172a] text-slate-300 flex flex-col transition-all duration-300 w-20 md:w-64 z-50 fixed md:relative h-full border-r border-slate-800/50 shadow-xl overflow-hidden group">
    
    <!-- HEADER / LOGO TOKO (DINAMIS) -->
    <div class="h-16 flex items-center justify-center md:justify-start md:px-5 bg-[#0B1121] border-b border-slate-800/50 relative">
        <?php if(!empty($storeLogo)): ?>
            <img src="<?= htmlspecialchars($storeLogo) ?>" class="w-10 h-10 rounded-xl object-cover bg-white/5 p-0.5 shadow-lg shadow-indigo-500/10 flex-shrink-0">
        <?php else: ?>
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/20 flex-shrink-0"><?= htmlspecialchars($initials) ?></div>
        <?php endif; ?>
        <span class="ml-3 font-bold text-sm text-white truncate hidden md:block" title="<?= htmlspecialchars($storeName) ?>"><?= htmlspecialchars($storeName) ?></span>
    </div>

    <!-- NAVIGASI MENU (Tetap Sama) -->
    <nav class="flex-1 overflow-y-auto py-6 flex flex-col gap-1.5 px-3 custom-scrollbar">
        
        <?php if(in_array('dashboard', $perms)): ?>
        <a href="<?= APP_URL ?>/admin/?page=dashboard" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'dashboard' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'hover:bg-slate-800 hover:text-white' ?>" title="Dashboard">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Dashboard</span>
        </a>
        
        <a href="<?= APP_URL ?>/admin/?page=report_pl" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'report_pl' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'hover:bg-slate-800 hover:text-white' ?>" title="Laporan Laba Rugi">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Laporan Laba Rugi</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('pos', $perms)): ?>
        <a href="<?= APP_URL ?>/admin/?page=pos" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'pos' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'hover:bg-slate-800 hover:text-white' ?>" title="Mesin Kasir (POS)">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Mesin Kasir (POS)</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('products', $perms) || in_array('stocks', $perms)): ?>
        <div class="mt-4 mb-1 px-3">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider hidden md:block">Master & Inventori</p>
            <div class="h-[1px] w-full bg-slate-800 md:hidden mt-2"></div>
        </div>
        
            <?php if(in_array('products', $perms)): ?>
            
            <a href="<?= APP_URL ?>/admin/?page=menus" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'menus' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Master Menu POS">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Master Menu POS</span>
            </a>

            <a href="<?= APP_URL ?>/admin/?page=products" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'products' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Master Bahan Baku">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Master Bahan Baku</span>
            </a>
            
            <a href="<?= APP_URL ?>/admin/?page=categories" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'categories' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Master Kategori">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Master Kategori</span>
            </a>
            <?php endif; ?>

            <?php if(in_array('stocks', $perms)): ?>
            <a href="<?= APP_URL ?>/admin/?page=stocks" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'stocks' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Stok Masuk">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Stok Masuk (FIFO)</span>
            </a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(in_array('transactions', $perms)): ?>
        <div class="mt-4 mb-1 px-3">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider hidden md:block">Manajemen Pesanan</p>
            <div class="h-[1px] w-full bg-slate-800 md:hidden mt-2"></div>
        </div>
        <a href="<?= APP_URL ?>/admin/?page=orders" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'orders' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Pesanan & Delivery">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Pesanan & Delivery</span>
        </a>
        <a href="<?= APP_URL ?>/admin/?page=transactions" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'transactions' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Riwayat Lunas">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Riwayat Lunas</span>
        </a>
        <a href="<?= APP_URL ?>/admin/?page=receivables" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'receivables' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Piutang Berjalan">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Piutang Berjalan</span>
        </a>
        <a href="<?= APP_URL ?>/admin/?page=closing" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'closing' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Tutup Buku">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Tutup Buku Bulanan</span>
            </a>
        <a href="<?= APP_URL ?>/admin/?page=expenses" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'expenses' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Pengeluaran (OPEX)">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-4 0h4v4M12 20a8 8 0 100-16 8 8 0 000 16z"></path></svg>
            <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Pengeluaran (OPEX)</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('settings', $perms) || in_array('users', $perms)): ?>
        <div class="mt-4 mb-1 px-3">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider hidden md:block">Sistem</p>
            <div class="h-[1px] w-full bg-slate-800 md:hidden mt-2"></div>
        </div>
            <?php if(in_array('settings', $perms)): ?>
            <a href="<?= APP_URL ?>/admin/?page=settings" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'settings' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Pengaturan Toko">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Pengaturan Toko</span>
            </a>
            <?php endif; ?>
            
            <?php if(in_array('users', $perms)): ?>
            <a href="<?= APP_URL ?>/admin/?page=users" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'users' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="Kelola Staf & Akses">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">Kelola Staf & Akses</span>
            </a>

            <a href="<?= APP_URL ?>/admin/?page=system_logs" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 <?= $page === 'system_logs' ? 'bg-slate-800 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' ?>" title="System Logs">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="ml-3 font-semibold text-sm hidden md:block whitespace-nowrap">System Logs</span>
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</aside>