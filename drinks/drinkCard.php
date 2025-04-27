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
    .card-wrapper {
        position: relative;
    }
    .price-badge {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 60px;
        height: 60px;
        background-color: #ffec99;
        z-index: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
    }
    .stock-badge {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background-color: rgb(152, 255, 176);
        color: black;
        padding: 5px 10px;
        font-weight: bold;
        border-radius: 20px;
        z-index: 2;
    }
    .card-img-top {
        position: relative;
    }

    .toast-container {
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1050;
    }

    .toast {
        width: 300px;
        background-color: #dc3545;
        color: white;
        border-radius: 0.5rem;
    }

    .loading-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 5px;
        background-color: rgba(255, 255, 255, 0.6);
        animation: loading 3s linear forwards;
    }

    @keyframes loading {
        from { width: 0; }
        to { width: 100%; }
    }

    /* --- Bonus hover effect --- */
    .card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    </style>