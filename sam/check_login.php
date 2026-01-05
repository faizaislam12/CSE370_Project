<?php
session_start(); // MUST be active to check user_id

// 1. Capture data from the incoming request
$flight_id   = $_GET['flight_id'] ?? '';
$seat_label  = $_GET['seat'] ?? '';
$rule_id     = $_GET['rule_id'] ?? '';
$price       = $_GET['price'] ?? ''; // Ensure this matches your incoming URL key

// 2. Build the query string to keep data alive
$query_params = "flight_id=$flight_id&seat=$seat_label&rule_id=$rule_id&price=$price";

if (!isset($_SESSION['user_id'])) {
    // User NOT logged in: Send to login and remember where they wanted to go
    $redirect_url = "all_view_bookings.php?" . $query_params;
    header("Location: login_form.php?redirect=" . urlencode($redirect_url));
    exit;
} else {
    // User IS logged in: Send them to the booking finalization page
    // Note: Ensure this script isn't ALREADY on all_view_bookings.php
    header("Location: all_view_bookings.php?" . $query_params);
    exit;
}
?>