<?php

require_once 'login_view.inc.php';
require_once 'config_session.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content ="IE-edge" >
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">


</head>

<body>
  <h1>Login</h1>
  <form action="includes/login.inc.php" method = "POST">
    <input type = "text" name = "username" placeholder="Username"><br>
    <input type = "password" name = "pwd" placeholder="Password"><br>
    <button>Login</button>
    <p>Don't have an account?<a href="signup_form.php"> sign up</a></p>
  </form>
   
  <?php
  check_login_errors();
  ?>
  


  <!--<h3>Signup</h3>
  <form action="includes/signup.inc.php" method = "POST">
    <input type = "text" name = "username" placeholder="Username"><br><br>
    <input type = "password" name = "pwd" placeholder="Password"><br><br>
    <input type = "text" name = "email" placeholder="E-mail"><br><br>
    <button>Signup</button>
  </form>
  <?php
   //check_signup_errors();
  ?>*/-->

 <!--<h3>Logout</h3>
  <form action="includes/logout.inc.php" method = "POST">
    
  <button>Logout</button>
  </form>-->

</body>
</html>



