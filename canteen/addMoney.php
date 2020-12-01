

<?php

require_once('connection.php');

$email = $_POST['email'];
$amt= $_POST['amount'];
// $email = 'alex@gmail.com';
// $amt= 000000;
try{

  $sql = 'SELECT id from users where email= ?';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $idd=$stmt->fetch();
  $id=$idd['id'];
  $count= $stmt->rowCount();
  if($count==0){
    $_SESSION['alex']="No user found in database";
    header('Location: canteen.php');

  }
  else{

    $sql = "INSERT INTO transactions (userId,amount) VALUES(?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id,$amt]);
    $_SESSION['alex']="Transaction Successful!";
    header('Location: canteen.php');



  }






}catch(PDOException $e){

  echo "Undefined Error occured!!!";
  echo "Please contact Alex Vettithanam (2018PE0253)";

}



?>
