<?php
// connect + query + Execute
include_once './../connect.php';
$sql = "SELECT * FROM drinks";
$result = mysqli_query($connect, $sql);
?>

<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Available Drinks</title>
    <style>
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
</head>
<body>

    <!-- Toast: [path related to : redToast.php] -->
    <?php if (isset($_GET['out_of_stock'])): include './../drinks/redToast.php'; endif; ?>

    <div class="container">
        <div class="row">
            <?php while ($drink = mysqli_fetch_assoc($result)) { ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card-wrapper">

                        <form action="./../note/addToNote.php" method="post" class="w-100">
                            <!-- Hidden inputs (outside button!) -->
                            <input type="hidden" name="drink_id" value="<?php echo $drink['id']; ?>">
                            <input type="hidden" name="drink_name" value="<?php echo $drink['name']; ?>">
                            <input type="hidden" name="drink_price" value="<?php echo $drink['price']; ?>">
                            <input type="hidden" name="drink_image" value="<?php echo $drink['image_path']; ?>">
                            <input type="hidden" name="drink_available" value="<?php echo $drink['available']; ?>">

                            <button type="submit" name="submitDrink" class="btn p-0 w-100 border-0 bg-transparent">
                                <!-- drink card [path related to : userMainPage.php] -->
                                <?php include "./../drinks/drinkCard.php"; ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"></script>
</body>
</html>
