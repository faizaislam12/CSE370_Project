<?php
session_start();
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 1. Capture data from URL (GET)
$flight_id   = (int)($_GET['flight_id'] ?? 0);
$seat_label  = $_GET['seat'] ?? '';
$rule_id     = (int)($_GET['rule_id'] ?? 0);
$final_price = (float)($_GET['price'] ?? 0); 

// Pre-check for required data
if ($flight_id === 0 || $rule_id === 0 || empty($seat_label)) {
    die("<div class='alert alert-danger'>Error: Flight, seat, or pricing rule missing.</div>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_booking'])) {
    
    // Sanitize Form Inputs
    $p_passport = mysqli_real_escape_string($con, $_POST['passport_number']);
    $p_phone    = mysqli_real_escape_string($con, $_POST['phone']);
    $p_dob      = $_POST['d_o_b'];
    
    // FETCH POST DATA
    $f_id    = (int)$_POST['flight_id'];
    $s_label = mysqli_real_escape_string($con, $_POST['seat_label']);
    $r_id    = (int)$_POST['rule_id']; 
    $price   = (float)$_POST['price'];

    // --- STEP 1: CHECK BLACKLIST & EXISTENCE ---
    $check_pass = mysqli_query($con, "SELECT status FROM passenger WHERE user_id = '$user_id'");
    $pass_row = mysqli_fetch_assoc($check_pass);
    
    if ($pass_row && $pass_row['status'] == 'Blacklisted') {
        $message = "<div class='alert alert-danger'>Booking Failed: Your account is restricted (Blacklisted).</div>";
    } else {
        // --- STEP 2: GET TEMPLATE_ID ---
        $temp_query = "SELECT a.template_id FROM flight f 
                       JOIN aircraft a ON f.aircraft_id = a.aircraft_id 
                       WHERE f.flight_id = $f_id";
        $temp_res = mysqli_query($con, $temp_query);
        $temp_data = mysqli_fetch_assoc($temp_res);
        $t_id = $temp_data['template_id'] ?? null;

        if (!$t_id) {
            $message = "<div class='alert alert-danger'>Error: Aircraft template not found.</div>";
        } else {
            // --- START TRANSACTION ---
            mysqli_begin_transaction($con);
            try {
                // A. UPSERT PASSENGER (Ensures user exists in passenger table)
                if (!$pass_row) {
                    $up_sql = "INSERT INTO passenger (user_id, passport_number, phone, d_o_b, status) 
                               VALUES ('$user_id', '$p_passport', '$p_phone', '$p_dob', 'Active')";
                } else {
                    $up_sql = "UPDATE passenger SET 
                               passport_number = '$p_passport', phone = '$p_phone', d_o_b = '$p_dob' 
                               WHERE user_id = '$user_id'";
                }
                mysqli_query($con, $up_sql);

                // B. INSERT BOOKING
                $sql = "INSERT INTO booking (booking_status, booking_date, template_id, seat_label, flight_id, user_id, rule_id)
                        VALUES ('Pending', CURDATE(), ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "isiii", $t_id, $s_label, $f_id, $user_id, $r_id);
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Booking Execute Error: " . mysqli_stmt_error($stmt));
                }
                $new_booking_id = mysqli_insert_id($con);

                // C. INSERT PAYMENT RECORD
                $txn_ref = "TXN-" . strtoupper(bin2hex(random_bytes(4)));
                $default_admin_id = 1; 
                $sql_pay = "INSERT INTO payment (booking_id, amount, pay_status, transaction_ref, admin_id) 
                            VALUES (?, ?, 'In_Review', ?, ?)";
                $stmt2 = mysqli_prepare($con, $sql_pay);
                mysqli_stmt_bind_param($stmt2, "idsi", $new_booking_id, $price, $txn_ref, $default_admin_id);
                
                if (!mysqli_stmt_execute($stmt2)) {
                    throw new Exception("Payment record failed.");
                }

                mysqli_commit($con);
                $message = "<div class='alert alert-success'>Booking successful! Redirecting...</div>";
                header("refresh:2; url=passenger_dashboard.php");

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
    <title>Confirm Reservation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light py-5">
<div class="container shadow-sm p-4 bg-white rounded" style="max-width: 500px;">
    <h3>Finalize Booking</h3>
    <?php echo $message; ?>

    <form method="POST">
        <input type="hidden" name="flight_id" value="<?php echo (int)$flight_id; ?>">
        <input type="hidden" name="seat_label" value="<?php echo htmlspecialchars($seat_label); ?>">
        <input type="hidden" name="rule_id" value="<?php echo (int)$rule_id; ?>">
        <input type="hidden" name="price" value="<?php echo (float)$final_price; ?>">

        <div class="p-3 bg-light rounded mb-3">
            <small class="text-muted">Seat:</small> <strong><?php echo htmlspecialchars($seat_label); ?></strong><br>
            <small class="text-muted">Total Price:</small> <strong>$<?php echo number_format($final_price, 2); ?></strong>
        </div>

        <div class="mb-3">
            <label class="form-label">Passport Number</label>
            <input type="text" name="passport_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="d_o_b" class="form-control" required>
        </div>

        <button type="submit" name="submit_booking" class="btn btn-primary w-100">Confirm Reservation</button>
        <a href="passenger_dashboard.php" class="btn btn-outline-secondary w-100 mt-2">GO BACK</a>
    </form>
</div>
</body>
</html>