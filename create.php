<?php
    include "connection.php";
    $flight_id = "" ;
    $flight_number = "" ;   
    $estimated_dep_time = ""; 
    $arr_time = "";
    $gate = "";
    $seat = "";
    $status = "";
    $from = "";
    $to = "";
    $current_time = "";

    $errorMessage = "";
    $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $flight_id = $_POST['flight_id'];
        $flight_number = $_POST['flight_number'];   
        $estimated_dep_time = $_POST['estimated_dep_time']; 
        $arr_time = $_POST['arr_time'];
        $gate = $_POST['gate'];
        $seat = $_POST['seat'];
        $status = $_POST['status'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $current_time = $_POST['current_time'];
        

        $query = "INSERT INTO flight (flight_id,flight_number,estimated_dep_time, arr_time,`from`,`to`,gate,seat,`status`, `current_time`)
                  VALUES ('$flight_id', '$flight_number','$estimated_dep_time', '$arr_time','$from','$to','$gate','$seat', '$status', '$current_time')";
        $execute = mysqli_query($con, $query);  
       
        if($execute) {
        echo "Flight inserted successfully!";
        }
        else {
        echo "Error!";
        }
    
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new flight schedule</title>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>New flight schedule</h2>

        <form method = "post">
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Flight_id</label>
                <div class = "col-sm-6">
                    <input type = "number" class = "form-control" name="flight_id" value = "<?php echo $flight_id?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Flight_number</label>
                <div class = "col-sm-6">
                    <input type = "text" class = "form-control" name="flight_number" value = "<?php echo $flight_number?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Estimated_dep_time</label>
                <div class = "col-sm-6">
                    <input type = "time" class = "form-control" name="estimated_dep_time" value = "<?php echo $estimated_dep_time?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Arr_time</label>
                <div class = "col-sm-6">
                    <input type = "time" class = "form-control" name="arr_time" value = "<?php echo $arr_time?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">From</label>
                <div class = "col-sm-6">
                    <input type = "text" class = "form-control" name="from" value = "<?php echo $from?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">To</label>
                <div class = "col-sm-6">
                    <input type = "text" class = "form-control" name="to" value = "<?php echo $to?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Gate</label>
                <div class = "col-sm-6">
                    <input type = "text" class = "form-control" name="gate" value = "<?php echo $gate?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Seat</label>
                <div class = "col-sm-6">
                    <input type = "text" class = "form-control" name="seat" value = "<?php echo $seat?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Status</label>
                <div class = "col-sm-6">
                    <input type = "text" class = "form-control" name="status" value = "<?php echo $status?>">
                </div>
            </div>
            <div class = "row mb-3">
                <label class = "col-sm-3 col-form-label">Current_time</label>
                <div class = "col-sm-6">
                    <input type = "time" class = "form-control" name="current_time" value = "<?php echo $current_time?>">
                </div>
            </div>

            <?php  
            if ( !empty($successMessage)){
                echo"
                <div class = 'row mb-3'>
                    <div class = 'offset-sm-3 col-sm-3 d-grid'>
                        <div class = 'alert alert-success alert-dismissible fade show' role = 'alert'>
                            <strong>$successMessage</strong>
                            <button type = 'button' class = 'btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                </div>    
                ";
            }
            ?>

            <div class = "row mb-3">
                <div class = "offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class = "col-sm-3 d-grid">
                    <a class="btn btn-primary" href="index.php" role="button">Cancel</a>
                </div>
            </div>


        </form>
    </div>
    
</body>
</html>