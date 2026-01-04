<?php
require_once "database.php";

session_start();


if (isset($_GET['pay_id']) && isset($_SESSION['admin_id'])) {
    $payment_id = (int)$_GET['pay_id'];
    $admin_id = (int)$_SESSION['admin_id'];

  $conn->begin_transaction();
  try {
    $sql1 = "UPDATE payment SET pay_status='Confirmed', admin_id = ?, confirmed_at = NOW() WHERE payment_id =?";
    $stmt_admin = $conn->prepare($sql1);
    $stmt_admin->bind_param("ii", $admin_id, $payment_id);
    $stmt_admin->execute();
    

    $sql2 = "UPDATE booking SET pay_status='Confirmed' WHERE booking_id= (SELECT booking_id FROM payment WHERE payment_id=?)";
    $stmt_book = $conn->prepare($sql2);
    $stmt_book->bind_param("i", $payment_id);
    $stmt_book->execute();
    

    $conn->commit();

    header("Location: pay_dashboard.php?msg=Confirmed");
    exit();

  } catch (Exception $e) {
          $conn->rollback();
          die("Transaction Failed: " . $e->getMessage());
      }
} else {
    die("Unauthorized Access.");
}
?>