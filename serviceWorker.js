const assets = [
    '/canteen/',
    '/canteen/index.html',
    '/canteen/css/styles.css',
    '/canteen/js/main.js',
    '/canteen/images/Sun-Tornado.svg',
    '/canteen/images/butter-cheese.jpg',
    '/canteen/images/chapati.jpg',
    '/canteen/images/chickenfriedrice-10.jpg',
    '/canteen/images/egg.jpg',
    '/canteen/images/grilled-veg.jpg',
    '/canteen/images/idli.jpg',
    '/canteen/images/jam.jpg',
    '/canteen/images/medu-vada.jpg',
    '/canteen/images/misal-pav.jpg',
    '/canteen/images/Poha-Recipe.jpg',
    '/canteen/images/Rava-Upma.jpg',
    '/canteen/images/SCHEZWAN-CHICKEN-NOODLES.jpg',
    '/canteen/images/veg-cheese.jpg',
    '/canteen/images/veg-fried-rice-recipe-1.jpg',
    '/canteen/images/veg-noodles-recipe-1.jpg',
    '/canteen/images/Vegetable-Sandwich.jpg'
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