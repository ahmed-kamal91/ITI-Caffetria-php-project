<div class="modal fade" id="waiterNote">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- header -->
            <div class="modal-header">
                <h5>Note</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- body -->
            <div class="modal-body">

                <!-- path related to : useMainPage.php -->
                <?php include_once "./../note/renderNote.php" ?>

            </div>

            <!-- footer -->
            <div class="modal-footer">
                <form action="" method='POST'>
                    <button class="btn btn-primary" name='createOrderBtn'>Crete Order</button>
                </form>
                <button class="btn btn-danger">resst</button>
            </div>

        </div>
    </div>
</div>

<?php
if(isset($_POST['createOrderBtn'])){
    echo 'hello in order.';
}

?>

<!-- toast is in the last of the userMainPage -->

