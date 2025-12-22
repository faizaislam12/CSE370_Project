<?php
    include "connection.php";
    $que = "SELECT * FROM flight";
    $result = mysqli_query($con, $que);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokidoki</title>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body>
    <div class = "container my-5">
        <h2>Flight schedule</h2>
        <a class = "btn btn-primary" href = "create.php" role="button">Add New</a> <br>
        <table class = "table">
            <th>
                <tr>
                    <td>fliid</td>
                    <td>flino</td>
                    <td>estimated_dep_time</td>
                    <td>arr_time</td>
                    <td>from</td>
                    <td>to</td>
                    <td>gate</td>
                    <td>seat</td>
                    <td>status</td>
                    <td>current_time</td>
                    <td>Actions</td>
                </tr>
            </th>
            <tbody>
                <?php
                    $con = mysqli_connect('localhost', 'root', '', 'cse370_pqlr_airway');
                    if (!$con) {
                        die("Connection Error!");
                    }
                    $que = "SELECT * FROM flight";
                    $result = mysqli_query($con, $que);
                    while ($row = mysqli_fetch_assoc($result)){
                        echo "
                            <tr>
                            <td>$row[flight_id]</td>
                            <td>$row[flight_number]</td>
                            <td>$row[estimated_dep_time]</td>
                            <td>$row[arr_time]</td>
                            <td>$row[from]</td>
                            <td>$row[to]</td>
                            <td>$row[gate]</td>
                            <td>$row[seat]</td>
                            <td>$row[status]</td>
                            <td>$row[current_time]</td>
                            <td>
                                <a class='btn btn-primary btn-sm' href = 'edit.php?flight_id=$row[flight_id]'>Edit</a>
                                <a class='btn btn-danger btn-sm' href = 'delete.php?flight_id=$row[flight_id]'>Delete</a>
                            </td>
                            ";}
                ?>   
            </tbody>
        </table>
    </div>
</body>
</html>