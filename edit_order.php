<?php
include 'db_connect.php';

$order_id = $_GET['id'] ?? '';
if (!$order_id) die('Invalid Order ID');

$message = '';

// Fetch order details
$stmt = oci_parse($conn, "SELECT * FROM shop_order WHERE order_id = :id");
oci_bind_by_name($stmt, ':id', $order_id);
oci_execute($stmt);
$order = oci_fetch_assoc($stmt);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $order_date = $_POST['order_date'];
    $total_amount = $_POST['total_amount'];

    $updStmt = oci_parse($conn, "UPDATE shop_order SET customer_id = :cust, order_date = TO_DATE(:odate,'YYYY-MM-DD'), total_amount = :tam WHERE order_id = :id");
    oci_bind_by_name($updStmt, ':cust', $customer_id);
    oci_bind_by_name($updStmt, ':odate', $order_date);
    oci_bind_by_name($updStmt, ':tam', $total_amount);
    oci_bind_by_name($updStmt, ':id', $order_id);

    if (oci_execute($updStmt)) {
        $message = "Order updated successfully";
        header("Location: view_orders.php");
        exit;
    } else {
        $message = "Order update failed";
    }
}

// Fetch customers for dropdown
$custStmt = oci_parse($conn, "SELECT customer_id, cname FROM Customer ORDER BY cname");
oci_execute($custStmt);
?>

<!DOCTYPE html>
<html>
<head><title>Edit Order</title></head>
<body>
<div class="card">
<a href="view_orders.php" class="btn">Back to Orders</a>
<h2>Edit Order</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post">
    <label>Customer</label>
    <select name="customer_id" required>
        <?php while ($cust = oci_fetch_assoc($custStmt)) { ?>
            <option value="<?= $cust['CUSTOMER_ID'] ?>" <?= ($order['CUSTOMER_ID'] == $cust['CUSTOMER_ID']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cust['CNAME']) ?>
            </option>
        <?php } ?>
    </select>

    <label>Order Date</label>
    <input type="date" name="order_date" value="<?= date('Y-m-d', strtotime($order['ORDER_DATE'])) ?>" required>

    <label>Total Amount</label>
    <input type="number" name="total_amount" value="<?= $order['TOTAL_AMOUNT'] ?>" step="0.01" required>

    <button type="submit" class="btn">Update Order</button>
</form>
</div>
</body>
</html>
