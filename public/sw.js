const CACHE_NAME = 'caminho-limpo-shell-v1';
const OFFLINE_FILES = ['/offline.html', '/manifest.webmanifest', '/icons/pwa-192.svg', '/icons/pwa-512.svg'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(OFFLINE_FILES)));
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)),
        )),
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET' || new URL(request.url).origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(fetch(request).catch(() => caches.match('/offline.html')));
        return;
    }

    if (new URL(request.url).pathname.startsWith('/build/')) {
        event.respondWith(caches.match(request).then((cached) => cached ?? fetch(request)));
    }
});
