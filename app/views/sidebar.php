<?php
// Ambil hak akses dan data user
$perms = $_SESSION['permissions'] ?? [];
$userName = $_SESSION['name'] ?? 'Staf';
$roleName = $_SESSION['role'] ?? 'Unknown';

// Ambil Data Toko
$storeName = $appSettings['store_name'] ?? 'PT Jogjatama Vishesha';
$storeLogo = $appSettings['store_logo'] ?? '';
$initials = strtoupper(substr($storeName, 0, 1)); 

// Helper untuk Class Menu Aktif & Tidak Aktif
$activeClass = 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold';
$inactiveClass = 'text-slate-400 hover:bg-slate-800/60 hover:text-indigo-300 font-medium transition-all duration-200';
?>

<aside id="sidebar" class="bg-[#0B1121] text-slate-300 flex flex-col transition-all duration-300 w-20 md:w-64 z-50 fixed md:relative h-full border-r border-slate-800/80 shadow-2xl overflow-hidden group">
    
    <div class="h-20 flex items-center justify-center md:justify-start md:px-6 bg-[#0f172a] border-b border-slate-800/80 relative">
        <?php if(!empty($storeLogo)): ?>
            <img src="<?= htmlspecialchars($storeLogo) ?>" class="w-10 h-10 rounded-xl object-cover bg-white/5 p-0.5 shadow-lg flex-shrink-0">
        <?php else: ?>
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/30 flex-shrink-0">
                <?= htmlspecialchars($initials) ?>
            </div>
        <?php endif; ?>
        <div class="ml-3 hidden md:block">
            <span class="font-black text-sm text-white tracking-wide block truncate" title="<?= htmlspecialchars($storeName) ?>"><?= htmlspecialchars($storeName) ?></span>
            <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">POS System</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 flex flex-col gap-1 px-3 hide-scrollbar">
        
        <?php if(in_array('dashboard', $perms)): ?>
        <a href="?page=dashboard" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'dashboard' ? $activeClass : $inactiveClass ?>" title="Dashboard">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Dashboard</span>
        </a>
        <a href="?page=report_pl" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'report_pl' ? $activeClass : $inactiveClass ?>" title="Laporan Laba Rugi">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Laporan Laba Rugi</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('pos', $perms)): ?>
        <a href="?page=pos" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'pos' ? $activeClass : $inactiveClass ?>" title="Mesin Kasir (POS)">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Mesin Kasir (POS)</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('transactions', $perms)): ?>
        <div class="mt-5 mb-2 px-3">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest hidden md:block">Transaksi</p>
            <div class="h-[1px] w-full bg-slate-800 md:hidden mt-2"></div>
        </div>
        
        <a href="?page=orders" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'orders' ? $activeClass : $inactiveClass ?>" title="Pesanan & Delivery">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Pesanan & Delivery</span>
        </a>
        <a href="?page=transactions" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'transactions' ? $activeClass : $inactiveClass ?>" title="Riwayat Lunas">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Riwayat Lunas</span>
        </a>
        <a href="?page=receivables" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'receivables' ? $activeClass : $inactiveClass ?>" title="Piutang Berjalan">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Piutang Berjalan</span>
        </a>
        <a href="?page=expenses" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'expenses' ? $activeClass : $inactiveClass ?>" title="Pengeluaran (OPEX)">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 14l6-6m-4 0h4v4M12 20a8 8 0 100-16 8 8 0 000 16z"></path></svg>
            <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Pengeluaran (OPEX)</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('products', $perms) || in_array('stocks', $perms) || in_array('users', $perms)): ?>
        <div class="mt-5 mb-2 px-3">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest hidden md:block">Master & Inventori</p>
            <div class="h-[1px] w-full bg-slate-800 md:hidden mt-2"></div>
        </div>
        
            <?php if(in_array('users', $perms)): // Asumsi fitur pelanggan dikelola admin yang punya akses users/settings ?>
            <a href="?page=customers" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'customers' ? $activeClass : $inactiveClass ?>" title="Master Pelanggan">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Master Pelanggan</span>
            </a>
            <?php endif; ?>

            <?php if(in_array('products', $perms)): ?>
            <a href="?page=menus" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'menus' ? $activeClass : $inactiveClass ?>" title="Master Menu POS">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Master Menu POS</span>
            </a>
            <a href="?page=products" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'products' ? $activeClass : $inactiveClass ?>" title="Master Bahan Baku">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Master Bahan Baku</span>
            </a>
            <a href="?page=categories" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'categories' ? $activeClass : $inactiveClass ?>" title="Master Kategori">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Master Kategori</span>
            </a>
            <?php endif; ?>

            <?php if(in_array('stocks', $perms)): ?>
            <a href="?page=stocks" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'stocks' ? $activeClass : $inactiveClass ?>" title="Stok Masuk">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Stok Masuk (FIFO)</span>
            </a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(in_array('settings', $perms) || in_array('users', $perms)): ?>
        <div class="mt-5 mb-2 px-3">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest hidden md:block">Sistem</p>
            <div class="h-[1px] w-full bg-slate-800 md:hidden mt-2"></div>
        </div>
        
            <?php if(in_array('settings', $perms)): ?>
            <a href="?page=closing" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'closing' ? $activeClass : $inactiveClass ?>" title="Tutup Buku">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Tutup Buku Bulanan</span>
            </a>
            <a href="?page=settings" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'settings' ? $activeClass : $inactiveClass ?>" title="Pengaturan Toko">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Pengaturan Toko</span>
            </a>
            <?php endif; ?>
            
            <?php if(in_array('users', $perms)): ?>
            <a href="?page=users" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'users' ? $activeClass : $inactiveClass ?>" title="Kelola Staf & Akses">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">Kelola Staf & Akses</span>
            </a>
            <a href="?page=system_logs" class="flex items-center px-3 py-3 rounded-xl <?= $page === 'system_logs' ? $activeClass : $inactiveClass ?>" title="System Logs">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="ml-3 text-sm hidden md:block whitespace-nowrap">System Logs</span>
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</aside>

<style>
    /* CSS kecil buat ngilangin scrollbar default yang jelek tapi tetep bisa discroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; } 
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>