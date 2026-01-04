<?php
include "connection.php";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM transit WHERE transit_id = ?";
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: transits.php?status=deleted");
            exit;
        } else {
            echo "Error deleting record: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    header("location: transits.php");
    exit;
}

mysqli_close($con);
?>