const assets = [
    '/',
    '/index.html',
    '/css/styles.css',
    '/js/main.js',
    '/images/Sun-Tornado.svg',
    '/images/butter-cheese.jpg',
    '/images/chapati.jpg',
    '/images/chickenfriedrice-10.jpg',
    '/images/egg.jpg',
    '/images/grilled-veg.jpg',
    '/images/idli.jpg',
    '/images/jam.jpg',
    '/images/medu-vada.jpg',
    '/images/misal-pav.jpg',
    '/images/Poha-Recipe.jpg',
    '/images/Rava-Upma.jpg',
    '/images/SCHEZWAN-CHICKEN-NOODLES.jpg',
    '/images/veg-cheese.jpg',
    '/images/veg-fried-rice-recipe-1.jpg',
    '/images/veg-noodles-recipe-1.jpg',
    '/images/Vegetable-Sandwich.jpg'
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