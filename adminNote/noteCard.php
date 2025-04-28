<div class="container w-100 p-0 bg-light mb-4 rounded-5">
    <div class="row">

        <!-- drink card -->
        <div class="col">
            <?php include "./../drinks/drinkCard.php" ?>
        </div>

<!-- amount-price [sub] -->
<div class="col position-relative d-flex flex-column justify-content-center align-items-center pe-5 text-center mb-2">

    <!-- remove the direct -->
    <form action='./../adminNote/removeFromNote.php' method='POST' class="position-absolute top-0 end-0 mt-3 me-1">
        <input type="hidden" name="drinkId" value="<?php echo $drinkId; ?>">
        <button type="submit" class="btn-close"></button>
    </form>

    <h3>
        <!-- iframe counter -->
        <iframe 
            name="counterFrame_<?php echo $drinkId; ?>" 
            src="./../adminNote/stockHandle/outputAmount.php?drinkId=<?php echo $drinkId; ?>" 
            style="border:1px solid #000; width:50px; height:50px; overflow:hidden;"
            scrolling='no'>
        </iframe>
        x 
        <?php echo $_SESSION['adminWaiterNote'][$drinkId]['price'] ?>$
    </h3>

    <div class="input-group d-flex justify-content-center align-items-center mb-5">

        <!-- INCREASE button: iframe will be changed to the parent -->
        <form 
            action="./../adminNote/stockHandle/increaseStock.php"
            method="post"
            target="renderNoteFrame" 
            class="w-50">
            <input type="hidden" name="drinkId" value="<?php echo $drinkId; ?>">
            <input class='btn bg-light-success w-100' type="submit" value='+' name="increaseDrinkBtn">
        </form>

        <!-- DECREASE button -->
        <form 
            action="./../note/stockHandle/decreaseStock.php" 
            method="post" 
            target="renderNoteFrame" 
            class="w-50">
            <input type="hidden" name="drinkId" value="<?php echo $drinkId; ?>">
            <input class='btn bg-light-danger w-100' type="submit" value='-' name="decreaseDrinkBtn">
        </form>

    </div>

    <h4 class='p-2 rounded-3'>total: 112.9$</h4>
</div>


    </div>
</div>










<style>
 .bg-light-success{
    background-color: rgb(152, 255, 176);
 }

 .bg-light-success:hover{
    background-color: rgb(97, 252, 133);
 }

 .bg-light-danger{
    background-color: rgb(255, 133, 145);
 }
 .bg-light-danger:hover{
    background-color: rgb(250, 171, 179);
 }

 .H-100{height: 200px;}
</style>