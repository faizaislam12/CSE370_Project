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
            header("location: airport.php?status=added");
            exit;
        } else {
            $error = "Error: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Airport | PQLR Airlines</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        input, select {
            background-color: #ffffff !important;
            color: #212529 !important;
            border: 1px solid #ced4da !important;
            margin-bottom: 0px !important;
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            border-radius: .375rem;
        }
        label {
            color: #ffffff !important;
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        .card {
            background: rgba(255, 255, 255, 0.1); 
            border: 1px solid #444;
            padding: 30px;
            border-radius: 12px;
        }
        h2 { 
            border-bottom: 2px solid #007bff; 
            padding-bottom: 10px; 
            color: white;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2>Add New Airport</h2>
        
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form action="" method="POST" class="card shadow-lg">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label>Airport Name</label>
                    <input type="text" name="name" placeholder="e.g. Heathrow Airport" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Country</label>
                    <input type="text" name="country" placeholder="e.g. United Kingdom" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label>City</label>
                    <input type="text" name="city" placeholder="e.g. London" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>IATA Code (3 Letters)</label>
                    <input type="text" name="iata" placeholder="e.g. LHR" maxlength="3" style="text-transform: uppercase;" required>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-between">
                <a href="airport.php" class="btn btn-outline-light" style="text-decoration: none;">← Back to Airports</a>
                <button type="submit" class="btn btn-primary px-5">Save Airport</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>