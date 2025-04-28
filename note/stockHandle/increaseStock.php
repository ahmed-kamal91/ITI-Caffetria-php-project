<?php
    session_start();
    $drinkId = $_POST['drinkId']; // ← change to POST
    if($_SESSION['waiterNote'][$drinkId]['stock'] < $_SESSION['waiterNote'][$drinkId]['available']){
        $_SESSION['waiterNote'][$drinkId]['stock']++;
    }
    echo "<h2>".$_SESSION['waiterNote'][$drinkId]['stock']."</h2>";


    // recalcuate the total price
    $total = array_reduce($_SESSION['waiterNote'], function($sum, $item) {
    return $sum + ($item['price'] * $item['stock']);}, 0); 
    $_SESSION['total_price'] = $total;

    // redirect
    header('Location: ./../renderNote.php');
    exit();

?>