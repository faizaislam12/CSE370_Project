<?php
session_start();

$flight_id    = $_GET['flight_id'] ?? '';
$seat_label   = $_GET['seat'] ?? '';
$price_id     = $_GET['price_id'] ?? '';
$price        = $_GET['price'] ?? '';
$final_price  = $_GET['final_price'] ?? '';

if (!isset($_SESSION['user_id'])) {
    $redirect_url = "all_view_bookings.php?flight_id=$flight_id&seat=$seat_label&price_id=$price_id&price=$price&final_price=$final_price";
    header("Location: login.inc.php?redirect=" . urlencode($redirect_url));
    exit;
} else {
    
    header("Location: all_view_bookings.php?flight_id=$flight_id&seat=$seat_label&price_id=$price_id&price=$price&final_price=$final_price");
    exit;
}
?>
