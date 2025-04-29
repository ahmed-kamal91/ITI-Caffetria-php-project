<?php
include_once('../connect.php');

if (isset($_GET['order_id'])) {
    $orderId = mysqli_real_escape_string($connect, $_GET['order_id']);
    
    $query = "SELECT d.id, d.name, d.image_path, od.quantity, od.price 
              FROM order_drinks od
              JOIN drinks d ON od.drink_id = d.id
              WHERE od.order_id = '$orderId'";
    $result = mysqli_query($connect, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $total = 0;
        while ($item = mysqli_fetch_assoc($result)) {
            $subtotal = $item['quantity'] * $item['price'];
            $total += $subtotal;
            
            echo '<div class="drink-card">
                    <img src="../'.$item['image_path'].'" class="drink-img" alt="'.$item['name'].'">
                    <div class="drink-info">
                        <div class="fw-bold">'.$item['name'].'</div>
                        <div class="text-muted small">Quantity: '.$item['quantity'].'</div>
                        <div class="text-muted small">Price: $'.number_format($item['price'], 2).' each</div>
                    </div>
                    <div class="drink-total">
                        $'.number_format($subtotal, 2).'
                    </div>
                  </div>';
        }
        
        echo '<div class="p-3 text-end border-top">
                <span class="order-total">Order Total: $'.number_format($total, 2).'</span>
              </div>';
    } else {
        echo '<div class="alert alert-info p-3">No items found for this order</div>';
    }
}

mysqli_close($connect);
?>