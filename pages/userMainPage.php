<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location:../user/login.php");
    exit();
}

// Initialize waiterNote if not set
if (!isset($_SESSION['waiterNote'])) {
    $_SESSION['waiterNote'] = []; 
}

// Handle order creation
if (isset($_POST['createOrderBtn'])) {
    include './../orders/createOrder.php';
    $_SESSION['order_created'] = true; // Set toast flag
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh to avoid resubmission
    exit();
}

// Get user name (fallback to 'test')
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'test';
?>

<?php include('../orders/header.php'); ?>
<?php include_once './../header.php'; ?>
<?php include_once "./../drinks/viewDrinks.php"; ?>
<?php include_once "./../note/NoteModal.php"; ?>

<!-- AUTO SCROLL -->
<script>
window.addEventListener('beforeunload', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});
window.addEventListener('load', function() {
    const scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition !== null) {
        document.documentElement.style.scrollBehavior = "auto";
        window.scrollTo(0, scrollPosition);
        setTimeout(() => {
            document.documentElement.style.scrollBehavior = "";
        }, 100);
    }
});
</script>

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

<?php
// Unset the flag so toast doesn't show again
if (isset($_SESSION['order_created'])) {
    unset($_SESSION['order_created']);
}

include('../orders/footer.php');
?>