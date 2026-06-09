<?php
// Deteksi nama page aktif dari URL
$active_page = $_GET['page'] ?? 'mobile';

// Deteksi Hak Akses (Role Base)
$perms = $_SESSION['permissions'] ?? [];
// Asumsi: Super Admin/Owner pasti memiliki akses 'settings' atau 'users'
$is_admin = in_array('settings', $perms) || in_array('users', $perms);

// Definisi Struktur Navigasi Bawah
$nav_items = [
    [
        'page'  => 'mobile',
        'label' => 'Home',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        'show'  => true // Kurir & Admin
    ],
    [
        'page'  => 'mobile_delivery',
        'label' => 'Pesanan',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
        'show'  => true // Kurir & Admin
    ],
    [
        'page'  => 'mobile_receivables',
        'label' => 'Piutang',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'show'  => true // Kurir & Admin
    ],
    [
        'page'  => 'mobile_history',
        'label' => 'Transaksi',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />',
        'show'  => true // Kurir & Admin
    ],
    [
        'page'  => 'mobile_expenses',
        'label' => 'Beban',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />',
        'show'  => $is_admin // Hanya Admin
    ]
];
?>

<!-- Container Utama Bottom Nav -->
<div class="fixed bottom-0 left-0 z-50 w-full h-[68px] bg-white border-t border-slate-100 flex justify-evenly items-center shadow-[0_-4px_20px_rgba(0,0,0,0.04)] pb-safe px-1">
    
    <?php foreach ($nav_items as $item): ?>
        <?php if ($item['show']): 
            // Cek apakah ini halaman yang sedang dibuka
            $isActive = ($active_page == $item['page']);
            
            // Konfigurasi style aktif/pasif secara elegan
            $colorClass  = $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500';
            $bgActive    = $isActive ? 'bg-indigo-50/80 scale-110' : 'scale-100';
            $fontClass   = $isActive ? 'font-bold' : 'font-medium';
            $strokeWidth = $isActive ? '2.5' : '2';
        ?>
            
            <a href="?page=<?= $item['page'] ?>" class="flex flex-col items-center justify-center w-full h-full transition-all duration-200 group">
                <!-- Wrapper Ikon dengan efek bubble jika aktif -->
                <div class="p-1.5 rounded-xl transition-all duration-300 <?= $bgActive ?> <?= $colorClass ?>">
                    <svg class="w-5 h-5 transition-transform duration-200 group-active:scale-90" fill="none" stroke="currentColor" stroke-width="<?= $strokeWidth ?>" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <?= $item['icon'] ?>
                    </svg>
                </div>
                <!-- Label Teks -->
                <span class="text-[9px] mt-0.5 <?= $colorClass ?> <?= $fontClass ?> transition-colors"><?= $item['label'] ?></span>
            </a>

        <?php endif; ?>
    <?php endforeach; ?>

</div>