<?php
  require_once('connection.php');


          $id=$_POST['id'];

          //for setting status decline
          $sql = "UPDATE orders SET status='Declined' WHERE id=?";
          $stmt= $pdo->prepare($sql);
          $stmt->EXECUTE([$id]);

          // get order total
          $stmt = $pdo->prepare('
            SELECT SUM(paidAmount)
              FROM orderitems
              WHERE orderId = ?
          ');
          $stmt->execute([$id]);
          $amount = $stmt->fetchColumn();

          // get user id
          $stmt = $pdo->prepare('
            SELECT id FROM users
              WHERE email = ?
          ');
          $stmt->execute([$_POST['email']]);
          $userId = $stmt->fetchColumn();

          $stmt = $pdo->prepare('
              INSERT INTO transactions (userId, amount)
                  VALUES (?, ?)
          ');
          $stmt->execute([$userId, $amount]);


            require_once __DIR__.'/vendor/phpmailer/phpmailer/src/Exception.php';
            require_once __DIR__.'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require_once __DIR__.'/vendor/phpmailer/phpmailer/src/SMTP.php';
            
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
            $mail->Subject = "Your order is declined!";
            $content = "<p>Unfortunately, your order was declined. Maybe try ordering another item.</p>";
            
            $mail->MsgHTML($content); 
            if(!$mail->Send()) {
                echo 'Error sending email';
            } else {
              echo $id;
            }

?>
