<?php
// Tarik data langsung pakai PHP biar instan
require_once __DIR__ . '/../../models/ReportModel.php';
$reportModel = new ReportModel($db);

$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-t');

try {
    $dataPL = $reportModel->getProfitLossReport($start, $end);
} catch (Exception $e) {
    die("Gagal memuat laporan: " . $e->getMessage());
}

$storeName = $appSettings['store_name'] ?? 'PT Jogjatama Vishesha (Perseroda)';

// --- PENGELOMPOKAN DATA UNTUK FORMAT 2 KOLOM ---
// Sesuai referensi: Beban Gaji (Labor) dipisah dari Beban Lainnya (Other Expenses)
$laborExpenses = [];
$otherExpenses = [];
$totalLabor = 0;
$totalOther = 0;

foreach($dataPL['expenses_detail'] as $exp) {
    // Jika nama kategori mengandung kata "Gaji" atau "Bonus", masuk ke Labor
    if(stripos($exp['category'], 'Gaji') !== false || stripos($exp['category'], 'Bonus') !== false) {
        $laborExpenses[] = $exp;
        $totalLabor += $exp['total'];
    } else {
        $otherExpenses[] = $exp;
        $totalOther += $exp['total'];
    }
}

// Data untuk Pie Chart (Proporsi Biaya)
$cogs = floatval($dataPL['cogs']);
$labor = floatval($totalLabor);
$other = floatval($totalOther);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement - <?= htmlspecialchars($storeName) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js CDN -->
    <style>
        /* Standar Kertas A4 & Print CSS */
        @page { size: A4 portrait; margin: 15mm; }
        body { background: white; color: #1e293b; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .no-print { display: none !important; }
        
        /* Garis Tabel Tebal Klasik Akuntansi */
        .border-t-thick { border-top: 2px solid #1e293b; }
        .border-b-thick { border-bottom: 2px solid #1e293b; }
        .border-b-thin { border-bottom: 1px solid #cbd5e1; }
    </style>
</head>
<body class="max-w-4xl mx-auto p-4 md:p-8">
    
    <!-- HEADER -->
    <div class="text-center mb-10 border-b-thick pb-6">
        <h2 class="text-xl font-bold text-slate-500 uppercase tracking-widest mb-1">Restaurant / Coffee Shop</h2>
        <h1 class="text-4xl font-black uppercase tracking-widest text-slate-900 mb-6">INCOME STATEMENT</h1>
        <h2 class="text-3xl font-bold text-slate-600"><?= htmlspecialchars($storeName) ?></h2>
    </div>

    <!-- PERIODE & NET INCOME BADGE -->
    <div class="flex justify-between items-end mb-8 px-8">
        <div>
            <p class="text-lg font-medium text-slate-600 mb-1">Periode</p>
            <div class="bg-slate-200 px-6 py-2 rounded-full font-bold text-slate-800 inline-block shadow-sm">
                <?= date('d M Y', strtotime($start)) ?> - <?= date('d M Y', strtotime($end)) ?>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-1">NET INCOME</p>
            <p class="text-3xl font-black text-slate-900">Rp <?= number_format($dataPL['net_profit'], 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- PIE CHART AREA -->
    <div class="flex justify-center mb-12 h-64 w-full">
        <canvas id="costPieChart"></canvas>
    </div>

    <!-- TWO COLUMN LAYOUT -->
    <div class="grid grid-cols-2 gap-12 text-sm font-medium">
        
        <!-- KOLOM KIRI (Pendapatan & HPP) -->
        <div class="space-y-10">
            <!-- SALES -->
            <div>
                <h3 class="font-bold text-slate-900 mb-2 border-b-thick pb-1">Sales (Pendapatan)</h3>
                <div class="flex justify-between py-1.5 border-b-thin text-slate-600">
                    <span>Penjualan Bersih (Omzet)</span>
                    <span>Rp <?= number_format($dataPL['revenue'], 0, ',', '.') ?></span>
                </div>
                <!-- Spasi kosong agar mirip referensi -->
                <div class="flex justify-between py-1.5 border-b-thin text-transparent"><span>-</span><span>-</span></div>
                
                <div class="flex justify-between py-2 border-b-thick font-bold text-slate-900 mt-1">
                    <span>TOTAL SALES</span>
                    <span>Rp <?= number_format($dataPL['revenue'], 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- COGS -->
            <div>
                <h3 class="font-bold text-slate-900 mb-2 border-b-thick pb-1">Cost of Goods Sold (COGS)</h3>
                <div class="flex justify-between py-1.5 border-b-thin text-slate-600">
                    <span>HPP Bahan Baku (FIFO)</span>
                    <span>Rp <?= number_format($dataPL['cogs'], 0, ',', '.') ?></span>
                </div>
                
                <div class="flex justify-between py-2 border-b-thick font-bold text-slate-900 mt-1">
                    <span>TOTAL COGS</span>
                    <span>Rp <?= number_format($dataPL['cogs'], 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- GROSS PROFIT -->
            <div class="flex justify-between py-3 border-y-thick font-black text-slate-900 text-base">
                <span>GROSS PROFIT <span class="font-normal text-sm text-slate-500">| Sales minus COGS</span></span>
                <span>Rp <?= number_format($dataPL['gross_profit'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- KOLOM KANAN (Pengeluaran Operasional) -->
        <div class="space-y-8">
            <!-- LABOR EXPENSES -->
            <div>
                <h3 class="font-bold text-slate-900 mb-2 border-b-thick pb-1">Labor Expense (Gaji & Tunjangan)</h3>
                <?php if(empty($laborExpenses)): ?>
                    <div class="flex justify-between py-1.5 border-b-thin text-slate-400 italic"><span>Tidak ada catatan</span><span>Rp 0</span></div>
                <?php else: ?>
                    <?php foreach($laborExpenses as $exp): ?>
                    <div class="flex justify-between py-1.5 border-b-thin text-slate-600">
                        <span><?= htmlspecialchars($exp['category']) ?></span>
                        <span>Rp <?= number_format($exp['total'], 0, ',', '.') ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="flex justify-between py-2 border-b-thick font-bold text-slate-900 mt-1">
                    <span>Total Labor Expense</span>
                    <span>Rp <?= number_format($totalLabor, 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- OTHER EXPENSES -->
            <div>
                <h3 class="font-bold text-slate-900 mb-2 border-b-thick pb-1">Other Expense (Beban Lainnya)</h3>
                <?php if(empty($otherExpenses)): ?>
                    <div class="flex justify-between py-1.5 border-b-thin text-slate-400 italic"><span>Tidak ada catatan</span><span>Rp 0</span></div>
                <?php else: ?>
                    <?php foreach($otherExpenses as $exp): ?>
                    <div class="flex justify-between py-1.5 border-b-thin text-slate-600">
                        <span><?= htmlspecialchars($exp['category']) ?></span>
                        <span>Rp <?= number_format($exp['total'], 0, ',', '.') ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="flex justify-between py-2 border-b-thick font-bold text-slate-900 mt-1">
                    <span>Total Other Expenses</span>
                    <span>Rp <?= number_format($totalOther, 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- TOTAL EXPENSES -->
            <div class="flex justify-between py-3 border-y-thick font-black text-slate-900 text-base">
                <span>TOTAL EXPENSES</span>
                <span>Rp <?= number_format($dataPL['total_opex'], 0, ',', '.') ?></span>
            </div>
        </div>

    </div>

    <script>
        // Data PHP disuntik ke Javascript
        const costData = [<?= $cogs ?>, <?= $labor ?>, <?= $other ?>];
        const ctx = document.getElementById('costPieChart').getContext('2d');
        
        // Render Pie Chart pakai Chart.js
        const costPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['COGS (HPP)', 'Labor Expense', 'Other Expense'],
                datasets: [{
                    data: costData,
                    backgroundColor: [
                        '#1e3a8a', // Biru Tua elegan
                        '#3b82f6', // Biru Muda
                        '#ef4444'  // Merah bata
                    ],
                    borderWidth: 1,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false, // MATIKAN ANIMASI BIAR LANGSUNG BISA DI-PRINT
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { size: 12, family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif" } }
                    }
                }
            }
        });

        // Trigger Print otomatis setelah chart selesai di-render (dikasih jeda 500ms biar aman)
        setTimeout(() => {
            window.print();
        }, 500);
    </script>
</body>
</html>