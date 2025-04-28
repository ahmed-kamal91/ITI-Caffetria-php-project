<?php
session_start();


// connect + query + execute
include_once "./../connect.php";

// data needed to create order
$userId = $_SESSION['user_id'];
$status = 'Processing';                 // default
$notes = "add 35 sugar please";          // need to be handled later
$totalPrice = $_SESSION['admin_total_price'];

// Create order: query + execute
$sql = "INSERT INTO orders (user_id, status, total, notes) VALUES ($userId, '$status', '$totalPrice', '$notes')";
$result = mysqli_query($connect, $sql);
$orderId = mysqli_insert_id($connect);

// add drinks to the order
foreach ($_SESSION['adminWaiterNote'] as $productId => $productData) {
    
    $quantity = $productData['stock'];  // quantity = stock
    $price = $productData['price'];
    $drinkSql = "INSERT INTO order_drinks (order_id, drink_id, quantity, price) 
                 VALUES ($orderId, $productId, $quantity, $price)";
    
    mysqli_query($connect, $drinkSql);
}


// feedback 
echo "Order created successfully. Order ID: " . $orderId;
?>
