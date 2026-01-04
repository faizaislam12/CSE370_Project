<?php
session_start();
$_SESSION['admin_id'] = 1; // Log in as Ahmad Raza
echo "You are now logged in. <a href='pay_dashboard.php'>Go to Dashboard</a>";
?>