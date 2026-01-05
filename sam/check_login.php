<?php
session_start();

$flight_id    = $_GET['flight_id'] ?? '';
$seat_label   = $_GET['seat'] ?? '';
$rule_id     = $_GET['rule_id'] ?? '';
$price        = $_GET['final_price'] ?? '';

if (!isset($_SESSION['user_id'])) {
    $redirect_url = "all_view_bookings.php?flight_id=$flight_id&seat=$seat_label&rule_id=$rule_id&price=$price";
    header("Location: login.inc.php?redirect=" . urlencode($redirect_url));
    exit;
} else {
    
    header("Location: all_view_bookings.php?flight_id=$flight_id&seat=$seat_label&rule_id=$rule_id&price=$price");
    exit;
}
?>