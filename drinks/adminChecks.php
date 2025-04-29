<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users, Orders, and Drinks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 1500px !important; /* Ensure container is wide enough for accordion */
        }
        .accordion {
            width: 700px; /* Fixed width */
            margin: 0 auto;
        }
        /* Responsive adjustments */
        @media (max-width: 1500px) {
            .accordion {
                width: 1200px;
            }
        }
        @media (max-width: 1280px) {
            .accordion {
                width: 1000px;
            }
        }
        @media (max-width: 1024px) {
            .accordion {
                width: 800px;
            }
        }
        @media (max-width: 576px) {
            .accordion {
                width: 100%;
            }
        }
        .accordion-button {
            font-weight: bold;
            background-color:rgb(116, 119, 121); /* Light teal background for headers */
            color: #333; /* Ensure text is readable */
        }
        /* Ensure the color persists when the accordion is expanded */
        .accordion-button:not(.collapsed) {
            background-color:rgb(116, 119, 121); /* Same light teal when expanded */
            color: #333;
        }
        .accordion-body {
            background-color: #ffffff;
            border-radius: 5px;
            padding: 15px;
        }
        .user-item, .order-item, .drink-item {
            display: block; /* Ensure vertical stacking of items */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            transition: background-color 0.2s;
            text-align: left;
            text-decoration: none;
            color: #333;
        }
        .user-item:hover, .order-item:hover, .drink-item:hover {
            background-color: #e9ecef;
        }
        .user-item .user-info, .order-item .order-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-item .user-name, .order-item .order-date {
            font-weight: bold;
        }
        .user-item .user-total, .order-item .order-total {
            color: #007bff;
        }
        .drink-item {
            display: flex; /* Use flexbox to place details and image side by side */
            align-items: center; /* Vertically center the content */
            justify-content: space-between; /* Space out details and image */
            gap: 15px; /* Space between details and image */
        }
        .drink-details {
            flex-grow: 1; /* Details take remaining space */
        }
        .drink-image-container {
            flex-shrink: 0; /* Image container doesn't shrink */
            text-align: right; /* Align image to the right */
        }
        .drink-item p {
            margin: 2px 0;
            font-size: 16px;
            line-height: 1.4;
        }
        .drink-item strong {
            display: inline-block;
            width: 100px;
            font-weight: 700;
            color: #333;
        }
        .drink-item span {
            color: #555;
        }
        .drink-item img {
            max-width: 100px; /* Limit image size */
            height: auto;
            border-radius: 5px; /* Rounded corners for image */
        }
        /* Responsive adjustment for smaller screens */
        @media (max-width: 576px) {
            .drink-item {
                flex-direction: column; /* Stack vertically on small screens */
                align-items: flex-start;
            }
            .drink-image-container {
                text-align: left; /* Align image to the left on small screens */
            }
        }
        /* Style for the date filter form */
        .date-filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text mb-4">Checks</h1>
        <div class="accordion" id="mainAccordion">
            <!-- First Accordion: Users -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="usersHeading">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#usersCollapse" aria-expanded="true" aria-controls="usersCollapse">
                        Users
                    </button>
                </h2>
                <div id="usersCollapse" class="accordion-collapse collapse show" aria-labelledby="usersHeading" data-bs-parent="#mainAccordion">
                    <div class="accordion-body">
                        <?php
                        // Include database connection
                        include_once('../connect.php');

                        // Fetch users with their total order amounts
                        $users_query = "SELECT u.id, u.name, COALESCE(SUM(o.total), 0) as total_amount
                                        FROM users u
                                        LEFT JOIN orders o ON u.id = o.user_id
                                        GROUP BY u.id, u.name";
                        $users_result = mysqli_query($connect, $users_query);

                        if (mysqli_num_rows($users_result) > 0) {
                            while ($user = mysqli_fetch_assoc($users_result)) {
                                // Link to select user
                                $user_link = '?user_id=' . $user['id'];
                                echo '<a href="' . htmlspecialchars($user_link) . '" class="user-item">';
                                echo '<div class="user-info">';
                                echo '<span class="user-name">' . htmlspecialchars($user['name']) . '</span>';
                                echo '<span class="user-total">$' . number_format($user['total_amount'], 2) . '</span>';
                                echo '</div>';
                                echo '</a>';
                            }
                        } else {
                            echo '<p>No users found.</p>';
                        }
                        mysqli_free_result($users_result);
                        ?>
                    </div>
                </div>
            </div>

            <!-- Date Filter Form -->
            <?php
            $date_from = isset($_GET['date_from']) ? htmlspecialchars($_GET['date_from']) : '';
            $date_to = isset($_GET['date_to']) ? htmlspecialchars($_GET['date_to']) : '';
            $user_id = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? intval($_GET['user_id']) : null;
            ?>
            <div class="date-filter-form">
                <form method="GET" action="adminChecks.php" class="row g-3 align-items-end">
                    <!-- Preserve user_id in the form -->
                    <?php if ($user_id): ?>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <?php endif; ?>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-3">
                        <a href="adminChecks.php<?php echo $user_id ? '?user_id=' . $user_id : ''; ?>" class="btn btn-secondary w-100">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Second Accordion: Orders -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="ordersHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ordersCollapse" aria-expanded="false" aria-controls="ordersCollapse">
                        Orders
                    </button>
                </h2>
                <div id="ordersCollapse" class="accordion-collapse collapse<?php echo isset($_GET['user_id']) ? ' show' : ''; ?>" aria-labelledby="ordersHeading" data-bs-parent="#mainAccordion">
                    <div class="accordion-body">
                        <?php
                        if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
                            $user_id = intval($_GET['user_id']);
                            // Base query for orders
                            $orders_query = "SELECT id, created_at, total FROM orders WHERE user_id = $user_id";
                            
                            // Add date filters if provided
                            $conditions = [];
                            if (!empty($date_from)) {
                                $conditions[] = "created_at >= '" . mysqli_real_escape_string($connect, $date_from) . " 00:00:00'";
                            }
                            if (!empty($date_to)) {
                                $conditions[] = "created_at <= '" . mysqli_real_escape_string($connect, $date_to) . " 23:59:59'";
                            }
                            if (!empty($conditions)) {
                                $orders_query .= " AND " . implode(" AND ", $conditions);
                            }
                            $orders_query .= " ORDER BY created_at DESC";
                            
                            $orders_result = mysqli_query($connect, $orders_query);

                            if (mysqli_num_rows($orders_result) > 0) {
                                while ($order = mysqli_fetch_assoc($orders_result)) {
                                    // Link to select order, preserving user_id and date filters
                                    $order_link = '?user_id=' . $user_id . '&order_id=' . $order['id'];
                                    if (!empty($date_from)) {
                                        $order_link .= '&date_from=' . urlencode($date_from);
                                    }
                                    if (!empty($date_to)) {
                                        $order_link .= '&date_to=' . urlencode($date_to);
                                    }
                                    echo '<a href="' . htmlspecialchars($order_link) . '" class="order-item">';
                                    echo '<div class="order-info">';
                                    echo '<span class="order-date">' . htmlspecialchars($order['created_at']) . '</span>';
                                    echo '<span class="order-total">$' . number_format($order['total'], 2) . '</span>';
                                    echo '</div>';
                                    echo '</a>';
                                }
                            } else {
                                echo '<p>No orders found for this user' . (!empty($date_from) || !empty($date_to) ? ' within the selected date range' : '') . '.</p>';
                            }
                            mysqli_free_result($orders_result);
                        } else {
                            echo '<p>Select a user to view their orders.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Third Accordion: Drinks -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="drinksHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#drinksCollapse" aria-expanded="false" aria-controls="drinksCollapse">
                        Drinks
                    </button>
                </h2>
                <div id="drinksCollapse" class="accordion-collapse collapse<?php echo isset($_GET['order_id']) ? ' show' : ''; ?>" aria-labelledby="drinksHeading" data-bs-parent="#mainAccordion">
                    <div class="accordion-body">
                        <?php
                        if (isset($_GET['order_id']) && is_numeric($_GET['order_id']) && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
                            $user_id = intval($_GET['user_id']);
                            $order_id = intval($_GET['order_id']);
                            // Fetch drinks with image
                            $drinks_query = "SELECT d.name, d.image_path, od.quantity, od.price, c.name as category
                                            FROM order_drinks od
                                            JOIN drinks d ON od.drink_id = d.id
                                            JOIN categories c ON d.category_id = c.id
                                            WHERE od.order_id = $order_id";
                            $drinks_result = mysqli_query($connect, $drinks_query);

                            if (mysqli_num_rows($drinks_result) > 0) {
                                while ($drink = mysqli_fetch_assoc($drinks_result)) {
                                    echo '<div class="drink-item">';
                                    // Drink details (left side)
                                    echo '<div class="drink-details">';
                                    echo '<p><strong>Name:</strong> <span>' . htmlspecialchars($drink['name']) . '</span></p>';
                                    echo '<p><strong>Category:</strong> <span>' . htmlspecialchars($drink['category']) . '</span></p>';
                                    echo '<p><strong>Quantity:</strong> <span>' . $drink['quantity'] . '</span></p>';
                                    echo '<p><strong>Price:</strong> <span>$' . number_format($drink['price'], 2) . '</span></p>';
                                    echo '</div>';
                                    // Drink image (right side)
                                    echo '<div class="drink-image-container">';
                                    if (!empty($drink['image_path'])) {
                                        echo '<img src="' . htmlspecialchars($drink['image_path']) . '" alt="' . htmlspecialchars($drink['name']) . '" class="drink-image">';
                                    } else {
                                        echo '<p>No image available.</p>';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No drinks found for this order.</p>';
                            }
                            mysqli_free_result($drinks_result);
                        } else {
                            echo '<p>Select an order to view its drinks.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
// Close database connection
mysqli_close($connect);
include 'footer.php';
?>