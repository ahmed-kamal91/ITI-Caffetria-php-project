<?php
function getDrinks($orderId, $connect) {
    $query = "SELECT drinks.name AS drink_name FROM order_drinks
              JOIN drinks ON order_drinks.drink_id = drinks.id
              WHERE order_drinks.order_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $drinks = [];
    while ($row = $result->fetch_assoc()) {
        $drinks[] = $row['drink_name'];
    }
    
    return $drinks; // Return an array of drinks names
}
?>
