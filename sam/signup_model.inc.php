<?php

declare(strict_types = 1);

function get_username(object $pdo ,string $username){
     $query ="SELECT username FROM users WHERE username = :username;";
     $stmt = $pdo->prepare($query);
     $stmt->bindParam(":username",$username);
     $stmt->execute();

     $result = $stmt->fetch(PDO::FETCH_ASSOC);
     return $result; 
}

function create_admin(object $pdo, int $userId) {
    $query = "INSERT INTO admin (user_id) VALUES (:user_id);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":user_id", $userId);
    $stmt->execute();
}

function create_passenger(object $pdo, string $username, string $pwd, string $email, $dob = null, $passport = null) {
    $query1 = "INSERT INTO users (username, pwd, email) VALUES (:username, :pwd, :email);";
    $stmt1 = $pdo->prepare($query1);
    
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    $stmt1->execute([':username' => $username, ':pwd' => $hashedPwd, ':email' => $email]);

    $userId = $pdo->lastInsertId();
        $query2 = "INSERT INTO passenger (user_id, d_o_b, passport_number) VALUES (:user_id, :d_o_b, :passport);";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute([
        ':user_id' => $userId, 
        ':d_o_b' => $dob ?: null, 
        ':passport' => $passport ?: null
    ]);
}

function get_email(object $pdo ,string $email){
     $query ="SELECT username FROM users WHERE email = :email;";
     $stmt = $pdo->prepare($query);
     $stmt->bindParam(":email",$email);
     $stmt->execute();

     $result = $stmt->fetch(PDO::FETCH_ASSOC);
     return $result; 
}

function set_user(object $pdo, string $username,string $pwd, string $email){
     $query ="INSERT INTO users(username, pwd, email) VALUES(:username, :pwd, :email);";
     $stmt = $pdo->prepare($query);

     $options =[
         'cost' => 12
     ];

     $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);
       
     $stmt->bindParam(":username",$username);
     $stmt->bindParam(":pwd",$hashedPwd);
     $stmt->bindParam(":email",$email);

     $stmt->execute();

     
}
