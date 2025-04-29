<?php
$serverName = "127.0.0.1";
$userName = 'root';     
$password = "aya_A_sultan_1192";         
$dbName = 'PHP_Project';

$connect = mysqli_connect($serverName,$userName,$password,$dbName);
echo ($connect)? "": "Failed to Connect";
?>