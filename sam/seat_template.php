<?php
require_once "database.php";


function generateTemplate($conn, $template_id,$rows,$letters,  $bis_rows){
   $checkStmt = $conn->prepare("SELECT template_id FROM seat_template WHERE template_id = ? LIMIT 1");
    $checkStmt->bind_param("i", $template_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "Template $template_id already exists. Skipping generation.<br>";
        $checkStmt->close();
        return; 
    }
    $checkStmt->close();

    
  $stmt = $conn->prepare("INSERT INTO seat_template(template_id, seat_label,class, base_price, capacity)
                         VALUES(?,?,?,?,?)");
  $total_seats = $rows*count($letters);
  for ($r = 1; $r <= $rows; $r++) {
        
        if ($r == 13) continue; 

        foreach ($letters as $l) {
            $seat_label = $r . $l;
            
            if ($r <= $bis_rows) {
                $class = "Business";
                $price = 500.00;
            } else {
                $class = "Economy";
                $price = 200.00;
            }

            $stmt->bind_param("isssi", $template_id, $seat_label, $class, $price, $total_seats);
            $stmt->execute();
            
        }
    }
    echo "Template $template_id generated ($total_seats seats).<br>";
    $stmt->close();
}


generateTemplate($conn, 10, 30, ['A', 'B', 'C', 'D', 'E',"F"], 3);


generateTemplate($conn, 20, 15, ['A', 'B', 'C', 'D'], 2);

generateTemplate($conn, 30, 20, ['A', 'B', 'C', 'D', 'E'], 3);


$conn->close();
?>


