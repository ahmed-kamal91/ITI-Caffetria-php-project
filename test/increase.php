<?php
session_start();

if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
}

$_SESSION['counter']++;

echo "<h1>" . $_SESSION['counter'] . "</h1>";
?>
