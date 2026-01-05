<?php
include "connection.php";

$airports_src = mysqli_query($con, "SELECT airport_id, name FROM airport");
$airports_dest = mysqli_query($con, "SELECT airport_id, name FROM airport");
$aircrafts = mysqli_query($con, "SELECT aircraft_id, tail_num FROM aircraft");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn       = $_POST['flight_number'];
    $sdt      = $_POST['standard_dep_time'];
    $sat      = $_POST['standard_arr_time'];
    $src      = $_POST['source'];
    $dest     = $_POST['destination'];
    $gate     = $_POST['gate'];
    $stat     = $_POST['fl_status'];
    $acid     = $_POST['aircraft_id'];
    $sdate    = $_POST['scheduled_date'];
    $sarrdate = $_POST['scheduled_arr_date'];

    $sql = "INSERT INTO flight (flight_number, standard_dep_time, standard_arr_time, source, destination, gate, fl_status, aircraft_id, scheduled_date, scheduled_arr_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssssisss", $fn, $sdt, $sat, $src, $dest, $gate, $stat, $acid, $sdate, $sarrdate);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: index.php?msg=created");
            exit;
        } else {
            $error = "Execution Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Flight | PQLR Airlines</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        input, select {
            background-color: #ffffff !important;
            color: #212529 !important;
            border: 1px solid #ced4da !important;
            margin-bottom: 0px !important;
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            border-radius: .375rem;
        }
        label {
            color: #ffffff !important;
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #444;
            padding: 30px;
            border-radius: 12px;
        }
        h2 { 
            border-bottom: 2px solid #007bff; 
            padding-bottom: 10px; 
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="index.php">PQLR Airlines</a>
        <div class="navbar-nav">
            <a class="nav-link active" href="index.php">Flights</a>
            <a class="nav-link" href="aircraft.php">Aircraft Fleet</a>
            <a class="nav-link" href="airport.php">Airports</a>
            <a class="nav-link" href="bookings.php">Bookings</a>
        </div>
    </nav>

    <div class="container my-5">
        <h2>Schedule New Flight</h2>
        
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form action="" method="POST" class="card shadow-lg">
            <div class="row">
                <div class="col-md-4">
                    <label>Flight Number</label>
                    <input type="text" name="flight_number" placeholder="e.g. DK-701" required>
                </div>
                <div class="col-md-4">
                    <label>Gate</label>
                    <input type="text" name="gate" placeholder="e.g. A1">
                </div>
                <div class="col-md-4">
                    <label>Initial Status</label>
                    <select name="fl_status">
                        <option value="Scheduled">Scheduled</option>
                        <option value="Delayed">Delayed</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label>Departure (Source)</label>
                    <select name="source" required>
                        <option value="">-- Select Source --</option>
                        <?php while($as = mysqli_fetch_assoc($airports_src)) echo "<option value='{$as['airport_id']}'>{$as['name']}</option>"; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Arrival (Destination)</label>
                    <select name="destination" required>
                        <option value="">-- Select Destination --</option>
                        <?php while($ad = mysqli_fetch_assoc($airports_dest)) echo "<option value='{$ad['airport_id']}'>{$ad['name']}</option>"; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label>Departure Date & Time (STD)</label>
                    <input type="date" name="scheduled_date" required>
                    <input type="time" name="standard_dep_time" class="mt-2" required>
                </div>
                <div class="col-md-6">
                    <label>Arrival Date & Time (STA)</label>
                    <input type="date" name="scheduled_arr_date" required>
                    <input type="time" name="standard_arr_time" class="mt-2" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label>Assign Aircraft</label>
                    <select name="aircraft_id" required>
                        <option value="">-- Select Aircraft --</option>
                        <?php while($ac = mysqli_fetch_assoc($aircrafts)) echo "<option value='{$ac['aircraft_id']}'>{$ac['tail_num']}</option>"; ?>
                    </select>
                </div>
            </div>
            
            <div class="mt-5 d-flex justify-content-between">
                <a href="index.php" class="btn btn-outline-light" style="text-decoration: none;">← Back</a>
                <button type="submit" class="btn btn-primary px-5">Create Flight Record</button>
            </div>
        </form>
    </div>
</body>
</html>