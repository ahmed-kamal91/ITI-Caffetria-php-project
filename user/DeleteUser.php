<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users"; // change this

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare DELETE query
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>User deleted successfully!</div>";
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
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Delete User</h4>
            </div>
            <div class="card-body">
                <p class="text-center">Are you sure you want to delete this user?</p>
                <form action="" method="GET" class="d-flex justify-content-center">
                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                    <button type="submit" class="btn btn-danger me-2">Yes, Delete</button>
                    <a href="view_users.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
