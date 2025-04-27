<div class="card rounded-5 p-2 shadow-sm w-fixed">
    <span class="price-badge rounded-5 d-flex justify-content-center align-items-center fw-bold text-dark"><?php echo $drink['price']; ?> $</span>
    <div class="position-relative">
        <img src="<?php echo './../' . $drink['image_path']; ?>" class="card-img-top rounded-5 border border-light bg-light" alt="<?php echo $drink['name']; ?>">

        <?php if ($drink['available']) { ?>
            <span class="stock-badge">
                <i class="fa-solid fa-wine-glass"></i>
                <?php echo $drink['available']; ?>
            </span>
        <?php } else { ?>
            <span class="stock-badge bg-danger text-white">
                <i class="fa-solid fa-wine-glass-empty"></i>
                out
            </span>
        <?php } ?>
    </div>

    <div class="card-body">
        <h5 class="card-title text-center"><?php echo $drink['name']; ?></h5>
    </div>
</div>


<style>
    .w-fixed{
        width: 230px;
    }
</style>