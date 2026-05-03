# Product Requirements Document (PRD)
**Project Name:** AsayoriPOS  
**Developer:** Asayori Tech  
**Version:** 1.0  
**Target Platform:** Web (PWA) & Android (TWA/APK)  

## 1. Ringkasan Eksekutif
AsayoriPOS adalah aplikasi Point of Sale (POS) berbasis web yang dioptimalkan untuk perangkat mobile. Sistem ini dirancang dengan pendekatan PWA (Progressive Web App) yang ringan dan akan dibungkus menjadi file APK (TWA). Infrastruktur aplikasi ini didesain khusus agar dapat berjalan 100% pada ekosistem gratis (Vercel Free Tier dan TiDB Serverless) tanpa mengorbankan fungsionalitas inti kasir, pencatatan FIFO, dan laporan dasar[cite: 1]. 

## 2. Tujuan Produk
*   Menyediakan sistem kasir yang cepat, responsif, dan stabil.
*   Memastikan akurasi Harga Pokok Penjualan (HPP) dan Laba Rugi dengan penerapan logika FIFO (First-In-First-Out)[cite: 1].
*   Memberikan keleluasaan operasional tanpa biaya server bulanan menggunakan arsitektur serverless[cite: 1].
*   Menyajikan antarmuka dengan filosofi *Minimalist, Natural, and Functional*.

## 3. Spesifikasi Teknis & Arsitektur
*   **Frontend:** HTML, Vanilla JavaScript, Tailwind CSS[cite: 1].
*   **Backend:** Native PHP terstruktur MVC (Model-View-Controller)[cite: 1].
*   **Database:** TiDB Serverless (MySQL Compatible)[cite: 1].
*   **Hosting & Routing:** Vercel (dengan runtime `vercel-php`)[cite: 1].
*   **Distribusi:** PWA & TWA (PWABuilder) untuk menghasilkan APK[cite: 1].

## 4. Kebutuhan Fitur Inti

### 4.1. Manajemen Inventaris & Logika FIFO
*   Sistem harus memisahkan data produk master dengan data *batch* stok masuk[cite: 1].
*   Setiap transaksi penjualan otomatis memotong kuantitas dari *batch* stok yang paling awal masuk (FIFO)[cite: 1].

### 4.2. Modul Kasir (Point of Sale)
*   Mendukung pencarian produk manual dan pemindaian *barcode*[cite: 1].
*   Keranjang belanja dinamis yang mengkalkulasi total harga secara real-time[cite: 1].

### 4.3. Fitur Cetak Struk
*   Pencetakan struk menggunakan *browser-side printing* (`window.print()`)[cite: 1].
*   Struk diformat menggunakan CSS `@media print` agar kompatibel dengan printer thermal (lebar 58mm atau 80mm) tanpa *driver* tambahan[cite: 1].

### 4.4. Akuntansi Dasar & Pelaporan
*   **Laporan Penjualan:** Rekapitulasi transaksi harian, bulanan, dan tahunan[cite: 1].
*   **Laporan HPP & Laba Rugi Kotor:** Sistem menghitung profit berdasarkan selisih harga jual dengan harga beli pada *batch* spesifik yang terpotong saat transaksi[cite: 1].
*   **Manajemen Pengeluaran:** Pencatatan biaya operasional harian[cite: 1].

### 4.5. Ekspor Data
*   **Ekspor Excel:** Sistem mengunduh laporan dalam format `.csv` atau `.xlsx` menggunakan fungsi native PHP (seperti `fputcsv` atau manipulasi HTTP Header) untuk efisiensi memori[cite: 1].
*   **Ekspor PDF:** Menggunakan fungsi cetak browser (`Print-to-PDF`) untuk menghindari beban komputasi di sisi serverless Vercel[cite: 1].

## 5. Struktur Direktori (MVC)
Sistem menggunakan struktur direktori yang memisahkan logika aplikasi dengan aset publik untuk keamanan dan kerapian:
*   `app/config/`: Konfigurasi *database* PDO.
*   `app/controllers/`: Logika pemrosesan permintaan dan ekspor laporan.
*   `app/models/`: Kueri SQL, termasuk logika kompleks untuk FIFO.
*   `app/views/`: Berisi antarmuka pengguna (tampilan kasir, komponen struk, laporan).
*   `public/`: *Entry point* untuk Vercel (`index.php`), aset statis (CSS/JS), dan *Service Worker* (PWA)[cite: 1].

## 6. Panduan UI/UX
*   **Desain Visual:** Pendekatan *Minimalist, Natural, and Functional*. Penggunaan palet warna yang nyaman dimata untuk durasi penggunaan kasir yang lama.
*   **Interaksi:** Tombol aksi utama ("Bayar", "Export") berukuran besar dan mudah ditekan pada perangkat layar sentuh[cite: 1].
*   **Tabel:** Penggunaan *sticky header* pada tabel laporan panjang untuk mempermudah pembacaan data[cite: 1].