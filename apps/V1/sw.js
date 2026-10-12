// Service Worker — Tiritaito for Creators (V1, web vieja)
// El HTML va RED PRIMERO: con conexión siempre llega la versión actual del servidor;
// la caché solo se usa sin conexión. Ya no hace falta recordar subir CACHE_NAME en cada entrega.
// CACHE_NAME solo se incrementa si cambian los assets estáticos (logo, fuente, manifest).
const CACHE_NAME = 'tt-creators-v1-16';

const HTML_URL = 'https://www.tiritaito.com/blog/Tiritaito%20for%20Creators/App/tiritaito-creators-v1-01.html';
const ASSETS_ESTATICOS = [
  HTML_URL,
  'https://www.tiritaito.com/blog/Tiritaito%20for%20Creators/App/manifest.json',
  'https://www.tiritaito.com/blog/wp-content/uploads/2026/06/IMAGE-2026-06-19-10-47-20_resultado.webp',
  'https://www.tiritaito.com/blog/wp-content/uploads/2026/04/YeahPapa.woff2'
];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE_NAME).then((c) => c.addAll(ASSETS_ESTATICOS)));
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((n) =>
      Promise.all(n.filter((x) => x !== CACHE_NAME).map((x) => caches.delete(x)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;
  const url = req.url;

  // API propia: siempre a red, nunca caché.
  if (url.includes('/wp-json/tiritaito/')) {
    event.respondWith(fetch(req));
    return;
  }

  // HTML: red primero (saltando la caché HTTP), caché como respaldo sin conexión.
  if (req.mode === 'navigate' || /\.html(\?|$)/.test(url)) {
    event.respondWith(
      fetch(url, { cache: 'no-cache' })
        .then((resp) => {
          if (resp && resp.ok) {
            const copia = resp.clone();
            caches.open(CACHE_NAME).then((c) => c.put(HTML_URL, copia));
          }
          return resp;
        })
        .catch(() => caches.match(HTML_URL))
    );
    return;
  }

  // Resto de assets estáticos: caché primero.
  event.respondWith(caches.match(req).then((r) => r || fetch(req)));
});
