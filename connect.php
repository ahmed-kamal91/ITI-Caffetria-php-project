<?php
// $serverName = 'localhost';
// $userName = 'root';     // enter username
// $password = '1234';         // enter password 
// $dbName = 'PHP_Project';

// $connect = mysqli_connect($serverName,$userName,$password,$dbName);
// echo ($connect)? "": "Failed to Connect";


$serverName = "127.0.0.1";
$userName = 'root';     
$password = "aya_A_sultan_1192";         
$dbName = 'PHP_Project';

$connect = mysqli_connect($serverName,$userName,$password,$dbName);
echo ($connect)? "": "Failed to Connect";
?>

