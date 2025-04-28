<?php
    session_start();
    $drinkId = $_POST['drinkId']; // ← change to POST
    if($_SESSION['adminWaiterNote'][$drinkId]['stock'] < $_SESSION['adminWaiterNote'][$drinkId]['available']){
        $_SESSION['adminWaiterNote'][$drinkId]['stock']++;
    }
    echo "<h2>".$_SESSION['adminWaiterNote'][$drinkId]['stock']."</h2>";


    // recalcuate the total price
    $total = array_reduce($_SESSION['adminWaiterNote'], function($sum, $item) {
    return $sum + ($item['price'] * $item['stock']);}, 0); 
    $_SESSION['admin_total_price'] = $total;

    // redirect
    header('Location: ./../renderNote.php');
    exit();

?>