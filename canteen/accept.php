<?php
  require_once('connection.php');


          $id=$_POST['id'];

          //for adding status = Started;
          $sql = "UPDATE orders SET status='Started' WHERE id=?";
          $stmt= $pdo->prepare($sql);
          $stmt->EXECUTE([$id]);



            echo $id;



?>
