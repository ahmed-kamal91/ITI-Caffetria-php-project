<?php
// Start of PHP processing
$success = "";
$error = "";

// Database Connection
// $servername = "localhost";
// $username = "root";
// $password_db = "1234";
// $database = "PHP_Project";

$servername = "127.0.0.1";
$username = 'root';     
$password_db = "aya_A_sultan_1192";         
$database = 'PHP_Project';

$conn = new mysqli($servername, $username, $password_db, $database);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = 'customer'; // Default role

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Handle file upload
        $picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $target_dir = "./../uploads/users/";

            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                if (!mkdir($target_dir, 0755, true)) {
                    $error = "Failed to create upload directory.";
                }
            }

            if (empty($error)) {
                $file_extension = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);

                // Clean the user's name to create a safe file name
                $safe_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', strtolower($name));
                $unique_filename = $safe_name . '_' . uniqid() . '.' . $file_extension;
                $picture = $target_dir . $unique_filename;

                // Validate file type and size
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                $max_size = 2 * 1024 * 1024; // 2MB

                if (!in_array(strtolower($file_extension), $allowed_types)) {
                    $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
                } elseif ($_FILES["profile_picture"]["size"] > $max_size) {
                    $error = "File size must be less than 2MB.";
                } elseif (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $picture)) {
                    $error = "Sorry, there was an error uploading your file.";
                }
            }
        }

        if (empty($error)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare Insert
            $sql = "INSERT INTO users (name, email, password, image_path, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("sssss", $name, $email, $hashed_password, $picture, $role);

            if ($stmt->execute()) {
                $success = "User '$name' has been registered successfully!";
                $_POST = array(); // Clear form
            } else {
                if ($conn->errno == 1062) {
                    $error = "This email is already registered.";
                } else {
                    $error = "Error: " . $stmt->error;
                }
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .form-container {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 600px;
    }
    .form-title {
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
      color: #4A47A3;
    }
    .btn-primary, .btn-secondary {
      width: 48%;
    }
    .form-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    .required-field::after {
      content: " *";
      color: red;
    }
    .alert {
      margin-bottom: 20px;
    }
    /* Add some styling for the image preview */
    #imagePreview {
      width: 100%;
      height: auto;
      max-width: 200px;
      margin-top: 15px;
      display: none;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2 class="form-title">User Registration</h2>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="name" class="form-label required-field">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" 
               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
      </div>
      
      <div class="mb-3">
        <label for="email" class="form-label required-field">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" 
               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
      </div>
      
      <div class="mb-3">
        <label for="password" class="form-label required-field">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      
      <div class="mb-3">
        <label for="confirm_password" class="form-label required-field">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>
      
      <div class="mb-3">
        <label for="profile_picture" class="form-label">Profile Picture</label>
        <input class="form-control" type="file" id="profile_picture" name="profile_picture" accept="image/*">
        <div class="form-text">Max 2MB (JPG, PNG, GIF only)</div>
      </div>
      
      <div class="form-buttons">
        <button type="submit" class="btn btn-primary">Register</button>
        <button type="reset" class="btn btn-secondary">Clear Form</button>
      </div>
    </form>
  </div>

  

<script>
  // JavaScript function to preview the image
  function previewImage(event) {
    const reader = new FileReader();
    const file = event.target.files[0];
    reader.onload = function() {
      const imagePreview = document.getElementById("imagePreview");
      imagePreview.src = reader.result;
      imagePreview.style.display = "block"; // Make the image visible
    };
    if (file) {
      reader.readAsDataURL(file); // Read the selected file as a data URL
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>