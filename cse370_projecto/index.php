<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doki</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="reg.php" >
        <u><p>Registration</p></u>
    </a>
    <p>Travel around the world, 
        and feel the sky.</p>
    <p>Book your flight right now</p>    
    <a href="flight_schedule.php" >
        <button><p>Book Flight</p></button>
    </a><br>
    
    <!-- <img src = "Airport_Runway.jpeg" alt="This is nothing" height = 200 width = 518> -->
    <style>
    body{
        text-align: center;
        background-color: rgba(192, 193, 218, 1);
        background-image: url("Airport_\ Runway.jpeg");
        background-repeat: no-repeat;
        background-size: cover;
    /* border-style: solid; */
    }
    button:hover{
        font-size: 1em;
        background-color:aliceblue;
    }
    button:active{
        font-size: 1.1em;
        background-color:rgba(91, 29, 133, 1);
    }
    </style>
</body>
</html>
<?php
    // include("database.php");
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "cse370_pqlr_airway";
    $conn = "";

    try{
        $conn = mysqli_connect($db_server,
                           $db_user,
                           $db_pass,
                           $db_name);        
    }
    catch(mysqli_sql_exception){
        echo "Awwww,, Could not connect with your database <br>";
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($name)){
            echo"enter name";
        }
        elseif(empty($password)){
            echo"enter password";
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (user, password)
                    VALUES ('$name', '$hash')";
            mysqli_query($conn, $sql); 
            echo "You are now regitered successfully!!";       
        }
    }
    // echo "Yahoo did somethingg";
?>