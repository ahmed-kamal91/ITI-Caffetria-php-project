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

// Check if ID is passed
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch current user data
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepare UPDATE query
    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $name, $email, $user_id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>User updated successfully!</div>";
            // Redirect to the viewAllUsers.php after successful update
            header("Location: viewAllUsers.php");
            exit; // Stop further script execution
        } else {
            $message = "<div class='alert alert-danger'>Error updating user: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>Error preparing statement: " . $conn->error . "</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php echo $message; ?>
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Update User</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                   
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Update User</button>
                        <a href="viewAllUsers.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
