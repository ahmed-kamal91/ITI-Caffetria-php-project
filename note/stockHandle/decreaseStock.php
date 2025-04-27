<?php
    session_start();
    $drinkId = $_POST['drinkId']; // ← change to POST
    if($_SESSION['waiterNote'][$drinkId]['stock']>0){
        $_SESSION['waiterNote'][$drinkId]['stock']--;
    }
    echo "<h2>".$_SESSION['waiterNote'][$drinkId]['stock']."</h2>";
?>