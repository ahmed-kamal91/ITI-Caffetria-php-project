<?php
session_start();

// Initialize the array if not set
if (!isset($_SESSION['order_items'])) {
    $_SESSION['order_items'] = [
        ['name' => 'Item 1', 'price' => 10, 'quantity' => 1]
    ];
}

// If form is submitted (button clicked)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new item
    $_SESSION['order_items'][] = [
        'name' => 'Item ' . (count($_SESSION['order_items']) + 1),
        'price' => rand(5, 20), // Random price
        'quantity' => rand(1, 3) // Random quantity
    ];
}

// Calculate total using array_reduce
$total = array_reduce($_SESSION['order_items'], fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Only - Shopping Cart</title>
</head>
<body style="background: #f0f0f0; font-family: Arial; padding: 20px;">

    <h1>Shopping Cart</h1>

    <ul>
        <?php foreach ($_SESSION['order_items'] as $item): ?>
            <li><?php echo htmlspecialchars($item['name']); ?> - Price: $<?php echo $item['price']; ?> × Quantity: <?php echo $item['quantity']; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Total using array_reduce: $<?php echo $total; ?></h2>

    <form method="post">
        <button type="submit">Add Random Item</button>
    </form>

</body>
</html>
