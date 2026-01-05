<?php
 
require_once "database.php";
require_once "dynamic_pricing.php";


$flight_id = $_GET["flight_id"]?? 0;

$sql = "SELECT t.seat_label, t.class, t.base_price, b.booking_id
        FROM flight f JOIN aircraft a ON f.aircraft_id = a.aircraft_id
        JOIN seat_template t ON a.template_id = t.template_id 
        LEFT JOIN booking b ON (b.seat_label = t.seat_label) AND b.flight_id = f.flight_id
        WHERE f.flight_id=?
        ORDER BY LENGTH(t.seat_label)ASC, t.seat_label ASC;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$flight_id);
$stmt-> execute();
$_result = $stmt->get_result();


$max_seats_in_any_row = 0;
$rows = [];
while ($row = $_result->fetch_assoc()){
  $row_num = (int)$row['seat_label']; 
  $rows[$row_num][] = $row;

   if (count($rows[$row_num]) > $max_seats_in_any_row) {
        $max_seats_in_any_row = count($rows[$row_num]);
    }
}

$aisle_trigger = ceil($max_seats_in_any_row / 2);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Seat</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; }
        
        /* The Container for the Plane */
        .plane-container { 
            display: flex; flex-direction: column; align-items: center; 
            gap: 12px; padding: 50px; margin-top: 20px;
        }

        /* Each Row of Seats */
        .seat-row { 
            display: flex; flex-direction: row; align-items: center; gap: 10px; 
        }

        /* Standard Seat Look */
        .seat { 
            width: 45px; height: 45px; border-radius: 8px; 
            display: flex; align-items: center; justify-content: center;
            color: white; text-decoration: none; font-weight: bold; font-size: 11px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        /* 2025 Dynamic Colors */
        .Business { background-color: #f1c40f; color: black; border: 2px solid #d4af37; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .Economy { background-color: #3498db; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .taken { background-color: #e74c3c !important; cursor: not-allowed; opacity: 0.5; border: none; }
        
        .seat:not(.taken):hover { transform: scale(1.1); cursor: pointer; }

        /* The Aisle (Walking Path) */
        .aisle-spacer { width: 30px; } 
        
        .row-id { width: 30px; font-weight: bold; color: #888; text-align: center; }
        
        .legend { display: flex; gap: 20px; justify-content: center; margin-bottom: 30px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: 14px; }
    </style>
</head>
<body>

<h1 style="text-align:center;">Flight Seat Selection</h1>

<!-- Legend to help users -->
<div class="legend">
    <div class="legend-item"><div class="seat Business" style="width:20px; height:20px;"></div> Business</div>
    <div class="legend-item"><div class="seat Economy" style="width:20px; height:20px;"></div> Economy</div>
    <div class="legend-item"><div class="seat taken" style="width:20px; height:20px;"></div> Booked</div>
</div>

<div class="plane-container">
    <?php if (empty($rows)){ ?>
        <div class="empty-state">
            <h2>No seats available</h2>
            <p>We couldn't find any seat information for Flight ID: <?php echo htmlspecialchars($flight_id); ?></p>
            <p><a href="search.php">Back to Flight List</a></p>
</div>
    <?php } else { ?>
    <?php foreach ($rows as $num => $seats){ ?>
        <div class="seat-row">
            <div class="row-id"><?php echo $num; ?></div>

            <?php 
            foreach ($seats as $index => $s){ 
                if ($index == $aisle_trigger) {
                    echo '<div class="aisle-spacer"></div>';
                }
                $final_price = $s['base_price'] * $multiplier;
                $is_taken = !empty($s['booking_id']);
                $display_class = $is_taken ? 'taken' : $s['class'];
            ?>
                
                <?php if ($is_taken){ ?>
                    <div class="seat taken"><?php echo $s['seat_label']; ?></div>
                <?php } else{ ?>
                       <a href="check_login.php?flight_id=<?php echo $flight_id; ?>&seat=<?php echo $s['seat_label']; ?>&rule_id=<?php echo $price_details['rule_id']; ?>&price=<?php echo $final_price; ?>" 
                       class="seat <?php echo $display_class; ?>">
                        <?php echo $s['seat_label']; ?>
                        
                    </a>
                <?php } ?>

            <?php } ?>
        </div>
    <?php } ?>
    <?php } ?>
</div>

</body>
</html>