<?php
    session_start();
    $drinkId = $_POST['drinkId']; 

    if ($_SESSION['adminWaiterNote'][$drinkId]['stock'] > 1) {
        $_SESSION['adminWaiterNote'][$drinkId]['stock']--;
        echo "<h2>".$_SESSION['adminWaiterNote'][$drinkId]['stock']."</h2>";
    }
    else{
        unset($_SESSION['adminWaiterNote'][$drinkId]);
    }

    // redirect
    header('Location: ./../renderNote.php');
    exit();

    
    
?>

