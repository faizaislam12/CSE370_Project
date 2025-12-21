<?php
require_once 'signup_view.inc.php';
require_once 'config_session.inc.php';



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

    <form action = "signup.inc.php" method = "POST" novalidate>
      <div>
        <label for="username">Username:</label>
        <input type = "text" id ="name" name = "username">
      </div>
        
      <div>
        <label for="email">Email:</label>
        <input type = "email" id ="email" name = "email">
      </div>

      <div>
        <label for="password">Password:</label>
        <input type = "password" id ="password" name = "password">
      </div>

      <div>
        <label for="password_confirmation">Repeat Password:</label>
        <input type = "password" id ="password_confirmation" name = "password_confirmation">
      </div>
      
      <button>Sign Up</button>
      <p>Already have an account?<a href="login_form.php"> log in</a></p>
    </form>
</body>
</html>


   check_signup_errors();
  ?>

