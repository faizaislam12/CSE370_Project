<?php


 if($_SERVER['REQUEST_METHOD']=="POST"){
    $username = $_POST["username"];
    $pwd = $_POST["pwd"]; 
    $email = $_POST["email"];

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
      if(strlen($_POST['password'])<8){
        die("Password must be at least 8 characters");
      }
      if(!preg_match("/[a-z]/i",$_POST["password"])){
          die("Password must contain at least one letter");
      }
     if(!preg_match("/[0-9]/i",$_POST["password"])){
         die("Password must contain at least one digit");
    }
    if($_POST["password"] !== $_POST["password_confirmation"]){
        die("Passwords must match");
    }
      
      require_once 'config_session.inc.php';

      if($error){
         $_SESSION["error_signup"] = $error;
         header("Location: ../index.php");
         die();
      }

      create_user( $pdo,  $username, $pwd,  $email);

      header("Location: \airline\user_page.php?status=signup");
      
      $pdo = null;
      $stmt = null;
      die();
    }
    
     catch (PDOException $e) {
     die("Query failed: ". $e->getMessage());
      
    }
 }
 else{
    header("Location:../index.php");
    die();
 }



