<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Search Results</title>
    <!-- Water.css - Automatically styles plain HTML tags -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    <style>
        body { max-width: 900px; margin: 0 auto; padding: 20px; }
        .route-cell { font-weight: bold; color: #005fb8; }
        .status-tag { color: #2e7d32; font-size: 0.9em; }
        .btn-select { text-decoration: none; font-weight: bold; }
        table { width: 100%; margin-top: 20px; }
        th, td { text-align: left; }
    </style>
</head>
<body>

    <header>
        <h1>Available Flights</h1>
        <p>Results for flights from <strong><?php echo htmlspecialchars($_GET['from_city'] ?? ''); ?></strong> 
        to <strong><?php echo htmlspecialchars($_GET['to_city'] ?? ''); ?></strong> on 
        <strong><?php echo htmlspecialchars($_GET['date'] ?? ''); ?></strong>.</p>
    </header>

    <?php
    require_once "database.php";
    date_default_timezone_set('Asia/Dhaka'); 
    $conn->query("SET time_zone = '+06:00'"); 

    $from = $_GET['from_city'] ?? '';
    $to = $_GET['to_city'] ?? '';
    $date = $_GET['date'] ?? '';

    if (!empty($from) && !empty($to) && !empty($date)) {
        // SQL query to get city names and flight info
        $sql = "SELECT f.flight_id, f.flight_number, f.standard_dep_time, f.fl_status, 
                       src.city as src_city, dest.city as dest_city
                FROM flight f
                INNER JOIN airport src ON f.source = src.airport_id
                INNER JOIN airport dest ON f.destination = dest.airport_id
                WHERE LOWER(src.city) = LOWER(?) 
                AND LOWER(dest.city) = LOWER(?)  
                AND f.scheduled_date = ? 
                AND (f.scheduled_date > CURDATE() OR (f.scheduled_date = CURDATE() AND f.standard_dep_time > CURTIME())) 
                AND f.fl_status IN ('Scheduled', 'On Time', 'Delayed', 'En Route')    
                ORDER BY f.standard_dep_time ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $from, $to, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Flight</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): 
                        $formattedTime = date("h:i A", strtotime($row['standard_dep_time']));
                    ?>
                    <tr>
                        <td><code><?php echo htmlspecialchars($row['flight_number']); ?></code></td>
                        <td class="route-cell">
                            <?php echo htmlspecialchars($row['src_city']); ?> → 
                            <?php echo htmlspecialchars($row['dest_city']); ?>
                        </td>
                        <td><?php echo $formattedTime; ?></td>
                        <td><span class="status-tag">● <?php echo $row['fl_status']; ?></span></td>
                        <td>
                            <a href="dynamic_seat_map.php?flight_id=<?php echo $row['flight_id']; ?>" class="btn-select">
                                Select Seats
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php
        } else {
            echo "<blockquote>No flights found for this route and date. <a href='home.php'>Go back</a></blockquote>";
        }
    }
    ?>

    <footer>
        <p><small>All times are in local Dhaka time (GMT+6).</small></p>
    </footer>

</body>
</html>