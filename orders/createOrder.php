<?php


// connect + query + execute
require_once '../connect.php';

// data needed to create order
$userId = $_SESSION['user_id']||$selectedUserId;
$status = 'Processing';                 // default
$notes = "add 3 sugar please";          // need to be handled later
$totalPrice = $_SESSION['total_price'];

// Create order: query + execute
$sql = "INSERT INTO orders (user_id, status, total, notes) VALUES ($userId, '$status', '$totalPrice', '$notes')";
$result = mysqli_query($connect, $sql);
$orderId = mysqli_insert_id($connect);

// add drinks to the order
foreach ($_SESSION['waiterNote'] as $productId => $productData) {
    
    $quantity = $productData['stock'];  // quantity = stock
    $price = $productData['price'];
    $drinkSql = "INSERT INTO order_drinks (order_id, drink_id, quantity, price) 
                 VALUES ($orderId, $productId, $quantity, $price)";
    
    mysqli_query($connect, $drinkSql);
}

//---add-change-the-latest-order------------------------------------
$_SESSION['latestOrder'] = array_slice($_SESSION['waiterNote'], 0, 4, true);
//------------------------------------------------------------------

// feedback 
echo "Order created successfully. Order ID: " . $orderId;
?>
