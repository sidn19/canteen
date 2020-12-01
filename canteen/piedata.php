<?php
//setting header to json
header('Content-Type: application/json');

require_once 'connection.php';

$query = '
  SELECT i.name, oi.quantity
    FROM items i
    INNER JOIN orderitems oi
      ON i.id = oi.itemId
    GROUP BY i.name
';

//execute query
$result = $pdo->query($query);

//loop through the returned data
$data = array();
foreach ($result as $row) {
  $data[] = $row;
}

//now print the data
print json_encode($data);