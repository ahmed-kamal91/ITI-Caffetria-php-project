<?php
// $servername = "localhost";
// $username = "root";
// $password = "1234";
// $dbname = "PHP_Project"; // change this

$servername = "127.0.0.1";
$username = 'root';     
$password = "aya_A_sultan_1192";         
$dbname = 'PHP_Project';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user data to display before deletion
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        } else {
            $message = "<div class='alert alert-warning'>User not found.</div>";
        }

        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>Error fetching user data: " . $conn->error . "</div>";
    }
}

// If user confirms deletion, proceed with the deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>User deleted successfully!</div>";
            header("Location: viewAllUsers.php"); // Redirect after deletion
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Error deleting user: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>Failed to prepare the statement!</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php echo $message; ?>
        <?php if (isset($user)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Delete User</h4>
            </div>
            <div class="card-body">
                <p class="text-center">Are you sure you want to delete the following user?</p>
                <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                
                <form action="" method="POST" class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-danger me-2">Yes, Delete</button>
                    <a href="viewAllUsers.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
