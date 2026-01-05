<?php
ob_start();
session_start();
ob_clean();

include "connection.php";

if (!isset($_SESSION['user_id'])) {
    if (isset($_GET['flight_id'])) {
        $_SESSION['pending_booking'] = [
            'flight_id' => $_GET['flight_id'],
            'seat'      => $_GET['seat'] ?? '',
            'price'     => $_GET['price'] ?? 0,
            'rule_id'   => $_GET['rule_id'] ?? 0
        ];
        $_SESSION['resume_booking'] = true;
    }
    header("Location: /sam/login_form.php");
    exit();
}
$message = "";
$user_id = $_SESSION['user_id'];

if (isset($_GET['flight_id'])) {
    $f_id = (int)$_GET['flight_id'];
    $s_label = $_GET['seat'];
    $final_price = (float)$_GET['price'];
    $r_id = isset($_GET['rule_id']) ? (int)$_GET['rule_id'] : 0;
} elseif (isset($_SESSION['pending_booking'])) {
    $f_id  =        (int)$_SESSION['pending_booking']['flight_id'];
    $s_label =      $_SESSION['pending_booking']['seat'];
    $final_price = (float)$_SESSION['pending_booking']['price'];
    $r_id = isset($_SESSION['pending_booking']['rule_id']) ? (int)$_SESSION['pending_booking']['rule_id'] : 0;
}else {
    header("Location: all_view_home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_booking'])) {
    // Collect Passenger Info
    $p_passport = mysqli_real_escape_string($con, $_POST['passport_number']);
    $p_phone = mysqli_real_escape_string($con, $_POST['phone']);
    $p_dob = $_POST['d_o_b'];

    // Collect Booking Info from hidden inputs
    $f_id = (int)$_POST['flight_id'];
    $s_label = mysqli_real_escape_string($con, $_POST['seat_label']);
    $r_id = (int)$_POST['rule_id'];
    $final_price = (float)$_POST['price'];

    // 2. FETCH TEMPLATE_ID based on the flight
    $res_temp = mysqli_query($con, "SELECT a.template_id FROM flight f 
                                    JOIN aircraft a ON f.aircraft_id = a.aircraft_id
                                    WHERE f.flight_id = $f_id");
    $row_temp = mysqli_fetch_assoc($res_temp);
    $t_id = $row_temp['template_id'] ?? null;

    if (!$t_id) {
        $message = "<div class='alert alert-danger'>Error: No aircraft assigned to this flight yet.</div>";
    } else {
            $upd_p = "UPDATE passenger SET
                      passport_number = '$p_passport', 
                      phone = '$p_phone', 
                      d_o_b = '$p_dob', 
                      status = 'Active' 
                      WHERE user_id = '$user_id'";

            mysqli_query($con, $upd_p);


        $check_status = mysqli_query($con, "SELECT status FROM passenger WHERE user_id = '$user_id'");
        $status_data = mysqli_fetch_assoc($check_status);
        $p_status = $status_data['status'] ?? 'Active';
        if ($p_status == 'Blacklisted') {
            $message = "<div class='alert alert-danger'>Booking Failed: Your account is currently restricted.</div>";
        } else {
          
            mysqli_begin_transaction($con);
            try {
                $b_date = date('Y-m-d');
                $pr_id = 7; 
                $default_admin_id = 1;

                // Insert Booking
                $sql = "INSERT INTO booking (booking_status, booking_date, template_id, seat_label, flight_id, user_id, price_id, rule_id) 
                        VALUES ('Pending', ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "sisiiii", $b_date, $t_id, $s_label, $f_id, $user_id, $pr_id, $r_id);
                mysqli_stmt_execute($stmt);
                
                $new_booking_id = mysqli_insert_id($con);

                // Insert Payment Record
                $txn_ref = "TXN-" . strtoupper(bin2hex(random_bytes(4)));
                $sql_pay = "INSERT INTO payment (booking_id, amount, pay_status, transaction_ref, admin_id) 
                            VALUES (?, ?, 'In_Review', ?, ?)";
                $stmt2 = mysqli_prepare($con, $sql_pay);
                mysqli_stmt_bind_param($stmt2, "idsi", $new_booking_id, $final_price, $txn_ref, $default_admin_id);
                mysqli_stmt_execute($stmt2);

                mysqli_commit($con);
                unset($_SESSION['pending_booking']); 
                
                $message = "<div class='alert alert-success'>Success! Your seat $s_label is reserved. Reference: $txn_ref. Please proceed to payment.</div>";
            } catch (Exception $e) {
                mysqli_rollback($con);
                $message = "<div class='alert alert-danger'>Transaction Error: " . $e->getMessage() . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Booking | PQLR Airlines</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #6b869dff; }
        .booking-card { max-width: 800px; margin: 50px auto; border-radius: 15px; overflow: hidden; }
        .flight-info-header { background: #3f577bff; color: white; padding: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card booking-card shadow">
        <div class="flight-info-header">
            <h3>Complete Your Booking</h3>
            <p class="mb-0">Seat: <strong><?php echo htmlspecialchars($s_label); ?></strong> | Total: <strong>$<?php echo number_format($final_price, 2); ?></strong></p>
        </div>
        
        <div class="card-body p-4">
            <?php echo $message; ?>

            <form method="POST">
                <input type="hidden" name="flight_id" value="<?php echo $f_id; ?>">
                <input type="hidden" name="seat_label" value="<?php echo htmlspecialchars($s_label); ?>">
                <input type="hidden" name="rule_id" value="<?php echo $r_id; ?>">
                <input type="hidden" name="price" value="<?php echo $final_price; ?>">

                <h5 class="mb-3">Passenger Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="passenger_name" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Passport Number</label>
                        <input type="text" name="passport_number" class="form-control" placeholder="A1234567" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="+123456789" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="d_o_b" class="form-control" required>
                    </div>
                </div>

                <div class="alert alert-info small mt-3">
                    By clicking "Confirm Reservation", you agree to PQLR Airlines' terms of service and flight regulations.
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="submit_booking" class="btn btn-primary btn-lg">Confirm Reservation</button>
                    <a href="all_view_home.php" class="btn btn-link text-muted">Cancel and go back</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>

</html>





