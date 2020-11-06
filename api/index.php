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
    $dataset = $db->query('
    SELECT ig.id AS itemGroupId, ig.name AS itemGroupName, ig.position AS itemGroupPosition,
        i.id, i.name, i.image, i.price, i.position, r.rating
        FROM items i
        INNER JOIN itemgroups ig
            ON i.groupId = ig.id
        INNER JOIN (
            SELECT i.id, ROUND(IFNULL(AVG(r.score), 0), 1) AS rating
                FROM items i
                LEFT JOIN ratings r
                    ON i.id = r.itemId
                GROUP BY i.id
        ) r
            ON i.id = r.id
    ')->fetchAll(PDO::FETCH_ASSOC);

    // process data
    $itemGroups = [];
    foreach($dataset as $item) {
        $index = array_search($item['itemGroupId'], array_column($itemGroups, 'id'));
        $tempItem = $item;
        unset($item['itemGroupId']);
        unset($item['itemGroupName']);
        unset($item['itemGroupPosition']);
        
        if ($index !== false) {
            $itemGroups[$index]['items'][] = $item;
        }
        else {
            $itemGroups[] = [
                'id' => $tempItem['itemGroupId'],
                'name' => $tempItem['itemGroupName'],
                'position' => $tempItem['itemGroupPosition'],
                'items' => [$item]
            ];
        }
    }

    return $itemGroups;
});

$router->get('/orders', function ($params) {
    return null;
});

$router->post('/orders', function ($params) {
    return null;
});

$router->get('/users', function ($params) use ($db) {
    // check if user exists in database and get balance
    $PDOStatement = $db->prepare('
        SELECT IFNULL(SUM(t.amount), 0) AS balance
            FROM users u
            LEFT JOIN transactions t
                ON u.id = t.userId
            WHERE u.email = :email
            GROUP BY u.id
    ');
    $PDOStatement->bindValue(':email', $params['user']['email'], PDO::PARAM_STR);
    $PDOStatement->execute();

    if ($PDOStatement->rowCount() > 0) {
        return $PDOStatement->fetch(PDO::FETCH_ASSOC);
    }
    else {
        // insert record
        $PDOStatement = $db->prepare('
            INSERT INTO users (name, email)
                VALUES (:name, :email)
        ');
        $PDOStatement->bindValue(':name', $params['user']['family_name'], PDO::PARAM_STR);
        $PDOStatement->bindValue(':email', $params['user']['email'], PDO::PARAM_STR);
        $PDOStatement->execute();
        return ['balance' => 0];
    }
});

$router->post('/feedback', function ($params) {
    return null;
});

// run resource
$router->run($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);