<?php
function getUserInfo($userId, $connect) {
    $query = "SELECT name, email FROM users WHERE id = $userId";
    $result = mysqli_query($connect, $query);
    return mysqli_fetch_assoc($result); 
}
?>
