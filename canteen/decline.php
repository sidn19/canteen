<?php
  require_once('connection.php');


          $id=$_POST['id'];

          //for setting status decline
          $sql = "UPDATE orders SET status='Declined' WHERE id=?";
          $stmt= $pdo->prepare($sql);
          $stmt->EXECUTE([$id]);



            echo $id;



?>
