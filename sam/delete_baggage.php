<?php
include "connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM baggage WHERE baggage_id = $id";
    mysqli_query($con, $sql);
}

header("location: baggage_tracking.php");
exit;
?>