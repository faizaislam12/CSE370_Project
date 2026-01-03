<?php
require_once 'signup_view.inc.php';
require_once 'config_session.inc.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Signup</h1>


  <form action="signup.inc.php" method = "POST">
    <input type = "text" name = "username" placeholder="Username"><br>
    <input type = "email" name = "email" placeholder="Email"><br>
    <input type = "password" name = "pwd" placeholder="Password"><br>
    <input type = "password" name = "pwd_repeat" placeholder="Repeat Password"><br>

      
      <button>Sign Up</button>
      <p>Already have an account?<a href="login_form.php"> log in</a></p>
    </form>
</body>
</html>

<?php
   check_signup_errors();
  ?>
