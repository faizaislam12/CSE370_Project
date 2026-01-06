<?php
include "connection.php";

$message = "";

// CAPTURE DATA FROM THE SEAT SELECTION PAGE (URL PARAMETERS)
$f_id        = isset($_GET['flight_id']) ? (int)$_GET['flight_id'] : 0;
$s_label     = $_GET['seat'] ?? '';
$r_id        = isset($_GET['rule_id']) ? (int)$_GET['rule_id'] : 0;
$final_price = isset($_GET['price']) ? (float)$_GET['price'] : 0.00;

// HANDLE NEW BOOKING SUBMISSION 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_booking'])) {
    $p_id = $_POST['user_id'];
    $f_id = $_POST['flight_id']; 
    $s_label = $_POST['seat_label']; 
    $r_id = $_POST['rule_id']; 
    $final_price = $_POST['price']; 
    
    $pr_id = 1; 
    $default_admin_id = 1; 
    $b_date = date('Y-m-d');

    // AUTO-FETCH TEMPLATE_ID based on the flight
    $res_temp = mysqli_query($con, "SELECT a.template_id FROM flight f 
                                    JOIN aircraft a ON f.aircraft_id = a.aircraft_id
                                    WHERE f.flight_id = $f_id");
    $row_temp = mysqli_fetch_assoc($res_temp);
    $t_id = $row_temp['template_id'] ?? null;

    if (!$t_id) {
        $message = "<div class='alert alert-danger'>Error: No aircraft template found for this flight.</div>";
    } else {
        
        $check_black = mysqli_query($con, "SELECT status FROM passenger WHERE user_id = $p_id");
        $p_data = mysqli_fetch_assoc($check_black);

        if ($p_data['status'] == 'Blacklisted') {
            $message = "<div class='alert alert-danger'>Access Denied: This passenger is blacklisted.</div>";
        } else {
            
            mysqli_begin_transaction($con);
            try {
                
                $sql = "INSERT INTO booking (booking_status, booking_date, template_id, seat_label, flight_id, user_id, price_id, rule_id) 
                        VALUES ('Pending', ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "sisiiii", $b_date, $t_id, $s_label, $f_id, $p_id, $pr_id, $r_id);
                mysqli_stmt_execute($stmt);
                
                $new_booking_id = mysqli_insert_id($con);

                
                $txn_ref = "TXN-2026-" . strtoupper(bin2hex(random_bytes(4)));
                $sql_pay = "INSERT INTO payment (booking_id, amount, pay_status, transaction_ref, admin_id) 
                            VALUES (?, ?, 'In_Review', ?, ?)";
                
                $stmt2 = mysqli_prepare($con, $sql_pay);
                mysqli_stmt_bind_param($stmt2, "idsi", $new_booking_id, $final_price, $txn_ref, $default_admin_id);
                mysqli_stmt_execute($stmt2);

                mysqli_commit($con);
                $message = "<div class='alert alert-success'>Booking & Payment record created! Ref: $txn_ref</div>";
            } catch (Exception $e) {
                mysqli_rollback($con);
                $message = "<div class='alert alert-danger'>Transaction Failed: " . $e->getMessage() . "</div>";
            }
        }
    }
}


$bookings = mysqli_query($con, "SELECT b.*, p.passenger_name, f.flight_number 
                                FROM booking b 
                                JOIN passenger p ON b.user_id = p.user_id 
                                JOIN flight f ON b.flight_id = f.flight_id
                                ORDER BY b.booking_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking System | PQLR Airlines</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="index.php">PQLR Airlines</a>
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
        <form method="POST" class="row g-3 mb-5 border p-3 bg-light" style="color: black;">
            
            <input type="hidden" name="flight_id" value="<?php echo $f_id; ?>">
            <input type="hidden" name="seat_label" value="<?php echo htmlspecialchars($s_label); ?>">
            <input type="hidden" name="rule_id" value="<?php echo $r_id; ?>">
            <input type="hidden" name="price" value="<?php echo $final_price; ?>">

            <div class="col-md-4">
                <label>Passenger</label>
                <select name="user_id" class="form-select" required>
                    <?php 
                    $ps = mysqli_query($con, "SELECT user_id, passenger_name FROM passenger");
                    while($p = mysqli_fetch_assoc($ps)) echo "<option value='{$p['user_id']}'>{$p['passenger_name']}</option>";
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>Seat Selected</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($s_label); ?>" disabled>
            </div>

            <div class="col-md-4">
                <label>Total Price</label>
                <input type="text" class="form-control" value="$<?php echo number_format($final_price, 2); ?>" disabled>
            </div>

            <div class="col-12 mt-3">
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
                            <a href="pay_dashboard.php?id=<?php echo $row['booking_id']; ?>" class="btn btn-sm btn-success">Confirm Payment</a>
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
