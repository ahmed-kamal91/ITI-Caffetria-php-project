<?php
$serverName = "127.0.0.1";
$userName = 'root';     
$password = "1234";         
$dbName = 'PHP_Project';

$connect = mysqli_connect($serverName,$userName,$password,$dbName);
echo ($connect)? "": "Failed to Connect";
?>