<?php
session_start();
include "connection.php";



$total_flights = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM flight"))['count'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM booking"))['count'];
$pending_payments = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM payment WHERE pay_status = 'In_Review'"))['count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel | PQLR Airlines</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">Admin Control Center</a>
        <a href="all_view_home.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group shadow-sm">
                    <a href="index.php" class="list-group-item list-group-item-action">Manage Flights</a>
                    <a href="aircraft.php" class="list-group-item list-group-item-action">Aircraft Fleet</a>
                    <a href="bookings.php" class="list-group-item list-group-item-action">All Bookings</a>
                    <a href="passenger.php" class="list-group-item list-group-item-action">Passenger List</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body">
                                <h5>Total Flights</h5>
                                <h2><?php echo $total_flights; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body">
                                <h5>Total Bookings</h5>
                                <h2><?php echo $total_bookings; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark mb-3">
                            <div class="card-body">
                                <h5>Pending Payments</h5>
                                <h2><?php echo $pending_payments; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header">Recent Flights</div>
                    <div class="card-body">
                        <p class="text-muted">Navigate to "Manage Flights" to edit or delete schedules.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>