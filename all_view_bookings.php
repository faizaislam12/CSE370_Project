<?php
session_start();
include "connection.php";

// 1. Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 2. Capture data from URL (GET)
$flight_id   = (int)($_GET['flight_id'] ?? 0);
$seat_label  = $_GET['seat'] ?? null;
$rule_id     = (int)($_GET['rule_id'] ?? 0);
$final_price = (float)($_GET['price'] ?? 0); 

// 3. Prevent page from loading if data is missing from the URL
if ($flight_id === 0 || empty($seat_label) || $rule_id === 0) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>
            <h4>Missing Booking Information</h4>
            <p>Please go back to the <a href='passenger_dashboard.php'>Dashboard</a> and select a flight and seat again.</p>
          </div></div>";
    exit();
}

// 4. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_booking'])) {
    
    // Sanitize user inputs
    $p_name     = mysqli_real_escape_string($con, $_POST['passenger_name']);
    $p_email    = mysqli_real_escape_string($con, $_POST['email']);
    $p_passport = mysqli_real_escape_string($con, $_POST['passport_number']);
    $p_phone    = mysqli_real_escape_string($con, $_POST['phone']);
    $p_dob      = $_POST['d_o_b'];
    
    // Re-capture from POST to ensure data integrity
    $f_id    = (int)$_POST['flight_id'];
    $s_label = mysqli_real_escape_string($con, $_POST['seat_label']);
    $r_id    = (int)$_POST['rule_id']; 
    $f_price = (float)$_POST['price'];

    // Check if passenger is Blacklisted
    $check_status = mysqli_query($con, "SELECT status FROM passenger WHERE user_id = '$user_id'");
    $pass_row = mysqli_fetch_assoc($check_status);
    
    if ($pass_row && $pass_row['status'] === 'Blacklisted') {
        $message = "<div class='alert alert-danger'><strong>Access Denied:</strong> Your account is restricted.</div>";
    } else {
        // Fetch aircraft template_id
        $temp_query = "SELECT a.template_id FROM flight f 
                       JOIN aircraft a ON f.aircraft_id = a.aircraft_id 
                       WHERE f.flight_id = $f_id";
        $temp_res = mysqli_query($con, $temp_query);
        $t_data = mysqli_fetch_assoc($temp_res);
        $t_id = $t_data['template_id'] ?? null;

        if (!$t_id) {
            $message = "<div class='alert alert-danger'>Error: Aircraft configuration not found.</div>";
        } else {
            mysqli_begin_transaction($con);
            try {
                // UPSERT Passenger (Saves the name and email you were missing)
                if ($pass_row) {
                    $up_pass = "UPDATE passenger SET 
                                passenger_name = '$p_name', email = '$p_email', 
                                passport_number = '$p_passport', phone = '$p_phone', d_o_b = '$p_dob' 
                                WHERE user_id = '$user_id'";
                } else {
                    $up_pass = "INSERT INTO passenger (user_id, passenger_name, email, passport_number, phone, d_o_b, status) 
                                VALUES ('$user_id', '$p_name', '$p_email', '$p_passport', '$p_phone', '$p_dob', 'Active')";
                }
                mysqli_query($con, $up_pass);

                // 1. Insert Booking
                $sql_book = "INSERT INTO booking (booking_status, booking_date, template_id, seat_label, flight_id, user_id, rule_id)
                             VALUES ('Pending', CURDATE(), ?, ?, ?, ?, ?)";
                $stmt_b = mysqli_prepare($con, $sql_book);
                mysqli_stmt_bind_param($stmt_b, "isiii", $t_id, $s_label, $f_id, $user_id, $r_id);
                mysqli_stmt_execute($stmt_b);
                
                $new_booking_id = mysqli_insert_id($con);

                // 2. Insert Payment Record (The part that caused your Fatal Error)
                $txn_ref = "TXN-" . strtoupper(bin2hex(random_bytes(4)));
                $default_admin_id = 1; // Ensure this ID exists in your 'admin' table

                $sql_pay = "INSERT INTO payment (booking_id, amount, pay_status, transaction_ref, admin_id) 
                            VALUES (?, ?, 'In_Review', ?, ?)";
                
                $stmt_p = mysqli_prepare($con, $sql_pay);
                // "idsi" = 4 types, so we provide exactly 4 variables
                mysqli_stmt_bind_param($stmt_p, "idsi", $new_booking_id, $f_price, $txn_ref, $default_admin_id);
                
                if (!mysqli_stmt_execute($stmt_p)) {
                    throw new Exception("Payment record failed: " . mysqli_stmt_error($stmt_p));
                }

                mysqli_commit($con);
                $message = "<div class='alert alert-success'>Booking Successful! Reference: <strong>$txn_ref</strong>. <br><a href='passenger_dashboard.php' class='alert-link'>Return to Dashboard</a></div>";
            } catch (Exception $e) {
                mysqli_rollback($con);
                $message = "<div class='alert alert-danger'>Booking Failed: " . $e->getMessage() . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Booking | PQLR Air</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .booking-container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>
<div class="container booking-container">
    <div class="card shadow border-0 p-4">
        <h2 class="text-primary mb-4">Complete Your Booking</h2>
        <hr>

        <?php echo $message; ?>

        <form method="POST">
            <input type="hidden" name="flight_id" value="<?= $flight_id ?>">
            <input type="hidden" name="seat_label" value="<?= htmlspecialchars($seat_label) ?>">
            <input type="hidden" name="rule_id" value="<?= $rule_id ?>">
            <input type="hidden" name="price" value="<?= $final_price ?>">

            <div class="mb-3">
                <label class="form-label fw-bold">Full Name</label>
                <input type="text" name="passenger_name" class="form-control" required placeholder="John Doe">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="john@example.com">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Passport Number</label>
                    <input type="text" name="passport_number" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Date of Birth</label>
                <input type="date" name="d_o_b" class="form-control" required>
            </div>

            <div class="p-3 bg-light rounded mb-4 border">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Selected Seat: <strong><?= htmlspecialchars($seat_label) ?></strong></span>
                    <span class="h4 mb-0 text-success">$<?= number_format($final_price, 2) ?></span>
                </div>
            </div>

            <button type="submit" name="submit_booking" class="btn btn-primary btn-lg w-100">Confirm Reservation</button>
            <a href="passenger_dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Go Back</a>
        </form>
    </div>
</div>
</body>
</html>