<?php
include "connection.php";

$airports = mysqli_query($con, "SELECT airport_id, name FROM airport");
$aircrafts = mysqli_query($con, "SELECT aircraft_id, tail_num FROM aircraft");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn     = $_POST['flight_number'];
    $sdt    = $_POST['standard_dep_time'];
    $sat    = $_POST['standard_arr_time'];
    $src    = $_POST['source'];
    $dest   = $_POST['destination'];
    $gate   = $_POST['gate'];
    $stat   = $_POST['fl_status'];
    $aid    = $_POST['airport_id'];
    $acid   = $_POST['aircraft_id'];
    $sdate  = $_POST['scheduled_date'];

    $sql = "INSERT INTO flight (flight_number, standard_dep_time, standard_arr_time, source, destination, gate, fl_status, airport_id, aircraft_id, scheduled_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssiss", $fn, $sdt, $sat, $src, $dest, $gate, $stat, $aid, $acid, $sdate);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: index.php");
            exit;
        } else {
            $error = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Flight</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container my-5">
        <div class="card shadow p-4 mx-auto" style="max-width: 800px; background: #fff;">
            <h2 class="mb-4">Schedule New Flight</h2>
            
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form action="create.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Flight Number</label>
                        <input type="text" name="flight_number" class="form-control" placeholder="e.g., DK101" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Scheduled Date</label>
                        <input type="date" name="scheduled_date" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Departure Time (STD)</label>
                        <input type="time" name="standard_dep_time" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Arrival Time (STA)</label>
                        <input type="time" name="standard_arr_time" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Source City</label>
                        <input type="text" name="source" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Destination City</label>
                        <input type="text" name="destination" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Gate</label>
                        <input type="text" name="gate" class="form-control" placeholder="e.g., G12">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Airport</label>
                        <select name="airport_id" class="form-select" required>
                            <?php while($a = mysqli_fetch_assoc($airports)) echo "<option value='{$a['airport_id']}'>{$a['name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Aircraft</label>
                        <select name="aircraft_id" class="form-select" required>
                            <?php while($ac = mysqli_fetch_assoc($aircrafts)) echo "<option value='{$ac['aircraft_id']}'>{$ac['tail_num']}</option>"; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Initial Status</label>
                    <select name="fl_status" class="form-select">
                        <option value="scheduled">Scheduled</option>
                        <option value="delayed">Delayed</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-5">Publish Flight</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>