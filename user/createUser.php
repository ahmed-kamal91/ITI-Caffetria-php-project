<?php
// Start of PHP processing
$success = "";
$error = "";

// Database Connection
$servername = "localhost"; // or your server name
$username = "root";         // your db username
$password_db = "";          // your db password
$database = "users"; // your database name

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
    $room = $_POST['room'] ?? '';
    $ext = $_POST['ext'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Handle file upload
        $profile_picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $profile_picture = $target_dir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture);
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare Insert
        $sql = "INSERT INTO users (name, email, password, room_no, ext, profile_pic) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssss", $name, $email, $hashed_password, $room, $ext, $profile_picture);
        
        if ($stmt->execute()) {
            $success = "User '$name' has been added successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add User</title>
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
  <h2 class="form-title">Add User</h2>

  <?php if (!empty($success)) : ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php endif; ?>

  <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name" class="form-label">Name *</label>
      <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? '') ?>" required>
    </div>
    
    <div class="mb-3">
      <label for="email" class="form-label">Email address *</label>
      <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>" required>
    </div>
    
    <div class="mb-3">
      <label for="password" class="form-label">Password *</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    
    <div class="mb-3">
      <label for="confirm_password" class="form-label">Confirm Password *</label>
      <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    
    <div class="mb-3">
      <label for="room" class="form-label">Room No.</label>
      <input type="text" class="form-control" id="room" name="room" value="<?php echo htmlspecialchars($_POST['room'] ?? '') ?>">
    </div>
    
    <div class="mb-3">
      <label for="ext" class="form-label">Ext.</label>
      <input type="text" class="form-control" id="ext" name="ext" value="<?php echo htmlspecialchars($_POST['ext'] ?? '') ?>">
    </div>
    
    <div class="mb-3">
      <label for="profile_picture" class="form-label">Profile Picture</label>
      <input class="form-control" type="file" id="profile_picture" name="profile_picture" onchange="previewImage(event)">
    </div>
    
    <!-- Image Preview -->
    <img id="imagePreview" src="" alt="Image Preview" />

    <div class="form-buttons">
      <button type="submit" class="btn btn-primary">Save</button>
      <button type="reset" class="btn btn-secondary">Reset</button>
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
