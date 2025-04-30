<?php
// Always start with PHP (no blank lines or HTML before this)
$serverName = "127.0.0.1";
$userName = 'root';
$password = 'aya_A_sultan_1192';  // Or your actual password
$dbName = 'PHP_Project';

$connect = mysqli_connect($serverName, $userName, $password, $dbName);

// Optional for debugging (but not recommended in production):
// echo ($connect) ? "" : "Failed to Connect";
?>
