<?php
    session_start();
    $drinkId = $_POST['drinkId']; 

    if ($_SESSION['waiterNote'][$drinkId]['stock'] > 1) {
        $_SESSION['waiterNote'][$drinkId]['stock']--;
        echo "<h2>".$_SESSION['waiterNote'][$drinkId]['stock']."</h2>";
    }
    else{
        unset($_SESSION['waiterNote'][$drinkId]);
    }

    // redirect
    header('Location: ./../renderNote.php');
    exit();

    
    
?>

