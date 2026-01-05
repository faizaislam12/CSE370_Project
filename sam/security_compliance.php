<?php
include "connection.php";

// Fetch Blacklist
$blacklist_res = mysqli_query($con, "SELECT * FROM blacklist");

// Handle ID Verification (Simulation/Input)
$verify_status = "";
if (isset($_POST['verify_id'])) {
    $passport = $_POST['passport_number'];
    $check_blacklist = mysqli_query($con, "SELECT * FROM blacklist WHERE passport_number = '$passport'");
    if (mysqli_num_rows($check_blacklist) > 0) {
        $verify_status = "DANGER: Passenger is on the BLACKLIST!";
    } else {
        $verify_status = "SUCCESS: No security threats found for this ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security & Compliance - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7f6;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .security-card {
            border-left: 5px solid #dc3545;
        }

        .verification-card {
            border-left: 5px solid #0d6efd;
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
                <a class="nav-link" href="crew_management.php">Crew</a>
                <a class="nav-link active" href="security_compliance.php">Security</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Security & Compliance Dashboard</h2>

        <div class="row">
            <!-- ID Verification Section -->
            <div class="col-md-5 mb-4">
                <div class="card p-4 shadow-sm verification-card">
                    <h4>ID Verification</h4>
                    <p class="text-muted">Check passengers or crew against the no-fly list.</p>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Passport Number / ID</label>
                            <input type="text" class="form-control" name="passport_number"
                                placeholder="Enter ID for verification" required>
                        </div>
                        <button type="submit" name="verify_id" class="btn btn-primary w-100">Run Verification</button>
                    </form>
                    <?php if ($verify_status) { ?>
                        <div
                            class="mt-3 alert <?php echo strpos($verify_status, 'DANGER') !== false ? 'alert-danger' : 'alert-success'; ?>">
                            <?php echo $verify_status; ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="card p-4 mt-4 shadow-sm">
                    <h4>Emergency Protocols</h4>
                    <p class="text-muted">Standard procedures for security incidents.</p>
                    <a href="emergency_reports.php" class="btn btn-outline-danger">Report Incident</a>
                </div>
            </div>

            <!-- Blacklist Section -->
            <div class="col-md-7 mb-4">
                <div class="card p-4 shadow-sm security-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Blacklisted Individuals (No-Fly List)</h4>
                        <a href="add_blacklist.php" class="btn btn-danger btn-sm">Add to List</a>
                    </div>
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Passport #</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($blacklist_res && mysqli_num_rows($blacklist_res) > 0) {
                                while ($row = mysqli_fetch_assoc($blacklist_res)) {
                                    echo "
                                    <tr>
                                        <td>{$row['passenger_name']}</td>
                                        <td>{$row['passport_number']}</td>
                                        <td><small>{$row['reason']}</small></td>
                                        <td>
                                            <a href='remove_blacklist.php?id={$row['blacklist_id']}' class='btn btn-outline-secondary btn-sm'>Remove</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No individuals currently on the blacklist.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>