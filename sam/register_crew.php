<?php
include "connection.php";

$name = "";
$age = "";
$role = "";
$phone = "";
$salary = "";
$experience = "";
$uni = "";
$training_year = "";
$nationality = "";
$language = "";
$license = "";
$marital = "";
$address = "";

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $salary = $_POST['salary'];
    $experience = $_POST['experience'];
    $uni = $_POST['uni'];
    $training_year = $_POST['training_year'];
    $nationality = $_POST['nationality'];
    $language = $_POST['language'];
    $license = $_POST['license'];
    $marital = $_POST['marital'];
    $address = $_POST['address'];

    if (empty($name) || empty($role)) {
        $errorMessage = "Name and Role are required.";
    } else {
        $query = "INSERT INTO crew (name, age, role, phone, salary, experience_years, grad_uni, training_year, nationality, language_skills, license_number, marital_status, family_address) 
                      VALUES ('$name', '$age', '$role', '$phone', '$salary', '$experience', '$uni', '$training_year', '$nationality', '$language', '$license', '$marital', '$address')";
        $result = mysqli_query($con, $query);

        if ($result) {
            $successMessage = "Crew member registered successfully!";
            header("location: crew_management.php");
            exit;
        } else {
            $errorMessage = "Error registering crew: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Crew - PQLR Airline</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <h2>Register New Crew Member</h2>
                    <hr>
                    <?php if (!empty($errorMessage))
                        echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>

                    <form method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Age</label>
                                <input type="number" class="form-control" name="age" value="<?php echo $age; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role">
                                    <option value="Pilot">Pilot</option>
                                    <option value="Co-Pilot">Co-Pilot</option>
                                    <option value="Cabin Crew">Cabin Crew</option>
                                    <option value="Engineer">Engineer</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary ($)</label>
                                <input type="number" step="0.01" class="form-control" name="salary"
                                    value="<?php echo $salary; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" name="experience"
                                    value="<?php echo $experience; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Training Year</label>
                                <input type="number" class="form-control" name="training_year"
                                    value="<?php echo $training_year; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" class="form-control" name="nationality"
                                    value="<?php echo $nationality; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">University Graduate (Uni Name)</label>
                            <input type="text" class="form-control" name="uni" value="<?php echo $uni; ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">License Number</label>
                                <input type="text" class="form-control" name="license" value="<?php echo $license; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Marital Status</label>
                                <input type="text" class="form-control" name="marital" value="<?php echo $marital; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Language Skills</label>
                            <input type="text" class="form-control" name="language"
                                placeholder="e.g. English, Bangla, Spanish" value="<?php echo $language; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Family Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo $address; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register Crew</button>
                            <a href="crew_management.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>