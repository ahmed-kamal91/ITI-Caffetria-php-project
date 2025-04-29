<?php
$serverName = "127.0.0.1";
$userName = 'root';
$dbName = 'PHP_Project';

//--PASSWORDS----------------------------
$password = "1234";                     // Senu
// $password = "aya_A_sultan_1192";     // AYA 
//---------------------------------------      


$connect = mysqli_connect($serverName,$userName,$password,$dbName);
if(!$connect) echo "Failed to Connect";
?>

