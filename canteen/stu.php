<?php

require_once('connection.php');

  $variable=$_POST['fname'];
  // $variable='B';
  $variable.='%';
  $sql = "SELECT * FROM items where name LIKE ?";
  $stmt=$pdo->prepare($sql);
  $stmt->execute([$variable]);

  $data=$stmt->fetchAll();
  $id=array();
  $name=array();
  $price=array();
  $groupId=array();
  $position=array();
  $image=array();

  foreach($data as $content){

    array_push( $id, $content['id']);
  }

  foreach($data as $content){
      array_push($name,$content['name']);
  }

  foreach($data as $content){
    array_push($price,$content['price']);
  }

  foreach($data as $content){
    array_push($image,$content['image']);
  }

  foreach($data as $content){
    array_push($groupId,$content['groupId']);
  }

  foreach($data as $content){
    array_push($position,$content['position']);
  }
//
// var_dump($id);
// echo "  ";
// var_dump($name);
// echo "  ";
// var_dump($ag);
  $tarray=array($id,$name,$price,$image,$groupId,$position);

  echo json_encode($tarray);

  function liveRequest(){

    $sql = "SELECT * FROM orders ";
    $stmt = $pdo->query($sql); //this method is used when there is no parametters..// and this execues the query
    $author = $stmt->fetchAll();

    return $author;
  }































// $result=array();
// $count= $stmt->rowCount();
// for($i=0;$i<$count;$i++){
//   $result[$i]=$data['fname'];
// }
//
//
//
// var_dump($result);
    // foreach($data as $content){
    //   $result= $content['fname']." ";
    // }

  // echo 'var thevar = ' . json_encode($data) . ';';

//     echo json_encode($data);
// return ($data);



//   require_once('connect.php');
//   if(isset($_POST['fname'])){
//     $variable=$_POST['fname'];
//     $variable.='%';
//     $sql = "SELECT * FROM test where fname LIKE ?";
//     $stmt=$pdo->prepare($sql);
//     $stmt->execute([$variable]);
//
//     $data=$stmt->fetchAll();
//
//
//       foreach($data as $content){
//         echo $content['fname'];
//       }
//
//
//     echo 'var thevar = ' . json_encode($data) . ';';
//
// //     echo json_encode($data);
// // return ($data);
//   }
















  // $variable='%';
  // $variable.=$_POST['fname'];
  //
  //
  //   $sql = "SELECT * FROM test where fname LIKE ?";
  //   $stmt=$pdo->prepare($sql);
  //   $stmt->execute([$variable]);
  //
  //
  //   $contents=$stmt->fetchAll();
  //   $data = array();
  //
  //   foreach($contents as $content){
  //     $data=$content['fname'];
  //   }
  //
  //
  //
  //
  //   // $posts =  $stmt->fetchAll();
  //
  //   // // var_dump($posts);
  //   // foreach($posts as $post){
  //   //     echo $post['fname'].' '.$post['lname'].'<br>';
  //   // }
  //
  //
  //
  //
  //   //
  //   //     // POST Data
  //   // $data['name'] = $_POST['firstName'] . " " . $_POST['lastName'];
  //   // $data['email'] = $_POST['email'];
  //   // $data['message'] = $_POST['message'];
  //
  //
  //

?>
