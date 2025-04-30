<!-- sdebar -->
<?php 
 echo "<div class='container d-flex w-100'>";
 include_once './sidebar.php'; ?> 

<?php
include_once '../connect.php';
include_once './H_viewMyOrder/getUserInfo.php';  
include_once './H_viewMyOrder/getDrinks.php';   

echo "<div class='container d-flex flex-column'>";


// TITLE
echo "<h1 class='container w-100 '> <i class='fa-solid fa-ticket text-primary'></i> My Order</h1>";

// init pagination
$limit =  5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

/* filter form
----------------------------------------*/
include './H_viewMyOrder/filterByDate.php';

// prepare filter
$filterClause = '';
$filterConditions = [];

// Manual validation for the 'from_date' input
if (!empty($_GET['from_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from_date'])) {
    $fromDate = $_GET['from_date'];
    $filterConditions[] = "DATE(created_at) >= '$fromDate'";
}

// Manual validation for the 'to_date' input
if (!empty($_GET['to_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to_date'])) {
    $toDate = $_GET['to_date'];
    $filterConditions[] = "DATE(created_at) <= '$toDate'";
}

if (!empty($filterConditions)) {
    $filterClause = 'WHERE ' . implode(' AND ', $filterConditions);
}

/* get total number of orders after filter
-------------------------------------------------------------------------------------*/
$totalOrdersQuery = 'SELECT COUNT(*) AS totalNum FROM orders ' . $filterClause;
$totalOrdersResult = mysqli_query($connect, $totalOrdersQuery);
$totalOrdersRow    = mysqli_fetch_assoc($totalOrdersResult);
$totalOrders       = $totalOrdersRow['totalNum'];
$totalPages        = ceil($totalOrders / $limit);

/* current page query with filter applied
----------------------------------------------------------------------------------*/
$ordersQuery = "SELECT * FROM orders $filterClause ORDER BY id DESC LIMIT $limit OFFSET $offset";
$ordersResult = mysqli_query($connect, $ordersQuery);

/* view orders in accordion
---------------------------------------------------------------------------*/
echo "<div class='container'><div class='accordion' id='accordionExample'>";
while ($orders = mysqli_fetch_assoc($ordersResult)) {
    // Fetch user info and drinks for each order
    $userInfo = getUserInfo($orders['user_id'], $connect);  // Fetch user name and email
    $drinks = getDrinks($orders['id'], $connect);  // Fetch the drinks for this order
    
    // Include accordion item with user info and drinks
    include './H_viewMyOrder/accordionOrder.php';
}
echo "</div></div>";

/* pagination view
----------------------------------------*/
include './H_viewMyOrder/pagination.php';
    echo '</div>';

echo '</div>';

?>
<head>
  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6.5.1 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap Bundle JS (with Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>