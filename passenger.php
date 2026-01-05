<?php
    include "connection.php";
    
    // Use a LEFT JOIN to get Name/Email from 'users' table if the 'passenger' table is empty
    $que = "SELECT 
                p.user_id, 
                COALESCE(p.passenger_name, u.username) AS passenger_name, 
                COALESCE(p.email, u.email) AS email, 
                p.passport_number, 
                p.phone, 
                p.d_o_b, 
                p.status 
            FROM passenger p
            LEFT JOIN users u ON p.user_id = u.id
            ORDER BY p.user_id";
            
    $result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Management | PQLR Air</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="index.php">PQLR Airlines</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php">Flights</a>
            <a class="nav-link" href="aircraft.php">Aircraft Fleet</a>
            <a class="nav-link" href="airport.php">Airports</a>
            <a class="nav-link" href="bookings.php">Bookings</a>
            <a class="nav-link" href="baggage_tracking.php">Baggage</a>
            <a class="nav-link" href="crew_management.php">Crew</a>
            <a class="nav-link active" href="passenger.php">Passengers</a>
            <a class="nav-link" href="security_compliance.php">Security</a>
        </div>
    </nav>

    <div class="container-fluid my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Passenger Records</h2>
            <a class="btn btn-primary" href="add_passenger.php">+ Add New Passenger</a>
        </div>

        <table class="table table-hover table-bordered border-primary">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Passport No.</th>
                    <th>Phone</th>
                    <th>Date of Birth</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $is_blacklisted = (isset($row['status']) && $row['status'] == 'Blacklisted');
                        
                        echo "<tr " . ($is_blacklisted ? "class='table-danger'" : "") . ">
                                <td>{$row['user_id']}</td>
                                <td>{$row['passenger_name']}</td>
                                <td>{$row['email']}</td>
                                <td><code>{$row['passport_number']}</code></td>
                                <td>{$row['phone']}</td>
                                <td>{$row['d_o_b']}</td>
                                <td>";
                        
                        if ($is_blacklisted) {
                            echo "<span class='badge bg-danger'>Blacklisted</span>";
                        } else {
                            echo "<span class='badge bg-success'>Active</span>";
                        }

                        echo "</td>
                                <td>
                                    <div class='btn-group'>
                                        <a class='btn btn-primary btn-sm' href='edit_passenger.php?id={$row['user_id']}'>Edit</a>
                                        <a class='btn btn-danger btn-sm' href='delete_passenger.php?id={$row['user_id']}' onclick=\"return confirm('Delete this passenger record?')\">Delete</a>
                                    </div>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No passengers registered.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>