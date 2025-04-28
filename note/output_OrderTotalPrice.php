

<html lang="en">
<head>

</head>
<body>
    <h1>
    <?php 
        session_start();
        echo 'Total Price: '.$_SESSION['total_price']; 
    ?>
    </h1>
</body>
</html>