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