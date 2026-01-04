<?php
include "connection.php";
$que = "SELECT * FROM airport";
$result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airport List</title>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container my-5">
        <h2>Airport Database</h2>
        <a class="btn btn-primary mb-3" href="add_airport.php">Add New Airport</a>
        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Airport Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>IATA Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['airport_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['country']}</td>
                                <td>{$row['city']}</td>
                                <td><span class='badge bg-secondary'>{$row['IATA_Code']}</span></td>
                                <td>
                                    <a class='btn btn-info btn-sm' href='edit_airport.php?id={$row['airport_id']}'>Edit</a>
                                    <a class='btn btn-danger btn-sm' href='delete_airport.php?id={$row['airport_id']}' onclick='return confirm(\"Delete this airport?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No airports found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>