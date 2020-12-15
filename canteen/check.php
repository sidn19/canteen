<?php

  require_once('connection.php');

 if(isset($_POST['email'])){

    $email = $_POST['email'];
    $pass = $_POST['password']

    $sql = "SELECT * FROM admins where email=? && pass=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email,$pass]);
    // $row = $stmt->fetchAll();
    $count=$stmt->rowCount();
    if($count>0){
      $_SESSION['user']= $name;
      header('Location: main.php');
    }else {
      $_SESSION['message']="No user found in database";
      header('Location: login.php');
    }


 }





?>
