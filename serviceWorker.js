const assets = [
    '/canteen/',
    '/canteen/index.html',
    '/canteen/css/styles.css',
    '/canteen/js/main.js'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open('college-canteen').then(cache => {
            cache.addAll(assets)
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request)
        })
    );
});