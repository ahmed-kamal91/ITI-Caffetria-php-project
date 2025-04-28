<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Orders</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php if(!empty($_SESSION['latestOrder'])){ ?>
<h2 class="text-center my-4">Latest Orders</h2>
<div class="container">
    <div class="row">
        <?php 
        // Loop through the associative array using drinkId as key and $drink as value (the drink object)
        foreach ($_SESSION['latestOrder'] as $drinkId => $drink) { 
        ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card-wrapper">
                    <form action="./../note/addToNote.php" method="post" class="w-100">
                        <!-- Hidden inputs to store the necessary drink information -->
                            <!-- Hidden inputs (outside button!) -->
                            <!-- <input type="hidden" name="drink_id" value="<?php //echo $drink['id']; ?>"> -->
                            <input type="hidden" name="drink_name" value="<?php echo $drink['name']; ?>">
                            <input type="hidden" name="drink_price" value="<?php echo $drink['price']; ?>">
                            <input type="hidden" name="drink_image" value="<?php echo $drink['image_path']; ?>">
                            <input type="hidden" name="drink_available" value="<?php echo $drink['available']; ?>">

                        <!-- Button that submits the drink information -->
                        <button type="submit" name="submitDrink" class="btn p-0 w-100 border-0 bg-transparent">
                            <?php include './../drinks/drinkCard.php'; ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php 
        } 
        ?>
    </div>
</div>
<hr>
<!-- Bootstrap JS & Popper.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"></script>
<?php } ?>
</body>
</html>
