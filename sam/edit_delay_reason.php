<?php
include "connection.php";

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $fetch_sql = "SELECT * FROM delay_reason WHERE reason_code = ?";
    $stmt = mysqli_prepare($con, $fetch_sql);
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        header("location: delay_reason.php");
        exit;
    }
} else {
    header("location: delay_reason.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_code    = $_POST['old_code']; 
    $description = $_POST['description'];
    $category    = $_POST['category'];

    $update_sql = "UPDATE delay_reason SET description = ?, category = ? WHERE reason_code = ?";
    
    if ($stmt = mysqli_prepare($con, $update_sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $description, $category, $old_code);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: delay_reason.php?msg=updated");
            exit;
        } else {
            $error = "Error updating record: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Delay Reason</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Update Delay Reason: <?php echo htmlspecialchars($row['reason_code']); ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="old_code" value="<?php echo htmlspecialchars($row['reason_code']); ?>">

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="Weather" <?php if($row['category'] == 'Weather') echo 'selected'; ?>>Weather</option>
                                    <option value="Technical" <?php if($row['category'] == 'Technical') echo 'selected'; ?>>Technical</option>
                                    <option value="Operational" <?php if($row['category'] == 'Operational') echo 'selected'; ?>>Operational</option>
                                    <option value="Security" <?php if($row['category'] == 'Security') echo 'selected'; ?>>Security</option>
                                    <option value="Other" <?php if($row['category'] == 'Other') echo 'selected'; ?>>Other</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="delay_reason.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>