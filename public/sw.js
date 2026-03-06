const CACHE_NAME = 'gestor-financeiro-v1';

const STATIC_ASSETS = [
  '/',
  '/dashboard',
  '/manifest.json',
  '/favicon.svg',
  '/icon-192.svg',
  '/icon-512.svg',
];

// Instalacao: cacheia assets estaticos
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch(() => {});
    })
  );
  self.skipWaiting();
});

// Ativacao: remove caches antigos
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k))
      )
    )
  );
  self.clients.claim();
});

// Fetch: network-first para paginas, cache-first para assets estaticos
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Ignora requests que nao sao GET ou sao de outros dominios
  if (event.request.method !== 'GET' || url.origin !== self.location.origin) {
    return;
  }

  // Assets estaticos (build, fontes, imagens): cache-first
  if (url.pathname.startsWith('/build/') || url.pathname.match(/\.(svg|ico|png|jpg|woff2?)$/)) {
    event.respondWith(
      caches.match(event.request).then((cached) => {
        return cached || fetch(event.request).then((response) => {
          if (response.ok) {
            const clone = response.clone();
            caches.open(CACHE_NAME).then((c) => c.put(event.request, clone));
          }
          return response;
        });
      })
    );
    return;
  }

  // Paginas e API: network-first, fallback para cache
  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request);
    })
  );
});
