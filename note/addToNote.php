<?php
include './../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drink_id = $_POST['drink_id'];

    $query = "SELECT available FROM drinks WHERE id = $drink_id LIMIT 1";
    $result = mysqli_query($connect, $query);
    $drink = mysqli_fetch_assoc($result);

    if ($drink['available'] == 0) {
        header("Location: ./../drinks/viewDrinks.php?out_of_stock=1");
        exit();
    }

    // Add to note logic here...
   echo '<br>';
   print_r($_POST);
    // header("Location: ./../drinks/viewDrinks.php");
    // exit();
}
