<?php
include "connection.php";

if (isset($_GET["id"])) {
    $aircraft_id = $_GET["id"];
    $sql = "DELETE FROM aircraft WHERE aircraft_id = ?";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $aircraft_id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: aircraft.php?message=deleted");
            exit;
        } else {
            echo "Error deleting record: " . mysqli_error($con);
        }
        
        mysqli_stmt_close($stmt);
    }
} else {
    header("location: aircraft.php");
    exit;
}

mysqli_close($con);
?>