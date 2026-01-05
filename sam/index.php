<?php
    include "connection.php";
    
    $que = "SELECT f.*, a.tail_num, 
                   src.name as source_name, 
                   dest.name as dest_name 
            FROM flight f
            LEFT JOIN aircraft a ON f.aircraft_id = a.aircraft_id
            LEFT JOIN airport src ON f.source = src.airport_id
            LEFT JOIN airport dest ON f.destination = dest.airport_id
            ORDER BY f.scheduled_date ASC";
            
    $result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PQLR Airlines | Flight Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        .notification-stack { position: fixed; top: 70px; right: 20px; z-index: 1050; width: 320px; }
        .clickable-notif { cursor: pointer; transition: transform 0.2s; border-left: 5px solid #ffc107 !important; }
        .clickable-notif:hover { transform: scale(1.02); filter: brightness(95%); }
        table { color: #333; }
        .badge { font-weight: 500; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="admin_dashboard.php">PQLR Airlines | Admin Dashboard</a>
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
            <h2 style="margin:0;">Flight Schedule Management</h2>
            <a class="btn btn-primary" href="create.php" style="text-decoration:none;">+ Add New Flight</a>
        </div>

        <table class="table table-hover table-bordered border-primary shadow-sm">
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
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status = trim(strtolower($row['fl_status'])); // trim is used so that unwanted spaces gets removed 
                        $disp_source = $row['source_name'] ?? "Airport #".$row['source'];
                        $disp_dest = $row['dest_name'] ?? "Airport #".$row['destination'];
                        
                        echo "<tr>
                                <td class='fw-bold'>{$row['flight_number']}</td>
                                <td>" . htmlspecialchars($disp_source) . " → " . htmlspecialchars($disp_dest) . "</td>
                                <td>{$row['scheduled_date']}</td>
                                <td>{$row['standard_dep_time']} / {$row['standard_arr_time']}</td>
                                <td>{$row['gate']}</td>
                                <td>" . ($row['tail_num'] ?? '<span class="text-muted">Unassigned</span>') . "</td>
                                <td>";

                        if ($status == 'delayed') {            // NOTIFICATION BAR
                            echo "<span class='badge bg-danger'>Delayed</span>";
                            echo "<script>
                                    window.addEventListener('load', function() {
                                        const box = document.getElementById('notif-container');
                                        box.innerHTML += `
                                            <div class='alert alert-warning alert-dismissible fade show shadow clickable-notif' 
                                                 onclick=\"window.location.href='delay.php?flight_id={$row['flight_id']}'\" 
                                                 role='alert' style='color: #856404; background-color: #fff3cd; border-color: #ffeeba; margin-bottom:10px;'>
                                                <strong>Delay Alert:</strong> {$row['flight_number']}
                                                <br><small>{$disp_source} to {$disp_dest}</small>
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
                    echo "<tr><td colspan='8' class='text-center py-4'>No flights found in database.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>