<?php
include "connection.php";

$crew_id = isset($_GET['crew_id']) ? $_GET['crew_id'] : "";
$flight_id = "";
$shift_start = "";
$shift_end = "";

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $crew_id = $_POST['crew_id'];
    $flight_id = $_POST['flight_id'];
    $shift_start = $_POST['shift_start'];
    $shift_end = $_POST['shift_end'];

    if (empty($crew_id) || empty($flight_id)) {
        $errorMessage = "Crew member and Flight are required.";
    } else {
        $query = "INSERT INTO crew_assignment (crew_id, flight_id, shift_start, shift_end) 
                      VALUES ('$crew_id', '$flight_id', '$shift_start', '$shift_end')";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Update availability status of the crew
            mysqli_query($con, "UPDATE crew SET availability_status = 0 WHERE crew_id = '$crew_id'");

            $successMessage = "Crew assigned successfully!";
            header("location: crew_management.php");
            exit;
        } else {
            $errorMessage = "Error: " . mysqli_error($con);
        }
    }
}

$crew_list = mysqli_query($con, "SELECT crew_id, name, role FROM crew WHERE availability_status = 1");
if ($crew_id) {
    $specific_crew = mysqli_query($con, "SELECT crew_id, name, role FROM crew WHERE crew_id = '$crew_id'");
    $crew_data = mysqli_fetch_assoc($specific_crew);
}
$flights = mysqli_query($con, "SELECT flight_id, flight_number FROM flight");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crew Assignment - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 shadow">
                    <h2>Assign Crew to Flight</h2>
                    <hr>
                    <?php if (!empty($errorMessage))
                        echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Crew Member</label>
                            <?php if ($crew_id) { ?>
                                <input type="hidden" name="crew_id" value="<?php echo $crew_id; ?>">
                                <input type="text" class="form-control"
                                    value="<?php echo $crew_data['name'] . ' (' . $crew_data['role'] . ')'; ?>" disabled>
                            <?php } else { ?>
                                <select class="form-select" name="crew_id">
                                    <option value="">Select Available Crew</option>
                                    <?php while ($c = mysqli_fetch_assoc($crew_list)) { ?>
                                        <option value="<?php echo $c['crew_id']; ?>">
                                            <?php echo $c['name'] . ' (' . $c['role'] . ')'; ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Flight</label>
                            <select class="form-select" name="flight_id">
                                <option value="">Select Flight</option>
                                <?php while ($f = mysqli_fetch_assoc($flights)) { ?>
                                    <option value="<?php echo $f['flight_id']; ?>"><?php echo $f['flight_number']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shift Start</label>
                                <input type="datetime-local" class="form-control" name="shift_start" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shift End</label>
                                <input type="datetime-local" class="form-control" name="shift_end" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Assign Crew</button>
                            <a href="crew_management.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>