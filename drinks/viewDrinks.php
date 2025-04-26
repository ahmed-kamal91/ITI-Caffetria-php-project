<?php
// connect + query + Execute
include_once './../connect.php';
$sql = "SELECT * FROM drinks";
$result = mysqli_query($connect,$sql);
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
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Toast for out-of-stock drinks -->
    <?php if (isset($_GET['out_of_stock'])): ?>
    <div class="toast-container">
        <div class="toast align-items-center show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex position-relative">
                <div class="toast-body">
                    This drink is out of stock!
                    <div class="loading-bar"></div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="container">
        <div class="row">
            <?php while ($drink = mysqli_fetch_assoc($result)) { ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card-wrapper">
                        <span class="price-badge rounded-5 d-flex justify-content-center align-items-center fw-bold text-dark"><?php echo $drink['price'] ?> $</span>
                        <form action="./../note/addToNote.php" method="post">
    <button class='btn btn-none w-100 text-start' type="submit" name="submitDrink" value="<?php echo $drink['id']; ?>">
        <div class="card rounded-5 p-2 shadow-sm">
            <div class="position-relative">
                <img src="<?php echo './../' . $drink['image_path']; ?>" class="card-img-top rounded-5 border border-light bg-light" alt="<?php echo $drink['name']; ?>">
                <?php if ($drink['available']) { ?>
                    <span class="stock-badge">
                        <i class="fa-solid fa-wine-glass"></i>
                        <?php echo $drink['available']; ?>
                    </span>
                <?php } else { ?>
                    <span class="stock-badge bg-danger text-white">
                        <i class="fa-solid fa-wine-glass-empty"></i>
                        out
                    </span>
                <?php } ?>
            </div>
            <div class="card-body">
                <h5 class="card-title text-center"><?php echo $drink['name']; ?></h5>
            </div>
        </div>
        <input type="hidden" name="drink_id" value="<?php echo $drink['id']; ?>">
        <input type="hidden" name="drink_name" value="<?php echo $drink['name']; ?>">
        <input type="hidden" name="drink_price" value="<?php echo $drink['price']; ?>">
        <input type="hidden" name="drink_image" value="<?php echo $drink['image_path']; ?>">
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
