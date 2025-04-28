<!-- to handle auto scroll to the same place you are in -->
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Waiter Note</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="p-2">

<?php
// Calculate total price and store it
$total = array_reduce($_SESSION['adminWaiterNote'] ?? [], function($sum, $item) {
    return $sum + ($item['price'] * $item['stock']);
}, 0);

$_SESSION['admin_total_price'] = $total;

// Check if waiterNote is not empty
if (!empty($_SESSION['adminWaiterNote'])) {

    // Loop through each drink
    foreach ($_SESSION['adminWaiterNote'] as $drinkId => $drink) {
        // Include the drink card template
        include "./../adminNote/noteCard.php";
    }

} else { ?>
    
    <div class='d-flex flex-column justify-content-center align-items-center mt-4'>
        <i class='fa-solid fa-mug-saucer fa-5x mb-3'></i> 
        <p class='h5'>No drinks added yet!</p>
    </div>

<?php } ?>




<!-- fixed div for totalPrice -->
<div id="fixedFooter" class="fixed-footer bg-primary text-white text-center py-2">
    <h2>Total Price: <?php echo $_SESSION['admin_total_price'] ?></h2>
</div>


<!-- Bootstrap JS (optional for some components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




<!-- STYLEEEEEEEEEEE -->

<style>

    .fixed-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 100;
    }

    body {
        margin: 0;
        padding: 10px;
        overflow-x: hidden; /* prevent horizontal scrolling */
    }
    
    .container, .row, .col, .card, * {
        max-width: 100%;
        box-sizing: border-box; /* important for correct width calculation */
    }


    .small-btn {
        padding-top: 2px;
        padding-bottom: 2px;
        font-size: 14px;
        height: auto;
    }

    .card-wrapper {
        position: relative;
    }
    .price-badge {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 60px;
        height: 60px;
        background-color: #ffec99;
        z-index: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
    }
    .stock-badge {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background-color: rgb(152, 255, 176);
        color: black;
        padding: 5px 10px;
        font-weight: bold;
        border-radius: 20px;
        z-index: 2;
    }
    .card-img-top {
        position: relative;
    }

    .toast-container {
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1050;
    }

    .toast {
        width: 300px;
        background-color: #dc3545;
        color: white;
        border-radius: 0.5rem;
    }

    .loading-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 5px;
        background-color: rgba(255, 255, 255, 0.6);
        animation: loading 3s linear forwards;
    }

    @keyframes loading {
        from { width: 0; }
        to { width: 100%; }
    }

    /* --- Bonus hover effect --- */
    .card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>
