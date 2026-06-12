<?php
// Deteksi nama page aktif dari URL
$active_page = $_GET['page'] ?? 'mobile';

// Deteksi Hak Akses (Role Base)
$perms = $_SESSION['permissions'] ?? [];
$is_admin = in_array('settings', $perms) || in_array('users', $perms);

// Definisi Navigasi Bawah dengan Ikon Baru (Premium Set)
$nav_items = [
    [
        'page'  => 'mobile',
        'label' => 'Dashboard',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
        'show'  => true 
    ],
    [
        'page'  => 'mobile_history',
        'label' => 'Transaksi',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />',
        'show'  => true 
    ],
    [
        'page'  => 'mobile_delivery',
        'label' => 'Pesanan',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />',
        'show'  => true 
    ],
    [
        'page'  => 'mobile_receivables',
        'label' => 'Piutang',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />',
        'show'  => true 
    ],
    [
        'page'  => 'mobile_expenses',
        'label' => 'Biaya',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />',
        'show'  => $is_admin // Hanya muncul buat Owner/Admin
    ]
];
?>

<div class="fixed bottom-0 left-4 right-4 z-50 h-[48px] bg-white rounded-full flex justify-around items-center shadow-[0_10px_40px_rgba(11,163,127,0.15)] px-2">
    
    <?php foreach ($nav_items as $item): ?>
        <?php if ($item['show']): 
            $isActive = ($active_page == $item['page']);
            
            // Konfigurasi Efek "Nyebul" (Popping Out)
            $iconWrap  = $isActive ? 'bg-[#0ba37f] text-white shadow-lg shadow-[#0ba37f]/40 -translate-y-6 scale-110 rounded-2xl' : 'text-[#0ba37f] bg-transparent translate-y-[-4px] group-hover:bg-[#e4f4ed] rounded-xl';
            $fontClass = $isActive ? 'font-bold' : 'font-medium';
        ?>
            
            <a href="?page=<?= $item['page'] ?>" class="relative flex flex-col items-center justify-center w-full h-full group">
                <div class="absolute transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] p-2 <?= $iconWrap ?> flex items-center justify-center z-10">
                    <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <?= $item['icon'] ?>
                    </svg>
                </div>
                
                <span class="absolute bottom-2.5 text-[9px] text-[#0ba37f] <?= $fontClass ?> tracking-wide transition-all duration-300 <?= $isActive ? 'translate-y-0 opacity-100' : 'translate-y-1 opacity-80' ?>">
                    <?= $item['label'] ?>
                </span>
            </a>

        <?php endif; ?>
    <?php endforeach; ?>

</div>