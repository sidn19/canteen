<?php

  require_once('connection.php');

  if(!empty($_POST['email']) && !empty($_POST['password']))
    {

      $email = $_POST['email'];
      $password = $_POST['password'];

      $sql = 'SELECT password FROM admins where email = ?';
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$email]);
      $row = $stmt->fetch();
      $count = $stmt->rowCount();
      if($count > 0) {
        if (password_verify($password, $row['password'])) {
          $_SESSION['user']= $email;
          header('Location: canteen.php'); 
        }
        else {
          $_SESSION['message']="Email / Password is incorrect";
          header('Location: login.php');
        }
      }else {
        $_SESSION['message']="Email / Password is incorrect";
        header('Location: login.php');
      }

    }

?>
