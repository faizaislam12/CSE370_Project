<?php
require_once "database.php";

if(isset($_GET['flight_id'])){
  $flight_id = (int)$_GET['flight_id'];
}else {
  $flight_id = 0;
  die("Please select a valid flight ID!");
}




$sql = "SELECT scheduled_date, standard_dep_time FROM flight WHERE flight_id = ?";

$stmt= $conn->prepare($sql);
$stmt->bind_param('i', $flight_id);
$stmt->execute();
$result = $stmt->get_result();
$flight_data = $result->fetch_assoc();


$dept_info = $flight_data['scheduled_date'].' '. $flight_data['standard_dep_time'];
$dept_datetime = new DateTime($dept_info);

$date = date("Y-m-d");
$time = date("H:i:s");
$current_datetime = new DateTime($date.' '.$time);


//LAST MIN RESERVATION//
$time_diff = $current_datetime->diff($dept_datetime);
$days_left = $time_diff->days;

if($days_left<=4){
  $price_sql = "SELECT rule_id, multiplier, rule_name FROM pricing_rule WHERE rule_name = 'Last Miniute'";
  $price = $conn->query($price_sql);
  $price_details= $price->fetch_assoc();
}else{
  $p_sql = "SELECT rule_id, multiplier, rule_name FROM pricing_rule
            WHERE (? BETWEEN st_date AND end_date OR st_date IS NULL)
            AND (? BETWEEN st_time AND end_time OR st_time IS NULL)
            ORDER BY priority_check DESC LIMIT 1";
  $stmt_price = $conn->prepare($p_sql);
  $stmt_price->bind_param("ss",$date,$time);
  $stmt_price->execute();
  $price = $stmt_price->get_result();
  $price_details = $price->fetch_assoc();
}

if($price_details){
  $multiplier = $price_details["multiplier"];
  $rule_name = $price_details["rule_name"];
  $rule_id    = $price_details["rule_id"];
}else{
  $multiplier = 1.0;
  $rule_name = "Regular Rate";
  $rule_id = 7; 
}

  
?>