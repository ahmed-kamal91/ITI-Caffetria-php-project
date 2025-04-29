<?php
include 'header.php';
include_once('../connect.php');

$items_per_page = 12; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Handle search parameter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = '%' . mysqli_real_escape_string($connect, $search) . '%';

// Total items query with search filter
$total_query = "SELECT COUNT(*) as total FROM drinks";
if (!empty($search)) {
    $total_query .= " WHERE name LIKE ?";
}
$stmt = mysqli_prepare($connect, $total_query);
if (!empty($search)) {
    mysqli_stmt_bind_param($stmt, 's', $search_param);
}
mysqli_stmt_execute($stmt);
$total_result = mysqli_stmt_get_result($stmt);
$total_row = mysqli_fetch_assoc($total_result);
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Fetch drinks with search filter
$drinks = [];
$query = "SELECT id, name, price, image_path, available FROM drinks";
if (!empty($search)) {
    $query .= " WHERE name LIKE ?";
}
$query .= " ORDER BY name ASC";
$stmt = mysqli_prepare($connect, $query);
if (!empty($search)) {
    mysqli_stmt_bind_param($stmt, 's', $search_param);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $drinks[] = $row;
    }
    mysqli_free_result($result);
} else {
    $error = "Error fetching drinks: " . mysqli_error($connect);
}

mysqli_close($connect);

$imageBasePath = '';

$start_index = ($page - 1) * $items_per_page;
$end_index = min($start_index + $items_per_page, count($drinks));
$current_page_drinks = array_slice($drinks, $start_index, $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
        }
        .main-content {
            padding: 20px;
            width: 100%;
        }
        .sub-header {
            background-color: #e9ecef;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .status-available {
            color: #28a745;
        }
        .status-unavailable {
            color: #dc3545;
        }
        .drink-card {
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            height: 100%;
        }
        .drink-card:hover {
            transform: translateY(-5px);
        }
        .drink-image-container {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            overflow: hidden;
        }
        .drink-image {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .card-footer {
            background-color: white;
        }
        .availability-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.8rem;
        }
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
        .page-item.active .page-link {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
        }
        .page-link {
            color: #495057;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-md-4 {
            padding-right: 15px;
            padding-left: 15px;
        }
        .search-form {
            max-width: 500px;
        }
        .form-control:focus {
            border-color: #495057;
            box-shadow: 0 0 5px rgba(73, 80, 87, 0.2);
        }
        .btn-outline-secondary {
            transition: all 0.2s;
        }
        .btn-outline-secondary:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="main-content" id="mainContent">
        <div class="container-fluid">
            <div class="mb-4">
                <form method="GET" action="" class="d-flex search-form">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search drinks by name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" aria-label="Search drinks">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <a href="?page=1" class="btn btn-outline-secondary ms-2"><i class="fas fa-times"></i> Clear</a>
                    <?php endif; ?>
                </form>
            </div>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php elseif (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (empty($current_page_drinks)): ?>
                <div class="alert alert-info">No drinks available on this page.</div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($current_page_drinks as $drink): ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card drink-card h-100">
                                <div class="drink-image-container">
                                    <?php
                                    $imagePath = $imageBasePath . $drink['image_path'];
                                    $filePath = __DIR__ . '/' . $imagePath;
                                    if (!empty($drink['image_path']) && file_exists($filePath)): ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($drink['name']); ?>" class="drink-image">
                                    <?php else: ?>
                                        <i class="fas fa-glass-water fa-3x text-muted"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($drink['name']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars(number_format($drink['price'], 2)); ?> EGP</p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge <?php echo $drink['available'] ? 'bg-success' : 'bg-danger'; ?> availability-badge">
                                            <?php echo $drink['available'] ? 'Available' : 'Unavailable'; ?>
                                        </span>
                                        <div class="btn-group">
                                            <a href="adminUpdateDrink.php?id=<?php echo $drink['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                    data-drink-id="<?php echo $drink['id']; ?>" 
                                                    data-drink-name="<?php echo htmlspecialchars($drink['name']); ?>"
                                                    data-page="<?php echo $page; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="First">
                                    <span aria-hidden="true">««</span>
                                </a>
                            </li>
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Previous">
                                    <span aria-hidden="true">«</span>
                                </a>
                            </li>
                            <?php
                            $range = 2; 
                            $start = max(1, $page - $range);
                            $end = min($total_pages, $page + $range);

                            if ($start > 1): ?>
                                <li class="page-item">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $total_pages): ?>
                                <li class="page-item">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Next">
                                    <span aria-hidden="true">»</span>
                                </a>
                            </li>
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $total_pages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Last">
                                    <span aria-hidden="true">»»</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong><span id="drinkName"></span></strong>?</p>
                    <p>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Confirm Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const drinkId = this.getAttribute('data-drink-id');
                const drinkName = this.getAttribute('data-drink-name');
                const page = this.getAttribute('data-page');
                
                // Set the drink name in the modal
                document.getElementById('drinkName').textContent = drinkName;
                
                // Set the delete URL
                const deleteUrl = `adminDeleteDrink.php?id=${drinkId}&page=${page}`;
                document.getElementById('confirmDeleteBtn').setAttribute('href', deleteUrl);
                
                // Show the modal
                deleteModal.show();
            });
        });

        // Clear search input on clear button click
        document.querySelector('.btn-outline-secondary')?.addEventListener('click', function() {
            document.querySelector('input[name="search"]').value = '';
        });

        // Prevent empty search submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const searchInput = document.querySelector('input[name="search"]').value.trim();
            if (searchInput === '') {
                e.preventDefault();
                window.location.href = '?page=1';
            }
        });
    });
    </script>
</body>
</html>
<?php
include 'footer.php';
?>