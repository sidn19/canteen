<?php

require_once __DIR__.'/classes/router.class.php';

// declare routes
$router = new Router();

$router->get('/', function($params) {
    return [
        'resources' => [
            [
                'method' => 'GET',
                'path' => '/menu'
            ],
            [
                'method' => 'GET',
                'path' => '/orders'
            ],
            [
                'method' => 'POST',
                'path' => '/orders'
            ],
            [
                'method' => 'GET',
                'path' => '/users?id=:id'
            ],
            [
                'method' => 'POST',
                'path' => '/feedback'
            ]
        ]
    ];
});

$router->get('/menu', function ($params) {
    return null;
});

$router->get('/orders', function ($params) {
    return null;
});

$router->post('/orders', function ($params) {
    return null;
});

$router->get('/users', function ($params) {
    return null;
});

$router->post('/feedback', function ($params) {
    return null;
});

// run resource
$router->run($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);