<?php
include "connection.php";

$baggage_id = isset($_GET['baggage_id']) ? $_GET['baggage_id'] : "";
$claim_type = "";
$description = "";

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baggage_id = $_POST['baggage_id'];
    $claim_type = $_POST['claim_type'];
    $description = $_POST['description'];

    if (empty($baggage_id) || empty($claim_type)) {
        $errorMessage = "Baggage ID and Claim Type are required.";
    } else {
        $query = "INSERT INTO baggage_claims (baggage_id, claim_type, description) 
                      VALUES ('$baggage_id', '$claim_type', '$description')";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Update baggage status to reflect the claim
            $update_baggage = "UPDATE baggage SET status = '$claim_type' WHERE baggage_id = '$baggage_id'";
            mysqli_query($con, $update_baggage);

            $successMessage = "Claim registered successfully!";
            header("location: baggage_tracking.php");
            exit;
        } else {
            $errorMessage = "Error registering claim: " . mysqli_error($con);
        }
    }
}

$baggages = mysqli_query($con, "SELECT baggage_id, tag_number FROM baggage");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baggage Claim - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2>Register Baggage Claim</h2>
                    <hr>
                    <?php if (!empty($errorMessage))
                        echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Baggage (Tag Number)</label>
                            <select class="form-select" name="baggage_id">
                                <option value="">Select Baggage</option>
                                <?php while ($b = mysqli_fetch_assoc($baggages)) { ?>
                                    <option value="<?php echo $b['baggage_id']; ?>" <?php echo ($baggage_id == $b['baggage_id']) ? 'selected' : ''; ?>>
                                        <?php echo $b['tag_number']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Claim Type</label>
                            <select class="form-select" name="claim_type">
                                <option value="Lost">Lost</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description / Details</label>
                            <textarea class="form-control" name="description"
                                rows="3"><?php echo $description; ?></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">Submit Claim</button>
                            <a href="baggage_tracking.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>