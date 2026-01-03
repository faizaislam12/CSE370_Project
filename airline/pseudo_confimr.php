<?php
require_once "database.php";

/**
 * 1. CAPTURE DATA FROM SEAT MAP
 * These variables come from the dynamic URL generated in your seat map.
 */
$flight_id    = isset($_GET['flight_id']) ? (int)$_GET['flight_id'] : 0;
$seat_label   = $_GET['seat']    ?? '';
$rule_id      = isset($_GET['rule_id']) ? (int)$_GET['rule_id'] : 0;
$final_price  = isset($_GET['price']) ? (float)$_GET['price'] : 0.00;

// Default Admin ID to satisfy Foreign Key Constraint (Ahmad Raza = 1)
$default_admin_id = 1; 

// Simulated Passenger (In production, use $_SESSION['passenger_id'])
$passenger_id = 501; 

/**
 * 2. VALIDATION
 */
if ($flight_id === 0 || empty($seat_label) || $rule_id === 0) {
    die("Error: Missing booking data. Please return to the <a href='seat_map.php'>Seat Map</a>.");
}

/**
 * 3. DATABASE TRANSACTION
 * We use a transaction to ensure both 'booking' and 'paymnt' tables stay in sync.
 */
$conn->begin_transaction();

try {
    // A. INSERT INTO BOOKING TABLE
    // Status is 'Pending' so it appears on the Admin Dashboard for review
    $sql_book = "INSERT INTO booking (flight_id, seat_label, passenger_id, rule_id, pay_status, created_at) 
                 VALUES (?, ?, ?, ?, 'Pending', NOW())";
    
    $stmt1 = $conn->prepare($sql_book);
    $stmt1->bind_param("isii", $flight_id, $seat_label, $passenger_id, $rule_id);
    $stmt1->execute();
    
    // Capture the ID of the booking we just created
    $new_booking_id = $conn->insert_id;

    // B. INSERT INTO PAYMNT TABLE
    // We include admin_id=1 to prevent the #1452 Foreign Key error.
    // pay_status is 'In_Review' to trigger the Admin Dashboard view.
    $sql_pay = "INSERT INTO paymnt (booking_id, amount, pay_status, transaction_ref, admin_id) 
                VALUES (?, ?, 'In_Review', ?, ?)";
    
    $stmt2 = $conn->prepare($sql_pay);
    $txn_ref = "TXN-2026-" . strtoupper(bin2hex(random_bytes(4))); 
    
    $stmt2->bind_param("idsi", $new_booking_id, $final_price, $txn_ref, $default_admin_id);
    $stmt2->execute();

    // C. COMMIT CHANGES
    $conn->commit();

    // SUCCESS UI
    echo "
    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 10px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);'>
        <h2 style='color: #2ecc71;'>Booking Request Received</h2>
        <p style='font-size: 1.1em;'>Seat: <span style='font-weight:bold;'>$seat_label</span></p>
        <p style='font-size: 1.1em;'>Total Amount: <span style='font-weight:bold; color:#2980b9;'>$ " . number_format($final_price, 2) . "</span></p>
        <div style='background: #f9f9f9; padding: 10px; margin: 20px 0; border-radius: 5px; font-family: monospace;'>
            Ref: $txn_ref
        </div>
        <p style='color: #7f8c8d;'>Please wait for Admin verification.</p>
        <br>
        <a href='payment_dashboard.php' style='display:inline-block; padding: 12px 25px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Go to Payment Dashboard</a>
    </div>";

} catch (Exception $e) {
    // If anything fails, rollback to prevent partial data (orphaned bookings)
    $conn->rollback();
    echo "<div style='color:red; padding:20px;'><strong>Booking Failed:</strong> " . $e->getMessage() . "</div>";
    echo "<p>Ensure Admin ID 1 exists in the admin table and Rule ID $rule_id exists in pricing_rule.</p>";
}
?>
