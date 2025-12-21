<?php

declare(strict_types = 1);


function check_signup_errors(){
  if (isset($_SESSION["error_signup"])){
     $errors = $_SESSION["error_signup"];
     
     echo"<br>";

     foreach($errors as $error){
      
      echo '<p class = "form-error">'.$error.'</p>';
     }

     unset($_SESSION["error_signup"]);
  } elseif (isset($_GET["status"])  && $_GET["status"]==="signup"){
      echo"<br>";
      echo '<p class = "form-success">Signup Success</p>';
     
  }
  
}