<?php
include_once '../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $query = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    mysqli_query($connect, $query);

    // Optional: simple message (can be removed if not needed)
    echo "Order #$order_id status updated to $status";

    $redirectUrl = $_SERVER['HTTP_REFERER']; //<=== GET THE TYPICAL URL
    header("Location: $redirectUrl");
    exit();
}
?>
