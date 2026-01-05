<?php
include "connection.php";
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    mysqli_query($con, "DELETE FROM delay_reason WHERE reason_code = '$code'");
    header("location: delay_reason.php?msg=deleted");
}
?>