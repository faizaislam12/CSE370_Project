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
         header("Location: ../includes/signup_form.php");
         die();
      }

      create_user( $pdo,  $username, $pwd,  $email);

      header("Location: \airline\home.php?status=signup");
      
      $pdo = null;
      $stmt = null;
      die();
    }
    
     catch (PDOException $e) {
     die("Query failed: ". $e->getMessage());
      
    }
 }
 else{
    header("Location:../includes/signup_form.php");
    die();
 }



