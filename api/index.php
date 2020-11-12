<?php

echo '1';

require_once __DIR__.'/classes/router.class.php';
require_once __DIR__.'/classes/database.class.php';

// initialize database connection
$db = null;

echo '2';

try {
    $db = new Database();
    echo '3';
}
catch(PDOException $e) {
    echo '4';
    http_response_code(500);
    die('Failed to connect to the database');
}

echo '5';

// declare routes
$router = new Router();

echo '6';

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

$router->get('/orders', function ($params) use ($db) {
    // get user id
    $PDOStatement = $db->prepare('
        SELECT id
            FROM users
            WHERE email = :email
    ');
    $PDOStatement->bindValue(':email', $params['user']['email'], PDO::PARAM_STR);
    $PDOStatement->execute();
    $userId = $PDOStatement->fetchColumn();
    // get orders
    $PDOStatement = $db->prepare('
        SELECT o.id, o.status, ABS(t.amount) AS amount, o.createdAt
            FROM orders o
            INNER JOIN transactions t
                ON o.transactionId = t.id AND o.userId = t.userId
            WHERE o.userId = :userId
            ORDER BY o.createdAt DESC
    ');
    $PDOStatement->bindValue(':userId', $userId, PDO::PARAM_INT);
    $PDOStatement->execute();
    $orders = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

    $PDOStatement = $db->prepare('
        SELECT i.name, oi.itemId
            FROM orderitems oi
            INNER JOIN items i
                ON oi.itemId = i.id
            WHERE oi.orderId = :orderId
    ');
    foreach ($orders as &$order) {
        $PDOStatement->bindValue(':orderId', $order['id'], PDO::PARAM_INT);
        $PDOStatement->execute();
        $order['items'] = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    return $orders;
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

$router->post('/feedback', function ($params) use ($db) {
    // get user id
    $PDOStatement = $db->prepare('
        SELECT id
            FROM users
            WHERE email = :email
    ');
    $PDOStatement->bindValue(':email', $params['user']['email'], PDO::PARAM_STR);
    $PDOStatement->execute();
    $userId = $PDOStatement->fetchColumn();

    // insert feedback
    $reviews = json_decode($params['reviews'], true);
    $PDOStatement = $db->prepare('
        INSERT INTO ratings (userId, itemId, score, review)
            VALUES (:userId, :itemId, :score1, :review1)
            ON DUPLICATE KEY UPDATE score = :score2, review = :review2
    ');
    $PDOStatement->bindValue(':userId', $userId, PDO::PARAM_INT);

    foreach($reviews as $review) {
        $PDOStatement->bindValue(':itemId', $review['itemId'], PDO::PARAM_INT);
        $PDOStatement->bindValue(':score1', $review['score'], PDO::PARAM_INT);
        $PDOStatement->bindValue(':score2', $review['score'], PDO::PARAM_INT);
        $PDOStatement->bindValue(':review1', $review['review'], PDO::PARAM_STR);
        $PDOStatement->bindValue(':review2', $review['review'], PDO::PARAM_STR);
        $PDOStatement->execute();
    }

    $PDOStatement = $db->prepare('
        INSERT INTO userlogs (text, userId)
            VALUES (:text, :userId)
    ');
    $PDOStatement->bindValue(':userId', $userId, PDO::PARAM_INT);
    $PDOStatement->bindValue(':text', 'Posted feedback for '.count($reviews).' items', PDO::PARAM_STR);
    $PDOStatement->execute();

    return true;
});

// run resource
$router->run($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

echo '7';