<?php

require_once __DIR__.'/functions.php';
require_once __DIR__.'/classes/router.class.php';

// declare routes
$router = new Router();

$router->get('/', function() {
    echo 'Test 123';
});


// decode resource
$resource = decodeResource($_SERVER['REQUEST_URI']);

// run resource
$router->run($_SERVER['REQUEST_METHOD'], $resource);