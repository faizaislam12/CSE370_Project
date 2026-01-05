<?php


 if($_SERVER['REQUEST_METHOD']=="POST"){
    $username = $_POST["username"];
    $pwd = $_POST["pwd"]; 
    $email = $_POST["email"];
    $pwd_repeat = $_POST["pwd_repeat"];

    try {
       require_once 'dbh.inc.php';
       require_once 'signup_model.inc.php';
       require_once 'signup_control.inc.php';

       //ERROR HANDLERS  -> give all the necessary  inputs please -_-
      $error =[];

      if(is_input_empty($username, $pwd, $email)){
          $error["empty_inputs"]= "Fill in all the feilds!";
      }
      
      if(is_email_invalid($email)){
          $error["invalid_email"]= "invalid email used!";

      }
  
      if(is_username_taken($pdo,  $username)){
          $error["taken_username"]= "This username is already taken!";

      }

      if(is_email_registered( $pdo,  $email)){
          $error["email_used"]= "This email is already registered!";

      }
      if(is_pwdLen_wrong($pwd)){
        $error["pwd_len"] = "Password must be of 8 characters at least";
      }

       if(!is_pwd_char($pwd)){
        $error["pwd_char"] = "Password must contain at least one letter";
      }
       if(!is_pwd_digit($pwd)){
        $error["pwd_digit"] = "Password must contain at least one digit";
      }
      
       if(!is_pwd_confirm($pwd)){
        $error["pwd_confirm"] = "Passwords must match";
      }
      
      require_once 'config_session.inc.php';

      if($error){
         $_SESSION["error_signup"] = $error;
         header("Location: /sam/signup_form.php");
         die();
      }

      
if (str_contains($email, 'admin')) {
    create_user($pdo, $username, $pwd, $email); // Create first
    $userId = $pdo->lastInsertId();             // Get ID second
    create_admin($pdo, $userId);                // Link subclass third
    header("Location: /sam/admin_dashboard.php");
    exit();
} else {
    // This handles both 'users' and 'passenger' tables internally
    create_passenger($pdo, $username, $pwd, $email, null, null);
    
    // Manually set session so they don't have to log in again after signing up
    $result = get_user($pdo, $username);
    $_SESSION['user_id'] = $result['id'];

    if (isset($_SESSION['pending_booking'])) {
    $_SESSION['booking_resumed'] = true;
    header("Location: /sam/all_view_bookings.php?resume=1");
    exit();

    } else {
        header("Location: /sam/passenger_dashboard.php");
        exit();
    }
}
      
      $pdo = null;
      $stmt = null;
      die();
    }
    
     catch (PDOException $e) {
     die("Query failed: ". $e->getMessage());
      
    }
 }
 else{
    header("Location:/signup_form.php");
    die();
 }





