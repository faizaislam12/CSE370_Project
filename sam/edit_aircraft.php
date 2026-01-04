<?php
include "connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $res = mysqli_query($con, "SELECT * FROM aircraft WHERE aircraft_id = $id");
    $row = mysqli_fetch_assoc($res);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aid         = $_POST['aircraft_id'];
    $tail_num    = $_POST['tail_num'];
    $model       = $_POST['model'];
    $template_id = $_POST['template_id'];

    $sql = "UPDATE aircraft SET tail_num = ?, model = ?, template_id = ? WHERE aircraft_id = ?";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssii", $tail_num, $model, $template_id, $aid);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: aircraft.php?msg=updated");
            exit;
        }
    }
}

$templates = mysqli_query($con, "SELECT template_id FROM seat_template");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Aircraft</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-4 mx-auto" style="max-width: 500px;">
            <h4>Update Aircraft Details</h4>
            <form action="" method="POST">
                <input type="hidden" name="aircraft_id" value="<?php echo $row['aircraft_id']; ?>">
                
                <div class="mb-3">
                    <label>Tail Number</label>
                    <input type="text" name="tail_num" class="form-control" value="<?php echo $row['tail_num']; ?>" required>
                </div>

                <div class="mb-3">
                    <label>Model</label>
                    <input type="text" name="model" class="form-control" value="<?php echo $row['model']; ?>" required>
                </div>

                <div class="mb-3">
                    <label>Seat Template</label>
                    <select name="template_id" class="form-select">
                        <?php while($t = mysqli_fetch_assoc($templates)): ?>
                            <option value="<?php echo $t['template_id']; ?>" <?php if($t['template_id'] == $row['template_id']) echo 'selected'; ?>>
                                Template #<?php echo $t['template_id']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>