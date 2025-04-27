<?php 
    session_start();
    $drinkId = $_GET['drinkId'];
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center">

    <h1><?php echo $_SESSION['waiterNote'][$drinkId]['stock']; ?></h1>

</body>
</html>
