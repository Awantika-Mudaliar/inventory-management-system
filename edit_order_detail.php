<?php
include 'db_connect.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid order detail id");
}

// Fetch existing details with order_id to redirect properly later
$stmt = oci_parse($conn, "SELECT order_id, equipment_id, quantity, price FROM order_details WHERE order_detail_id = :id");
oci_bind_by_name($stmt, ":id", $id);
oci_execute($stmt);
$detail = oci_fetch_assoc($stmt);

if (!$detail) {
    die("Order detail not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $update = oci_parse($conn,
        "UPDATE order_details SET quantity = :quantity, price = :price WHERE order_detail_id = :id");
    oci_bind_by_name($update, ":quantity", $quantity);
    oci_bind_by_name($update, ":price", $price);
    oci_bind_by_name($update, ":id", $id);
    oci_execute($update);

    // Redirect to order detail list for the specific order
    header('Location: view_order_details.php?order_id=' . $detail['ORDER_ID']);
    exit;
}
?>
<form method="POST">
    Quantity: <input type="number" name="quantity" value="<?= htmlspecialchars($detail['QUANTITY']) ?>" required><br>
    Price: <input type="text" name="price" value="<?= htmlspecialchars($detail['PRICE']) ?>" required><br>
    <button type="submit">Update</button>
</form>
