# Changelog AsayoriPOS

Semua perubahan signifikan pada proyek ini akan didokumentasikan di file ini.

## [1.0.0-alpha.1] - 2026-05-04
### Added
- Inisialisasi struktur direktori MVC (`app/` dan `public/`).
- Setup file konfigurasi `vercel.json` untuk integrasi runtime `vercel-php`.
- Pembuatan *entry point* utama di `public/index.php` untuk menangani *routing*.

## [1.0.0-alpha.2] - 2026-05-04
### Added
- Desain skema database SQL untuk TiDB.
- Pembuatan tabel `products` (master data).
- Pembuatan tabel `stock_batches` (untuk mencatat HPP spesifik dan logika FIFO).
- Pembuatan tabel `sales` dan `sale_details` (pencatatan transaksi kasir).

## [1.0.0-alpha.3] - 2026-05-04
### Added
- Setup environment development lokal.
- Pembuatan file `.gitignore` untuk mencegah file lokal masuk ke repositori.
- Pembuatan `app/config/database.php` menggunakan PDO untuk koneksi database.

## [1.0.0-alpha.4] - 2026-05-04
### Added
- Setup cloud providers pipeline (GitHub, TiDB Serverless, Vercel).
- Integrasi repositori lokal dengan remote GitHub.

## [1.0.0-alpha.5] - 2026-05-04
### Changed
- Update remote repository GitHub ke `https://github.com/warnadunia/YoriPos.git` untuk bypass isu otentikasi akun Vercel lama.

## [1.0.0-alpha.6] - 2026-05-04
### Changed
- Migrasi *entry point* dari `public/index.php` ke `api/index.php` untuk mematuhi aturan Vercel Serverless Functions.
- Update konfigurasi `vercel.json` agar *routing* MVC mengarah ke `api/index.php`.

## [1.0.0-alpha.7] - 2026-05-04
### Changed
- Upgrade runtime Vercel PHP dari `vercel-php@0.6.0` menjadi `vercel-php@0.6.2` untuk mengatasi *error deprecation* Node.js 18.

## [1.0.0-alpha.8] - 2026-05-04
### Changed
- Sukses deployment ke Vercel Serverless. Aplikasi live dan routing PHP berfungsi normal.

## [1.0.0-alpha.9] - 2026-05-04
### Added
- Eksekusi skema database (5 tabel) di TiDB Serverless.
- Setup koneksi PDO `database.php` dengan implementasi sertifikat SSL `cacert.pem` untuk terhubung ke TiDB Cloud secara aman.

## [1.0.0-alpha.10] - 2026-05-04
### Added
- Pembuatan `app/models/ProductModel.php` untuk menangani CRUD Master Produk.
- Implementasi logika penerimaan stok (`receiveStock`) untuk mencatat batch awal sistem FIFO.
- Kueri agregasi `getAllProducts` untuk menghitung sisa stok dari tabel `stock_batches`.

## [1.0.0-alpha.11] - 2026-05-04
### Added
- Pembuatan `app/models/SaleModel.php` untuk menangani transaksi POS (Point of Sale).
- Implementasi algoritma pemotongan stok FIFO (First-In-First-Out) terpusat.
- Penggunaan *Database Transaction* (Commit & Rollback) untuk mencegah data *corrupt* saat pemrosesan kasir.
- Pencatatan HPP spesifik (harga beli) per transaksi ke dalam tabel `sale_details` untuk kemudahan laporan Laba Rugi.

## [1.0.0-alpha.12] - 2026-05-04
### Added
- Testing simulasi transaksi POS di `api/index.php` untuk memvalidasi pemotongan otomatis algoritma FIFO pada `SaleModel.php`.

## [1.0.0-alpha.13] - 2026-05-04
### Added
- Inisialisasi antarmuka frontend Kasir (POS) di `app/views/pos.php`.
- Integrasi Tailwind CSS via CDN untuk styling yang *minimalist* dan responsif.
- Refaktor `api/index.php` menjadi *Main Controller* untuk merender tampilan HTML dan memisahkan jalur API.

## [1.0.0-alpha.14] - 2026-05-04
### Changed
- Redesain `app/views/pos.php` menggunakan tema Dark Mode untuk mengurangi kelelahan mata.
- Optimalisasi UI/UX: Menghapus header tebal untuk memperlebar *workspace* grid produk.
- Memperbesar area sentuh (*hitbox*) *card* produk untuk meminimalisir salah input pada layar *touchscreen*.

## [1.0.0-alpha.15] - 2026-05-04
### Added
- Fitur *Toggle Dark/Light Mode* pada UI Kasir (`pos.php`).
- Refaktor kelas Tailwind CSS dengan kombinasi `dark:` *modifiers* untuk mendukung dua tema dinamis secara penuh.
- Implementasi penyimpanan preferensi tema secara persisten menggunakan *browser* `localStorage`.

## [1.0.0-alpha.16] - 2026-05-04
### Added
- Implementasi Vanilla JavaScript State Management (`products` & `cart`) di `pos.php`.
- Fetching data produk secara asinkron (AJAX/Fetch API) dari `?action=get_products`.
- Render dinamis untuk *grid* produk dan *list* keranjang belanja, menggantikan data statis/hardcoded.
- Logika dasar *Add to Cart* dan kalkulasi *Total Tagihan* secara *real-time*.

## [1.0.0-alpha.17] - 2026-05-04
### Added
- Pembuatan API endpoint `?action=checkout` di `api/index.php` untuk memproses payload JSON dari keranjang.
- Fungsi JavaScript `processCheckout()` di `pos.php` untuk mengirim data transaksi via POST Request.
- Fitur *auto-refresh* data produk setelah transaksi berhasil, agar stok di layar selalu sinkron.

## [1.0.0-alpha.18] - 2026-05-04
### Changed
- Mengganti notifikasi `alert()` bawaan browser dengan **SweetAlert2** (`Swal`) untuk UI/UX yang lebih cantik dan profesional.
- Implementasi *Toast Notification* untuk peringatan stok batas maksimal agar tidak mengganggu *flow* kasir.
- Implementasi *Popup Success/Error* yang interaktif saat proses *checkout* selesai.

## [1.0.0-alpha.19] - 2026-05-04
### Added
- Fitur Cetak Struk Thermal (Receipt) terintegrasi pada antarmuka Kasir.
- Implementasi CSS `@media print` untuk memformat tampilan cetak khusus berukuran 58mm.
- Fungsi JavaScript `printReceipt()` untuk mem- *generate* struk secara dinamis sesaat setelah transaksi tervalidasi.

## [1.0.0-alpha.20] - 2026-05-04
### Added
- Pembuatan `app/models/ReportModel.php` untuk menangani kueri analitik dan pelaporan.
- Implementasi kueri agregasi untuk menghitung Total Pendapatan, Total HPP (Harga Pokok Penjualan), dan Laba Kotor berdasarkan data transaksi FIFO.

## [1.0.0-alpha.21] - 2026-05-04
### Added
- Inisialisasi arsitektur Super Admin Panel dengan pemisahan halaman (*1 fungsi 1 halaman*).
- Pembuatan *Admin Router* di `admin/index.php` untuk mengatur navigasi halaman.
- Pembuatan *Master Layout* Admin di `app/views/admin/layout.php` dengan *Sidebar* dinamis.
- Pembuatan halaman **Dashboard** di `app/views/admin/dashboard.php` yang terintegrasi dengan `ReportModel` untuk menampilkan Omzet, HPP, dan Laba Kotor secara *real-time*.

## [1.0.0-alpha.22] - 2026-05-04
### Changed
- Optimalisasi UI/UX: Mengubah *Sidebar* statis menjadi *Collapsible Sidebar* (dapat dilipat) di `layout.php`.
- Menambahkan animasi transisi *smooth* menggunakan CSS agar perubahan lebar *workspace* terasa natural.
- Menambahkan tombol *Hamburger Menu* di *Header* untuk melakukan *toggle sidebar*.
- Persiapan arsitektur UI untuk implementasi *Role-Based Menu Leveling* di masa mendatang.

## [1.0.0-alpha.23] - 2026-05-04
### Changed
- Redesain Sidebar menjadi **Mini-Sidebar (Icon-Only)** saat mode *collapse*.
- Perubahan skema warna dari Hitam pekat ke *Modern Slate & Indigo* untuk visual yang lebih *clean* dan nyaman di mata.
- Optimasi transisi lebar sidebar dan visibilitas teks menu menggunakan CSS *opacity* dan *width* transform.

## [1.0.0-alpha.24] - 2026-05-05
### Added
- Integrasi *Main Sidebar* (ekosistem Super Admin) ke dalam antarmuka POS (`pos.php`).
- Restrukturisasi arsitektur menu: menjadikan POS sebagai *Parent Menu* yang siap menampung *Sub-Menu* (Kasir, Riwayat Transaksi, dll).
- Penyesuaian *layout* tiga kolom (Sidebar App -> Workspace Produk -> Panel Keranjang) dengan sistem responsif yang tidak mengorbankan area kerja kasir.

## [1.0.0-alpha.25] - 2026-05-05
### Changed
- Refaktor arsitektur sistem: Menerapkan prinsip DRY dengan memusatkan antarmuka POS ke dalam Master Layout (`layout.php`).
- Mengubah `api/index.php` menjadi murni *Backend* penghasil JSON (tanpa render HTML).
- Menambahkan rute `?page=pos` pada Admin Router untuk mengakses mesin kasir.
- Optimasi *Fetch API URL* pada modul POS untuk menyesuaikan dengan struktur rute yang baru.

## [1.0.0-alpha.26] - 2026-05-05
### Changed
- **Arsitektur RBAC Ready**: Pemisahan *Entry Point* (rute akses) untuk Modul Admin (`/admin/`) dan Modul Kasir (`/pos/`) sebagai persiapan implementasi *Role-Based Access Control*.
- **Global Layout**: Memindahkan kerangka UI dari spesifik admin menjadi Global Layout (`app/views/layout.php`) agar prinsip DRY (*Don't Repeat Yourself*) berlaku lintas peran.
- Perbaikan struktur *folder* tampilan (`app/views/pos.php` dan `app/views/admin/dashboard.php`) agar rapi sesuai hierarki modul.
- Konsistensi UI/UX: Menyeragamkan *behavior* Sidebar di halaman POS dengan Admin, menggunakan mode *manual collapse* (via tombol Hamburger) menggantikan efek *hover otomatis*.
- Pembaruan rute *Base URL* pada navigasi *sidebar* agar perpindahan antar modul (POS <-> Dashboard Admin) tetap presisi dan tidak menghasilkan *error 404 Not Found*.
📂 yoripos (Folder paling luar / Root)
 ┣ 📂 admin
 ┃ ┗ 🐘 index.php
 ┣ 📂 api
 ┃ ┗ 🐘 index.php
 ┣ 📂 app
 ┃ ┣ 📂 config
 ┃ ┣ 📂 models
 ┃ ┗ 📂 views
 ┃   ┣ 📂 admin
 ┃   ┣ 🐘 layout.php
 ┃   ┗ 🐘 pos.php
 ┗ 📂 pos             <-- SEJAJAR SAMA admin & api
   ┗ 🐘 index.php     <-- Pakai require_once layout.php

## [1.0.0-alpha.27] - 2026-05-05
### Added
- Pembuatan antarmuka **Master Produk** (`app/views/admin/products.php`) untuk *Super Admin*.
- Implementasi desain tabel *Anti Jepat Core* dengan skema warna *Slate & Indigo*.
- Menambahkan tombol aksi untuk *Tambah Produk*, *Edit*, dan *Hapus* (UI *preparation*).

## [1.0.0-alpha.28] - 2026-05-05
### Added
- Modifikasi *Database*: Menambahkan kolom `category` pada tabel `products`.
---
ALTER TABLE products ADD COLUMN category VARCHAR(100) NULL AFTER sku;
---
- Implementasi fitur CRUD (*Create, Read, Update, Delete*) penuh pada Master Produk.
- Integrasi Form Input Modal dengan metode *AJAX fetch* untuk pengalaman *user* tanpa *reload* halaman.
- Penambahan fungsi SweetAlert untuk konfirmasi hapus data dan validasi Form.

## [1.0.0-alpha.29] - 2026-05-05
### Added
- Pembuatan tabel `categories` pada *database* untuk menyimpan master kategori dinamis.
- Pembuatan halaman **Master Kategori** (`app/views/admin/categories.php`) dengan fitur CRUD penuh.
- Penambahan rute `?page=categories` pada Admin Router.
- Integrasi *Dynamic Dropdown*: Form pada Master Produk sekarang mengambil daftar kategori secara *real-time* dari API (menghapus *hardcode* HTML).

## [1.0.0-alpha.30] - 2026-05-05
### Added
- Pembuatan tabel `stock_in` pada *database* untuk mencatat riwayat stok masuk beserta *Harga Beli (HPP)* sebagai pondasi algoritma FIFO.
- Pembuatan antarmuka **Manajemen Stok Masuk** (`app/views/admin/stocks.php`).
- Penambahan *Endpoint API* (`get_stocks` dan `save_stock`) dengan *Database Transaction* untuk memastikan penambahan riwayat `stock_in` dan *update* `products.total_stock` berjalan sinkron (menghindari *data corrupt*).

## [1.0.0-alpha.31] - 2026-05-05
### Changed & Added
- **Bugfix Stok**: Menyelaraskan *query* pada `ProductModel.php` agar membaca nilai stok aktual dari kolom `total_stock` di tabel `products`.
- **Fitur Estimasi Profit**: Menambahkan kolom "Harga Beli Terakhir" (*view only*) pada tabel Master Produk dengan melakukan *Subquery* ke tabel `stock_in`.
- Modifikasi UI tabel Master Produk untuk menampilkan indikator Margin Keuntungan (Selisih Harga Jual dan Harga Beli Terakhir) untuk memudahkan Owner memantau profitabilitas.

## [1.0.0-alpha.32] - 2026-05-05
### Changed
- **Redesain UI Master Produk**: Memisahkan kolom finansial menjadi 3 bagian terpisah (`HPP`, `Harga Jual`, `Profit`) untuk pembacaan metrik bisnis yang lebih intuitif.
- Memperbarui algoritma *render* JavaScript untuk menghitung dan menampilkan Nominal Profit dan Persentase Margin secara terpisah di kolom *Profit*.

## [1.0.0-alpha.33] - 2026-05-05
### Added
- **Opsi Pembayaran**: Menambahkan pilihan metode pembayaran (Tunai, QRIS, Transfer) pada antarmuka Kasir (POS).
- **Integrasi API Checkout**: Menghubungkan pilihan metode pembayaran dinamis ke sistem *backend* `api/index.php`.
- **Fitur Struk Digital**: 
  - Optimalisasi format cetak HTML ke PDF menggunakan fungsi bawaan browser (Save as PDF).
  - Menambahkan *WhatsApp Text Generator* untuk mengirim struk langsung ke kontak pembeli via WA secara otomatis.

## [1.0.0-alpha.34] - 2026-05-27
### Added
- **UI/UX Pembayaran**: Menambahkan Modal Pembayaran (Checkout) khusus untuk mempermudah kasir.
- **Kalkulator Kembalian Otomatis**: Fitur input jumlah uang tunai yang diterima dengan perhitungan kembalian *real-time*, termasuk tombol cepat (Uang Pas & Saran Nominal Bulat).
- **Pengaturan Kertas Struk**: Mengunci format cetak (PDF/Printer) menggunakan CSS `@page { size: 80mm auto; }` agar hasil cetak memanjang menyesuaikan standar *thermal printer* kasir.

### Changed
- Memindahkan opsi Metode Pembayaran (Tunai, QRIS, Transfer) dari panel keranjang utama ke dalam Modal Pembayaran.

## [1.0.0-alpha.35] - 2026-05-27
### Added
- **UI Statis Pembayaran**: Menambahkan area khusus untuk menampilkan gambar statis kode QRIS di dalam Modal Checkout jika kasir memilih metode QRIS.
- **Fitur Ubah QRIS**: Penambahan *placeholder* UI (Overlay) untuk fitur *upload/ubah* gambar QRIS agar dinamis.
- **Info Rekening Bank**: Menambahkan pilihan layanan mutasi / transfer tujuan (Mandiri, BNI, BRI) secara *hardcode* dalam Modal Checkout agar kasir mudah memandu pelanggan melakukan pembayaran *cashless*.

## [1.0.0-alpha.36] - 2026-05-27
### Added
- **Antarmuka Riwayat Transaksi**: Penambahan halaman `app/views/admin/transactions.php` dengan desain tabel *Anti Jepat Core* dan fitur pencarian invoice secara *real-time*.
- **Modal Detail Transaksi**: Menambahkan UI Popup untuk melihat detail *item* produk yang dibeli per transaksi dan menyertakan tombol aksi untuk Cetak Ulang Struk.
- **Backend - Get Transactions API**: Pembuatan method `getAllSales` di `SaleModel.php` menggunakan teknik penggabungan data (JOIN) antara *header* transaksi (`sales`) dan *detail* produk (`sale_details`).
- **Routing Endpoint**: Pembaruan `api/index.php` untuk menampung *endpoint* `?action=get_transactions` dan `admin/index.php` untuk merender *View* `?page=transactions`.

### Changed
- **Navigasi Sidebar**: Memperbarui *Global Layout* (`app/views/layout.php`) dengan menambahkan logika variabel `$isTransactions` dan `$isPosGroup` agar *dropdown* menu "Point of Sale" tetap aktif dan terbuka saat kasir mengakses halaman Riwayat Transaksi, serta memperbaiki tautan yang sebelumnya kosong (`href="#"`) menjadi rute yang tepat.

## [1.0.0-alpha.37] - 2026-05-27
### Added
- **Database Konsumen**: Penambahan tabel `customers` dan relasi kolom `customer_id` pada tabel `sales` untuk persiapan fitur *Delivery Order* dan analitik pelanggan.
- **Backend API Pelanggan**: Integrasi endpoint `?action=get_customers` dan `?action=save_customer` pada `api/index.php`.
- **Modifikasi Checkout**: Memperbarui parameter `processSale` di `SaleModel.php` untuk menyimpan data `customer_id` saat transaksi terjadi.
- **UI/UX Kasir**: 
  - Menambahkan *Dropdown* Pemilihan Pelanggan di dalam panel keranjang (Daftar Pesanan).
  - Menambahkan Modal Form untuk membuat Data Pelanggan Baru (Nama, No. WA, Alamat) secara *on-the-fly* dari antarmuka POS tanpa perlu pindah ke dashboard Admin.

  ## [1.0.0-alpha.38] - 2026-05-27
### Added
- **Arsitektur Checkout Bertingkat**: Memisahkan alur *checkout* menjadi 2 langkah untuk mendukung *scalability* aplikasi (Level 1: Menu Pemesanan, Level 2: Pembayaran Eksekusi).
- **UI Modal Checkout Menu**: 
  - Penambahan antarmuka pemilihan *Metode Pemesanan* (Bayar Langsung, Piutang, Buat Pesanan, Gabungkan Pesanan) dengan desain responsif.
  - Implementasi *placeholder* untuk fitur Diskon Transaksi & Biaya Tambahan.
  - Penambahan rekapitulasi *Rincian Pesanan* sebelum kasir memasukkan nominal uang.
### Changed
- Modifikasi logika fungsi *Checkout* pada `pos.php`: Tombol keranjang kini membuka *Checkout Menu*, sedangkan Modal Pembayaran (Tunai/QRIS/Transfer) akan dipicu setelah kasir mengonfirmasi opsi "Bayar Langsung".

## [1.0.0-alpha.39] - 2026-05-27
### Added
- **Dashboard Master Pelanggan**: Pembuatan halaman `app/views/admin/customers.php` untuk menampilkan daftar *database* konsumen secara lengkap dengan fitur *Search* (berdasarkan Nama dan No. WA).
- **CRUD Konsumen**: Mengembangkan kemampuan API endpoint pada `api/index.php` untuk tidak hanya menambahkan pelanggan (*Create*), tetapi juga mengedit (*Update*) dan menghapus (*Delete*) data pelanggan melalui antarmuka Super Admin.
- **Routing & Navigasi**: Pendaftaran rute `?page=customers` pada `admin/index.php` dan mengintegrasikannya ke dalam navigasi *Sidebar* utama.

## [1.0.0-alpha.40] - 2026-05-27
### Changed
- **Restrukturisasi Arsitektur Menu**: Memecah ekosistem Point of Sale menjadi menu mandiri yang lebih fokus. POS kini murni untuk halaman input kasir, sementara administrasi pasca-transaksi dipindahkan ke dalam modul baru.
- **Pembaruan Global Layout**: Modifikasi `layout.php` dengan menghilangkan sub-menu POS lama dan mengimplementasikan rumpun menu mandiri bernama **Manajemen Pesanan** guna mendukung skalabilitas aplikasi tingkat tinggi.

### Added
- **Modul Pesanan & Pengiriman**: Pembuatan rute halaman `?page=orders` pada router admin dan inisialisasi file view `app/views/admin/orders.php` sebagai wadah penampung data checkout pesanan berjalan (Pre-Order) dan pelacakan kurir *Delivery Order* di masa depan.
- **Pondasi Menu Piutang**: Menyesuaikan halaman riwayat transaksi menjadi sub-menu gabungan "Riwayat & Piutang" sebagai persiapan penanganan kasus cicilan / hutang konsumen.

## [1.0.0-alpha.41] - 2026-05-27
### Changed
- **Optimalisasi Kuantitas POS (Manual Input)**: Mengubah komponen counter jumlah barang di panel keranjang menjadi element `<input type="number">` interaktif berdasarkan referensi berkas citra `Cuplikan layar 2026-05-27 110136.png`, memudahkan kasir memproses transaksi partai besar tanpa lelah menekan tombol penambah[cite: 3].
- **Mekanisme Pencarian Pelanggan**: Merombak elemen select database konsumen menjadi kustom dropdown berbasis kolom pencarian teks teks interaktif guna menunjang akurasi pemilihan data pelanggan secara kilat.

## [1.0.0-alpha.42] - 2026-05-27
### Fixed
- **Sinkronisasi Data Konsumen di POS**: Memperbaiki isu kegagalan render listing pelanggan pada kustom dropdown kasir dengan menambahkan pemicu eksekusi `fetchCustomers()` ke dalam event listener `DOMContentLoaded` di file `pos.php`, memastikan database konsumen tersinkronisasi sempurna sejak halaman pertama kali dimuat.

## [1.0.0-alpha.43] - 2026-05-27
### Added
- **Popup Pemilihan Konsumen (Modal Selector)**: Mengimplementasikan sistem pencarian dan pemilihan data pelanggan berbasis modal mengambang mengantisipasi kegagalan muat data asinkron (*silent freeze*)[cite: 8].
- **Indikator Status Transaksi Asinkron**: Menambahkan indikator animasi pemuatan (*CSS Spinner Loader*) dan penanganan transparansi galat (*error display*) guna memantau integritas status pipa konektivitas TiDB Cloud[cite: 8].
- **Sinkronisasi Pembuatan Data Baru**: Menghubungkan tombol buat pelanggan di dalam popup langsung terintegrasi dengan Master Data Pelanggan di panel Admin[cite: 1, 8].

## [1.0.0-alpha.44] - 2026-05-27
### Added
- **Arsitektur Pemesanan & Delivery (Fase 1)**: Modifikasi skema tabel `sales` dengan menambahkan kolom indikator `type` (direct/order), `status` (proses/terkirim/lunas/piutang), dan `shipping_proof` guna memfasilitasi workflow aplikasi kurir logistik (PWA) di masa depan.
- **Workflow Buat Pesanan (POS)**: Membuka kunci fitur *Buat Pesanan* pada Menu Checkout Level 1, lengkap dengan sistem validasi kewajiban input data pelanggan (untuk alamat pengiriman) sebelum pesanan diproses.
### Changed
- Pembaruan algoritma `api/index.php` untuk mengatur perbedaan kodifikasi struk secara otomatis: kodifikasi *prefix* `INV-[ID]` akan dicetak untuk transaksi *Direct Payment*, sedangkan kodifikasi *prefix* `ORD-[ID]` dirilis khusus untuk *Pre-Order / Delivery*.

## [1.0.0-alpha.45] - 2026-05-27
### Fixed
- **Isolasi Data Pesanan vs Transaksi Selesai**: Memperbaiki anomali pemanggilan data pada `SaleModel.php` dengan memperkenalkan parameter asersi (*isOrderActive*) untuk memisahkan secara ketat *query* riwayat transaksi (`type = direct`) dengan antrean pesanan berjalan (`type = order`).
### Added
- **Dashboard Pesanan & Delivery**: Implementasi antarmuka dinamis pada `orders.php` untuk memonitor antrean pesanan aktif secara *real-time* berbasis API.
- **Sistem Mutasi State Transaksi**: Menambahkan kapabilitas API `complete_order` untuk mengonversi dokumen *Nota Order* (`ORD-`) menjadi *Invoice Lunas/Piutang* (`INV-`) beserta pembaruan statusnya setelah kurir atau tim operasional menyelesaikan pengiriman barang.

## [1.0.0-alpha.46] - 2026-05-27
### Changed
- **Pemisahan Entitas Transaksi**: Memecah halaman operasional menjadi *Riwayat Transaksi* murni dan *Monitoring Piutang* untuk akurasi rekapitulasi data.
- **Standarisasi Kodifikasi Dokumen**:
  - Transaksi *Bayar Langsung* (Lunas) menggunakan *prefix* `KWI-` (Kwitansi).
  - Pesanan *Pre-Order / Delivery* menggunakan *prefix* `ORD-`.
  - Pesanan yang diselesaikan dengan metode *Piutang* akan bermutasi menjadi `INV-` (Invoice) dan berpindah ke halaman *Monitoring Piutang*.
  - Pesanan yang diselesaikan dengan metode *Lunas* akan bermutasi menjadi `KWI-` dan berpindah ke *Riwayat Transaksi*.
- **Template Struk Kasir**: Mengubah *header* cetak struk termal menjadi parameter pengaturan *placeholder* (*NAMA TOKO*, *Alamat*, *No. Telp*) sebagai persiapan modul *Settings* di fase mendatang.

## [1.0.0-alpha.47] - 2026-05-27
### Added
- **Digital Receipt Engine (HTML-to-Web)**: Menciptakan *endpoint* `?action=view_receipt` yang dapat melakukan *render* struk digital secara dinamis tanpa perlu menyimpan *file* PDF statis di server, sehingga menghemat *cloud storage* dan mempermudah akses lintas perangkat.
- **WhatsApp API Integration (URL Scheme)**: Menambahkan tombol tautan pintar pada kolom kontak tabel *Manajemen Pesanan* (Riwayat, Piutang, Pesanan Aktif) yang langsung mengarahkan perangkat kasir ke WhatsApp pembeli dengan melampirkan teks *template* beserta URL Struk Digital.
### Changed
- Mengoptimalkan *DataGrid* pada antarmuka admin dengan menggabungkan kolom Identitas Pelanggan dan Kontak (Tautan WA) untuk meningkatkan kecepatan respons operasional tanpa mengorbankan kepadatan layar.

## [1.0.0-alpha.48] - 2026-05-27
### Added
- **Modul Pengaturan Toko (Settings)**: Menyelesaikan *milestone* terakhir untuk MVP (Minimum Viable Product) dengan merilis halaman `app/views/admin/settings.php`. Modul ini mengonfigurasi Identitas Toko secara terpusat melalui struktur tabel Key-Value.
- **Kustomisasi Struk Kasir & Digital**: 
  - Mengizinkan kustomisasi nama toko, alamat, dan sosial media pada header cetak thermal dan HTML.
  - Membatasi input *Free Text Area* pada footer struk sebanyak maksimal 150 karakter untuk mencegah pemborosan kertas gulung (*thermal roll*).
  - Mengintegrasikan *Template* Format Teks WhatsApp yang dapat diubah sesuai bahasa *marketing* toko (*support dynamic variables* `{invoice}`, `{link}`, `{store_name}`).
### Note
- Fase **Alpha** secara resmi ditutup. Kode di-*freeze* untuk persiapan transisi menuju rilis kandida **1.0.0-beta.1**!

## [1.0.0-alpha.49] - 2026-05-27
### Added
- **Native Cloud Storage Integration**: Mengawinkan ekosistem backend *Vanilla PHP* dengan infrastruktur **Vercel Blob Storage** berbasis protokol *REST API cURL*. Solusi skala Enterprise yang dirancang khusus untuk melewati rintangan *serverless environment*, menggantikan kebutuhan penyimpanan data lokal.
- **REST API Blob Proxy**: Menulis endpoint `?action=upload_blob` untuk meredam risiko kebocoran otentikasi. Token rahasia Vercel `BLOB_READ_WRITE_TOKEN` diamankan sepenuhnya di sisi *Environment Variable* tanpa pernah terpapar di *frontend/browser*.
- **UI Asynchronous Image Uploader**: Memodifikasi antarmuka *Master Produk* dengan kapabilitas memunculkan *Live Image Preview* saat pemilihan gambar lokal dan melakukan injeksi *silent upload* tepat sebelum eksekusi *database insert* berlangsung.
- **Visualisasi Grid POS**: Memperbarui antarmuka `pos.php` agar *card* produk menampilkan gambar yang diunggah dari Vercel Blob. Jika gambar tidak tersedia, sistem akan menampilkan *placeholder* vektor otomatis.

## [1.0.0-beta.1] - 2026-05-27
### Changed
- **Status Rilis Dinaikkan**: Memasuki fase Beta seiring dengan ekspansi *scope* aplikasi dari sekadar Point of Sale (POS) menjadi sistem *Enterprise Resource Planning* (ERP) yang mencakup manufaktur F&B dasar.
- **Konversi UoM (Unit of Measurement) Stok Masuk**: Merombak logika `api/index.php` pada aksi `save_stock`. Admin kini tidak perlu menghitung manual harga satuan. Input form diubah menjadi *Total Harga Nota* dan sistem secara *backend* akan memecah nilainya menjadi harga dasar eceran (per gram/ml/pcs) untuk dimasukkan ke *pipeline* antrean HPP FIFO.

### Added
- **Segmentasi Tipe Item & Satuan Dasar**: Mengubah struktur tabel `products` dengan penambahan kolom `type` dan `unit`.
  - Item dengan tipe `bahan_baku` secara asinkron akan disembunyikan dari *grid* menu aplikasi Kasir (POS).
  - Penambahan kolom *Unit* memastikan akurasi pencatatan inventori lintas satuan metrik (Liter ke miliLiter, Kilogram ke Gram) sebagai persiapan untuk fitur *Bill of Materials* (Resep Menu).

## [1.1.0-beta.2] - 2026-05-29

### Added
- **Mesin Auto-Deduct HPP & BOM (Bill of Materials)**: `SaleModel.php` dirombak total. Saat transaksi POS terjadi, sistem kini dapat mendeteksi apakah produk tersebut adalah barang retail murni atau produk racikan (memiliki komposisi/resep). Sistem otomatis memotong stok bahan mentah (gram/ml/pcs) di gudang secara proporsional.
- **Dynamic Stock Balancing (POS)**: Aplikasi kasir kini memiliki kalkulator *real-time*. Stok barang racikan dihitung mundur berdasarkan ketersediaan bahan baku paling kritis di gudang (Tampil dengan indikator **"🛠️ BISA: X"**).
- **Modul Monitoring Piutang (Receivables)**: Penambahan halaman *Monitoring Piutang* untuk melacak invoice pelanggan yang berstatus Kasbon/Piutang, lengkap dengan tombol "Tandai Lunas" via CASH atau QRIS.
- **Struk Digital & Integrasi WhatsApp (Level 2)**: Menambahkan *endpoint* `view_receipt` untuk *render* HTML Nota Digital. Fitur Kirim INV via WhatsApp kini mengambil *template* dinamis dan data toko langsung dari tabel `settings`.

### Changed
- **Audit Trail Standar PSAK (Transisi Dokumen)**: Logika `completeOrder` pada *backend* disesuaikan dengan standar akuntansi F&B. Kode transaksi kini bermutasi sesuai *state*:
  - **`ORD-`** (Sales Order) -> Status `proses`.
  - **`INV-`** (Invoice/Piutang) -> Saat kurir melaporkan piutang, `ORD` berubah menjadi `INV` dan masuk antrean tagihan.
  - **`KWI-`** (Kwitansi/Lunas) -> Saat tagihan dilunasi, berubah menjadi Nota Lunas final.
- **Validasi Harga Bahan Baku**: Bypass validasi `empty()` pada PHP untuk mengizinkan input angka `0` pada harga jual bahan baku (karena bahan mentah tidak dijual langsung di POS).
- **Pemecahan Parameter FIFO TiDB**: Menyesuaikan lubang variabel PDO SQL pada antrean FIFO menjadi `:qty_initial` dan `:qty_remaining` untuk mencegah duplikasi parameter.

### Fixed
- **Bug `SQLSTATE[HY093]` Invalid Parameter**: Mengatasi kegagalan eksekusi TiDB saat menyimpan *batch* stok FIFO masuk.
- **Bug Constraint `fk_3` (Foreign Key)**: Menghapus relasi kaku `stock_batch_id` pada `sale_details` agar satu transaksi POS dapat memotong bahan baku dari banyak *batch* FIFO sekaligus tanpa diblokir oleh TiDB.
- **Bug Pesanan Kosong**: Memperbaiki fungsi `getSalesData` yang sebelumnya gagal memuat rincian keranjang (`sale_details`) ke dalam *grid* Manajemen Pesanan.
- **Isolasi Piutang**: Memperbaiki filter SQL agar transaksi Kasir bermetode `PIUTANG` tidak nyasar masuk ke halaman *Riwayat Transaksi (Lunas)*.

### Database Updates
- **Tabel `sales`**: Penambahan kolom `reference_no` (VARCHAR) untuk menyimpan rekam jejak mutasi kode nota (menyimpan kode `ORD` lama saat struk berubah menjadi `INV`).
- **Tabel `sale_details`**: Menghapus *Foreign Key* `fk_3` pada `stock_batch_id` untuk mendukung algoritma pemotongan *multi-batch* FIFO.

## [1.1.0-beta.3] - 2026-05-29

### 🛠️ Fixed (Perbaikan Kritis)
- **Audit Trail Relasi KWI**: Memperbaiki logika fungsi `completeOrder` pada `SaleModel.php` agar transaksi yang dilunasi dari status Piutang (`INV-`) mengunci kode `INV-` tersebut sebagai `reference_no` (bukan melompat balik ke `ORD-`). Audit trail dokumen kini 100% patuh standar PSAK (ORD ➔ INV ➔ KWI).
- **Buka Gembok Cetak Ulang**: Mengaktifkan tombol "Cetak Ulang Struk" di halaman `transactions.php` (Riwayat Transaksi) dan `receivables.php` (Monitoring Piutang). Menggunakan metode *Iframe Injection* bawaan Anti Jepat Core agar langsung memicu print preview thermal browser tanpa alert gembok lagi.

## [1.1.0-beta.5] - 2026-05-30

### ✨ Added (Fitur Baru)
- **Modul Pengaturan Toko**: Menambahkan `SettingModel.php`, tabel `settings`, dan API endpoints untuk menyimpan pengaturan toko (Nama, Alamat, Sosmed, Footer Struk) secara dinamis.
- **Dynamic Payment Gateway (Manual)**: Mengintegrasikan daftar Rekening Bank dan Multi-QRIS dari Pengaturan Toko langsung ke Modal Pembayaran di POS.
- **Anti-Blocker Placeholder**: Mengganti URL gambar *placeholder* QRIS dengan kode SVG Base64 bawaan *browser* agar kebal dari pemblokiran ISP lokal.

### 🛠️ Fixed (Perbaikan Kritis)
- **Cuci Gudang API Server (`api/index.php`)**: Membersihkan kode ganda (*duplicate code*) yang menyebabkan error `Undefined variable $sale`.
- **Pathing Issue**: Memperbaiki pemanggilan `require_once` menggunakan `__DIR__` untuk mencegah gagal *load* model yang memicu *SyntaxError JSON*.

## [1.1.0-beta.6] - 2026-05-30

### ✨ Added (Fitur Baru)
- **Dashboard Analytics**: Menambahkan `ReportModel.php` untuk memanen data akuntansi FIFO. Dashboard kini menampilkan kalkulasi Omzet Hari Ini, Laba Kotor Hari Ini, akumulasi bulanan, dan 5 Produk Terlaris secara *real-time*.

## [1.1.0-beta.7] - 2026-05-30

### ✨ Added (Fitur Baru)
- **Advanced Analytics Dashboard**: Memperluas metrik pada `ReportModel.php` dan `dashboard.php` untuk menampilkan visualisasi komprehensif, meliputi:
  - **Status Piutang & Pesanan**: Metrik *real-time* jumlah *invoice* menggantung beserta nominal totalnya.
  - **Distribusi Saldo Pembayaran**: Rincian total pendapatan bulanan yang dipecah secara dinamis berdasarkan metode bayar (Tunai, QRIS, Transfer).
  - **Sistem Peringatan Dini (Early Warning)**: Indikator khusus untuk memantau 5 daftar produk dengan *total_stock* yang menipis (≤ 10).

  ## [1.1.0-beta.8] - 2026-05-30

### 🔐 Security & RBAC (Sistem Keamanan)
- **Granular RBAC (Role-Based Access Control)**: Mengimplementasikan sistem hak akses berbasis JSON. Izin ruangan dipecah menjadi 7 kunci: `pos`, `dashboard`, `products`, `stocks`, `transactions`, `settings`, `users`.
- **Sistem Autentikasi**: Menambahkan halaman Login terintegrasi (`login.php`) dengan proteksi sesi (PHP Session) di `admin/index.php`.
- **Auto-Provisioning**: Sistem otomatis membuat *role* 'Owner' dan *user* 'admin' (password: admin123) pada saat *login* pertama kali jika database kosong.

## [1.1.0-beta.9] - 2026-05-30

### 🏗️ Architecture & Refactoring
- **Centralized Configuration**: Memisahkan semua kredensial API dan kunci server (seperti `BLOB_READ_WRITE_TOKEN` dan `APP_URL`) ke dalam satu *file* aman `app/config/config.php` untuk mempermudah proses *deployment* dan keamanan skala *Enterprise*.
- **Clean API Router**: Menghapus *hardcoded environment variables* dari dalam `api/index.php`.

## [1.1.0-beta.10] - 2026-05-30

### 🏗️ Architecture & UI/UX
- **UI Modularization**: Memisahkan antarmuka Sidebar menjadi file parsial `app/views/sidebar.php` untuk mempermudah *maintenance* dan navigasi.
- **Dynamic RBAC Menu**: Menginjeksi logika *permissions* JSON ke dalam Sidebar, secara otomatis menyembunyikan menu-menu *back-office* (seperti Dashboard, Master, Pengaturan) dari pengguna dengan tingkat akses Kasir.
- **User & Role Control Room**: Membuat antarmuka `/admin/?page=users` sebagai *control room* *Super Admin* untuk manajemen data staf (Akun Login) dan Jabatan (hak akses/ *permissions* menggunakan fitur *checkbox*).

## [1.1.0-beta.11] - 2026-05-30

### 🎨 UI/UX & Branding
- **White-labeling Sidebar**: Mengganti logo dan nama aplikasi di header navigasi (*sidebar*) secara dinamis menggunakan Logo dan Nama Toko dari pengaturan *database*. Jika logo kosong, sistem otomatis merender inisial toko.
- **Sticky YoriPOS Footer**: Memindahkan *branding* "YoriPOS" ke bagian bawah (*footer*) sidebar sebagai penanda *Powered by* yang *sticky* dan elegan.
- **Fitur Upload Logo Toko**: Menambahkan *uploader* dengan kompresi HTML5 Canvas (Max 400px, 80% JPEG) di halaman Pengaturan Toko (`settings.php`) untuk menghemat kapasitas *database*.

## [1.1.0-beta.12] - 2026-05-30

### ✨ Added (Fitur Baru)
- **Modul Pengeluaran (OPEX)**: Menambahkan antarmuka `expenses.php` khusus untuk mencatat biaya operasional (seperti listrik, air, gaji, ATK, dan sewa) secara terpisah agar tidak merusak perhitungan HPP pada Stok Masuk (FIFO).
- **Database & API Expenses**: Membuat tabel `expenses` di TiDB dan menanamkan *endpoint* CRUD (`get_expenses`, `save_expense`, `delete_expense`) pada `api/index.php`.
- **Integrasi Menu & RBAC**: Mendaftarkan rute `expenses` pada *router* utama `admin/index.php` (menggunakan kunci hak akses `transactions`) dan menempatkan tombol navigasinya pada `sidebar.php`.

## [1.1.0-beta.13] - 2026-05-30

### 📊 Reporting & Analytics
- **Corporate Income Statement (P&L)**: Mengubah format cetak Laporan Laba Rugi menjadi desain *Enterprise* 2 kolom (Pendapatan & HPP vs Pengeluaran Operasional) di file `print_pl.php`.
- **Data Visualization**: Mengintegrasikan `Chart.js` untuk merender *Pie Chart* proporsi biaya (COGS, Labor Expense, Other Expense) secara dinamis pada halaman cetak.
- **Print Layout Bypass**: Memodifikasi *router* di `admin/index.php` (menambahkan *case* `print_pl`) untuk mem-*bypass* `layout.php`. Hal ini memastikan halaman cetak benar-benar bersih (A4 *Portrait*) tanpa campur tangan CSS *Sidebar*, *Header*, atau *Scrollbar* bawaan *dashboard*.
- **New Tab Trigger**: Mengubah fungsi tombol cetak di `report_pl.php` untuk membuka *tab* baru (`window.open`) dan otomatis memicu dialog `window.print()` setelah grafik selesai dimuat.

## [1.1.0-rc.1] - 2026-05-30

### ✨ Added (Fitur Baru)
- **Daily Closing (Shift Kasir)**: Menambahkan fitur manajemen Shift di aplikasi POS. Kasir diwajibkan menginput Uang Modal Awal saat buka shift, dan uang fisik akhir saat tutup shift. Sistem akan otomatis menghitung selisih (*Over/Short*) dan mencetak Struk Rekap Kasir (End of Day).
- **Softclosing & Annual Closing**: Merilis modul `closing.php` bagi Super Admin untuk mengunci pembukuan bulanan dan tahunan. Mencegah manipulasi atau perubahan data P&L pada periode yang sudah dilaporkan/diaudit.
- **VIP Backdate Mode**: Menambahkan form input *Tanggal Transaksi* rahasia di layar Pembayaran POS. Fitur "Jalur Dalam" ini otomatis disembunyikan dari Kasir dan hanya bisa diakses oleh akun tingkat Owner (memiliki hak akses `settings`).

### 🔐 Security & Validation
- **Gembok Transaksi Lintas Periode**: Menginjeksi *middleware* validasi pada API `checkout`. Sistem akan menolak mutlak seluruh transaksi kasir (termasuk *request backdate* dari Owner) apabila tanggal transaksi tersebut berada di bulan atau tahun yang sudah di- *closing*.

### 🏗️ Architecture & Refactoring
- **Sinkronisasi Payload Checkout**: Merombak fungsi `processSale` pada `SaleModel.php` dari parameter terpisah menjadi satu kesatuan array `$data` guna mempermudah injeksi manipulasi waktu (`created_at`) dari API *frontend*.
- **Database Restructuring**: Drop dan *recreate* tabel `cashier_shifts` agar strukturnya lebih dinamis, serta menambahkan tabel `closed_periods` dan `closed_years` sebagai brankas audit trail.

## [1.1.0-rc.2] - 2026-05-30

### 🛠️ Debugging & Maintenance (Fitur Baru)
- **Global Error Logger (Black Box)**: Mengimplementasikan sistem pencatat *error* diam-diam (*silent logger*) berbasis *database* TiDB. Sistem kini secara otomatis menangkap *Fatal Error/Exception* dari PHP (*Backend*) dan *Syntax/Runtime Error* dari Javascript (*Frontend* di `layout.php`) tanpa mengganggu pengalaman pengguna.
- **System Error Logs Dashboard**: Membuat antarmuka kontrol monitor (`app/views/admin/logs.php`) bergaya *terminal server* untuk Super Admin. Halaman ini memungkinkan pelacakan *bug* secara *real-time* lengkap dengan informasi *Stack Trace* dan waktu kejadian, serta fitur pembersihan log (*Clear Logs*).
- **Endpoint Logger API**: Menambahkan *endpoint* `log_error`, `get_logs`, dan `clear_logs` pada `api/index.php` untuk melayani lalu lintas pelaporan sistem dari berbagai halaman.
- **Perbaikan Typo Kasir**: Memperbaiki isu *TypeError* pada `pos.php` (penggantian ID `cartTotal` menjadi `payTotalDisplay`) yang sempat menghentikan fungsi keranjang akibat perubahan *layout*.

## [1.1.0-rc.3] - 2026-05-30

### ✨ Added (Fitur Baru & UI/UX)
- **Live Shift Monitor (POS)**: Menambahkan 4 panel statistik (*Modal, Cash Masuk, QRIS, Transfer*) di atas grid produk pada mesin kasir (`pos.php`). Pendapatan kasir kini ter- *update* secara *real-time* setiap ada transaksi berhasil.
- **Smart Document Badges (POS)**: Menambahkan indikator jumlah dokumen aktif harian (LUNAS/KWI, PESANAN/ORD, PIUTANG/INV) di *header* kasir. Indikator ini sekaligus berfungsi sebagai tombol *shortcut* ke modul masing-masing.
- **Datagrid & Horizontal Calendar**: Merombak total desain halaman `orders.php`, `transactions.php`, dan `receivables.php`. Mengganti format *card* menjadi tabel *Datagrid* yang lebih padat dan informatif.
- **Smart Date Filter**: Menginjeksi kalender horizontal interaktif di seluruh halaman transaksi. Kalender secara cerdas akan me- *disable* tanggal yang tidak memiliki data (mengurangi beban server) dan memberi warna merah khusus untuk hari Minggu.
- **Receipt Cleansing**: Menghapus stempel miring "BELUM LUNAS" pada nota digital HTML agar tampilan lebih bersih, minimalis, dan profesional ala sistem *Enterprise*.

### 🐛 Fixed (Perbaikan Bug)
- **Order Mutation Crash**: Memperbaiki *Fatal Error* pada `SaleModel.php` (salah panggil variabel `$this->db` menjadi `$this->conn`) yang sebelumnya menyebabkan pesanan gagal diselesaikan atau diubah menjadi Piutang.
- **Cart Total TypeError**: Memperbaiki *error* Javascript pada `pos.php` akibat perubahan ID DOM dari `cartTotal` menjadi `payTotalDisplay` yang sempat melumpuhkan fitur keranjang.
- **Shift Query Exception**: Menghapus filter pengecekan `user_id` pada histori transaksi saat menghitung uang laci. Perbaikan ini mencegah *crash database* yang sebelumnya terus-menerus meminta kasir memasukkan uang modal akibat struktur tabel `sales` versi lama.