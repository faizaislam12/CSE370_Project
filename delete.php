<?php 
    if(isset($_GET['flight_id']))
        {
        $flight_id = $_GET["flight_id"];
        include "connection.php";
        $sql = "DELETE from `flight` where `flight_id`='$flight_id'";
        $execute = mysqli_query($con, $sql);   
        
        if(!$execute) { 
            header("location: /sam/index.php?error=invalid_query");
            $errorMessage =  "Invalid query!";
            exit; 
            }
        
    else{
        header("location: /sam/index.php?msg=deleted");
        echo "Deleted successfully";
        exit;    
    }
}
      
?>