<?php
    include "connection.php";
    $que = "SELECT * FROM flight";
    $result = mysqli_query($con, $que);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Schedule</title>
</head>
<body>
    <a href="airport.php" >
        <button>Airport</button>
    </a><br>
    <a href="aircraft.php" >
        <button>Aircraft</button>
    </a><br>
    <form action = "<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h2>Flight schedule </h2>
        flight_id <input type = "number" name = "flight_id"><br>
        flight_no <input type = "number" name = "flight_no"><br>
        dep_time <input type = "time" name = "dep_time"><br>
        arr_time <input type = "time" name = "arr_time"> <br>
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

    <div class = "container my-4">
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
            <td>Actions</td>
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
            <td>
                    <a href = 'edit.php?=$row[flight_id]'>Edit</a>
                    <a href = 'delete.php?=$row[flight_id]'>Delete</a>
                    
            </td>
        </tr>
        <?php
            } ?>
        </table>
    </div>  
</body>
</html>
 <?php  
    include "connection.php";
    if(isset($_POST['sb']))
        {
        $flight_id = $_POST['flight_id'];
        $flight_no = $_POST['flight_no'];    
        $dep_time = $_POST['dep_time'];
        $arr_time = $_POST['arr_time'];
        $gate = $_POST['gate'];
        $seat = $_POST['seat'];
        $sta = $_POST['status'];
        $from = $_POST['from'];
        $to = $_POST['to'];


        $query = "INSERT INTO flight (flight_id,flight_number,dep_time, arr_time,`from`,`to`,gate,seat,`status`)
                  VALUES ('$flight_id', '$flight_no','$dep_time', '$arr_time','$from','$to','$gate','$seat', '$sta')";
        $execute = mysqli_query($con, $query);  
       
        if($execute) {
        echo "Flight inserted successfully!";
        }
        else {
        echo "Error!";
        }
    }
 ?>
