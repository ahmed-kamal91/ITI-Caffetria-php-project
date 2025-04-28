<?php
session_start();

// remove 
$drinkId= $_POST['drinkId'];
unset($_SESSION['waiterNote'][$drinkId]);

// re-calc totalPrice
$total = array_reduce($_SESSION['waiterNote'] ?? [], function($sum, $item) {
return $sum + ($item['price'] * $item['stock']);}, 0);
$_SESSION['total_price'] = $total;

// redirect
header('location: ./../note/renderNote.php');
exit();
?>