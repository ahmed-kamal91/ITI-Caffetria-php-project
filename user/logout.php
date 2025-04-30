<?php
session_start();

// Clear all session variables
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_role']);

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeteria - Logging Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .logout-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 380px;
            text-align: center;
        }
        .logout-title {
            font-weight: bold;
            margin-bottom: 20px;
            color: #0056b3;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
            margin-bottom: 20px;
        }
        .logout-message {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="logout-card">
        <h2 class="logout-title">Logging Out</h2>
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Logging out...</span>
        </div>
        <p class="logout-message">You are being logged out. Please wait...</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>