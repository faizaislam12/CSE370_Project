<?php

session_start();


require_once "database.php"; 


if (!isset($conn) && isset($con)) {
    $conn = $con;
}


if (!isset($_SESSION['user_id'])) {

    header("Location: /login_form.php");
    exit();
}

// For this dashboard, we assume the person logged in is the Admin.
$current_admin_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .navbar { background-color: #1a237e !important; } /* Deep Blue */
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .section-title { border-left: 5px solid #1a237e; padding-left: 15px; margin-bottom: 20px; color: #1a237e; }
        .table-pending { border-left: 5px solid #ffa000; }
        .table-confirmed { border-left: 5px solid #43a047; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-4 px-4 shadow">
    <a class="navbar-brand fw-bold" href="#">PQLR Airlines Admin</a>
    <div class="ms-auto text-white">
        <small>Logged in as: <?php echo htmlspecialchars($_SESSION['user_username'] ?? 'Administrator'); ?></small>
        <a href="all_view_home.php" class="btn btn-sm btn-outline-light ms-3">Logout</a>
    </div>
</nav>

<div class="container pb-5">

    <h3 class="section-title">Payments Awaiting Verification</h3>
    <div class="card mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Transaction Ref</th>
                        <th>Amount</th>
                        <th>Flight & Seat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.payment_id, p.amount, p.transaction_ref, b.seat_label, b.flight_id 
                            FROM payment p 
                            JOIN booking b ON p.booking_id = b.booking_id 
                            WHERE p.pay_status = 'In_Review'"; 

                    $res = $conn->query($sql);
                    if($res && $res->num_rows > 0):
                        while ($row = $res->fetch_assoc()): ?>
                            <tr class="table-pending">
                                <td><code class="fw-bold text-dark"><?php echo $row['transaction_ref']; ?></code></td>
                                <td class="text-primary fw-bold">$<?php echo number_format($row['amount'], 2); ?></td>
                                <td>
                                    <span class="d-block small text-muted">Flight ID: <?php echo $row['flight_id']; ?></span>
                                    <span class="badge bg-secondary">Seat: <?php echo $row['seat_label']; ?></span>
                                </td>
                                <td>
                                    <a href="admin_verification.php?pay_id=<?php echo $row['payment_id']; ?>" 
                                       class="btn btn-warning btn-sm fw-bold shadow-sm">
                                       Verify Now
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; 
                    else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No pending payments to review.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <h3 class="section-title">Confirmed & Boarding Passes</h3>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Payment ID</th>
                        <th>Flight Details</th>
                        <th>Seat</th>
                        <th>Boarding Pass</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_confirmed = "SELECT p.payment_id, b.flight_id, b.seat_label 
                                      FROM payment p 
                                      JOIN booking b ON p.booking_id = b.booking_id 
                                      WHERE p.pay_status = 'Confirmed'"; 

                    $res_conf = $conn->query($sql_confirmed);
                    if($res_conf && $res_conf->num_rows > 0):
                        while ($row = $res_conf->fetch_assoc()): ?>
                            <tr class="table-confirmed">
                                <td>#<?php echo $row['payment_id']; ?></td>
                                <td><strong>Flight #<?php echo $row['flight_id']; ?></strong></td>
                                <td><span class="badge bg-success"><?php echo $row['seat_label']; ?></span></td>
                                <td>
                                    <a href="boarding_pass.php?payment_id=<?php echo $row['payment_id']; ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                       <i class="bi bi-ticket-perforated"></i> Generate Pass
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No confirmed bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
