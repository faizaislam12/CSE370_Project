<?php
include "connection.php";

$template_query = "SELECT template_id FROM seat_template";
$templates = mysqli_query($con, $template_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tail_num    = $_POST['tail_num'];
    $model       = $_POST['model'];
    $template_id = $_POST['template_id'];

    $sql = "INSERT INTO aircraft (tail_num, model, template_id) VALUES (?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $tail_num, $model, $template_id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: aircraft.php?status=success");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($con) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Aircraft</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Aircraft Registration</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Tail Number</label>
                                <input type="text" name="tail_num" class="form-control" placeholder="e.g., N737DG" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Aircraft Model</label>
                                <input type="text" name="model" class="form-control" placeholder="e.g., Airbus A320neo" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Seat Template</label>
                                <select name="template_id" class="form-select" required>
                                    <option value="">-- Choose Template --</option>
                                    <?php 
                                    while($t = mysqli_fetch_assoc($templates)) {
                                        echo "<option value='{$t['template_id']}'>Template #{$t['template_id']}</option>";
                                    } 
                                    ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="aircraft.php" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Save Aircraft</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>