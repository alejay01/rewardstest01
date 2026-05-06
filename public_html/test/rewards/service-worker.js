const CACHE_NAME = 'boudin-rewards-phase1-v1';
const APP_SHELL = [
  './',
  './index.php',
  './join.php',
  './wallet.php',
  './redeem.php',
  './admin.php',
  './assets/css/app.css',
  './assets/js/app.js',
  './assets/img/boudin-rewards-mark.svg'
];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(APP_SHELL)));
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(
      keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
    ))
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cached) => cached || fetch(event.request))
  );
});
