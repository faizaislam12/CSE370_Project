<?php
    include "connection.php";
    
    $que = "SELECT f.*, a.tail_num, 
                   src.name as source_name, 
                   dest.name as dest_name 
            FROM flight f
            LEFT JOIN aircraft a ON f.aircraft_id = a.aircraft_id
            LEFT JOIN airport src ON f.source = src.airport_id
            LEFT JOIN airport dest ON f.destination = dest.airport_id
            ORDER BY f.scheduled_date ASC";
            
    $result = mysqli_query($con, $que);

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PQLR Airlines | Flight Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    
</head>
<body>

    <div class="notification-stack" id="notif-container"></div>

    <div class="container-fluid my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="margin:0;">Flight Schedule Management</h2>
            <a href = "all_view_home.php" type = "button">HOME</a>
        </div>

        <table class="table table-hover table-bordered border-primary shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>Flight No.</th>
                    <th>Route (From-To)</th>
                    <th>Date</th>
                    <th>STD/STA</th>
                    <th>Gate</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status = trim(strtolower($row['fl_status'])); // trim is used so that unwanted spaces gets removed 
                        $disp_source = $row['source_name'] ?? "Airport #".$row['source'];
                        $disp_dest = $row['dest_name'] ?? "Airport #".$row['destination'];
                        
                        echo "<tr>
                                <td class='fw-bold'>{$row['flight_number']}</td>
                                <td>" . htmlspecialchars($disp_source) . " → " . htmlspecialchars($disp_dest) . "</td>
                                <td>{$row['scheduled_date']}</td>
                                <td>{$row['standard_dep_time']} / {$row['standard_arr_time']}</td>
                                <td>{$row['gate']}</td>
                                <td>{$row['fl_status']}</td>
                                ";

                        

                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center py-4'>No flights found in database.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>