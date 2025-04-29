<?php
// START SESSION
session_start(); 


function handleDrinkNotAvailable($drink_id){
    include './../connect.php';
    $query = "SELECT available FROM drinks WHERE id = $drink_id LIMIT 1";
    $result = mysqli_query($connect, $query);
    $drink = mysqli_fetch_assoc($result);
    if ($drink['available'] == 0) {
        header("Location: ./../pages/adminMainPage.php?out_of_stock=1");
        exit();
    }
}


function addDrink($drink_id){
    // id exist
    if(isset($_SESSION['adminWaiterNote'][$drink_id])){
        // increase the stock
        $_SESSION['adminWaiterNote'][$drink_id]['stock'] += 1;
    }else{
        //add drink
        $productInfo = [ 
            'stock' => 1, 'available' => $_POST['drink_available'], 'name' => $_POST['drink_name'], //<==look here for available
            'price' => $_POST['drink_price'],'image_path' => $_POST['drink_image']];
        $_SESSION['adminWaiterNote'][$drink_id] = $productInfo;

        // calculate the total price
        $total = array_reduce($_SESSION['adminWaiterNote'], function($sum, $item) {
            return $sum + ($item['price'] * $item['stock']);}, 0); 
            $_SESSION['admin_total_price'] = $total;

    }
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $drink_id = $_POST['drink_id'];

    // check drink availble
    handleDrinkNotAvailable($drink_id);

    // add drink to note
    addDrink($drink_id);

    // redirect to user main page
    header("location: ./../pages/adminMainPage.php");
    exit();
}
?>
