<?php

require_once "database.php";

$payment_id = (int)$_GET['payment_id'];

$sql = "SELECT b.booking_id, b.seat_label, p.amount 
        FROM booking b 
        JOIN payment p ON b.booking_id = p.booking_id 
        WHERE p.payment_id = ? AND p.pay_status = 'Confirmed'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if ($data) {
    echo "<h1>E-TICKET ISSUED</h1>";
    echo "Booking Ref: " . $data['booking_id'] . "<br>";
    echo "Seat: " . $data['seat_label'] . "<br>";
    echo "Payment: $" . $data['amount'] . " (Verified)";
    echo "<p><i>QR Code Generated...</i></p>";
} else {
    echo "Ticket not yet verified.";
}
?>