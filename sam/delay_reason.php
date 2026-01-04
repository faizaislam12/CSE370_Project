<?php
    include "connection.php";
    $que = "SELECT * FROM delay_reason";
    $result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokidoki</title>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Flight Delay Reason According to the Reason Codes</h2>
            <div>
                <a class="btn btn-secondary" href="delay.php">← Back to Reports</a>
                <a class="btn btn-primary" href="add_delay_reason.php">Add New Reason</a>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Reason Code</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)){
                            echo "
                            <tr>
                                <td>{$row['reason_code']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['category']}</td>
                                <td>
                                    <a class='btn btn-info btn-sm' href='edit_delay_reason.php?code={$row['reason_code']}'>Edit</a>
                                </td>
                            </tr>";
                        }
                    }
                ?>   
            </tbody>
        </table>
    </div>
</body>