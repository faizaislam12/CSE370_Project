<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['passenger_name'];
    $email = $_POST['email'];
    $passport = $_POST['passport_number'];
    $phone = $_POST['phone'];
    $dob = $_POST['d_o_b'];
    $status = $_POST['status'] ?? 'Active'; 

    $sql = "INSERT INTO passenger (passenger_name, email, passport_number, phone, d_o_b, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $passport, $phone, $dob, $status);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: passenger.php");
            exit;
        } else {
            $error = "Execution Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Passenger</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container my-5">
        <div class="card shadow p-4 mx-auto" style="max-width: 700px; background: #fff;">
            <h2 class="mb-4">Register New Passenger</h2>
            
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="passenger_name" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Passport Number</label>
                        <input type="text" name="passport_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date of Birth</label>
                        <input type="date" name="d_o_b" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Passenger Status</label>
                    <select name="status" class="form-select">
                        <option value="Active">Active</option>
                        <option value="Blacklisted">Blacklisted</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="passenger.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-5">Register Passenger</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>