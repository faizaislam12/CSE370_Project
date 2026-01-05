<?php
include "connection.php";
$message = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM airport WHERE airport_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $airport = mysqli_fetch_assoc($result);
    
    if (!$airport) {
        die("Airport not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id      = $_POST['airport_id'];
    $name    = $_POST['name'];
    $country = $_POST['country'];
    $city    = $_POST['city'];
    $iata    = strtoupper($_POST['iata']);

    $update_sql = "UPDATE airport SET name = ?, country = ?, city = ?, IATA_Code = ? WHERE airport_id = ?";
    
    if ($update_stmt = mysqli_prepare($con, $update_sql)) {

        mysqli_stmt_bind_param($update_stmt, "ssssi", $name, $country, $city, $iata, $id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            header("location: airport.php?status=updated");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Update failed: " . mysqli_error($con) . "</div>";
        }
        mysqli_stmt_close($update_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Airport</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-4">
            <h3 class="mb-4">Edit Airport Details</h3>
            
            <?php echo $message; ?>

            <form action="edit_airport.php" method="POST">
                <input type="hidden" name="airport_id" value="<?php echo $airport['airport_id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Airport Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $airport['name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?php echo $airport['country']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?php echo $airport['city']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">IATA Code</label>
                    <input type="text" name="iata" class="form-control" maxlength="3" value="<?php echo $airport['IATA_Code']; ?>" required>
                    <small class="text-muted">Must be 3 uppercase letters.</small>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Update Airport</button>
                    <a href="airport.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>