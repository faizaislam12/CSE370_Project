<?php
include "connection.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code        = $_POST['reason_code'];
    $description = $_POST['description'];
    $category    = $_POST['category'];

    $sql = "INSERT INTO delay_reason (reason_code, description, category) VALUES (?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $code, $description, $category);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: delay_reason.php?msg=added");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Error: Code might already exist or " . mysqli_error($con) . "</div>";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Delay Reason - Dokidoki</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Add New Delay Reason</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form action="add_delay_reason.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Reason Code (Short Name)</label>
                                <input type="text" name="reason_code" class="form-control" placeholder="e.g., WTHR, TECH, SECU" required>
                                <small class="text-muted">Use a unique 4-5 letter code.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Describe the delay reason..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="Weather">Weather</option>
                                    <option value="Technical">Technical</option>
                                    <option value="Operational">Operational</option>
                                    <option value="Security">Security</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="delay_reason.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">Save Reason</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>