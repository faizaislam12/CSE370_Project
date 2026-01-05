<?php
require_once "database.php";

session_start();


if (isset($_GET['pay_id']) && isset($_SESSION['user_id'])) {
    
    $payment_id = (int)$_GET['pay_id'];
    $admin_id_from_session = (int)$_SESSION['user_id']; 

    // Start Transaction
    $conn->begin_transaction();

    try {
        /**
         * 1. UPDATE PAYMENT TABLE
         * Based on your attributes: admin_id (int) and pay_status (varchar)
         * Note: confirmed_at updates automatically due to ON UPDATE CURRENT_TIMESTAMP
         */
        $sql1 = "UPDATE payment SET pay_status = 'Confirmed', admin_id = ? WHERE payment_id = ?";
        $stmt_pay = $conn->prepare($sql1);
        
        if (!$stmt_pay) {
            throw new Exception("Prepare failed (Payment): " . $conn->error);
        }

        $stmt_pay->bind_param("ii", $admin_id_from_session, $payment_id);
        $stmt_pay->execute();
        
        /**
         * 2. UPDATE BOOKING TABLE
         * Updates the status to 'Confirmed' for the associated booking
         */
        $sql2 = "UPDATE booking SET booking_status = 'Confirmed' 
                 WHERE booking_id = (SELECT booking_id FROM payment WHERE payment_id = ? LIMIT 1)";
        $stmt_book = $conn->prepare($sql2);

        if (!$stmt_book) {
            throw new Exception("Prepare failed (Booking): " . $conn->error);
        }

        $stmt_book->bind_param("i", $payment_id);
        $stmt_book->execute();
        
        // Everything worked!
        $conn->commit();

        // Redirect back
        header("Location: pay_dashboard.php?msg=success");
        exit();

    } catch (Exception $e) {
        // Something went wrong, undo changes
        $conn->rollback();
        echo "<h3>Update Failed</h3>";
        echo "Error Details: " . $e->getMessage();
        echo "<br><a href='pay_dashboard.php'>Back to Dashboard</a>";
        exit();
    }
} else {
    // This runs if pay_id is missing or session is dead
    die("Unauthorized Access. Session Status: " . (isset($_SESSION['user_id']) ? "Active" : "Expired"));
}
?>