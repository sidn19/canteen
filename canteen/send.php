
<?php
require_once ('connection.php');

if((!empty($_POST['fname'])) && (!empty($_POST['gid'])) && (!empty($_POST['pos'])) && (!empty($_POST['price']))){

      try{

        $name=$_POST['fname'];
        $price=$_POST['price'];
        $groupId=$_POST['gid'];
        $position=$_POST['pos'];

        $sql = 'SELECT name from items where name=?';
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$name]);
        $row=$stmt->rowCount();

        if($row >0)
        {

                  try {
                          move_uploaded_file($_FILES["filename"]["tmp_name"], '../api/images/'.basename($_FILES["filename"]["name"]));
                          $imagePath = '/images/'.basename($_FILES["filename"]["name"]);
                          $sql = "UPDATE items SET price=?, groupId=?, position=?, image = ? WHERE name=?";
                          // $sql= 'insert into items(name,price,groupId,position) values(?,?,?,?)';
                          $stmt= $pdo->prepare($sql);
                          $stmt->execute([$price,$groupId,$position,$imagePath,$name]);

                          $_SESSION['alex']='Successfully Updated!';

                          header('Location: canteen.php');

                  } catch (PDOException $e) {
                          $_SESSION['alex']='Cannot UPDATE into database(inside-INSIDE)';

                          header('Location: canteen.php');
                          exit;
                  }



        }else{

                  try {

                      move_uploaded_file($_FILES["filename"]["tmp_name"], '../api/images/'.basename($_FILES["filename"]["name"]));
                      $imagePath = '/images/'.basename($_FILES["filename"]["name"]);
                      $sql= 'insert into items(name,price,groupId,position,image) values(?,?,?,?,?)';
                      $stmt= $pdo->prepare($sql);
                      $stmt->execute([$name,$price,$groupId,$position,$imagePath]);



                    $_SESSION['alex']='Successfully Inserted ';

                    header('Location: canteen.php');

                  } catch (PDOException $e) {
                    $_SESSION['alex']='Cannot INSERT into database(inside-OUT)';

                    header('Location: canteen.php');
                    exit;

                  }
        }






















      //   $sql= 'insert into items(name) values(?)';
      //   $stmt= $pdo->prepare($sql);
      //   $stmt->EXECUTE([$name]);
      //
      //   $sql= 'insert into items(name,price,groupId,position) values(?,?,?,?)';
      //   $stmt= $pdo->prepare($sql);
      //   $stmt->EXECUTE([$name,$price,$groupId,$position]);
      //
      //
      //   $_SESSION['alex']="Inserted New Entry into Menu";
      //
      //   header('Location: canteen.php');
      //
      //
      //
      //
      //
      //
      }catch(PDOException $e){
        $_SESSION['alex']='Cannot insert into database(inside)';

        header('Location: canteen.php');
        exit;
      }




}else{
  $_SESSION['alex']='Modification Unsuccessful';

  header('Location: canteen.php');
}

?>
