<?php
  require_once('connection.php');


          $id=$_POST['id'];

          //for adding status = Started;
          $sql = "UPDATE orders SET status='Started', startedAt = CURRENT_TIMESTAMP WHERE id=?";
          $stmt= $pdo->prepare($sql);
          $stmt->EXECUTE([$id]);


require __DIR__.'/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__.'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__.'/vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";

$mail->SMTPDebug  = 1;  
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "testcollegecanteen@gmail.com";
$mail->Password   = "rushishirole9@123456789";

$mail->IsHTML(true);
$mail->AddAddress($_POST['email'], $_POST['name']);
$mail->SetFrom("testcollegecanteen@gmail.com", "College Canteen");
$mail->AddReplyTo("testcollegecanteen@gmail.com", "College Canteen");
$mail->Subject = "Your order is accepted!";
$content = "<h1>Thank you for ordering!</h1><p>Food is on the way!</p>";

$mail->MsgHTML($content); 
if(!$mail->Send()) {
    echo 'Error sending email';
} else {
  echo $id;
}


?>
