<?php
include_once './../connect.php';

// Handle chosen user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['choseUser'])) {
    $_SESSION['user_id_a'] = (int)$_POST['choseUser'];
    
    ///////////////////ERROR////////////////////////////////////////
    // header("Location: " . $_SERVER['PHP_SELF']);
    // exit;
    ////////////////////////////////////////////////////////////////
}

// PAGINATION
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SEARCH
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchValue = mysqli_real_escape_string($connect, $_GET['search']);
    $searchQuery = " WHERE name LIKE '%$searchValue%'";
}

// GET total number of drinks
$totalQuery = "SELECT COUNT(*) AS total FROM drinks" . $searchQuery;
$totalResult = mysqli_query($connect, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalDrinks = $totalRow['total'];
$totalPages = ceil($totalDrinks / $limit);

// GET drinks
$sql = "SELECT * FROM drinks" . $searchQuery . " LIMIT $limit OFFSET $offset";
$result = mysqli_query($connect, $sql);

// GET users
$userSql = "SELECT * FROM users";
$userResult = mysqli_query($connect, $userSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Available Drinks</title>
    <style>
        .card-wrapper { position: relative; }
        .price-badge { position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background-color: #ffec99; z-index: 1; display: flex; justify-content: center; align-items: center; font-weight: bold; }
        .stock-badge { position: absolute; bottom: 10px; left: 10px; background-color: rgb(152, 255, 176); color: black; padding: 5px 10px; font-weight: bold; border-radius: 20px; z-index: 2; }
        .card-img-top { position: relative; }
        .hidden-section { display: none; }
    </style>
</head>
<body>

<div class="container border text-center">
    <h2>Admin Choose User</h2>

    <!-- User Dropdown -->
    <form action="" method="POST" class="mb-4">
        <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
            <select name="choseUser" class="form-select w-auto" required>   
                <option value="">Choose User</option>                         
                <?php while($user = mysqli_fetch_assoc($userResult)): ?>
                    <option value="<?php echo $user['id']; ?>">
                        <?php echo $user['id'] . ':' . htmlspecialchars($user['name']) . ':' . htmlspecialchars($user['email']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-success">Select User</button>
        </div>
    </form>

    <!-- Search -->
    <form action="" method="GET" class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-4">
        <input 
            type="text" 
            name="search" 
            class="form-control w-50" 
            placeholder="Search drinks..." 
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
        >
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Search
        </button>
    </form>

    <!-- Drinks -->
    <div class="row d-flex justify-content-center">
        <?php while ($drink = mysqli_fetch_assoc($result)): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card-wrapper">
                    <form action="./../adminNote/addToNote.php" method="post" class="w-100">
                        <input type="hidden" name="drink_id" value="<?php echo $drink['id']; ?>">
                        <input type="hidden" name="drink_name" value="<?php echo htmlspecialchars($drink['name']); ?>">
                        <input type="hidden" name="drink_price" value="<?php echo $drink['price']; ?>">
                        <input type="hidden" name="drink_image" value="<?php echo htmlspecialchars($drink['image_path']); ?>">
                        <input type="hidden" name="drink_available" value="<?php echo $drink['available']; ?>">

                        <button type="submit" name="submitDrink" class="btn p-0 w-100 border-0 bg-transparent">
                            <?php include "./../drinks/drinkCard.php"; ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo isset($_GET['search']) ? urlencode($_GET['search']) : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"></script>

</body>
</html>
