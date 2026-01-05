<?php
include "connection.php";

$id = $_GET['id'];
$passenger_name = "";
$flight_id = "";
$tag_number = "";
$weight = "";
$status = "";

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['id'])) {
        header("location: baggage_tracking.php");
        exit;
    }
    $sql = "SELECT * FROM baggage WHERE baggage_id = $id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        header("location: baggage_tracking.php");
        exit;
    }

    $passenger_name = $row['passenger_name'];
    $flight_id = $row['flight_id'];
    $tag_number = $row['tag_number'];
    $weight = $row['weight'];
    $status = $row['status'];
} else {
    $passenger_name = $_POST['passenger_name'];
    $flight_id = $_POST['flight_id'];
    $tag_number = $_POST['tag_number'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];

    $sql = "UPDATE baggage SET passenger_name = '$passenger_name', flight_id = '$flight_id', tag_number = '$tag_number', weight = '$weight', status = '$status' WHERE baggage_id = $id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header("location: baggage_tracking.php");
        exit;
    } else {
        $errorMessage = "Error updating record: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Baggage - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2>Edit Baggage Record</h2>
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
                                <option value="Checked-in" <?php if ($status == 'Checked-in')
                                    echo 'selected'; ?>>
                                    Checked-in</option>
                                <option value="In Transit" <?php if ($status == 'In Transit')
                                    echo 'selected'; ?>>In
                                    Transit</option>
                                <option value="Arrived" <?php if ($status == 'Arrived')
                                    echo 'selected'; ?>>Arrived
                                </option>
                                <option value="Claimed" <?php if ($status == 'Claimed')
                                    echo 'selected'; ?>>Claimed
                                </option>
                                <option value="Lost" <?php if ($status == 'Lost')
                                    echo 'selected'; ?>>Lost</option>
                                <option value="Damaged" <?php if ($status == 'Damaged')
                                    echo 'selected'; ?>>Damaged
                                </option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Baggage</button>
                            <a href="baggage_tracking.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>