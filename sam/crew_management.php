<?php
include "connection.php";
$query = "SELECT * FROM crew";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crew Management - PQLR Airline</title>
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
                <a class="nav-link" href="baggage_tracking.php">Baggage</a>
                <a class="nav-link active" href="crew_management.php">Crew</a>
                <a class="nav-link" href="security_compliance.php">Security</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Crew Management</h2>
            <a href="register_crew.php" class="btn btn-primary">Register New Crew</a>
        </div>

        <div class="card p-4">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Experience</th>
                        <th>Salary</th>
                        <th>Nationality</th>
                        <th>License</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $avail_class = $row['availability_status'] ? 'text-success' : 'text-danger';
                            $avail_text = $row['availability_status'] ? 'Available' : 'Busy';
                            echo "
                            <tr>
                                <td>{$row['crew_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['role']}</td>
                                <td>{$row['experience_years']} yrs</td>
                                <td>\${$row['salary']}</td>
                                <td>{$row['nationality']}</td>
                                <td>{$row['license_number']}</td>
                                <td class='{$avail_class}'>{$avail_text}</td>
                                <td>
                                    <a href='edit_crew.php?id={$row['crew_id']}' class='btn btn-outline-secondary btn-sm'>Edit</a>
                                    <a href='crew_assignment.php?crew_id={$row['crew_id']}' class='btn btn-outline-info btn-sm'>Assign</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No crew members found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>