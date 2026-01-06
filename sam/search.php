<?php
require_once "database.php";

date_default_timezone_set('Asia/Dhaka'); 

$from = $_GET['from_city'] ?? '';
$to = $_GET['to_city'] ?? '';
$date = $_GET['date'] ?? '';

if (!empty($from) && !empty($to) && !empty($date)) {
    
    
    $sql = "SELECT flight_id, flight_number, standard_dep_time, fl_status 
            FROM flight 
            WHERE source = ? 
            AND destination = ? 
            AND scheduled_date = ? 
            AND (scheduled_date > CURDATE() OR (scheduled_date = CURDATE() AND standard_dep_time > CURTIME())) 
            AND fl_status = 'Scheduled'
            ORDER BY standard_dep_time ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $from, $to, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "Flight: " . $row['flight_number'] . " - Departs: " . $row['standard_dep_time'];
            echo " <a href='dynamic_seat_map.php?flight_id=" . $row['flight_id'] . "'>Select Seats</a><br>";
        }
    } else {
        echo "No upcoming flights found for $date.";
    }
}
?>

