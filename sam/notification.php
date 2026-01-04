<?php
include "connection.php";
session_start();

// Ensure the passenger is logged in
if (!isset($_SESSION['passenger_id'])) {
    // For testing, we use 1, but in production, redirect to login
    $current_passenger_id = 1; 
} else {
    $current_passenger_id = $_SESSION['passenger_id'];
}

// Optimized Query: Using TRIM to handle accidental spaces and Explicit Joins
$notif_query = "SELECT f.flight_number, f.source, f.destination, f.standard_dep_time 
                FROM booking b
                INNER JOIN flight f ON b.flight_id = f.flight_id
                WHERE b.passenger_id = ? 
                AND (TRIM(f.fl_status) = 'Delayed' OR TRIM(f.fl_status) = 'delayed')
                AND b.booking_status = 'Confirmed'";

$stmt = $con->prepare($notif_query);
$stmt->bind_param("i", $current_passenger_id);
$stmt->execute();
$notifications = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Alerts | Dokidoki Air</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <div id="passenger-alerts">
            <?php if ($notifications->num_rows > 0): ?>
                <?php while($notif = $notifications->fetch_assoc()): ?>
                    <div class="alert alert-warning alert-dismissible fade show shadow border-danger" role="alert">
                         FLIGHT DELAY NOTICE:
                        Flight <b><?php echo htmlspecialchars($notif['flight_number']); ?></b> 
                        (<?php echo htmlspecialchars($notif['source']); ?> to <?php echo htmlspecialchars($notif['destination']); ?>) 
                        is currently delayed.
                        <br>
                        <small>Scheduled Departure was: <?php echo htmlspecialchars($notif['standard_dep_time']); ?></small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-muted">No active delay notifications for your confirmed trips.</div>
            <?php endif; ?>
        </div>

        <h2 class="mt-4">Your Bookings</h2>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>