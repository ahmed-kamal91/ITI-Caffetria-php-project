<!-- 

 CONTENT: 
 ========
 1- LOGIN
 2- AUTO SCROLL
 3- ORDER CREATION [embedded] + (handle latest order)

 ----main work-------------------------------
 4- NOTE BUTTON + BS + FONT-AUSOME
 5- LIST DRINKS + ADD TO WAITER NOTE BY CLICK
 6- NOTE MODAL
 --------------------------------------------

 7- GREEN TOAST: ORDER CREATED
 8- HANDLING ORDER TOAST APPEAEANCE
 -->


<!-- LOGIN -->
<?php
session_start();
//   unset($_SESSION['waiterNote']); //for development

// LOGIN [SIMULATION]---------------------------------------------------
$_SESSION['user_id'] = "4";             // id from database [read]
//----------------------------
$_SESSION['user_name'] = "ali";         // name saved based on database
$_SESSION['user_role'] = "customer";    // role saved based on db
//----------------------------------------------------------------------
?>




<!-- AUTO SCROLL -->
<script>
window.addEventListener('beforeunload', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});

window.addEventListener('load', function() {
    const scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition !== null) {
        // Temporarily disable smooth scrolling
        document.documentElement.style.scrollBehavior = "auto";
        
        window.scrollTo(0, scrollPosition);
        
        // Re-enable smooth scrolling (optional)
        setTimeout(() => {
            document.documentElement.style.scrollBehavior = "";
        }, 100); // after 100ms
    }
});
</script>


<!-- ORDER CREATION -->
<?php

if(!isset($_SESSION['adminWaiterNote'])) {
    $_SESSION['adminWaiterNote'] = []; 
}

// for current order
if (isset($_POST['createOrderBtn'])) {

    // LOGIC OF CREATING THE ORDER HERE
    include './../orders/adminCreateOrder.php';
    
    $_SESSION['order_created'] = true;              // set a flag
    header("Location: " . $_SERVER['PHP_SELF']);    // redirect to same page
    exit();
}

?>

<!-- NOTE BUTTON + BS + FONT-AUSOME -->
<?php
include_once './../header.php'
?>



 <!-- LIST DRINKS + ADD TO WAITER NOTE BY CLICK-->

<?php
include_once "./../drinks/adminViewUserDrinks.php";
?>

<!-- NOTE MODAL -->
<?php
include_once "./../adminNote/NoteModal.php"; //<====HERE=====
?>


<!-- GREEN TOAST: ORDER CREATED -->
<div class="position-fixed bottom-0 start-0 p-3" style="z-index: 1055;">
    <div id="orderToast" class="toast align-items-center text-white bg-success border-0 <?php echo isset($_SESSION['order_created']) ? 'show' : ''; ?>" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Order created successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>


<!-- HANDLING ORDER TOAST APPEAEANCE -->
<?php
// Unset the flag so it doesn't appear again
if (isset($_SESSION['order_created'])) {
    unset($_SESSION['order_created']);
}
?>
