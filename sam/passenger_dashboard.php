<?php
session_start();
include "connection.php";

// 1. SECURE THE PAGE
if (!isset($_SESSION['user_id'])) {
    header("Location: /sam/login_form.php");
    exit();
}

$p_id = $_SESSION['user_id']; 

// 2. FETCH BOOKINGS FOR THIS SPECIFIC USER


$query = "SELECT b.*, f.flight_id, f.flight_number, f.scheduled_date, f.fl_status, 
                 src.name as src_name, dest.name as dest_name
          FROM booking b
          JOIN flight f ON b.flight_id = f.flight_id
          JOIN airport src ON f.source = src.airport_id
          JOIN airport dest ON f.destination = dest.airport_id
          WHERE b.user_id = $p_id
          ORDER BY f.scheduled_date DESC"; // Shows booking status for that specific passenger 
          
$result = mysqli_query($con, $query);

// 3. TARGETED DELAY NOTIFICATION LOGIC (PHP Side)
$delay_check = mysqli_query($con, "SELECT COUNT(*) as delay_count 
                                   FROM booking b 
                                   JOIN flight f ON b.flight_id = f.flight_id 
                                   WHERE b.user_id = $p_id 
                                   AND LOWER(f.fl_status) = 'delayed'");
$delay_data = mysqli_fetch_assoc($delay_check);
$has_delays = $delay_data['delay_count'] > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | PQLR Airlines</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #0d6efd !important; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .status-badge { font-size: 0.85rem; padding: 5px 12px; border-radius: 20px; font-weight: 500; }
        
        /* New Styles for Clickable Notifications */
        .clickable-notif { cursor: pointer; transition: transform 0.2s; }
        .clickable-notif:hover { transform: scale(1.02); }
        #notif-container { position: sticky; top: 20px; z-index: 1000; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4">
        <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-plane-up me-2"></i>PQLR Airlines</a>
        <div class="ms-auto d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-inline">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_username'] ?? 'Passenger'); ?></strong>
            </span>
            <a href="all_view_home.php" class="btn btn-sm btn-outline-light rounded-pill px-3">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        
        <div id="notif-container"></div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark mb-0">My Bookings</h2>
                    <a href="all_view_home.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fa-solid fa-plus me-2"></i>New Booking
                    </a>
                </div>

                <div class="card overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4 py-3">Flight No.</th>
                                    <th>Route</th>
                                    <th>Date</th>
                                    <th>Seat</th>
                                    <th>Booking Status</th>
                                    <th class="pe-4 text-center">Live Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): 
                                        $status = strtolower($row['fl_status']);
                                        $disp_source = $row['src_name'];
                                        $disp_dest = $row['dest_name'];
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary"><?php echo $row['flight_number']; ?></td>
                                        <td>
                                            <div class="small fw-semibold"><?php echo "$disp_source to $disp_dest"; ?></div>
                                        </td>
                                        <td class="small"><?php echo date('D, d M Y', strtotime($row['scheduled_date'])); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo $row['seat_label']; ?></span></td>
                                        <td>
                                            <?php if($row['booking_status'] == 'Confirmed'): ?>
                                                <span class="status-badge bg-success-subtle text-success">Booked</span>
                                            <?php else: ?>
                                                <span class="status-badge bg-warning-subtle text-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-center">
                                            <?php 
                                            // INTEGRATED DELAY LOGIC
                                            if ($status == 'delayed') {
                                                echo "<span class='badge bg-danger'>Delayed</span>";
                                                // Inject JS to create the floating notification
                                                echo "<script>
                                                        window.addEventListener('load', function() {
                                                            const box = document.getElementById('notif-container');
                                                            box.innerHTML += `
                                                                <div class='alert alert-warning alert-dismissible fade show shadow clickable-notif' 
                                                                     onclick=\"window.location.href='delay.php?flight_id={$row['flight_id']}'\" 
                                                                     role='alert' style='color: #856404; background-color: #fff3cd; border-color: #ffeeba; margin-bottom:10px;'>
                                                                    <i class='fa-solid fa-triangle-exclamation me-2'></i>
                                                                    <strong>Delay Alert:</strong> Flight {$row['flight_number']} is delayed.
                                                                    <br><small>{$disp_source} to {$disp_dest}</small>
                                                                    <button type='button' class='btn-close' data-bs-dismiss='alert' onclick='event.stopPropagation();'></button>
                                                                </div>`;
                                                        });
                                                      </script>";
                                            } else {
                                                echo "<span class='badge bg-success'>Scheduled</span>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">No active bookings found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>