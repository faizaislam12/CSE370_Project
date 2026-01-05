<?php
include "connection.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /sam/all_view_flight.php");
    exit();
}

$current_user_id = $_SESSION['user_id']; 

// Query: Select delayed flights ONLY for this specific user's bookings
$notif_query = "SELECT f.flight_number, src.name as source_name, dest.name as dest_name, 
                       f.standard_dep_time, f.fl_status, b.seat_label
                FROM booking b
                INNER JOIN flight f ON b.flight_id = f.flight_id
                INNER JOIN airport src ON f.source = src.airport_id
                INNER JOIN airport dest ON f.destination = dest.airport_id
                WHERE b.user_id = ? 
                AND LOWER(f.fl_status) = 'delayed'";

$stmt = $con->prepare($notif_query);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$notifications = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Alerts | PQLR Airlines</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f7f9; }
        .alert-custom {
            background: #fff;
            border-left: 5px solid #ffc107;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .flight-badge { background: #e9ecef; color: #495057; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary px-4 shadow">
        <a class="navbar-brand" href="passenger_dashboard.php">PQLR Airlines</a>
        <span class="navbar-text text-white">Logged in as: <?php echo $_SESSION['user_username']; ?></span>
    </nav>

    <div class="container my-5">
        <h2 class="fw-bold mb-4">Travel Alerts for You</h2>
        
        <?php if ($notifications->num_rows > 0): ?>
            <?php while($notif = $notifications->fetch_assoc()): ?>
                <div class="alert alert-custom alert-dismissible fade show mb-3" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-warning"><i class="fas fa-exclamation-triangle fa-2xl"></i></div>
                        <div>
                            <h5 class="alert-heading mb-1 text-dark">Flight Delayed: <?php echo htmlspecialchars($notif['flight_number']); ?></h5>
                            <p class="mb-0 text-muted">
                                Your flight from <strong><?php echo $notif['source_name']; ?></strong> to 
                                <strong><?php echo $notif['dest_name']; ?></strong> is currently delayed.
                            </p>
                            <small class="text-secondary">Your Seat: <?php echo $notif['seat_label']; ?> | Scheduled: <?php echo $notif['standard_dep_time']; ?></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted">All your flights are currently on schedule. Safe travels!</p>
                <a href="passenger_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>