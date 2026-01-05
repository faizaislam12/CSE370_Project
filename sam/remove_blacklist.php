<?php
include "connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM blacklist WHERE blacklist_id = $id";
    mysqli_query($con, $sql);
}

header("location: security_compliance.php");
exit;
?>