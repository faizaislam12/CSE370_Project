<?php
include "connection.php";

$passenger_name = "";
$flight_id = "";
$tag_number = "";
$weight = "";
$status = "Checked-in";

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $passenger_name = $_POST['passenger_name'];
    $flight_id = $_POST['flight_id'];
    $tag_number = $_POST['tag_number'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];

    if (empty($passenger_name) || empty($tag_number)) {
        $errorMessage = "Passenger name and Tag number are required.";
    } else {
        $query = "INSERT INTO baggage (passenger_name, flight_id, tag_number, weight, status) 
                      VALUES ('$passenger_name', '$flight_id', '$tag_number', '$weight', '$status')";
        $result = mysqli_query($con, $query);

        if ($result) {
            $successMessage = "Baggage added successfully!";
            header("location: baggage_tracking.php");
            exit;
        } else {
            $errorMessage = "Error adding baggage: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Baggage - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2>Add New Baggage</h2>
                    <hr>
                    <?php if (!empty($errorMessage))
                        echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Passenger Name</label>
                            <input type="text" class="form-control" name="passenger_name"
                                value="<?php echo $passenger_name; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Flight ID</label>
                            <input type="number" class="form-control" name="flight_id"
                                value="<?php echo $flight_id; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tag Number</label>
                            <input type="text" class="form-control" name="tag_number"
                                value="<?php echo $tag_number; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control" name="weight"
                                value="<?php echo $weight; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="Checked-in">Checked-in</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Arrived">Arrived</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Save Baggage</button>
                            <a href="baggage_tracking.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>