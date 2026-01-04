<?php
include "connection.php";
$flight_query = "SELECT flight_id, flight_number FROM flight";
$flight_result = mysqli_query($con, $flight_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leg      = $_POST['flight_leg'];
    $flight   = $_POST['flight_id'];
    $crew     = $_POST['flight_crew_id'];

    $sql = "INSERT INTO transit (flight_leg, flight_id, flight_crew_id) VALUES (?, ?, ?)";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "iii", $leg, $flight, $crew);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: transits.php?status=success");
            exit;
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Transit Leg</title>
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-4" style="max-width: 600px; margin: auto;">
            <h3 class="text-center mb-4">Assign Transit Leg</h3>
            <form action="add_transit.php" method="POST">
                
                <div class="mb-3">
                    <label class="form-label">Select Flight</label>
                    <select name="flight_id" class="form-select" required>
                        <option value="">-- Choose a Flight --</option>
                        <?php while($f_row = mysqli_fetch_assoc($flight_result)): ?>
                            <option value="<?php echo $f_row['flight_id']; ?>">
                                <?php echo $f_row['flight_number']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Flight Leg (Order)</label>
                    <select name="flight_leg" class="form-select" required>
                        <option value="1">1st Leg (Origin)</option>
                        <option value="2">2nd Leg (Stopover)</option>
                        <option value="3">3rd Leg (Stopover)</option>
                        <option value="4">4th Leg (Final)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Crew ID</label>
                    <input type="number" name="flight_crew_id" class="form-control" placeholder="Enter Crew ID" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Save Transit Leg</button>
                <a href="transits.php" class="btn btn-link w-100 mt-2 text-secondary text-decoration-none">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>