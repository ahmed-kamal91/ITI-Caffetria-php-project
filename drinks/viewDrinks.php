<?php
include_once('../connect.php');

$items_per_page = 12; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$total_query = "SELECT COUNT(*) as total FROM drinks";
$total_result = mysqli_query($connect, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

$drinks = [];
$query = "SELECT id, name, price, image_path, available FROM drinks ORDER BY name ASC";
$result = mysqli_query($connect, $query);
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
            display: flex;
            min-height: 100vh;
            transition: all 0.3s;
        }
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 20px 0;
            transition: all 0.3s;
            position: relative;
        }
        .sidebar.collapsed {
            width: 60px;
            overflow: hidden;
        }
        .sidebar.collapsed .nav-link {
            padding: 10px;
            text-align: center;
        }
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        .sidebar .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 0;
            white-space: nowrap;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }
        .sidebar .nav-link.active {
            background-color: #e9ecef;
            font-weight: 600;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            transition: all 0.3s;
        }
        .toggle-btn {
            position: absolute;
            right: -15px;
            top: 20px;
            width: 30px;
            height: 30px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1;
            transition: all 0.3s;
        }
        .toggle-btn:hover {
            background-color: #e9ecef;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s;
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
    </style>
</head>
<body>
    
    <div class="sidebar" id="sidebar">
        <div class="toggle-btn" id="toggleBtn">
            <i class="fas fa-chevron-left"></i>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-home"></i><span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fas fa-box"></i><span>Drinks</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-users"></i><span>Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-shopping-cart"></i><span>Manual Order</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-file-invoice"></i><span>Checks</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-cog"></i><span>Admin</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content" id="mainContent">
        <div class="container-fluid">
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
                                <a class="page-link" href="?page=1" aria-label="First">
                                    <span aria-hidden="true">««</span>
                                </a>
                            </li>
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
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
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $total_pages): ?>
                                <li class="page-item">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">»</span>
                                </a>
                            </li>
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $total_pages; ?>" aria-label="Last">
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

        // Toggle sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const mainContent = document.getElementById('mainContent');
        
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            // Change the icon based on state
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
            
            // Store the state in localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
        
        // Check for saved state on page load
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            sidebar.classList.add('collapsed');
            const icon = toggleBtn.querySelector('i');
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
        }
    });
    </script>
</body>
</html>