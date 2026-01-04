<?php
require_once "database.php";
date_default_timezone_set('Asia/Dhaka'); 

$now = date('Y-m-d H:i:s');
echo "Now = $now <br>";

// SPAWN: Uses all four columns to build accurate DATETIME strings
$spawn_sql = "INSERT INTO flight_track (flight_id, speed, altitude, longitude, latitude, pt_status, heading)
              SELECT f.flight_id, 450, 35000, a.longitude, a.latitude, 'En Route', 0
              FROM flight f
              JOIN airport a ON f.source = a.airport_id
              WHERE 
                -- Combine Departure Date and Time
                CONCAT(f.scheduled_date, ' ', f.scheduled_dep_time) <= '$now' 
                AND 
                -- Combine Arrival Date and Time
                CONCAT(f.scheduled_arr_date, ' ', f.scheduled_arr_time) > '$now'
              AND f.flight_id NOT IN (SELECT flight_id FROM flight_track)";


if (!$conn->query($spawn_sql)) {
    die("Spawn Error: " . $conn->error);
}

// LAND: Uses all four columns to determine landing time
$land_sql = "DELETE FROM flight_track 
             WHERE flight_id IN (
                SELECT flight_id FROM flight f
                WHERE CONCAT(f.scheduled_arr_date, ' ', f.scheduled_arr_time) <= '$now'
             )";
if (!$conn->query($land_sql)) { die("Land Error: " . $conn->error); }


$active_flights = $conn->query("
    SELECT t.*, dest.latitude AS arr_lat, dest.longitude AS arr_lon 
    FROM flight_track t 
    JOIN flight f ON t.flight_id = f.flight_id
    JOIN airport dest ON f.destination = dest.airport_id
");

if ($active_flights && $active_flights->num_rows > 0) {
    while($row = $active_flights->fetch_assoc()) {
        $id = $row['pt_id'];
        $lat1 = deg2rad($row['latitude']);
        $lon1 = deg2rad($row['longitude']);
        $lat2 = deg2rad($row['arr_lat']);
        $lon2 = deg2rad($row['arr_lon']);

        // Bearing 
        $y = sin($lon2 - $lon1) * cos($lat2);
        $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($lon2 - $lon1);
        $bearing = (rad2deg(atan2($y, $x)) + 360) % 360;

        //SPEED
        $dist_km = (450 * 1.852) / 60; 
        $R = 6371; // Earth radius
        $ang_dist = $dist_km/$R;

        $new_lat = asin(sin($lat1) * cos($ang_dist) + cos($lat1) * sin($ang_dist) * cos(deg2rad($bearing)));
        $new_lon = $lon1 + atan2(sin(deg2rad($bearing)) * sin($ang_dist) * cos($lat1), cos($ang_dist) - sin($lat1) * sin($new_lat));

        $new_lat = rad2deg($new_lat);
        $new_lon = rad2deg($new_lon);

        $stmt = $conn->prepare("UPDATE flight_track SET latitude=?, longitude=?, heading=?, timestamp=NOW() WHERE pt_id=?");
        $stmt->bind_param("dddi", $new_lat, $new_lon, $bearing, $id);
        $stmt->execute();
    }
}
echo "<h2>Active Flights:</h2>";
$check_flights = $conn->query("SELECT * FROM flight_track");
if ($check_flights && $check_flights->num_rows > 0) {
    while ($row = $check_flights->fetch_assoc()) {
        echo "Flight ID: {$row['flight_id']}, Lat: {$row['latitude']}, Lon: {$row['longitude']}, Heading: {$row['heading']}<br>";
    }
} else {
    echo "No active flights.";
}
?>
