<?php
include "connection.php";

$name = "";
$passport = "";
$reason = "";

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['passenger_name'];
    $passport = $_POST['passport_number'];
    $reason = $_POST['reason'];

    if (empty($name) || empty($passport)) {
        $errorMessage = "Name and Passport Number are required.";
    } else {
        $query = "INSERT INTO blacklist (passenger_name, passport_number, reason) 
                      VALUES ('$name', '$passport', '$reason')";
        $result = mysqli_query($con, $query);

        if ($result) {
            $successMessage = "Individual added to blacklist!";
            header("location: security_compliance.php");
            exit;
        } else {
            $errorMessage = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blacklist Entry - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 border-danger shadow">
                    <h2 class="text-danger">Add to Blacklist</h2>
                    <hr>
                    <?php if (!empty($errorMessage))
                        echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="passenger_name" value="<?php echo $name; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Passport Number</label>
                            <input type="text" class="form-control" name="passport_number"
                                value="<?php echo $passport; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason for Blacklisting</label>
                            <textarea class="form-control" name="reason" rows="3"><?php echo $reason; ?></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">Confirm Blacklist Entry</button>
                            <a href="security_compliance.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>