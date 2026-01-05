<?php
require_once "database.php";
date_default_timezone_set('Asia/Dhaka'); 

$now = date('Y-m-d H:i:s');
echo "Now = $now <br>";

$ft_sql = "INSERT INTO flight_track (flight_id, speed, altitude, longitude, latitude, pt_status, heading)
              SELECT f.flight_id, 450, 35000, a.longitude, a.latitude, 'En Route', 0
              FROM flight f
              JOIN airport a ON f.source = a.airport_id
              WHERE 
              CONCAT(f.scheduled_date, ' ', f.standard_dep_time) <= '$now' 
              AND 
              CONCAT(f.scheduled_arr_date, ' ', f.standard_arr_time) > '$now'
              AND f.flight_id NOT IN (SELECT flight_id FROM flight_track)";


if (!$conn->query($ft_sql)) {
    die("Spawn Error: " . $conn->error);
}


$land_sql = "DELETE FROM flight_track 
             WHERE flight_id IN (
                SELECT flight_id FROM flight f
                WHERE CONCAT(f.scheduled_arr_date, ' ', f.standard_arr_time) <= '$now'
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
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; text-align: left;'>";
    echo "<thead>
            <tr style='background-color: #f2f2f2;'>
                <th>Flight ID</th>

                <th>Latitude</th>
                <th>Longitude</th>
                <th>Heading</th>
            </tr>
          </thead>";
    echo "<tbody>";

    while ($row = $check_flights->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['flight_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['latitude']) . "</td>";
        echo "<td>" . htmlspecialchars($row['longitude']) . "</td>";
        echo "<td>" . htmlspecialchars($row['heading']) . "°</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "No active flights found.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Flight Tracker</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="unpkg.com" />


    <style>
        #map { height: 600px; width: 100%; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        h2 { margin: 15px 0; text-align: center; }
    </style>
</head>
<body>

<h2>Live Flight Tracker</h2>
<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://rawgit.com/bbecquet/Leaflet.RotatedMarker/master/leaflet.rotatedMarker.js"></script>
<script src="unpkg.com"></script>

<script>
    // 1️⃣ Initialize map
    var map = L.map('map').setView([40.6413, -73.7781], 4); // center over JFK for example

    // 2️⃣ Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 3️⃣ Plane icon
    var planeIcon = L.icon({
        iconUrl: 'plane.png', // local plane icon in your project folder
        iconSize: [30, 30],
        iconAnchor: [15, 15] // center so it rotates properly
    });

    // 4️⃣ Global marker store (flight_id => marker)
    var flightMarkers = {};

    // 5️⃣ Fetch flight data and update map
    function updateFlights() {
        fetch('flight_api.php')
        .then(response => response.json())
        .then(flights => {
            flights.forEach(flight => {
                var lat = parseFloat(flight.latitude);
                var lon = parseFloat(flight.longitude);
                var heading = parseFloat(flight.heading);
                var speed = parseFloat(flight.speed).toFixed(1);
                var altitude = parseInt(flight.altitude);

                var popupText = `
                    <b>Flight ID:</b> ${flight.flight_id}<br>
                    <b>Speed:</b> ${speed} knots<br>
                    <b>Altitude:</b> ${altitude} ft<br>
                    <b>Heading:</b> ${heading}&deg;
                `;

                // Update existing marker
                if (flightMarkers[flight.flight_id]) {
                    var marker = flightMarkers[flight.flight_id];
                    marker.setLatLng([lat, lon]);
                    marker.setRotationAngle(heading);
                    marker.getPopup().setContent(popupText);
                    // Refresh clusters to account for new position
                    markers.refreshClusters(marker);
             } else {
                    // Create new marker
                    var marker = L.marker([lat, lon], {
                        icon: planeIcon,
                        rotationAngle: heading
                    }).addTo(map)
                      .bindPopup(popupText);
                    flightMarkers[flight.flight_id] = marker;
                }
            });
        })
        .catch(err => console.error('Flight API Error:', err));
    }

    // 6️⃣ Initial load
    updateFlights();

    // 7️⃣ Refresh every 5 seconds
    setInterval(updateFlights, 5000);
</script>

</body>
</html>




