<?php
include "connection.php";
$query = "SELECT * FROM baggage";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baggage Tracking - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">PQLR Airline</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Flights</a>
                <a class="nav-link active" href="baggage_tracking.php">Baggage</a>
                <a class="nav-link" href="crew_management.php">Crew</a>
                <a class="nav-link" href="security_compliance.php">Security</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Baggage Tracking</h2>
            <a href="add_baggage.php" class="btn btn-primary">Add New Baggage</a>
        </div>

        <div class="card p-4">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Flight ID</th>
                        <th>Passenger</th>
                        <th>Tag Number</th>
                        <th>Status</th>
                        <th>Weight (kg)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $status_class = '';
                            switch ($row['status']) {
                                case 'Lost':
                                    $status_class = 'text-danger fw-bold';
                                    break;
                                case 'Arrived':
                                    $status_class = 'text-success';
                                    break;
                                case 'Damaged':
                                    $status_class = 'text-warning';
                                    break;
                                default:
                                    $status_class = '';
                            }
                            echo "
                            <tr>
                                <td>{$row['baggage_id']}</td>
                                <td>{$row['flight_id']}</td>
                                <td>{$row['passenger_name']}</td>
                                <td>{$row['tag_number']}</td>
                                <td class='{$status_class}'>{$row['status']}</td>
                                <td>{$row['weight']}</td>
                                <td>
                                    <a href='edit_baggage.php?id={$row['baggage_id']}' class='btn btn-outline-secondary btn-sm'>Update</a>
                                    <a href='baggage_claims.php?baggage_id={$row['baggage_id']}' class='btn btn-outline-danger btn-sm'>Claim</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No baggage records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>