<div class="modal fade" id="waiterNote">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- header -->
            <div class="modal-header">
                <h5>Note</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- body -->
            <div class="modal-body p-0">

                <!-- path related to : useMainPage.php -->
                <iframe src="./../note/renderNote.php" name='renderNoteFrame' class='p-0 m-0 w-100' style='height:600px'></iframe>

            </div>

            <!-- footer -->

            <div class="modal-footer">

                <div class="container d-flex flex-column">

                    <!-- view the the calculated total price -->
                    <div class="d-flex align-items-center mb-2">
                        <h3 class="m-0 me-2">Notes:</h3>
                        <input type="text" class="form-control" name='userNote'>
                    </div>
                    <!-- create order button -->
                    <form action="" method='POST' class='w-100'>
                        <button class="btn btn-primary w-100 p-2" name='createOrderBtn'>Crete Order</button>
                    </form>

                </div>


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

