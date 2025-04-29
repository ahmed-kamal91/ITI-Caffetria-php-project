<?php
include_once('../connect.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    
    // Check if drink exists first
    $check_query = "SELECT id FROM drinks WHERE id = $id";
    $check_result = mysqli_query($connect, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Delete the drink
        $delete_query = "DELETE FROM drinks WHERE id = $id";
        $delete_result = mysqli_query($connect, $delete_query);
        
        if ($delete_result) {
            header("Location: adminViewDrinks.php?page=$page&success=Drink deleted successfully");
            exit();
        } else {
            header("Location: adminViewDrinks.php?page=$page&error=Error deleting drink: " . mysqli_error($connect));
            exit();
        }
    } else {
        header("Location: adminViewDrinks.php");
        exit();
    }
} else {
    header("Location: adminViewDrinks.php");
    exit();
}
// header(location:);
// mysqli_close($connect);
?>
