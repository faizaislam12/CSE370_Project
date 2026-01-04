<?php
    include "connection.php";
    // We join with aircraft to show the Tail Number and airport to show the name
    $que = "SELECT f.*, a.tail_num, ap.name 
            FROM flight f
            LEFT JOIN aircraft a ON f.aircraft_id = a.aircraft_id
            LEFT JOIN airport ap ON f.airport_id = ap.airport_id
            ORDER BY f.scheduled_date DESC";
    $result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dokidoki | Flight Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        .notification-stack { position: fixed; top: 70px; right: 20px; z-index: 1050; width: 320px; }
        .clickable-notif { cursor: pointer; transition: transform 0.2s; }
        .clickable-notif:hover { transform: scale(1.02); filter: brightness(95%); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="index.php">Dokidoki Air</a>
        <div class="navbar-nav">
            <a class="nav-link active" href="index.php">Flights</a>
            <a class="nav-link" href="aircraft.php">Aircraft Fleet</a>
            <a class="nav-link" href="airport.php">Airports</a>
            <a class="nav-link" href="bookings.php">Bookings</a>
            <a class="nav-link" href="baggage_tracking.php">Baggage</a>
            <a class="nav-link" href="crew_management.php">Crew</a>
            <a class="nav-link" href="passenger.php">Passengers</a>
            <a class="nav-link" href="security_compliance.php">Security</a>
        </div>
    </nav>

    <div class="notification-stack" id="notif-container"></div>

    <div class="container-fluid my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Flight Schedule Management</h2>
            <a class="btn btn-primary" href="create.php">+ Add New Flight</a>
        </div>

        <table class="table table-hover table-bordered border-primary">
            <thead class="table-primary">
                <tr>
                    <th>Flight No.</th>
                    <th>Route (From-To)</th>
                    <th>Date</th>
                    <th>STD/STA</th>
                    <th>Gate</th>
                    <th>Aircraft</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status = strtolower($row['fl_status']);
                        echo "<tr>
                                <td>{$row['flight_number']}</td>
                                <td>{$row['source']} → {$row['destination']}</td>
                                <td>{$row['scheduled_date']}</td>
                                <td>{$row['standard_dep_time']} / {$row['standard_arr_time']}</td>
                                <td>{$row['gate']}</td>
                                <td>" . ($row['tail_num'] ?? 'Unassigned') . "</td>
                                <td>";

                        if ($status == 'delayed') {
                            echo "<span class='badge bg-danger'>Delayed</span>";
                            echo "<script>
                                    window.addEventListener('load', function() {
                                        const box = document.getElementById('notif-container');
                                        box.innerHTML += `
                                            <div class='alert alert-warning alert-dismissible fade show shadow clickable-notif' 
                                                 onclick=\"window.location.href='delay.php?flight_id={$row['flight_id']}'\" 
                                                 role='alert'>
                                                Delay Alert: {$row['flight_number']}
                                                <br><small>Route: {$row['source']} - {$row['destination']}</small>
                                                <button type='button' class='btn-close' data-bs-dismiss='alert' onclick='event.stopPropagation();'></button>
                                            </div>`;
                                    });
                                </script>";
                        } else {
                            echo "<span class='badge bg-success'>Scheduled</span>";
                        }

                        echo "</td>
                                <td>
                                    <div class='btn-group'>
                                        <a class='btn btn-primary btn-sm' href='edit.php?flight_id={$row['flight_id']}'>Edit</a>
                                        <a class='btn btn-danger btn-sm' href='delete.php?flight_id={$row['flight_id']}' onclick=\"return confirm('Delete this flight?')\">Delete</a>
                                    </div>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No flights found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>