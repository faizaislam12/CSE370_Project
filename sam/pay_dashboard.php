<?php
session_start();
require_once "database.php";

echo "<h2>Payment Dashboard</h2>";

if(!isset($_SESSION['admin_id'])){
  $_SESSION['admin_id'] = 1;
}

$sql = "SELECT  p.payment_id, 
            p.amount, 
            p.transaction_ref, 
            b.seat_label, 
            b.flight_id 
        FROM payment p 
        JOIN booking b ON p.booking_id = b.booking_id 
        WHERE p.pay_status = 'In_Review'"; 

$res = $conn->query($sql);
if($res->num_rows>0){
     while ($row = $res->fetch_assoc()){
      echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
      echo "<b>Reference:</b>".$row['transaction_ref']."<b>Amount:</b> $". number_format($row['amount'],2). "<br>";
      echo "<b>Flight ID:</b>". $row['flight_id']."<br>";
      echo "<b>Seat:</b>".$row['seat_label']."<br><br>";
echo "<a href='admin_verification.php?pay_id={$row['payment_id']}' 
         style='background:#e67e22; color:white; padding:5px 10px; text-decoration:none; border-radius:4px;'>
         Verify Payment
      </a>";     }
}else{
  echo"<p>No pending Payments!</p>";
}


echo "<h2>Confirmed Payments </h2>";

$sql_confirmed = "SELECT p.payment_id, b.flight_id, b.seat_label 
                  FROM payment p 
                  JOIN booking b ON p.booking_id = b.booking_id 
                  WHERE p.pay_status = 'Confirmed'"; 

$res_conf = $conn->query($sql_confirmed);

if($res_conf->num_rows > 0){
    while ($row = $res_conf->fetch_assoc()){
        echo "<div style='border:1px solid #2ecc71; padding:10px; margin-bottom:10px; background:#f4fff4;'>";
        echo "<b>Flight:</b> {$row['flight_id']} | <b>Seat:</b> {$row['seat_label']}<br><br>";
        
        // LINK TO BOARDING PASS HERE
        echo "<a href='boarding_pass.php?payment_id={$row['payment_id']}' 
                 style='background:#3498db; color:white; padding:5px 10px; text-decoration:none; border-radius:4px;'>
                 Generate Boarding Pass
              </a>";
        echo "</div>";
    }
} else {
    echo "<p>No confirmed payments to generate passes for.</p>";
}
?>