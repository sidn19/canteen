<?php
session_start();
// if(!empty($_SESSION['message'])){
//   echo $_SESSION['message'];
// }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login to Canteen Systems</title>
        <link rel="stylesheet" href="loginstyle.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>

    <body>

        <div class="container">
            <img src="images/epic-cafe.jpg" alt="" class="i">
            <div class="main">

                <div class="card">
                    <img src="images/logo.png" width="100px" alt="" style="float: left;">
                    <h2 style="display: inline-block;">Epic Cafe Login</h2><br>
                    <?php
                    if(!empty($_SESSION['message'])){
                      echo $_SESSION['message'];
                    }
                    ?>
                    <center>
                        <form style="max-width:450px;margin:auto" action="loginCheck.php" method="post">

                            <div class="input-icons">
                                <i class="fa fa-user icon">
                              </i>
                                <input class="input-field" type="text" placeholder="Username">
                            </div>

                            <div class="input-icons">
                                <i class="fa fa-envelope icon">
                              </i>
                                <input class="input-field" type="text" placeholder="Email" name="email">
                            </div>

                            <div class="input-icons">
                                <i class="fa fa-key icon">
                              </i>
                                <input class="input-field" type="password" placeholder="Password" name="password">
                            </div>
                            <button class="button" style="vertical-align:middle"><span>Log In</span></button>
                        </form>
                    </center>
                    <h4>Forgot your password ? Contact Office.</h4>
                    <h4 style="text-align: center;">&copy MES | Pillai College of Engineering</h4>
                </div>
            </div>
        </div>


    </body>

    </html>
