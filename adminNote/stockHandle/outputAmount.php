<?php
session_start();
$drinkId = $_GET['drinkId']; // Get the drinkId from the URL
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center">

    <?php 
    // Check if the drink exists in the session before displaying the stock
    if (isset($_SESSION['adminWaiterNote'][$drinkId])) {
        echo "<h1>" . $_SESSION['adminWaiterNote'][$drinkId]['stock'] . "</h1>";
    } 
    ?>

</body>
</html>
