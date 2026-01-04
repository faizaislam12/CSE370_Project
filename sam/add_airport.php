<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name      = $_POST['name'];
    $country   = $_POST['country'];
    $city      = $_POST['city'];
    $iata      = strtoupper($_POST['iata']); 

    $sql = "INSERT INTO airport (name, country, city, IATA_Code) VALUES (?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $name, $country, $city, $iata);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: airports.php?status=added");
            exit;
        } else {
            echo "Error: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Airport</title>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-4">
            <h3>Add New Airport</h3>
            <form action="add_airport.php" method="POST">
                <div class="mb-3">
                    <label>Airport Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Heathrow Airport" required>
                </div>
                <div class="mb-3">
                    <label>Country</label>
                    <input type="text" name="country" class="form-control" placeholder="e.g. United Kingdom" required>
                </div>
                <div class="mb-3">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" placeholder="e.g. London" required>
                </div>
                <div class="mb-3">
                    <label>IATA Code (3 Letters)</label>
                    <input type="text" name="iata" class="form-control" placeholder="e.g. LHR" maxlength="3" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Airport</button>
                <a href="airports.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</body>
</html>