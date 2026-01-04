<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Flight Tracker</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="unpkg.com" />
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
        iconUrl: 'plane_icon.png', // local plane icon in your project folder
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
