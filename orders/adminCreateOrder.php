<?php

// connect + query + execute
include_once "./../connect.php";

// Check if necessary session variables are set
if (!isset($_SESSION['user_id_a']) || !isset($_SESSION['admin_total_price']) || !isset($_SESSION['adminWaiterNote'])) {
    die("Missing session data.");
}

// Escape values properly
$userId = (int) $_SESSION['user_id_a'];
$status = 'Processing';
$notes = mysqli_real_escape_string($connect, "add 35 sugar please");
$totalPrice = (float) $_SESSION['admin_total_price'];

// Create order: query + execute
$sql = "INSERT INTO orders (user_id, status, total, notes) VALUES ($userId, '$status', $totalPrice, '$notes')";
$result = mysqli_query($connect, $sql);

if (!$result) {
    die("Error creating order: " . mysqli_error($connect));
}

$orderId = mysqli_insert_id($connect);

// Add drinks to the order
foreach ($_SESSION['adminWaiterNote'] as $productId => $productData) {
    $quantity = (int) $productData['stock'];
    $price = (float) $productData['price'];
    
    $drinkSql = "INSERT INTO order_drinks (order_id, drink_id, quantity, price) 
                 VALUES ($orderId, $productId, $quantity, $price)";
    
    mysqli_query($connect, $drinkSql);
}

// Clear the session if needed (optional)
// unset($_SESSION['adminWaiterNote']); 

// Feedback
echo "Order created successfully. Order ID: " . $orderId;
?>
