<?php
include "connection.php";
$que = "SELECT * FROM aircraft";
$result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fleet Inventory | Dokidoki</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="index.php">Dokidoki Air</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php">Flights</a>
            <a class="nav-link active" href="aircraft.php">Aircraft Fleet</a>
            <a class="nav-link" href="airport.php">Airports</a>
            <a class="nav-link" href="bookings.php">Bookings</a>
            <a class="nav-link" href="baggage_tracking.php">Baggage</a>
            <a class="nav-link active" href="crew_management.php">Crew</a>
            <a class="nav-link" href="passenger.php">Passengers</a>
            <a class="nav-link" href="security_compliance.php">Security</a>
        </div>
    </nav>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Aircraft Fleet</h2>
            <a href="add_aircraft.php" class="btn btn-primary">Register New Aircraft</a>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Aircraft ID</th>
                    <th>Tail Number</th>
                    <th>Model</th>
                    <th>Template ID</th>
                    <th>Last Update</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['aircraft_id']}</td>
                            <td>{$row['tail_num']}</td>
                            <td>{$row['model']}</td>
                            <td><span class='badge bg-info text-dark'>T-{$row['template_id']}</span></td>
                            <td>{$row['last_update']}</td>
                            <td>
                                <div class='btn-group'>
                                    <a href='edit_aircraft.php?id={$row['aircraft_id']}' class='btn btn-sm btn-outline-primary'>Edit</a>
                                    <a href='delete_aircraft.php?id={$row['aircraft_id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Delete this aircraft?\")'>Delete</a>
                                </div>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No aircraft registered.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>