<?php

require_once 'login_view.inc.php';
require_once 'config_session.inc.php';
$redirect_to = $_GET['redirect'] ?? '';
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
  <form action="login.inc.php" method = "POST">
    <input type = "text" name = "username" placeholder="Username"><br>
    <input type = "password" name = "pwd" placeholder="Password"><br>
    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($redirect_to) ?>">
    <button>Login</button>
    <p>Don't have an account?<a href="signup_form.php"> sign up</a></p>
  </form>
   
  <?php
  check_login_errors();
  ?>
  

</body>
</html>



