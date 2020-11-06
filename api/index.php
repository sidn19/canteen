<?php

require_once __DIR__.'/classes/router.class.php';
require_once __DIR__.'/classes/database.class.php';

// initialize database connection
$db = null;

try {
    $db = new Database();
}
catch(PDOException $e) {
    http_response_code(500);
    die('Failed to connect to the database');
}

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
                'path' => '/users?token=:token'
            ],
            [
                'method' => 'POST',
                'path' => '/feedback'
            ]
        ]
    ];
});

$router->get('/menu', function ($params) use ($db) {
    return $db->query('SELECT * FROM items')->fetchAll(PDO::FETCH_ASSOC);
});

$router->get('/orders', function ($params) {
    return null;
});

$router->post('/orders', function ($params) {
    return null;
});

$router->get('/users', function ($params) {
    require_once __DIR__.'/vendor/autoload.php';
    $client = new Google_Client(['client_id' => '570178535400-0ljjrn2urq7el0maauibd1qjq0482n76.apps.googleusercontent.com']);
    $payload = $client->verifyIdToken($params['token']);
    
    if ($payload) {
        return $payload;
    }
    else {
        http_response_code(400);
        die('Invalid token!');
    }
    return $params;
});

$router->post('/feedback', function ($params) {
    return null;
});

// run resource
$router->run($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);