<?php
session_start();
require_once '../connect.php';

// Check if user is admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ./../user/login.php");
//     exit();
// }

// Database connection
if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // update or cancel
    if ($_POST['action'] === 'update_status') {
        $orderId = $_POST['order_id'];
        $newStatus = $_POST['new_status'];
        
        // Validate status
        $validStatuses = ['Processing', 'out for delivery', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            die("Invalid status");
        }
        
        // 
        $updateSql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($connect, $updateSql);
        mysqli_stmt_bind_param($stmt, "si", $newStatus, $orderId);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash_message'] = "Order #$orderId status updated to $newStatus";
        } else {
            $_SESSION['flash_error'] = "Error updating order status: " . mysqli_error($connect);
        }
        
        mysqli_stmt_close($stmt);
    }
    elseif ($_POST['action'] === 'cancel') {
        $orderId = $_POST['order_id'];
        
        $updateSql = "UPDATE orders SET status = 'cancelled' WHERE id = ? AND status = 'Processing'";
        $stmt = mysqli_prepare($connect, $updateSql);
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash_message'] = "Order #$orderId has been cancelled";
        } else {
            $_SESSION['flash_error'] = "Error cancelling order: " . mysqli_error($connect);
        }
        
        mysqli_stmt_close($stmt);
    }
    
    header("Location: admin_orders.php");
    exit();
}

// Pagination and filtering
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Filter parameters
$dateFrom = isset($_GET['date_from']) && $_GET['date_from'] ? $_GET['date_from'] : null;
$dateTo = isset($_GET['date_to']) && $_GET['date_to'] ? $_GET['date_to'] : null;
$statusFilter = isset($_GET['status']) && $_GET['status'] ? $_GET['status'] : null;

// Base SQL for orders
$baseSql = "SELECT o.id, o.user_id, o.total, o.status, o.created_at, u.name AS customer_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id";
$baseParams = [];
$baseTypes = "";

// Count SQL
$countSql = "SELECT COUNT(*) AS total_count FROM orders o";
$countParams = [];
$countTypes = "";

// Apply filters
$whereClauses = [];
if ($dateFrom) {
    $whereClauses[] = "DATE(o.created_at) >= ?";
    $baseParams[] = $dateFrom;
    $countParams[] = $dateFrom;
    $baseTypes .= "s";
    $countTypes .= "s";
}
if ($dateTo) {
    $dateToFormatted = date('Y-m-d H:i:s', strtotime($dateTo . ' 23:59:59'));
    $whereClauses[] = "o.created_at <= ?";
    $baseParams[] = $dateToFormatted;
    $countParams[] = $dateToFormatted;
    $baseTypes .= "s";
    $countTypes .= "s";
}
if ($statusFilter) {
    $whereClauses[] = "o.status = ?";
    $baseParams[] = $statusFilter;
    $countParams[] = $statusFilter;
    $baseTypes .= "s";
    $countTypes .= "s";
}

if (!empty($whereClauses)) {
    $where = " WHERE " . implode(" AND ", $whereClauses);
    $baseSql .= $where;
    $countSql .= $where;
}

// Get total count
$countStmt = mysqli_prepare($connect, $countSql);
$totalOrders = 0;
if ($countStmt) {
    if (!empty($countParams)) {
        mysqli_stmt_bind_param($countStmt, $countTypes, ...$countParams);
    }
    mysqli_stmt_execute($countStmt);
    $countResult = mysqli_stmt_get_result($countStmt);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalOrders = $countRow['total_count'];
    mysqli_stmt_close($countStmt);
}

// Calculate pagination
$totalPages = ceil($totalOrders / $itemsPerPage);

if ($totalOrders == 0) {
    $currentPage = 1;
    $offset = 0;
    $totalPages = 1;
} elseif ($currentPage < 1) {
    $currentPage = 1;
    $offset = ($currentPage - 1) * $itemsPerPage;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
    $offset = ($currentPage - 1) * $itemsPerPage;
}

// Get orders
$orders = [];
$sql = $baseSql . " ORDER BY o.created_at DESC LIMIT ?, ?";
$params = array_merge($baseParams, [$offset, $itemsPerPage]);
$types = $baseTypes . "ii";

$stmt = mysqli_prepare($connect, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        // Get order items
        $orderItemsSql = "SELECT od.quantity, d.name AS drink_name, od.price AS drink_price, d.image_path 
                          FROM order_drinks od 
                          JOIN drinks d ON od.drink_id = d.id 
                          WHERE od.order_id = ?";
        $itemStmt = mysqli_prepare($connect, $orderItemsSql);
        $items = [];
        if ($itemStmt) {
            mysqli_stmt_bind_param($itemStmt, "i", $row['id']);
            mysqli_stmt_execute($itemStmt);
            $itemResult = mysqli_stmt_get_result($itemStmt);
            while ($itemRow = mysqli_fetch_assoc($itemResult)) {
                $items[] = $itemRow;
            }
            mysqli_stmt_close($itemStmt);
        }
        $row['items'] = $items;
        $orders[] = $row;
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($connect);

// Helper function for building pagination URLs
function buildPaginationUrl($page, $dateFrom, $dateTo, $statusFilter) {
    $queryParams = ['page' => $page];
    if ($dateFrom) $queryParams['date_from'] = $dateFrom;
    if ($dateTo) $queryParams['date_to'] = $dateTo;
    if ($statusFilter) $queryParams['status'] = $statusFilter;
    return '?' . http_build_query($queryParams);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Orders Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        h1 {
            color: #0d6efd;
            text-align: center;
            margin-bottom: 20px;
        }
        .filter-form {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .accordion-button {
            font-weight: 500;
            transition: background-color 0.3s ease;
            align-items: center;
            padding: 15px 20px;
        }
        .accordion-button:not(.collapsed) {
            background-color: #cfe2ff;
            color: #0a58ca;
        }
        .accordion-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .order-summary {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding-right: 40px;
            font-size: 0.95rem;
            align-items: center;
        }
        .order-summary > div {
            flex: 1 1 0;
            padding: 0 10px;
            text-align: left;
        }
        .order-summary > div:first-child {
            flex: 0 0 180px;
            padding-left: 0;
            font-size: 0.9rem;
            color: #6c757d;
        }
        .order-summary > div:nth-child(2) {
            flex: 0 0 120px;
            text-align: center;
        }
        .order-summary > div:nth-child(3) {
            flex: 0 0 100px;
            text-align: right;
            font-size: 1.1rem;
        }
        .order-summary > div:last-child {
            flex: 0 0 80px;
            text-align: right;
            padding-right: 0;
        }
        .status-badge {
            min-width: 100px;
            text-align: center;
            font-size: 0.85rem;
            padding: 0.4em 0.6em;
        }
        .accordion-body {
            background-color: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #dee2e6;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px dashed #e0e0e0;
        }
        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
            border: 1px solid #dee2e6;
        }
        .item-details {
            flex-grow: 1;
            font-size: 1rem;
        }
        .item-details strong {
            display: block;
            margin-bottom: 3px;
            color: #343a40;
        }
        .item-price {
            min-width: 90px;
            text-align: right;
            font-weight: bold;
            color: #198754;
            font-size: 1rem;
        }
        .status-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .flash-message {
            animation: fadeInOut 3s ease-in-out forwards;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-clipboard-list me-2"></i>Orders Management</h1>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success flash-message">
                <?= $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger flash-message">
                <?= $_SESSION['flash_error']; ?>
                <?php unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <form method="GET" action="admin_orders.php" class="filter-form mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From:</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?= htmlspecialchars($dateFrom ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To:</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?= htmlspecialchars($dateTo ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status:</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="Processing" <?= ($statusFilter === 'Processing') ? 'selected' : ''; ?>>Processing</option>
                        <option value="out for delivery" <?= ($statusFilter === 'out for delivery') ? 'selected' : ''; ?>>Out for Delivery</option>
                        <option value="completed" <?= ($statusFilter === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?= ($statusFilter === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                </div>
            </div>
        </form>

        <div class="accordion" id="ordersAccordion">
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <?php
                        $statusBadgeClass = 'bg-secondary';
                        if ($order['status'] === 'Processing') {
                            $statusBadgeClass = 'bg-warning text-dark';
                        } elseif ($order['status'] === 'out for delivery') {
                            $statusBadgeClass = 'bg-info text-dark';
                        } elseif ($order['status'] === 'completed') {
                            $statusBadgeClass = 'bg-success';
                        } elseif ($order['status'] === 'cancelled') {
                            $statusBadgeClass = 'bg-danger';
                        }
                        $orderIdHtml = "order-" . htmlspecialchars($order['id']);
                        $collapseId = "collapse-" . $orderIdHtml;
                        $headerId = "header-" . $orderIdHtml;
                    ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="<?= $headerId; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId; ?>" aria-expanded="false" aria-controls="<?= $collapseId; ?>">
                                <div class="order-summary">
                                    <div>
                                        Order #<?= htmlspecialchars($order['id']); ?>
                                        <div class="text-muted small">
                                            <i class="far fa-user me-1"></i><?= htmlspecialchars($order['customer_name']); ?>
                                            <i class="far fa-calendar-alt ms-2 me-1"></i>
                                            <?= htmlspecialchars(date('Y-m-d H:i', strtotime($order['created_at']))); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge rounded-pill status-badge <?= $statusBadgeClass; ?>">
                                            <?= htmlspecialchars(ucwords($order['status'])); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <strong class="text-success">
                                            <?= htmlspecialchars(number_format($order['total'], 2)); ?> EGP
                                        </strong>
                                    </div>
                                    <div class="status-form">
                                        <form method="post" action="admin_orders.php" class="d-inline">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                            <select name="new_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="Processing" <?= ($order['status'] === 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                                <option value="out for delivery" <?= ($order['status'] === 'out for delivery') ? 'selected' : ''; ?>>Out for Delivery</option>
                                                <option value="completed" <?= ($order['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                <option value="cancelled" <?= ($order['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </form>
                                        <?php if ($order['status'] === 'Processing'): ?>
                                            <form method="post" action="admin_orders.php" class="d-inline">
                                                <input type="hidden" name="action" value="cancel">
                                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="<?= $collapseId; ?>" class="accordion-collapse collapse" aria-labelledby="<?= $headerId; ?>" data-bs-parent="#ordersAccordion">
                            <div class="accordion-body">
                                <?php if (!empty($order['items'])): ?>
                                    <h5 class="mb-3">Items:</h5>
                                    <ul class="list-unstyled">
                                        <?php foreach ($order['items'] as $item): ?>
                                            <li class="order-item">
                                                <img src="<?= htmlspecialchars($item['image_path'] ?? 'placeholder.png'); ?>"
                                                     alt="<?= htmlspecialchars($item['drink_name']); ?>"
                                                     onerror="this.onerror=null; this.src='placeholder.png';">
                                                <div class="item-details">
                                                    <strong><?= htmlspecialchars($item['drink_name']); ?></strong>
                                                    <span><?= htmlspecialchars($item['quantity']); ?> x <?= htmlspecialchars(number_format($item['drink_price'], 2)); ?> EGP</span>
                                                </div>
                                                <div class="item-price">
                                                    <?= htmlspecialchars(number_format($item['quantity'] * $item['drink_price'], 2)); ?> EGP
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted text-center">No items found for this order.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>No orders found matching the selected criteria.
                </div>
            <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Orders pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= buildPaginationUrl($currentPage - 1, $dateFrom, $dateTo, $statusFilter); ?>">
                            <i class="fas fa-angle-left"></i> Previous
                        </a>
                    </li>
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);

                    if ($startPage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . buildPaginationUrl(1, $dateFrom, $dateTo, $statusFilter) . '">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= ($i === $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?= buildPaginationUrl($i, $dateFrom, $dateTo, $statusFilter); ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor;

                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . buildPaginationUrl($totalPages, $dateFrom, $dateTo, $statusFilter) . '">' . $totalPages . '</a></li>';
                    }
                    ?>
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= buildPaginationUrl($currentPage + 1, $dateFrom, $dateTo, $statusFilter); ?>">
                            Next <i class="fas fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss flash messages after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                }, 3000);
            });
        });
    </script>
</body>
</html>