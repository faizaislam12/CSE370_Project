
<?php
    include "connection.php"; 
    $que = "SELECT f.flight_id, f.flight_number, fd.delay_id, fd.start_time, fd.duration, fd.reason_code 
            FROM flight f
            LEFT JOIN delay fd ON f.flight_id = fd.flight_id
            WHERE LOWER(f.fl_status) = 'delayed'";
    $result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Delays - Dokidoki</title>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Delayed Flights Report</h2>
        <a href="index.php" class="btn btn-secondary">← Back to Schedule</a>
    </div>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container my-5">

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Delay ID</th>
                    <th>Flight ID</th>
                    <th>Start Time</th>
                    <th>Duration</th>
                    <th>Reason Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                        <td>{$row['delay_id']}</td>
                                        <td>{$row['flight_id']}</td>
                                        <td>{$row['start_time']}</td>
                                        <td>{$row['duration']}</td>
                                        <td>";
                                
                                // Show a badge if the reason hasn't been set yet
                                if ($row['reason_code'] == 'PENDING') {
                                    echo "<span class='badge bg-warning text-dark'>Action Required</span>";
                                } else {
                                    echo $row['reason_code'];
                                }

                                echo "</td>
                                <td>
                                    <div class='btn-group'>
                                        <a class='btn btn-outline-primary btn-sm' href='edit.php?flight_id={$row['flight_id']}'>Edit Flight</a>
                                        <a class='btn btn-outline-info btn-sm' href='edit_delay_reason.php?code={$row['reason_code']}'>Update Reason</a>
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No delays currently reported.</td></tr>";
                    }
                ?>   
            </tbody>
        </table>
    </div>
</body>
</html>