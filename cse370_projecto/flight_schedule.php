<?php 
    $con = mysqli_connect('localhost', 'root', '', 'cse370_pqlr_airway');
    if (!$con) {
        die("Connection Error");
    }
    // require_once('cse370_projecto/database.php');
    $que = "SELECT * FROM flight";
    $result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action = "<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h2>Flight schedule </h2>
        fli_no <input type = "number" name = "flino"><br>
        dep_time <input type = "time" name = "dtime"><br>
        arr_time <input type = "time" name = "atime"> <br>
        gate <input type = "text" name = "gate"> <br>
        seat <input type = "text" name = "seat"> <br>
        status <input type = "text" name = "status"> <br>
        from <input type = "text" name = "from"> <br>
        to <input type = "text" name = "to"> <br>
           <input type = "submit" name = "sb"> <br>
    </form>
    <a href="payment.php" >
        <button>Click to complete payment</button>
    </a><br>
    <table>
        <tr>
            <td>fliid</td>
            <td>flino</td>
            <td>dep_time</td>
            <td>arr_time</td>
            <td>from</td>
            <td>to</td>
            <td>gate</td>
            <td>seat</td>
            <td>status</td>
        </tr>
        <tr>
            <?php
            while ($row = mysqli_fetch_assoc($result))
                {
            ?>
            <td><?php echo $row['flight_id'];?></td>
            <td><?php echo $row['flight_number'];?></td>
            <td><?php echo $row['dep_time'];?></td>
            <td><?php echo $row['arr_time'];?></td>
            <td><?php echo $row['from'];?></td>
            <td><?php echo $row['to'];?></td>
            <td><?php echo $row['gate'];?></td>
            <td><?php echo $row['seat'];?></td>
            <td><?php echo $row['status'];?></td>
        </tr>
        <?php 
        } ?>
    </table>
</body>
</html>
 <?php  
    $con = mysqli_connect('localhost', 'root', '', 'cse370_pqlr_airway');
    if (!$con) {
        die("Connection Error");
    }
    if(isset($_POST['sb']))
        {
        $fli = $_POST['flino'];    
        $dept = $_POST['dtime'];
        $arrt = $_POST['atime'];
        $gate = $_POST['gate'];
        $seat = $_POST['seat'];
        $sta = $_POST['status'];
        $from = $_POST['from'];
        $to = $_POST['to'];

        $query = "INSERT INTO flight (flight_number,dep_time, arr_time,`from`,`to`,gate,seat,`status`)
                  VALUES ( '$fli','$dept', '$arrt','$from','$to','$gate','$seat', '$sta')";
        $execute = mysqli_query($con, $query);   
        
        if($execute) {
        echo "Flight inserted successfully!";
        }
        else {
        echo "Error: " . mysqli_error($con); 
        }
    
    }
    
 ?> 