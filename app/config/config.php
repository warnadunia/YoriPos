<?php
// =========================================================
// CENTRALIZED CONFIGURATION (Pengaturan Kunci API & Server)
// =========================================================

// Token Vercel Blob Storage
define('BLOB_READ_WRITE_TOKEN', getenv('BLOB_READ_WRITE_TOKEN') ?: 'vercel_blob_rw_nqfPnuiuW2lY0KRe_Ss57T1f5TlP6FtJ0qDyhotllFkfCqK');

// URL Dasar Aplikasi (Otomatis deteksi dari Vercel, fallback ke localhost)
define('APP_URL', getenv('VERCEL_URL') ? 'https://' . getenv('VERCEL_URL') : 'http://localhost/yoripos');

// Nanti lu bisa tambahin API Key Midtrans, Qiscus (WA), dll di bawah sini:
// define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-xxx');
?>