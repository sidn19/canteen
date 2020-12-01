

<?php

require_once('connection.php');

  $sql1 = "SELECT
  users.name AS person,
  users.email AS UID,
  orders.id AS ID,
  users.email
from orders
INNER JOIN users
    ON users.id=orders.userId
    where
     orders.status='Waiting'";
     
  $stmt = $pdo->query($sql1); //this method is used when there is no parametters..// and this execues the query
  $authors = $stmt->fetchAll();

  $sql2 = "SELECT
  items.name AS food,
  items.image
from items
INNER JOIN orderitems
	ON orderitems.itemId = items.id
INNER JOIN orders
    ON orders.id = orderitems.orderId
    where
     orders.id= ?";
  $stmt = $pdo->prepare($sql2);

  foreach ($authors as &$result) {
    $stmt->execute([$result['ID']]);
    $result['items'] = $stmt->fetchAll();
  }



?>
