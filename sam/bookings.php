<?php
include "connection.php";

$message = "";

// --- PART 1: HANDLE NEW BOOKING SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_booking'])) {
    $p_id = $_POST['passenger_id'];
    $f_id = $_POST['flight_id'];
    $t_id = $_POST['template_id'];
    $s_label = $_POST['seat_label'];
    $pr_id = $_POST['price_id'];
    $r_id = $_POST['rule_id'];
    $b_date = date('Y-m-d');
    
    // Payment variables
    $final_price = 500.00; // You should calculate this based on rule_id/price_id
    $default_admin_id = 1;

    // 1. Check if Passenger is Blacklisted
    $check_black = mysqli_query($con, "SELECT status FROM passenger WHERE passenger_id = $p_id");
    $p_data = mysqli_fetch_assoc($check_black);

    if ($p_data['status'] == 'Blacklisted') {
        $message = "<div class='alert alert-danger'>Access Denied: This passenger is blacklisted.</div>";
    } else {
        // 2. Check if the seat is already booked (Confirmed)
        $check_seat = mysqli_query($con, "SELECT * FROM booking WHERE flight_id = $f_id AND template_id = $t_id AND seat_label = '$s_label' AND booking_status = 'Confirmed'");
        
        if (mysqli_num_rows($check_seat) > 0) {
            $message = "<div class='alert alert-warning'>This seat is already officially booked.</div>";
        } else {
            
            // --- START TRANSACTION ---
            mysqli_begin_transaction($con);

            try {
                // 3. Insert Booking
                $sql = "INSERT INTO booking (booking_status, booking_date, template_id, seat_label, flight_id, passenger_id, price_id, rule_id) 
                        VALUES ('Pending', ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "sisiiii", $b_date, $t_id, $s_label, $f_id, $p_id, $pr_id, $r_id);
                mysqli_stmt_execute($stmt);
                
                // Get the ID of the booking we just made
                $new_booking_id = mysqli_insert_id($con);

                // 4. Insert Payment Record (YOUR ADDED CODE)
                $txn_ref = "TXN-2026-" . strtoupper(bin2hex(random_bytes(4)));
                $sql_pay = "INSERT INTO payment (booking_id, amount, pay_status, transaction_ref, admin_id) 
                            VALUES (?, ?, 'In_Review', ?, ?)";
                
                $stmt2 = mysqli_prepare($con, $sql_pay);
                mysqli_stmt_bind_param($stmt2, "idsi", $new_booking_id, $final_price, $txn_ref, $default_admin_id);
                mysqli_stmt_execute($stmt2);

                // If both succeeded, commit to database
                mysqli_commit($con);
                $message = "<div class='alert alert-success'>Booking & Payment record created! Ref: $txn_ref</div>";

            } catch (Exception $e) {
                // If anything fails, undo everything
                mysqli_rollback($con);
                $message = "<div class='alert alert-danger'>Transaction Failed: " . $e->getMessage() . "</div>";
            }
        }
    }
}

// --- PART 2: FETCH DATA FOR VIEWING ---
$bookings = mysqli_query($con, "SELECT b.*, p.passenger_name, f.flight_number 
                                FROM booking b 
                                JOIN passenger p ON b.passenger_id = p.passenger_id 
                                JOIN flight f ON b.flight_id = f.flight_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking System | Dokidoki</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="index.php">Dokidoki Air</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php">Flights</a>
            <a class="nav-link" href="aircraft.php">Aircraft Fleet</a>
            <a class="nav-link" href="airport.php">Airports</a>
            <a class="nav-link active" href="bookings.php">Bookings</a>
            <a class="nav-link" href="baggage_tracking.php">Baggage</a>
            <a class="nav-link" href="crew_management.php">Crew</a>
            <a class="nav-link" href="passenger.php">Passengers</a>
            <a class="nav-link" href="security_compliance.php">Security</a>
        </div>
    </nav>
    <div class="container-fluid my-5">
        <?php echo $message; ?>

        <h2>Create New Booking</h2>
        <form method="POST" class="row g-3 mb-5 border p-3 bg-light">
            <div class="col-md-3">
                <label>Passenger</label>
                <select name="passenger_id" class="form-select" required>
                    <?php 
                    $ps = mysqli_query($con, "SELECT passenger_id, passenger_name FROM passenger");
                    while($p = mysqli_fetch_assoc($ps)) echo "<option value='{$p['passenger_id']}'>{$p['passenger_name']}</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Flight</label>
                <select name="flight_id" class="form-select" required>
                    <?php 
                    $fs = mysqli_query($con, "SELECT flight_id, flight_number FROM flight");
                    while($f = mysqli_fetch_assoc($fs)) echo "<option value='{$f['flight_id']}'>{$f['flight_number']}</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Template ID</label>
                <input type="number" name="template_id" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label>Seat Label</label>
                <input type="text" name="seat_label" class="form-control" placeholder="e.g. 1A" required>
            </div>
            <div class="col-md-2">
                <label>Pricing Rule</label>
                <select name="rule_id" class="form-select">
                    <?php 
                    $rs = mysqli_query($con, "SELECT rule_id FROM pricing_rule");
                    while($r = mysqli_fetch_assoc($rs)) echo "<option value='{$r['rule_id']}'>Rule #{$r['rule_id']}</option>";
                    ?>
                </select>
            </div>
            <input type="hidden" name="price_id" value="1"> <div class="col-12 mt-3">
                <button type="submit" name="add_booking" class="btn btn-primary w-100">Reserve Seat</button>
            </div>
        </form>

        <hr>

        <h2>Booking Records</h2>
        <table class="table table-hover table-bordered border-primary">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Passenger</th>
                    <th>Flight</th>
                    <th>Seat (Temp-Label)</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Admin Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($bookings)): ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['passenger_name']; ?></td>
                    <td><?php echo $row['flight_number']; ?></td>
                    <td>T<?php echo $row['template_id']; ?> - <?php echo $row['seat_label']; ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                    <td>
                        <span class="badge <?php echo ($row['booking_status'] == 'Confirmed') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                            <?php echo $row['booking_status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['booking_status'] == 'Pending'): ?>
                            <a href="confirm_payment.php?id=<?php echo $row['booking_id']; ?>" class="btn btn-sm btn-success">Confirm Payment</a>
                        <?php endif; ?>
                        <a href="cancel_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn btn-sm btn-danger">Cancel</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>