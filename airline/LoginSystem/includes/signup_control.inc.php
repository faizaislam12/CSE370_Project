<?php

declare(strict_types = 1);

function is_input_empty(string $username, string $pwd, string $email){
  if(empty($username) || empty($pwd) || empty($email)){
    return true;
  }
  else{
    return false;
  }
}

function is_email_invalid(string $email){
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    return true;
  }
  else{
    return false;
  }
}

function is_username_taken(object $pdo, string $username){
  if(get_username($pdo, $username)){
    return true;
  }
  else{
    return false;
  }
}

function is_email_registered(object $pdo, string $email){
  if(get_email( $pdo , $email)){
    return true;
  }
  else{
    return false;
  }
}


function is_pwdLen_wrong(string $pwd){
   if(strlen($pwd)<8){
    return true;
   }else{
    return false;
   }
}

function is_pwd_char($pwd){
    if(preg_match("/[a-z]/i",$pwd)){
      return True;
    } else{
      return false;
    }
}

function is_pwd_digit($pwd){
   if(preg_match("/[0-9]/i",$pwd)){
     return true;
   } else{
    return false;
   }
}

function is_pwd_confirm($pwd){
  if($pwd== $_POST["pwd_repeat"]){
    return true;
  } else{
    return false;
  }
}

function create_user(object $pdo, string $username,string $pwd, string $email){
  
  set_user($pdo, $username, $pwd, $email);
}
