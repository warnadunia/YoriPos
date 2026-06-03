<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AsayoriPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl border border-slate-200 m-4">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl font-black mx-auto mb-4 shadow-lg shadow-indigo-200 transform -rotate-3">Y</div>
            <h2 class="text-2xl font-black text-slate-800">AsayoriPOS</h2>
            <p class="text-sm text-slate-500 mt-1">Sistem Manajemen Bisnis & Kasir</p>
        </div>

        <form id="loginForm" onsubmit="executeLogin(event)" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                <input type="text" id="username" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-slate-800 font-medium" placeholder="Masukkan username...">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" id="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-slate-800 font-medium" placeholder="••••••••">
            </div>
            <button type="submit" id="btnLogin" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-200 transition duration-200 mt-4">
                Masuk ke Sistem
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 mt-8">Default Login perdana: <b>admin / admin123</b></p>
    </div>

    <script>
        async function executeLogin(e) {
            e.preventDefault();
            const btn = document.getElementById('btnLogin');
            btn.innerText = 'Memverifikasi...';
            btn.disabled = true;

            const payload = {
                username: document.getElementById('username').value,
                password: document.getElementById('password').value
            };

            try {
                const res = await fetch('../api/?action=login', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                
                if (result.status === 'success') {
                    // LOGIKA BARU: Deteksi otomatis device pakai Regex JS
                    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

                    if (isMobile) {
                        // Kalau HP, langsung tembak ke URL PWA Dashboard
                        window.location.href = '../admin/?page=mobile';
                    } else {
                        // Kalau PC/Laptop, tembak ke Dashboard Utama
                        window.location.href = '../admin/?page=dashboard'; 
                    }
                } else {
                    Swal.fire('Gagal Login', result.message, 'error');
                }
            } catch (err) {
                Swal.fire('Error', 'Kesalahan koneksi ke server.', 'error');
            } finally {
                btn.innerText = 'Masuk ke Sistem';
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>