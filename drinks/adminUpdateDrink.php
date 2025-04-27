<?php
include_once('../connect.php');

// Check if drink ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: drinks.php?error=Invalid drink ID");
    exit();
}

$drink_id = (int)$_GET['id'];

// Fetch drink details
$query = "SELECT id, name, price, image_path, available FROM drinks WHERE id = $drink_id";
$result = mysqli_query($connect, $query);
$drink = mysqli_fetch_assoc($result);

if (!$drink) {
    header("Location: drinks.php?error=Drink not found");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $price = (float)$_POST['price'];
    $available = (int)$_POST['available']; // Stock as number
    
    // Handle image upload
    $image_path = $drink['image_path']; // Keep existing image by default
    
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/drinks/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Generate unique filename
            $new_filename = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            
            // Try to upload file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = "../uploads/drinks/" . $new_filename;
                
                // Delete old image if it exists
                if (!empty($drink['image_path']) && file_exists("../" . $drink['image_path'])) {
                    unlink("../uploads/drinks/" . $drink['image_path']);
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }
    
    // Update drink in database
    $update_query = "UPDATE drinks SET 
                    name = '$name', 
                    price = $price, 
                    available = $available, 
                    image_path = '$image_path' 
                    WHERE id = $drink_id";
    
    if (mysqli_query($connect, $update_query)) {
        header("Location:viewDrinks.php?success=Drink updated successfully");
        exit();
    } else {
        $error = "Error updating drink: " . mysqli_error($connect);
    }
}

mysqli_close($connect);

// Include the header
include 'header.php';
?>

<div class="container-fluid">  <!-- Changed to container-fluid for full width -->
    <div class="row justify-content-center">
        <div class="col-lg-10">  <!-- Increased from col-lg-8 to col-lg-10 -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Edit Drink</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form action="adminUpdateDrink.php?id=<?php echo $drink_id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">  <!-- Increased margin-bottom -->
                                    <label for="name" class="form-label">Drink Name</label>
                                    <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                        value="<?php echo htmlspecialchars($drink['name']); ?>" required>  <!-- Added form-control-lg -->
                                </div>
                                
                                <div class="mb-4">  <!-- Increased margin-bottom -->
                                    <label for="price" class="form-label">Price (EGP)</label>
                                    <input type="number" step="0.01" class="form-control form-control-lg" id="price" name="price" 
                                        value="<?php echo htmlspecialchars($drink['price']); ?>" required>  <!-- Added form-control-lg -->
                                </div>
                                
                                <div class="mb-4">  <!-- Increased margin-bottom -->
                                    <label for="available" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control form-control-lg" id="available" name="available" 
                                        value="<?php echo htmlspecialchars($drink['available']); ?>" min="0" required>  <!-- Added form-control-lg -->
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">  <!-- Increased margin-bottom -->
                                    <label class="form-label">Current Image</label>
                                    <?php if (!empty($drink['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($drink['image_path']); ?>" 
                                            class="img-thumbnail d-block mb-3" id="currentImage" style="max-height: 250px; width: auto;">  <!-- Increased max-height -->
                                    <?php else: ?>
                                        <div class="text-muted">No image uploaded</div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-4">  <!-- Increased margin-bottom -->
                                    <label for="image" class="form-label">Update Image</label>
                                    <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*">  <!-- Added form-control-lg -->
                                    <div class="form-text">Max size: 2MB. Formats: JPG, PNG, GIF</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-5">  <!-- Increased margin-top -->
                            <a href="viewDrinks.php" class="btn btn-outline-secondary btn-lg">  <!-- Added btn-lg -->
                                <i class="fas fa-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">  <!-- Added btn-lg -->
                                <i class="fas fa-save me-2"></i> Update Drink
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the footer
include 'footer.php';
?>