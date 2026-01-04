<?php
include "connection.php";

$id = isset($_GET['flight_id']) ? $_GET['flight_id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if (!$id) {
    header("location: index.php");
    exit;
}

$stmt = $con->prepare("SELECT * FROM flight WHERE flight_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$airports = mysqli_query($con, "SELECT airport_id, name FROM airport");
$aircrafts = mysqli_query($con, "SELECT aircraft_id, tail_num FROM aircraft");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = $_POST['flight_number'];
    $sdt = $_POST['standard_dep_time'];
    $sat = $_POST['standard_arr_time'];
    $src = $_POST['source'];
    $dest = $_POST['destination'];
    $gate = $_POST['gate'];
    $stat = $_POST['fl_status']; 
    $aid = $_POST['airport_id'];
    $acid = $_POST['aircraft_id'];
    $sdate = $_POST['scheduled_date'];

    $update = $con->prepare("UPDATE flight SET flight_number=?, standard_dep_time=?, standard_arr_time=?, source=?, destination=?, gate=?, fl_status=?, airport_id=?, aircraft_id=?, scheduled_date=? WHERE flight_id=?");
    $update->bind_param("sssssssissi", $fn, $sdt, $sat, $src, $dest, $gate, $stat, $aid, $acid, $sdate, $id);
    
    if ($update->execute()) {
            if ($stat == 'Delayed') {
            // Check if a delay record already exists for this flight to avoid duplicates
            $check_delay = $con->prepare("SELECT * FROM delay WHERE flight_id = ?");
            $check_delay->bind_param("i", $id);
            $check_delay->execute();
            $res = $check_delay->get_result();

            if ($check_res->num_rows == 0) {
                $insert_delay = $con->prepare("INSERT INTO delay (flight_id, start_time, duration, reason_code) VALUES (?, ?, 'TBD', 'PENDING')");
                $insert_delay->bind_param("is", $id, $sdt);
                $insert_delay->execute();
        }
        }
        
        header("location: index.php?msg=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Flight | Dokidoki</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container my-5">
        <h2>Edit Flight: <?php echo htmlspecialchars($row['flight_number']); ?></h2>
        
        <form action="" method="POST" class="card p-4 shadow-sm">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Flight Number</label>
                    <input type="text" name="flight_number" class="form-control" value="<?php echo $row['flight_number']; ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Scheduled Date</label>
                    <input type="date" name="scheduled_date" class="form-control" value="<?php echo $row['scheduled_date']; ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Gate Assignment</label>
                    <input type="text" name="gate" class="form-control" value="<?php echo $row['gate']; ?>" placeholder="e.g. B12">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>STD (Departure Time)</label>
                    <input type="time" name="standard_dep_time" class="form-control" value="<?php echo $row['standard_dep_time']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>STA (Arrival Time)</label>
                    <input type="time" name="standard_arr_time" class="form-control" value="<?php echo $row['standard_arr_time']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Source City</label>
                    <input type="text" name="source" class="form-control" value="<?php echo $row['source']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Destination City</label>
                    <input type="text" name="destination" class="form-control" value="<?php echo $row['destination']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Flight Status</label>
                    <select name="fl_status" class="form-select">
                        <option value="Scheduled" <?php if($row['fl_status'] == 'Scheduled') echo 'selected'; ?>>Scheduled</option>
                        <option value="Delayed" <?php if($row['fl_status'] == 'Delayed') echo 'selected'; ?>>Delayed</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Operating Airport</label>
                    <select name="airport_id" class="form-select">
                        <?php while($a = mysqli_fetch_assoc($airports)): ?>
                            <option value="<?php echo $a['airport_id']; ?>" <?php if($a['airport_id'] == $row['airport_id']) echo 'selected'; ?>><?php echo $a['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Assigned Aircraft</label>
                    <select name="aircraft_id" class="form-select">
                        <?php while($ac = mysqli_fetch_assoc($aircrafts)): ?>
                            <option value="<?php echo $ac['aircraft_id']; ?>" <?php if($ac['aircraft_id'] == $row['aircraft_id']) echo 'selected'; ?>><?php echo $ac['tail_num']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">← Back to List</a>
                <button type="submit" class="btn btn-success px-5">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>