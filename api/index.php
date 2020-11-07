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

$router->post('/orders', function ($params) use ($db) {
    $cart = json_decode($params['cart']);
    // get user id and balance
    $PDOStatement = $db->prepare('
        SELECT u.id, IFNULL(SUM(t.amount), 0) AS balance
            FROM users u
            LEFT JOIN transactions t
                ON u.id = t.userId
            WHERE u.email = :email
            GROUP BY u.id
    ');
    $PDOStatement->bindValue(':email', $params['user']['email'], PDO::PARAM_STR);
    $PDOStatement->execute();
    [$userId, $balance] = $PDOStatement->fetch(PDO::FETCH_NUM);
    
    // preprocess cart
    $cartItems = [];
    foreach ($cart as $cartItem) {
        $cartItemIndex = array_search($cartItem, array_column($cartItems, 'id'));
        
        if ($cartItemIndex !== false) {
            ++$cartItems[$cartItemIndex]['quantity'];
        }
        else {
            $cartItems[] = [
                'id' => $cartItem,
                'quantity' => 1
            ];
        }
    }

    // get price of items
    $PDOStatement = $db->prepare('
        SELECT price
            FROM items
            WHERE id = :id
    ');
    $price = 0;
    foreach ($cartItems as &$cartItem) {
        $PDOStatement->bindValue(':id', $cartItem['id'], PDO::PARAM_INT);
        $PDOStatement->execute();
        $cartItem['price'] = $PDOStatement->fetchColumn();
        $price += $cartItem['price'] * $cartItem['quantity'];
    }
    
    if ($balance >= $price) {
        try {
            // using transactions
            $db->beginTransaction();

            $PDOStatement = $db->prepare('
                INSERT INTO transactions (userId, amount)
                    VALUES (:userId, :amount)
            ');
            $PDOStatement->bindValue(':userId', $userId, PDO::PARAM_INT);
            $PDOStatement->bindValue(':amount', -$price, PDO::PARAM_INT);
            $PDOStatement->execute();

            $PDOStatement = $db->prepare('
                INSERT INTO orders (userId, transactionId, status)
                    VALUES (:userId, :transactionId, "Waiting")
            ');
            $PDOStatement->bindValue(':userId', $userId, PDO::PARAM_INT);
            $PDOStatement->bindValue(':transactionId', $db->lastInsertId(), PDO::PARAM_INT);
            $PDOStatement->execute();

            $PDOStatement = $db->prepare('
                INSERT INTO orderitems (orderId, itemId, paidAmount, quantity)
                    VALUES (:orderId, :itemId, :paidAmount, :quantity)
            ');
            $PDOStatement->bindValue(':orderId', $db->lastInsertId(), PDO::PARAM_INT);
            
            foreach ($cartItems as $item) {
                $PDOStatement->bindValue(':itemId', $item['id'], PDO::PARAM_INT);
                $PDOStatement->bindValue(':paidAmount', $item['price'], PDO::PARAM_INT);
                $PDOStatement->bindValue(':quantity', $item['quantity'], PDO::PARAM_INT);
                $PDOStatement->execute();
            }
            
            $PDOStatement = $db->prepare('
                INSERT INTO userlogs (text, userId)
                    VALUES (:text, :userId)
            ');
            $PDOStatement->bindValue(':text', 'Ordered '.count($cart).' items worth Rs. '.$price, PDO::PARAM_STR);
            $PDOStatement->bindValue(':userId', $userId, PDO::PARAM_INT);
            $PDOStatement->execute();

            $db->commit();

            return ['balance' => $balance - $price];
        }
        catch (PDOException $e) {
            $db->rollBack();
            http_response_code(500);
            die('Server Error!');
        }
    }
    else {
        http_response_code(400);
        die('Insufficient balance!');
    }

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