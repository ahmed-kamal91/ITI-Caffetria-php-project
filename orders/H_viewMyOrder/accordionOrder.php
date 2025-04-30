<!-- ALL OF THIS ALREADY INSIDE A LOOP -->

<?php // get the user name and email using user id
$query = "SELECT name, email FROM users WHERE id =".$orders['user_id'];
$result = mysqli_query($connect, $query);
$user= mysqli_fetch_assoc($result); 
 ?>


<?php // get the drinks data [order_drinks]
$drinkQuery = "SELECT drink_id, quantity FROM order_drinks WHERE order_id =".$orders['id'];
$drinkresult = mysqli_query($connect, $drinkQuery);
?>


<style>
    .accordion-header {
        position: relative;
    }

    .cancel-button {
        position: absolute;
        top: 50%;
        right: 7.5rem; /* Enough to not cover the arrow */
        transform: translateY(-50%);
        z-index: 10; /* Keep it above */
        background-color: white; /* Prevent blend-in on collapse */
    }

    /* Ensure the button does not block toggle */
    .cancel-button button {
        pointer-events: auto;
    }
</style>

<div class="accordion-item">
    <h2 class="accordion-header" id="heading<?php echo $orders['id']; ?>">

        <!-- Accordion toggle button -->
        <button class="accordion-button collapsed pe-5" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse<?php echo $orders['id']; ?>"
                aria-expanded="false"
                aria-controls="collapse<?php echo $orders['id']; ?>">
                
            Order #<?php echo $orders['id']; ?> - <?php echo $orders['created_at']; ?>

            <?php
            $statusClass = '';
            $statusText = '';
            switch ($orders['status']) {
                case 'Processing':
                    $statusClass = 'bg-warning';
                    $statusText = 'Processing';
                    break;
                case 'out for delivery':
                    $statusClass = 'bg-success';
                    $statusText = 'out for delivery';
                    break;
                case 'cancelled':
                    $statusClass = 'bg-danger';
                    $statusText = 'cancelled';
                    break;
                case 'completed':
                    $statusClass = 'bg-danger';
                    $statusText = 'completed';
                    break;
                default:
                    $statusClass = 'bg-secondary';
                    $statusText = 'something went wrong';
                    break;
            }
            ?>
            <span class="badge <?php echo $statusClass; ?> ms-3"><?php echo $statusText; ?></span>
        </button>

        <!-- Cancel Button in header (positioned) -->
        <?php if ($orders['status'] === 'Processing'): ?>
            <form method="POST" action="./H_viewMyOrder/cancelOrder.php"
                  class="cancel-button"
                  onsubmit="return confirm('Are you sure you want to cancel this order?')">
                <input type="hidden" name="order_id" value="<?php echo $orders['id']; ?>">
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    Cancel
                </button>
            </form>
        <?php endif; ?>

    </h2>

    <div id="collapse<?php echo $orders['id']; ?>" class="accordion-collapse collapse"
         aria-labelledby="heading<?php echo $orders['id']; ?>"
         data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <!-- --------------------------------------------------------------- -->
             <!-- user info -->
            <p><strong>Username:</strong> <?php echo $user['name']; ?></p>
            <p><strong>User Email:</strong> <?php echo $user['email']; ?></p>

            <!-- drinks items -->
            <table class="table table-striped table-bordered align-middle mt-4">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Image</th>
                        <th>Drink Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $orderTotal = 0;
                    while ($drink = mysqli_fetch_assoc($drinkresult)) {
                        $eachDrinkQuery = 'SELECT * FROM drinks WHERE id = ' . $drink['drink_id'];
                        $eachDrinkResult = mysqli_query($connect, $eachDrinkQuery);
                        $eachDrink = mysqli_fetch_assoc($eachDrinkResult);

                        $totalPrice = $eachDrink['price'] * $drink['quantity'];
                        $orderTotal += $totalPrice;
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo '../'.$eachDrink['image_path']; ?>"
                                    alt="<?php echo htmlspecialchars($eachDrink['name']); ?>"
                                    class="img-thumbnail"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            </td>
                            <td><?php echo htmlspecialchars($eachDrink['name']); ?></td>
                            <td>$<?php echo number_format($eachDrink['price'], 2); ?></td>
                            <td><?php echo $drink['quantity']; ?></td>
                            <td>$<?php echo number_format($totalPrice, 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>

                <!-- Optional: Total order row -->
                <tfoot>
                    <tr class="fw-bold text-center">
                        <td colspan="4">Total Order Price</td>
                        <td>$<?php echo number_format($orderTotal, 2); ?></td>
                    </tr>
                </tfoot>
            </table>


            <!-- ---------------------------------------------------------------- -->
        </div>
    </div>
</div>
