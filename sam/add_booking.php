<?php
include "connection.php";
$flights_res = mysqli_query($con, "SELECT flight_id, flight_number FROM flight");
$passengers_res = mysqli_query($con, "SELECT passenger_id, name FROM passenger");
$pricing_res = mysqli_query($con, "SELECT rule_id, rule_name FROM pricing_rule");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status    = $_POST['booking_status'];
    $date      = $_POST['booking_date'];
    $template  = $_POST['template_id'];
    $seat      = $_POST['seat_label'];
    $flight    = $_POST['flight_id'];
    $passenger = $_POST['passenger_id'];
    $price     = $_POST['price_id'];

    $sql = "INSERT INTO booking (booking_status, booking_date, template_id, seat_label, flight_id, passenger_id, price_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssisiii", $status, $date, $template, $seat, $flight, $passenger, $price);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: bookings.php?msg=success");
            exit;
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PQLR Airlines | New Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-4 mx-auto" style="max-width: 800px;">
            <h3 class="mb-4">Create New Flight Booking</h3>
            <form action="" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Booking Date</label>
                        <input type="date" name="booking_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Initial Status</label>
                        <select name="booking_status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Select Flight</label>
                        <select name="flight_id" class="form-select" required>
                            <option value="">-- Choose Flight --</option>
                            <?php while($f = mysqli_fetch_assoc($flights_res)) echo "<option value='{$f['flight_id']}'>{$f['flight_number']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Select Passenger</label>
                        <select name="passenger_id" class="form-select" required>
                            <option value="">-- Choose Passenger --</option>
                            <?php while($p = mysqli_fetch_assoc($passengers_res)) echo "<option value='{$p['passenger_id']}'>{$p['name']}</option>"; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Seat Template ID</label>
                        <input type="number" name="template_id" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Seat Label</label>
                        <input type="text" name="seat_label" class="form-control" placeholder="e.g., 14C" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pricing Rule</label>
                        <select name="price_id" class="form-select" required>
                            <option value="">-- Select Price --</option>
                            <?php while($pr = mysqli_fetch_assoc($pricing_res)) echo "<option value='{$pr['rule_id']}'>{$pr['rule_name']}</option>"; ?>
                        </select>
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="bookings.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-5">Finalize Booking</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>