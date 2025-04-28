<?php
session_start();

// remove 
$drinkId= $_POST['drinkId'];
unset($_SESSION['adminWaiterNote'][$drinkId]);

// re-calc totalPrice
$total = array_reduce($_SESSION['adminWaiterNote'] ?? [], function($sum, $item) {
return $sum + ($item['price'] * $item['stock']);}, 0);
$_SESSION['admin_total_price'] = $total;

// redirect
header('location: ./../adminNote/renderNote.php');
exit();
?>