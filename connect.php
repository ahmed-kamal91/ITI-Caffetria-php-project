<?php
$serverName = 'localhost';
$userName = 'root';     // enter username
$password = '1234';         // enter password 
$dbName = 'PHP_Project';

$connect = mysqli_connect($serverName,$userName,$password,$dbName);
echo ($connect)? "": "Failed to Connect";
?>