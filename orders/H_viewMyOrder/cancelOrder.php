<?php
include_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get order id
    $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    if ($orderId) {
        // update query to change status to 'cancelled'
        $updateQuery = "UPDATE orders SET status = 'cancelled' WHERE id = $orderId";
        if (mysqli_query($connect, $updateQuery)) {
            // redirect 
            $redirectUrl = $_SERVER['HTTP_REFERER']; //<=== GET THE TYPICAL URL
            header("Location: $redirectUrl");
            exit();
        } else {echo "couldn't update the order status.";}
    }
}
?>
