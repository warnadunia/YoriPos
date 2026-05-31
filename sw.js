// Service Worker super ringan buat bypass syarat install PWA
self.addEventListener('install', (e) => {
    self.skipWaiting();
});
self.addEventListener('activate', (e) => {
    return self.clients.claim();
});
self.addEventListener('fetch', (e) => {
    // Biarkan aplikasi tetap online-first (langsung ke server)
});