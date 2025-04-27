<?php

// check empty
if (!empty($_SESSION['waiterNote'])) {
    // loop on note
    foreach ($_SESSION['waiterNote'] as $drinkId => $drink) {

        // drink variable contain the data with right names for the card
        include "./../note/noteCard.php";
    }
} else { ?>
   <h2 class='d-flex flex-column justify-content-center align-items-center mt-2'>
    <i class='fa-solid fa-mug-saucer fa-5x'></i> 
    <p class='d-block'>No drinks added yet!</p>
    </h2>";
<?php  }?>