<?php

if ($_SERVER["REQUEST_METHOD"]==="POST"){
    $username = $_POST["username"];
    $pwd = $_POST["pwd"]; 
   


    try {
      require_once 'dbh.inc.php';
      require_once 'login_model.inc.php';
      require_once 'login_control.inc.php';

       //ERROR HANDLERS  -> give all the necessary  inputs please -_-
      $error =[];

      if(is_input_empty($username, $pwd)){
          $error["empty_inputs"]= "Fill in all the feilds!";
      }
      
      $result = get_user($pdo, $username);
      
      if(is_username_wrong($result)){
        $error['login_incorrect'] = "Incorrect login info!";
      }
      
      if (!is_username_wrong($result) && is_password_wrong($pwd, $result['pwd'])){
        $error['login_incorrect'] = "Incorrect login info!";
      }

      require_once 'config_session.inc.php';

      if($error){
         $_SESSION["error_login"] = $error;
         header("Location: ../index.php");
         die();
      }

      $newSessionId = session_create_id();
      $sessionId = $newSessionId.'_'.$result['id'];
      session_id($sessionId);
      
      $_SESSION['user_username']= htmlspecialchars($result['username']);
      $_SESSION['user_id']= $result['id'];
      $_SESSION["last_regeneration"] = time();

      header("Location:\airline\user_page.php?login=success");
      $pdo = null;
      $stmt = null;
      die();
 
    } catch (PDOException $e) {
      die("query failed".$e->getMessage());
    }
  }


else{
    header("Location: /LoginSystem/index.php");
    die();
  }
?>