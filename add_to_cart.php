<?php
session_start();
include 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipment_id = $_POST['equipment_id'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 0);

    if ($equipment_id && $quantity > 0) {
        $_SESSION['cart'][$equipment_id] = $quantity;
        $message = "Added to cart!";
    } else {
        $message = "Select equipment and quantity!";
    }
}

$equipments = [];
$res = oci_parse($conn, "SELECT EQUIPMENT_ID, EQUIPMENT_NAME FROM EQUIPMENT ORDER BY EQUIPMENT_NAME");
oci_execute($res);
while ($row = oci_fetch_assoc($res)) {
    $equipments[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add to Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
<h2>Add Equipment to Cart</h2>
<?php if ($message) echo "<p>$message</p>"; ?>

<form method="post">
    <label>Equipment:</label>
    <select name="equipment_id" required>
        <option value="" disabled selected>Select equipment</option>
        <?php foreach ($equipments as $equip): ?>
        <option value="<?= $equip['EQUIPMENT_ID'] ?>"><?= htmlspecialchars($equip['EQUIPMENT_NAME']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Quantity:</label>
    <input type="number" name="quantity" min="1" placeholder="Enter quantity" required>

    <button type="submit" class="btn">Add to Cart</button>
</form>

<p><a href="view_cart.php" class="btn">View Cart and Checkout</a></p>
<p><a href="index.php" class="btn">Back to Dashboard</a></p>
</div>
</body>
</html>
