<?php
include_once('../connect.php');

$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($connect, $sql_categories);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $productName = trim($_POST['name']);
    $price = $_POST['price'];
    $category_id = $_POST['cat'];
    $stock = $_POST['stock'] ?? 0;
    $file = $_FILES['file'] ?? null;

    if (empty($productName) || empty($price) || empty($category_id)) {
        echo "Please fill in all required fields (Drink, Price, Category).";
    } elseif (!is_numeric($price) || $price <= 0) {
        echo "Price must be a positive number.";
    } elseif (!is_numeric($stock) || $stock < 0) {
        echo "Stock must be a non-negative number.";
    } else {
        $uploadPath = '';
        
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imageExt = ["png", "jpg", "jpeg", "gif"];
            $originalFileName = $file["name"];
            $tmpPath = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $imageExt)) {
                echo "Please upload a valid image file (png, jpg, jpeg, gif).";
            } elseif ($fileSize > 5000000) {
                echo "File size is too large (maximum 5MB).";
            } else {
                $newImageName = time() . "_" . $originalFileName;
                $uploadPath = "../Uploads/drinks/" . $newImageName;

                if (!move_uploaded_file($tmpPath, $uploadPath)) {
                    echo "Failed to upload product image.";
                    $uploadPath = '';
                }
            }
        }

        $productName = mysqli_real_escape_string($connect, $productName);
        $price = mysqli_real_escape_string($connect, $price);
        $uploadPath = mysqli_real_escape_string($connect, $uploadPath);
        $category_id = mysqli_real_escape_string($connect, $category_id);
        $stock = mysqli_real_escape_string($connect, $stock);

        $sql = "INSERT INTO drinks (name, price, image_path, category_id, available) VALUES ('$productName', '$price', '$uploadPath', '$category_id', '$stock')";
        mysqli_query($connect, $sql);
    }
}
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Drink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #dc3545;
      --secondary-color: #6c757d;
      --light-color: #f8f9fa;
      --dark-color: #343a40;
    }
    
    body { 
      background-color: #f5f5f5;
    }
    
    .form-container {
      background-color: white;
      padding: 0; /* Remove padding from container */
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(43, 65, 27, 0.08);
      margin: 2rem auto;
      max-width: 800px;
      overflow: hidden; /* Ensures the header corners are rounded */
    }
    
    .form-header {
      background-color: var(--dark-color);
      color: white;
      padding: 0.5rem;
      margin-bottom: 0; /* Remove margin bottom */
    }
    
    .form-title {
      font-weight: 600;
      font-size: 1.8rem;
      margin: 0; /* Remove margin */
      color: white; /* Ensure text is white */
    }
    
    .form-body {
      padding: 2.5rem;
    }
    
    .form-label {
      font-weight: 500;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      border: 1px solid #ced4da;
      transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    
    .input-group-text {
      background-color: #e9ecef;
      color: var(--dark-color);
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      border-radius: 8px;
    }
    
    .btn-primary:hover {
      background-color:rgb(51, 35, 200);
      border-color:rgb(189, 33, 33);
    }
    
    .btn-secondary {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      border-radius: 8px;
    }
    
    .btn-outline-secondary {
      border-radius: 8px;
      padding: 0.5rem 1.5rem;
    }
    
    .form-group {
      margin-bottom: 1.75rem;
    }
    
    .action-buttons {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 2.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid #eee;
    }
    
    .image-preview {
      max-width: 200px;
      max-height: 200px;
      display: block;
      margin-top: 1rem;
      border-radius: 8px;
      border: 1px dashed #ddd;
      padding: 0.5rem;
    }
    
    .add-category-btn {
      white-space: nowrap;
      margin-left: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="main-content">
    <div class="container">
      <div class="form-container">
        <div class="form-header">
          <h2 class="form-title">Add New Drink</h2>
        </div>
        
        <div class="form-body">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="productName" class="form-label">Drink Name</label>
                  <input type="text" class="form-control" id="productName" placeholder="Enter drink name" name="name" required>
                </div>
                
                <div class="form-group">
                  <label for="price" class="form-label">Price (EGP)</label>
                  <div class="input-group">
                    <input type="number" step="0.01" min="0" class="form-control" id="price" placeholder="0.00" name="price" required>
                    <span class="input-group-text">EGP</span>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="category" class="form-label">Category</label>
                  <div class="d-flex">
                    <select class="form-select" id="category" name="cat" required>
                      <option value="">Select category</option>
                      <?php while ($category = mysqli_fetch_assoc($result_categories)): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>">
                          <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                      <?php endwhile; ?>
                    </select>
                    <a href="#" class="btn btn-outline-secondary add-category-btn">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="stock" class="form-label">Stock Quantity</label>
                  <div class="input-group">
                    <input type="number" class="form-control" id="stock" placeholder="0" name="stock" min="0" required>
                    <span class="input-group-text">units</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="productImage" class="form-label">Product Image</label>
              <input type="file" class="form-control" id="productImage" name="file" accept="image/*">
              <small class="text-muted">Recommended size: 500x500px (Max 5MB)</small>
              <div id="imagePreviewContainer" class="mt-2" style="display:none;">
                <img id="imagePreview" class="image-preview" src="#" alt="Preview">
              </div>
            </div>
            
            <div class="action-buttons">
              <div>
                <button type="submit" class="btn btn-primary me-2">
                  <i class="fas fa-save me-1"></i> Save Drink
                </button>
                <button type="reset" class="btn btn-secondary">
                  <i class="fas fa-undo me-1"></i> Reset
                </button>
              </div>
              <a href="drinks.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Drinks
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Image preview functionality
    document.getElementById('productImage').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          const preview = document.getElementById('imagePreview');
          preview.src = event.target.result;
          document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
<?php include 'footer.php'; ?>