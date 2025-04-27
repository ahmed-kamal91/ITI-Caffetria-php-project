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

error_reporting(E_ALL);
ini_set('display_errors', 1);

$sql = "SELECT id, name, email, room_no, ext, profile_pic FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">All Users</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo !empty($row['profile_pic']) ? $row['profile_pic'] : 'default-avatar.jpg'; ?>" class="card-img-top" alt="User Photo" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                    <p class="card-text">
                                        <strong>Email:</strong> <?php echo $row['email']; ?><br>
                                        <strong>Room No:</strong> <?php echo $row['room_no']; ?><br>
                                        <strong>Extension:</strong> <?php echo $row['ext']; ?>
                                    </p>
                                    <a href="updateUser.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="DeleteUser.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-warning col-12">No users found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
