<?php
include "connection.php";
$que = "SELECT t.*, f.flight_number 
        FROM transit t
        LEFT JOIN flight f ON t.flight_id = f.flight_id";
        
$result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transit Management</title>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container my-5">
        <h2>Flight Transit / Legs</h2>
        <a class="btn btn-primary mb-3" href="add_transit.php">Add New Transit Leg</a>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Transit ID</th>
                    <th>Flight Number</th>
                    <th>Leg Number</th>
                    <th>Crew ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['transit_id']}</td>
                            <td>{$row['flight_number']}</td>
                            <td>Leg #{$row['flight_leg']}</td>
                            <td>{$row['flight_crew_id']}</td>
                            <td>
                                <a class='btn btn-danger btn-sm' href='delete_transit.php?id={$row['transit_id']}'>Remove</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>