<?php

  require_once('connection.php');

  $sql1 = "SELECT
  users.name AS person,
  users.email AS UID,
  orders.id AS ID,
  users.email AS EMAIL
from orders
INNER JOIN users
    ON users.id=orders.userId
    where
     orders.status='Started'";
     
  $stmt = $pdo->query($sql1); //this method is used when there is no parametters..// and this execues the query
  $queues = $stmt->fetchAll();

  $sql2 = "SELECT
  items.name AS food,
  items.image,
  orderitems.quantity
from items
INNER JOIN orderitems
	ON orderitems.itemId = items.id
INNER JOIN orders
    ON orders.id = orderitems.orderId
    where
     orders.id= ?";
  $stmt = $pdo->prepare($sql2);

  foreach ($queues as &$result) {
    $stmt->execute([$result['ID']]);
    $result['items'] = $stmt->fetchAll();
  }

  // var_dump($queues);







  //
  // $sql = "SELECT
  //   	items.name AS,
  //       users.name ,
  //       users.email
  //
  //   from orders
  //   	INNER JOIN users
  //       	ON users.id=orders.userId
  //       INNER JOIN items
  //       	ON items.id=orders.userId,
  //    where
  //     orders.status='Started'";
  // $stmt = $pdo->query($sql); //this method is used when there is no parametters..// and this execues the query
  // $queues = $stmt->fetchAll();
  // $id=$queues[''];
  //
  //
  // var_dump($queues);


?>
