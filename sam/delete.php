<?php
include "connection.php";

if (isset($_GET['flight_id'])) {
    $id = $_GET['flight_id'];

    $check = $con->prepare("SELECT COUNT(*) as count FROM booking WHERE flight_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();

    if ($res['count'] > 0) {
        header("location: index.php?error=active_bookings");
    } else {
        $del = $con->prepare("DELETE FROM flight WHERE flight_id = ?");
        $del->bind_param("i", $id);
        
        if ($del->execute()) {
            header("location: index.php?msg=deleted");
        }
    }
}
?>