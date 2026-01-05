<?php
require_once "database.php";

// Fetch all active flights from flight_track
$result = $conn->query("SELECT flight_id, latitude, longitude, heading, speed, altitude
                        FROM flight_track");

$flights = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($flights);
?>
