<!-- to auto scroll to the same part -->
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



<?php
session_start();
//   unset($_SESSION['waiterNote']); //for development

if(!isset($_SESSION['waiterNote'])) {
    $_SESSION['waiterNote'] = []; 
}

// for current order
if (isset($_POST['createOrderBtn'])) {

    // LOGIC OF CREATING THE ORDER HERE
    
    $_SESSION['order_created'] = true; // set a flag
    header("Location: " . $_SERVER['PHP_SELF']); // redirect to same page
    exit();
}

?>


<html lang="en">
    <head>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#waiterNote'>click</button>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"></script>
    </body>
</html>




<?php
// listing items + add items by click on the note
include_once "./../drinks/viewDrinks.php";
print_r($_SESSION);
?>


<?php
include_once "./../note/NoteModal.php";
?>


<!-- green toast for the current order button -->
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


<!-- variable for the current order -->
<?php
// Unset the flag so it doesn't appear again
if (isset($_SESSION['order_created'])) {
    unset($_SESSION['order_created']);
}
?>
