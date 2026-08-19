// Service Worker — Tiritaito for Creators (V1, web vieja)
// Versión de caché: incrementar SIEMPRE que se suba un HTML nuevo.
// ⚠️ CONFIRMADO EN PRODUCCIÓN (agosto 2026): a diferencia de lo que
// documenta TIRITAITO_FOR_CREATORS_VERSIONS.md Sección 1 ("V1 — un
// archivo nuevo por versión"), la carpeta real del servidor
// SOBREESCRIBE siempre el mismo nombre de archivo fijo
// (tiritaito-creators-v1-01.html), igual que V2. La versión real solo
// se sabe por el footer del HTML. Por eso este CACHE_NAME es la única
// forma de forzar que el móvil descarte una caché vieja — si no se
// incrementa en cada entrega, el navegador puede seguir sirviendo el
// HTML/manifest antiguos aunque se sobrescriba el archivo en el servidor.
const CACHE_NAME = 'tt-creators-v1-10';

// Solo se cachean assets ESTÁTICOS. Las llamadas a /tiritaito/v1/*
// (Devocional, Novedades, Biblioteca de Medios) NUNCA se cachean —
// tienen que llegar siempre frescas del servidor. Ver fetch() más abajo.
// Ruta real confirmada contra el servidor (agosto 2026): la carpeta
// lleva espacios en el nombre ("Tiritaito for Creators"), codificados
// como %20 en la URL — NO "TiritaitoforCreators" sin espacios.
const ASSETS_ESTATICOS = [
  'https://www.tiritaito.com/blog/Tiritaito%20for%20Creators/App/tiritaito-creators-v1-01.html',
  'https://www.tiritaito.com/blog/Tiritaito%20for%20Creators/App/manifest.json',
  'https://www.tiritaito.com/blog/wp-content/uploads/2026/06/IMAGE-2026-06-19-10-47-20_resultado.webp',
  'https://www.tiritaito.com/blog/wp-content/uploads/2026/04/YeahPapa.woff2'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS_ESTATICOS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((nombres) =>
      Promise.all(
        nombres
          .filter((nombre) => nombre !== CACHE_NAME)
          .map((nombre) => caches.delete(nombre))
      )
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const url = event.request.url;

  // Regla crítica: cualquier petición al endpoint propio de la API
  // (datos, novedades, medios, subir) va SIEMPRE a red, nunca a caché.
  // Si esto se cachea por error, el equipo vería contenido desactualizado
  // (Devocional o Novedades de ayer) sin saber que es una caché vieja.
  if (url.includes('/wp-json/tiritaito/')) {
    event.respondWith(fetch(event.request));
    return;
  }

  // Assets estáticos: caché primero, red como respaldo si no está cacheado.
  event.respondWith(
    caches.match(event.request).then((respuestaCacheada) => {
      return respuestaCacheada || fetch(event.request);
    })
  );
});
