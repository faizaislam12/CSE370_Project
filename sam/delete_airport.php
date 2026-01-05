<?php
include "connection.php";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM airport WHERE airport_id = ?";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: airport.php?status=deleted");
            exit;
        } else {
            echo "Error: Could not delete airport. It might be linked to existing flights. " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    header("location: airport.php");
    exit;
}

mysqli_close($con);
?>