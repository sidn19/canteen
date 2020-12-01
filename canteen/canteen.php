<?php
session_start();
if(!isset($_SESSION['user'])){
  $_SESSION['message']='Not logged in <br> Please Login';

  header('Location: login.php');
}





?>

    <!DOCTYPE html>
    <html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <title>Canteen View</title>
        <link rel="stylesheet" href="teststyle.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->


        <script>
            // starting animation
            setTimeout(function() {
                $('#loading').fadeOut('fast');
            }, 2500); // <-- time in milliseconds
        </script>

        <!-- dropdown code here -->





    </head>

    <body>
        <div id="loading">
            <img id="loading-image" src="images/load.gif" alt="Loading..." />
        </div>
        <div class="container">
            <img src="images/logo.png" alt="" style="width:70px; margin:50px 0 0 50px">

            <input type="text" placeholder="Status:Online" id="search" autocomplete="off" value="<?php if(!empty($_SESSION['alex'])) echo $_SESSION['alex']?>" readonly>

            <div class="logout" >
                <a href="logout.php"><img src="images/logout.svg" width="25px" height="25px" style="float: left;" alt="" ><span>Logout</span></a>
            </div>


            <br>
            <br>
            <div>
                <ul class="menu leftsideMenu">
                    <li class="lo showSingle" id="landingsite" target="1">
                        <div>
                            <img src="images/liveorder.svg" width="25px" height="25px" alt="" style="float: left;padding-right: 10px;"><span>Live Orders</span>
                        </div>
                    </li>
                    <li class="ac showSingle" target="2">
                        <div>
                            <img src="images/addcash.svg" width="25px" height="25px" alt="" style="float: left;padding-right: 10px;"><span>Add Cash</span>
                        </div>
                    </li>
                    <li class="mcm showSingle" target="3">
                        <div>
                            <img src="images/modifymenu.svg" width="25px" height="25px" alt="" style="float: left;padding-right: 10px;"> Modify Menu
                        </div>
                    </li>
                    <li class="m showSingle" target="4">
                        <div>
                            <img src="images/metrics.svg" width="25px" height="25px" alt="" style="float: left;padding-right: 10px;">Metrics
                        </div>
                    </li>


                </ul>
            </div>
            <h4 style="position:absolute;bottom: 0;left: 32px; font-family: 'Roboto', sans-serif;color: white;font-size:14px;">MES &copy PCE</h4>
            <div class="main">

                <div id="div1" class="targetDiv d1 innerBox qwerty e">
                    <div class="canteen card cl" id="live" style="display: inline-block;">
                        <h2>Live Orders</h2>
                        <form action="do.php" method="post">

                          <?php
                                // require_once 'orders.php';
                                // session_start();
                              require_once 'orders.php';
                              foreach ($authors as $author) {
                              echo '<div class="innercard cl" style="display: flex">';
                              echo "<div><img src='/api".$author['items'][0]['image']."' width='100px' height='100px' style='border-radius: 50%; padding-right: 20px;'></div>";
                              echo '<div>
                                <h3 style="display: inline-block;"><b>'.join(', ', array_column($author['items'], 'food')).'</b></h3>
                                <p name="'.$author['person'].'" style="display: inline-block;"> '.$author['person'].'</p>
                              </div>';
                              echo '<div style="display: flex; flex-direction: column; justify-content: space-between"><input type="button" name="'.$author['ID'].'" value="Accept" class="accept">';
                              echo '<input type="button" name="'.$author['ID'].'" value="Decline" class="decline"></div>';
                              echo " </div>";
                              }
                            ?>

                        </form>

                    </div>
                    <div class="canteen card cr" id="ca" style="display: inline-block;">
                        <h2>Call Out</h2>



                          <?php
                            require_once 'call.php';
                            // // $authors = loadAuthors();
                            // $queues=callout();
                        			foreach ($queues as $author) {
                                        echo '<div class="innercard cr" style="display: flex">';
                                        echo "<div><img src='/api".$author['items'][0]['image']."' width='100px' height='100px' style='border-radius: 50%; padding-right: 20px;'></div>";
                                        echo '<div>
                                            <h3 style="display: inline-block;"><b>'.join(', ', array_column($author['items'], 'food')).'</b></h3>
                                            <p name="'.$author['person'].'" style="display: inline-block;"> '.$author['person'].'</p>
                                        </div>';
                                        echo '<div style="display: flex; flex-direction: column; justify-content: flex-end"><input type="button" data-order="'.$author['ID'].'" data-name="'.$author['person'].'" name="'.$author['EMAIL'].'" value="Call Out" style="float: right;" class="callout button"></div>';
                                        echo " </div>";
                                    }
                          ?>
                      </div>
                  </div>

                  <div id="div2" class="targetDiv d2 innerBox qwerty">
                    <div class="Deposite">
                        <h1 style="color:#009688;">Deposite Money to User Account</h1>
                        <div class="cards">
                          <form action="addMoney.php" method="post">
                           <fieldset>
                               <legend>Add Virtual Money</legend>
                               <label for="adno" class="lbl">Email Id:</label>
                               <input type="text" id="adno" name="email" placeholder="Enter Email Id" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"><br><br>
                               <label for="amt" class="lbl">Amount</label>
                               <input type="text" id="amt" name="amount" placeholder="Enter Money to Deposit" required pattern="[0-9]+"><br><br>
                               <button class="button" style="vertical-align:middle"><span>Submit </span></button>
                           </fieldset>
                          </form>
                        </div>

                    </div>
                </div>

                <div id="div3" class="targetDiv d3 innerBox qwerty">


                  <form class="" action="send.php" method="post" enctype="multipart/form-data">
                    <h1 style="color:#009688;">Modify Canteen Menu</h1>
                    <div class="cards">
                      <fieldset>
                          <legend>Modify Menu</legend>

                          <span>Item Name</span><input type="text" list="browsers" id="fn" name="fname" placeholder="Enter the food item" onkeyup="snameret()"><br>
                            <datalist id="browsers">
                                <?php
                                      require_once 'stu.php';
                                      foreach ($data as $dat) {
                                            echo "<option id='".$dat['id']."' value='".$dat['name']."'>".$dat['name']."</option>";
                                    }
                                  ?>
                            </datalist>
                        <span>Category</span><select id="gid" name="gid" required>
                            <?php
                                require_once 'connection.php';
                                $results = $pdo->query('
                                    SELECT id, name
                                        FROM itemgroups
                                ')->fetchAll();
                                
                                // echo dummy
                                echo '<option value="">Select Category</option>';
                                foreach($results as $result) {
                                    echo '<option value="'.$result['id'].'">'.$result['name'].'</option>';
                                }
                            ?>

                        </select>
                            <br>
                          <span>Position</span><input type="text" id="pos" name="pos" placeholder="position" required><br>
                          <span>Enter Price</span><input type="text" id="price" name="price" placeholder="price" required><br>
                          <span>Select Image</span><input type="file" id="image" name="filename" accept="image/gif, image/jpeg, image/png" required><br>


                          <button class="button" name="submit"><span>Submit</span></button>
                      </fieldset>
                    </div>

                  </form>


                </div>

                <div id="div4" class="targetDiv d4 innerBox qwerty" style="overflow: auto; max-height: 75vh;">
                <h2 align="center"  style="color:#009688;">Daily Earnings</h2>
                    <center><div id="chart-container">
                    <canvas id="mycanvas1"></canvas>
                    </div></center>
                    <hr>
                    <h2 align="center" style="color:#009688;">Food Item Sales</h2>
                <center><div id="chart-container">
                <canvas id="mycanvas2"></canvas>
                </div></center>
                
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
                <script src="./app.js"></script>
                
                </div>
            </div>
        </div>

<script type="text/javascript">

//
// setInterval(function(){
// $("#live").load('orders.php');
// $("#ca").load('call.php');
// }, 10000);

var em;
//for accept button press
$('.accept').click(function (e) {
    em = $(this).attr("name");

    console.log(em);
    $.ajax({
        url: 'accept.php',
        type: 'POST',
        data: {"id":em},
        success: function(data) {
              location.reload(true);
        }
    });

});

//for decline button press
$('.decline').click(function (e) {
    em = $(this).attr("name");
    //alert(em);
    console.log(em);
    $.ajax({
        url: 'decline.php',
        type: 'POST',
        data: {"id":em},
        success: function(data) {
          location.reload(true);
        }
    });

});

//for sending Email notification
$('.callout').click(function (e) {
    let em = $(this).attr("name");
    let name = $(this).attr('data-name');
    let orderId = $(this).attr('data-order');


    $.ajax({
        url: 'callout.php',
        type: 'POST',
        data: {"email":em, "name": name, "orderId": orderId},
        success: function(data) {
            location.reload(true);
        }
    });

});


console.log(em);




// $(".accept").click(function(){
//   var title = $( ".accept" ).attr( "name" );
//   alert(title);
// });

  function snameret(){
          let fname = $("#fn").val();
          console.log(fname);
          $.ajax({
                    type:"POST",
                    url:"stu.php",
                    data: 'fname='+fname
                }).done(function(books){
                        // console.log(books);
                        books = JSON.parse(books);
                        console.log(books[0].length);
                        var x=books[0];
                        var y=books[1];
                        var z=books[2];
                        var a=books[3];
                        var b=books[4];
                        var c=books[5];


                        console.log("test 1 = "+x);
                        console.log("test 2 = "+y);
                        console.log("test 3 = "+z);
                        console.log("test 4 = "+a);
                        console.log("test 5 = "+b);
                        console.log("test 6 = "+c);
                        if(books[1].length==1){
                          // item,gid,pos,price,image
                          // $('#fn').val(y);
                          $('#gid').val(b);
                          $('#pos').val(c);
                          $('#price').val(z);
                          $('#image').val(a);

                        }
                        if(books[1].length>1){
                          // item,gid,pos,price,image
                          // $('#fn').val(y);
                          $('#gid').val("");
                          $('#pos').val("");
                          $('#price').val("");
                          $('#image').val("");

                        }
              });


      }
</script>
        <script src="index.js" charset="utf-8"></script>
        <script src="app.js" charset="utf-8"></script>
    </body>

    </html>
