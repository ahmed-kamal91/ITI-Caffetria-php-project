<?php

if(!isset($_SESSION['latestOrder'])){
    $_SESSION['latestOrder'] = [];
}

// Connect to the database
include_once './../connect.php';

// Pagination settings
$limit = 8;  // Number of drinks per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search query
$searchQuery = '';
$searchValue = '';
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $searchQuery = " WHERE name LIKE '%$search%'";
}

// Get the total number of drinks
$totalQuery = "SELECT COUNT(*) AS total FROM drinks" . $searchQuery;
$totalResult = mysqli_query($connect, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalDrinks = $totalRow['total'];
$totalPages = ceil($totalDrinks / $limit);

// Get drinks based on search and pagination
$sql = "SELECT * FROM drinks" . $searchQuery . " LIMIT $limit OFFSET $offset";
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
        .hidden-section {
            display: none;
        }
    </style>
    <script>
        function toggleLatestOrder() {
            var latestOrderSection = document.getElementById("latestOrderSection");
            var searchSection = document.getElementById("searchSection");

            // Toggle visibility
            latestOrderSection.classList.toggle("hidden-section");
            searchSection.classList.toggle("hidden-section");
        }
    </script>
</head>
<body class='bg-light'>

    <!-- Toast: [path related to : redToast.php] -->
    <?php if (isset($_GET['out_of_stock'])): include './../drinks/redToast.php'; endif; ?>

    <div class="container border text-center bg-white">

        <!-- Latest order section -->
        <?php 
            ////////////////////////LATEST/ORDER////////////////////////////
            if (!isset($_GET['search']) || empty($_GET['search'])) {
                include './../drinks/DrinksLatestOrderSection.php';
            }
            ////////////////////////////////////////////////////////////////
        ?>

        <!-- Search form -->
        <div class="row mb-4" id="searchSection">
            <div class="col-12">
                <form action="" method="get" class="d-flex justify-content-center">
                    <input type="text" name="search" class="form-control w-50" placeholder="Search drinks..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
            </div>
        </div>

        <!-- Button to show latest order again -->
        <?php if (isset($_GET['search']) && !empty($_GET['search'])) { ?>
            <button onclick="toggleLatestOrder()" class="btn btn-secondary mb-4">Show Latest Orders</button>
        <?php } ?>

        <!-- Display drinks -->
        <div class="row d-flex justify-content-center">
            <?php while ($drink = mysqli_fetch_assoc($result)) { ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card-wrapper">
                        <form action="./../note/addToNote.php" method="post" class="w-100">
                            <!-- Hidden inputs -->
                            <input type="hidden" name="drink_id" value="<?php echo $drink['id']; ?>">
                            <input type="hidden" name="drink_name" value="<?php echo $drink['name']; ?>">
                            <input type="hidden" name="drink_price" value="<?php echo $drink['price']; ?>">
                            <input type="hidden" name="drink_image" value="<?php echo $drink['image_path']; ?>">
                            <input type="hidden" name="drink_available" value="<?php echo $drink['available']; ?>">

                            <button type="submit" name="submitDrink" class="btn p-0 w-100 border-0 bg-transparent">
                                <?php include "./../drinks/drinkCard.php"; ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"></script>
</body>
</html>
